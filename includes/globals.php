<?php
	defined('ABSPATH') or die("Jog on!");

	global $form_number;

	// Set defaults
	$globals = array(
		'WE_LS_DATA_UNITS' => 'kg',
		'WE_LS_IMPERIAL_WEIGHTS' => false,
		'WE_LS_ALLOW_TARGET_WEIGHTS' => true,
		'WE_LS_ALLOW_POINTS' => true,
		'WE_LS_USE_TABS' => true,
		'WE_LS_CSS_ENABLED' => true,
		'WE_LS_TARGET_LINE_COLOUR' => '#76bada',
		'WE_LS_WEIGHT_LINE_COLOUR' => '#aeaeae',
		'WE_LS_WEIGHT_FILL_COLOUR' => '#f9f9f9',
		'WE_LS_US_DATE' => false,
		'WS_LS_USE_DECIMALS' => false,
		'WE_LS_CHART_TYPE' => 'line', //line, bar
		'WS_LS_ADVANCED_TABLES' => true,
		'WE_LS_CHART_MAX_POINTS' => 25,
		'WE_LS_CHART_HEIGHT' => 250,
		'WE_LS_CHART_BEZIER_CURVE' => true,
		'WE_LS_CHART_POINT_SIZE' => 3,
		'WE_LS_CHART_SHOW_GRID_LINES' => true
	);

	// -----------------------------------------------------------------------------------
	// Constants - highly recommended that you don't change these
	// -----------------------------------------------------------------------------------
	define('WE_LS_TITLE', 'Weight Loss Tracker');
	define('WE_LS_SLUG', 'weight-loss-tracker');
	define('WE_LS_TABLENAME', 'WS_LS_DATA');
	define('WE_LS_DATA_URL', 'https://yeken.uk/wlt/plugin-info.json');
	define('WE_LS_TARGETS_TABLENAME', 'WS_LS_DATA_TARGETS');
	define('WE_LS_USER_PREFERENCES_TABLENAME', 'WS_LS_DATA_USER_PREFERENCES');
	define('WE_LS_CACHE_ENABLED', true);
	define('WE_LS_CACHE_TIME', 15 * MINUTE_IN_SECONDS);
	define('WE_LS_CACHE_KEY_TARGET', 'target-data');
	define('WE_LS_CACHE_KEY_DATA', 'weight-data');
	define('WE_LS_CACHE_KEY_MIN_MAX_DATES', 'min-max-dates');
	define('WE_LS_CACHE_KEY_TARGET_WEIGHT', 'target-weight');
	define('WE_LS_CACHE_KEY_START_WEIGHT', 'start-weight-index');
	define('WE_LS_CACHE_KEY_WEIGHT_EXTREME', 'weight-extreme-');
	define('WE_LS_CACHE_KEY_USER_PREFERENCE', 'user-preference');
	define('WE_LS_CACHE_KEY_YEKEN_JSON', 'yeken-json-lookup');
	define('WE_LS_CACHE_ADMIN_USER_DATA', 'admin-user-data');
	define('WE_LS_TABLE_MAX_WEEK_FILTERS', 150);
	define('WS_LS_PRO_PRICE', 25.00);
	define('WE_LS_USE_MINIFIED_SCRIPTS', true);
	// -----------------------------------------------------------------------------------
	// Allow user's to override the default admin settings?
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && (false == get_option('ws-ls-allow-user-preferences') || 'yes' == get_option('ws-ls-allow-user-preferences'))) {
		define('WE_LS_ALLOW_USER_PREFERENCES', true);
	} else {
		define('WE_LS_ALLOW_USER_PREFERENCES', false);
	}
	// -----------------------------------------------------------------------------------
	// Define whether Imperial and Units
	// -----------------------------------------------------------------------------------
	if ('stones_pounds' == get_option('ws-ls-units') || 'pounds_only' == get_option('ws-ls-units')) {
		$globals['WE_LS_DATA_UNITS'] = get_option('ws-ls-units');
		$globals['WE_LS_IMPERIAL_WEIGHTS'] = true;
	}
	// -----------------------------------------------------------------------------------
	// UK or US date?
	// -----------------------------------------------------------------------------------
	if ('us' == get_option('ws-ls-use-us-dates')){
		$globals['WE_LS_US_DATE'] = true;
	}
	// -----------------------------------------------------------------------------------
	// Allow decimals?
	// -----------------------------------------------------------------------------------
	if ('yes' == get_option('ws-ls-allow-decimals')){
		$globals['WS_LS_USE_DECIMALS'] = true;
	}
	// -----------------------------------------------------------------------------------
	// Plot points
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && get_option('ws-ls-max-points')){
		$globals['WE_LS_CHART_MAX_POINTS'] = get_option('ws-ls-max-points');
	}
	// -----------------------------------------------------------------------------------
	// Point Size
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && get_option('ws-ls-point-size')){
		$globals['WE_LS_CHART_POINT_SIZE'] = get_option('ws-ls-point-size');
	}
	// -----------------------------------------------------------------------------------
	// Bezier Curve
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && 'no' == get_option('ws-ls-bezier-curve')){
		$globals['WE_LS_CHART_BEZIER_CURVE'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Grid Lines?
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && 'no' == get_option('ws-ls-grid-lines')){
		$globals['WE_LS_CHART_SHOW_GRID_LINES'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Bar chart?
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && in_array(get_option('ws-ls-chart-type'), array('bar', 'line'))){
		$globals['WE_LS_CHART_TYPE'] = get_option('ws-ls-chart-type');
	}
	// -----------------------------------------------------------------------------------
	// Advanced tables?
	// -----------------------------------------------------------------------------------
	if (WS_LS_IS_PRO && ('yes' == get_option('ws-ls-allow-advanced-tables') || false == get_option('ws-ls-allow-advanced-tables')) ){
		$globals['WS_LS_ADVANCED_TABLES'] = true;
	}
	// -----------------------------------------------------------------------------------
	// Define if target weights enabled
	// -----------------------------------------------------------------------------------
	if ('no' == get_option('ws-ls-allow-targets')) {
		$globals['WE_LS_ALLOW_TARGET_WEIGHTS'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Plot points on graph?
	// -----------------------------------------------------------------------------------
	if ('no' == get_option('ws-ls-allow-points')) {
		$globals['WE_LS_ALLOW_POINTS'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Define whether to use tabs
	// -----------------------------------------------------------------------------------
	if ('no' == get_option('ws-ls-use-tabs')) {
			$globals['WE_LS_USE_TABS'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Disable plugin CSS
	// -----------------------------------------------------------------------------------
	if ('yes' == get_option('ws-ls-disable-css')) {
			$globals['WE_LS_CSS_ENABLED'] = false;
	}
	// -----------------------------------------------------------------------------------
	// Line Colours
	// -----------------------------------------------------------------------------------
	if (get_option('ws-ls-target-colour')) {
		$globals['WE_LS_TARGET_LINE_COLOUR'] = get_option('ws-ls-target-colour');
	}
	if (get_option('ws-ls-line-colour')) {
		$globals['WE_LS_WEIGHT_LINE_COLOUR'] = get_option('ws-ls-line-colour');
	}
	if (get_option('ws-ls-line-fill-colour')) {
		$globals['WE_LS_WEIGHT_FILL_COLOUR'] = get_option('ws-ls-line-fill-colour');
	}

	// -----------------------------------------------------------------------------------
	// Loop through array and set defines!
	// -----------------------------------------------------------------------------------
  foreach($globals as $key => $value) {
		define($key, $value);
	}
