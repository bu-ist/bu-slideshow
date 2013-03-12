<li class="bu-slideshow-slide">
	<a href="#" class="bu-slide-expand bu-slide-control" title="Click to edit this slide">+</a>
	<div class="bu-slide-header">
		<p><span class="bu-slide-header-thumb"><?php echo $img_thumb; ?></span></p>
		<p><span class="bu-slide-title"><?php echo esc_attr($slide["caption"]["title"]); ?></span></p>
	</div>
	<div class="bu-slide-edit-container">
		<a class="bu-slide-delete-button button" href="#">delete slide</a>
		<div class="bu-slide-thumb-container">
			<a class="button thickbox bu-slideshow-add-img" href="media-upload.php?referer=bu_slideshow&post_id=0&TB_iframe=true&width=640&height=332">Select Image</a>
			<input type="hidden" class="bu-slideshow-img-id" name="bu_slides[<?php echo esc_attr($order); ?>][image_id]" id="bu_slides[<?php echo esc_attr($order); ?>][image_id]" value="<?php echo esc_attr($slide['image_id']); ?>" />
			<input type="hidden" class="bu-slideshow-img-size" name="bu_slides[<?php echo esc_attr($order); ?>][image_size]" id="bu_slides[<?php echo esc_attr($order); ?>][image_size]" value="<?php echo esc_attr($slide['image_size']); ?>" />
			<span class="bu-slide-thumb"><?php echo $img_thumb; ?></span>
		</div>
		<div class="bu-slide-caption-container">
			<p>
				<label for="bu_slides[<?php echo esc_attr($order); ?>][caption][title]">Title</label>
				<input type="text" class="bu-slideshow-title-input" id="bu_slides[<?php echo esc_attr($order); ?>][caption][title]" name="bu_slides[<?php echo esc_attr($order); ?>][caption][title]" value="<?php echo esc_attr(strip_tags($slide["caption"]["title"])); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($order); ?>][caption][link]">Link (optional)</label>
				<input type="text" id="bu_slides[<?php echo esc_attr($order); ?>][caption][link]" name="bu_slides[<?php echo esc_attr($order); ?>][caption][link]" value="<?php echo esc_url($slide["caption"]["link"]); ?>"
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($order); ?>][caption][text]">Text (optional)</label>
				<textarea id="bu_slides[<?php echo esc_attr($order); ?>][caption][text]" name="bu_slides[<?php echo esc_attr($order); ?>][caption][text]"><?php echo esc_textarea(wp_kses_data($slide["caption"]["text"])); ?></textarea>
			</p>
		</div>
	</div>
</li>