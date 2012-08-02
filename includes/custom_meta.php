<?php

	if(!class_exists('contentSlide_Custom_Meta_Handler')):
	
	class contentSlide_Custom_Meta_Handler{
	
		function __construct(){
			add_action('save_post', array(&$this,'save_slide_data') );
		}
	
		function home_create(){
			
			add_meta_box('slide-type-div', __('Slide Information'),  array(&$this,'slide_type_metabox'), 'slides', 'normal', 'low');
			 
		}
		
		function slide_type_metabox($post) {
		
			$slide_title = get_post_meta($post->ID, '_post_title', TRUE);
			$short_desc = get_post_meta($post->ID, '_short_desc', TRUE);
			$long_desc = get_post_meta($post->ID, '_long_desc', TRUE);
			$link = get_post_meta($post->ID, '_link', TRUE);

			?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="post_title">Title:</label></th><td><input type="text" name="post_title" id="post_title" class="regular-text" value="<?php echo esc_attr($slide_title); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="short_desc">Short Description:</label></th><td><input type="text" name="short_desc" id="short_desc" class="regular-text" value="<?php echo esc_attr($short_desc); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="long_desc">Long Description:</label></th><td><textarea name="long_desc" id="long_desc" class="large-text"><?php echo esc_attr($long_desc); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="link">Link:</label></th><td><input type="text" name="link" id="link" class="regular-text" value="<?php echo esc_attr($link); ?>"></td>
				</tr>
			</table>
			<?php
			
		}

		function save_slide_data($post_id) {		 
		
			$post = get_post($post_id);
			if ($post->post_type == 'slides') { 
			
				update_post_meta($post_id, '_post_title', esc_attr($_POST['post_title']) );
				update_post_meta($post_id, '_short_desc', esc_attr($_POST['short_desc']) );
				update_post_meta($post_id, '_long_desc', $_POST['long_desc'] );
				update_post_meta($post_id, '_link', $_POST['link'] );

			}

		}
	}
endif;