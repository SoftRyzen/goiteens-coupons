<?php
namespace goit_prmcode\controller;


/**
 * AJAX Controller
 **/
class ajax
{

	/**
	 * Constructor
	 **/
	function __construct()
	{

		add_action('wp_ajax_check_promocode_name', [$this, 'check_promocode_name']);
		add_action('wp_ajax_nopriv_ajax_check_promocode_name', [$this, 'check_promocode_name']);

		add_action('wp_ajax_add_promocodes', [$this, 'add_promocodes']);
		add_action('wp_ajax_nopriv_ajax_add_promocodes', [$this, 'add_promocodes']);

		add_action('wp_ajax_update_promocode', [$this, 'update_promocode']);
		add_action('wp_ajax_nopriv_ajax_update_promocode', [$this, 'update_promocode']);

		add_action('wp_ajax_statistic_datepicker', [$this, 'statistic_datepicker']);
		add_action('wp_ajax_nopriv_ajax_statistic_datepicker', [$this, 'statistic_datepicker']);

		add_action('wp_ajax_update_config', [$this, 'update_config']);
		add_action('wp_ajax_nopriv_ajax_update_config', [$this, 'update_config']);

		add_action('wp_ajax_rebuild_db', [$this, 'rebuild_db']);
		add_action('wp_ajax_nopriv_ajax_rebuild_db', [$this, 'rebuild_db']);

		add_action('wp_ajax_sync_crm_products', [$this, 'sync_crm_products']);
		add_action('wp_ajax_nopriv_ajax_sync_crm_products', [$this, 'sync_crm_products']);

		add_action('wp_ajax_check_promocode', [$this, 'check_promocode']);
		add_action('wp_ajax_nopriv_ajax_check_promocode', [$this, 'check_promocode']);

		add_action('wp_ajax_new_lead', [$this, 'new_lead']);
		add_action('wp_ajax_nopriv_ajax_new_lead', [$this, 'new_lead']);

	}

	/**
	 * Load suppliers
	 */
	function check_promocode_name()
	{

		$count = 0;
		$promocode = $_POST['promocod'];
		$group = $_POST['count'] > 1 ? true : false;

		$count = GOIT_PRMCODE()->model->promocode->check_promocode_name($promocode, $group);

		/* Returning a response to the AJAX request. */
		echo json_encode($count);
		exit;
	}

	function add_promocodes()
	{

		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		$type_promocode = $_POST['count'] > 1 ? 'promocod_group' : 'promocod_id';

		/* Adding promocod to the database. */
		$data = [
			'promocod'            => $_POST['promocod'],
			'count'               => $_POST['count'],
			'activete_count_user' => $_POST['activete_count_user'],
			'product'             => $_POST['product'],
			'conditions'          => $_POST['conditions'],
			'date_start'          => $_POST['date_start'],
			'date_end'            => $_POST['date_end'],
			'manager'             => $_POST['manager'],
			'promo_status'        => $_POST['status'],
			'promocode_limit'     => $_POST['promocode_limit'],
			'msg_success'         => $_POST['msg_success'],
			'msg_not_found'       => $_POST['msg_not_found'],
			'msg_data_end'        => $_POST['msg_data_end'],
		];
		GOIT_PRMCODE()->model->promocode->add($data);

		/* Adding a new tariff to the database. */
		for ($i = 0; $i < count($_POST['tariff']); $i++) {
			$data_tariff = [
				$type_promocode    => $_POST['promocod'],
				'tariff'           => $_POST['tariff'][$i][0],
				'amount_surcharge' => $_POST['tariff'][$i][1],
				'discount_tariff'  => $_POST['tariff'][$i][2],
			];
			GOIT_PRMCODE()->model->tariff->add($data_tariff);
		}

		/* Returning a response to the AJAX request. */
		echo json_encode(true);
		exit;

	}

	function update_promocode()
	{

		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		$type_promocode = $_POST['count'] > 1 ? 'promocod_group' : 'promocod_id';

		/* Adding promocod to the database. */
		$data = [
			'promocod'            => $_POST['promocod'],
			'count'               => $_POST['count'],
			'activete_count_user' => $_POST['activete_count_user'],
			'product'             => $_POST['product'],
			'conditions'          => $_POST['conditions'],
			'date_start'          => $_POST['date_start'],
			'date_end'            => $_POST['date_end'],
			'promo_status'        => $_POST['status'],
			'promocode_limit'     => $_POST['promocode_limit'],
			'msg_success'         => $_POST['msg_success'],
			'msg_not_found'       => $_POST['msg_not_found'],
			'msg_data_end'        => $_POST['msg_data_end'],
		];
		GOIT_PRMCODE()->model->promocode->update($data);

		/* Adding a new tariff to the database. */
		for ($i = 0; $i < count($_POST['tariff']); $i++) {
			$data_tariff = [
				$type_promocode    => $_POST['promocod'],
				'tariff'           => $_POST['tariff'][$i][0],
				'amount_surcharge' => $_POST['tariff'][$i][1],
				'discount_tariff'  => $_POST['tariff'][$i][2],
			];
			GOIT_PRMCODE()->model->tariff->add($data_tariff);
		}

		/* Returning a response to the AJAX request. */
		echo json_encode(true);
		exit;

	}

