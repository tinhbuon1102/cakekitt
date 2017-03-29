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
			$('div.featherlight-content').addClass( (aspectRatio <= 1) ? 'portrait' : 'landscape');
			$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'col-sm-6' : 'col-sm-12');
			$(window).on('load resize', function(){
								//height値を取得する
								var hsize = $(window).height();
								var hinsize = hsize - 100;
				                var immdwsize = $('.image-middler').width();
				                console.log(immdwsize);
								if (imgHeight <= hsize && imgWidth < immdwsize) {
									$(".galcon-inner .image-inner").css("height", imgHeight + "px");
									$(".galcon-inner .image-inner").css("width", imgWidth + "px");
									//$(".kitt-wrap .esgbox-skin").css("width", imgWidth + 50 + "px");
									$('.featherlight').addClass('nofullbox');
									$(".galcon-inner").css("height", hsize - 100 + "px");
								} else if (imgHeight >= hsize) {
									$('.featherlight').addClass('fullbox');
									$(".galcon-inner .image-inner").css("height", hinsize + "px");
									$(".galcon-inner .image-inner").css("width", hinsize * aspectRatio + "px");
									$(".galcon-inner").css("height", hsize - 100 + "px");
								}
							});
		}
	});
});
