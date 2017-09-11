jQuery(document).ready(function()
{ 
	wcuf_init();
	jQuery(document).on('click', '.wcuf_collapse_options', wcuf_onCollassableButtonClick);
	jQuery(document).on('click','.wcuf_display_on_product_checkbox', wcuf_onDisplayOnProductCheckboxClick);
	
	try
	{
		if(jQuery(".wcuf_sortable").length > 0)
			 jQuery(".wcuf_sortable").sortable({
				containment: "parent",
				handle: ".wcuf_sort_button",
				 cancel: '',
				placeholder: "ui-state-highlight",
				//update: wcuf_uploaded_field_sorted,
				//start: wcuf_on_start_field_sorting
			});
	}catch(error){jQuery('.wcuf_sort_button').hide();}
	wcuf_checkMultipleUpoloadsCheckbox();	
});
function wcuf_init()
{
	//var max_fields      = 999999; //maximum input boxes allowed
	var wrapper         = jQuery(".input_fields_wrap"); //Fields wrapper
	var add_button      = jQuery(".add_field_button"); //Add button ID
	var x = parseInt(wcuf.last_id); //initlal text box count
	var confirm_delete_message = wcuf.confirm_delete_message;
	
	//jQuery(".js-multiple").select2({'width':300});
	jQuery(document).on('change', ".upload_type", setSelectBoxVisibility);
	jQuery(".upload_type").trigger('change');
	
	
	function setSelectBoxVisibility(event)
	{
		if(jQuery(event.target).val() != 'always')
		   {
			   jQuery("#upload_categories_box"+jQuery(event.target).data('id')).show();
		   }
		   else
			  jQuery("#upload_categories_box"+jQuery(event.target).data('id')).hide();
	}

	jQuery(add_button).click(function(e)
	{ 
		//on add input button click
		e.preventDefault();
		e.stopImmediatePropagation();
		//if(x < max_fields)
		{
			//x++; 
			wcuf_getHtmlTemplate(x);
			wcuf_activate_new_category_select_box("#upload_type_id"+x);
			wcuf_activate_new_product_select_box("#product_select_box"+x);
		}
		//jQuery(".js-multiple").select2({'width':300});
		jQuery(".upload_type").on('change', setSelectBoxVisibility);
		jQuery('.wcuf_collapse_options').on('click', wcuf_onCollassableButtonClick);
		wcuf_checkMultipleUpoloadsCheckbox();
		return false;
	});
   
	jQuery(wrapper).on("click",".remove_field", function(e)
	{ //user click on remove text
		e.preventDefault();
		
		if(!confirm(confirm_delete_message))
			return false;
		
		var id = jQuery(e.currentTarget).data('id');
		
		//smooth scroll
		 jQuery('html, body').animate({
			//scrollTop: jQuery(this).parent().parent('.input_box').offset().top-100
			scrollTop: jQuery("#input_box_"+id).offset().top-100
		}, 500);

		//jQuery(this).parent().parent('.input_box').delay(500).fadeOut(500, function()
		jQuery("#input_box_"+id).delay(500).fadeOut(500, function()
		{
			jQuery(this).remove(); 
			//x--; //??
		}); 
		
	});
}
function wcuf_on_start_field_sorting( event, ui ) 
{
	/* console.log(jQuery(event.target));
	console.log(ui);
	if(!jQuery(event.currentTarget).hasClass('dashicons-sort'))
	{
		event.preventDefault();
		event.stopImmediatePropagation();
		return false;
	} */
}
function wcuf_uploaded_field_sorted( event, ui ) 
{
	//console.log(event.currentTarget);
	/*jQuery(".input_box").each(function(index, element)
	{
		var id = jQuery(this).find('.wcup_file_meta_id').val();
		jQuery(this).find('.wcup_file_meta_sort_order').val(index);
		//console.log( id+", new index: "+index );
	});*/
}
function wcuf_onCollassableButtonClick(event)
{
	//console.log(jQuery(event.currentTarget).data('id'));
	event.preventDefault();
	event.stopImmediatePropagation();
	var id = jQuery(event.currentTarget).data('id');
	jQuery('#wcuf_collapsable_box_'+id).toggleClass('wcuf_box_hidden');
	
	return false;
}
function wcuf_checkMultipleUpoloadsCheckbox()
{
	jQuery('.wcuf_display_on_product_checkbox').each(function(index,value)
	{
		var id = jQuery(this).data('id');
		wcuf_setMultipleUploadCheckboux(id, this);
	});
}
function wcuf_onDisplayOnProductCheckboxClick(event)
{
	var id = jQuery(event.target).data('id');
	//console.log(jQuery(event.target));
	//wcuf_setMultipleUploadCheckboux(id, event.target);
	wcuf_checkMultipleUpoloadsCheckbox();
	
}
function wcuf_setMultipleUploadCheckboux(id, elem)
{
	if(jQuery(elem).prop('checked'))
	{
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).prop('checked',true);
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).attr('disabled',true);
		
		//jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).prop('checked',true);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).removeAttr('disabled');
		jQuery('#wcuf_display_on_product_before_adding_to_cart_container_'+id).fadeIn();
	}
	else
	{
		//jQuery('#wcuf_multiple_uploads_checkbox_'+id).prop('checked',false);
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).removeAttr('disabled');
		
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).attr('disabled',true);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).attr('checked',false);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_container_'+id).fadeOut();
	}
}
function wcuf_beforeLoadingTemplate()
{
	jQuery('.add_field_button').hide(0);
	jQuery('.wcuf_preloader_image').show();
}
function wcuf_afterLoadingTemplate()
{
	jQuery('.wcuf_preloader_image').hide(0);
	jQuery('.add_field_button').show();
}
function wcuf_getHtmlTemplate(index)
{
	var random = Math.floor((Math.random() * 1000000) + 999);
	var formData = new FormData();
	formData.append('action', 'wcuf_get_upload_field_configurator_template'); 
	formData.append('start_index', index); 

	//UI
	wcuf_beforeLoadingTemplate();
	
	jQuery.ajax({
		url: ajaxurl+"?nocache="+random,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			//UI	
			wcuf_afterLoadingTemplate();
			jQuery(".input_fields_wrap").append(data);	

			wcuf_activate_new_category_select_box(".js-data-product-categories-ajax");
			wcuf_activate_new_product_select_box(".js-data-products-ajax");
		},
		error: function (data) 
		{
			//console.log(data);
			//alert("Error: "+data);
		},
		cache: false,
		contentType: false,
		processData: false
	});	
}