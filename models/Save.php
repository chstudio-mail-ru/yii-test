<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Save extends Model 
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [];
    }

	public function save()
	{
		$pic = explode(',',$_POST['data']);
		$pic = str_replace(' ','+',$pic[1]);
		$pic = base64_decode($pic);

		file_put_contents("1.png", $pic);
	}
}