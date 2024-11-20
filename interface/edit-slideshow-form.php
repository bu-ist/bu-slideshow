<p>
	<label for="bu_slideshow_name"><strong><?php esc_html_e('Slideshow Name', BU_SSHOW_LOCAL); ?>: </strong></label>
	<input type="text" id="bu_slideshow_name" name="bu_slideshow_name" value="<?php echo esc_attr($name); ?>" />
</p>

<?php if( count( $valid_templates ) ): ?>
<p>
	<label for="bu_slideshow_template"><strong><?php esc_html_e('Slideshow Template', BU_SSHOW_LOCAL); ?>: </strong></label>

	<select name="bu_slideshow_template">
		<option value="">Default template</option>
		<?php 
			foreach ($valid_templates as $i => $t){
				if( is_array( $t ) && array_key_exists( 'name', $t ) ){
					printf( '<option value="%s" %s>%s</option>', esc_attr($i), selected( $i, $template_id, false ), esc_attr($t['name']) );
				}
			} 
		?>
	</select>
</p>

<?php endif; ?>

<p><strong><?php esc_html_e('Slides', BU_SSHOW_LOCAL); ?>:</strong><br />
	<em><?php esc_html_e('Drag to reorder, expand to edit', BU_SSHOW_LOCAL); ?></em>
</p>
<div id="bu-slideshow-slidelist">
	<ul>
	<?php 
	if ( count($slides) > 0 ) {
		foreach ($slides as $index => $slide) {
			$slide->set_order($index);
			$slide->set_view('admin');
			echo wp_kses_post($slide->get());
		}
	} else {
		$slide = new BU_Slide( array( 'view' => 'admin' ) );
		echo wp_kses_post($slide->get());
	} ?>
	</ul>
</div>
<p class="bu-add-slide-section">
	<a href="#" id="bu-slideshow-add-slide" class="button"><?php esc_html_e('Add New Slide', BU_SSHOW_LOCAL); ?></a>
</p>

<p><strong><?php esc_html_e('Advanced options', BU_SSHOW_LOCAL); ?>:</strong> (<?php esc_html_e('optional', BU_SSHOW_LOCAL); ?>)<br />
	<label for="bu_slideshow_height"><?php esc_html_e('Default height', BU_SSHOW_LOCAL); ?>: </label>
	<input type="number" id="bu_slideshow_height" name="bu_slideshow_height" value="<?php echo esc_attr($height); ?>" min="0" max="9999" />
	<span>px</span>
</p>