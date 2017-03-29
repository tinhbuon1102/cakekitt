jQuery(function($){
	if ($("#fanpop").length > 0) {
        $("#fanpop").fancybox({
            width: 'auto',
            height: 'auto',
            padding: 0,
            margin: 0,
            mouseWheel: true,
            fitToView: false,
            autoSize: false,
            closeClick: false,
			hideOnOverlayClick: false,
			enableEscapeButton: false,
			wrapCSS : 'kitt-fancy',
			keys : {
				close : null // default value = [27]
			},
            helpers: {
                overlay: {
                    locked: false,
					closeClick: false
                }
            },
			afterload : function () {
				$('.fancybox-skin').addClass('landscape');
			}
        });
		/*var dfimage = $('img.esgbox-image');
		dfimage.on('load',function(){
		var img = new Image();
		img.src = dfimage.attr('src');
		var imgWidth = img.width;
		var imgHeight = img.height;
		var aspectRatio = imgWidth / imgHeight;
		$('.kitt-fancy > .fancybox-skin').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
		});*/
    }
});