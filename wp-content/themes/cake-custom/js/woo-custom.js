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
	$('.checkout > .columns').wrapAll('<div class="row" id="checkoutbox"></div>');
	//$("span.pass:not(:has(.woocommerce-password-hint))").removeClass('pass');
	$('#customer_login > div.u-column1 > h2, #customer_login > div.u-column1 > form').wrapAll('<div class="inner"></div>');
	$('#customer_login > div.u-column2 > h2, #customer_login > div.u-column2 > form').wrapAll('<div class="inner"></div>');
});