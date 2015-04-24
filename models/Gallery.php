<?php

namespace app\models;

use yii\base\Model;
use yii\db\Query;
use yii\web\DbSession;
use yii\base\ErrorException;

/**
 * Gallery is the model on Index page.
 */
class Gallery extends Model
{
    public static $gallery = [];

    public function __construct()
    {
        $query = new Query();

        //SELECT * FROM `user_list` LEFT JOIN `image_list` ON user_list.id = image_list.userId WHERE image_list.imageName<>''
        $rows = $query->select(['*'])
                     ->from('user_list')
                     ->leftJoin('image_list', 'user_list.id=image_list.userId')
                     ->where('image_list.imageName<>\'\'')
                     ->orderby('image_list.createTime DESC')
                     ->all();

        foreach($rows as $arr)
        {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $arr['createTime']);
            if(isset(\Yii::$app->user->identity->id) && $arr['userId'] === \Yii::$app->user->identity->id)
            {
                self::$gallery[] = [
                    'user_id' => $arr['userId'],
                    'author_name' => $arr['username'],
                    'img_name' => $arr['imageName'],
                    'thumb_name' => "tn-".$arr['imageName'],
                    'create_time' => $date->format("d.m.Y H:i:s"),
                    'edit_link' => '<a href="">редактировать</a>',
                    'delete_link' => '<a href="">удалить</a>',
               ];
            }
            else
            {
                self::$gallery[] = [
                    'user_id' => $arr['userId'],
                    'author_name' => $arr['username'],
                    'img_name' => $arr['imageName'],
                    'thumb_name' => "tn-".$arr['imageName'],
                    'create_time' => $date->format("d.m.Y H:i:s"),
               ];
            }
        }

        //print_r(self::$gallery);

        /*
        [0] => Array
        (
            [id] => 27
            [useremail] => da@aaaa16.ru
            [username] => 1
            [password] => c4ca4238a0b923820dcc509a6f75849b
            [accessToken] => c9akZmPUtDC3yOe1P9DzdA8ecaHTGuWX
            [authKey] => A6czMZ4UJkn8yZsIhnDrwFSZNHZ5f07p
            [registerTime] => 2015-04-24 15:18:14
            [userId] => 42
            [imageName] => 42-hHQ7mgTeEiBZ1ckLk8ZGVCwYQKlKX6fb.png
            [createTime] => 2015-04-24 15:18:25
        )
        */
    }
}
