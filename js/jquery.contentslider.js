(function($){
 $.fn.contentSlider = function(options) {
	
	 var defaults = {  
	   interval: 6000,
	   inactivityInterval: 30000,
       disableInterval: false,
	   startslide: 0,
	   list: ".advanced-slide-menu",
	   slides: ".advanced-slide-contents",
	   effect: "fade",
	   enablesubslides: false,
	   subclass: "",
	   subslideNext: ".controls span.next",
	   subslidePrev: ".controls span.prev",
       subslidePagerClass: ".controls nav.pager"
	};	
	
	var options = $.extend(defaults, options);  
	
	return this.each(function() {
		
		var base = this;
		base.timer = null;
		base.inactivitytimer = null;
		base.currentslide = options.startslide;
		base.nextslide = null;
		base.totalSlides = 0;
		
		base.inactivityStart = function(){
			// Clear the original timer
			clearInterval(base.inactivitytimer);
			clearInterval(base.timer);
			// Create a new one
			base.inactivitytimer = setInterval(function(){ base.handleInactivity(); }, options.inactivityInterval);
		}
		base.handleInactivity = function(){
			base.startInterval();
		}
		base.clickAction = function(obj){
			$(base).find('li.active').removeClass('active');
			$(obj).addClass("active");
			base.showContent(obj);
			// Only if the slider should move.
			if(!options.disableInterval && options.interval>0){
				// Ping off the inactivityInterval
				base.inactivityStart();
			}
		}		
		base.startInterval = function(){
			clearInterval(base.inactivitytimer);
			base.timer = setInterval(function(){ base.handleInterval(); }, options.interval);
		}
		base.handleInterval = function(){
			
			// Log the call
			console.log("firing the interval off "+parseInt(base.currentslide+1));
			
			if(parseInt(base.currentslide+1) >= base.totalSlides){
				base.currentslide = -1;
				base.nextslide = 0;
			}
			
			// Activate the right menu list class.
			$(base).find('li.active').removeClass('active');
			$(base).find("[data-list='" + parseInt(base.currentslide+1) + "']").addClass("active");
			
			// Hide and show new slide content.
			base.hideAllSlides();
			var slide = $(options.slides).find("[data-slide='" + parseInt(base.currentslide+1) + "']");
			base.showSlide(slide);
			
			// Move the counters along.
			base.currentslide = base.currentslide+1;
			base.nextslide = base.nextslide+1;
			
		}
		base.showContent = function(obj){ 
			// Show corresponding slide
			base.hideAllSlides();
			// Update the current slide
			base.currentslide = $(obj).data('list');
			base.nextslide = parseInt($(obj).data('list')+1);
			// Select the new slide
			var slide = $(options.slides).find("[data-slide='" + $(obj).data('list') + "']");
			base.showSlide(slide);
			// If sub slides have been enabled
			if(options.enablesubslides===true){
				base.enableSubs(obj);
			}
		}
		base.enableSubs = function(obj){
			var subSlideContainer = $(options.slides).find("[data-slide='" + $(obj).data('list') + "']");
			var subContainer = $(subSlideContainer).find(options.subclass);
			var pager = $(subSlideContainer).parent().find(options.subslidePagerClass);
			$(pager).html('');
			// Check if any children are present.
			if($(subContainer).children().length > 0){
				$(subContainer)
				.cycle({ 
				    fx:     'scrollRight', 
				    speed:  300, 
				    timeout: 0, 
				    next:   $(subContainer).parent().find(options.subslideNext), 
				    prev:   $(subContainer).parent().find(options.subslidePrev),
					pager:  pager
				});
			}
		}
		base.showSlide = function(slide){
			if(options.effect=='fade'){
				$(slide).fadeIn('slow',function(){
					$(slide).find('a').click(function(){
						location.replace($(this).attr('href'));
					});
				});
			}else if(options.effect=='slideDown'){
			
			}			
		}
		base.hideAllSlides = function(){
			$(options.slides).find('> li').each(function(i,value){
				$(this).addClass("display-off").hide();
			});
		}
		
		// Hook up the list menu.
		$(this).find('li').each(function(i,value){				
			// Bind click actions
			$(this).bind('click', function(){
				base.clickAction(this);
			}).attr("data-list", i);
		});
		
		// Register total slides
		base.totalSlides = $(this).find('li').length;
		
		// Add the active class to the first slide.
		$(this).find('li').first().addClass("active");		
		
		// Hook up the slides and add data to them.
		$(options.slides).find('> li').each(function(i,value){
			$(this).attr("data-slide", i);
		});
			
		// Kick off the content interval
		if(options.interval>0 && !options.disableInterval){
			base.startInterval();
		}
		
		// Show the first slide 
		var firstSlide = $(options.slides).find('li').first();
		base.showSlide(firstSlide);
		
	});
 };
})(jQuery);
