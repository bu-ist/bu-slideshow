<?php
require_once plugin_dir_path(__FILE__) . 'bu-slideshow.php';

class BU_Slideshow_Instance {
	
	public $view;
	public $name;
	public $id;
	public $slides;
	
	static $defaults = array(
		'name' => 'Untitled Slideshow',
		'id' => 1,
		'slides' => array(),
		'view' => 'public'
	);
	
	static $classes = array('bu-slideshow');
	static $id_prefix = 'bu-slideshow-';
	static $slide_excerpt_length = 20;

	/**
	 * Retrieves or creates new slideshow.
	 * @param array $args
	 */
	public function __construct($args) {
		
		$args = wp_parse_args($args, self::$defaults);
		extract(array_intersect_key($args, self::$defaults));
		
		$id = intval($id);
		
		if (!is_int($id)) {
			return new WP_Error('invalid arguments', 'Invalid arguments supplied to slideshow constructor.');
		}
			
		if (BU_Slideshow::slideshow_exists($id)) {
			$all_slideshows = get_option(BU_Slideshow::$meta_key, array());
			$slideshow = $all_slideshows[$id];
		} else {
			$slideshow = $args;
		}
		
		$this->init($slideshow);
		$this->set_view($view);
	}
	
	/**
	 * Populates slideshow properties
	 * @param array $args
	 */
	protected function init($args = array()) {
		if (!is_array($args) || empty($args)) {
			return new WP_Error('invalid arguments', 'Invalid arguments supplied to slideshow init.');
		}
		
		foreach ($args as $key => $val) {
			$this->$key = $val;
		}
	}
	
	/**
	 * Set the view type for this slideshow instance.
	 * @param string $view
	 */
	public function set_view($view) {
		$views = array(
			'admin',
			'public'
		);
		
		if (!in_array($view, $views)) {
			return;
		}
		
		$this->view = $view;
	}
	
	/**
	 * Set the name of the slideshow.
	 * @param string $name
	 */
	public function set_name($name) {
		$name = trim((string) $name);
		if (!$name) {
			return;
		}
		
		$this->name = $name;
	}
	
	/**
	 * Set slides.
	 * @param array $slides
	 */
	public function set_slides($slides = array()) {
		
		if (!is_array($slides)) {
			return;
		}
		
		// sanity check
		foreach ($slides as $i => $slide) {
			if (!is_array($slide)) {
				unset($slides[$i]);
			}
		}
		
		$this->slides = $slides;
	}
	
	/**
	 * Save changes to this slideshow.
	 * @return int
	 */
	public function update() {
		
		$all_slideshows = BU_Slideshow::get_slideshows();

		$updated_show = array(
			"name" => $this->name,
			"id" => $this->id,
			"slides" => array()
		);
		
		foreach ($this->slides as $i => $slide) {
			$slide['caption']['title'] = trim(strip_tags($slide['caption']['title']));
			$slide['caption']['link'] = trim(esc_url($slide['caption']['link']));
			$slide['caption']['text'] = trim(wp_kses_data($slide['caption']['text']));
			
			$updated_show["slides"][$i] = $slide;
		}
		
		$all_slideshows[$this->id] = $updated_show;
		
		update_option(BU_Slideshow::$meta_key, $all_slideshows);
		
		return 1;
	}
	
	/**
	 * Returns markup for the slideshow. If view is public, this is the slideshow;
	 * if view is admin, it is the markup for editing the slideshow.
	 * @param array $args
	 * @return string
	 */
	public function get($args = array()) {
		
		switch ($this->view) {
			
			case 'admin':
				
				ob_start();
				
				include BU_SLIDESHOW_BASEDIR . 'edit-slideshow-ui.php';
				
				$html = ob_get_contents();
				ob_end_clean();
				return $html;
				
				break;
			
			case 'public':

				$show_id = esc_attr(self::$id_prefix . $this->id);
				
				$container_class = $args['autoplay'] ? ' autoplay' : '';

				// deliberately allowing custom values here
				$ul_classes = self::$classes;
				$ul_classes[] = 'transition-' . $args['transition']; 
				$ul_class_str = esc_attr(join(' ', $ul_classes));

				$html = sprintf('<div class="bu-slideshow-container%s" id="%s">', esc_attr($container_class), esc_attr(self::$id_prefix . 'container-' . $this->id));
				$html .= sprintf('<div class="bu-slideshow-slides"><ul class="%s" id="%s" aria-hidden="true">', $ul_class_str, $show_id);

				foreach ($this->slides as $i => $slide) {
					$slide_args = array('slide' => $slide, 'order' => $i);
					$html .= $this->get_slide($slide_args);
				}

				$html .= '</ul></div>';

				// slideshow nav
				if ($args['show_nav']) {
					$nav_args = array(
						'style' => $args['nav_style']
					);
					
					$html .= $this->get_nav($nav_args);
				}
				
				// forward/back arrows
				if ($args['show_arrows']) {
					$html .= sprintf('<div class="bu-slideshow-arrows" id="%s_arrows"><a class="bu-slideshow-arrow-left" href="#"></a><a class="bu-slideshow-arrow-right" href="#"></a></div>', $show_id);
				}

				$html .= '</div>';	

				return $html;
				
				break;
			
			default:
				break;
			
		}
	}
	
