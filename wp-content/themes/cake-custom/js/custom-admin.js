jQuery(document).ready(function($){
	if ($('#acf-field-cake_shape').length)
	{
		$('body').on('change', '#acf-field-cake_shape', function(){
			if ($(this).val() == '丸型')
			{
				$('#acf-custom_order_cakesize_round').show();
				$('#acf-custom_order_cakesize_square').hide();
			}
			else {
				$('#acf-custom_order_cakesize_square').show();
				$('#acf-custom_order_cakesize_round').hide();
			}
		});
		
		$('#acf-field-cake_shape').trigger('change');
	}
	
	if ($('#custom_order_cake_shape').length)
	{
		$('body').on('change', '#custom_order_cake_shape', function(){
			var shapeElement = $(this);
			var formData = $(this).closest('form').serialize();
			formData += '&action=get_size_cake_shape_price';
			$.ajax({
            	url: gl_ajaxUrl,
            	data: formData, 
                method: 'POST',
                dataType: 'html',
                success: function(response){
        			if (roundGroup.indexOf(shapeElement.val()) != -1)
        			{
        				$('#custom_order_cakesize_square').attr('disabled', true);
        				$('#custom_order_cakesize_round').attr('disabled', false);
        				$('#custom_order_cakesize_round_wraper').removeClass('disable');
        				$('#custom_order_cakesize_square_wraper').addClass('disable');
        			}
        			else {
        				$('#custom_order_cakesize_square').attr('disabled', false);
        				$('#custom_order_cakesize_round').attr('disabled', true);
        				$('#custom_order_cakesize_square_wraper').removeClass('disable');
        				$('#custom_order_cakesize_round_wraper').addClass('disable');
        			}

                	$('#custom_order_cakesize_square').html(response);
                	$('#custom_order_cakesize_round').html(response);
                }
            });
			
		});
	}
});
