<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * This is the model class for table "users".
 *
 * @property string $name
 * @property string $email
 * @property string $password
 */
class RegistrationForm extends Model
{

    public $name;
    public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'registration-form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['email'], 'trim'],
            [['email'], 'required', 'message' => 'Please give an email!'],
            [['email'], 'email'],
            //[['email'], 'message' => 'Email address has already taken!'],
            [['password'], 'required', 'message' => 'You forgot to give a password!'],
            //min 8 char, at least 1 letter and 1 number
            [['password'], 'string', 'min' => 8, 'message' => 'Your password must be 8 long at least!']
        ];
    }

    /**
     * Registrate user up.
     *
     * @return bool whether the creating new account was successful
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        date_default_timezone_set("Europe/Budapest");
        $date = date('Y-m-d H:i:s');
        $hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($hash);
        $user->is_admin = false;
        $user->reg_time = $date;
        $user->last_login_time = $date;
        $user->generateAuthKey();
        return $user->save();
    }
}
