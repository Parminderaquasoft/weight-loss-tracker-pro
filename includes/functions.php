<?php
defined('ABSPATH') or die("Jog on!");

/*
Date Interval class doesn't exist before PHP 4.3.
*/
function ws_ls_is_date_intervals_enabled()	{
  return class_exists('DateInterval');
}

/* Get string representation of a weight  */
function ws_ls_weight_object($user_id, $kg, $pounds, $stones, $pounds_only, $notes = '', $date = false,
                              $detect_and_convert_missing_values = false, $database_row_id = false, $user_nicename = '')
{
    $weight['display'] = '';
    $weight['user_id'] = $user_id;
    $weight['user_nicename'] = $user_nicename;
    $weight['only_pounds'] = $pounds_only;
    $weight['kg'] = $kg;
    $weight['stones'] = $stones;
    $weight['pounds'] = $pounds;
    $weight['graph_value'] = 0;
    $weight['notes'] = esc_html($notes);
    $weight['first_weight'] = false;
    $weight['difference_from_unit'] = '';
    $weight['db_row_id'] = $database_row_id;

    // Build different date formats
    if($date != false && !empty($date)) {

        $time = strtotime($date);
        $weight['date'] = $date;
        $weight['date-uk'] = date('d/m/Y',$time);
        $weight['date-us'] = date('m/d/Y',$time);
        $weight['date-graph'] = date('d M',$time);
    }

    // If enabled, detect which weight fields need to be calculated and do it
    if ($detect_and_convert_missing_values)
    {
      switch (ws_ls_get_config('WE_LS_DATA_UNITS')) {
    			case 'pounds_only':

        			$weight['kg'] = ws_ls_pounds_to_kg($weight['only_pounds']);
              $conversion = ws_ls_pounds_to_stone_pounds($weight['only_pounds']);
              $weight['stones'] = $conversion['stones'];
              $weight['pounds'] = $conversion['pounds'];
            break;
    			case 'kg':
    				  $weight['only_pounds'] = ws_ls_to_lb($weight['kg']);
              $conversion = ws_ls_to_stone_pounds($weight['kg']);
              $weight['stones'] = $conversion['stones'];
              $weight['pounds'] = $conversion['pounds'];
    				break;
    			default:
    					$weight['kg'] = ws_ls_to_kg($weight['stones'], $weight['pounds']);
              $weight['only_pounds'] = ws_ls_stones_pounds_to_pounds_only($weight['stones'], $weight['pounds']);
    				break;
    	}
    }

    // If not allowing decimals, then round figures down
    if(!WS_LS_USE_DECIMALS){
      $weight = ws_ls_round_weights($weight);
    }

    // Generate display
    switch (ws_ls_get_config('WE_LS_DATA_UNITS')) {
      case 'pounds_only':
        $data = ws_ls_round_decimals($weight['only_pounds']);
        $weight['display'] = $data . __('lbs', WE_LS_SLUG);
        $weight['graph_value'] = $data;
        break;
      case 'kg':
        $weight['display'] = $weight['kg'] . __('Kg', WE_LS_SLUG);
        $weight['graph_value'] = $weight['kg'];
        break;
      default:
        $weight['display'] = $weight['stones'] . __('St', WE_LS_SLUG) . " " . $pounds . __('lbs', WE_LS_SLUG);
        $weight['graph_value'] = ($weight['stones'] * 14) + $weight['pounds'];
        break;
  }

  if ($weight['user_id']) {

    // Generate weight index
    $weight['user_id'] = $user_id;

    $weight['first_weight'] = ws_ls_get_start_weight($weight['user_id']);
    if(is_numeric($weight['first_weight']) && $weight['first_weight'] > 0 && $weight['first_weight'] <> $weight['kg']) {
      $weight['difference_from_start'] = (($weight['kg'] - $weight['first_weight']) / $weight['first_weight']) * 100;
      $weight['difference_from_start'] = round($weight['difference_from_start']);
    }

    // Get user display name
    // $user_info = get_userdata($weight['user_id']);
    //
    // if($user_info) {
    //   $weight['user_nicename'] = $user_info->user_nicename;
    // }

  }
  return $weight;
}



/* Delete ALL existing user data */
function ws_ls_delete_existing_data() {
    if(is_admin())  {
        global $wpdb;
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . WE_LS_TARGETS_TABLENAME);
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . WE_LS_TABLENAME);
    }
}

/* Delete all data for a user */
function ws_ls_delete_data_for_user() {
    
    if(WE_LS_ALLOW_USER_PREFERENCES)  {

        $user_id = get_current_user_id();
        global $wpdb;
        // Delete user targets
        ws_ls_delete_target($user_id);

        // Delete weight history
        $table_name =  $wpdb->prefix . WE_LS_TABLENAME;
        $sql = $wpdb->prepare("Delete from $table_name where weight_user_id = %d", $user_id);
        $wpdb->query($sql);

        ws_ls_delete_cache_for_given_user($user_id);
    }
}

