<div id="bu-slideshow-selector">
	<h3>Insert Slideshow</h3>
	<p>
		<label for="">Select Slideshow:</label>
		<?php //var_dump($all_slideshows); ?>
		<select name="bu-slideshow-selected">
			<?php foreach ($all_slideshows as $show) {
				printf('<option value="%d">%s</option>', esc_attr($show['id']), esc_html($show['name']));
			} ?>
		</select>
	</p>
</div>