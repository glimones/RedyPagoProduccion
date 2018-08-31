<?php

namespace app\models;
use Yii;

class Pedidos extends \app\models\base\PedidosBase
{
	public function rules()
    {
        return array_merge(parent::rules(),
	    [
            ['total_con_iva', 'default', 'value' => '0.00'],
            ['total_sin_iva', 'default', 'value' => '0.00'],
            [['total_con_iva', 'total_sin_iva', 'descripcion'], 'required'],
            [['descripcion', ], 'safe'],
        ]);
    }

    public function attributeLabels()
	{
		return [
		    'id' => Yii::t('app', 'ID'),
		    'cliente_id' => Yii::t('app', 'Cliente'),
		    'empresa_id' => Yii::t('app', 'Empresa'),
		    'usuario_id' => Yii::t('app', 'Generado por'),
		    'a_pagar' => Yii::t('app', 'Monto en USD'),
		    'tarjeta' => Yii::t('app', 'Tarjeta'),
		    'numero_pedido' => Yii::t('app', '# Pedido'),
		    'authorization_result' => Yii::t('app', 'Authorization Result'),
		    'authorization_code' => Yii::t('app', 'Código de autorización'),
		    'error_code' => Yii::t('app', 'Error Code'),
		    'payment_reference_code' => Yii::t('app', '# tarjeta'),
		    'reserved_22' => Yii::t('app', 'Tipo de tarjeta'),
		    'reserved_23' => Yii::t('app', 'Banco emisor'),
		    'estado' => Yii::t('app', 'Estado'),
		    'url_pago' => Yii::t('app', 'Url Pago'),
		    'fecha_creacion' => Yii::t('app', 'Fecha Creación'),
		    'fecha_pago' => Yii::t('app', 'Fecha Pago'),
		    'descripcion' => Yii::t('app', 'Descripción del pedido'),
		    'purchase_operation_number' => Yii::t('app', '# de operación'),
		    'brand' => Yii::t('app', 'Marca'),
		    'total_con_iva' => Yii::t('app', 'Monto Tarifa 12%'),
		    'total_sin_iva' => Yii::t('app', 'Monto Tarifa 0%'),
		    'error_message' => Yii::t('app', 'Tipo de pago'),

		];
	}
}