/* Admin tool to check the relevant tables exist for this plugin */
function ws_ls_admin_check_mysql_tables_exist()
{
    $error_text = '';
    global $wpdb;

    // Check Target table exists!
    $rows = $wpdb->get_row('Show columns in ' . $wpdb->prefix . WE_LS_TARGETS_TABLENAME);
    if (0 == count($rows)) {
        $error_text .= '<li>' . $wpdb->prefix . WE_LS_TARGETS_TABLENAME . '</li>';
    }
    // Check main data table exists!
    $rows = $wpdb->get_row('Show columns in ' . $wpdb->prefix . WE_LS_TABLENAME);
    if (0 == count($rows)) {
        $error_text .= '<li>' . $wpdb->prefix . WE_LS_TABLENAME . '</li>';
    }

    // Check user_preferences exists!
    $rows = $wpdb->get_row('Show columns in ' . $wpdb->prefix . WE_LS_USER_PREFERENCES_TABLENAME);
    if (0 == count($rows)) {
        $error_text .= '<li>' . $wpdb->prefix . WE_LS_USER_PREFERENCES_TABLENAME . '</li>';
    }

    // Return error message if tables missing
    if (!empty($error_text))  {
        return  __('The following MySQL tables are missing for this plugin', WE_LS_SLUG) . ':<ul>' . $error_text . '</ul>';
    }
    return false;
}

/*
  Used to display a jQuery dialog box in the admin panel
*/
function ws_ls_create_dialog_jquery_code($title, $message, $class_used_to_prompt_confirmation, $js_call = false)
{
  	global $wp_scripts;
  	$queryui = $wp_scripts->query('jquery-ui-core');
  	$url = "//ajax.googleapis.com/ajax/libs/jqueryui/".$queryui->ver."/themes/smoothness/jquery-ui.css";
  	wp_enqueue_script( 'jquery-ui-dialog' );
  	wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
    $id_hash = md5($title . $message . $class_used_to_prompt_confirmation);

    ?>
    <div id='<?php echo $id_hash; ?>' title='<?php echo $title; ?>'>
      <p><?php echo $message; ?></p>
    </div>
     <script>
          jQuery(function($) {
            var $info = $('#<?php echo $id_hash; ?>');
            $info.dialog({
                'dialogClass'   : 'wp-dialog',
                'modal'         : true,
                'autoOpen'      : false
            });
            $('.<?php echo $class_used_to_prompt_confirmation; ?>').click(function(event) {
                event.preventDefault();
                target_url = $(this).attr('href');
                var $info = $('#<?php echo $id_hash; ?>');
                $info.dialog({
                    'dialogClass'   : 'wp-dialog',
                    'modal'         : true,
                    'autoOpen'      : false,
                    'closeOnEscape' : true,
                    'buttons'       : {
                        'Yes': function() {

                            <?php if ($js_call != false): ?>
                                <?php echo $js_call; ?>

                                $(this).dialog('close');
                            <?php else: ?>
                                window.location.href = target_url;
                            <?php endif; ?>
                        },
                         'No': function() {
                            $(this).dialog('close');
                        }
                    }
                });
                $info.dialog('open');
            });

        });




      </script>

  <?php
}
function ws_ls_render_date($weight_object)
{
  // Return US date if enabled otherwise return UK date
  if(ws_ls_get_config('WE_LS_US_DATE')) {
    return $weight_object['date-us'];
  }
  else {
    return $weight_object['date-uk'];
  }

}
function ws_ls_get_unit()
{

  switch (ws_ls_get_config('WE_LS_DATA_UNITS')) {
    case 'pounds_only':
      $unit = __("lbs", WE_LS_SLUG);
      break;
    case 'kg':
      $unit = __("Kg", WE_LS_SLUG);
      break;
    default:
      $unit = __("St", WE_LS_SLUG) . " " . __("lbs", WE_LS_SLUG);
      break;
  }

  return $unit;
}
function ws_ls_get_week_ranges()
{
  $entered_date_ranges = ws_ls_get_min_max_dates(get_current_user_id());

  if ($entered_date_ranges != false)  {

    // Get min and max dates for weight entries
    $start_date = new DateTime($entered_date_ranges->min_date);
    $end_date = new DateTime($entered_date_ranges->max_date);

    // Grab all the weekly intervals between those dates
    $interval = new DateInterval('P1W');
    $daterange = new DatePeriod($start_date, $interval ,$end_date);

    $date_ranges = array();

    $i = 1;

    // Build an easy to use array
    foreach($daterange as $date){

      $end_of_week = clone $date;
      $end_of_week = date_modify($end_of_week, '+1 week' );

        $date_ranges[$i] =  array("start" => $date->format("Y-m-d"), "end" => $end_of_week->format("Y-m-d") );

        $i++;
    }

    return $date_ranges;
  }

  return false;
}
function ws_ls_get_date_format()
{
  if(ws_ls_get_config('WE_LS_US_DATE')){
    return 'm/d/Y';
  }

  return 'd/m/Y';
}
function ws_ls_display_week_filters($week_ranges, $selected_week_number)
{
  $output = '';

  // If we have valid options for week dropdown, start building it
  if ($week_ranges != false && count($week_ranges > 1))	{

    $output .= '<form action="' .  get_permalink() . '#wlt-weight-history" method="post">
                  <input type="hidden" value="true" name="week_filter">
                    <div class="ws_ls_week_controls">
                      <select name="week_number" onchange="this.form.submit()" class="ws-ls-select">
                        <option value="-1" ' . (($selected_week_number == -1) ?  ' selected="selected"' : '') . '>'. __('View all weeks', WE_LS_SLUG) . '</option>';

    // Loop through each weekly option and build drop down
    foreach ($week_ranges as $key => $week) {

      $date_format = ws_ls_get_date_format();

      $start_date = new DateTime($week['start']);
      $start_date = $start_date->format($date_format);

      $end_date = new DateTime($week['end']);
      $end_date = $end_date->format($date_format);

      $output .= '<option value="' . $key . '" ' . (($selected_week_number == $key) ? ' selected="selected"' : '') . '>'
                  . __('View Week', WE_LS_SLUG) . ' ' . $key . ' - ' . $start_date . ' ' . __('to', WE_LS_SLUG) . ' ' . $end_date . '</option>';

    }

    $output .= '</select>
              </div>
            </form>';
  }

  return $output;

}

