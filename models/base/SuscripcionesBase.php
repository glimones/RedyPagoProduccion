<?php

namespace app\models\base;

use Yii;
use app\models\Empresas;

/**
 * This is the model class for table "suscripciones".
*
    * @property integer $id
    * @property integer $empresa_id
    * @property string $fecha
    *
            * @property Empresas $empresa
    */
class SuscripcionesBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'suscripciones';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['empresa_id', 'fecha'], 'required'],
            [['empresa_id'], 'integer'],
            [['fecha'], 'safe'],
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::className(), 'targetAttribute' => ['empresa_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('app', 'ID'),
    'empresa_id' => Yii::t('app', 'Empresa ID'),
    'fecha' => Yii::t('app', 'Fecha'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresa()
    {
    return $this->hasOne(Empresas::className(), ['id' => 'empresa_id']);
    }
}