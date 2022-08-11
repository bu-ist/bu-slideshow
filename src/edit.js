import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';

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
	const [slideShows, setSlideshows] = useState();

	const [preview, setPreview] = useState('');

	useEffect(() => {
		getSlideshows();
	}, []);

	const getSlideshows = async () => {
		const fetchedSlideshows = await apiFetch({
			path: 'bu-slideshow/v1/shows',
		});

		setSlideshows(fetchedSlideshows);
	};

	// Refresh the block preview markup whenever the attributes change.
	useEffect(() => {
		getPreview();
	}, [attributes]);

	const getPreview = async () => {};

	// Format the slideshows as menu options, or as an empty array if they haven't been loaded yet.
	const options = slideShows
		? slideShows.map((slideshow) => ({
				label: slideshow.name,
				value: slideshow.id,
		  }))
		: [];

	return (
		<div className="wp-block-bu-slideshow-slideshow-block">
			<div>
				<SelectControl
					label={__('Choose a slideshow', 'slideshow-block')}
					value={attributes.slideshowId}
					onChange={(val) => setAttributes({ slideshowId: val })}
					options={options}
				/>
			</div>
		</div>
	);
}
