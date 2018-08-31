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
                      'id' => 'pagos-form',
                      'enableClientValidation' => true, 
                      'enableAjaxValidation' => false,
                      'action' => ['/forms/setpedido'],
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
                      <button id="PagoSubmitButton" style="margin-top: 20px;" type="submit" class="btn btn-primary btn-lg btn-block"> Pay</button>
                    </div>
                                          
                  <?php ActiveForm::end(); ?>


                <form name="f1" id="f1" action="<?php echo Yii::$app->params['pgp_produccion'] ?>" method="post" class="alignet-form-vpos2" style="display: none;">
                    <table>
                        <tr><td>acquirerId</td><td><input type="text" name="acquirerId" id="acquirerId" value="" /></td></tr>
                        <tr><td>idCommerce</td><td> <input type="text" name ="idCommerce" id="idCommerce" value="" /></td></tr>
                        <tr><td>purchaseOperationNumber </td><td><input type="text" name="purchaseOperationNumber" id="purchaseOperationNumber" value="" /></td></tr>
                        <tr><td>purchaseAmount </td><td><input type="text" name="purchaseAmount" id="purchaseAmount" value="" /></td></tr>
                        <tr><td>purchaseCurrencyCode </td><td><input type="text" name="purchaseCurrencyCode" id="purchaseCurrencyCode" value="" /></td></tr>
                        <tr><td>language </td><td><input type="text" name="language" value="EN" /></td></tr>                
                        <tr><td>shippingFirstName </td><td><input type="text" name="shippingFirstName" id="shippingFirstName" value="" /></td></tr>
                        <tr><td>shippingLastName </td><td><input type="text" name="shippingLastName" id="shippingLastName" value="" /></td></tr>
                        <tr><td>shippingEmail </td><td><input type="text" name="shippingEmail" id="shippingEmail" value="" /></td></tr>
                        <tr><td>shippingAddress </td><td><input type="text" name="shippingAddress" id="shippingAddress" value="" /></td></tr>
                        <tr><td>shippingZIP </td><td><input type="text" name="shippingZIP" value="Pagomedios Zip" /></td></tr>
                        <tr><td>shippingCity </td><td><input type="text" name="shippingCity" value="Pagomedios Ciudad" /></td></tr>
                        <tr><td>shippingState </td><td><input type="text" name="shippingState" value="Pagomedios Estado" /></td></tr>
                        <tr><td>shippingCountry </td><td><input type="text" name="shippingCountry" value="EC" /></td></tr>                
                <!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codCardHolderCommerce.-->
                        <tr><td>userCommerce </td><td><input type="text" name="userCommerce" id="userCommerce" value="" /></td></tr> <!-- 0101010101 -->
                <!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codAsoCardHolderWallet.-->
                        <tr><td>userCodePayme </td><td><input type="text" name="userCodePayme" id="userCodePayme" value="" /></td></tr> <!-- 5--420--2340 -->
                        <tr><td>descriptionProducts </td><td><input type="text" name="descriptionProducts" id="descriptionProducts" value="" /></td></tr>
                        <tr><td>programmingLanguage </td><td><input type="text" name="programmingLanguage" id="programmingLanguage" value="PHP" /></td></tr>
                <!--Ejemplo envío campos reservados en parametro reserved1.-->
                        <tr><td>reserved1 </td><td><input type="text" name="reserved1" value="" /></td></tr>
                        <tr><td>purchaseVerification </td><td><input type="text" name="purchaseVerification" id="purchaseVerification" value="" /></td></tr>
                        <!-- IVA -->
                        <tr><td>taxMontoFijo</td><td><input type="text" name="taxMontoFijo" id="taxMontoFijo" value="" /></td></tr>
                        <tr><td>taxMontoIVA</td><td><input type="text" name="taxMontoIVA" id="taxMontoIVA" value="" /></td></tr>
                        <tr><td>taxMontoNoGravaIva</td><td><input type="text" name="taxMontoNoGravaIva" id="taxMontoNoGravaIva" value="" /></td></tr>
                        <tr><td>taxMontoGravaIva</td><td><input type="text" name="taxMontoGravaIva" id="taxMontoGravaIva" value="" /></td></tr>
                    </table>
                </form>
                
                </div>
            </div>
        </div>
    </div>
</div>
