<?php

use app\assets\ManagerAsset;
use app\components\Html;
use app\models\Order;
use yii\bootstrap\Nav;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\OrderFilterForm */

$this->title = 'Панель менеджера';
$this->params['breadcrumbs'][] = $this->title;

$today = date('Y-m-d');
$yesterday = date("Y-m-d", time() - 60 * 60 * 24);
$get = Yii::$app->request->get();

$interval = isset($get['after']) && isset($get['before']) ? $get['after'] : '';

if (isset($get['after'])) {

}
if (isset($get['before'])) {

}

ManagerAsset::register($this);
?>
<div class="manager-toolbar">
    <div class="row manager-toolbar-wrap">
        <div class="col-sm-7 manager-date">
            <form action="/manager" method="get" class="datepicker-form">
                <input type="hidden" name="after" class="manager-date-start" value=<?=$model->after?>/>
                <input type="hidden" name="before" class="manager-date-end" value=<?=$model->before?>/>
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'nav nav-pills'],
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => 'Сегодня', 'url' => ['/manager', 'before' => $today, 'after' => $today]],
                        ['label' => 'Вчера', 'url' => ['/manager', 'before' => $yesterday, 'after' => $yesterday]],
                        '<li><input type="text" class="form-control date-interval" placeholder="Задать интервал" name="daterange"/></li>',
                    ],
                ]);
                ?>
                <select name="status" value=<?=$model->status?>>
                    <option value="-1">Все</option>
                <?php foreach (Order::$STATUS_TO_STRING as $k => $v) {
                    echo "<option value='$k'>$v</option>";
                }?>
                </select>
            </form>
        </div>


        <div class="col-sm-5 manager-password">
            <form action="/manager/secret" method="post">
                <ul class="nav nav-pills">
                    <li class="col-xs-8 cell">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <input type="text" name="password" class="form-control col-xs-10" placeholder="Секретный ключ заказа">
                    </li>
                    <li class="col-xs-3 cell">
                        <button type="submit" class="btn">Отправить</button>
                    </li>
                </ul>
            </form>
        </div><!-- manager-password -->

    </div>
</div>



<div class="product__table">
    <button class="btn js-print">Печать</button>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'ref',
                'format' => 'raw',
                'value' => function ($order) {
                    /* @var $order app\models\Order*/
                    return Html::a($order->public_id, ['view-order', 'id' => $order->id]);
                }
            ],
            'user.email',
            'created_at:datetime',
            [
                'attribute' => 'total_price',
                'format' => 'raw',
                'value' => function($x) { return Html::unstyled_price($x->total_price); }
            ],
            [
                'attribute' => 'confirmed_price',
                'format' => 'raw',
                'value' => function($x) { return Html::unstyled_price($x->confirmed_price); }
            ],
            'status_str',
        ],
    ]); ?>
</div>
