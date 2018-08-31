<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'options'=>['enctype'=>'multipart/form-data'] // important
]); ?>

<?php
$wizard_config = [
	'id' => 'stepwizard',
	'steps' => [
		1 => [
			'title' => 'Información personal',
			'icon' => 'glyphicon glyphicon-user',
			'content' => $this->render('_paso_1', [ 'form'=>$form, 'model' => $model]),
			'buttons' => [
				'next' => [
					'title' => 'Siguiente', 
					'options' => [
						'class' => 'disabled'
					],
				 ],
			 ],
		],
		2 => [
			'title' => 'Documentación para afiliación',
			'icon' => 'glyphicon glyphicon-cloud-upload',
			'content' => $this->render('_paso_2', [ 'form'=>$form, 'model' => $model]),
			// 'skippable' => true,
		],
	],
	'complete_content' => "You are done!", // Optional final screen
	'start_step' => 1, // Optional, start with a specific step
];
?>

<?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>	

<?php ActiveForm::end(); ?>