jQuery(function($) {
	function resizeGalleryPopup(){
        var img = new Image();
		img.src = $('.image-middler:visible img').attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		var aspectRatio = imgWidth / imgHeight;
		
		$('.image-outer, .gal-content-inside-wrap').removeClass('table-cell'); //when portrait
		$('.image-outer, .gal-content-inside-wrap').removeClass('non-table-cell'); //when landscape
		$('.image-outer, .gal-content-inside-wrap').addClass( (aspectRatio <= 1) ? 'table-cell' : 'non-table-cell'); //when portrait:landscape
	}
	
	function initFeatherilght(){
		$('#grid-gallery a').featherlight({
			targetAttr : 'href',
			afterContent : function(event) {
				var lightbox = $($(event.currentTarget).attr('href'));
				var lbimage = lightbox.find('img.lightbox-image');
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
				$('.featherlight').addClass( (imgHeight <= hinsize) ? 'nofullbox' : 'fullbox');
				$('.image-inner').removeClass('nominimize');
				$('.image-inner').removeClass('minimize');
				$('.image-inner').addClass( (imgHeight <= hinsize) ? 'nominimize' : 'minimize');
				$('.landscape').addClass( (imgHeight <= hinsize) ? 'nominimize-wrap' : 'minimize-wrap');
				
				var galconHeight = $('.landscape .gal-content-inside-wrap').height();
				var felandHeight = $('.featherlight-content.landscape').height();
				$('.landscape .image-inner.minimize').css("height", felandHeight - galconHeight + "px");
				var landscapeImgWidth = $('.landscape .lightbox-image').width();
				$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
				var wsize =  $(window).width();
				var feWsize =  $('.featherlight-content').width();
				var portraitHeight = $('.featherlight-content.portrait').height();
				$('.portrait .image-inner').css('width', wsize > feWsize ? (portraitHeight * aspectRatio) + "px" : "");
				$('.portrait .image-inner').css('height', wsize > feWsize ? portraitHeight + "px" : "");
				$('.featherlight-content').removeClass( (wsize > feWsize) ? '' : 'portrait');
				$('.featherlight-content').addClass( (wsize > feWsize) ? '' : 'portrait-shrink');
				
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
				var landscapeImgWidth = $('.landscape .lightbox-image').width();
				$('.landscape .non-table-cell').css("width", landscapeImgWidth + "px");
				//**********portrait**********//
				var feWsize =  $('.featherlight-content').width();
				var portraitHeight = $('.featherlight-content.portrait').height();
				$('.portrait .image-inner').css('width', wsize > feWsize ? (portraitHeight * aspectRatio) + "px" : "");
				$('.portrait .image-inner').css('height', wsize > feWsize ? portraitHeight + "px" : "");
				$('.featherlight-content').removeClass( (wsize > feWsize) ? '' : 'portrait');
				$('.featherlight-content').addClass( (wsize > feWsize) ? '' : 'portrait-shrink');
			}
		});
	}
	
	$(window).on('resize', function(){
		setTimeout(function(){
			resizeGalleryPopup();
		}, 500);
	});

		
	$(document).ajaxStart(function(){
		$("#wait").css("display", "block");
	});

	$(document).ajaxComplete(function(){
		$("#wait").css("display", "none");
	}); 

	$('body').on('change', '.gal_cat,.gal_color_type,.gal_scene', function() {
		var search_terms = {};
		$('.gal_itms li').css('display','none');
		$('.filter_opt select').each(function(i,e){
			if($("option:selected", this).val().length > 0){
				var si_cls = $(this).attr('class');
				var si_val = $("option:selected", this).val();
				search_terms[si_cls]= si_val;
			}
		});
		var searchtrm = JSON.stringify(search_terms);
		var data = {
			action: 'load_items',
			searchtrm: searchtrm
		};
		$.post(jscon.ajaxurl, data, function(msg) {
			if(msg.output.length > 0){
				var out = msg.output;
				$('.gal_itms').html(out);
				initFeatherilght();
			}
		}, 'json');
	});
	
	initFeatherilght();
});
