<?php 
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
?>
<div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td style=""><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr><td height='25'></td></tr>
                      <tr>
                        <td>
                          <div class='contentEditableContainer contentTextEditable'>
                            <div class='contentEditable' style='text-align: justify;'>
                              <h2 style="font-size: 20px; text-align: center;">Solicitud de pago</h2>
                              <br>
                              <p><strong>Estimado/a <?php echo $model->cliente->nombres ?>:</strong></p>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr><td height='24'><p><?php echo $model->empresa->razon_social ?> le ha enviado una solicitud de pago por el valor de <strong>$<?php echo $model->a_pagar ?> USD</strong></p>
                      </td></tr>
                    </table></td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
</div>

<div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
  <tr>
    <td height="40"></td>
  </tr>
  <tr>
    <td style="background:#F6F6F6; border-radius:6px;-moz-border-radius:6px;-webkit-border-radius:6px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
  <tr>
    <td width="40" valign="top">&nbsp;</td>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr><td height='25'></td></tr>
                    <tr>
                      <td>
                        <div class='contentEditableContainer contentTextEditable'>
                          <div class='contentEditable' style='text-align: center;'>
                            <h2 style="font-size: 20px;">Detalle del pago</h2>
                            <br>
                            <p>
                              <table width="100%" border="0">
                                <tr>
                                  <td style="text-align: left;"><strong>Descripción</strong></td>
                                  <td style="text-align: right;"><strong>A pagar</strong></td>
                                </tr>
                                <tr>
                                  <td style="text-align: left;"><?php echo $model->descripcion ?></td>
                                  <td style="text-align: right;">$<?php echo $model->a_pagar ?> USD</td>
                                </tr>
                              </table>

                            </p>
                            <br><br>
                            <a target='_blank' href="<?php echo Url::to(['payments/t?token='.$model->token], true) ?>" class='link2' style="color:#FFFFFF;" >Pagar ahora</a>
                            <br>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr><td height='24'></td></tr>
                  </table></td>
    <td width="40" valign="top">&nbsp;</td>
  </tr>
</tbody>
</table>
</td>
  </tr>
</tbody>
</table>
</div>