<?php

namespace app\models\base;

use app\models\Usuarios;
use Yii;
use app\models\Pedidos;
use app\models\Empresas;

/**
 * This is the model class for table "usuarios".
*
    * @property integer $id
    * @property integer $empresa_id
    * @property string $nombres
    * @property string $apellidos
    * @property string $email
    * @property string $clave
    * @property integer $estado
    * @property string $token
    * @property integer $es_super
    * @property integer $es_admin
    * @property string $fecha_creacion
    * @property string $es_user
    *
            * @property Pedidos[] $pedidos
            * @property Empresas $empresa
    */
class usuariosBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'usuarios';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['empresa_id', 'estado', 'es_super', 'es_admin'], 'integer'],
            [['email', 'clave'], 'required'],
            [['token'], 'string'],
            [['fecha_creacion'], 'safe'],
            [['nombres', 'apellidos','es_user'], 'string', 'max' => 200],
            [['email', 'clave'], 'string', 'max' => 450],
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
    'nombres' => 'Nombres',
    'apellidos' => 'Apellidos',
    'email' => 'Email',
    'clave' => 'Clave',
    'estado' => 'Estado',
    'token' => 'Token',
    'es_super' => 'Es Super',
    'es_admin' => 'Es Admin',
    'fecha_creacion' => 'Fecha Creacion',
    'es_user' => 'Usuario',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPedidos()
    {
    return $this->hasMany(Pedidos::className(), ['usuario_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresa()
    {
    return $this->hasOne(Empresas::className(), ['id' => 'empresa_id']);
    }
}