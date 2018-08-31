<?php

namespace app\models\base;

use Yii;
use app\models\Adquirentes;
use app\models\Procesamientos;

/**
 * This is the model class for table "adquirentes_procesamientos".
*
    * @property integer $id
    * @property integer $adquirente_id
    * @property integer $procesamiento_id
    *
            * @property Adquirentes $adquirente
            * @property Procesamientos $procesamiento
    */
class AdquirentesProcesamientosBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'adquirentes_procesamientos';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['adquirente_id', 'procesamiento_id'], 'required'],
            [['adquirente_id', 'procesamiento_id'], 'integer'],
            [['adquirente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adquirentes::className(), 'targetAttribute' => ['adquirente_id' => 'id']],
            [['procesamiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Procesamientos::className(), 'targetAttribute' => ['procesamiento_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'adquirente_id' => 'Adquirente ID',
    'procesamiento_id' => 'Procesamiento ID',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAdquirente()
    {
    return $this->hasOne(Adquirentes::className(), ['id' => 'adquirente_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProcesamiento()
    {
    return $this->hasOne(Procesamientos::className(), ['id' => 'procesamiento_id']);
    }
}