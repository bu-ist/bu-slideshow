<?php

add_action('init', 'bu_slideshow_upgrade');

/**
 * Check if shows need to be moved
 */

function bu_slideshow_upgrade() {
	$current_version = get_option('bu_slideshow_version');
	if (empty($current_version) || version_compare($current_version, BU_SLIDESHOW_VERSION, '<')) {
		if (version_compare($current_version, '2.3', '<') && version_compare('2.3', BU_SLIDESHOW_VERSION, '<=')) {
			bu_slideshow_migrate_shows();
		}
		update_option('bu_slideshow_version', BU_SLIDESHOW_VERSION);
	}
}


/**
 *  Move slideshows to individual site options
 */

function bu_slideshow_migrate_shows() {
	$all_slideshows = get_option( 'bu_slideshows' , array() );
	$has_errors = FALSE;

	foreach ($all_slideshows as $k => $show) {

		if( ! intval( $show->id ) ){
			$has_errors = TRUE;
			error_log( sprintf( '[%s] Invalid Slideshow ID: %d Object: %s',
				__FUNCTION__, $show->id, var_export( $show, TRUE ) ) );
			continue;
		}

		if ( FALSE === add_option( BU_Slideshow::$meta_key_prefix . $show->id , $show , '', 'no' ) ){
			$has_errors = TRUE;
		}

		// if( ! $has_errors ){
		// 	delete_option( 'bu_slideshows' );
		// }
	}
}