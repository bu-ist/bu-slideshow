=== BU Slideshow ===
Contributors: awbauer, clrux, mgburns, gannondigital
Tags: slideshow, images, boston university, bu
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: 2.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==
BU Slideshow is a plugin for creating and managing image-based slideshows. It is designed to be simple enough for any site admin to use, and powerful enough to meet the needs of designers. The plugin has many optional functions that make it flexible enough for a variety of uses: slideshows, photo rotations, feature card decks in the sidebar, etc. 
[View full documentation on BU.edu](http://www.bu.edu/tech/services/comm/websites/www/wordpress/how-to/create-slideshows/)

= Features =
* Drag-and-drop re-ordering of slides
* Optional titles and captions on a per-slide basis
* Set links on a per-slide basis
* Six locations for caption positioning, also on a per-slide basis
* Supports unique CSS classes on a per-slide basis for advanced styling options
* Integrated with WordPress Media Library for image upload/selection, size options, and image cropping/editing

== Installation ==
This plugin can be installed automatically through the WordPress admin interface, or by clicking the downlaod link on this page and installing manually.

Once installed, slideshows can be created by accessing the "Slideshows" menu in the admin panel. Slideshows can be dropped into any page by using the "Add Slideshow" button on the post/page edit screen.

= Manual Installation =
1. Upload the bu-slideshow plugin folder to the /wp-content/plugins/ directory
2. Activate 'BU Slideshow' through the 'Plugins' menu in WordPress

== Screenshots ==

1. An intuitive, compact user interface allows drag-and-drop reordering of slides, easy addition of new slides, and expand/collapse toggles to edit details of individual slides.
2. Select/remove images, edit titles, captions, links, and specify caption positioning and custom CSS classes all within a single location.
3. Full integration with the WordPress Media Library allows you to bulk-upload your images and select them from the Media Library, or upload individually as you build slides. Easily remove and/or select a different image using the WordPress tools you are familiar with.

== Changelog ==
= 2.2.1 =
* Cleaning up slide image select in WP 4.0

= 2.2 =
* Set caption position as dropdown, instead of radio buttons. Also adds `bu_slideshow_caption_positions` filter.

= 2.1.1 =
* Resolves an issue where slideshows may not update correctly on save/delete.

= 2.1 =
* Adding Grunt
* Updating "Insert Slideshow" modal z-index for WP4.0

= 2.0.1 =
* Admin-side fix for inconsistent behavior after updating slide order

= 2.0 =
* Improved “Add Slideshow” flow (3 screens -> 1). Now similar flow to creating new posts/pages.
* Abstracted “edit slideshow” form to a separate template file
* Added option to set default height (Avoids “jump” while calculating height on page load)
* Added option to set transition delay
* Added option to choose caption position
* Added loading indicator
* Added option to set show alignment
* Added shortcode option “shuffle”
* Added option to update image size (full/large/medium/etc) used in slide
* Removed old back compatibility with WP < 3.3

