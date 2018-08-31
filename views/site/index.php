<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use miloschuman\highcharts\Highcharts;
// use app\models\Facturasoft;

$this->title = 'Redypago';
// $this->title = Yii::t('app', 'Bienvenido');
$this->params['breadcrumbs'][] = 'Resumen';
?>

<?php 
  // $cedula = '0401359583';
  // $idtransaccion = 696969;
  // $valor = '1.01';

  // // $url = Yii::$app->params['descomplicate_api'].'rechargeBalance/';
  
  // $url = Yii::$app->params['descomplicate_api'].'revertRechargeBalance/';
  
  // // $data = '{"cedula":"'.$cedula.'","codigo":"'.$idtransaccion.'","valor":"'.$valor.'"}';

  // $data = '{"codigo":"'.$idtransaccion.'"}';

  // $header = array( 'Content-Type: application/json' );
  // $curl = curl_init();
  // curl_setopt($curl, CURLOPT_URL, $url);
  // curl_setopt($curl, CURLOPT_POST, 1);
  // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
  // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  // curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  // $response = curl_exec($curl);
  // curl_close($curl);
  // $array = json_decode( $response, JSON_FORCE_OBJECT );
  // echo '<pre>';
  // print_r($array);
  // echo '</pre>';
?>

<?php 
// $data = array(
//     "ambiente" => "1", //1->Pruebas, 2->Producción
//     "razonSocial" => "WTCOMMERCIAL DEL ECUADOR S.A.", //Comercio
//     "nombreComercial" =>"Terra2go", //Comercio
//     "ruc" =>"1792002540001", //Comercio
//     "tipoComprobante" => "01", //01 -> Factura
//     "direccionMatriz" => "LUIS TOLA 7A OE3L Y JUAN CAMPUZANO, QUITO", //Comercio
//     "obligadoContabilidad" => "SI", //Comercio
//     "fechaEmision" => date('Y-m-d'), //Fecha emision de la factura (presente o maximo un mes atras)
//     "razonSocialComprador" => "Juan Carlos Cedillo Crespo", //Cliente
//     "tipoIdentificacion" => "05", //05 -> Cedula, 04 -> RUC, 06 -> Pasaporte, 07->Consumidor final
//     "identificacionComprador" => "1717797433", //Cliente
//     "direccionComprador" => "Av. Amazonas N24-66 y Joaquin Pinto. Quito, Pichincha", //Cliente
//     "establecimiento" => "001", //Comercio sucursal
//     "direccionEstablecimiento" => "LUIS TOLA 7A OE3L Y JUAN CAMPUZANO, QUITO", //Comercio sucursal direccion
//     "detallesFactura" => array(
//         array("codigo" => "01","nombre"=>"Pago desde web Terra2go","cantidad"=>"1","precioUnitario"=>"15.99","descuento"=>"0","impuestoAplicado"=>"2") //impuestoAplicado: 0->0% / 2->IVA / 6->No objeto / 7->Exento IVA. El precioUnitario es sin IVA
//     ),
//     "formasDePago" => array(
//       array("codigo"=>"19") //19 -> Tarjeta de credito / 01 -> Efectivo
//     ),
//     "informacionAdicional" => array(
//         array("nombre" => "Dirección","descripcion" => "Av. Amazonas N24-66 y Joaquin Pinto. Quito, Pichincha"),
//         array("nombre" => "Email","descripcion" => "info@abitmedia.com")
//     )
//   );

//   $respuesta = Facturasoft::emitirComprobante($data);
//   if($respuesta['estado'] == 'OK')
//   {
//         echo $respuesta['mensaje'];
//   }
//   if($respuesta['estado'] != 'OK')
//   {
//     echo $respuesta['mensaje'];
//   }

?>




<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Resumen</h1>
    </div>
</div><!--/.row-->
<div class="row-fluid">
    <div class="col-md-5">
        
        <div class="dash-box dash-box-color-3">
            <div class="dash-box-icon">
                <i class="glyphicon glyphicon-usd"></i>
            </div>
            <div class="dash-box-body">
                <span class="dash-box-count">$ <?php echo (float)$sumatoria_autorizadas; ?> USD</span>
                <span class="dash-box-title">Pagos recibidos a la fecha (Año <?php echo date('Y'); ?>)</span>
            </div>   
            <div class="dash-box-action">
                <a href="<?php echo Url::to(['pedidos/estadocuenta'], true); ?>">Más información</a>
            </div>         
        </div>


        <div class="dash-box dash-box-color-1">
             <?php 
                echo Highcharts::widget([
                   'options' => [
                      'title' => ['text' => 'Transacciones autorizadas ('.date('Y').')'],
                      'xAxis' => [
                         'categories' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
                      ],
                      'yAxis' => [
                         'title' => ['text' => '# Transacciones']
                      ],
                      'chart' => [
                          'plotBackgroundColor' => '#ffffff',
                          'plotBorderWidth' => null,
                          'plotShadow' => false,
                          'height' => 300,
                          'type'=>'column'
                        ],
                      'series' => [
                         ['name' => 'Solicitudes de pago', 'data' => $solicitudes],
                         ['name' => 'Transacciones autorizadas', 'data' => $procesadas],
                      ]
                   ]
                ]);
             ?>  
        </div>

    </div>
    <div class="col-md-7" style="padding-top: 40px;">

        <div class="panel panel-default"> 
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-ok-sign"></i> Últimas transacciones autorizadas
                </h3>
                <div class="clearfix"></div>
            </div>
            
            <table class="table"> 
                <tbody> 
                    <?php if( count( $ultimas_autorizadas ) > 0 ){ ?>
                        <?php foreach ($ultimas_autorizadas as $ultima) { ?>
                        <tr> 
                            <th scope="row">
                                <center>
                                    <?php 
                                        $fecha = strtotime( $ultima->fecha_pago );
                                        echo strtoupper( date('M', $fecha) ).'<br>';
                                        echo date('j', $fecha);
                                    ?>
                                </center>
                            </th> 
                                <td>
                                    <h2><?php echo $ultima->cliente->nombres.' '.$ultima->cliente->apellidos; ?></h2>
                                    Transacción autorizada
                                </td> 
                                <td style="text-align: right;"><h4>$<?php echo $ultima->a_pagar; ?></h4></td> 
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="alert alert-info">No se encontraron transacciones</div>
                    <?php } ?>
                </tbody> 
            </table>
            <a class="btn btn-default btn-block" href="<?php echo Url::to(['pedidos/estadocuenta'], true); ?>">Más información</a> 
        </div>

    </div>
</div>