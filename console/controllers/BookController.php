<?php

namespace console\controllers;

use common\models\Book;
use common\models\Category;
use common\models\Tag;
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
     * @param string $oldCategoryName
     * @param string $newCategoryName
     */
    public function actionReplace(string $oldCategoryName, string $newCategoryName)
    {
        \Yii::$app->replaceBookQueue->publisher(['oldCategoryName' => $oldCategoryName, 'newCategoryName' => $newCategoryName]);
    }

    public function actionConsumeReplace()
    {
        \Yii::$app->replaceBookQueue->consume(function (AMQPMessage $message) {
            $data = json_decode($message->body);

            if (!is_object($data) || !isset($data->oldCategoryName) || !isset($data->newCategoryName)) {
                echo '[*] something wrong look at line 60 in console/controllers/BookController ', "\n";
                
                return false;
            }
            /** @var  $books Book[] */
            $books = Book::find()
                ->joinWith('category')
                ->where(['category.name' => $data->oldCategoryName])
                ->all();
            $category = Category::findOne(['name' => $data->newCategoryName]);

            if (!$category instanceof Category) {
                echo '[*] error! no category found with this name - ' . $data->newCategoryName, "\n";

                return false;
            }

            if (empty($books)) {
                echo '[*] error! books not found where category name - ' . $data->oldCategoryName, "\n";

                return false;
            }
            
            foreach ($books as $book) {
                if ($book->category_id === $category->id) {
                    echo '[*] error! This book '  . $book->name . ' already has this category ' . $category->name . ' installed', "\n";

                    return false;
                } else {
                    $book->category_id = $category->id;
                    $book->update();
                    echo '[*] success! book - ' . $book->name . ' now category ' . $category->name , "\n";
                }
            }

            return true;
        });
    }
}
