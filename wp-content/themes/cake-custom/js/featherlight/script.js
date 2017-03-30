jQuery(function($) {
	function resizeGalleryPopup(){
		//var hsize = $(window).height();
		//var hinsize = hsize - 100;
        //var immdwsize = $('.image-middler:visible').width();
        //var wsize =  $(window).width();
		//console.log('window width: ' + wsize);
		/*var feWsize =  $('.featherlight-content').width();
	    console.log('feather width: ' + feWsize);*/
        var img = new Image();
		img.src = $('.image-middler:visible img').attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		var aspectRatio = imgWidth / imgHeight;
        console.log('imgWidth: ' + imgWidth);
        console.log('imgHeight: ' + imgHeight);
		/*$('.featherlight').removeClass('shrink');
		$('.featherlight').removeClass('noshrink');
		if (wsize < feWsize) {
			$('.featherlight').addClass('shrink');
		} else {
			$('.featherlight').addClass('noshrink');
		}*/
		$('.image-outer, .gal-content-inside-wrap').removeClass('table-cell'); //when portrait
		$('.image-outer, .gal-content-inside-wrap').removeClass('non-table-cell'); //when landscape
		$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'table-cell' : 'non-table-cell'); //when portrait:landscape
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
			//$('.image-outer, .gal-content-inside-wrap').removeClass('table-cell'); //when portrait
			//$('.image-outer, .gal-content-inside-wrap').removeClass('non-table-cell'); //when landscape
			//$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'table-cell' : 'non-table-cell'); //when portrait:landscape
			$('.featherlight').addClass( (imgHeight <= hinsize) ? 'nofullbox' : 'fullbox');
			$('.image-inner').removeClass('nominimize');
			$('.image-inner').removeClass('minimize');
			$('.image-inner').addClass( (imgHeight <= hinsize) ? 'nominimize' : 'minimize');
			$('.landscape').addClass( (imgHeight <= hinsize) ? 'nominimize-wrap' : 'minimize-wrap');
			//var gcWsize =  $('.gal-content-inside-wrap').innerWidth();
			//console.log('gal-content-inside-wrap width: ' + gcWsize);
			var galconHeight = $('.landscape .gal-content-inside-wrap').height();
			var felandHeight = $('.featherlight-content.landscape').height();
			//console.log('landscape height: ' + felandHeight);
			$('.landscape .image-inner.minimize').css("height", felandHeight - galconHeight + "px");
			var landscapeImgWidth = $('.landscape .lightbox-image').width();
			//console.log('landscape image width: ' + landscapeImgWidth);
			$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
			//var landscapeImgWidth = $('.landscape .lightbox-image').width();
			//console.log('landscape image width: ' + landscapeImgWidth);
			//$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
			/*var portraitHeight = $('.featherlight-content.portrait').height();
			console.log('portrait height: ' + portraitHeight);
			$('.portrait .image-inner').css("height", portraitHeight + "px");
			var portraitImgWidth = $('.portrait .lightbox-image').width();
			console.log('portrait image width: ' + portraitImgWidth);
			$('.portrait .image-inner').css("width", portraitHeight * aspectRatio + "px");*/
			var wsize =  $(window).width();
			var feWsize =  $('.featherlight-content').width();
			var portraitHeight = $('.featherlight-content.portrait').height();
			$('.portrait .image-inner').css('width', wsize > feWsize ? (portraitHeight * aspectRatio) + "px" : "");
			$('.portrait .image-inner').css('height', wsize > feWsize ? portraitHeight + "px" : "");
			$('.featherlight-content').removeClass( (wsize > feWsize) ? '' : 'portrait');
			$('.featherlight-content').addClass( (wsize > feWsize) ? '' : 'portrait-shrink');
			//var psgalconHeight = $('.portrait-shrink .gal-content-inside-wrap').height();
			//var psfelandHeight = $('.featherlight-content.portrait-shrink').height();
			//$('.portrait-shrink .image-inner').css("height", psfelandHeight - psgalconHeight + "px");
			//$('.portrait-shrink .image-inner').css("width", (psfelandHeight - psgalconHeight) * aspectRatio + "px");
			
			resizeGalleryPopup();
		},
		onResize : function() {
			var lightbox = $($(event.currentTarget).attr('href'));
			var lbimage = lightbox.find('img.lightbox-image');
			var img = new Image();
			img.src = lbimage.attr('src');
			var imgWidth = img.width;
			var imgHeight = img.height;
			var aspectRatio = imgWidth / imgHeight;
			var wsize =  $(window).width();
			//**********landscape**********//
			/*var galconHeight = $('.landscape .gal-content-inside-wrap').height();
			var felandHeight = $('.featherlight-content.landscape').height();
			$('.landscape .image-inner.minimize').css("height", felandHeight - galconHeight + "px");*/
			var landscapeImgWidth = $('.landscape .lightbox-image').width();
			$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
			//**********portrait**********//
			//console.log('window width: ' + wsize);
			var feWsize =  $('.featherlight-content').width();
			//console.log('featherlight-content width: ' + feWsize + 'px');
			var portraitHeight = $('.featherlight-content.portrait').height();
			//console.log('portrait height: ' + portraitHeight);
			$('.portrait .image-inner').css('width', wsize > feWsize ? (portraitHeight * aspectRatio) + "px" : "");
			$('.portrait .image-inner').css('height', wsize > feWsize ? portraitHeight + "px" : "");
			$('.featherlight-content').removeClass( (wsize > feWsize) ? '' : 'portrait');
			$('.featherlight-content').addClass( (wsize > feWsize) ? '' : 'portrait-shrink');
			//var psgalconHeight = $('.portrait-shrink .gal-content-inside-wrap').height();
			//var psfelandHeight = $('.featherlight-content.portrait-shrink').height();
			//$('.portrait-shrink .image-inner').css("height", psfelandHeight - psgalconHeight + "px");
			//$('.portrait-shrink .image-inner').css("width", (psfelandHeight - psgalconHeight) * aspectRatio + "px");
			/*if (wsize > feWsize) {
				$('.portrait .image-inner').css("height", portraitHeight + "px");
				$('.portrait .image-inner').css("width", portraitHeight * aspectRatio + "px");
			}*/
		}
	});
});
