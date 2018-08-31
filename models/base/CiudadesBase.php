<?php

namespace app\models\base;

use Yii;
use app\models\Empresas;
use app\models\Solicitudes;

/**
 * This is the model class for table "ciudades".
*
    * @property integer $id
    * @property string $nombre
    *
            * @property Empresas[] $empresas
            * @property Solicitudes[] $solicitudes
    */
class CiudadesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'ciudades';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 250],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'nombre' => 'Nombre',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresas()
    {
    return $this->hasMany(Empresas::className(), ['ciudad_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSolicitudes()
    {
    return $this->hasMany(Solicitudes::className(), ['ciudad_id' => 'id']);
    }
}