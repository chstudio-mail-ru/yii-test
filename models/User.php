<?php

namespace app\models;

use yii\db\Query;

/**
 * User class for athenticated users.
 */
class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $useremail;
    public $username;
    public $password;
    public $accessToken;
    public $authKey;
    public $registerTime;
    
    //private static $users = [];

    /*private static $users = [
        '100' => [
            'id' => '100',
            'useremail' => 'da@accons.ru',
            'username' => 'Вася',
            'password' => '1',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'useremail' => 'da@pkredit.ru',
            'username' => 'Роберт',
            'password' => '1',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];*/

    /**
     * @inheritdoc
     * MySQL query SELECT * FROM user_list WHERE id = $id
     * @param  int      $id
     * @return static|null
     */
    public static function findIdentity($id)
    {
        
        $query = new Query();

        $row = $query->select(['*'])
                     ->from('user_list')
                     ->where(['id' => $id])
                     ->one();

        
        return isset($row['id'])? new static($row) : null;
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     * MySQL query SELECT * FROM user_list WHERE accessToken = $token
     * @param  mixed      $token
     * @param  mixed      $type
     * @return static|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        $query = new Query();

        $row = $query->select(['*'])
                     ->from('user_list')
                     ->where(['accessToken' => $token])
                     ->one();

        return isset($row['id'])? new static($row) : null;

        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }*/

        //return null;
    }

    /**
     * Finds user by useremail
     * @param  string      $useremail
     * @return static|null
     */
    public static function findByUseremail($useremail)
    {

        $query = new Query();

        $row = $query->select(['*'])
                     ->from('user_list')
                     ->where(['useremail' => $useremail])
                     ->one();

        return isset($row['id'])? new static($row) : null;

        /*foreach (self::$users as $user) {
            if (strcasecmp($user['useremail'], $useremail) === 0) {
                return new static($user);
            }
        }*/

        //return null;
    }

    /**
     * @inheritdoc
     * take user $id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     * take user authKey
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     * take user accessToken
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @inheritdoc
     * validate authKey
     * @param  mixed $authKey authKey to validate
     * @return boolean if authKey provided is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * add new user to DB
     * MySQL query INSERT (useremail, username, password) VALUES ($useremail, $username, $password) INTO user_list
     * @param  string $useremail
     * @param  string $username
     * @param  string $password
     * @return static|null
     */
    public static function addUser($useremail, $username, $password)
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->insert('user_list', [
                                        'useremail' => $useremail,
                                        'username' => $username,
                                        'password' => md5($password),
                                    ]);
        $command->execute();
        $id = $connection->getLastInsertID();

        $authKey = \Yii::$app->getSecurity()->generateRandomString();
        $accessToken = \Yii::$app->getSecurity()->generateRandomString();

        $command = $connection->createCommand()
                                    ->update('user_list', [
                                        'authKey' => $authKey,
                                        'accessToken' => $accessToken,
                                    ], 'id='.$id);
        $command->execute();

        $arr =  [
                    'id' => $id,
                    'useremail' => $useremail,
                    'username' => $username,
                    'password' => md5($password),
                    'authKey' => $authKey,
                    'accessToken' => $accessToken,
                    'registerTime' => time(),  //date("Y-m-d H:i:s", time()),
                ];
        return new static($arr);
    }

    /**
     * Validates password
     * @param  string  $password password to validate (encoded by md5)
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
