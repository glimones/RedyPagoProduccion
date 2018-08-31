<?php
use yii\helpers\Url;

return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    // ],
    // [
    //     'class' => 'kartik\grid\SerialColumn',
    //     'width' => '30px',
    // ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cedula',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idtransaccion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'valor',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cod_descomplicate',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'mensaje_descomplicate',
    ],
    [
        'attribute'=>'estado',
        'value'=>function ($model, $key, $index, $widget) {
            if( $model->estado == 0 ){
                return 'Sin conexión Descomplicate';
            }elseif( $model->estado == 1 ){
                return 'Transacción Autorizada';
            }elseif( $model->estado == 2 ){
                return 'Reverso Autorizado';
            }elseif( $model->estado == 4 ){
                return 'Error';
            }
        },
        // 'filterType'=>GridView::FILTER_SELECT2,
        // 'filter'=>[ ''=>'Todos', 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Autorizado' => 'Autorizado'], 
        'format' => 'raw',
    ],
    
    // [
    //     'class' => 'kartik\grid\ActionColumn',
    //     'dropdown' => false,
    //     'vAlign'=>'middle',
    //     'urlCreator' => function($action, $model, $key, $index) { 
    //             return Url::to([$action,'id'=>$key]);
    //     },
    //     'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
    //     'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
    //     'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
    //                       'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
    //                       'data-request-method'=>'post',
    //                       'data-toggle'=>'tooltip',
    //                       'data-confirm-title'=>'Are you sure?',
    //                       'data-confirm-message'=>'Are you sure want to delete this item'], 
    // ],

];   