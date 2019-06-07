<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m190529_092834_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'create_time' => $this->timestamp()->notNull(),
            'image' => $this->text(),
            'content' => $this->text()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'ticket_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id'
        );

        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comment-ticket_id',
            'comment',
            'ticket_id'
        );

        $this->addForeignKey(
            'fk-comment-ticket_id',
            'comment',
            'ticket_id',
            'ticket',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-comment-user_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-user_id',
            'comment'
        );

        $this->dropForeignKey(
            'fk-comment-ticket_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-ticket_id',
            'comment'
        );

        $this->dropTable('{{%comment}}');
    }
}
