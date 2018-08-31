<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedidos-form">

    <?php $form = ActiveForm::begin(); ?>
    <!--
    Se parametriza la empresa alignet para que puedan visualizar los cambios en produccion
    Glimones
    -->
    <?php  if (Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa']) {?><!--Ini GLimones-->
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'cliente_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Clientes::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id)->orderBy('id')->asArray()->all(), 'id',
                        function($model, $defaultValue) {
                            return $model['identificacion'].'-'.$model['nombres'].' '.$model['apellidos'];
                        }
                    ),
                'options' => ['placeholder' => Yii::t('app', 'Seleccione un cliente')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            ¿No encuentra a su cliente?
            <a class="" href="<?php echo Url::to(['clientes/create']); ?>" title="Solicitar nuevo pago" role="modal-remote"><i class="glyphicon glyphicon-plus"></i> Agregar nuevo cliente</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
              <?= $form->field($model, 'total_con_iva')->widget(MaskMoney::className(), [
                'pluginOptions' => [
                    'prefix' => '$',
                    'suffix' => '',
                    'affixesStay' => true,
                    'decimal' => '.',
                    'precision' => 2,
                    'allowZero' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'total_sin_iva')->widget(MaskMoney::className(), [
                'pluginOptions' => [
                    'prefix' => '$',
                    'suffix' => '',
                    'affixesStay' => true,
                    'decimal' => '.',
                    'precision' => 2,
                    'allowZero' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <label class="control-label" for="pedidos-total_sin_iva">Total IVA</label>
            <input class="form-control" style="text-align: right;" type="text" name="iva" value="0.00" id="iva" readonly="readonly">
        </div>
        <div class="col-md-3">
            <label class="control-label" for="pedidos-total_sin_iva">A pagar</label>
            <input class="form-control" style="text-align: right;" type="text" name="a_pagar" value="0.00" id="a_pagar" readonly="readonly">
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?php }else{ ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'cliente_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Clientes::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id)->orderBy('id')->asArray()->all(), 'id',
                        function($model, $defaultValue) {
                            return $model['identificacion'].'-'.$model['nombres'].' '.$model['apellidos'];
                        }
                    ),
                'options' => ['placeholder' => Yii::t('app', 'Seleccione un cliente')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            ¿No encuentra a su cliente?
            <a class="" href="<?php echo Url::to(['clientes/create']); ?>" title="Solicitar nuevo pago" role="modal-remote"><i class="glyphicon glyphicon-plus"></i> Agregar nuevo cliente</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3"> 
            <?= $form->field($model, 'total_con_iva')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => '',
                        'digits' => 2, 
                        'autoGroup' => true
                    ],
            ])->textInput(['value' => '0.00']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'total_sin_iva')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => '',
                        'digits' => 2, 
                        'autoGroup' => true
                    ],
                ])->textInput(['value' => '0.00']) ?>
            </div>
            <div class="col-md-3">
                <label class="control-label" for="pedidos-total_sin_iva">Total IVA</label>
                <input class="form-control" style="text-align: right;" type="text" name="iva" value="0.00" id="iva" readonly="readonly">
            </div>
            <div class="col-md-3">
                <label class="control-label" for="pedidos-total_sin_iva">A pagar</label>
                <input class="form-control" style="text-align: right;" type="text" name="a_pagar" value="0.00" id="a_pagar" readonly="readonly">
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
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
