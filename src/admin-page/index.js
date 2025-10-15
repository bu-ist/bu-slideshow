/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';

import SlideShowEditor from './slideShowEditor';

const init = () => {
    const container = document.getElementById('bu-slideshow-admin-editor');
   render(<SlideShowEditor />, container);
};

if (document.getElementById('bu-slideshow-admin-editor')) {
    init();
} else {
    document.addEventListener('DOMContentLoaded', init);
}
