<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use kartik\money\MaskMoney;

$this->title = 'Redypago';
?>
<div class="site-index">
    
    <div class="body-content">    <input type="hidden" id="PagmediosAmbiente" name="PagmediosAmbiente" value="<?php echo Yii::$app->user->identity->empresa->ambiente; ?>">


        <div class="row-fluid">
            <!-- <div class="col-xs-8 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3"> -->
            <div class="col-md-12">
                <div class="formulario-infomacion">
                <div class="row">
                    <div class="col-md-8">
                        <h1>Formulario de cobro directo</h1>
                    </div>
                    <div class="col-md-4">
                        <?php echo Html::img( Url::to('@web/images/visa-mastercard.png'), ['class'=> 'pull-right']) ?>
                    </div>
                </div>
                <hr>


                <?php $form = ActiveForm::begin([
                      'id' => 'cobro-directo-form',
                      'enableClientValidation' => true, 
                      'enableAjaxValidation' => false,
                      'action' => ['/forms/cobrodirecto'],
                      // 'layout' => 'horizontal',
                      'fieldConfig' => [
                          'template' => "{label}\n{input}\n{error}",
                          // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
                      ],
                  ]); ?>
<!--
Se parametriza la empresa alignet para que puedan visualizar los cambios en produccion

-->
                    <?php  if ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa']) || (Yii::$app->params['todos']=='S')) { ?><!--Ini GLimones-->

                    <div class="row">

                              <div class="col-md-6">
                              <div class="col-md-18">
                                  <div class="row col-md-10" id="ExteriorResponsive">
                                      <input type="checkbox" id="extranjero">&nbsp;Exterior
                                  </div>
                                  <div class="row col-md-10">
                                    <?= $form->field($form_pago, 'identificacion', ['template' => '
                                        {label}                                                                               
                                            <div class="input-group col-sm-12">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                             </div>
                                         {error}
                                         <p id="cedulaInc" style="display: none; color:#a94442;">Error cedula incorrecta</p>
                                         {hint}
                                     ']
                                     )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Ingrese su identificación', 'tabindex'=>1]) ?>
                                  </div>
                                  <div class="row" id="validaExterior">
                                      <br>
                                      &nbsp;
                                      <div class="input-group col-sm-2">
                                          &nbsp;&nbsp;<input type="checkbox" id="extranjero1">&nbsp;Exterior
                                      </div>
                                  </div>
                              </div>
                                    <?= $form->field($form_pago, 'nombres', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus nombres', 'tabindex'=>1]) ?>

                                     <?= $form->field($form_pago, 'apellidos', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus apellidos', 'tabindex'=>2]) ?>

                              </div>
                              <div class="col-md-6">

                                      <?= $form->field($form_pago, 'email', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese su e-mail', 'tabindex'=>3]) ?>

                                      <?= $form->field($form_pago, 'form_descripcion', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textArea(['class'=>'form-control', 'placeholder'=>'Ingrese motivo del cobro', 'tabindex'=>4]) ?>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <?= $form->field($form_pago, 'form_base12', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                          )->textInput(['autofocus' => true,'class'=>'form-control', 'tabindex'=>5])->widget(MaskMoney::className(), ['value' => 0.01,
                                          'pluginOptions' => [
                                          'prefix' => '$',
                                          //'suffix' => '',
                                          //'affixesStay' => true,
                                          'decimal' => '.',
                                          'precision' => 2,
                                          'allowZero' => true,
                                          //'allowNegative' => true,
                                          ],
                                          ]) ?>

                                      </div>
                                      <div class="col-md-6">
                                          <?= $form->field($form_pago, 'form_iva', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                          )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus nombres', 'disabled'=>true,'value'=>'0.00','tabindex'=>1])

                                          ?>
                                      </div>
                                  </div>
                                      <div class="row">
                                      <div class="col-md-6">
                                          <?= $form->field($form_pago, 'form_base0', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     '])->textInput(['autofocus' => true,'class'=>'form-control', 'tabindex'=>5])->widget(MaskMoney::className(), [ 'value' => 0.01,
                                              'pluginOptions' => [
                                                  'prefix' => '$',
                                                  //'suffix' => '',
                                                  //'affixesStay' => true,
                                                  'decimal' => '.',
                                                  'precision' => 2,
                                                  'allowZero' => true,
                                                 // 'allowNegative' => true,
                                              ],
                                          ]) ?>

                                      </div>
                                          <div class="col-md-6">
                                          <?= $form->field($form_pago, 'form_total', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                          )->textInput(['class'=>'form-control', 'value'=>'0.00','disabled'=>true,'placeholder'=>'Ingrese sus nombres', 'tabindex'=>1]) ?>
                                      </div>
                                      </div>
                                    <?= $form->field($form_pago, 'commerce_id')->hiddenInput(['value'=>Yii::$app->user->identity->empresa->id_commerce])->label(false); ?>
                              </div>
                            <?php }else{ ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($form_pago, 'identificacion', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Ingrese su identificación', 'tabindex'=>1]) ?>

                                    <?= $form->field($form_pago, 'nombres', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus nombres', 'tabindex'=>1]) ?>

                                     <?= $form->field($form_pago, 'apellidos', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus apellidos', 'tabindex'=>2]) ?>

                              </div>
                              <div class="col-md-6">

                                      <?= $form->field($form_pago, 'email', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese su e-mail', 'tabindex'=>3]) ?>

                                      <?= $form->field($form_pago, 'form_descripcion', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textArea(['class'=>'form-control', 'placeholder'=>'Ingrese motivo del cobro', 'tabindex'=>4]) ?>

                             


                                    <?= $form->field($form_pago, 'form_total', ['template' => '
                                        {label}
                                         <div class="input-group col-sm-12 ">
                                            <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            {input}
                                         </div>
                                         {error}{hint}
                                     ']
                                     )->textInput(['class'=>'form-control', 'placeholder'=>'Ingrese sus nombres', 'tabindex'=>1])->widget(\yii\widgets\MaskedInput::className(), [
                                        'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => '',
                                                'digits' => 2, 
                                                'autoGroup' => true
                                            ],
                                    ]) ?>

                                    <?= $form->field($form_pago, 'commerce_id')->hiddenInput(['value'=>Yii::$app->user->identity->empresa->id_commerce])->label(false); ?>

                                </div>
                            <?php }?>
                  <?php ActiveForm::end(); ?>

                  <button id="PagoSubmitButtonCobroDirecto" style="margin-top: 20px;" type="submit" class="btn btn-primary btn-lg btn-block"> Realizar Cobro</button>
                
                </div>
            </div>
        </div>
    </div>
</div>
