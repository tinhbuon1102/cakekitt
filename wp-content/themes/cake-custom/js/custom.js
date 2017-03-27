jQuery(document).ready(function(){
	
	var address = [
		{postcode : '#deliver_postcode', state : '#deliver_state', city: '#deliver_city', address1: '#deliver_addr1'},
		{postcode : '#billing_postcode', state : '#billing_state', city: '#billing_city', address1: '#billing_address_1'},
		{postcode : '#shipping_postcode', state : '#shipping_state', city: '#shipping_city', address1: '#shipping_address_1'},
	]
	
	// Make state, city, address is readonly
	$.each(address, function(index, addressItem){
		$(addressItem['state']).attr('readonly', 'true');
		$(addressItem['city']).attr('readonly', 'true');
		$(addressItem['address1']).attr('readonly', 'true');
	});
	
	//Show Options
	//$('.gal_cat').SumoSelect();
	
	if ($(".gallery a").length > 0) {
        $(".gallery a").fancybox({
            width: 'auto',
            height: 'auto',
            padding: 0,
            margin: 0,
            autoSize: false,
            mouseWheel: true,
            fitToView: false,
            autoSize: false,
            closeClick: false,
			hideOnOverlayClick: false,
			enableEscapeButton: false,
			keys : {
				close : null // default value = [27]
			},
            helpers: {
                overlay: {
                    locked: false,
					closeClick: false
                }
            }
        });
    }
	$(function() {

        var $document = $(document);
        var selector = '[data-rangeslider]';
        var $element = $(selector);

        // For ie8 support
        var textContent = ('textContent' in document) ? 'textContent' : 'innerText';

        // Example functionality to demonstrate a value feedback
        function valueOutput(element) {
            var value = element.value;
            var output = $('.timepicker .timepick output').get(0)
            value = value < 12 ? (value + gl_timeAM) : (value + gl_timePM);
            if (output)
            {
            	output[textContent] = value;
            }
        }

        $document.on('input', 'input[type="range"], ' + selector, function(e) {
            valueOutput(e.target);
        });
        
        // Basic rangeslider initialization
        $element.rangeslider({

            // Deactivate the feature detection
            polyfill: false,

            // Callback function
            onInit: function() {
            	$('body').on('click', '.time-range__minus', function(){
            		var rangeInput = $(this).closest('.time-range').find('input[type="range"]');
            		rangeInput.val(parseInt(rangeInput.val()) - 1).change();
                });
                
                $('body').on('click', '.time-range__plus', function(){
                	var rangeInput = $(this).closest('.time-range').find('input[type="range"]');
                	rangeInput.val(parseInt(rangeInput.val()) + 1).change();
                });
                
                valueOutput(this.$element[0]);
            },

            // Callback function
            onSlide: function(position, value) {
            },

            // Callback function
            onSlideEnd: function(position, value) {
            }
        });

    });
	
	$('input.checkbox_input.labelauty').on('change', function(event){
		$(this).closest('li').find('.suboption_box').toggleClass('disable');
	});
	
	$('#username').attr( 'placeholder', 'ユーザネームまたはメールアドレス' );
	$('#password').attr( 'placeholder', 'パスワード' );
	
	$('input.radio_input, input.checkbox_input').on('ifUnchecked', function(event){
		$(this).closest('li').find('.suboption_box').toggleClass('disable');
	});
	
	var ribbonWidth = $( ".label-btn" ).outerWidth();
	var ribbonHalfWidth = $( ".label-btn" ).outerWidth() / 2;
	$('head').append('<style>.label-btn:after { width: ' + ribbonWidth + 'px; } </style>');
	$('head').append('<style>.label-btn:after { border-width: ' + ribbonHalfWidth + 'px; } </style>');
	
	//Kana
	jQuery.fn.autoKana('#customer_name_last', '#customer_name_last_kana');
    jQuery.fn.autoKana('#customer_name_first', '#customer_name_first_kana');
    
    jQuery.fn.autoKana('#account_last_name', '#account_last_name_kana');
    jQuery.fn.autoKana('#account_first_name', '#account_first_name_kana');
    
    jQuery.fn.autoKana('#billing_first_name', '#billing_first_name_kana');
    jQuery.fn.autoKana('#billing_last_name', '#billing_last_name_kana');
	
    
    function loadIcheck(){
    	$("input.labelauty").labelauty();
    	
    	$('input:radio:not(.labelauty), input:checkbox:not(.labelauty)').each(function(){
    		if (!$(this).next().hasClass('iCheck-helper'))
    		{
    			$(this).iCheck({
    	    	    checkboxClass: 'icheckbox_square-pink',
    	    	    radioClass: 'iradio_square-pink',
    	    	    increaseArea: '20%' 
    	    	  });
    		}
    	})
    }
    $( document.body ).on( 'updated_cart_totals', function(){
    	loadIcheck();
    });
    $( document.body ).on( 'updated_checkout', function(){
    	loadIcheck();
    });

    loadIcheck();
  
	jQuery('#BSbtndanger').filestyle({
				buttonName : 'btn-danger',
                buttonText : ' File selection'
			});
			jQuery('#BSbtnsuccess').filestyle({
				buttonName : 'btn-success',
                buttonText : ' Open'
			});
			jQuery('#BSbtninfo').filestyle({
				buttonName : 'btn-info',
                buttonText : ' Select a File'
			});
	jQuery(".panel-group a").click(function(){
		jQuery('.panel-group a').removeClass('is-selected');
		jQuery(this).addClass('is-selected');
		$('#custom_order_shipping_' + $(this).attr('id')).iCheck('check');
		$('#custom_order_shipping_' + $(this).attr('id')).change();
});
	jQuery(".panel-group a#delivery").click(function(){
		jQuery('.deliver-info').removeClass('disable');
});
	jQuery(".panel-group a#pickup").click(function(){
		jQuery('.deliver-info').addClass('disable');
});
//auto height
jQuery('.c-list_3Column li.m-input__radio').autoHeight();
jQuery('.round-icon-select li.m-input__radio').autoHeight();

if (jQuery(".ordercake-cart-sidebar-container").length)
{
	jQuery(".ordercake-cart-sidebar-container").pinBox({
		//default 0px
		Top : '90px',
		//default '.container' 
		Container : '#pinBoxContainer',
		//default 20 
		ZIndex : 20,
		//default '767px' if you disable pinBox in mobile or tablet
		MinWidth : '991px'
		//events if scrolled or window resized 
	});
}

if (jQuery(".ordercake-cart-sidebar-container2").length)
{
	jQuery(".ordercake-cart-sidebar-container2").pinBox({
		//default 0px
		Top : '120px',
		//default '.container' 
		Container : '#main.site-main',
		//default 20 
		ZIndex : 20,
		//default '767px' if you disable pinBox in mobile or tablet
		MinWidth : '991px'
		//events if scrolled or window resized 
	});
}
    // shape icons
	jQuery('.cake_shape_round i').addClass('iconkitt-kitt_icons_cake-round');
	jQuery('.cake_shape_square i').addClass('iconkitt-kitt_icons_shape-square');
	jQuery('.cake_shape_heart i').addClass('iconkitt-kitt_icons_shape-heart');
	jQuery('.cake_shape_star i').addClass('iconkitt-kitt_icons_shape-star');
	jQuery('.cake_shape_custom i').addClass('iconkitt-kitt_icons_shape-custom');
	//flavor icons
	jQuery('.cake_flavor_shortcake i').addClass('iconkitt-kitt_icons_shortcake');
	jQuery('.cake_flavor_chocolate i').addClass('iconkitt-kitt_icons_chocolate');
	jQuery('.cake_flavor_cheese i').addClass('iconkitt-kitt_icons_cheese');
	

$(window).on('load resize', function(){
//width値を取得する
var windowHeight = $(window).height();
var sideWidth = $('.ordercake-cart-sidebar-container').width();
$('.cake-cart-sidebar').css('width', sideWidth + 'px');

var divWidth = $('.round-icon-select #fixwh-inner').width();
var divfixHeight = $('.round-icon-select #fixwh-inner .center-middle-fix').height();
$('.round-icon-select #fixwh-inner').css('height', divWidth + 'px');
$('.round-icon-select #fixwh-inner .center-middle-fix').css('margin-top', '-' + (divfixHeight / 2 + 10) + 'px');
});

/* nav scroll*/
$(window).scroll(function(){
  if ($(window).scrollTop() > 100) {
    $('.navbar').addClass('scroll');
  } else {
    $('.navbar').removeClass('scroll');
  }
});
/* toggle menu*/
$('button#toggleMenu').click(function(){
    $('#toggleTarget').slideToggle('slow');
	if ($(this).hasClass('linericon-menu')){
        $(this).removeClass('linericon-menu');
		$(this).addClass('linericon-cross');
		$('.Header-supHeaderLogo').addClass('shrink');
		$('.navbar-fixed-top').addClass('open');
    } else {
		$(this).removeClass('linericon-cross');
        $(this).addClass('linericon-menu');
		$('.Header-supHeaderLogo').removeClass('shrink');
		$('.navbar-fixed-top').removeClass('open');
      }
});

$('body').on('change', '#deliver_postcode, #billing_postcode, #shipping_postcode', function(){
	var zip1 = $.trim($(this).val());
    var zipcode = zip1;
    var elementChange = $(this);
    
    // Remove error message about postcode
    $('.postcode_fail').remove();

    $.ajax({
        type: "post",
        url: gl_siteUrl + "/dataAddress/api.php",
        data: JSON.stringify(zipcode),
        crossDomain: false,
        dataType : "jsonp",
        scriptCharset: 'utf-8'
    }).done(function(data){
    	var address = [
    		{postcode : '#deliver_postcode', state : '#deliver_state', city: '#deliver_city', address1: '#deliver_addr1'},
    		{postcode : '#billing_postcode', state : '#billing_state', city: '#billing_city', address1: '#billing_address_1'},
    		{postcode : '#shipping_postcode', state : '#shipping_state', city: '#shipping_city', address1: '#shipping_address_1'},
    	]
    	
        if(data[0] == "" || gl_stateAllowed.indexOf(data[0]) == -1){
        	if (data[0] != "" && gl_stateAllowed.indexOf(data[0]) == -1)
        	{
        		var alertElement = '<span style="display: block" class="woocommerce-error postcode_fail clear">'+ gl_alertStateNotAllowed +'</span>';
        		elementChange.parent().append(alertElement);
        	}
        	$.each(address, function(index, addressItem){
        		$(addressItem['postcode']).val('');
        		$(addressItem['state']).val('');
        		$(addressItem['city']).val('');
        		$(addressItem['address1']).val('');
        	});
        	
        } else {
    		$.each(address, function(index, addressItem){
        		if ($(addressItem['postcode']).length && ('#'+elementChange.attr('id') == addressItem['postcode']))
        		{
        			$(addressItem['state'] + ' option').each(function(){
                		if($(this).text() == data[0])
                		{
                			$(addressItem['state']).val($(this).attr('value')).change();
                		}
                	});
                	
                    $(addressItem['city']).val(data[1]);
//                    var address1 = $(addressItem['address1']).val();
                    var address1 = '';
                    address1 = address1.replace(data[2], '');
                    $(addressItem['address1']).val(data[2] + address1);
        		}
        	});
        }
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
    });
});

});


  
