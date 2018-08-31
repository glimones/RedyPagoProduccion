<?php

namespace app\controllers;

use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Pedidos;
use app\models\Formularios;
use app\models\PagosForm;
use app\models\Empresas;
use app\models\Util;
use yii\helpers\Url;
use SoapClient;

class FormsController extends Controller
{
    /**
     * @inheritdoc
     */
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

    public function actionT($token=null)
    {
        $this->layout = 'main_clientes_produccion';
        if( !is_null($token) ){
            $model = Formularios::find()->where('token = "'.$token.'"')->one();
            if( is_object( $model ) ){
                $form = new PagosForm();
                $form->scenario = PagosForm::SCENARIO_FORMWEB;

                if( $model->idioma == 'Español' ){


                    if( $model->empresa->procesamiento_id == 1 ){ //VPOS1
                            
                        return $this->render('formulario_es_vpos1', [
                            'form_pago' => $form,
                            'model' => $model,
                        ]);

                    }elseif( $model->empresa->procesamiento_id == 2 ){ //VPOS 2 Payme
                        
                        return $this->render('formulario_es', [
                            'form_pago' => $form,
                            'model' => $model,
                        ]);

                    }

                }else{

                    if( $model->empresa->procesamiento_id == 1 ){ //VPOS1
                            
                        return $this->render('formulario_en_vpos1', [
                            'form_pago' => $form,
                            'model' => $model,
                        ]);

                    }elseif( $model->empresa->procesamiento_id == 2 ){ //VPOS 2 Payme
                        
                        \Yii::$app->language = 'en-US';
                        return $this->render('formulario_en', [
                            'form_pago' => $form,
                            'model' => $model,

                        ]);

                    }

                }
            }else{

                $datosValidacion = explode('-form', $token);
    
                if( isset( $datosValidacion[1] ) ){
                    $datosForm = explode('-', $datosValidacion[1]);
                    if( count( $datosForm ) == 3 ){
                        $comercio = Empresas::findOne(['id' => $datosForm[0], 'estado' => 1]);
                        if( is_object( $comercio ) ){
                            $form = new PagosForm();
                            $valor = number_format((float)$datosForm[1].'.'.$datosForm[2], 2, '.', '');
                            return $this->render('form_pago_express_es', [
                                'form_pago' => $form,
                                'model' => $comercio,
                                'valor' => $valor,
                            ]);
                        }
                    }else{
                        $arrayErrores = array( 'titulo'=>'Formulario no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el formulario solicitado' );
                    }
                }else{
                    $arrayErrores = array( 'titulo'=>'Formulario no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el formulario solicitado' );
                }
                if( isset( $arrayErrores ) ){
                    return $this->render( 'error', ['error'=>$arrayErrores] );
                }
            }
        }else{
            $arrayErrores = array( 'titulo'=>'Formulario no encontrado', 'tipo' => 'danger', 'mensaje'=> 'No se encontró el formulario solicitado' );
            return $this->render( 'error', ['error'=>$arrayErrores] );
        }
    }

