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
        'attribute'=>'ruc',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'razon_social',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_commerce',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'logo',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'contacto_cedula',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'contacto_nombres',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'contacto_apellidos',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ciudad_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'direccion',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'actividades',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'url_comercio_electronico',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_afiliacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_afiliacion',
        'header'=>'Fecha renovación',
        'value'=>function ($model, $key, $index, $widget) {
            $afiliacion = strtotime( $model->fecha_afiliacion );
            $afiliacion = strtotime( '+1 month', $afiliacion );
            return date( 'Y-m-d', $afiliacion );
        },
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_acquirer',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_wallet',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'llave_vpos',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'llave_wallet',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ambiente',
    ],
    [
        'attribute'=>'adquirente_id',
        'value'=>function ($model, $key, $index, $widget) {
            if( !is_null($model->adquirente_id) ){
                return $model->adquirente->nombre;
            }else{
                return null;
            }
        },
        // 'width'=>'8%',
        // 'filterType'=>GridView::FILTER_SELECT2,
        // 'filter'=>[ 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Pagado' => 'Pagado', 'Depositado' => 'Depositado'], 
        'format'=>'raw'
    ],
    [
        'attribute'=>'procesamiento_id',
        'value'=>function ($model, $key, $index, $widget) {
            if( !is_null($model->procesamiento_id) ){
                return $model->procesamiento->nombre;
            }else{
                return null;
            }
        },
        // 'width'=>'8%',
        // 'filterType'=>GridView::FILTER_SELECT2,
        // 'filter'=>[ 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Pagado' => 'Pagado', 'Depositado' => 'Depositado'], 
        'format'=>'raw'
    ],
    [
        'attribute'=>'estado',
        'value'=>function ($model, $key, $index, $widget) {
            if( $model->estado == 0 ){
                $color = 'warning';
                $estado = 'Inactivo';
            }else{
                $color = 'success';
                $estado = 'Activo';
            }
            return "<span class='badge btn btn-".$color."'> ". $estado ."</span>";
        },
        // 'width'=>'8%',
        // 'filterType'=>GridView::FILTER_SELECT2,
        // 'filter'=>[ 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Pagado' => 'Pagado', 'Depositado' => 'Depositado'], 
        'format'=>'raw'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   