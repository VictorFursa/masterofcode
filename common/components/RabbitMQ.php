<?php

namespace common\components;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    /** @var AMQPChannel */
    private $channel;
    /** @var AMQPStreamConnection  */
    private $connection;
    /** @var  $uniqueQueueName string */
    private $uniqueQueueName;
    
    public function __construct(array $config, string $uniqueQueueName)
    {
        $this->uniqueQueueName = $uniqueQueueName;
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['pass']
        );

        $this->channel = $this->connection->channel();
    }

    /**
     * @param array $data
     */
    public function publisher(array $data)
    {
        $this->channel->queue_declare($this->uniqueQueueName, false, false, false, false);

        $amqpMessage = new AMQPMessage(json_encode($data), [
            'content_type' => 'application/json',
            "timestamp" => (new \DateTime())->getTimestamp(),
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $this->channel->basic_publish($amqpMessage, '', $this->uniqueQueueName);
      
        echo " [x] Successful sending\n";

        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param callable $callback
     */
    public function consume(callable $callback)
    {
        $this->channel->queue_declare($this->uniqueQueueName, false, false, false, false);
        $this->channel->basic_consume($this->uniqueQueueName,
            '',
            false,
            true,
            false,
            false,
            function (AMQPMessage $message) use ($callback) {
            $callback($message);
        });

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
