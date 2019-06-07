<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m190529_085613_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'heading' => $this->string()->notNull(),
            'priority' => $this->string(9)->notNull(),
            'status' => $this->boolean()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'admin_id' => $this->integer()
        ]);

        $this->createIndex(
            'idx-ticket-user_id',
          'ticket',
          'user_id'
        );

        $this->addForeignKey(
          'fk-ticket-user_id',
            'ticket',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-ticket-admin_id',
            'ticket',
            'admin_id'
        );

        $this->addForeignKey(
            'fk-ticket-admin_id',
            'ticket',
            'admin_id',
            'users',
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
            'fk-ticket-user_id',
            'ticket'
        );

        $this->dropIndex(
            'idx-ticket-user_id',
            'ticket'
        );

        $this->dropForeignKey(
            'fk-ticket-admin_id',
            'ticket'
        );

        $this->dropIndex(
            'idx-ticket-admin_id',
            'ticket'
        );

        $this->dropTable('{{%ticket}}');
    }
}
