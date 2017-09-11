function wcuf_ui_delete_file()
{
	jQuery('.single_add_to_cart_button, .quantity').fadeOut(200);
	jQuery("#wcuf_file_uploads_container").fadeOut(400);
	jQuery("#wcuf_deleting_message").delay(500).fadeIn(400,function()
	{
		//Smooth scroll
		try{
			jQuery('html, body').animate({
				  scrollTop: jQuery('#wcuf_deleting_message').offset().top - 200 //#wcmca_address_form_container ?
				}, 500);
		}catch(error){}
	});
}
function wcuf_ui_delete_file_on_order_details_page()
{
	jQuery("#wcuf_file_uploads_container").fadeOut(400);
	jQuery("#wcuf_deleting_message").delay(500).fadeIn(400,function()
	{
		//Smooth scroll
		try{
			jQuery('html, body').animate({
				  scrollTop: jQuery('#wcuf_deleting_message').offset().top - 200 //#wcmca_address_form_container ?
				}, 500);
		}catch(error){}
	});
}
function wcuf_show_popup_alert(text)
{
	jQuery('#wcuf_alert_popup_content').html(text);
	jQuery('#wcuf_show_popup_button').trigger('click');
}

function wcuf_ui_after_delete()
{  
	//if(wcuf_current_page == "product" || wcuf_current_page == "checkout")
	if(wcuf_current_page != "cart" && wcuf_current_page != "order_details")
	{
		setTimeout(function(){wcuf_ajax_reload_upload_fields_container() }, 1500); 
		//return false;
	}
	 else
		wcuf_reload_page(500);    
}
function wcuf_reload_page(time)
{
	wcuf_is_force_reloading = true;
	setTimeout(function(){ window.location.reload(true);   /* window.location.href = window.location.href + '?upd=' + Math.floor((Math.random() * 100000000) + 135775544) */  ;  }, time); 
}