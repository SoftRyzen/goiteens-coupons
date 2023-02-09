<?php
use goit_prmcode\helper\model;

$activete_count = 0;
$item = $data['item'];
$group_name = isset($data['group_name']) ? $data['group_name'] : false;
$action = $data['action'] ? $data['action'] : '';
$ordersID = [];
// TableView
$id = $item->id; // ID of the product
$product = $item->product; // Product            
$promocod = $item->promocod; // Promocode of the product
$promocode_limit = $item->promocode_limit; // Promocode Limit
$promocode_used = $item->promocode_used; // How match was Use     
$date_start = $item->date_start; // Date Start
$date_end = $item->date_end; // Date End
$status = $item->promo_status; // Promocode Status
$activete_count_user = $item->activete_count_user;
$conditions = $item->conditions;
$manager = get_user_by('ID', $item->manager);
$msg_success = $item->msg_success;
$msg_not_found = $item->msg_not_found;
$msg_data_end = $item->msg_data_end;

if (empty($tariff))
	$tariff = GOIT_PRMCODE()->model->tariff->get($promocod);

$orders = GOIT_PRMCODE()->model->order->get_promocode_orders($item->id);
foreach ($orders as $order) {
	if ($order->order_status == 1) {
		$activete_count++;
	}
}

if ($action == 'statistic'):
	$ordersArr = GOIT_PRMCODE()->model->order->get_promocode_orders($item->id);
	if ($ordersArr) {
		if (empty($ordersID))
			$ordersID = [$item->id];
		else
			array_push($ordersID, $item->id);
	}
endif;

$data = [
	"orders" => $orders,
	'type'   => "single"
]; ?>

<section class="goit">
	<div class="goit__header">
		<div class="goit__header-wrapper">
			<h1>
				<?php if ($action == 'statistic'): ?>
					<?php _e('Статистика Промокоду', 'goit_promocode'); ?>
				<?php else: ?>
					<?php _e('Промокод', 'goit_promocode'); ?>
				<?php endif; ?>
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
						<?php echo 'З <b>' . wp_date("d.m.Y", strtotime($date_start)) . '</b>'; ?>
						<?php if ($date_end == '0000-00-00') {
							_e('до <b>∞</b>', 'goit_promocode');
						} else {
							echo __('до', 'goit_promocode') . ' <b>' . wp_date("d.m.Y", strtotime($date_end)) . '</b>';
						} ?>
					</div>
				</div>
			</div>
			<?php if ($action == 'statistic'):
				$dataArr = [
					"orders" => $ordersID ? GOIT_PRMCODE()->model->order->get_promocode_orders($ordersID) : [],
					"type"   => "single"
				]; ?>
				<div class="goit-post__separator"></div>
				<div class="row">
					<?php GOIT_PRMCODE()->view->load('admin_elements/order-table', $dataArr); ?>
				</div>
			<?php else: ?>
				<div class="goit-post__separator"></div>
				<div class="row">
					<!-- selected products -->
					<div id="selected-products" class="col-md-6">
						<div class="label">
							<?php _e('Обрані продукти', 'goit_promocode'); ?>
						</div>
						<div class="selected-products">
							<?php model::product_list(str_contains($product, ',') ? explode(",", $product) : $product, true); ?>
						</div>
					</div>
					<!-- tariff -->
					<div class="col-md-6 justify-content-start">
						<div class="label">
							<?php _e('Обрані тарифи', 'goit_promocode'); ?>
						</div>
						<div class="selected-tariff">
							<?php model::tariff_list($tariff); ?>
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
			<?php endif; ?>
		</div>

		<div class="goit-post__side container">
			<div class="goit-post__side-header">
				<h4 class="title">
					<?php _e('Деталі', 'goit_promocode'); ?>
				</h4>
			</div>
			<div class="goit-post__side-body">

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
				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Кількість активацій', 'goit_promocode'); ?>
					</div>
					<span id="promocode_limit"> <?php
					echo $activete_count . " / ";
					if ($promocode_limit > 0) {
						echo $promocode_limit;
					} else {
						echo '<b>∞</b>';
					}
					?> </span>
				</div>
				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Активації для користувача', 'goit_promocode'); ?>
					</div>
					<span id="activete_count_user"><?php echo $activete_count_user; ?></span>
				</div>

				<div class="goit-post__side-item">
					<div class="label">
						<?php _e('Автор', 'goit_promocode'); ?>
					</div>
					<span id="manager">
						<?php echo $manager->display_name; ?>
					</span>
				</div>
			</div>
			<div class="goit-post__side-buttons">
				<?php if ($group_name): ?>
					<a id="edit_promocode"
						href="<?php echo admin_url('/admin.php?page=goit_promocode_post&group=' . $group_name . '&action=edit') ?>"
						class="goit-primary-btn">
						<?php _e('Редагувати групу', 'goit_promocode'); ?>
					<?php else: ?>
						<a id="edit_promocode"
							href="<?php echo admin_url('/admin.php?page=goit_promocode_post&id=' . $promocod . '&action=edit') ?>"
							class="goit-primary-btn">
							<?php _e('Редагувати', 'goit_promocode'); ?>
						<?php endif; ?>
					</a>
			</div>
		</div>
	</div>
</section>