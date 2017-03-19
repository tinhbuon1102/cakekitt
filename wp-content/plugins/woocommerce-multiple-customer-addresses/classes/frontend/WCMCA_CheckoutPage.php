<?php 
class WCMCA_CheckoutPage
{
	public function __construct()
	{
		add_action('wp_footer', array( &$this,'add_custom_css'),99);
		add_action('woocommerce_before_checkout_form', array(&$this, 'add_popup_html'));
		add_action('woocommerce_before_checkout_billing_form', array(&$this, 'add_billing_address_select_menu'));
		add_action('woocommerce_before_checkout_shipping_form', array(&$this, 'add_shipping_address_select_menu'));
		
		add_action('woocommerce_checkout_update_order_meta', array( &$this, 'save_checkout_extra_field' ));
	}
	public function add_custom_css()
	{	
		global $wcmca_html_helper;
		if(@is_checkout())
			$wcmca_html_helper->render_custom_css('my_account_page');
	}
	function add_popup_html($checkout)
	{
		global $wcmca_html_helper,$wcmca_address_model;
		
		if(!get_current_user_id())
			return;
		
		$wcmca_html_helper->render_address_form_popup();
		$wcmca_html_helper->render_custom_css('checkout_page');
	}
	function add_billing_address_select_menu($checkout)
	{
		global $wcmca_html_helper,$wcmca_address_model,$wcmca_option_model;
		
		//wcmca_var_dump($checkout);
		//wcmca_var_dump(get_user_meta( get_current_user_id()));
		//wcmca_var_dump($wcmca_address_model->get_address_by_id('def_billing'));
		
		if(!get_current_user_id())
			return;
		$wcmca_html_helper->render_address_select_menu();
	}
	function add_shipping_address_select_menu($checkout)
	{
		global $wcmca_html_helper,$wcmca_option_model;
		
		if(!get_current_user_id())
			return;
		$wcmca_html_helper->render_address_select_menu('shipping');
	}
	public function save_checkout_extra_field($order_id)
	{
		global $wcmca_option_model, $wcev_order_model;
		if(!$wcmca_option_model->is_vat_identification_number_enabled())
			return;
		
		if(!isset($wcev_order_model) && isset($_POST['billing_vat_number']))
			update_post_meta($order_id,'billing_vat_number',$_POST['billing_vat_number']);
	}
}
?>