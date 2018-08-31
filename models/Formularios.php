<?php

namespace app\models;

class Formularios extends \app\models\base\FormulariosBase
{

    public function rules()
    {
        $rules[] = ['$iva', 'number', 'min' => 0, 'max' => 99999999];

        return array_merge(parent::rules(),
	    [
            // [['a_pagar', 'descripcion'], 'required'],
        ]);
    }

    public function attributeLabels()
	{
		return [
		    'id' => 'ID',
		    'descripcion' => 'Descripción del pago',
		    'fecha' => 'Fecha',
            'precio' => 'Monto en USD',
		    'token' => 'Url Formulario',
		    'empresa_id' => 'Empresa ID',
		    'idioma' => 'Idioma',
            'iva' => 'IVA',
            'usuario_id'=>'Usuario_id',
           // 'base12' => 'Base12',
            //'base0' => 'Base0',


		];
	}


}