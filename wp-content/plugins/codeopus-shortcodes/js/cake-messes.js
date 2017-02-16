jQuery(document).ready(function($){
	
"use strict";
	
var element = $('.cake-messes');

element.each(function(){
	
	var color = $(this).attr("data-color");
	
	$(this).find('.messes-title').each(function () {
		$(this).css({'color' : color});
		$(this).next().css({'background-color' : color});
		
	});
	
});
	
});

