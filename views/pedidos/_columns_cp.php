<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use app\models\Clientes;
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
        'attribute'=>'numero_pedido',
        'width'=>'100px',
        //'hAlign' => 'right',
        'hAlign' => 'center',
    ],
    /*[
        'attribute'=>'cliente_id',
        'hAlign' => 'center',
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
    ],*/
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
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'payment_reference_code',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'hAlign' => 'center',
        // 'width'=>'180px',
        // 'format'=>'raw'
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'purchase_operation_number',
    //     'width'=>'130px',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'authorization_code',
    //     'width'=>'130px',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'payment_reference_code',
    //     'width'=>'130px',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_creacion',
        'hAlign' => 'center',
        //'hAlign' => 'right',
        'width'=>'130px',
        // 'filter' => \janisto\timepicker\TimePicker::widget([
        //     'mode' => 'date',
        //     'clientOptions'=>[
        //         'dateFormat' => 'yy-mm-dd',
        //         // 'timeFormat' => 'HH:mm',
        //         'showSecond' => false,
        //     ]
        // ]),
        // 'format'=>'datetime',

    ],

    [
        'attribute'=>'usuarioCons',
        'hAlign' => 'center',
        'value'=>function ($model, $key, $index, $widget) {
            $usuario=$model->usuario_id;
            $modelo=\app\models\Usuarios::findBySql("select * from usuarios where id='$usuario'")->one();
            if($modelo) {
                return $modelo->nombres.' '.$modelo->apellidos;
            }else{
                return "";
            }
        },
        // 'filterType'=>GridView::FILTER_SELECT2,
        // 'filter'=>[ ''=>'Todos', 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Autorizado' => 'Autorizado'], 
        //'format' => 'raw',
    ],
    [
        'attribute'=>'a_pagar',
        'width'=>'110px',
       //'hAlign' => 'right',
        'hAlign' => 'center',
        'value'=>function ($model, $key, $index, $widget) {
                $model->mensaje($model->error_message);
                return "<table><tbody><tr><h2>$ $model->a_pagar </h2></tr><tr><h6>$model->value</h6></tr></tbody></table>";
        },
        'format' => 'raw',
    ],
    [
        'attribute'=>'estado',
        'filter'=>false,
        'width'=>'140px',
        'hAlign' => 'center',
        'value'=>function ($model, $key, $index, $widget) {
            $color = 'default';
            if( $model->estado == 'Pago pendiente' ){
                $color = 'warning';
            }elseif( $model->estado == 'No autorizado' ){
                $color = 'danger';
            }elseif( $model->estado == 'Autorizado' ){
                $color = 'success';
            }elseif( $model->estado == 'Depositado' ){
                $color = 'success';
            }
            return Html::img( Url::to('@web/images/ajax-loader-pedidos.gif'), [ 'id'=>'loader_pedido_'.$model->id, 'class'=> 'oculto'])."<span id-pedido='".$model->id."' id='btn_pedido_".$model->id."' estado='".$model->estado."' class='pedido_estado badge btn btn-".$color."'> ".$model->estado ."</span>";
        },
        // 'width'=>'8%',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>[ ''=>'Todos', 'Pago pendiente' => 'Pago pendiente', 'No autorizado' => 'No autorizado', 'Autorizado' => 'Autorizado'], 
        'format'=>'raw'

    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view}{link2}{link}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip',],
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
                        Url::to('@web/images/copy.png'),
                        array(
                           'id'=>'obtieneToken',
                            'title'=>'Copy',
                            'name'=> Url::to(['payments/t?token='.$key->token],true),
                            'data-toggle'=>'tooltip',
                            'style'=>'cursor: pointer;',
                            'class'=>'boton1',
                            'value' => Url::to(['payments/t?token='.$key->token],true),
                            //'onclick'=>$this->get('session')->getFlashBag()->add('success', 'ok'),
                                           ));

                },
            'link2' => function ($model,$key) {
               return Html::img(
                    Url::to('@web/images/espacio.png'));
            },
            ],
//gli fin
    ],

];   