<?php

defined('ABSPATH') or die('Jog on!');

function ws_ls_advertise_pro() {

	global $pro_features;

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$yeken_data = ws_ls_get_data_from_yeken();

	$price = ($yeken_data && isset($yeken_data->price)) ? $yeken_data->price : WS_LS_PRO_PRICE;

		?>

		<div class="wrap">

			<div id="icon-options-general" class="icon32"></div>
					<div id="poststuff">

				<div id="post-body" class="metabox-holder columns-2">

					<!-- main content -->
					<div id="post-body-content">

						<div class="meta-box-sortables ui-sortable">

							<div class="postbox">

								<div class="handlediv" title="Click to toggle"><br></div>
								<!-- Toggle -->

								<h3 class="hndle"><span><?php echo __('Get ' . WE_LS_TITLE . ' Pro!', WE_LS_SLUG); ?></span>
								</h3>

								<div class="inside">
									<p><?php echo __('Get Weight Loss Tracker Pro for', WE_LS_SLUG) . ' &pound;' . $price .  __(' and have the following features listed below.', WE_LS_SLUG); ?> </p>
                                    <center>
                                        <h3><?php echo __('In case you need, your <strong>Site Hash</strong> is', WE_LS_SLUG); ?>: <?php echo ws_ls_generate_site_hash(); ?></h3>
										<a href="https://www.yeken.uk/weight-loss-tracker-upgrade-to-pro-version/?hash=<?php echo ws_ls_generate_site_hash(); ?>" style="width:60%;font-size:15px;text-align:center;" target="_blank" class="button-primary"><?php echo __('Upgrade now for', WE_LS_SLUG); ?> &pound;<?php echo $price; ?></a>
									</center>
								</div>
								<!-- .inside -->

							</div>
							<!-- .postbox -->

						</div>
						<!-- .meta-box-sortables .ui-sortable -->

					</div>

					<!-- sidebar -->
					<div id="postbox-container-1" class="postbox-container">

						<div class="meta-box-sortables">

							<div class="postbox">

								<div class="handlediv" title="Click to toggle"><br></div>
								<!-- Toggle -->

								<h3 class="hndle"><span><?php echo __('Enter License Key', WE_LS_SLUG); ?></span></h3>
								<?php

								$display_form = true;

								if(ws_ls_has_a_valid_license()){
									$display_form = false;
								}
								elseif (isset($_GET['add-license']) && 'true' == $_GET['add-license'] && !empty($_POST['license-key'])){

									$entered_license = $_POST['license-key'];
									$valid_license = ws_ls_is_validate_license($entered_license);

									if ($valid_license) {
										$display_form = false;
                                        ws_ls_delete_all_cache();
									}
								}

								?>
								<div class="inside">
									<?php if ($display_form): ?>
									<form action="<?php echo admin_url('admin.php?page=ws-ls-weight-loss-tracker-pro&add-license=true'); ?>" method="post">
										<p><?php echo __('Got an existing license key? If so, enter it below', WE_LS_SLUG); ?>. <?php echo __('In case you need, your <strong>Site Hash</strong>', WE_LS_SLUG); ?>: <?php echo ws_ls_generate_site_hash(); ?></p>
										<input type="text" name="license-key" class="large-text" placeholder="<?php echo __('Enter license key', WE_LS_SLUG); ?>" />
										<br /><br />
										<input type="submit" class="button-primary large-text" value="<?php echo __('Add License', WE_LS_SLUG); ?>" />
									</form>
								<?php else: ?>
									<p>Thank you! Your license key for future reference is: <br /><br /><strong><?php echo ws_ls_get_license(); ?></strong></p>

								<?php endif; ?>
								</div>
								<!-- .inside -->

							</div>
							<!-- .postbox -->

						</div>
						<!-- .meta-box-sortables -->

					</div>
					<!-- #postbox-container-1 .postbox-container -->

					<!-- post-body-content -->
					<div id="post-body" class="metabox-holder columns-3">

						<!-- main content -->
						<div id="post-body-content">

							<div class="meta-box-sortables ui-sortable">

								<div class="postbox">

									<div class="handlediv" title="Click to toggle"><br></div>
									<!-- Toggle -->

									<h3 class="hndle"><span><?php echo __('Features of Pro version', WE_LS_SLUG); ?></span></h3>
									<div style="padding: 0px 15px 0px 15px">

									<div class="inside">
										<p><?php echo __('Below is a list of the intended features of the Pro version:', WE_LS_SLUG); ?></p>
										<table class="form-table" >
											<?php

											$class = '';

											foreach ($pro_features as $feature) {

												$class = ('alternate' == $class) ? '' : 'alternate';

												?>
												<tr valign="top" class="<?php echo $class; ?>">
													<td scope="row" style="padding-left:30px"><label for="tablecell">
														&middot; <?php echo $feature; ?>
													</label></td>

												</tr>

												<?php
											}
											?>


										</table>
									</div>
									<!-- .inside -->

								</div>
								<!-- .postbox -->

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

<?php } ?>
