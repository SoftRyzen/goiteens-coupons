<?php
namespace goit_prmcode\model;

class order extends model
{

	// Add Order
	public function add_order($data)
	{
		if (!empty($data['promocod_id']) && !empty($data['invoice']) && !empty($data['date_order']) &&
			!empty($data['product_price']) && !empty($data['discount']) && !empty($data['discount_tariff'])) {
			$request = array(
				'promocod_id'     => $data['promocod_id'],
				'invoice'         => $data['invoice'],
				'date_order'      => $data['date_order'],
				'product_price'   => $data['product_price'],
				'discount'        => $data['discount'],
				'discount_tariff' => $data['discount_tariff'],
				'order_status'    => 0 // in progress
			);
			return $this->wpdb->insert("{$this->tables['order']}", $request);
		}
		return false;
	}

	/**
	 * Update Order status
	 * 
	 * @param id Order ID
	 * @param status 1 = active, 0 = in progress
	 **/
	public function update_order_status($id, $status = 1): void
	{
		$this->wpdb->update($this->tables['order'], array("order_status" => $status), array("id" => $id));
	}

	public function get_promocode_orders($promocode_id)
	{
		return $this->wpdb->get_results("SELECT * FROM {$this->tables['order']} WHERE promocod_id = '{$promocode_id}' ORDER BY id DESC");
	}

}