<?php

namespace console\controllers;

use api\modules\v1\models\Book;
use api\modules\v1\models\Category;
use api\modules\v1\models\Tag;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;

class BookController extends Controller
{
    /**
     * @param string $categoryName
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionRemoveByCategoryName(string $categoryName)
    {
        $category = Category::findOne(['name' => $categoryName]);

        if ($category instanceof Category) {
            Book::deleteAll(['category_id' => $category->id]);
            $category->delete();
            $this->stdout("Removed all books and category. Where category name:'$categoryName' " . PHP_EOL);
        } else {
            $this->stdout('nothing delete' . PHP_EOL);
        }
    }

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
            $this->stdout('nothing delete' . PHP_EOL);
        }
    }

    /**
     * @param int $bookId
     * @param int $categoryId
     */
    public function actionReplace(int $bookId, int $categoryId)
    {
        \Yii::$app->replaceBookQueue->publisher(['bookId' => $bookId, 'categoryId' => $categoryId]);
    }
    
    public function actionConsumeReplace()
    {
        \Yii::$app->replaceBookQueue->consume(function (AMQPMessage $message) {
            $data = json_decode($message->body);

            if (!is_object($data) || !isset($data->bookId) || !isset($data->categoryId)) {
                echo '[*] something wrong look at line 60 in console/controllers/BookController ' . $data->categoryId, "\n";
                
                return false;
            }

            $book = Book::findOne($data->bookId);
            $category = Category::findOne($data->categoryId);

            if (!$category instanceof Category) {
                echo '[*] error! no category found with this id - ' . $data->categoryId, "\n";

                return false;
            }

            if (!$book instanceof Book) {
                echo '[*] error! no book found with this id - ' . $data->bookId, "\n";

                return false;
            }

            if ($book->category_id === $category->id) {
                echo '[*] error! This book '  . $book->name . ' already has this category ' . $category->name . ' installed', "\n";

                return false;
            }
            
            $book->category_id = $category->id;
            $book->update();
            echo '[*] success! book - ' . $book->name . ' now category ' . $category->name , "\n";

            return true;
        });
    }
}
