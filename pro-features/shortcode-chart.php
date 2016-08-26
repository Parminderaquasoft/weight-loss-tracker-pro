<?php

defined('ABSPATH') or die("Jog on!");

function ws_ls_shortcode_chart($user_defined_arguments)
{
    if(!WS_LS_IS_PRO) {
       return false;
    }
    
    $chart_arguments = shortcode_atts(
        array(
            'user-id' => get_current_user_id(),
            'max-data-points' => WE_LS_CHART_MAX_POINTS,
            'type' => WE_LS_CHART_TYPE,
            'height' => WE_LS_CHART_HEIGHT,
            'width' => false,
            'weight-line-color' => WE_LS_WEIGHT_LINE_COLOUR,
            'weight-fill-color' => WE_LS_WEIGHT_FILL_COLOUR,
            'weight-target-color' => WE_LS_TARGET_LINE_COLOUR,
            'show-gridlines' => WE_LS_CHART_SHOW_GRID_LINES,
            'bezier' => WE_LS_CHART_BEZIER_CURVE
           ), $user_defined_arguments );

    // Tidy up a few configs
    $chart_arguments['bezier'] = ws_ls_force_bool_argument($chart_arguments['bezier']);
    $chart_arguments['show-gridlines'] = ws_ls_force_bool_argument($chart_arguments['show-gridlines']);

    // Validate height
    if (!is_numeric($chart_arguments['height']) || $chart_arguments['height'] < 50) {
       $chart_arguments['height'] = WE_LS_CHART_HEIGHT;
    }

    // Validate max points
    if (!is_numeric($chart_arguments['max-data-points']) || $chart_arguments['max-data-points'] < 2) {
       $chart_arguments['max-data-points'] = WE_LS_CHART_MAX_POINTS;
    }
    // Fetch data for chart
    $weight_data = ws_ls_get_weights($chart_arguments['user-id'], $chart_arguments['max-data-points'], -1, 'desc');

    // Reverse array so in cron order
    $weight_data = array_reverse($weight_data);
            
    // Render chart
    if ($weight_data){
        return ws_ls_display_chart($weight_data, $chart_arguments);
    } else {
        return '<p>' . __('No weight data was found for the specified user.', WE_LS_SLUG) . '</p>';
    }
}