	function statistic_datepicker()
	{

		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		ob_start();
		$orders = GOIT_PRMCODE()->model->order->get_promocode_orders('all', $_POST['date_start'], $_POST['date_end']);
		GOIT_PRMCODE()->view->load('admin_elements/orders-result', $orders);
		$orders_result = ob_get_clean();

		ob_start();
		$data = [
			"page"       => 'statistic',
			"promocodes" => GOIT_PRMCODE()->model->promocode->get_promocodes(),
			"date_start" => $_POST['date_start'],
			"date_end"   => $_POST['date_end'],
		];
		GOIT_PRMCODE()->view->load('admin_elements/body_table', $data);
		$body_result = ob_get_clean();

		$answer = [
			'orders_result' => $orders_result,
			'body_result'   => $body_result,
		];

		/* Returning a response to the AJAX request. */
		echo json_encode($answer);
		exit;

	}


	function update_config()
	{
		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		$data = array(
			'ZOHO_CLIENT_ID'     => $_POST['ZOHO_CLIENT_ID'],
			'ZOHO_CLIENT_SECRET' => $_POST['ZOHO_CLIENT_SECRET'],
			'ZOHO_REFRESH_TOKEN' => $_POST['ZOHO_REFRESH_TOKEN'],
			'W4P_TOKEN'          => $_POST['W4P_TOKEN'],
			'products_crm_URL'   => $_POST['products_crm_URL'],
		);
		GOIT_PRMCODE()->controller->config->config_update_all($data);

		/* Returning a response to the AJAX request. */
		echo json_encode(true);
		exit;

	}

	function rebuild_db()
	{
		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		GOIT_PRMCODE()->model->database->drop_table();
		GOIT_PRMCODE()->model->database->create_table();
		// And Update Product Database
		GOIT_PRMCODE()->controller->ZohoCRM->zoho_products_check();

		/* Returning a response to the AJAX request. */
		echo json_encode(true);
		exit;

	}

	function sync_crm_products()
	{
		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		GOIT_PRMCODE()->controller->ZohoCRM->zoho_products_check();

		/* Returning a response to the AJAX request. */
		echo json_encode(true);
		exit;

	}

	function check_promocode()
	{
		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		$answer = GOIT_PRMCODE()->model->promocode->check($_POST['promocode'], $_POST['product']);

		/* Returning a response to the AJAX request. */
		echo json_encode($answer);
		exit;

	}

	function new_lead()
	{
		check_ajax_referer('goiteens_ajax_nonce', 'nonce');

		$parameters = array(
			"productID"       => (int) $_POST['productID'], // айді продукту з зохо
			"first_name"      => (string) $_POST['first_name'],
			"phone"           => (string) $_POST['phone'],
			"email"           => (string) $_POST['email'],
			"productName"     => (string) $_POST['productName'], // назва продукту
			"fopID"           => (string) $_POST['fopID'], // айді фопа з зохо
			"productPrice"    => (string) $_POST['productPrice'],
			"productCurrency" => (string) isset($_POST['productCurrency']) ? $_POST['productCurrency'] : "UAH", // валюта продукту
			"promoKey"        => (string) $_POST['promoKey'], // промокод
			"promoDiscount"   => (int) $_POST['promoDiscount'], // знижка
			"utm_content"     => (string) isset($_POST['utm_content ']) ? $_POST['utm_content '] : '',
			"utm_medium"      => (string) isset($_POST['utm_medium ']) ? $_POST['utm_medium '] : '',
			"utm_source"      => (string) isset($_POST['utm_source ']) ? $_POST['utm_source '] : '',
			"utm_term"        => (string) isset($_POST['utm_term ']) ? $_POST['utm_term '] : '',
			"utm_campaign"    => (string) isset($_POST['utm_campaign ']) ? $_POST['utm_campaign '] : '',

			"returnURL"       => (string) isset($_POST['returnURL']) ? $_POST['returnURL'] : site_url( "wp-content/plugins/goit-promocodes/app/crm/return_url.php"),
		);

		$answer = GOIT_PRMCODE()->controller->lead->new($parameters);

		/* Returning a response to the AJAX request. */
		echo json_encode($answer);
		exit;

	}
}