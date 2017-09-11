<?php 
class WCUF_Text
{
	var $text_cache;
	public function __construct()
	{
	}
	public function get_cart_identifier_prefix()
	{
		$result = get_field('wcuf_cart_individual_item_identifier', 'option'); 	
		$result = $result != null ? $result : __("#","woocommerce-files-upload"); 	
		
		return $result;
	}
	public function get_button_texts()
	{
		if($this->text_cache != null)
			return $this->text_cache;
		
		$bad_chars = array('"', "'");
		
		$all_data['browse_button'] = get_field('wcuf_browse_button', 'option'); 		
		$all_data['browse_button'] = $all_data['browse_button'] != null ? $all_data['browse_button'] : __("Browse...","woocommerce-files-upload"); 		
		
		$all_data['add_files_button'] = get_field('wcuf_add_files_button', 'option'); 
		$all_data['add_files_button'] = $all_data['add_files_button'] != null ? $all_data['add_files_button'] : __("Add files","woocommerce-files-upload"); 
		
		$all_data['upload_selected_files_button'] = get_field('wcuf_upload_selected_files_button', 'option'); 
		$all_data['upload_selected_files_button'] = $all_data['upload_selected_files_button'] != null ? $all_data['upload_selected_files_button'] : __("Upload selected files","woocommerce-files-upload"); 
		
		$all_data['delete_file_button'] = get_field('wcuf_delete_file_button', 'option'); 
		$all_data['delete_file_button'] = $all_data['delete_file_button'] != null ? $all_data['delete_file_button'] : __("Delete uploaded file(s)","woocommerce-files-upload"); 
// 		pr($all_data['delete_file_button']);die;
		$all_data['crop_and_upload_button'] = get_field('wcuf_crop_and_upload_button', 'option'); 
		$all_data['crop_and_upload_button'] = $all_data['crop_and_upload_button'] != null ? $all_data['crop_and_upload_button'] : __("Crop & Upload","woocommerce-files-upload"); 
		
		$all_data['zoom_in_crop_button'] = get_field('wcuf_zoom_in_crop_button', 'option'); 
		$all_data['zoom_in_crop_button'] = $all_data['zoom_in_crop_button'] != null ? $all_data['zoom_in_crop_button'] : "+"; 
		
		$all_data['zoom_out_crop_button'] = get_field('wcuf_zoom_out_crop_button', 'option'); 
		$all_data['zoom_out_crop_button'] = $all_data['zoom_out_crop_button'] != null ? $all_data['zoom_out_crop_button'] : "-"; 
		
		$all_data['rotate_left_button'] = get_field('wcuf_rotate_left_button', 'option'); 
		$all_data['rotate_left_button'] = $all_data['rotate_left_button'] != null ? $all_data['rotate_left_button'] : __("Rotate left", "woocommerce-files-upload"); 
		
		$all_data['rotate_right_button'] = get_field('wcuf_rotate_right_button', 'option'); 
		$all_data['rotate_right_button'] = $all_data['rotate_right_button'] != null ? $all_data['rotate_right_button'] : __("Rotate right","woocommerce-files-upload"); 
		
		$all_data['save_uploads_button'] = get_field('wcuf_save_uploads_button', 'option') ; 
		$all_data['save_uploads_button'] = $all_data['save_uploads_button']  != null ? $all_data['save_uploads_button']  : __('Save upload(s)', 'woocommerce-files-upload'); 
		
		$all_data['select_quantity_label'] = get_field('wcuf_select_quantity_label', 'option') ; 
		$all_data['select_quantity_label'] = $all_data['select_quantity_label'] != null ? $all_data['select_quantity_label'] : __('Select quantity:', 'woocommerce-files-upload'); 
		
		$all_data['incomplete_files_upload_message'] = get_field('wcuf_incomplete_files_upload_message', 'option'); 
		$all_data['incomplete_files_upload_message'] = $all_data['incomplete_files_upload_message']  != null ? $all_data['incomplete_files_upload_message'] : __("Your file upload is incomplete – click on the 'Upload selected files' button or remove the file(s)", 'woocommerce-files-upload'); 
		
		$all_data['required_upload_add_to_cart_warning_message'] = get_field('wcuf_required_upload_add_to_cart_warning_message', 'option'); 
		$all_data['required_upload_add_to_cart_warning_message'] = $all_data['required_upload_add_to_cart_warning_message']  != null ? $all_data['required_upload_add_to_cart_warning_message'] : __("Please upload all the required file(s). Once done you will be able to add the product to cart.", 'woocommerce-files-upload'); 
		
		$all_data['order_page_save_uploaded_files_title'] = get_field('wcuf_order_page_save_uploaded_files_title', 'option'); 
		$all_data['order_page_save_uploaded_files_title'] = $all_data['order_page_save_uploaded_files_title']  != null ? $all_data['order_page_save_uploaded_files_title'] : __("When ready to save the uploaded file(s), click on the following button:", 'woocommerce-files-upload'); 
		
		$all_data['cart_individual_item_identifier'] = get_field('wcuf_cart_individual_item_identifier', 'option'); 	
		$all_data['cart_individual_item_identifier'] = $all_data['cart_individual_item_identifier'] != null ? $all_data['cart_individual_item_identifier'] : __("#","woocommerce-files-upload"); 	
		
		foreach($all_data as $key => $single_data)
				$all_data[$key] = str_replace($bad_chars, "", $single_data);
		
		$this->text_cache = $all_data;
		return $all_data;
	}
}
?>