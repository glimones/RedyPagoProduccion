<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "localizaciones".
*
    * @property integer $id
    * @property double $lat
    * @property double $lon
    * @property string $nombre
    * @property string $direccion
*/
class LocalizacionesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'localizaciones';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['lat', 'lon'], 'number'],
            [['direccion'], 'string'],
            [['nombre'], 'string', 'max' => 450],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'lat' => 'Lat',
    'lon' => 'Lon',
    'nombre' => 'Nombre',
    'direccion' => 'Direccion',
];
}
}