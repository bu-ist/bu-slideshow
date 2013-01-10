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
		<p>The slideshow may look different on your site's pages, depending on your site's theme.</p>
		<p>Return to <a href="<?php echo self::$manage_url; ?>">Manage Slideshows</a></p>
	</div>
	<?php 
	/* load scripts for shortcode */
	do_action('wp_footer'); ?>
</div>