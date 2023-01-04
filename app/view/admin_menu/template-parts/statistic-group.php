<?php
$id_start = $id_end = null;
$promocod = '';
$activete_count;
$product;
$manager;
$status;
$amount_payments; // Сума оплат
$amount_surcharge; // Сума знижки
$discount_tariff; // Тип знижки


foreach ($data['group_array'] as $item):

	$order = GOIT_PRMCODE()->model->order->get_promocode_orders($item->id);

	if (!$id_end)
		$id_end = $item->id;

	if ($id_end != $item->id)
		$id_start = $item->id;

	if (empty($activete_count))
		$activete_count = count($order);
	else
		$activete_count += count($order);

	if (empty($amount_payments))
		$amount_payments = $item->amount_payments;
	else
		$amount_payments += $item->amount_payments;

	if (empty($manager))
		$manager = get_user_by('ID', $item->manager); // Manager 

	if (empty($amount_surcharge))
		$amount_surcharge = $item->amount_surcharge;

	if (empty($discount_tariff))
		$discount_tariff = $item->discount_tariff; // Type of discount ( percent / UAH / USD / EUR / PLN ) 

	if (empty($status)) {
		$status = $item->promo_status;
		if ($item->promo_status > $status)
			$status = $item->promo_status;
	}
	$promocod = $item->promocod_group;

endforeach; ?>

<div class="goit-statistic__group link">
	<div class="goit-statistic__group-id">
		<?php
		if ($id_end) {
			echo $id_start . ' - ' . $id_end;
		} else {
			echo $id_start;
		} ?>
	</div>
	<div class="goit-statistic__group-promocod">
		<span>
			<?php _e('Група', 'goit_promocode'); ?>
			<b>
				<?php echo $promocod; ?>
			</b>
		</span>
		<div class="actions">
			<a href="<?php echo admin_url('/admin.php?page=goit_promocode_post&group=' . $promocod) ?>">
				<?php _e('Деталі', 'goit_promocode'); ?>
			</a>
		</div>
	</div>
	<div class="goit-statistic__group-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-statistic__group-amount_payments">
		<?php echo $amount_payments . ' UAH'; ?>
	</div>
	<div class="goit-statistic__group-amount_surcharge">
		<?php
		if ($discount_tariff != 'percent') {
			echo $amount_surcharge . ' ' . $discount_tariff;
		} else {
			echo $amount_surcharge . '%';
		} ?>
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