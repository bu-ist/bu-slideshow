import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

function SlideShowItem({ id }) {
    const [slideshowData, setSlideshowData] = useState(null);
    const [loading, setLoading] = useState(true);
    
    useEffect(() => {
        async function fetchSlideshowData() {
            try {
                // Use the custom REST API endpoint to fetch slideshow data.
                const { slideshow: data } = await apiFetch({ 
                    path: `/bu-slideshow/v1/slideshows/${id}`
                });
                
                setSlideshowData(data);
            } catch (error) {
                console.error('Error fetching slideshow meta:', error);
            } finally {
                setLoading(false);
            }
        }

        fetchSlideshowData();
    }, [id]);
    
    if (loading) {
        return <li className="loading">Loading details...</li>;
    }

    const { name, slides = [] } = slideshowData ?? {};
    const title = name || `Slideshow #${id}`;


    return (
        <li className="slideshow-item">
            <div className="slideshow-title">{title}</div>
            {slideshowData && (
                <div className="slideshow-meta">
                    {slideshowData.slides && (
                        <span className="slide-count">
                            {slideshowData.slides.length} slides
                        </span>
                    )}
                </div>
            )}
        </li>
    );
}

export default SlideShowItem;