<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Redypago';
?>
<div class="site-index">

    <div class="encabezado-comercio">
        <?php echo Html::img( Url::to('@web/logos_comercios/'.$form->empresa->logo ), ['class'=> 'img-circle logo-comercio']) ?>
    </div>

    <div class="body-content">

        <div class="row-fluid">
            <!-- <div class="col-xs-8 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3"> -->
            <div class="col-md-12">
                <div class="formulario-infomacion">
                <div class="titulo-form">Datos del cliente</div>
                <hr>
                
                </div>
            </div>
        </div>
    </div>
</div>
