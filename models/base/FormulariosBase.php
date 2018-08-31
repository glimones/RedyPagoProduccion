<?php

namespace app\models\base;

use Yii;
use app\models\Empresas;

/**
 * This is the model class for table "formularios".
*
    * @property integer $id
    * @property string $descripcion
    * @property string $fecha
    * @property string $precio
    * @property string $token
    * @property integer $empresa_id
    * @property string $idioma
    * @property string $iva
    * @property string $usuario_id
    * @property string $base12
    * @property string $base0

    *
            * @property Empresas $empresa
    */
class FormulariosBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'formularios';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            ['base12', 'default', 'value' => '0.00'],
            ['base0', 'default', 'value' => '0.00'],
            ['iva', 'default', 'value' => '0.00'],
            [['descripcion', 'precio', 'empresa_id', 'idioma'], 'required'],
            [['fecha'], 'safe'],
            //[['precio'], 'number'],
            [['token', 'idioma'], 'string'],
            [['empresa_id'], 'integer'],
            [['descripcion'], 'string', 'max' => 2000],
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
    'descripcion' => 'Descripcion',
    'fecha' => 'Fecha',
    'precio' => 'Precio',
    'token' => 'Token',
    'empresa_id' => 'Empresa ID',
    'idioma' => 'Idioma',
    'iva'=>'Iva',
    'usuario_id'=>'Usuario_id',
    'base12'=>'Base12',
    'base0'=>'Base0',

];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmpresa()
    {
    return $this->hasOne(Empresas::className(), ['id' => 'empresa_id']);
    }
    public function getUsuario()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'usuario_id']);
    }
}