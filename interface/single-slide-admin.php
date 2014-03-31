<li class="bu-slideshow-slide">
	<div class="bu-slide-header">
		<p><span class="bu-slide-header-thumb"><?php echo $img_thumb; ?></span></p>
		<p><span class="bu-slide-title"><?php echo esc_attr($this->caption["title"]); ?></span></p>
		<a href="#" class="bu-slide-expand bu-slide-control" title="<?php _e('Click to edit this slide', BU_SSHOW_LOCAL); ?>"></a>
	</div>
	<div class="bu-slide-edit-container">
		<a class="bu-slide-delete-button" href="#"><?php _e('delete slide', BU_SSHOW_LOCAL); ?></a>
		<div class="bu-slide-thumb-container">
			<a class="button thickbox bu-slideshow-add-img" href="media-upload.php?referer=bu_slideshow&type=bu_slideshow&post_id=0&TB_iframe=true"><?php _e('Select Image', BU_SSHOW_LOCAL); ?></a>
			<a class="button bu-slideshow-remove-img" href="#"<?php echo $img_thumb ? '' : ' style="display:none;"'; ?>><?php _e('Remove Image', BU_SSHOW_LOCAL); ?></a>
			<input type="hidden" class="bu-slideshow-img-id" name="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" id="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" value="<?php echo esc_attr($this->image_id); ?>" />
			<input type="hidden" class="bu-slideshow-img-size" name="bu_slides[<?php echo esc_attr($this->order); ?>][image_size]" id="bu_slides[<?php echo esc_attr($this->order); ?>][image_size]" value="<?php echo esc_attr($this->image_size); ?>" />
			<span class="bu-slide-thumb"><?php echo $img_thumb; ?></span>
			<span class="bu-slide-meta">
				<?php
					printf("%s <br /> (%spx x %spx) &middot; <a href='%s' target='_blank'>Edit</a>", $img_meta['file'], $img_meta['width'], $img_meta['height'], $edit_url);
				?>
			</span>
		</div>
		<div class="bu-slide-caption-container">
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]"><?php _e('Title', BU_SSHOW_LOCAL); ?></label>
				<input type="text" class="bu-slideshow-title-input" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" value="<?php echo esc_attr(strip_tags($this->caption["title"])); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]"><?php _e('Link', BU_SSHOW_LOCAL); ?></label> 
				<input type="text" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" value="<?php echo esc_url($this->caption["link"]); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php _e('Text', BU_SSHOW_LOCAL); ?></label>
				<textarea id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php echo esc_textarea(wp_kses_data($this->caption["text"])); ?></textarea>
			</p>
			<div class="captionposition">
				<fieldset>
					<p><legend><?php _e('Caption Position', BU_SSHOW_LOCAL); ?></legend></p>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-left]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-top-left"  <?php echo esc_attr($this->caption["position"]) === "caption-top-left" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-left]"><?php _e('Top Left', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-right]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-top-right"  <?php echo esc_attr($this->caption["position"]) === "caption-top-right" ? 'checked' : '' ; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-right]"><?php _e('Top Right', BU_SSHOW_LOCAL); ?></label>
					<br />
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-left]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-bottom-left"  <?php echo esc_attr($this->caption["position"]) === "caption-bottom-left" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-left]"><?php _e('Bottom Left', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-right]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-bottom-right"  <?php echo esc_attr($this->caption["position"]) === "caption-bottom-right" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-right]"><?php _e('Bottom Right', BU_SSHOW_LOCAL); ?></label>
				</fieldset>
			</div>
		</div>
	</div>
</li>