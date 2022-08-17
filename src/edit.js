import { __ } from '@wordpress/i18n';
import {
	TextControl,
	SelectControl,
	ToggleControl,
	PanelBody,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';

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

	const getPreview = async () => {
		// Bail if the slideshow ID is not set.
		if (Number(attributes.slideshowId) === 0) {
			setPreview(`<p>${__('Please select a slideshow.')}</p>`);
			return;
		}

		// Convert the block attributes to a query string.
		const queryString = Object.keys(attributes)
			.map((key) => key + '=' + attributes[key])
			.join('&');

		// Fetch the markup output through the API.
		const fetchedPreview = await apiFetch({
			path: `bu-slideshow/v1/markup?${queryString}`,
		});

		// Unfortunately the frontend javascript is designed to only run on document.ready, so it's not easy to ititialize the slideshow.
		// As a hacky workaround, I'm parsing the markup to an offscreen DOM objects, to be able to run a standard css selector that can isolate the first slide element
		const offscreenPreview = document.createElement('div');
		offscreenPreview.innerHTML = fetchedPreview;

		// Get the first slide element.
		const firstSlide = offscreenPreview.getElementsByClassName('slide')[0];

		// If the frontend js were working, we could just set the preview state to the fetched markup.
		// It isn't though, so set the preview state to the first slide element.
		setPreview(firstSlide.innerHTML);
	};

	// Format the slideshows as menu options, or as an empty array if they haven't been loaded yet.
	let showOptions = slideShows
		? slideShows.map((slideshow) => ({
				label: slideshow.name,
				value: slideshow.id,
		  }))
		: [];

	// Add a label to the zero option, so there's a message to select a slideshow on first load.
	showOptions = [{ label: 'Select a slideshow', value: 0 }, ...showOptions];

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
			<InspectorControls>
				<PanelBody title={__('Slideshow Options', 'slideshow-block')}>
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
					<div>
						<ToggleControl
							label={__('Shuffle slides', 'slideshow-block')}
							checked={attributes.shuffle}
							onChange={(val) => setAttributes({ shuffle: val })}
						/>
					</div>
					<div>
						<TextControl
							label={__('Fixed width px (optional)', 'slideshow-block')}
							value={attributes.width === 'auto' ? '' : attributes.width}
							onChange={(val) =>
								setAttributes({ width: val === '' ? 'auto' : val })
							}
						/>
					</div>
					<div>
						<TextControl
							label={__('Transition delay in seconds', 'slideshow-block')}
							value={attributes.delay}
							onChange={(val) => setAttributes({ delay: val })}
						/>
					</div>
				</PanelBody>
			</InspectorControls>
			<div dangerouslySetInnerHTML={{ __html: preview }} />
		</div>
	);
}
