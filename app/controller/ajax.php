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

}