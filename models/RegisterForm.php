<?php

namespace app\models;

use yii\base\Model;
use app\models\Save;

/**
 * RegisterForm is the model behind the register form under paint rectangle.
 */
class RegisterForm extends Model
{
    public $useremail;
    public $password;
    public $password_repeat;
    public $username;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // useremail, passwor, password_repeat and username are all required
            [['useremail', 'password', 'password_repeat', 'username'], 'required'],
            // useremail should be a valid email address
            ['useremail', 'email'],
            // verify existing useremail
            ['useremail', 'app\components\StatusValidator'],
            // password_repeat === password 
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

   /**
     * Finds user by [[useremail]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByuseremail($this->useremail);
        }

        return $this->_user;
    }

    /**
     * Labels for registration form
     *
     * @return array of field labels
     */
    public function attributeLabels()
    {
        return [
            'useremail' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор',
            'username'  => 'Имя',
        ];
    }

    /**
     * Registration new User
     *
     * @return boolean
     */
    public function register()
    {
        if ($this->validate())
        {
            if($this->getUser() === null)
            {
                $user = User::addUser($this->useremail, $this->username, $this->password);
                //new user login
                \Yii::$app->user->login($user, 0);

                //save picture and rename file picture after registration and authentication
                $save = new Save();
                $save->set_author_picture();

                return true;
            }
        } 
        else
        {
            return false;
        }
    }
}
