<?php
/**
 *  Settings Page
 */
global $wpdb;

// GOIT_PRMCODE()->model->database->drop_table();
// GOIT_PRMCODE()->model->database->create_table();


$data = [
	'promocod_id'     => 15,
	'invoice'         => '1819773000000087677',
	'date_order'      => date('Y-m-d'),
	'product_price'   => '15000',
	'discount'        => '50',
	'discount_tariff' => 'percent',
];
GOIT_PRMCODE()->model->order->add_order($data);

?>

<section class="goit">
	<div class="goit__header-wrapper">
		<h1>
			<?php _e('Налаштування промокодів GoITeens', 'goit_promocode'); ?>
		</h1>
	</div>
	
	<?php GOIT_PRMCODE()->view->load('admin_menu/template-parts/footer'); ?>
</section>