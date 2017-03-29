jQuery(function($) {
	$('#grid-gallery a').featherlight({
		targetAttr : 'href',
		afterContent : function(event) {
			var lightbox = $(event.currentTarget).attr('href');
			var lbimage = $(lightbox).find('img.lightbox-image');
			var img = new Image();
			img.src = lbimage.attr('src');
			var imgWidth = img.width;
			var imgHeight = img.height;
			var aspectRatio = imgWidth / imgHeight;
			console.log(imgWidth);
			console.log(imgHeight);
			$('div.galBox').removeClass('portrait');
			$('div.galBox').removeClass('landscape');
			$('div.galBox').addClass( (aspectRatio <= 1) ? 'portrait' : 'landscape');
		}
	});
});
