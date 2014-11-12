<?php
require_once plugin_dir_path(__FILE__) . 'bu-slideshow.php';
require_once plugin_dir_path(__FILE__) . 'class-bu-slide.php';

if (!defined('BU_SSHOW_LOCAL')) {
	define('BU_SSHOW_LOCAL', 'BU_Slideshow');
}

class BU_Slideshow_Instance {
	
	public $view;
	public $name = '';
	public $id = 1;
	public $height = 0;
	public $slides = array();
	
	static $classes = array('bu-slideshow');
	static $id_prefix = 'bu-slideshow-';
	static $views = array('admin', 'public');

	/**
	 * @param array $args
	 */
	public function __construct($args = array()) {
		if (!is_array($args)) {
			$args = array();
		}
		
		$this->name = __('Untitled Slideshow', BU_SSHOW_LOCAL);
		$id = BU_Slideshow::get_new_id();
		$this->id = $id;
		
		if (isset($args['view'])) {
			$this->set_view($args['view']);
		}
	}
	
	/**
	 * Set the view type for this slideshow instance.
	 * @param string $view
	 */
	public function set_view($view) {
		if (!in_array($view, self::$views)) {
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
	 * Set the height of the slideshow.
	 * @param int $height
	 */
	public function set_height($height) {
		$height = intval($height);
		$this->height = $height;
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
			if ((get_class($slide) !== 'BU_Slide')) {
				unset($slides[$i]);
			}
		}
		
		$this->slides = $slides;
	}
	
	/**
	 * Save changes to this slideshow.
	 * 
	 * @return int
	 */
	public function update() {
		
		$all_slideshows = BU_Slideshow::get_slideshows();
		$all_slideshows[$this->id] = $this;

		if( version_compare( get_bloginfo('version'), '3.6', '>=') ){
			update_option(BU_Slideshow::$meta_key, $all_slideshows);
		} else {
			delete_option(BU_Slideshow::$meta_key);
			add_option(BU_Slideshow::$meta_key, $all_slideshows);
		}

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
				$msg = $args['msg'] ? $args['msg'] : '';
				$name = $this->name;
				$height = $this->height;
				$slides = $this->slides;
				ob_start();
				
				include BU_SLIDESHOW_BASEDIR . 'interface/edit-slideshow-ui.php';
				
				$html = ob_get_contents();
				ob_end_clean();
				return $html;
				
				break;
			
			case 'public':

				$show_id = esc_attr(self::$id_prefix . $this->id);
				$show_name = $this->name ? str_replace(' ', '-', strtolower(stripslashes($this->name))) : '';
				
				$width = $args['width'] === 'auto' ? 'auto' : $args['width'] . 'px';
				$height = ($this->height > 0) ? 'height: '.intval($this->height).'px;' : '';
				$alignment = strtolower($args['align']);

				if ($width !== 'auto') {

					if ($alignment === 'left') {
						$styles = sprintf(' style="width: %s; %s; float: left;"', $width, $height);	
					} elseif ($alignment === 'right') {
						$styles = sprintf(' style="width: %s; %s; float: right;"', $width, $height);
					} else {
						$styles = sprintf(' style="width: %s; %s; margin-left: auto; margin-right: auto;"', $width, $height);
					}

				} else {
					$styles = sprintf(' style="width: %s; %s"', $width, $height);
				}
				
				$container_class = 'bu-slideshow-container';
				$container_class .= ' ' . $show_name;
				$container_class .= $args['autoplay'] ? ' autoplay' : '';

				// deliberately allowing custom values here
				$ul_classes = self::$classes;
				$ul_classes[] = 'transition-' . $args['transition']; 
				$ul_class_str = esc_attr(join(' ', $ul_classes));
				$name_att = $show_name ? sprintf(' data-slideshow-name="%s" data-slideshow-delay="%d"', $show_name, $args['transition_delay']) : '';

				$html = sprintf('<div class="%s" id="%s"%s%s>', esc_attr($container_class), esc_attr(self::$id_prefix . 'container-' . $this->id), $name_att, $styles);
				$html .= "<div class='slideshow-loader active'><div class='loader-animation'></div><p>" . __("loading slideshow...") . "</p></div>";
				$html .= sprintf('<div class="bu-slideshow-slides"><ul class="%s" id="%s">', $ul_class_str, $show_id);
				
				if( $args['shuffle'] ){
					shuffle( $this->slides );
				}

				foreach ($this->slides as $i => $slide) {
					$id_prefix = self::$id_prefix . $this->id;
					
					$slide->set_order($i);
					$slide->set_view($this->view);
					
					$slide_args = array('id_prefix' => $id_prefix);
					$html .= $slide->get($slide_args);
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
			$html .= sprintf('<li><a href="#" id="pager-%s" class="%s" aria-hidden="true"><span>%s</span></a></li> ', $i, $a_class, $i);;
		}

		$html .= '</ul></div>';
		
		return $html;
	}
	
}
