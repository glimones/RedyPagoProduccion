<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Redypago</title>
  <style type="text/css">
  body {
   padding-top: 0 !important;
   padding-bottom: 0 !important;
   padding-top: 0 !important;
   padding-bottom: 0 !important;
   margin:0 !important;
   width: 100% !important;
   -webkit-text-size-adjust: 100% !important;
   -ms-text-size-adjust: 100% !important;
   -webkit-font-smoothing: antialiased !important;
 }
 .tableContent img {
   border: 0 !important;
   display: block !important;
   outline: none !important;
 }
 a{
  color:#382F2E;
}

p, h1,h2,ul,ol,li,div{
  margin:0;
  padding:0;
}

h1,h2{
  font-weight: normal;
  background:transparent !important;
  border:none !important;
}

@media only screen and (max-width:480px)
		
{
		
table[class="MainContainer"], td[class="cell"] 
	{
		width: 100% !important;
		height:auto !important; 
	}
td[class="specbundle"] 
	{
		width: 100% !important;
		float:left !important;
		font-size:13px !important;
		line-height:17px !important;
		display:block !important;
		padding-bottom:15px !important;
	}	
td[class="specbundle2"] 
	{
		width:80% !important;
		float:left !important;
		font-size:13px !important;
		line-height:17px !important;
		display:block !important;
		padding-bottom:10px !important;
		padding-left:10% !important;
		padding-right:10% !important;
	}
		
td[class="spechide"] 
	{
		display:none !important;
	}
	    img[class="banner"] 
	{
	          width: 100% !important;
	          height: auto !important;
	}
		td[class="left_pad"] 
	{
			padding-left:15px !important;
			padding-right:15px !important;
	}
		 
}
	
@media only screen and (max-width:540px) 

{
		
table[class="MainContainer"], td[class="cell"] 
	{
		width: 100% !important;
		height:auto !important; 
	}
td[class="specbundle"] 
	{
		width: 100% !important;
		float:left !important;
		font-size:13px !important;
		line-height:17px !important;
		display:block !important;
		padding-bottom:15px !important;
	}	
td[class="specbundle2"] 
	{
		width:80% !important;
		float:left !important;
		font-size:13px !important;
		line-height:17px !important;
		display:block !important;
		padding-bottom:10px !important;
		padding-left:10% !important;
		padding-right:10% !important;
	}
		
td[class="spechide"] 
	{
		display:none !important;
	}
	    img[class="banner"] 
	{
	          width: 100% !important;
	          height: auto !important;
	}
		td[class="left_pad"] 
	{
			padding-left:15px !important;
			padding-right:15px !important;
	}
		
}

.contentEditable h2.big,.contentEditable h1.big{
  font-size: 26px !important;
}

 .contentEditable h2.bigger,.contentEditable h1.bigger{
  font-size: 37px !important;
}

td,table{
  vertical-align: top;
}
td.middle{
  vertical-align: middle;
}

a.link1{
  font-size:13px;
  color:#27A1E5;
  line-height: 24px;
  text-decoration:none;
}
a{
  text-decoration: none;
}

.link2{
color:#ffffff;
border-top:10px solid #1588c9;
border-bottom:10px solid #1588c9;
border-left:18px solid #1588c9;
border-right:18px solid #1588c9;
border-radius:3px;
-moz-border-radius:3px;
-webkit-border-radius:3px;
background:#1588c9;
}

.link3{
color:#555555;
border:1px solid #cccccc;
padding:10px 18px;
border-radius:3px;
-moz-border-radius:3px;
-webkit-border-radius:3px;
background:#ffffff;
}

.link4{
color:#27A1E5;
line-height: 24px;
}

h2,h1{
line-height: 20px;
}
p{
  font-size: 14px;
  line-height: 21px;
  color:#000000;
}

.contentEditable li{
 
}

.appart p{
 
}
.bgItem{
background: #ffffff;
}
.bgBody{
background: #ffffff;
}

img { 
  outline:none; 
  text-decoration:none; 
  -ms-interpolation-mode: bicubic;
  width: auto;
  clear: both; 
  display: block;
  float: none;
}

</style>


<script type="colorScheme" class="swatch active">
{
    "name":"Default",
    "bgBody":"ffffff",
    "link":"27A1E5",
    "color":"AAAAAA",
    "bgItem":"ffffff",
    "title":"444444"
}
</script>

