<?php

namespace app\models\base;

use Yii;
use app\models\AdquirentesProcesamientos;
use app\models\Empresas;

/**
 * This is the model class for table "adquirentes".
*
    * @property integer $id
    * @property string $nombre
    * @property string $codigo
    * @property string $codigo_testing
    *
            * @property AdquirentesProcesamientos[] $adquirentesProcesamientos
            * @property Empresas[] $empresas
    */
class AdquirentesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'adquirentes';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 800],
            [['codigo', 'codigo_testing'], 'string', 'max' => 45],
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
    'codigo' => 'Codigo',
    'codigo_testing' => 'Codigo Testing',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAdquirentesProcesamientos()
    {
    return $this->hasMany(AdquirentesProcesamientos::className(), ['adquirente_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresas()
    {
    return $this->hasMany(Empresas::className(), ['adquirente_id' => 'id']);
    }
}