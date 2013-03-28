<?php
/*
 Plugin Name: BU Slideshow
 Description: Allows for the creation and display of rotating image slideshows, with or without captions.
 
 Version: 1.0
 Author: Boston University (IS&T)
 Author URI: http://www.bu.edu/tech/
*/
define('BU_SLIDESHOW_BASEDIR', plugin_dir_path(__FILE__));
define('BU_SLIDESHOW_BASEURL', plugin_dir_url(__FILE__));

require_once BU_SLIDESHOW_BASEDIR . 'class-bu-slideshow.php';
require_once BU_SLIDESHOW_BASEDIR . 'class-bu-slide.php';

class BU_Slideshow {
	static $wp_version;
	
	static $meta_key = 'bu_slideshows';
	static $manage_url = 'admin.php?page=bu-slideshow';
	static $edit_url = 'admin.php?page=bu-edit-slideshow';
	static $add_url = 'admin.php?page=bu-add-slideshow';
	static $preview_url = 'admin.php?page=bu-preview-slideshow';
	static $min_cap = 'edit_posts';
	
	static $shortcode_defaults = array(
		'show_id' => 0,
		'show_nav' => 1,
		'transition' => 'slide',
		'nav_style' => 'icon',
		'autoplay' => 1,
		'show_arrows' => 0
	);
	static $transitions = array('slide', 'fade'); // prepackaged transitions
	static $nav_styles = array('icon', 'number');
	
	static $editor_screens = array('page', 'post'); // edit screens 
	
	static public function init() {
		global $pagenow;
		
		self::$wp_version = get_bloginfo('version');
		
		add_action('init', array(__CLASS__, 'custom_thumb_size'));
		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_scripts_styles'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'public_scripts_styles'));
		add_action('media_buttons_context', array(__CLASS__, 'add_media_button'),99);
		add_action('admin_footer', array(__CLASS__, 'admin_footer'));
		
		if ('media-upload.php' === $pagenow || 'async-upload.php' === $pagenow) {
			self::media_upload_custom();
		}
		
		add_action('wp_ajax_bu_delete_slideshow', array(__CLASS__, 'delete_slideshow_ajax'));
		add_action('wp_ajax_bu_add_slide', array(__CLASS__, 'add_slide_ajax'));
		add_action('wp_ajax_bu_get_slide_thumb', array(__CLASS__, 'get_slide_thumb_ajax'));
		add_action('wp_ajax_bu_slideshow_get_url', array(__CLASS__, 'get_url'));
		
		add_shortcode('bu_slideshow', array(__CLASS__, 'shortcode_handler'));
		
		/**
		 * Back compat for WP < 3.3
		 */
		if (version_compare(self::$wp_version, '3.3', '<')) {
			add_action('wp_footer', array(__CLASS__, 'conditional_script_load'));
		}
	}
	
