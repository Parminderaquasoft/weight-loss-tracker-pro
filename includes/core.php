<?php
	defined('ABSPATH') or die('Jog on!');

  /* Display weight data in a HTML table */
  function ws_ls_display_table($weight_data)
  {
    $html_output = '';

    $html_output .= '
    <table width="100%" class="ws-ls-data-table">
      <thead>
      <tr>
        <th width="25%">' . __('Date', WE_LS_SLUG) .'</th>
        <th width="25%">' . __('Weight', WE_LS_SLUG) . ' (' . ws_ls_get_unit() . ')</th>
        <th>' . __('Notes', WE_LS_SLUG) . '</th>
      </tr>
      </thead>
    <tbody>';

    foreach ($weight_data as $weight_object)
    {
        $html_output .= '<tr>
                          <td>' . ws_ls_render_date($weight_object) . '</td>
                          <td>' . $weight_object['display'] . '</td>
                          <td>' . $weight_object['notes'] . '</td>
                        </tr>';
    }

    $html_output .= '<tbody></table>';

    return $html_output;
  }

  /* Display Chart */
	function ws_ls_display_chart($weight_data, $options = false)
	{
        // Build the default arguments for a chart. This can then be overrided by what is being passed in (i.e. to support shortcode arguments)
		$chart_config = array(
			'user-id' => get_current_user_id(),
			'type' => WE_LS_CHART_TYPE,
			'height' => WE_LS_CHART_HEIGHT,
            'width' => false,
			'weight-line-color' => WE_LS_WEIGHT_LINE_COLOUR,
			'weight-fill-color' => WE_LS_WEIGHT_FILL_COLOUR,
			'weight-target-color' => WE_LS_TARGET_LINE_COLOUR,
			'show-gridlines' => WE_LS_CHART_SHOW_GRID_LINES,
			'bezier' => WE_LS_CHART_BEZIER_CURVE,
            'hide_login_message_if_needed' => true
		);

        // If we are PRO and the developer has specified options then override the default
        if($options && WS_LS_IS_PRO){
            $chart_config = wp_parse_args( $options, $chart_config );
        }
        
        // Make sure they are logged in
        if (!is_user_logged_in())	{
            if ($chart_config['hide_login_message_if_needed']) {
                return '<blockquote class="ws-ls-blockquote"><p>' .	__('You need to be logged in to record your weight.', WE_LS_SLUG) . ' <a href="' . wp_login_url(get_permalink()) . '">' . __('Login now', WE_LS_SLUG) . '</a>.</p></blockquote>';    
            } else {
                return;
            }       
        }

		$chart_id = 'ws_ls_chart_' . rand(10,1000) . '_' . rand(10,1000);

		// If Pro disabled then force to line
		if(!WS_LS_IS_PRO) {
			$chart_config['type'] = 'line';
		}

		$y_axis_unit = (ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS')) ? __('lbs', WE_LS_SLUG) : __('Kg', WE_LS_SLUG) ;

		// Build graph data
		$graph_data['labels'] = array();
		$graph_data['datasets'][0] = array(  'label' =>  __('Weight', WE_LS_SLUG),
											 'fillColor' => $chart_config['weight-fill-color'],
											 'strokeColor' => $chart_config['weight-line-color'],
											 'pointColor' => $chart_config['weight-line-color'],
											 'pointStrokeColor' => '#fff',
											 'pointHighlightFill' => '#fff',
											 'pointHighlightStroke' => 'rgba(220,220,220,1)'
								            );
		$graph_data['datasets'][0]['data'] = array();

		$target_weight = ws_ls_get_user_target($chart_config['user-id']);

		$chart_type_supports_target_data = ('bar' == $chart_config['type']) ? false : true;

		// If target weights are enabled, then include into javascript data object
		if ($target_weight != false && WE_LS_ALLOW_TARGET_WEIGHTS && $chart_type_supports_target_data){
				$graph_data['datasets'][1] = array( 'label' =>  __('Target', WE_LS_SLUG),
												    'fillColor' => 'rgba(255,255,255,0.2)',
													'strokeColor' => $chart_config['weight-target-color'],
													'pointColor' => $chart_config['weight-target-color'],
													'pointStrokeColor' => '#fff',
													'pointHighlightFill' => '#fff',
													'pointHighlightStroke' => 'rgba(220,220,220,1)'
												);
				$graph_data['datasets'][1]['data'] = array();
		}

		foreach ($weight_data as $weight_object) {
			array_push($graph_data['labels'], $weight_object['date-graph']);
			array_push($graph_data['datasets'][0]['data'], $weight_object['graph_value']);

			// Set target weight if specified
			if ($target_weight != false && WE_LS_ALLOW_TARGET_WEIGHTS && $chart_type_supports_target_data){
				array_push($graph_data['datasets'][1]['data'], $target_weight['graph_value']);
			}

		}

		// Embed JavaScript data object for this graph into page
		wp_localize_script( 'jquery-chart-ws-ls', $chart_id . '_data', $graph_data );

		$graph_line_options = array();

		// Build the Chart options for JS library depending on type of Chart
		if('bar' == $chart_config['type'])	{

				$graph_line_options = array(
			  	'scaleBeginAtZero' => true,
					'scaleShowGridLines' => (($chart_config['show-gridlines']) ? 'true' : ''),
					'scaleGridLineColor:' => 'rgba(0,0,0,.05)',
					'scaleGridLineWidth:' => 1,
					'scaleShowHorizontalLines' => true,
					'scaleShowVerticalLines' => true,
					'barShowStroke' => true,
					'multiTooltipTemplate' => '"<%= datasetLabel %> - <%= value %> ' . $y_axis_unit . '"',
					'scaleLabel' => '&nbsp;<%= value%>',
					'barStrokeWidth' => 2,
					'barValueSpacing' => 1,
					'barDatasetSpacing' => 1,
					'responsive' => true
				);
		}
		elseif ('line' == $chart_config['type']) {

			$graph_line_options = array(
		  	'scaleShowGridLines' => (($chart_config['show-gridlines']) ? 'true' : ''),
				'scaleGridLineColor' => 'rgba(0,0,0,.05)',
				'scaleGridLineWidth' => 1,
				'bezierCurve' => (($chart_config['bezier'] == true) ? 'true' : ''),
				'bezierCurveTension' => 0.4,
				'pointDot' =>  ((WE_LS_ALLOW_POINTS) ? 'true' : ''),
				'pointDotRadius' => WE_LS_CHART_POINT_SIZE,
				'pointDotStrokeWidth' => 1,
				'pointHitDetectionRadius' => 5,
				'datasetStroke' => true,
				'datasetStrokeWidth' => 2,
				'datasetFill' => true,
				'responsive' => true,
				'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %> ' . $y_axis_unit . '',
				'scaleLabel' => '&nbsp;<%= value%>',
				'graphTitle' => '',
				'graphTitleFontFamily' => 'Arial',
				'graphTitleFontSize' => 24,
				'graphTitleFontStyle' => 'bold',
				'graphTitleFontColor' => '#666'
			);

		}

		// Embed JavaScript options object for this graph into page
		wp_localize_script( 'jquery-chart-ws-ls', $chart_id . '_options', $graph_line_options );

		$html = '<div><canvas id="' . $chart_id . '" class="ws-ls-chart" ' . (($chart_config['width']) ? 'width="'.  $chart_config['width'] . '" ' : '') . ' ' . (($chart_config['height']) ? 'height="'.  $chart_config['height'] . '" ' : '') . ' " data-chart-type="' . $chart_config['type']  . '" data-target-weight="' . $target_weight['graph_value'] . '" data-target-colour="' . $chart_config['weight-target-color'] . '"></canvas>';
		$html .= '<div class="ws-ls-notice-of-refresh ws-ls-reload-page-if-clicked ws-ls-hide"><a href="#">' . __('You have modified data. Please refresh page.', WE_LS_SLUG) . '</a></div>';
		$html .= '</div>';
		return $html;
	}
/*

	Displays either a target or weight form

*/
function ws_ls_display_weight_form($target_form = false, $class_name = false, $user_id = false, $hide_titles = false, 
                                        $form_number = false, $force_to_todays_date = false, $hide_login_message_if_needed = true)
{
    global $save_response;
    $html_output  = '';

    // Make sure they are logged in
    if (!is_user_logged_in())	{
        if ($hide_login_message_if_needed) {
            return '<blockquote class="ws-ls-blockquote"><p>' .	__('You need to be logged in to record your weight.', WE_LS_SLUG) . ' <a href="' . wp_login_url(get_permalink()) . '">' . __('Login now', WE_LS_SLUG) . '</a>.</p></blockquote>';    
        } else {
            return;
        }       
    }
    
    if(false == $user_id){
        $user_id = get_current_user_id();
    }

    $form_id = 'ws_ls_form_' . rand(10,1000) . '_' . rand(10,1000);
		$form_class = ' ws_ls_display_form';

	// Set title / validator
    if (!$hide_titles)
    {
        if($target_form)	{
            $html_output .= '<h3 class="ws_ls_title">' . __('Target Weight', WE_LS_SLUG) . '</h3>';
        }else {
            $html_output .= '<h3 class="ws_ls_title">' . __('Add a new weight', WE_LS_SLUG) . '</h3>';
        }
    }

	// If a form was previously submitted then display resulting message!
	if ($form_number && !empty($save_response) && $save_response['form_number'] == $form_number){
		$html_output .= $save_response['message'];
	}

	$html_output .= '
	<form action="' .  get_permalink() . '" method="post" class="we-ls-weight-form we-ls-weight-form-validate' . $form_class . (($class_name) ? ' ' . $class_name : '') . '" id="' . $form_id . '" data-is-target-form="' . (($target_form) ? 'true' : 'false') . '" data-metric-unit="' . ws_ls_get_chosen_weight_unit_as_string() . '">
		<input type="hidden" value="' . (($target_form) ? 'true' : 'false') . '" id="ws_ls_is_target" name="ws_ls_is_target" />
		<input type="hidden" value="true" id="ws_ls_is_weight_form" name="ws_ls_is_weight_form" />
    <input type="hidden" value="' . $user_id . '" id="ws_ls_user_id" name="ws_ls_user_id" />
		<input type="hidden" value="' . wp_hash($user_id) . '" id="ws_ls_security" name="ws_ls_security" />';

		if($form_number){
				$html_output .= '	<input type="hidden" value="' . $form_number . '" id="ws_ls_form_number" name="ws_ls_form_number" />';
		}

		$html_output .= '<div class="ws-ls-inner-form comment-input">
			<div class="ws-ls-error-summary">
				<ul></ul>
			</div>
		';

			// If not a target form include date
			if (!$target_form) {

				$default_date = date("d/m/Y");

				// Overide if US
				if (ws_ls_get_config('WE_LS_US_DATE')) {
					$default_date = date("m/d/Y");
				}

				if(false == $force_to_todays_date){
					$html_output .= '<input type="text" name="we-ls-date" tabindex="' . ws_ls_get_next_tab_index() . '" id="we-ls-date-' . $form_id . '" value="' . $default_date . '" placeholder="' . $default_date . '" size="22" tabindex="1" class="we-ls-datepicker">';
				} else {
					$html_output .= '<input type="hidden" name="we-ls-date" value="' . $default_date . '">';
				}

			} else {

				$target_weight = ws_ls_get_user_target($user_id);

				if ($target_weight['display'] != '') {
					$html_output .= '<p>' . __('Your target weight is', WE_LS_SLUG) . ' <strong>' . $target_weight['display'] . '</strong>.</p>';
				}
			}

			// Display the relevant weight fields depending on weight unit selected
			if(ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS'))
			{
				if (ws_ls_get_config('WE_LS_DATA_UNITS') == 'stones_pounds') {
					$html_output .= '<input  type="number"  tabindex="' . ws_ls_get_next_tab_index() . '" step="any" min="0" name="we-ls-weight-stones" id="we-ls-weight-stones" value="" placeholder="' . __('Stones', WE_LS_SLUG) . '" size="11" tabindex="2" >';
					$html_output .= '<input  type="number" tabindex="' . ws_ls_get_next_tab_index() . '" step="any" min="0" max="14" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11" tabindex="3" >';
				}
				else {
					$html_output .= '<input  type="number" tabindex="' . ws_ls_get_next_tab_index() . '" step="any" min="1" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11" tabindex="3" >';
				}
			}
			else {
				$html_output .= '<input  type="number" tabindex="' . ws_ls_get_next_tab_index() . '" step="any" min="1" name="we-ls-weight-kg" id="we-ls-weight-kg" value="" placeholder="' . __('Weight', WE_LS_SLUG) . ' (' . __('Kg', WE_LS_SLUG) . ')" size="22" tabindex="2">';
			}

		$html_output .= '</div>';

		// Display notes section if not target form
		if (!$target_form) {
			$html_output .= '<div id="comment-textarea">
												<textarea name="we-ls-notes" tabindex="' . ws_ls_get_next_tab_index() . '" id="we-ls-notes" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="' . __('Notes', WE_LS_SLUG) . '"></textarea>
											</div>';
		}


		$button_text = ($target_form) ?  __('Set Target', WE_LS_SLUG) :  __('Save Entry', WE_LS_SLUG);

			$html_output .= '<div id="comment-submit-container">
			<p>
				<div>
					<input name="submit_button" type="submit" id="we-ls-submit"  tabindex="' . ws_ls_get_next_tab_index() . '" value="' . $button_text . '" class="comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat" />';
            
                //If a target form, display "Clear Target" button
                if ($target_form){
                    $html_output .= '&nbsp;<button name="ws-ls-clear-target" id="ws-ls-clear-target" type="button" tabindex="' . ws_ls_get_next_tab_index() . '" class="ws-ls-clear-target comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat" >' . __('Clear Target', WE_LS_SLUG) . '</button>';
                }
    
                $html_output .= '</div>
			</p>
		</div>
	</form>';


	return $html_output;

}
function ws_ls_convert_date_to_iso($date)
{
	if (ws_ls_get_config('WE_LS_US_DATE')) {
		list($month,$day,$year) = sscanf($date, "%d/%d/%d");
		$date = "$year-$month-$day";
	} else {
		list($day,$month,$year) = sscanf($date, "%d/%d/%d");
		$date = "$year-$month-$day";
	}

	return $date;
}


function ws_ls_capture_form_validate_and_save($user_id = false)
{
    if(false == $user_id){
        $user_id = get_current_user_id();
    }

	$allowed_post_keys = array('ws_ls_is_target', 'we-ls-date', 'we-ls-weight-pounds',
															'we-ls-weight-stones', 'we-ls-weight-kg', 'we-ls-notes');

	// Strip slashes from post object
	$form_values = stripslashes_deep($_POST);

	// Target form?
	$is_target_form = ('true' == $form_values['ws_ls_is_target']) ? true : false;

	// Remove invalid post keys
	foreach ($form_values as $key => $value) {
		if(!in_array($key, $allowed_post_keys)) {
			unset($form_values[$key]);
		}	elseif ('we-ls-date' == $key)	{
			// Convert date to ISO
			$form_values[$key] = ws_ls_convert_date_to_iso($form_values[$key]);
		}
	}

	$weight_object = false;
	$weight_notes = (!$is_target_form) ? $form_values['we-ls-notes'] : '' ;
	$weight_date = (!$is_target_form) ? $form_values['we-ls-date'] : false ;

	switch (ws_ls_get_config('WE_LS_DATA_UNITS')) {
			case 'pounds_only':
					$weight_object = ws_ls_weight_object($user_id, 0, 0, 0, $form_values['we-ls-weight-pounds'], $weight_notes,	$weight_date, true);
				break;
			case 'kg':
					$weight_object = ws_ls_weight_object($user_id, $form_values['we-ls-weight-kg'], 0, 0, 0, $weight_notes,	$weight_date, true);
				break;
			default:
					$weight_object = ws_ls_weight_object($user_id, 0, $form_values['we-ls-weight-pounds'], $form_values['we-ls-weight-stones'], 0, $weight_notes,	$weight_date, true);
				break;
	}

	$result = ws_ls_save_data($user_id, $weight_object, $is_target_form);
    
    ws_ls_delete_cache_for_given_user($user_id);
    
    return $result;
}

function ws_ls_validate_weight_data($weight_object, $is_target_form = false)
{
    if(is_numeric($weight_object['only_pounds']) &&
        is_numeric($weight_object['kg']) &&
          is_numeric($weight_object['stones']) &&
            is_numeric($weight_object['pounds']))
            {
              return true;
            }
		return false;
}

function ws_ls_get_chosen_weight_unit_as_string(){

	$use_imperial_weights = ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS');

	if($use_imperial_weights && 'stones_pounds' == ws_ls_get_config('WE_LS_DATA_UNITS'))	{
		return 'imperial-both';
	}
	elseif($use_imperial_weights && 'pounds_only' == ws_ls_get_config('WE_LS_DATA_UNITS'))	{
		return 'imperial-pounds';
	}
	else	{
		 return 'metric';
	}
}
function ws_ls_get_js_config()
{
	$message_for_pounds = (ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS') && 'stones_pounds' == ws_ls_get_config('WE_LS_DATA_UNITS')) ? __('Please enter a between 0-14 for pounds', WE_LS_SLUG) : __('Please enter a valid figure for pounds', WE_LS_SLUG);

	$use_us_date = ws_ls_get_config('WE_LS_US_DATE');

	return array (
		'us-date' => ($use_us_date) ? 'true' : 'false',
		'date-format' => ($use_us_date) ? 'mm/dd/yy' : 'dd/mm/yy',
        'clear-target' => __('Are you sure you wish to clear your target weight?', WE_LS_SLUG),
		'validation-we-ls-weight-pounds' => $message_for_pounds,
		'validation-we-ls-weight-kg' => __('Please enter a valid figure for Kg', WE_LS_SLUG),
		'validation-we-ls-weight-stones' => __('Please enter a valid figure for Stones', WE_LS_SLUG),
		'validation-we-ls-date' => __('Please enter a valid date', WE_LS_SLUG),
		'validation-we-ls-history' => __('Please confirm you wish to delete ALL your weight history', WE_LS_SLUG),
        'confirmation-delete' => __('Are you sure you wish to delete this entry? If so, press OK.', WE_LS_SLUG),
		'tabs-enabled' => (WE_LS_USE_TABS) ? 'true' : 'false',
		'advanced-tables-enabled' => (WS_LS_ADVANCED_TABLES && WS_LS_IS_PRO) ? 'true' : 'false',
		'ajax-url' => admin_url('admin-ajax.php'),
		'ajax-security-nonce' => wp_create_nonce( 'ws-ls-nonce' ),
		'is-pro' => (WS_LS_IS_PRO) ? 'true' : 'false',
		'user-id' => get_current_user_id(),
		'current-url' => get_permalink()
	);

}

function ws_ls_get_next_tab_index()
{
	global $ws_ls_tab_index;

	$current_index = $ws_ls_tab_index;
	$ws_ls_tab_index++;

	return $current_index;

}
