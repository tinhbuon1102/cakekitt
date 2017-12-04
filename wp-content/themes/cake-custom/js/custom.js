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
	});
	
	//cancel policy check
	$('#submit_form_order').attr('disabled', 'disabled');
	
		$('#cpcheck').on('ifUnchecked', function(event) {
			$('#submit_form_order').attr('disabled', 'disabled');
		});
		$('#cpcheck').on('ifChecked', function(event){
			$('#submit_form_order').removeAttr('disabled');
		});
	//Show Options
	//$('.gal_cat').SumoSelect();
	
	$(function() {

        var $document = $(document);
        var selector = '[data-rangeslider]';
        var $element = $(selector);

        // For ie8 support
        var textContent = ('textContent' in document) ? 'textContent' : 'innerText';

        // Example functionality to demonstrate a value feedback
        function valueOutput(element) {
            var pick_time = element.value;
            pick_time = pick_time.replace('.5', ':30');
            var aPickTimes = pick_time.split(':');
            var hourTime = aPickTimes[0].length == 1 ? '0' + aPickTimes[0] : aPickTimes[0];
            var minuteTime = aPickTimes[1] ? aPickTimes[1] : '00';
            var output = $('.timepicker .timepick output').get(0)
            var timeText = pick_time < 12 ? (gl_timeAM) : (gl_timePM);
            if (output)
            {
            	output[textContent] = hourTime + ':' + minuteTime;
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

        
        if ($('.thwepo-extra-options.select_plate').length)
        {
        	var defaultSelectOption = '選択してください';
        	$('#plate').val();
        	$('#message_type').val();
        	$('#text_message').val();
        	$('#text_message_other').val();
        	
        	$('#plate').wrap('<div class="select-style black border-dark custom_select_wraper"></div>');
        	$('#message_type').wrap('<div class="select-style black border-dark custom_select_wraper"></div>');
        	
        	$('#plate > option:eq(0)').text(defaultSelectOption);
        	$('#message_type > option:eq(0)').text(defaultSelectOption);
        	
        	// Add require
        	$('#plate').addClass('validate[required]');
        	
        	$('html').on('change', '#plate', function(){
        		setTimeout(function(){$('#plate > option:eq(0)').text(defaultSelectOption);}, 300);
        		
        		if($(this).val() == 'Yes' || $(this).val() == 'はい')
        		{
        			$('.thwepo-extra-options.select_message_type').fadeIn();
        			$('#message_type').addClass('validate[required]');
        			$('#message_type > option:eq(0)').text(defaultSelectOption);
        		}
        		else {
        			$('#message_type').val('').change();
        			$('.thwepo-extra-options.select_message_type').fadeOut();
        			$('#message_type').removeClass('validate[required]');
        		}
        	});
        	
        	$('html').on('change', '#message_type', function(){
        		setTimeout(function(){$('#message_type > option:eq(0)').text(defaultSelectOption);}, 300);
        		
        		if($(this).val() == '')
        		{
        			$('.thwepo-extra-options.text_message_other').hide();
        			$('.thwepo-extra-options.text_message').hide();
        			$('#message_other').addClass('validate[required]');
        		}
        		else if($(this).val() != 'その他' && $(this).val() != 'Others')
        		{
        			$('.thwepo-extra-options.text_message').fadeIn();
        			$('.thwepo-extra-options.text_message_other').hide();
        			
        			$('#message_other').removeClass('validate[required]');
        			
        			if ($(this).val() == 'Happy Birthday')
        			{
        				$('.text_message #message').attr('placeholder', 'お誕生日の方のお名前');
        			}
        			if ($(this).val() == 'Happy Wedding')
        			{
        				$('.text_message #message').attr('placeholder', 'ご結婚される方のお名前');
        			}
        		}
        		else {
        			$('.thwepo-extra-options.text_message_other').fadeIn();
        			$('.thwepo-extra-options.text_message').hide();
        			
        			$('#message_other').addClass('validate[required]');
        			$('#message_other').attr('placeholder', 'オリジナルメッセージを記入してください');
        		}
        	});
        	
        	$('html').on('click', '.single_add_to_cart_button', function(e) {
        		var cartForm = $("form.cart");
        		e.preventDefault();
        		cartForm.validationEngine({promptPosition: 'inline', addFailureCssClassToField: "inputError", bindMethod:"live"});
        		var validate = cartForm.validationEngine('validate');
        		if (validate)
        		{
        			cartForm.submit();
        		}
        	});
        }
        
    });
	
	$('input.checkbox_input.labelauty').on('change', function(event){
		$(this).closest('li').find('.suboption_box').toggleClass('disable');
	});
	
	$('#macaron_color_custom').on('ifChecked', function(event) {
		$('#custom_order_macaron_color_text_wraper').show();
	});
	
	$('#macaron_color_custom').on('ifUnchecked', function(event) {
		$('#custom_order_macaron_color_text_wraper').hide();
	});
	
	$('#username').attr( 'placeholder', 'ユーザネームまたはメールアドレス' );
	$('#password').attr( 'placeholder', 'パスワード' );
	
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
		$('#deliver_postcode').trigger('change');
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

	$('form.variations_form.cart').on('show_variation', function(){
		$('#main_price').addClass('disable');
		$('#variation_price').removeClass('disable');
		$('#variation_price').html($('.woocommerce-variation-price .price').html());
	});
	
	$('form.variations_form.cart').on('hide_variation', function(){
		$('#main_price').removeClass('disable');
		$('#variation_price').addClass('disable');
	});
});


  
