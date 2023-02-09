<?php
namespace goit_prmcode\model;

class tariff extends database
{
	public function add($data)
	{
		$promocod_type = $data['promocod_group'] ? 'promocod_group' : 'promocod_id';
		$check_val = $this->check($promocod_type, $data[$promocod_type], $data['tariff']);

		$request = array(
			'tariff'           => $data['tariff'], // Month count
			$promocod_type     => $data[$promocod_type], // Only ID or Group
			'amount_surcharge' => $data['amount_surcharge'], // Amount
			'discount_tariff'  => $data['discount_tariff'] // Percent, UAH, USD, EUR
		);

		if (empty($check_val)) {
			$this->wpdb->insert($this->tables['tariff'], $request);
		} else {
			$this->wpdb->update($this->tables['tariff'], $request, array($promocod_type => $data[$promocod_type], 'tariff' => $data['tariff']));
		}

		return true;
	}

	public function get($promocod, $group = false): array
	{
		$where = $group ? 'promocod_group' : 'promocod_id';
		return $this->wpdb->get_results($this->wpdb->prepare("SELECT tariff, amount_surcharge, discount_tariff FROM {$this->tables['tariff']} WHERE $where = %s ORDER BY id ASC", $promocod));
	}

	public function get_ID_by_promocode($promocod, $group = false): array
	{
		$where = $group ? 'promocod_group' : 'promocod_id';
		return $this->wpdb->get_results($this->wpdb->prepare("SELECT id FROM {$this->tables['tariff']} WHERE $where = %s ORDER BY id ASC", $promocod));
	}

	private function check($pType, $pValue, $tValue): array
	{
		return $this->wpdb->get_results($this->wpdb->prepare("SELECT %s FROM $this->tables['tariff'] WHERE %s = %s AND tariff = %s ORDER BY id DESC", $pType, $pType, $pValue, $tValue));
	}

}