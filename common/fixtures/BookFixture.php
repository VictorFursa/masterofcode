<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class BookFixture extends ActiveFixture
{
    public $tableName = 'book';

    public $dataFile = '@common/data/book.php';

    public $depends = ['common\fixtures\CategoryFixture'];
}