<?php 
class WCUF_UploadField
{
	function __construct()
	{
	}
	public function get_individual_id_from_string($string)
	{
		return str_replace("idsai", "" ,$string); 
	}
	public function is_individual_id_string($string)
	{
		return strpos($string,"idsai") !== false;
	}
	public function get_uploaded_files_meta_data_by_order_id($order_id)
	{
		$result = get_post_meta($order_id, '_wcst_uploaded_files_meta', true); //old error
		$result2 = get_post_meta($order_id, '_wcuf_uploaded_files', true);
		/* $result = is_array($result) ? ksort($result) : array();
		$result2 = is_array($result2) ? ksort($result2) : array(); */
		
		if( (!$result || empty($result)) && (!$result2 || empty($result2)))
			return array();
		
		if(!$result || empty($result))		
			return !$result2 ? array() : $result2;
		
		if(!$result2 || empty($result2))		
			return !$result ? array() : $result;
		
		$final_result = array_merge($result, $result2); //impossible, on save the old _wcst_uploaded_files_meta is deleted;
		//ksort($final_result);
		return $final_result;
	}
	public function save_uploaded_files_meta_data_to_order($order_id, $file_order_metadata)
	{
		delete_post_meta( $order_id, '_wcst_uploaded_files_meta'); //old and wrong meta is deleted
		update_post_meta( $order_id, '_wcuf_uploaded_files', $file_order_metadata);
	}
	public function delete_uploaded_files_meta_data_by_order_id($order_id)
	{
		delete_post_meta( $order_id, '_wcst_uploaded_files_meta');
		delete_post_meta( $order_id, '_wcuf_uploaded_files');
	}
	public function get_num_uploaded_files($order_id, $upload_field_id = 'none', $max_uploaded_files_number_considered_as_sum_of_quantities = false)
	{
		$result = $this->get_uploaded_files_meta_data_by_order_id($order_id);
		$total = 0;
		//wcuf_var_dump($result);
		foreach((array)$result as $upload_field_id_key => $meta)
				if($upload_field_id == 'none' || $upload_field_id == $upload_field_id_key)
				{
					if($max_uploaded_files_number_considered_as_sum_of_quantities)
						foreach((array)$meta["quantity"] as $quantity)
							$total +=  intval($quantity);
					else
						$total += isset($meta['original_filename']) ? count($meta['original_filename']) : 0;
				}
		return $total;
	}
	public function get_num_uploaded_files_in_session($upload_field_id, $max_uploaded_files_number_considered_as_sum_of_quantities)
	{
		global $wcuf_session_model;
		$number = 0;
		$data = $wcuf_session_model->get_item_data($upload_field_id);
		if(!isset($data) || !isset($data["tmp_name"]))
			return $number;
		
		foreach((array)$data["quantity"] as $uploaded_files)
		{
			$number += $max_uploaded_files_number_considered_as_sum_of_quantities ? intval($uploaded_files) : 1;
		}
			
		return $number;
	}
	public function is_upload_field_content_managed_as_zip($file_meta) //old multiple files upload were managed as single zip file
	{
		return isset($meta['original_filename']) && is_array($file_meta['original_filename']) && !isset($file_meta['is_multiple_file_upload']) ? true : false;
	}
	public function is_dropbox_stored($file_meta)
	{
		return WCUF_DropBox::is_dropbox_file_path($file_meta['absolute_path']);
	}
	public function is_upload_field_content_managed_as_multiple_files($file_meta)
	{
		return isset($file_meta['original_filename']) && isset($file_meta['is_multiple_file_upload']) ? $file_meta['is_multiple_file_upload'] : false;
	}
	public function can_be_zip_file_created_upload_field_content($file_meta)
	{
		$result = array();
		$counter = 0;
		
		if(is_array($file_meta['source']) && class_exists('ZipArchive'))
			foreach($file_meta['source'] as $source)
			{
				if($source == 'local')
					$result[] = array('path' => $file_meta['absolute_path'][$counter], 'name' => $file_meta['original_filename'][$counter++]);
					
			}
		
		return $result;
	}
}
?>