<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use kartik\widgets\DepDrop;
/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="empresas-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data'] // important
    ]); ?>
    <h2>Datos empresa</h2>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'ruc')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?php //if( $model->id > 0 ){ ?>
                <?= $form->field($model, 'ambiente')->dropDownList([ 'Test' => 'Test', 'Test-Producción'=>'Test-Producción', 'Producción' => 'Producción', ], ['prompt' => '']) ?>
            <?php //} ?>
            
        </div>
        <div class="col-md-4">
            
            <?= $form->field($model, 'ciudad_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Ciudades::find()->orderBy('id')->asArray()->all(), 'id', 'nombre'), ['prompt' => '']) ?>
            
            <?= $form->field($model, 'actividades')->textarea(['rows' => 1]) ?>
            <?php if( $model->id > 0 ){ ?>
                <?= $form->field($model, 'estado')->dropDownList(array(0=>'Inactivo', 1=>'Activo'), ['prompt' => '']) ?>
            <?php } ?>
            
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'direccion')->textarea(['rows' => 2]) ?>
            <?php echo $form->field($model, 'logo')->hiddenInput(['maxlength' => true]) ?>
            
            <?php //if( !empty( $model->logo ) ){
                echo Html::img( Url::to('@web/logos_comercios/'.$model->logo) , ['style'=> 'height:55px;', 'id'=>'vista_previa_logo_pagomedios']);
            //} ?>

            <?= FileUpload::widget([
                'model' => $model,
                'attribute' => 'comp_logo_pagomedios',
                'url' => ['ajax/subirimagenpagomedios', 'id' => $model->id], // your url, this is just for demo purposes,
                'options' => ['accept' => 'image/*'],
                'clientOptions' => [
                    'maxFileSize' => 2000000,
                    'dataType' => 'json'
                ],
                // Also, you can specify jQuery-File-Upload events
                // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                            $("#empresas-logo").val( data.result[0].name );
                                            $("#vista_previa_logo_pagomedios").attr("src", data.result[0].url);
                                        }',
                    'fileuploadfail' => 'function(e, data) {

                                        }',
                ],
            ]); ?>
            
        </div> 
    </div>
    <hr>

    
    <h2>Datos de contacto</h2>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'contacto_cedula')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'contacto_nombres')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'contacto_apellidos')->textInput(['maxlength' => true]) ?>
        </div> 
    </div>
    <hr>

    <h2>Información adquirente y procesamiento</h2>
    <div class="row">
        <div class="col-md-3">
            
            <?= $form->field($model, 'adquirente_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Adquirentes::find()->asArray()->all(), 'id', 
                        function($model, $defaultValue) {
                            return $model['nombre'];
                        }
                    ),
                'options' => ['placeholder' => Yii::t('app', 'Seleccione'), 'id'=>'cat-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
        <div class="col-md-2">
            <?php 
                // $lista_empresas = \yii\helpers\ArrayHelper::map(\app\models\Empresas::find()->orderBy('nombre_comercial')->asArray()->all(), 'id', 
                //         function($model, $defaultValue) {
                //             return $model['ruc'].'-'.$model['nombre_comercial'];
                //         }
                // );
                // echo $form->field($model, 'empresa_id')->dropDownList($lista_empresas, ['id'=>'cat-id']);
                echo Html::hiddenInput('input-type-1', 'Additional value 1', ['id'=>'input-type-1']);
                echo Html::hiddenInput('input-type-2', 'Additional value 2', ['id'=>'input-type-2']);

                $array_procesamiento = [];

                if( !is_null( $model->procesamiento_id ) ){
                    $array_procesamiento[$model->procesamiento_id] = $model->procesamiento->nombre;
                }

                echo $form->field($model, 'procesamiento_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'data'=>$array_procesamiento,
                    'options'=>['id'=>'subcat1-id', 'placeholder'=>'Seleccione'],
                    'select2Options'=>['pluginOptions'=>['multiple' => false,'allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['cat-id'],
                        'url'=>Url::to(['/ajax/procesamientosadquirentes']),
                        'params'=>['input-type-1', 'input-type-2'],
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_commerce')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'monto_maximo_por_transaccion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php $style_vpos1 = 'display:none'; $style_payme = 'display:none'; ?>
    <?php
        if( $model->id > 0 ){
            if( $model->procesamiento_id == 1 ){
                $style_vpos1 = 'display:inline';
            }elseif( $model->procesamiento_id == 2 ){
                $style_payme = 'display:inline';
            }
        } 
    ?>
    <div style="<?php echo $style_payme; ?>" class="box-procesamiento box-payme">
        <hr>
        <h2>Integración Payme Alignet</h2>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'llave_vpos')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <div style="<?php echo $style_vpos1; ?>" class="box-procesamiento box-vpos1">
        <hr>
        <h2>Integración Alignet VPOS1</h2>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'alignet_publica_cifrado_rsa')->textarea(['rows' => 2]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'alignet_publica_firma_rsa')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'llave_privada_cifrado_rsa')->textarea(['rows' => 2]) ?>
                <?= $form->field($model, 'vector')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'llave_privada_firma_rsa')->textarea(['rows' => 2]) ?>
            </div>
        </div>
    </div>


    <?php if( Yii::$app->user->identity->id = 1 ){ ?>

        <hr>
        <h2>Integración FacturaSoft</h2>
        <div class="row">
            <div class="col-md-1" style="margin-top: 0px;">
                <?= $form->field( $model, 'facturacion_electronica' )->checkbox(); ?>  
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'facturacion_ambiente')->dropDownList(array(1=>'Pruebas', 2=>'Producción')) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'obligado_llevar_contabilidad')->dropDownList(array('SI'=>'SI', 'NO'=>'NO')) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'establecimiento')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'nombre_comercial')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        
    <?php } ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#subcat1-id').on('select2:select', function (e) {
            $(".box-procesamiento").hide();
            var procesamiento_id = $(this).val();
            if( procesamiento_id == 1 ){
                $(".box-vpos1").show();
            }
            if( procesamiento_id == 2 ){
                $(".box-payme").show();   
            }
        });
    });
</script>