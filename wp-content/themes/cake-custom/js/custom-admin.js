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
});
