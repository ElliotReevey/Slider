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
		
		// Register the slides custom post type
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
		
		// Register a custom taxonomy
		// Add new taxonomy, make it hierarchical (like categories)
		 $labels = array(
		   'name' => _x( 'Slides Type', 'taxonomy general name' ),
		   'singular_name' => _x( 'Slide Groups', 'taxonomy singular name' ),
		   'search_items' =>  __( 'Search Slide Group' ),
		   'all_items' => __( 'All Slide Group' ),
		   'parent_item' => __( 'Parent Slide Group' ),
		   'parent_item_colon' => __( 'Parent Slide Group:' ),
		   'edit_item' => __( 'Edit Slide Group' ), 
		   'update_item' => __( 'Update Slide Group' ),
		   'add_new_item' => __( 'Add New Slide Group' ),
		   'new_item_name' => __( 'New Slide Group Name' ),
		   'menu_name' => __( 'Slide Groups' ),
		 ); 	

		 register_taxonomy('slide_type',array('slides'), array(
		   'hierarchical' => true,
		   'labels' => $labels,
		   'show_ui' => true,
		   'query_var' => true,
		   'rewrite' => array( 'slug' => 'slide_type' ),
		 ));		
		
	}	
}		

/**
 * Custom meta
 */
 
add_action( 'init', 'contentSlide_init');
require_once('includes/custom_meta.php');

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
		
		// If some argument's are posted, deal with them here.
	
		$extra_args = array();
		
		if($args['slides'])
			$extra_args[] = "posts_per_page=".$args['slides'];
			
		if($args['slide_type'])
			$extra_args[] = "slide_type=".$args['slide_type'];

		if(count($extra_args)>0):
			$extra_args = '&'.implode("&",$extra_args);
		else:
			$extra_args = '';
		endif;
		
		query_posts("post_type=slides&post_status=publish&orderby=menu_order&order=ASC$extra_args");
		while(have_posts()): the_post();
			$slide_title = get_post_meta(get_the_ID(), '_post_title', TRUE);
			$short_desc = get_post_meta(get_the_ID(), '_short_desc', TRUE);
			$long_desc = get_post_meta(get_the_ID(), '_long_desc', TRUE);
			$link = get_post_meta(get_the_ID(), '_link', TRUE);
			$link_title_attr = get_post_meta(get_the_ID(), '_link_title_attr', TRUE);
			$link_anchor = get_post_meta(get_the_ID(), '_link_anchor', TRUE);
			$slide_image_anchor = get_post_meta(get_the_ID(), '_slide_image_anchor', TRUE);
			$slide_image_url = get_post_meta(get_the_ID(), '_slide_image_url', TRUE);
			$portfolio_linkage = get_post_meta(get_the_ID(), '_portfolio_linkage', TRUE);
			$portfolio_linkage_2 = get_post_meta(get_the_ID(), '_portfolio_linkage_2', TRUE);
			$slide_id = get_the_ID();
		
			// Populate the information about the list elements
		
			$slides['list'][$slide_id]['title'] = $slide_title;
			$slides['list'][$slide_id]['description'] = $short_desc;
			$slides['list'][$slide_id]['image'] = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ));
			
			
			$slides['slide'][$slide_id]['title'] = $slide_title;
			$slides['slide'][$slide_id]['description'] = $long_desc;
			$slides['slide'][$slide_id]['link'] = $link;
			$slides['slide'][$slide_id]['link_title_attr'] = $link_title_attr;
			$slides['slide'][$slide_id]['link_anchor'] = $link_anchor;
			$slides['slide'][$slide_id]['slide_image_anchor'] = $slide_image_anchor;
			$slides['slide'][$slide_id]['slide_image_url'] = $slide_image_url;
			
			
			// If a portfolio link exists create it.
			if($portfolio_linkage || $portfolio_linkage_2):
				
				$portfolio_images = array();
				if($portfolio_linkage):
					$portfolio_images[] = $portfolio_linkage;
				endif;
				if($portfolio_linkage_2):
					$portfolio_images[] = $portfolio_linkage_2;	
				endif;
				
			endif;
			
			/* Return the portfolio images once stored */
			if($portfolio_images)
				$slides['slide'][$slide_id]['portfolio_links'] = $portfolio_images;

			$slides['slide'][$slide_id]['image'] = kd_mfi_get_featured_image_url( 'slide_main', 'slides' );
			
			if($args['subslide']){
				
				$slides['slide'][$slide_id]['sub_slides'] = array();
				
				for ($i=1; $i < 11; $i++) { 
					$slides['slide'][$slide_id]['sub_slides'][$i] = get_post_meta(get_the_ID(), '_advslide_sublide_'.$i, TRUE); 
				}
			
			}
			
		
		endwhile;
		wp_reset_query();

		return $slides;

	}
}

/**
 * Include the necessary JS & CSS files
 */
function byte_slides_enqueue() {
	// Enqueue the basic JS files required
    // wp_register_script( 'contentSlider', plugins_url('js/contentslider.js',__FILE__) );
    // wp_enqueue_script( 'contentSlider' );
    // wp_register_script( 'slides-slider', plugins_url('js/jquery.slides.min.js',__FILE__) );
    // wp_enqueue_script( 'slides-slider' );
    wp_register_script( 'cycle-plugin', plugins_url('js/jquery.cycle.min.js',__FILE__) );
    wp_enqueue_script( 'cycle-plugin' );
    wp_register_script( 'content-slider-plugin', plugins_url('js/jquery.contentslider.js',__FILE__), 'cycle-plugin' );
    wp_enqueue_script( 'content-slider-plugin' );


	// Enqueue the basic styles
	wp_register_style( 'basic-styles',plugins_url('css/basic-styles.css',__FILE__), '', '1.0' );
	wp_enqueue_style('basic-styles');
}    
 
add_action('wp_enqueue_scripts', 'byte_slides_enqueue');
