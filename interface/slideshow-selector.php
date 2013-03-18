<div class="bu-slideshow-selector">
	<?php if (!count($all_slideshows)): ?>
	
	<p>No slideshows found. <a href="<?php echo BU_Slideshow::$add_url; ?>" title="Create a new slideshow">Add a Slideshow</a></p>
	
	<?php else: ?>
	
	<div class="col1">
		<p>
			<label for="bu_slideshow_selected">Select Slideshow:</label><br/>
			<?php //var_dump($all_slideshows); ?>
			<select name="bu_slideshow_selected" id="bu_slideshow_selected"">
				<option value="0"<?php if (!$args['show_id']) echo ' selected="selected"'; ?>></option>
				<?php foreach ($all_slideshows as $show) {
					$sel = intval($args['show_id']) === $show['id'] ? ' selected="selected"' : '';
					printf('<option value="%d"%s>%s</option>', esc_attr($show['id']), $sel, esc_html($show['name']));
				} ?>
			</select>
		</p>
		<p>
			<label for="bu_slideshow_select_transition">Select transition type:</label><br />
			<select name="bu_slideshow_select_transition" id="bu_slideshow_select_transition">
				<?php foreach (BU_Slideshow::$transitions as $transition) {
					$sel = $args['transition'] === $transition ? ' selected="selected"' : '';
					printf('<option value="%s"%s>%s</option>', esc_attr($transition), $sel, ucwords(esc_html($transition)));
				} ?>
				<option value="custom"<?php if ($args['transition'] === 'custom') echo ' selected="selected"'; ?>>Custom: </option>
			</select><br />
			<?php $trans = isset($args['custom_transition']) ? $args['custom_transition'] : ''; ?>
			<input type="text" name="bu_slideshow_custom_transition" id="bu_slideshow_custom_transition" value="<?php echo $trans; ?>"<?php if ($args['transition'] !== 'custom') echo ' style="display:none;" '; ?>/>
		</p>
	</div>
	<div class="col2">
		<p>
			<input type="checkbox" name="bu_slideshow_show_nav" id="bu_slideshow_show_nav" value="1"<?php echo $args['show_nav'] ? ' checked="checked"' : ''; ?>/> <label for="bu_slideshow_show_nav">Display navigation</label>
		</p>
		<p>
			<label for="bu_slideshow_nav_style">Navigation style:</label><br />
			<select name="bu_slideshow_nav_style" id="bu_slideshow_nav_style">
				<?php foreach (BU_Slideshow::$nav_styles as $style) {
					$sel = $args['nav_style'] === $style ? ' selected="selected"' : '';
					printf('<option value="%s"%s>%s</option>', esc_attr($style), $sel, ucwords(esc_html($style)));
				} ?>
			</select>
		</p>
		<p>
			<input type="checkbox" name="bu_slideshow_autoplay" id="bu_slideshow_autoplay" value="1"<?php echo $args['autoplay'] ? ' checked="checked"' : ''; ?> /> <label for="bu_slideshow_autoplay">Automatically play slideshow</label>
		</p>
	</div>
	
	<?php endif; ?>
	
</div>