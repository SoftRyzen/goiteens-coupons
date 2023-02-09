<?php
namespace goit_prmcode\helper;

class Log
{
	public static function logging($module_name = '', $data = '', $file_name = 'logs')
	{
		file_put_contents(dirname(__FILE__) . '/' . $file_name . '.txt', wp_date('Y-m-d H:i:s') . " Error $module_name : " . $data . "\n", FILE_APPEND);
	}

}