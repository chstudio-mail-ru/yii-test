<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Picture is the ActiveRecord of DB table image_list.
 */
class Picture extends ActiveRecord
{
	public $picture_id;
	
	public static function tableName()
    {
        return 'image_list';
    }
}
