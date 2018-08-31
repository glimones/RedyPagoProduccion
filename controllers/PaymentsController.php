<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Pedidos;
use app\models\Util;
use app\models\Formularios;
use app\models\PedidosOperaciones;
use app\models\Facturasoft;
use app\models\Empresas;

class PaymentsController extends Controller
{
    /**
     * @inheritdoc
     */

    public $pedidoIdioma;
    public $pedidoFecha;
    public $numeroPedido;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {            
        if ($action->id == 'response') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionT($token=null, $type=null)
    {
        $this->layout = 'main_clientes';
        if( !is_null($token) ){
            $pedido = Pedidos::find()->where('token = "'.$token.'"')->one();
            if( is_object( $pedido ) ){

                if( $pedido->estado != 'Autorizado' ){
                    $url = Yii::$app->params['core_api_url'].'/getorder/'; //URL del servicio web REST
                    // $url = 'https://localhost/pagomedios/web/api/getorder/'; //URL del servicio web REST
                    $header = array( 'Content-Type: application/json' );
                    $dataOrden = array( 'commerce_id' => $pedido->empresa->id_commerce, //ID unico por comercio
                                        'order_id' => $pedido->numero_pedido,
                                    );

                    $params = http_build_query( $dataOrden ); //Tranformamos un array en formato GET
                     //Consumo del servicio Rest
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url.'?'.$params);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $responsePagomedios = json_decode($response);
                    $pedido->refresh();
                }


                if( $pedido->empresa->ambiente != 'Test' )
                    $this->layout = 'main_clientes_produccion';
        
                if( $pedido->estado != 'Autorizado' && $pedido->estado != 'Depositado' ){
                    $pedido->purchase_operation_number = Util::generarOrdenOperacion($pedido->id);
                    if( is_null( $pedido->pin_efectivo ) ){
                        $pedido->pin_efectivo = time();
                    }
                    $pedido->save();
                    $pedido->refresh();
                    if(  $type == 'direct-payment' ){

                        if( $pedido->empresa->procesamiento_id == 1 ){ //VPOS1
                            
                            return $this->render('cobro_directo_vpos1', [
                                'pedido' => $pedido,
                            ]);

                        }elseif( $pedido->empresa->procesamiento_id == 2 ){ //VPOS 2 Payme
                            
                            return $this->render('cobro_directo', [
                                'pedido' => $pedido,
                            ]);

                        }

                    }else{

                        if( $pedido->empresa->procesamiento_id == 1 ){ //VPOS1

                            return $this->render('indexvpos1', [
                                'pedido' => $pedido,
                            ]);
                        }elseif( $pedido->empresa->procesamiento_id == 2 ){ //VPOS 2 Payme

                            return $this->render('index', [
                                'pedido' => $pedido,
                            ]);
                        }

                    }
                }else{
                    $arrayErrores = array( 'logo'=>$pedido->empresa->logo, 'titulo'=>'Pedido pagado', 'tipo' => 'info', 'mensaje'=> 'El pedido ya fue pagado');
                    return $this->render( 'error', ['error'=>$arrayErrores] );
                }
            }else{
                $arrayErrores = array( 'titulo'=>'Pedido no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el pedido, contáctese con su comercio' );
                return $this->render( 'error', ['error'=>$arrayErrores] );
            }
        }else{
            $arrayErrores = array( 'titulo'=>'Pedido no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el pedido' );
            return $this->render( 'error', ['error'=>$arrayErrores] );
        }
    }

    public function actionForms($token=null)
    {
        $this->layout = 'main_clientes';
        if( !is_null($token) ){
            $form = Formularios::find()->where('token = "'.$token.'"')->one();
            if( is_object( $form ) ){
                return $this->render('formulario_es', [
                    'form' => $form,
                ]);
            }else{
                $arrayErrores = array( 'titulo'=>'Formulario no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el formulario solicitado' );
                return $this->render( 'error', ['error'=>$arrayErrores] );
            }
        }else{
            $arrayErrores = array( 'titulo'=>'Formulario no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el formulario solicitado' );
            return $this->render( 'error', ['error'=>$arrayErrores] );
        }
    }

