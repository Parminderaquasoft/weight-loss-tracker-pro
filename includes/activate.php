<?php

	defined('ABSPATH') or die("Jog on!");


	function ws_ls_activate()
	{
		$debug_values = true;

		global $wpdb;

   	$table_name = $wpdb->prefix . WE_LS_TABLENAME;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  weight_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  weight_user_id integer NOT NULL,
		  weight_weight float NOT NULL,
		  weight_stones float NOT NULL,
		  weight_pounds float NOT NULL,
		  weight_only_pounds float NOT NULL,
		  weight_notes text null,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

		$sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  weight_user_id integer NOT NULL,
			  target_weight_weight float NOT NULL,
			  target_weight_stones float NOT NULL,
			  target_weight_pounds float NOT NULL,
			  target_weight_only_pounds float NOT NULL,
			  UNIQUE KEY id (id)
		) $charset_collate;";

		 dbDelta( $sql );

		 $table_name = $wpdb->prefix . WE_LS_USER_PREFERENCES_TABLENAME;

		 $sql = "CREATE TABLE $table_name (
				 user_id integer NOT NULL,
				 settings text not null,
				 UNIQUE KEY user_id (user_id)
		 ) $charset_collate;";

			dbDelta( $sql );


	}

?>
