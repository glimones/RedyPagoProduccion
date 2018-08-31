<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Redypago';
?>
<div class="site-index">

    <div class="encabezado-comercio">
        <?php echo Html::img( Url::to('@web/logos_comercios/'.$pedido->empresa->logo ), ['class'=> 'img-circle logo-comercio']) ?>
    </div>

    <div class="body-content">

        <div class="row-fluid">
            <!-- <div class="col-xs-8 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3"> -->
            <div class="col-md-5">
                <div class="formulario-infomacion">
                <div class="titulo-form">Datos del cliente</div>
                <hr>
                <?= DetailView::widget([
                    'model' => $pedido->cliente,
                    'attributes' => [
                        // 'id',
                        'identificacion',
                        'nombres',
                        'apellidos',
                        // 'telefonos',
                        // 'direccion:ntext',
                    ],
                ]) ?>
                </div>
            </div>
            <div class="col-md-7">    
                <div class="formulario-infomacion">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="titulo-form">Detalle del pedido</div>
                        </div>
                        <div class="col-md-4">
                            <?php echo Html::img( Url::to('@web/images/visa-mastercard.png'), ['class'=> 'pull-right']) ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Comercio:</strong>
                        </div>
                        <div class="col-md-8 text-right">
                            <?php echo $pedido->empresa->razon_social; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Nro. de pedido:</strong>
                        </div>
                        <div class="col-md-8 text-right">
                            <?php echo $pedido->numero_pedido; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Descripción:</strong>
                        </div>
                        <div class="col-md-8 text-right">
                            <?php echo $pedido->descripcion; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Monto a pagar:</strong>
                        </div>
                        <div class="col-md-8 text-right">
                            <span class="titulo-form">$<?php echo $pedido->a_pagar; ?> USD</span>
                            <!-- Alignet -->
                            <?php

                                //Integracion de Wallet
                                $idEntCommerce = $pedido->empresa->id_wallet;
                                $codCardHolderCommerce = 'PM_'.$pedido->cliente_id;
                                $names = $pedido->cliente->nombres;
                                $lastNames = $pedido->cliente->apellidos;
                                $mail = $pedido->cliente->email;
                                $reserved1 = '';
                                $reserved2 = '';
                                $reserved3 = '';
                                $claveSecretaWallet = $pedido->empresa->llave_wallet;
                                $registerVerification = openssl_digest($idEntCommerce . $codCardHolderCommerce . $mail . $claveSecretaWallet, 'sha512');

                                if( $pedido->empresa->ambiente == 'Test' ){
                                    $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
                                }elseif( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ){
                                    $wsdl = 'https://www.pay-me.pe/WALLETWS/services/WalletCommerce?wsdl';
                                }

                                $client = new SoapClient($wsdl);

                                if( method_exists( $client, 'RegisterCardHolder' ) ){
                                    $params = array(
                                        'idEntCommerce'=>$idEntCommerce,
                                        'codCardHolderCommerce'=>$codCardHolderCommerce,
                                        'names'=>$names,
                                        'lastNames'=>$lastNames,
                                        'mail'=>$mail,
                                        'reserved1'=>$reserved1,
                                        'reserved2'=>$reserved2,
                                        'reserved3'=>$reserved3,
                                        'registerVerification'=>$registerVerification
                                    );
                                    $result = $client->RegisterCardHolder($params);
                                    $codAsoCardHolderWallet = $result->codAsoCardHolderWallet;
                                }else{
                                    $codAsoCardHolderWallet = '';
                                }
                                //Integracion de Wallet
                                


                                //Parametros Configuración
                                $valor_total_orden = (float)$pedido->a_pagar;
                                $valor_total_orden = number_format($valor_total_orden, 2, '.', '');
                                $valor_total_orden = (float)$valor_total_orden * (int)100;

                                $acquirerId = ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) ? $pedido->empresa->adquirente->codigo : $pedido->empresa->adquirente->codigo_testing;
                                $idCommerce = $pedido->empresa->id_commerce;
                                $purchaseOperationNumber = $pedido->purchase_operation_number;
                                $purchaseAmount = $valor_total_orden;
                                $purchaseCurrencyCode = '840';
                                
                                //Clave SHA-2 de VPOS2
                                $claveSecreta = $pedido->empresa->llave_vpos;
                                
                                //VERSION PHP >= 5.3
                                //echo openssl_digest('', 'sha512');
                                //VERSION PHP < 5.3
                                //echo hash('sha512', '$acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta');


                                //Integración calculo de IVA
                                
                                $valor_Iva = (float)$pedido->iva;
                                $valor_Iva = number_format($valor_Iva, 2, '.', '');
                                $valor_Iva = (float)$valor_Iva * (int)100;

                                $valor_MontoNoGravaIva = (float)$pedido->total_sin_iva;
                                $valor_MontoNoGravaIva = number_format($valor_MontoNoGravaIva, 2, '.', '');
                                $valor_MontoNoGravaIva = (float)$valor_MontoNoGravaIva * (int)100;
                                
                                $valor_MontoGravaIva = (float)$pedido->total_con_iva;
                                $valor_MontoGravaIva = number_format($valor_MontoGravaIva, 2, '.', '');
                                $valor_MontoGravaIva = (float)$valor_MontoGravaIva * (int)100;


                                $taxMontoFijo = $purchaseAmount; // campo a_pagar contiene ya toda la sumatoria con o sin iva
                                $taxMontoIVA = $valor_Iva;
                                $taxMontoNoGravaIva = $valor_MontoNoGravaIva;
                                $taxMontoGravaIva = $valor_MontoGravaIva;


                                $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta, 'sha512');                       
                            ?>
                            <!--Envío de parametros a V-POS2-->
                            <form name="f1" id="f1" action="#" method="post" class="alignet-form-vpos2" style="display: none;">
                                <table>
                                    <tr><td>acquirerId</td><td><input type="text" name ="acquirerId" value="<?php echo $acquirerId; ?>" /></td></tr>
                                    <tr><td>idCommerce</td><td> <input type="text" name ="idCommerce" value="<?php echo $idCommerce; ?>" /></td></tr>
                                    <tr><td>purchaseOperationNumber </td><td><input type="text" name="purchaseOperationNumber" value="<?php echo $purchaseOperationNumber; ?>" /></td></tr>
                                    <tr><td>purchaseAmount </td><td><input type="text" name="purchaseAmount" value="<?php echo $purchaseAmount; ?>" /></td></tr>
                                    <tr><td>purchaseCurrencyCode </td><td><input type="text" name="purchaseCurrencyCode" value="<?php echo $purchaseCurrencyCode; ?>" /></td></tr>
                                    <tr><td>language </td><td><input type="text" name="language" value="SP" /></td></tr>                
                                    <tr><td>shippingFirstName </td><td><input type="text" name="shippingFirstName" value="<?php echo $pedido->cliente->nombres; ?>" /></td></tr>
                                    <tr><td>shippingLastName </td><td><input type="text" name="shippingLastName" value="<?php echo $pedido->cliente->apellidos; ?>" /></td></tr>
                                    <tr><td>shippingEmail </td><td><input type="text" name="shippingEmail" value="<?php echo $pedido->cliente->email; ?>" /></td></tr>
                                    <tr><td>shippingAddress </td><td><input type="text" name="shippingAddress" value="<?php echo $pedido->cliente->direccion; ?>" /></td></tr>
                                    <tr><td>shippingZIP </td><td><input type="text" name="shippingZIP" value="Pagomedios Zip" /></td></tr>
                                    <tr><td>shippingCity </td><td><input type="text" name="shippingCity" value="Pagomedios Ciudad" /></td></tr>
                                    <tr><td>shippingState </td><td><input type="text" name="shippingState" value="Pagomedios Estado" /></td></tr>
                                    <tr><td>shippingCountry </td><td><input type="text" name="shippingCountry" value="EC" /></td></tr>                
                            <!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codCardHolderCommerce.-->
                                    <tr><td>userCommerce </td><td><input type="text" name="userCommerce" value="<?php echo $codCardHolderCommerce; ?>" /></td></tr> <!-- 0101010101 -->
                            <!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codAsoCardHolderWallet.-->
                                    <tr><td>userCodePayme </td><td><input type="text" name="userCodePayme" value="<?php echo $codAsoCardHolderWallet; ?>" /></td></tr> <!-- 5--420--2340 -->
                                    <tr><td>descriptionProducts </td><td><input type="text" name="descriptionProducts" value="<?php echo $pedido->descripcion; ?>" /></td></tr>
                                    <tr><td>programmingLanguage </td><td><input type="text" name="programmingLanguage" value="PHP" /></td></tr>
                            <!--Ejemplo envío campos reservados en parametro reserved1.-->
                                    <tr><td>reserved1 </td><td><input type="text" name="reserved1" value="" /></td></tr>
                                    <tr><td>purchaseVerification </td><td><input type="text" name="purchaseVerification" value="<?php echo $purchaseVerification; ?>" /></td></tr>
                                    <!-- IVA -->
                                    <tr><td>taxMontoFijo</td><td><input type="text" name="taxMontoFijo" value="<?php echo $taxMontoFijo; ?>" /></td></tr>
                                    <tr><td>taxMontoIVA</td><td><input type="text" name="taxMontoIVA" value="<?php echo $taxMontoIVA; ?>" /></td></tr>
                                    <tr><td>taxMontoNoGravaIva</td><td><input type="text" name="taxMontoNoGravaIva" value="<?php echo $taxMontoNoGravaIva; ?>" /></td></tr>
                                    <tr><td>taxMontoGravaIva</td><td><input type="text" name="taxMontoGravaIva" value="<?php echo $taxMontoGravaIva; ?>" /></td></tr>
                                </table>
                            </form>
                            <!-- Alignet -->
                            <?php if( $pedido->empresa->ambiente == 'Test' ){ ?>
                                <button onclick="javascript:AlignetVPOS2.openModal('https://integracion.alignetsac.com/')" style="margin-top: 20px;" class="btn btn-warning btn-lg btn-block"> Pagar pedido (ambiente test)</button>
                            <?php }elseif ( $pedido->empresa->ambiente == 'Producción' || $pedido->empresa->ambiente == 'Test-Producción' ) { ?>
                                <button onclick="javascript:AlignetVPOS2.openModal('', '2')" style="margin-top: 20px;" class="btn btn-primary btn-lg btn-block"> Pagar Pedido</button>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</div>
