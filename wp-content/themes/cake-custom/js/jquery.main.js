var order_form_data = {};
$(function(){
	var step_store_request;
	var get_size_request;
    //original field values
    var field_values = {
            //id        :  value
            'username'  : 'username',
            'password'  : 'password',
            'cpassword' : 'password',
            'firstname'  : 'first name',
            'lastname'  : 'last name',
            'email'  : 'email address'
    };


    //inputfocus
    $('input#username').inputfocus({ value: field_values['username'] });
    $('input#password').inputfocus({ value: field_values['password'] });
    $('input#cpassword').inputfocus({ value: field_values['cpassword'] }); 
    $('input#lastname').inputfocus({ value: field_values['lastname'] });
    $('input#firstname').inputfocus({ value: field_values['firstname'] });
    $('input#email').inputfocus({ value: field_values['email'] }); 



    function showItemInCart(isNextStep, currentStepActive){
		// Store value to server
		if (!currentStepActive)
		{
			var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
			var divCurrentStep = 'form#omOrder .step_wraper[data-step="'+ (currentStepActive) +'"]';
        	order_form_data = $(divCurrentStep + ' input:visible, '+ divCurrentStep +' select:visible, '+ divCurrentStep +' textarea:visible, '+ divCurrentStep +' input[type="hidden"]').serialize();
        	order_form_data += '&action=cake_steps_store&step=' + currentStepActive
		}
		
		
		if (step_store_request)
		{
			step_store_request.abort();
		}
		
		
		if (isNextStep)
		{
			$('body').LoadingOverlay("show");
		}
		
		step_store_request = $.ajax({
        	url: gl_ajaxUrl,
        	data: order_form_data, 
            method: 'POST',
            dataType: 'json',
            success: function(response){
            	$('.cake-cart-sidebar #cart_items').html('');
            	if (response.cart_html)
            	{
            		$('.cake-cart-sidebar #cart_items').append(response.cart_html);
            		$('#cart_empty_block').addClass('disable');
            		$('#cart_total_wraper').removeClass('disable');
            		if (response.shipping_fee)
            		{
            			$('#shipping_fee').removeClass('disable');
            			$('#sub_total').removeClass('disable');
            			$('#shipping_fee .text-right h6').html(response.shipping_fee);
            		}
            		else {
            			$('#shipping_fee').addClass('disable');
            			$('#sub_total').addClass('disable');
            		}
            		
            		if (response.total_tax)
            		{
            			$('#sub_total').removeClass('disable');
            			//$('#total_tax').removeClass('disable');
            			//$('#total_tax .text-right h6').html(response.total_tax);
            		}
            		else {
            			//$('#total_tax').addClass('disable');
            		}
            		
            		if (response.cart_notice)
            		{
            			$('#cart_notice').removeClass('disable');
            			$('#cart_notice').html(response.cart_notice);
            		}
            		else {
            			$('#cart_notice').addClass('disable');
            		}
            		
            		$('#sub_total .text-right h6').html(response.sub_total);
            		$('#cart_total .text-right h4').html(response.cart_total);
            		
            		if (isNextStep)
            		{
            			if (currentStepActive == 2 && response.error)
            			{
            				$('body').LoadingOverlay("hide");
            				alert(response.error);
            				return '';
            			}
            			
            			if (currentStepActive == 3)
                        {
                        	// Hide Next button
                        	$('#button_wraper').hide();
                        	$('#confirmation_wraper').removeClass('disable');
                        	$('#confirmation_content').html('<div><img src="'+ gl_templateUrl +'/images/loading-1.gif"/></div>');
                        }
            			
            			$('form#omOrder .step_wraper').slideUp();
                		$('form#omOrder .step_wraper[data-step="'+ (currentStepActive + 1) +'"]').slideDown(function(){
                			var percentComplete = currentStepActive == 3 ? 100 : (currentStepActive * 33);
                        	var widthComplete = currentStepActive * 113;
                        	
                        	$('#progress_text').html(percentComplete + '% Complete');
                            $('#progress').css('width',widthComplete + 'px');
                            
                            $('html, body').animate({
                                scrollTop: $('#four_steps').offset().top - $('.navbar-brand-cake').outerHeight() - 50
                            }, 500);
            				$('form.form-style-common .help-block').addClass('disable');
            				$('body').trigger('resize');
                		});
            		}
            		
            		
            	}
            	else {
            		$('#cart_empty_block').removeClass('disable');
            		$('#cart_total_wraper').addClass('disable');
            	}
            	
            	if (isNextStep && currentStepActive == 3)
                {
            		$('#confirmation_wraper').removeClass('disable');
            		$('#confirmation_content').html(response.confirm_html);
            		$('#confirmation_footer').removeClass('disable');
                }
            	if (isNextStep)
        		{
            		$('body').LoadingOverlay("hide");
        		}
            },
            error: function(){
            	console.log ('showItemInCart error');
            	$('body').LoadingOverlay("hide");
            }
        });
	}
    
    function initCustomOrderForm(){
    	//reset progress bar
        $('#progress').css('width','0');
        $('#progress_text').html('0% Complete');

        //first_step
    	$('#four_steps .step.first').addClass('current');
    	$('form.form-style-common .help-block').addClass('disable');
        	
        
    	$('body').on('change', 'form#omOrder input:radio, form#omOrder input:checkbox, form#omOrder select:not([name="custom_order_layer"])', function(){
    		// Dont load cart when click question input
    		if (!$(this).closest('.question-form').length)
    		{
    			var input_name = $(this).attr('name');
    			var input = $(this).get(0)
    			var aCartShowingItems = [
    					//'custom_order_cake_type',
    					'custom_order_cake_shape',
    					'custom_order_layer',
    					'custom_order_cakesize_square',
    					'custom_order_cakesize_heart',
    					'custom_order_cakesize_round',
    					'custom_order_cakeflavor',
    					'custom_order_cakecolor',
    					'custom_order_printq',
    					'custom_order_cake_decorate',
    					'custom_order_msgplate',
    					'custom_order_shipping'
    				];
    			$.each(aCartShowingItems, function(index, field_name){
    				if ($(input).is(':visible') && input_name.indexOf(field_name) != -1)
    				{
    	    				showItemInCart();
    	    				
    	    				if (input_name == 'custom_order_cake_shape')
    	    				{
    	    					// Get layer size 
    	    	    				$('select[name="custom_order_layer"]').trigger('change');
    	    				}
    				}
    			});
    			
    			
    		}
    	});
    	
    	$('input.radio_input, input.checkbox_input').on('ifChecked', function(event){
    		if ($(this).attr('name') == 'custom_order_cake_shape')
    		{
    			if (roundGroup.indexOf($(this).val()) != -1)
    			{
    				$('select[name="custom_order_cakesize_round"]').removeClass('disable');
    				$('select[name="custom_order_cakesize_square"]').addClass('disable');
    				$('select[name="custom_order_cakesize_heart"]').addClass('disable');
    				
    				$('select[name="custom_order_cakesize_round"]').addClass("validate[required]");
    				$('select[name="custom_order_cakesize_square"]').removeClass("validate[required]");
    				$('select[name="custom_order_cakesize_heart"]').removeClass("validate[required]");
    			}
    			else if (['heart'].indexOf($(this).val()) != -1)
    			{
    				$('select[name="custom_order_cakesize_heart"]').removeClass('disable');
    				$('select[name="custom_order_cakesize_round"]').addClass('disable');
    				$('select[name="custom_order_cakesize_square"]').addClass('disable');
    				
    				$('select[name="custom_order_cakesize_heart"]').addClass("validate[required]");
    				$('select[name="custom_order_cakesize_round"]').removeClass("validate[required]");
    				$('select[name="custom_order_cakesize_square"]').removeClass("validate[required]");
    			}
    			else {
    				$('select[name="custom_order_cakesize_square"]').removeClass('disable');
    				$('select[name="custom_order_cakesize_round"]').addClass('disable');
    				$('select[name="custom_order_cakesize_heart"]').addClass('disable');
    				
    				$('select[name="custom_order_cakesize_square"]').addClass("validate[required]");
    				$('select[name="custom_order_cakesize_round"]').removeClass("validate[required]");
    				$('select[name="custom_order_cakesize_heart"]').removeClass("validate[required]");
    			}
    		}
    	});
    	
    	$("input:radio[name='custom_order_cake_type']").on('ifChecked', function(event){
    		// Hide / show decoration print
    		if (['cake_type_e', 'cake_type_f'].indexOf($(this).val()) != -1)
    		{
    			$('li.custom_order_cake_decorate_print').show();
    		}
    		else {
    			$('li.custom_order_cake_decorate_print').hide();
    		}
    		
			if (['cake_type_c', 'cake_type_f'].indexOf($(this).val()) != -1)
    		{
				//$('li.cake_shape_heart').show();
    			$('li.cake_shape_heart, li.cake_shape_star, li.cake_shape_custom').show();
    		}
    		else {
				//$('li.cake_shape_heart').hide();
    			$('li.cake_shape_heart, li.cake_shape_star, li.cake_shape_custom').hide();
    		}
			
			if (['cake_type_e'].indexOf($(this).val()) != -1)
			{
				if (!$('.custom_order_cake_decorate_print input[type="checkbox"]').is(':checked'))
				{
					$('.custom_order_cake_decorate_print input[type="checkbox"]').click();
				}
			}
			else{
				if ($('.custom_order_cake_decorate_print input[type="checkbox"]').is(':checked'))
				{
					$('.custom_order_cake_decorate_print input[type="checkbox"]').click();
				}
			}
    	});
    	
    $('body').on('click', 'form#omOrder .submit_next', function(event, is_validate){
        	$('.formError.inline').remove()
        	$("form#omOrder").validationEngine({promptPosition: 'inline', addFailureCssClassToField: "inputError", bindMethod:"live"});
        	var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        	var divCurrentStep = 'form#omOrder .step_wraper[data-step="'+ (currentStepActive) +'"]';
        	order_form_data = $(divCurrentStep + ' input:visible, '+ divCurrentStep +' select:visible, '+ divCurrentStep +' textarea:visible, '+ divCurrentStep +' input[type="hidden"]').serialize();
        	order_form_data += '&action=cake_steps_store&step=' + currentStepActive
        	
        	if (is_validate == 'no')
        	{
        		validate = false;
        	}
        	else {
        		var validate = $("form#omOrder").validationEngine('validate');
        	}
        	
    		var checkecaketype = $("input:radio[name='custom_order_cake_type']").is(':checked')
    		var checkcakeshape = $("input:radio[name='custom_order_cake_shape']").is(':checked')
    		
    		if (currentStepActive >= 2)
	    	{
	    		$('.submit_prev').show();
	    	}
    		
        	if (validate && (checkecaketype || checkcakeshape))
        	{
                $('form#omOrder').validationEngine('hideAll');
                $('form#omOrder').validationEngine('detach');

                
        		// Add current class for slide step
        		$('#four_steps .step').removeClass('current');
        		$('#four_steps .step[data-step="'+ (currentStepActive) +'"]').addClass('current');

        		// Store value to server
                showItemInCart(true, currentStepActive);
        	}
        	
        });
        
        $('body').on('click', '.submit_prev', function(){
        	// Show Next button
        	$('#button_wraper').show();
        	$('#confirmation_wraper').addClass('disable');
        	$('#confirmation_content').html('');
        	$('#confirmation_footer').addClass('disable');
        	
        	var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        	var changeStep = currentStepActive - 1;
        	
        	if (changeStep == 2)
        	{
        		$('.submit_prev').hide();
        	}
    		
    		// Add current class for slide step
    		$('#four_steps .step').removeClass('current');
    		$('#four_steps .step[data-step="'+ (changeStep - 1) +'"]').addClass('current');
    		
    		$('form#omOrder .step_wraper').slideUp();
    		$('form#omOrder .step_wraper[data-step="'+ (changeStep) +'"]').slideDown(function(){
    			var percentComplete = (changeStep - 1) * 33;
    	    	var widthComplete = (changeStep - 1) * 113;
    	    	$('#progress_text').html(percentComplete + '% Complete');
    	        $('#progress').css('width',widthComplete + 'px');
    	        
    	        $('html, body').animate({
                    scrollTop: $('#four_steps').offset().top - $('.navbar-brand-cake').outerHeight() - 50
                }, 500);
    	        
    	        $('body').trigger('resize');
    		});
    		
        	
        });
        
        
        $('body').on('click', '.cake-row__remove', function(){
        	var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        	
        	var item_cart_row = $(this);
        	var step = currentStepActive;
        	var step_remove = $(this).attr('data-step');
        	var item_remove = $(this).attr('data-item-remove');
        	var child_item_remove = $(this).attr('data-item-child-remove');
        	
        	$('body').LoadingOverlay("show");
	        $.ajax({
	        	url: gl_ajaxUrl,
	        	data: {action: 'cake_steps_store', 'step': step, 'step_remove': step_remove, 'data-item-remove': item_remove, 'data-item-child-remove': child_item_remove}, 
	            method: 'POST',
	            dataType: 'json',
	            success: function(response){
	            	// Uncheck
	            	$('input[name^='+item_remove+']').each(function(){
	            		if ($(this).val() == child_item_remove) {
	            			$(this).iCheck('uncheck');
	            		}
	            	});
	            	
	            	$('.cake-cart-sidebar #cart_items').html('');
                	if (response.cart_html)
                	{
                		$('.cake-cart-sidebar #cart_items').append(response.cart_html);
                		$('#cart_empty_block').addClass('disable');
                		$('#cart_total_wraper').removeClass('disable');
                		if (response.shipping_fee)
                		{
                			$('#shipping_fee').removeClass('disable');
                			$('#shipping_fee .text-right h6').html(response.shipping_fee);
                			$('#sub_total').removeClass('disable');
                		}
                		else {
                			$('#shipping_fee').addClass('disable');
                			$('#sub_total').addClass('disable');
                		}
                		
                		if (response.total_tax)
                		{
                			$('#sub_total').removeClass('disable');
                			//$('#total_tax').removeClass('disable');
                			//$('#total_tax .text-right h6').html(response.total_tax);
                		}
                		else {
                			//$('#total_tax').addClass('disable');
                		}
                		
                		if (response.cart_notice)
                		{
                			$('#cart_notice').removeClass('disable');
                			$('#cart_notice').html(response.cart_notice);
                		}
                		else {
                			$('#cart_notice').addClass('disable');
                		}
                		
                		
                		$('#sub_total .text-right h6').html(response.sub_total);
                		$('#cart_total .text-right h4').html(response.cart_total);
                	}
                	else {
                		$('#cart_empty_block').removeClass('disable');
                		$('#cart_total_wraper').addClass('disable');
                	}
                	
                	if (currentStepActive == 4)
                    {
                		$('#confirmation_wraper').removeClass('disable');
                		$('#confirmation_content').html(response.confirm_html);
                		$('#confirmation_footer').removeClass('disable');
                    }
                	
	            	$('body').LoadingOverlay("hide");
	            },
	            error: function(){
	            	$('body').LoadingOverlay("hide");
	            }
	        });
        });
        
        $('body').on('click', '.skip-authenticate-btn', function(){
        		is_loggedin = true;
        		$('#submit_form_order').trigger('click');
        });
        
        $('body').on('click', '#submit_form_order', function(){
	        	$('#submit_form_order').hide();
	        	$('body').LoadingOverlay("show");
	        	
	        	if (is_loggedin)
	        	{
	        		$.ajax({
	                	url: gl_ajaxUrl,
	                	data: $('#confirmation_form').serialize(), 
	                    method: 'POST',
	                    dataType: 'json',
	                    success: function(response){
	                    	$('body').LoadingOverlay("hide");
	                    	if (response.error)
	                    	{
	                    		alert (response.message);
	                    	}
	                    	else {
	                    		// Redirect to thank you page
	                    		location.href = response.redirect;
	                    	}
	                    	$('#submit_form_order').show();
	                    },
	                    error: function(){
	                    	$('body').LoadingOverlay("hide");
	                    	$('#submit_form_order').show();
	                    }
	                });
	        	}
	        	else {
	        		showLoginPopup('register');
	        	}
        });
        
        $('#custom_order_login_modal').on('show.bs.modal', function (e) {
        	if ($('#customer_email').length)
        	{
        		$('#lwa_user_login').val($('#customer_email').val());
        		$('#user_email').val($('#customer_email').val());
        	}
        });
        
        $('#custom_order_login_modal').on('hidden.bs.modal', function (e) {
        	$('#submit_form_order').show();
        });
        
        $('body').on('click', '.lwa-links-modal', function(){
        	$('.modal').modal('hide');
        });
        
        $('body').on('click', '.step_wraper a.showlogin', function(e){
        	e.preventDefault();
        	showLoginPopup();
        });
        
        $('body').on('click', '.lwa-links-login', function(e){
        	e.preventDefault();
        	$(".lwa-status").trigger("reveal:close");
        	setTimeout(function(){
        		showLoginPopup();
        	}, 300);
        });
        
        
        function showLoginPopup(popup){
        	$('body').LoadingOverlay("hide");
        	if (popup == 'register')
        	{
        		$('.lwa-links-modal').click();
        	}
        	else {
        		$('#custom_order_login_modal').modal({
        			backdrop: 'static',
        		    keyboard: false
        		});
        	}
        }
        
        function pullFieldData(field_data){
        	$.each(field_data, function(field_name, field_value){
    			if ($('[name="'+field_name+'"]').is(':radio') || $('[name="'+field_name+'"]').is(':checkbox'))
    			{
    				if ($('[name="'+field_name+'"][value="'+field_value+'"]').next().is('ins'))
    				{
    					$('[name="'+field_name+'"][value="'+field_value+'"]').next().click();
    				}
    				else {
    					$('[name="'+field_name+'"][value="'+field_value+'"]').prop('checked').change();
    				}
    			}
    			else if ($('[name="'+field_name+'"]').is('select'))
    			{
    				$('[name="'+field_name+'"]').val(field_value);
    				if ($('[name="'+field_name+'"]').is(':visible'))
    				{
    					$('[name="'+field_name+'"]').change();
    				}
    			}
    			else {
    				$('[name="'+field_name+'"]').val(field_value);
    			}
    		});
        }
        function actionLoginRegister(e, i, n){
        	if (i.result && $('form#omOrder').length)
        	{
        		var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        		
        		is_loggedin = true;
            	$('.modal').modal('hide');
            	$(".lwa-status").trigger("reveal:close");
            	
            	if (currentStepActive == 3)
            	{
            		// Login/register in step 3
            		pullFieldData(i.user_info);
            		
            		if ($('[name="custom_order_deliver_pref"]').is(':visible'))
            		{
            			$('[name="custom_order_deliver_pref"]').trigger('change');
            		}
            		
            		$('#returning_customer_wraper').hide();
            		$('.get-from-savedata').removeClass('disable');
            	}
            	else if (currentStepActive == 4)
            	{
            		$('#submit_form_order').click();
            	}
        	}
        }
        
        $(document).on("lwa_register", function(e, i, n) {
        	actionLoginRegister(e, i, n);
        });
        
        $(document).on("lwa_login", function(e, i, n) {
        	actionLoginRegister(e, i, n);
        });
        
        $('#user_saved_address').on('ifChecked', function(event){
        	$('body').LoadingOverlay("show");
        	$.ajax({
            	url: gl_ajaxUrl,
            	data: {action: 'get_user_address_data'}, 
                method: 'POST',
                dataType: 'json',
                success: function(response){
                	if (response.user_address)
                	{
                		pullFieldData(response.user_address);
                	}
                	$('body').LoadingOverlay("hide");
                },
                error: function(){
                	$('body').LoadingOverlay("hide");
                }
            });
        	
    	});

        
        
        var picWraper = '';
        var picHiddenName = '';
        var picElement = '';
    	function showUploadResponse(response, statusText, xhr, $form){

    		response = $.parseJSON(response);
    		picElement.replaceWith('<input type="file" class="filestyle upload_cakePic" name="upload_cakePic" id="'+ picElement.attr('id') +'" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">')
    		if (picHiddenName == 'custom_order_photocakepic')
    		{
    			picWraper.find(".inspired_images").html('');
    		}

    	    if(response.error){
    	    	picWraper.find(".image_loading").html(response.message);

    		}else{
    			// remove loading when done
    			picWraper.find(".image_loading").html('');
    			
    			var new_image = '<img alt="" src="'+ (response.file_src) +'?t='+ (new Date().getTime()) +'"   class="cake_upload_preview" />';
    			new_image += '<input type="hidden" class="filestyle" class="custom_order_cakePic" name="'+picHiddenName+'" value="'+response.file_name+'">';
    			picWraper.find(".inspired_images").append('<li>'+new_image+'<span class="glyphicon glyphicon-remove remove-image" ></span></li>');
    			picWraper.find('.custom_order_cakePic').val(response.file_name);    	    	
    		}
        }
    	
    	$('body').on('change', '.upload_cakePic', function() {
    		picWraper = $(this).closest('.upload_cakePic_wraper');
    		picHiddenName = $(this).attr('id');
    		picElement = $(this);
    		
    		$('form#omOrder').validationEngine('hideAll');
        $('form#omOrder').validationEngine('detach');
            
    		picWraper.find(".image_loading").html('<img class="loading-image" style="width: auto !important" src="'+ gl_templateUrl +'/images/loading.gif" />');
            $("form#omOrder").ajaxForm({
            	url: gl_ajaxUrl,
            	data: {action: 'cake_file_upload', fileName: picElement.attr('id')}, 
                type: 'POST',
                contentType: 'text',
                success:    showUploadResponse 
            }).submit();
        });
    	
    	$('body').on('click', 'span.remove-image', function(){
    		$(this).fadeOut(function(){$(this).closest('li').remove()});
    	});
    	 
    	
    	// Init color Picker
    	jQuery(".cp-select").colorPicker({
    		colors: gl_templateUrl + '/js/colorpicker/colors.json',
    		rowitem: 10,
    		onSelect: function( ui, c ){
    			if ($(ui).attr('id') == 'custom_order_color_picker')
    			{
    				$(ui).closest('#ColorOptionbox').find('#custom_order_cakecolor_other').val(c);
    				showItemInCart();
    			}
    			jQuery('.selected-color').css('background-color', c);		
    		}
    	});
    	
    	// Init step 1
    	var checkedType = jQuery('input[name="custom_order_cake_type"]:checked').val();
    	if (checkedType)
    	{
    		$('input.submit_next').trigger('click', ['no']);
    		jQuery('input[name="custom_order_cake_type"]:checked').iCheck('check');
    	}
    	
    	// trigger shape at initial
    	$('input[name="custom_order_cake_shape"]').each(function(){
    		if ($(this).is(':checked'))
    		{
    			$(this).closest('li').find('label').trigger('click');
    		}
    	});
    	
    	$('input[type="checkbox"]:checked, input[type="radio"]:checked').each(function(){
    		$(this).trigger('change');
    	});
    	
    	$('body').on('change', 'select[name="custom_order_layer"]', function(){
    		var layer = $(this).val();
    		var notice_layer = $('.cake-size.select-wrapper').find('.text-notice-layer');
    		notice_layer.addClass('disable');
    		if (layer == 2 || layer == 3)
    		{
    			notice_layer.removeClass('disable');
    			notice_layer.text(/*layer + */'1段目のサイズを選択してください');
    		}
    		if (get_size_request)
    		{
    			get_size_request.abort();
    		}
    		get_size_request = $.ajax({
 	           url: gl_ajaxUrl,
 	           data: {  
 	        	   	action: 'get_layer_cake_size', 
 	        	   	layer: layer, 
 	        	   	shape: $('input[name="custom_order_cake_shape"]:checked').val(),
 	        	   	szSizeRound : $('select[name="custom_order_cakesize_round"]').val(), 
 	        	   	szSizeHeart : $('select[name="custom_order_cakesize_heart"]').val(), 
 	        	   	szSizeSquare : $('select[name="custom_order_cakesize_square"]').val()}, 
 	           method: 'POST',
 	           dataType: 'json',
 	           success: function(response){
	 	        	   if (response.custom_order_cakesize_round)
	 	        		{
	 	        		   $('select[name="custom_order_cakesize_round"]').html(response.custom_order_cakesize_round);
	 	        		}
	 	        	  if (response.custom_order_cakesize_heart)
		        		{
		        		   $('select[name="custom_order_cakesize_heart"]').html(response.custom_order_cakesize_heart);
		        		}
	 	        	   if (response.custom_order_cakesize_square)
		        		{
		        		   $('select[name="custom_order_cakesize_square"]').html(response.custom_order_cakesize_square);
		        		}
	 	        	   
	 	        	   if (layer == '特注段数')
	 	        	   {
	 	        		 $('select[name="custom_order_cakesize_round"] option:last').prop('selected', true)
	 	        		 $('select[name="custom_order_cakesize_square"] option:last').prop('selected', true)
	 	        		 $('select[name="custom_order_cakesize_heart"] option:last').prop('selected', true)
	 	        	   }
	 	        	   else {
	 	        		  $('select[name="custom_order_cakesize_round"] option:first').prop('selected', true)
		 	        	  $('select[name="custom_order_cakesize_square"] option:first').prop('selected', true)
		 	          $('select[name="custom_order_cakesize_heart"] option:first').prop('selected', true)
	 	        	   }
	 	        	  $('select[name="custom_order_cakesize_round"]:visible').trigger('change');
	 	        	  $('select[name="custom_order_cakesize_square"]:visible').trigger('change');
	 	        	  $('select[name="custom_order_cakesize_heart"]:visible').trigger('change');
	 	        	   
 	           },
 	           error: function(response){
 	        	   $('body').LoadingOverlay("hide");
 	           }
            });
    	});
    	
    }
   if ($("form#omOrder").length)
   {
	   initCustomOrderForm();
   }
   
   $('input.radio_input, input.checkbox_input').on('ifChecked', function(event){
	   	var suboption = $(this).closest('li').find('.suboption_box');
	   	suboption.toggleClass('disable');
	   	if (suboption.find('textarea').length)
	   	{
	   		if (suboption.find('textarea').attr('required'))
			{
				suboption.find('textarea').removeAttr('required');
			}
			else {
				suboption.find('textarea').attr('required', 'required');
			}
	   	}
	});
   
   $('input.radio_input, input.checkbox_input').on('ifUnchecked', function(event){
	   var suboption = $(this).closest('li').find('.suboption_box');
	   	suboption.toggleClass('disable');
	   	if (suboption.find('textarea').length)
	   	{
	   		if (suboption.find('textarea').attr('required'))
			{
				suboption.find('textarea').removeAttr('required');
			}
			else {
				suboption.find('textarea').attr('required', 'required');
			}
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
	                			$(addressItem['state']).val($(this).attr('value'));
	                			// Change state if not custom order page
	                			if ($('form#omOrder #cake_shape_square').length == 0)
	                	    	{
	                				$(addressItem['state']).change();
	                	    	}
	                		}
	                	});
	                	
	                    $(addressItem['city']).val(data[1]);
//	                    var address1 = $(addressItem['address1']).val();
	                    var address1 = '';
	                    address1 = address1.replace(data[2], '');
	                    $(addressItem['address1']).val(data[2] + address1);
	        		}
	        	});
	        }
	    	
	    	if ($('form#omOrder #cake_shape_square').length)
	    	{
	    		showItemInCart();
	    	}
	    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
	    });
	});
   
   //Trigger default calendar today
   	if ($('div.calendar').length)
	{
	   setTimeout(function(){
			$('.pignose-calendar-unit-active').trigger('click');
		}, 300)
	}
	
   
   if ($('#contact_form_submit_button').length)
   {
	   $('body').on('click', '#contact_form_submit_button', function(e){
		   e.preventDefault();
		   var contact_form = $(this).closest('form');
		   contact_form.find('#form_action').val('wcp_contact_form_submit');
		   
		   $('body').LoadingOverlay("show");
		   $.ajax({
	           url: gl_ajaxUrl,
	           data: contact_form.serialize(), 
	           method: 'POST',
	           dataType: 'json',
	           success: function(response){
	        	   if (response.html)
	        	   {
	        		   $('#notification_wraper').html(response.html);
	        	   }
	        	   
	        	   if (response.error)
	        	   {
	        		   $('#notification_wraper').addClass('form-error');
	        	   }
	        	   else {
	        		   contact_form.find("input.wpcf7-text, textarea").val("");
	        	   }
	        	   
	        	   $('html, body').animate({
                       scrollTop: $('#notification_wraper').offset().top - $('.navbar-brand-cake').outerHeight() - 50
                   }, 500);
	        	   
	        	   $('body').LoadingOverlay("hide");
	        	   grecaptcha.reset();
	           },
	           error: function(response){
	        	   $('body').LoadingOverlay("hide");
	        	   grecaptcha.reset();
	           }
           });
	   })
   }
   
   if ($('#q07_f').length)
   {
 	  $(document).on('change', '.survey_particular', function(){
 		  if ($(this).attr('id') == 'q07_f')
 		  {
 			  $('#survey_particular_comment').show();
 		  }
 		  else {
 			  $('#survey_particular_comment').hide();
 		  }
 	  });
 	  
 	 $(document).on('change', 'input[name="survey[engine]"]', function(){
 		  if ($(this).attr('id') == 'q01_a')
		  {
			  $('#engine_sns').show();
		  }
		  else {
			  $('#engine_sns').hide();
		  }
		  
 		  if ($(this).attr('id') == 'q01_c')
		  {
			  $('#engine_other').show();
		  }
		  else {
			  $('#engine_other').hide();
		  }
	  });
 	 
 	$(document).on('change', '#engine_sns', function(){
		  if ($(this).find('option:last').is(':checked'))
		  {
			  $('#engine_sns_comment').show();
		  }
		  else {
			  $('#engine_sns_comment').hide();
		  }
	  })
   }
});