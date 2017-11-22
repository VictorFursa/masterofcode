<?php

namespace console\controllers;

use common\models\Book;
use common\models\Category;
use yii\console\Controller;

class CategoryController extends Controller
{
    /**
     * @param string $categoryName
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionRemoveByName(string $categoryName)
    {
        $category = Category::findOne(['name' => $categoryName]);

        if ($category instanceof Category) {
            Book::deleteAll(['category_id' => $category->id]);
            $category->delete();
            $this->stdout("Removed all books and category. Where category name:'$categoryName' " . PHP_EOL);
        } else {
            $this->stdout('nothing to delete' . PHP_EOL);
        }
    }
}