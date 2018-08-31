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
class PasarelaForm extends Model
{
    public $tipo_tarjeta;
    public $numero_tarjeta;
    public $codigo_seguridad;
    public $fecha_expiracion_mes;
    public $fecha_expiracion_anio;

    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['tipo_tarjeta', 'numero_tarjeta', 'codigo_seguridad', 'fecha_expiracion_mes', 'fecha_expiracion_anio'], 'required'],
        ];
    }


}
