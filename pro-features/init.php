<?php

defined('ABSPATH') or die("Jog on!");

// Include relevant files
if(defined('WS_LS_ABSPATH')){
  include WS_LS_ABSPATH . 'pro-features/user-preferences.php';
  include WS_LS_ABSPATH . 'pro-features/ajax-handler-public.php';
  include WS_LS_ABSPATH . 'pro-features/shortcode-chart.php';
  include WS_LS_ABSPATH . 'pro-features/shortcode-form.php';
  include WS_LS_ABSPATH . 'pro-features/shortcode-table.php';
  include WS_LS_ABSPATH . 'pro-features/advanced-table.php';
  include WS_LS_ABSPATH . 'pro-features/widget-chart.php';
  include WS_LS_ABSPATH . 'pro-features/widget-form.php';
  include WS_LS_ABSPATH . 'pro-features/user-data.php';
  include WS_LS_ABSPATH . 'pro-features/user-data-ajax.php';
  include WS_LS_ABSPATH . 'pro-features/db.php';
}

// Register shortcodes
function ws_ls_register_pro_shortcodes(){

    /*
        [weight-loss-tracker-chart] - Displays a chart
        [weight-loss-tracker-form] - Displays a form
        [weight-loss-tracker-table] - Displays a data table
    */

    add_shortcode( 'weight-loss-tracker-chart', 'ws_ls_shortcode_chart' );
    add_shortcode( 'weight-loss-tracker-form', 'ws_ls_shortcode_form' );
    add_shortcode( 'weight-loss-tracker-table', 'ws_ls_shortcode_table' );
}
add_action( 'init', 'ws_ls_register_pro_shortcodes');

function ws_ls_enqeue_pro_scripts(){

  if(WS_LS_ADVANCED_TABLES) {
    wp_enqueue_script('ws-ls-datatables-responsive', 'https://cdn.datatables.net/r/dt/dt-1.10.9,r-1.0.7/datatables.min.js', array('jquery'), WE_LS_CURRENT_VERSION);
    wp_enqueue_style('ws-ls-datatables-responsive', 'https://cdn.datatables.net/r/dt/dt-1.10.9,r-1.0.7/datatables.min.css', array(), WE_LS_CURRENT_VERSION);
    wp_enqueue_script('ws-ls-datatables-moment', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js', array('jquery'), WE_LS_CURRENT_VERSION);
    wp_enqueue_script('ws-ls-datatables-moment-date', '//cdn.datatables.net/plug-ins/1.10.7/sorting/datetime-moment.js', array('jquery'), WE_LS_CURRENT_VERSION);
  }
}
add_action( 'wp_enqueue_scripts', 'ws_ls_enqeue_pro_scripts');

function ws_ls_admin_enqeue_pro_scripts(){

    wp_enqueue_script('ws-ls-datatables-responsive', 'https://cdn.datatables.net/r/dt/dt-1.10.9,r-1.0.7/datatables.min.js', array('jquery'), WE_LS_CURRENT_VERSION);
    wp_enqueue_style('ws-ls-datatables-responsive', 'https://cdn.datatables.net/r/dt/dt-1.10.9,r-1.0.7/datatables.min.css', array(), WE_LS_CURRENT_VERSION);
    wp_enqueue_script('ws-ls-datatables-moment', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js', array('jquery'), WE_LS_CURRENT_VERSION);
    wp_enqueue_script('ws-ls-datatables-moment-date', '//cdn.datatables.net/plug-ins/1.10.7/sorting/datetime-moment.js', array('jquery'), WE_LS_CURRENT_VERSION);
}
add_action( 'admin_enqueue_scripts', 'ws_ls_admin_enqeue_pro_scripts');

function we_ls_register_widgets()
{
    register_widget( 'ws_ls_widget_chart' );
    register_widget( 'ws_ls_widget_form' );
}
add_action( 'after_setup_theme', 'we_ls_register_widgets', 20 );
