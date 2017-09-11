var wcuf_current_paymenth_method = 'none';
jQuery(document).ready(function()
{
	if(wcuf_exists_at_least_one_upload_field_bounded_to_gateway)
		jQuery(document).on('click','li.wc_payment_method input.input-radio', wcuf_on_payment_method_change);
});
function wcuf_on_payment_method_change(event)
{
	var method_id = jQuery(event.target).val();
	var random = Math.floor((Math.random() * 1000000) + 999);
	wcuf_current_paymenth_method = method_id;
	var formData = new FormData();
	formData.append('action', 'reload_upload_fields_on_checkout');
	formData.append('payment_method', method_id);
	formData.append('wcuf_wpml_language', wcuf_wpml_language);
	
	//UI
	jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').animate({ opacity: 0 }, 500, function()
	{
		//UI
		jQuery('#wcuf_'+wcuf_current_page+'_ajax_container_loading_container').html("<h4>"+wcuf_ajax_reloading_fields_text+"</h4>");
		
		jQuery.ajax({
			url: wcuf_ajaxurl+"?nocache="+random,
			type: 'POST',
			data: formData,
			async: false,
			success: function (data) 
			{
				//UI
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container_loading_container').html("");  
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').html(data);
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').animate({ opacity: 1 }, 500);	
							
				//Hide add to cart in case of required field 
				wcuf_hide_add_to_cart_button_if_product_page_and_before_add();
							
			},
			error: function (data) {
				//wcuf_show_popup_alert("Error: "+data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
			
}
