<?php
/*
Plugin Name: BU Slideshow
Plugin URI: http://developer.bu.edu/bu-slideshow/
Description: Allows for the creation and display of animated slideshows. Uses sequence.js.
Version: 2.3.13
Author: Boston University (IS&T)
Author URI: http://www.bu.edu/tech/
Requires at least: 3.5
Tested up to: 6.5.2

License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('BU_SLIDESHOW_VERSION', '2.3.13');

define('BU_SLIDESHOW_BASEDIR', plugin_dir_path(__FILE__));
define('BU_SLIDESHOW_BASEURL', plugin_dir_url(__FILE__));
//define('SCRIPT_DEBUG', true);
/*possible remove this definition - check usage
It was at least partly in use as the text domain parameter for translation function __(string, 'text-domain') but that usage throws an error in WP plugin checker and may prevent publication of the pligin*/
if (!defined('BU_SSHOW_LOCAL')) {
	define('BU_SSHOW_LOCAL', 'BU_Slideshow');
}

if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
	define('BU_SSHOW_MIN', '');
} else {

	define('BU_SSHOW_MIN', '');//.min
}
//define('BU_SSHOW_MIN', '');
require_once BU_SLIDESHOW_BASEDIR . 'class-bu-slideshow.php';
require_once BU_SLIDESHOW_BASEDIR . 'class-bu-slide.php';
require_once BU_SLIDESHOW_BASEDIR . 'slideshow-upgrade.php';

// Load block.
require_once BU_SLIDESHOW_BASEDIR . '/src/block.php';

class BU_Slideshow {
	static $wp_version;

	static $meta_key = 'bu_slideshows';
	static $show_id_meta_key = 'bu_slideshow_last_id';
	static $custom_thumb_size = 'bu-slideshow-thumb';
	static $post_support_slug = 'bu_slideshow';

	// explicitly add page_alt to allow slideshow ui with bu-versions
	static $supported_post_types = array('page', 'post', 'page_alt');// post types to support Add Slideshow button
	static $editor_screens = array(); // other screens on which to include Add Slideshow modal
	static $caption_positions = array(
		'Top Right'     => 'caption-top-right',
		'Top Center'    => 'caption-top-center',
		'Top Left'      => 'caption-top-left',
		'Middle Center' => 'caption-center-center',
		'Bottom Right'  => 'caption-bottom-right',
		'Bottom Center' => 'caption-bottom-center',
		'Bottom Left'   => 'caption-bottom-left'
	);
	static $slide_templates = array();

	static $manage_url  = 'admin.php?page=bu-slideshow';
	static $edit_url    = 'admin.php?page=bu-edit-slideshow';
	static $add_url     = 'admin.php?page=bu-add-slideshow';
	static $preview_url = 'admin.php?page=bu-preview-slideshow';
	static $min_cap     = 'edit_posts';

	static $shortcode_defaults = array(
		'show_id'          => 0,
		'show_nav'         => 1,
		'transition'       => 'slide',
		'nav_style'        => 'icon',
		'autoplay'         => 1,
		'show_arrows'      => 0,
		'transition_delay' => 5,
		'width'            => 'auto',
		'align'            => 'center',
		'shuffle'          => false
	);
	static $transitions = array('slide', 'fade'); // prepackaged transitions
	static $nav_styles = array('icon', 'number');

	static $image_mimes = array('jpg|jpeg|jpe', 'png', 'gif');
	//static $upload_error = 'That does not appear to be a valid image. Please upload a JPEG, PNG or GIF file.';

	static public function add_plugins_loaded_hook(){
		add_action('plugins_loaded', array(__CLASS__, 'init'));
	}

	static public function init() {
		global $pagenow;

		self::$wp_version = get_bloginfo('version');
		$upload_error = __('That does not appear to be a valid image. Please upload a JPEG, PNG or GIF file.', 'bu-slideshow');

		add_action('init', array(__CLASS__, 'register_cpt'), 6);
		add_action('init', array(__CLASS__, 'custom_thumb_size'));
		add_action('init', array(__CLASS__, 'add_post_support'),99);
		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_scripts_styles'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'public_scripts_styles'));
		add_action('media_buttons', array(__CLASS__, 'add_media_button'),99);
		add_action('admin_footer-post.php', array(__CLASS__, 'admin_footer'));
		add_action('admin_footer-post-new.php', array(__CLASS__, 'admin_footer'));

		// media upload/insert restrictions
		if ('media-upload.php' === $pagenow || 'async-upload.php' === $pagenow) {
			self::media_upload_custom();
		}
		add_action('media_upload_bu_slideshow', array(__CLASS__, 'handle_upload'));
		add_action('pre_get_posts', array(__CLASS__, 'media_library_filter'));
		add_filter('upload_file_glob', array(__CLASS__, 'flash_file_types')); // does not exist in 3.3+

		add_action('wp_ajax_bu_delete_slideshow', array(__CLASS__, 'delete_slideshow_ajax'));
		add_action('wp_ajax_bu_add_slide', array(__CLASS__, 'add_slide_ajax'));
		add_action('wp_ajax_bu_get_slide_thumb', array(__CLASS__, 'get_slide_thumb_ajax'));
		add_action('wp_ajax_bu_slideshow_get_url', array(__CLASS__, 'get_url'));

		add_shortcode('bu_slideshow', array(__CLASS__, 'shortcode_handler'));

	}

