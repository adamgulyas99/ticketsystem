<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ticket */

$this->title = 'Update Ticket: ' . $ticketModel->id;
$this->params['breadcrumbs'][] = ['label' => 'Opened Tickets', 'url' => ['listopenedtickets']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <h1><?= Html::encode('Do you want to manage this ticket?') ?></h1>

    <div class="ticket-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($ticketModel, 'admin_id')->checkbox([
                'label' => 'Click here, if you want to manage'
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
