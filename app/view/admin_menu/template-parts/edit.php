<?php
use goit_prmcode\helper\svg_icons;

$action = $data['action'] ? $data['action'] : '';
$count = $data['group_count'];
$item = $data['item'] ? $data['item'] : '';
$user = wp_get_current_user();

/* Declaring variables. */
// Default Values
$date_start = $date_end = $product = $tariff =
	$group = $id_start = $id_end = false;
$discount_tariff = 'percent';

if ($data['group_array'] && $action != 'new') {
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

		$activete_count_user = $item->activete_count_user;
		$conditions = $item->conditions;
		$manager = get_user_by('ID', $item->manager);
		$msg_success = $item->msg_success;
		$msg_not_found = $item->msg_not_found;
		$msg_data_end = $item->msg_data_end;
		$product = $item->product;
		$promocod = $item->promocod_group;
		$tariff = $item->tariff;
		$group = true;
		break;

	endforeach;
}

if ($item && $action != 'new') {
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
	$activete_count_user = $item->activete_count_user;
	$tariff = $item->tariff;
	$conditions = $item->conditions;
	$discount_tariff = $item->discount_tariff; // Type of discount ( percents / UAH / USD / EUR / PLN ) 
	$manager = get_user_by('ID', $item->manager);
	$count = 1;
	$msg_success = $item->msg_success;
	$msg_not_found = $item->msg_not_found;
	$msg_data_end = $item->msg_data_end;
}

?>

