<?php
$item = $data['item'];
$url = $data['url'];

// TableView
$discount = $payments = 0;
$id = $item->id; // ID of the product
$promocod = $item->promocod; // Promocode of the product
$product = $item->product; // Product                 
$status = $item->promo_status; // Promocode Status
$discount_tariff = '₴'; 
$manager = get_user_by('ID', $item->manager); // Manager 
$activete_count = 0;
$orders = GOIT_PRMCODE()->model->order->get_promocode_orders($item->id, $data['date_start'], $data['date_end']);

foreach ($orders as $order) {
	if ($order->order_status == 1) {
		if ($order->discount_tariff == 'percent') {
			$orderDiscount = ($order->product_price * $order->discount) / 100;
		} else {
			$orderDiscount = $order->discount;
		}
		$activete_count++;
		$discount += $orderDiscount;
		$payments += $order->product_price - $orderDiscount;
	}
} ?>

<div class="goit-statistic__item<?php if ($activete_count > 0)
	echo ' has-orders'; ?>">
	<div class="goit-statistic__item-id">
		<?php echo $id; ?>
	</div>
	<div class="goit-statistic__item-promocod">
		<b>
			<?php echo $promocod; ?>
		</b>
		<div class="actions">
			<a
				href="<?php echo admin_url('/admin.php?page=goit_promocode_post&id=' . $promocod . '&action=statistic'); ?>">
				<?php _e('Деталі', 'goit_promocode'); ?>
			</a>
		</div>
	</div>
	<div class="goit-statistic__item-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-statistic__item-amount_payments">
		<?php echo $payments . ' ₴'; ?>
	</div>
	<div class="goit-statistic__item-amount_surcharge">
		<?php echo $discount . ' ₴' ?>
	</div>
	<div class="goit-statistic__item-manager">
		<?php echo $manager->display_name; ?>
	</div>
	<div class="goit-promocodes__group-status">
		<?php if ($status == 0) {
			echo '<div class="inactive">';
			_e('Не активний', 'goit_promocode');
			echo '</div>';
		} else if ($status == 1) {
			echo '<div class="paused">';
			_e('На паузі', 'goit_promocode');
			echo '</div>';
		} else {
			echo '<div class="actived">';
			_e('Активний', 'goit_promocode');
			echo '</div>';
		} ?>
	</div>
</div>