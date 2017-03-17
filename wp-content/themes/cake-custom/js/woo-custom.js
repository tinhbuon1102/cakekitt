// woocommerce js code
$(function() {
	$('#username').attr( 'placeholder', 'ユーザ名またはメールアドレス' );
	$('#password').attr( 'placeholder', 'パスワード' );
	$('#reg_email').attr( 'placeholder', 'メールアドレス' );
	$('#reg_password').attr( 'placeholder', 'パスワード' );
	$('input#username').wrap('<span class="user"></span>');
	$('input#password').wrap('<span class="pass"></span>');
	//$('input#reg_password').wrap('<span class="pass"></span>');
	$('input#reg_email').wrapAll('<span class="email"></span>');
	$('.checkout > h3#order_review_heading, .checkout > #order_review').wrapAll('<div class="col-md-4 columns position-static pt-md-4 pt-sm-2 pb-sm-4"></div>');
	$('.checkout > #customer_details, .checkout > ul').wrapAll('<div class="col-md-8 columns"></div>');
	$('.checkout > .col-md-8 > ul').wrap('<div class="select-datetime"></div>');
	$('.checkout > .columns').wrapAll('<div class="row" id="checkoutbox"></div>');
	//$("span.pass:not(:has(.woocommerce-password-hint))").removeClass('pass');
	$('#customer_login > div.u-column1 > h2, #customer_login > div.u-column1 > form').wrapAll('<div class="inner"></div>');
	$('#customer_login > div.u-column2 > h2, #customer_login > div.u-column2 > form').wrapAll('<div class="inner"></div>');
	$('body.woocommerce-edit-address .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses.col2-set > .col-1').hide();
	$('body.woocommerce-edit-address .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses.col2-set > .col-2').removeClass('col-2');
});
jQuery(window).load(function(){
	if($('.woocommerce-checkout #shipping_method').length > 0){
		$('.woocommerce-checkout #shipping_method li').each(function(){
			if($(this).find('div').hasClass('checked')){
				var shipping_mtd = $(this).find('label').text();
				if(shipping_mtd != 'Local Pickup'){
					$('#ship-to-different-address .icheckbox_square-pink').addClass('checked').css('display','none');
					//$('#ship-to-different-address label').text('別の住所へ配送しますか').css('padding-left','0px');
					$('#ship-to-different-address').text('Deliver Info');
					$('.woocommerce-shipping-fields .shipping_address').css('display','block');
				}
			}
		});
	}
});
jQuery(document).ready(function(){
	if($('.woocommerce-checkout #shipping_method').length > 0){
		jQuery(".woocommerce-checkout #shipping_method li").click(function(){
			if($(this).find('div').hasClass('checked')){
				var shipping_mtd = $(this).find('label').text();
				if(shipping_mtd != 'Local Pickup'){
					$('#ship-to-different-address .icheckbox_square-pink').addClass('checked').css('display','none');
					$('.woocommerce-shipping-fields .shipping_address').css('display','block');
				}else{
					$('.woocommerce-shipping-fields .shipping_address').css('display','none');
				}
			} 
		});
	}
});