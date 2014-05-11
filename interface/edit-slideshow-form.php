<p>
	<label for="bu_slideshow_name"><strong><?php _e('Slideshow Name', BU_SSHOW_LOCAL); ?>: </strong></label>
	<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo $name; ?>" />
</p>

<p><strong><?php _e('Slides', BU_SSHOW_LOCAL); ?>:</strong><br />
	<em><?php _e('Drag to reorder, expand to edit', BU_SSHOW_LOCAL); ?></em>
</p>
<p>
	<a href="#" id="bu-slideshow-add-slide" class="button"><?php _e('Add New Slide', BU_SSHOW_LOCAL); ?></a>
	<span class="slide-added-confirmation">Slide added!</span>
</p>
<div id="bu-slideshow-slidelist">
	<ul>
	<?php 
	if ( count($slides) > 0 ) {
		foreach ($slides as $index => $slide) {
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
<p><strong><?php _e('Advanced options', BU_SSHOW_LOCAL); ?>:</strong> (<?php _e('optional', BU_SSHOW_LOCAL); ?>)<br />
	<label for="bu_slideshow_height"><?php _e('Default height', BU_SSHOW_LOCAL); ?>: </label>
	<input type="number" id="bu_slideshow_height" name="bu_slideshow_height" value="<?php echo $height; ?>" min="0" max="9999" />
	<span>px</span>
</p>