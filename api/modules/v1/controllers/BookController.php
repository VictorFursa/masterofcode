<?php

namespace api\modules\v1\controllers;

use api\controllers\RestController;
use api\modules\v1\models\Book;
use Yii;
use yii\web\NotFoundHttpException;

class BookController extends RestController
{

    /**
     * @param string $name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSearchByName(string $name)
    {
        return Book::find()
            ->where(['like', 'name', $name])
            ->select('name')
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionIndex()
    {
        return Book::find()->all();
    }

    /**
     * @param string $name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSearchByCategoryName(string $name)
    {
        return Book::find()
            ->joinWith('category')
            ->where(['like', 'category.name', $name])
            ->select('book.name')
            ->all();   
    }
    
    /**
     * @param integer $id
     * @return Book
     */
    public function actionView(int $id)
    {
        return $this->findModel($id);
    }
    
    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