    public function actionResponse(){
        
        $this->layout = 'main_clientes';
        $arrayErrores = array();
        
        if( isset( $_POST['IDCOMMERCE'] ) ){ //Procesado por VPOS1
            $empresa = Empresas::find()->andWhere( ['id_commerce' => $_POST['IDCOMMERCE'] ] )->one();
            if( is_object( $empresa ) ){
                
                require_once 'vpos_plugin_NOTAX.php';
                $llavePrivadaCifrado = $empresa->llave_privada_cifrado_rsa;
                $llavePublicaFirma = $empresa->alignet_publica_firma_rsa;
                $arrayIn['IDACQUIRER'] = $_POST['IDACQUIRER'];
                $arrayIn['IDCOMMERCE'] = $_POST['IDCOMMERCE'];
                $arrayIn['XMLRES'] = $_POST['XMLRES'];
                $arrayIn['DIGITALSIGN'] = $_POST['DIGITALSIGN'];
                $arrayIn['SESSIONKEY'] = $_POST['SESSIONKEY'];
                $arrayOut = '';
                $VI = $empresa->vector;

                if(VPOSResponse($arrayIn,$arrayOut,$llavePublicaFirma,$llavePrivadaCifrado,$VI)){
            
                    if( isset( $arrayOut['authorizationResult'] ) ){

                        $regOper = PedidosOperaciones::find()->where(['purchase_operation_number' => $arrayOut['purchaseOperationNumber']])->one();

                        $pedido = Pedidos::findOne( $regOper->pedido_id );

                        if( is_object( $pedido ) ){
                            $pedido->purchase_operation_number = $arrayOut['purchaseOperationNumber'];       
                            $pedido->save();
                            $pedido->refresh();

                            if( $pedido->empresa->ambiente != 'Test' ){
                                $this->layout = 'main_clientes_produccion';
                            }

                            if( $arrayOut['authorizationResult'] == '05' ){ //Transaccion Rechazada por VPOS / Se dio clic en retornar en comercio
                                if( $pedido->empresa->ambiente == 'Test' || $pedido->empresa->ambiente == 'Test-Producción' ){ 
                                    echo '<h2>Pruebas certificación ambiente testing/producción</h2><pre>'.$arrayOut['authorizationResult'].'<br>'.$arrayOut['errorCode'].'<br>'.$arrayOut['errorMessage'].'<br>'.'</pre>';
                                }else{
                                    return $this->redirect(['t', 'token' => $pedido->token]);
                                }
                            }else{
                                $pedido->authorization_result = $arrayOut['authorizationResult'];
                                $pedido->authorization_code = ( isset( $arrayOut['authorizationCode'] ) ) ? $arrayOut['authorizationCode'] : null;
                                $pedido->error_code = $arrayOut['errorCode'];
                                // $pedido->payment_reference_code = $arrayOut['paymentReferenceCode'];
                                // $pedido->reserved_22 = $arrayOut['reserved22'];
                                // $pedido->reserved_23 = $arrayOut['reserved23'];
                                $pedido->fecha_pago = date('Y-m-d H:i:s');
                                // $pedido->idtransaction = $arrayOut['IDTransaction'];
                                // $pedido->purchaseverification = $arrayOut['purchaseVerification'];
                                $pedido->brand = ( isset( $arrayOut['cardType'] ) ) ? $arrayOut['cardType'] : null;
                                $pedido->error_message = $arrayOut['errorMessage'];

                                if( $arrayOut['authorizationResult'] == '00' ){ //Transaccion autorizada
                                    $pedido->estado = 'Autorizado';
                                    $pedido->save();
                                    $arrayErrores = array( 'logo'=>$pedido->empresa->logo, 'titulo'=>'Transacción exitosa', 'tipo' => 'success', 'mensaje'=> 'Operación autorizada para el pedido: #'.$pedido->numero_pedido.' <br> El código de autorización es: <strong>'.$arrayOut['authorizationCode'].'</strong> <br> El número de operación es: <strong>'.$arrayOut['purchaseOperationNumber'].'</strong>. <br>Se le envío un correo electrónico con el detalle de su pago.' );
                                

                                    if( $pedido->empresa->ambiente == 'Producción' ){
                                        if( isset( $pedido->usuario ) ){
                                            $asunto = 'Notificación de pago recibido';
                                            $plantilla = 'pago_recibido';

                                            $this->pedidoFecha = $pedido->fecha_pago;
                                            $this->numeroPedido = $pedido->numero_pedido;

                                            $email = Yii::$app->mailer->compose($plantilla, [ 'model'=>$pedido ])
                                            ->setFrom( Yii::$app->params['notificacionesEmail'] )
                                            ->setTo( $pedido->usuario->email )
                                            ->setSubject( $asunto )
                                            ->send();
                                        }
                                    }

                                    if( $pedido->empresa->facturacion_electronica == 1 ){
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

                                            // print_r($data);

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

                                          // print_r($pedido->getErrors());

                                    }


                                }elseif( $arrayOut['authorizationResult'] == '01' ){ //Transacción denegada por Emisor de la tarjeta
                                    $pedido->estado = 'No autorizado';
                                    $pedido->save();
                                    $arrayErrores = array( 'logo'=>$pedido->empresa->logo, 'titulo'=>'Transacción no exitosa', 'tipo' => 'danger', 'mensaje'=> 'Operación no autorizada por el emisor de su tarjeta, verifique que los datos ingresados de su tarjeta sean correctos y vuelva a intentarlo.' );
                                }
                            }


                            if( $pedido->empresa->ambiente != 'Producción' ){ 
                                if( $arrayOut['authorizationResult'] != '05' ){
                                    $arrayErrores['mensaje'] .= '<h2>Pruebas certificación ambiente testing/producción</h2><pre>'.$arrayOut['authorizationResult'].'<br>'.$arrayOut['errorCode'].'<br>'.$arrayOut['errorMessage'].'<br>'.'</pre>';
                                }
                            }

                            if( isset( $arrayOut['authorizationResult'] ) ){
                                if( $arrayOut['authorizationResult'] != '05' ){
                                    if( !empty( $pedido->url_pago ) ){
                                        return $this->render( 'api_response', ['pedido'=>$pedido] );
                                        
                                    }else{
                                        return $this->render( 'error', ['error'=>$arrayErrores] );
                                    }
                                }
                            }

                        }else{
                            $arrayErrores = array( 'titulo'=>'Pedido', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el pedido con purchaseOperationNumber '.$arrayOut['purchaseOperationNumber'].' contactarse con soporte Redypago.' );

                            return $this->render( 'error', ['error'=>$arrayErrores] );
                        }
                    }

                 }else{
                    $arrayErrores = array( 'titulo'=>'VPOS1 error', 'tipo' => 'danger', 'mensaje'=> 'Las llaves configuradas para su comercio en VPOS1 son incorrectas. Contactarse con soporte Redypago.' );

                    return $this->render( 'error', ['error'=>$arrayErrores] );
                 }

            }else{
                $arrayErrores = array( 'titulo'=>'Comercio no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el comercio con código '.$_POST['IDCOMMERCE'].' contactarse con soporte Redypago.' );

                return $this->render( 'error', ['error'=>$arrayErrores] );
            }

            if( isset( $_POST['authorizationResult'] ) ){
                if( $_POST['authorizationResult'] != '05' ){
                    if( !empty( $pedido->url_pago ) ){
                        return $this->render( 'api_response', ['pedido'=>$pedido] );
                        
                    }else{
                        return $this->render( 'error', ['error'=>$arrayErrores] );
                    }
                }
            }
            
        }else{ //Procesado por VPOS2
            if( isset( $_POST['authorizationResult'] ) ){

                $regOper = PedidosOperaciones::find()->where(['purchase_operation_number' => $_POST['purchaseOperationNumber']])->one();

                $pedido = Pedidos::findOne( $regOper->pedido_id );

                if( is_object( $pedido ) ){
                    $pedido->purchase_operation_number = $_POST['purchaseOperationNumber'];       
                    $pedido->save();
                    $pedido->refresh();
                    $claveSecreta = $pedido->empresa->llave_vpos;
                }else{
                    $claveSecreta = '';
                }
                $purchaseVericationVPOS2 = ( isset( $_POST['purchaseVerification'] ) ) ? $_POST['purchaseVerification'] : '';
                $purchaseVericationComercio = openssl_digest($_POST['acquirerId'] . $_POST['idCommerce'] . $_POST['purchaseOperationNumber'] . $_POST['purchaseAmount'] . $_POST['purchaseCurrencyCode'] . $_POST['authorizationResult'] . $claveSecreta, 'sha512');
                if ($purchaseVericationVPOS2 == $purchaseVericationComercio || $purchaseVericationVPOS2 == "") {
                    
                    $pedido = Pedidos::find()->where(['purchase_operation_number' => $_POST['purchaseOperationNumber']])->one();
                    if( is_object( $pedido ) ){
                        if( $pedido->empresa->ambiente != 'Test' )
                            $this->layout = 'main_clientes_produccion';

                        if( $_POST['authorizationResult'] == '05' ){ //Transaccion Rechazada por VPOS / Se dio clic en retornar en comercio
                            if( $pedido->empresa->ambiente == 'Test' ){ 
                                echo '<h2>Pruebas certificación ambiente testing/producción</h2><pre>'.$_POST['authorizationResult'].'<br>'.$_POST['errorCode'].'<br>'.$_POST['errorMessage'].'<br>'.'</pre>';
                            }else{
                                return $this->redirect(['t', 'token' => $pedido->token]);
                            }
                        }else{
                            $pedido->authorization_result = $_POST['authorizationResult'];
                            $pedido->authorization_code = $_POST['authorizationCode'];
                            $pedido->error_code = $_POST['errorCode'];
                            $pedido->payment_reference_code = $_POST['paymentReferenceCode'];
                            $pedido->reserved_22 = $_POST['reserved22'];
                            $pedido->reserved_23 = $_POST['reserved23'];
                            $pedido->fecha_pago = date('Y-m-d H:i:s');
                            $pedido->idtransaction = $_POST['IDTransaction'];
                            $pedido->purchaseverification = $_POST['purchaseVerification'];
                            $pedido->brand = $_POST['brand'];
                            $pedido->error_message = $_POST['errorMessage'];

                            if( $_POST['authorizationResult'] == '00' ){ //Transaccion autorizada
                                $pedido->estado = 'Autorizado';
                                $pedido->save();
                                $arrayErrores = array( 'logo'=>$pedido->empresa->logo, 'titulo'=>'Transacción exitosa', 'tipo' => 'success', 'mensaje'=> 'Operación autorizada para el pedido: #'.$pedido->numero_pedido.' <br> El código de autorización es: <strong>'.$_POST['authorizationCode'].'</strong> <br> El número de operación es: <strong>'.$_POST['purchaseOperationNumber'].'</strong>. <br>Se le envío un correo electrónico con el detalle de su pago.' );
                            

                                if( $pedido->empresa->ambiente == 'Producción' ){
                                    if( isset( $pedido->usuario ) ){
                                        $asunto = 'Notificación de pago recibido';
                                        $plantilla = 'pago_recibido';

                                        $this->pedidoFecha = $pedido->fecha_pago;
                                        $this->numeroPedido = $pedido->numero_pedido;

                                        $email = Yii::$app->mailer->compose($plantilla, [ 'model'=>$pedido ])
                                        ->setFrom( Yii::$app->params['notificacionesEmail'] )
                                        ->setTo( $pedido->usuario->email )
                                        ->setSubject( $asunto )
                                        ->send();
                                    }
                                }

                                if( $pedido->empresa->facturacion_electronica == 1 ){
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

                                        // print_r($data);

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

                                      // print_r($pedido->getErrors());

                                }


                            }elseif( $_POST['authorizationResult'] == '01' ){ //Transacción denegada por Emisor de la tarjeta
                                $pedido->estado = 'No autorizado';
                                $pedido->save();
                                $arrayErrores = array( 'logo'=>$pedido->empresa->logo, 'titulo'=>'Transacción no exitosa', 'tipo' => 'danger', 'mensaje'=> 'Operación no autorizada por el emisor de su tarjeta, verifique que los datos ingresados de su tarjeta sean correctos y vuelva a intentarlo.' );
                            }
                        }

                        if( $pedido->empresa->ambiente != 'Producción' ){ 
                            if( $_POST['authorizationResult'] != '05' ){
                                $arrayErrores['mensaje'] .= '<h2>Pruebas certificación ambiente testing/producción</h2><pre>'.$_POST['authorizationResult'].'<br>'.$_POST['errorCode'].'<br>'.$_POST['errorMessage'].'<br>'.'</pre>';
                            }
                        }

                    }else{
                        $arrayErrores = array( 'titulo'=>'Pedido no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el pedido, contáctese con su comercio' );
                    }

                } else {
                    $arrayErrores = array( 'titulo'=>'Error en operación', 'tipo' => 'danger', 'mensaje'=> 'Ocurrio un error al procesar el pedido, contáctese con soporte Pagomedios' );
                }

            }else{
                return 'Pagomedios. No se recibieron parámetros de respuesta.';
            }
            if( isset( $_POST['authorizationResult'] ) ){
                if( $_POST['authorizationResult'] != '05' ){
                    if( !empty( $pedido->url_pago ) ){
                        return $this->render( 'api_response', ['pedido'=>$pedido] );
                        
                    }else{
                        return $this->render( 'error', ['error'=>$arrayErrores] );
                    }
                }
            }
        }

    }

}
