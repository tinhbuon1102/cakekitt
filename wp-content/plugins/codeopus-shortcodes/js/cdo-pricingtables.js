jQuery(document).ready(function($){
	
"use strict";
	
var element = $('.cdo-pricing-grid');

element.each(function(){
	
	var column = $(this).attr("data-column");
	$(this).addClass('thecolumn-' + column);
	
	$(this).find('.cdo-pricing-item-container').each(function () {
		
		if(column=="2"){
			var columnClass = 'col-sm-6';
		}else if(column=="3"){
			var columnClass = 'col-sm-4';
		}else{
			var columnClass = 'col-sm-3';
		}
		
		$(this).removeClass('col-sm-3');
		$(this).addClass(columnClass);

		
	});
	
});
	
});

