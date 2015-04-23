<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegisterForm */

$this->title = 'Редактор рисунков';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<canvas id="imageView" width="500" height="400">
<p>
    Ваш браузер не поддерживает рисование мышью.
</p>
</canvas>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

<div class="form-group">
    <div class="col-lg-offset-0 col-lg-11">
	    <?= Html::button('Очистить', ['class' => 'btn btn-primary', 'name' => 'clear-button', 'onClick' => "canvasClear();"]) ?>
		<?php
        	$file_name = session_id().".png";
	        if(!\Yii::$app->user->isGuest)
	        {
	        	$user_id = \Yii::$app->user->id;
	        }	

	        if(\Yii::$app->user->isGuest)
	        {
				echo Html::submitButton('Сохранить + Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'register-button', 'onClick' => "canvasSave('".$file_name."');"]);
				?>
				<div class="register-fields">
				<?php
    			echo $form->field($model, 'useremail');
    			echo $form->field($model, 'password')->passwordInput();
    			echo $form->field($model, 'password_repeat')->passwordInput();
				echo $form->field($model, 'username');    			
				?>
				</div>
				<?php
	        }
	        else
	        {
				echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button', 'onClick' => "canvasSave('".$file_name."','".$user_id."');"]);
	        }
		?>
    </div>
</div>

    <?php ActiveForm::end() ?>
