<?php

 	/* This is an example template script for Wordpress, showing how the functions and jQuery can be used */

?>

<!-- The jQuery call -->
<script type="text/javascript">
jQuery(function(){
	jQuery(".service_flipper_menu").contentSlider({
		slides: "ul.services_flipper_slides",
		effect: "fade",
		disableInterval: true
	});
});
</script>
<!-- End the jQuery Plugin call -->

<?php // Get the slides
$slides = getSlideInfo(array(
	"subslide" => true,
	"slide_type" => 'services-home'
)); // echo '<pre>'; print_r($slides); echo '</pre>'; ?>

<!-- Slide display -->
		
<?php 
	$looping = 0;
	$totalSlides = count($slides['list']);		
	if(count($slides['list'])>0){ ?>

		<ul class="service_flipper_menu">
	
		<?php foreach ($slides['list'] as $k => $v) { $looping++; ?>

			<li class="list_<?=$k?><?php if($looping==$totalSlides) echo " last"; ?>">
				
				<?php if($v['image']): ?>
					<img src="<?=$v['image']?>">
				<?php endif; ?>
				
				<h3><?=$v['title']?></h3>
				<span><?=$v['description']?></span>
				
			</li>

		<?php } ?>

		</ul>

	<?php }else{ echo "No lists created, see custom post type."; } ?>
	
	<!-- End services list -->
	
	<!-- Start services content -->
	<?php
	$looping = 0;
	$totalSlides = count($slides['slide']);
	if(count($slides['slide'])>0){ ?>

		<ul class="services_flipper_slides">

		<?php 
		foreach ($slides['slide'] as $k => $v) { $looping++; ?>

			<li class="slide_<?=$k?><?php if($looping>0) echo " display-off"; ?>">
				
				<div class="slide_img">
					<img src="<?=$v['image']?>">
					<?php if($v['slide_image_anchor']):?>
						<?php if($v['slide_image_url']): ?>
							<a href="<?=$v['slide_image_url']?>">
						<?php endif; ?>
						<em><?=$v['slide_image_anchor']?></em>
						<?php if($v['slide_image_url']): ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				
				
				<?php 
				
				$portfolio = $v['portfolio_links'];
				
				/* Use the left hand menu design */
				if($portfolio || $v['link']): ?>
					<div class="portfolio_example">
						<?php 
						if($portfolio):
							if(is_array($portfolio)):
								$the_loop = 0;
								foreach ($portfolio as $key => $val) {
									$the_loop++;
									$the_portfolio = _byte_retrieve_portfolio($val);
									if($the_portfolio[0]['image']): ?>
										<img src="<?=$the_portfolio[0]['image']?>">	
									<?php endif; ?>
									<h4><?=__("Case Study")?></h4>
									<p><?php if($the_portfolio[0]['link']): ?>
										<a href="<?=$the_portfolio[0]['link']?>"><?=$the_portfolio[0]['name']?></a>
										<?php else: ?>
											<?=$the_portfolio[0]['name']?>
										<?php endif; ?>
									</p>
									<?php if(count($portfolio)>0 && $the_loop==1):?>
									<hr />
									<?php endif; ?>
								<?php }
					 		endif;
					 	endif; ?>
					
						<?php 
						if($v['link']):
							if(strpos('http://',$v['link'])): ?>
								<a href="<?=$v['link']?>"></a>
							<?php else: ?>
								<a href="<?php bloginfo('url'); ?><?=$v['link']?>" class="button yellow_style"
									<?php if($v['link_title_attr']):
										echo ' title="'.sprintf(__("%s"),$v['link_title_attr']).'"'; 
									endif; ?>
									>

									<?php 
									if($v['link_anchor'])
										echo $v['link_anchor'];
									else
										echo __('Click here');
									?>

								</a>
							<?php endif; ?>
						<?php endif; ?>						
					
					</div>
				<?php endif; ?>
				
				<p><?=$v['description']?></p>

			</li>

		<?php } ?>

		</ul>

	<?php }else{ echo "No slide created, see custom post type."; } ?>

	
<!-- End slide display -->
		
