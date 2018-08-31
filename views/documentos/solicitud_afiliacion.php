<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>
<div class="alert alert-warning">
	<center>
		<strong>
			Antes de iniciar su solicitud, verifique tener sus documentos escaneados en formato PDF, (tamaño menor o igual a 1Mb y legibles), que requerirá subir para la solicitud.  
			<br>
			Una vez entregada toda la documentación solicitada debe esperar la confirmación de parte del Banco sobre su afiliación y poder comenzar con el proceso.
		</strong>
	</center>
</div>
<?php 
if( is_null( $tipo ) ){ ?>
<center>
<h1>Por favor seleccione:</h1>
</center>
	<div class="row-fluid">
		<div class="col-md-6">
			<a class="btn btn-primary btn-block btn-lg" href="<?php echo Url::to(['documentos/solicitudregistro','tipo'=>'persona_natural']); ?>"><span class="glyphicon glyphicon-user"></span> Persona natural</a>
			<p>&nbsp;</p>
			<legend>Documentos de afiliación para Personas Naturales</legend>
			<ul>
				<li>Copia Registro Único del Contribuyente  (RUC) actualizado.</li>
				<li>Copias a color de Cédula y Certificado de Votación actualizado del Representante Legal.</li>
				<li>Copia actualizada de Tasa de Habilitación o Patente Municipal.</li>
				<li>Copia de contrato de Arrendamiento (sí lo amerita).</li>
				<li>Copia de la Última declaración de impuestos. Formulario 102.</li>
				<li>Copia de los servicios básicos (planilla, agua, luz o teléfono del domicilio del Representante Legal.</li>
				<li>Copia del Certificado para los Distribuidores de Celulares.</li>
			</ul>
		</div>
		<div class="col-md-6">
			<a class="btn btn-primary btn-block btn-lg" href="<?php echo Url::to(['documentos/solicitudregistro','tipo'=>'compania']); ?>"><span class="glyphicon glyphicon-home"></span> Compañía</a>
			<p>&nbsp;</p>
			<legend>Documentos de afiliación para Compañías</legend>
			<ul>
				<li>Copias Registro Único del Contribuyente  (RUC) actualizado.</li>
				<li>Copias del Acta del Nombramiento del Representante Legal.</li>
				<li>Copias de Cédula y Certificado de Votación actualizado del Representante Legal.</li>
				<li>Copias de la Constitución de la Compañía.</li>
				<li>Copia de contrato de Arrendamiento (sí lo amerita).</li>
				<li>Copia de la Última declaración de impuestos. Formulario 101.</li>
				<li>Copias de Referencias Bancarias ( emitida por Banco).</li>
				<li>Copias de Certificado de Cumplimiento de Obligaciones (CCO).</li>
				<li>Copia actualizada de Tasa de Habilitación o Patente Municipal.</li>
				<li>Copias de los servicios básicos ( planilla, agua, luz o teléfono del local comercial) (*1 en caso de Apertura de cuenta en Banco Bolivariano).</li>
				<li>Copia del Certificado para los Distribuidores de Celulares.</li>
				<li>De ser agencia de viajes se solicita certificado de afiliación a la IATA.</li>
			</ul>
		</div>
	</div>
<?php }elseif( $tipo == 'persona_natural' ){
	echo $this->render('_partials/_form_persona_natural', [
        'model' => $model,
    ]); 
}else{
	$this->render('_partials/_form_compania', [
        'model' => $model,
    ]); 
}
?>