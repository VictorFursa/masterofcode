<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TagFixture extends ActiveFixture
{
    public $tableName = 'tag';

    public $dataFile = '@common/data/tag.php';
}