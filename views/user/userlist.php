<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a($data['name'], '/user/ticketlist?id=' . $data['id']);
                },
            ],
            'email',
            [
                'attribute' => 'last_login_time',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            'reg_time:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'update') {
                        $url ='/user/modifyuser?id='.$model->id;
                        return $url;
                    }

                    if ($action === 'delete') {
                        $url ='/user/delete?id='.$model->id;
                        return $url;
                    }

                },
            ],
        ],
    ]); ?>


</div>
