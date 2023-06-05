<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

//If the user is preserving the settings then don't delete them
$options = get_option( 'sby_settings' );
$sby_preserve_settings = isset( $options[ 'preserve_settings' ] ) ? $options[ 'preserve_settings' ] : false;

// allow the user to preserve their settings in case they are upgrading
if ( ! $sby_preserve_settings ) {

	// clear cron jobs
	wp_clear_scheduled_hook( 'sby_cron_job' );
	wp_clear_scheduled_hook( 'sby_feed_update' );

	// clean up options from the database
	delete_option( 'sby_settings' );
	delete_option( 'sby_channel_ids' );
	delete_option( 'sby_channel_status' );
	delete_option( 'sby_cron_report' );
	delete_option( 'sby_errors' );
	delete_option( 'sby_ajax_status' );
	delete_option( 'sby_db_version' );
	delete_option( 'sby_statuses' );
	delete_option( 'sby_rating_notice' );
	delete_option( 'sby_newuser_notifications' );
	delete_option( 'sby_usage_tracking_config' );

	// delete role
	global $wp_roles;
	$wp_roles->remove_cap( 'administrator', 'manage_youtube_feed_options' );

	// delete all custom post type data
	global $wpdb;

	$youtube_ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type = 'sby_videos';" );

	$id_string = implode( ', ', $youtube_ids );
	if ( ! empty( $id_string ) ) {
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE post_id IN ($id_string);" );
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'sby_videos';" );
	}

	// delete transients and backup data
	$table_name = $wpdb->prefix . "options";
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\!sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\~sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_&sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_&sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_\$sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_\$sby\_%')
        " );

	//delete image resizing related things
	$posts_table_name       = $wpdb->prefix . 'sby_items';
	$feeds_posts_table_name = esc_sql( $wpdb->prefix . 'sby_items_feeds' );

	$upload                 = wp_upload_dir();
	$wpdb->query( "DROP TABLE IF EXISTS $posts_table_name" );
	$wpdb->query( "DROP TABLE IF EXISTS $feeds_posts_table_name" );

	$locator_table_name = $wpdb->prefix . SBY_FEED_LOCATOR;
	$wpdb->query( "DROP TABLE IF EXISTS $locator_table_name" );

	$image_files = glob( trailingslashit( $upload['basedir'] ) . trailingslashit( 'sby-local-media' ) . '*' ); // get all file names
	foreach ( $image_files as $file ) { // iterate files
		if ( is_file( $file ) ) {
			unlink( $file );
		} // delete file
	}

	global $wp_filesystem;

	$wp_filesystem->delete( trailingslashit( $upload['basedir'] ) . trailingslashit( 'sby-local-media' ) , true );
}


