<?php
namespace goit_prmcode\helper;

class media
{

	/**
	 * Print tag
	 */
	public static function print_tag($text, $tag, $default_tag = 'div', $class = '')
	{

		$print_tag = '';

		if (in_array($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
			$print_tag = $tag;
		} else {
			$print_tag = $default_tag;
		}

		echo '<' . $print_tag . ' class="' . $class . '">' . nl2br($text) . '</' . $print_tag . '>';
	}

	public static function create_csv_file($create_data, $file = null, $col_delimiter = ';', $row_delimiter = "\r\n")
	{

		if (!is_array($create_data)) {
			return false;
		}

		if ($file && !is_dir(dirname($file))) {
			return false;
		}

		$CSV_str = '';

		foreach ($create_data as $row) {
			$cols = array();

			foreach ($row as $col_val) {
				if ($col_val && preg_match('/[",;\r\n]/', $col_val)) {
					if ($row_delimiter === "\r\n") {
						$col_val = str_replace(["\r\n", "\r"], ['\n', ''], $col_val);
					} elseif ($row_delimiter === "\n") {
						$col_val = str_replace(["\n", "\r\r"], '\r', $col_val);
					}

					$col_val = str_replace('"', '""', $col_val);
					$col_val = '"' . $col_val . '"';
				}

				$cols[] = $col_val;
			}


			$CSV_str .= implode($col_delimiter, $cols) . $row_delimiter;
		}
		$CSV_str = rtrim($CSV_str, $row_delimiter);

		if ($file) {
			$CSV_str = iconv("UTF-8", "cp1251", $CSV_str);

			$done = file_put_contents($file, $CSV_str);

			return $done ? $CSV_str : false;
		}

		return $CSV_str;

	}

}