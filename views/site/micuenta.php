<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Mi cuenta</h1>
    </div>
</div><!--/.row-->
<div class="usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?php if( Yii::$app->user->identity->es_super == 1 ){ ?>
                <?= $form->field($model, 'empresa_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Empresas::find()->orderBy('razon_social')->asArray()->all(), 'id', 'razon_social'
                        ),
                    'options' => ['placeholder' => Yii::t('app', 'Seleccione una empresa')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            <?php } ?>
            <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            

            <div class="form-group field-usuarios-email required">
                <label class="control-label" for="usuarios-email">E-mail</label>
                <input id="email" class="form-control" name="email" value="<?php echo $model->email ?>" maxlength="450" readonly="yes" type="text">

                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'clave')->passwordInput(['maxlength' => true]) ?>
        </div>
    </div>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
