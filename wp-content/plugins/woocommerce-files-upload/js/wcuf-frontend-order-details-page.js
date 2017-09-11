jQuery(document).ready(function()
{
	jQuery('.wcuf_file_input').val('');
	jQuery(document).on('click', '.delete_button', wcuf_delete_file);
	jQuery(document).on('click', '.wcuf_delete_single_file_stored_on_server', wcuf_delete_single_file_on_server);
	jQuery(document).on('click','#wcuf_upload_button', wcuf_save_all_uploads);
	jQuery(document).on('click', '.wcuf_upload_field_button', wcuf_browse_file);
	
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
	jQuery(document).on('click', '#wcuf_close_popup_alert, #wcuf_leave_page', function(event){ event.preventDefault(); event.stopImmediatePropagation(); jQuery.magnificPopup.close(); return false});
	
	if (window.File && window.FileReader && window.FileList && window.Blob) 
	{
		//Old "string encoding" method
		//jQuery('.wcuf_file_input').on('change' ,wcuf_encode_file);
		
		//jQuery('.wcuf_file_input.wcuf_file_input_multiple').on('change', wcuf_check_multiple_file_uploads_limit);
		jQuery(document).on('change','.wcuf_file_input', wcuf_file_input_check);
		jQuery(document).on('click','.wcuf_upload_multiple_files_button', wcuf_start_checks_on_files_info);
	} 
	else 
	{
		jQuery('#wcuf_file_uploads_container').hide();
		wcuf_show_popup_alert(wcuf_html5_error);
	}
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
	wcuf_ui_delete_file_on_order_details_page();
	event.preventDefault();
	event.stopImmediatePropagation();
	//wcuf_show_popup_alert(wcuf_delete_single_file_warning_msg);
	//console.log(id);
	
	jQuery.post( wcuf_ajaxurl , { action: wcuf_ajax_delete_single_file_action, id: id, order_id:wcuf_order_id, field_id:field_id } ).done( wcuf_ui_after_delete );
	return false;
}
function wcuf_delete_file(event)
{
	wcuf_is_deleting = true;
	wcuf_ui_delete_file_on_order_details_page();
	event.preventDefault();
	event.stopImmediatePropagation();
	var is_temp = jQuery(event.target).data('temp');
	
	if(is_temp == "yes")
		return;
	
	jQuery.post( wcuf_ajaxurl , { action: wcuf_ajax_delete_action, id: jQuery(event.target).data('id'), order_id:wcuf_order_id, is_temp:is_temp } ).done( wcuf_ui_after_delete /* function(){  window.location.reload(true);    } */);
	return false;
}
function wcuf_delete_temp_file(event)
{
	event.preventDefault();
	event.stopImmediatePropagation();
	//jQuery("#wcuf_deleting_message").animate({'opacity':'1'}, 200); 
	
	var id = jQuery(event.target).data('id');
	var upload_id = jQuery(event.target).data('upload-id');
	var is_temp = jQuery(event.target).data('temp');
	jQuery('#wcuf_delete_button_box_'+upload_id).fadeOut();
	jQuery('#wcuf_deleting_box_'+upload_id).fadeIn(400);
	jQuery('#wcuf_file_name_'+upload_id).fadeOut(400);
	jQuery('#wcuf_upload_button').fadeOut(400);
	
	jQuery.post( wcuf_ajaxurl , { action: wcuf_ajax_delete_action, id: id, order_id:wcuf_order_id, is_temp:is_temp, wcuf_wpml_language:'wcuf_wpml_language' } ).done( function()
				{  
					jQuery('#wcuf_feedback_textarea_'+upload_id).prop('disabled', false);
					jQuery("#wcuf_max_size_notice_"+upload_id).removeClass("wcuf_already_uploaded");
					jQuery("#wcuf_disclaimer_label_"+upload_id).removeClass("wcuf_already_uploaded");
					//jQuery("#wcuf_feedback_textarea_"+upload_id).removeClass("wcuf_already_uploaded");
					
					jQuery("#wcuf_upload_field_button_"+upload_id).removeClass("wcuf_already_uploaded");
					jQuery("#wcuf_upload_multiple_files_button_"+upload_id).removeClass("wcuf_already_uploaded");
					jQuery("#wcuf_file_name"+upload_id).removeClass("wcuf_already_uploaded");
					jQuery('#wcuf_upload_field_button_'+upload_id+', #wcuf_max_size_notice_'+upload_id+', #wcuf_feedback_textarea_'+upload_id+', #wcuf_upload_multiple_files_button_'+id).fadeIn(200); 
					check_which_multiple_files_upload_button_show();
					
					jQuery('#wcuf_file_name_'+upload_id).html("");
					jQuery('#wcuf_delete_button_box_'+upload_id).empty(); 
					jQuery('#wcuf_delete_button_box_'+upload_id).fadeIn(); 					
					jQuery('#wcuf_disclaimer_label_'+upload_id).fadeIn(); 					
					
					jQuery('#wcuf_deleting_box_'+upload_id).fadeOut(400);
					jQuery('#wcuf_upload_button').fadeIn(400);
				});
	return false;
}
function check_which_multiple_files_upload_button_show()
{
	jQuery('.wcuf_upload_multiple_files_button:not(".wcuf_already_uploaded")').each(function(index,elem)
	{
		var id = jQuery(this).data('id');
		if(typeof wcuf_multiple_files_queues !== 'undefined' && typeof wcuf_multiple_files_queues[id] !=='undefined' && wcuf_multiple_files_queues[id].length > 0)
			jQuery(this).fadeIn(500);
	});
}
function wcuf_upload_complete(id)//wcuf_append_file_delete
{
	var delete_id = 'wcufuploadedfile_'+id;
	jQuery('#wcuf_file_name_'+id).delay(320).fadeIn(300,function()
	{
		//Smooth scroll
		try{
			jQuery('html, body').animate({
				  scrollTop: jQuery('#wcuf_file_name_'+id).offset().top - 200 //#wcmca_address_form_container ?
				}, 500);
		}catch(error){}
	});
	jQuery('#wcuf_upload_status_box_'+id).delay(300).hide(500);
	
	wcuf_show_control_buttons();
	jQuery('#wcuf_delete_button_box_'+id).empty(); 
	jQuery('#wcuf_delete_button_box_'+id).append('<button data-temp="yes" class="button delete_button" data-id="'+delete_id+'" data-upload-id="'+id+'">'+wcuf_delete_file_msg+'</button>');
	jQuery('#wcuf_delete_button_box_'+id).on('click', wcuf_delete_temp_file);	
}
function wcuf_set_bar_background()
{
	jQuery('.wcuf_bar').css('background-color',wcuf_progressbar_color);
}
function wcuf_hide_control_buttons()
{
	
	jQuery('#wcuf_upload_button').fadeOut(0)
	jQuery('.wcuf_crop_container, .wcuf_disclaimer_label, .wcuf_upload_field_button, .wcuf_upload_multiple_files_button, .wcuf_max_size_notice, .delete_button, .wcuf_feedback_textarea').fadeOut(300);
	
}
function wcuf_show_control_buttons()
{
	jQuery('#wcuf_upload_button').fadeIn(200);
	jQuery('.wcuf_crop_container:not(".wcuf_already_uploaded"):not(".wcuf_not_to_be_showed"), .wcuf_disclaimer_label:not(".wcuf_already_uploaded"), .wcuf_upload_field_button:not(".wcuf_already_uploaded"), .wcuf_max_size_notice:not(".wcuf_already_uploaded"), .wcuf_feedback_textarea:not(".wcuf_already_uploaded"), .delete_button').fadeIn(500);
	jQuery('.wcuf_file_name:not(".wcuf_already_uploaded")').each(function(index, obj)
	{
		if(jQuery(obj).children().length > 0)
			jQuery(obj).fadeIn(500);
	});
	check_which_multiple_files_upload_button_show();
}
function wcuf_show_multiple_files_progress_area(id)
{
	jQuery('#wcuf_multiple_file_progress_container_'+id).fadeIn();
}
function wcuf_reset_loading_ui(id)
{
	wcuf_set_bar_background();;
	jQuery('#wcuf_file_name_'+id).html("");
	jQuery('.wcuf_file_name, wcuf_multiple_file_progress_container_'+id).fadeOut(0);	
	jQuery('#wcuf_bar_'+id+"#wcuf_multiple_file_bar_"+id).css('width', "0%");
	
	wcuf_hide_control_buttons();	
	jQuery("#wcuf_crop_container_"+id).addClass("wcuf_already_uploaded");
	jQuery("#wcuf_upload_field_button_"+id).addClass("wcuf_already_uploaded");
	jQuery("#wcuf_upload_multiple_files_button_"+id).addClass("wcuf_already_uploaded");
	jQuery("#wcuf_file_name"+id).addClass("wcuf_already_uploaded");
	jQuery("#wcuf_disclaimer_label_"+id).addClass("wcuf_already_uploaded");
	
	jQuery("#wcuf_max_size_notice_"+id).addClass("wcuf_already_uploaded");
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
	jQuery('#wcuf_status_'+id).html(wcuf_loading_msg);
	
}
function wcuf_save_all_uploads(evt)
{
	evt.preventDefault();
	evt.stopImmediatePropagation();
	
	//validation
	 var can_send = true;
	
	/*if(!wcuf_all_required_uploads_have_been_performed())
	{
		wcuf_show_popup_alert(wcuf_checkout_required_message); 
		return false;
	}*/
	
	if(typeof wcuf_multiple_files_queues !== 'undefined')
		for (var key in wcuf_multiple_files_queues) {
		  if (wcuf_multiple_files_queues[key].length != 0) {
			  wcuf_show_popup_alert(wcuf_multiple_uploads_error_message);
			  return false;
		  }
		}
	jQuery('.wcuf_file_input').each(function(index,elem)
	{
		var my_id = jQuery(this).data('id');
		if(jQuery(this).prop('required') && jQuery(this).val() == "")
		{
			can_send = false;
		}
	});
	if(!can_send)
	{
		wcuf_show_popup_alert(wcuf_upload_required_message)
		return;
	} 
	
	//UI
	jQuery('#wcuf_upload_button').fadeOut(200);	
	jQuery('#wcuf_file_uploads_container').fadeOut(200);
	jQuery('#wcuf_progress').delay(250).fadeIn();
	try{
			jQuery('html, body').animate({
				  scrollTop: jQuery('#wcuf_file_uploads_container').offset().top - 200 
				}, 500);
		}catch(error){}
	
	var formData = new FormData();
	formData.append('action', 'save_uploaded_files_on_order_detail_page');
	formData.append('order_id', wcuf_order_id);
	var random = Math.floor((Math.random() * 1000000) + 999);
	
	jQuery.ajax({
		url: wcuf_ajaxurl+"?nocache="+random,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) {
			//wcuf_show_popup_alert(data);
			window.location.reload(true);			
		},
		error: function (data) 
		{
			//console.log(data);
			window.location.reload(true);		
			//wcuf_show_popup_alert("Error: "+data);
		},
		cache: false,
		contentType: false,
		processData: false
	});
	return false;
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
	if(max_image_width == 0 &&  max_image_height  == 0 &&  min_image_width  == 0 &&  min_image_height  == 0 && min_image_dpi == 0 && max_image_dpi == 0)
		//wcuf_backgroud_file_upload(evt);
		wcuf_check_if_show_cropping_area(evt)
	else
		wcuf_check_image_file_width_and_height(files,evt,wcuf_result_on_files_info, max_image_width, max_image_height, min_image_width, min_image_height, min_image_dpi ,max_image_dpi, dimensions_logical_operator);
}
function wcuf_result_on_files_info(evt, error, img, data)
{
	if(!error)
	{
		wcuf_check_if_show_cropping_area(evt)
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
		
		//if(!data.exact_image_size)
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
		new wcuf_image_crop(evt, id,wcuf_backgroud_file_upload);
	}			
	else
		wcuf_backgroud_file_upload(evt);
}
function wcuf_all_required_uploads_have_been_performed() //not used
{
	var ok = true;
	
	jQuery('.wcuf_file_input').each(function(index,value)
	{
		var min_files = parseInt(jQuery(this).data('min-files'));
		var data_is_required = jQuery(this).data('required');
		
		//if(jQuery(this).prop('required') && jQuery(this).val() == '') //before incremental upload 
		if(data_is_required && min_files != 0 && jQuery(this).is(":visible"))
			ok =  false;
	});
	return ok;
}
function wcuf_backgroud_file_upload(evt)
{
	evt.preventDefault();
	evt.stopImmediatePropagation();
	var id =  jQuery(evt.currentTarget).data('id');
	var current_elem = jQuery('#wcuf_upload_field_'+id); //jQuery(evt.currentTarget)
	
	var size = current_elem.data('size');
	var min_size = current_elem.data('min-size');
	var file_wcuf_name = current_elem.attr('name');
	var file_wcuf_title = current_elem.data('title');
	var check_disclaimer = current_elem.data('disclaimer');
	var extension =  current_elem.val().replace(/^.*\./, '');
	var extension_accepted = current_elem.attr('accept');
	var file_wcuf_user_feedback = jQuery('#wcuf_feedback_textarea_'+id).val();
	var detect_pdf = current_elem.data('detect-pdf');
	
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
		else //in case the file (image) has been cropped
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
		jQuery(evt.currentTarget).val("");
		wcuf_show_popup_alert(wcuf_disclaimer_must_be_accepted_message);
		return false;
	}
	if(is_multiple)
	{
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
						jQuery('#wcuf_multiple_file_bar_'+id).css('width',  multiple_file_uploader.getProgress(e.loaded)+"%");
						jQuery('#wcuf_multiple_file_upload_percent_'+id).html( multiple_file_uploader.getProgress(e.loaded) + "%");	
					}
					
				}, false);
				xhr.upload.addEventListener("load",function(e)
				{
					//2
					//wcuf_upload_complete(id);
					//multiple_file_uploader.setAlreadyLoadedBytes(e.total);
					
				},false);
				// file received/failed
				xhr.onreadystatechange = function(e) {
					if (xhr.readyState == 4) 
					{
						//1
						if(xhr.responseText === '0' || xhr.responseText === '1')
						{
							jQuery('#wcuf_status_'+id).html(wcuf_file_sizes_error);
						}
						//3
						else if(xhr.status == 200)
						{
							if(multiple_file_uploader.continueUploading() == false)
							{
								jQuery('#wcuf_status_'+id).html(xhr.status == 200 ? wcuf_success_msg : wcuf_failure_msg);
								wcuf_upload_complete(id);
							}
						}
					}
				};*/

				/* var formData = new FormData();
				xhr.open("POST", wcuf_ajaxurl, true);
				formData.append('action', wcuf_ajax_action); //'upload_file_during_checkout_or_product_page'
				formData.append('title', file_wcuf_title);
				formData.append('user_feedback', file_wcuf_user_feedback);
				formData.append('order_id', wcuf_order_id); */
				var formData = {'action': wcuf_ajax_action,
							    'title': file_wcuf_title,
								'detect_pdf': detect_pdf,
								'user_feedback': file_wcuf_user_feedback,
								'order_id': wcuf_order_id,
								'wcuf_wpml_language': wcuf_wpml_language
							};
				
				var multiple_file_uploader = new WCUFMultipleFileUploader({/* xhr: xhr, */ upload_field_id:id, form_data: formData, files: files, file: file, file_name:file_wcuf_name});
				document.addEventListener('onWCUFMultipleFileUploaderComplete', function(){wcuf_upload_complete(id);});
				
				if(files.length == 1)
				{
					var tempfile_name  = wcuf_replace_bad_char(file.name);
					/*var quantity = typeof file.quantity !== 'undefined' ? file.quantity : 1;
					formData.append(file_wcuf_name, file, tempfile_name); //file id used as key
					formData.append('multiple', 'no');
					formData.append('quantity_0', quantity);*/
					jQuery('#wcuf_file_name_'+id).html("<ol><li>"+tempfile_name+"</li><ol>");
				}
				else
				{
					var file_list = "<ol>";
					jQuery('#wcuf_file_name_'+id).html("");
					//formData.append('multiple', 'yes');
					for(var i = 0; i < files.length; i++)
					{
						var tempfile_name  = wcuf_replace_bad_char(files[i].name);
						/*var quantity = typeof files[i].quantity !== 'undefined' ? files[i].quantity : 1;
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
						//jQuery('#wcuf_file_name_'+id).append(tempfile_name+"<br>");
						*/
						file_list += "<li>"+tempfile_name+"</li>";
					}
					file_list += "</ol>";
					jQuery('#wcuf_file_name_'+id).html(file_list)
				}
				if(typeof wcuf_multiple_files_queues !== 'undefined' && typeof wcuf_multiple_files_queues[id] !== 'undefined')
					wcuf_multiple_files_queues[id] = new Array();
				/* try{
					setTimeout(function(){xhr.send(formData)},600);
				}catch(e){wcuf_show_popup_alert(e)} */
				multiple_file_uploader.continueUploading();

			}	
			else
			{
				wcuf_display_file_size_or_ext_error(file, size, extension_accepted, min_size, size);
			}
}
function wcuf_replace_bad_char(text)
{
	text = text.replace(/'/g,"");
	text = text.replace(/"/g,"");
	text = text.replace(/ /g,"_");
	return text;
}
function wcuf_show_popup_alert(text)
{
	jQuery('#wcuf_alert_popup_content').html(text);
	jQuery('#wcuf_show_popup_button').trigger('click');
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
	if (max_num != 0 && /* files.length */ all_files_quantity_sum > max_num)
	{
		wcuf_show_popup_alert(wcuf_file_num_error+max_num);
		error = true;
	}
	else if(min_num != 0 && /* files.length */ all_files_quantity_sum < min_num)
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
				
				msg += "<br/>"; 
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