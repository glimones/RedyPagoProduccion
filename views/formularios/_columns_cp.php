<?php
use yii\helpers\Url;
use yii\helpers\Html;

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
        'attribute'=>'fecha',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'attribute'=>'precio',
        'hAlign' => 'right',
        'value'=>function ($model, $key, $index, $widget) {
            return '<h2>$ '.$model->precio.'</h2>';
        },
        'format' => 'raw',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'token',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'empresa_id',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view}{update}{delete}{link}',//gli agrega link
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
        //gli ini
        'buttons' => [
            'link' => function ($model,$key) {
                return Html::img(
                    Url::to('@web/images/ws.png'),
                    array(
                        'id'=>'obtToken',
                        'title'=>'Copy',
                        'data-toggle'=>'tooltip',
                        'name'=>Url::to(array('forms/t?token='.$key->token), true),
                        'class'=>'boton',
                        'data-toggle'=>'tooltip',
                        'style'=>'cursor: pointer;',
                        'value' => Url::to(array('forms/t?token='.$key->token), true),
            ))
                    ;

            },
        ],
        //gli fin
    ],

];
