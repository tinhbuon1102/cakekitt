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
	jQuery('.cake_shape_round i').addClass('iconkitt-kitt_icons_cake-size');
	jQuery('.cake_shape_square i').addClass('iconkitt-kitt_icons_shape-square');
	jQuery('.cake_shape_heart i').addClass('iconkitt-kitt_icons_shape-heart');
	jQuery('.cake_shape_star i').addClass('iconkitt-kitt_icons_shape-star');
	jQuery('.cake_shape_other i').addClass('iconkitt-kitt_icons_shape-custom');
	//flavor icons
	jQuery('.cake_flavor_short i').addClass('iconkitt-kitt_icons_shortcake');
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


});


  
