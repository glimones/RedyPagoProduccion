<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\tabs\TabsX;
use app\models\PedidosOperaciones;
/* @var $this yii\web\View */
/* @var $model app\models\Pedidos */
?>
<div class="pedidos-view">

<?php 

$facturacion_electronica =  [
                                'label' => '',
                                'content' => '',
                            ];

if( $model->empresa->facturacion_electronica == 1 ){
    $facturacion_electronica =  [
                                    'label' => 'Facturación electrónica',
                                    'content' => DetailView::widget([
                                                    'model' => $model,
                                                    'attributes' => [
                                                        [
                                                            'attribute'=>'factura_emitida',
                                                            'value'=>( $model->factura_emitida == 1 ) ? 'Si' : 'No',
                                                            'format' => ['raw'],
                                                        ],
                                                        'factura_fecha_emision',
                                                        'factura_clave_acceso',
                                                    ],
                                                ]),
                                ];
}

$regOper = PedidosOperaciones::find()->where(['pedido_id' => $model->id])->all();

$intentos = '<table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap">';
$intentos .= '<tr>';
$intentos .= '<th>';
$intentos .= '# de operación';
$intentos .= '</th>';
$intentos .= '<th>';
$intentos .= 'Dispositivo';
$intentos .= '</th>';
$intentos .= '<th>';
$intentos .= 'Fecha';
$intentos .= '</th>';
$intentos .= '</tr>';
foreach ($regOper as $reg) {
    $intentos .= '<tr>';
    $intentos .= '<td>';
    $intentos .= $reg->purchase_operation_number;
    $intentos .= '</td>';
    $intentos .= '<td>';
    $intentos .= $reg->dispositivo;
    $intentos .= '</td>';
    $intentos .= '<td>';
    $intentos .= $reg->fecha;
    $intentos .= '</td>';
    $intentos .= '</tr>';
}
$intentos .= '</table>';
?>

<?php 
    echo TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_LEFT,
    'items' => [
        [
            'label' => 'Detalle',
            'content' => DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        // 'id',
                        // 'url_pago:url',
                        'numero_pedido',
                        'descripcion',
                        [
                            'label' => 'Solicitud',
                            'attribute'=>'url_pago',
                            'value'=>'<a class="btn btn-primary btn-block" target="_blank" href="'.Url::to(['payments/t?token='.$model->token], true).'">Ver solicitud</a>',
                            'format' => ['raw'],
                        ],
                        [
                            'attribute'=>'url_pago',
                            'label' => 'Url de pago',
                            'format'=>'raw',
                            'value'=>Url::to(['payments/t?token='.$model->token], true),
                        ],

                        [
                            'attribute' => 'usuario.email',
                            'label' => 'Creado por',
                        ],
                        'purchase_operation_number',
                        'reserved_23',
                        'authorization_code',
                        'payment_reference_code',
                        'brand',
                        'reserved_22',
                        [
                            'attribute' => 'fecha_creacion',
                            // 'format'=>'datetime',
                            // 'label' => 'Creado por',
                        ],
                        [
                            'attribute' => 'fecha_pago',
                            // 'format'=>'datetime',
                            // 'label' => 'Creado por',
                        ],
                        [
                            'attribute'=>'a_pagar',
                            'value'=>'<h2>$ '.$model->a_pagar.' USD</h2>',
                            'format' => ['raw'],
                        ],
                        'estado',
                    ],
                ]),
            'active' => true
        ],
        [
            'label' => 'Intentos de pago',
            'content' => $intentos,
            
        ],
        $facturacion_electronica,
    ],
]);
?>

</div>
