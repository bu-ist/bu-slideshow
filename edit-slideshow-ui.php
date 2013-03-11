<div class="wrap">
	<?php //var_dump($this); ?>
	<p><a href="<?php echo BU_Slideshow::$manage_url; ?>">&laquo; Back to Manage Slideshows</a></p>
	<h2>Edit Slideshow: <?php echo esc_html($this->name); ?></h2>
	
	<?php if (isset($msg) && $msg) { ?>
	<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="">
			<p>
				<label for="bu_slideshow_name"><strong>Slideshow Name: </strong></label>
				<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo esc_attr($this->name); ?>" />
			</p>
			
			<p><strong>Slides:</strong><br />
				<em>Drag to reorder, expand to edit</em>
			</p>
			<p>
				<a href="#" id="bu-slideshow-add-slide" class="button">Add New Slide</a>
			</p>
			<div class="bu-slideshow-slidelist">
				<ul>
				<?php foreach ($this->slides as $index => $slide) {
					echo $this->get_slide(array('slide' => $slide, 'order' => $index));
				}
				if (count($this->slides) < 1) {
					echo $this->get_slide(array('slide' => null, 'index' => 0));
				} ?>
				</ul>
			</div>
			<p>
				<input type="hidden" name="bu_slideshow_edit_show" value="1" />
				<input type="hidden" name="bu_slideshow_id" value="<?php echo esc_attr($this->id); ?>" />
				<?php wp_nonce_field('bu_update_slideshow', 'bu_slideshow_nonce', false, true); ?>
				<input type="submit" value="Save Changes" id="bu-slideshow-editform-submit"class="button-primary" />
			</p>
		</form>
	</div>
</div>