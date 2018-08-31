 <?php 
 use yii\helpers\ArrayHelper;
 use app\models\Empresas;
 ?>
 <div class="panel panel-default"> 
 	<div style="color:#FFF;" class="panel-heading">Set de pruebas para la certificación de la integración en el ambiente de testing</div> 
 <div class="panel-body"> 
 <div class="row">
 	<div class="col-md-8">
 		<?php 
		 echo \kartik\widgets\Select2::widget([
		    'name' => 'empresa_id',
		    'id' => 'empresa_id',
		    'data' => ArrayHelper::map(Empresas::find()->where('ambiente = "Test" ')->orderBy('razon_social')->asArray()->all(), 'id', 'razon_social'),
		    'options' => [
		        'placeholder' => 'Seleccione una empresa para los test de integración',
		        'multiple' => false
		    ],
		]);
		 
		 ?>		
 	</div>
 	<div class="col-md-4">
 		<a class="btn btn-primary btn-block eliminar_testing" href="javascript:;">Eliminar sesiones testing</a>
 	</div>
 </div>
 <label style="margin-top: 10px;">URL de Respuesta;</label>
 <input readonly="yes" class="col-md-12" type="" name="" value="https://app.pagomedios.com/payments/response">

 <table class="table"> 
 	<thead> 
 		<tr> 
 			<th>#</th> 
 			<th>Caso</th> 
 			<th>Registro en Wallet</th> 
 			<th>Datos Tarjeta</th>
 			<th>Respuesta</th> 
 			<th></th> 
 		</tr> 
 	</thead> 
 	<tbody> 
 		<tr> 
 			<th scope="row">1</th> 
 			<td>
 				Enviar una transacción a Pay-me, luego hacer clic en el botón Retornar al Comercio. Conectarse previamente a Wallet, generar un código de asociación y enviar los datos userCommerce y userCodePayme a V-POS2.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							NA
 						</td>
 					</tr>
 				</table>
 			</td>
 			<td>
 				05<br>
				2300<br>
				User Cancelled in PASS 1
 			</td> 
 			<td><a class="btn btn-primary btn-block casos" monto="100.00" caso="test1" href="javascript:;">Ejecutar caso</a></td> 
 		</tr>

 		<tr> 
 			<th scope="row">2</th> 
 			<td>
 				Enviar una transacción a Pay-me (incluyendo los datos de Wallet userCommerce y userCodePayme previamente generados), ingresar los datos de la tarjeta 485951******0036, dar clic en el botón Pagar, ingresar la contraseña Verified by Visa y dar clic en Continuar. Registrar la tarjeta en Wallet activando el checkbox “Registrar mi tarjeta en Pay-me”.
 			</td>
 			<td>Si</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4859510000000036<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 648<br>
 							<strong>3D:</strong> 360036
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				00 <br>
				00 <br>
				Successful approval/completion
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="200.00" caso="test2" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">3</th> 
 			<td>
 				Enviar una transacción a Pay-me, ingresar los datos de la tarjeta 485951******0051, dar clic en el botón Pagar.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4859510000000051<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 365<br>
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				00 <br>
				00 <br>
				Successful approval/completion
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="300.00" caso="test3" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">4</th> 
 			<td>
 				Enviar una transacción a Pay-me, seleccionar la tarjeta 485951******0036 registrada, dar clic en el botón Pagar, y al solicitarse la contraseña Verified by Visa dar clic en Cancelar .
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4859510000000036<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 648<br>
 							<strong>3D:</strong> 360036
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				05 <br>
				2401 <br>
				Pre Authentication rules not approved
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="400.00" caso="test4" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">5</th> 
 			<td>
 				Enviar una transacción con un monto mayor a 1000.00. Ingresar los datos de la tarjeta 554911******9586.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 5549110920049586<br>
 							<strong>Exp:</strong> Feb-20<br>
 							<strong>CVV:</strong> 608<br>
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				00 <br>
				00 <br>
				Successful approval/completion
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="1200.00" caso="test5" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">6</th> 
 			<td>
 				Enviar una transacción con un monto menor a uno. Por ejemplo, envíe 75 para que llegue 0.75. Ingresar los datos de la tarjeta 485951******0051.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4859510000000051<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 365<br>
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				00 <br>
				00 <br>
				Successful approval/completion
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="0.75" caso="test6" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">7</th> 
 			<td>
 				Enviar una transacción a Pay-me, ingresar los datos de la tarjeta 485951******0028, dar clic en el botón Pagar.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4859510000000028<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 416<br>
 							<strong>3D:</strong> 280028
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				01 <br>
				57 <br>
				Transaction not permitted to cardholder
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="700.00" caso="test7" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 
 		
 		</tbody> 
 	</table> 
 </div> 
</div>