<?php 
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>
<div class="container">
	<h3>Documentación para afiliación</h3>
	<table class="table"> 
	<thead> 
		<tr> 
			<th>#</th> 
			<th>Documento</th> 
			<th>Subir PDF</th>  
		</tr> 
	</thead> 
	<tbody> 
		<tr> 
			<th scope="row">1</th> 
			<td>
				Copia Registro Único del Contribuyente (RUC) actualizado.
			</td>
			<td>
				<?= FileUpload::widget([
	                'model' => $model,
	                'attribute' => 'ruc_documento',
	                'url' => ['ajax/subirimagenpagomedios', 'id' => $model->id], // your url, this is just for demo purposes,
	                'options' => ['accept' => 'image/*'],
	                'clientOptions' => [
	                    'maxFileSize' => 1000000,
	                    'dataType' => 'json'
	                ],
	                // Also, you can specify jQuery-File-Upload events
	                // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
	                'clientEvents' => [
	                    'fileuploaddone' => 'function(e, data) {
	                                            $("#empresas-logo").val( data.result[0].name );
	                                            $("#vista_previa_logo_pagomedios").attr("src", data.result[0].url);
	                                        }',
	                    'fileuploadfail' => 'function(e, data) {

	                                        }',
	                ],
	            ]); ?>
			</td>
		</tr>
		<tr> 
			<th scope="row">2</th> 
			<td>
				Copias a color de Cédula y Certificado de Votación actualizado del Representante Legal.
			</td>
			<td>
				
			</td>
		</tr>
		<tr> 
			<th scope="row">3</th> 
			<td>
				Copia actualizada de Tasa de Habilitación o Patente Municipal.
			</td>
			<td>
				
			</td>
		</tr>
		<tr> 
			<th scope="row">4</th> 
			<td>
				Copia de contrato de Arrendamiento (sí lo amerita).
			</td>
			<td>
				
			</td>
		</tr>
		<tr> 
			<th scope="row">5</th> 
			<td>
				Copia de la Última declaración de impuestos. Formulario 102.
			</td>
			<td>
				
			</td>
		</tr>
		<tr> 
			<th scope="row">6</th> 
			<td>
				Copia de los servicios básicos (planilla, agua, luz o teléfono del domicilio del Representante Legal.

			</td>
			<td>
				
			</td>
		</tr>
		<tr> 
			<th scope="row">7</th> 
			<td>
				Copia del Certificado para los Distribuidores de Celulares.
			</td>
			<td>
				
			</td>
		</tr>
		
		</tbody> 
	</table> 
</div>








