<?php

namespace console\controllers;

use api\modules\v1\models\Category;
use api\modules\v1\models\Book;
use yii\console\Controller;

class BookController extends Controller
{
    public function actionRemoveByCategoryName(string $name)
    {
        $category = Category::findOne(['name' => $name]);
        if($category instanceof Category) {
            Book::deleteAll(['category_id' => $category->id]);
            $category->delete();
            $this->stdout("Removed all books and category. Where category name:'$name' " . PHP_EOL);
        } else {
            $this->stdout('nothing delete' . PHP_EOL);
        }
    }
}
