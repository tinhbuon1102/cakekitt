jQuery(document).ready(function()
{
	/* jQuery('#wcmca_persontype_field').change(wcmca_check_which_wcbcf_fields_to_hide);
	jQuery('#wcmca_persontype_field').trigger('change'); */
});
/* function wcmca_check_which_wcbcf_fields_to_hide(event)
{
	var value = jQuery(event.target.value);
	if(event.target.value == 1) //Individuals
	{
		jQuery('#wcmca_cnpj_field').hide();
		jQuery('#wcmca_cnpj_field').siblings().hide();
		jQuery('#wcmca_company_field').hide();
		jQuery('#wcmca_company_field').siblings().hide();
		jQuery('#wcmca_ie_field').hide();
		jQuery('#wcmca_ie_field').siblings().hide();
		jQuery('#wcmca_rg_field').show();
		jQuery('#wcmca_rg_field').siblings().show();
		jQuery('#wcmca_cpf_field').show();
		jQuery('#wcmca_cpf_field').siblings().show();
	}
	else
	{
		jQuery('#wcmca_cnpj_field').show();
		jQuery('#wcmca_cnpj_field').siblings().show();
		jQuery('#wcmca_company_field').show();
		jQuery('#wcmca_company_field').siblings().show();
		jQuery('#wcmca_ie_field').show();
		jQuery('#wcmca_ie_field').siblings().show();
		jQuery('#wcmca_cpf_field').hide();
		jQuery('#wcmca_cpf_field').siblings().hide();
		jQuery('#wcmca_rg_field').hide();
		jQuery('#wcmca_rg_field').siblings().hide();
	}
} */
function wcmca_remove_state_field(type)
{
	jQuery('#wcmca_country_field_container_'+type).empty();
}
function wcmca_start_loading_state_field(type)
{
	jQuery('#wcmca_country_field_container_'+type).fadeOut(200, function(){jQuery('#wcmca_country_field_container_'+type).empty(); jQuery('.wcmca_preloader_image').fadeIn(); });
	jQuery('#wcmca_save_address_button_'+type).fadeOut();
}
function wcmca_end_loading_state_field(type)
{
	jQuery('.wcmca_preloader_image, #wcmca_save_address_button_'+type).stop();
	jQuery('.wcmca_preloader_image').fadeOut(200, function(){ jQuery('#wcmca_country_field_container_'+type).fadeIn(); });
	jQuery('#wcmca_save_address_button_'+type).fadeIn();
}
function wcmca_show_saving_loader(type)
{
	jQuery('.wcmca_saving_loader_image, .wcmca_loader_image').fadeIn();
	jQuery('.wcmca_add_new_address_button, #wcmca_add_new_address_button_billing, #wcmca_add_new_address_button_shipping').prop('disabled', true);
	jQuery('.wcmca_edit_address_button, .class_action_sparator, .wcmca_delete_address_button, .wcmca_duplicate_address_button').fadeOut();
	
	var html_elem_to_use = document.getElementById("wcmca_address_form_container_"+type) != null ? jQuery('#wcmca_address_form_container_'+type).offset().top : jQuery('#wcmca_custom_addresses').offset().top;
	try{
	 jQuery('html, body').animate({
          scrollTop: html_elem_to_use.offset().top - 60 //#wcmca_address_form_container ?
        }, 1000);
	}catch(error){}
	 return false;
}
function wcma_highlight_empty_field(field)
{
	//console.log(jQuery(field));
	var original_border = jQuery(field).css('border');
	var original_border_width = jQuery(field).css('border-width');
	
	if(field.name === "wcmca_billing_country" || 
	   field.name === "wcmca_billing_state" || 
	   field.name === "wcmca_shipping_country" || 
	   field.name === "wcmca_shipping_state"  )
	   {
		jQuery("#s2id_"+field.name).css({ 'border': "1px #FF0000 solid " });
	   }
	jQuery(field).css({ 'border': "1px #FF0000 solid " })/* .animate({borderWidth: 2}, 500) */;
	/* jQuery(field).animate({ borderTopColor: '#FF0000',
							borderBottomColor: '#FF0000',
							borderLeftColor: '#FF0000',
							borderRightColor: '#FF0000'}, 'slow'); */
	
	setTimeout(function(){ jQuery(field).css({ 'border': original_border })/* .animate({borderWidth: original_border_width}, 500) */; }, 3000);
}
function wcmca_show_address_form()
{
	jQuery('footer, .fusion-header-wrapper').fadeOut();
	//jQuery('#wcmca_form_popup_container').css('top', jQuery(document).scrollTop()  -  (jQuery('#wcmca_form_popup_container').height()/2) + 60);
	//jQuery('#wcmca_form_popup_container .woocommerce, #wcmca_form_popup_container .woocommerce #wcmca_address_form, #wcmca_form_popup_container .woocommerce #wcmca_address_form #wcmca_address_form_fieldset').css('top', jQuery('#wcmca_form_popup_container').offset().top+20);
	//jQuery('#wcmca_form_popup_container').css('left', jQuery(document).scrollLeft()  );
	
	/* jQuery('#wcmca_form_popup_container').fadeIn();
	jQuery('#wcmca_form_background_overlay').fadeIn(); */
	
	jQuery('html, body').animate({
          scrollTop: jQuery('#wcmca_form_popup_container').offset().top
        }, 1000);
}
function wcmca_hide_address_form()
{
	jQuery('footer, .fusion-header-wrapper').fadeIn();
	jQuery.magnificPopup.instance.close();
	/* jQuery('#wcmca_form_popup_container').fadeOut();
	jQuery('#wcmca_form_background_overlay').fadeOut(); */
}
function wcmca_update_fields_options_and_attributes(options_and_attributes, type)
{
	var required_label_extra_html = ' <abbr title="required" class="required">*</abbr>';
	//add the default required classes to html elements
	jQuery('#wcmca_'+type+'_state, #wcmca_'+type+'_city, #wcmca_'+type+'_postcode').addClass('not_empty');
	jQuery('#wcmca_'+type+'_state_field, #wcmca_'+type+'_city_field, #wcmca_'+type+'_postcode_field').addClass('validate-required');
	jQuery('#wcmca_'+type+'_state, #wcmca_'+type+'_city, #wcmca_'+type+'_postcode').prop('required', 'required');
	//string
	jQuery('#wcmca_'+type+'_state_field label.wcmca_form_label').html(wcmca_state_string+required_label_extra_html);
	jQuery('#wcmca_'+type+'_postcode_field label.wcmca_form_label').html(wcmca_postcode_string+required_label_extra_html);
	jQuery('#wcmca_'+type+'_city_field label.wcmca_form_label').html(wcmca_city_string+required_label_extra_html);
	
	if(options_and_attributes == null)
		return;
	
	for(var option in options_and_attributes)
	{
		var current_field = null;
		var current_element = null;
		switch(option)
		{
			case 'state': current_element = options_and_attributes.state; break;
			case 'postcode': current_element = options_and_attributes.postcode; break;
			case 'city': current_element = options_and_attributes.city; break;
		}
		if(current_element != null)
		{
			if(typeof current_element.required !== 'undefined' && current_element.required == false)
			{
				jQuery('#wcmca_'+type+'_'+option+'_field').removeClass('validate-required');
				jQuery('#wcmca_'+type+'_'+option).removeClass('not_empty');
				jQuery('#wcmca_'+type+'_'+option).removeProp('required');
				//Text without the "*" html
				if(typeof jQuery('#wcmca_'+type+'_'+option+'_field label.wcmca_form_label').html() !== 'undefined')
					jQuery('#wcmca_'+type+'_'+option+'_field label.wcmca_form_label').html(jQuery('#wcmca_'+type+'_'+option+'_field label.wcmca_form_label').html().replace(required_label_extra_html, ""));
			}
			if(typeof current_element.label !== 'undefined')
			{
				jQuery('#wcmca_'+type+'_'+option+'_field label.wcmca_form_label').html(current_element.label);
				if(typeof current_element.required === 'undefined' || current_element.required != false)
					jQuery('#wcmca_'+type+'_'+option+"_field label").html(current_element.label+required_label_extra_html);
			}
			if(typeof current_element.hidden !== 'undefined' && current_element.hidden == true)
			{
				jQuery('#wcmca_'+type+'_'+option+"_field").hide();
			}
			else
			{
				jQuery('#wcmca_'+type+'_'+option+"_field").show();
			}
		}
	}
}
