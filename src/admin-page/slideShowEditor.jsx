import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import SlideShowItem from './slideShowEditor/slideShowItem';

function SlideShowEditor() {
    const [slideshows, setSlideshows] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchAllSlideshows() {
            try {
                // Fetch all slideshows in a single request
                const data = await apiFetch({ 
                    path: '/bu-slideshow/v1/slideshows'
                });
                
                setSlideshows(data);
            } catch (err) {
                console.error('Error fetching slideshows:', err);
                setError('Failed to load slideshows');
            } finally {
                setLoading(false);
            }
        }

        fetchAllSlideshows();
    }, []);

    if (loading) {
        return <div>Loading slideshows...</div>;
    }

    if (error) {
        return <div className="error-message">{error}</div>;
    }

    return (
        <div>
            <h1>Slideshow Editor</h1>
            {slideshows.length === 0 ? (
                <p>No slideshows found.</p>
            ) : (
                <ul className="slideshow-list">
                    {slideshows.map((item) => (
                        <SlideShowItem 
                            key={item.id} 
                            id={item.id}
                            slideshowData={item.slideshow} 
                        />
                    ))}
                </ul>
            )}
        </div>
    );
}

export default SlideShowEditor;