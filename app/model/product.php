<?php
namespace goit_prmcode\model;

class product extends database
{

	/**
	 * If the data is an array, then loop through the array and call the function again. 
	 * If the data is not an array, then insert the data into the database.
	 * 
	 * @param array $products - The data to be inserted.
	 */
	public function add(array $products): void
	{
		foreach ($products as $product) {
			$data = array(
				'product_id'   => $product['id'],
				'product_name' => $product['Product_Name'],
				'unit_price'   => $product['Unit_Price']
			);

			if ($this->get_product($product['id'])) {
				$this->wpdb->update($this->tables['products'], $data, array("product_id" => $product['id']));
			} else {
				$this->wpdb->insert("{$this->tables['products']}", $data);
			}
		}
	}


	/**
	 * Get all products from the products table, ordered by product name.
	 * 
	 * @return An array of objects.
	 */
	public function get_all_products()
	{
		return $this->wpdb->get_results("SELECT * FROM {$this->tables['products']} ORDER BY product_name ASC");
	}

	/**
	 * It returns the results of a query to the database.
	 * 
	 * @param string product_id The ID of the product you want to get.
	 * @param bool selectID If true, it will only return the id of the product. If false, it will return
	 * all the data of the product.
	 * 
	 * @return array An array of results from the database.
	 */
	public function get_product(string $product_id, bool $selectID = false): array
	{
		if ($selectID)
			return $this->wpdb->get_results("SELECT id FROM {$this->tables['products']} where product_id = {$product_id}");
		else
			return $this->wpdb->get_results("SELECT * FROM {$this->tables['products']} where product_id = {$product_id}");
	}
}