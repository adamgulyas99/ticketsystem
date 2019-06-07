<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $is_admin
 * @property string $last_login_time
 * @property string $reg_time
 *
 * @property Comment[] $comments
 * @property Ticket[] $tickets
 * @property Ticket[] $tickets0
 * @property string auth_key
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    public $auth_key;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_admin', 'last_login_time', 'reg_time', 'email', 'password', 'name'], 'required'],
            [['name', 'email'], 'string', 'max' => 150],
            //[['email'], 'unique', 'email'],
            [['password'], 'string', 'max' => 255],
            [['is_admin'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['is_admin'], 'default', 'value' => false],
            [['reg_time', 'last_login_time'], 'datetime', 'format' => 'php:Y-m-d H:i:s']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'is_admin' => 'Admin',
            'last_login_time' => 'Last login time',
            'reg_time' => 'Registration date',
        ];
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets0()
    {
        return $this->hasMany(Ticket::className(), ['admin_id' => 'id']);
    }

    /**
     * Validates password
     *
     * @param string password to validate
     * @return bool if password is valid for current user
     *
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * Returns email
     *
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $last_login_time
     */
    public function setLastLoginTime($last_login_time)
    {
        $this->last_login_time = $last_login_time;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
