<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
?>
<div class="usuarios-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombres',
            'apellidos',
            'email:email',
            'fecha_creacion',
        ],
    ]) ?>

</div>
