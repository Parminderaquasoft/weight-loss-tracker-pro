<?php

defined('ABSPATH') or die('Naw ya dinnie!');

function ws_ls_clear_target_callback()
{
    $ajax_response = 0;

  check_ajax_referer( 'ws-ls-nonce', 'security' ); //TODO: Add back in!

  $user_id = ws_ls_ajax_post_value('user-id');
  
  if(true == ws_ls_delete_target($user_id)){
    $ajax_response = 1;
  }
  echo $ajax_response;
	wp_die();
}
add_action( 'wp_ajax_ws_ls_clear_target', 'ws_ls_clear_target_callback' );

function ws_ls_ajax_post_value($key, $json_decode = false)
{
    if(isset($_POST[$key]) && $json_decode) {
        return json_decode($_POST[$key]);
    }
    elseif(isset($_POST[$key])) {
    	return $_POST[$key];
    }

    return NULL;
}
