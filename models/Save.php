<?php

namespace app\models;

use yii\base\Model;
use yii\db\Query;
//use yii\filters\AccessControl;

/**
 * AJAX save pictures.
 */
class Save extends Model 
{
	private $_user = null;

	public function save()
	{
		if(isset($_POST['user_id']) && intval($_POST['user_id']) > 0)
		{
			//verify authorization of user
			if($_POST['user_id'] === \Yii::$app->user->identity->id)
			{
				$this->_user = User::findIdentity($_POST['user_id']);
				$user_id = \Yii::$app->user->identity->id;
				
				//file_name for authorized users
				$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
			}
			else
			{
				//file_name for guest users
				$file_name = "g-".session_id().".png";
			}
		}
		else
		{
			//file_name for guest users
			$file_name = "g-".session_id().".png";
		}

		$pic = explode(',',$_POST['data']);
		$pic = str_replace(' ','+',$pic[1]);
		$pic = base64_decode($pic);

		$size = file_put_contents("./pictures/".$file_name, $pic);

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
			
			if(file_exists("./pictures/g-".session_id().".png"))
			{
				unlink("./pictures/g-".session_id().".png");
			}
		}
	}
}