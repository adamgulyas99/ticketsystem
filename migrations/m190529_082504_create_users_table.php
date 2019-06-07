<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190529_082504_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'email' => $this->string(50)->unique()->notNull(),
            'password' => $this->string(255)->notNull(),
            'is_admin' => $this->boolean()->notNull(),
            'last_login_time' => $this->timestamp()->notNull(),
            'reg_time' => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ]);

        $this->createIndex(
            'idx-users-is_admin',
            'users',
            'is_admin'
        );

        $this->createIndex(
            'idx-users-email',
            'users',
            'email'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-users-is_admin',
            'users'
        );

        $this->dropIndex(
            'idx-users-email',
            'users'
        );

        $this->dropTable('{{%users}}');
    }
}
