<?php
/*
Plugin Name: Codeopus Shortcodes
Plugin URI: http://codeopus.net
Description: Shortcode generator. Add buttons, columns, tabs, toggles to your theme.
Version: 1.1
Author: codeopus
Author URI: http://codeopus.net
*/

class CDOShortcodes {

    function __construct() 
    {	
		define('CDO_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));
		define('CDO_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));
		define('CDO_TINYMCE_URI', plugin_dir_url( __FILE__ ) .'tinymce/');
		define('CDO_TINYMCE_DIR', plugin_dir_path( __FILE__ ) .'tinymce/');
		
		require_once( CDO_PLUGIN_DIR_PATH .'/include/functions.php' );
		require_once( CDO_PLUGIN_DIR_PATH .'/include/post-type.php' );
		require_once( CDO_PLUGIN_DIR_PATH .'/include/metaboxes.php' );
    	require_once( CDO_PLUGIN_DIR_PATH .'shortcodes.php' );
		
        add_action('init', array(&$this, 'init'));
        add_action('admin_init', array(&$this, 'admin_init'));
		add_action('init', array(&$this, 'codeopus_add_image_size'));

		add_filter('widget_text', 'do_shortcode');
		add_filter('widget_title', 'do_shortcode');
	
	
	}
	
	function codeopus_add_image_size(){
		
		add_image_size( 'product-thumb-small', 220, 220, false );
		add_image_size( 'product-thumb-small2', 156, 203, true ); //use for product item in cdo_product_category shortcode
		
	}
	
	/**
	 * Registers Javascript
	 *
	 * @return	void
	 */
	function init()
	{
		if( !is_admin() )
		{
		
			//register script
			wp_register_script( 'cdo-gmap-jquery', CDO_PLUGIN_DIR_URL . 'js/jquery.gmap.min.js', array('jquery'));
			wp_register_script( 'cdo-easyResponsiveTabs-jquery', CDO_PLUGIN_DIR_URL . 'js/jquery.easyResponsiveTabs.js', array('jquery'), '', true);
			wp_register_script( 'cdo-accordion-jquery', CDO_PLUGIN_DIR_URL . 'js/cdo-accordion.js', array('jquery'), '', true);
			wp_register_script( 'cdo-tab-jquery', CDO_PLUGIN_DIR_URL . 'js/cdo-tab.js', array('jquery'), '', true);
			wp_register_script( 'cdo-jquery-owl-carousel', CDO_PLUGIN_DIR_URL . 'js/owl.carousel.min.js', array('jquery'), '', true);
			wp_register_script( 'cdo-banner-setting-jquery', CDO_PLUGIN_DIR_URL . 'js/cdo-banner-setting.js', array('jquery'), '', true);
			wp_register_script( 'cdo-jquery-product-featured-setting', CDO_PLUGIN_DIR_URL . 'js/product.featured.setting.js', array('jquery'), '', true);
			wp_register_script( 'cdo-jquery-product-slider', CDO_PLUGIN_DIR_URL . 'js/product.slider.js', array('jquery'), '', true);
			wp_enqueue_script( 'cdo-waypoints', CDO_PLUGIN_DIR_URL . 'js/jquery.waypoints.js', array('jquery'), '', true);
			wp_register_script( 'cdo-isotope', CDO_PLUGIN_DIR_URL . 'js/isotope.pkgd.min.js', array('jquery'), '', true);
			wp_register_script( 'cdo-portfolio', CDO_PLUGIN_DIR_URL . 'js/cdo-portfolio.js', array('jquery'), '', true);
			wp_register_script( 'cdo-fancybox', CDO_PLUGIN_DIR_URL . 'js/jquery.fancybox.js', array('jquery'), '', true);
			wp_register_script( 'cdo-fancybox-media', CDO_PLUGIN_DIR_URL . 'js/jquery.fancybox-media.js', array('jquery'), '', true);
			wp_register_script( 'cdo-fancybox-setting', CDO_PLUGIN_DIR_URL . 'js/cdo-fancybox.js', array('jquery'), '', true);
			wp_register_script( 'cdo-cake-messes-setting', CDO_PLUGIN_DIR_URL . 'js/cake-messes.js', array('jquery'), '', true);
			wp_register_script( 'cdo-pricingtables', CDO_PLUGIN_DIR_URL . 'js/cdo-pricingtables.js', array('jquery'), '', true);
			wp_register_script( 'cdo-masonry', CDO_PLUGIN_DIR_URL . 'js/masonry.pkgd.min.js', array('jquery'), '', true);
			wp_register_script( 'cdo-cake-newsflash', CDO_PLUGIN_DIR_URL . 'js/cake-newsflash.js', array('jquery'), '', true);
			wp_register_script( 'cdo-slick-slider', CDO_PLUGIN_DIR_URL . 'js/slick.min.js', array('jquery'), '', true);
			wp_register_script( 'cdo-slick-slider-setting', CDO_PLUGIN_DIR_URL . 'js/cdo-slick-setting.js', array('jquery'), '', true);
			wp_enqueue_script( 'cdo-main', CDO_PLUGIN_DIR_URL . 'js/cdo-main.js', array('jquery'), '', true);
		
			//enqueue style
			wp_enqueue_style( 'cdo-slick',  CDO_PLUGIN_DIR_URL.'css/slick.css' , '', '', 'screen, all');
			wp_enqueue_style( 'cdo-slick-theme',  CDO_PLUGIN_DIR_URL.'css/slick-theme.css' , '', '', 'screen, all');
			
			wp_enqueue_style( 'cdo-bootstrap',  CDO_PLUGIN_DIR_URL.'css/bootstrap.css' , '', '', 'screen, all');
			wp_enqueue_style( 'cdo-fontawesome',  CDO_PLUGIN_DIR_URL.'css/font-awesome.css' , '', '', 'screen, all');
			wp_enqueue_style( 'cdo-owl-carousel',  CDO_PLUGIN_DIR_URL .'css/owl-carousel.css', '', '', 'screen, all');
			wp_enqueue_style( 'cdo-owl-theme',  CDO_PLUGIN_DIR_URL .'css/owl.theme.default.min.css', '', '', 'screen, all');
			wp_enqueue_style( 'cdo-animate',  CDO_PLUGIN_DIR_URL .'css/animate.css', '', '', 'screen, all');
			wp_enqueue_style( 'cdo-fancybox',  CDO_PLUGIN_DIR_URL .'css/fancybox.css', '', '', 'screen, all');
			wp_enqueue_style( 'cdo-shortcodes',  CDO_PLUGIN_DIR_URL .'shortcodes.css', '', '', 'screen, all');
			

		}
		
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
	
		if ( get_user_option('rich_editing') == 'true' )
		{
			add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
			add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Defins TinyMCE rich editor js plugin
	 *
	 * @return	void
	 */
	function add_rich_plugins( $plugin_array )
	{
		$plugin_array['CDOShortcodes'] =  CDO_TINYMCE_URI . 'plugin.js';
		return $plugin_array;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function register_rich_buttons( $buttons )
	{
		array_push( $buttons, "|", 'cdo_button' );
		return $buttons;
	}
	
	/**
	 * Enqueue Scripts and Styles
	 *
	 * @return	void
	 */
	function admin_init()
	{
		// css
		wp_enqueue_style( 'cdo-fontawesome',  CDO_PLUGIN_DIR_URL.'css/font-awesome.css' );
		wp_enqueue_style( 'cdo-popup',  CDO_TINYMCE_URI . 'css/popup.css', false, '1.0', 'all' );
		wp_enqueue_style( 'cdo-chosen',  CDO_TINYMCE_URI . 'css/chosen.min.css', false, '1.0', 'all' );
		wp_enqueue_style( 'cdo-minicolor',  CDO_TINYMCE_URI . 'css/jquery.minicolors.css', false, '1.0', 'all' );
		
		// js
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery.chosen',  CDO_TINYMCE_URI . 'js/jquery.chosen.js', false, '1.0', false );	
		wp_enqueue_script( 'jquery-livequery',  CDO_TINYMCE_URI . 'js/jquery.livequery.js', false, '1.1.1', false );
		wp_enqueue_script( 'jquery-appendo',  CDO_TINYMCE_URI . 'js/jquery.appendo.js', false, '1.0', false );
		wp_enqueue_script( 'base64',  CDO_TINYMCE_URI . 'js/base64.js', false, '1.0', false );
		wp_enqueue_script( 'cdo-popup',  CDO_TINYMCE_URI . 'js/popup.js', false, '1.0', false );
		wp_enqueue_script( 'cdo-minicolor',  CDO_TINYMCE_URI . 'js/jquery.minicolors.min.js', false, '1.0', false );
		
		wp_localize_script( 'jquery', 'CDOShortcodes', array('plugin_folder' => WP_PLUGIN_URL .'/codeopus-shortcodes') );
	}
    
}
$codeopus_shortcodes = new CDOShortcodes();

add_action('plugins_loaded', 'cdo_load_textdomain');
function cdo_load_textdomain() {
	load_plugin_textdomain( 'codeopus', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}
?>