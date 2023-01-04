<?php
$item = $data['item'];
$action = $data['action'] ? $data['action'] : '';

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
$activete_count_user = $item->activete_count_user;
$tariff = $item->tariff;
$conditions = $item->conditions;
$discount_tariff = $item->discount_tariff; // Type of discount ( percents / UAH / USD / EUR / PLN ) 
$manager = get_user_by('ID', $item->manager);
$msg_success = $item->msg_success;
$msg_not_found = $item->msg_not_found;
$msg_data_end = $item->msg_data_end;
?>
<section class="goit">
	<div class="goit__header">
		<div class="goit__header-wrapper">
			<h1>
				<?php _e('Промокод', 'goit_promocode'); ?>
			</h1>
		</div>
	</div>
	<?php if ($action == 'updated'): ?>
		<div class="goit__status--success">
			<?php _e('Успішно оновлено', 'goit_promocode'); ?>
		</div>
	<?php endif; ?>
	<div class="goit__body goit-post">
		<div class="goit-post__content container">
			<div class="row">
				<div class="col-md-9">
					<!-- promocod -->
					<div class="label">
						<?php _e('Назва промокоду', 'goit_promocode'); ?>
					</div>
					<h2 id="promocod"><?php echo $promocod ?></h2>
				</div>
				<div class="col-md-3">
					<div class="label">
						<?php _e('Час дії промокоду', 'goit_promocode'); ?>
					</div>
					<div id="date">
						<?php echo 'З <b>' . date("d.m.Y", strtotime($date_start)) . '</b>'; ?>
						<?php if ($date_end == '0000-00-00') {
							_e('до <b>∞</b>', 'goit_promocode');
						} else {
							echo __('до', 'goit_promocode') . ' <b>' . date("d.m.Y", strtotime($date_end)) . '</b>';
						} ?>
					</div>
				</div>
			</div>

			<div class="goit-post__separator"></div>
			<div class="row">
				<!-- selected products -->
				<div id="selected-products" class="col-md-6">
					<div class="label">
						<?php _e('Обрані продукти', 'goit_promocode'); ?>
					</div>
					<input type="hidden" id="products" value="">
					<div class="selected-products">
						<?php
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
						wp_reset_postdata(); ?>
					</div>
				</div>
				<!-- tariff -->
				<div class="col-md-3">
					<div class="label">
						<?php _e('Обраний Тариф', 'goit_promocode'); ?>
					</div>
					<div name="tariff" id="tariff">
						<?php echo $tariff . ' ' . __('місяці', 'goit_promocode'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<!-- amount_surcharge -->
					<div class="label">
						<?php _e('Сума знижки', 'goit_promocode'); ?>
					</div>
					<div class="amount_surcharge">
						<?php echo $amount_surcharge . ' ';
						if ($discount_tariff == 'percent')
							echo '%';
						else
							echo $discount_tariff; ?>
					</div>


				</div>

			</div>

			<!-- conditions -->
			<div class="goit-post__separator"></div>
			<div class="row">
				<div class="col-md-12">
					<div class="label">
						<?php _e('Умови акції', 'goit_promocode'); ?>
					</div>
					<h4 id="conditions">
						<?php
						if ($conditions == '')
							_e('Нічого не знайдено', 'goit_promocode');
						else
							echo $conditions;
						?>
					</h4>
				</div>
			</div>

			<!-- Messages -->
			<div class="goit-post__separator"></div>
			<div class="row">
				<div class="col-md-4">
					<!-- msg_success -->
					<div class="label">
						<?php _e('Повідомлення: <b>Активовано</b>', 'goit_promocode'); ?>
					</div>
					<h4 id="msg_success">
						<?php echo $msg_success; ?>
					</h4>
				</div>
				<div class="col-md-4 searator-left">
					<!-- msg_not_found -->
					<div class="label">
						<?php _e('Повідомлення: <b>Не існує</b>', 'goit_promocode'); ?>
					</div>
					<h4 id="msg_success">
						<?php echo $msg_not_found; ?>
					</h4>
				</div>
				<div class="col-md-4 searator-left">
					<!-- msg_data_end -->
					<div class="label">
						<?php _e('Повідомлення: <b>Завершено</b>', 'goit_promocode'); ?>
					</div>
					<h4 id="msg_data_end">
						<?php echo $msg_not_found; ?>
					</h4>
				</div>
			</div>
		</div>

		<div class="goit-post__side container">
			<div class="goit-post__side-header">
				<h4 class="title">
					<?php _e('Деталі', 'goit_promocode'); ?>
				</h4>
			</div>
			<div class="goit-post__side-body">

				<div class="goit-post__side-item">
					<div class="label"><?php _e('Автор', 'goit_promocode'); ?></div>
					<span id="manager">
						<?php echo $manager->display_name; ?>
					</span>
				</div>
				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Активації для користувача', 'goit_promocode'); ?>
					</div>
					<span id="activete_count_user"><?php echo $activete_count_user; ?></span>
				</div>
				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Кількість активацій', 'goit_promocode'); ?>
					</div>
					<span id="promocode_limit"> <?php
					echo $promocode_used . " / ";
					if ($promocode_limit > 0) {
						echo $promocode_limit;
					} else {
						echo '<b>∞</b>';
					}
					?> </span>
				</div>
				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Статус', 'goit_promocode'); ?>
					</div>
					<span id="status"><?php
					if ($status == 2) {
						echo 'Активний';
					} else if ($status == 1) {
						echo 'Призупинено';
					} else {
						echo 'Не активний';
					} ?></span>
				</div>
			</div>
			<div class="goit-post__side-buttons">
				<a id="edit_promocode"
					href="<?php echo admin_url('/admin.php?page=goit_promocode_post&group=' . $promocod . '&action=edit') ?>"
					class="goit-primary-btn">
					<?php _e('Редагувати', 'goit_promocode'); ?>
				</a>
				<a id="export" href="#" class="goit-secondary-btn">
					<?php _e('Експорт', 'goit_promocode'); ?>
				</a>
			</div>
		</div>
	</div>
</section>