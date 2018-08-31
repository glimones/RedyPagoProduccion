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
        <?php 
        	if( isset( $error['logo'] ) ){
        		echo Html::img( Url::to('@web/logos_comercios/'.$error['logo'] ), ['class'=> 'img-circle logo-comercio']);
        	}else{
        		echo Html::img( Url::to('@web/images/logo_pagomedios.png'), ['class'=> '']);
        	}
        ?>
    </div>

    <div class="body-content">

        <div class="row-fluid">
        	<div class="col-md-12">
                <div class="formulario-infomacion">
					<div class="alert alert-<?php echo $error['tipo']; ?>" role="alert">
						<h1><?php echo $error['titulo']; ?></h1>
						<?php echo $error['mensaje']; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>