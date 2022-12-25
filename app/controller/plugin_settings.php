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
	}

	/**
	 * It drops the table after deactivation.
	 */
	function deactivation_promocodes()
	{
		GOIT_PRMCODE()->model->database->drop_table();
	}

}