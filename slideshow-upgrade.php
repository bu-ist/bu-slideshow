<?php

add_action('init', 'bu_slideshow_upgrade', 99);

/**
 * Run any appropriate upgrade routines for the current version &
 * update version number in site options
 */
function bu_slideshow_upgrade() {
	$current_version = get_option('bu_slideshow_version');
	$run_all = ( FALSE === $current_version && version_compare('2.3', BU_SLIDESHOW_VERSION, '<=') );

	if ( $run_all || -1 === version_compare($current_version, BU_SLIDESHOW_VERSION ) ) {

		if( $run_all || -1 === version_compare($current_version, '2.3' ) ){
			bu_slideshow_migrate_shows();
		}

		update_option('bu_slideshow_version', BU_SLIDESHOW_VERSION);
	}
// 	$all_slideshows = get_option( 'bu_slideshows' , array() );
	
// 	foreach ($all_slideshows as $k => $show) {
// print_r(serialize($show)."\n\n\n");
// 	}
}

/**
 *  Move slideshows to individual site options
 */
function bu_slideshow_migrate_shows() {
	$all_slideshows = get_option( 'bu_slideshows' , array() );
	$has_errors = FALSE;
	$slideshow_id_map_option = 'bu_slideshow_id_map';

	foreach ($all_slideshows as $k => $show) {

		if( ! intval( $show->id ) ){
			$has_errors = TRUE;
			error_log( sprintf( '[%s] Invalid Slideshow ID: %d Object: %s',
				__FUNCTION__, $show->id, var_export( $show, TRUE ) ) );
			continue;
		}

		// Create post object
		$slideshow = array(
			'post_title'	=> $show->name,
			'post_content'	=> '',
			'post_status'	=> 'publish',
			'post_type'		=> 'bu_slideshow',
		);

		$result = $postID = wp_insert_post( $slideshow );

		if( is_wp_error( $result ) ) {
		    error_log( sprintf( '[%s] Error creating post: %s Object: %s',
				__FUNCTION__, $result->get_error_message(), var_export( $show, TRUE ) ) );
		    $has_errors = TRUE;
		} else {
			if( false === add_post_meta( $postID, '_bu_slideshow', $show, true ) ){
				error_log( sprintf( '[%s] Error adding posst meta: ID %d Object: %s',
					__FUNCTION__, $postID, var_export( $show, TRUE ) ) );
				$has_errors = TRUE;
			}
		}

		if( !$has_errors ){
			$map = get_option( $slideshow_id_map_option );

			if( FALSE === $map ){
				$map = array();
				add_option( $slideshow_id_map_option, $map, '', false );
			}
			
			$map[ $show->id ] = $postID;
			
			update_option( $slideshow_id_map_option, $map );
		}
	}

	// if( ! $has_errors ){
	// 	delete_option( 'bu_slideshows' );
	// }
}