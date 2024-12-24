<div class="wrap">
	<p><a href="<?php echo esc_url(BU_Slideshow::$manage_url); ?>">&laquo; <?php esc_html_e('Back to Manage Slideshows', 'bu-slideshow'); ?></a></p>
	<h2><?php esc_html_e("Edit Slideshow: " . esc_html(stripslashes($this->name)), 'bu-slideshow'); ?></h2>
	
	<?php if ( $msg ): ?>
	<div class="updated"><p><?php echo wp_kses_post($msg); ?></p></div>
	<?php endif; ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="">
			<input type="hidden" name="bu_slideshow_save_show" value="1" />
			<input type="hidden" name="bu_slideshow_id" value="<?php echo esc_attr($this->id); ?>" />
			<?php 
				wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); 
				require_once plugin_dir_path(__FILE__) . 'edit-slideshow-form.php'; 
			?>
			<p>
				<input type="submit" value="<?php esc_html_e('Save Changes', 'bu-slideshow'); ?>" id="bu-slideshow-editform-submit" class="button-primary" />
			</p>
		</form>
	</div>
</div>