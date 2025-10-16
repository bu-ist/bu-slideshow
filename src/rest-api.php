<?php
/**
 * REST API endpoint for BU Slideshow
 *
 * @package BU_SlideShow
 *
 * IMPLEMENTATION NOTES:
 *
 * 1. Custom endpoints were chosen over standard meta registration because:
 *    - The '_bu_slideshow' field uses an underscore prefix (traditionally "private" in WP)
 *    - WordPress 5.7 has limited support for complex meta objects in REST API
 *    - This approach provides better control over response format and permissions
 *
 * 2. Future considerations:
 *    - When upgrading to WP 6.1+, consider using enhanced REST API functionality
 *    - For a major version update, consider renaming meta key without underscore prefix
 *    - If implementing write operations, add proper validation and sanitization
 *
 * 3. Backward compatibility:
 *    - This implementation maintains compatibility with existing slideshow data
 *    - No database migrations required
 *    - Both old and new admin interfaces can coexist during transition
 */

add_action(
	'rest_api_init',
	function () {
		// Register a route to fetch slideshow data by ID.
		register_rest_route(
			'bu-slideshow/v1',
			'/slideshows/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => function ( $request ) {
					$slideshow_id = $request->get_param( 'id' );
					$meta         = get_post_meta( $slideshow_id, '_bu_slideshow', true );
					return array(
						'id'        => $slideshow_id,
						'slideshow' => $meta,
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'read' );
				},
			)
		);

		// Register an endpoint for listing all slideshows.
		register_rest_route(
			'bu-slideshow/v1',
			'/slideshows',
			array(
				'methods'             => 'GET',
				'callback'            => function () {
					$slideshows = array();
					$posts      = get_posts(
						array(
							'post_type'      => 'bu_slideshow',
							'posts_per_page' => -1,  // Get all slideshows --- consider pagination!
							'fields'         => 'ids',
						)
					);

					foreach ( $posts as $post_id ) {
						$meta = get_post_meta( $post_id, '_bu_slideshow', true );
						if ( $meta ) {
							$slideshows[] = array(
								'id'        => $post_id,
								'slideshow' => $meta,
							);
						}
					}

					return $slideshows;
				},
				'permission_callback' => function () {
					return current_user_can( 'read' );
				},
			)
		);
	}
);
