<?php

////////////////////////////
// Custom Post Type Setup
////////////////////////////
add_action( 'init', 'my_post_type' );
function my_post_type() {
    $labels = array(
        'name' => __('Carousel Images', 'myCarousel'),
        'singular_name' => __('Carousel Image', 'myCarousel'),
        'add_new' => __('Add New', 'myCarousel'),
        'add_new_item' => __('Add New Carousel Image', 'myCarousel'),
        'edit_item' => __('Edit Carousel Image', 'myCarousel'),
        'new_item' => __('New Carousel Image', 'myCarousel'),
        'view_item' => __('View Carousel Image', 'myCarousel'),
        'search_items' => __('Search Carousel Images', 'myCarousel'),
        'not_found' => __('No Carousel Image', 'myCarousel'),
        'not_found_in_trash' => __('No Carousel Images found in Trash', 'myCarousel'),
        'parent_item_colon' => '',
        'menu_name' => __('Carousel', 'myCarousel')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'page',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-images-alt',
        'supports' => array('title','excerpt','thumbnail', 'page-attributes')
    );
    register_post_type('my', $args);
}
// Create a taxonomy for the carousel post type
function my_taxonomies () {
    $args = array('hierarchical' => true, 'label' => 'Carousel Categories');
    register_taxonomy( 'carousel_category', 'my', $args );
}
add_action( 'init', 'my_taxonomies', 0 );


// Add theme support for featured images if not already present
// http://wordpress.stackexchange.com/questions/23839/using-add-theme-support-inside-a-plugin
function my_addFeaturedImageSupport() {
    $supportedTypes = get_theme_support( 'post-thumbnails' );
    if( $supportedTypes === false ) {
        add_theme_support( 'post-thumbnails', array( 'my' ) );
        add_image_size('featured_preview', 100, 55, true);
    } elseif( is_array( $supportedTypes ) ) {
        $supportedTypes[0][] = 'my';
        add_theme_support( 'post-thumbnails', $supportedTypes[0] );
        add_image_size('featured_preview', 100, 55, true);
    }
}
add_action( 'after_setup_theme', 'my_addFeaturedImageSupport');
