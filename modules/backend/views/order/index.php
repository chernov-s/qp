<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'label' => 'ID пользователя',
                'value' => function ($order) {
                    return Html::a(Html::encode($order->user_id),
                        Url::to(['user/view', 'id' => $order->user->id]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user.email',
                'label' => 'Email пользователя',
                'value' => function ($order) {
                    return Html::a(Html::encode($order->user->email),
                        Url::to(['user/view', 'id' => $order->user->id]));
                },
                'format' => 'raw',
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        return Html::a(
                            '<i class="fa fa-edit"></i>',
                            $url);
                    },
                    'delete' => function ($url,$model) {
                        return Html::a(
                            '<i class="fa fa-trash"></i>',
                            $url);
                    },
                ],
            ],
        ],
    ]); ?>
</div>