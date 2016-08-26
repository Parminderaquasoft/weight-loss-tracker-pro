<?php

	defined('ABSPATH') or die("Jog on!");

	function ws_ls_build_admin_menu()
	{
		add_menu_page(WE_LS_TITLE, WE_LS_TITLE, 'manage_options', 'ws-ls-weight-loss-tracker-main-menu', 'ws_ls_settings_page', 'dashicons-chart-line');
		add_submenu_page( 'ws-ls-weight-loss-tracker-main-menu', __('Settings', WE_LS_SLUG),  __('Settings', WE_LS_SLUG), 'manage_options', 'ws-ls-weight-loss-tracker-main-menu');

		if(!WS_LS_IS_PRO) {
			add_submenu_page( 'ws-ls-weight-loss-tracker-main-menu', __('Manage User Data', WE_LS_SLUG),  __('Manage User Data', WE_LS_SLUG), 'manage_options', 'ws-ls-weight-loss-tracker-pro', 'ws_ls_advertise_pro');
			add_submenu_page( 'ws-ls-weight-loss-tracker-main-menu', __('Get Pro Version', WE_LS_SLUG),  __('Get Pro Version', WE_LS_SLUG), 'manage_options', 'ws-ls-weight-loss-tracker-pro', 'ws_ls_advertise_pro');
		} else {
			add_submenu_page( 'ws-ls-weight-loss-tracker-main-menu', __('Manage User Data', WE_LS_SLUG),  __('Manage User Data', WE_LS_SLUG), 'manage_options', 'ws-ls-weight-loss-tracker-pro', 'ws_ls_manage_user_data_page');
		}

		add_submenu_page( 'ws-ls-weight-loss-tracker-main-menu', __('Help', WE_LS_SLUG),  __('Help', WE_LS_SLUG), 'manage_options', 'ws-ls-weight-loss-tracker-help', 'ws_ls_help_page');

	}
	add_action( 'admin_menu', 'ws_ls_build_admin_menu' );

  /* Register the relevant WP shortcodes */
	function ws_ls_register_shortcodes(){

		/*
			[weightloss_weight_difference] - total weight lost by the logged in member
			[weightloss_weight_start] - start weight of the logged in member
			[weightloss_weight_most_recent] - end weight of the logged in member
			[weightloss_weight_difference_from_target] - difference between latest and target
		*/

	 	add_shortcode( 'weightlosstracker', 'ws_ls_shortcode' );
		add_shortcode( 'weight-loss-tracker', 'ws_ls_shortcode' );
	 	add_shortcode( 'weightloss_weight_difference', 'ws_ls_weight_difference' );
	 	add_shortcode( 'weightloss_weight_start', 'ws_ls_weight_start' );
	 	add_shortcode( 'weightloss_weight_most_recent', 'ws_ls_weight_recent' );
		add_shortcode( 'weightloss_weight_difference_from_target', 'ws_ls_weight_difference_target' );

        // If user has deleted all their data then delete it here. That way cache isn't displayed
        if(WE_LS_ALLOW_USER_PREFERENCES && isset($_GET['user-delete-all']) && 'true' == $_GET['user-delete-all'])	{
            ws_ls_delete_data_for_user();
        }
        
	}
	add_action( 'init', 'ws_ls_register_shortcodes');

	function ws_ls_enqueue_files(){

			$minified = (WE_LS_USE_MINIFIED_SCRIPTS) ? '.min' : '';

			// CSS
			if (WE_LS_CSS_ENABLED) {
				wp_enqueue_style('wlt-style', plugins_url( '../css/ws-ls' . 	$minified . '.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);
			}

			wp_enqueue_style('jquery-style', plugins_url( '../css/jquery-ui.min.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);

			// JavaScript files
			wp_enqueue_script('jquery-chart-ws-ls', plugins_url( '../js/chart.min.js', __FILE__ ), array( 'jquery' ), WE_LS_CURRENT_VERSION);
			wp_enqueue_script('jquery-validate',plugins_url( '../js/jquery.validate.min.js', __FILE__ ), array('jquery'), WE_LS_CURRENT_VERSION);
			wp_enqueue_script('jquery-validate-additional',plugins_url( '../js/additional-methods.min.js', __FILE__ ), array('jquery', 'jquery-validate'), WE_LS_CURRENT_VERSION);
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('wl-ls-js', plugins_url( '../js/ws-ls' . 	$minified . '.js', __FILE__ ), array(), WE_LS_CURRENT_VERSION);

			// Add locilzation data for JS
			wp_localize_script('wl-ls-js', 'ws_ls_config', ws_ls_get_js_config());

			// Tabs enabled?
			if (WE_LS_USE_TABS)	{
				wp_enqueue_script('jquery-tabs',plugins_url( '../js/tabs.min.js', __FILE__ ), array('jquery'), WE_LS_CURRENT_VERSION);
				wp_enqueue_style('wlt-tabs', plugins_url( '../css/tabs.min.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);
				wp_enqueue_style('wlt-tabs-flat', plugins_url( '../css/tabs.flat.min.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);
			}
	}
	add_action( 'wp_head', 'ws_ls_enqueue_files');
