<?php
	defined('ABSPATH') or die('Jog on!');

  function ws_ls_advanced_data_table($weight_data)
  {
			$table_id = 'ws_ls_data_table_' . rand(10,1000) . '_' . rand(10,1000);

      $html_output = '
      <table id="' . $table_id . '" class="display ws-ls-advanced-data-table" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>' . __('Date', WE_LS_SLUG) .'</th>
          <th>' . __('Weight', WE_LS_SLUG) . '</th>
          <th>+/-</th>
          <th class="tablet-l">' . __('Notes', WE_LS_SLUG) . '</th>
          <th class="never"></th>
					<th>Options</th>
        </tr>
        </thead>
      <tbody>';

			$delete_image = plugins_url( '../css/images/delete.png', __FILE__ );
			$edit_image = plugins_url( '../css/images/edit.png', __FILE__ );

      foreach ($weight_data as $weight_object)
      {
                $html_output .= '<tr id="ws-ls-row-' . $weight_object['db_row_id'] . '">
                            <td>' . ws_ls_render_date($weight_object) . '</td>
                            <td>' . $weight_object['display'] . '</td>
                            <td>' . ((isset($weight_object['difference_from_start']) && is_numeric($weight_object['difference_from_start'])) ? $weight_object['difference_from_start'] . '%' : '') . '</td>
                            <td style="font-size:0.7em;word-wrap:break-word;">' . $weight_object['notes'] . '</td>
                            <td>' . $weight_object['kg'] . '</td>
														<td class="ws-ls-table-options">

																<img src="' . $edit_image .'" width="15" height="15" id="ws-ls-edit-' . $weight_object['db_row_id'] . '" data-row-id="' . $weight_object['db_row_id'] . '" class="ws-ls-edit-row" />
																<img src="' . $delete_image .'" width="15" height="15" id="ws-ls-delete-' . $weight_object['db_row_id'] . '" data-row-id="' . $weight_object['db_row_id'] . '" class="ws-ls-delete-row" />

														</td>
                          </tr>';
      }
      $html_output .= '</tbody>

      </table>';

      return $html_output;
  }
