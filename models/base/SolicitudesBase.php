<?php

namespace app\models\base;

use Yii;
use app\models\Ciudades;

/**
 * This is the model class for table "solicitudes".
*
    * @property integer $id
    * @property string $ruc
    * @property string $razon_social
    * @property string $contacto_cedula
    * @property string $contacto_nombres
    * @property string $contacto_apellidos
    * @property string $direccion
    * @property string $email
    * @property string $actividades
    * @property integer $ciudad_id
    * @property string $fecha_solicitud
    * @property string $estado
    *
            * @property Ciudades $ciudad
    */
class SolicitudesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'solicitudes';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['direccion', 'actividades', 'estado'], 'string'],
            [['ciudad_id'], 'integer'],
            [['fecha_solicitud'], 'safe'],
            [['ruc', 'contacto_cedula', 'contacto_nombres', 'contacto_apellidos'], 'string', 'max' => 45],
            [['razon_social', 'email'], 'string', 'max' => 450],
            [['ciudad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudades::className(), 'targetAttribute' => ['ciudad_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'ruc' => 'Ruc',
    'razon_social' => 'Razon Social',
    'contacto_cedula' => 'Contacto Cedula',
    'contacto_nombres' => 'Contacto Nombres',
    'contacto_apellidos' => 'Contacto Apellidos',
    'direccion' => 'Direccion',
    'email' => 'Email',
    'actividades' => 'Actividades',
    'ciudad_id' => 'Ciudad ID',
    'fecha_solicitud' => 'Fecha Solicitud',
    'estado' => 'Estado',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCiudad()
    {
    return $this->hasOne(Ciudades::className(), ['id' => 'ciudad_id']);
    }
}