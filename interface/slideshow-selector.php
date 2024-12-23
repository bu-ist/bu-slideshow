<div class="bu-slideshow-selector">
	
	<?php if (is_array($all_slideshows) && count($all_slideshows) > 0): ?>

	<div class="bu-slideshow-selector-basic">
			<p>
				<label for="bu_slideshow_selected"><?php esc_html_e('Select Slideshow:', 'bu-slideshow'); ?></label><br/>
				<select name="bu_slideshow_selected" id="bu_slideshow_selected">
					<option value="0"<?php if (!$args['show_id']) echo ' selected="selected"'; ?>>Select...</option>
					<?php foreach ($all_slideshows as $id => $show) {
						printf('<option value="%d"%s>%s</option>', esc_attr($id), selected( $id, $args['show_id'] ), esc_html($show->name));
					} ?>
				</select>
			</p>
			<p>
				<label for="bu_slideshow_select_transition"><?php esc_html_e('Select transition type:', 'bu-slideshow'); ?></label><br />
				<select name="bu_slideshow_select_transition" id="bu_slideshow_select_transition">
					<?php foreach (BU_Slideshow::$transitions as $transition) {
						$sel = $args['transition'] === $transition ? ' selected="selected"' : '';
						printf('<option value="%s"%s>%s</option>', esc_attr($transition), esc_attr($sel), esc_attr(ucwords($transition)));
					} ?>
				</select><br />
			</p>
			<p>
				<input type="checkbox" name="bu_slideshow_show_nav" id="bu_slideshow_show_nav" value="1"<?php echo esc_attr($args['show_nav']) ? ' checked="checked"' : ''; ?>/> <label for="bu_slideshow_show_nav"><?php esc_html_e('Display navigation', 'bu-slideshow'); ?></label>
			</p>
			<p class="bu_slideshow_nav_style" <?php echo esc_attr($args['show_nav']) ? ' style="display:inline;"' : ''; ?>>
				<label for="bu_slideshow_nav_style"><?php esc_html_e('Navigation style:', 'bu-slideshow'); ?></label><br />
				<select name="bu_slideshow_nav_style" id="bu_slideshow_nav_style">
					<?php foreach (BU_Slideshow::$nav_styles as $style) {
						$sel = $args['nav_style'] === $style ? ' selected="selected"' : '';
						printf('<option value="%s"%s>%s</option>', esc_attr($style), esc_attr($sel), esc_attr(ucwords($style)));
					} ?>
				</select>
			</p>
			<p>
				<input type="checkbox" name="bu_slideshow_autoplay" id="bu_slideshow_autoplay" value="1"<?php echo $args['autoplay'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_autoplay"><?php esc_html_e('Automatically play slideshow', 'bu-slideshow'); ?></label>
			</p>
			<p>
				<input type="checkbox" name="bu_slideshow_shuffle" id="bu_slideshow_shuffle" value="true"<?php echo $args['shuffle'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_shuffle"><?php esc_html_e('Shuffle slides', 'bu-slideshow'); ?></label>
			</p>
			
			<p>
				<label for="bu_slideshow_width"><?php esc_html_e('Fixed width:', 'bu-slideshow'); ?></label><br />
				<input type="text" name="bu_slideshow_width" id="bu_slideshow_width" value="<?php echo $args['width'] !== 'auto' ? esc_attr($args['width']) : ''; ?>" />px
			</p>
			<fieldset id="bu_slideshow_alignment">
				<legend><?php esc_html_e('Alignment:', 'bu-slideshow'); ?></legend>
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_left" value="left" <?php if ($args['align'] === 'left') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_left">Left</label><br />
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_center" value="center" <?php if ($args['align'] === 'center') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_center">Center</label><br />
				<input type="radio" class="bu_slideshow_alignment_loop" name="bu_slideshow_alignment" id="bu_slideshow_alignment_right" value="right" <?php if ($args['align'] === 'right') { echo 'selected="selected"'; } ?> /> <label for="bu_slideshow_alignment_right">Right</label><br />
			</fieldset>
			<p>
				<label for="bu_slideshow_custom_trans"><?php esc_html_e('Custom Transition:', 'bu-slideshow'); ?></label><br />
				<input type="text" name="bu_slideshow_custom_trans" id="bu_slideshow_custom_trans" value="<?php if (isset($args['custom_transition'])) echo esc_attr($args['custom_transition']); ?>" /><br />
				<em><small>Overrides transition selected above. You must provide custom CSS transition code if you enter a value here.</small></em>
			</p>
			<p>
				<label for="bu_slideshow_transition_delay"><?php esc_html_e('Slide transition delay:', 'bu-slideshow'); ?></label><br />
				<input type="text" name="bu_slideshow_transition_delay" id="bu_slideshow_transition_delay" value="<?php if (isset($args['transition_delay'])) echo esc_attr($args['transition_delay']); ?>" /><br />
				<em><small>Number of seconds between slide transitions.</small></em>
			</p>
	</div>
	<p class='slide-show-generated-shortcode-label'>
				
				<div class='slide-show-generated-shortcode'>
				</div>
			</p>
	<?php else: ?>
	
	<p><?php esc_html_e('No slideshows found.', 'bu-slideshow'); ?> <a href="<?php echo esc_url(BU_Slideshow::$add_url); ?>" title="<?php esc_html_e('Create a new slideshow', 'bu-slideshow'); ?>"><?php esc_html_e('Add a Slideshow', 'bu-slideshow'); ?></a></p>
	
	<?php endif; ?>
	
</div>