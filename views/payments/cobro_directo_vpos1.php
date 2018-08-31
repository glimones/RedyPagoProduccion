<?php
require_once('vpos_plugin_NOTAX.php');

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Redypago';
                         
?>
<?php

$valor_total_orden = (float)$pedido->a_pagar;
$valor_total_orden = number_format($valor_total_orden, 2, '.', '');
$valor_total_orden = (float)$valor_total_orden * (int)100;
$idorden = $pedido->purchase_operation_number;
$mail = ( $pedido->empresa->ambiente != 'Producción' ) ? 'testing'.$pedido->id.'@pagomedios.com' : $pedido->cliente->email;

$purchaseAmount = $valor_total_orden;
//Integración calculo de IVA

// Ejemplo:    100 + IVA
//             10 sin iva

$valor_Iva = (float)$pedido->iva; //12 USD
$valor_Iva = number_format($valor_Iva, 2, '.', '');
$valor_Iva = (float)$valor_Iva * (int)100;

$valor_MontoNoGravaIva = (float)$pedido->total_sin_iva; //10
$valor_MontoNoGravaIva = number_format($valor_MontoNoGravaIva, 2, '.', '');
$valor_MontoNoGravaIva = (float)$valor_MontoNoGravaIva * (int)100;

$valor_MontoGravaIva = (float)$pedido->total_con_iva; //100
$valor_MontoGravaIva = number_format($valor_MontoGravaIva, 2, '.', '');
$valor_MontoGravaIva = (float)$valor_MontoGravaIva * (int)100;


$taxMontoFijo = $purchaseAmount; //122 // campo a_pagar contiene ya toda la sumatoria con o sin iva
$taxMontoIVA = $valor_Iva; //12
$taxMontoNoGravaIva = $valor_MontoNoGravaIva; //10
$taxMontoGravaIva = $valor_MontoGravaIva; //100


$array_send['acquirerId']=( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
$array_send['commerceId']=$pedido->empresa->id_commerce;
$array_send['purchaseOperationNumber']=$idorden;
$array_send['purchaseAmount']=$valor_total_orden;
$array_send['purchaseCurrencyCode']=840;
$array_send['language']="SP"; //En español,
$array_send['billingFirstName']=$pedido->cliente->nombres;
$array_send['billingLastName']=$pedido->cliente->apellidos;
$array_send['billingEMail']=$mail;
$array_send['billingAddress']=$pedido->cliente->direccion;
$array_send['billingZIP']='593';
$array_send['billingCity']='EC';
$array_send['billingState']='7';
$array_send['billingCountry']='EC';
$array_send['billingPhone']=$pedido->cliente->telefonos;
//die(Yii::$app->user->identity->empresa->adquiriente_id);
if(($pedido->empresa->procesamiento_id==1)&&($pedido->empresa->adquirente->id==3)){
    $array_send['reserved1']= $valor_MontoGravaIva ;
    $array_send['reserved2']= $taxMontoIVA ;
    $array_send['reserved11']= $valor_MontoNoGravaIva;
    $array_send['reserved9']= '000';//$purchaseAmount + $taxMontoIVA + $valor_MontoNoGravaIva;
    $array_send['reserved12']='000';
}else{
    $array_send['reserved2']= ( $taxMontoGravaIva > 0 ) ? $taxMontoGravaIva : '000' ;
    $array_send['reserved3']= ( $taxMontoIVA > 0 ) ? $taxMontoIVA : '000' ;
    $array_send['reserved9']= ( $taxMontoNoGravaIva > 0 ) ? $taxMontoNoGravaIva : '000' ;
    $array_send['reserved10']= ( $taxMontoGravaIva > 0 ) ? $taxMontoGravaIva : '000' ;
    $array_send['reserved11']=$idorden;
}

$arrayOut['XMLREQ']="";
$arrayOut['DIGITALSIGN']="";
$arrayOut['SESSIONKEY']="";
$VI = $pedido->empresa->vector;
$llaveVPOSCryptoPub = $pedido->empresa->alignet_publica_cifrado_rsa;
$llavePrivadaFirmaComercio = $pedido->empresa->llave_privada_firma_rsa;

if (VPOSSend($array_send,$arrayOut,$llaveVPOSCryptoPub,$llavePrivadaFirmaComercio,$VI)) { ?>
    <form style="display:none;" name="params_form" id="f1" method="post" action="<?php echo ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? Yii::$app->params['vpos1_produccion'] : Yii::$app->params['vpos1_testing'] ?>" >

       <table border="0">
          <tr>
            <td>IDACQUIRER:</td>
            <td><input name="IDACQUIRER" id="IDACQUIRER" value="<?php echo ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing; ?>"></td>
          </tr>
          <tr>
            <td>COMMERCE:</td>
            <td><input name="IDCOMMERCE" id="IDCOMMERCE" value="<?php echo $pedido->empresa->id_commerce; ?>"></td>
          </tr>
          <tr>
            <td>XML:</td>
            <td><input name="XMLREQ" id="XMLREQ" value="<?php echo $arrayOut['XMLREQ'];?>"></td>
          </tr>
          <tr>
            <td>SIGNATURE:</td>
            <td><input name="DIGITALSIGN" id="SIGNATURE" value="<?php echo $arrayOut['DIGITALSIGN'];?>"></td>
          </tr>
          <tr>
            <td>SESSIONKEY:</td>
            <td><input name="SESSIONKEY" id="SESSIONKEY" value="<?php echo $arrayOut['SESSIONKEY'];?>"></td>
          </tr>
          <tr>
            <td><input type="submit" name="envio" id="envio" value="Enviar" /></td>
          </tr>
        </table>

    </form>
<?php }else{
  echo '<div class="alert alert-error">Hay un problema con Alignet VPOS, contactese con el administrador Redypago.</div>';
}     
?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<?php echo Html::img( Url::to('@web/images/ajax-loader.gif'), ['class'=> 'center-block']) ?>
<script type="text/javascript">
    // $(document).ready(function(){
        document.getElementById('f1').submit();
    // });
</script>
                       