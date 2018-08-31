<?php

namespace app\models\base;

use Yii;
use app\models\AdquirentesProcesamientos;
use app\models\Empresas;

/**
 * This is the model class for table "procesamientos".
*
    * @property integer $id
    * @property string $nombre
    *
            * @property AdquirentesProcesamientos[] $adquirentesProcesamientos
            * @property Empresas[] $empresas
    */
class ProcesamientosBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'procesamientos';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 45],
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
    public function getAdquirentesProcesamientos()
    {
    return $this->hasMany(AdquirentesProcesamientos::className(), ['procesamiento_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresas()
    {
    return $this->hasMany(Empresas::className(), ['procesamiento_id' => 'id']);
    }
}