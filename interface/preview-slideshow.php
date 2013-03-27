<div class="wrap">
	<h2>Preview Slideshow</h2>
	
	<div id="bu-slideshow-preview">
		
	<?php if (isset($msg) && $msg) { ?>
		<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
		
	<?php if ($id) { ?>
		
		<?php self::shortcode_handler(array("show_id" => $id)); ?>
		
	<?php } ?>
		<h4>Note</h4>
		<p>This preview uses the default settings. When you add this slideshow to a page, you will be able to customize the display settings.</p>
		<p>Return to <a href="<?php echo self::$manage_url; ?>">Manage Slideshows</a></p>
	</div>
	<?php 
	/* load scripts for shortcode */
	BU_Slideshow::conditional_script_load(); ?>
</div>