<div class="wrap">
	<h2><?php esc_html_e('Manage Slideshows', 'bu-slideshow'); ?> <a href="<?php echo esc_url(self::$add_url); ?>" class="button">Add New</a></h2>
	<div id="bu-slideshow-slides">
		<ul class="widefat" id="bu-slideshow-manage">
			
			<?php 
			if (is_array($slideshows) && count($slideshows) > 0) {
				foreach($slideshows as $id => $show) { ?>

				<li>
					<span class="alignleft"><a href="<?php echo esc_url(self::$edit_url) . '&amp;bu_slideshow_id=' . esc_attr($id); ?>"><?php echo esc_html(stripslashes($show->name)); ?></a></span>
					<span class="alignright">
						<a class="button" href="<?php echo esc_url(self::$edit_url) . '&amp;bu_slideshow_id=' . esc_attr($id); ?>"><?php esc_html_e('Edit', 'bu-slideshow'); ?></a> 
						<a href="<?php echo esc_url(self::$preview_url) . '&amp;bu_slideshow_id=' . esc_attr($id); ?>" class="bu-slideshow-preview button"><?php esc_html_e('Preview', 'bu-slideshow'); ?></a> 
						<a href="#" class="bu-slideshow-delete button" data-slideshow-id="<?php echo esc_attr($id); ?>"><?php esc_html_e('Delete', 'bu-slideshow'); ?></a></span>
				</li>

				<?php } 
			} else { ?>
				
			<li>
				<p><?php esc_html_e('No slideshows yet.', 'bu-slideshow'); ?></p>
				<p><a class="button" href="<?php echo esc_url(self::$add_url); ?>"><?php esc_html_e('Add a slideshow', 'bu-slideshow'); ?></a></p>
			</li>
			
			<?php } ?>
			
		</ul>
		<?php wp_nonce_field('bu_delete_slideshow', 'bu_slideshow_nonce', false, true); ?>
	</div>
</div>