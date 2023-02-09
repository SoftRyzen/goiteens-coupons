<?php
namespace goit_prmcode\model;

class order extends database
{

	/**
	 * It takes an array of data, checks if the array contains the required data, and if it does, it
	 * inserts the data into the database
	 * 
	 * @param array $data 
	 */
	public function add($data, bool $returnId = false): int
	{
		$request = array(
			'promocod_id'     => $data['promocod_id'],
			'invoice'         => $data['invoice'],
			'date_order'      => $data['date_order'],
			'product_price'   => $data['product_price'],
			'discount'        => $data['discount'],
			'discount_tariff' => $data['discount_tariff'],
			'order_status'    => $data['order_status'] ?? 0, // in progress
		);
		$this->wpdb->insert("{$this->tables['order']}", $request);
		return $this->wpdb->insert_id;
	}

	/**
	 * Updates the order status of the order with the given id to the given status.
	 * 
	 * @param int  $id - The ID of the order you want to update.
	 * @param bool $status 1 = active (default), 0 = in progress
	 */
	public function update_order_status($id, $status = 1): void
	{
		$this->wpdb->update($this->tables['order'], array("order_status" => $status), array("id" => $id));
	}

	/**
	 * It returns the results of a query that selects all the rows from the table named in the
	 * tables['order'] variable, where the promocod_id column is equal to the 
	 * parameter, and where the date_order column is between the  and  parameters
	 * 
	 * @param int 	 $promocode_id  - The ID of the promocode you want to get the orders for.
	 * @param string $data_start 	- The start date of the orders you want to retrieve.
	 * @param string $data_end 		- The end date of the range you want to search for orders.
	 */
	public function get_promocode_orders($promocode_id = 'all', string $data_start = '', string $data_end = '')
	{
		$query = "SELECT * FROM {$this->tables['order']} WHERE ";
		if (is_array($promocode_id) && $promocode_id != 'all') {
			foreach ($promocode_id as $key => $id) {
				if ($key != 0)
					$query .= " OR ";
				$query .= "promocod_id = '{$id}'";
			}
		} else if ($promocode_id != 'all')
			$query .= "promocod_id = '{$promocode_id}'";
		if ($promocode_id != 'all' && !empty($data_start))
			$query .= " AND ";
		if (!empty($data_start) && !empty($data_end))
			$query .= "date_order BETWEEN '$data_start' AND '$data_end'";
		$query .= " ORDER BY id DESC";
		return $this->wpdb->get_results($query);
	}

	/**
	 * > Get the last order id from the database
	 * 
	 * @return int The last id of the order table.
	 */
	public function get_last_id(): int
	{
		$results = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->tables['order']}");
		return $results > 0 ? $this->wpdb->get_var("SELECT MAX(id) FROM {$this->tables['order']}") : 0;
	}

	/**
	 * It returns the promocode_id from the order table.
	 * 
	 * @param int id The ID of the order.
	 * 
	 * @return int The promocode_id from the order table.
	 */
	public function get_promocode_id(int $id): int
	{
		return $this->wpdb->get_var($this->wpdb->prepare("SELECT promocode_id FROM {$this->tables['order']} WHERE id = %d", $id));
	}

}