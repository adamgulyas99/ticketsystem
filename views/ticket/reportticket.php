<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $ticketModel app\models\Ticket */
/* @var $commentSendForm app\models\Comment */

$this->title = 'Report ticket';
$this->params['breadcrumbs'][] = ['label' => 'Tickets'];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Report ticket </h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($ticketModel, 'priority')->dropDownList(
    ['normal' => 'Normal', 'urgent' => 'Urgent', 'critical' => 'Critical']
);?>

<?= $form->field($ticketModel, 'heading')->textarea() ?>

<?= $form->field($commentSendForm, 'content')->textarea(['rows' => 6]) ?>

<div class="form-group">
    <?= Html::submitButton('Send report', ['class' => 'btn btn-danger']) ?>
</div>

<?php ActiveForm::end(); ?>