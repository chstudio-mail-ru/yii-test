<?php

namespace app\models;

use Yii;
use yii\base\Model;

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
            // useremail and password are both required
            [['useremail', 'password', 'password_repeat', 'username'], 'required'],
            // useremail should be a valid email address
            ['useremail', 'email'],
            // useremail is validated by validateuseremail()
            ['useremail', 'validateuseremail'],
            // password_repeat === password 
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Validates the useremail.
     * This method serves as the inline validation for existing user.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateuseremail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if ($user) {
                $this->addError($attribute, 'Пользователь с логином '.$this->useremail.' уже существует.');
            }
        }
    }

    /**
     * Finds user by [[useremail]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByusername($this->useremail);
        }

        return $this->_user;
        /*$user = User::findByuseremail($this->useremail);
        
        return $user;*/
    }


    public function attributeLabels()
    {
        return [
            'useremail' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор',
            'username'  => 'Имя',
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            if($this->getUser() !== null) {
               $this->useremail = null;
               $this->password = null;
               $this->password_repeat = null;
               $this->username = null;
               return Yii::$app->user->login($this->getUser(), 3600*24*30);
            }
        } else {
            return false;
        }
    }
}
