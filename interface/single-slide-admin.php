<li class="bu-slideshow-slide">
	<div class="bu-slide-header">
		<p><span class="bu-slide-header-thumb"><?php echo $img_thumb; ?></span></p>
		<p><span class="bu-slide-title"><?php echo esc_attr($this->caption["title"]); ?></span></p>
		<a href="#" class="bu-slide-expand bu-slide-control" title="<?php _e('Click to edit this slide', BU_S_LOCAL); ?>"></a>
	</div>
	<div class="bu-slide-edit-container">
		<a class="bu-slide-delete-button button" href="#"><?php _e('delete slide', BU_S_LOCAL); ?></a>
		<div class="bu-slide-thumb-container">
			<a class="button thickbox bu-slideshow-add-img" href="media-upload.php?referer=bu_slideshow&post_id=0&TB_iframe=true&width=640&height=332"><?php _e('Select Image', BU_S_LOCAL); ?></a>
			<a class="button bu-slideshow-remove-img" href="#"<?php echo $img_thumb ? '' : ' style="display:none;"'; ?>><?php _e('Remove Image', BU_S_LOCAL); ?></a>
			<input type="hidden" class="bu-slideshow-img-id" name="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" id="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" value="<?php echo esc_attr($this->image_id); ?>" />
			<input type="hidden" class="bu-slideshow-img-size" name="bu_slides[<?php echo esc_attr($this->order); ?>][image_size]" id="bu_slides[<?php echo esc_attr($this->order); ?>][image_size]" value="<?php echo esc_attr($this->image_size); ?>" />
			<span class="bu-slide-thumb"><?php echo $img_thumb; ?></span>
		</div>
		<div class="bu-slide-caption-container">
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]"><?php _e('Title', BU_S_LOCAL); ?></label>
				<input type="text" class="bu-slideshow-title-input" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" value="<?php echo esc_attr(strip_tags($this->caption["title"])); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]"><?php _e('Link', BU_S_LOCAL); ?></label>
				<input type="text" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" value="<?php echo esc_url($this->caption["link"]); ?>"
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php _e('Text', BU_S_LOCAL); ?></label>
				<textarea id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php echo esc_textarea(wp_kses_data($this->caption["text"])); ?></textarea>
			</p>
		</div>
	</div>
</li>