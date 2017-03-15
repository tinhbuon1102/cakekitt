jQuery(document).ready(function(){
	//Show Options
	
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
            value = value < 12 ? (value + ' AM') : (value + ' PM');
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
	
	$('input.radio_input, input.checkbox_input').on('ifChecked', function(event){
		$(this).closest('li').find('.suboption_box').toggleClass('disable');
		if ($(this).attr('name') == 'custom_order_cake_shape')
		{
			if (roundGroup.indexOf($(this).val()) != -1)
			{
				$('select[name="custom_order_cakesize_round"]').removeClass('disable');
				$('select[name="custom_order_cakesize_square"]').addClass('disable');
			}
			else {
				$('select[name="custom_order_cakesize_round"]').addClass('disable');
				$('select[name="custom_order_cakesize_square"]').removeClass('disable');
			}
		}
	});
	
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
	
  $('input:not(.labelauty)').iCheck({
    checkboxClass: 'icheckbox_square-pink',
    radioClass: 'iradio_square-pink',
    increaseArea: '20%' 
  });

  $("input.labelauty").labelauty();
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
});
	jQuery(".panel-group a#delivery").click(function(){
		jQuery('.deliver-info').removeClass('disable');
});
	jQuery(".panel-group a#pickup").click(function(){
		jQuery('.deliver-info').addClass('disable');
});

jQuery(".ordercake-cart-sidebar-container").pinBox({
		//default 0px
		Top : '90px',
		//default '.container' 
		Container : '#pinBoxContainer',
		//default 20 
		ZIndex : 20,
		//default '767px' if you disable pinBox in mobile or tablet
		MinWidth : '767px'
		//events if scrolled or window resized 
	});
	
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
var sideWidth = $('.ordercake-cart-sidebar-container').width();
$('.cake-cart-sidebar').css('width', sideWidth + 'px');

var divWidth = $('.round-icon-select #fixwh-inner').width();
$('.round-icon-select #fixwh-inner').css('height', divWidth + 'px');
/*$('.round-icon-select #fixwh-inner').height(divWidth);*/
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
		$('.navbar-fixed-top').addClass('open');
    } else {
		$(this).removeClass('linericon-cross');
        $(this).addClass('linericon-menu');
		$('.navbar-fixed-top').removeClass('open');
      }
});

$('body').on('keyup change', '#deliver_postcode', function(){
	var zip1 = $.trim($(this).val());
    var zipcode = zip1;

    $.ajax({
        type: "post",
        url: gl_siteUrl + "/dataAddress/api.php",
        data: JSON.stringify(zipcode),
        crossDomain: false,
        dataType : "jsonp",
        scriptCharset: 'utf-8'
    }).done(function(data){
        if(data[0] == ""){
        } else {
        	$('#deliver_state option').each(function(){
        		if($(this).text() == data[0])
        		{
        			$('#deliver_state').val($(this).attr('value')).change();
        		}
        	});
        	
//            $('#deliver_state').val(data[0]).change();
            $('#deliver_city').val(data[1]);
//            $('#deliver_addr1').val(data[2]);
            var address1 = $('#deliver_addr1').val();
            address1 = address1.replace(data[2], '');
            $('#deliver_addr1').val(data[2] + address1);
        }
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
    });
});

});


  
