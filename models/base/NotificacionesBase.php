<?php

namespace app\models\base;

use Yii;
use app\models\Clientes;
use app\models\Empresas;

/**
 * This is the model class for table "notificaciones".
*
    * @property integer $id
    * @property integer $cliente_id
    * @property string $para
    * @property string $asunto
    * @property string $html
    * @property string $fecha_creacion
    * @property string $fecha_envio
    * @property string $estado
    * @property integer $empresa_id
    *
            * @property Clientes $cliente
            * @property Empresas $empresa
    */
class NotificacionesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'notificaciones';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['cliente_id', 'empresa_id'], 'integer'],
            [['html', 'estado'], 'string'],
            [['fecha_creacion', 'fecha_envio'], 'safe'],
            [['para', 'asunto'], 'string', 'max' => 850],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::className(), 'targetAttribute' => ['empresa_id' => 'id']],
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
    'para' => 'Para',
    'asunto' => 'Asunto',
    'html' => 'Html',
    'fecha_creacion' => 'Fecha Creacion',
    'fecha_envio' => 'Fecha Envio',
    'estado' => 'Estado',
    'empresa_id' => 'Empresa ID',
];
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
}