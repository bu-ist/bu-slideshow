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
		
			<span class="bu-slide-thumb"><?php echo $img_thumb; ?></span>
			<div class="bu-slide-meta">
				<?php
				// print_r($img_meta);
					if($img_thumb){
					
						printf("%s &middot; <a href='%s' target='_blank'>Edit</a> <br />",$img_meta['file'], $edit_url);

						// printf("<span class='show-slide-size'>(%spx x %spx) <a href='#' class='resize-slide-image'>Change size</a></span>",$img_meta['sizes'][$this->image_size]['width'], $img_meta['sizes'][$this->image_size]['height']);
						printf("<div class='change-slide-size'><select name='bu_slides[%d][image_size]' class='bu-slideshow-img-size'>", $this->order );

						foreach ($img_meta['sizes'] as $size => $d) {
							$selected = ($size == $this->image_size) ? " selected " : "";
							$display_size = $size;
							if( "thumbnail" == $display_size ){
								$display_size = "Thumb";
							} else if ( "full" == $display_size ){
								$display_size = "Full size";
							}
							printf("<option value='%s' %s>%s (%d x %d)</option>", $size, $selected, ucfirst($display_size), $d['width'], $d['height']);
						}

						echo "</select></div>";
					} else {
						printf('<input type="hidden" class="bu-slideshow-img-size" name="bu_slides[%d][image_size]" id="bu_slides[%d][image_size]" value="%s" />',$this->order,$this->order,$this->image_size);
					}

				?>
			</div>
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
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php _e('Caption', BU_SSHOW_LOCAL); ?></label>
				<textarea id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php echo esc_textarea(wp_kses_data($this->caption["text"])); ?></textarea>
			</p>
			<div class="captionposition">
				<fieldset>
					<p><legend><?php _e('Caption Position', BU_SSHOW_LOCAL); ?></legend></p>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-left]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-top-left"  <?php echo esc_attr($this->caption["position"]) === "caption-top-left" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-left]"><?php _e('Top Left', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-center]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-top-center"  <?php echo esc_attr($this->caption["position"]) === "caption-top-center" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-center]"><?php _e('Top Center', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-right]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-top-right"  <?php echo esc_attr($this->caption["position"]) === "caption-top-right" ? 'checked' : '' ; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-top-right]"><?php _e('Top Right', BU_SSHOW_LOCAL); ?></label>
					<br />
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-left]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-bottom-left"  <?php echo esc_attr($this->caption["position"]) === "caption-bottom-left" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-left]"><?php _e('Bottom Left', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-center]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-bottom-center"  <?php echo esc_attr($this->caption["position"]) === "caption-bottom-center" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-center]"><?php _e('Bottom Center', BU_SSHOW_LOCAL); ?></label>
					<input type="radio" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-right]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" value="caption-bottom-right"  <?php echo esc_attr($this->caption["position"]) === "caption-bottom-right" ? 'checked' : ''; ?> />
					<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position][caption-bottom-right]"><?php _e('Bottom Right', BU_SSHOW_LOCAL); ?></label>
				</fieldset>
			</div>
			<p>
				<label for="bu_slides[<?php echo $this->order; ?>][additional_styles]"><?php _e('Additional CSS Class(es)', BU_SSHOW_LOCAL); ?></label>
				<input type="text" id="bu_slides[<?php echo $this->order; ?>][additional_styles]" name="bu_slides[<?php echo $this->order; ?>][additional_styles]" value="<?php echo $this->additional_styles; ?>" />
			</p>
		</div>
	</div>
</li>