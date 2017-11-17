<?php

namespace console\controllers;

use yii\console\Controller;
use api\modules\v1\models\Book;
use api\modules\v1\models\Category;
use api\modules\v1\models\Tag;

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
}
