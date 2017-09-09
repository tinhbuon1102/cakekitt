var wcuf_is_force_reloading = false;

/*
 Workflow:
	-- upload:  wcuf_backgroud_file_upload -> wcuf_append_file_delete() -> wcuf_ajax_reload_upload_fields_container()
	-- delete: wcuf_delete_file -> wcuf_ajax_reload_upload_fields_container()
*/
jQuery(document).ready(function()
{
	jQuery('.wcuf_file_input').val('');
	if(wcuf_unload_check) //not performed in checkout page. For that one is used the '#place_order' click event.
		wcuf_before_unload();
	
	jQuery(document).on('click','.delete_button', wcuf_delete_file);
	jQuery(document).on('click', '.wcuf_delete_single_file_stored_on_server', wcuf_delete_single_file_on_server);
	jQuery(document).on('click','.wcuf_upload_field_button', wcuf_browse_file);
	jQuery(document).on('click', '#place_order', wcuf_checkout_check_required_fields); //only during checkout
	
	jQuery('#wcuf_show_popup_button').magnificPopup({
          type: 'inline',
		  showCloseBtn:false,
          preloader: false,
            callbacks: {
            /*
			
			beforeOpen: function() {
              console.log("here");
            }*/
			 /* close: function(event) {
				  wcuf_test(event)
				} */
          } 
        });
	jQuery('#wcuf_close_popup_alert').on('click',function(event){ event.preventDefault(); event.stopImmediatePropagation(); jQuery.magnificPopup.close(); return false});
	jQuery('#wcuf_leave_page').on('click', wcuf_leave_the_page_after_warning_popup_prompt); //see method comment
		
	if (window.File && window.FileReader && window.FileList && window.Blob) 
	{
		//Old "string encoding" method
		//jQuery('.wcuf_file_input').on('change' ,wcuf_encode_file);
		
		//jQuery('form').on('change', '.wcuf_file_input.wcuf_file_input_multiple', wcuf_check_multiple_file_uploads_limit);
		
		jQuery(document).on('change','.wcuf_file_input', wcuf_file_input_check);
		//jQuery('.wcuf_file_input').change(wcuf_file_input_check);
		
		jQuery(document).on('click','.wcuf_upload_multiple_files_button', wcuf_start_checks_on_files_info);
	} 
	else 
	{
		jQuery('#wcuf_file_uploads_container').hide();
		wcuf_show_popup_alert(wcuf_html5_error);
	}
	
	//Required uploads warning message
	if(wcuf_show_required_upload_add_to_cart_warning_message)
		jQuery(".product_meta").prepend(wcuf_required_upload_add_to_cart_warning_message);
	
	//Hide add to cart in case of required field 
	wcuf_hide_add_to_cart_button_if_product_page_and_before_add();
});

function wcuf_browse_file(event)
{
	event.preventDefault();
	event.stopImmediatePropagation();
	var id = jQuery(event.currentTarget).data('id');
	jQuery("#wcuf_upload_field_"+id).trigger('click');
	return false;
}
function wcuf_delete_single_file_on_server(event)
{
	var id = jQuery(event.currentTarget).data('id');
	var field_id = jQuery(event.currentTarget).data('field-id');
	wcuf_ui_delete_file();
	event.preventDefault();
	event.stopImmediatePropagation();
	
	//wcuf_show_popup_alert(wcuf_delete_single_file_warning_msg);
	//console.log(id);
	
	//
	jQuery.post( wcuf_ajaxurl , { action: wcuf_ajax_delete_single_file_action, id: id, field_id:field_id } ).done(wcuf_ui_after_delete);
}

function wcuf_delete_file(event)
{
	wcuf_is_deleting = true;
	wcuf_ui_delete_file();
	event.preventDefault();
	event.stopImmediatePropagation();
	
	
	jQuery.post( wcuf_ajaxurl , { action: wcuf_ajax_delete_action, id: jQuery(event.target).data('id')/* , order_id:wcuf_order_id  */} ).done(wcuf_ui_after_delete);
	return false;
}

