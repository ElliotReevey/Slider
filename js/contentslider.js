jQuery(document).ready(function() {
	
	jQuery(".tab").click(function(){
		
		var tabID = jQuery(this).attr('id');
		var slideID = tabID.split('_');
				
		jQuery(".tab").removeClass('active');
		jQuery("#"+tabID).addClass('active');
		jQuery(".slide").hide();
		jQuery("#slide_"+slideID[1]).show();
	
	});
	
	setInterval ( function(){ nextSlide(); } , 6000 );

	function nextSlide (){
		
		var max = jQuery(".the_list").children().length;

		var currentSlide = jQuery('.the_list .active').attr('id');
		var currentSlideID = currentSlide.split('_');
				
		if(currentSlideID[1] == max) {
			
			var temp = parseInt(1);
						
			jQuery(".tab").removeClass('active');
			jQuery("#list_"+temp).addClass('active');
			jQuery(".slide").hide();
			jQuery("#slide_"+temp).show();
			
		} else {
			
			var temp = parseInt(currentSlideID[1]) + 1;
						
			jQuery(".tab").removeClass('active');
			jQuery("#list_"+temp).addClass('active');
			jQuery(".slide").hide();
			jQuery("#slide_"+temp).show();
			
		}
	
	}
	
});