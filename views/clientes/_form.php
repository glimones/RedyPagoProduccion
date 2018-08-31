<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clientes-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group col-sm-4">
                &nbsp;&nbsp;<input type="checkbox" id="extranjero1">&nbsp;Exterior
            </div>
            <?= $form->field($model, 'identificacion')->textInput(['maxlength' => true]) ?>
            <p id="cedulaInc" style="display: none; color:#a94442;">Error cédula incorrecta</p>
            <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'telefonos')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'direccion')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'idioma')->dropDownList([ 'Español' => 'Español', 'Inglés' => 'Inglés', ], ['prompt' => '']) ?>
        </div>
    </div>
    
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cedulaInc').hide();
            //$('#cedulaRep').hide();
            var cedulavalida='';
            var ValidaExtranjero='';
            $("#clientes-identificacion").keyup(function () {
                    if ($("#extranjero1").is(':checked')) {
                        ValidaExtranjero = 'S';
                        /*if (!/^[ a-z0-9]*$/i.test(this.value)) {
                            this.value = this.value.replace(/[^ a-z0-9]+/ig, "");
                        }*/
                    } else {
                        ValidaExtranjero = 'N';
                        if (!/^[ 0-9]*$/i.test(this.value)) {
                            this.value = this.value.replace(/[^ 0-9]+/ig, "");
                        }
                    }
               // var cedula = $(this).val();

                    if ((longitud >= 10) || (longitud <= 13)) {
                        var cad = $(this).val();
                        var total = 0;
                        var longitud = cad.length;
                        var longcheck = longitud - 1;
                        if (cad !== "" && longitud === 10) {
                            for (i = 0; i < longcheck; i++) {
                                if (i % 2 === 0) {
                                    var aux = cad.charAt(i) * 2;
                                    if (aux > 9) aux -= 9;
                                    total += aux;
                                } else {
                                    total += parseInt(cad.charAt(i));
                                }
                            }
                            total = total % 10 ? 10 - total % 10 : 0;
                            //total = total % 13 ? 13 - total % 13 : 0;
                            //if (((cad.charAt(longitud - 1) >= 10) && (cad.charAt(longitud - 1) == total)) || ValidaExtranjero === 'S') {
                            alert($(this).val().substring(1, 5));
                            if ((cad.charAt(longitud - 1) == total) || ValidaExtranjero === 'S') {
                                //alert('nueva:'+$(this).val());
                                validaRuc=$(this).val();
                                 alert(cad.charAt(longitud - 1));
                                cedulavalida = '1';
                                $("#cedulaInc").hide();
                                $.ajax({
                                    type: "post",
                                    url: base_url + "/ajax/informacioncliente",
                                    dataType: 'json',
                                    data: {'id': $(this).val()},
                                    beforeSend: function () {
                                    },
                                    success: function (data) {
                                        if (data.nombres != null) {//gli
                                            cedulavalida = '2';
                                            //$('#cedulaRep').show();
                                         //   alert('existe '+data.nombres);

                                        } else {
                                            $('#cedulaInc').show();
                                         //  alert('No existe Correcta');
                                        }//gli
                                    },
                                    async: false,
                                    cache: false
                                });
                            } else {
                                $('#cedulaInc').show();
                                //alert('Incorrecta menor longitud');
                            }
                        }
                    }else {
                        $('#cedulaInc').show();
                        //alert('Incorrecta menor longitud');
                        }

            });



            $("#clientes-nombres").keyup(function () {
                if (!/^[ a-z]*$/i.test(this.value)) {
                    this.value = this.value.replace(/[^ a-z]+/ig, "");
                }
            });
            $("#clientes-apellidos").keyup(function () {
                if (!/^[ a-z]*$/i.test(this.value)) {
                    this.value = this.value.replace(/[^ a-z]+/ig, "");
                }
            });
            $("#clientes-telefonos").keyup(function () {
                if (!/^[ 0-9]*$/i.test(this.value)) {
                    this.value = this.value.replace(/[^ 0-9]+/ig, "");
                }
            });

        });
</script>
