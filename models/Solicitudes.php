<?php

namespace app\models;
use yii\base\Model;
use yii\web\UploadedFile;

class Solicitudes extends \app\models\base\SolicitudesBase
{
    public $tipo_empresa; //persona natural o compañias
   
    //Persona natural
    public $ruc_documento;
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
            [['ruc', 'razon_social', 'actividades', 'direccion', 'ciudad_id', 'contacto_cedula', 'contacto_nombres', 'contacto_apellidos', 'email', 'ruc_documento', 'cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'], 'required', 'message' => '{attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_NATURAL],
            [['ruc_documento', 'nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'], 'required', 'message' => 'El documento {attribute} es requerido para la solicitud de afiliación', 'on' => self::SCENARIO_COMPANIA],
            ['email', 'email'],
            ['email', 'unique'],
            ['ruc', 'unique'],
            ['ruc', 'checkRUC'],
            ['contacto_cedula', 'checkRUC'],
            [['cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
        ];
    }

    public function attributeLabels()
	{
	return [
	    'id' => 'ID',
	    'ruc' => 'Número de Ruc',
	    'razon_social' => 'Razón Social',
	    'contacto_cedula' => 'Cédula',
	    'contacto_nombres' => 'Nombres',
	    'contacto_apellidos' => 'Apellidos',
	    'direccion' => 'Dirección',
	    'email' => 'Email',
	    'actividades' => 'Actividad Comercial',
	    'ciudad_id' => 'Ciudad',
	    'fecha_solicitud' => 'Fecha Solicitud',
	    'estado' => 'Estado',
	];
	}

    public function checkRUC($attributes, $params)
    {
        $numero = $this -> cedula;
        $suma = 0;
        $residuo = 0;
        $pri = false;
        $pub = false;
        $nat = false;
        $numeroProvincias = 24;
        $modulo = 11;

        /* Verifico que el campo no contenga letras */
        if(!(substr($numero, 0, 2) > 0 && substr($numero, 0, 2) <= $numeroProvincias))
            $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');

        /* Aqui almacenamos los digitos de la cedula en variables. */
        $d1 = substr($numero, 0,1);
        $d2 = substr($numero, 1,1);
        $d3 = substr($numero, 2,1);
        $d4 = substr($numero, 3,1);
        $d5 = substr($numero, 4,1);
        $d6 = substr($numero, 5,1);
        $d7 = substr($numero, 6,1);
        $d8 = substr($numero, 7,1);
        $d9 = substr($numero, 8,1);
        $d10 = substr($numero, 9,1);

        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */

        if($d3==7 || $d3==8)
        {
            $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
        }

        /* Solo para personas naturales (modulo 10) */
        if ($d3 < 6){
            $nat = true;
            $p1 = $d1 * 2; if ($p1 >= 10) $p1 -= 9;
            $p2 = $d2 * 1; if ($p2 >= 10) $p2 -= 9;
            $p3 = $d3 * 2; if ($p3 >= 10) $p3 -= 9;
            $p4 = $d4 * 1; if ($p4 >= 10) $p4 -= 9;
            $p5 = $d5 * 2; if ($p5 >= 10) $p5 -= 9;
            $p6 = $d6 * 1; if ($p6 >= 10) $p6 -= 9;
            $p7 = $d7 * 2; if ($p7 >= 10) $p7 -= 9;
            $p8 = $d8 * 1; if ($p8 >= 10) $p8 -= 9;
            $p9 = $d9 * 2; if ($p9 >= 10) $p9 -= 9;
            $modulo = 10;
        }

        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else if($d3 == 6)
        {
            $pub = true;
            $p1 = $d1 * 3;
            $p2 = $d2 * 2;
            $p3 = $d3 * 7;
            $p4 = $d4 * 6;
            $p5 = $d5 * 5;
            $p6 = $d6 * 4;
            $p7 = $d7 * 3;
            $p8 = $d8 * 2;
            $p9 = 0;
        }

        /* Solo para entidades privadas (modulo 11) */
        else if($d3 == 9)
        {
            $pri = true;
            $p1 = $d1 * 4;
            $p2 = $d2 * 3;
            $p3 = $d3 * 2;
            $p4 = $d4 * 7;
            $p5 = $d5 * 6;
            $p6 = $d6 * 5;
            $p7 = $d7 * 4;
            $p8 = $d8 * 3;
            $p9 = $d9 * 2;
        }

        $suma = $p1 + $p2 + $p3 + $p4 + $p5 + $p6 + $p7 + $p8 + $p9;
        $residuo = $suma % $modulo;

        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
        $digitoVerificador = ($residuo==0)? 0 : ($modulo - $residuo);

        /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
        if($pub==true)
        {
            if($digitoVerificador != $d9)
            {
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
            /* El ruc de las empresas del sector publico terminan con 0001*/
            if( substr($numero, 9, 4) < 1 )
            {
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
        }
        else if($pri == true)
        {
            if($digitoVerificador != $d10)
            {
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
            if( substr($numero, 10, 3) < 1 ){
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
        }
        else if($nat == true)
        {
            if ($digitoVerificador != $d10)
            {
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
            if (strlen($numero) > 10 && substr($numero, 10, 3) < 1 )
            {
                $this->addError('cedula','N&uacute;mero de Identificaci&oacute;n Incorrecto');
            }
        }
    }

    // public function scenarios()
    // {
    //     return [
    //         self::SCENARIO_NATURAL => ['cedula', 'papeleta_votacion', 'patente_municipal', 'contrato_arrendamiento', 'formulario_102', 'sevicio_basico', 'certificado_distribuidores_celulares'],
    //         self::SCENARIO_COMPANIA => [  'nombramiento_representante_legal', 'cedula', 'papeleta_votacion', 'constitucion_compania', 'patente_municipal', 'contrato_arrendamiento', 'formulario_101', 'sevicio_basico', 'certificado_distribuidores_celulares', 'referencia_bancaria', 'cumplimiento_obligaciones', 'certificado_iata'],
    //     ];
    // }
}