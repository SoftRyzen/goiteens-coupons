<?php

namespace goit_prmcode;

defined('ABSPATH') or exit;

/**
 * Define GoITeeens Promocodes class
 */
class app
{

	private static $instance = null;
	public $model;
	public $view;
	public $controller;
	public $current_lang;


	/**
	 * @return static
	 **/
	public static function getInstance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static ();
		}

		return static::$instance;
	}

	private function __construct()
	{

		// $this->includes();

	}

	private function __clone()
	{
	}

	/**
	 * Run the core
	 **/
	public function run()
	{

		session_start();

		$this->current_lang = isset($_SERVER['HTTP_X_GT_LANG']) ? $_SERVER['HTTP_X_GT_LANG'] : 'ua';

		// Load core classes
		$this->_dispatch();
	}

	/**
	 * Load and instantiate all application
	 * classes neccessary for this theme
	 **/
	private function _dispatch()
	{

		$this->model = new \stdClass();
		$this->view = new \stdClass();
		$this->controller = new \stdClass();

		// Autoload models
		$this->_load_modules('model', '/');

		// Init view
		$this->view = new \goit_prmcode\view\view();

		// Load controllers manually
		$controllers = [
			'backend',
			'plugin_settings',
			'ajax',
		];

		$this->_load_controllers($controllers);
	}

	/**
	 * Autoload core modules in a specific directory
	 *
	 * @param string
	 * @param string
	 * @param bool
	 **/
	private function _load_modules($layer, $dir = '/')
	{

		$directory = GOIT_PRMCODE_PATH . '/app/' . $layer . $dir;
		$handle = opendir($directory);

		if (count(glob("$directory/*")) === 0) {
			return false;
		}

		while (false !== ($file = readdir($handle))) {

			if (is_file($directory . $file)) {

				// Figure out class name from file name
				$class = str_replace('.php', '', $file);

				// Avoid recursion
				if ($class !== get_class($this)) {
					$classPath = "\\goit_prmcode\\{$layer}\\{$class}";
					$this->$layer->$class = new $classPath();
				}
			}
		}
	}

	/**
	 * Autoload controllers in specific order
	 */
	private function _load_controllers($list)
	{

		$directory = GOIT_PRMCODE_PATH . '/app/controller/';

		foreach ($list as $controller_name) {

			if (is_file($directory . $controller_name . '.php')) {
				$class = $controller_name;

				// Avoid recursion
				if ($class !== get_class($this)) {
					$classPath = "\\goit_prmcode\\controller\\{$class}";
					$this->controller->$controller_name = new $classPath();
				}
			}
		}
	}
}