<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Clientes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PedidosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pedidos');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<div class="pedidos-index">
    <div id="ajaxCrudDatatable" class="table-responsive">
        <!--timeout="10000"
Se parametriza la empresa alignet para que puedan visualizar los cambios en produccion
Glimones
-->
        <?php  if ((Yii::$app->user->identity->empresa->id == $url = Yii::$app->params['idEmpresa'])||(Yii::$app->params['todos']=='S')) {?><!--Ini GLimones-->

        <?= GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require( ( Yii::$app->user->identity->es_super == 1 ) ? __DIR__ . '/_columns_admin_cp.php' : __DIR__ . '/_columns_cp.php'),
            'toolbar'=> [
                ['content'=>
                    ( Yii::$app->user->identity->es_admin == 1 ) ? Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','id'=>'create_new','title'=> 'Solicitar nuevo pago','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1,'id'=>'refresh_page', 'class'=>'btn btn-default', 'title'=>'Recargar']).
                    '{toggleData}'.
                    '{export}' : ''
                ],
            ],
            //'responsiveWrap' => false,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            //'containerOptions' => ['style' => 'overflow: auto'],
            'panel' => [
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> Solicitudes de Pagos',
                // 'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                // 'after'=>BulkButtonWidget::widget([
                //             'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                //                 ["bulk-delete"] ,
                //                 [
                //                     "class"=>"btn btn-danger btn-xs",
                //                     'role'=>'modal-remote-bulk',
                //                     'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                //                     'data-request-method'=>'post',
                //                     'data-confirm-title'=>'Are you sure?',
                //                     'data-confirm-message'=>'Are you sure want to delete this item'
                //                 ]),
                //         ]).
                //         '<div class="clearfix"></div>',
            ],

        ])

        ?>
        <?php }else{ ?>
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => require( ( Yii::$app->user->identity->es_super == 1 ) ? __DIR__.'/_columns_admin.php' : __DIR__.'/_columns.php' ),
                'toolbar'=> [
                    ['content'=>
                        ( Yii::$app->user->identity->es_admin == 1 ) ? Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                                ['role'=>'modal-remote','title'=> 'Solicitar nuevo pago','class'=>'btn btn-default']).
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Recargar']).
                            '{toggleData}'.
                            '{export}' : ''
                    ],
                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Solicitudes de Pagos',
                    // 'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                    // 'after'=>BulkButtonWidget::widget([
                    //             'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                    //                 ["bulk-delete"] ,
                    //                 [
                    //                     "class"=>"btn btn-danger btn-xs",
                    //                     'role'=>'modal-remote-bulk',
                    //                     'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    //                     'data-request-method'=>'post',
                    //                     'data-confirm-title'=>'Are you sure?',
                    //                     'data-confirm-message'=>'Are you sure want to delete this item'
                    //                 ]),
                    //         ]).
                    //         '<div class="clearfix"></div>',
                ]
            ])?>
        <?php } ?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    //'enablePushState' => false,
    "footer"=>"",// always need it for jquery plugin
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "size"=>"modal-lg",

])?>

<?php Modal::end(); ?>
