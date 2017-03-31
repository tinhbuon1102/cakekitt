jQuery(function($) {
	$('#custom_order_cake_decorate').change(function(){
	if ($(this).is(':checked')) {
		$('#custom_order_basecolor_text_wraper').removeClass('disabled');
        $('#custom_order_icingcookie_qty_wraper').removeClass('disabled');
	} else {
		$('#custom_order_basecolor_text_wraper').addClass('disabled');
        $('#custom_order_icingcookie_qty_wraper').addClass('disabled');
	}
	});
});