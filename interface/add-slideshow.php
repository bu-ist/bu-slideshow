<div class="wrap">
	<p><a href="<?php echo esc_url(BU_Slideshow::$manage_url); ?>">&laquo; <?php esc_html_e('Back to Manage Slideshows', 'bu-slideshow'); ?></a></p>
	<h2><?php esc_html_e("Add Slideshow", 'bu-slideshow'); ?></h2>
	
	<?php if ( $msg ): ?>
	<div class="updated"><p><?php echo wp_kses_post($msg); ?></p></div>
	<?php endif; ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>&noheader">
			<input type="hidden" name="bu_slideshow_save_show" value="1" />
			<?php 
				wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); 
				require_once plugin_dir_path(__FILE__) . 'edit-slideshow-form.php'; 
			?>
			<p>
				<input type="submit" value="<?php esc_html_e('Create Slideshow', 'bu-slideshow'); ?>" id="bu-slideshow-editform-submit" class="button-primary" />
			</p>
		</form>
	</div>
</div>