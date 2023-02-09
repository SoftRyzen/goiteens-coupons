<?php
/**
 *  Statistic page
 */
use goit_prmcode\helper\svg_icons;

$data = [
	"page"       => 'statistic',
	"promocodes" => GOIT_PRMCODE()->model->promocode->get_promocodes(),
	"date_start" => wp_date('Y-m-d', strtotime('-7 days')),
	"date_end"   => wp_date('Y-m-d'),
];
$table_items = 0;
$promo_counter = GOIT_PRMCODE()->model->promocode->get_promocodes_count();
$orders = GOIT_PRMCODE()->model->order->get_promocode_orders('all', $data["date_start"], $data["date_end"]);
?>

<section id="statistic" class="goit">
	<div class="goit__header-wrapper">
		<h1>
			<?php _e('Статистика промокодів GoITeens', 'goit_promocode'); ?>
		</h1>
	</div>

	<div class="goit-statistic">
		<div class="goit-statistic__table">
			<div class="goit-statistic__table-datepicker">
				<?php echo svg_icons::get('calendar'); ?>
				<input id="datepicker" class="form-control" type="text" name="daterange" value="" />
				<?php GOIT_PRMCODE()->view->load('admin_elements/orders-result', $orders); ?>
			</div>

			<div class="goit-statistic__table-header">
				<div>
					<?php _e("ID's", 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Промокод', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Кількість оплат', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Сума оплат', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Сума знижки', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Менеджер', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Статус', 'goit_promocode'); ?>
				</div>
			</div>
			<div class="goit-statistic__table-body">
				<?php GOIT_PRMCODE()->view->load('admin_elements/body_table', $data); ?>
			</div>
			<div class="goit-statistic__table-footer">
				<div class="counter-elements">
					<?php echo __('Кількість промокодів на сторінці : ', 'goit_promocode') . $promo_counter; ?>
				</div>
			</div>

		</div>
	</div>

	<?php GOIT_PRMCODE()->view->load('admin_elements/footer'); ?>
</section>