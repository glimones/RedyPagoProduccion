<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Redypago';
?>
<div class="site-index">
    <div class="encabezado-comercio">
        <?php echo Html::img( Url::to('@web/logos_comercios/'.$model->empresa->logo ), ['class'=> 'img-circle logo-comercio']) ?>
    </div>
    <input type="hidden" id="PagmediosAmbiente" name="PagmediosAmbiente" value="<?php echo $model->empresa->ambiente; ?>">
    <div class="body-content">

        <div class="row-fluid">
            <!-- <div class="col-xs-8 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3"> -->
            <div class="col-md-12">
                <div class="formulario-infomacion">
                <div class="row">
                    <div class="col-md-8">
                        <div class="titulo-form">Payment form</div>
                    </div>
                    <div class="col-md-4">
                        <?php echo Html::img( Url::to('@web/images/visa-mastercard.png'), ['class'=> 'pull-right']) ?>
                    </div>
                </div>
                <hr>


                <?php $form = ActiveForm::begin([
                      'id' => 'pagos-form-vpos1',
                      'enableClientValidation' => true, 
                      'enableAjaxValidation' => false,
                      'action' => ['/forms/setpedidovpos1'],
                      // 'layout' => 'horizontal',
                      'fieldConfig' => [
                          'template' => "{label}\n{input}\n{error}",
                          // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
                      ],
                  ]); ?>

                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                              <div class="col-md-6">

                                  <?= $form->field($form_pago, 'identificacion', ['template' => '
                                        <strong>Identification number</strong>
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Enter your identification number', 'tabindex'=>1]) ?>


                                     <?= $form->field($form_pago, 'apellidos', ['template' => '
                                        <strong>Last Name</strong>
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Enter your Last Name', 'tabindex'=>3]) ?>


                                     <?= $form->field($form_pago, 'telefonos', ['template' => '
                                        <strong>Phone</strong>
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Enter your Phone', 'tabindex'=>5]) ?>

                              </div>
                              <div class="col-md-6">
                                    <?= $form->field($form_pago, 'nombres', ['template' => '
                                        <strong>First Name</strong>
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Enter your First Name', 'tabindex'=>2]) ?>

                                    <?= $form->field($form_pago, 'email', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Enter your e-mail', 'tabindex'=>4]) ?>


                                     <?= $form->field($form_pago, 'direccion', ['template' => '
                                        <strong>Address</strong>
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textArea(['class'=>'form-control', 'placeholder'=>'Enter your Address', 'tabindex'=>6]) ?>

                                    <?php
                                    $valor_MontoNoGravaIva = $model->base0;
                                    $valor_MontoGravaIva = $model->base12;
                                    $valor_Iva = $model->iva;
                                    $usuario_id = $model->usuario_id;
                                    ?>

                                    <?= $form->field($form_pago, 'commerce_id')->hiddenInput(['value'=>$model->empresa->id_commerce])->label(false); ?>
                                    <?= $form->field($form_pago, 'form_descripcion')->hiddenInput(['value'=>$model->descripcion])->label(false); ?>
                                    <?= $form->field($form_pago, 'form_total')->hiddenInput(['value'=>$model->precio])->label(false); ?>
                                    <?= $form->field($form_pago, 'base12')->hiddenInput(['value'=>$valor_MontoGravaIva])->label(false); ?>
                                    <?= $form->field($form_pago, 'base0')->hiddenInput(['value'=>$valor_MontoNoGravaIva])->label(false); ?>
                                    <?= $form->field($form_pago, 'iva')->hiddenInput(['value'=>$valor_Iva])->label(false); ?>
                                    <?= $form->field($form_pago, 'usuario_id')->hiddenInput(['value'=>$usuario_id])->label(false); ?>

                              </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                       
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Commerce:</strong>
                                </div>
                                <div class="col-md-8 text-right">
                                    <?php echo $model->empresa->razon_social; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-md-8 text-right">
                                    <?php echo $model->descripcion; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Amount Due:</strong>
                                </div>
                                <div class="col-md-8 text-right">
                                    <span class="titulo-form">$<?php echo $model->precio; ?> USD</span>
                                </div>
                            </div>
                      <button id="PagoSubmitButton" style="margin-top: 20px;" type="submit" class="btn btn-primary btn-lg btn-block"> Pagar</button>
                    </div>
                                          
                  <?php ActiveForm::end(); ?>


                    <form style="display:none;" name="params_form" id="f1" method="post" action="<?php echo ( $model->empresa->ambiente == 'Producción' || $model->empresa->ambiente == 'Test-Producción' ) ? Yii::$app->params['vpos1_produccion'] : Yii::$app->params['vpos1_testing'] ?>" >

                       <table border="0">
                          <tr>
                            <td>IDACQUIRER:</td>
                            <td><input name="IDACQUIRER" id="IDACQUIRER" value=""></td>
                          </tr>
                          <tr>
                            <td>COMMERCE:</td>
                            <td><input name="IDCOMMERCE" id="IDCOMMERCE" value=""></td>
                          </tr>
                          <tr>
                            <td>XML:</td>
                            <td><input name="XMLREQ" id="XMLREQ" value=""></td>
                          </tr>
                          <tr>
                            <td>SIGNATURE:</td>
                            <td><input name="DIGITALSIGN" id="SIGNATURE" value=""></td>
                          </tr>
                          <tr>
                            <td>SESSIONKEY:</td>
                            <td><input name="SESSIONKEY" id="SESSIONKEY" value=""></td>
                          </tr>
                          <tr>
                            <td><input type="submit" name="envio" id="envio" value="Enviar" /></td>
                          </tr>
                        </table>

                    </form>
                
                </div>
            </div>
        </div>
    </div>
</div>