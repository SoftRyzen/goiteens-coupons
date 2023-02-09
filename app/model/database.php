<?php
namespace goit_prmcode\model;

class database extends model
{

	/**
	 * It creates a table in the database
	 */
	public function create_table(): void
	{
		$charset = $this->wpdb->get_charset_collate();
		$promocodes = "CREATE TABLE {$this->tables['promocodes']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			promocod VARCHAR(40) NOT NULL,
			promocod_group VARCHAR(35),
			activete_count_user INTEGER NOT NULL,
			product VARCHAR(55) NOT NULL,
			conditions VARCHAR(50) NOT NULL,
			date_start DATE,
			date_end DATE,
			manager VARCHAR(50) NOT NULL,			
			promo_status smallint(1) NOT NULL,
			promocode_limit mediumint(4) NOT NULL,
			promocode_used mediumint(9) DEFAULT 0,
			msg_success VARCHAR(50),
			msg_not_found VARCHAR(50),
			msg_data_end VARCHAR(50),
			UNIQUE KEY id (id)
		) $charset;";
		$order = "CREATE TABLE {$this->tables['order']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			promocod_id mediumint(9) NOT NULL,
			invoice VARCHAR(19),
			date_order DATE NOT NULL,
			product_price mediumint(8),
			discount mediumint(6),
			discount_tariff VARCHAR(10) NOT NULL,
			order_status smallint(1) NOT NULL,
			UNIQUE KEY id (id)
		) $charset;";
		$tariff = "CREATE TABLE {$this->tables['tariff']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			tariff VARCHAR(50) NOT NULL,
			promocod_id mediumint(9),
			promocod_group VARCHAR(35),
			amount_surcharge mediumint(9),
			discount_tariff VARCHAR(10) NOT NULL,
			UNIQUE KEY id (id)
		) $charset;";
		$products = "CREATE TABLE {$this->tables['products']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			product_id VARCHAR(55),
			product_name VARCHAR(55),
			unit_price VARCHAR(9),
			UNIQUE KEY id (id)
		) $charset;";

		/* A WordPress function that is used to create a table in the database. */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		/* Creating the tables in the database. */
		$this->wpdb->query($promocodes);
		$this->wpdb->query($order);
		$this->wpdb->query($tariff);
		$this->wpdb->query($products);
	}

	/**
	 * If the table exists, drop it.
	 */
	public function drop_table(): void
	{
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['promocodes']}");
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['order']}");
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['tariff']}");
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['products']}");
	}

}