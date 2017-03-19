<?php 
class WCMCA_Order
{
	public function __construct()
	{
	}
	public function get_vat_meta_field($order_id)
	{
		global $wcev_order_model;
		$billing_vat_number = /* isset($wcev_order_model) ? $wcev_order_model->get_vat_number($order_id) : */ get_post_meta($order_id, 'billing_vat_number',true);
	
		
		$billing_vat_number = $billing_vat_number ? $billing_vat_number : "";
		return $billing_vat_number;
	}
}
?>