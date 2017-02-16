 jQuery(document).ready(function($){
	 
	 
	$.fn.cycle.transitions.custom = {
        before: function(opts, curr, next, fwd) {
            opts.API.stackSlides(opts, curr, next, fwd);
            opts.cssBefore = {
                 top: 500,
                 opacity: 1, 
				 display: 'block', 
				 visibility: 'visible',
            };
			opts.animIn = {
                top: 0,
            };
            opts.animOut = {
                top: 500,
            };
        }
    };
	 
	var feature = $('.cdocycle-slides');
	
	$('.cdocycle-slides').cycle({
		timeout: 7000,
		fx: 'custom',
		slides : '> div',
		next: '.nextControl',
		prev: '.prevControl',
		
	});
			
 });
 