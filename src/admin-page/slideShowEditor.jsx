import { useState, useEffect } from '@wordpress/element';

import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';

import SlideShowItem from './slideShowEditor/slideShowItem';

function SlideShowEditor() {
    const [slideShows, setSlideshows] = useState();

    const fetchedSlideShows = useSelect(
        ( select ) => select( coreDataStore ).getEntityRecords(
            'postType', // Entity kind
            'bu_slideshow', // Custom post type slug
            { per_page: -1 } // Query arguments (e.g., -1 for all, or specify number)
        ),
        [] // Dependencies array, empty means it runs once on mount
    );

    useEffect(() => {
        if (fetchedSlideShows) {
            setSlideshows(fetchedSlideShows);
        }
    }, [fetchedSlideShows]);

    if (!slideShows) {
        return <div>Loading...</div>;
    }

    return (
        <div>
            <h1>Slideshow Editor</h1>
            <ul>
                {slideShows.map((slideShow) => (
                    <SlideShowItem key={slideShow.id} id={slideShow.id} />
                ))}
            </ul>
        </div>
    );
}

export default SlideShowEditor;