<?php $this->head() ?>
</head>
<body paddingwidth="0" paddingheight="0" bgcolor="#d1d3d4"  style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
  <?php $this->beginBody() ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" style="font-family:helvetica, sans-serif;" class="MainContainer">
      <!-- =============== START HEADER =============== -->
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td class="movableContentContainer">
      <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative; background-color: #022C35;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="15"></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
     
      <td width="10" valign="top">&nbsp;</td>
      <td valign="middle" style='vertical-align: middle;'>
                          <div class='contentEditableContainer contentTextEditable'>
                            <div class='contentEditable' style='text-align: left;font-weight: light; color:#555555;font-size:26;line-height: 39px;font-family: Helvetica Neue;'>
                              <h1 class='big'><a target='_blank' href="http://pagomedios.com" style='color:#444444'>
                                <img src="data:image/png;base64,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" />
                              </a></h1>
                            </div>
                          </div>
                        </td>
    </tr>
  </tbody>
</table>
</td>
      <td valign="middle" style='vertical-align: middle;' width='250'>
        <div class='contentEditableContainer contentTextEditable'>
          <div class='contentEditable' style='text-align: right; color: #FFF;'>
            <?php echo Yii::$app->controller->pedidoFecha; ?> <br>
            <span style="color: #FFFFFF;"><?php echo Yii::$app->controller->numeroPedido; ?></span>
             <td valign="top" width="20">&nbsp;</td>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table></td>
    </tr>
    <tr>
       <td height='15'></td>
    </tr>
    <tr>
     </tr>
  </tbody>
</table>
	  </div>
      <!-- =============== END HEADER =============== -->
<!-- =============== START BODY =============== -->

      <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tbody>
          <tr>
            <td class="spechide" width="90" valign="top">&nbsp;</td>
            <td><table align="center" cellspacing="0" cellpadding="0" width="100%">
                            <tbody><tr>
                              <td>
                                <div class="contentEditableContainer contentTextEditable">
                                  <div class="contentEditable" style="text-align: justify;color:#000000;">
                                    <p>
                                      <?= $content ?>
                                    </p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </tbody></table></td>
            <td class="spechide" width="90" valign="top">&nbsp;</td>
          </tr>
        </tbody>
      </table>
      <!-- =============== END BODY =============== -->
<!-- =============== START FOOTER =============== -->

      <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td height="48"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td valign="top" width="90" class="spechide">&nbsp;</td>
            <td><table width="100%" cellpadding="0" cellspacing="0" align="center">
                            <tr>
                              <td>
                                <div class='contentEditableContainer contentTextEditable'>
                                  <div class='contentEditable' style='text-align: center;color:#AAAAAA;'>
                                    <p>
                                    <?php if( Yii::$app->controller->pedidoIdioma == 'en' ){ ?>
                                      This message was automatically generated, please do not reply to this email because you will not receive a response.
                                    <?php }elseif( Yii::$app->controller->pedidoIdioma == 'es' ) { ?>
                                      Este mensaje fue generado automáticamente, por favor no responda a este correo electrónico ya que no recibirá respuesta.
                                    <?php } ?>
                                    </p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table></td>
            <td valign="top" width="90" class="spechide">&nbsp;</td>
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
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td valign="top" width="185" class="spechide">&nbsp;</td>
            <td class="specbundle2"><table width="100%" cellpadding="0" cellspacing="0" align="center">
                            <tr>
                              <td width='100%'>
                                <div class='contentEditableContainer contentFacebookEditable'>
                                  <div class='contentEditable' style='text-align: center;color:#AAAAAA;'>
                                    <?php if( Yii::$app->controller->pedidoIdioma == 'en' ){ ?>
                                      2017 Redypago. All rights reserved.
                                    <?php }elseif( Yii::$app->controller->pedidoIdioma == 'es' ) { ?>
                                      2017 Redypago. Derechos reservados.
                                    <?php } ?>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table></td>
            <td valign="top" width="185" class="spechide">&nbsp;</td>
          </tr>
        </tbody>
      </table>
      </td>
          </tr>
          <tr>
          	<td height='40'></td>
          </tr>
        </tbody>
      </table>

     <!-- =============== END FOOTER =============== --> 
      </div>
      </td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
<?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage() ?>