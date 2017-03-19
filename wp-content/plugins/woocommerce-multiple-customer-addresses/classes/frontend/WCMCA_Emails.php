<?php 
class WCMCA_Emails
{
	public function __construct()
	{
		//Emails
		add_action('woocommerce_email_customer_details', array( &$this, 'woocommerce_include_extra_fields_in_emails' ), 11, 3);
	}
	public function woocommerce_include_extra_fields_in_emails( $order, $sent_to_admin = false, $plain_text = false)
	{
		global $wcmca_option_model, $wcmca_order_model, $wcev_order_model;
		$billing_vat_number = $wcmca_order_model->get_vat_meta_field($order->id);
		if(isset($wcev_order_model) || !$wcmca_option_model->is_vat_identification_number_enabled())
			return;
		?>
		<ul>
		 <li><strong><?php _e( 'VAT Identification Number', 'woocommerce-multiple-customer-addresses' ); ?>:</strong> 
			 <span class="text"><?php echo $billing_vat_number; ?></span></li>
		</ul>
		<?php 
	}
}
?>