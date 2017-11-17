<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $tableName = 'category';

    public $dataFile = '@common/data/category.php';

    public $modelClass = '@api\modules\v1\models\Category';
}