function ws_ls_round_weights($weight)
{
  $weight['only_pounds'] = round($weight['only_pounds']);
  $weight['kg'] = round($weight['kg']);
  $weight['stones'] = round($weight['stones']);
  $weight['pounds'] = round($weight['pounds']);

  if (is_numeric($weight['difference_from_start'])) {
    $weight['difference_from_start'] = round($weight['difference_from_start']);
  }

  return $weight;
}

function ws_ls_get_config($key)
{
  // If user preferences are enabled, then see if they specified
  if (WE_LS_ALLOW_USER_PREFERENCES && !is_admin())  {

    // Look to see if the user had a preference, if not, default to admin choice
    $user_preference = ws_ls_get_user_preference($key);

    if(is_null($user_preference)) {
      return constant($key);
    } else {
      return $user_preference;
    }

  }
  else {
    // Use admin default
    return constant($key);
  }
}
function ws_ls_get_user_preference($key, $user_id = false)
{
  if(false == $user_id){
    $user_id = get_current_user_id();
  }

  $user_preferences = ws_ls_get_user_preferences($user_id);

  if(array_key_exists($key, $user_preferences)){
    return $user_preferences[$key];
  }

  return NULL;
}
function ws_ls_string_to_bool($value)
{
  if('false' == $value) {
    return false;
  }
  elseif('true' == $value) {
    return true;
  }

  return $value;
}
function ws_ls_force_bool_argument($value)
{

    if (strtolower($value) == 'true' || (is_bool($value) === true && $value == true)) {
        return true;
    }

    return false;
}
function ws_ls_remove_non_numeric($text)
{
  if(!empty($text)){
    return preg_replace("/[^0-9]/", "", $text);
  }
  return $text;
}
function ws_ls_get_data_from_yeken()
{
  // Look up date from Yeken.uk
  $cache = ws_ls_get_cache(WE_LS_CACHE_KEY_YEKEN_JSON);

  // Return cache if found!
  if ($cache)   {
      return $cache;
  }
  $response = wp_remote_get(WE_LS_DATA_URL);

  if( is_array($response) ) {
    if (200 == $response['response']['code'] && !empty($response['body'])) {
      $data = json_decode($response['body']);
      ws_ls_set_cache(WE_LS_CACHE_KEY_YEKEN_JSON, $data, 3 * HOUR_IN_SECONDS);
      return $data;
    }
  }

  return false;
}
function ws_ls_fetch_elements_from_end_of_array($data, $number_to_grab)
{
    if (is_array($data) && count($data) > $number_to_grab) {
        $start = count($data) - $number_to_grab;
        $data = array_slice($data, $start, $number_to_grab);
    }
    
    return $data;
}
    

?>
