<?php

defined('ABSPATH') or die('Jog on!');

function ws_ls_settings_page() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' , WE_LS_SLUG) );
	}

	$disable_if_not_pro_class = (WS_LS_IS_PRO) ? '' : 'ws-ls-disabled';

	wp_enqueue_script('jquery-tabs',plugins_url( '../js/tabs.min.js', __FILE__ ), array('jquery'), WE_LS_CURRENT_VERSION);
	wp_enqueue_style('wlt-tabs', plugins_url( '../css/tabs.min.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);
	wp_enqueue_style('wlt-tabs-flat', plugins_url( '../css/tabs.flat.min.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);
	wp_enqueue_script('ws-ls-admin',plugins_url( '../js/admin.js', __FILE__ ), array('jquery'), WE_LS_CURRENT_VERSION);
	wp_enqueue_style('ws-ls-admin-style', plugins_url( '../css/admin.css', __FILE__ ), array(), WE_LS_CURRENT_VERSION);

	$clear_cache = (isset($_GET['settings-updated']) && 'true' == $_GET['settings-updated']) ? true : false;

	if (is_admin() && isset($_GET['recreatetables']) && 'y' == $_GET['recreatetables']) {
		ws_ls_activate();
		$clear_cache = true;
	}

	if($clear_cache) {
			ws_ls_delete_all_cache();
	}


		?>
		<div class="wrap">

<?php

	$mysql_table_check = ws_ls_admin_check_mysql_tables_exist();

	if ($mysql_table_check != false): ?>
		<div class="error">
			<p><?php echo $mysql_table_check; ?></p>
 			<p><a href="<?php echo get_permalink() . '?page=ws-ls-weight-loss-tracker-main-menu';  ?>&amp;recreatetables=y"><?php echo __('Rebuild them now', WE_LS_SLUG); ?></a></p>
		</div>
	<?php
	endif;

?>


	<div id="icon-options-general" class="icon32"></div>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-3">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">


						<h3 class="hndle"><span><?php echo __( WE_LS_TITLE . ' Settings', WE_LS_SLUG); ?></span></h3>

						<div class="inside">

							<form method="post" action="options.php">
								<?php

									settings_fields( 'we-ls-options-group' );
									do_settings_sections( 'we-ls-options-group' );

								?>

								<div id="ws-ls-tabs">
									<ul>
											<li><a><?php echo __( 'General', WE_LS_SLUG); ?><span><?php echo __( 'General settings', WE_LS_SLUG); ?></span></a></li>
											<li><a><?php echo __( 'User Experience', WE_LS_SLUG); ?><span><?php echo __( "Settings that effect the user's overall experience", WE_LS_SLUG); ?></span></a></li>
											<li><a><?php echo __( 'Chart', WE_LS_SLUG); ?><span><?php echo __( 'Chart styling and config', WE_LS_SLUG); ?></span></a></li>
									</ul>
									<div>
										<div>
												<table class="form-table">
													<tr>
														<th scope="row"><?php echo __( 'Weight Units' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-units" name="ws-ls-units">
																<option value="kg" <?php selected( get_option('ws-ls-units'), 'kg' ); ?>><?php echo __('Kg', WE_LS_SLUG); ?></option>
																<option value="stones_pounds" <?php selected( get_option('ws-ls-units'), 'stones_pounds' ); ?>><?php echo __('Stones & Pounds', WE_LS_SLUG); ?></option>
																<option value="pounds_only" <?php selected( get_option('ws-ls-units'), 'pounds_only' ); ?>><?php echo __('Pounds', WE_LS_SLUG); ?></option>
															</select>
															<p><?php echo __('You can specify whether to display weights in Kg, Stones & Pounds or just Pounds. Please note: The graph will be displayed in Pounds if "Stones & Pounds" is selected.', WE_LS_SLUG);?></p>
														</td>
													</tr>

													<tr>
														<th scope="row"><?php echo __( 'UK or US Date format?' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-use-us-dates" name="ws-ls-use-us-dates">
																<option value="uk" <?php selected( get_option('ws-ls-use-us-dates'), 'uk' ); ?>><?php echo __('UK (DD/MM/YYYY)', WE_LS_SLUG); ?></option>
																<option value="us" <?php selected( get_option('ws-ls-use-us-dates'), 'us' ); ?>><?php echo __('US (MM/DD/YYYY)', WE_LS_SLUG); ?></option>
															</select>
															<p><?php echo __('Specify what format dates should be displayed in (i.e. UK or US format)', WE_LS_SLUG); ?></p>
														</td>
													</tr>
													<tr class="<?php echo $disable_if_not_pro_class; ?>">
														<th scope="row"><?php echo __( 'Allow decimal places?' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-allow-decimals" name="ws-ls-allow-decimals">
																<option value="yes" <?php selected( get_option('ws-ls-allow-decimals'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
																<option value="no" <?php selected( get_option('ws-ls-allow-decimals'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>

															</select>
															<p><?php echo __('If enabled, Decimal weight entries will be allowed in Kg or Pounds mode.', WE_LS_SLUG)?></p>
														</td>
													</tr>
													<tr>
														<th scope="row"><?php echo __( 'Disable plugin CSS?' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-disable-css" name="ws-ls-disable-css">
																<option value="no" <?php selected( get_option('ws-ls-disable-css'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>
																<option value="yes" <?php selected( get_option('ws-ls-disable-css'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
															</select>
															<p><?php echo __('If you wish to style the forms in your own way, you can use this option to disable WLT\'s style sheets.', WE_LS_SLUG)?></p>
														</td>
													</tr>
											</table>
										</div>
										<div>
											<table class="form-table">
												<tr>
													<th scope="row"><?php echo __( 'Allow target weights?' , WE_LS_SLUG); ?></th>
													<td>
														<select id="ws-ls-allow-targets" name="ws-ls-allow-targets">
															<option value="no" <?php selected( get_option('ws-ls-allow-targets'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG); ?></option>
															<option value="yes" <?php selected( get_option('ws-ls-allow-targets'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG); ?></option>
														</select>
														<p><?php echo __('If enabled, a user is allowed to enter a target weight. This will be displayed as a horizontal bar on the line chart.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
													<tr class="<?php echo $disable_if_not_pro_class; ?>">
														<th scope="row"><?php echo __( 'Allow user settings' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-allow-user-preferences" name="ws-ls-allow-user-preferences">
																<option value="yes" <?php selected( get_option('ws-ls-allow-user-preferences'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
																<option value="no" <?php selected( get_option('ws-ls-allow-user-preferences'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>
															</select>
															<p><?php echo __('Allow your users to select their own weight unit, date format and remove all their data.', WE_LS_SLUG)?></p>
														</td>
													</tr>
													<tr class="<?php echo $disable_if_not_pro_class; ?>">
															<th scope="row"><?php echo __( 'Advanced data tables?' , WE_LS_SLUG); ?></th>
															<td>
																<select id="ws-ls-allow-advanced-tables" name="ws-ls-allow-advanced-tables">
																	<option value="yes" <?php selected( get_option('ws-ls-allow-advanced-tables'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
																	<option value="no" <?php selected( get_option('ws-ls-allow-advanced-tables'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>
																</select>
																<p><?php echo __("User's weight history is presented in responsive tables that allow sorting, paging, editing and deleting. Weight Index (i.e. percentage lost / gained to be displayed.)", WE_LS_SLUG)?></p>
															</td>
														</tr>
													<tr>
														<th scope="row"><?php echo __( 'Display data in tabs?' , WE_LS_SLUG); ?></th>
														<td>
															<select id="ws-ls-use-tabs" name="ws-ls-use-tabs">
																<option value="no" <?php selected( get_option('ws-ls-use-tabs'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>
																<option value="yes" <?php selected( get_option('ws-ls-use-tabs'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
															</select>
															<p><?php echo __('If enabled, "Weight History" and "Target Weight" will be displayed on sepearate tabs.', WE_LS_SLUG)?></p>
														</td>
													</tr>
											</table>
										</div>
										<div>
											<table class="form-table">
												<tr  class="<?php echo $disable_if_not_pro_class; ?>">
													<th scope="row"><?php echo __( 'Default chart type', WE_LS_SLUG ); ?></th>
													<td>
														<select id="ws-ls-chart-type" name="ws-ls-chart-type">
															<option value="line" <?php selected( get_option('ws-ls-chart-type'), 'line' ); ?>><?php echo __('Line Chart', WE_LS_SLUG)?></option>
															<option value="bar" <?php selected( get_option('ws-ls-chart-type'), 'bar' ); ?>><?php echo __('Bar Chart', WE_LS_SLUG)?></option>

														</select>
														<p><?php echo __('If enabled, "Allows points and labels to be displayed on graph.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
													<tr  class="<?php echo $disable_if_not_pro_class; ?>">
														<th scope="row"><?php echo __( 'Bezier Curve (Line only)?', WE_LS_SLUG ); ?></th>
														<td>
															<select id="ws-ls-bezier-curve" name="ws-ls-bezier-curve">
																<option value="yes" <?php selected( get_option('ws-ls-bezier-curve'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
																<option value="no" <?php selected( get_option('ws-ls-bezier-curve'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>

															</select>
															<p><?php echo __('If enabled, lines between points on a line graph will be curved', WE_LS_SLUG); ?></p>
														</td>
													</tr>
													<tr  class="<?php echo $disable_if_not_pro_class; ?>" >
														<th scope="row"><?php echo __( 'Display gridlines?', WE_LS_SLUG ); ?></th>
														<td>
															<select id="ws-ls-grid-lines" name="ws-ls-grid-lines">
																<option value="yes" <?php selected( get_option('ws-ls-grid-lines'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
																<option value="no" <?php selected( get_option('ws-ls-grid-lines'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>

															</select>
															<p><?php echo __('If enabled, gridlines will be displayed on the Graph canvas.', WE_LS_SLUG); ?></p>
														</td>
													</tr>
												<tr  class="<?php echo $disable_if_not_pro_class; ?>">
													<th scope="row"><?php echo __( 'Display points on graph?', WE_LS_SLUG ); ?></th>
													<td>
														<select id="ws-ls-allow-points" name="ws-ls-allow-points">
															<option value="yes" <?php selected( get_option('ws-ls-allow-points'), 'yes' ); ?>><?php echo __('Yes', WE_LS_SLUG)?></option>
															<option value="no" <?php selected( get_option('ws-ls-allow-points'), 'no' ); ?>><?php echo __('No', WE_LS_SLUG)?></option>

														</select>
														<p><?php echo __('If enabled, "Allows points and labels to be displayed on graph.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
													<tr  class="<?php echo $disable_if_not_pro_class; ?>">
														<th scope="row"><?php echo __( 'Maximum points per graph', WE_LS_SLUG ); ?></th>
														<td>
															<?php $chart_options = array(5,10,25,50,100,200); ?>
															<select id="ws-ls-max-points" name="ws-ls-max-points">
																<?php foreach ($chart_options as $option):?>
																	<option value="<?php echo $option; ?>" <?php selected( WE_LS_CHART_MAX_POINTS, $option ); ?>><?php echo $option; ?></option>
																<?php endforeach; ?>

															</select>
															<p><?php echo __('If enabled, "Allows points and labels to be displayed on graph.', WE_LS_SLUG); ?></p>
														</td>
													</tr >
														<tr  class="<?php echo $disable_if_not_pro_class; ?>">
															<th scope="row" class="<?php echo $disable_if_not_pro_class; ?>"><?php echo __( 'Point thickness (Line only)', WE_LS_SLUG ); ?></th>
															<td>
																<?php $chart_options = array(1,2,3,4,5,6,7,8,9,10); ?>
																<select id="ws-ls-point-size" name="ws-ls-point-size">
																	<?php foreach ($chart_options as $option):?>
																		<option value="<?php echo $option; ?>" <?php selected( WE_LS_CHART_POINT_SIZE, $option ); ?>><?php echo $option; ?></option>
																	<?php endforeach; ?>

																</select>
																<p><?php echo __('Specifies the point thickness on a line chart.', WE_LS_SLUG); ?></p>
															</td>
														</tr>
												<tr>
													<th scope="row"><?php echo __( 'Weight colour?', WE_LS_SLUG ); ?></th>
													<td>
														<input id="ws-ls-line-colour" name="ws-ls-line-colour" type="color" value="<?php echo WE_LS_WEIGHT_LINE_COLOUR; ?>">
														<p><?php echo __('If enabled, enter a HEX colour code to use for the Weight history line on graph.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
												<tr>
													<th scope="row"><?php echo __( 'Weight fill colour?', WE_LS_SLUG ); ?></th>
													<td>
														<input id="ws-ls-line-fill-colour" name="ws-ls-line-fill-colour" type="color" value="<?php echo WE_LS_WEIGHT_FILL_COLOUR; ?>">
														<p><?php echo __('If enabled, enter a HEX colour code to use forthe shading underneath the Weight history line on graph. Line charts only.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
												<tr>
													<th scope="row"><?php echo __( 'Target line colour?', WE_LS_SLUG ); ?></th>
													<td>
														<input id="ws-ls-target-colour" name="ws-ls-target-colour" type="color" value="<?php echo WE_LS_TARGET_LINE_COLOUR; ?>">
														<p><?php echo __('If enabled, enter a HEX colour code to use for the Target line on graph.', WE_LS_SLUG); ?></p>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								<?php submit_button(); ?>



							</form>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

					<div class="postbox">
				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->

	<?php




}

function ws_ls_register_settings()
{
		register_setting( 'we-ls-options-group', 'ws-ls-units' );
  	register_setting( 'we-ls-options-group', 'ws-ls-allow-targets' );
  	register_setting( 'we-ls-options-group', 'ws-ls-allow-points' );
  	register_setting( 'we-ls-options-group', 'ws-ls-use-tabs' );
  	register_setting( 'we-ls-options-group', 'ws-ls-target-colour' );
  	register_setting( 'we-ls-options-group', 'ws-ls-line-fill-colour' );
  	register_setting( 'we-ls-options-group', 'ws-ls-line-colour' );
		register_setting( 'we-ls-options-group', 'ws-ls-use-us-dates' );
		register_setting( 'we-ls-options-group', 'ws-ls-disable-css' );

		// Pro only open
		if(WS_LS_IS_PRO){
			register_setting( 'we-ls-options-group', 'ws-ls-allow-user-preferences' );
			register_setting( 'we-ls-options-group', 'ws-ls-allow-decimals' );
			register_setting( 'we-ls-options-group', 'ws-ls-chart-type' );
			register_setting( 'we-ls-options-group', 'ws-ls-allow-advanced-tables' );
			register_setting( 'we-ls-options-group', 'ws-ls-max-points' );
			register_setting( 'we-ls-options-group', 'ws-ls-bezier-curve' );
			register_setting( 'we-ls-options-group', 'ws-ls-point-size' );
			register_setting( 'we-ls-options-group', 'ws-ls-grid-lines' );
		}

}
add_action( 'admin_init', 'ws_ls_register_settings' );