	/**
	 * Returns markup for one slide. If view is public, this is the slide markup;
	 * if view is admin, this is the markup to edit slide.
	 * @param array $args
	 * @return string
	 */
	public function get_slide($args){
		
		$defaults = array(
			'slide' => null,
			'order' => 0
		);
		extract(wp_parse_args($args, $defaults));
		
		switch ($this->view) {
			
			case 'admin':
				
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

				ob_start();
				include BU_SLIDESHOW_BASEDIR . 'interface/single-slide-admin.php';
				$html = ob_get_contents();
				ob_end_clean();
				
				return $html;
				
				break;
			
			case 'public':

				$haslink = false;

				if (!empty($slide['caption']['link'])) {
					$haslink = true;
				}

				$slide['caption'] = stripslashes_deep($slide['caption']);

				$img_arr = wp_get_attachment_image_src($slide['image_id'], $slide['image_size']);
				$img_alt = BU_Slideshow::get_image_alt($slide['image_id']);

				$slide_id = self::$id_prefix . $this->id . '_' . $order;

				$html = sprintf('<li id="%s" class="slide">', $slide_id);
				$html .= '<div class="bu-slide-container">';
				$img_str = sprintf('<img src="%s" alt="%s" /></a>', esc_url($img_arr[0]), esc_attr($img_alt));
				
				if ($haslink) {
					$html .= sprintf('<a href="%s">%s</a>', esc_url($slide['caption']['link']), $img_str);
				} else {
					$html .= $img_str;
				}
				
				$html .= '<div class="bu-slide-caption">';

				$html .= '<p class="bu-slide-caption-title">';
				$title_str = esc_html(strip_tags($slide['caption']['title']));
				
				if ($haslink) {
					$html .= sprintf('<a href="%s">%s</a></p>', esc_url($slide['caption']['link']), $title_str);
				} else {
					$html .= $title_str . '</p>';
				}

				if (!empty($slide['caption']['text'])) {
					$text = $this->trim_slide_caption($slide['caption']['text']);
					$html .= sprintf('<p class="bu-slide-caption-text">%s</p>', wp_kses_data($text));
				}
				$html .= '</div></div></li>';

				return $html;
				
				break;
			
			default:
				break;
		}
	}
	
	/**
	 * Returns markup for slideshow navigation.
	 * @param array $args
	 * @return string
	 */
	protected function get_nav($args) {
		extract($args);
		$html = sprintf('<div class="bu-slideshow-navigation-container"><ul class="bu-slideshow-navigation %s" id="bu-slideshow-nav-%s" aria-hidden="true">', 'nav-' . $style, $this->id);
		
		$num_slides = count($this->slides);
		for ($i = 1; $i <= $num_slides; $i++) {
			$a_class = $i === 1 ? ' active' : '';
			$html .= sprintf('<li><a href="#" id="pager-%s" class="%s"><span>%s</span></a></li> ', $i, $a_class, $i);;
		}

		$html .= '</ul></div>';
		
		return $html;
	}

	/**
	 * Trims the caption text displayed, if neccesary
	 * @param string $text
	 * @return string
	 */
	protected function trim_slide_caption($text) {
		$words = explode(' ', $text);
		if (count($words) > self::$slide_excerpt_length) {
			$text = implode(' ', array_splice($words, 0, self::$slide_excerpt_length)) . '...';
		}
		
		return $text;
	}
	
}