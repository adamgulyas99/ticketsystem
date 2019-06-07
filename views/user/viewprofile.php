<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = 'Profile';
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode('Profile') ?></h1>

    <p>
        <?= Html::a('Modify Name', ['modifyname', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'email',
            'last_login_time',
            'reg_time',
        ],
    ]) ?>

</div>
