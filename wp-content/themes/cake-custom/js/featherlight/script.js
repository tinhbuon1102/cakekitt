jQuery(function($) {
	$('#grid-gallery a').featherlight ({
		afterOpen: function(event) {
		var lbimage = $(this).find('img.lightbox-image');
		lbimage.on('load',function(){
		var img = new Image();
		img.src = lbimage.attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		var aspectRatio = imgWidth / imgHeight;
		$('div.galBox').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
	});	
		}
	});
});
