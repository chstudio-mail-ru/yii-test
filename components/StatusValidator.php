<?php

namespace app\components;

use yii\validators\Validator;
use app\models\Status;

class StatusValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Пользователь с таким e-mail существует.';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (Status::find()->where(['useremail' => $value])->exists())
        {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $statuses = json_encode(Status::find()->select('useremail')->asArray()->column());
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        //echo $statuses;
        return <<< JS
if ($.inArray(value, $statuses) >= 0) {
    messages.push($message);
}
JS;
    }
}