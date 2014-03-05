<div class="wrap">
	<p><a href="<?php echo BU_Slideshow::$manage_url; ?>">&laquo; <?php _e('Back to Manage Slideshows', BU_SSHOW_LOCAL); ?></a></p>
	<h2><?php _e("Add Slideshow", BU_SSHOW_LOCAL); ?></h2>
	
	<?php if ( $msg ): ?>
	<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php endif; ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader">
			<input type="hidden" name="bu_slideshow_save_show" value="1" />
			<?php 
				wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); 
				require_once plugin_dir_path(__FILE__) . 'edit-slideshow-form.php'; 
			?>
			<p>
				<input type="submit" value="<?php _e('Create Slideshow', BU_SSHOW_LOCAL); ?>" id="bu-slideshow-editform-submit" class="button-primary" />
			</p>
		</form>
	</div>
</div>