<?php
namespace goit_prmcode\model;


class database extends model
{

	/**
	 * It creates a table in the database
	 */
	public function create_table()
	{
		$promocodes = "CREATE TABLE {$this->tables['promocodes']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			promocod VARCHAR(40) NOT NULL,
			promocod_group VARCHAR(35),
			activete_count INTEGER NOT NULL,
			activete_count_user INTEGER NOT NULL,
			product VARCHAR(55) NOT NULL,
			conditions VARCHAR(50) NOT NULL,
			tariff VARCHAR(50) NOT NULL,
			date_start DATE,
			date_end DATE,
			manager VARCHAR(50) NOT NULL,			
			status smallint(1) NOT NULL,
			promocode_limit mediumint(4),
			promocode_used mediumint(9),
			amount_payments mediumint(6),
			amount_surcharge mediumint(9),
			discount_tariff VARCHAR(10) NOT NULL,
			-- Повідомлення при активації
			msg_success VARCHAR(50) NOT NULL DEFAULT 'Промокод активовано!',
			msg_not_found VARCHAR(50) NOT NULL DEFAULT 'На жаль такого промокода не існує.',
			msg_data_end VARCHAR(50) NOT NULL DEFAULT 'На жаль термін дії промокоду закінчився.',

			UNIQUE KEY id (id)
		);";
		$order = "CREATE TABLE {$this->tables['order']} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			promocod VARCHAR(40) NOT NULL,
			date_order DATE,
			UNIQUE KEY id (id)
		);";

		echo ABSPATH . 'wp-admin/includes/upgrade.php';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$this->wpdb->query($promocodes);
		$this->wpdb->query($order);
	}

	/**
	 * If the table exists, drop it.
	 */
	public function drop_table()
	{
		$sql = "DROP TABLE IF EXISTS {$this->tables['promocodes']}";
		$this->wpdb->query($sql);
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
	 * @param status 0 - inactive, 1 - paused, 2 - active
	 * @param promocode_limit The number of times the coupon can be used.
	 * @param promocode_used The number of times the coupon has been used.
	 * @param amount_payments The amount of payments that the user must make to activate the promo code.
	 * @param amount_surcharge The amount of the surcharge.
	 * @param discount_tariff Percent or currency code (UAH / USD / EUR / PLN)
	 * @param count the number of promocodes to generate
	 */

	public function add_promocodes(
		$promocod, $activete_count, $activete_count_user, $product, $tariff, $conditions,
		$date_start, $date_end, $manager, $status, $promocode_limit, $promocode_used,
		$amount_payments, $amount_surcharge, $discount_tariff, $count = 1)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		if ($count == 1) {
			$request = array(
				'promocod'            => $promocod,
				'activete_count'      => $activete_count,
				'activete_count_user' => $activete_count_user,
				'product'             => $product,
				'tariff'              => $tariff,
				'conditions'          => $conditions,
				'date_start'          => $date_start,
				'date_end'            => $date_end,
				'manager'             => $manager,
				'status'              => $status,
				'promocode_limit'        => $promocode_limit,
				'promocode_used'         => $promocode_used,
				'amount_payments'     => $amount_payments,
				'amount_surcharge'    => $amount_surcharge,
				'discount_tariff'     => $discount_tariff,
			);
			$this->wpdb->insert("{$this->tables['promocodes']}", $request);
		} else {
			for ($i = 0; $i < $count; $i++) {
				$request = array(
					'promocod'            => $promocod . '_' . substr(str_shuffle($permitted_chars), 0, 4),
					'promocod_group'      => $promocod,
					'activete_count'      => $activete_count,
					'activete_count_user' => $activete_count_user,
					'product'             => $product,
					'tariff'              => $tariff,
					'conditions'          => $conditions,
					'date_start'          => $date_start,
					'date_end'            => $date_end,
					'manager'             => $manager,
					'status'              => $status,
					'promocode_limit'        => $promocode_limit,
					'promocode_used'         => $promocode_used,
					'amount_payments'     => $amount_payments,
					'amount_surcharge'    => $amount_surcharge,
					'discount_tariff'     => $discount_tariff
				);
				$this->wpdb->insert("{$this->tables['promocodes']}", $request);
			}
		}
	}

	/**
	 * It gets all the coupons from the database
	 * 
	 * @return Array An array of objects.
	 */
	public function get_promocodes($order = 'ASC')
	{
		return $this->wpdb->get_results("SELECT * FROM {$this->tables['promocodes']}");
	}

	/**
	 * It returns the number of coupons in the database
	 * 
	 * @return Number The number of coupons in the database.
	 */
	public function get_promocodes_count($order = 'ASC')
	{
		return $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(*) FROM {$this->tables['promocodes']}"));
	}

}