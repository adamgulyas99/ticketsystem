<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="panel panel-default" style="position: relative">
    <div class="panel panel-heading" style="margin-bottom: 0; height: 50px;">
        <p style="font-size: 9pt;">
            Author: <?= $model->user->name; ?> <br>
            Date: <?= $model->create_time; ?> <br>
        </p>
    </div>
    <?php if ($model->user->is_admin) : ?>
    <div class="panel-body" style="border: 2px red; color: darkslategrey; background-color: #e5d0ae; font-size: 12pt;"><?=Html::encode($model->content) ?> </div>
    <?php elseif (!$model->user->is_admin) : ?>
    <div class="panel-body" style="border: 1px black; color: darkslategrey; background-color: #e0e2e5; min-height: 100px;"><?=Html::encode($model->content) ?> </div>
    <?php endif; ?>
</div>