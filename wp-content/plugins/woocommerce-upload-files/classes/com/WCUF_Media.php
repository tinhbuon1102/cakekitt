<?php 
class WCUF_Media
{
	function __construct()
	{
		//File proxy managment
		add_action('wp_loaded', array( &$this, 'get_image' ));
		add_action( 'wp_ajax_wcuf_rotate_image', array( &$this, 'ajax_rotate_image' ));
		add_action( 'wp_ajax_nopriv_wcuf_rotate_image', array( &$this, 'ajax_rotate_image' ));
	}
	function ajax_rotate_image()
	{
		//wcuf_var_dump($_FILES);
		$file = $_FILES["image"]['tmp_name'];
		$degrees =  isset($_POST['direction']) && $_POST['direction'] == 'right' ? -90 : +90;

		// Content type
		//header('Content-type: image/jpeg');

		// Load
		$source = imagecreatefromjpeg($file);

		// Rotate
		$rotate = imagerotate($source, $degrees, 0);

		// Output
		ob_start();
		imagejpeg($rotate);
		$data = ob_get_contents();
		ob_end_clean();
		echo 'data:image/jpeg;base64,' .base64_encode($data);
		
		//echo "<img src=$base64 />" ;
		// Free the memory
		imagedestroy($source);
		imagedestroy($rotate);
		wp_die();
	}
	public function get_media_preview_html($field_data, $file_name, $is_zip, $order_id, $counter)
	{
		global $wcuf_file_model,$wcuf_upload_field_model;
		$is_temp_upload = !isset($field_data['absolute_path']);
		//new multiple file managment
		$index = $is_temp_upload  ? "tmp_name" : "absolute_path";
		$file_full_path = is_array($field_data[$index]) ? $field_data[$index][$counter] : $field_data[$index];
		
		//New folder organization: files are stored in "product_id-variation_id" folder
		$product_ids = isset($field_data["id"]) ? explode("-",$field_data["id"]) : null;
		$product_id_folder_name = "";
		if(isset($product_ids) && isset($product_ids[1]))
		{
			$upload_field_ids = isset($product_ids[2]) ? $product_ids[1]."-".$product_ids[2] : $product_ids[1]."-0";
			$upload_field_ids = isset($product_ids[3]) ? $upload_field_ids."-".$wcuf_upload_field_model->get_individual_id_from_string($product_ids[3]) : $upload_field_ids; //sold as individual id
			$product_id_folder_name = "&wcuf_product_folder_name=".$upload_field_ids;
		}
		
		global $wcuf_option_model; 
		$all_options = $wcuf_option_model->get_all_options();
		$image_preview_width = $all_options['image_preview_width'];
		$image_preview_height = $all_options['image_preview_height'];
		$image = false;
		$file_name_real_name = basename($file_full_path);
		
		//DropBox managment
		/* if(WCUF_DropBox::is_dropbox_file_path($file_full_path) != false)
		{
			$dropbox = new WCUF_DropBox();
			$file_full_path = $dropbox->getTemporaryLink($file_full_path, true);
		} */
		
		if($is_zip) //old zip managment method
		{
			if(class_exists('ZipArchive'))
			{
				$z = new ZipArchive();
				if ($z->open($file_full_path) && $z->filename != "") 
				{
					$im_string = $z->getFromName($file_name);
					$image = @imagecreatefromstring($im_string);
					$z->close();
				}
			}
			else return "";
			
			if($image === false)
				return "";
		}
		else		
		{
			$image_data = @getimagesize($file_full_path);
			$image = @is_array($image_data) ? true : false;
			
		}
		
		//no bmp preview
		if($image && isset($image_data) &&  $image_data['mime'] == 'image/x-ms-bmp')
			return ""; 
		$is_dropbox = WCUF_DropBox::is_dropbox_file_path($file_full_path);
		//$is_zip = $is_zip ? "true": "false";
		//return $image !== false ? '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.get_site_url().'?wcuf_file_name='.$file_name_real_name.'&wcuf_image_name='.$file_name.'&wcuf_is_zip='.$is_zip.'&wcuf_order_id='.$order_id.'"></img>' : "";
		if($is_zip && $image !== false)
		{
			$is_zip = $is_zip ? "true": "false";
			
			//DropBox managment
			if($is_dropbox)
				return "";
		
			return '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.get_site_url().'?wcuf_file_name='.$file_name_real_name.'&wcuf_image_name='.$file_name.'&wcuf_is_zip='.$is_zip.'&wcuf_order_id='.$order_id.'"></img>';
		}
		elseif(!$is_zip)
		{
			$file_name = $is_temp_upload  ? $field_data['file_temp_name'][$counter] : $file_name ;
			$temp_dir = $wcuf_file_model->get_temp_dir_path($order_id, true);
			$url = isset($field_data['url'])? $field_data['url'][$counter] : $temp_dir.$file_name;
			$is_remote_image = /* !isset($field_data["ID3_info"][$counter]["index"]) */ $is_dropbox && $this->is_image($url);
			
			
			//Old preview: was valid for local and remote. Local file preview was not compressed and this can cause some problems
			/* if($image  || $is_remote_image)
				return '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.$url .'"></img>'; */
			
			//New method: local files preview is compressed
			if($image || $is_remote_image)
			{
				if(!$is_remote_image)
				{
					//compressed
					$file_name_real_name = $order_id != 0 ? $file_name : $file_name_real_name; //$file_name_real_name contains the full path when the $order_id is not 0
					return '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.get_site_url().'?wcuf_file_name='.$file_name_real_name.'&wcuf_image_name='.$file_name.'&wcuf_is_zip=false&wcuf_order_id='.$order_id.$product_id_folder_name.'"></img>';
				}
				elseif($is_dropbox)
				{
					//return '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.$url .'"></img>';
					return '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.get_site_url().'?wcuf_file_name='.$file_full_path.'&wcuf_image_name='.$file_name.'&wcuf_is_zip=false&wcuf_order_id='.$order_id.'"></img>'; //'&full_url='.$url.'
				}
			}
			//end new compressed method
			elseif(isset($field_data["ID3_info"][$counter]["index"]) && $field_data["ID3_info"][$counter]['type'] == 'audio' /* $this->is_audio_file($file_full_path) */ )
			{
				//$url = !isset($field_data['file_temp_name'][$counter])  ? $field_data['url'][$counter] : $temp_dir.$field_data['file_temp_name'][$counter];
				$url = isset($field_data['url'][$counter])  ? $field_data['url'][$counter] : $temp_dir.$field_data['file_temp_name'][$counter];
				return '<audio class="wcuf_audio_control" controls><source src="'.$url.'   "type="audio/ogg"><source src="'.$url.' "type="audio/mpeg"></audio>';
			}
		}
		
		return "";
	}
	//Used in FRONTEND by links generated by get_media_preview_html() method. In case of Dropbox that method doesn't not generate any preview link
	public function get_image()
	{
		global $wcuf_file_model;
		if(!isset($_GET['wcuf_file_name']) || !isset($_GET['wcuf_image_name']) || !isset($_GET['wcuf_is_zip']))
			return;
		
		$temp_dir = $wcuf_file_model->get_temp_dir_path(isset($_GET['wcuf_order_id']) ? $_GET['wcuf_order_id'] : null);
		
		//DropBox managment
		if(WCUF_DropBox::is_dropbox_file_path($_GET['wcuf_file_name']))
		{
			try
			{
				$dropbox = new WCUF_DropBox();
				$dropbox->render_thumb($_GET['wcuf_file_name']);
			}catch(Exception $e){ /* wcuf_var_dump($e); */ _e('DropBox account unlinked', 'woocommerce-files-upload'); /* wp_redirect($_GET['full_url']); */}
		}
		elseif($_GET['wcuf_is_zip'] === "true")
		{
			if(class_exists('ZipArchive'))
			{
				$z = new ZipArchive();
				if ($z->open($temp_dir.$_GET['wcuf_file_name'])) 
				{
					$im_string = $z->getFromName($_GET['wcuf_image_name']);
					//$type = $this->image_file_type_from_binary($im_string);
					$im = imagecreatefromstring($im_string);
					
					//original
					/* header('Content-Type: image/png');
					imagepng($im, null, 9);  */
					
					header('Content-Type: image/jpeg'); 
					$image_result = imagejpeg($im, null,50); 
					
					//Working alternative
					/* switch($type)
					{
							case "image/jpeg":
								header('Content-Type: image/jpeg');
								imagejpeg($im, null,50);
								break;
							case "image/gif":
								header('Content-Type: image/gif');
								imagegif($im);
								break;
							case "image/png":
								header('Content-Type: image/png');
								imagepng($im,null, 9);
								break;
							 //case "image/x-ms-bmp":
								//$im = imagecreatefromwbmp($path); //png file
								//break; 
							default: 
								$im=false;
								break;
					}  */
			
					imagedestroy($im);
					$z->close();
				
				}
			}
		}
		else
		{
			
			$path = isset($_GET['wcuf_product_folder_name']) && isset($_GET['wcuf_order_id']) ? $temp_dir.$_GET['wcuf_product_folder_name']."/".$_GET['wcuf_file_name']: $temp_dir.$_GET['wcuf_file_name'];
			//wcuf_var_dump($path);
			$fileName = basename($path);
			//New
			if(!file_exists($path))
			{
				_e('Invalid image path', 'woocommerce-files-upload');
				wp_die();
			}
			$size = getimagesize($path);
			/* wcuf_var_dump($size["mime"]);
			wp_die(); */
			switch($size["mime"])
			{
					default: 
					case "image/jpeg":
						header('Content-Type: image/jpeg');
						$im = imagecreatefromjpeg($path); //jpeg file
						imagejpeg($im, null,10); //was 50
						imagedestroy($im);
						break;
					case "image/gif":
						header('Content-Type: image/gif');
						$im = imagecreatefromgif($path); //gif file
						imagegif($im);
						imagedestroy($im);
						break;
					case "image/png":
						header('Content-Type: image/png');
						$im = imagecreatefrompng($path); //png file
						imagepng($im,null, 9);
						imagedestroy($im);
						break;
					 case "image/x-ms-bmp": //doesn't work
						header('Content-Type: image/bmp');
						$im = imagecreatefromwbmp($path); //bmp file
						imagewbmp($im);
						imagedestroy($im);
						break; 
					/* default: 
						$im=false;
						break; */
			} 
			
			//Old
			/* $size = filesize($path);
			$metadata = getimagesize($path);
			$file_type = $metadata["mime"];
			header("Content-length: ".$size);
			//header("Content-type: application/octet-stream");
			header("Content-type: ".$file_type);
			header("Content-disposition: attachment; filename=".$fileName.";" );
			
			//header('Content-Transfer-Encoding: binary');
			header('Content-Transfer-Encoding: chunked');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			//header("Content-Type: application/download");
			header('Content-Description: File Transfer');
			//header('Content-Type: application/force-download');
			//echo $content;
			if ($fd = fopen ($path, "r")) 
			{

					set_time_limit(0);
					ini_set('memory_limit', '1024M');
				
				if (ob_get_contents()) ob_clean();
				while(!feof($fd)) {
					echo fread($fd, 4096);
				}   
				flush();
				ob_end_flush();
				try{
					fclose($fd);
				}catch(Exception $e){}
			} */
		}
		die();
	}
	
