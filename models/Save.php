<?php

namespace app\models;

//use yii\base\Model;
use yii\db\Query;
use yii\web\DbSession;
use yii\base\ErrorException;
//use yii\filters\AccessControl;

/**
 * Save class for AJAX save/delete pictures.
 */
class Save
{
	//static const for save current session
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
				
				//if $_POST['picture_id'] < 1
				if(isset(\Yii::$app->request->post()['picture_id']) && \Yii::$app->request->post()['picture_id'] < 1)
				{
					//generate unique file_name
					do
					{
						//file_name for authorized users
						$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
					}
					while(file_exists("./pictures/".$file_name));
				}
				else
				{
			        $picture_id = \Yii::$app->request->post()['picture_id'];
			        $query = new Query();

			        //get picture info from DB
			        $row = $query->select('*')
			                     ->from('image_list')
			                     ->where(['id' => $picture_id])
			                     ->one();

			        //file_name edited picture
			        $file_name = $row['imageName'];
				}	
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

		if(isset(\Yii::$app->request->post()['data']))
		{
			$pic = explode(',',\Yii::$app->request->post()['data']);
			$pic = str_replace(' ','+',$pic[1]);
			$pic = base64_decode($pic);

			//if save file for authorized user, if not set $picture_id, add record to DB
			if(isset($user_id) && !isset($picture_id))
			{
				$size = file_put_contents("./pictures/".$file_name, $pic);

				if($size > 0)
				{
					self::$session->set('last_file_name', $file_name);


			        $connection = \Yii::$app->db;
			        $command = $connection->createCommand()
			                                    ->insert('image_list', [
			                                        'userId' => $user_id,
			                                        'imageName' => $file_name,
			                                    ]);
			        $command->execute();

			        $this->makeThumb($file_name);
				}
			} //else if change file_name for update cache, save file and update record in DB
			elseif(isset($user_id) && isset($picture_id))
			{

				//save old file name
				$old_file_namame = $file_name;

				//generate new unique file_name
				do
				{
					//file_name for authorized users
					$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
				}
				while(file_exists("./pictures/".$file_name));
				
				$size = file_put_contents("./pictures/".$file_name, $pic);
				if($size > 0)
				{
					$this->makeThumb($file_name);

			        $connection = \Yii::$app->db;
			        $command = $connection->createCommand()
			                                    ->update('image_list',
			                                    	['imageName' => $file_name],
			                                    	['id' => $picture_id]
			                                    );
			        $command->execute();

					//deleting old files
					@unlink("./pictures/".$old_file_namame);
					@unlink("./pictures/tn-".$old_file_namame);
				}
				elseif($size == 0)
				{
					throw new ErrorException("File can't saved in ./pictures/".$file_name);
				}
			}
			elseif($size == 0)
			{
				throw new ErrorException("File can't saved in ./pictures/".$file_name);
			}
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

		//rename file from $file_tmp to $file_name
		if(file_exists("./pictures/".self::$session->get('last_file_name')))
		{
			$user_id = \Yii::$app->user->identity->id;
			
			//generate unique file_name for authorized users
			do
			{
				//file_name for authorized users
				$file_name = $user_id."-".\Yii::$app->getSecurity()->generateRandomString().".png";
			}
			while(file_exists("./pictures/".$file_name));

			if(!rename("./pictures/".self::$session->get('last_file_name'), "./pictures/".$file_name))
			{
				throw new ErrorException("Unable to rename file ".self::$session->get('last_file_name')." to ".$file_name);
			}
			else
			{
				$this->makeThumb($file_name);
			}

	        //save to DB
	        $connection = \Yii::$app->db;
	        $command = $connection->createCommand()
	                                    ->insert('image_list', [
	                                        'userId' => $user_id,
	                                        'imageName' => $file_name,
	                                    ]);
	        $command->execute();
		}
		else
		{
			throw new ErrorException("File not exists ./pictures/".self::$session->get('last_file_name'));
		}
	}

	private function makeThumb($file_name, $w=160, $h=128)	
	{
		if(file_exists("./pictures/".$file_name))
		{
			$size = getimagesize("./pictures/".$file_name);

			$tW = $size[0];    //original width
			$tH = $size[1];   //original height

			if($w == 0 || $h == 0)
			{
			    throw new ErrorException("Can't make thumbnail (width or height == 0)");
			}

			if($tW / $tH > $w / $h) {
			    // specified height is too big for the specified width
			    $h = $w * $tH / $tW;
			}
			elseif($tW / $tH < $w / $h) {
			    // specified width is too big for the specified height
			    $w = $h * $tW / $tH;
			}

			$tn = imagecreatetruecolor($w, $h);  //this will create it with black background
			imagefill($tn, 0, 0, imagecolorallocate($tn, 255, 255, 255));    //fill it with white;

			//copy the original image:
			$image = imagecreatefrompng("./pictures/".$file_name);
			//create a scaled-down image
			imagecopyresampled($tn, $image, 0, 0, 0, 0, $w, $h, $tW, $tH);

			if(!imagepng($tn, "./pictures/tn-".$file_name))
			{
				throw new ErrorException("Unable to save thumbnail file ./pictures/tn-".$file_name);
			}
		}
		else
		{
			throw new ErrorException("File not exists ./pictures/".$file_name);
		}
	}


	public function delete()
	{
		if(isset(\Yii::$app->request->post()['picture_id']) && intval(\Yii::$app->request->post()['picture_id']) > 0)
		{
			//verify authorization of user
			if(\Yii::$app->request->post()['user_id'] === \Yii::$app->user->identity->id)
			{
				$this->_user = User::findIdentity(\Yii::$app->request->post()['user_id']);
				$user_id = \Yii::$app->user->identity->id;
				$picture_id = \Yii::$app->request->post()['picture_id'];

		        $query = new Query();

		        //get picture info from DB
		        $row = $query->select('*')
		                     ->from('image_list')
		                     ->where(['id' => $picture_id])
		                     ->one();
		                     
		        //delete from server
		        @unlink("./pictures/".$row['imageName']);
		        @unlink("./pictures/tn-".$row['imageName']);

		        //delete from DB
		        $connection = \Yii::$app->db;
		        $command = $connection->createCommand()
		                            ->delete('image_list', ['id' => $picture_id]);
		        $command->execute();

			}
		}
	}	
}