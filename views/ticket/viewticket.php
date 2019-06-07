<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $commentFormModel app\models\CommentSendForm */
/* @var $ticketModel app\models\Ticket */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\ListView;

$this->title = 'Ticket View';
$this->params['breadcrumbs'][] = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode('Ticket details:')?></h2>
<?php if (Yii::$app->user->identity->is_admin) : ?>
    <?= Html::a('Set priority', '/ticket/setpriority?id=' . $ticketModel->id, ['class' => 'btn btn-info btn-sm']); ?>
<?php endif ?>
<?php if (!Yii::$app->user->identity->is_admin) : ?>
    <?= Html::a('Report', '/ticket/reportticket?id=' . $ticketModel->id, ['class' => 'btn btn-warning btn-sm']); ?>
<?php endif ?>
<?= DetailView::widget([
    'model' => $ticketModel,
    'attributes' => [
        'id',
        'heading',
        [
            'label' => 'Description',
            'attribute' => 'description.content'
        ],
        'user.name',
        [
            'label' => 'Who manage',
            'attribute' => 'manager',
            'value' => function ($data) {
                return !empty($data->admin_id) ? $data->admin->name : '[NEW]';
            }
        ],
        [
                'label' => 'Number of comments',
                'attribute' => 'numberofcomments',
        ],

    ],
]) ?>

<div class="Comment-view" style="overflow-y: scroll; height: 400px; border: 1px solid lightslategrey">
    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_post',
        'layout' => "{pager}\n{items}",
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel' => '>>>',
            'maxButtonCount' => 3,
        ],
    ]);?>
</div>
<div class="add-comment-form">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($commentFormModel, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send Comment', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
