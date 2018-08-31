<?php 
namespace app\controllers;
 
use app\models\Usuarios;
use Yii;
use app\models\Pedidos;
use app\models\Empresas;
use app\models\Clientes;
use app\models\Util;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Url;
use app\models\PedidosOperaciones;

class ApiController extends Controller
{
 
    public function behaviors()
    {
    return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
            'setorder'=>['get'],
            'getorder'=>['get'],
            'setform'=>['get'],
        ],
 
        ]
    ];
    }
 
 
    public function beforeAction($event)
    {
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $action = $event->id;
    if (isset($this->actions[$action])) {
        $verbs = $this->actions[$action];
    } elseif (isset($this->actions['*'])) {
        $verbs = $this->actions['*'];
    } else {
        return $event->isValid;
    }
    $verb = Yii::$app->getRequest()->getMethod();
 
      $allowed = array_map('strtoupper', $verbs);
 
      if (!in_array($verb, $allowed)) {
 
        $this->setHeader(400);
        return array('status'=>0,'error_code'=>400,'message'=>'Método no encontrado');
        exit;
 
    }  
 
      return true;  
    }
 

    public function actionSetorder( $commerce_id="", 
                                    $customer_id="", 
                                    $customer_name="", 
                                    $customer_lastname="", 
                                    $customer_phones="", 
                                    $customer_address="", 
                                    $customer_email="", 
                                    $customer_language="", 
                                    $order_description = "",
                                    $order_tarifa ="", //Monto base 12 gli
                                    $order_basecero ="", //Monto base 0 gli
                                    $taxMontoIVA ="", //Monto iva gli
                                    $order_amount = "", 
                                    $order_id = "",
                                    $usuario_inserta="",
                                    $response_url = ""
                                    )
    {
      $arrayOrden = array();
      $comercio = Empresas::findOne(['id_commerce' => $commerce_id, 'estado' => 1]);
      if( is_object($comercio) ){
        $cliente = Clientes::findOne(['identificacion' => $customer_id, 'empresa_id'=>$comercio->id]);

          if( is_object( $cliente ) ){
            if( !empty( $customer_name ) && !empty( $customer_lastname ) && !empty( $customer_phones ) && !empty( $customer_address ) && !empty( $customer_email ) && !empty( $customer_language ) && !empty( $order_description ) && !empty( $order_amount ) ){
                    $pedido = $this->setPedidoUser($cliente->id, $comercio->id , $order_description, $order_amount, $response_url, $order_id,$order_tarifa,$order_basecero,$taxMontoIVA,$usuario_inserta);

            if( is_object( $pedido ) ){
              
              $arrayOrden['customer_id'] = $pedido->cliente->identificacion;
              $arrayOrden['order_id'] = $pedido->numero_pedido;
              $arrayOrden['payment_url'] = Url::to(['payments/t?token='.$pedido->token], true);
              $this->setHeader(200);
              return array('status'=>1,'data'=>$arrayOrden);  
            }else{
              $this->setHeader(400);
              return array('status'=>0,'error_code'=>400,'message'=>'No se pudo generar pedido');
            }
          }else{
            $this->setHeader(400);
            return array('status'=>0,'error_code'=>400,'message'=>'Es necesario el envio de todos los parámetros cliente');
          }
        }else{
          if( !empty( $customer_name ) && !empty( $customer_lastname ) && !empty( $customer_phones ) && !empty( $customer_address ) && !empty( $customer_email ) && !empty( $customer_language ) && !empty( $order_description ) && !empty( $order_amount ) ){
            $cliente = new Clientes();
            $cliente->empresa_id = $comercio->id;
            $cliente->identificacion = $customer_id;
            $cliente->telefonos = $customer_phones;
            $cliente->direccion = $customer_address;
            $cliente->email = $customer_email;
            $cliente->nombres = $customer_name;
            $cliente->apellidos = $customer_lastname;
            $cliente->idioma = ( $customer_language == 'es' ) ? 'Español' : 'Inglés';
            if( $cliente->save() ){
              //$pedido = $this->setPedido($cliente->id, $comercio->id, $order_description, $order_amount, $response_url, $order_id);
                $pedido = $this->setPedidoUser($cliente->id, $comercio->id , $order_description, $order_amount, $response_url, $order_id,$order_tarifa,$order_basecero,$taxMontoIVA,$usuario_inserta);
                if( is_object( $pedido ) ){
                
                $arrayOrden['customer_id'] = $pedido->cliente->identificacion;
                $arrayOrden['order_id'] = $pedido->numero_pedido;
                $arrayOrden['payment_url'] = Url::to(['payments/t?token='.$pedido->token], true);
                $this->setHeader(200);
                return array('status'=>1,'data'=>$arrayOrden);  
              }else{
                $this->setHeader(400);
                return array('status'=>0,'error_code'=>400,'message'=>'No se pudo generar pedido');
              }
            }else{
              $this->setHeader(400);
              return array('status'=>0,'error_code'=>400,'message'=>'No se pudo generar cliente');
            }
          }else{
            $this->setHeader(400);
            return array('status'=>0,'error_code'=>400,'message'=>'Cliente no existente, es necesario el envio de todos los parámetros cliente');
          }
        }
      }else{
        $this->setHeader(400);
        return array('status'=>0,'error_code'=>400,'message'=>'Parámetro commerce_id incorrecto. Comercio no encontrado o inactivo, contáctese con soporte Pagomedios');
      } 
    }

    public function actionSetform( $commerce_id="", $order_amount = "" )
    {
      $arrayOrden = array();
      $comercio = Empresas::findOne(['id_commerce' => $commerce_id, 'estado' => 1]);
      if( is_object($comercio) ){
        if( !empty( $order_amount ) ){
            $order_amount = number_format((float)$order_amount, 2, '.', '');
            $token = Util::getGenerarPermalink( Yii::$app->getSecurity()->generatePasswordHash('PagoMediosAbitmedia'.date('Y-m-d H:i:s').$comercio->id.'PagoMediosAbitmedia').'-form'.$comercio->id.'-'.urlencode( $order_amount ) );
            $arrayOrden['payment_form_url'] = Url::to(['forms/t?token='.$token], true);
            $this->setHeader(200);
            return array('status'=>1,'data'=>$arrayOrden);  
        }else{
          $this->setHeader(400);
          return array('status'=>0,'error_code'=>400,'message'=>'Es parámetro order_amount es requerido');
        }
        
      }else{
        $this->setHeader(400);
        return array('status'=>0,'error_code'=>400,'message'=>'Parámetro commerce_id incorrecto. Comercio no encontrado o inactivo, contáctese con soporte Pagomedios');
      } 
    }

    public function actionGetorder( $commerce_id, $order_id ){
      $arrayOrden = array();
      $comercio = Empresas::findOne(['id_commerce' => $commerce_id, 'estado' => 1]);
      if( is_object($comercio) ){

        $pedido = Pedidos::findOne(['empresa_id' => $comercio->id, 'numero_pedido' => $order_id]);
        if( is_object( $pedido ) ){
          $this->updatePedido($pedido->id);
          $pedido->refresh();
          $arrayOrden['authorization_result'] = $pedido->authorization_result;
          $arrayOrden['authorization_code'] = $pedido->authorization_code;
          $arrayOrden['customer_id'] = $pedido->cliente->identificacion;
          $arrayOrden['order_id'] = $pedido->numero_pedido;
          $arrayOrden['order_status'] = $pedido->estado;
          $arrayOrden['purchase_operation_number'] = $pedido->purchase_operation_number;
          $arrayOrden['card_brand'] = $pedido->brand;
          $arrayOrden['card_number'] = $pedido->payment_reference_code;
          
          $this->setHeader(200);
          return array('status'=>1,'data'=>$arrayOrden);
        }else{
          $this->setHeader(400);
          return array('status'=>0,'error_code'=>400,'message'=>'Pedido no encontrado');
        }
      }else{
        $this->setHeader(400);
        return array('status'=>0,'error_code'=>400,'message'=>'Parámetro commerce_id incorrecto. Comercio no encontrado o inactivo, contáctese con soporte Pagomedios');
      } 
    }
    public function setPedidoUser($cliente_id, $empresa_id, $descripcion, $monto, $response_url = '', $order_id = '',$order_tarifa,$order_basecero,$taxMontoIVA,$usuario_inserta)
    {
        if (!empty($order_id)) {
            $model = Pedidos::findOne(['empresa_id' => $empresa_id, 'numero_pedido' => $order_id]);
            if (!is_object($model)) {
                $model = new Pedidos();
            }
        } else {
            $model = new Pedidos();
        }
        $model->empresa_id = $empresa_id;
        $model->cliente_id = $cliente_id;
        $model->usuario_id = $usuario_inserta;
        $model->estado = 'Pago pendiente';
        $model->fecha_creacion = date('Y-m-d H:i:s');
        $model->pin_efectivo = time();
        $model->token = Util::getGenerarPermalink( Yii::$app->getSecurity()->generatePasswordHash('PagoMediosAbitmedia'.date('Y-m-d H:i:s').$empresa_id.'PagoMediosAbitmedia'.$cliente_id).strtotime(date('Y-m-d H:i:s')) );
        $model->a_pagar = $monto;

        $subtotal = (float)$order_tarifa;
        $model->total_con_iva = (float)$subtotal;
        $model->iva = ((float)$order_tarifa * 12)/100;
        $model->total_sin_iva = $order_basecero;
        $model->descripcion = $descripcion;
        $model->url_pago = $response_url;

        if((empty($model->total_con_iva)||$model->total_con_iva==0)&&(empty($model->total_sin_iva)||($model->total_sin_iva==0))&&(empty($model->iva)||($model->iva==0))&&($monto>0)){
            $valor_con_iva = (float)$monto/(float) Yii::$app->params['valor_iva'];
            $valor_con_iva = round($valor_con_iva,2);
            $model->total_con_iva = $valor_con_iva;
            $ivaCalculado= (float)$monto-$valor_con_iva;
            $ivaCalculado= round($ivaCalculado,2);
            $model->iva = $ivaCalculado;
        }

        $model->save();
        if( empty( $order_id ) ){
            $model->numero_pedido = str_pad($model->id, 9, "0", STR_PAD_LEFT);
        }else{
            $model->numero_pedido = $order_id;
        }
        if( $model->save() ){
            return $model;
        }else{
            return false;
        }
    }
    public function setPedido($cliente_id, $empresa_id, $descripcion, $monto, $response_url = '', $order_id = ''){
      if( !empty( $order_id ) ){
        $model = Pedidos::findOne(['empresa_id' => $empresa_id, 'numero_pedido' => $order_id]);
        if( !is_object( $model ) ){
          $model = new Pedidos(); 
        }
      }else{
        $model = new Pedidos();  
      }
      $model->empresa_id = $empresa_id;
      $model->cliente_id = $cliente_id;
      $model->estado = 'Pago pendiente';
      $model->fecha_creacion = date('Y-m-d H:i:s');
      $model->pin_efectivo = time();
      $model->token = Util::getGenerarPermalink( Yii::$app->getSecurity()->generatePasswordHash('PagoMediosAbitmedia'.date('Y-m-d H:i:s').$empresa_id.'PagoMediosAbitmedia'.$cliente_id).strtotime(date('Y-m-d H:i:s')) );
      $model->a_pagar = $monto;

      $subtotal = (float)$model->a_pagar / (float)Yii::$app->params['valor_iva'];
      $model->total_con_iva = (float)$subtotal;
      $model->iva = (float)$model->a_pagar - (float)$subtotal;
      $model->total_sin_iva = 0;

      $model->descripcion = $descripcion;
      $model->url_pago = $response_url;
      $model->save();
      if( empty( $order_id ) ){
        $model->numero_pedido = str_pad($model->id, 9, "0", STR_PAD_LEFT);
      }else{
        $model->numero_pedido = $order_id;
      }
      if( $model->save() ){
        return $model;
      }else{
        return false;
      }
    }

    public function updatePedido($id){
        $pedido = Pedidos::findOne($id);
        $acquirerId = ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
        $idCommerce = $pedido->empresa->id_commerce;
        $purchaseOperationNumber = $pedido->purchase_operation_number;
        $claveSecreta = $pedido->empresa->llave_vpos;
        $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $claveSecreta, 'sha512');
        //Referencia al Servicio Rest de Consulta de Trasacciones     
        if( $pedido->empresa->ambiente == 'Test' ){
            $url = 'https://integracion.alignetsac.com/VPOS2/rest/operationAcquirer/consulte';
        }elseif ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) {
            $url = 'https://vpayment.verifika.com/VPOS2/rest/operationAcquirer/consulte';
        }
        //Trama JSON para request
        $dataRest = '{"idAcquirer":"'.$acquirerId.'","idCommerce":"'.$idCommerce.'","operationNumber":"'.$purchaseOperationNumber.'","purchaseVerification":"'.$purchaseVerification.'"}';
            
        $header = array( 'Content-Type: application/json' );
        //Consumo del servicio Rest
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRest);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);

        $array = json_decode( $response, JSON_FORCE_OBJECT );

        if( isset( $array['authenticationECI'] ) ){
            $pedido->authorization_result =  ( isset( $array['errorCode'] ) ) ? trim( $array['errorCode'] ) : null;
            $pedido->authorization_code =  ( isset( $array['authorizationCode'] ) ) ? trim( $array['authorizationCode'] ) : null;
            $pedido->payment_reference_code = trim( $array['cardNumber'] );
            $pedido->brand = trim( $array['cardType'] );
        }

        if( isset( $array['result'] ) ){
            $result = trim( $array['result'] );
            if( $pedido->estado != 'Autorizado' ){

                if( $result == '3' ){ //Autorizado
                    $pedido->estado = 'Autorizado';
                }elseif( $result == '5' ){ //Depositado
                    $pedido->estado = 'Depositado';
                }elseif( $result == '7' ){ //Liquidado
                    $pedido->estado = 'Autorizado';
                }elseif( $result == '13' ){
                    $pedido->estado = 'Pago pendiente';
                }elseif( $result == '0' ){
                    $pedido->estado = 'Pago pendiente';
                }else{
                    $pedido->estado = 'No autorizado';
                }
            }
        }

        if( isset( $array['errorCode'] ) ){
          if( trim($array['errorCode']) == '7003' ){ //No existe la orden, vamos a dar un recorrido por pedidos_operaciones
            $regOper = PedidosOperaciones::find()->where(['pedido_id' => $pedido->id])->all();
            if( count($regOper) > 1 ){

              foreach ($regOper as $reg) {
                $purchaseOperationNumber = $reg->purchase_operation_number;
                $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $claveSecreta, 'sha512');
                
                $dataRest = '{"idAcquirer":"'.$acquirerId.'","idCommerce":"'.$idCommerce.'","operationNumber":"'.$purchaseOperationNumber.'","purchaseVerification":"'.$purchaseVerification.'"}';

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRest);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($curl);
                curl_close($curl);
                $array2 = json_decode( $response, JSON_FORCE_OBJECT );

                if( isset( $array2['authenticationECI'] ) ){
                    $pedido->authorization_result =  ( isset( $array2['errorCode'] ) ) ? trim( $array2['errorCode'] ) : null;
                    $pedido->authorization_code =  ( isset( $array2['authorizationCode'] ) ) ? trim( $array2['authorizationCode'] ) : null;
                    $pedido->payment_reference_code = trim( $array2['cardNumber'] );
                    $pedido->brand = trim( $array2['cardType'] );
                }

                if( isset( $array2['result'] ) ){
                    $result = trim( $array2['result'] );
                    if( $pedido->estado == 'Pago pendiente' ){

                        if( $result == '3' ){ //Autorizado
                            $pedido->estado = 'Autorizado';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }elseif( $result == '5' ){ //Depositado
                            $pedido->estado = 'Depositado';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }elseif( $result == '7' ){ //Liquidado
                            $pedido->estado = 'Autorizado';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }elseif( $result == '13' ){
                            $pedido->estado = 'Pago pendiente';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }elseif( $result == '0' ){
                            $pedido->estado = 'Pago pendiente';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }else{
                            $pedido->estado = 'No autorizado';
                            $pedido->purchase_operation_number = $purchaseOperationNumber;
                        }
                    }
                }
              }

            }
          }
        }

        $pedido->save();

        if( $pedido->empresa->facturacion_electronica == 1 ){
          if( $pedido->estado == 'Autorizado' ){
            if( $pedido->factura_emitida != 1 ){
              $conteo_identificacion = strlen($pedido->cliente->identificacion);
              if( $conteo_identificacion == 10 ){
                  $tipo_identificacion = '05';
              }elseif( $conteo_identificacion == 13 ){
                  $tipo_identificacion = '04';
              }else{
                  $tipo_identificacion = '06';
              }

              $subtotal = (float)$pedido->a_pagar / (float)Yii::$app->params['valor_iva'];
              $data = array(
                  "ambiente" => $pedido->empresa->facturacion_ambiente, //1->Pruebas, 2->Producción
                  "razonSocial" => $pedido->empresa->razon_social, //Comercio
                  "nombreComercial" =>( is_null($pedido->empresa->nombre_comercial) ) ? $pedido->empresa->razon_social : $pedido->empresa->nombre_comercial, //Comercio
                  "ruc" =>$pedido->empresa->ruc, //Comercio
                  "tipoComprobante" => "01", //01 -> Factura
                  "direccionMatriz" => $pedido->empresa->direccion, //Comercio
                  "obligadoContabilidad" => $pedido->empresa->obligado_llevar_contabilidad, //Comercio
                  "fechaEmision" => date('Y-m-d'), //Fecha emision de la factura (presente o maximo un mes atras)
                  "razonSocialComprador" => $pedido->cliente->nombres.' '.$pedido->cliente->apellidos, //Cliente
                  "tipoIdentificacion" => $tipo_identificacion, //05 -> Cedula, 04 -> RUC, 06 -> Pasaporte, 07->Consumidor final
                  "identificacionComprador" => $pedido->cliente->identificacion, //Cliente
                  "direccionComprador" => $pedido->cliente->direccion, //Cliente
                  "establecimiento" => $pedido->empresa->establecimiento, //Comercio sucursal
                  "direccionEstablecimiento" => $pedido->empresa->direccion, //Comercio sucursal direccion
                  "detallesFactura" => array(
                      array("codigo" => $pedido->id,"nombre"=>$pedido->descripcion,"cantidad"=>"1","precioUnitario"=>number_format((float)$subtotal, 2, '.', ''),"descuento"=>"0","impuestoAplicado"=>"2") //impuestoAplicado: 0->0% / 2->IVA / 6->No objeto / 7->Exento IVA. El precioUnitario es sin IVA
                  ),
                  "formasDePago" => array(
                    array("codigo"=>"19") //19 -> Tarjeta de credito / 01 -> Efectivo
                  ),
                  "informacionAdicional" => array(
                      array("nombre" => "Dirección","descripcion" => $pedido->cliente->direccion),
                      array("nombre" => "Email","descripcion" => $pedido->cliente->email)
                  )
                );


                $respuesta = Facturasoft::emitirComprobante($data);
                if($respuesta['estado'] == 'OK')
                {
                  $pedido->factura_emitida = 1;
                  $pedido->factura_fecha_emision = date('Y-m-d H:i:s');
                  $pedido->factura_clave_acceso = $respuesta['mensaje'];
                }
                if($respuesta['estado'] != 'OK')
                {
                  $pedido->factura_emitida = 2;
                  $pedido->factura_clave_acceso = $respuesta['mensaje'];
                }
                
                $pedido->save();
            }
          }
        }

        
    }
 
 
    private function setHeader($status)
      {
 
      $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
      $content_type="application/json; charset=utf-8";
 
      header($status_header);
      header('Content-type: ' . $content_type);
      header('X-Powered-By: ' . "Nintriva <nintriva.com>");
      }
    private function _getStatusCodeMessage($status)
    {
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
    }
}