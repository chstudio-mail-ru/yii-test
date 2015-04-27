<?php

namespace app\models;

use yii\base\Model;
use app\models\Save;
use app\models\User;
use yii\db\Query;

/**
 * EditPicture is the model for edit picture in paint rectangle and register form under paint rectangle.
 */
class EditPicture extends Model
{
    public $useremail;
    public $password;
    public $password_repeat;
    public $username;
    public $user_id;
    public $picture_id;
    public $picture;

    private $_user = false;

    public function __construct()
    {
        if(!\Yii::$app->user->isGuest)
        {
            $this->user_id = \Yii::$app->user->id;
            $this->picture_id = isset(\Yii::$app->request->get()['id'])? \Yii::$app->request->get ()['id'] : 0;

            //verification privilegies to edit this picture
            if($this->picture_id > 0)
            {
                $this->picture = Picture::find()->select('*')->where(['id' => $this->picture_id])->one();
                $user = User::findIdentity($this->user_id);
                $this->username = $user->username;
            }
        }
    }

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
                $save->save();
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
