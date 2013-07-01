<div class="wrap">
	<h2><?php _e('Manage Slideshows', BU_SSHOW_LOCAL); ?> <a href="<?php echo self::$add_url; ?>" class="button">Add New</a></h2>
	<div id="bu-slideshow-slides">
		<ul class="widefat" id="bu-slideshow-manage">
			
			<?php 
			if (is_array($slideshows) && count($slideshows) > 0) {
				foreach($slideshows as $id => $show) { ?>

				<li>
					<span class="alignleft"><a href="<?php echo self::$edit_url . '&amp;bu_slideshow_id=' . $id; ?>"><?php echo esc_html(stripslashes($show->name)); ?></a></span>
					<span class="alignright">
						<a class="button" href="<?php echo self::$edit_url . '&amp;bu_slideshow_id=' . $id; ?>"><?php _e('Edit', BU_SSHOW_LOCAL); ?></a> 
						<a href="<?php echo self::$preview_url . '&amp;bu_slideshow_id=' . $id; ?>" class="bu-slideshow-preview button"><?php _e('Preview', BU_SSHOW_LOCAL); ?></a> 
						<a href="#" class="bu-slideshow-delete button" data-slideshow-id="<?php echo $id; ?>"><?php _e('Delete', BU_SSHOW_LOCAL); ?></a></span>
				</li>

				<?php } 
			} else { ?>
				
			<li>
				<p><?php _e('No slideshows yet.', BU_SSHOW_LOCAL); ?></p>
				<p><a class="button" href="<?php echo self::$add_url; ?>"><?php _e('Add a slideshow', BU_SSHOW_LOCAL); ?></a></p>
			</li>
			
			<?php } ?>
			
		</ul>
		<?php wp_nonce_field('bu_delete_slideshow', 'bu_slideshow_nonce', false, true); ?>
	</div>
</div>