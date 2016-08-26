<?php

defined('ABSPATH') or die('Jog on!');

function ws_ls_help_page() {
		?>
		<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-3">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">
						<h3 class="hndle"><span><?php echo __( 'Help', WE_LS_SLUG); ?> </span></h3>
						<div style="padding: 0px 15px 0px 15px">
							<p><?php echo __( 'You can find detailed help with this plugin at our Github Wiki:', WE_LS_SLUG ); ?></p>
                            <p><a href="https://github.com/yekenuk/Weight-Loss-Tracker/wiki" target="_blank">https://github.com/yekenuk/Weight-Loss-Tracker/wiki</a></p>
						</div>
					</div>
                    
                    <div class="postbox">
						<h3 class="hndle"><span><?php echo __( 'Our road map', WE_LS_SLUG); ?> </span></h3>
						<div style="padding: 0px 15px 0px 15px">
							<p><?php echo __( 'Interested in the future development of this plugin? Then have a peek at our road map:', WE_LS_SLUG ); ?></p>
                            <p><a href="https://github.com/yekenuk/Weight-Loss-Tracker/wiki/Road-map" target="_blank">https://github.com/yekenuk/Weight-Loss-Tracker/wiki/Road-map</a></p>
						</div>
					</div>
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
?>
