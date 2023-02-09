<?php
$discount = $payments = $count = 0;
$orders = isset($data) ? $data : [];
if (!empty($orders))
	foreach ($orders as $order) {
		if ($order->order_status == 1) {
			if ($order->discount_tariff == 'percent') {
				$orderDiscount = ($order->product_price * $order->discount) / 100;
			} else {
				$orderDiscount = $order->discount;
			}
			$count++;
			$discount += $orderDiscount;
			$payments += $order->product_price - $orderDiscount;
		}
	}
?>
<div id="orders_result" class="result">
	<?php _e('Кількість оплат', 'goit_promocode'); ?>: <span class="count"><?php echo $count; ?></span>
	<?php _e('Сума оплат', 'goit_promocode'); ?> <span class="payments">
		<?php echo $payments; ?>₴
	</span>
	<?php _e('Сума знижки', 'goit_promocode'); ?>: <span class="discount">
		<?php echo $discount; ?>₴
	</span>
</div>