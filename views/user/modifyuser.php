<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Modify User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['userlist']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h2> <?= Html::encode('Modify user') ?></h2>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($userUpdate, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userUpdate, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userUpdate, 'is_admin')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
