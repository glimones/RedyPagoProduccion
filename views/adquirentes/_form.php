<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Adquirentes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adquirentes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <div class=row>
    	<div class="col-md-3">
    		<?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-md-3">
    		<?= $form->field($model, 'codigo_testing')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-md-6">
    		<?php 
                $array_filtros = array();
                if ($model->id > 0) {
                    foreach ($model->adquirentesProcesamientos as $p) {
                        $array_filtros[ $p->procesamiento_id ] = [ 'selected' => true ];
                    }
                }
            ?>
            <?= $form->field($model, 'procesamiento')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Procesamientos::find()->asArray()->all(), 'id', 'nombre'),
                'options' => ['placeholder' => 'Seleccione', 'options'=> $array_filtros],
                'pluginOptions' => [
                    'allowClear' => true, 
                    'multiple' => true,
                ],
            ]); ?>
    	</div>
    </div>

    

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
