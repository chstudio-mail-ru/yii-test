<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form under paint rectangle.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $name;
    //public $rememberMe = true;

    private $_userExists = false;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'password_repeat'], 'required'],
            // username should be a valid email address
            ['username', 'email'],
            // username is validated by validateUsername()
            ['username', 'validateUsername'],
            // password_repeat === password 
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Validates the username.
     * This method serves as the inline validation for existing user.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if ($user) {
                $this->_userExists = true;
                $this->_user = false;
                $this->addError($attribute, 'Пользователь с логином '.$this->username.' уже существует.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }


    public function attributeLabels()
    {
        return [
            'username' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор',
            'name'  => 'Имя',
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            if($this->getUser() !== null)
            {
               return Yii::$app->user->login($this->getUser(), 0);
            }
        } else {
            return false;
        }
    }
}
