<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ListOfTicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Closed Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            [
                'attribute' => 'heading',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a($data['heading'], '/ticket/viewticket?id=' . $data['id']);
                },
            ],
            [
                'attribute' => 'priority',
                'contentOptions' => function ($model, $key, $index, $column) {
                    if(strcmp($model->priority, 'urgent') === 0) {
                        return ['style' => 'color:orange'];
                    } else if(strcmp($model->priority, 'critical') === 0) {
                        return ['style' => 'color:red'];
                    }
                    return ['style' => 'color:black'];
                }
            ],
            [
                'label' => 'Who sent',
                'attribute' => 'sender',
                'value' => 'user.name'
            ],
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
    ]); ?>


</div>
