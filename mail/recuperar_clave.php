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
                              <h2 style="font-size: 20px; text-align: center;">Recuperar Clave</h2>
                              <br>
                              <p><strong>Estimado/a <?php echo $model->nombres ?>:</strong></p>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr><td height='24'><p>Conforme a su solicitud de recuperación de clave, la misma se adjunta a continuación:</p>
                        <p>&nbsp;</p>
                        <center>
                          <?php echo $clave_temporal; ?>
                        </center>
                        <p>&nbsp;</p>
                        <p>Le recomendamos ingresar en su cuenta Redypago y cambiar de clave.</p>
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
