import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, ToggleControl } from '@wordpress/components';
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
	const showOptions = slideShows
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
					options={showOptions}
				/>
			</div>
			<div>
				<SelectControl
					label={__('Transition', 'slideshow-block')}
					value={attributes.transition}
					onChange={(val) => setAttributes({ transition: val })}
					options={[
						{
							label: __('Slide', 'slideshow-block'),
							value: 'slide',
						},
						{
							label: __('Fade', 'slideshow-block'),
							value: 'fade',
						},
					]}
				/>
			</div>
			<div>
				<ToggleControl
					label={__('Show navigation', 'slideshow-block')}
					checked={attributes.showNav}
					onChange={(val) => setAttributes({ showNav: val })}
				/>
			</div>
			<div>
				<SelectControl
					label={__('Navigation Style', 'slideshow-block')}
					value={attributes.navStyle}
					onChange={(val) => setAttributes({ navStyle: val })}
					options={[
						{
							label: __('Icon', 'slideshow-block'),
							value: 'icon',
						},
						{
							label: __('Number', 'slideshow-block'),
							value: 'number',
						},
					]}
				/>
			</div>
			<div>
				<ToggleControl
					label={__('Play automatically', 'slideshow-block')}
					checked={attributes.autoPlay}
					onChange={(val) => setAttributes({ autoPlay: val })}
				/>
			</div>
		</div>
	);
}
