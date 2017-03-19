<?php 
class WCMCA_MyAccountPage
{
	var $addresses_list_already_rendered = false;
	public function __construct()
	{
		add_action('wp_footer', array( &$this,'add_custom_css'),99);
		$theme_version = wcmca_get_file_version( get_template_directory() . '/woocommerce/myaccount/my-account.php' );
		try{
			$wc_version = wcmca_get_woo_version_number();
		}catch(Exception $e){}
		
		if(!isset($wc_version) || version_compare($wc_version , 2.6, '<') || version_compare($theme_version , 2.6, '<') )
			add_action( 'woocommerce_after_my_account', array( &$this,'add_additional_addresses_list'), 20 );
		add_action( 'woocommerce_my_account_my_address_title', array( &$this,'change_my_addresses_list_title') );
		
		//Wc 2.6
		add_action( 'woocommerce_my_account_my_address_description', array( &$this,'change_my_addresses_list_title') );
		if(isset($wc_version) && version_compare($wc_version , 2.6, '>=') )
			add_action( 'woocommerce_account_content', array( &$this,'add_additional_addresses_list'),99 );
	}
	public function add_custom_css()
	{		
		global $wcmca_html_helper;
		if(@is_account_page())
			$wcmca_html_helper->render_custom_css('my_account_page');
	}
	public function add_additional_addresses_list()
	{
		if($this->addresses_list_already_rendered)
        	return;
    	
    	$this->addresses_list_already_rendered = true;
		
		global $wcmca_html_helper, $wp, $wcmca_option_model;
		if ( did_action( 'woocommerce_account_content' ) ) 
		{
			//wcmca_var_dump($wp->query_vars);
			foreach ( $wp->query_vars as $key => $value ) 
			{
				if($key == 'edit-address' && $value == "")
				{
					$wcmca_html_helper->render_addresses_list();			
				}
				/* if ( has_action( 'woocommerce_account_' . $key . '_endpoint' ) ) 
				{
					wcmca_var_dump('woocommerce_account_' . $key . '_endpoint' );
				} */
			} 
		}
		else if(did_action('woocommerce_after_my_account')) //WC < 2.6
		{
			$wcmca_html_helper->render_addresses_list();
		}
		
	}
	public function change_my_addresses_list_title($title)
	{
		return __( 'Latest Used Addresses', 'woocommerce-multiple-customer-addresses' );
	}
}
?>