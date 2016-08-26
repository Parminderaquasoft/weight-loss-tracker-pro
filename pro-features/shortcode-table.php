<?php

defined('ABSPATH') or die("Jog on!");

function ws_ls_shortcode_table($user_defined_arguments)
{
    if(!WS_LS_IS_PRO) {
       return false;
    }
  
    $table_arguments = shortcode_atts(
    array(
        'user-id' => get_current_user_id()
     ), $user_defined_arguments );
  
    // Fetch data for chart
    $weight_data = ws_ls_get_weights($table_arguments['user-id']);

    // Render chart
    if ($weight_data){
        return ws_ls_advanced_data_table($weight_data);
    } else {
        return '<p>' . __('No weight data was found for the specified user.', WE_LS_SLUG) . '</p>';
    }
}
