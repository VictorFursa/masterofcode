<?php

namespace common\components;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ReplaceBookQueue
{
    const QUEUE_NAME = 'replace_book_queue';

    /** @var AMQPChannel */
    private $channel;
    /** @var AMQPStreamConnection  */
    private $connection;

    public function __construct()
    {
        $config = \Yii::$app->params['amqp'];

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
        $this->channel->queue_declare(self::QUEUE_NAME, false, false, false, false);

        $amqpMessage = new AMQPMessage(json_encode($data), [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $this->channel->basic_publish($amqpMessage, '', self::QUEUE_NAME);
        echo " [x] Successful sending\n";

        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param callable $callback
     */
    public function consume(callable $callback)
    {
        $this->channel->queue_declare(self::QUEUE_NAME, false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $this->channel->basic_consume(self::QUEUE_NAME,
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
