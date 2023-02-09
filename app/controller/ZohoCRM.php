<?php
namespace goit_prmcode\controller;

/**
 * Zoho CRM Controller
 **/
class ZohoCRM
{
	private $zoho;
	private $config;

	function __construct()
	{
		$this->zoho = new ZohoHelper();
		$this->config = new config();
		/* wp_schedule_event zoho_products_check */
		add_action('zoho_products_check', [$this, 'zoho_products_check']);
	}


	/**
	 * It gets all the products from Zoho, saves the date of the last update to the config file, and then
	 * adds the products to the database
	 */
	public function zoho_products_check()
	{
		$plans_query = "select id, Unit_Price, Product_Name from Products where for_promocode_teens=true";
		$all_plans = $this->zoho->searchByCriteria('Products', $plans_query);
		$this->config->config_update('products_update', wp_date("d.m.Y H:i:s"));

		GOIT_PRMCODE()->model->product->add($all_plans['data']);
	}

	public function reqeust($data)
	{
		// $conn = new connector(json_decode(file_get_contents('Request.json'), true), $this->zoho);
	}

}