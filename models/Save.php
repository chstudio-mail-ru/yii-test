<?php

namespace app\models;

//use yii\base\Model;
use yii\db\Query;
use yii\web\DbSession;
use yii\base\ErrorException;
//use yii\filters\AccessControl;

/**
 * AJAX save pictures.
 */
class Save
{
	public static $session = null;
	private $_user = null;

    public function __construct()
    {
        if(self::$session === null)
        {
        	self::$session = new DbSession();	
        	self::$session->open();
        }
    }

	public function save()
	{
		if(isset(\Yii::$app->request->post()['user_id']) && intval(\Yii::$app->request->post()['user_id']) > 0)
		{
			//verify authorization of user
			if(\Yii::$app->request->post()['user_id'] === \Yii::$app->user->identity->id)
			{
				$this->_user = User::findIdentity(\Yii::$app->request->post()['user_id']);
				$user_id = \Yii::$app->user->identity->id;
				
				//file_name for authorized users
				$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
			}
			else
			{
				//file_name for guest users
				$file_name = "g-".self::$session->getId().".png";
			}
		}
		else
		{
			//file_name for guest users
			$file_name = "g-".self::$session->getId().".png";
		}

		$pic = explode(',',\Yii::$app->request->post()['data']);
		$pic = str_replace(' ','+',$pic[1]);
		$pic = base64_decode($pic);

		$size = file_put_contents("./pictures/".$file_name, $pic);

		self::$session->set('last_file_name', $file_name);

		//if save file for authorized user, add record tu DB and delete temporary file (g-session_id().png)
		if(isset($user_id) && $size > 0)
		{
	        $connection = \Yii::$app->db;
	        $command = $connection->createCommand()
	                                    ->insert('image_list', [
	                                        'userId' => $user_id,
	                                        'imageName' => $file_name,
	                                    ]);
	        $command->execute();
		}
	}

	public function set_author_picture()
	{
		if($this->_user === null)
		{
			$this->_user = User::findIdentity(\Yii::$app->user->identity->id);
		}
		
        $user_id = \Yii::$app->user->identity->id;
        $file_name = self::$session->get('last_file_name');

        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->insert('image_list', [
                                        'userId' => $user_id,
                                        'imageName' => $file_name,
                                    ]);
        $command->execute();
		//rename file from $file_tmp to $file_name
		if(file_exists("./pictures/".self::$session->get('last_file_name')))
		{
			$user_id = \Yii::$app->user->identity->id;
			
			//file_name for authorized users
			$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
			if(!rename("./pictures/".self::$session->get('last_file_name'), "./pictures/".$file_name))
			{
				throw new ErrorException("Unable to rename file ".self::$session->get('last_file_name')." to ".$file_name);
			}
		}
		else
		{
			throw new ErrorException("File not exists ./pictures/".self::$session->get('last_file_name'));
		}
	}	
}