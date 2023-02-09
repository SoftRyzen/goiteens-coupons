<?php
namespace goit_prmcode\helper;

class model
{

	public static function product_list($selected_products = false, $returnLinks = false)
	{

		$products = GOIT_PRMCODE()->model->product->get_all_products();
		$selected_products = $selected_products ? $selected_products : false;
		$selected = "";

		foreach ($products as $item) {

			$currencies = '₴';

			/* if Multycurrencies */

			// $currencies = $item->discount_tariff == 'percent' ? '%' : $item->discount_tariff;
			// if ($currencies == 'UAH')
			// 	$currencies = '₴';
			// else if ($currencies == 'USD')
			// 	$currencies = '$';
			// else if ($currencies == 'EUR')
			// 	$currencies = '€';

			/* It's a function that returns a link to the product page. */
			if ($returnLinks && $selected_products) {
				if (is_array($selected_products)) {
					foreach ($selected_products as $id)
						if ($id == $item->id)
							echo '<a href="' . admin_url('/admin.php?page=goit_promocode_products') . '&id=' . $item->id . '" target="_blank">'
								. $item->product_name . ' [' . $item->unit_price . $currencies . ']' . '</a>';
				} else {
					if ($selected_products == $item->id)
						echo '<a href="' . admin_url('/admin.php?page=goit_promocode_products') . '&id=' . $item->id . '" target="_blank">'
							. $item->product_name . ' [' . $item->unit_price . $currencies . ']' . '</a>';
				}
			} else { // It's a function that returns a list of products. 

				if (is_array($selected_products)) {
					foreach ($selected_products as $id)
						if ($id == $item->id)
							$selected = ' selected';
				} else {
					if ($selected_products == $item->id)
						$selected = ' selected';
				}

				echo '<option value="' . $item->product_name . '&id=' . $item->id . '" ' . $selected . '>'
					. $item->product_name . ' [' . $item->unit_price . $currencies . ']'
					. '</option>';
			}

		}
	}

	// Tariff Elements
	public static function tariff_list($data, $edit = false): void
	{
		foreach ($data as $item) {

			$monthTariff = '';

			if ($item->tariff == 1)
				$monthTariff = $item->tariff . ' ' . __('місяць', 'goit_promocode');
			else if ($item->tariff == 2 || $item->tariff == 3 || $item->tariff == 4)
				$monthTariff = $item->tariff . ' ' . __('місяці', 'goit_promocode');
			else
				$monthTariff = $item->tariff . ' ' . __('місяців', 'goit_promocode');

			$discount_tariff = $item->discount_tariff == 'percent' ? '%' : $item->discount_tariff;

			if ($discount_tariff == 'UAH')
				$discount_tariff = '₴';
			else if ($discount_tariff == 'USD')
				$discount_tariff = '$';
			else if ($discount_tariff == 'EUR')
				$discount_tariff = '€';

			// Return Tariff Items
			if ($edit)
				echo '<div class="tariff" style="order: ' . $item->tariff . '">' .
					$monthTariff . ' - ' . $item->amount_surcharge . $discount_tariff .
					'<span class="remove" data-value="' . $item->tariff . '"></span>' .
					'<input type="hidden" id="tariff_' . $item->tariff . '" value="' . $item->tariff . '-' . $item->amount_surcharge . ' ' . $item->discount_tariff . '">' .
					'</div>';
			else
				echo '<div class="tariff" style="order: ' . $item->tariff . '">' . $monthTariff . ' - ' . $item->amount_surcharge . $discount_tariff . '</div>';
		}
	}

	public static function tariff_select_options(array $tariff_active)
	{

		$max_month_count = 12;
		$selected_el = null;

		for ($month = 1; $month <= $max_month_count; $month++) {
			$class = '';

			if (in_array($month, $tariff_active)) {
				$class = 'hidden';
				if (empty($selected_el))
					$selected_el = $month + 1;
			}

			if ($month == 1) {
				$month_counts = __('місяць', 'goit_promocode');
			} else if ($month > 1 && $month < 5) {
				$month_counts = __('місяці', 'goit_promocode');
			} else {
				$month_counts = __('місяців', 'goit_promocode');
			}

			// Return Option
			echo '<option class="' . $class . '" value="' . $month . '"';
			if ($selected_el == $month) {
				echo ' selected ';
			}
			echo '>' . $month . ' ' . $month_counts . '</option>';
		}
	}

}