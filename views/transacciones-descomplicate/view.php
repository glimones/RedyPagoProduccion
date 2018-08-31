<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TransaccionesDescomplicate */
?>
<div class="transacciones-descomplicate-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cedula',
            'idtransaccion',
            'valor',
            'fecha',
            'estado',
            'mensaje_descomplicate',
        ],
    ]) ?>

</div>
