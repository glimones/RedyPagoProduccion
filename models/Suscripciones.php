<?php

namespace app\models;
use Yii;

class Suscripciones extends \app\models\base\SuscripcionesBase
{

	public $tipo_empresa; //persona natural o compañias
   
    //Persona natural
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

    public function rules()
    {
        return [
            // username and password are both required
            [['cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'], 'required', 'message' => 'El documento {attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_NATURAL],
            [['nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'], 'required', 'message' => 'El documento {attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_COMPANIA],
            ['email', 'email'],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_NATURAL => ['cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'],
            self::SCENARIO_COMPANIA => [  'nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'],
        ];
    }

    public function attributeLabels()
	{
		return [
		    'id' => Yii::t('app', 'ID'),
		    'empresa_id' => Yii::t('app', 'Empresa ID'),
		    'fecha' => Yii::t('app', 'Fecha Caducidad de licencia'),
		];
	}
}