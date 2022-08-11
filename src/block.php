<?php
/**
 * BU Slideshow Block
 *
 * @package BU_Slideshow
 */

namespace BU\Plugins\Slideshow;

/**
 * Fetches the slidshow posts to choose from as the source for the block.
 *
 * @return array Array of slideshow posts.
 */
function get_slideshow_posts() {

	$slideshow_ids = get_posts(
		array(
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'post_type'      => 'bu_slideshow',
		)
	);

	$slideshows = array_map(
		function( $slideshow_id ) {
			$slideshow_meta = get_post_meta( $slideshow_id, '_bu_slideshow' );

			return array(
				'id'   => $slideshow_id,
				'name' => $slideshow_meta[0]->name,
			);
		},
		$slideshow_ids
	);

	return $slideshows;
}

/**
 * Dynamic render callback for the slideshow block
 *
 * @param array $attributes Block attributes.
 */
function slideshow_block_render_callback( $attributes ) {
	$slideshow_id = empty( $attributes['slideshowId'] ) ? 0 : $attributes['slideshowId'];

	if ( empty( $slideshow_id ) ) {
		return '<div>No valid slideshow ID</div>';
	}

	// Setup parameters for slideshow rendering.
	$args = array(
		'show_id'    => $slideshow_id,
		'transition' => empty( $attributes['transition'] ) ? 'slide' : $attributes['transition'],
		'show_nav'   => array_key_exists( 'showNav', $attributes ) ? 0 : 1,
		'nav_style'  => array_key_exists( 'navStyle', $attributes ) ? $attributes['navStyle'] : 'icon',
		'autoplay'   => array_key_exists( 'autoPlay', $attributes ) ? 0 : 1,
		'shuffle'    => array_key_exists( 'shuffle', $attributes ) ? true : false,
	);

	// Render the slideshow with the shortcode handler.
	return \BU_Slideshow::shortcode_handler( $args );
}

/**
 * Registers the block
 */
function slideshow_block_init() {

	// Load dependencies.
	$asset_file = include plugin_dir_path( __FILE__ ) . '/../build/index.asset.php';

	wp_register_script(
		'slideshow-block',
		plugins_url( '/../build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	register_block_type(
		'bu-slideshow/slideshow-block',
		array(
			'api_version'     => 2,
			'editor_script'   => 'slideshow-block',
			'render_callback' => __NAMESPACE__ . '\slideshow_block_render_callback',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\slideshow_block_init' );

/**
 * Add REST endpoints for parents query and block preview.
 */
add_action(
	'rest_api_init',
	function() {
		// Endpoint for parent posts.
		register_rest_route(
			'bu-slideshow/v1',
			'/shows/',
			array(
				'methods'             => 'GET',
				'callback'            => __NAMESPACE__ . '\get_slideshow_posts',
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				},
			)
		);
	}
);
