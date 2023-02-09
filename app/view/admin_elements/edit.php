<?php
use goit_prmcode\helper\svg_icons;
use goit_prmcode\helper\model;

$action = $data['action'] ? $data['action'] : '';
$count = $data['group_count'];
$item = $data['item'] ? $data['item'] : false;
$user = wp_get_current_user();

$date_start = $date_end = $product = $tariff = $group = $id_start = $id_end = $first_selected_option = false;
$tariff_active = [];

if ($item) {
	$id = $item->id; // ID of the product
	$product = $item->product; // Product    
	$promocod = $item->promocod; // Promocode of the product
	$promocode_limit = $item->promocode_limit; // Promocode Limit
	$promocode_used = $item->promocode_used; // How match was Used
	$activete_count_user = $item->activete_count_user;
	$date_start = $item->date_start; // Date Start
	$date_end = $item->date_end; // Date End
	$status = $item->promo_status; // Promocode Status
	$conditions = $item->conditions;
	$manager = get_user_by('ID', $item->manager);
	$count = 1;
	$msg_success = $item->msg_success;
	$msg_not_found = $item->msg_not_found;
	$msg_data_end = $item->msg_data_end;

	if (empty($tariff))
		$tariff = GOIT_PRMCODE()->model->tariff->get($promocod);
} else if ($data['group_array']) {
	foreach ($data['group_array'] as $item):

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

		$conditions = $item->conditions;
		$manager = get_user_by('ID', $item->manager);
		$msg_success = $item->msg_success;
		$msg_not_found = $item->msg_not_found;
		$msg_data_end = $item->msg_data_end;
		$product = $item->product;
		$promocod = $item->promocod_group;
		$group = true;
		if (empty($tariff))
			$tariff = GOIT_PRMCODE()->model->tariff->get($promocod, true);

	endforeach;
}

if ($tariff) {
	foreach ($tariff as $item) {
		array_push($tariff_active, $item->tariff);
	}
} ?>

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
		<div class="col-md-3">
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
		<div class="col-md-3">
			<!-- activete_count_user -->
			<label for="activete_count_user">
				<?php _e('Активації для користувача', 'goit_promocode'); ?>
			</label>
			<h4 class="d-flex mb-2">
				<?php _e('Одна активація', 'goit_promocode'); ?>
			</h4>
			<input type="hidden" name="activete_count_user" id="activete_count_user" value="1" min="1" />
		</div>
		<div class="col-md-3 searator-left pr-md-1">
			<!-- date_start -->
			<label for="date_start">
				<?php _e('Початок дії', 'goit_promocode'); ?>
			</label>
			<input type="date" id="date_start" name="date_start"
				value="<?php echo $date_start ? wp_date("Y-m-d", strtotime($date_start)) : wp_date('Y-m-d') ?>"
				min="<?php echo $date_start ? wp_date("Y-m-d", strtotime($date_start)) : wp_date('Y-m-d') ?>" max="">
		</div>
		<div class="col-md-3 pl-md-1">
			<!-- date_end -->
			<label for="date_end">
				<?php _e('Кінець дії', 'goit_promocode'); ?>
			</label>
			<input type="date" id="date_end" name="date_end"
				value="<?php echo $date_end != '' ? wp_date("Y-m-d", strtotime($date_end)) : '' ?>"
				min="<?php echo $date_end != '' ? wp_date("Y-m-d", strtotime($date_end)) : wp_date('Y-m-d') ?>" max="">
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
		<div class="col-md-6 justify-content-start">
			<label for="product" class="d-flex">
				<?php _e('Оберіть продукт', 'goit_promocode'); ?>
			</label>
			<select name="product" id="product" multiple="multiple" class="mb-2">
				<?php model::product_list(str_contains($product, ',') ? explode(",", $product) : $product); ?>
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
				<?php if ($action == 'edit' && $product)
					model::product_list(str_contains($product, ',') ? explode(",", $product) : $product, true); ?>
			</div>
		</div>
	</div>
	<div class="goit-<?php echo $data['class']; ?>__separator"></div>
	<div class="row">
		<!-- tariff -->
		<div class="col-md-6 justify-content-start">
			<label for="tariff">
				<?php _e('Оберіть Тариф', 'goit_promocode'); ?>
			</label>
			<select name="tariff" id="tariff">
				<?php model::tariff_select_options($tariff_active); ?>
			</select>
			<div class="row mt-2">
				<!-- amount_surcharge -->
				<div class="col-md-6 col-xlg-8">
					<label for="amount_surcharge">
						<?php _e('Сума знижки', 'goit_promocode'); ?>
					</label>
					<input type="number" name="amount_surcharge" size="45" min="1" value="1" id="amount_surcharge">
				</div>
				<div class="col-md-6 col-xlg-4">
					<!-- discount_tariff -->
					<label for="amount_surcharge">
						<?php _e('Тип знижки', 'goit_promocode'); ?>
					</label>
					<select name="discount_tariff" id="discount_tariff">
						<option value="percent" selected>
							<?php _e('Відсоток %', 'goit_promocode'); ?>
						</option>
						<option value="UAH">
							<?php _e('UAH', 'goit_promocode'); ?>
						</option>
						<option value="USD">
							<?php _e('USD', 'goit_promocode'); ?>
						</option>
						<option value="EUR">
							<?php _e('EUR', 'goit_promocode'); ?>
						</option>
					</select>
				</div>
			</div>
			<a href="#" id="add_tariff" class="goit-primary-btn mt-3 d-block">
				<?php _e('Додати', 'goit_promocode'); ?>
			</a>
		</div>
		<!-- selected tariff -->
		<div id="selected-tariff" class="col-md-6 justify-content-start">
			<label for="tariff">
				<?php _e('Обрані тарифи', 'goit_promocode'); ?>
			</label>
			<input type="hidden" id="tariff" value="">
			<div class="selected-tariff">
				<?php if ($action == 'edit' && $tariff)
					model::tariff_list($tariff, true); ?>
			</div>
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