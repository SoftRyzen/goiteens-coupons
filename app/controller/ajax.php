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

		add_action('wp_ajax_ajax_check_promocode_name', [$this, 'check_promocode_name']);
		add_action('wp_ajax_nopriv_ajax_check_promocode_name', [$this, 'check_promocode_name']);

		add_action('wp_ajax_ajax_add_promocodes', [$this, 'add_promocodes']);
		add_action('wp_ajax_nopriv_ajax_add_promocodes', [$this, 'add_promocodes']);

		add_action('wp_ajax_ajax_update_promocode', [$this, 'update_promocode']);
		add_action('wp_ajax_nopriv_ajax_update_promocode', [$this, 'update_promocode']);

	}

	/**
	 * Load suppliers
	 */
	function check_promocode_name()
	{

		$count = 0;
		$promocode = $_POST['promocod'];
		$group = $_POST['count'] > 1 ? true : false;

		$count = GOIT_PRMCODE()->model->database->check_promocode_name($promocode, $group);

		echo json_encode($count);

		exit;
	}

	function add_promocodes()
	{

		if (isset($_POST['promocod']))
			$promocod = $_POST['promocod'];

		if (isset($_POST['activete_count_user']))
			$activete_count_user = $_POST['activete_count_user'];

		if (isset($_POST['product']))
			$product = $_POST['product'];

		if (isset($_POST['tariff']))
			$tariff = $_POST['tariff'];

		if (isset($_POST['conditions']))
			$conditions = $_POST['conditions'];

		if (isset($_POST['date_start']))
			$date_start = $_POST['date_start'];

		if (isset($_POST['date_end']))
			$date_end = $_POST['date_end'];

		if (isset($_POST['manager']))
			$manager = $_POST['manager'];

		if (isset($_POST['status']))
			$status = $_POST['status'];

		if (isset($_POST['promocode_limit']))
			$promocode_limit = $_POST['promocode_limit'];

		if (isset($_POST['amount_surcharge']))
			$amount_surcharge = $_POST['amount_surcharge'];

		if (isset($_POST['discount_tariff']))
			$discount_tariff = $_POST['discount_tariff'];

		if (isset($_POST['count']))
			$count = $_POST['count'];

		if (isset($_POST['msg_success']))
			$msg_success = $_POST['msg_success'];

		if (isset($_POST['msg_not_found']))
			$msg_not_found = $_POST['msg_not_found'];

		if (isset($_POST['msg_data_end']))
			$msg_data_end = $_POST['msg_data_end'];

		if (
			isset($_POST['promocod']) &&
			isset($_POST['activete_count_user']) &&
			isset($_POST['product']) &&
			isset($_POST['tariff']) &&
			isset($_POST['conditions']) &&
			isset($_POST['date_start']) &&
			isset($_POST['date_end']) &&
			isset($_POST['manager']) &&
			isset($_POST['status']) &&
			isset($_POST['promocode_limit']) &&
			isset($_POST['amount_surcharge']) &&
			isset($_POST['count']) &&
			isset($_POST['msg_success']) &&
			isset($_POST['msg_not_found']) &&
			isset($_POST['msg_data_end'])
		) {
			GOIT_PRMCODE()->model->database->add_promocodes($promocod, $activete_count_user, $product,
				$tariff, $conditions, $date_start, $date_end, $manager, $status, $promocode_limit, $amount_surcharge, $discount_tariff, $count,
				$msg_success, $msg_not_found, $msg_data_end);
			return true;
		} else {
			return false;
		}

	}

	function update_promocode()
	{
		$count = 'succes';
		$data = [
			'promocod'            => $_POST['promocod'],
			'activete_count_user' => $_POST['activete_count_user'],
			'product'             => $_POST['product'],
			'tariff'              => $_POST['tariff'],
			'conditions'          => $_POST['conditions'],
			'date_start'          => $_POST['date_start'],
			'date_end'            => $_POST['date_end'],
			'promo_status'        => $_POST['promo_status'],
			'promocode_limit'     => $_POST['promocode_limit'],
			'amount_surcharge'    => $_POST['amount_surcharge'],
			'discount_tariff'     => $_POST['discount_tariff'],
			'count'               => $_POST['count'],
			'msg_success'         => $_POST['msg_success'],
			'msg_not_found'       => $_POST['msg_not_found'],
			'msg_data_end'        => $_POST['msg_data_end'],
		];

		GOIT_PRMCODE()->model->database->update_promocodes($data);
		
		echo json_encode($count);

		exit;

	}

}