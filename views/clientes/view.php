<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */
?>
<div class="clientes-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'identificacion',
            'nombres',
            'apellidos',
            'telefonos',
            'direccion:ntext',
            'email:email',
            'idioma',
        ],
    ]) ?>

</div>
