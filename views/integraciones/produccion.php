 <?php 
 use yii\helpers\ArrayHelper;
 use app\models\Empresas;
 ?>
 <div class="panel panel-default"> 
 	<div style="color:#FFF;" class="panel-heading">Set de pruebas para la certificación de la integración en el ambiente de producción</div> 
 <div class="panel-body"> 
 <div class="row">
 	<div class="col-md-8">
 		<?php 
		 echo \kartik\widgets\Select2::widget([
		    'name' => 'empresa_id',
		    'id' => 'empresa_id',
		    'data' => ArrayHelper::map(Empresas::find()->where('ambiente = "Test-Producción" ')->orderBy('razon_social')->asArray()->all(), 'id', 'razon_social'),
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
 				Enviar una transacción a Pay-me, luego hacer clic en el botón Retornar al Comercio.
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
 				Enviar una transacción a Pay-me (incluyendo los datos de Wallet userCommerce y userCodePayme previamente generados), ingresar los datos de la tarjeta 411111******1111, dar clic en el botón Pagar, ingresar la contraseña Verified by Visa y dar clic en Continuar.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 4111111111111111<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 648<br>
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				01 <br>
				05 <br>
				No such issuer
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="200.00" caso="test2" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		<tr> 
 			<th scope="row">3</th> 
 			<td>
 				Enviar una transacción con cualquier monto (incluyendo los datos de Wallet userCommerce y userCodePayme previamente generados). Ingresar los datos de la tarjeta 503849******0032, dar clic en el botón Pagar.
 			</td>
 			<td>No</td> 
 			<td>
 				<table width="200px">
 					<tr>
 						<td>
 							<strong>#:</strong> 5038490000000032<br>
 							<strong>Exp:</strong> Dic-20<br>
 							<strong>CVV:</strong> 517<br>
 						</td>
 					</tr>
 				</table>
 			</td> 
 			<td>
 				01 <br>
				14 <br>
				Invalid account number (no such number)
 			</td>
 			<td><a class="btn btn-primary btn-block casos" monto="300.00" caso="test3" href="javascript:;">Ejecutar caso</a></td> 
 		</tr> 

 		
 		</tbody> 
 	</table> 
 </div> 
</div>