    public function actionCobrodirecto($token=null)
    {
        $request = Yii::$app->request;
        $form = new PagosForm();
        $form->scenario = PagosForm::SCENARIO_COBRODIRECTO;

        if( $form->load( $request->post() ) ){
            if ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa']) || (Yii::$app->params['todos']=='S')) {
                //Gli cambios ini
                $basecero = $form->form_base0;
                $tarifa = $form->form_base12;
                $tarifa2 = (float)$tarifa;
                $tarifa2 = number_format($tarifa2, 2, '.', '');
                $tarifa2 = (float)$tarifa2 * (int)100;
                $valor_Iva = ((float)$tarifa) * 12 / 100;
                $valor_Iva = number_format($valor_Iva, 2, '.', '');
                $valor_Iva = (float)$valor_Iva * (int)100;
                $valor_MontoNoGravaIva = (float)$basecero;
                $valor_MontoNoGravaIva = number_format($valor_MontoNoGravaIva, 2, '.', '');
                $valor_MontoNoGravaIva = (float)$valor_MontoNoGravaIva * (int)100;
                $total = $valor_Iva + $valor_MontoNoGravaIva + $tarifa2;
                $total2 = $total;
                $_SESSION['$tarifa2'] = $tarifa2;
                $_SESSION['$valor_Iva'] = $valor_Iva;
                $_SESSION['$valor_MontoNoGravaIva'] = $valor_MontoNoGravaIva;
                $_SESSION['$total'] = $total;
                $totalIns = substr($total, 0, strlen($total) - 2) . '.';
                $totalIns = $totalIns . '' . substr($total2, strlen($totalIns) - 1);
                //gli cambios fin
                //Generación de pedido
                $arrayResponse = array();
                $url = Yii::$app->params['core_api_url'] . '/setorder/'; //URL del servicio web REST
                //$url = 'http://localhost/pagomedios/web/api/setorder/'; //URL del servicio web REST
                $header = array('Content-Type: application/json');
                $dataOrden = array('commerce_id' => Yii::$app->user->identity->empresa->id_commerce, //ID unico por comercio
                    'customer_id' => $form->identificacion, //Identificación del tarjeta habiente (RUC, Cédula, Pasaporte)
                    'customer_name' => $form->nombres, //Nombres del tarjeta habiente
                    'customer_lastname' => $form->apellidos, //Apellidos del tarjeta habiente
                    'customer_phones' => 'N/A',  //Teléfonos del tarjeta habiente
                    'customer_address' => 'N/A',  //Dirección del tarjeta habiente
                    'customer_email' => $form->email,  //Correo electrónico del tarjeta habiente
                    'customer_language' => 'es',  //Idioma del tarjeta habiente
                    'order_description' => $form->form_descripcion,  //Descripción de la órden
                    'order_tarifa' => $form->form_base12, //Monto base 12 gli
                    'order_basecero' => $form->form_base0, //Monto base 0 gli
                    'taxMontoIVA' => $form->form_base12, //Monto iva gli
                    'usuario_inserta'=> Yii::$app->user->identity->id,//usuario id
                    'order_amount' => $totalIns, //Monto total de la órden
                    'response_url' => Url::to(['pedidos/index'], true), //Monto total de la órden
                );
            }else{
                $arrayResponse = array();
                $url = Yii::$app->params['core_api_url'].'/setorder/'; //URL del servicio web REST
                //$url = 'http://localhost/api/setorder/'; //URL del servicio web REST
                $header = array( 'Content-Type: application/json' );
                $dataOrden = array( 'commerce_id' => Yii::$app->user->identity->empresa->id_commerce, //ID unico por comercio
                    'customer_id' => $form->identificacion, //Identificación del tarjeta habiente (RUC, Cédula, Pasaporte) GLI cambio Identificación
                    'customer_name' => $form->nombres, //Nombres del tarjeta habiente
                    'customer_lastname' => $form->apellidos, //Apellidos del tarjeta habiente
                    'customer_phones' => 'N/A',  //Teléfonos del tarjeta habiente
                    'customer_address' => 'N/A',  //Dirección del tarjeta habiente
                    'customer_email' => $form->email,  //Correo electrónico del tarjeta habiente
                    'customer_language' => 'es',  //Idioma del tarjeta habiente
                    'order_description' => $form->form_descripcion,  //Descripción de la órden
                    'order_amount' => $form->form_total, //Monto total de la órden
                    'response_url' => Url::to(['pedidos/index'], true), //Monto total de la órden
                );
            }
            $params = http_build_query( $dataOrden ); //Tranformamos un array en formato GET
             //Consumo del servicio Rest
            die($url.'?'.$params);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url.'?'.$params);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_PORT, 80);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


            $response = curl_exec($curl);
            curl_close($curl);
            $responsePagomedios = json_decode($response);

            if( $responsePagomedios->status == 1 ){
                Yii::$app->getResponse()->redirect($responsePagomedios->data->payment_url.'&type=direct-payment')->send();
                return;

                // $pedido = Pedidos::findOne(['numero_pedido' => $responsePagomedios->data->order_id]);
                // $pedido->purchase_operation_number = Util::generarOrdenOperacion( $pedido->id );
                // $pedido->save();
                // $pedido->refresh();

            }else{
                $arrayResponse['error'] = 1;
            }
            //Generación de pedido
        }

        return $this->render('cobro_directo', [
            'form_pago' => $form,
        ]);
    }

    public function actionSetpedido(){
        $arrayResponse = array();
        $url = Yii::$app->params['core_api_url'].'/setorder/'; //URL del servicio web REST
        // $url = 'http://localhost/pagomedios/web/api/setorder/';; //URL del servicio web REST
        $header = array( 'Content-Type: application/json' );
        $dataOrden = array( 'commerce_id' => $_POST['PagosForm']['commerce_id'], //ID unico por comercio
                            'customer_id' => $_POST['PagosForm']['identificacion'], //Identificación del tarjeta habiente (RUC, Cédula, Pasaporte)
                            'customer_name' => $_POST['PagosForm']['nombres'], //Nombres del tarjeta habiente
                            'customer_lastname' => $_POST['PagosForm']['apellidos'], //Apellidos del tarjeta habiente
                            'customer_phones' => $_POST['PagosForm']['telefonos'],  //Teléfonos del tarjeta habiente
                            'customer_address' => $_POST['PagosForm']['direccion'],  //Dirección del tarjeta habiente
                            'customer_email' => $_POST['PagosForm']['email'],  //Correo electrónico del tarjeta habiente
                            'customer_language' => 'es',  //Idioma del tarjeta habiente
                            'order_description' => $_POST['PagosForm']['form_descripcion'],  //Descripción de la órden
                            'order_amount' => $_POST['PagosForm']['form_total'], //Monto total de la órden
                            'order_tarifa' => $_POST['PagosForm']['base12'], //Monto total de la órden
                            'order_basecero' => $_POST['PagosForm']['base0'], //Monto total de la órden
                            'usuario_inserta'=>  $_POST['PagosForm']['usuario_id'],//usuario id
                            'taxMontoIVA' => $_POST['PagosForm']['iva'], //Monto total de la órden
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

        // order_id
        if( $responsePagomedios->status == 1 ){

            $pedido = Pedidos::findOne(['numero_pedido' => $responsePagomedios->data->order_id]);
            $pedido->purchase_operation_number = Util::generarOrdenOperacion( $pedido->id );
            $pedido->save();
            $pedido->refresh();

            $idEntCommerce = $pedido->empresa->id_wallet;
            $codCardHolderCommerce = 'PM_'.$pedido->cliente_id;
            $names = $pedido->cliente->nombres;
            $lastNames = $pedido->cliente->apellidos;
            $mail = $pedido->cliente->email;
            $reserved1 = '';
            $reserved2 = '';
            $reserved3 = '';
            $claveSecretaWallet = $pedido->empresa->llave_wallet;
            $registerVerification = openssl_digest($idEntCommerce . $codCardHolderCommerce . $mail . $claveSecretaWallet, 'sha512');

            // if( $pedido->empresa->ambiente == 'Test' ){
            //     $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
            // }elseif( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ){
            //     $wsdl = 'https://www.pay-me.pe/WALLETWS/services/WalletCommerce?wsdl';
            // }

            // $client = new SoapClient($wsdl);

            // if( method_exists( $client, 'RegisterCardHolder' ) ){
            //     $params = array(
            //         'idEntCommerce'=>$idEntCommerce,
            //         'codCardHolderCommerce'=>$codCardHolderCommerce,
            //         'names'=>$names,
            //         'lastNames'=>$lastNames,
            //         'mail'=>$mail,
            //         'reserved1'=>$reserved1,
            //         'reserved2'=>$reserved2,
            //         'reserved3'=>$reserved3,
            //         'registerVerification'=>$registerVerification
            //     );
            //     $result = $client->RegisterCardHolder($params);
            //     $codAsoCardHolderWallet = $result->codAsoCardHolderWallet;
            // }else{
            //     $codAsoCardHolderWallet = '';
            // }

            $codAsoCardHolderWallet = '';
            
            $valor_total_orden = (float)$pedido->a_pagar;
            $valor_total_orden = number_format($valor_total_orden, 2, '.', '');
            $valor_total_orden = (float)$valor_total_orden * (int)100;

            $acquirerId = ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
            $idCommerce = $pedido->empresa->id_commerce;
            $purchaseOperationNumber = $pedido->purchase_operation_number;
            $purchaseAmount = $valor_total_orden;
            $purchaseCurrencyCode = '840';
            
            $claveSecreta = $pedido->empresa->llave_vpos;
            $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta, 'sha512');


            //Integración calculo de IVA

            $valor_Iva = (float)$pedido->iva;
            $valor_Iva = number_format($valor_Iva, 2, '.', '');
            $valor_Iva = (float)$valor_Iva * (int)100;

            $valor_MontoNoGravaIva = (float)$pedido->total_sin_iva;
            $valor_MontoNoGravaIva = number_format($valor_MontoNoGravaIva, 2, '.', '');
            $valor_MontoNoGravaIva = (float)$valor_MontoNoGravaIva * (int)100;
            
            $valor_MontoGravaIva = (float)$pedido->total_con_iva;
            $valor_MontoGravaIva = number_format($valor_MontoGravaIva, 2, '.', '');
            $valor_MontoGravaIva = (float)$valor_MontoGravaIva * (int)100;

            $taxMontoFijo = $purchaseAmount; // campo a_pagar contiene ya toda la sumatoria con o sin iva
            $taxMontoIVA = $valor_Iva;
            $taxMontoNoGravaIva = $valor_MontoNoGravaIva;
            $taxMontoGravaIva = $valor_MontoGravaIva;

            $arrayResponse['error'] = 0;
            $arrayResponse['acquirerId'] = $acquirerId;
            $arrayResponse['idCommerce'] = $idCommerce;
            $arrayResponse['purchaseOperationNumber'] = $purchaseOperationNumber;
            $arrayResponse['purchaseAmount'] = $purchaseAmount;
            $arrayResponse['purchaseCurrencyCode'] = $purchaseCurrencyCode;
            $arrayResponse['shippingFirstName'] = $_POST['PagosForm']['nombres'];
            $arrayResponse['shippingLastName'] = $_POST['PagosForm']['apellidos'];
            $arrayResponse['shippingEmail'] = $_POST['PagosForm']['email'];
            $arrayResponse['shippingAddress'] = $_POST['PagosForm']['direccion'];
            $arrayResponse['userCommerce'] = $codCardHolderCommerce;
            $arrayResponse['userCodePayme'] = $codAsoCardHolderWallet;
            $arrayResponse['descriptionProducts'] = $_POST['PagosForm']['form_descripcion'];
            $arrayResponse['purchaseVerification'] = $purchaseVerification;
            //IVA
            $arrayResponse['taxMontoFijo'] = $taxMontoFijo;
            $arrayResponse['taxMontoIVA'] = $valor_Iva;
            $arrayResponse['taxMontoNoGravaIva'] = $valor_MontoNoGravaIva;
            $arrayResponse['taxMontoGravaIva'] = $valor_MontoGravaIva;

        }else{
            $arrayResponse['error'] = 1;
        }
        return json_encode( $arrayResponse );
    }





    public function actionSetpedidovpos1(){
        require_once 'vpos_plugin_NOTAX.php';
        $arrayResponse = array();
        $url = Yii::$app->params['core_api_url'].'/setorder/'; //URL del servicio web REST
        // $url = 'http://localhost/pagomedios/web/api/setorder/';; //URL del servicio web REST
        $header = array( 'Content-Type: application/json' );
        $dataOrden = array( 'commerce_id' => $_POST['PagosForm']['commerce_id'], //ID unico por comercio
                            'customer_id' => $_POST['PagosForm']['identificacion'], //Identificación del tarjeta habiente (RUC, Cédula, Pasaporte)
                            'customer_name' => $_POST['PagosForm']['nombres'], //Nombres del tarjeta habiente
                            'customer_lastname' => $_POST['PagosForm']['apellidos'], //Apellidos del tarjeta habiente
                            'customer_phones' => $_POST['PagosForm']['telefonos'],  //Teléfonos del tarjeta habiente
                            'customer_address' => $_POST['PagosForm']['direccion'],  //Dirección del tarjeta habiente
                            'customer_email' => $_POST['PagosForm']['email'],  //Correo electrónico del tarjeta habiente
                            'customer_language' => 'es',  //Idioma del tarjeta habiente
                            'order_description' => $_POST['PagosForm']['form_descripcion'],  //Descripción de la órden
                            'order_amount' => $_POST['PagosForm']['form_total'], //Monto total de la órden
                            'order_tarifa' => $_POST['PagosForm']['base12'], //Monto total de la órden
                            'order_basecero' => $_POST['PagosForm']['base0'], //Monto total de la órden
                            'usuario_inserta'=>  $_POST['PagosForm']['usuario_id'],//usuario id
                            'taxMontoIVA' => $_POST['PagosForm']['iva'], //Monto total de la órden
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

        // order_id
        if( $responsePagomedios->status == 1 ){

            $pedido = Pedidos::findOne(['numero_pedido' => $responsePagomedios->data->order_id]);
            $pedido->purchase_operation_number = Util::generarOrdenOperacion( $pedido->id );
            $pedido->save();
            $pedido->refresh();

            $valor_total_orden = (float)$pedido->a_pagar;
            $valor_total_orden = number_format($valor_total_orden, 2, '.', '');
            $valor_total_orden = (float)$valor_total_orden * (int)100;
            $idorden = $pedido->purchase_operation_number;
            $mail = ( $pedido->empresa->ambiente != 'Producción' ) ? 'testing'.$pedido->id.'@pagomedios.com' : $pedido->cliente->email;

            $purchaseAmount = $valor_total_orden;
            //Integración calculo de IVA

            // Ejemplo:    100 + IVA
            //             10 sin iva
            
            $valor_Iva = (float)$pedido->iva; //12 USD
            $valor_Iva = number_format($valor_Iva, 2, '.', '');
            $valor_Iva = (float)$valor_Iva * (int)100;

            $valor_MontoNoGravaIva = (float)$pedido->total_sin_iva; //10
            $valor_MontoNoGravaIva = number_format($valor_MontoNoGravaIva, 2, '.', '');
            $valor_MontoNoGravaIva = (float)$valor_MontoNoGravaIva * (int)100;
            
            $valor_MontoGravaIva = (float)$pedido->total_con_iva; //100
            $valor_MontoGravaIva = number_format($valor_MontoGravaIva, 2, '.', '');
            $valor_MontoGravaIva = (float)$valor_MontoGravaIva * (int)100;


            $taxMontoFijo = $purchaseAmount; //122 // campo a_pagar contiene ya toda la sumatoria con o sin iva
            $taxMontoIVA = $valor_Iva; //12
            $taxMontoNoGravaIva = $valor_MontoNoGravaIva; //10
            $taxMontoGravaIva = $valor_MontoGravaIva; //100




            $array_send['acquirerId']=( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
            $array_send['commerceId']=$pedido->empresa->id_commerce;
            $array_send['purchaseOperationNumber']=$idorden;
            $array_send['purchaseAmount']=$valor_total_orden;
            $array_send['purchaseCurrencyCode']=840;
            $array_send['language']="SP"; //En español,
            $array_send['billingFirstName']=$pedido->cliente->nombres;
            $array_send['billingLastName']=$pedido->cliente->apellidos;
            $array_send['billingEMail']=$mail;
            $array_send['billingAddress']=$pedido->cliente->direccion;
            $array_send['billingZIP']='593';
            $array_send['billingCity']='EC';
            $array_send['billingState']='7';
            $array_send['billingCountry']='EC';
            $array_send['billingPhone']=$pedido->cliente->telefonos;

            if((Yii::$app->user->identity->empresa->procesamiento_id==1)&&(Yii::$app->user->identity->empresa->adquiriente_id==3)){
            $array_send['reserved1']= $purchaseAmount ;
            $array_send['reserved2']= $taxMontoIVA ;
            $array_send['reserved11']= $valor_MontoNoGravaIva;
            $array_send['reserved9']= $purchaseAmount + $taxMontoIVA + $valor_MontoNoGravaIva;
            $array_send['reserved12']=$idorden;
            }else{
                $array_send['reserved2']= ( $taxMontoGravaIva > 0 ) ? $taxMontoGravaIva : '000' ;
                $array_send['reserved3']= ( $taxMontoIVA > 0 ) ? $taxMontoIVA : '000' ;
                $array_send['reserved9']= ( $taxMontoNoGravaIva > 0 ) ? $taxMontoNoGravaIva : '000' ;
                $array_send['reserved10']= ( $taxMontoGravaIva > 0 ) ? $taxMontoGravaIva : '000' ;
                $array_send['reserved11']=$idorden;
            }

            $arrayOut['XMLREQ']="";
            $arrayOut['DIGITALSIGN']="";
            $arrayOut['SESSIONKEY']="";
            $VI = $pedido->empresa->vector;
            $llaveVPOSCryptoPub = $pedido->empresa->alignet_publica_cifrado_rsa;
            $llavePrivadaFirmaComercio = $pedido->empresa->llave_privada_firma_rsa;

            if (VPOSSend($array_send,$arrayOut,$llaveVPOSCryptoPub,$llavePrivadaFirmaComercio,$VI)) {
                $arrayResponse['error'] = 0;
                $arrayResponse['IDACQUIRER'] = ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
                $arrayResponse['IDCOMMERCE'] = $pedido->empresa->id_commerce;
                $arrayResponse['XMLREQ'] = $arrayOut['XMLREQ'];
                $arrayResponse['DIGITALSIGN'] = $arrayOut['DIGITALSIGN'];
                $arrayResponse['SESSIONKEY'] = $arrayOut['SESSIONKEY'];

            }else{
                $arrayResponse['error'] = 1;
            }

        }else{
            $arrayResponse['error'] = 1;
        }
        return json_encode( $arrayResponse );
    }

}
