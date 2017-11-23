<?php
/*
Plugin Name: WooCommerce Upload Files
Description: WCUF plugin allows your customers to attach files to their orders according to the purchased products.
Author: Lagudi Domenico
Version: 26.2
*/

/* 
Copyright: WooCommerce Upload Files uses the ACF PRO plugin. ACF PRO files are not to be used or distributed outside of the WooCommerce Upload Files plugin.
*/


//define('wcuf_PLUGIN_PATH', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('wcuf_PLUGIN_PATH', rtrim(plugin_dir_url(__FILE__), "/") ) ;
define('WCUF_PLUGIN_LANG_PATH', basename( dirname( __FILE__ ) ) . '/languages' ) ;
define('WCUF_PLUGIN_ABS_PATH', dirname( __FILE__ ) );


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
     (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))	
	)
{
	
	include_once( "classes/com/vendor/getid3/getid3.php"); 
	if (strpos($_GET['page'], 'acf-') !== false || strpos($_GET['page'], 'woocommerce-files-upload') !== false)
	{
		include_once( "classes/com/WCUF_Acf.php"); 
	}
	
	
	//com
	if(!class_exists('WCUF_Email'))
			require_once('classes/com/WCUF_Email.php');
	if(!class_exists('WCUF_DropBox'))
			require_once('classes/com/WCUF_DropBox.php');
	if(!class_exists('WCUF_UploadField'))
	{
			require_once('classes/com/WCUF_UploadField.php');
			$wcuf_upload_field_model = new WCUF_UploadField();
	} 
	if(!class_exists('WCUF_Html'))
	{
			require_once('classes/com/WCUF_Html.php');
			$wcuf_html_helper = new WCUF_Html();
	} 
	if(!class_exists('WCUF_DB'))
	{
			require_once('classes/com/WCUF_DB.php');
			$wcuf_db_model = new WCUF_DB();
	} 
	if(!class_exists('WCUF_File'))
	{
			require_once('classes/com/WCUF_File.php');
			$wcuf_file_model = new WCUF_File();
	} 
	if(!class_exists('WCUF_Media'))
	{
			require_once('classes/com/WCUF_Media.php');
			$wcuf_media_model = new WCUF_Media();
	} 
	if(!class_exists('WCUF_Option'))
	{
			require_once('classes/com/WCUF_Option.php');
			$wcuf_option_model = new WCUF_Option();
	}
	if(!class_exists('WCUF_Customer'))
	{
			require_once('classes/com/WCUF_Customer.php');
			$wcuf_customer_model = new WCUF_Customer();
	}
	if(!class_exists('WCUF_WCPriceCalculatorMeasurementHelper'))
	{
			require_once('classes/com/WCUF_WCPriceCalculatorMeasurementHelper.php');
			$wcuf_price_calculator_measurement_helper = new WCUF_WCPriceCalculatorMeasurementHelper();
	}
	if(!class_exists('WCUF_Cart'))
	{
			require_once('classes/com/WCUF_Cart.php');
			$wcuf_cart_model = new WCUF_Cart();
	}
	if(!class_exists('WCUF_Session'))
	{
			require_once('classes/com/WCUF_Session.php');
			$wcuf_session_model = new WCUF_Session();
	}
	if(!class_exists('WCUF_Text'))
	{
			require_once('classes/com/WCUF_Text.php');
			$wcuf_text_model = new WCUF_Text();
	}
	if(!class_exists('WCUF_Shortcode'))
	{
			require_once('classes/com/WCUF_Shortcode.php');
			$wcuf_shortcodes = new WCUF_Shortcode();
	}
	if(!class_exists('WCUF_Wpml'))
	{
			require_once('classes/com/WCUF_Wpml.php');
			$wcuf_wpml_helper = new WCUF_Wpml();
	}
	if(!class_exists('WCUF_Product'))
	{
			require_once('classes/com/WCUF_Product.php');
			$wcuf_product_model = new WCUF_Product();
	}
	if(!class_exists('WCUF_Order'))
	{
			require_once('classes/com/WCUF_Order.php');
			$wcuf_order_model = new WCUF_Order();
	}
	
	//admin
	if(!class_exists('WCUF_OptionPage'))
	{
		require_once('classes/admin/WCUF_OptionPage.php');
		$wcuf_option_page = new WCUF_OptionPage();
	}
	if(!class_exists('WCUF_UploadFieldsConfiguratorPage')) 
	{	
		require_once('classes/admin/WCUF_UploadFieldsConfiguratorPage.php');
	}
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	if(!class_exists('WCUF_WooCommerceAddon'))
	{
			require_once('classes/admin/WCUF_OrderDetailAddon.php');
			$wcuf_woocommerce_addon = new WCUF_OrderDetailAddon();
	}
	if(!class_exists('WCUF_OrdersTableAddon'))
	{
			require_once('classes/admin/WCUF_OrdersTableAddon.php');
			$wcuf_woocommerce_orderstable_addon = new WCUF_OrdersTableAddon();
	}
	if(!class_exists('WCUF_TextConfiguratorPage'))
	{
		require_once('classes/admin/WCUF_TextConfiguratorPage.php');	
	}
	if(!class_exists('WCUF_IndividualProductConfigurator'))
	{
		require_once('classes/admin/WCUF_IndividualProductConfigurator.php');
		$wcuf_individual_product_configurator_page = new WCUF_IndividualProductConfigurator();
	}
	
	//frontend
	if(!class_exists('WCUF_OrderDetailsPage'))
	{
			require_once('classes/frontend/WCUF_OrderDetailsPage.php');
			$wcuf_order_details_page_addon ;
	}
	if(!class_exists('WCUF_ProductPage'))
	{
			require_once('classes/frontend/WCUF_ProductPage.php');
			$wcuf_product_page_addon;
	}	
	if(!class_exists('WCUF_CheckoutPage'))
	{
			require_once('classes/frontend/WCUF_CheckoutPage.php');
			$wcuf_checkout_addon;
	}
	if(!class_exists('WCUF_CartPage'))
	{
			require_once('classes/frontend/WCUF_CartPage.php');
			$wcuf_cart_addon;
	}if(!class_exists('WCUF_MyAccountPage'))
	{
			require_once('classes/frontend/WCUF_MyAccountPage.php');
			$wcuf_my_account_addon;
	}
	
	//Global
	include 'classes/com/WCUF_Globals.php';
	
	add_action('admin_menu', 'wcuf_init_admin_panel');
	add_action( 'plugins_loaded', 'wcuf_plugin_loaded_init');
	add_action( 'init', 'wcuf_init');
	//add_action( 'init', 'wcuf_init_session');
	
	//js managment
	//add_action( 'admin_init', 'wcuf_register_settings');
	//add_action( 'admin_enqueue_scripts', 'wcuf_unregister_css_and_js' ); 
	add_action( 'wp_print_scripts', 'wcuf_unregister_css_and_js' ); 
} 
function wcuf_init()
{
	load_plugin_textdomain('woocommerce-files-upload', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
function wcuf_plugin_loaded_init()
{
	global $wcuf_product_page_addon, $wcuf_checkout_addon, $wcuf_cart_addon, $wcuf_order_details_page_addon,$wcuf_my_account_addon;
	 $wcuf_product_page_addon = new WCUF_ProductPage();
	 $wcuf_checkout_addon = new WCUF_CheckoutPage();
	 $wcuf_cart_addon = new WCUF_CartPage();
	 $wcuf_my_account_addon = new WCUF_MyAccountPage();
	 $wcuf_order_details_page_addon = new WCUF_OrderDetailsPage();
	 $wcuf_text_configurator_page = new WCUF_TextConfiguratorPage();
	 							
}
function wcuf_init_session()
{
	/* if(!WC()->session->_has_cookie)
		WC()->session->set_customer_session_cookie(true); */
}
function wcuf_unregister_css_and_js($enqueue_styles)
{
	WCUF_UploadFieldsConfiguratorPage::force_dequeue_scripts($enqueue_styles);
}
function wcuf_register_settings()
{ 
	//WCUF_UploadFieldsConfiguratorPage::enqueue_scripts();
	
	/*register_setting('wcuf_files_fields_meta_groups', 'wcuf_files_fields_meta');*/
} 

function wcuf_init_admin_panel()
{
	if(!current_user_can('manage_woocommerce'))
		return;
	
	$place = wcuf_get_free_menu_position(59 , .1);
	
	add_menu_page( __('Upload files configurator', 'woocommerce-files-upload'), __('Upload files configurator', 'woocommerce-files-upload'), 'manage_woocommerce', 'woocommerce-files-upload', 'render_wcuf_option_page', 'dashicons-images-alt2', (string)$place);
	//add_submenu_page('woocommerce', __('Upload files configurator','woocommerce-files-upload'), __('Upload files configurator','woocommerce-files-upload'), 'edit_shop_orders', 'woocommerce-files-upload', 'render_wcuf_option_page');

}
function wcuf_get_free_menu_position($start, $increment = 0.1)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}
	
	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) {
		$start += $increment;
	}
	return $start;
}
function render_wcuf_option_page()
{
	$page = new WCUF_UploadFieldsConfiguratorPage();
	$page->render_page();
}
function wcuf_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
?>