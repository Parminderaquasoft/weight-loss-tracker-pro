<?php
	defined('ABSPATH') or die('Jog on!');

function ws_ls_admin_delete_entry_callback()
{
  $ajax_response = 0;

  check_ajax_referer( 'ajax-security-nonce', 'security' ); //TODO: Add back in!

  $user_id = ws_ls_ajax_post_value('user-id');
  $row_id = ws_ls_ajax_post_value('row-id');

  if(true == ws_ls_delete_entry($user_id, $row_id)){
    $ajax_response = 1;
  }
  echo $ajax_response;
	wp_die();
}
add_action( 'wp_ajax_ws_ls_admin_delete_entry', 'ws_ls_admin_delete_entry_callback' );

function ws_ls_user_data_callback()
{
  $ajax_response = 0;
	$filters = array();

  //check_ajax_referer( 'ws-ls-nonce', 'security' ); //TODO: Add back in!

	$draw_id = ws_ls_ajax_get_value('draw', true);
	$table_columns = ws_ls_ajax_get_value('columns');

	// Defaults
	$filters['start'] = ws_ls_ajax_get_value('start', true, 0);
	$filters['limit'] = ws_ls_ajax_get_value('length', true, 10);

	// Sort specified?
	$sort = ws_ls_ajax_get_value('order');
	if (is_array($sort)){
		$filters['sort-order'] = $sort[0]['dir'];
		$filters['sort-column'] = $table_columns[$sort[0]['column'][0]]['name'];
	} else {
			$filters['sort-order'] = 'desc';
			$filters['sort-column'] = 'weight_date';
	}

	// Search?
	$search = ws_ls_ajax_get_value('search');
	if (is_array($search)){
		$filters['search'] = $search['value'];
	}

	echo ws_ls_load_json($draw_id, $filters);

	wp_die();
}
add_action( 'wp_ajax_ws_ls_user_data', 'ws_ls_user_data_callback' );
add_action( 'wp_ajax_nopriv_ws_ls_user_data', 'ws_ls_user_data_callback' ); //TODO: REMOVE

function ws_ls_load_json($draw_id, $filters)
{
	$data_from_db = ws_ls_user_data($filters);

	$data['draw'] = $draw_id;
	$data['recordsTotal'] = 0;
	$data['recordsFiltered'] = 0;
	$data['data'] = array();

	if ($data_from_db != false) {

		$data['recordsTotal'] = ws_ls_user_data_count();
		$data['recordsFiltered'] = $data_from_db['count'];

		$delete_image = plugins_url( '../css/images/delete.png', __FILE__ );

		foreach ($data_from_db['weight_data'] as $weight) {
			array_push($data['data'], array($weight['user_nicename'], ws_ls_render_date($weight), $weight['display'], $weight['notes'], '<img src="' . $delete_image . '" width="15" height="15" border="0"  class="ws-ls-admin-delete-weight" id="ws-ls-delete-row-' . $weight['db_row_id'] . '" data-user-id="' . $weight['user_id'] . '" data-row-id="' . $weight['db_row_id'] . '" />', 'row-id' => $weight['db_row_id'], 'user-id' => $weight['user_id'] ));
		}
	}

	return json_encode($data);
}

function ws_ls_ajax_get_value($key, $force_to_int = false, $default = false)
{
		$return_value = NULL;

    if(isset($_GET[$key]) && $force_to_int) {
        return intval($_GET[$key]);
    }
    elseif(isset($_GET[$key])) {
    	return $_GET[$key];
    }

		// Use default if aval
		if ($default && is_null($return_value)) {
			$return_value = $default;
		}
    return $return_value;
}






















function ws_ls_sample_data()
{
	return '
		{
		  "draw": 1,
		  "recordsTotal": 57,
		  "recordsFiltered": 57,
		  "data": [
		    [
		      "Airi",
		      "Satou",
		      "Accountant",
		      "Tokyo",
		      "28th Nov 08",
		      "$162,700"
		    ],
		    [
		      "Angelica",
		      "Ramos",
		      "Chief Executive Officer (CEO)",
		      "London",
		      "9th Oct 09",
		      "$1,200,000"
		    ],
		    [
		      "Ashton",
		      "Cox",
		      "Junior Technical Author",
		      "San Francisco",
		      "12th Jan 09",
		      "$86,000"
		    ],
		    [
		      "Bradley",
		      "Greer",
		      "Software Engineer",
		      "London",
		      "13th Oct 12",
		      "$132,000"
		    ],
		    [
		      "Brenden",
		      "Wagner",
		      "Software Engineer",
		      "San Francisco",
		      "7th Jun 11",
		      "$206,850"
		    ],
		    [
		      "Brielle",
		      "Williamson",
		      "Integration Specialist",
		      "New York",
		      "2nd Dec 12",
		      "$372,000"
		    ],
		    [
		      "Bruno",
		      "Nash",
		      "Software Engineer",
		      "London",
		      "3rd May 11",
		      "$163,500"
		    ],
		    [
		      "Caesar",
		      "Vance",
		      "Pre-Sales Support",
		      "New York",
		      "12th Dec 11",
		      "$106,450"
		    ],
		    [
		      "Cara",
		      "Stevens",
		      "Sales Assistant",
		      "New York",
		      "6th Dec 11",
		      "$145,600"
		    ],
		    [
		      "Cedric",
		      "Kelly",
		      "Senior Javascript Developer",
		      "Edinburgh",
		      "29th Mar 12",
		      "$433,060"
		    ]
		  ]
		}

	';
}
