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
});
