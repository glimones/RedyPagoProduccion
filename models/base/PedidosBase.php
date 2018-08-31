<?php

namespace app\models\base;

use app\models\Pedidos;
use Yii;
use app\models\Items;
use app\models\Clientes;
use app\models\Empresas;
use app\models\Usuarios;
use app\models\PedidosOperaciones;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "pedidos".
*
    * @property integer $id
    * @property integer $cliente_id
    * @property integer $empresa_id
    * @property integer $usuario_id
    * @property string $a_pagar
    * @property string $tarjeta
    * @property string $numero_pedido
    * @property string $authorization_result
    * @property string $authorization_code
    * @property string $error_code
    * @property string $payment_reference_code
    * @property string $reserved_22
    * @property string $reserved_23
    * @property string $estado
    * @property string $url_pago
    * @property string $token
    * @property string $fecha_creacion
    * @property string $fecha_pago
    * @property string $descripcion
    * @property string $purchase_operation_number
    * @property string $idtransaction
    * @property string $purchaseverification
    * @property string $brand
    * @property string $error_message
    * @property string $subtotal
    * @property string $iva
    * @property string $total
    * @property string $total_con_iva
    * @property string $total_sin_iva
    * @property string $forma_pago
    * @property string $pago_recibido
    * @property string $cambio_entregado
    * @property string $tipo
    * @property integer $testing
    * @property string $aplica_iva
    * @property integer $pin_efectivo
    * @property integer $factura_emitida
    * @property string $factura_fecha_emision
    * @property string $factura_clave_acceso
    *
            * @property Items[] $items
            * @property Clientes $cliente
            * @property Empresas $empresa
            * @property Usuarios $usuario
            * @property PedidosOperaciones[] $pedidosOperaciones
    */
class PedidosBase extends \yii\db\ActiveRecord
{

Public $value='';
public function mensaje($model){
    if($model==''){
        $this->value='(no definido)';
    }elseif( $model == 'PONLEMAS' ){
        $this->value= 'Efectivo';
    }elseif( !is_null($model) ){
        $this->value= 'Tarjeta crédito/debito';
    }
}

/**
* @inheritdoc
*/
public static function tableName()
{
return 'pedidos';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['cliente_id', 'empresa_id'], 'required'],
            [['cliente_id', 'empresa_id', 'usuario_id', 'testing', 'pin_efectivo', 'factura_emitida'], 'integer'],
            [['a_pagar', 'subtotal', 'iva', 'total', 'total_con_iva', 'total_sin_iva', 'pago_recibido', 'cambio_entregado'], 'number'],
            [['tarjeta', 'estado', 'token', 'forma_pago', 'tipo', 'aplica_iva'], 'string'],
            [['fecha_creacion', 'fecha_pago', 'factura_fecha_emision'], 'safe'],
            [['numero_pedido', 'purchase_operation_number'], 'string', 'max' => 9],
            [['authorization_result'], 'string', 'max' => 2],
            [['authorization_code'], 'string', 'max' => 6],
            [['error_code'], 'string', 'max' => 4],
            [['payment_reference_code'], 'string', 'max' => 45],
            [['reserved_22'], 'string', 'max' => 10],
            [['reserved_23'], 'string', 'max' => 300],
            [['url_pago'], 'string', 'max' => 2000],
            [['descripcion'], 'string', 'max' => 400],
            [['idtransaction'], 'string', 'max' => 100],
            [['purchaseverification'], 'string', 'max' => 200],
            [['brand'], 'string', 'max' => 25],
            [['error_message'], 'string', 'max' => 1000],
            [['factura_clave_acceso'], 'string', 'max' => 1500],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::className(), 'targetAttribute' => ['empresa_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'cliente_id' => 'Cliente ID',
    'empresa_id' => 'Empresa ID',
    'usuario_id' => 'Usuario ID',
    'a_pagar' => 'A Pagar',
    'tarjeta' => 'Tarjeta',
    'numero_pedido' => 'Numero Pedido',
    'authorization_result' => 'Authorization Result',
    'authorization_code' => 'Authorization Code',
    'error_code' => 'Error Code',
    'payment_reference_code' => 'Payment Reference Code',
    'reserved_22' => 'Reserved 22',
    'reserved_23' => 'Reserved 23',
    'estado' => 'Estado',
    'url_pago' => 'Url Pago',
    'token' => 'Token',
    'fecha_creacion' => 'Fecha Creacion',
    'fecha_pago' => 'Fecha Pago',
    'descripcion' => 'Descripcion',
    'purchase_operation_number' => 'Purchase Operation Number',
    'idtransaction' => 'Idtransaction',
    'purchaseverification' => 'Purchaseverification',
    'brand' => 'Brand',
    'error_message' => 'Error Message',
    'subtotal' => 'Subtotal',
    'iva' => 'Iva',
    'total' => 'Total',
    'total_con_iva' => 'Total Con Iva',
    'total_sin_iva' => 'Total Sin Iva',
    'forma_pago' => 'Forma Pago',
    'pago_recibido' => 'Pago Recibido',
    'cambio_entregado' => 'Cambio Entregado',
    'tipo' => 'Tipo',
    'testing' => 'Testing',
    'aplica_iva' => 'Aplica Iva',
    'pin_efectivo' => 'Pin Efectivo',
    'factura_emitida' => 'Factura Emitida',
    'factura_fecha_emision' => 'Factura Fecha Emision',
    'factura_clave_acceso' => 'Factura Clave Acceso',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getItems()
    {
    return $this->hasMany(Items::className(), ['pedido_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCliente()
    {
    return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresa()
    {
    return $this->hasOne(Empresas::className(), ['id' => 'empresa_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUsuario()
    {
    return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPedidosOperaciones()
    {
    return $this->hasMany(PedidosOperaciones::className(), ['pedido_id' => 'id']);
    }
}