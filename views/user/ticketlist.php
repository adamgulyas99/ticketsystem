<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'heading:text',
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
            'status:boolean',

        ],
    ]); ?>


</div>
