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
class SolicitudregistroForm extends Model
{
    public $tipo_empresa; //persona natural o compañias
    public $nombre_comercio;
    public $numero_ruc;
    public $email;

    //Persona natural
    public $ruc;
    public $cedula;
    public $papeleta_votacion;
    public $patente_municipal;
    public $contrato_arrendamiento;
    public $formulario_102;
    public $sevicio_basico;
    public $certificado_distribuidores_celulares;

    //Compañias
    public $nombramiento_representante_legal;
    public $constitucion_compania;
    public $formulario_101;
    public $referencia_bancaria;
    public $cumplimiento_obligaciones;
    public $certificado_iata;


    const SCENARIO_NATURAL = 'persona_natural';
    const SCENARIO_COMPANIA = 'companias';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['ruc', 'cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'], 'required', 'message' => 'El documento {attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_NATURAL],
            [['ruc', 'nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'], 'required', 'message' => 'El documento {attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_COMPANIA],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            // 'identificacion' => Yii::t('app', 'Identificación'),
            // 'telefonos' => Yii::t('app', 'Teléfonos'),
            // 'direccion' => Yii::t('app', 'Dirección'),
            // 'form_descripcion' => Yii::t('app', 'Motivo del cobro'),
            // 'form_total' => Yii::t('app', 'Monto a cobrar'),
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_NATURAL => ['ruc', 'cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'],
            self::SCENARIO_COMPANIA => [ 'ruc', 'nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'],
        ];
    }

}
