<?php
/**
 * Register Admin page for editing slideshows.
 *
 * @package BU_SlideShow
 */

namespace BU\Plugins\SlideShow;

// WordPress functions are in global namespace.
use function add_action;
use function add_menu_page;
use function add_submenu_page;
use function current_user_can;
use function get_current_screen;
use function plugin_dir_path;
use function plugins_url;
use function wp_enqueue_script;
use function wp_set_script_translations;

/**
 * Render the React admin page with a div that will be replaced by the React app.
 *
 * @return void
 */
function render_admin_page() {
	// Check if the user has the required capability to view this page.
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	// Include the form template.
	echo '<div id="bu-slideshow-admin-editor"></div>';
}

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Slideshow New Editor', 'bu_slideshow' ),
			__( 'Slideshow New Editor', 'bu_slideshow' ),
			'edit_posts',
			'bu_slideshow',
			__NAMESPACE__ . '\render_admin_page',
			'dashicons-format-gallery',
			20
		);

		add_submenu_page(
			'bu_slideshow',
			__( 'All Slideshows', 'bu_slideshow' ),
			__( 'All Slideshows', 'bu_slideshow' ),
			'edit_posts',
			'bu_slideshow',
			__NAMESPACE__ . '\render_admin_page'
		);

		add_submenu_page(
			'bu_slideshow',
			__( 'Add New Slideshow', 'bu_slideshow' ),
			__( 'Add New', 'bu_slideshow' ),
			'edit_posts',
			'bu_slideshow_new',
			__NAMESPACE__ . '\render_admin_page'
		);
	}
);

/**
 * Enqueue scripts and styles for the admin page.
 *
 * @return void
 */
function enqueue_admin_scripts() {
	$page = get_current_screen();
	if ( 'toplevel_page_bu_slideshow' !== $page->id ) {
		return;
	}

	$asset_file = include plugin_dir_path( __DIR__ ) . 'build/admin.asset.php';

	wp_enqueue_script(
		'bu-slideshow-admin',
		plugins_url( 'build/admin.js', __DIR__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	wp_set_script_translations(
		'bu-slideshow-admin',
		'bu-slideshow'
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_scripts' );
