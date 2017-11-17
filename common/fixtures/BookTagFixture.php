<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class BookTagFixture extends ActiveFixture
{
    public $tableName = 'book_tag';

    public $dataFile = '@common/data/book_tag.php';

    public $depends = ['common\fixtures\BookFixture', 'common\fixtures\TagFixture'];
}
