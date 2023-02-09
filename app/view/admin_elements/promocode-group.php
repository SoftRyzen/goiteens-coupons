<?php
$id_start = $id_end = null;
$promocod = $date_start = $date_end = '';
$amount_payments = $activete_count = $discount = $payments = 0;
$product;
$status;
$promocode_limit;
$promocode_used;
$amount_surcharge;
$discount_tariff;

foreach ($data['group_array'] as $item):

	$promocod = $item->promocod_group;

	if (!$id_end)
		$id_end = $item->id;

	if ($id_end != $item->id)
		$id_start = $item->id;

	if (empty($date_start))
		$date_start = $item->date_start;

	if (empty($date_end))
		$date_end = $item->date_end;

	if (empty($promocode_used))
		$promocode_used = $item->promocode_used;
	else
		$promocode_used += $item->promocode_used;

	if (empty($promocode_limit))
		$promocode_limit = $item->promocode_limit;
	else
		$promocode_limit += $item->promocode_limit;

	if (empty($status)) {
		$status = $item->promo_status;
		if ($item->promo_status > $status)
			$status = $item->promo_status;
	}

	$orders = GOIT_PRMCODE()->model->order->get_promocode_orders($item->id);

	if (!empty($orders))
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
		}

endforeach; ?>


<div data-group="<?php echo $promocod; ?>" data-status="<?php if ($status == 2) {
	   echo 'active';
   } else {
	   echo 'inactive';
   } ?>" class="goit-promocodes__group <?php if ($status == 0) {
		echo ' inactive';
	} ?>">
	<div class="goit-promocodes__group-id">
		<?php
		if ($id_end) {
			echo $id_start . ' - ' . $id_end;
		} else {
			echo $id_start;
		} ?>
	</div>
	<div class="goit-promocodes__group-promocod">
		<a href="<?php echo admin_url('/admin.php?page=goit_promocode_post&group=' . $promocod) ?>">
			<?php _e('Група', 'goit_promocode'); ?> <b><?php echo $promocod; ?></b>
		</a>
		<div class="actions">
			<?php if ($status == 2) { // Active?>
				<a href="#" data-id="<?php echo $promocod; ?>" data-new-status="inactive" data-type="group">
					<?php _e('Деактивувати', 'goit_promocode'); ?><span> | </span>
				</a>
			<?php } else if ($status == 1) { // Inactive ?>
					<a href="#" data-id="<?php echo $promocod; ?>" data-new-status="active" data-type="group">
					<?php _e('Активувати', 'goit_promocode'); ?><span> | </span>
					</a>
			<?php } ?>
			<a
				href="<?php echo admin_url('/admin.php?page=goit_promocode_post&group=' . $promocod . '&action=edit') ?>">
				<?php _e('Редагувати', 'goit_promocode'); ?>
			</a>
		</div>
	</div>
	<div class="goit-promocodes__group-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-promocodes__group-payments">
		<?php echo $payments; ?>₴
	</div>
	<div class="goit-promocodes__group-discount">
		<?php echo $discount; ?>₴
	</div>
	<div class="goit-promocodes__group-date_start">
		<?php echo wp_date("d.m.Y", strtotime($date_start)); ?>
	</div>
	<div class="goit-promocodes__group-date_end">
		<?php if ($date_end == '0000-00-00') {
			_e('Безкінечно', 'goit_promocode');
		} else {
			echo wp_date("d.m.Y", strtotime($date_end));
		} ?>
	</div>
	<div class="goit-promocodes__group-limit">
		<?php
		$percent = ($promocode_used / $promocode_limit) * 100; ?>

		<div class="percent">
			<?php if ($percent < 30): ?>
				<span class="value">
					<?php echo round($percent); ?>%
				</span>
			<?php endif; ?>
			<span class="bar <?php if ($percent == 100)
				echo 'done' ?>" style="width: <?php if ($percent <= 5)
				echo 5;
			else
				echo $percent; ?>%">
				<?php if ($percent >= 30)
					echo round($percent) . '%'; ?>
			</span>
		</div>
		<?php
		echo $promocode_used . " / ";
		if ($promocode_limit > 0) {
			echo $promocode_limit;
		} else {
			echo '∞';
		}
		?>
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