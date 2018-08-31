<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "transacciones_descomplicate".
*
    * @property integer $id
    * @property string $cedula
    * @property string $idtransaccion
    * @property string $valor
    * @property string $fecha
    * @property integer $estado
    * @property string $mensaje_descomplicate
    * @property string $cod_descomplicate
*/
class TransaccionesDescomplicateBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'transacciones_descomplicate';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['valor'], 'number'],
            [['fecha'], 'safe'],
            [['estado'], 'integer'],
            [['cedula'], 'string', 'max' => 45],
            [['idtransaccion'], 'string', 'max' => 200],
            [['mensaje_descomplicate'], 'string', 'max' => 800],
            [['cod_descomplicate'], 'string', 'max' => 450],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'cedula' => 'Cedula',
    'idtransaccion' => 'Idtransaccion',
    'valor' => 'Valor',
    'fecha' => 'Fecha',
    'estado' => 'Estado',
    'mensaje_descomplicate' => 'Mensaje Descomplicate',
    'cod_descomplicate' => 'Cod Descomplicate',
];
}
}