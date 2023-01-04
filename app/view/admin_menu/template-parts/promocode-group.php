<?php
$id_start = $id_end = null;
$promocod = '';
$activete_count;
$product;
$date_start = '';
$date_end = '';
$status;
$promocode_limit;
$promocode_used;
$amount_payments;
$amount_surcharge;
$discount_tariff;

foreach ($data['group_array'] as $item):

	if (!$id_end)
		$id_end = $item->id;

	if ($id_end != $item->id)
		$id_start = $item->id;

	if (empty($activete_count))
		$activete_count = $item->activete_count;
	else
		$activete_count += $item->activete_count;

	if (empty($amount_payments))
		$amount_payments = $item->amount_payments;
	else
		$amount_payments += $item->amount_payments;

	if (empty($date_start))
		$date_start = $item->date_start;

	if (empty($date_end))
		$date_end = $item->date_end;

	if (empty($amount_surcharge))
		$amount_surcharge = $item->amount_surcharge;

	if (empty($promocode_used))
		$promocode_used = $item->promocode_used;
	else
		$promocode_used += $item->promocode_used;

	if (empty($promocode_limit))
		$promocode_limit = $item->promocode_limit;
	else
		$promocode_limit += $item->promocode_limit;

	if (empty($discount_tariff))
		$discount_tariff = $item->discount_tariff; // Type of discount ( percent / UAH / USD / EUR / PLN ) 

	if (empty($status)) {
		$status = $item->promo_status;
		if ($item->promo_status > $status)
			$status = $item->promo_status;
	}

	$promocod = $item->promocod_group;

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
			<?php if ($status == 2) { ?>
			<a href="#" data-id="<?php echo $promocod; ?>" data-new-status="inactive" data-type="group">
				<?php _e('Деактивувати', 'goit_promocode'); ?><span> | </span>
			</a>
			<?php } else if ($status == 1) { ?>
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
	<div class="goit-promocodes__group-amount_payments">
		<?php echo $amount_payments . ' UAH'; ?>
	</div>
	<div class="goit-promocodes__group-amount_surcharge">
		<?php
        if ($discount_tariff != 'percent') {
	        echo $amount_surcharge . ' ' . $discount_tariff;
        } else {
	        echo $amount_surcharge . '%';
        } ?>
	</div>
	<div class="goit-promocodes__group-date_start">
		<?php echo date("d.m.Y", strtotime($date_start)); ?>
	</div>
	<div class="goit-promocodes__group-date_end">
		<?php if ($date_end == '0000-00-00') {
	        _e('Безкінечно', 'goit_promocode');
        } else {
	        echo date("d.m.Y", strtotime($date_end));
        } ?>
	</div>
	<div class="goit-promocodes__group-limit">
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