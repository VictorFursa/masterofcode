<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m171116_192217_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'category_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('FK_book_category_id', 'book', 'category_id', 'category', 'id');
        $this->createIndex('I_book_category', 'book', ['category_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('book');
    }
}
