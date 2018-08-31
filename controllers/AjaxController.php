<?php

namespace app\controllers;

use app\models\Pedidos;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\models\Empresas;
use app\models\Util;
use app\models\Clientes;
use app\models\Localizaciones;
use app\models\AdquirentesProcesamientos;

/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class AjaxController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionSubirimagenpagomedios()
    {
        $model = new Empresas();
        $image = UploadedFile::getInstance($model, 'comp_logo_pagomedios');
        if( $image ){
            $model->logo = Util::getGenerarPermalink( Yii::$app->security->generateRandomString() ). '.' . $image->extension;
            $path = \Yii::getAlias('@webroot') .'/logos_comercios/' . $model->logo;
            $pathweb = \Yii::getAlias('@web') .'/logos_comercios/' . $model->logo;
            if( $image->saveAs($path) ){
                return Json::encode([
                    [
                        'name' => $model->logo,
                        'size' => $image->size,
                        'url' => $pathweb,
                        'thumbnailUrl' => $path,
                        // 'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ]);
            }
        }
        return '';
    }

    public function actionSubirimagenpayme()
    {
        $model = new Empresas();
        $image = UploadedFile::getInstance($model, 'comp_logo_payme');
        if( $image ){
            $model->logo_payme = Util::getGenerarPermalink( Yii::$app->security->generateRandomString() ). '.' . $image->extension;
            $path = \Yii::getAlias('@webroot') .'/logos_comercios/' . $model->logo_payme;
            $pathweb = \Yii::getAlias('@web') .'/logos_comercios/' . $model->logo_payme;
            if( $image->saveAs($path) ){
                return Json::encode([
                    [
                        'name' => $model->logo_payme,
                        'size' => $image->size,
                        'url' => $pathweb,
                        'thumbnailUrl' => $path,
                        // 'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ]);
            }
        }
        return '';
    }

    public function actionInformacioncliente(){
        $id = $_POST['id'];
        $cliente = Clientes::find()->andWhere(['empresa_id'=>Yii::$app->user->identity->empresa_id, 'identificacion'=>$id])->one();
        if( is_object( $cliente ) ){
            return json_encode( array_merge( $cliente->attributes, ['error'=>0] ) );
        }else{
            return json_encode(['error'=>1]);
        }
    }


    public function actionProcesamientosadquirentes(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $procesamientos = AdquirentesProcesamientos::find()->andWhere( ['adquirente_id'=>$cat_id] )->all();
                foreach ($procesamientos as $pro) {
                    $out [] = ['id'=>$pro->procesamiento_id, 'name'=>$pro->procesamiento->nombre]; 
                }
                return Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        return Json::encode(['output'=>'', 'Seleccione'=>'']);
    }

    public function actionLocalizaciones(){
        $locales = Localizaciones::find()->all();
        $array = [];
        foreach ($locales as $lo) {
            $array[] = [ 'lat'=>$lo->lat, 'lon'=>$lo->lon, 'nombre'=>$lo->nombre, 'direccion'=>$lo->direccion ];
        }
        return json_encode( $array );
    }
    public function actionAlertcopiaurl(){
           Yii::$app->getSession()->setFlash('success', [
               'type' => 'success',
               'duration' => 4000,
               'icon' => 'glyphicon glyphicon-ok-sign',
               'message' => 'Url Copiada con éxito',
               'title' => 'Redypago',
               'positonY' => 'bottom',
               'positonX' => 'right'
           ]);
        $this->redirect(['pedidos/index']);
    }
    public function actionAlertcopiaurl2(){
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 4000,
            'icon' => 'glyphicon glyphicon-ok-sign',
            'message' => 'Url Copiada con éxito',
            'title' => 'Redypago',
            'positonY' => 'bottom',
            'positonX' => 'right'
        ]);
        $this->redirect(['formularios/index']);
    }
}
