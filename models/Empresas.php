<?php

namespace app\models;
use Yii;

class Empresas extends \app\models\base\EmpresasBase
{
	public $comp_logo_pagomedios;
	public $comp_logo_payme;

	public function rules()
    {
        return array_merge(parent::rules(),
        [
        	[['adquirente_id', 'procesamiento_id'], 'required'],
        	['ruc', 'unique'],
        ]);
    }

    public function attributeLabels()
	{
		return [
		    'id' => Yii::t('app', 'ID'),
		    'ruc' => Yii::t('app', 'Ruc'),
		    'razon_social' => Yii::t('app', 'Razon Social'),
		    'comp_logo_pagomedios' => Yii::t('app', 'Subir nuevo (300 x 300 pixeles)'),
		    'contacto_cedula' => Yii::t('app', 'Identificación'),
		    'contacto_nombres' => Yii::t('app', 'Nombres'),
		    'contacto_apellidos' => Yii::t('app', 'Apellidos'),
		    'ciudad_id' => Yii::t('app', 'Ciudad'),
		    'direccion' => Yii::t('app', 'Dirección'),
		    'email' => Yii::t('app', 'Email'),
		    'actividades' => Yii::t('app', 'Actividades'),
		    'url_comercio_electronico' => Yii::t('app', 'Url Comercio'),
		    'fecha_afiliacion' => Yii::t('app', 'Fecha Afiliación'),
		    'id_commerce' => Yii::t('app', 'Código de comercio'),
		    'id_acquirer' => Yii::t('app', 'Id Acquirer'),
		    'id_wallet' => Yii::t('app', 'Id Wallet'),
		    'llave_vpos' => Yii::t('app', 'Llave Vpos'),
		    'llave_wallet' => Yii::t('app', 'Llave Wallet'),
		    'ambiente' => Yii::t('app', 'Ambiente'),
		    'estado' => Yii::t('app', 'Estado'),
		    'logo_payme' => Yii::t('app', 'Logo Alignet (225 x 55 pixeles max 80kb)'),
		    'logo' => Yii::t('app', 'Logo Comercio (300 x 300 pixeles)'),
		    'facturacion_electronica' => 'Activar',
		    'facturacion_usuario' => 'Usuario',
		    'facturacion_clave' => 'Clave',
		    'facturacion_ambiente' => 'Ambiente',
		    'adquirente_id' => 'Adquirente',
		    'procesamiento_id' => 'Procesamiento',
		    'monto_maximo_por_transaccion' => 'Monto Máximo por transacción',
		];
	}
    
}