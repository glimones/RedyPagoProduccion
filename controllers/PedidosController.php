<?php

namespace app\controllers;

use Yii;
use app\models\Pedidos;
use app\models\PedidosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Util;
use yii\filters\AccessControl;


/**
 * PedidosController implements the CRUD actions for Pedidos model.
 */
class PedidosController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete', 'bulkdelete', 'wsgetpedido', 'estadocuenta'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {            
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Pedidos models.
     * @return mixed
     */

    public function actionWsgetpedido($id){
        // Respuesta: {"errorCode":"7003","errorMessage":"Order is not registered","result":"0"}
        // Respuesta: {"authenticationECI":"06","authorizationCode":"019369","billingCountry":"","cardNumber":"485951
        // ******0051","cardType":"VISA","errorCode":"00  ","errorMessage":"Successful approval/completion","language"
        // :"SP","operationNumber":"641388389","purchaseAmount":"120000","purchaseCurrencyCode":"840","purchaseIPAddress"
        // :"181.199.77.95","result":"3 ","shippingAddress":"Su casita","shippingCity":"Pagomedios Ciudad","shippingCountry"
        // :"EC","shippingEMail":"info@abitmedia.com","shippingFirstName":"Carlos","shippingLastName":"Crespo","shippingState"
        // :"Pagomedios Esta","shippingZIP":"Pagomedios","terminalCode":""}

        $pedido = $this->findModel($id);
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
            print_r( $responsePagomedios );

        }
    }

    public function actionIndex()
    {    
        $searchModel = new PedidosSearch();
        if((Yii::$app->user->identity->es_user=='1') && ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa'])||(Yii::$app->params['todos']=='S'))){
            $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);
        }else{
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEstadocuenta()
    {    
        $searchModel = new PedidosSearch();
        if(Yii::$app->user->identity->es_user=='1'){
            $dataProvider = $searchModel->estado_cuentaUser();
        }else{
            $dataProvider = $searchModel->estado_cuenta();
        }


        return $this->render('estadocuenta', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pedidos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Pedidos #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            // Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Pedidos model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Pedidos();  
        $model->empresa_id = Yii::$app->user->identity->empresa_id;
        $model->usuario_id = Yii::$app->user->identity->id;
        $model->estado = 'Pago pendiente';
        $model->fecha_creacion = date('Y-m-d H:i:s');
        $model->pin_efectivo = time();
        $model->token = Util::getGenerarPermalink( Yii::$app->getSecurity()->generatePasswordHash('PagoMediosAbitmedia'.date('Y-m-d H:i:s').Yii::$app->user->identity->empresa_id.'PagoMediosAbitmedia'.Yii::$app->user->identity->id).strtotime(date('Y-m-d H:i:s')) );

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Solicitar pago",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Solicitar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){

                $valor_impuesto_iva = Yii::$app->params['valor_iva'];
                $valor_iva = ( $model->total_con_iva > 0) ? $model->total_con_iva : 0;
                $valor_sin_iva = ( $model->total_sin_iva > 0 ) ? $model->total_sin_iva : 0;
                $total_iva = (float)$valor_iva * (float)$valor_impuesto_iva;
                $solo_iva = (float)$total_iva - (float)$valor_iva;
                
                $model->iva = $solo_iva;
                $model->a_pagar = (float)$total_iva + (float)$valor_sin_iva;

                $model->numero_pedido = str_pad($model->id, 9, "0", STR_PAD_LEFT);
                $model->save();

                $asunto = '';
                $plantilla = '';

                if( $model->cliente->idioma == 'Español' ){
                    $asunto = 'Nueva solicitud de pago a '.$model->empresa->razon_social;
                    $plantilla = 'solicitud_es';
                    $this->pedidoIdioma = 'es'; 
                }elseif( $model->cliente->idioma == 'Inglés' ){
                    $asunto = 'New request for payment to '.$model->empresa->razon_social;
                    $plantilla = 'solicitud_en';
                    $this->pedidoIdioma = 'en';
                }

                $this->pedidoFecha = $model->fecha_creacion;
                $this->numeroPedido = $model->numero_pedido;

                $email = Yii::$app->mailer->compose($plantilla, [ 'model'=>$model ])
                ->setFrom( Yii::$app->params['notificacionesEmail'] )
                ->setTo( $model->cliente->email )
                ->setSubject( $asunto )
                ->send();

           return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Solicitar pago",
                    'content'=>'<span class="text-success">Solicitud de pago generada exitosamente, se envió un correo a su cliente con información para realizar el pago</span>',
                     'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Solicitar otro pago',['create'],['class'=>'btn btn-primary','role'=>'modal-remote']),

                ];
            }else{           
                return [
                    'title'=> "Solicitar pago",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Solicitar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            $model->tarifa_sin_iva=$model->a_pagar;
            $model->tarifa_con_iva=Yii::$app->user->identity->id;
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Pedidos model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Pedidos #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Pedidos #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Pedidos #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Pedidos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    public function actionListaclientes($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, identificacion, razon_social')
                ->from('clientes')
                ->where(['like', 'identificacion', $q])
                ->orWhere(['like', 'razon_social', $q])
                ->andWhere(['=', 'empresa_id', Yii::$app->user->identity->empresa_id])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Clientes::find($id)->identificacion.' - '.Clientes::find($id)->razon_social];
        }
        return $out;
    }

     /**
     * Delete multiple existing Pedidos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Pedidos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pedidos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedidos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
?>

