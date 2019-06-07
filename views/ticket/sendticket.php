<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\TicketSendForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Send ticket';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('ticketSendFormSubmitted')): ?>

        <div class="alert alert-success">
            You have sent a ticket successfully!
        </div>



    <?php else: ?>

        <p>
            Please fill out the following form to send a ticket!
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($ticketSendForm, 'heading')->textInput() ?>

                <?= $form->field($ticketSendForm, 'content')->textarea(['rows' => 6]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'ticket-send-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
