<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RecuperarForm;
use app\models\PasarelaForm;
use app\models\ContactForm;
use app\models\Util;
use app\models\Usuarios;

class SiteController extends Controller
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
                'only' => ['logout', 'index', 'micuenta'],
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */

    public function actionMicuenta(){
        $request = Yii::$app->request;
        $model = Usuarios::findOne( Yii::$app->user->identity->id );
        $modelOld = Usuarios::findOne( Yii::$app->user->identity->id );

        if($model->load($request->post()) && $model->save()){
            if( $modelOld->clave != $model->clave ){
                $model->clave = Yii::$app->getSecurity()->generatePasswordHash( $model->clave );
                $model->save();

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'message' => 'Datos actualizados exitosamente',
                    'title' => 'Redypago',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            }
        }

        return $this->render('micuenta', [
            'model' => $model,
        ]);
    }

    public function actionRecuperar(){
        $usuario = Usuarios::find()->where('email = "'.trim( $_POST['RecuperarForm']['email'] ).'"')->one();
        if( is_object( $usuario ) ){
            $clave_temporal = Util::generateRandomString(10);
            $usuario->clave = Yii::$app->getSecurity()->generatePasswordHash( $clave_temporal );
            $usuario->save();

            $asunto = 'Recuperar clave';
            $plantilla = 'recuperar_clave';

            $this->pedidoFecha = '';
            $this->numeroPedido = '';

            $email = Yii::$app->mailer->compose($plantilla, [ 'model'=>$usuario, 'clave_temporal' => $clave_temporal ])
            ->setFrom( Yii::$app->params['notificacionesEmail'] )
            ->setTo( $usuario->email )
            ->setSubject( $asunto )
            ->send();

            echo 'Se le envio un e-mail con una clave temporal';
            

        }else{
            echo 'No se encontró un usuario asociado al correo ingresado';
        }
    }

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

    public function actionPago($express=null){
        if( is_null( $express ) ){
            echo 'No se encontro la orden';
        }else{
            echo $express;
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 4000,
            'icon' => 'glyphicon glyphicon-ok-sign',
            'message' => Yii::$app->user->identity->nombres.' bienvenid@ a Redypago, La mejor forma de optimizar tu dinero.',
            'title' => 'Redypago',
            'positonY' => 'bottom',
            'positonX' => 'right'
        ]);


        $meses = Util::getMesesReportes();
        $solicitudes = array();
        $procesadas = array();
        if( Yii::$app->user->identity->es_super == 1 ){
            foreach ($meses as $key => $value) {
                $solicitudes [] = Util::getTransaccionesPorMes($key, date('Y'), 1);
                $procesadas [] = Util::getTransaccionesProcesadasPorMes($key, date('Y'), 1);
            }
            $ultimas_autorizadas = \app\models\Pedidos::find()->where('estado = "Autorizado" and YEAR(fecha_pago) = "'.date('Y').'" ')->orderBy(['fecha_pago' => SORT_DESC])->limit(6)->all();
            $sumatoria_autorizadas = \app\models\Pedidos::find()->where('estado = "Autorizado" and YEAR(fecha_pago) = "'.date('Y').'" ')->sum('a_pagar');
        }elseif ((Yii::$app->user->identity->es_admin == 1) && (Yii::$app->user->identity->es_user == 0)) {
            foreach ($meses as $key => $value) {
                $solicitudes [] = Util::getTransaccionesPorMes($key, date('Y'));
                $procesadas [] = Util::getTransaccionesProcesadasPorMes($key, date('Y'));
            }
            $ultimas_autorizadas = \app\models\Pedidos::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id.' and estado = "Autorizado" and YEAR(fecha_pago) = "'.date('Y').'" ')->orderBy(['fecha_pago' => SORT_DESC])->limit(6)->all();
            $sumatoria_autorizadas = \app\models\Pedidos::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id.' and estado = "Autorizado" and YEAR(fecha_pago) = "'.date('Y').'" ')->sum('a_pagar');
        } elseif((Yii::$app->user->identity->es_user == 1) && ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa'])|| (Yii::$app->params['todos']=='S'))) {
            foreach ($meses as $key => $value) {
                $solicitudes [] = Util::getTransaccionesPorMesUser($key, date('Y'));
                $procesadas [] = Util::getTransaccionesProcesadasPorMesUser($key, date('Y'));
            }
            $ultimas_autorizadas = \app\models\Pedidos::find()
                ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id.' and pedidos.usuario_id="'.Yii::$app->user->identity->id.'" and pedidos.estado = "Autorizado" and YEAR(pedidos.fecha_pago) = "'.date('Y').'" ')
                ->orderBy(['fecha_pago' => SORT_DESC])->limit(6)->all();
            $sumatoria_autorizadas = \app\models\Pedidos::find()
                ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id.' and pedidos.usuario_id="'.Yii::$app->user->identity->id.'" and pedidos.estado = "Autorizado" and YEAR(pedidos.fecha_pago) = "'.date('Y').'" ')
                ->sum('a_pagar');
        }
        return $this->render('index', [
            'solicitudes' => $solicitudes,
            'procesadas' => $procesadas,
            'ultimas_autorizadas' => $ultimas_autorizadas,
            'sumatoria_autorizadas' => $sumatoria_autorizadas,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $recuperar = new RecuperarForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
            'recuperar' => $recuperar,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
