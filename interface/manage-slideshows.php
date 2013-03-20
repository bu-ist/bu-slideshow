<div class="wrap">
	<h2>Manage Slideshows</h2>
	<div id="bu-slideshow-slides">
		<ul class="widefat" id="bu-slideshow-manage">
			
			<?php foreach($slideshows as $id => $show) { ?>
			
			<li>
				<span class="alignleft"><a href="<?php echo self::$edit_url . '&amp;bu_slideshow_id=' . $id; ?>"><?php echo esc_html(stripslashes($show->name)); ?></a></span>
				<span class="alignright">
					<a class="button" href="<?php echo self::$edit_url . '&amp;bu_slideshow_id=' . $id; ?>">Edit</a> 
					<a href="<?php echo self::$preview_url . '&amp;bu_slideshow_id=' . $id; ?>" class="bu-slideshow-preview button">Preview</a> 
					<a href="#" class="bu-slideshow-delete button" data-slideshow-id="<?php echo $id; ?>">Delete</a></span>
			</li>
			
			<?php } 
			if (count($slideshows) < 1) { ?>
				
			<li>
				<p>No slideshows yet.</p>
				<p><a class="button" href="<?php echo self::$add_url; ?>">Add a slideshow</a></p>
			</li>
			
			<?php } ?>
			
		</ul>
		<?php wp_nonce_field('bu_delete_slideshow', 'bu_slideshow_nonce', false, true); ?>
	</div>
</div>