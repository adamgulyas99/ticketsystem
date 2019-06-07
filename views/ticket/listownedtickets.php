<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OwnedTicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'heading',
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
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status ? 'Opened' : 'Closed';
                },
            ],
            [
                    'label' => 'Creation time',
                    'attribute' => 'create_time',
                    'value' => 'lastcomment.create_time'
//                    'value' => function ($data) {
//                        return $data->lastcomment->create_time;
//                    }
            ],
            [
                'label' => 'Number of comments',
                'attribute' => 'numberofcomments',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'view') {
                        $url ='/ticket/viewticket?id='.$model->id;
                        return $url;
                    }

                },
            ]
        ],
    ]); ?>


</div>
