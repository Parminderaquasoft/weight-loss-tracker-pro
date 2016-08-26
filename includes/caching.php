<?php
defined('ABSPATH') or die("Jog on!");

/* All caching related logic here! */

function ws_ls_get_cache($key) {

    if(WE_LS_CACHE_ENABLED) {
      $key = ws_ls_generate_cache_key($key);
      return get_transient($key);
    }

    return false;

}

function ws_ls_set_cache($key, $data, $time_to_expire = WE_LS_CACHE_TIME) {

    if(WE_LS_CACHE_ENABLED) {
      $key = ws_ls_generate_cache_key($key);
      set_transient($key, $data, WE_LS_CACHE_TIME);
    }

    return false;
}

function ws_ls_delete_cache($key){

    if(WE_LS_CACHE_ENABLED) {
      $key = ws_ls_generate_cache_key($key);
      return delete_transient($key);
    }
    return false;
}
function ws_ls_delete_cache_for_given_user($user_id = false)
{
  global $wpdb;

  if (WE_LS_CACHE_ENABLED){
    if (false == $user_id)  {
      $user_id = get_current_user_id();
    }

    $sql = "Delete FROM  $wpdb->options
            WHERE option_name LIKE '%transient_" . WE_LS_SLUG . $user_id ."%'";

    $wpdb->query($sql);

    $sql = "Delete FROM  $wpdb->options
            WHERE option_name LIKE '%transient_timeout_" . WE_LS_SLUG . $user_id ."%'";

    ws_ls_delete_cache($user_id . '-' . WE_LS_CACHE_KEY_START_WEIGHT);

    $wpdb->query($sql);
  }
}
function ws_ls_delete_all_cache()
{
  global $wpdb;

  if (WE_LS_CACHE_ENABLED){

    $sql = "Delete FROM  $wpdb->options
            WHERE option_name LIKE '%transient_" . WE_LS_SLUG ."%'";

    $wpdb->query($sql);

    $sql = "Delete FROM  $wpdb->options
            WHERE option_name LIKE '%transient_timeout_" . WE_LS_SLUG ."%'";

    $wpdb->query($sql);
  }
}
function ws_ls_generate_cache_key($key){
    return WE_LS_SLUG . $key;
}
