<?php

namespace app\controllers;

use Yii;
use app\models\Empresas;
use app\models\EmpresasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Pedidos;
use app\models\PedidosSearch;

/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class MailController extends Controller
{
    public $pedidoIdioma;
    public $pedidoFecha;
    public $numeroPedido;

    public function actionIndex()
    {    
    	$model = Pedidos::findOne(956);

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
        
        $this->layout = '@app/mail/layouts/html';
        return $this->render('mail', [
        	'model'=>$model
        ]);
    }

}
