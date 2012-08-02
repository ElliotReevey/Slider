<?php
/*
Plugin Name: Content Slider
Description: Slider which shows images and text.
Version: 1.0
Author: Bytewire Ltd
Author URI: http://www.bytewire.co.uk
License: GPL2
*/


/**
 * Sets up the custom post type accordingly
 */
 
add_action( 'after_setup_theme', 'contentSlide_setup' );

if( ! function_exists('contentSlide_setup')){
	
	function contentSlide_setup(){	
		
		add_theme_support( 'post-thumbnails' );

		register_post_type( 'slides',
				array(
					'labels' => array(
						'name' => __( 'Slides' ),
						'singular_name' => __( 'Slides' ),
						'add_new' => __( 'Add New Slide' ),
						'add_new_item' => __( 'Add New Slide' ),
						'edit' => __( 'Edit' ),
						'edit_item' => __( 'Edit Slide' ),
						'new_item' => __( 'New Slide' ),
						'view' => __( 'View Slide' ),
						'view_item' => __( 'View Slide' ),
						'search_items' => __( 'Search Slides' ),
						'not_found' => __( 'No slides found' ),
						'not_found_in_trash' => __( 'No slides found in Trash' ),
						'parent' => __( 'Parent slides' ),
					),
					'public' => true,
					'query_var' => true,
					'menu_position' => 30,
					'show_ui' => true,
					'supports' => array('thumbnail','page-attributes'),
					'publicly_queryable' => true,
					'exclude_from_search' => true,
					'hierarchical' => true
				)
			);
	}	
}		

/**
 * Custom meta
 */
 
add_action( 'init', 'contentSlide_init');

require_once(ABSPATH . 'wp-content/plugins/contentSlider/includes/custom_meta.php');

if(!function_exists('contentSlide_init')){
	function contentSlide_init(){
		
		$contentSlide_custom_meta = new contentSlide_Custom_Meta_Handler;
		add_action('add_meta_boxes',array(&$contentSlide_custom_meta,'home_create'));
				
	}
}

/**
 * Get the slide info
 */
 
if(!function_exists('getSlideInfo')){
	function getSlideInfo($args){
		
		$slides = array();

		query_posts("post_type=slides&post_status=publish&orderby=menu_order&order=ASC&posts_per_page=".$args['slides']);
		while(have_posts()): the_post();
			$slide_title = get_post_meta(get_the_ID(), '_post_title', TRUE);
			$short_desc = get_post_meta(get_the_ID(), '_short_desc', TRUE);
			$long_desc = get_post_meta(get_the_ID(), '_long_desc', TRUE);
			$link = get_post_meta(get_the_ID(), '_link', TRUE);
			$slide_id = get_the_ID();
		
			$slides['list'][$slide_id]['title'] = $slide_title;
			$slides['list'][$slide_id]['description'] = $short_desc;
			$slides['list'][$slide_id]['image'] = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ));
			
			$slides['slide'][$slide_id]['title'] = $slide_title;
			$slides['slide'][$slide_id]['description'] = $long_desc;
			$slides['slide'][$slide_id]['link'] = $link;
			$slides['slide'][$slide_id]['image'] = kd_mfi_get_featured_image_url( 'slide_main', 'slides' );
		
		endwhile;
		wp_reset_query();

		return $slides;

	}
}

/**
 * Include the nessecary JS files
 */
function my_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
    wp_register_script( 'contentSlider', get_bloginfo('url'). '/wp-content/plugins/contentSlider/js/contentslider.js');
    wp_enqueue_script( 'contentSlider' );
}    
 
add_action('wp_enqueue_scripts', 'my_scripts_method');