	/**
	 * Loads admin scripts/styles on plugin's pages. Add a page's id using by hooking
	 * the bu_slideshow_selector_pages filter to load the selector scripts/styles.
	 * 
	 * Scripts/styles loaded only as needed for various modules: slideshow admin pages,
	 * the selector UI, and the modal that contains selector UI.
	 * 
	 * @global type $current_screen
	 */
	static public function admin_scripts_styles() {
		global $current_screen;
		$admin_pages = array(
			'toplevel_page_bu-slideshow',
			'slideshows_page_bu-slideshow',
			'admin_page_bu-edit-slideshow'
		);
		
		$selector_pages = self::$editor_screens;
		$selector_pages = apply_filters('bu_slideshow_selector_pages', $selector_pages);
		
		if (in_array($current_screen->id, $selector_pages)) {
			self::selector_scripts_styles();
		}
		
		if (self::using_editor()) {
			wp_register_script('bu-modal', BU_SLIDESHOW_BASEURL . 'interface/js/bu-modal.js', array('jquery'), false, true);
			wp_register_style('bu-modal', BU_SLIDESHOW_BASEURL . 'interface/css/bu-modal.css');
			wp_enqueue_script('bu-modal');
			wp_enqueue_style('bu-modal');
		}
		
		if (in_array($current_screen->id, $admin_pages) || self::using_editor()) {
			wp_register_script('bu-slideshow-admin', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow-admin.js', array('jquery'), false, true);
			wp_enqueue_script('bu-slideshow-admin');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('thickbox');
			
			wp_register_style('bu-slideshow-admin', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow-admin.css');
			wp_enqueue_style('bu-slideshow-admin');
			wp_enqueue_style('thickbox');
		}
		
		/* preview page needs public scripts/styles */
		if ($current_screen->id === 'admin_page_bu-preview-slideshow') {
			self::public_scripts_styles();
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
		wp_register_script('modernizr', BU_SLIDESHOW_BASEURL . 'interface/js/modernizr-dev.js', array(), false, true);
		
		/* patch for jQuery < 1.71 to make sequence.js work, only used in WP < 3.3 */
		if (version_compare(self::$wp_version, '3.3', '<')) {
			wp_register_script('bu-sequence-patch', BU_SLIDESHOW_BASEURL . 'interface/js/bu-sequence-patch.js', array('jquery'), false, true);
			wp_register_script('jquery-sequence', BU_SLIDESHOW_BASEURL . 'interface/js/sequence.jquery.js', array('jquery', 'modernizr', 'bu-sequence-patch'), false, true);
			wp_register_script('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow.js', array('jquery', 'jquery-sequence', 'modernizr', 'bu-sequence-patch'), false, true);
		} else {
			wp_register_script('jquery-sequence', BU_SLIDESHOW_BASEURL . 'interface/js/sequence.jquery.js', array('jquery', 'modernizr'), false, true);
			wp_register_script('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow.js', array('jquery', 'jquery-sequence', 'modernizr'), false, true);
		}
		
		if (!defined('BU_SLIDESHOW_CUSTOM_CSS') || !BU_SLIDESHOW_CUSTOM_CSS) {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow.css');
			wp_enqueue_style('bu-slideshow');
		}
		
		/* enqueue public styles on preview page */
		global $current_screen;
		if ($current_screen && $current_screen->id === 'admin_page_bu-preview-slideshow') {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow.css');
			wp_enqueue_style('bu-slideshow');
		}
	}
	
	/**
	 * Load scripts and styles for the selector UI
	 */
	static public function selector_scripts_styles() {
		wp_register_script('bu-slideshow-selector', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow-selector.js', array('jquery'), false, true);
		wp_enqueue_script('bu-slideshow-selector');
		
		wp_register_style('bu-slideshow-selector', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow-selector.css');
		wp_enqueue_style('bu-slideshow-selector');
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
				echo $urls[$_POST['url']];
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
			$conditional_scripts = array('modernizr', 'bu-sequence-patch', 'jquery-sequence', 'bu-slideshow');
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
		add_image_size('bu-slideshow-thumb', 100, 100, true);
	}
	
	/**
	 * Handles customizations to media upload for slide images
	 */
	static public function media_upload_custom() {
		$referer = strpos( wp_get_referer(), 'bu_slideshow' );
		if ($referer != '') {
			add_filter('gettext', array(__CLASS__, 'replace_thickbox_text'), 1, 3);
			add_filter('post_mime_types', array(__CLASS__, 'post_mime_types'));
		}
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
			return __('Select Image');
		}
		
		return $translated_text;
	}
	
	/**
	 * Restricts uploads to image files and only displays image files when 
	 * adding from the Media Library
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
	
	static public function admin_menu() {
		add_menu_page(__('Slideshows'), __('Slideshows'), self::$min_cap, 'bu-slideshow', array(__CLASS__, 'manage_slideshow_page'), '', 21);
		add_submenu_page('bu-slideshow', __('Add Slideshow'), __('Add Slideshow'), self::$min_cap, 'bu-add-slideshow', array(__CLASS__, 'add_slideshow_page'));
		add_submenu_page('bu-preview-slideshow', __('Preview Slideshow'), __('Preview Slideshow'), self::$min_cap, 'bu-preview-slideshow', array(__CLASS__, 'preview_slideshow_page'));
		add_submenu_page('bu-edit-slideshow', __('Edit Slideshow'), __('Edit Slideshow'), self::$min_cap, 'bu-edit-slideshow', array(__CLASS__, 'edit_slideshow_page'));
	}
	
	/**
	 * Loads and handles submissions from Add Slideshow page.
	 */
	static public function add_slideshow_page() {
		if (isset($_POST['bu-new-slideshow-name'])) {
			if (!isset($_POST['bu_slideshow_nonce']) || !wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_add_slideshow')) {
				wp_die('You are not authorized to perform this action.');
			}
			
			if (!current_user_can(self::$min_cap)) {
				wp_die('You do not have permission to add a new slideshow.');
			}

			if (!isset($_POST['bu-new-slideshow-name']) || trim($_POST['bu-new-slideshow-name']) === '') {
				$msg = 'You must enter a name for the slideshow.'; 
			} else {
				
				$show = self::create_slideshow(trim($_POST['bu-new-slideshow-name']));
				$msg = sprintf('Slideshow "%s" successfully created. <a href="%s&amp;bu_slideshow_id=%s">Edit this slideshow.</a>', esc_html(stripslashes($show->name)), self::$edit_url, $show->id);
				
			}
		}
		
		require_once BU_SLIDESHOW_BASEDIR . 'interface/add-slideshow.php';
	}
	
	/**
	 * Displays Manage Slideshow page.
	 */
	static public function manage_slideshow_page() {
		$slideshows = self::get_slideshows();

		require_once BU_SLIDESHOW_BASEDIR . 'interface/manage-slideshows.php';
	}
	
	/**
	 * Displays Preview Slideshow page.
	 */
	static public function preview_slideshow_page() {
		if (isset($_GET['bu_slideshow_id']) && !empty($_GET['bu_slideshow_id'])) {
			
			$id = intval($_GET['bu_slideshow_id']);
			
			if (!self::slideshow_exists($id)) {
				$msg = "Could not find slideshow.";
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
			return new WP_Error('invalid argument', 'Invalid name supplied for slideshow.');
		}
		
		$all_slideshows = self::get_slideshows();
		
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
		$all_slideshows = self::get_slideshows();
		
		if (empty($all_slideshows)) {
			return 1;
		}
		
		$keys = array_keys($all_slideshows);
		asort($keys);
		$last_index = implode('', array_slice($keys, -1, 1));
		$index = $last_index + 1;
		
		return $index;
	}
	
	/**
	 * Handles AJAX request to delete slideshow
	 */
	static public function delete_slideshow_ajax() {

		if (!isset($_POST['bu_slideshow_nonce'])) {
			wp_die("You are not authorized to perform that action.");
		}

		if (!wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_delete_slideshow')) {
			wp_die("You are not authorized to perform that action.");
		}

		if (!current_user_can(self::$min_cap)) {
			wp_die("You do not have the neccesary permissions to delete slideshows.");
		}
		
		if (!isset($_POST['id']) || empty($_POST['id'])) {
			return;
		}
		
		$id = intval($_POST['id']);

		echo self::delete_slideshow($id);
		exit;
	}
	
	/**
	 * Deletes slideshow with given id if it exists.
	 * @param int $id
	 * @return int
	 */
	static public function delete_slideshow($id) {
		if (!self::slideshow_exists($id)) {
			return;
		}
		
		$all_slideshows = self::get_slideshows();
		
		unset($all_slideshows[$id]);
		update_option(self::$meta_key, $all_slideshows);
		
		return 1;
	}
	
	/**
	 * Returns true if a slideshow with an id of $id exists.
	 * 
	 * @param int $id
	 * @return boolean
	 */
	static public function slideshow_exists($id) {
		$all_slideshows = self::get_slideshows();
		
		foreach ($all_slideshows as $show_id => $show_arr) {
			if (intval($id) === $show_id) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Returns slideshow with given id, or false if slideshow doesn't exist.
	 * 
	 * @param int $id
	 * @return bool|array
	 */
	static public function get_slideshow($id) {
		if (!self::slideshow_exists($id)) {
			return false;
		}
		
		$all_slideshows = self::get_slideshows();
		return $all_slideshows[$id];
	}
	
	/**
	 * Returns array of all slideshows defined.
	 * 
	 * @return array
	 */
	static public function get_slideshows() {
		$all_slideshows = get_option(self::$meta_key, array());
		if (!is_array($all_slideshows)) {
			$all_slideshows = array();
		}
		return $all_slideshows;
	}
	
	/**
	 * Displays and handles submissions from Edit Slideshow page.
	 */
	static public function edit_slideshow_page() {
		if ( isset($_POST['bu_slideshow_edit_show']) && $_POST['bu_slideshow_edit_show'] ) {
			
			if (!isset($_POST['bu_slideshow_nonce']) || !wp_verify_nonce($_POST['bu_slideshow_nonce'], 'bu_update_slideshow')) {
				wp_die("You are not authorized to perform this action.");
			}

			if (!current_user_can(self::$min_cap)) {
				wp_die("You do not have the neccesary permissions to update slideshows.");
			}
			
			$update = 1;
			$msg = '';

			$req = array('bu_slideshow_id', 'bu_slideshow_name');
			foreach ($req as $r) {
				if (!isset($_POST[$r]) || empty($_POST[$r])) {
					$update = 0;
					$msg .= sprintf('Could not update slideshow: missing or invalid %s. ', $r);
				}
			}
			
			// okay to have no slides 
			if (!isset($_POST['bu_slides']) || !is_array($_POST['bu_slides'])) {
				$_POST['bu_slides'] = array();
			}

			if ($update) {
				
				if (!self::slideshow_exists(intval($_POST['bu_slideshow_id']))) {
					$msg .= 'Could not find slideshow. ';
				} else {
					
					$show = self::get_slideshow(intval($_POST['bu_slideshow_id']));
					$show->set_view('admin');
					
					$show->set_name($_POST['bu_slideshow_name']);
					
					//$show->set_slides($_POST['bu_slides']);
					$slides = array();
					foreach ($_POST['bu_slides'] as $i => $arr) {
						$args = array(
							'view' => 'admin',
							'order' => $i,
							'image_id' => $arr['image_id'],
							'image_size' => $arr['image_size'],
							'caption' => array(
								'title' => $arr['caption']['title'],
								'link' => $arr['caption']['link'],
								'text' => $arr['caption']['text']
							)
						);
						$slides[] = new BU_Slide($args);
					}
					$show->set_slides($slides);
					
					if ($show->update()) {
						$msg .= "Slideshow updated successfully. ";
					} else {
						$msg .= "Slideshow did not save succesfully.";
					}
				
				}
			}
			
		}
		
		if (isset($_GET['bu_slideshow_id']) && !empty($_GET['bu_slideshow_id'])) {
			
			$id = intval($_GET['bu_slideshow_id']);
			
			if (self::slideshow_exists($id)) {
				self::edit_slideshow_ui($id, $msg);
				return;
			}
			
		}
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
		echo $show->get(array('msg' => $msg));
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
		echo $slide->get();
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
		
		echo json_encode($img_info);
		exit;
	}
	
	/**
	 * Gets thumbnail for custom size; generates that thumbnail if it doesn't yet exist
	 * in order to support images uploaded before plugin was activated
	 * @return array
	 */
	static public function get_slide_image_thumb($img_id) {
		$img_arr = wp_get_attachment_image_src($img_id, 'bu-slideshow-thumb');

		/* if the regular img url is returned it means we don't have an existing thumb of correct size */
		if (strpos($img_arr[0], strval($img_arr[1])) === false) {
			error_log('generating thumb for img id ' . $img_id);
			$img_path = get_attached_file($img_id);
			$success = wp_update_attachment_metadata($img_id, wp_generate_attachment_metadata($img_id, $img_path));
			if ($success) {
				$img_arr = wp_get_attachment_image_src($img_id, 'bu-slideshow-thumb');
			}
		}
		
		return $img_arr;
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
			wp_enqueue_script('modernizr');
			wp_enqueue_script('jquery-sequence');
			wp_enqueue_script('bu-slideshow');
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
		
		if (!in_array($atts['nav_style'], self::$nav_styles)) {
			$atts['nav_style'] = $att_defaults['nav_style'];
		}
		
		// liberally accept args
		foreach (array('show_nav', 'autoplay', 'show_arrows') as $var) {
			if (in_array(strtolower($atts[$var]), $falsish)) {
				$atts[$var] = 0;
			} 
		}
		
		$show = self::get_slideshow(intval($atts['show_id']));
		$show->set_view('public');
		
		$html = $show->get($atts);
		
		echo $html;
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
		$empty_ok = array('show_nav', 'autoplay');
		
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
	 * functionality in the WP editor. Allows for filtering of screens.
	 * 
	 * @global object $current_screen
	 * @return boolean
	 */
	static public function using_editor() {
		global $current_screen;
		
		$screens = apply_filters('bu_slideshow_editor_screens', self::$editor_screens);
		if (!is_array($screens)) {
			$screens = array();
		}
		
		if (in_array($current_screen->id, $screens)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Adds modal UI to footer, for display in thickbox.
	 */
	static public function admin_footer() {
		if (self::using_editor()):   ?>
			<div id="bu_slideshow_modal_wrap" class="wrap postbox">
				
				<h2>Insert Slideshow</h2>
				<?php echo self::get_selector(); ?>
				<p><a href="#" id="bu_insert_slideshow" class="button-primary">Insert Slideshow</a></p>
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
	static public function add_media_button($context) {
		if (self::using_editor()) {
			$html = '<a id="bu_slideshow_modal_button" title="Insert Slideshow" href="#">Insert Slideshow</a>';
			$context = $context . $html;
		}
		
		return $context;
	}
	
}

BU_Slideshow::init();

/**
 * Function wrapper for adding slideshow display to themes etc. See shortcode handler
 * for expected args.
 */
if (!function_exists('bu_get_slideshow')) {
	function bu_get_slideshow($args) {
		if (!isset($args['show_id']) || empty($args['show_id']) ) {
			return '';
		}
		
		ob_start();
		BU_Slideshow::shortcode_handler($args);
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
}