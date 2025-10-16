function SlideShowItem({ id, slideshowData }) {
    
    const { name, slides = [] } = slideshowData || {};
    const title = name || `Slideshow #${id}`;
    
    return (
        <li className="slideshow-item">
            <div className="slideshow-title">{title}</div>
            <div className="slideshow-meta">
                {slides?.length > 0 && (
                    <span className="slide-count">
                        {slides.length} slides
                    </span>
                )}
            </div>
        </li>
    );
}

export default SlideShowItem;