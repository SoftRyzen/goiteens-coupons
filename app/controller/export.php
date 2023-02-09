<?php
namespace goit_prmcode\controller;

use goit_prmcode\helper\media;

/**
 * Export CSV Controller
 **/
class export
{

	public static function csv_url($group): string
	{
		$url = GOIT_PRMCODE_PATH . 'assets/csv/Promocodes_' . $group . '.csv';

		/* It checks if the file exists and if it does, it returns the answer. */
		if (file_exists($url)) {
			return '/wp-content/plugins/goit-promocodes/assets/csv/Promocodes_' . $group . '.csv';
		}

		/* Creating a CSV file. */
		$promocodes = GOIT_PRMCODE()->model->promocode->get_promocodes($group, true);
		$create_data = array(array(__("Промокоди", 'goit_promocode')));
		foreach ($promocodes as $item) {
			$arr = array(array("$item->promocod"));
			$create_data = array_merge($create_data, $arr);
		}

		media::create_csv_file($create_data, $url);

		return '/wp-content/plugins/goit-promocodes/assets/csv/Promocodes_' . $group . '.csv';
	}

}