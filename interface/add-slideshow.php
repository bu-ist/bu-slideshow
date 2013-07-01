<div class="wrap">
	<h2><?php _e('Add Slideshow', BU_SSHOW_LOCAL); ?></h2>
	
	<div id="bu-slideshow-new">
		
		<?php if (isset($msg) && !empty($msg)) { ?>
		<div class="updated"><p><?php echo $msg; ?></p></div>
		<?php } ?>
		
		<form method="post" action="" id="bu-slideshow-newform">
			<h3><?php _e('New Slideshow', BU_SSHOW_LOCAL); ?></h3>
			<p><label for="bu-new-slideshow-name"><?php _e('Slideshow Name', BU_SSHOW_LOCAL); ?></label>
			<input type="text" name="bu-new-slideshow-name" id="bu-new-slideshow-name" /></p>
			<p>
				<?php wp_nonce_field('bu_add_slideshow', 'bu_slideshow_nonce', false, true); ?>
				<input class="button-primary" type="submit" name="bu-new-slideshow-create" id="bu-new-slideshow-create" value="<?php _e('Create Slideshow', BU_SSHOW_LOCAL); ?>" /></p>
		</form>
	</div>
	
</div>