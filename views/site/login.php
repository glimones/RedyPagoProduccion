<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
?>
<div class="container">
  <div class="row-fluid">
      <div class="col-md-4 col-md-offset-4">
          <div class="login-panel panel panel-default">
              <div class="panel-heading">
                  <div class="col-sm-offset-0"><?= Html::img( Url::to('@web/images/logo_pagomedios.png'), ['class'=> 'login-logo']);?></div>
                 <!--  <div class="col-md-offset-0"><h3><i>Iniciar Sesión<i></h3></div> -->
              </div>
              <div class="panel-body">
                  <?php $form = ActiveForm::begin([
                      'id' => 'login-form',
                      // 'layout' => 'horizontal',
                      'fieldConfig' => [
                          'template' => "{label}\n{input}\n{error}",
                          // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
                      ],
                  ]); ?>
                          <?= $form->field($model, 'username', ['template' => '
                                 <div class="input-group col-sm-12 ">
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    {input}
                                 </div>
                                 {error}{hint}
                             ']
                             )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Ingrese su e-mail']) ?>

                          <?= $form->field($model, 'password', ['template' => '
                                 <div class="input-group col-sm-12 ">
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-lock"></span>
                                    </span>
                                    {input}
                                 </div>
                                 {error}{hint}
                             ']
                             )->passwordInput(['placeholder'=>'Ingrese su contraseña']) ?>                    
                          <?= $form->field($model, 'rememberMe')->checkbox([
                              // 'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                          ]) ?>
                      
                      <?= Html::submitButton('Ingresar', ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>
                  <?php ActiveForm::end(); ?>
                  <p>&nbsp;</p>
                  <center>
                    <a data-toggle="modal" href="javascript:;" data-target="#modal_recuperar_clave" class="label-forgot">¿Olvido su contraseña?</a>
                  </center>

                  <!-- <div class="registrarse">
                      <div class="col-sm-offset-0"><p>¿No tienes una cuenta?</p></div>
                      <div class="col-md-4 col-md-offset-4"><p class="register"><a href="#">Registrarse</a></p></div>
                  </div> -->
              </div>
          </div>
          <div class="footer">
                      <div class="col-sm-offset-0">&copy; Redypago <?php echo date('Y') ?>. Derechos reservados.<?php //echo  Html::img( Url::to('@web/images/logo_abitmedia.png'), ['class'=> '']);?></div>
                  </div>
      </div><!-- /.col-->
  </div><!-- /.row -->    
</div>

<?php 
Modal::begin(['id' => 'modal_recuperar_clave',
    'header' => '<h4>Recuperar contraseña</h4>']);
?>    

    <?php $form = ActiveForm::begin([
        'id' => 'recuperar-form',
        'enableClientValidation' => true, 
        'enableAjaxValidation' => false,
        'action' => ['/site/recuperar'],
        // 'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>


            <?= $form->field($recuperar, 'email', ['template' => '
                   <div class="input-group col-sm-12 ">
                      <span class="input-group-addon">
                         <span class="glyphicon glyphicon-user"></span>
                      </span>
                      {input}
                   </div>
                   {error}{hint}
               ']
               )->textInput(['autofocus' => true, 'class'=>'form-control', 'placeholder'=>'Ingrese su e-mail registrado']) ?>
        <div style="color: #000; font-weight: bold; font-size: 14px;" class="response_email"></div>
        <?= Html::submitButton('Recuperar cuenta', ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
<?php
Modal::end();
?>