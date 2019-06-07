<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ListOfTicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opened Tickets';
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
                    if($data['admin_id'] !== null && $data['admin_id'] === Yii::$app->user->identity->getId()) {
                        return Html::a($data['heading'], '/ticket/viewticket?id=' . $data['id']);
                    }
                    return $data['heading'];
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

            [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Manager options',
                    'template' => '{update}',
                    'urlCreator' => function ($action, $model, $key, $index) {

                        if ($action === 'update') {
                            $url ='/ticket/takingticket?id='.$model->id;
                            return $url;
                        }

                    },
                    'visible' => function ($data) {
                        return $data->admin->name ? false : true;
                    }
            ],
        ],
    ]); ?>


</div>