	static public function register_cpt(){
		$args = array(
			'labels'             => array(
				'name'               => __( 'Slideshows', 'bu-slideshow' ),
				'singular_name'      => __( 'Slideshow', 'bu-slideshow' ),
				'add_new'            => __( 'Add New', 'bu-slideshow' ),
				'add_new_item'       => __( 'Add New Slideshow', 'bu-slideshow' ),
				'edit_item'          => __( 'Edit Slideshow', 'bu-slideshow' ),
				'new_item'           => __( 'New Slideshow', 'bu-slideshow' ),
				'view_item'          => __( 'View Slideshow', 'bu-slideshow' ),
				'search_items'       => __( 'Search Slideshows', 'bu-slideshow' ),
				'not_found'          => __( 'No Slideshows found', 'bu-slideshow' ),
				'not_found_in_trash' => __( 'No Slideshows in the trash', 'bu-slideshow' ),
				'parent_item_colon'  => __( 'Parent Slideshows:', 'bu-slideshow' ),
				'menu_name'          => __( 'Slideshows', 'bu-slideshow' ),
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'show_in_rest'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => false,
			'can_export'         => true,
		);

		register_post_type( 'bu_slideshow', $args );
	}

	static public function add_post_support() {
		$post_types = apply_filters('bu_slideshow_supported_post_types', self::$supported_post_types);

		if (!is_array($post_types)) {
			$post_types = array();
		}

		foreach ($post_types as $pt) {
			add_post_type_support($pt, self::$post_support_slug);
		}
	}

	/**
	 * Loads admin scripts/styles on plugin's pages. Add a page's id using by hooking
	 * the bu_slideshow_selector_pages filter to load the selector scripts/styles.
	 *
	 * @global type $current_screen
	 */
	static public function admin_scripts_styles() {
		global $current_screen;

		if (self::using_editor()) {
			self::selector_scripts_styles();
		}

		self::admin_scripts();

		/* preview page needs public scripts/styles */
		if ($current_screen->id === 'admin_page_bu-preview-slideshow') {
			self::public_scripts_styles();
		}
	}

	/**
	 * Admin scripts, for older and newer jQuery.
	 */
	static public function admin_scripts() {
		global $current_screen;
		$admin_pages = array(
			'toplevel_page_bu-slideshow',
			'slideshows_page_bu-slideshow',
			'admin_page_bu-edit-slideshow',
			'slideshows_page_bu-add-slideshow'
		);

		$js_url = BU_SLIDESHOW_BASEURL . 'interface/js/';

		if (in_array($current_screen->id, $admin_pages) || self::using_editor()) {
			wp_enqueue_script('bu-modal', $js_url . 'bu-modal/bu-modal' . BU_SSHOW_MIN . '.js', array('jquery'), BU_SLIDESHOW_VERSION, false);
			wp_enqueue_style('bu-modal', $js_url . 'bu-modal/css/bu-modal.css');
			wp_register_script('bu-slideshow-admin', $js_url . 'bu-slideshow-admin' . BU_SSHOW_MIN . '.js', array('jquery', 'bu-modal'), BU_SLIDESHOW_VERSION, true);

			wp_enqueue_script('media-upload');
			wp_enqueue_script('bu-slideshow-admin');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('thickbox');

			self::localize('bu-slideshow-admin');

			wp_register_style('bu-slideshow-admin', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow-admin' . BU_SSHOW_MIN . '.css', array(), BU_SLIDESHOW_VERSION);
			wp_enqueue_style('bu-slideshow-admin');
			wp_enqueue_style('thickbox');
		}

		/* enqueue new media uploader stuff */
		if ( ($current_screen->id === 'admin_page_bu-edit-slideshow' || $current_screen->id === 'slideshows_page_bu-add-slideshow')
				&& function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}

	/**
	 * Prepares styles and scripts for front end. Scripts are registered here and enqueued in shortcode handler
	 * (or in WP < 3.3, printed in footer; see conditional_script_load()).
	 *
	 * Define BU_SLIDESHOW_CUSTOM_CSS in a theme to prevent default CSS from loading. You will
	 * need to supply your own CSS transitions in this case.
	 */
	static public function public_scripts_styles() {

		self::public_scripts();

		if (!defined('BU_SLIDESHOW_CUSTOM_CSS') || !BU_SLIDESHOW_CUSTOM_CSS) {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow' . BU_SSHOW_MIN . '.css', array(), BU_SLIDESHOW_VERSION);
			wp_enqueue_style('bu-slideshow');
		}

		/* enqueue public styles on preview page */
		global $current_screen;
		if ($current_screen && $current_screen->id === 'admin_page_bu-preview-slideshow') {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow' . BU_SSHOW_MIN . '.css', array(), BU_SLIDESHOW_VERSION);
			wp_enqueue_style('bu-slideshow');
		}
	}

	/**
	 * Front end scripts, for older and newer jQuery. For jQuery < 1.71, patches jQuery 'on' to support sequence.js
	 */
	static public function public_scripts() {
		$js_url = BU_SLIDESHOW_BASEURL . 'interface/js/';

		$seq_deps = array('jquery');
		$slideshow_deps = array('jquery','jquery-sequence');

		wp_register_script('jquery-sequence', BU_SLIDESHOW_BASEURL . 'interface/js/vendor/sequence/sequence.jquery' . BU_SSHOW_MIN . '.js', $seq_deps, BU_SLIDESHOW_VERSION, true);
		wp_register_script('bu-slideshow', $js_url . 'bu-slideshow' . BU_SSHOW_MIN . '.js', $slideshow_deps, BU_SLIDESHOW_VERSION, true);
	}

	/**
	 * Load scripts and styles for the selector UI
	 */
	static public function selector_scripts_styles() {
		wp_register_script('bu-slideshow-selector', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow-selector' . BU_SSHOW_MIN . '.js', array('jquery'), BU_SLIDESHOW_VERSION, true);

		wp_enqueue_script('bu-slideshow-selector');

		wp_register_style('bu-slideshow-selector', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow-selector' . BU_SSHOW_MIN . '.css', array(), BU_SLIDESHOW_VERSION);
		wp_enqueue_style('bu-slideshow-selector');

		self::localize('bu-slideshow-selector');
	}

	/**
	 * Localize text in javascript
	 * @param string $script
	 */
	static public function localize($script = '') {
		switch($script) {

			case 'bu-slideshow-admin':
				$local = array(
					'noSlideshowsMsg'    => __('No slideshows yet.', 'bu-slideshow'),
					'addButtonText'      => __('Add a slideshow', 'bu-slideshow'),
					'deleteConfirm'      => __('Are you sure you want to delete this slideshow? This action cannot be undone.', 'bu-slideshow'),
					'deleteConfirmSlide' => __('Are you sure you want to delete this slide?', 'bu-slideshow'),
					'deleteError'        => __('Could not delete slideshow.', 'bu-slideshow'),
					'noneSelectedError'  => __('You must select a slideshow.', 'bu-slideshow'),
					'emptyNameError'     => __('The name field for the slideshow cannot be empty.', 'bu-slideshow'),
					'thumbFailError'     => __('Could not load image thumbnail.', 'bu-slideshow'),
					'addSlideFailError'  => __('Could not create new slide.', 'bu-slideshow'),
					'mediaUploadTitle'   => __('Select Image', 'bu-slideshow'),
					'mediaUploadButton'  => __('Select Image', 'bu-slideshow')
				);
				wp_localize_script($script, 'buSlideshowLocalAdmin', $local);

				break;

			case 'bu-slideshow-selector':
				$local = array(
					'toggleTextShow' => __('Show advanced', 'bu-slideshow'),
					'toggleTextHide' => __('Hide advanced', 'bu-slideshow')
				);
				wp_localize_script($script, 'buSlideshowLocalSelector', $local);

			default:
				break;
		}
	}

	/**
	 * Helper for retrieving plugin admin URLs via ajax
	 */
	static public function get_url() {
		$urls = array(
			'manage_url' => self::$manage_url,
			'edit_url' => self::$edit_url,
			'add_url' => self::$add_url,
			'preview_url' => self::$preview_url
		);
		if (isset($_POST['url'])) {
			if (array_key_exists($_POST['url'], $urls)) {
				echo esc_url($urls[$_POST['url']]);
			}
		}
		exit;
	}

	/**
	 * Loads scripts only when global variable is set by shortcode handler. Scripts
	 * are never enqueued, so a filter is available should another script need to
	 * prevent these from loading.
	 *
	 * This method is only used in WP < 3.3; later version simply enqueue scripts
	 * in the shortcode handler.
	 *
	 * @global int|bool $bu_slideshow_loadscripts
	 */
	static public function conditional_script_load() {
		global $bu_slideshow_loadscripts;

		if ($bu_slideshow_loadscripts) {
			$conditional_scripts = array('bu-sequence-patch', 'jquery-sequence', 'bu-slideshow');
			apply_filters('bu_slideshow_conditional_scripts', $conditional_scripts);

			foreach($conditional_scripts as $script) {
				wp_print_scripts($script);
			}
		}
	}

	/**
	 * Establishes custom thumbnail size.
	 */
	static public function custom_thumb_size() {
		add_image_size(static::$custom_thumb_size, 100, 100, true);
	}

	/**
	 * Handles customizations to media upload for slide images
	 */
	static public function media_upload_custom() {

		$referer = strpos( wp_get_referer(), 'bu_slideshow' );
		if ($referer !== FALSE) {
			add_filter('gettext', array(__CLASS__, 'replace_thickbox_text'), 99, 3);
			add_filter('media_upload_tabs', array(__CLASS__, 'remove_url_tab'), 99);
			add_filter('post_mime_types', array(__CLASS__, 'post_mime_types'), 99);
		}

	}

	/**
	 * Called when media upload form is first loaded and again when the upload is
	 * complete, with the image info in POST. This function exists so we can add
	 * the mime type filter hook only when the
	 */
	static public function handle_upload() {
		add_filter('upload_mimes', array(__CLASS__, 'upload_mime_types'), 99);

		// non-flash upload field
		if (isset($_POST['html-upload']) && !empty($_FILES)) {

			// uploads the file, inserts the attachment
			$id = media_handle_upload('async-upload', 0);
			unset($_FILES);
			if (is_wp_error($id)) {
				$errors['upload_error'] = $id;
				$id = false;
			}
		}

		// user has pressed 'insert into post' or equivalent
		if (!empty($_POST)) {

			$return = media_upload_form_handler();

			if (is_string($return))
				return $return;
			if (is_array($return))
				$errors = $return;
		}

		if (isset($_POST['save'])) {
			$errors['upload_notice'] = __('Saved.', 'bu-slideshow');
		}

		return wp_iframe('media_upload_type_form', 'bu_slideshow', $errors, $id);

	}

	/**
	 * Change the text on the media upload button.
	 *
	 * @param string $translated_text
	 * @param string $text
	 * @param string $domain
	 * @return string
	 */
	static public function replace_thickbox_text($translated_text, $text, $domain) {
		if ($text === 'Insert into Post') {
			return __('Select Image', 'bu-slideshow');
		}

		return $translated_text;
	}

	/**
	 * Remove 'insert from URL' tab, which breaks without a post ID
	 *
	 * @param array $tabs
	 * @return array
	 */
	static public function remove_url_tab($tabs) {

		unset($tabs['type_url']);

		return $tabs;

	}

	/**
	 * Restrict 'insert media' filter choices to image file types
	 *
	 * @param array $mime_types
	 * @return array
	 */
	static public function post_mime_types($mime_types) {

		foreach($mime_types as $key => $val) {
			if ($key !== 'image') {
				unset($mime_types[$key]);
			}
		}

		return $mime_types;
	}

	/**
	 * Restrict media that can be uploaded to images. Is not applied to flash uploader.
	 *
	 * @param array $mime_types
	 * @return array
	 */
	static public function upload_mime_types($mime_types) {

		foreach($mime_types as $key => $val) {
			if (!in_array($key, self::$image_mimes)) {
				unset($mime_types[$key]);
			}
		}

		return $mime_types;
	}

	/**
	 * When Flash uploader is being used on Edit page, restrict allowed file types
	 *
	 * @param string $types
	 * @return string
	 */
	static public function flash_file_types($types) {

		if (strpos(wp_get_referer(), self::$edit_url) !== false) {

			$new_types = '';
			foreach (self::$image_mimes as $mime) {
				if (strpos($mime, 'jpg') !== false) {
					$submimes = explode('|', $mime);
					foreach ($submimes as $sub) {
						$new_types .= $sub . ';';
					}
				} else {
					$new_types .= $mime . ';';
				}
			}

			return $new_types;

		}

		return $types;
	}

	/**
	 * Restrict query that populates the 'insert from media library' view to images
	 *
	 * @global string $pagenow
	 * @param obj $query
	 */
	static public function media_library_filter($query) {

		global $pagenow;

		if ($pagenow !== 'media-upload.php') {
			return;
		}

		if (strpos( wp_get_referer(), 'bu_slideshow' ) === false) {
			return;
		}

		$query->set('post_mime_type', 'image');

	}

	static public function admin_menu() {
		$index = self::get_menu_index(21);

		add_menu_page(__('Slideshows', 'bu-slideshow'), __('Slideshows', 'bu-slideshow'), self::$min_cap, 'bu-slideshow', array(__CLASS__, 'manage_slideshow_page'), 'dashicons-format-gallery', $index);
		add_submenu_page('bu-slideshow', __('Add Slideshow', 'bu-slideshow'), __('Add Slideshow', 'bu-slideshow'), self::$min_cap, 'bu-add-slideshow', array(__CLASS__, 'add_slideshow_page'));
		add_submenu_page('bu-preview-slideshow', __('Preview Slideshow', 'bu-slideshow'), __('Preview Slideshow', 'bu-slideshow'), self::$min_cap, 'bu-preview-slideshow', array(__CLASS__, 'preview_slideshow_page'));
		add_submenu_page('bu-edit-slideshow', __('Edit Slideshow', 'bu-slideshow'), __('Edit Slideshow', 'bu-slideshow'), self::$min_cap, 'bu-edit-slideshow', array(__CLASS__, 'edit_slideshow_page'));
	}

	/**
	 * Hack to prevent admin menu position from overwriting any existing menu items.
	 * A better solution should be available in the future, see http://core.trac.wordpress.org/ticket/12718
	 *
	 * @global array $menu
	 * @param int $index
	 * @return int
	 */
	static protected function get_menu_index($index) {

		if (!is_numeric($index)) {
			return NULL;
		}

		global $menu;

		if (isset($menu[$index])) {
			return self::get_menu_index( ($index + 1) );
		}

		return (int) $index;

	}

	static private function save_show($show){
		$height = (intval($_POST['bu_slideshow_height']) > 0) ? intval($_POST['bu_slideshow_height']) : 0 ;
		$caption_positions = apply_filters('bu_slideshow_caption_positions', self::$caption_positions);
		$valid_templates = apply_filters('bu_slideshow_slide_templates', self::$slide_templates);
		$template = ( isset($_POST['bu_slideshow_template']) && array_key_exists( $_POST['bu_slideshow_template'], $valid_templates ) ) ? $_POST['bu_slideshow_template'] : '';
		$all_templates = apply_filters('bu_slideshow_slide_templates', BU_Slideshow::$slide_templates);

		// okay to have no slides
		if (!isset($_POST['bu_slides']) || !is_array($_POST['bu_slides'])) {
			$_POST['bu_slides'] = array();
		}

		$slides = array();

		$show->set_view('admin');
		$show->set_name($_POST['bu_slideshow_name']);
		$show->set_template( $template );
		$show->set_height($height);

		foreach ($_POST['bu_slides'] as $i => $arr) {
			$customfields = array();

			if( $show->template_id && is_array( $arr['custom_fields'] ) ){
				foreach( $arr['custom_fields'] as $k => $v){
					if( ! array_key_exists($k, $all_templates[ $show->template_id ]['custom_fields'] ) ){
						continue;
					}
					$customfields[ $k ] = wp_kses_post( $v );
				}
			}

			$args = array(
				'view'              => 'admin',
				'order'             => $i,
				'image_id'          => intval($arr['image_id']),
				'image_size'        => esc_attr(wp_kses_data($arr['image_size'])),
				'caption'           => array(
					'title'         => wp_kses_data($arr['caption']['title']),
					'link'          => esc_attr(wp_kses_data($arr['caption']['link'])),
					'text'          => wp_kses_data($arr['caption']['text']),
					'position'      => ( FALSE === array_search($arr['caption']['position'], $caption_positions) ) ? 'caption-bottom-right' : $arr['caption']['position']
				),
				'template_id'       => $template,
				'additional_styles' => esc_attr(wp_kses_data($arr['additional_styles'])),
				'custom_fields'     => $customfields,
			);
			$slides[] = new BU_Slide($args);
		}
		$show->set_slides($slides);
	}


	/**
	 * Loads and handles submissions from Add Slideshow page.
	 */
	static public function add_slideshow_page() {

		$action = !empty( $_POST['bu_slideshow_save_show'] ) ? 'do_create' : 'view_form';
		$msg = '';
		$name = '';
		$height = 0;
		$slides = array();
		$valid_templates = apply_filters('bu_slideshow_slide_templates', BU_Slideshow::$slide_templates );
		$template_id = '';

		switch ( $action ) {
			case 'do_create':
				if ( !isset($_POST['bu_slideshow_nonce']) || !wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_update_slideshow') || !current_user_can(self::$min_cap) ) {
					require_once(ABSPATH . 'wp-admin/admin-header.php');
					wp_die(esc_html__('You are not authorized to perform this action.', 'bu-slideshow'));
					exit;
				}

				if( !isset( $_POST['bu_slideshow_name'] ) || '' == trim( $_POST['bu_slideshow_name'] ) ){
					$msg .= __('Could not create slideshow: missing name.', 'bu-slideshow');
					break;
				} else {
					$show = self::create_slideshow( filter_var($_POST['bu_slideshow_name'], FILTER_SANITIZE_STRING) );
					if( !$show || is_wp_error($show) ){
						$msg = __('Error creating slideshow', 'bu-slideshow');
						break;
					}
				}

				// we are handling a form submission & all validation complete
				self::save_show($show);

				if ($show->update()) {
					$url = 'admin.php?page=bu-edit-slideshow&bu_slideshow_id=' . $show->id . "&msg=";
					$url .= urlencode( __("Slideshow created successfully.", 'bu-slideshow') );
					wp_safe_redirect( admin_url( $url ) );
					exit;
				} else {
					require_once(ABSPATH . 'wp-admin/admin-header.php');
					$msg = __("Error creating slideshow", 'bu-slideshow');
				}
				break;
		}
		require_once(ABSPATH . 'wp-admin/admin-header.php');
		require_once BU_SLIDESHOW_BASEDIR . 'interface/add-slideshow.php';
	}

	/**
	 * Displays Manage Slideshow page.
	 */
	static public function manage_slideshow_page() {
		$slideshows = self::get_slideshows();

		if(isset($_GET['msg'])){
			$msg = filter_var( $_GET['msg'], FILTER_SANITIZE_STRING );
		}

		require_once BU_SLIDESHOW_BASEDIR . 'interface/manage-slideshows.php';
	}

	/**
	 * Displays Preview Slideshow page.
	 */
	static public function preview_slideshow_page() {
		if (isset($_GET['bu_slideshow_id']) && !empty($_GET['bu_slideshow_id'])) {

			$id = intval($_GET['bu_slideshow_id']);

			if (!self::slideshow_exists($id)) {
				$msg = __("Could not find slideshow.", 'bu-slideshow');
				$id = false;
			}
		}

		require_once BU_SLIDESHOW_BASEDIR . 'interface/preview-slideshow.php';
	}

	/**
	 * Creates a new, empty slideshow and returns it. IDs begin at 1.
	 * @param string $name
	 * @return array
	 */
	static public function create_slideshow($name) {
		if (!is_string($name) || !trim($name)) {
			return new WP_Error(__('invalid argument', 'bu-slideshow'), __('Invalid name supplied for slideshow.', 'bu-slideshow'));
		}

		$show = new BU_Slideshow_Instance();
		$show->set_name(trim($name));
		$show->update();

		return $show;
	}

	/**
	 * Returns next unassigned numeric slideshow id. Slideshow ids begin at 1.
	 * @return int
	 */
	static public function get_new_id() {
		$last_id = get_option(self::$show_id_meta_key, 0);
		$new_id = $last_id + 1;
		update_option(self::$show_id_meta_key, $new_id);

		return $new_id;
	}

	/**
	 * Handles AJAX request to delete slideshow
	 */
	static public function delete_slideshow_ajax() {

		if (!isset($_POST['bu_slideshow_nonce'])) {
			wp_die(esc_html__("You are not authorized to perform that action.", 'bu-slideshow'));
		}

		if (!wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_delete_slideshow')) {
			wp_die(esc_html__("You are not authorized to perform that action.", 'bu-slideshow'));
		}

		if (!current_user_can(self::$min_cap)) {
			wp_die(esc_html__("You do not have the necessary permissions to delete slideshows.", 'bu-slideshow'));
		}

		if (!isset($_POST['id']) || empty($_POST['id'])) {
			return;
		}

		$id = intval($_POST['id']);

		echo wp_kses_post(self::delete_slideshow($id));
		exit;
	}

	/**
	 * Deletes slideshow with given id if it exists.
	 *
	 * @param int $id
	 * @return int
	 */
	static public function delete_slideshow($id) {
		$id = self::slideshow_maybe_translate_id( $id );
		return ( FALSE !== wp_delete_post( $id ) );
	}

	/**
	 * Returns true if a slideshow with an id of $id exists.
	 *
	 * @param int $id
	 * @return boolean
	 */
	static public function slideshow_exists($id) {
		$id = self::slideshow_maybe_translate_id( $id );

		return ( 'object' === gettype( get_post_meta( $id, '_bu_slideshow', TRUE ) ) );
	}

	/**
	* Determine if slideshow ID was created before v3.2
	* If it was, fetch the new post ID.
	*
	* @param int $id
	* @return int
	*/
	static public function slideshow_maybe_translate_id($id){
		$old_slideshow_ids = get_option( 'bu_slideshow_id_map', array() );

		if( array_key_exists( $id, $old_slideshow_ids ) ){
			$id = $old_slideshow_ids[ $id ];
		}

		return $id;
	}

	/**
	 * Returns slideshow with given id, or false if slideshow doesn't exist.
	 *
	 * @param int $id
	 * @return bool|array
	 */
	static public function get_slideshow($id) {
		$id = self::slideshow_maybe_translate_id( $id );
		$slideshow = get_post_meta( $id, '_bu_slideshow', TRUE );

		return ( 'object' === gettype( $slideshow ) ) ? $slideshow : FALSE;
	}

	/**
	 * Returns array of all slideshows defined.
	 *
	 * @return array
	 */
	static public function get_slideshows() {
		$slideshows = array();

		$slideshow_ids = get_posts(
			array(
				'post_type'              => 'bu_slideshow',
				'posts_per_page'         => 500,
				'orderby'                => 'title',
				'order'                  => 'asc',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
			)
		);

		foreach ( $slideshow_ids as $id ) {
			$slideshows[ $id ] = self::get_slideshow( $id );
		}

		return $slideshows;
	}

	/**
	 * Displays and handles submissions from Edit Slideshow page.
	 */
	static public function edit_slideshow_page() {

	$action = !empty( $_POST['bu_slideshow_save_show'] ) ? 'save' : 'view';
	$msg = !empty($_GET['msg']) ? filter_var( $_GET['msg'], FILTER_SANITIZE_STRING ) : '';

		switch ( $action ) {
			case 'save':
				if ( !isset($_POST['bu_slideshow_nonce']) || !wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_update_slideshow') || !current_user_can(self::$min_cap) ) {
					wp_die(esc_html__('You are not authorized to perform this action.', 'bu-slideshow'));
					exit;
				}

				if( !self::slideshow_exists( intval( $_POST['bu_slideshow_id'] ) ) ){
					wp_die(esc_html__('Invalid slideshow.', 'bu-slideshow'));
					exit;
				}

				if( !isset( $_POST['bu_slideshow_name'] ) || '' == trim( $_POST['bu_slideshow_name'] ) ){
					$msg = __('Could not save slideshow: missing name.', 'bu-slideshow');
					break;
				} else {
					$show = self::get_slideshow(intval($_POST['bu_slideshow_id']));
					if( !$show || is_wp_error($show) ){
						$msg = __('Error getting slideshow', 'bu-slideshow');
						break;
					}
				}

				// we are handling a form submission & all validation complete
				self::save_show($show);
				$msg = $show->update() ? __("Slideshow updated successfully.", 'bu-slideshow') : __("Slideshow did not save succesfully.", 'bu-slideshow');

				break;

			case 'view':
				if ( !isset($_GET['bu_slideshow_id']) || empty($_GET['bu_slideshow_id']) ) {
					wp_die(esc_html__('Invalid slideshow', 'bu-slideshow'));
					exit;
				}

				$show = self::get_slideshow( intval( $_GET['bu_slideshow_id'] ) );
				if( !$show || is_wp_error($show) ){
					wp_die(esc_html__('Error getting slideshow', 'bu-slideshow'));
					exit;
				}
				break;
		}

		$show->set_view('admin');
		echo wp_kses_post($show->get(array('msg' => $msg)));
	}

	/**
	 * Loads edit view for slideshow with given id.
	 * @param int $id
	 */
	static public function edit_slideshow_ui($id, $msg = '') {
		if (!self::slideshow_exists($id)) {
			return;
		}

		$show = self::get_slideshow($id);
		$show->set_view('admin');
		echo wp_kses_post($show->get(array('msg' => $msg)));
	}

	/**
	 * AJAX handler for adding a new slide.
	 * @todo add nonce check
	 */
	static public function add_slide_ajax() {
		if (!current_user_can(self::$min_cap)) {
			return;
		}

		$slide = new BU_Slide(array('view' => 'admin', 'order' => $_POST['order']));
		echo wp_kses_post($slide->get());
		exit;
	}

	/**
	 * Echoes slide image thumb data as JSON
	 * @return string
	 */
	static public function get_slide_thumb_ajax() {
		if (!isset($_POST['image_id']) || !$_POST['image_id']) {
			return;
		}

		$img_info = self::get_slide_image_thumb(intval($_POST['image_id']));

		echo wp_kses_post($img_info);
		exit;
	}

	/**
	 * Generates thumbnail if it doesn't yet exist.
	 * Supports images uploaded before plugin was activated.
	 *
	 * @param  int  $img_id
	 * @return bool
	 */
	static public function generate_slideshow_thumb( $img_id ) {
		$img_arr = wp_get_attachment_image_src($img_id, static::$custom_thumb_size);

		/* if the regular img url is returned it means we don't have an existing thumb of correct size */
		if (strpos($img_arr[0], strval($img_arr[1])) === false) {
			$img_path = get_attached_file($img_id);
			$success = wp_update_attachment_metadata($img_id, wp_generate_attachment_metadata($img_id, $img_path));
			return false !== $success;
		}

		return true;
	}

	/**
	 * Gets thumbnail for custom size. Generates thumbnail if it doesn't exist.
	 * @return array
	 */
	static public function get_slide_image_thumb( $img_id ) {
		if ( ! static::generate_slideshow_thumb( $img_id ) ) {
			error_log( sprintf( '%s: Failed generating thumbnail for image (%s).', __METHOD__, $img_id ) );
		}

		return wp_get_attachment_image( $img_id, static::$custom_thumb_size );
	}

	/**
	 * Implements shortcode. Supported shortcode attributes:
	 * show_id:		mandatory, id of slideshow
	 * show_nav:	optional, whether or not to display slideshow 'navigation'
	 *
	 * @global int $bu_slideshow_loadscripts
	 * @param array $atts
	 *
	 * @todo check for presence of titles in all slides, prevent user subitted atts
	 * from doing anything awkward
	 */
	static public function shortcode_handler($atts) {

		/**
		 * Trigger loading of plugin scripts in footer in older versions of WP.
		 */
		if (version_compare(self::$wp_version, '3.3', '<')) {
			global $bu_slideshow_loadscripts;
			$bu_slideshow_loadscripts = 1;
		} else {
			wp_enqueue_script('jquery-sequence');
			wp_enqueue_script('bu-slideshow');
			do_action('bu_slideshow_enqueued');
		}

		$att_defaults = self::$shortcode_defaults;

		$falsish = array('0', 'false', 'no', 'none');

		// try to show arrows if no autoplay
		if (isset($atts['autoplay']) && in_array(strtolower($atts['autoplay']), $falsish)) {
			$att_defaults['show_arrows'] = 1;
		}

		$atts = shortcode_atts($att_defaults, $atts);

		if (!self::slideshow_exists(intval($atts['show_id']))) {
			echo '';
			return;
		}

		// clean up possible bad att values...

		$atts['shuffle'] = filter_var($atts['shuffle'], FILTER_VALIDATE_BOOLEAN);

		if (!is_numeric($atts['width']) && strtolower($atts['width']) !== 'auto') {
			$atts['width'] = 'auto';
		}

		if ( ! is_numeric( $atts['transition_delay'] ) || 1 > $atts['transition_delay'] ) {
			$atts['transition_delay'] = 5000;
		} else {
			$atts['transition_delay'] = intval( $atts['transition_delay'] ) * 1000;
		}

		if (!in_array($atts['nav_style'], self::$nav_styles)) {
			$atts['nav_style'] = $att_defaults['nav_style'];
		}

		foreach (array('show_nav', 'autoplay', 'show_arrows') as $var) {
			if (in_array(strtolower($atts[$var]), $falsish)) {
				$atts[$var] = 0;
			}
		}

		$show = self::get_slideshow(intval($atts['show_id']));
		$show->set_view('public');

		$html = $show->get($atts);

		return $html;
	}

	/**
	 * Attempts to identify and return alt text for image with given id.
	 * Necessary because checking postmeta that stores the alt does not always return
	 * values as expected -- possibly related to WP caching?
	 *
	 * @param int|string $img_id
	 * @return string
	 */
	static public function get_image_alt($img_id) {
		$img_id = intval($img_id);
		if (!is_numeric($img_id) || $img_id === 0) {
			return '';
		}

		$img_alt = get_post_meta($img_id, '_wp_attachment_img_alt', true);
		if (empty($img_alt)) {
			$markup = wp_get_attachment_image($img_id);
			$patt = '/alt=\"(.*?)\"/';
			$matches = array();
			if (preg_match($patt, $markup, $matches)) {
				return $matches[1];
			}
		}

		return strval($img_alt);
	}

	/**
	 * Returns markup for the Slideshow selector UI.
	 *
	 * @param array $args
	 * @return string
	 */
	static public function get_selector($args = array()) {
		$all_slideshows = self::get_slideshows();
		$defaults = self::$shortcode_defaults;
		$empty_ok = array('show_nav', 'autoplay', 'transition_delay');

		foreach ($defaults as $key => $def) {
			if (in_array($key, $empty_ok)) {
				if (!isset($args[$key])) {
					$args[$key] = $def;
				}
			} else if (!isset($args[$key]) || !$args[$key]) {
				$args[$key] = $def;
			}
		}

		ob_start();
		include BU_SLIDESHOW_BASEDIR . 'interface/slideshow-selector.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Returns true if the current screen should integrate the 'insert slideshow'
	 * button and modal functionality in the WP editor. Allows for filtering of screens.
	 *
	 * @global object $current_screen
	 * @return boolean
	 */
	static public function using_editor() {
		$current_screen  = get_current_screen();
		$allowed_screens = apply_filters( 'bu_slideshow_insert_slideshow_screens', self::$editor_screens );
		$screen_id       = $current_screen->id;

		// If the current screen is using the block editor,
		// we don't need to output the button and modal.
		if ( bu_slideshow_is_block_editor() ) {
			return false;
		}

		if ( $screen_id && post_type_supports( $screen_id, self::$post_support_slug ) ) {
			return true;
		}

		if (in_array($screen_id, $allowed_screens)) {
			return true;
		}

		return false;
	}

	/**
	 * Adds modal UI to footer, for display in thickbox.
	 */
	static public function admin_footer() {
		if ( self::using_editor() ) :
		?>
			<div id="bu_slideshow_modal_wrap" class="wrap postbox">
				<h2><?php esc_html_e('Insert Slideshow', 'bu-slideshow'); ?></h2>
				<?php echo wp_kses_post(self::get_selector()); ?>
				<p><a href="#" id="bu_insert_slideshow" class="button-primary"><?php esc_html_e('Insert Slideshow', 'bu-slideshow'); ?></a></p>
			</div>

		<?php
		endif;
	}

	/**
	 * Adds 'Insert Slideshow' button above editor
	 *
	 * @param string $context
	 * @return string
	 */
	static public function add_media_button() {
		if ( self::using_editor() ) {
			echo wp_kses_post(sprintf( '<a class="button" id="bu_slideshow_modal_button" title="%s" href="#">%s</a>', __( 'Add Slideshow', 'bu-slideshow' ), __( 'Add Slideshow', 'bu-slideshow' ) ) );
		}
	}

}

BU_Slideshow::add_plugins_loaded_hook();

/**
 * Determine whether the block editor is loading on the current screen.
 *
 * Checks if `get_current_screen()->is_block_editor()` exists (WP 5.x).
 * Falls back to `is_gutenberg_page` if it exists.
 *
 * @return bool
 */
function bu_slideshow_is_block_editor() {
	if ( method_exists( get_current_screen(), 'is_block_editor' ) ) {
		return get_current_screen()->is_block_editor();
	} elseif ( function_exists( 'is_gutenberg_page' ) ) {
		return is_gutenberg_page();
	}

	return false;
}

/**
 * Function wrapper for adding slideshow display to themes etc. See shortcode handler
 * for expected args.
 */
if (!function_exists('bu_get_slideshow')) {
	function bu_get_slideshow($args) {
		if (!isset($args['show_id']) || empty($args['show_id']) ) {
			return '';
		}

		$html = BU_Slideshow::shortcode_handler($args);
		return $html;
	}
}

/**
 * For use by third party code that uses the Slideshow Selector UI.
 */
if (!function_exists('bu_enqueue_slideshow_selector')) {
	function bu_enqueue_slideshow_selector() {
		BU_Slideshow::selector_scripts_styles();
	}
}
