<?php

namespace app\controllers;

use Yii;
use app\models\Usuarios;
use app\models\Pedidos;
use app\models\Solicitudes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class DocumentosController extends Controller
{
    
    public function actionIndex()
    {    
    	
    }

    public function actionSolicitudregistro( $tipo = null )
    {    
        $model = new Solicitudes;
        $model->scenario = Solicitudes::SCENARIO_NATURAL;
        $this->layout = 'main_public';
        return $this->render('solicitud_afiliacion', [
           'model' => $model, 
           'tipo' => $tipo, 
        ]);
    }

}
