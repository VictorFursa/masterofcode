<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book_tag`.
 */
class m171116_192244_create_book_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book_tag', [
            'book_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'PRIMARY KEY(book_id, tag_id)',
        ]);

        $this->createIndex('I_book_tag_book_id', 'book_tag', 'book_id');
        $this->createIndex('I_book_tag_tag_id', 'book_tag', 'tag_id');

        $this->addForeignKey('FK_book_tag_book_id', 'book_tag', 'book_id', 'book', 'id', 'CASCADE');
        $this->addForeignKey('FK_book_tag_tag_id', 'book_tag', 'tag_id', 'tag', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('FK_book_tag_book_id', 'book_tag');
        $this->dropForeignKey('FK_book_tag_tag_id', 'book_tag');

        $this->dropTable('book_tag');
    }
}
