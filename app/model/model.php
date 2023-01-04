<?php
namespace goit_prmcode\model;

class model
{

	protected $wpdb;
	protected $tables = [];
	protected $table_name;

	public function __construct()
	{
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->tables = [
			'promocodes' => $this->wpdb->prefix . 'goit_promocodes',
			'order' => $this->wpdb->prefix . 'goit_order',
			'tariff' => $this->wpdb->prefix . 'goit_tariff',
			'products' => $this->wpdb->prefix . 'goit_products',
		];
		$this->tables = apply_filters('goit_promocodes_tables', $this->tables, $this);
	}

}

?>