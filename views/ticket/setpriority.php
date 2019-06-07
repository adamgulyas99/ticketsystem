<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $ticketModel app\models\Ticket */

$this->title = 'Ticket View';
$this->params['breadcrumbs'][] = ['label' => 'Tickets'];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Set new priority to this ticket: </h1>

<?php $form = ActiveForm::begin(); ?>


    <?= $form->field($ticketModel, 'priority')->dropDownList(
            ['normal' => 'Normal', 'urgent' => 'Urgent', 'critical' => 'Critical']
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Change it', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>