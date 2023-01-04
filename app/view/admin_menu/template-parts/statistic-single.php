<?php
$item = $data['item'];
$url = $data['url'];

// TableView
$id = $item->id; // ID of the product
$promocod = $item->promocod; // Promocode of the product
$activete_count = $item->activete_count; // How maActive 
$product = $item->product; // Product                 
$status = $item->promo_status; // Promocode Status
$amount_payments = $item->amount_payments; // Amount payments
$amount_surcharge = $item->amount_surcharge; // Amount surcharge
$discount_tariff = $item->discount_tariff; // Type of discount ( percents / UAH / USD / EUR / PLN ) 
$manager = get_user_by('ID', $item->manager); // Manager ?>

<div class="goit-statistic__item">
	<div class="goit-statistic__item-id">
		<?php echo $id; ?>
	</div>
	<div class="goit-statistic__item-promocod">
		<?php echo $promocod; ?>
		<div class="actions">
			<a href="<?php echo admin_url('/admin.php?page=goit_promocode_post&id=' . $promocod); ?>">
				<?php _e('Деталі', 'goit_promocode'); ?>
			</a>
		</div>
	</div>
	<div class="goit-statistic__item-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-statistic__item-amount_payments">
		<?php echo $amount_payments . ' UAH'; ?>
	</div>
	<div class="goit-statistic__item-amount_surcharge">
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