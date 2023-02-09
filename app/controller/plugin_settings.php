<?php
namespace goit_prmcode\controller;

/**
 * Plugin Settings Controller
 **/
class plugin_settings
{

	/**
	 * Constructor
	 **/
	function __construct()
	{

		/* Registering the activation and deactivation hooks. */
		register_activation_hook(GOIT_PRMCODE_FILE, [$this, 'activation_promocodes']);
		register_deactivation_hook(GOIT_PRMCODE_FILE, [$this, 'deactivation_promocodes']);

	}

	/**
	 * It creates a table in the database after activation.
	 */
	function activation_promocodes()
	{
		GOIT_PRMCODE()->model->database->create_table();

		/* Checking if the hook is already scheduled. If not, it will schedule it. */
		if (!wp_next_scheduled('zoho_products_check')) {
			GOIT_PRMCODE()->controller->ZohoCRM->zoho_products_check();
			wp_schedule_event(time(), 'hourly', 'zoho_products_check');
		}
	}

	/**
	 * It drops the table after deactivation.
	 */
	function deactivation_promocodes()
	{
		wp_clear_scheduled_hook('zoho_products_check');
	}

}