<div class="wrap">
	<p><a href="<?php echo BU_Slideshow::$manage_url; ?>">&laquo; Back to Manage Slideshows</a></p>
	<h2>Edit Slideshow: <?php echo esc_html(stripslashes($this->name)); ?></h2>
	
	<?php if (isset($msg) && $msg) { ?>
	<div class="updated"><p><?php echo $msg; ?></p></div>
	<?php } ?>
	
	<div id="bu-slideshow-edit">
		<form id="bu-slideshow-editform" method="post" action="">
			<p>
				<label for="bu_slideshow_name"><strong>Slideshow Name: </strong></label>
				<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo esc_attr(stripslashes($this->name)); ?>" />
			</p>
			
			<p><strong>Slides:</strong><br />
				<em>Drag to reorder, expand to edit</em>
			</p>
			<p>
				<a href="#" id="bu-slideshow-add-slide" class="button">Add New Slide</a>
			</p>
			<div id="bu-slideshow-slidelist">
				<ul>
				<?php foreach ($this->slides as $index => $slide) {
					$slide->set_order($index);
					$slide->set_view('admin');
					echo $slide->get();
				}
				if (count($this->slides) < 1) {
					$slide = new BU_Slide(array('view' => 'admin'));
					echo $slide->get();
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