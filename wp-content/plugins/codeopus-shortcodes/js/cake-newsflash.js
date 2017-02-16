jQuery(document).ready(function($){
	
"use strict";
	
var element = $('.cdo-newsflash-grid');

element.find(".cdo-newsflash-grid-item").each(function(){
	
	var width = $(this).attr("data-width");
	var height = $(this).attr("data-height");
	var bgcolor = $(this).attr("data-bgcolor");
	
	
		$(this).css({'width' : width, 'height' : height, 'background-color' : bgcolor});
	

	
});

$('.cdo-newsflash-grid').masonry({
  itemSelector: '.cdo-newsflash-grid-item',
  percentPosition: true
})
	
});

