<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PagosForm extends Model
{
    public $identificacion;
    public $nombres;
    public $apellidos;
    public $email;
    public $telefonos;
    public $direccion;
    public $commerce_id;
    public $form_descripcion;
    public $form_total;
    public $form_iva;
    public $form_base12;
    public $form_base0;
 //   public static $base12=0;
   // public static $base0=0;

    const SCENARIO_FORMWEB = 'form_web';
    const SCENARIO_COBRODIRECTO = 'cobro_directo';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge(parent::rules(),
     [
         ['form_base12', 'validateBase','on'=> self::SCENARIO_COBRODIRECTO],
         ['form_base12', 'default', 'value' => '0.00','on' => self::SCENARIO_COBRODIRECTO],
         ['form_base0', 'default', 'value' => '0.00','on' => self::SCENARIO_COBRODIRECTO],
         ['form_total', 'default', 'value' => '0.00','on' => self::SCENARIO_COBRODIRECTO],
         ['form_iva', 'default', 'value' => '0.00','on' => self::SCENARIO_COBRODIRECTO],

         [['identificacion'], 'string', 'max' => 13, 'tooLong' => 'Su identificación no consta con la longitud de caracteres permitida','on' => self::SCENARIO_COBRODIRECTO],
         [['identificacion', 'nombres', 'apellidos', 'email', 'telefonos', 'direccion'], 'required', 'message' => 'El campo {attribute} es requerido', 'on' => self::SCENARIO_FORMWEB],
         [['identificacion', 'nombres','apellidos', 'email', 'form_descripcion', 'form_total','form_iva'], 'required', 'message' => 'El campo {attribute} es requerido', 'on' => self::SCENARIO_COBRODIRECTO],
         ['email', 'email'],
     ]);

    }

     public function attributeLabels()
    {
        return [
            'identificacion' => Yii::t('app', 'Identificación'),
            'nombres' => Yii::t('app', 'nombres'),
            'telefonos' => Yii::t('app', 'Teléfonos'),
            'direccion' => Yii::t('app', 'Dirección'),
            'form_descripcion' => Yii::t('app', 'Motivo del cobro'),
            'form_total' => Yii::t('app', 'Total'),
            'form_iva' => Yii::t('app', 'IVA'),
            'form_base12' => Yii::t('app', 'Monto base 12'),
            'form_base0' => Yii::t('app', 'Monto base 0'),

        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_FORMWEB => ['identificacion', 'nombres', 'apellidos', 'email', 'telefonos', 'direccion'],
            self::SCENARIO_COBRODIRECTO => [ 'identificacion', 'nombres', 'apellidos', 'email', 'form_descripcion', 'form_total','form_iva','form_base12','form_base0'],
        ];
    }

    public function validateBase($attribute)
    {
      $this->addError($attribute, "Cedula Incorrecta");
    }

}
