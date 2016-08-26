<?php
defined('ABSPATH') or die("Jog on!");

function ws_ls_user_preferences_form()
{
	$html_output = ws_ls_title(__('Settings', WE_LS_SLUG));

	$html_output .= '<form action="' .  get_permalink() . '" class="ws-ls-user-pref-form" method="post">
  <input type="hidden" name="ws-ls-user-pref" value="true" />
	<input type="hidden" name="ws-ls-user-pref-redirect" value="' . get_the_ID() . '" />
  <label>' . __('Which unit would you like to record your weight in:', WE_LS_SLUG) . '</label>
    <select id="WE_LS_DATA_UNITS" name="WE_LS_DATA_UNITS"  tabindex="' . ws_ls_get_next_tab_index() . '">
      <option value="kg" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'kg', false ) . '>' . __('Kg', WE_LS_SLUG) . '</option>
      <option value="stones_pounds" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'stones_pounds', false ) . '>' . __('Stones & Pounds', WE_LS_SLUG) . '</option>
      <option value="pounds_only" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'pounds_only', false ) . '>' . __('Pounds', WE_LS_SLUG) . '</option>
    </select>
    <label>' . __('Display dates in the following formats:', WE_LS_SLUG) . '</label>

    <select id="WE_LS_US_DATE" name="WE_LS_US_DATE"  tabindex="' . ws_ls_get_next_tab_index() . '">
      <option value="false" ' . selected( ws_ls_get_config('WE_LS_US_DATE'), false, false ) . '>' . __('UK (DD/MM/YYYY)', WE_LS_SLUG) . '</option>
      <option value="true" ' . selected( ws_ls_get_config('WE_LS_US_DATE'), true, false ) . '>' . __('US (MM/DD/YYYY)', WE_LS_SLUG) . '</option>
    </select>
  <input name="submit_button" type="submit" id="we-ls-user-pref-submit"  tabindex="' . ws_ls_get_next_tab_index() . '" value="' .  __('Save Settings', WE_LS_SLUG) . '" class="comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat">
</form><br />
' . ws_ls_title(__('Delete existing data', WE_LS_SLUG)) . '
<form action="' .  get_permalink() . '?user-delete-all=true" class="ws-ls-user-delete-all" method="post">
<div class="ws-ls-error-summary">
	<ul></ul>
</div>
	<input type="hidden" name="ws-ls-user-delete-all" value="true" />
	<label for="ws-ls-delete-all">' . __('The button below allows you to clear your existing weight history. Confirm:', WE_LS_SLUG) . '</label>
	<select id="ws-ls-delete-all" name="ws-ls-delete-all"  tabindex="' . ws_ls_get_next_tab_index() . '" required>
		<option value=""></option>
		<option value="true">DELETE ALL DATA</option>
	</select>
	<input name="submit_button" type="submit" tabindex="' . ws_ls_get_next_tab_index() . '" value="' .  __('Delete', WE_LS_SLUG) . '" class="comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat">
</form>
  ';
	return $html_output;
}
