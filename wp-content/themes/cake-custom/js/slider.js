$(function() {
	$(window).on('load', function(){
		var windowWidth = $(window).width();
		var windowHeight = $(window).height();
		if (windowWidth > 479) {
			$('#slideshow-container').css('height', windowHeight + 'px');
		}
		if (windowWidth > 479 && windowHeight > 600 && windowHeight <= 660) {
			$('.rabels_3columns').addClass('minheight1');
			$('.label-icon > img').wrap('<div class="table-cell tabel-cell-icon"></div>');
			$('.label-icon').prepend('<div class="table-cell table-cell-space"></div>');
			$('.tabel-cell-icon').after('<div class="table-cell table-cell-space"></div>');
		} else if (windowWidth > 479 && windowHeight <= 600) {
			$('.rabels_3columns').addClass('minheight2');
		}
	});
	$(window).on('load resize', function(){
		//width値を取得する
		//var windowWidth = $(window).width();
		//var windowHeight = $(window).height();
		//var sideWidth = $('.ordercake-cart-sidebar-container').width();
		//$('.cake-cart-sidebar').css('width', sideWidth + 'px');
		//var divWidth = $('.round-icon-select #fixwh-inner').width();
		//var divfixHeight = $('.round-icon-select #fixwh-inner .center-middle-fix').height();
		//$('.round-icon-select #fixwh-inner').css('height', divWidth + 'px');
		//$('.round-icon-select #fixwh-inner .center-middle-fix').css('margin-top', '-' + (divfixHeight / 2 + 10) + 'px');
	});
});
