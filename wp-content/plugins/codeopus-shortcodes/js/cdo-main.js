/* --------------------------------------------------------------------------
 *  
 * Author         : Codeopus
 * Author URI     : http://codeopus.net
 *
 * -------------------------------------------------------------------------- */
(function($){
/* --------------------------------------------------------------------------
 * jQuery Handle Initialization
 * -------------------------------------------------------------------------- */
	"use strict";

	/* ----------- SETTING ----------- */
	var cdoPlugin = {
		

		// media element
		cdo_animation:function() {
			
			if (!Modernizr.touch) {
				if ($(".animation")[0]) {
					$('.animation').css('opacity', '0');
				}

				$('.animation').waypoint(function() {
					var animate = $(this).attr('data-animate');
					var delayanimate = $(this).attr('data-delay');

					if( delayanimate > 0 ) {
						var delayTime = (delayanimate / 1000) + 's';

						$(this).css({
							'visibility'              : 'visible',
							'-webkit-animation-delay' : delayTime,
							'-moz-animation-delay'    : delayTime,   
							'-o-animation-delay'      : delayTime,     
							'animation-delay'         : delayTime,
						});
					}

					$(this).css('opacity', '');
					$(this).addClass("animated " + animate);
				}, {
					offset: '80%',
					triggerOnce: true
				});
			}

		},


		
		// theme init
		cdo_init:function(){
	        cdoPlugin.cdo_animation();
	    }
		
	}
	
	
	  
	// intialization
	jQuery(document).ready(function($){
	
		cdoPlugin.cdo_init();
		
		
	});

}(jQuery));
