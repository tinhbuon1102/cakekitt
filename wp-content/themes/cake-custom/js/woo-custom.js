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
	//$('.checkout > .columns').wrapAll('<div class="row" id="checkoutbox"></div>');
	$(".order-detail-custom-table > .cake_info_wraper > .row:has(.col-xs-3)").addClass('first-child');
	//$("span.pass:not(:has(.woocommerce-password-hint))").removeClass('pass');
	$('#customer_login > div.u-column1 > h2, #customer_login > div.u-column1 > form').wrapAll('<div class="inner"></div>');
	$('#customer_login > div.u-column2 > h2, #customer_login > div.u-column2 > form').wrapAll('<div class="inner"></div>');
	$('body.woocommerce-edit-address .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses.col2-set > .col-1').hide();
	$('body.woocommerce-edit-address .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses.col2-set > .col-2').removeClass('col-2');
	//add numbering for label of customer info and shipping info of woo
	$('#customer_details > .col-1 > .woocommerce-billing-fields > h3').addClass('numbering display-table');
	$('#customer_details h3.numbering').wrapInner('<span class="display-table-cell pl-2"></span>');
	$('<span class="title-number display-table-cell"></span>').insertBefore('#customer_details h3.numbering .pl-2');
	$('<span class="title-number display-table-cell"></span>').insertBefore('#checkoutbox .columns h4.heading-form .pl-2');
	$('#customer_details .col-1 h3.numbering .title-number').append('1');//numbering for Enter your info
	$('#checkoutbox .columns h4.heading-form .title-number').append('2');//numbering for Deliver info
	
});
