<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\nav\NavX;
use kartik\sidenav\SideNav;
use yii\widgets\DetailView;
use app\models\Clientes;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'showProgressbar' => true,
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
    ?>
<?php endforeach; ?>

<input type="hidden" name="base_url" id="base_url" value="<?php echo Yii::getAlias('@web') ?>">
<input type="hidden" name="valor_iva" id="valor_iva" value="<?php echo Yii::$app->params['valor_iva'] ?>">
<input type="hidden" name="display_iva" id="display_iva" value="<?php echo Yii::$app->params['display_iva'] ?>">
<?php $this->beginBody() ?>


    <?php
    NavBar::begin([
        'brandLabel' => Html::img( Url::to('@web/images/redypago-interna.png'), ['class'=> 'logo-admin']),
        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class' => 'container-fluid'],
        'options' => [
            'class' => 'navbar navbar-inverse navbar-fixed-top',
        ],
    ]);
    if( Yii::$app->user->identity->es_admin == 1 ){
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                [
                    'url' => ['site/index'],
                    'label' => 'Resumen',
                    'icon' => 'stats',
                ],
                [
                    'url' => ['pedidos/estadocuenta'],
                    'label' => 'Estado de cuenta',
                    'icon' => 'credit-card',
                ],
                [
                    'url' => ['clientes/index'],
                    'label' => 'Clientes',
                    'icon' => 'user',
                ],
                [
                    'url' => ['forms/cobrodirecto'],
                    'label' => 'Cobro directo',
                    'icon' => 'credit-card',
                ],
                [
                    'url' => ['pedidos/index'],
                    'label' => 'Solicitudes de pago',
                    'icon' => 'credit-card',
                ],
                [
                    'url' => ['formularios/index'],
                    'label' => 'Formularios de pago',
                    'icon' => 'credit-card',
                ],
                // [
                //     'url' => ['pedidos/index'],
                //     'label' => 'TPV',
                //     'icon' => 'usd',
                // ],
                // [
                //     'label' => 'Administración',
                //     'icon' => 'book',
                //     'items' => [
                //         // [
                //         //     'url' => ['pedidos/index'],
                //         //     'label' => 'Familias de productos',
                //         // ],
                //         // [
                //         //     'url' => ['pedidos/index'],
                //         //     'label' => 'Marcas de productos',
                //         // ],
                //         // [
                //         //     'url' => ['pedidos/index'],
                //         //     'label' => 'Productos',
                //         // ],
                //         [
                //             'url' => ['usuarios/index'],
                //             'label' => 'Usuarios',
                //         ],
                //     ],
                // ],
                '<li class="dropdown usuario-panel"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.Html::img( Url::to('@web/images/usuario-icono.png'), ['class'=> 'usuario-icono']).' '.Yii::$app->user->identity->nombres.' <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="'.Url::to(['site/micuenta'], true).'"><i class="icon-user"></i> Mi cuenta</a></li>
                        <li><a data-toggle="modal" href="javascript:;" data-target="#modal_licencia"><i class="icon-user"></i> Información de licencia</a></li>
                        <li><a target="_blank" href="http://web.redypago.com/#preguntas"><i class="icon-user"></i> Preguntas Frecuentes</a></li>
                        <li class="divider"></li>
                        <li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Cerrar sesión',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>
                    </ul>
                </li>',
                
            ],
        ]);
    }elseif ( Yii::$app->user->identity->es_super == 1 ) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                [
                    'url' => ['site/index'],
                    'label' => 'Resumen',
                    'icon' => 'stats',
                ],
                [
                    'url' => ['pedidos/estadocuenta'],
                    'label' => 'Transacciones',
                    'icon' => 'credit-card',
                ],
                [
                    'url' => ['adquirentes/index'],
                    'label' => 'Adquirentes',
                    'icon' => 'user',
                ],
                [
                    'url' => ['empresas/index'],
                    'label' => 'Empresas',
                    'icon' => 'briefcase',
                ],
                [
                    'url' => ['integraciones'],
                    'label' => 'Integraciones',
                    'items' => [
                        [
                            'url' => ['integraciones/testing'],
                            'label' => 'Ambiente Testing',
                        ],
                        [
                            'url' => ['integraciones/produccion'],
                            'label' => 'Ambiente Producción',
                        ],
                    ],
                ],
                [
                    'url' => ['pedidos'],
                    'label' => 'Transacciones globales',
                    'items' => [
                        [
                            'url' => ['pedidos/index'],
                            'label' => 'Redypago',
                            'icon' => 'usd',
                        ],
                        [
                            'url' => ['transacciones-descomplicate/index'],
                            'label' => 'Descomplicate',
                            'icon' => 'usd',
                        ],
                    ],
                ],
                
                [
                    'url' => ['usuarios/index'],
                    'label' => 'Usuarios',
                    'icon' => 'user',
                ],
                '<li class="dropdown usuario-panel"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.Html::img( Url::to('@web/images/usuario-icono.png'), ['class'=> 'usuario-icono']).' '.Yii::$app->user->identity->nombres.' <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="javascript:;" data-target="#modal_licencia"><i class="icon-user"></i> Suscripción</a></li>
                        <li><a target="_blank" href="http://soporte.abitmedia.com/"><i class="icon-envelope"></i> Soporte técnico</a></li>
                        <li class="divider"></li>
                        <li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Cerrar sesión',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>
                    </ul>
                </li>',
            ],
        ]);
    }
    NavBar::end();
    ?>

    <?php
    
    // NavBar::begin([
    //     'brandLabel' => Html::img( Url::to('@web/images/logo_pagomedios_interno.png'), ['class'=> 'logo-admin']),
    //     'brandUrl' => Yii::$app->homeUrl,
    //     'innerContainerOptions' => ['class' => 'container-fluid'],
    //     'options' => [
    //         'class' => 'navbar navbar-inverse navbar-fixed-top',
    //     ],
    // ]);
    



    // $usuario = ( is_null(Yii::$app->user->identity->empresa_id) ) ? 'SuperAdministrador' : Yii::$app->user->identity->empresa->razon_social;
    // $menu = [   '<li>'.
    //             '<button style="margin-top:8px;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_licencia">
    //             <span class="glyphicon glyphicon-home"></span> '.
    //             $usuario
    //             .'</button>'
    //             .'</li>',
    //             '<li>'
    //             . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
    //             . Html::submitButton(
    //                 '<span class="glyphicon glyphicon-off"></span> Cerrar sesión',
    //                 ['class' => 'btn btn-primary']
    //             )
    //             . Html::endForm()
    //             . '</li>'
    //         ];
    // echo Nav::widget([
    //     'options' => ['class' => 'navbar-nav navbar-right'],
    //     'encodeLabels' => false,
    //     'items' => $menu,
    // ]);
    // NavBar::end();
    ?>

<div class="col-md-12 main">
    
        <div class="row bloque-interfaz">
            <div class="col-md-12 bloque-interfaz-interno">
                <?= $content ?>
            </div>
        </div>
        
</div>
<?php 
    if( Yii::$app->user->identity->es_super == 0 ){
        Modal::begin(['id' => 'modal_licencia',
            'header' => '<h4>Detalle de empresa</h4>']);

            echo DetailView::widget([
            'model' => Yii::$app->user->identity->empresa,
            'attributes' => [
                'ruc',
                'razon_social',
                'contacto_cedula',
                'contacto_nombres',
                'contacto_apellidos',
                'direccion:ntext',
                'email:email',
                'actividades:ntext',
                'fecha_afiliacion',
                'ambiente',
            ],
            ]);
            // echo DetailView::widget([
            // 'model' => Yii::$app->user->identity->empresa->getSuscripciones()->orderBy('fecha DESC')->one(),
            // 'attributes' => [
            //     'fecha',
            // ],
            // ]);

        Modal::end();
    }
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
