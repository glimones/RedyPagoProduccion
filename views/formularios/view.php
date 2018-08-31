<?php

use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Formularios */
?>
<div class="formularios-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'attribute'=>'token',
                'value'=>'<a class="btn btn-primary btn-block" target="_blank" href="'.Url::to(['forms/t?token='.$model->token], true).'">Ver formulario</a>',
                'format' => ['raw'],
            ],
            // [
            //     'attribute'=>'token',
            //     'value'=>'<div class="row"><div class="col-md-6"><a onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600\');return false;" class="btn btn-primary btn-block" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.Url::to(['forms/t?token='.$model->token], true).'">Facebook</a></div><div class="col-md-6"></div></div>',
            //     'label' => 'Compartir/Enviar',
            //     'format' => ['raw'],
            // ],
            'descripcion',
            'fecha',
            [
                'attribute'=>'precio',
                'value'=>'<h2>$ '.$model->precio.' USD</h2>',
                'format' => ['raw'],
            ],
            // 'token:ntext',
            // 'empresa_id',
        ],
    ]) ?>

</div>
