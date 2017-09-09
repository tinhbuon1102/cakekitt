var wcuf_multiple_files_queues = new Array();
jQuery(document).on('click', '.wcuf_delete_single_file_in_multiple_list', wcuf_delete_single_file_in_multiple_list);
jQuery(document).on('change', '.wcuf_quantity_per_file_input', wcuf_set_quantity_per_file);

function wcuf_manage_multiple_file_browse(evt)
{
	var id =  jQuery(evt.currentTarget).data('id'); 
	var disable_image_preview =  jQuery(evt.currentTarget).data('images-preview-disabled'); 
	var detect_pdf =  jQuery(evt.currentTarget).data('detect-pdf'); 
	var options = {'disable_image_preview':disable_image_preview, 'detect_pdf':detect_pdf};
	var files = evt.target.files;
	
	if(typeof wcuf_multiple_files_queues[id] === 'undefined')
		wcuf_multiple_files_queues[id] = new Array();
	
	//jQuery('button.button#wcuf_upload_multiple_files_button_'+id).fadeIn();
	jQuery('button.button#wcuf_upload_multiple_files_button_'+id).css('display', 'inline-block');
	
	for( var i = 0; i < files.length; i++)
	{
		files[i].quantity = 1;
		wcuf_multiple_files_queues[id].push(files[i]);
		wcuf_append_new_file_ui(id,files[i], options);
	}
	
	//console.log(wcuf_multiple_files_queues);
}
//the id is not relative to the file but to the upload field unique id
function wcuf_append_new_file_ui(id, file, options)
{
	var manage_pdf = options.detect_pdf && wcuf_is_pdf_file(file);
	var is_quantity_per_file_box_visible = !wcuf_enable_select_quantity_per_file || (manage_pdf) ? 'style="display:none"' : '';
	var template = '<div class="wcuf_single_file_in_multiple_list" >';
		template +=  '<h4>'+wcuf_multiple_file_list_tile+'</h4>';
		template +=  '<div class="wcuf_single_file_name_container" >';
		template +=    '<span class="wcuf_single_file_name_in_multiple_list">'+file.name+'</span>';
		template +=    '<i data-id="'+id+'" class="wcuf_delete_single_file_in_multiple_list wcuf_delete_file_icon"></i>';
		template +=   '</div>';
		template +=   '<div class="wcuf_quantity_per_file_container" >';
		template +=     '<div class="wcuf_media_preview_container"><img width="50" class="wcuf_image_quantity_preview"></img></div>';
		template +=     '<span class="wcuf_quantity_per_file_label" '+is_quantity_per_file_box_visible+' >'+wcuf_quantity_per_file_label+'</span>';
		template +=     '<input type="number" min="1" data-id="'+id+'" class="wcuf_quantity_per_file_input" value="1" '+is_quantity_per_file_box_visible+'></input>';
		template +=   '</div>';
	template += '</div>';
	
	var elem = jQuery('#wcuf_file_name_'+id).append(template);
	jQuery('#wcuf_file_name_'+id).show();
	if(options.disable_image_preview == false)
		wcuf_readURL(file, jQuery('.wcuf_media_preview_container').last());
}
function wcuf_is_pdf_file(file) 
{
	var allowed_fileTypes = ['pdf']; 
	var extension = file.name.split('.').pop().toLowerCase();
	return allowed_fileTypes.indexOf(extension) > -1;
}
function wcuf_readURL(file, container) 
{
	var reader = new FileReader();
	var allowed_fileTypes = ['jpg', 'jpeg', 'png'/* , 'bmp' */];  
	
	 var extension = file.name.split('.').pop().toLowerCase(), 
         isSuccess = allowed_fileTypes.indexOf(extension) > -1 || file.type.match('audio.*');
		
	var is_audio = file.type.match('audio.*');	
	if(!isSuccess)
	{
		container.remove();
		return;
	}
    wcuf_setImage(file,container);
	/* reader.onload = function (e) 
	{
		if(!is_audio)
			container.find('.wcuf_image_quantity_preview').attr('src', e.target.result);
		 //else 
		 //	container.html('<audio class="wcuf_audio_control" controls><source src="', e.target.result,'   "type="audio/ogg"><source src="', e.target.result,' "type="audio/mpeg"></audio>');
		
	}
	reader.readAsDataURL(file); */
}
function wcuf_setImage(file, container) 
{
    //var file = this.files[0];
    var URL = window.URL || window.webkitURL;
    if (URL.createObjectURL && (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/gif" /* || file.type == "image/bmp" */ )) 
	{
        //document.getElementById('uploadingImg').src = URL.createObjectURL(file);
		container.find('.wcuf_image_quantity_preview').attr('src', URL.createObjectURL(file));
    } else {
        container.find('.wcuf_image_quantity_preview').remove();
    }
}
function wcuf_get_field_index(elem)
{
	return elem.parent().parent().index(); 
}
function wcuf_delete_single_file_in_multiple_list(evt)
{
	//Files have not an unique id. To remove the html list index is found and then is used to splice the array
	var id =  jQuery(evt.currentTarget).data('id'); 
	//var index =  jQuery(evt.currentTarget).parent().parent().index(); 
	var index =  wcuf_get_field_index(jQuery(evt.currentTarget)); 
	jQuery('.wcuf_single_file_in_multiple_list:nth-child('+(index+1)+')').remove();
	wcuf_multiple_files_queues[id].splice(index, 1);
	
	if(wcuf_multiple_files_queues[id].length == 0)
	{
		jQuery('button.button#wcuf_upload_multiple_files_button_'+id).fadeOut();
		jQuery('#wcuf_file_name_'+id).hide();
	}
}
function wcuf_set_quantity_per_file(evt)
{
	var index =  wcuf_get_field_index(jQuery(evt.currentTarget)); 
	var value = jQuery(evt.currentTarget).val();
	var id = jQuery(evt.currentTarget).data('id'); 
	
	value = value < 1 ? 1 : value;
	jQuery(evt.currentTarget).val(value);
	wcuf_multiple_files_queues[id][index].quantity = value;
}