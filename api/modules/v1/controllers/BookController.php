<?php

namespace api\modules\v1\controllers;

use api\controllers\RestController;
use common\models\Book;

class BookController extends RestController
{
    /**
     * @param string $bookName
     * @return Book[]
     */
    public function actionSearchByName(string $bookName)
    {
        return Book::find()
            ->where(['like', 'name', $bookName])
            ->all();
    }

    /**
     * @return Book[]
     */
    public function actionIndex()
    {
        return Book::find()->all();
    }

    /**
     * @param string $tagName
     * @return Book[]
     */
    public function actionSearchByTag(string $tagName)
    {
        return Book::find()
            ->joinWith('tags')
            ->where(['tag.name' => explode(',', $tagName)])
            ->all();
    }

    /**
     * @param string $categoryName
     * @param string $tagName
     * @return Book[]
     */
    public function actionSearchByCategoryAndTag(string $categoryName, string $tagName)
    {
        return Book::find()
            ->joinWith(['category', 'tags'])
            ->where(['like', 'tag.name', $tagName])
            ->andWhere(['like', 'category.name', $categoryName])
            ->all();
    }

    /**
     * @param string $categoryName
     * @return Book[]
     */
    public function actionSearchByCategoryName(string $categoryName)
    {
        return Book::find()
            ->joinWith('category')
            ->where(['like', 'category.name', $categoryName])
            ->all();
    }
}
