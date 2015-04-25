<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\EditPicture */

$this->title = 'Редактор рисунков';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<canvas id="imageView" width="500" height="400">
<p>
    Ваш браузер не поддерживает рисование мышью.
</p>
</canvas>
<?php
	if(isset($model->picture->imageName))
	{
		//setTimeout(...) - wait for loading all needed JS scripts
		echo "<script type=\"text/javascript\">window.onload = setTimeout(\"canvasLoad('".$model->picture->imageName."')\", 100);</script>";
	}
?>
    <?php $form = ActiveForm::begin([
        'id' => 'registerform',
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
	        if(!\Yii::$app->user->isGuest)
	        {
	        	$user_id = \Yii::$app->user->id;
	        	$picture_id = isset(\Yii::$app->request->get()['id'])? \Yii::$app->request->get ()['id'] : 0;
	        }

	        if(\Yii::$app->user->isGuest)
	        {
				echo Html::submitButton('Сохранить + Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'register-button', 'onClick' => "canvasSave(0, 0);"]);
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
				echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button', 'onClick' => "canvasSave(".$user_id.", ".$picture_id.");"]);
	        }
		?>
    </div>
</div>

    <?php ActiveForm::end() ?>
