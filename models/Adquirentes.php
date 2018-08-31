<?php

namespace app\models;

class Adquirentes extends \app\models\base\AdquirentesBase
{
    public $procesamiento;

	public function rules()
    {
        return array_merge(parent::rules(),
        [
        	[['procesamiento', 'codigo', 'codigo_testing'], 'required'],
        	['codigo', 'unique'],
        ]);
    }

    public function attributeLabels()
	{
		return [
		    'id' => 'ID',
		    'nombre' => 'Nombre',
		    'codigo' => 'ID producción',
		    'codigo_testing' => 'ID Testing',
		];
	}
}