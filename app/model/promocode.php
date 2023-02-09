<?php
namespace goit_prmcode\model;

use ftp;

class promocode extends database
{
	/**
	 * It takes a bunch of parameters, and if the count parameter is 1, it inserts a row into the database
	 * with the parameters as the values. If the count parameter is greater than 1, it inserts a bunch of
	 * rows into the database with the parameters as the values, except for the promocod parameter, which
	 * is appended with a random string.
	 * 
	 * @param array $data an array of data to be inserted into the database.
	 */

	public function add(array $data): void
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$count = $data['count'];
		$request = array(
			'promocod'            => null,
			'promocod_group'      => null,
			"activete_count_user" => $data['activete_count_user'],
			"product"             => $data['product'],
			"conditions"          => $data['conditions'],
			"date_start"          => $data['date_start'],
			"date_end"            => $data['date_end'],
			"promo_status"        => $data['promo_status'],
			'promocode_limit'     => $data['promocode_limit'],
			'manager'             => $data['manager'],
			"msg_success"         => $data['msg_success'],
			"msg_not_found"       => $data['msg_not_found'],
			"msg_data_end"        => $data['msg_data_end']
		);

		if ($count == 1) {
			$request['promocod'] = $data['promocod'];
			$this->wpdb->insert("{$this->tables['promocodes']}", $request);
		} else {
			for ($i = 0; $i < $count; $i++) {
				$promocode = $data['promocod'] . '_' . substr(str_shuffle($permitted_chars), 0, 4);
				// Check for a unique promo code
				if ($this->check_promocode_name($promocode)) {
					--$i;
					continue;
				}
				$request['promocod'] = $promocode;
				$request['promocod_group'] = $data['promocod'];
				$this->wpdb->insert("{$this->tables['promocodes']}", $request);

			}
		}
	}

	/**
	 * It updates a row in the database.
	 * 
	 * @param array $data The data to be inserted into the database.
	 */
	public function update(array $data): void
	{
		$count = $data['count'];
		$data = array(
			"promocod"            => $data['promocod'],
			"activete_count_user" => $data['activete_count_user'],
			"product"             => $data['product'],
			"conditions"          => $data['conditions'],
			"date_start"          => $data['date_start'],
			"date_end"            => $data['date_end'],
			"promo_status"        => $data['promo_status'],
			'promocode_limit'     => $data['promocode_limit'],
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
	 * Get all promocodes from the database, optionally filtered by a search term or group, and return them
	 * as an array.
	 * 
	 * @param string $sort
	 * @param bool $group if true, then the promocodes will be grouped by promocod_group
	 * 
	 * @return array An array of objects.
	 */
	public function get_promocodes($sort = false, bool $group = false): array
	{
		$query = "SELECT * FROM {$this->tables['promocodes']} ";
		if ($sort && !$group)
			$query .= "WHERE promocod like '%{$sort}%' or id like '%{$sort}%'";
		if ($sort && $group)
			$query .= "WHERE promocod_group = '{$sort}'";
		$query .= " ORDER BY id DESC";
		return $this->wpdb->get_results("$query");
	}

	/**
	 * Get one promocode from the database, optionally filtered by a search term or group, and return them
	 * as an array.
	 * 
	 * @param string $sort
	 * @param bool $group if true, then the promocodes will be grouped by promocod_group
	 * 
	 * @return array An array of objects.
	 */
	public function get_promocode($sort = false, bool $group = false): array
	{
		$query = "SELECT * FROM {$this->tables['promocodes']} ";
		if ($sort && !$group)
			$query .= "WHERE promocod = '{$sort}'";
		if ($sort && $group)
			$query .= "WHERE promocod_group = '{$sort}'";
		$query .= " ORDER BY id DESC";
		return $this->wpdb->get_results("$query");
	}


	public function getpromocodeid(string $promocode): int
	{
		return $this->wpdb->get_var($this->wpdb->prepare("SELECT id FROM {$this->tables['promocodes']} WHERE promocod = %s ORDER BY id DESC", $promocode));
	}

	public function check(string $promocode, string $productID): array
	{
		$response = [
			"status" => false,
			"msg"    => __('Промокод не знайдено', 'goit_promocode')
		];

		// Отримуємо промокод
		$query = "SELECT product, date_start, date_end, promo_status, msg_success, msg_not_found, msg_data_end
				  FROM {$this->tables['promocodes']} WHERE promocod = '{$promocode}'";
		$promocode = $this->wpdb->get_results("$query");

		// Неіснуючий промокод
		if (empty($promocode) || !isset($promocode[0]))
			return $response;

		// Отримуємо ID продукту
		$product = GOIT_PRMCODE()->model->product->get_product($productID, true);
		$product = $product[0]->id;

		// Promocode Values
		$date_start = $promocode[0]->date_start;
		$date_end = $promocode[0]->date_end;
		$promo_status = $promocode[0]->promo_status;
		$msg_success = $promocode[0]->msg_success;
		$msg_not_found = $promocode[0]->msg_not_found;
		$msg_data_end = $promocode[0]->msg_data_end;

		// Промокод не відноситься до продукту
		if (empty($product) || !isset($product[0])) {
			$response["msg"] = "Промокод не відноситься до продукту";
			return $response;
		}
		// Не почався або закінчився
		if ($date_end !== "0000-00-00" && wp_date('Y-m-d') > $date_end) {
			$response["msg"] = $msg_data_end;
			return $response;
		}
		if ($date_start > wp_date('Y-m-d')) {
			$response["msg"] = $msg_not_found;
			return $response;
		}
		// Неактивний статус
		if ($promo_status !== '2') {
			$response["msg"] = $msg_not_found;
			return $response;
		}

		$productArray = explode(',', $promocode[0]->product);
		foreach ($productArray as $value) {
			if ($value == $product) {
				$response["status"] = true;
				$response["msg"] = $msg_success;
				return $response;
			}
		}

	}

	/**
	 * It checks if a promocode name exists in the database.
	 * 
	 * @param string $name The name of the promocode
	 * @param bool $group true/false
	 * 
	 * @return int An array of objects.
	 */
	public function check_promocode_name(string $name = '', bool $group = false): int
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
	 * @param $sort The search term
	 * 
	 * @return string The number of rows in the table.
	 */
	public function get_promocodes_count($sort = false): string
	{
		$query = "SELECT COUNT(*) FROM {$this->tables['promocodes']} ";

		if ($sort)
			$query .= "WHERE promocod LIKE '%{$sort}%' OR id LIKE '%{$sort}%'";

		$query .= " ORDER BY id DESC";
		
		return $this->wpdb->get_var($this->wpdb->prepare("$query", null));
	}

	/**
	 * It changes the status of a promocode
	 * 
	 * @param $id The ID of the promocode you want to change the status of.
	 * @param $status 0 = inactive, 1 = active
	 * @param $type item or group
	 */
	public function change_promocode_status($id, $status, $type)
	{
		if ($type == 'item')
			$this->wpdb->update($this->tables['promocodes'], array('promo_status' => $status), array("id" => $id));
		else
			foreach ($this->get_promocodes($id) as $promocode)
				$this->wpdb->update($this->tables['promocodes'], array('promo_status' => $status), array("id" => $promocode->id));
	}

	/**
	 * It updates the promocode_used column in the promocodes table
	 * 
	 * @param int $promocode_id The ID of the promocode you want to update.
	 */
	public function update_promocode_used(int $promocode_id): void
	{
		$query = $this->wpdb->prepare("SELECT promocode_used, promocode_limit FROM {$this->tables['promocodes']} WHERE id = %d", $promocode_id);
        $promocode = $this->wpdb->get_results($query);
		foreach ($promocode as $promo) {
			if ($promo->promocode_used > $promo->promocode_limit)
				$this->wpdb->update($this->tables['promocodes'], array('promocode_used' => $promo->promocode_used + 1), array("id" => $promocode_id));
			if ($promo->promocode_used == $promo->promocode_limit)
				$this->change_promocode_status($promocode_id, 0, 'item');
		}
	}

	/**
	 * It returns the promocode name from the promocodes table
	 * 
	 * @param string $id The ID of the promocode you want to get the name of.
	 * 
	 * @return string The promocode name.
	 */
	public function get_promocode_name(string $id): string
	{
		return $this->wpdb->get_var($this->wpdb->prepare("SELECT promocod FROM {$this->tables['promocodes']} WHERE id = %s", $id));
	}

}