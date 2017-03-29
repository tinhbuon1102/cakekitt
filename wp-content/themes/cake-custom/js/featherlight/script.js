jQuery(function($) {
	function resizeGalleryPopup(){
		//var hsize = $(window).height();
		//var hinsize = hsize - 100;
        //var immdwsize = $('.image-middler:visible').width();
        
        var img = new Image();
		img.src = $('.image-middler:visible img').attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		//var aspectRatio = imgWidth / imgHeight;
        console.log('imgWidth: ' + imgWidth);
        console.log('imgHeight: ' + imgHeight);
		/*if (imgHeight <= hsize) {
			$('.featherlight').addClass('nofullbox');
		} else {
			$('.featherlight').addClass('fullbox');
		}*/
		//for portrait
		/*if (imgHeight <= hsize && imgWidth < immdwsize && imgHeight > imgWidth) {
			//$(".galcon-inner .image-inner").css("height", imgHeight + "px");
			$(".galcon-inner .image-inner").css("width", imgWidth + "px");
			//$(".galcon-inner").css("height", hsize - 100 + "px");
		} else if (imgHeight >= hsize && imgHeight > imgWidth) {
			//$(".galcon-inner .image-inner").css("height", hinsize + "px");
			$(".galcon-inner .image-inner").css("width", hinsize * aspectRatio + "px");
			//$(".galcon-inner").css("height", hsize - 100 + "px");
		}*/
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
			console.log('hinsize: ' + hinsize);
			$('div.galBox').removeClass('portrait');
			$('div.galBox').removeClass('landscape');
			$('div.featherlight-content').addClass( (aspectRatio <= 1) ? 'portrait' : 'landscape');
			$('.image-outer, .gal-content-inside-wrap').removeClass('table-cell'); //when portrait
			$('.image-outer, .gal-content-inside-wrap').removeClass('non-table-cell'); //when landscape
			$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'table-cell' : 'non-table-cell'); //when portrait:landscape
			$('.featherlight').addClass( (imgHeight <= hinsize) ? 'nofullbox' : 'fullbox');
			$('.image-inner').removeClass('nominimize');
			$('.image-inner').removeClass('minimize');
			$('.image-inner').addClass( (imgHeight <= hinsize) ? 'nominimize' : 'minimize');
			$('.landscape').addClass( (imgHeight <= hinsize) ? 'nominimize-wrap' : 'minimize-wrap');
			var galconHeight = $('.landscape .gal-content-inside-wrap').height();
			var felandHeight = $('.featherlight-content.landscape').height();
			console.log('landscape height: ' + felandHeight);
			$('.landscape .image-inner.minimize').css("height", felandHeight - galconHeight + "px");
			var landscapeImgWidth = $('.landscape .lightbox-image').width();
			console.log('landscape image width: ' + landscapeImgWidth);
			$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
			//$('.landscape .image-inner').css("display", "table-cell");
			var portraitHeight = $('.featherlight-content.portrait').height();
			console.log('portrait height: ' + portraitHeight);
			$('.portrait .image-inner').css("height", portraitHeight + "px");
			var portraitImgWidth = $('.portrait .lightbox-image').width();
			console.log('portrait image width: ' + portraitImgWidth);
			$('.portrait .image-inner').css("width", portraitImgWidth + "px");
			resizeGalleryPopup();
		}
	});
});
