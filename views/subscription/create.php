<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Subscription */
/* @var $author app\models\Author */

$this->title = "Подписка на {$author->name}";
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php
$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label('Номер телефона') ?>

<div class="form-group">
    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
</div>

<?php
ActiveForm::end(); ?>
