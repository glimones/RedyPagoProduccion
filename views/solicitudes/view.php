<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Solicitudes */
?>
<div class="solicitudes-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ruc',
            'razon_social',
            'contacto_cedula',
            'contacto_nombres',
            'contacto_apellidos',
            'direccion:ntext',
            'email:email',
            'actividades:ntext',
            'ciudad_id',
            'fecha_solicitud',
            'estado',
        ],
    ]) ?>

</div>
