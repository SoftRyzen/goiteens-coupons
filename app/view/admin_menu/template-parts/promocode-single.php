<?php
$item = $data['item'];
$url = $data['url'];

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
$discount_tariff = $item->discount_tariff; // Type of discount ( percents / UAH / USD / EUR / PLN ) ?>
<div data-status="<?php if ($status == 2) {
	echo 'active';
} else {
	echo 'inactive';
} ?>" class="goit-promocodes__item<?php if ($status == 0) {
	 echo ' inactive';
 } ?>">
	<div class="goit-promocodes__item-id">
		<?php echo $id; ?>
	</div>
	<div class="goit-promocodes__item-promocod">
		<a href="<?php if ($url)
	        echo admin_url('/admin.php?page=goit_promocode_post&id=' . $promocod);
        else
	        '#'; ?>">
			<b><?php echo $promocod; ?></b>
		</a>
		<?php if ($url): ?>
		<div class="actions">
			<?php if ($status == 2) { ?>
			<a href="#" data-id="<?php echo $id; ?>" data-new-status="inactive" data-type="item">
				<?php _e('Деактивувати', 'goit_promocode'); ?><span> | </span>
			</a>
			<?php } else if ($status == 1) { ?>
			<a href="#" data-id="<?php echo $id; ?>" data-new-status="active" data-type="item">
				<?php _e('Активувати', 'goit_promocode'); ?><span> | </span>
			</a>
			<?php } ?>
			<a href="<?php echo admin_url('/admin.php?page=goit_promocode_post&id=' . $promocod . '&action=edit') ?>">
				<?php _e('Редагувати', 'goit_promocode'); ?>
			</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="goit-promocodes__item-activete_count">
		<?php echo $activete_count; ?>
	</div>
	<div class="goit-promocodes__item-amount_payments">
		<?php echo $amount_payments . ' UAH'; ?>
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
	        _e('Безкінечно', 'goit_promocode');
        } else {
	        echo date("d.m.Y", strtotime($date_end));
        } ?>
	</div>
	<div class="goit-promocodes__item-limit">
		<?php
        $percent = ($promocode_used / $promocode_limit) * 100; ?>
		<div class="percent">
			<?php if ($percent < 30): ?>
			<span class="value"><?php echo round($percent); ?>%</span>
			<?php endif; ?>
			<span class="bar <?php if ($percent == 100)
	            echo 'done' ?>" style="width: <?php if ($percent <= 5)
	                  echo 5;
                  else
	                  echo $percent; ?>%"><?php if ($percent >= 30)
	                        echo round($percent) . '%'; ?></span>
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