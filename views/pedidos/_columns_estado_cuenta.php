<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use app\models\Clientes;
use yii\helpers\Html;
use app\models\Empresas;
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
        'attribute'=>'fecha_pago',
        'width'=>'100px',
        'hAlign' => 'left',
    ],
    [
        'attribute'=>'cliente_id', 
        // 'width'=>'520px',
        // 'vAlign'=>'middle',  
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->cliente->identificacion.' - '.$model->cliente->nombres.' '.$model->cliente->apellidos;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> (Yii::$app->user->identity->es_admin == 1) ? ArrayHelper::map(Clientes::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id)->orderBy('nombres')->asArray()->all(), 'id', 
            function($model, $defaultValue) {
                return $model['identificacion'].'-'.$model['nombres'].' '.$model['apellidos'];
            }) : ArrayHelper::map(Clientes::find()->orderBy('nombres')->asArray()->all(), 'id', 
            function($model, $defaultValue) {
                return $model['identificacion'].'-'.$model['nombres'].' '.$model['apellidos'];
            }
        ), 
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Seleccione cliente'],
        'format'=>'raw'
    ],
    [
        'attribute'=>'brand',
        'width'=>'100px',
        'hAlign' => 'left',
    ],
    [
        'attribute'=>'reserved_22',
        'width'=>'100px',
        'hAlign' => 'left',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'purchase_operation_number',
        'width'=>'130px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'authorization_code',
        'width'=>'130px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'payment_reference_code',
        'width'=>'130px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'a_pagar',
        'width'=>'130px',
        'hAlign' => 'right',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'hAlign' => 'right',
        'width'=>'130px',
        // 'format'=>'datetime',
    ],

];   