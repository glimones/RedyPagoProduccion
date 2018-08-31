<?php 
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>

<div class="container">
	<h3>Información del comercio</h3>
	<div class="row-fluid">
		<div class="col-md-6">
			<?= $form->field($model, 'ruc')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'actividades')->textarea(['rows' => 2]) ?>
			<?= $form->field($model, 'ciudad_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Ciudades::find()->orderBy('nombre')->asArray()->all(), 'id', 'nombre'), ['prompt' => '']) ?>

		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'direccion')->textarea(['rows' => 6]) ?>
		</div>
	</div>
</div>

<div class="container">
	<h3>Información de contacto</h3>
	<div class="row-fluid">
		<div class="col-md-6">
			<?= $form->field($model, 'contacto_cedula')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'contacto_nombres')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'contacto_apellidos')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
</div>