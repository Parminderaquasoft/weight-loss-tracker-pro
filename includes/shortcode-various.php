<?php
	defined('ABSPATH') or die('Jog on!');

function ws_ls_weight_start()
{
	// If not logged in then return no value
	if(!is_user_logged_in()) {
		return '';
	}

	if (ws_ls_get_config('WE_LS_DATA_UNITS') == "pounds_only") {
		$weight = ws_ls_get_start_weight_in_pounds();
	}
	else {
		$weight = ws_ls_get_weight_extreme(get_current_user_id());
	}
	return we_ls_format_weight_into_correct_string_format($weight);
}
function ws_ls_weight_recent()
{
	// If not logged in then return no value
	if(!is_user_logged_in()) {
		return '';
	}

	if (ws_ls_get_config('WE_LS_DATA_UNITS') == "pounds_only") {
		$weight =  ws_ls_get_recent_weight_in_pounds();
	}
	else {
		$weight =  ws_ls_get_weight_extreme(get_current_user_id(), true);
	}

	return we_ls_format_weight_into_correct_string_format($weight);
}
function ws_ls_weight_difference()
{
	// If not logged in then return no value
	if(!is_user_logged_in()) {
		return '';
	}

	if (ws_ls_get_config('WE_LS_DATA_UNITS') == "pounds_only"){
		$start_weight = ws_ls_get_start_weight_in_pounds();
		$recent_weight = ws_ls_get_recent_weight_in_pounds();
	}
	else	{
		$start_weight = ws_ls_get_start_weight_in_kg();
		$recent_weight = ws_ls_get_weight_extreme(get_current_user_id(), true);
	}

	$difference = $recent_weight - $start_weight;

	$display_string = ($difference > 0) ? "+" : "";

	$display_string .= we_ls_format_weight_into_correct_string_format($difference);

	return $display_string;
}
function ws_ls_weight_difference_target()
{
	// If not logged in then return no value
	if(!is_user_logged_in()) {
		return '';
	}

	if (ws_ls_get_config('WE_LS_DATA_UNITS') == "pounds_only")
	{
		$target_weight = ws_ls_get_target_weight_in_pounds();
		$recent_weight = ws_ls_get_recent_weight_in_pounds();
	}
	else
	{
		$target_weight = ws_ls_get_target_weight_in_kg();
		$recent_weight = ws_ls_get_weight_extreme(get_current_user_id(), true);
	}
	$difference = $recent_weight - $target_weight;

	$display_string = ($difference > 0) ? "+" : "";

	$display_string .= we_ls_format_weight_into_correct_string_format($difference);

	return $display_string;
}

function ws_ls_get_start_weight_in_kg()
{
	return ws_ls_get_weight_extreme(get_current_user_id());
}
function ws_ls_get_recent_weight_in_kg()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), true);
}
function ws_ls_get_start_weight_in_pounds()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), false, "weight_only_pounds");
}
function ws_ls_get_recent_weight_in_pounds()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), true, "weight_only_pounds");
}

function ws_ls_get_weight_extreme($user_id, $recent = false, $unit = "weight_weight")
{
	global $wpdb;

	$direction = "asc";

	if ($recent)
		$direction = "desc";

	$cache_key = $user_id . '-' . WE_LS_CACHE_KEY_WEIGHT_EXTREME . '-' . $direction;
	$cache = ws_ls_get_cache($cache_key);

	// Return cache if found!
	if ($cache)   {
			return $cache;
	}

	$table_name = $wpdb->prefix . WE_LS_TABLENAME;
	$sql =  $wpdb->prepare("SELECT " . $unit . " as weight_value FROM $table_name where weight_user_id = %d order by weight_date " . $direction . " limit 0, %d", $user_id, 1);
	$rows = $wpdb->get_row($sql);

	if (count($rows) > 0) {
		ws_ls_set_cache($cache_key, $rows->weight_value);
		return $rows->weight_value;
	}
	else
		return false;

}
function ws_ls_get_target_weight_in_kg()
{
	return ws_ls_get_weight_target(get_current_user_id());
}
function ws_ls_get_target_weight_in_pounds()
{
	return ws_ls_get_weight_target(get_current_user_id(), "target_weight_only_pounds");
}
function ws_ls_get_weight_target($user_id, $unit = "target_weight_weight")
{
	global $wpdb;

	$cache_key = $user_id . '-' . WE_LS_CACHE_KEY_TARGET_WEIGHT;
  $cache = ws_ls_get_cache($cache_key);

  // Return cache if found!
  if ($cache)   {
      return $cache;
  }

	$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;
	$sql =  $wpdb->prepare("SELECT " . $unit . " as weight_value FROM $table_name where weight_user_id = %d", $user_id);
	$rows = $wpdb->get_row($sql);

	if (count($rows) > 0) {
		ws_ls_set_cache($cache_key, $rows->weight_value);
		return $rows->weight_value;
	}

	return false;

}
function we_ls_format_weight_into_correct_string_format($weight)
{
	if(ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS'))
	{
		if (ws_ls_get_config('WE_LS_DATA_UNITS') == "pounds_only")
			return $weight . __("lbs", WE_LS_SLUG);
		else
		{
			$weight_data = ws_ls_to_stone_pounds($weight);
			return $weight_data["stones"] . __("st", WE_LS_SLUG) . " " . (($weight_data["pounds"] < 0) ? abs($weight_data["pounds"]) : $weight_data["pounds"]) . __("lbs", WE_LS_SLUG);
		}

	}
	else
		return $weight . __("Kg", WE_LS_SLUG);
}
