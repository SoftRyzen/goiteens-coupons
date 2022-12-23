<?php
/**
 *  Admin Add Coupon page
 */
$user = wp_get_current_user();

// $promocod = 'MINECRAFT';
$promocod = 'JAVA_PROMO';
$activete_count = 10;
$activete_count_user = 1;
$product = 'JAVA';
$conditions = 'Щось неймовірне';
$tariff = '1mouth';
$date_start = date('Y-m-d');
$date_end = '';
$manager = $user->id;
$status = 2;
$promocode_limit = 1000;
$promocode_used = 10;
$amount_payments = 2000;
$amount_surcharge = 10;
$discount_tariff = 'percent'; // Percent or UAH or USD or EUR or PLN

$count = 1;

GOIT_PRMCODE()->model->database->add_promocodes($promocod, $activete_count, $activete_count_user,
	$product, $tariff, $conditions, $date_start, $date_end, $manager, $status, $promocode_limit,
	$promocode_used, $amount_payments, $amount_surcharge, $discount_tariff, $count);
?>

<section class="goit-add-promocode">
	<div class="dashboard__header-wrapper">
		<h1>
			<?php _e('Додати промокоди GoITeens', 'goit_promocode'); ?>
		</h1>
	</div>
	<div class="dashboard__header-wrapper" style="background: #a5ff96; padding: 15px 32px;">
		<div>
			Автоматично було згенеровано
			<?php if ($count > 1): ?>
			групу з <b><?php echo $count; ?></b> промокодів
			<?php else: ?>
			промокод
			<?php endif; ?>
			з тегом <b><?php echo $promocod; ?></b>
		</div>
	</div>
</section>