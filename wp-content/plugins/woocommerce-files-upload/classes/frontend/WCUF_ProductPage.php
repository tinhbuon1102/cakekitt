<?php 
class WCUF_ProductPage
{
	var $upload_form_is_active = false;
	public function __construct()
	{
		
		add_action( 'init', array( &$this, 'init' ));
		//Upload form
		//add_action( 'woocommerce_single_product_summary', array( &$this, 'add_uploads_on_product_page' ), 10 ); 
		//add_action( 'woocommerce_after_single_product_summary', array( &$this, 'add_uploads_on_product_page' ), 10 );
		//add_action( 'woocommerce_after_single_product', array( &$this, 'add_uploads_on_product_page' ), 10 ); 
		//add_action( 'woocommerce_before_add_to_cart_form', array( &$this, 'add_uploads_on_product_page' ), 10 ); 
		
		//default
		//add_action( 'woocommerce_before_add_to_cart_button', array( &$this, 'add_uploads_on_product_page' ), 10 ); 
		
		//ajax reload (product page)
		add_action( 'wp_ajax_reload_upload_fields', array( &$this, 'ajax_reload_upload_fields' ));
		add_action( 'wp_ajax_nopriv_reload_upload_fields', array( &$this, 'ajax_reload_upload_fields' ));
		
		
		
		add_action('wp_head', array( &$this,'add_meta'));
		add_action('wp', array( &$this,'add_headers_meta'));
		//add_action('send_headers', array( &$this,'add_headers_meta'));
	}
	function init()
	{
		global $wcuf_option_model;
		$position = 'woocommerce_before_add_to_cart_form';
		try
		{
			$all_options = $wcuf_option_model->get_all_options();
			$position = $all_options['browse_button_position'];
		}catch(Exception $e){};
		
		add_action( $position, array( &$this, 'add_uploads_on_product_page' ), 10 ); 
	}
	function add_headers_meta()
	{
		if(@is_product())
		{
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache');
		}
	}
	function add_meta()
	{
		if(@is_product())
		{
			
			 echo '<meta http-equiv="Cache-control" content="no-cache">';
			echo '<meta http-equiv="Expires" content="-1">';
		}
	}
	function ajax_reload_upload_fields()
	{
		$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
		$variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : 0;
		$this->add_uploads_on_product_page(true, $product_id,false, $variation_id );
		wp_die();
	}
	function add_uploads_on_product_page($is_ajax_request = false, $post_id = 0, $used_by_shortcode = false, $variation_id = 0)
	{
		$is_ajax_request = $is_ajax_request == "" ? false : $is_ajax_request;
		global $wcuf_option_model, $post,$wcuf_wpml_helper,$wcuf_session_model, $wcuf_cart_model, $wcuf_shortcodes,
		       $wcuf_product_model,$wcuf_text_model, $sitepress, $wcuf_customer_model, $wcuf_upload_field_model;
		$button_texts  = $wcuf_text_model->get_button_texts();
		$this->upload_form_is_active = false;
		$current_product_id = $post_id == 0 ? $post->ID : $post_id;
		$current_page = 'product';
		$current_item_data = array("product_id" => $current_product_id, "variation_id" => $variation_id, "data" => $variation_id == 0 ? new WC_Product($current_product_id) : new WC_Product_Variation($variation_id));
		/* $product_class_name = get_class(wc_get_product($current_product_id));*/
		$is_variable_product_page = is_a(wc_get_product($current_product_id), 'WC_Product_Variable');
	
		//if($wcuf_cart_model->item_is_in_cart($product->id))
		{
			$item_to_show_upload_fields = $wcuf_cart_model->get_sorted_cart_contents();
			//$file_order_metadata = array();
			$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
			$style_options = $wcuf_option_model->get_style_options();
			$crop_area_options = $wcuf_option_model->get_crop_area_options();
			$all_options = $wcuf_option_model->get_all_options();
			$additional_button_class = $all_options['additional_button_class'];
			$check_if_standard_managment_is_disabled = $all_options['pages_in_which_standard_upload_fields_managment_is_disabled'];
			$display_summary_box = 'no';
			
			if(in_array($current_page,$check_if_standard_managment_is_disabled) && !$is_ajax_request && !$used_by_shortcode)
			{
				return;
			}
			else
				$this->upload_form_is_active = true;
		
			//Has the current product added to cart?
			$has_already_added_to_cart = false;
			foreach( (array)$item_to_show_upload_fields as $cart_item_key => $item ) 
			{
				if( $current_product_id == $item["product_id"])
					$has_already_added_to_cart = true;
			}
			
			/*$wcuf_cart_model->remove_item_data(); */
			//wcuf_var_dump($wcuf_cart_model->get_item_data());
			//wcuf_var_dump($item_to_show_upload_fields);
			
			
			//wp_enqueue_script('wcuf-ajax-upload-file', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-checkout-product-page.js' ,array('jquery'));  
			//wp_enqueue_style('wcuf-frontend-product-page', wcuf_PLUGIN_PATH.'/css/wcuf-frontend-product-page.css' );  
			if(!$is_ajax_request)
			{
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/load-image.all.min.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-cart-checkout-product-page.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-product-page.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-multiple-file-manager.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-audio-video-file-manager.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-image-size-checker.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/vendor/cropbox.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-cropper.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-ui-manager.js"></script>';
				echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-multiple-file-uploader.js"></script>';
				//echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-global-error-catcher.js"></script>';
				
				echo '<link rel="stylesheet"  href="'.wcuf_PLUGIN_PATH.'/css/vendor/magnific-popup.css" type="text/css" media="all" />';
				echo '<link rel="stylesheet"  href="'.wcuf_PLUGIN_PATH.'/css/wcuf-frontend-common.css.php?'.http_build_query($style_options).'" type="text/css" media="all" />';
				echo '<link rel="stylesheet"  href="'.wcuf_PLUGIN_PATH.'/css/vendor/cropbox.php?'.http_build_query($crop_area_options).'" type="text/css" media="all" />';
				echo '<link rel="stylesheet"  href="'.wcuf_PLUGIN_PATH.'/css/wcuf-frontend-product-page.css.php?'.http_build_query($style_options).'" type="text/css" media="all" />';
				
				include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
				echo '<div id="wcuf_product_ajax_container_loading_container"></div>';
				echo '<div id="wcuf_product_ajax_container">';
			}
			include WCUF_PLUGIN_ABS_PATH.'/template/checkout_cart_product_page_template.php';
			if(!$is_ajax_request)
				echo '</div>';			
		}
	}
}
?>