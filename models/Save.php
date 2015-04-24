<?php

namespace app\models;

//use yii\base\Model;
use yii\db\Query;
use yii\web\DbSession;
use yii\base\ErrorException;
//use yii\filters\AccessControl;

/**
 * Save class for AJAX save pictures.
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

	        $this->makeThumb($file_name);
		}
		elseif($size == 0)
		{
			throw new ErrorException("File can't saved in ./pictures/".$file_name);
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
}