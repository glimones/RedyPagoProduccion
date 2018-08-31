<?php 
use yii\helpers\Html;
use yii\helpers\Url;

// echo Html::beginForm($pedido->url_pago, 'post', ['id' => 'payment_form', 'style'=>'display:none;']);
// echo Html::textInput('authorization_result', $pedido->authorization_result);
// echo Html::textInput('customer_id', $pedido->cliente->identificacion);
// echo Html::textInput('order_id', $pedido->numero_pedido);
// echo Html::textInput('order_status', $pedido->estado);
// echo Html::textInput('purchase_operation_number', $pedido->purchase_operation_number);
// echo Html::textInput('card_brand', $pedido->brand);
// echo Html::textInput('card_number', $pedido->payment_reference_code);
// echo Html::textInput('card_type', $pedido->reserved_22);
// echo Html::endForm();
?>
<form method="post" action="<?php echo $pedido->url_pago; ?>" id="payment_form" style="display:none;">
	<input type="text" name="authorization_result" value="<?php echo $pedido->authorization_result ?>">
	<input type="text" name="customer_id" value="<?php echo $pedido->cliente->identificacion ?>">
	<input type="text" name="order_id" value="<?php echo $pedido->numero_pedido ?>">
	<input type="text" name="order_status" value="<?php echo $pedido->estado ?>">
	<input type="text" name="purchase_operation_number" value="<?php echo $pedido->purchase_operation_number ?>">
	<input type="text" name="card_brand" value="<?php echo $pedido->brand ?>">
	<input type="text" name="card_number" value="<?php echo $pedido->payment_reference_code ?>">
	<input type="text" name="card_type" value="<?php echo $pedido->reserved_22 ?>">
</form>
<script type="text/javascript">
	document.getElementById("payment_form").submit();
</script>
<div style="margin-top: 150px;">
	<center>
		<?php echo Html::img( Url::to('@web/images/loader_api.gif'), ['class'=> '']) ?>
		<h2>Espere por favor ...</h2>
	</center>
</div>