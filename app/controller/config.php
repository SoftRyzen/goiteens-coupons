<?php

namespace goit_prmcode\controller;

/**
 * Config Controller
 **/
class config
{

	private $ZOHO_CLIENT_ID;
	private $ZOHO_CLIENT_SECRET;
	private $ZOHO_REFRESH_TOKEN;
	private $W4P_TOKEN;
	private $products_update;
	private $products_crm_URL;

	public function __construct()
	{
		$this->run();
	}

	private function run()
	{
		$this->ZOHO_CLIENT_ID = GOIT_PRMCODE()->config['ZOHO_CLIENT_ID'];
		$this->ZOHO_CLIENT_SECRET = GOIT_PRMCODE()->config['ZOHO_CLIENT_SECRET'];
		$this->ZOHO_REFRESH_TOKEN = GOIT_PRMCODE()->config['ZOHO_REFRESH_TOKEN'];
		$this->W4P_TOKEN = GOIT_PRMCODE()->config['W4P_TOKEN'];
		$this->products_update = GOIT_PRMCODE()->config['products_update'];
		$this->products_crm_URL = GOIT_PRMCODE()->config['products_crm_URL'];
	}

	public function current_config()
	{
		return [
			'ZOHO_CLIENT_ID'     => GOIT_PRMCODE()->config['ZOHO_CLIENT_ID'],
			'ZOHO_CLIENT_SECRET' => GOIT_PRMCODE()->config['ZOHO_CLIENT_SECRET'],
			'ZOHO_REFRESH_TOKEN' => GOIT_PRMCODE()->config['ZOHO_REFRESH_TOKEN'],
			'W4P_TOKEN'          => GOIT_PRMCODE()->config['W4P_TOKEN'],
			'products_update'    => GOIT_PRMCODE()->config['products_update'],
			'products_crm_URL'   => GOIT_PRMCODE()->config['products_crm_URL']
		];
	}

	public function config_update($arg, $value)
	{
		$config = $this->current_config();

		switch ($arg) {
			case 'ZOHO_CLIENT_ID':
				$config['ZOHO_CLIENT_ID'] = $value;
				break;
			case 'ZOHO_CLIENT_SECRET':
				$config['ZOHO_CLIENT_SECRET'] = $value;
				break;
			case 'ZOHO_REFRESH_TOKEN':
				$config['ZOHO_REFRESH_TOKEN'] = $value;
				break;
			case 'W4P_TOKEN':
				$config['W4P_TOKEN'] = $value;
				break;
			case 'products_update':
				$config['products_update'] = $value;
				break;
			case 'products_crm_URL':
				$config['products_crm_URL'] = $value;
				break;
			default:
				break;
		}

		file_put_contents(GOIT_PRMCODE_PATH . '/app/config.php', '<?php ' . PHP_EOL . 'return ' . var_export($config, true) . ';');

		$this->run();
	}

	public function config_update_all(array $data)
	{
		$config = $this->current_config();

		$config['ZOHO_CLIENT_ID'] = $data['ZOHO_CLIENT_ID'] ? $data['ZOHO_CLIENT_ID'] : $this->ZOHO_CLIENT_ID;
		$config['ZOHO_REFRESH_TOKEN'] = $data['ZOHO_REFRESH_TOKEN'] ? $data['ZOHO_REFRESH_TOKEN'] : $this->ZOHO_REFRESH_TOKEN;
		$config['ZOHO_CLIENT_SECRET'] = $data['ZOHO_CLIENT_SECRET'] ? $data['ZOHO_CLIENT_SECRET'] : $this->ZOHO_CLIENT_SECRET;
		$config['W4P_TOKEN'] = $data['W4P_TOKEN'] ? $data['W4P_TOKEN'] : $this->W4P_TOKEN;
		$config['products_crm_URL'] = $data['products_crm_URL'] ? $data['products_crm_URL'] : "";

		file_put_contents(GOIT_PRMCODE_PATH . '/app/config.php', '<?php ' . PHP_EOL . 'return ' . var_export($config, true) . ';');

		$this->run();

		return $this->current_config();

	}

}