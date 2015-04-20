<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'Нарисуйте изображение';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<canvas id="imageView" width="500" height="400">
<p>
    Ваш браузер не поддерживает рисование мышью.
</p>
</canvas>