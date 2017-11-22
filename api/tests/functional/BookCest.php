<?php

namespace api\tests\functional;

use api\tests\FunctionalTester;
use common\fixtures\BookFixture;

/**
 * Class BookCest
 */
class BookCest
{

    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => BookFixture::className(),
                'dataFile' => codecept_data_dir() . 'book.php'
            ]
        ];
    }
    
    /**
     * @param FunctionalTester $I
     */
    public function createBook(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function updateBook(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewBook(FunctionalTester $I)
    {
    }
}
