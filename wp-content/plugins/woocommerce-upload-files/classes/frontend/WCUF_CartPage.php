<?php 
class WCUF_CartPage
{
	var $upload_form_is_active = false;
	public function __construct()
	{
		
		add_action( 'init', array( &$this, 'init' ));
		add_action( 'wp_ajax_reload_upload_fields_on_cart', array( &$this, 'ajax_reload_upload_fields' ));
		add_action( 'wp_ajax_nopriv_reload_upload_fields_on_cart', array( &$this, 'ajax_reload_upload_fields' ));
		
		//Upload form
		add_action('wp_head', array( &$this,'add_meta'));
		add_action('wp', array( &$this,'add_headers_meta'));
		//add_action('send_headers', array( &$this,'add_headers_meta'));
	}
	
	function init()
	{
		global $wcuf_option_model;
		$position = 'woocommerce_before_cart_table';
		try
		{
			$all_options = $wcuf_option_model->get_all_options();
			$position = $all_options['cart_page_positioning'];
		}catch(Exception $e){};
		
		add_action( $position, array( &$this, 'add_uploads_cart_page' ), 10, 1 ); //Cart page	
	}
	function ajax_reload_upload_fields()
	{
		$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
		$this->add_uploads_cart_page(true, false, true );
		wp_die();
	}
	public function add_uploads_cart_page($checkout, $used_by_shortcode = false, $is_ajax_request = false)
	{
		global $wcuf_option_model, $wcuf_wpml_helper, $wcuf_session_model, $wcuf_cart_model, $wcuf_shortcodes, 
		       $wcuf_product_model,$wcuf_text_model, $sitepress, $wcuf_customer_model, $wcuf_upload_field_model; 
		$button_texts  = $wcuf_text_model->get_button_texts();
		//wcuf_var_dump($wcuf_cart_model->get_sorted_cart_contents());
		$item_to_show_upload_fields = $wcuf_cart_model->get_sorted_cart_contents();
		$file_order_metadata = array();
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		$style_options = $wcuf_option_model->get_style_options();
		$crop_area_options = $wcuf_option_model->get_crop_area_options();
		$display_summary_box = $wcuf_option_model->get_all_options('display_summary_box_strategy'); 
		$summary_box_info_to_display = $wcuf_option_model->get_all_options('summary_box_info_to_display'); 
		$all_options = $wcuf_option_model->get_all_options();
		$additional_button_class = $all_options['additional_button_class'];
		$check_if_standard_managment_is_disabled = $all_options['pages_in_which_standard_upload_fields_managment_is_disabled'];
		$current_page = 'cart';
		
		if($this->upload_form_is_active || (in_array($current_page,$check_if_standard_managment_is_disabled) && !$used_by_shortcode))
			return;
		else
			$this->upload_form_is_active = true;
		if(!$is_ajax_request)
		{
			wp_enqueue_script('wcuf-image-size-checker', wcuf_PLUGIN_PATH. '/js/wcuf-image-size-checker.js' ,array('jquery'));	
			wp_enqueue_script('wcuf-ajax-upload-file', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cart-checkout-product-page.js' ,array('jquery'));  
			wp_enqueue_script('wcuf-multiple-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-multiple-file-manager.js' ,array('jquery'));  
			wp_enqueue_script('wcuf-audio-video-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-audio-video-file-manager.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-load-image', wcuf_PLUGIN_PATH. '/js/load-image.all.min.js' ,array('jquery'));  
			wp_enqueue_script('wcuf-cropbox', wcuf_PLUGIN_PATH. '/js/vendor/cropbox.js' ,array('jquery'));		
			wp_enqueue_script('wcuf-image-cropper', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cropper.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js', array('jquery'));
			wp_enqueue_script('wcuf-frontend-ui-manager', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-ui-manager.js', array('jquery'));
			wp_enqueue_script('wcuf-frontend-multiple-file-uploader', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-multiple-file-uploader.js', array('jquery'));
			//wp_enqueue_script('wcuf-frontend-global-error-catcher', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-global-error-catcher.js', array('jquery'));
			
			wp_enqueue_style('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/css/vendor/magnific-popup.css');
			wp_enqueue_style('wcuf-frontend-common', wcuf_PLUGIN_PATH.'/css/wcuf-frontend-common.css.php?'.http_build_query($style_options));
			wp_enqueue_style('wcuf-cropbox', wcuf_PLUGIN_PATH.'/css/vendor/cropbox.php?'.http_build_query($crop_area_options) );
			wp_enqueue_style('wcuf-checkout', wcuf_PLUGIN_PATH. '/css/wcuf-frontend-cart.css.php?'.http_build_query($style_options) );  
	
			include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
			echo '<div id="wcuf_cart_ajax_container_loading_container"></div>';
			echo '<div id="wcuf_cart_ajax_container">';
		}
		include WCUF_PLUGIN_ABS_PATH.'/template/checkout_cart_product_page_template.php';	
		if(!$is_ajax_request)		
			echo '</div>';
		else
		{
			wp_die();
		}
	}
	function add_meta()
	{
		if(@is_cart())
		{
			
			 echo '<meta http-equiv="Cache-control" content="no-cache">';
			echo '<meta http-equiv="Expires" content="-1">';
		}
	}
	function add_headers_meta()
	{
		if(@is_cart())
		{
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache');
		}
	}
}
?>