	private function image_file_type_from_binary($im_string) {
		$type = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $im_string);
		return $type;
	}
	public function is_pdf($is_image)
	{
		if(strpos(strtolower($file_name), '.pdf'))
		   return true;
		   
		return false;
	}
	private function is_image($file_name)
	{
		if(strpos(strtolower($file_name), '.jpg')  ||
		   strpos(strtolower($file_name), '.jpeg') ||
		   strpos(strtolower($file_name), '.png'))
		   return true;
		   
		return false;
	}
	private function is_audio_file($tmp)
	{
		$allowed = array(
        'audio/mpeg', 'audio/x-mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 'audio/aiff', 
        'audio/mid', 'audio/x-aiff', 'audio/x-mpequrl','audio/midi', 'audio/x-mid', 
        'audio/x-midi','audio/wav','audio/x-wav','audio/xm','audio/x-aac','audio/basic',
        'audio/flac','audio/mp4','audio/x-matroska','audio/ogg','audio/s3m','audio/x-ms-wax',
        'audio/xm'
		);
		
		// check REAL MIME type
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $tmp );
		finfo_close($finfo);
		
		// check to see if REAL MIME type is inside $allowed array
		if( in_array($type, $allowed) ) {
			return true;
		} else {
			return false;
		}

	}
	public function pdf_count_pages($pdfname) 
	{
	  $pdftext = file_get_contents($pdfname);
	  $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
	  return $num;
	}
}
?>