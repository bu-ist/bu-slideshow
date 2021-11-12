<?php
/**
 * BU Slideshow Block
 *
 * @package BU_Slideshow
 */

namespace BU\Plugins\Slideshow;

/**
 * Dynamic render callback for the slideshow block
 */
function slideshow_block_render_callback() {
	return '<div>block content</div>';
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
