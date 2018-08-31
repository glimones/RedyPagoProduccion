<?php

namespace app\models;

class TransaccionesDescomplicate extends \app\models\base\TransaccionesDescomplicateBase
{
    public function attributeLabels()
	{
		return [
		    'id' => 'ID',
		    'cedula' => 'Cedula',
		    'idtransaccion' => 'Idtransaccion',
		    'valor' => 'Valor',
		    'fecha' => 'Fecha',
		    'estado' => 'Estado',
		    'mensaje_descomplicate' => 'Mensaje Descomplicate',
		    'cod_descomplicate' => 'Autorización',
		];
	}
}