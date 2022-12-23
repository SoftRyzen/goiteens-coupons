<?php
$item = $data['item'];

// TableView
$id = $item->id; // ID of the product
$promocod = $item->promocod; // Promocode of the product
$activete_count = $item->activete_count; // How maActive 
$product = $item->product; // Product                 
$date_start = $item->date_start; // Date Start
$date_end = $item->date_end; // Date End
$status = $item->promo_status; // Promocode Status
$promocode_limit = $item->promocode_limit; // Promocode Limit
$promocode_used = $item->promocode_used; // How match was Used
$amount_payments = $item->amount_payments; // Amount payments
$amount_surcharge = $item->amount_surcharge; // Amount surcharge
$discount_tariff = $item->discount_tariff; // Type of discount ( percents / UAH / USD / EUR / PLN ) 
?>
<div class="goit-promocodes__item<?php if ($status == 0) {
	echo ' inactive';
} ?>">
	<div class="goit-promocodes__item-id">
		<?php echo $id; ?>
	</div>
	<div class="goit-promocodes__item-promocod">
		<a href="<?php echo admin_url('/admin.php?page=goit_promocode_post&post=' . $promocod . '&action=edit') ?>">
			<b><?php echo $promocod; ?></b>
		</a>
		<div class=" row-actions">
			<a href="#" data-action="deactivate">
				<?php _e('Деактивувати'); ?>
			</a>
			<span> | </span>
			<a href="#" data-action="edit">
				<?php _e('Редагувати'); ?>
			</a>
			<span> | </span>
			<a href="#">
				<?php _e('Зупинити', 'goit_promocode'); ?>
			</a>
		</div>
	</div>
	<div class="goit-promocodes__item-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-promocodes__item-amount_payments">
		<?php echo $amount_payments; ?>
	</div>
	<div class="goit-promocodes__item-amount_surcharge">
		<?php
        if ($discount_tariff != 'percent') {
	        echo $amount_surcharge . ' ' . $discount_tariff;
        } else {
	        echo $amount_surcharge . '%';
        } ?>
	</div>
	<div class="goit-promocodes__item-date_start">
		<?php echo date("d.m.Y", strtotime($date_start)); ?>
	</div>
	<div class="goit-promocodes__item-date_end">
		<?php if ($date_end == '0000-00-00') {
	        echo _e('Безкінечно', 'goit_promocode');
        } else {
	        echo date("d.m.Y", strtotime($date_end));
        } ?>
	</div>
	<div class="goit-promocodes__item-limit">
		<?php
        $percent = ($promocode_used / $promocode_limit) * 100;
        if ($percent <= 5)
	        $percent = 5; ?>

		<div class="percent">
			<span style="width: <?php echo $percent; ?>%"></span>
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
	<div class="goit-promocodes__item-status">
		<?php if ($status == 0) {
	        echo '<div class="inactive">';
	        echo _e('Не активний', 'goit_promocode');
	        echo '</div>';
        } else if ($status == 1) {
	        echo '<div class="paused">';
	        echo _e('На паузі', 'goit_promocode');
	        echo '</div>';
        } else {
	        echo '<div class="actived">';
	        echo _e('Активний', 'goit_promocode');
	        echo '</div>';
        } ?>
	</div>
</div>