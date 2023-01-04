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
			activete_count INTEGER DEFAULT 0,
			activete_count_user INTEGER NOT NULL,
			product VARCHAR(55) NOT NULL,
			conditions VARCHAR(50) NOT NULL,
			tariff VARCHAR(50) NOT NULL,
			date_start DATE,
			date_end DATE,
			manager VARCHAR(50) NOT NULL,			
			promo_status smallint(1) NOT NULL,
			promocode_limit mediumint(4) NOT NULL,
			promocode_used mediumint(9) DEFAULT 0,
			amount_payments mediumint(6) DEFAULT 0,
			amount_surcharge mediumint(9),
			discount_tariff VARCHAR(10) NOT NULL,
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

		echo ABSPATH . 'wp-admin/includes/upgrade.php';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$this->wpdb->query($promocodes);
		$this->wpdb->query($order);
		$this->wpdb->query($tariff);
	}

	/**
	 * If the table exists, drop it.
	 */
	public function drop_table(): void
	{
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['promocodes']}");
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['order']}");
		$this->wpdb->query("DROP TABLE IF EXISTS {$this->tables['tariff']}");
	}

	/**
	 * It takes a bunch of parameters, and if the count parameter is 1, it inserts a row into the database
	 * with the parameters as the values. If the count parameter is greater than 1, it inserts a bunch of
	 * rows into the database with the parameters as the values, except for the promocod parameter, which
	 * is appended with a random string.
	 * 
	 * @param promocod The name of the promocode.
	 * @param activete_count The number of times the coupon can be used.
	 * @param activete_count_user The number of times the coupon can be used by a user.
	 * @param product the product ID
	 * @param tariff the name of the tariff
	 * @param conditions 
	 * @param date_start The date the coupon starts being valid.
	 * @param date_end The date the coupon expires.
	 * @param manager the user ID of the manager who created the promo code
	 * @param promo_status 0 - inactive, 1 - paused, 2 - active
	 * @param promocode_limit The number of times the coupon can be used.
	 * @param promocode_used The number of times the coupon has been used.
	 * @param amount_payments The amount of payments that the user must make to activate the promo code.
	 * @param amount_surcharge The amount of the surcharge.
	 * @param discount_tariff Percent or currency code (UAH / USD / EUR / PLN)
	 * @param count the number of promocodes to generate
	 */

	//  Update to -> add_promocodes($data)
	public function add_promocodes(
		$promocod, $activete_count_user, $product, $tariff, $conditions,
		$date_start, $date_end, $manager, $promo_status, $promocode_limit,
		$amount_surcharge, $discount_tariff, $count = 1,
		$msg_success = 'Промокод активовано!',
		$msg_not_found = 'На жаль такого промокода не існує.',
		$msg_data_end = 'На жаль термін дії промокоду закінчився.')
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		if ($count == 1) {
			$request = array(
				'promocod'            => $promocod,
				'activete_count'      => 0,
				'activete_count_user' => $activete_count_user,
				'product'             => $product,
				'tariff'              => $tariff,
				'conditions'          => $conditions,
				'date_start'          => $date_start,
				'date_end'            => $date_end,
				'manager'             => $manager,
				'promo_status'        => $promo_status,
				'promocode_limit'     => $promocode_limit,
				'promocode_used'      => 0,
				'amount_payments'     => 0,
				'amount_surcharge'    => $amount_surcharge,
				'discount_tariff'     => $discount_tariff,
				'msg_success'         => $msg_success,
				'msg_not_found'       => $msg_not_found,
				'msg_data_end'        => $msg_data_end
			);
			return $this->wpdb->insert("{$this->tables['promocodes']}", $request);
		} else {
			for ($i = 0; $i < $count; $i++) {
				$request = array(
					'promocod'            => $promocod . '_' . substr(str_shuffle($permitted_chars), 0, 4),
					'promocod_group'      => $promocod,
					'activete_count'      => 0,
					'activete_count_user' => $activete_count_user,
					'product'             => $product,
					'tariff'              => $tariff,
					'conditions'          => $conditions,
					'date_start'          => $date_start,
					'date_end'            => $date_end,
					'manager'             => $manager,
					'promo_status'        => $promo_status,
					'promocode_limit'     => $promocode_limit,
					'promocode_used'      => 0,
					'amount_payments'     => 0,
					'amount_surcharge'    => $amount_surcharge,
					'discount_tariff'     => $discount_tariff,
					'msg_success'         => $msg_success,
					'msg_not_found'       => $msg_not_found,
					'msg_data_end'        => $msg_data_end
				);
				$this->wpdb->insert("{$this->tables['promocodes']}", $request);
			}
			return true;
		}
	}

	public function update_promocodes($data): void
	{
		$count = $data['count'];
		$data = array(
			"promocod"            => $data['promocod'],
			"activete_count_user" => $data['activete_count_user'],
			"product"             => $data['product'],
			"tariff"              => $data['tariff'],
			"conditions"          => $data['conditions'],
			"date_start"          => $data['date_start'],
			"date_end"            => $data['date_end'],
			"promo_status"        => $data['promo_status'],
			'promocode_limit'     => $data['promocode_limit'],
			"amount_surcharge"    => $data['amount_surcharge'],
			"discount_tariff"     => $data['discount_tariff'],
			"msg_success"         => $data['msg_success'],
			"msg_not_found"       => $data['msg_not_found'],
			"msg_data_end"        => $data['msg_data_end']
		);
		if ($count == 1)
			$this->wpdb->update($this->tables['promocodes'], $data, array("promocod" => $data['promocod']));
		else
			$this->wpdb->update($this->tables['promocodes'], $data, array("promocod_group" => $data['promocod']));

	}

	/**
	 * It returns a list of promocodes from the database, optionally filtered by a search term and/or a
	 * status.
	 * 
	 * @param sort This is the search term. If you want to search for a specific promo code, you can enter
	 * it here.
	 * 	 * 
	 * @return Array The query is returning all the promocodes from the database.
	 */
	public function get_promocodes($sort = false)
	{
		$query = "SELECT * FROM {$this->tables['promocodes']} ";
		if ($sort)
			$query .= "WHERE promocod like '%{$sort}%' or id like '%{$sort}%'";
		$query .= " ORDER BY id DESC";
		return $this->wpdb->get_results("$query");
	}

	/**
	 * It checks if a promocode name exists in the database.
	 * 
	 * @param name The name of the promocode
	 * @param group true/false
	 * 
	 * @return An array of objects.
	 */
	public function check_promocode_name($name = '', $group = false)
	{
		$query = "SELECT COUNT(*) FROM {$this->tables['promocodes']} ";
		if ($group)
			$query .= "WHERE promocod_group = '{$name}'";
		else
			$query .= "WHERE promocod = '{$name}'";
		return $this->wpdb->get_var("$query");
	}


	/**
	 * It returns the number of rows in the database table
	 * 
	 * @param sort The search term
	 * 
	 * @return The number of rows in the table.
	 */
	public function get_promocodes_count($sort = false)
	{
		$query = "SELECT COUNT(*) FROM {$this->tables['promocodes']} ";
		if ($sort)
			$query .= "WHERE promocod like '%{$sort}%' or id like '%{$sort}%'";
		$query .= " ORDER BY id DESC";
		return $this->wpdb->get_var($this->wpdb->prepare("$query"));
	}

	/**
	 * Change the status of a promocode
	 */
	public function change_promocode_status($id, $status, $type)
	{
		$query = "UPDATE {$this->tables['promocodes']} SET promo_status = {$status} WHERE ";
		if ($type == 'item') {
			$query .= "id = {$id}";
			return $this->wpdb->get_results($this->wpdb->prepare($query));
		} else {
			foreach ($this->get_promocodes($id) as $promocode):
				$query .= "id = {$promocode->id}";
				$this->wpdb->get_results($this->wpdb->prepare($query));
			endforeach;
			return;
		}
	}



}