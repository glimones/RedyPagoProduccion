<?php

namespace app\models\base;

use Yii;
use app\models\Pedidos;

/**
 * This is the model class for table "pedidos_operaciones".
*
    * @property integer $id
    * @property integer $pedido_id
    * @property string $purchase_operation_number
    * @property string $dispositivo
    * @property string $fecha
    *
            * @property Pedidos $pedido
    */
class pedidosOperacionesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'pedidos_operaciones';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['pedido_id', 'purchase_operation_number'], 'required'],
            [['pedido_id'], 'integer'],
            [['fecha'], 'safe'],
            [['purchase_operation_number'], 'string', 'max' => 9],
            [['dispositivo'], 'string', 'max' => 150],
            [['pedido_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['pedido_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'pedido_id' => 'Pedido ID',
    'purchase_operation_number' => 'Purchase Operation Number',
    'dispositivo' => 'Dispositivo',
    'fecha' => 'Fecha',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPedido()
    {
    return $this->hasOne(Pedidos::className(), ['id' => 'pedido_id']);
    }
}