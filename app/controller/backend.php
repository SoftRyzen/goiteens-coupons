<?php

namespace goit_prmcode\controller;

/**
 * Backend Controller
 **/
class backend
{

	/**
	 * Constructor
	 **/
	function __construct()
	{
		// load admin assets
		add_action('admin_enqueue_scripts', [$this, 'load_assets']);

		// Register a custom menu page.
		add_action('admin_menu', [$this, 'register_nav_menu_page'], 15);

	}

	/**
	 * Loads the CSS and JS files for the admin area.
	 */
	function load_assets()
	{
		wp_enqueue_style('goiteens-promocodes-admin', plugins_url('/assets/scss/app.min.css', GOIT_PRMCODE_FILE), false, '1.0');

		wp_enqueue_script('goiteens-promocodes-libs',
			plugins_url('/assets/js/libs.min.js', GOIT_PRMCODE_FILE)
		);

		wp_enqueue_script('goiteens-promocodes-admin',
			plugins_url('/assets/js/app.min.js', GOIT_PRMCODE_FILE),
			['jquery'], '1.0', true
		);

		wp_localize_script('goiteens-promocodes-admin', 'pluginVars', [
			'ajaxurl'   => admin_url('admin-ajax.php'),
			'ajaxNonce' => wp_create_nonce('wa_ajax_nonce'),
			'adminURL'  => admin_url('/admin.php?page=goit_promocode'),
			'postURL'  => admin_url('/admin.php?page=goit_promocode_post'),
		]);

	}

	/**
	 * Registration Nav
	 **/
	function register_nav_menu_page()
	{
		add_menu_page(
			__('Промокоди', 'goit_promocode'),
			__('Промокоди', 'goit_promocode'),
			'manage_options',
			'goit_promocode',
			[$this, 'goit_promocode_screen'],
			'dashicons-tickets',
			6
		);
		add_submenu_page(
			'goit_promocode',
			__('Додати новий', 'goit_promocode'),
			__('Додати новий', 'goit_promocode'),
			'edit_themes',
			'goit_promocode_add',
			[$this, 'goit_promocode_add']
		);
		add_submenu_page(
			'goit_promocode',
			__('Статистика', 'goit_promocode'),
			__('Статистика', 'goit_promocode'),
			'manage_options',
			'goit_promocode_statistics',
			[$this, 'goit_promocode_statistics']
		);
		add_submenu_page(
		null,
			__('Промокод/Группа', 'goit_promocode'),
			__('Промокод/Группа', 'goit_promocode'),
			'manage_options',
			'goit_promocode_post',
			[$this, 'goit_promocode_post'],
		null
		);
	}

	function goit_promocode_post()
	{
		GOIT_PRMCODE()->view->load('admin_menu/post');
	}

	/**
	 *  Admin Nav - Dashboard Screen
	 **/
	function goit_promocode_statistics()
	{
		GOIT_PRMCODE()->view->load('admin_menu/statistics');
	}

	/**
	 *  Admin Nav - Coupons Screen
	 **/
	function goit_promocode_screen()
	{
		GOIT_PRMCODE()->view->load('admin_menu/promocodes');
	}

	/**
	 *  Admin Nav - Add Coupon Screen
	 **/
	function goit_promocode_add()
	{
		GOIT_PRMCODE()->view->load('admin_menu/add');
	}

}