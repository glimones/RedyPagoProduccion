<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Empresas;
use app\models\EmpresasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Pedidos;
use app\models\Util;
use yii\helpers\Url;
use app\models\PedidosOperaciones;



/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class IntegracionesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['testing', 'produccion', 'generarcaso', 'eliminar'],
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
        if (!Yii::$app->user->isGuest) {
            if ( Yii::$app->user->identity->es_super != 1 ) {
                $this->redirect(['site/index']);
                return false;
            }
        }
        return parent::beforeAction($action);
    }

    public function actionTesting()
    {    
        return $this->render('testing');
    }

    public function actionProduccion()
    {    
        return $this->render('produccion');
    }

    public function actionGenerarcaso(){
        $model = new Pedidos();  
        $model->empresa_id = $_POST['empresa_id'];
        $model->usuario_id = Yii::$app->user->identity->id;
        $model->estado = 'Pago pendiente';
        $model->fecha_creacion = date('Y-m-d H:i:s');
        $model->token = Util::getGenerarPermalink( Yii::$app->getSecurity()->generatePasswordHash('PagoMediosAbitmedia'.date('Y-m-d H:i:s').Yii::$app->user->identity->empresa_id.'PagoMediosAbitmedia'.Yii::$app->user->identity->id).strtotime(date('Y-m-d H:i:s')) );

        $subtotal = (float)$_POST['monto'] / (float)Yii::$app->params['valor_iva'];

        $model->cliente_id = 1;
        $model->a_pagar = $_POST['monto'];
        $model->descripcion = 'Sesión testing '.$_POST['caso'];
        $model->testing = 1;
        $model->total_con_iva = (float)$subtotal;
        $model->iva = (float)$_POST['monto'] - (float)$subtotal;
        $model->total_sin_iva = 0;

        $model->save();
        $model->numero_pedido = str_pad($model->id, 9, "0", STR_PAD_LEFT);
        if( $model->save() ){
            return json_encode( array( "url" => Url::to(['payments/t?token='.$model->token], true), "target" => "_blank" ) );
        }else{
            return json_encode( array( "errors" => $model->getErrors() ) );
        }
    }

    public function actionEliminar(){
        $pedidos = Pedidos::find()->where('testing = 1')->all();
        foreach ($pedidos as $pedido) {
            foreach ($pedido->pedidosOperaciones as $p) {
                $p->delete();
            }
            $pedido->delete();
        }
    }
    public function actionAlertcopiaurl(){
     /*   Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 4000,
            'icon' => 'glyphicon glyphicon-ok-sign',
            'message' => 'Url Copiada con exito',
            'title' => 'Redypago',
            'positonY' => 'bottom',
            'positonX' => 'right'
        ]);
      /*  $this->layout="sintel";
        $model= new Customers();
        \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
        return $this->render("function4",['model'=>$model]);*/
    }
}
