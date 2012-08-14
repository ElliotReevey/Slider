<?php

 	/* This is an example template script for Wordpress, showing how the functions and jQuery can be used */

?>

<!-- The jQuery call -->
<script type="text/javascript">
jQuery(function(){
	jQuery("ul#menu-header-hori-nav").wpSubMenu();
	jQuery(".advanced-slide-menu").contentSlider({
		slides: "ul.main-slides",
		effect: "fade",
		enablesubslides: true,
		subclass: "div.sub-slides",
		subslideNext: "span.next",
		subslidePrev: "span.prev",
		subnavPagerClass: "nav.pager"
	});
});
</script>
<!-- End the jQuery Plugin call -->

<?php // Get the slides
$slides = getSlideInfo(array(
	"subslide" => true,
	"slide_type" => 'main'
));  // echo '<pre>'; print_r($slides); echo '</pre>'; ?>

<?php global $slides; ?>

<!-- Start advanced content slide -->

<div class="advanced-content-slider">

	<div class="advanced-slide-menu">
	
		<h2>Bytewire Offers</h2>

		<?php 
		$looping = 0;
		$totalSlides = count($slides['list']);		
		if(count($slides['list'])>0){ ?>

			<ul>
		
			<?php foreach ($slides['list'] as $k => $v) { $looping++; ?>

				<li class="list_<?=$k?><?php if($looping==$totalSlides) echo " last"; ?>">
					<img src="<?=$v['image']?>" class="menu-list-icon">
					<h3><?=$v['title']?></h3>
					<span><?=$v['description']?></span>
				</li>

			<?php } ?>

			</ul>

		<?php }else{ echo "No lists created, see custom post type."; } ?>

	</div><!-- End slide menu -->
	
	<!-- Start slide contents -->
	<div class="advanced-slide-contents">

	<?php
	$looping = 0;
	$totalSlides = count($slides['slide']);
	if(count($slides['slide'])>0){ ?>
		
		<ul class="main-slides">
		
		<?php 
		foreach ($slides['slide'] as $k => $v) { $looping++; ?>
		
			<li class="slide_<?=$k?><?php if($looping>1) echo " display-off"; ?>">
				
				<!-- Sub slide start -->
				<?php
					if($v['sub_slides'] && count($v['sub_slides'])>0){ ?>
						<div class="sub-slides">
						<?php foreach ($v['sub_slides'] as $key => $val) { ?>
							<?php if($val): ?>
								<img src="<?=$val?>">
							<?php endif; ?>
						<?php } ?>
						</div>
						<div class="controls">
							<nav class="pager"></nav>
							<span class="next"></span>
							<span class="prev"></span>				
						</div>
				<?php } ?>
				<!-- End slide start -->
				
				<img src="<?=$v['image']?>">
				<h3><?=$v['title']?></h3>
				<p><?=$v['description']?></p>
				
				<?php if($v['link']): ?>
				<a href="<?=$v['link']?>">Button</a>
				<?php endif; ?>
			</li>
		
		<?php } ?>
	
		</ul>
	
	<?php }else{ echo "No slide created, see custom post type."; } ?>
	
	</div>
	<!-- End slide contents -->
	
</div>

<!-- End advanced content slider -->