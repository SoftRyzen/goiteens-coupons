<?php
/*
 * Plugin UI Page
 */
?>

<script>
	/* It's a script that is used to open the menu in the admin panel. */
	const promocode = document.querySelector('#toplevel_page_goit_promocode'),
		promocode_link = document.querySelector('#toplevel_page_goit_promocode > a');

	document.title = '<?php echo 'Promocode UI Page'; ?>';
	promocode_link.classList.remove('wp-not-current-submenu');
	promocode_link.classList.add('wp-has-current-submenu');
	promocode.classList.remove('wp-not-current-submenu');
	promocode.classList.add('wp-has-current-submenu');
	promocode.classList.add('wp-menu-open');
</script>


<?php /* Adding a new promocode to the database. */
/* $data = [
'promocod'            => 'JAVA',
'count'               => 3,
'activete_count_user' => 1,
'product'             => 1312312,
'conditions'          => 'sas',
"date_start"          => wp_date('Y-m-d'),
"date_end"            => wp_date('Y-m-d'),
"promo_status"        => 0,
'promocode_limit'     => 1,
"msg_success"         => '',
"msg_not_found"       => '',
"msg_data_end"        => '',
];
// GOIT_PRMCODE()->model->promocode->add($data);
?>
<?php /* This is a test data for the database. */
/* $promocode_id = 1;
$data_order = [
'promocod_id'     => $promocode_id,
'invoice'         => '0',
'date_order'      => wp_date('Y-m-d'),
'product_price'   => '16200',
'discount'        => '10',
'discount_tariff' => 'percent',
'order_status'    => 1
];
// GOIT_PRMCODE()->model->order->add($data_order);
// GOIT_PRMCODE()->model->promocode->update_promocode_used($promocode_id);
?>
<?php /* This is a test Tariff data for the database. */
/*$data_tariff = [
'tariff'           => '10',
'promocod_id'      => '105',
'promocod_group'   => '',
'amount_surcharge' => '15',
'discount_tariff'  => 'percent',
];
// GOIT_PRMCODE()->model->tariff->add($data_tariff);
?>
<?php /* Adding a new promocode to the database. */
/* $data_promocod = [
'promocod'            => 'NEWTESTIC',
"activete_count_user" => 1,
"product"             => '1',
"conditions"          => '33',
"date_start"          => wp_date('Y-m-d'),
"date_end"            => wp_date('Y-m-d'),
"promo_status"        => 0,
'promocode_limit'     => 1,
"msg_success"         => '',
"msg_not_found"       => '',
"msg_data_end"        => '',
"count"               => 2,
]; ?>
<?php /* Adding a new promocode to the database. */
// var_dump(GOIT_PRMCODE()->model->promocode->check('SINGLE_TEST', 1819773000576977969));
?>

<?php /* NEW Lead */
$additionalParameters = array(
	"first_name"      => 'Test Name',
	"email"           => "testGoITeens@mail.ua",
	"phone"           => "+380889998888",
	"productID"       => 1819773000506923518, // айді продукту з зохо
	"productName"     => "GoITeens_UA_AutoFE_wishboard_free", // назва продукту
	"fopID"           => 1002, // айді фопа з зохо
	"productPrice"    => 16200, // ціна продукту
	"productCurrency" => "UAH", // валюта продукту
	"promo"           => "TESTPROMOCODE",
	"promoID"         => "5", // промокод
	"promoDiscount"   => 16199 // знижка по промокоду (ціна мінус знижка формує ціну! не оце поле!)
);
// var_dump(GOIT_PRMCODE()->controller->lead->new($additionalParameters));

// $data_order = [
// 	'promocod_id'     => '',
// 	'invoice'         => '0',
// 	'date_order'      => wp_date('Y-m-d'),
// 	'product_price'   => '16200',
// 	'discount'        => '10',
// 	'discount_tariff' => 'percent',
// 	'order_status'    => 1
// ];
// GOIT_PRMCODE()->model->order->add($data_order);
?>

<div class="goiteens-ui">
	<div class="goiteens-ui__block">
		<form class="form">
			<input type="text" id="goit-promocode__input" data-product="1819773000576977969" value="SINGLE_TEST">
			<input type="submit" id="goit-promocode__activate">
			<div id="goit-promocode__msg"></div>
		</form>
	</div>
</div>