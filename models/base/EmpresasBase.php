<?php

namespace app\models\base;

use Yii;
use app\models\Categorias;
use app\models\Clientes;
use app\models\Contactos;
use app\models\Adquirentes;
use app\models\Ciudades;
use app\models\Procesamientos;
use app\models\Formularios;
use app\models\Marcas;
use app\models\Notificaciones;
use app\models\Pedidos;
use app\models\Productos;
use app\models\Suscripciones;
use app\models\Usuarios;

/**
 * This is the model class for table "empresas".
*
    * @property integer $id
    * @property string $ruc
    * @property string $razon_social
    * @property string $logo
    * @property string $contacto_cedula
    * @property string $contacto_nombres
    * @property string $contacto_apellidos
    * @property integer $ciudad_id
    * @property string $direccion
    * @property string $email
    * @property string $actividades
    * @property string $url_comercio_electronico
    * @property string $fecha_afiliacion
    * @property string $id_commerce
    * @property string $id_acquirer
    * @property string $id_wallet
    * @property string $llave_vpos
    * @property string $llave_wallet
    * @property string $ambiente
    * @property integer $estado
    * @property string $logo_payme
    * @property string $aplica_iva
    * @property string $habilitar_diferidos
    * @property integer $facturacion_electronica
    * @property string $facturacion_usuario
    * @property string $facturacion_clave
    * @property string $nombre_comercial
    * @property string $obligado_llevar_contabilidad
    * @property string $establecimiento
    * @property integer $facturacion_ambiente
    * @property integer $adquirente_id
    * @property integer $procesamiento_id
    * @property string $vector
    * @property string $alignet_publica_cifrado_rsa
    * @property string $alignet_publica_firma_rsa
    * @property string $llave_privada_cifrado_rsa
    * @property string $llave_privada_firma_rsa
    * @property string $monto_maximo_por_transaccion
    *
            * @property Categorias[] $categorias
            * @property Clientes[] $clientes
            * @property Contactos[] $contactos
            * @property Adquirentes $adquirente
            * @property Ciudades $ciudad
            * @property Procesamientos $procesamiento
            * @property Formularios[] $formularios
            * @property Marcas[] $marcas
            * @property Notificaciones[] $notificaciones
            * @property Pedidos[] $pedidos
            * @property Productos[] $productos
            * @property Suscripciones[] $suscripciones
            * @property Usuarios[] $usuarios
    */
class EmpresasBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'empresas';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['ruc', 'razon_social', 'logo', 'contacto_cedula', 'contacto_nombres', 'contacto_apellidos', 'ciudad_id', 'direccion', 'email', 'actividades', 'fecha_afiliacion'], 'required'],
            [['ciudad_id', 'facturacion_electronica', 'facturacion_ambiente', 'adquirente_id', 'procesamiento_id'], 'integer'],
            [['direccion', 'actividades', 'ambiente', 'aplica_iva', 'habilitar_diferidos', 'obligado_llevar_contabilidad', 'alignet_publica_cifrado_rsa', 'alignet_publica_firma_rsa', 'llave_privada_cifrado_rsa', 'llave_privada_firma_rsa'], 'string'],
            [['fecha_afiliacion'], 'safe'],
            [['monto_maximo_por_transaccion'], 'number'],
            [['ruc'], 'string', 'max' => 13],
            [['razon_social', 'email', 'url_comercio_electronico', 'facturacion_usuario', 'facturacion_clave'], 'string', 'max' => 450],
            [['logo', 'logo_payme'], 'string', 'max' => 2000],
            [['contacto_cedula', 'id_acquirer'], 'string', 'max' => 10],
            [['contacto_nombres', 'contacto_apellidos'], 'string', 'max' => 250],
            [['id_commerce', 'vector'], 'string', 'max' => 20],
            [['id_wallet'], 'string', 'max' => 45],
            [['llave_vpos', 'llave_wallet'], 'string', 'max' => 300],
            [['estado'], 'string', 'max' => 4],
            [['nombre_comercial'], 'string', 'max' => 1000],
            [['establecimiento'], 'string', 'max' => 3],
            [['adquirente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adquirentes::className(), 'targetAttribute' => ['adquirente_id' => 'id']],
            [['ciudad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudades::className(), 'targetAttribute' => ['ciudad_id' => 'id']],
            [['procesamiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Procesamientos::className(), 'targetAttribute' => ['procesamiento_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'ruc' => 'Ruc',
    'razon_social' => 'Razon Social',
    'logo' => 'Logo',
    'contacto_cedula' => 'Contacto Cedula',
    'contacto_nombres' => 'Contacto Nombres',
    'contacto_apellidos' => 'Contacto Apellidos',
    'ciudad_id' => 'Ciudad ID',
    'direccion' => 'Direccion',
    'email' => 'Email',
    'actividades' => 'Actividades',
    'url_comercio_electronico' => 'Url Comercio Electronico',
    'fecha_afiliacion' => 'Fecha Afiliacion',
    'id_commerce' => 'Id Commerce',
    'id_acquirer' => 'Id Acquirer',
    'id_wallet' => 'Id Wallet',
    'llave_vpos' => 'Llave Vpos',
    'llave_wallet' => 'Llave Wallet',
    'ambiente' => 'Ambiente',
    'estado' => 'Estado',
    'logo_payme' => 'Logo Payme',
    'aplica_iva' => 'Aplica Iva',
    'habilitar_diferidos' => 'Habilitar Diferidos',
    'facturacion_electronica' => 'Facturacion Electronica',
    'facturacion_usuario' => 'Facturacion Usuario',
    'facturacion_clave' => 'Facturacion Clave',
    'nombre_comercial' => 'Nombre Comercial',
    'obligado_llevar_contabilidad' => 'Obligado Llevar Contabilidad',
    'establecimiento' => 'Establecimiento',
    'facturacion_ambiente' => 'Facturacion Ambiente',
    'adquirente_id' => 'Adquirente ID',
    'procesamiento_id' => 'Procesamiento ID',
    'vector' => 'Vector',
    'alignet_publica_cifrado_rsa' => 'Alignet Publica Cifrado Rsa',
    'alignet_publica_firma_rsa' => 'Alignet Publica Firma Rsa',
    'llave_privada_cifrado_rsa' => 'Llave Privada Cifrado Rsa',
    'llave_privada_firma_rsa' => 'Llave Privada Firma Rsa',
    'monto_maximo_por_transaccion' => 'Monto Maximo Por Transaccion',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCategorias()
    {
    return $this->hasMany(Categorias::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getClientes()
    {
    return $this->hasMany(Clientes::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContactos()
    {
    return $this->hasMany(Contactos::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAdquirente()
    {
    return $this->hasOne(Adquirentes::className(), ['id' => 'adquirente_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCiudad()
    {
    return $this->hasOne(Ciudades::className(), ['id' => 'ciudad_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProcesamiento()
    {
    return $this->hasOne(Procesamientos::className(), ['id' => 'procesamiento_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFormularios()
    {
    return $this->hasMany(Formularios::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMarcas()
    {
    return $this->hasMany(Marcas::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNotificaciones()
    {
    return $this->hasMany(Notificaciones::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPedidos()
    {
    return $this->hasMany(Pedidos::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProductos()
    {
    return $this->hasMany(Productos::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSuscripciones()
    {
    return $this->hasMany(Suscripciones::className(), ['empresa_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUsuarios()
    {
    return $this->hasMany(Usuarios::className(), ['empresa_id' => 'id']);
    }
}