<?php

	if(!class_exists('contentSlide_Custom_Meta_Handler')):
	
	class contentSlide_Custom_Meta_Handler{
	
		function __construct(){
			add_action('save_post', array(&$this,'save_slide_data') );
		}
	
		function home_create(){

			add_meta_box('slide-type-div', __('Slide Information'),  array(&$this,'slide_type_metabox'), 'slides', 'normal', 'low'); 
			add_meta_box('sub-slide-images', __('Sub Slide Images'),  array(&$this,'sub_slide_images_metabox'), 'slides', 'normal', 'low'); 
			add_meta_box('slide-portfolio-linkage', __('Portfolio links'),  array(&$this,'slide_portfolio_type'), 'slides', 'normal', 'low'); 
			
		}
		
		function sub_slide_images_metabox($post){
			
			$sub_images = array();
			$sub_images[1] = get_post_meta($post->ID,'_advslide_sublide_1', TRUE);
			$sub_images[2] = get_post_meta($post->ID,'_advslide_sublide_2', TRUE);
			$sub_images[3] = get_post_meta($post->ID,'_advslide_sublide_3', TRUE);
			$sub_images[4] = get_post_meta($post->ID,'_advslide_sublide_4', TRUE);
			$sub_images[5] = get_post_meta($post->ID,'_advslide_sublide_5', TRUE);
			$sub_images[6] = get_post_meta($post->ID,'_advslide_sublide_6', TRUE);
			$sub_images[7] = get_post_meta($post->ID,'_advslide_sublide_7', TRUE);
			$sub_images[8] = get_post_meta($post->ID,'_advslide_sublide_8', TRUE);
			$sub_images[9] = get_post_meta($post->ID,'_advslide_sublide_9', TRUE);
			$sub_images[10] = get_post_meta($post->ID,'_advslide_sublide_10', TRUE); ?>
			
			<p>Enter the upload URLS for any slides you want to use on the bytewire front end</p>
			
			<table> 
			
			<?php for ($i=1; $i < 11; $i++) { ?>
			
				<tr>
					<th scope="row"><label for="sub_slide_image_<?=$i?>">Image <?=$i?>:</label></th><td><input type="text" name="sub_slide_image_<?=$i?>" id="sub_slide_image_<?=$i?>" class="regular-text" value="<?php echo esc_attr($sub_images[$i]); ?>"></td>
				</tr>
			
			<?php } ?>
			
			</table>
			
		<?php }
		
		function slide_type_metabox($post) {
		
			$slide_title = get_post_meta($post->ID, '_post_title', TRUE);
			$short_desc = get_post_meta($post->ID, '_short_desc', TRUE);
			$long_desc = get_post_meta($post->ID, '_long_desc', TRUE);
			$link = get_post_meta($post->ID, '_link', TRUE);
			$link_title_attr = get_post_meta($post->ID, '_link_title_attr', TRUE);
			$link_anchor = get_post_meta($post->ID, '_link_anchor', TRUE);
			$slide_image_anchor = get_post_meta($post->ID, '_slide_image_anchor', TRUE);
			$slide_image_url = get_post_meta($post->ID, '_slide_image_url', TRUE);

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
				<tr>
					<th scope="row"><label for="link_title_attr">Link title attr:</label></th><td><input type="text" name="link_title_attr" id="link_title_attr" class="regular-text" value="<?php echo esc_attr($link_title_attr); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="link_anchor">Link title anchor:</label></th><td><input type="text" name="link_anchor" id="link_anchor" class="regular-text" value="<?php echo esc_attr($link_anchor); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="slide_image_anchor">Slide image anchor text:</label></th><td><input type="text" name="slide_image_anchor" id="slide_image_anchor" class="regular-text" value="<?php echo esc_attr($slide_image_anchor); ?>"></td>
				</tr>	
				<tr>
					<th scope="row"><label for="slide_image_url">Slide image url:</label></th><td><input type="text" name="slide_image_url" id="slide_image_url" class="regular-text" value="<?php echo esc_attr($slide_image_url); ?>"></td>
				</tr>											
			</table>
			<?php
			
		}
		
		function slide_portfolio_type($post){ 
				
				$link_portfolio = get_post_meta($post->ID, '_portfolio_linkage', TRUE);
				$link_portfolio_2 = get_post_meta($post->ID, '_portfolio_linkage_2', TRUE);

				$loop = new WP_Query( 
					array( 											
						'post_type' => 'portfolio',
						'posts_per_page' => 50,
						'caller_get_posts' => 1
					) 
				);	
				$posts = array();					
				while ( $loop->have_posts() ) : $loop->the_post(); $loop_count++;
					$posts[] = array("name" => get_the_title(), "ID" => get_the_ID());
				endwhile;
						
			?>
			<p>This section links directly through to a custom post type called portfolio (known here as a Case Study).<br>The plugin does not create this custom post type, it only provides the interface to use it if you choose too.</p>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="portfolio_linkage">Case Study 1:</label></th><td>
						<select name="portfolio_linkage" id="portfolio_linkage">
							<option selected="selected" disabled="disabled">-- Select a portfolio --</option>
							<?php foreach ($posts as $k => $v) {
								echo '<option value="'.$v['ID'].'"'; if(selected($v['ID'],$link_portfolio)): echo 'selected="selected"'; endif; echo '>'.$v['name'].'</option>';
							}?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="portfolio_linkage_2">Case Study 2:</label></th><td>
						<select name="portfolio_linkage_2" id="portfolio_linkage_2">
							<option selected="selected" disabled="disabled">-- Select a portfolio --</option>
							<?php foreach ($posts as $k => $v) {
								echo '<option value="'.$v['ID'].'"'; if(selected($v['ID'],$link_portfolio_2)): echo 'selected="selected"'; endif; echo '>'.$v['name'].'</option>';
							}?>
						</select>
					</td>
				</tr>				
			</table>			
			
		<?php }

		function save_slide_data($post_id) {		 
				
			$post = get_post($post_id);
			if ($post->post_type == 'slides') { 
			
				update_post_meta($post_id, '_post_title', esc_attr($_POST['post_title']) );
				update_post_meta($post_id, '_short_desc', esc_attr($_POST['short_desc']) );
				update_post_meta($post_id, '_long_desc', $_POST['long_desc'] );
				update_post_meta($post_id, '_link', $_POST['link'] );
				update_post_meta($post_id, '_link_title_attr', $_POST['link_title_attr'] );
				update_post_meta($post_id, '_link_anchor', $_POST['link_anchor'] );
				update_post_meta($post_id, '_slide_image_anchor', $_POST['slide_image_anchor'] );
				update_post_meta($post_id, '_slide_image_url', $_POST['slide_image_url'] );
				
				update_post_meta($post_id, '_portfolio_linkage', $_POST['portfolio_linkage'] );
				update_post_meta($post_id, '_portfolio_linkage_2', $_POST['portfolio_linkage_2'] );
				
				for ($i=1; $i < 11; $i++) { 
					if($_POST['sub_slide_image_'.$i])
						update_post_meta($post_id, '_advslide_sublide_'.$i, $_POST['sub_slide_image_'.$i]);
				}
			

			}

		}
	}
endif;