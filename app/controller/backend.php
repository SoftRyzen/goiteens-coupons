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
			['jquery',], '1.0', true
		);

		wp_localize_script('goiteens-promocodes-admin', 'goitPluginVars', [
			'ajaxurl'   => admin_url('admin-ajax.php'),
			'ajaxNonce' => wp_create_nonce('goiteens_ajax_nonce'),
			'adminURL'  => admin_url('/admin.php?page=goit_promocode'),
			'postURL'   => admin_url('/admin.php?page=goit_promocode_post'),
			'settings'  => admin_url('/admin.php?page=goit_promocode_settings'),
			'statistic' => admin_url('/admin.php?page=goit_promocode_statistic'),
			'product'   => admin_url('/admin.php?page=goit_promocode_products'),
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
			'publish_pages',
			'goit_promocode',
			[$this, 'goit_promocode_screen'],
			'dashicons-tickets',
			6
		);
		add_submenu_page(
			'goit_promocode',
			__('Додати новий', 'goit_promocode'),
			__('Додати новий', 'goit_promocode'),
			'edit_pages',
			'goit_promocode_add',
			[$this, 'goit_promocode_add']
		);
		add_submenu_page(
			'goit_promocode',
			__('Статистика', 'goit_promocode'),
			__('Статистика', 'goit_promocode'),
			'publish_pages',
			'goit_promocode_statistic',
			[$this, 'goit_promocode_statistic']
		);
		add_submenu_page(
			'goit_promocode',
			__('Продукти', 'goit_promocode'),
			__('Продукти', 'goit_promocode'),
			'publish_pages',
			'goit_promocode_products',
			[$this, 'goit_promocode_products']
		);
		add_submenu_page(
			'goit_promocode',
			__('Налаштування', 'goit_promocode'),
			__('Налаштування', 'goit_promocode'),
			'edit_dashboard',
			'goit_promocode_settings',
			[$this, 'goit_promocode_settings']
		);
		add_submenu_page(
			'',
			__('Промокод/Группа', 'goit_promocode'),
			__('Промокод/Группа', 'goit_promocode'),
			'publish_pages',
			'goit_promocode_post',
			[$this, 'goit_promocode_post']
		);
		add_submenu_page(
			'',
			__('UI', 'goit_promocode'),
			__('UI', 'goit_promocode'),
			'publish_pages',
			'goit_promocode_ui',
			[$this, 'goit_promocode_ui']
		);
	}

	function goit_promocode_post()
	{
		GOIT_PRMCODE()->view->load('admin_menu/post');
	}

	/**
	 *  Admin Nav - Dashboard Screen
	 **/
	function goit_promocode_statistic()
	{
		GOIT_PRMCODE()->view->load('admin_menu/statistic');
	}

	/**
	 *  Admin Nav - Promocodes Screen
	 **/
	function goit_promocode_screen()
	{
		GOIT_PRMCODE()->view->load('admin_menu/promocodes');
	}

	/**
	 *  Admin Nav - Add Page Screen
	 **/
	function goit_promocode_products()
	{
		GOIT_PRMCODE()->view->load('admin_menu/products');
	}

	/**
	 *  Admin Nav - Add Page Screen
	 **/
	function goit_promocode_add()
	{
		GOIT_PRMCODE()->view->load('admin_menu/add');
	}

	/**
	 *  Admin Nav - Settings Screen
	 **/
	function goit_promocode_settings()
	{
		GOIT_PRMCODE()->view->load('admin_menu/settings');
	}

	/**
	 *  Admin Nav - UI Hidden Screen
	 **/
	function goit_promocode_ui()
	{
		GOIT_PRMCODE()->view->load('admin_menu/ui');
	}

}