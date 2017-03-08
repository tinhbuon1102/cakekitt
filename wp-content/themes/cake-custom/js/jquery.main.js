var order_form_data = {};
$(function(){
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



    function initCustomOrderForm(){
    	//reset progress bar
        $('#progress').css('width','0');
        $('#progress_text').html('0% Complete');

        //first_step
    	$('#four_steps .step.first').addClass('current');
    	$('form.form-style-common .help-block').addClass('disable');
        	
        
        $('body').on('click', 'form#omOrder .submit_next', function(){
        	$("form#omOrder").validationEngine({promptPosition: 'inline', addFailureCssClassToField: "inputError", bindMethod:"live"});
        	
        	var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        	var divCurrentStep = 'form#omOrder .step_wraper[data-step="'+ (currentStepActive) +'"]';
        	order_form_data = $(divCurrentStep + ' input:visible, '+ divCurrentStep +' select:visible, '+ divCurrentStep +' textarea:visible, '+ divCurrentStep +' input[type="hidden"]').serialize();
        	order_form_data += '&action=cake_steps_store&step=' + currentStepActive
        	
        	var validate = $("form#omOrder").validationEngine('validate');
    		var checkecaketype = $("input:radio[name='custom_order_cake_type']").is(':checked')
    		var checkcakeshape = $("input:radio[name='custom_order_cake_shape']").is(':checked')
    		
        	if (validate && (checkecaketype || checkcakeshape))
        	{
                $('form#omOrder').validationEngine('hideAll');
                $('form#omOrder').validationEngine('detach');

                
        		// Add current class for slide step
        		$('#four_steps .step').removeClass('current');
        		$('#four_steps .step[data-step="'+ (currentStepActive + 1) +'"]').addClass('current');
        		
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
                    
                    
                    if (currentStepActive == 3)
                    {
                    	// Hide Next button
                    	$('form#omOrder .submit_next').hide();
                    	$('form#confirmation_content').html('<div><img src="'+ gl_templateUrl +'/images/loading-1.gif"/></div>');
                    }	
                    
                    // Store value to server
                    $.ajax({
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
                        		$('#cart_total').removeClass('disable');
                        		
                        	}
                        	else {
                        		$('#cart_empty_block').removeClass('disable');
                        		$('#cart_total').addClass('disable');
                        	}
                        	
                        	if (currentStepActive == 3)
                            {
                        		$('form#confirmation_content').html(response.confirm_html);
                            }
                        }
                    });
        		});
        		
        	}
        	
        });
        
        $('body').on('click', 'form#omOrder .submit_prev', function(){
        	// Show Next button
        	$('form#omOrder .submit_next').show();
        	$('form#confirmation_content').html('');
        	
        	var currentStepActive = $('form#omOrder .step_wraper:visible').data('step');
        	var changeStep = currentStepActive - 1;
    		
    		// Add current class for slide step
    		$('#four_steps .step').removeClass('current');
    		$('#four_steps .step[data-step="'+ (changeStep) +'"]').addClass('current');
    		
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
        
        
    	function showUploadResponse(response, statusText, xhr, $form){

    		response = $.parseJSON(response);

    	    if(response.error){
    	    	$('#viewimage').html(response.message);

    		}else{
    			$('#viewimage').html('<img alt="" src="'+ (response.file_src) +'?t='+ (new Date().getTime()) +'"   id="cake_upload_preview" />');
    	    	$('#custom_order_cakePic').val(response.file_name);    	    	
    		}
        }
    	
    	$('body').on('change', '#upload_cakePic', function() {
            $("#omOrder #viewimage").html('<img class="loading-image" style="width: auto !important" src="'+ gl_templateUrl +'/images/loading.gif" />');
            $("form#omOrder").ajaxForm({
            	url: gl_ajaxUrl,
            	data: {action: 'cake_file_upload'}, 
                type: 'POST',
                contentType: 'text',
                success:    showUploadResponse 
            }).submit();
        });
    	
    	// Init step 1
    	var checkedType = jQuery('input[name="custom_order_cake_type"]:checked').val();
    	if (checkedType)
    	{
    		$('input.submit_next').trigger('click');
    	}
    }
   if ($("form#omOrder").length)
   {
	   initCustomOrderForm();
   }
});