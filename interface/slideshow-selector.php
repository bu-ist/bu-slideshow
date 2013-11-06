<div class="bu-slideshow-selector">
	
	<?php if (is_array($all_slideshows) && count($all_slideshows) > 0): ?>

	<div class="bu-slideshow-selector-basic">
		<div class="col1">
			<p>
				<label for="bu_slideshow_selected"><?php _e('Select Slideshow:', BU_SSHOW_LOCAL); ?></label><br/>
				<select name="bu_slideshow_selected" id="bu_slideshow_selected">
					<option value="0"<?php if (!$args['show_id']) echo ' selected="selected"'; ?>></option>
					<?php foreach ($all_slideshows as $show) {
						$sel = intval($args['show_id']) === $show->id ? ' selected="selected"' : '';
						printf('<option value="%d"%s>%s</option>', esc_attr($show->id), $sel, esc_html($show->name));
					} ?>
				</select>
			</p>
			<p>
				<label for="bu_slideshow_select_transition"><?php _e('Select transition type:', BU_SSHOW_LOCAL); ?></label><br />
				<select name="bu_slideshow_select_transition" id="bu_slideshow_select_transition">
					<?php foreach (BU_Slideshow::$transitions as $transition) {
						$sel = $args['transition'] === $transition ? ' selected="selected"' : '';
						printf('<option value="%s"%s>%s</option>', esc_attr($transition), $sel, ucwords(esc_html($transition)));
					} ?>
				</select><br />
			</p>
		</div>
		<div class="col2">
			<p>
				<input type="checkbox" name="bu_slideshow_show_nav" id="bu_slideshow_show_nav" value="1"<?php echo $args['show_nav'] ? ' checked="checked"' : ''; ?>/> <label for="bu_slideshow_show_nav"><?php _e('Display navigation', BU_SSHOW_LOCAL); ?></label>
			</p>
			<p>
				<label for="bu_slideshow_nav_style"><?php _e('Navigation style:', BU_SSHOW_LOCAL); ?></label><br />
				<select name="bu_slideshow_nav_style" id="bu_slideshow_nav_style">
					<?php foreach (BU_Slideshow::$nav_styles as $style) {
						$sel = $args['nav_style'] === $style ? ' selected="selected"' : '';
						printf('<option value="%s"%s>%s</option>', esc_attr($style), $sel, ucwords(esc_html($style)));
					} ?>
				</select>
			</p>
			<p>
				<input type="checkbox" name="bu_slideshow_autoplay" id="bu_slideshow_autoplay" value="1"<?php echo $args['autoplay'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_autoplay"><?php _e('Automatically play slideshow', BU_SSHOW_LOCAL); ?></label>
			</p>
		</div>
	</div>
	
	<p>
		<a href="#" class="bu-slideshow-advanced-toggle"><?php _e('Show advanced'); ?></a>
	</p>
	<div class="bu-slideshow-selector-advanced">
		<div class="col1">
			<p>
				<label for="bu_slideshow_width"><?php _e('Fixed width:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_width" id="bu_slideshow_width" value="<?php echo $args['width'] !== 'auto' ? esc_attr($args['width']) : ''; ?>" />px
			</p>
			<p>
				<label for="bu_slideshow_custom_trans"><?php _e('Custom Transition:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_custom_trans" id="bu_slideshow_custom_trans" value="<?php if (isset($args['custom_transition'])) echo esc_attr($args['custom_transition']); ?>" /><br />
				<em><small>Overrides transition selected above. You must provide custom CSS transition code if you enter a value here.</small></em>
			</p>
			<!-- <p>
				<label for="bu_slideshow_autoplaydelay"><?php _e('Slide transition delay:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_autoplaydelay" id="bu_slideshow_autoplaydelay" value="<?php if (isset($args['autoPlayDelay'])) echo esc_attr($args['autoPlayDelay']); ?>" /><br />
				<em><small>Number in milliseconds between slide transitions (i.e., 5000 would be 5 seconds).</small></em>
			</p> -->
		</div>
	</div>
	
	<?php else: ?>
	
	<p><?php _e('No slideshows found.', BU_SSHOW_LOCAL); ?> <a href="<?php echo BU_Slideshow::$add_url; ?>" title="<?php _e('Create a new slideshow', BU_SSHOW_LOCAL); ?>"><?php _e('Add a Slideshow', BU_SSHOW_LOCAL); ?></a></p>
	
	<?php endif; ?>
	
</div>