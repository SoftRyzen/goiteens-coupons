<?php

/**
 * Plugin Name: GoITeens Промокоди
 * Plugin URI: https://goiteens.com
 * Description:	Плагін для роботи з промокодами на платформі GoITeens. 
 * Version: 1.0.0
 * Author: Oleksandr Orlovskyi
 * Author URI: https://empat.dev
 */

/* If this file is called directly, abort. */
if (!defined('WPINC')) {
	die();
}

/* Defining constants for the plugin. */
define('GOIT_PRMCODE_FILE', __FILE__);
define('GOIT_PRMCODE_PATH', trailingslashit(plugin_dir_path(GOIT_PRMCODE_FILE)));
define('GOIT_PRMCODE_URL', plugins_url('/', GOIT_PRMCODE_FILE));
define('GOIT_PRMCODE_VERSION', '1.0.0');

/* It's autoloader. It's loading classes from the `app` folder. */
spl_autoload_register(function ($class) {
	$prefix = 'goit_prmcode\\';
	$base_dir = GOIT_PRMCODE_PATH . 'app/';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	if (file_exists($file)) {
		require $file;
	}
});

// Dump to log helper function
if ( ! function_exists( 'wp_log' ) ) {
	function wp_log( $var, $desc = ' >> ', $clear_log = false ) {
		$log_file_destination = GOIT_PRMCODE_PATH . '/app/logs/dump.log';
		if ( $clear_log ) {
			file_put_contents( $log_file_destination, '' );
		}
		error_log( '[' . wp_date( "H:i:s" ) . ']' . '-------------------------' . PHP_EOL, 3, $log_file_destination );
		error_log( '[' . wp_date( "H:i:s" ) . ']' . $desc . ' : ' . print_r( $var, true ) . PHP_EOL, 3, $log_file_destination );
	}
}

/**
 * Global point of enter
 * It's a function that returns an instance of the `app` class. 
 **/
if (!function_exists('GOIT_PRMCODE')) {
	function GOIT_PRMCODE()
	{
		return \goit_prmcode\app::getInstance();
	}
}

/**
 * Run the plugin
 **/
GOIT_PRMCODE()->run();