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

class BU_Slideshow {
	static $meta_key = 'bu_slideshows';
	static $manage_url = 'admin.php?page=bu-slideshow';
	static $edit_url = 'admin.php?page=bu-edit-slideshow';
	static $add_url = 'admin.php?page=bu-add-slideshow';
	static $preview_url = 'admin.php?page=bu-preview-slideshow';
	static $min_cap = 'edit_posts';
	static $slide_excerpt_length = 20;
	
	static public function init() {
		global $pagenow;
		
		add_action('init', array(__CLASS__, 'custom_thumb_size'));
		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_scripts_styles'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'public_scripts_styles'));
		
		if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
			self::media_upload_custom();
		}
		
		add_action('wp_ajax_bu_delete_slideshow', array(__CLASS__, 'delete_slideshow_ajax'));
		add_action('wp_ajax_bu_add_slide', array(__CLASS__, 'add_slide_ajax'));
		add_action('wp_ajax_bu_get_slide_thumb', array(__CLASS__, 'get_slide_thumb_ajax'));
		
		add_shortcode('bu_slideshow', array(__CLASS__, 'shortcode_handler'));
		
		/* revise conditional script loading after we update WP version, remove this action
		 * (see comments below)
		 */
		add_action('wp_footer', array(__CLASS__, 'conditional_script_load'));
	}
	
	/**
	 * Loads admin scripts/styles on plugin's pages
	 * @global type $current_screen
	 */
	static public function admin_scripts_styles() {
		global $current_screen;
		$admin_pages = array(
			'toplevel_page_bu-slideshow',
			'slideshows_page_bu-slideshow',
			'admin_page_bu-edit-slideshow'
		);
		
		if (in_array($current_screen->id, $admin_pages)) {
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
	 * Prepares styles and scripts for front end. Scripts are printed in footer when needed,
	 * see conditional_scritp_load().
	 * 
	 * Define BU_SLIDESHOW_CUSTOM_CSS in a theme to prevent default CSS from loading.
	 */
	static public function public_scripts_styles() {
		wp_register_script('jquery-cycle', BU_SLIDESHOW_BASEURL . 'interface/js/jquery.cycle.lite.js', array('jquery'), false, true);
		wp_register_script('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/js/bu-slideshow.js', array('jquery', 'jquery-cycle'), false, true);
		
		if (!defined('BU_SLIDESHOW_CUSTOM_CSS') || !BU_SLIDESHOW_CUSTOM_CSS) {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow.css');
			wp_enqueue_style('bu-slideshow');
		}
		
		global $current_screen;
		if ($current_screen && $current_screen->id === 'admin_page_bu-preview-slideshow') {
			wp_register_style('bu-slideshow', BU_SLIDESHOW_BASEURL . 'interface/css/bu-slideshow.css');
			wp_enqueue_style('bu-slideshow');
		}
	}
	
	/**
	 * Loads scripts only when global variable is set by shortcode handler. Scripts
	 * are never enqueued, so a filter is available should another script need to 
	 * prevent these from loading.
	 * 
	 * When we update WP to something more recent than 3.3, the shortcode handler can
	 * just call wp_enqueue_script() directly and this method can be removed.
	 * 
	 * @global int|bool $bu_slideshow_loadscripts
	 */
	static public function conditional_script_load() {
		global $bu_slideshow_loadscripts;
		
		if ($bu_slideshow_loadscripts) {
			$conditional_scripts = array('jquery-cycle', 'bu-slideshow');
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
				$msg = sprintf('Slideshow "%s" successfully created. <a href="%s&amp;bu_slideshow_id=%s">Edit this slideshow.</a>', esc_html(stripslashes($show['name'])), self::$edit_url, $show['id']);
				
			}
		}
		
		require_once BU_SLIDESHOW_BASEDIR . 'add-slideshow.php';
	}
	
	/**
	 * Displays Manage Slideshow page.
	 */
	static public function manage_slideshow_page() {
		
		$slideshows = get_option(self::$meta_key, array());

		require_once BU_SLIDESHOW_BASEDIR . 'manage-slideshows.php';
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
		
		require_once BU_SLIDESHOW_BASEDIR . 'preview-slideshow.php';
	}
	
	/**
	 * Creates a new, empty slideshow and saves it. IDs begin at 1.
	 * @param string $name
	 * @return array 
	 */
	static public function create_slideshow($name) {
		$def = array();
		$all_slideshows = get_option(self::$meta_key, $def);
		
		if (empty($all_slideshows)) {
			$all_slideshows = $def;
		}
		
		/* get highest assigned index and increment it */
		$keys = array_keys($all_slideshows);
		asort($keys);
		$last_index = implode('', array_slice($keys, -1, 1));
		$index = $last_index + 1;
		
		$new_sshow = array(
			"id" => $index,
			"name" => $name,
			"slides" => array()
		);
		
		$all_slideshows[$index] = $new_sshow;
		
		update_option(self::$meta_key, $all_slideshows);
		
		return $new_sshow;
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
		
		$all_slideshows = get_option(self::$meta_key, array());
		
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
		$all_slideshows = get_option(self::$meta_key, array());
		
		foreach ($all_slideshows as $show_id => $show_arr) {
			if ($id === $show_id) {
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
		
		$all_slideshows = get_option(self::$meta_key, array());
		return $all_slideshows[$id];
	}
	
	/**
	 * Replaces specified slideshow with slideshow data passed to it.
	 * 
	 * @param array $args
	 * @return int
	 * 
	 * @todo run caption through kses
	 * @todo return/display error messages
	 */
	static public function update_slideshow($args) {
		
		$show_id = intval($args['bu_slideshow_id']);
		
		if (!self::slideshow_exists($show_id)) {
			return;
		}
		
		$all_slideshows = get_option(self::$meta_key, array());

		$updated_show = array(
			"name" => trim(strip_tags($args['bu_slideshow_name'])),
			"id" => $show_id,
			"slides" => array()
		);
		
		foreach ($args['bu_slides'] as $i => $slide) {
			$slide['caption']['title'] = trim(strip_tags($slide['caption']['title']));
			$slide['caption']['link'] = trim(esc_url($slide['caption']['link']));
			$slide['caption']['text'] = trim(wp_kses_data($slide['caption']['text']));
			
			$updated_show["slides"][$i] = $slide;
		}
		
		$all_slideshows[$show_id] = $updated_show;
		
		update_option(self::$meta_key, $all_slideshows);
		
		return 1;
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

			$req = array('bu_slides', 'bu_slideshow_id', 'bu_slideshow_name');
			foreach ($req as $r) {
				if (!isset($_POST[$r])) {
					$update = 0;
					$msg = sprintf('Could not update slideshow: missing %s.', $r);
				}

				if ($r !== 'bu_slides') {
					if (empty($_POST[$r])) {
						$update = 0;
						$msg = sprintf('Could not update slideshow: %s invalid.', $r);
					}
				}
			}

			if ($update) {
				if (self::update_slideshow($_POST)) {
					$msg = "Slideshow updated successfully.";
				}
			}
			
		}
		
		if (isset($_GET['bu_slideshow_id']) && !empty($_GET['bu_slideshow_id'])) {
			
			$id = intval($_GET['bu_slideshow_id']);
			
			if (self::slideshow_exists($id)) {
				self::edit_slideshow_ui($id);
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
		if ($show) {
			require_once BU_SLIDESHOW_BASEDIR . 'edit-slideshow-ui.php';
		}
	}
	
	/**
	 * AJAX handler for adding a new slide.
	 * @todo add nonce check
	 */
	static public function add_slide_ajax() {
		if (!current_user_can(self::$min_cap)) {
			return;
		}
			
		if (!isset($_POST['order']) || !is_numeric($_POST['order'])) {
			return;
		}
		
		self::get_slide_markup_admin($_POST['order']);
		exit;
	}
	
	/**
	 * Echoes markup for a single slide for use in the admin.
	 * 
	 * @param int $order
	 * @param array $slide
	 */
	static public function get_slide_markup_admin($order, $slide = null) { 
		if (!is_array($slide)) {
			$slide = array(
				"image_id" => 0,
				"image_size" => 'full',
				"caption" => array(
					"title" => 'Untitled Slide',
					"link" => '',
					"text" => ''
				)
			);
		} else {
			$slide['caption'] = stripslashes_deep($slide['caption']);
		}
		
		$img_thumb = '';
		
		if ($slide['image_id']) {
			$img_thumb = wp_get_attachment_image($slide['image_id'], 'bu-slideshow-thumb');
		}
		
		include BU_SLIDESHOW_BASEDIR . 'interface/single-slide-admin.php';
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
		/*
		* Following is a more graceful way to load the js on-demand, but it won't work til after we 
		* migrate to a more recent WP version. (3.3+)
		* 
		* wp_enqueue_script('jquery-cycle');
		* wp_enqueue_script('bu-slideshow');
		*/
		
		/* set flag so that scripts are loaded only when shortcode present (old fashioned way, 
		 * remove after we update WP) */
		global $bu_slideshow_loadscripts;
		$bu_slideshow_loadscripts = 1;
		
		$att_defaults = array(
			'show_id' => 0,
			'show_nav' => 1
		);
		$atts = shortcode_atts($att_defaults, $atts);
		
		$html = self::get_show_markup($atts);

		echo $html;
	}
	
	/**
	 * Returns markup for the slideshow.
	 * @param array $atts
	 * @return string
	 */
	static public function get_show_markup($atts) {
		$id = intval($atts['show_id']);
		
		// liberally accept args
		$falsish = array('0', 'false', 'FALSE', 'no', 'none');
		foreach ($falsish as $falsey) {
			if ($atts['show_nav'] === $falsey) {
				$atts['show_nav'] = 0;
			}
		}
		
		$show = self::get_slideshow($id);
		if (!$show) {
			return;
		}
		
		$html = '<section class="bu-slideshow-container">
			<ol class="bu-slideshow" id="bu-slideshow-' . $id . '" aria-hidden="true">';
		
		foreach ($show["slides"] as $i => $slide) {
			$html .= self::get_slide_markup($slide);
		}

		$html .= '</ol>';
		
		if ($atts['show_nav']) {
			
			$html .= '<ol class="bu-slideshow-navigation" id="bu-slideshow-nav-' . $id . '" aria-hidden="true">';
			for ($i = 1; $i <= count($show["slides"]); $i++) {
				$html .= '<li><a href="#"><span>' . $i . '</span></a></li> ';
			}

			$html .= '</ol>';
			
		}
			
		$html .= '</section>';	
		
		return $html;
	}
	
	/**
	 * Returns markup for one public-facing slide.
	 * 
	 * @param array $slide
	 * @return string
	 */
	static public function get_slide_markup($slide) {
		/*
		 * <li class="slide">
				<img src="<?php echo TW_THEME_URI; ?>/images/temp/feature-1.jpg" alt="A technician repairing a computer" />
				<!-- <div class="container"> -->
					<div class="hero-caption">
						<p class="hero-caption-title">Caption Title Goes here</p>
						<p>Suspendisse mi nulla, accumsan eget ultricies quis, congue vel leo. Integer non metus nec ante vulputate venenatis. <a href="">Nunc laoreet</a>, est id consectetur consectetur.</p>
					</div>
				<!-- </div> -->
			</li>
		 */
		if (empty($slide) || !is_array($slide)) {
			return '';
		}
		
		$slide['caption'] = stripslashes_deep($slide['caption']);
		
		$img_arr = wp_get_attachment_image_src($slide['image_id'], $slide['image_size']);
		$img_alt = self::get_image_alt($slide['image_id']);
		
		$html = '<li class="slide">';
		$html .= '<img src="' . esc_url($img_arr[0]) . '" alt="' . esc_attr($img_alt) . '" />';
		$html .= '<div class="bu-slide-caption">';
		
		$html .= '<p class="bu-slide-caption-title">';
		if (!empty($slide['caption']['link'])) {
			$html .= '<a href="' . esc_url($slide['caption']['link']) . '">';
			$html .= esc_html(strip_tags($slide['caption']['title'])) . '</a></p>';
		} else {
			$html .= esc_html(strip_tags($slide['caption']['title'])) . '</p>';
		}
		
		if (!empty($slide['caption']['text'])) {
			$text = self::trim_slide_caption($slide['caption']['text']);
			$html .= '<p class="bu-slide-caption-text">' . wp_kses_data($text) . '</p>';
		}
		$html .= '</div></li>';
		
		return $html;
	}
	
	/**
	 * Trim slide captions to a maximum # of words specified by $slide_excerpt_length
	 */
	static public function trim_slide_caption($text) {
		$words = explode(' ', $text);
		if (count($words) > self::$slide_excerpt_length) {
			$text = implode(' ', array_splice($words, 0, self::$slide_excerpt_length)) . '...';
		}
		
		return $text;
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
		
		return BU_Slideshow::shortcode_handler($args);
	}
}