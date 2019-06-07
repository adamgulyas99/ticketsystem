<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $heading
 * @property string $priority
 * @property bool $status
 * @property int $user_id
 * @property int $admin_id
 *
 * @property Comment[] $comments
 * @property User $user
 * @property User $admin
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['heading'], 'required', 'message' => 'You forgot to give heading to your ticket!'],
            [['priority', 'status', 'user_id'], 'required'],
            [['status'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['user_id'], 'integer'],
            [['priority'], 'in', 'range' => ['normal', 'urgent', 'critical']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['admin_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'heading' => 'Heading',
            'priority' => 'Priority',
            'status' => 'Availability',
            'user_id' => 'User ID',
            'admin_id' => 'Admin ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsDesc()
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id'])->orderBy(['create_time' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastcomment()
    {
        return $this->hasOne(Comment::class, ['ticket_id' => 'id'])->orderBy(['create_time' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }

    /**
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status ? 'Opened' : 'Closed';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescription()
    {
        return $this->hasOne(Comment::class, ['ticket_id' => 'id'])->orderBy(['create_time' => SORT_ASC])->limit(1);
    }

    public function getNumberofcomments() {
        return $this->hasOne(Comment::class, ['ticket_id' => 'id'])->count('*');
    }
}
