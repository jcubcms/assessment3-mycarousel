# My Carousel Plugin
<!--
Contributors: Varun Vyas
Tags: carousel, slider, image, bootstrap
Requires at least: 3.0.1
Tested up to: 5.4
Requires PHP: 5.2.4
Stable tag: 1.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
-->

A custom post type for choosing images and content which outputs My Carousel Slider from the [image-carousel] shortcode.

## Description

A custom post type for choosing images and content which outputs a [carousel](http://getbootstrap.com/javascript/#carousel) from [Twitter Bootstrap](http://www.getbootstrap.com) using the shortcode `[image-carousel]`.

The plugin assumes that you're already using Bootstrap, so you need to load the Bootstrap javascript and CSS separately.

## Installation

### The easy way

1. Go to the Plugins Menu in WordPress
2. Search for "My Carousel"
3. Click 'Install'
4. Activate the plugin

### Manual Installation

1. Download the plugin file from this page and unzip the contents
2. Upload the `my-carousel` folder to the `/wp-content/plugins/` directory
3. Activate the `my-carousel` plugin through the 'Plugins' menu in WordPress

### Once Activated

1. Make sure that your theme is loading the [Twitter Bootstrap](http://www.getbootstrap.com) CSS and Carousel javascript
2. Place the `[image-carousel]` shortcode in a Page or Post
3. Create new items in the `Carousel` post type, uploading a Featured Image for each.
  * (Optional) You can hyperlink each image by entering the desired url `Image Link URL` admin metabox when adding a new carousel image.

### How do I insert the carousel?

First of all, install and activate the plugin. Go to 'Carousel' in the WordPress admin pages and add some images. Then, insert the carousel using the `[image-carousel]` into the body of any page.