<div class="goit-<?php echo $data['class']; ?>__content container">
	<div class="row">
		<div class="col-md-7">
			<!-- promocod -->
			<label for="promocod">
				<?php _e('Унікальний Промокод', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $promocod): ?>
				<h2 id="promocod">
					<?php echo $promocod ?>
				</h2>
				<input type="hidden" name="promocod" size="45" id="promocod" value="<?php echo $promocod ?>">
				<?php else: ?>
				<input type="text" name="promocod" size="45" id="promocod" value
					placeholder="<?php _e('ПРОМОКОД ЛАТИНИЦЕЮ', 'goit_promocode'); ?>">
				<?php endif; ?>
		</div>
		<div class="col-md-2">
			<!-- count -->
			<label for="count">
				<?php _e('Кількість', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $count): ?>
				<div class="count">
					<?php echo $count ?>
				</div>
				<input type="hidden" name="count" size="45" value="<?php echo $count ?>" id="count" min="1">
				<?php else: ?>
				<input type="number" name="count" size="45" value="1" id="count" min="1">
				<?php endif; ?>
		</div>
		<div class="col-md-3">
			<label for="status"><?php _e('Статус', 'goit_promocode'); ?></label>
			<select name="status" id="status">
				<option value="2">
					<?php _e('Активний', 'goit_promocode'); ?>
				</option>
				<option value="1" <?php if ($action == 'edit' && $status == 1)
				echo ' selected'; ?>>
					<?php _e('На паузі', 'goit_promocode'); ?>
				</option>
			</select>
		</div>
	</div>

	<div class="goit-<?php echo $data['class']; ?>__separator"></div>
	<div class="row">
		<div class="col-md-3 pr-md-1">
			<!-- promocode_limit -->
			<label for="promocode_limit">
				<?php _e('Кількість активацій', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $promocode_limit): ?>
				<input type="number" name="promocode_limit" id="promocode_limit" value="<?php echo $promocode_limit ?>"
					min="1" />
				<?php else: ?>
				<input type="number" name="promocode_limit" id="promocode_limit" value="1" min="1" />
				<?php endif; ?>
		</div>
		<div class="col-md-3 pl-md-1">
			<!-- activete_count_user -->
			<label for="activete_count_user">
				<?php _e('Активації для 1 користувача', 'goit_promocode'); ?>
			</label>
			<input type="number" name="activete_count_user" id="activete_count_user" value="1" min="1" />
		</div>
		<div class="col-md-3 searator-left pr-md-1">
			<!-- date_start -->
			<label for="date_start">
				<?php _e('Початок дії', 'goit_promocode'); ?>
			</label>
			<input type="date" id="date_start" name="date_start"
				value="<?php echo $date_start ? date("Y-m-d", strtotime($date_start)) : date('Y-m-d') ?>"
				min="<?php echo $date_start ? date("Y-m-d", strtotime($date_start)) : date('Y-m-d') ?>" max="">
		</div>
		<div class="col-md-3 pl-md-1">
			<!-- date_end -->
			<label for="date_end">
				<?php _e('Кінець дії', 'goit_promocode'); ?>
			</label>
			<input type="date" id="date_end" name="date_end"
				value="<?php echo $date_end != '' ? date("Y-m-d", strtotime($date_end)) : '' ?>"
				min="<?php echo $date_end != '' ? date("Y-m-d", strtotime($date_end)) : date('Y-m-d') ?>" max="">
		</div>
	</div>

	<div class="goit-<?php echo $data['class']; ?>__separator"></div>
	<div class="row">
		<!-- info block -->
		<div class="col-md-12">
			<div class="info mb-2">
				<?php echo svg_icons::get('info') ?>
				<?php _e('Обраний тариф діє для усіх обраних курсів, та группи купонів.', 'goit_promocode'); ?>
			</div>
		</div>
		<!-- product -->
		<div class="col-md-6">
			<label for="product" class="d-flex">
				<?php _e('Оберіть продукт', 'goit_promocode'); ?>
			</label>
			<select name="product" id="product" multiple="multiple" class="mb-2">
				<?php
				$selected = '';
				$productArr = str_contains($product, ',') ? explode(",", $product) : $product;
				$loop = new WP_Query(array(
					'post_type'      => 'courses',
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC'
				));
				while ($loop->have_posts()):
					$loop->the_post();
					if (is_array($productArr)) {
						foreach ($productArr as $p) {
							if ($p == get_the_ID())
								$selected = ' selected';
						}
					} else {
						if ($product == get_the_ID())
							$selected = ' selected';
					} ?>
					<option
						value="<?php echo get_the_permalink(); ?>?id=<?php echo get_the_ID(); ?>&name=<?php echo get_the_title(); ?>"
						<?php echo $selected; ?>>
						<?php echo get_the_title(); ?>
					</option>

					<?php endwhile;
				wp_reset_postdata(); ?>
			</select>
		</div>
		<!-- selected products -->
		<div id="selected-products" class="col-md-6">
			<label for="products">
				<?php _e('Обрані продукти', 'goit_promocode'); ?>
			</label>
			<input type="hidden" id="products" value="<?php if ($product)
			echo $product; ?>">
			<div class="selected-products">
				<?php
				if ($action == 'edit' && $product):
					$product = str_contains($product, ',') ? explode(",", $product) : $product;
					$loop = new WP_Query(array(
						'post_type'      => 'courses',
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'p'              => $product
					));
					while ($loop->have_posts()):
						$loop->the_post(); ?>

						<a href="<?php echo get_the_permalink(); ?>" target="_blank">
							<?php echo get_the_title(); ?>
						</a>

						<?php endwhile;
					wp_reset_postdata();
				endif; ?>
			</div>
		</div>
		<!-- tariff -->
		<div class="col-md-6">
			<label for="tariff">
				<?php _e('Оберіть Тариф', 'goit_promocode'); ?>
			</label>
			<select name="tariff" id="tariff">
				<option value="1" <?php if ($tariff == 1)
				echo 'selected'; ?>>
					<?php _e('1 місяць', 'goit_promocode'); ?>
				</option>
				<option value="2" <?php if ($tariff == 2)
				echo 'selected'; ?>>
					<?php _e('2 місяця', 'goit_promocode'); ?>
				</option>
				<option value="3" <?php if ($tariff == 3)
				echo 'selected'; ?>>
					<?php _e('3 місяця', 'goit_promocode'); ?>
				</option>
				<option value="4" <?php if ($tariff == 4)
				echo 'selected'; ?>>
					<?php _e('4 місяця', 'goit_promocode'); ?>
				</option>
				<option value="5" <?php if ($tariff == 5)
				echo 'selected'; ?>>
					<?php _e('5 місяців', 'goit_promocode'); ?>
				</option>
				<option value="6" <?php if ($tariff == 6)
				echo 'selected'; ?>>
					<?php _e('6 місяців', 'goit_promocode'); ?>
				</option>
				<option value="7" <?php if ($tariff == 7)
				echo 'selected'; ?>>
					<?php _e('7 місяців', 'goit_promocode'); ?>
				</option>
				<option value="8" <?php if ($tariff == 8)
				echo 'selected'; ?>>
					<?php _e('8 місяців', 'goit_promocode'); ?>
				</option>
				<option value="9" <?php if ($tariff == 9)
				echo 'selected'; ?>>
					<?php _e('9 місяців', 'goit_promocode'); ?>
				</option>
				<option value="10" <?php if ($tariff == 10)
				echo 'selected'; ?>>
					<?php _e('10 місяців', 'goit_promocode'); ?>
				</option>
				<option value="11" <?php if ($tariff == 11)
				echo 'selected'; ?>>
					<?php _e('11 місяців', 'goit_promocode'); ?>
				</option>
				<option value="12" <?php if ($tariff == 12)
				echo 'selected'; ?>>
					<?php _e('12 місяців', 'goit_promocode'); ?>
				</option>
			</select>
		</div>
		<div class="col-md-3">
			<!-- amount_surcharge -->
			<label for="amount_surcharge">
				<?php _e('Сума знижки', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $amount_surcharge): ?>
				<input type="number" name="amount_surcharge" id="amount_surcharge" value="<?php echo $amount_surcharge ?>"
					min="1" />
				<?php else: ?>
				<input type="number" name="amount_surcharge" size="45" min="1" value id="amount_surcharge">
				<?php endif; ?>
		</div>
		<div class="col-md-3">
			<!-- discount_tariff -->
			<label for="amount_surcharge"><?php _e('Тип знижки', 'goit_promocode'); ?></label>
			<select name="discount_tariff" id="discount_tariff">
				<option value="percent" <?php if ($discount_tariff == 'percent')
				echo 'selected'; ?>>
					<?php _e('Відсоток %', 'goit_promocode'); ?>
				</option>
				<option value="UAH" <?php if ($discount_tariff == 'UAH')
				echo 'selected'; ?>>
					<?php _e('UAH', 'goit_promocode'); ?>
				</option>
				<option value="USD" <?php if ($discount_tariff == 'USD')
				echo 'selected'; ?>>
					<?php _e('USD', 'goit_promocode'); ?>
				</option>
				<option value="EUR" <?php if ($discount_tariff == 'EUR')
				echo 'selected'; ?>>
					<?php _e('EUR', 'goit_promocode'); ?>
				</option>
			</select>
		</div>

	</div>

	<!-- conditions -->
	<div class="goit-<?php echo $data['class']; ?>__separator"></div>
	<div class="row">
		<div class="col-md-12">
			<label for="conditions">
				<?php _e('Умови акції', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $conditions): ?>
				<textarea name="conditions" id="conditions" cols="30" rows="2"
					maxlength="50"><?php echo $conditions ?></textarea>
				<?php else: ?>
				<textarea name="conditions" id="conditions" cols="30" rows="2" maxlength="50"></textarea>
				<?php endif; ?>

		</div>
	</div>

	<!-- Messages -->
	<div class="goit-<?php echo $data['class']; ?>__separator"></div>
	<div class="row">
		<div class="col-md-4">
			<!-- msg_success -->
			<label for="msg_success">
				<?php _e('Повідомлення: <b>Активовано</b>', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $msg_success): ?>
				<textarea id="msg_success" name="msg_success" size="45" cols="8" rows="2"
					placeholder="<?php _e('Успішно - Промокод активовано!', 'goit_promocode'); ?>"><?php echo $msg_success ?></textarea>
				<?php else: ?>
				<textarea id="msg_success" name="msg_success" size="45" cols="8" rows="2"
					placeholder="<?php _e('Успішно - Промокод активовано!', 'goit_promocode'); ?>"><?php _e('Успішно - Промокод активовано!', 'goit_promocode'); ?></textarea>
				<?php endif; ?>
		</div>
		<div class="col-md-4 searator-left">
			<!-- msg_not_found -->
			<label for="msg_not_found">
				<?php _e('Повідомлення: <b>Не існує</b>', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $msg_not_found): ?>
				<textarea id="msg_not_found" name="msg_not_found" size="45" cols="8" rows="2"
					placeholder="<?php _e('На жаль такого промокода не існує.', 'goit_promocode'); ?>"><?php echo $msg_not_found ?></textarea>
				<?php else: ?>
				<textarea id="msg_not_found" name="msg_not_found" size="45" cols="8" rows="2"
					placeholder="<?php _e('На жаль такого промокода не існує.', 'goit_promocode'); ?>"><?php _e('На жаль такого промокода не існує.', 'goit_promocode'); ?></textarea>
				<?php endif; ?>
		</div>
		<div class="col-md-4 searator-left">
			<!-- msg_data_end -->
			<label for="msg_data_end">
				<?php _e('Повідомлення: <b>Завершено</b>', 'goit_promocode'); ?>
			</label>
			<?php if ($action == 'edit' && $msg_data_end): ?>
				<textarea id="msg_data_end" name="msg_data_end" size="45" cols="8" rows="2"
					placeholder="<?php _e('На жаль термін дії промокоду закінчився.', 'goit_promocode'); ?>"><?php echo $msg_data_end ?></textarea>
				<?php else: ?>
				<textarea id="msg_data_end" name="msg_data_end" size="45" cols="8" rows="2"
					placeholder="<?php _e('На жаль термін дії промокоду закінчився.', 'goit_promocode'); ?>"><?php _e('На жаль термін дії промокоду закінчився.', 'goit_promocode'); ?></textarea>
				<?php endif; ?>
		</div>
	</div>

	<!-- Manager -->
	<input type="hidden" id="manager" value="<?php echo $user->id; ?>">
</div>