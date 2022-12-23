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
			'group' => $this->wpdb->prefix . 'goit_group',
			'order' => $this->wpdb->prefix . 'goit_order',
		];
		$this->tables = apply_filters('goit_promocodes_tables', $this->tables, $this);
	}

}

?>