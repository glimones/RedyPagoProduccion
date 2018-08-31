<?php

namespace app\models\base;

use Yii;
use app\models\Empresas;
use app\models\Notificaciones;
use app\models\Pedidos;

/**
 * This is the model class for table "clientes".
*
    * @property integer $id
    * @property integer $empresa_id
    * @property string $identificacion
    * @property string $telefonos
    * @property string $direccion
    * @property string $email
    * @property string $nombres
    * @property string $apellidos
    * @property string $idioma
    *
            * @property Empresas $empresa
            * @property Notificaciones[] $notificaciones
            * @property Pedidos[] $pedidos
    */
class ClientesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'clientes';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['empresa_id', 'identificacion', 'telefonos', 'direccion', 'email', 'nombres', 'apellidos', 'idioma'], 'required'],
            [['empresa_id'], 'integer'],
            [['direccion', 'idioma'], 'string'],
            [['identificacion'], 'string', 'max' => 30],
            [['telefonos'], 'string', 'max' => 45],
            [['email'], 'string', 'max' => 850],
            [['nombres', 'apellidos'], 'string', 'max' => 700],
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
    'empresa_id' => 'Empresa ID',
    'identificacion' => 'Identificacion',
    'telefonos' => 'Telefonos',
    'direccion' => 'Direccion',
    'email' => 'Email',
    'nombres' => 'Nombres',
    'apellidos' => 'Apellidos',
    'idioma' => 'Idioma',
];
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
    public function getNotificaciones()
    {
    return $this->hasMany(Notificaciones::className(), ['cliente_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPedidos()
    {
    return $this->hasMany(Pedidos::className(), ['cliente_id' => 'id']);
    }
}