<?php 
require_once plugin_dir_path(__FILE__) . 'bu-slideshow.php';
require_once plugin_dir_path(__FILE__) . 'class-bu-slideshow.php';

class BU_Slide {
	
	public $image_id = 0;
	public $image_size = 'full';
	public $caption = array(
		'title' => 'Untitled Slide',
		'link' => '',
		'text' => ''
	);
	public $order = 0;
	public $view;
	
	static public $views = array('admin', 'public');
	static public $slide_excerpt_length = 20;
	
	public function __construct($args) {
		foreach ($this as $prop => $val) {
			if (isset($args[$prop])) {
				$this->$prop = $args[$prop];
			}
		}
	}
	
	/**
	 * Set the order of this slide in a slideshow.
	 * @param int $order
	 */
	public function set_order($order) {
		$this->order = intval($order);
	}
	
	/**
	 * Set the view type.
	 * @param string $view
	 */
	public function set_view($view) {
		if (in_array($view, self::$views)) {
			$this->view = $view;
		}
	}
	
	/**
	 * Returns markup for one slide. If view is public, this is the slide markup;
	 * if view is admin, this is the markup to edit slide.
	 * @param array $args
	 * @return string
	 */
	public function get($args = array()){
		
		switch ($this->view) {
			
			case 'admin':

				$img_thumb = '';
				$this->caption = stripslashes_deep($this->caption);

				if ($this->image_id) {
					$img_thumb = wp_get_attachment_image($this->image_id, 'bu-slideshow-thumb');
				}

				ob_start();
				include BU_SLIDESHOW_BASEDIR . 'interface/single-slide-admin.php';
				$html = ob_get_contents();
				ob_end_clean();
				
				return $html;
				
				break;
			
			case 'public':

				$slide_id = $args['id_prefix'] . '_' . $this->order;
				$haslink = false;

				$this->caption = stripslashes_deep($this->caption);

				$html = sprintf('<li id="%s" class="slide">', $slide_id);
				$html .= '<div class="bu-slide-container">';
				
				$html .= $this->get_image_html();
				
				$html .= $this->get_caption_html();
				
				$html .= '</div></li>';

				return $html;
				
				break;
			
			default:
				break;
		}
	}
	
	public function get_caption_html() {
		$html = '';
		// If no title or text, bail
		if ( ( !isset($this->caption['title']) || empty($this->caption['title']) ) && 
				( !isset($this->caption['text']) || empty($this->caption['text']) ) ) {
			return $html;
		}
		
		$html .= '<div class="bu-slide-caption">';
		
		if (isset($this->caption['title']) && !empty($this->caption['title'])) {
			$html .= '<p class="bu-slide-caption-title">';
			
			$title_str = esc_html(strip_tags($this->caption['title']));
			
			if (isset($this->caption['link']) && $this->caption['link']) {
				$html .= sprintf('<a href="%s">%s</a></p>', esc_url($this->caption['link']), $title_str);
			} else {
				$html .= $title_str . '</p>';
			}
		}

		if (isset($this->caption['text']) && !empty($this->caption['text'])) {
			$text = $this->trim_slide_caption($this->caption['text']);
			$html .= sprintf('<p class="bu-slide-caption-text">%s</p>', wp_kses_data($text));
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_image_html() {
		$html = '';
		
		if ($this->image_id) {
			$img_arr = wp_get_attachment_image_src($this->image_id, $this->image_size);

			if (is_array($img_arr) && !empty($img_arr)) {
				$img_alt = BU_Slideshow::get_image_alt($this->image_id);
				$img_str = sprintf('<img src="%s" alt="%s" />', esc_url($img_arr[0]), esc_attr($img_alt));

				if (isset($this->caption['link']) && $this->caption['link']) {
					$html .= sprintf('<a href="%s">%s</a>', esc_url($this->caption['link']), $img_str);
				} else {
					$html .= $img_str;
				}
			}
		}
		
		return $html;
	}
	
	/**
	 * Trims the caption text displayed, if neccesary
	 * @param string $text
	 * @return string
	 */
	static protected function trim_slide_caption($text) {
		$words = explode(' ', $text);
		if (count($words) > self::$slide_excerpt_length) {
			$text = implode(' ', array_splice($words, 0, self::$slide_excerpt_length)) . '...';
		}
		
		return $text;
	}
	
}