<div class="wrap">
	<h2><?php esc_html_e('Preview Slideshow', BU_SSHOW_LOCAL); ?></h2>
	
	<div id="bu-slideshow-preview">
		
	<?php if (isset($msg) && $msg) { ?>
		<div class="updated"><p><?php echo esc_html($msg); ?></p></div>
	<?php } ?>
		
	<?php if ($id) { ?>
		
		<?php echo wp_kses_post(self::shortcode_handler(array("show_id" => $id))); ?>
		
	<?php } ?>
		<h4><?php esc_html_e('Note', BU_SSHOW_LOCAL); ?></h4>
		<p><?php esc_html_e('This preview uses the default settings. When you add this slideshow to a page, you will be able to customize the display settings.', BU_SSHOW_LOCAL); ?></p>
		<p><?php echo wp_kses_post(sprintf('Return to <a href="%s">Manage Slideshows</a></p>', esc_url(self::$manage_url)), BU_SSHOW_LOCAL); ?>
	</div>
	<?php 
	/* load scripts for shortcode */
	BU_Slideshow::conditional_script_load(); ?>
</div>