<?php

namespace app\models\base;

use Yii;
use app\models\Empresas;

/**
 * This is the model class for table "contactos".
*
    * @property integer $id
    * @property integer $empresa_id
    * @property string $nombres
    * @property string $apellidos
    * @property string $telefono
    * @property string $email
    * @property string $tipo
    *
            * @property Empresas $empresa
    */
class ContactosBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'contactos';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['empresa_id', 'nombres', 'apellidos', 'telefono', 'email', 'tipo'], 'required'],
            [['empresa_id'], 'integer'],
            [['tipo'], 'string'],
            [['nombres', 'apellidos', 'email'], 'string', 'max' => 450],
            [['telefono'], 'string', 'max' => 200],
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
    'nombres' => Yii::t('app', 'Nombres'),
    'apellidos' => Yii::t('app', 'Apellidos'),
    'telefono' => Yii::t('app', 'Telefono'),
    'email' => Yii::t('app', 'Email'),
    'tipo' => Yii::t('app', 'Tipo'),
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