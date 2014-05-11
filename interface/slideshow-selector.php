<div class="bu-slideshow-selector">
	
	<?php if (is_array($all_slideshows) && count($all_slideshows) > 0): ?>

	<div class="bu-slideshow-selector-basic">
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
			<p>
				<input type="checkbox" name="bu_slideshow_show_nav" id="bu_slideshow_show_nav" value="1"<?php echo $args['show_nav'] ? ' checked="checked"' : ''; ?>/> <label for="bu_slideshow_show_nav"><?php _e('Display navigation', BU_SSHOW_LOCAL); ?></label>
			</p>
			<p class="bu_slideshow_nav_style" <?php echo $args['show_nav'] ? ' style="display:inline;"' : ''; ?>>
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
			<p>
				<input type="checkbox" name="bu_slideshow_shuffle" id="bu_slideshow_shuffle" value="true"<?php echo $args['shuffle'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_shuffle"><?php _e('Shuffle slides', BU_SSHOW_LOCAL); ?></label>
			</p>
	</div>
	
	<p>
		<a href="#" class="bu-slideshow-advanced-toggle"><?php _e('Show advanced'); ?></a>
	</p>
	<div class="bu-slideshow-selector-advanced" style="display: none;">
			<p>
				<label for="bu_slideshow_width"><?php _e('Fixed width:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_width" id="bu_slideshow_width" value="<?php echo $args['width'] !== 'auto' ? esc_attr($args['width']) : ''; ?>" />px
			</p>
			<fieldset id="bu_slideshow_alignment">
				<legend><?php _e('Alignment:', BU_SSHOW_LOCAL); ?></legend>
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_left" value="left" <?php if ($args['align'] === 'left') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_left">Left</label><br />
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_center" value="center" <?php if ($args['align'] === 'center') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_center">Center</label><br />
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_right" value="right" <?php if ($args['align'] === 'right') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_right">Right</label><br />
			</fieldset>
			<p>
				<label for="bu_slideshow_custom_trans"><?php _e('Custom Transition:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_custom_trans" id="bu_slideshow_custom_trans" value="<?php if (isset($args['custom_transition'])) echo esc_attr($args['custom_transition']); ?>" /><br />
				<em><small>Overrides transition selected above. You must provide custom CSS transition code if you enter a value here.</small></em>
			</p>
			<p>
				<label for="bu_slideshow_transition_delay"><?php _e('Slide transition delay:', BU_SSHOW_LOCAL); ?></label><br />
				<input type="text" name="bu_slideshow_transition_delay" id="bu_slideshow_transition_delay" value="<?php if (isset($args['transition_delay'])) echo esc_attr($args['transition_delay']); ?>" /><br />
				<em><small>Number of seconds between slide transitions.</small></em>
			</p>
	</div>
	
	<?php else: ?>
	
	<p><?php _e('No slideshows found.', BU_SSHOW_LOCAL); ?> <a href="<?php echo BU_Slideshow::$add_url; ?>" title="<?php _e('Create a new slideshow', BU_SSHOW_LOCAL); ?>"><?php _e('Add a Slideshow', BU_SSHOW_LOCAL); ?></a></p>
	
	<?php endif; ?>
	
</div>