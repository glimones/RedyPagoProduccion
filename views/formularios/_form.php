<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Formularios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="formularios-form" id="oculta_modal">

    <?php $form = ActiveForm::begin(); ?>
    <!--
    Se parametriza la empresa alignet para que puedan visualizar los cambios en produccion
    Glimones
    -->
    <?php  if ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa'])||(Yii::$app->params['todos']=='S')) {?><!--Ini GLimones-->
    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'base12')->widget(MaskMoney::className(), [
                'pluginOptions' => [
                    'prefix' => '$',
                    'decimal' => '.',
                    'precision' => 2,
                    'allowZero' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'idioma')->dropDownList([ 'Español' => 'Español', 'Inglés' => 'Inglés', ], ['prompt' => '']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'base0')->widget(MaskMoney::className(), [
                'pluginOptions' => [
                    'prefix' => '$',
                    'decimal' => '.',
                    'precision' => 2,
                    'allowZero' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'iva')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'digits' => 2,
                    'autoGroup' => true
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'precio')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'digits' => 2,
                    'autoGroup' => true
                ],
            ]) ?>
        </div>
        </div>
        <?php }else{?>
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'precio')->widget(\yii\widgets\MaskedInput::className(), [
                        'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => '',
                            'digits' => 2,
                            'autoGroup' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'idioma')->dropDownList([ 'Español' => 'Español', 'Inglés' => 'Inglés', ], ['prompt' => '']) ?>
                </div>
            </div>
        <?php } ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'ModalOculta']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#formularios-precio').val('0.00');
            $('#formularios-iva').val('0.00');
            document.getElementById('formularios-precio').style.textAlign="left";
            suma();



        <?php  if ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa'])||(Yii::$app->params['todos']=='S')) {?><!--Ini GLimones-->
            document.getElementById("formularios-precio").readOnly = true;
            document.getElementById("formularios-iva").readOnly = true;

        <?php }?>
        });
        function suma() {
            var base12 = $("#formularios-base12-disp").val().replace('$', '');
            base12=base12.replace(',','');
            base12=base12.replace(',','');
            base12=base12.replace(',','');
            base12=parseFloat(base12);
            var base0 = $("#formularios-base0-disp").val().replace('$', '');
            base0=base0.replace(',','');
            base0=base0.replace(',','');
            base0=base0.replace(',','');
            base0=parseFloat(base0);
            var iva=((parseFloat(base12)*12)/100).toFixed(2);
            $("#formularios-iva").val(((parseFloat(base12)*12)/100).toFixed(2));
            iva=iva.replace(',','');
            iva=iva.replace(',','');
            iva=iva.replace(',','');
            iva=parseFloat(iva);
            var suma=base0+base12+iva;
            suma=number_format(suma,2);
            $("#formularios-precio").val(suma);
        }
        function number_format(amount, decimals) {
            amount += '';
            amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
            decimals = decimals || 0;
            if (isNaN(amount) || amount === 0)
                return parseFloat(0).toFixed(decimals);
            amount = '' + amount.toFixed(decimals);
            var amount_parts = amount.split('.'),
                regexp = /(\d+)(\d{3})/;
            while (regexp.test(amount_parts[0]))
                amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
            return amount_parts.join('.');
        }
        $( "#formularios-base12-disp" ).keyup(function() {
            var valor=$(this).val().replace('$','');
            var BaseCero=$("#formularios-base0-disp").val().replace('$','');
            BaseCero=BaseCero.replace('$','');
            BaseCero=BaseCero.replace(',','');
            valor=valor.replace('$','');
            valor=valor.replace(',','');
            valor=valor.replace(',','');
            //$("#formularios-base0-disp").val('0.00');
            $("#formularios-iva").val(((parseFloat(valor)*12)/100).toFixed(2));
            var valorIva=$("#formularios-iva").val();
            $("#formularios-precio").val((parseFloat(valorIva)+ parseFloat(valor)+parseFloat(BaseCero)).toFixed(2));
        }).on('submit', function(e){
            e.preventDefault();
        });

        $( "#formularios-base0-disp" ).keyup(function() {
            var sumaIva=$("#formularios-iva").val();
            var sumaTarifa=$("#formularios-base12-disp").val().replace('$','');
            sumaTarifa=parseFloat(sumaTarifa.replace(',',''));
            var sumaNueva=$('#formularios-base0-disp').val().replace('$','');
            sumaNueva=sumaNueva.replace(',','');
            sumaNueva=parseFloat(sumaNueva.replace(',',''));
            suma=parseFloat(sumaIva)+sumaNueva+sumaTarifa;
            $("#formularios-precio").val(suma.toFixed(2));
        }).on('submit', function(e){
            e.preventDefault();
        });



    </script>