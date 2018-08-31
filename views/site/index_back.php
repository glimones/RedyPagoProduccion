<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Abitmedia - Pagomedios (Simulador)';
?>
<div class="site-index">

    <div class="encabezado-comercio">
        <?php echo Html::img( Url::to('@web/images/logo_comercio.jpg'), ['class'=> 'img-circle logo-comercio']) ?>
    </div>

    <div class="body-content">

        <div class="row-fluid">
            <div class="col-md-6">
                <div class="formulario-infomacion">
                    <div class="titulo-form">Datos de la compra</div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            Comercio:
                        </div>
                        <div class="col-md-4">
                            Abitmedia
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            Nro. de pedido:
                        </div>
                        <div class="col-md-4">
                            514654654651
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            Importe:
                        </div>
                        <div class="col-md-4">
                            <span class="titulo-form">100.00 USD</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="formulario-infomacion">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="titulo-form">Datos de tarjeta</div>
                        </div>
                        <div class="col-md-4">
                            <?php echo Html::img( Url::to('@web/images/visa-mastercard.png'), ['class'=> 'pull-right']) ?>
                        </div>
                    </div>
                    <hr>
                    

                    <?php $form = ActiveForm::begin([
                        'id' => 'pasarela-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'control-label'],
                        ],
                    ]); ?>

                        <div class="row-fluid">
                            <div class="col-md-5 radio_contenedor">
                                <?= $form->field($model, 'tipo_tarjeta')->radioList([
                                            'MasterCard' => Html::img( Url::to('@web/images/mastercard.png')),
                                            'Visa' => Html::img( Url::to('@web/images/visa.png'))
                                ])->label(false) ?>
                            </div>
                            <div class="col-md-7">
                                <?= $form->field($model, 'numero_tarjeta', ['template' => '
                                   {label}
                                   <div class="input-group col-sm-12 ">
                                      {input}
                                      <span class="input-group-addon">
                                         <span class="glyphicon glyphicon-credit-card"></span>
                                      </span>
                                   </div>
                                   {error}{hint}
                                ']
                                )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Ingrese número de tarjeta'])?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-md-6">
                                <?= $form->field($model, 'codigo_seguridad')->textInput([]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'fecha_expiracion_mes')->textInput([]) ?>    
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'fecha_expiracion_anio')->textInput([]) ?>   
                            </div>
                        </div>
                        

                        <?php
                        // $form->field($model, 'rememberMe')->checkbox([
                        //     'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        // ]) 
                        ?>

                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-11">
                                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            </div>
                        </div>

                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>

    </div>
</div>
