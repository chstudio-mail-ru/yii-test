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
            [['username', 'password'], 'required'],
            // username should be a valid email address
            ['username', 'email'],
            // password is validated by validatePassword()
            ['password', 'validateUsername'],
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
