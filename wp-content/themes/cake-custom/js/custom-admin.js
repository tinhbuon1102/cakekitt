jQuery(document).ready(function($){

    var cakeShape = $('#acf-field-custom_order_cake_shape');
    if (cakeShape.length){
        $('body').on('change', cakeShape, function(){
        	var squareSize = $('#acf-custom_order_cakesize_square'),
				roundSize = $('#acf-custom_order_cakesize_round');

            if ($(cakeShape).val() == 'square') {
                roundSize.hide();
                squareSize.show();
            } else {
                squareSize.hide();
                roundSize.show();
            }
        });
        cakeShape.trigger('change');
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
	
	if ($('.add_more_pic').length)
	{
		$('body').on('click', '.add_more_pic', function(e){
			e.preventDefault();
			var tableRow = $(this).closest('tr');
			var cloneUploader = tableRow.find('.button_upload_pic_tmp_wraper .acf-image-uploader').clone();
			cloneUploader.insertBefore('.add_more_pic');
			cloneUploader.find('.add-image').trigger('click');
		})
	}
	
	if ($('#order_data').length && $('.edit_address:visible').length)
	{
		setTimeout(function(){
			$('.edit_address:visible').trigger('click');
		}, 500);
	}
	
	if ($('input.line_subtotal_tax.wc_input_price').length)
	{
		$('input.line_subtotal_tax.wc_input_price').attr('readonly', true);
		$('input.line_tax.wc_input_price').attr('readonly', true);
		//$('#order_shipping_line_items input.line_total.wc_input_price').attr('readonly', true);
		
		
	}
	
	if ($('#off_duty_date_from').length)
	{
		$('#off_duty_date_from').datepicker({dateFormat: 'yy-mm-dd' });
		$('#off_duty_date_to').datepicker({dateFormat: 'yy-mm-dd' });
		$('#off_duty_date_from').attr('autocomplete', 'off');
		$('#off_duty_date_to').attr('autocomplete', 'off');
	}
});
