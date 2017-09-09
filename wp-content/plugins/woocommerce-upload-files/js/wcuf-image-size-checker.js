	
function wcuf_check_image_file_width_and_height(files, evt, callback, max_image_width, max_image_height,min_image_width, min_image_height, min_image_dpi ,max_image_dpi,dimensions_logical_operator)
{
	var wcuf_imgldd_current_file_loaded = 0;
    var wcuf_imgldd_error = false;
	var sizes_obj = {'max_image_width': max_image_width, 
					 'max_image_height':max_image_height, 
					 'min_image_height':min_image_height, 
					 'min_image_width': min_image_width, 
					 'min_dpi': min_image_dpi, 
					 'max_dpi': max_image_dpi,
					 'dimensions_logical_operator': dimensions_logical_operator};
			
	for(var i = 0; i< files.length; i++)
	{
		var loadingImage = loadImage(
			files[i],
			function(img, meta){wcuf_image_loaded(img, meta, files, evt, callback, sizes_obj); },
			{
				meta:true
			}
		);
		//loadingImage.filename = files[i].name;
		//loadImage.parseMetaData(files[i],function(meta){wcuf_check_image_dpi(meta, evt, callback, max_image_width, max_image_height,min_image_width, min_image_height); });
	}

	function wcuf_image_loaded(img, metadata, files, evt, callback, sizes_obj) 
	{
		if(img.type === "error") 
		{
			if(wcuf_imgldd_error)
				return false;
			
			wcuf_imgldd_current_file_loaded++;					
			if(max_image_width != 0 || max_image_height != 0)
				wcuf_imgldd_error = true;
			if(wcuf_imgldd_error == true || wcuf_imgldd_current_file_loaded == files.length )
			{
				callback(evt,wcuf_imgldd_error,this, sizes_obj);
			}
		} 
		else 
		{
			if(wcuf_imgldd_error)
				return false;
			
			if( (sizes_obj.min_dpi != 0 || sizes_obj.max_dpi != 0) && !wcuf_check_image_dpi(metadata, callback, evt, sizes_obj))
			{
				wcuf_imgldd_error = true;
				callback(evt, wcuf_imgldd_error, this, sizes_obj);
				return false;
			}
			
			wcuf_imgldd_current_file_loaded++;
			/* if( ((sizes_obj.max_image_width != 0 && img.width > sizes_obj.max_image_width) || (sizes_obj.max_image_height != 0 && img.height > sizes_obj.max_image_height)) ||
				((sizes_obj.min_image_width != 0 && img.width < min_image_width) || (sizes_obj.min_image_height != 0 && img.height < min_image_height)) ) */
			
			if(sizes_obj.dimensions_logical_operator == 'or')
			{	
				if( ((sizes_obj.max_image_width != 0 && img.width > sizes_obj.max_image_width) || (sizes_obj.min_image_width != 0 && img.width < min_image_width)) &&
					   ( (sizes_obj.max_image_height != 0 && img.height > sizes_obj.max_image_height) || (sizes_obj.min_image_height != 0 && img.height < min_image_height)) )	
					wcuf_imgldd_error = true;
			}
			else //original in AND
				if( ((sizes_obj.max_image_width != 0 && img.width > sizes_obj.max_image_width) || (sizes_obj.min_image_width != 0 && img.width < min_image_width)) ||
					   ( (sizes_obj.max_image_height != 0 && img.height > sizes_obj.max_image_height) || (sizes_obj.min_image_height != 0 && img.height < min_image_height)) )	
					wcuf_imgldd_error = true;
			
			if(wcuf_imgldd_error == true || wcuf_imgldd_current_file_loaded == files.length)
			{
				callback(evt,wcuf_imgldd_error, this, sizes_obj);
			}
		}
	}
	function wcuf_check_image_dpi(metadata, callback, evt, sizes_obj) 
	{
		if (!metadata.exif) 
		{ 
			return false;
		}

		//console.log(metadata.exif);
		var resX = metadata.exif.get('XResolution');
		var resY = metadata.exif.get('YResolution');
		var resUnit = metadata.exif.get('ResolutionUnit'); //2: inch
		if(/* (sizes_obj.min_image_dpi != 0 || sizes_obj.max_dpi != 0) &&  */
		    (resX != resY || resUnit != 2 || resX < sizes_obj.min_dpi || resX > sizes_obj.max_dpi)
		   )
		{
			return false;
		}
		return true;
	}
}