function wcuf_reset_data()
{
	wcuf_multiple_files_queues = new Array();
}
function wcuf_ajax_reload_upload_fields_container()
{
	wcuf_reset_data();
	//var action = wcuf_current_page == 'checkout' ? 'reload_upload_fields_on_checkout' : 'reload_upload_fields'; 
	var action = 'reload_upload_fields_on_checkout';
	if(wcuf_current_page == 'product')
		action = 'reload_upload_fields';
	else if(wcuf_current_page == 'cart')
		action = 'reload_upload_fields_on_cart';
	else if(wcuf_current_page == 'shortcode')
		action = 'reload_shortcode_upload_fields';
	
	if(wcuf_current_page ==  'checkout')
		jQuery( document.body ).trigger( 'update_checkout' ); 
	
	var random = Math.floor((Math.random() * 1000000) + 999);
	
	//ajax_reload_upload_fields
	jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').animate({ opacity: 0 }, 500, function()
	{	
		jQuery('#wcuf_'+wcuf_current_page+'_ajax_container_loading_container').html("<h4>"+wcuf_ajax_reloading_fields_text+"</h4>");
		var variation_id = jQuery('input[name=variation_id]').val();
		var formData = new FormData();	
		formData.append('action', action);	
		if( wcuf_current_page == 'checkout' && typeof wcuf_current_paymenth_method !== 'unedfined')
			formData.append('payment_method', wcuf_current_paymenth_method);
		formData.append('product_id', wcuf_current_prduct_id);		
		formData.append('variation_id', jQuery.isNumeric(variation_id) ? variation_id : 0);		
		formData.append('wcuf_wpml_language', wcuf_wpml_language);
		jQuery.ajax({
			url: wcuf_ajaxurl+"?nocache="+random,
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container_loading_container').html("");  
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').html(data);
				jQuery('#wcuf_'+wcuf_current_page+'_ajax_container').animate({ opacity: 1 }, 500);	
							
				//Hide add to cart in case of required field 
				wcuf_hide_add_to_cart_button_if_product_page_and_before_add();
				
			},
			error: function (data) {
				//console.log(data);
				//wcuf_show_popup_alert("Error: "+data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
}
function wcuf_append_file_delete(id) //after file upload
{
	 if(wcuf_current_page == "cart"  ) //|| wcuf_current_page == "shortcode"
	{
		wcuf_reload_page(500);
		return false;
	} 
	else //if(wcuf_current_page == "product") 
	{
		wcuf_ajax_reload_upload_fields_container();
		//setTimeout(function(){wcuf_ajax_reload_upload_fields_container() }, 1500);
		return false;
	} 
	
	
	//Not used any more ------------------------------------------------------ 
	var delete_id = wcuf_is_order_detail_page ? id : 'wcufuploadedfile_'+id;
	jQuery('.wcuf_file_name').delay(320).fadeIn(300);
	jQuery('#wcuf_upload_status_box_'+id).delay(300).hide(500);
	
	wcuf_show_control_buttons();

	jQuery('#wcuf_upload_field_button_'+id+', #wcuf_upload_multiple_files_button_'+id+', #wcuf_file_name_'+id+', #wcuf_max_size_notice_'+id+', #wcuf_feedback_textarea_'+id).remove();
	
	jQuery('#wcuf_delete_button_box_'+id).append('<button class="button delete_button" data-id="'+delete_id+'">'+wcuf_delete_file_msg+'</button>');
	jQuery(document).on('click', '#wcuf_delete_button_box_'+id, wcuf_delete_file);	
}
function wcuf_set_bar_background()
{
	jQuery('.wcuf_bar').css('background-color',wcuf_progressbar_color);
}
function wcuf_hide_control_buttons()
{
	jQuery('.wcuf_crop_container, .wcuf_disclaimer_label, .wcuf_upload_field_button, .wcuf_feedback_textarea, .wcuf_max_size_notice, .wcuf_upload_multiple_files_button, .delete_button').fadeOut(300);
	
}
function wcuf_show_control_buttons()
{
	jQuery('.wcuf_upload_field_button,  .wcuf_disclaimer_label, .wcuf_max_size_notice, .wcuf_feedback_textarea, .delete_button').fadeIn(500);
	jQuery('.wcuf_upload_multiple_files_button').each(function(index,elem)
	{
		var id = jQuery(this).data('id');
		if(typeof wcuf_multiple_files_queues !== 'undefined' && typeof wcuf_multiple_files_queues[id] !=='undefined' && wcuf_multiple_files_queues[id].length > 0)
			jQuery(this).fadeIn(500);
	});
}
function wcuf_show_multiple_files_progress_area(id)
{
	jQuery('#wcuf_multiple_file_progress_container_'+id).fadeIn();
}
function wcuf_reset_loading_ui(id)
{
	wcuf_set_bar_background();
	jQuery('#wcuf_file_name_'+id).html("");
	jQuery('.wcuf_file_name, wcuf_multiple_file_progress_container_'+id).fadeOut(0);	
	jQuery('#wcuf_bar_'+id+", #wcuf_multiple_file_bar_"+id).css('width', "0%");
	
	//jQuery('input.wcuf_file_input, .wcuf_max_size_notice, .delete_button').fadeOut(500);
	wcuf_hide_control_buttons();
	
	jQuery('#wcuf_upload_status_box_'+id).show(400,function()
	{
		//Smooth scroll
		try{
			jQuery('html, body').animate({
				  scrollTop: jQuery('#wcuf_upload_status_box_'+id).offset().top - 200 //#wcmca_address_form_container ?
				}, 500);
		}catch(error){}
	});
	jQuery('#wcuf_delete_button_box_'+id).empty();
	//jQuery('#wcuf_status_'+id).html("");
	jQuery('#wcuf_status_'+id).html(wcuf_loading_msg);
}
function wcuf_file_input_check(evt)
{
	evt.preventDefault();
	evt.stopImmediatePropagation();
	var id = jQuery(evt.target).data('id');
	
	if(jQuery(evt.target).prop('multiple'))
	{
		wcuf_manage_multiple_file_browse(evt);
		if(wcuf_auto_upload_for_multiple_files_upload_field)
		{
			//console.log('#wcuf_upload_multiple_files_button_'+id);
			jQuery('#wcuf_upload_multiple_files_button_'+id).trigger('click');
		}
	}
	else
		wcuf_start_checks_on_files_info(evt);
	
	return false;
}
function wcuf_start_checks_on_files_info(evt)
{
	evt.preventDefault();
	evt.stopImmediatePropagation();
	var id =  jQuery(evt.currentTarget).data('id');
	var current_elem = jQuery('#wcuf_upload_field_'+id);
	var dimensions_logical_operator = current_elem.data("dimensions-logical-operator");
	var max_image_width = current_elem.data("max-width");
	var max_image_height = current_elem.data("max-height");
	var min_image_width = current_elem.data("min-width");
	var min_image_height = current_elem.data("min-height");
	var min_image_dpi = current_elem.data("min-dpi");
	var max_image_dpi = current_elem.data("max-dpi");
	/* var exact_image_size = current_elem.data("exact-image-size"); */

	var is_multiple = jQuery(evt.currentTarget).hasClass('wcuf_upload_multiple_files_button');
	if(is_multiple)
	{
		if(typeof wcuf_multiple_files_queues === 'undefined' || typeof wcuf_multiple_files_queues[id] === 'undefined')
			return false;
		
		files = wcuf_multiple_files_queues[id];
	}
	else
	{
		files = evt.target.files;
	}
	//For not image files, error is not rised if min/max width/wight are not set.
	wcuf_check_image_file_width_and_height(files,evt,wcuf_result_on_files_info, max_image_width, max_image_height, min_image_width, min_image_height, min_image_dpi ,max_image_dpi, dimensions_logical_operator);
}
function wcuf_result_on_files_info(evt, error, img, data)
{
	if(!error)
	{
		wcuf_check_if_show_cropping_area(evt);
	}
	else
	{
		var size_string = "<br/>";
		size_string += typeof data.min_image_width !== 'undefined' && data.min_image_width != 0 ? data.min_image_width+" "+wcuf_image_min_width_text+"<br/>" : ""; 
		size_string += typeof data.max_image_width !== 'undefined' && data.max_image_width != 0 ? data.max_image_width+" "+wcuf_image_width_text+"<br/>" : ""; 
		size_string += typeof data.min_image_height !== 'undefined' && data.min_image_height != 0 ? data.min_image_height+" "+wcuf_image_min_height_text+"<br/>": "";
		size_string += typeof data.max_image_height !== 'undefined' && data.max_image_height != 0 ? data.max_image_height+" "+wcuf_image_height_text+"<br/>" : "";
		size_string += typeof data.min_dpi !== 'undefined' && data.min_dpi != 0 ? data.min_dpi+" "+wcuf_image_min_dip_text+"<br/>" : "";
		size_string += typeof data.max_dpi !== 'undefined' && data.max_dpi != 0 ? data.max_dpi+" "+wcuf_image_max_dip_text+"<br/>" : "";
		
		/* if(!data.exact_image_size) */
			wcuf_show_popup_alert(wcuf_image_size_error+" "+size_string);
		/* else
			wcuf_show_popup_alert(img.name+wcuf_image_exact_size_error+size_string); */
		return false;
	}
		
}
function wcuf_check_if_show_cropping_area(evt)
{
	var is_multiple = jQuery(evt.currentTarget).hasClass('wcuf_upload_multiple_files_button');
	var id = jQuery(evt.currentTarget).data('id');
	var enable_crop = jQuery("#wcuf_upload_field_"+id).data('enable-crop-editor');
	/* console.log(is_multiple);
	console.log(enable_crop); */
	if(!is_multiple && enable_crop)
	{
		new wcuf_image_crop(evt, id, wcuf_backgroud_file_upload);
	}			
	else
		wcuf_backgroud_file_upload(evt);
}
function wcuf_backgroud_file_upload(evt)
{
	evt.preventDefault();
	evt.stopImmediatePropagation();
	var id =  jQuery(evt.currentTarget).data('id');
	var current_elem = jQuery('#wcuf_upload_field_'+id); //jQuery(evt.currentTarget)
	var file_wcuf_user_feedback = jQuery('#wcuf_feedback_textarea_'+id).val();
	var size = current_elem.data('size');
	var min_size = current_elem.data('min-size');
	var file_wcuf_name = current_elem.attr('name');
	var file_wcuf_title = current_elem.data('title');
	var detect_pdf = current_elem.data('detect-pdf');
	var check_disclaimer = current_elem.data('disclaimer');
	var extension =  current_elem.val().replace(/^.*\./, '');
	var extension_accepted = current_elem.attr('accept');
	var max_image_width = current_elem.data("max-width");
	var max_image_height = current_elem.data("max-height");
	
	var files;
    var file;
	var is_multiple = jQuery(evt.currentTarget).hasClass('wcuf_upload_multiple_files_button');

	if(is_multiple)
	{
		if(typeof wcuf_multiple_files_queues === 'undefined' || typeof wcuf_multiple_files_queues[id] === 'undefined')
			return false;
		
		files = wcuf_multiple_files_queues[id];
		file = wcuf_multiple_files_queues[id][0];
	}
	else
	{
		files = evt.target.files;
		if(typeof evt.blob === 'undefined') 
			file = files[0]; 
		else  //in case the file (image) has been cropped
			file = evt.blob;
	}
	
	extension =  extension.toLowerCase();
	if(typeof extension_accepted !== 'undefined')
		extension_accepted =  extension_accepted.toLowerCase();
	
	if (location.host.indexOf("sitepointstatic") >= 0) return;
	
	var xhr = new XMLHttpRequest();
	
	//Checkes
	if(check_disclaimer && !jQuery('#wcuf_disclaimer_checkbox_'+id).prop('checked'))
	{
		wcuf_show_popup_alert(wcuf_disclaimer_must_be_accepted_message)
		return false;
	}
	if(is_multiple)
	{
		/* for(var i = 0; i < files.length; i++)
		{
			var curr_ext = files[i].name.replace(/^.*\./, '');
			if( (typeof extension_accepted !== 'undefined' && extension_accepted.indexOf(curr_ext) == -1) || files[i].size > size)
			{
				wcuf_display_file_size_or_ext_error(iles[i], size, extension_accepted);
				return false;
			}
		} */
		if(!wcuf_check_multiple_file_uploads_limit(id))
		{
			return false;
		}
	}
	if(jQuery('#wcuf_feedback_textarea_'+id).val() == "" && jQuery('#wcuf_feedback_textarea_'+id).prop('required'))
	{
		wcuf_show_popup_alert(wcuf_user_feedback_required_message)
		return;
	}	
	jQuery('#wcuf_feedback_textarea_'+id).prop('disabled', true);

	
	if (xhr.upload && 
		/* file.type == "image/jpeg" && */ 
		(extension_accepted == undefined || extension_accepted.indexOf(extension) > -1) &&
		((size == 0 || file.size <= size) && (min_size == 0 || file.size >= min_size) )) 
		{
			//UI			
			wcuf_reset_loading_ui(id);
			/*if(files.length > 1)
				wcuf_show_multiple_files_progress_area(id);*/
			
			// progress bar
			/*xhr.upload.addEventListener("progress", function(e) 
			{
				var pc = parseInt((e.loaded / e.total * 100));
				jQuery('#wcuf_bar_'+id).css('width', pc+"%");
				jQuery('#wcuf_percent_'+id).html(pc + "%");
				
				if(files.length > 1)
				{
					jQuery('#wcuf_multiple_file_bar_'+id).css('width', multiple_file_uploader.getProgress(e.loaded)+"%");
					jQuery('#wcuf_multiple_file_upload_percent_'+id).html( multiple_file_uploader.getProgress(e.loaded) + "%");	
				}
				
			}, false);
			xhr.upload.addEventListener("load",function(e)
			{
				// console.log(JSON.stringify(e));
				//2
				//wcuf_append_file_delete(id);
				//multiple_file_uploader.setAlreadyLoadedBytes(e.total);
				
			},false);
			// file received/failed
			xhr.onreadystatechange = function(e) 
			{
				if (xhr.readyState == 4) 
				{
					//1.
					if(xhr.responseText === '0' || xhr.responseText === '1')
					{
						//jQuery('#wcuf_status_'+id).html(wcuf_file_sizes_error);
					}
					else if(xhr.status == 200)
					{
						//3
						if(multiple_file_uploader.continueUploading() == false)
						{
							jQuery('#wcuf_status_'+id).html(xhr.status == 200 ? wcuf_success_msg : wcuf_failure_msg);
							wcuf_append_file_delete(id);
						}
					}
				}
			};*/

			/* var formData = new FormData();
			xhr.open("POST", wcuf_ajaxurl, true); //3rd parameter: async ->true/false
			formData.append('action', wcuf_ajax_action); //'upload_file_during_checkout_or_product_page'
			formData.append('title', file_wcuf_title);
			formData.append('detect_pdf', detect_pdf);
			formData.append('user_feedback', file_wcuf_user_feedback);
			formData.append('order_id', wcuf_order_id);
			formData.append('wcuf_wpml_language', wcuf_wpml_language); */
			
			
			var formData = {'action': wcuf_ajax_action,
							'title': file_wcuf_title,
							'detect_pdf': detect_pdf,
							'user_feedback': file_wcuf_user_feedback,
							'order_id': wcuf_order_id,
							'wcuf_wpml_language': wcuf_wpml_language
						};
			
			var multiple_file_uploader = new WCUFMultipleFileUploader({/* xhr: xhr, */  upload_field_id:id, form_data: formData, files: files, file: file, file_name:file_wcuf_name});
			document.addEventListener('onWCUFMultipleFileUploaderComplete', function(){wcuf_append_file_delete(id);});
			
			/*if(files.length == 1)
			{
				var tempfile_name  = wcuf_replace_bad_char(file.name);
				var quantity = typeof file.quantity !== 'undefined' ? file.quantity : 1;
				formData.append(file_wcuf_name, file, tempfile_name); //file id used as key
				formData.append('multiple', 'no');
				formData.append('quantity_0', quantity);
				
				//Append file name to wcuf_file_name_
				jQuery('#wcuf_file_name_'+id).html(tempfile_name);
			}
			else
			{
				formData.append('multiple', 'yes');
				for(var i = 0; i < files.length; i++)
				{
					var tempfile_name  = wcuf_replace_bad_char(files[i].name);
					var quantity = typeof files[i].quantity !== 'undefined' ? files[i].quantity : 1;
					formData.append('quantity_'+i, quantity);
					if(i == 0)
					{
						formData.append(file_wcuf_name, files[i], tempfile_name);
						//jQuery('#wcuf_file_name_'+id).html(files[i].name);
					}
					else
					{
						formData.append(file_wcuf_name+"_"+i, files[i], tempfile_name);
						//jQuery('#wcuf_file_name_'+id).html(jQuery('#wcuf_file_name_'+id).html()+"<br/>"+files[i].name);
					}
				}
			}*/
			
			if(typeof wcuf_multiple_files_queues !== 'undefined' && typeof wcuf_multiple_files_queues[id] !== 'undefined')
				wcuf_multiple_files_queues[id] = new Array();
			/* try{
				xhr.send(formData);
			}catch(e){}  */
			multiple_file_uploader.continueUploading();
		}	
		else
		{
			wcuf_display_file_size_or_ext_error(file, size, extension_accepted, min_size, size);
		}
			
	return false;
}
function wcuf_replace_bad_char(text)
{
	text = text.replace(/'/g,"");
	text = text.replace(/"/g,"");
	text = text.replace(/ /g,"_");
	return text;
}
function wcuf_display_file_size_or_ext_error(file, size, extension_accepted, min_size, max_size)
{
	var msg = "";
	
	if(min_size != 0)
		msg += file.name+wcuf_file_min_size_error+(min_size/(1024*1024))+" MB<br/>";
	if(max_size != 0)
		msg += file.name+wcuf_file_size_error+(size/(1024*1024))+" MB<br/>";
	
	if(typeof extension_accepted !== 'undefined')
		msg += wcuf_type_allowed_error+" "+extension_accepted;
	wcuf_show_popup_alert(msg);
}  
function wcuf_check_multiple_file_uploads_limit(id)
{
	var fileUpload = jQuery('#wcuf_upload_field_'+id);
	var max_size = fileUpload.data('size');
	var min_size = fileUpload.data('min-size');
	var multiple_files_sum_max_size = fileUpload.data('multiple-files-max-sum-size');
	var multiple_files_sum_min_size = fileUpload.data('multiple-files-min-sum-size');
	var is_multiple_files_field = fileUpload.data('is-multiple-files');
	var max_num = fileUpload.data('max-files');
	var min_num = fileUpload.data('min-files');
	var extension_accepted = fileUpload.attr('accept');
	var error = false;
	var files = wcuf_multiple_files_queues[id];
    var all_files_quantity_sum = 0;
	var sum_all_file_sizes = 0;
	
	if(typeof extension_accepted !== 'undefined')
		extension_accepted =  extension_accepted.toLowerCase();
	
	//Computing number of files and their quantity
	all_files_quantity_sum = files.length;
	if(wcuf_max_uploaded_files_number_considered_as_sum_of_quantities)
	{
		for (var i=0; i<files.length; i++)
		{
			all_files_quantity_sum += typeof files[i].quantity !== 'undefined' && parseInt(files[i].quantity) > 1 ? parseInt(files[i].quantity) - 1 : 0;
		}
		
	}
	
	//if (parseInt($fileUpload.get(0).files.length) > max_num)
	if (max_num != 0 && /* (files.length+all_files_quantity_sum) */ all_files_quantity_sum > max_num)
	{
		wcuf_show_popup_alert(wcuf_file_num_error+max_num);
		error = true;
	}
	else if(min_num != 0 && /* (files.length+all_files_quantity_sum)  */ all_files_quantity_sum < min_num)
	{
		wcuf_show_popup_alert(wcuf_minimum_required_files_message+min_num);
		error = true;
	}
	else 
	{
		var msg="";
		for(var i = 0; i < files.length; i++)
		{
			var name = files[i].name;
			var extension =  name.replace(/^.*\./, '');
			extension =  extension.toLowerCase();
			sum_all_file_sizes += files[i].size;
			if((min_size != 0 && files[i].size < min_size) || (max_size != 0 && files[i].size > max_size) || (extension_accepted != undefined && extension_accepted.indexOf(extension) == -1))
			{
				msg += name+wcuf_file_size_type_header_error;
				if(max_size != 0)
					msg += wcuf_file_size_error+(max_size/(1024*1024))+" MB<br/>";
				if(min_size != 0)
					msg += wcuf_file_min_size_error+(min_size/(1024*1024))+" MB<br/>";
				
				if(typeof extension_accepted !== 'undefined')
					msg += wcuf_type_allowed_error+" "+extension_accepted+"<br/>";
				
				//msg += "<br/>"; 
			}
		}
		if(msg =="" && is_multiple_files_field && multiple_files_sum_max_size != 0 && sum_all_file_sizes > multiple_files_sum_max_size)
		{
			var size_error_message = wcuf_file_sizes_error.replace("%s", (multiple_files_sum_max_size/(1024*1024)));
			msg = size_error_message; 
		}
		if(msg =="" && is_multiple_files_field && multiple_files_sum_min_size != 0 && sum_all_file_sizes < multiple_files_sum_min_size)
		{
			var size_error_message = wcuf_file_sizes_min_error.replace("%s", (multiple_files_sum_min_size/(1024*1024)));
			msg = size_error_message; 
		}
		
		if(msg != "")
		{
			wcuf_show_popup_alert(msg);
			error = true;
		}
	}
	
	if(error)
	{
		/* event.stopImmediatePropagation();
		event.preventDefault(); */
		return false;
	}
	return true;
}
function wcuf_check_multiple_upload_status(evt)
{
    if(typeof wcuf_multiple_files_queues !== 'undefined')
		for (var key in wcuf_multiple_files_queues) 
		//jQuery.each(wcuf_multiple_files_queues, function(key, value)
		{
		  if ((!isNaN(key) || key.indexOf('-') > -1) && wcuf_multiple_files_queues[key].length != 0) //In some instalaltion keys contains some other window/dom elements
		  {
			  if(evt != null)
			  {
				  evt.preventDefault();
				  evt.stopImmediatePropagation();
				  wcuf_show_popup_alert(wcuf_multiple_uploads_error_message);
			  }
			 return false;
		  }
		}
	return true;
}
function wcuf_all_required_uploads_have_been_performed(bypass_page_leave_check)
{
	var ok = true;
	if(!bypass_page_leave_check && wcuf_allow_user_to_leave_page_in_case_of_required_field == 'always')
		return true;
	jQuery('.wcuf_file_input').each(function(index,value)
	{
		var min_files = parseInt(jQuery(this).data('min-files'));
		var data_is_required = jQuery(this).data('required');
		
		//if(jQuery(this).prop('required') && jQuery(this).val() == '') //before incremental upload 
		if(data_is_required && min_files != 0)
			ok =  false;
	});
	return ok;
}
function wcuf_checkout_check_required_fields(event)
{
	/* jQuery('.wcuf_file_input').each(function(index, object)
	{
		if(jQuery(this).val() == "" && jQuery(this).prop('required'))
		{
			event.preventDefault();
			event.stopImmediatePropagation();
			wcuf_show_popup_alert(wcuf_checkout_required_message);
			return false;
		}
	}); */
	if(!wcuf_all_required_uploads_have_been_performed(false))
	{
		event.preventDefault();
		event.stopImmediatePropagation();
		wcuf_show_popup_alert(wcuf_checkout_required_message); 
		return false;
	}
	
	wcuf_check_multiple_upload_status(event);
}
function wcuf_hide_add_to_cart_button_if_product_page_and_before_add()
{
	/* if(!wcuf_exist_a_field_before_add_to_cart || wcuf_item_has_been_added_to_cart)
	{
		jQuery('.single_add_to_cart_button').fadeIn(600);
		return
	} */
	if(wcuf_current_page != 'product' /* || !wcuf_exist_a_field_before_add_to_cart */)
		return;
	
	if(!wcuf_exist_a_field_before_add_to_cart || (wcuf_all_required_uploads_have_been_performed(true) && wcuf_check_multiple_upload_status(null)))
	{
		jQuery('.single_add_to_cart_button, .quantity').fadeIn(600);
		jQuery('.wcuf_required_upload_add_to_cart_warning_message').fadeOut(400);
	}
	else
	{
		jQuery('.single_add_to_cart_button, .quantity').fadeOut(400);
		jQuery('.wcuf_required_upload_add_to_cart_warning_message').fadeIn(600);
	}
	
}
function wcuf_is_edge()
{
	return /Edge\/\d+/i.test(navigator.userAgent);
}
function wcuf_is_IE()
{
	var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
    {
		 return true;
    }
}

//In case of 'allow_user_to_leave_page_in_case_of_required_field', popup ok button (alert_popup.php) has id "wcuf_leave_page". So the click
//is detected via this handler and user can leave page on next leave attempt
function wcuf_leave_the_page_after_warning_popup_prompt(event)
{
	jQuery.magnificPopup.close();
	wcuf_is_force_reloading = true;
	return false;
}
function wcuf_before_unload() 
{
	jQuery(window).bind("beforeunload", function (e) 
	{
		if(wcuf_is_force_reloading)
			return; 
        var can_exit = true;
		var multiple_uploads_status = wcuf_check_multiple_upload_status(null);
		var confirmationMessage = wcuf_unload_confirm_message;
		if(!multiple_uploads_status)
		{
			can_exit = multiple_uploads_status;
			confirmationMessage = wcuf_multiple_uploads_error_message;
		}
		
		if( wcuf_current_page != 'product' || 
			(wcuf_force_require_check_before_adding_item_to_cart && wcuf_exist_a_field_before_add_to_cart) || 
			(wcuf_current_page == 'product' && wcuf_item_has_been_added_to_cart))
				/* jQuery('.wcuf_file_input').each(function(index,value)
				{
					if(jQuery(this).prop('required') && jQuery(this).val() == '')
						can_exit = false;
				}); */
				can_exit = wcuf_all_required_uploads_have_been_performed(false);
		if(can_exit || wcuf_is_deleting)
			return undefined;
		
		/* wcuf_show_popup_alert(confirmationMessage);
				window.setTimeout(function() {
					window.stop();
				}, 1); */
		
		 //if(/Firefox[\/\s](\d+)/.test(navigator.userAgent) && new Number(RegExp.$1) >= 4) 
		 {
			 if(!wcuf_is_IE() && confirm(confirmationMessage)) 
				history.go();
			else 
				{
					if(!wcuf_is_edge() && !wcuf_is_IE())
						wcuf_show_popup_alert(confirmationMessage);
					window.setTimeout(function() {
						window.stop();
					}, 1);
					if(wcuf_is_edge() || wcuf_is_IE())
					{
						e.preventDefault();
						e.stopImmediatePropagation();
						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage;
					}
				}
		}
		/* else //old method
		{
			wcuf_show_popup_alert(confirmationMessage);
			(e || window.event).returnValue = confirmationMessage; //Gecko + IE
			e.preventDefault();
			e.stopImmediatePropagation();
			return confirmationMessage;
		}  */
    });
};