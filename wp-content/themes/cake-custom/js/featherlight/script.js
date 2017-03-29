jQuery(function($) {
	function resizeGalleryPopup(){
		var hsize = $(window).height();
		var hinsize = hsize - 100;
        var immdwsize = $('.image-middler:visible').width();
        console.log(immdwsize);
		/*if (imgHeight <= hsize) {
			$('.featherlight').addClass('nofullbox');
		} else {
			$('.featherlight').addClass('fullbox');
		}*/
		if (imgHeight <= hsize && imgWidth < immdwsize) {
			$(".galcon-inner .image-inner").css("height", imgHeight + "px");
			$(".galcon-inner .image-inner").css("width", imgWidth + "px");
			//$(".kitt-wrap .esgbox-skin").css("width", imgWidth + 50 + "px");
			$(".galcon-inner").css("height", hsize - 100 + "px");
		} else if (imgHeight >= hsize) {
			$(".galcon-inner .image-inner").css("height", hinsize + "px");
			$(".galcon-inner .image-inner").css("width", hinsize * aspectRatio + "px");
			$(".galcon-inner").css("height", hsize - 100 + "px");
		}
	}
	$(window).on('resize', function(){
		setTimeout(function(){
			resizeGalleryPopup();
		}, 500);
	});
	
	$('#grid-gallery a').featherlight({
		targetAttr : 'href',
		afterContent : function(event) {
			var lightbox = $($(event.currentTarget).attr('href'));
			var lbimage = lightbox.find('img.lightbox-image');
			//var cimgWidth = img.width;
			//var cimgHight = img.height;
			var img = new Image();
			img.src = lbimage.attr('src');
			var imgWidth = img.width;
			var imgHeight = img.height;
			var aspectRatio = imgWidth / imgHeight;
			var hsize = $(window).height();
			var hinsize = hsize - 100;
			$('div.galBox').removeClass('portrait');
			$('div.galBox').removeClass('landscape');
			$('div.featherlight-content').addClass( (aspectRatio <= 1) ? 'portrait' : 'landscape');
			$('.image-outer, .gal-content-inside-wrap').removeClass('col-sm-6');
			$('.image-outer, .gal-content-inside-wrap').removeClass('col-sm-12');
			$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'col-sm-6' : 'col-sm-12');
			$('.featherlight').addClass( (imgHeight <= hinsize) ? 'nofullbox' : 'fullbox');
			$('.image-inner').removeClass('nominimize');
			$('.image-inner').removeClass('minimize');
			$('.image-inner').addClass( (imgHeight <= hinsize) ? 'nominimize' : 'minimize');
			var galconHeight = $('.landscape .gal-content-inside-wrap').height();
			$('.landscape .image-inner.minimize').css("height", hinsize - galconHeight + "px");
			var cimgw = $('.landscape .image-inner.minimize').width();
			console.log('コンテンツ本体：' + cimgw);
			//$('.featherlight-content.landscape').css("width", cimgWidth + 50 + "px");
			/*if (imgHeight <= hinsize) {
				$('.featherlight').addClass('nofullbox');
			} else if (imgHeight >= hinsize) {
				$('.featherlight').addClass('fullbox');
			}*/
			resizeGalleryPopup();
		}
	});
});
