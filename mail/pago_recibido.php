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
                              <h2 style="font-size: 20px; text-align: center;">Notificación de pago recibido</h2>
                              <br>
                              <p><strong>Estimado/a <?php echo $model->usuario->nombres ?>:</strong></p>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr><td height='24'><p>Ha recibido un pago de <?php echo $model->cliente->nombres.' '.$model->cliente->apellidos ?>, por el valor de <strong>$<?php echo $model->a_pagar ?> USD</strong></p>
                        <p>&nbsp;</p>
                        <p>Para ver el detalle de esta transacción, por favor ingrese a su cuenta Redypago.</p>
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
