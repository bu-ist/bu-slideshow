<div class="wrap">
	<h2><?php _e('Preview Slideshow', BU_SSHOW_LOCAL); ?></h2>
	
	<div id="bu-slideshow-preview">
		
	<?php if (isset($msg) && $msg) { ?>
		<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
		
	<?php if ($id) { ?>
		
		<?php echo self::shortcode_handler(array("show_id" => $id)); ?>
		
	<?php } ?>
		<h4><?php _e('Note', BU_SSHOW_LOCAL); ?></h4>
		<p><?php _e('This preview uses the default settings. When you add this slideshow to a page, you will be able to customize the display settings.', BU_SSHOW_LOCAL); ?></p>
		<p><?php _e(sprintf('Return to <a href="%s">Manage Slideshows</a></p>', self::$manage_url), BU_SSHOW_LOCAL); ?>
	</div>
	<?php 
	/* load scripts for shortcode */
	BU_Slideshow::conditional_script_load(); ?>
</div>