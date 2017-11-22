<?php

namespace console\controllers;

use common\components\RabbitMQ;
use common\models\Book;
use common\models\Category;
use common\models\Tag;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;

class BookController extends Controller
{
    
    /**
     * @param string $tagName
     */
    public function actionRemoveByTag(string $tagName)
    {
        $tag = Tag::findOne(['name' => $tagName]);

        if ($tag instanceof Tag) {
            $tag->unlinkAll('books', true);
            $this->stdout('done!' . PHP_EOL);
        } else {
            $this->stdout('nothing to delete' . PHP_EOL);
        }
    }

    /**
     * Replace category names for books
     * using RabbitMQ
     * @param string $oldCategoryName
     * @param string $newCategoryName
     */
    public function actionReplace(string $oldCategoryName, string $newCategoryName)
    {
        \Yii::$container
            ->get(RabbitMQ::class, [\Yii::$app->params['amqp'], 'replace_book_queue'])
            ->publisher(['oldCategoryName' => $oldCategoryName, 'newCategoryName' => $newCategoryName]);
    }
    
    /**
     * Prints the queue
     */
    public function actionConsumeReplace()
    {
        \Yii::$container
            ->get(RabbitMQ::class, [\Yii::$app->params['amqp'], 'replace_book_queue'])
            ->consume(function (AMQPMessage $message) {
            $data = json_decode($message->body);
            if (!is_object($data) || !isset($data->oldCategoryName) || !isset($data->newCategoryName)) {
                echo 'timestamp ' . $message->get('timestamp') . '[*] something wrong look at line 60 in console/controllers/BookController ', "\n";
                
                return false;
            }
                
            /** @var  $books Book[] */
            $books = Book::find()
                ->joinWith('category')
                ->where(['category.name' => $data->oldCategoryName])
                ->all();
            $category = Category::findOne(['name' => $data->newCategoryName]);

            if (!$category instanceof Category) {
                echo 'timestamp ' . $message->get('timestamp') . '[*] error! no category found with this name - ' . $data->newCategoryName, "\n";

                return false;
            }

            if (empty($books)) {
                echo 'timestamp ' . $message->get('timestamp') . '[*] error! books not found where category name - ' . $data->oldCategoryName, "\n";

                return false;
            }
            
            foreach ($books as $book) {
                if ($book->category_id === $category->id) {
                    echo 'timestamp ' . $message->get('timestamp') . '[*] error! This book '  . $book->name . ' already has this category ' . $category->name . ' installed', "\n";

                    return false;
                } else {
                    $book->category_id = $category->id;
                    $book->update();
                    echo 'timestamp ' . $message->get('timestamp') . '[*] success! book - ' . $book->name . ' now category ' . $category->name , "\n";
                }
            }

            return true;
        });
    }
}
