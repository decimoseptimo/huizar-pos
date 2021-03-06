<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\BootstrapAsset;
use yii\web\JqueryAsset;
use app\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = [
    'encode' => false,
    'label' => '<span class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Clientes <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>' . Html::a("Ordenes", ["order/index"]) . '</li>
            </ul>
        </span>
    ',
];

//Bootstrap Select
$this->registerCssFile('@web/css/bootstrap-select.min.css', ['depends' => [BootstrapAsset::className()]]);
$this->registerJsFile('@web/js/bootstrap-select.min.js', ['depends' => [JqueryAsset::className()]]);
?>

<div class="customer-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="action-menu">
            <?= $this->render('_search', ['model' => $searchModel, 'allowedTimeUnits' => $allowedTimeUnits]); ?>
            <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> &nbsp;Crear Cliente', ['create'], ['class' => 'btn btn-success btn-sm item']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'customer table table-striped table-bordered'],
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function($model) {
                    return Html::a('<span class="glyphicon glyphicon-user text-warning" title="Ver cliente"></span>', ['customer/view', 'id' => $model->id], ['tooltip' => 'ver cliente']);
                }
            ],
            //'id',
            /*[
                'attribute' => 'id',
                'filterOptions' => ['class' => 'id-filter'],
                'contentOptions' => ['class' => 'id-content'],
            ],*/
            'first_name',
            'last_name',
            'email:email',
            [
                'attribute' => 'Ordenes',
                'filter' => '
                <table class="orders-column-table filters-row">
                    <tr>
                        <td>'.Order::getStatusIcon(Order::STATUS_RECEIVED).'</td>
                        <td>'.Order::getStatusIcon(Order::STATUS_READY_TO_DELIVER).'</td>
                        <td>'.Order::getStatusIcon(Order::STATUS_DELIVERED).'</td>
                    </tr>
                </table>',
                'format' => 'raw',
                'filterOptions' => ['class' => 'orders-column'],
                'contentOptions' => ['class' => 'text-center orders-column'],
                'value' => function($model) {
                    $ordersReceivedCount = $model->getOrders()->where(['status' => Order::STATUS_RECEIVED])->count();
                    $ordersReadyCount = $model->getOrders()->where(['status' => Order::STATUS_READY_TO_DELIVER])->count();
                    $ordersDeliveredCount = $model->getOrders()->where(['status' => Order::STATUS_DELIVERED])->count();

                    return '
                    <table class="orders-column-table">
                        <tr>
                            <td>'. HTML::a($ordersReceivedCount, ['customer/orders', 'id'=>$model->id]) .'</td>
                            <td>'. HTML::a($ordersReadyCount, ['customer/orders', 'id'=>$model->id]) .'</td>
                            <td>'. HTML::a($ordersDeliveredCount, ['customer/orders', 'id'=>$model->id]) .'</td>
                        </tr>
                    </table>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Crear Orden',
                'template' => '{new-order}',
                'buttons' => [
                    'new-order' => function ($url,$model) {
                        return Html::a('Crear Orden', ['order/create', 'id' => $model->id], ['class' => 'btn btn-default btn-sm btn-block']);
                    },
                ],
            ],
        ],
    ]); ?>

</div>