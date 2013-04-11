<div class="wrap">
	<p><a href="<?php echo BU_Slideshow::$manage_url; ?>">&laquo; <?php _e('Back to Manage Slideshows', BU_S_LOCAL); ?></a></p>
	<h2><?php _e(sprintf("Edit Slideshow: %s", esc_html(stripslashes($this->name))), BU_S_LOCAL); ?></h2>
	
	<?php if (isset($msg) && $msg) { ?>
	<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="">
			<p>
				<label for="bu_slideshow_name"><strong><?php _e('Slideshow Name', BU_S_LOCAL); ?>: </strong></label>
				<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo esc_attr(stripslashes($this->name)); ?>" />
			</p>
			
			<p><strong><?php _e('Slides', BU_S_LOCAL); ?>:</strong><br />
				<em><?php _e('Drag to reorder, expand to edit', BU_S_LOCAL); ?></em>
			</p>
			<p>
				<a href="#" id="bu-slideshow-add-slide" class="button"><?php _e('Add New Slide', BU_S_LOCAL); ?></a>
			</p>
			<div id="bu-slideshow-slidelist">
				<ul>
				<?php 
				if (is_array($this->slides) && count($this->slides) > 0) {
					foreach ($this->slides as $index => $slide) {
						$slide->set_order($index);
						$slide->set_view('admin');
						echo $slide->get();
					}
				} else {
					$slide = new BU_Slide(array('view' => 'admin'));
					echo $slide->get();
				} ?>
				</ul>
			</div>
			<p>
				<input type="hidden" name="bu_slideshow_edit_show" value="1" />
				<input type="hidden" name="bu_slideshow_id" value="<?php echo esc_attr($this->id); ?>" />
				<?php wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); ?>
				<input type="submit" value="<?php _e('Save Changes', BU_S_LOCAL); ?>" id="bu-slideshow-editform-submit"class="button-primary" />
			</p>
		</form>
	</div>
</div>