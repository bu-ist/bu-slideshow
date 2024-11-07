<li class="bu-slideshow-slide control-section">
	<div class="bu-slide-header menu-item-bar">
		<div class="menu-item-handle ui-sortable-handle accordion-section-title" style="width:auto;">
			<p><span class="bu-slide-header-thumb"><?php echo esc_html($img_thumb); ?></span></p>
			<p><span class="bu-slide-title"><?php echo esc_attr($this->caption["title"]); ?></span></p>
			<a href="#" class="bu-slide-expand bu-slide-control" title="<?php esc_html_e('Click to edit this slide', BU_SSHOW_LOCAL); ?>">edit</a>
		</div>
	</div>
	<div class="bu-slide-edit-container">
		<a class="bu-slide-delete-button" href="#"><?php esc_html_e('delete slide', BU_SSHOW_LOCAL); ?></a>
		<div class="bu-slide-thumb-container">
			<a class="button thickbox bu-slideshow-add-img" href="media-upload.php?referer=bu_slideshow&amp;type=bu_slideshow&amp;post_id=0&amp;TB_iframe=true"><?php esc_html_e('Select Image', BU_SSHOW_LOCAL); ?></a>
			<a class="button bu-slideshow-remove-img" href="#"<?php echo $img_thumb ? '' : ' style="display:none;"'; ?>><?php esc_html_e('Remove Image', BU_SSHOW_LOCAL); ?></a>
			<input type="hidden" class="bu-slideshow-img-id" name="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" id="bu_slides[<?php echo esc_attr($this->order); ?>][image_id]" value="<?php echo esc_attr($this->image_id); ?>" />

			<span class="bu-slide-thumb"><?php echo esc_html($img_thumb); ?></span>
			<div class="bu-slide-meta">
				<?php
					if($img_thumb){

						printf("%s &middot; <a href='%s' target='_blank'>Edit</a> <br />",esc_html($img_meta['file']), esc_url($edit_url));

						// printf("<span class='show-slide-size'>(%spx x %spx) <a href='#' class='resize-slide-image'>Change size</a></span>",$img_meta['sizes'][$this->image_size]['width'], $img_meta['sizes'][$this->image_size]['height']);
						printf("<div class='change-slide-size'><select name='bu_slides[%d][image_size]' class='bu-slideshow-img-size'>", esc_attr($this->order) );

						foreach ($img_meta['sizes'] as $size => $d) {
							$selected = ($size == $this->image_size) ? " selected " : "";
							$display_size = $size;
							if( "thumbnail" == $display_size ){
								$display_size = "Thumb";
							} else if ( "full" == $display_size ){
								$display_size = "Full size";
							}
							printf("<option value='%s' %s>%s (%d x %d)</option>", esc_attr($size), esc_attr($selected), esc_attr(ucfirst($display_size)), esc_attr($d['width']), esc_attr($d['height']));
						}

						echo esc_html("</select></div>");
					} else {
						printf('<input type="hidden" class="bu-slideshow-img-size" name="bu_slides[%d][image_size]" id="bu_slides[%d][image_size]" value="%s" />',esc_attr($this->order),esc_attr($this->order),esc_attr($this->image_size));
					}

				?>
			</div>
		</div>
		<div class="bu-slide-caption-container">
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]"><?php esc_html_e('Title', BU_SSHOW_LOCAL); ?></label>
				<input type="text" class="bu-slideshow-title-input" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][title]" value="<?php echo esc_textarea($this->caption["title"]); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]"><?php esc_html_e('Link', BU_SSHOW_LOCAL); ?></label>
				<input type="text" id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][link]" value="<?php echo esc_url($this->caption["link"]); ?>" />
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php esc_html_e('Caption', BU_SSHOW_LOCAL); ?></label>
				<textarea id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][text]"><?php echo esc_textarea($this->caption["text"]); ?></textarea>
			</p>
			<p><label><?php esc_html_e('Caption Position', BU_SSHOW_LOCAL); ?></label>
			<select id="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" name="bu_slides[<?php echo esc_attr($this->order); ?>][caption][position]" >
				<?php
				foreach($caption_positions as $l=>$v){
					$maybe_selected = ($v === $this->caption["position"]) ? "selected" : "";
					printf("<option value='%s' %s>%s</option>", esc_attr($v), esc_attr($maybe_selected), esc_html($l));
				}
				?>
			</select>
			</p>
			<p>
				<label for="bu_slides[<?php echo esc_attr($this->order); ?>][additional_styles]"><?php esc_html_e('Additional CSS Class(es)', BU_SSHOW_LOCAL); ?></label>
				<input type="text" id="bu_slides[<?php echo esc_attr($this->order); ?>][additional_styles]" name="bu_slides[<?php echo esc_attr($this->order); ?>][additional_styles]" value="<?php echo esc_attr($this->additional_styles); ?>" />
			</p>

			<?php
				foreach( $allowed_fields as $id => $field ){
					if( !is_array( $field ) || !isset( $field['label'] ) ){
						return;
					}
					$type = ( isset( $field['type'] ) && in_array( $field['type'], self::$custom_field_types, true ) ) ? $field['type'] : 'text';
					$value = array_key_exists($id, $custom_fields) ? $custom_fields[ $id ] : '';

					echo "<p>";
					printf('<label for="bu_slides[%s][custom_fields][%s]">%s</label>', esc_attr($this->order), esc_attr($id), esc_html($field['label']) );

					switch ( $type ) {
						case 'text':
							printf('<input type="text" id="bu_slides[%s][custom_fields][%s]" name="bu_slides[%s][custom_fields][%s]" value="%s" />',
							esc_attr($this->order), esc_attr($id), esc_attr($this->order), esc_attr($id), esc_attr($value));
							break;
					}
					echo esc_html("</p>");
				}
			?>
		</div>
	</div>
</li>