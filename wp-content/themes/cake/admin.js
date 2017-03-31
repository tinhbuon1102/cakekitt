jQuery(function($) {
	if (!$('#custom_order_cake_decorate').is(':checked')) {
		$('#icingcookie_wraper').addClass('disabled');
		$('#custom_order_basecolor_text_wraper').addClass('disabled');
        $('#custom_order_icingcookie_qty_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-cupcake').is(':checked')) {
		$('#cupcake_wraper').addClass('disabled');
		$('#custom_order_cupcake_qty_wraper').addClass('disabled');
        $('#custom_order_cpck_text_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-macaron').is(':checked')) {
		$('#macaron_wraper').addClass('disabled');
		$('#custom_order_macaron_qty_wraper').addClass('disabled');
        $('#custom_order_macaron_color_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-flower').is(':checked')) {
		$('#flower_wraper').addClass('disabled');
		$('#custom_order_flowercolor_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-print').is(':checked')) {
		$('#photocakepic_wraper').addClass('disabled');
		$('#custom_order_photocakepic_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-candy').is(':checked')) {
		$('#candy_wraper').addClass('disabled');
		$('#custom_order_candy_text_wraper').addClass('disabled');
	}
	if (!$('#custom_order_cake_decorate-figure').is(':checked')) {
		$('#figure_wraper').addClass('disabled');
		$('#custom_order_doll_text_wraper').addClass('disabled');
	}
	$('#custom_order_cake_decorate').change(function(){
	if ($(this).is(':checked')) {
		$('#icingcookie_wraper').removeClass('disabled');
		$('#custom_order_basecolor_text_wraper').removeClass('disabled');
        $('#custom_order_icingcookie_qty_wraper').removeClass('disabled');
	} else {
		$('#icingcookie_wraper').addClass('disabled');
		$('#custom_order_basecolor_text_wraper').addClass('disabled');
        $('#custom_order_icingcookie_qty_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-cupcake').change(function(){
	if ($(this).is(':checked')) {
		$('#cupcake_wraper').removeClass('disabled');
		$('#custom_order_cupcake_qty_wraper').removeClass('disabled');
        $('#custom_order_cpck_text_wraper').removeClass('disabled');
	} else {
		$('#cupcake_wraper').addClass('disabled');
		$('#custom_order_cupcake_qty_wraper').addClass('disabled');
        $('#custom_order_cpck_text_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-macaron').change(function(){
	if ($(this).is(':checked')) {
		$('#macaron_wraper').removeClass('disabled');
		$('#custom_order_macaron_qty_wraper').removeClass('disabled');
        $('#custom_order_macaron_color_wraper').removeClass('disabled');
	} else {
		$('#macaron_wraper').addClass('disabled');
		$('#custom_order_macaron_qty_wraper').addClass('disabled');
        $('#custom_order_macaron_color_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-flower').change(function(){
	if ($(this).is(':checked')) {
		$('#flower_wraper').removeClass('disabled');
        $('#custom_order_flowercolor_wraper').removeClass('disabled');
	} else {
		$('#flower_wraper').addClass('disabled');
		$('#custom_order_flowercolor_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-print').change(function(){
	if ($(this).is(':checked')) {
		$('#photocakepic_wraper').removeClass('disabled');
        $('#custom_order_photocakepic_wraper').removeClass('disabled');
	} else {
		$('#photocakepic_wraper').addClass('disabled');
		$('#custom_order_photocakepic_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-candy').change(function(){
	if ($(this).is(':checked')) {
		$('#candy_wraper').removeClass('disabled');
        $('#custom_order_candy_text_wraper').removeClass('disabled');
	} else {
		$('#candy_wraper').addClass('disabled');
		$('#custom_order_candy_text_wraper').addClass('disabled');
	}
	});
	$('#custom_order_cake_decorate-figure').change(function(){
	if ($(this).is(':checked')) {
		$('#figure_wraper').removeClass('disabled');
        $('#custom_order_doll_text_wraper').removeClass('disabled');
	} else {
		$('#figure_wraper').addClass('disabled');
		$('#custom_order_doll_text_wraper').addClass('disabled');
	}
	});
});