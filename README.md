# BU Slideshow #
**Contributors:** [inderpreet99](https://profiles.wordpress.org/inderpreet99), [awbauer](https://profiles.wordpress.org/awbauer), [clrux](https://profiles.wordpress.org/clrux), [mgburns](https://profiles.wordpress.org/mgburns), [gannondigital](https://profiles.wordpress.org/gannondigital)  
**Tags:** slideshow, images, boston university, bu  
**Requires at least:** 3.5  
**Tested up to:** 6.5.2  
**Stable tag:** 2.3.13

**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  


## Description ##
BU Slideshow is a plugin for creating and managing image-based slideshows. It is designed to be simple enough for any site admin to use, and powerful enough to meet the needs of designers. The plugin has many optional functions that make it flexible enough for a variety of uses: slideshows, photo rotations, feature card decks in the sidebar, etc.

[View full documentation on BU.edu](https://www.bu.edu/tech/services/comm/websites/www/wordpress/how-to/create-slideshows/)

### Features ###
* Drag-and-drop re-ordering of slides
* Optional titles and captions on a per-slide basis
* Set links on a per-slide basis
* Six locations for caption positioning, also on a per-slide basis
* Supports custom templates for slides, including custom slide fields
* Supports unique CSS classes on a per-slide basis for advanced styling options
* Integrated with WordPress Media Library for image upload/selection, size options, and image cropping/editing
* Block interface for the Gutenberg editor

### Developers ###

For developer documentation, feature roadmaps and more visit the [plugin repository on Github](https://github.com/bu-ist/bu-slideshow/).

[Build Status](https://travis-ci.org/bu-ist/bu-slideshow)

## Installation ##
This plugin can be installed automatically through the WordPress admin interface, or by clicking the download link on this page and installing manually.

Once installed, slideshows can be created by accessing the "Slideshows" menu in the admin panel. Slideshows can be dropped into any page by using the "Add Slideshow" button on the post/page edit screen in the classic editor, or by using the "BU Slideshow" block in the Gutenberg editor.

### Manual Installation ###
1. Upload the bu-slideshow plugin folder to the /wp-content/plugins/ directory
2. Activate 'BU Slideshow' through the 'Plugins' menu in WordPress

## Screenshots ##

### 1. An intuitive, compact user interface allows drag-and-drop reordering of slides, easy addition of new slides, and expand/collapse toggles to edit details of individual slides. ###
![An intuitive, compact user interface allows drag-and-drop reordering of slides, easy addition of new slides, and expand/collapse toggles to edit details of individual slides.](http://ps.w.org/bu-slideshow/assets/screenshot-1.png)

### 2. Select/remove images, edit titles, captions, links, and specify caption positioning and custom CSS classes all within a single location. ###
![Select/remove images, edit titles, captions, links, and specify caption positioning and custom CSS classes all within a single location.](http://ps.w.org/bu-slideshow/assets/screenshot-2.png)

### 3. Full integration with the WordPress Media Library allows you to bulk-upload your images and select them from the Media Library, or upload individually as you build slides. Easily remove and/or select a different image using the WordPress tools you are familiar with. ###
![Full integration with the WordPress Media Library allows you to bulk-upload your images and select them from the Media Library, or upload individually as you build slides. Easily remove and/or select a different image using the WordPress tools you are familiar with.](http://ps.w.org/bu-slideshow/assets/screenshot-3.png)

## Changelog ##

### 2.3.14 ###
* Removed grunt-phplint due to vulnerabilities in dependencies
* Bump node package versions.
* Update jquery .bind functions to use .on

### 2.3.13 ###

* Adds @wordpress/scripts JS build process
* Adds a "BU Slideshow" block for the Gutenberg editor
* Adds phpcs and eslint linting
* Adds wp-env for local development

### 2.3.12 ###
* Optimize the get_posts call for slideshows
* Determine if the block editor is in use before loading an interface
* Use more specific hooks to load the button and modal only when needed
* Update failing Travis CI tests

### 2.3.10 ###
* Added 'page_alt' post type support to enable slides with BU Versions plugin

### 2.3.9 ###
* Fix formatting error in shortcode generation

### 2.3.8 ###
* Fix UI issues since WP 4.4
* Use minified CSS on the frontend. Fixes #31.
* Switch to min filenames: https://core.trac.wordpress.org/ticket/21633

### 2.3.7 ###
* Adds error checking to `bu-slideshow-thumb` image size handling

### 2.3.6 ###
* Allow custom fields to use HTML (GH #28)
* Add `bu_slideshow_slide_admin` filter, allowing custom templates to change slide admin
* Adds "shuffle" attribute when shortcode is inserted in the Editor (GH #25)

### 2.3.5 ###
* Fixes issue with "Add Slideshow" modal in Editor

### 2.3.4 ###
* Formats slide titles (with HTML entities) correctly in admin view
* Adds unit tests

### 2.3.3 ###
* Bugfixes for PHP notices
* Improving Add Slide UX

### 2.3.2 ###
* Bugfix for potential duplicate slideshows

### 2.3.1 ###
* Fix PHP notice

### 2.3 ###
* Migrating slideshows to custom post type
* Support for custom slide templates: https://github.com/bu-ist/bu-slideshow/wiki/Actions-&-Filters#bu_slideshow_slide_templates
* Support for custom fields

### 2.2.1 ###
* Cleaning up slide image select in WP 4.0

### 2.2 ###
* Set caption position as dropdown, instead of radio buttons. Also adds `bu_slideshow_caption_positions` filter.

### 2.1.1 ###
* Resolves an issue where slideshows may not update correctly on save/delete.

### 2.1 ###
* Adding Grunt
* Updating "Insert Slideshow" modal z-index for WP4.0

### 2.0.1 ###
* Admin-side fix for inconsistent behavior after updating slide order

### 2.0 ###
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
* Minor UI tweaks
* - On slideshow edit screen, expand edit container for first slide by default (previously: all collapsed)
* - Hide navigation elements until slideshow is fully loaded
* - Image details / “edit” link on slideshow admin page
