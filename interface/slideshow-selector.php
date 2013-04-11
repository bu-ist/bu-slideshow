<div class="bu-slideshow-selector">
	
	<?php if (is_array($all_slideshows) && count($all_slideshows) > 0): ?>
	
	<div class="col1">
		<p>
			<label for="bu_slideshow_selected"><?php _e('Select Slideshow:', BU_S_LOCAL); ?></label><br/>
			<select name="bu_slideshow_selected" id="bu_slideshow_selected">
				<option value="0"<?php if (!$args['show_id']) echo ' selected="selected"'; ?>></option>
				<?php foreach ($all_slideshows as $show) {
					$sel = intval($args['show_id']) === $show->id ? ' selected="selected"' : '';
					printf('<option value="%d"%s>%s</option>', esc_attr($show->id), $sel, esc_html($show->name));
				} ?>
			</select>
		</p>
		<p>
			<label for="bu_slideshow_select_transition"><?php _e('Select transition type:', BU_S_LOCAL); ?></label><br />
			<select name="bu_slideshow_select_transition" id="bu_slideshow_select_transition">
				<?php foreach (BU_Slideshow::$transitions as $transition) {
					$sel = $args['transition'] === $transition ? ' selected="selected"' : '';
					printf('<option value="%s"%s>%s</option>', esc_attr($transition), $sel, ucwords(esc_html($transition)));
				} ?>
				<option value="custom"<?php if ($args['transition'] === 'custom') echo ' selected="selected"'; ?>><?php _e('Custom: ', BU_S_LOCAL); ?></option>
			</select><br />
			<?php $trans = isset($args['custom_transition']) ? $args['custom_transition'] : ''; ?>
			<input type="text" name="bu_slideshow_custom_transition" id="bu_slideshow_custom_transition" value="<?php echo $trans; ?>"<?php if ($args['transition'] !== 'custom') echo ' style="display:none;" '; ?>/>
		</p>
	</div>
	<div class="col2">
		<p>
			<input type="checkbox" name="bu_slideshow_show_nav" id="bu_slideshow_show_nav" value="1"<?php echo $args['show_nav'] ? ' checked="checked"' : ''; ?>/> <label for="bu_slideshow_show_nav"><?php _e('Display navigation', BU_S_LOCAL); ?></label>
		</p>
		<p>
			<label for="bu_slideshow_nav_style"><?php _e('Navigation style:', BU_S_LOCAL); ?></label><br />
			<select name="bu_slideshow_nav_style" id="bu_slideshow_nav_style">
				<?php foreach (BU_Slideshow::$nav_styles as $style) {
					$sel = $args['nav_style'] === $style ? ' selected="selected"' : '';
					printf('<option value="%s"%s>%s</option>', esc_attr($style), $sel, ucwords(esc_html($style)));
				} ?>
			</select>
		</p>
		<p>
			<input type="checkbox" name="bu_slideshow_autoplay" id="bu_slideshow_autoplay" value="1"<?php echo $args['autoplay'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_autoplay"><?php _e('Automatically play slideshow', BU_S_LOCAL); ?></label>
		</p>
	</div>
	
	<?php else: ?>
	
	<p><?php _e('No slideshows found.', BU_S_LOCAL); ?> <a href="<?php echo BU_Slideshow::$add_url; ?>" title="<?php _e('Create a new slideshow', BU_S_LOCAL); ?>"><?php _e('Add a Slideshow', BU_S_LOCAL); ?></a></p>
	
	<?php endif; ?>
	
</div>