<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Status is the ActiveRecord of DB table user_list.
 */
class Status extends ActiveRecord
{
	public static function tableName()
    {
        return 'user_list';
    }
}
