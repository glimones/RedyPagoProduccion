<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Adquirentes */
?>
<div class="adquirentes-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'nombre',
            'codigo',
            'codigo_testing',
        ],
    ]) ?>

</div>
