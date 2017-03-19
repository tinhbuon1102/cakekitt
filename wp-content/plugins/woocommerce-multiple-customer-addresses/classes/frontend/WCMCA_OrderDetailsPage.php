<?php 
class WCMCA_OrderDetailsPage
{
	public function __construct()
	{
		add_action('woocommerce_order_details_after_customer_details', array(&$this, 'show_custom_fields'));
	}
	function show_custom_fields($order)
	{
		global $wcmca_option_model, $wcmca_order_model;
		$billing_vat_number = $wcmca_order_model->get_vat_meta_field($order->id);
		if(isset($wcev_order_model) || !$wcmca_option_model->is_vat_identification_number_enabled())
			return;
		?>
		<tr>
			<th><?php _e( 'VAT Identification Number:', 'woocommerce-multiple-customer-addresses' ); ?></th>
			<td><?php echo $billing_vat_number; ?></td>
		</tr>
		<?php 
	}
}
?>