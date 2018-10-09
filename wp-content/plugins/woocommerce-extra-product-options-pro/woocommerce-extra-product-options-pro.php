<?php
/**
 * Plugin Name:       WooCommerce Extra Product Options Pro
 * Plugin URI:        https://themehigh.com/product/woocommerce-extra-product-options
 * Description:       Design woocommerce Product form in your own way, customize Product fields(Add, Edit, Delete and re arrange fields).
 * Version:           2.3.6
 * Author:            ThemeHigh
 * Author URI:        https://themehigh.com/
 *
 * Text Domain:       woocommerce-extra-product-options-pro
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.0
 */

if(!defined('WPINC')){	die; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce');
	}
}

if(is_woocommerce_active()) {
	define('THWEPO_VERSION', '2.3.6');
	!defined('THWEPO_SOFTWARE_TITLE') && define('THWEPO_SOFTWARE_TITLE', 'WooCommerce Extra Product Options');
	!defined('THWEPO_FILE') && define('THWEPO_FILE', __FILE__);
	!defined('THWEPO_PATH') && define('THWEPO_PATH', plugin_dir_path( __FILE__ ));
	!defined('THWEPO_URL') && define('THWEPO_URL', plugins_url( '/', __FILE__ ));
	!defined('THWEPO_BASE_NAME') && define('THWEPO_BASE_NAME', plugin_basename( __FILE__ ));
	
	/**
	 * The code that runs during plugin activation.
	 */
	function activate_thwepo() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwepo-activator.php';
		THWEPO_Activator::activate();
	}
	
	/**
	 * The code that runs during plugin deactivation.
	 */
	function deactivate_thwepo() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwepo-deactivator.php';
		THWEPO_Deactivator::deactivate();
	}
	
	register_activation_hook( __FILE__, 'activate_thwepo' );
	register_deactivation_hook( __FILE__, 'deactivate_thwepo' );
	
	function init_auto_updater_thwepo(){
		if(!class_exists('THWEPO_Auto_Update_License') ) {
			$api_url = 'https://themehigh.com/';
			require_once plugin_dir_path( __FILE__  ) . 'class-thwepo-auto-update-license.php';
			THWEPO_Auto_Update_License::instance(__FILE__, THWEPO_SOFTWARE_TITLE, THWEPO_VERSION, 'plugin', $api_url, 'woocommerce-extra-product-options-pro');
		}
	}
	init_auto_updater_thwepo();
	
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-thwepo.php';
	
	/**
	 * Begins execution of the plugin.
	 */
	function run_thwepo() {
		$plugin = new THWEPO();
		$plugin->run();
	}
	run_thwepo();
}