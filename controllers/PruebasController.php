<?php

namespace app\controllers;

use Yii;
use app\models\Usuarios;
use app\models\Pedidos;
use app\models\UsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class PruebasController extends Controller
{
    

	public $pedidoIdioma;
    public $pedidoFecha;
    public $numeroPedido;
    
    public function actionIndex()
    {   
        ini_set('max_execution_time', 0);
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M');
        ini_set('memory_limit', '2024M'); 
        $pedidos = Pedidos::find()->all();
        foreach ($pedidos as $model) {
            
            $subtotal = (float)$model->a_pagar / (float)Yii::$app->params['valor_iva'];
            $model->total_con_iva = (float)$subtotal;
            $model->iva = (float)$model->a_pagar - (float)$subtotal;
            $model->total_sin_iva = 0;

            if(!$model->save()){
                echo '<pre>';
                print_r($model->getErrors());
                echo '</pre>';
            }

        }
    	// $pedido = Pedidos::findOne( 8012 );
     //    if( $pedido->empresa->ambiente == 'Producción' ){
     //        if( isset( $pedido->usuario ) ){
     //            $asunto = 'Notificación de pago recibido';
     //            $plantilla = 'pago_recibido';

     //            $this->pedidoFecha = $pedido->fecha_pago;
     //            $this->numeroPedido = $pedido->numero_pedido;

     //            $email = Yii::$app->mailer->compose($plantilla, [ 'model'=>$pedido ])
     //            ->setFrom( Yii::$app->params['notificacionesEmail'] )
     //            ->setTo( $pedido->usuario->email )
     //            ->setSubject( $asunto )
     //            ->send();
     //        }
     //    }
    }

}
