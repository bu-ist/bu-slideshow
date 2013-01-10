<div class="wrap">
	<p><a href="<?php echo self::$manage_url; ?>">&laquo; Back to Manage Slideshows</a></p>
	<h2>Edit Slideshow: <?php echo esc_html($show["name"]); ?></h2>
	
	<?php if (isset($msg) && $msg) { ?>
	<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="">
			<p>
				<label for="bu_slideshow_name"><strong>Slideshow Name: </strong></label>
				<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo esc_attr($show["name"]); ?>" />
			</p>
			
			<p><strong>Slides:</strong><br />
				<em>Drag to reorder, expand to edit</em>
			</p>
			<p>
				<a href="#" id="bu-slideshow-add-slide" class="button">Add New Slide</a>
			</p>
			<div class="bu-slideshow-slidelist">
				<ul>
				<?php foreach ($show['slides'] as $order => $slide) { 
					self::get_slide_markup_admin($order, $slide);
				}
				if (count($show['slides']) < 1) {
					self::get_slide_markup_admin(0);
				} ?>
				</ul>
			</div>
			<p>
				<input type="hidden" name="bu_slideshow_edit_show" value="1" />
				<input type="hidden" name="bu_slideshow_id" value="<?php echo esc_attr($show["id"]); ?>" />
				<?php wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); ?>
				<input type="submit" value="Save Changes" id="bu-slideshow-editform-submit"class="button-primary" />
			</p>
		</form>
	</div>
</div>