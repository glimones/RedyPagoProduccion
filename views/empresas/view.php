<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
?>
<div class="empresas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ruc',
            'razon_social',
            'logo',
            'contacto_cedula',
            'contacto_nombres',
            'contacto_apellidos',
            'ciudad_id',
            'direccion:ntext',
            'email:email',
            'actividades:ntext',
            'url_comercio_electronico:url',
            'fecha_afiliacion',
            'id_commerce',
            'id_acquirer',
            'id_wallet',
            'llave_vpos',
            'llave_wallet',
            'ambiente',
            'estado',
        ],
    ]) ?>

</div>
