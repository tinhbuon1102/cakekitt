<?php 
class WCUF_Order
{
	public function __construct()
	{
	}
	public function get_order_id($order)
	{
		return version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();
	}
	public function get_order_status($order)
	{
		return version_compare( WC_VERSION, '2.7', '<' ) ? $order->status : $order->get_status();
	}
	public function get_billing_email($order)
	{
		return version_compare( WC_VERSION, '2.7', '<' ) ? $order->billing_email : $order->get_billing_email();
	}
	public function read_order_item_meta($item, $meta_key, $single = true)
	{
		$value = null;
		if(version_compare( WC_VERSION, '2.7', '<' ))
		{
			if(isset($item["item_meta"][$meta_key]))
				$value = $single ? $item["item_meta"][$meta_key][0] : $item["item_meta"][$meta_key];
			
		}
		else 
			$value = $item->get_meta($meta_key, $single);
		
		return $value;
	}
	public function get_sorted_order_items($order)
	{
		$items = $order->get_items();
		if(is_array($items))
		  usort($items, function($a, $b) {
			return $a['product_id'] - $b['product_id'];
		});
		return $items;
	}
	public function remove_single_file_form_order_uploaded_data($order_id, $field_id, $single_file_id)
	{
		global $wcuf_upload_field_model, $wcuf_session_model, $wcuf_file_model;
		$file_order_metadata = $wcuf_upload_field_model->get_uploaded_files_meta_data_by_order_id($order_id);
		//wcuf_var_dump($field_id);
		//wcuf_var_dump($file_order_metadata);
		if(!isset($file_order_metadata[$field_id]))
			return;
		
		//file delete
		$wcuf_file_model->delete_temp_file($file_order_metadata[$field_id]['absolute_path'][$single_file_id]);
		unset($file_order_metadata[$field_id]['absolute_path'][$single_file_id]);
		
		$result = $wcuf_session_model->remove_subitem_from_session_array($file_order_metadata[$field_id], $single_file_id);
		if($result == null)
			unset($file_order_metadata[$field_id]);
		else 
			$file_order_metadata[$field_id] = $result;
		
		//wcuf_var_dump($file_order_metadata);
		$wcuf_upload_field_model->save_uploaded_files_meta_data_to_order($order_id, $file_order_metadata);
	}
	public function is_selected_payment_method_allowed($order_or_payment_code, $allowed_gateways, $visibility_payment_gateway_policy)
	{
		//$gateways = new WC_Payment_Gateways();
		$selected_payment_method = is_object($order_or_payment_code) ? $order_or_payment_code->get_payment_method() : $order_or_payment_code;
		/* foreach($gateways->payment_gateways( ) as $gateway_code => $gateway)
		{
		} */
		if(($visibility_payment_gateway_policy == 'allow' && !array_key_exists ($selected_payment_method, $allowed_gateways)) || 
		   ($visibility_payment_gateway_policy == 'deny' && array_key_exists ($selected_payment_method, $allowed_gateways)))
		   return false;
		   
		return true;
	}
}
?>