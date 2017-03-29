jQuery(function($) {
	var dfimage = $('img.esgbox-image');
	dfimage.on('load',function(){
		var img = new Image();
		img.src = dfimage.attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		var aspectRatio = imgWidth / imgHeight;
		$('div.galBox').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
	});
	/*$.fn.featherlight = function(){
		$(this).find(".featherlight").addClass('landscape');
	};
	$('body').on('click', '.featherlight', function() {
		var featherlight = $(this);
		$('.featherlight').addClass('landscape');
	});*/
});
