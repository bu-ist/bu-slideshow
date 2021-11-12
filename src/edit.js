/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import { TextControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object}   props
 * @param {Array}    props.attributes
 * @param {Function} props.isSelected
 * @param {Function} props.setAttributes
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, isSelected, setAttributes }) {
	return (
		<div className="wp-block-bu-slideshow-slideshow-block">
			{attributes.message && !isSelected ? (
				<div>Message: {attributes.message}</div>
			) : (
				<div>
					<TextControl
						label={__('Message', 'slideshow-block')}
						value={attributes.message}
						onChange={(val) => setAttributes({ message: val })}
					/>
				</div>
			)}
		</div>
	);
}
