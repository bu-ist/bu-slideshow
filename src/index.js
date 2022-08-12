/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType('bu-slideshow/slideshow-block', {
	apiVersion: 2,
	title: 'Slideshow Block',
	icon: 'slides',
	category: 'widgets',
	attributes: {
		slideshowId: {
			type: 'string',
			default: 0,
		},
		transition: {
			type: 'string',
			default: 'slide',
		},
		showNav: {
			type: 'boolean',
			default: true,
		},
		navStyle: {
			type: 'string',
			default: 'icon',
		},
		autoPlay: {
			type: 'boolean',
			default: true,
		},
		shuffle: {
			type: 'boolean',
			default: false,
		},
		width: {
			type: 'string',
			default: 'auto',
		},
		delay: {
			type: 'string',
			default: '5',
		},
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * Save is 'null' for dynamic blocks.
	 */
	save: () => null,
});
