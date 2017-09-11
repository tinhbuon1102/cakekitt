<?php 
class WCUF_CheckoutPage
{
	var $upload_form_is_active = false;
	public function __construct()
	{
		
		add_action( 'init', array( &$this, 'init' ));
		//Upload form
		add_action( 'woocommerce_before_checkout_billing_form', array( &$this, 'add_popup' ), 10, 1 ); //Checkout page
	
		
		//Ajax upload -> moved to File model
		//add_action( 'wp_ajax_upload_file_during_checkout', array( &$this, 'ajax_upload_file_during_checkout' ));
		
		//After Checkout
		//add_action('woocommerce_checkout_update_order_meta', array( &$this, 'save_uploads_after_checkout' )); //After checkout
		//add_action('woocommerce_checkout_order_processed', array( &$this, 'save_uploads_after_checkout' )); //After checkout
		add_action('woocommerce_thankyou', array( &$this, 'save_uploads_after_checkout' ), 1, 1); //After checkout
		
		//Before Checkout
		add_action('woocommerce_checkout_process', array( &$this, 'check_required_uploads_before_checkout_is_complete' )); 
		
		add_action( 'wp_ajax_reload_upload_fields_on_checkout', array( &$this, 'ajax_add_uploads_checkout_page' ));
		add_action( 'wp_ajax_nopriv_reload_upload_fields_on_checkout', array( &$this, 'ajax_add_uploads_checkout_page' ));
		
		/* add_action( 'wp_ajax_checkout_reload_fields_according_payment_method', array( &$this, 'ajax_reload_fields_according_payment_method' ));
		add_action( 'wp_ajax_nopriv_checkout_reload_fields_according_payment_method', array( &$this, 'ajax_reload_fields_according_payment_method' )); */
		
		add_action('wp', array( &$this,'add_headers_meta'));
		add_action('wp_head', array( &$this,'add_meta'));
		//add_action('send_headers', array( &$this,'add_headers_meta'));
	}
	function init()
	{
		global $wcuf_option_model;
		$position = 'woocommerce_after_checkout_billing_form';
		try
		{
			$all_options = $wcuf_option_model->get_all_options();
			$position = $all_options['checkout_page_positioning'];
		}catch(Exception $e){};
		
		add_action( $position, array( &$this, 'add_uploads_checkout_page' ), 10, 1 ); //Checkout page
		
		if(version_compare( WC_VERSION, '3.0.7', '<' ))
			add_action('woocommerce_add_order_item_meta', array( &$this, 'update_order_item_meta' ),10,3); //Update order items meta
		else
			add_action('woocommerce_new_order_item', array( &$this, 'update_order_item_meta' ),10,3);
	}
	/* function ajax_reload_fields_according_payment_method()
	{
		$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'none';
		$this->add_uploads_checkout_page("",true,false, $payment_method);
	} */
	function ajax_add_uploads_checkout_page() 
	{
		$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'none';
		//wcuf_var_dump("ajax: ".$payment_method);
		$this->add_uploads_checkout_page("",true, false, $payment_method);
	}
	function add_popup($checkout)
	{
		global $wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();
		include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
	}
	function add_uploads_checkout_page($checkout,$is_ajax_request=false, $used_by_shortcode = false, $current_payment_method = 'none') 
	{
		global $wcuf_option_model, $wcuf_order_model, $wcuf_wpml_helper, $wcuf_session_model, $wcuf_cart_model, 
		       $wcuf_shortcodes,$wcuf_product_model,$wcuf_text_model, $sitepress, $wcuf_customer_model, $wcuf_upload_field_model;
		$button_texts  = $wcuf_text_model->get_button_texts();
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
		$current_page = 'checkout';
		//$wcuf_session_model->remove_item_data();
		
		//When rendering on checkout page, before "place order" the upload area is reloaded twice. In order to avoid to lose the posted value, check in this way.
		$current_payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : $current_payment_method; 
		
		//wcuf_var_dump($wcuf_session_model->get_item_data());
		if($this->upload_form_is_active || (in_array($current_page,$check_if_standard_managment_is_disabled) && !$is_ajax_request && !$used_by_shortcode) )
			return;
		else
			$this->upload_form_is_active = true;
		
		if(!$is_ajax_request)
			{
				wp_enqueue_script('wcuf-load-image', wcuf_PLUGIN_PATH. '/js/load-image.all.min.js' ,array('jquery')); 
				wp_enqueue_script('wcuf-ajax-upload-file', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cart-checkout-product-page.js' ,array('jquery'));  
				wp_enqueue_script('wcuf-multiple-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-multiple-file-manager.js' ,array('jquery'));  
				wp_enqueue_script('wcuf-audio-video-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-audio-video-file-manager.js' ,array('jquery')); 
				wp_enqueue_script('wcuf-image-size-checker', wcuf_PLUGIN_PATH. '/js/wcuf-image-size-checker.js' ,array('jquery')); 
				wp_enqueue_script('wcuf-cropbox', wcuf_PLUGIN_PATH. '/js/vendor/cropbox.js' ,array('jquery')); 
				wp_enqueue_script('wcuf-image-cropper', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cropper.js' ,array('jquery')); 
				wp_enqueue_script('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js', array('jquery'));
				wp_enqueue_script('wcuf-checkout-page', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-checkout-page.js', array('jquery'));
				wp_enqueue_script('wcuf-frontend-ui-manager', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-ui-manager.js', array('jquery'));
				wp_enqueue_script('wcuf-frontend-multiple-file-uploader', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-multiple-file-uploader.js', array('jquery'));
				//wp_enqueue_script('wcuf-frontend-global-error-catcher', wcuf_PLUGIN_PATH.'/js/wcuf-frontend-global-error-catcher.js', array('jquery'));
				
				wp_enqueue_style('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/css/vendor/magnific-popup.css');	
				wp_enqueue_style('wcuf-frontend-common', wcuf_PLUGIN_PATH.'/css/wcuf-frontend-common.css.php?'.http_build_query($style_options));			
				wp_enqueue_style('wcuf-cropbox', wcuf_PLUGIN_PATH.'/css/vendor/cropbox.php?'.http_build_query($crop_area_options) );
				wp_enqueue_style('wcuf-checkout', wcuf_PLUGIN_PATH. '/css/wcuf-frontend-checkout.css.php?'.http_build_query($style_options) );  
				
				//include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
				echo '<div id="wcuf_checkout_ajax_container_loading_container"></div>';
				echo '<div id="wcuf_checkout_ajax_container">';
			}
		include WCUF_PLUGIN_ABS_PATH.'/template/checkout_cart_product_page_template.php';
		if(!$is_ajax_request)		
			echo '</div>';
		else
		{
			wp_die();
		}
	}
	function check_required_uploads_before_checkout_is_complete($checkout_fields)
	{
		global $wcuf_product_model,$woocommerce, $wcuf_cart_model;
		$wcuf_cart_model->cart_update_validation();
		$cart = $woocommerce->cart->cart_contents;
		$upload_fields_already_processed = array();
		foreach((array)$cart as $cart_item)
		{
			//wcuf_var_dump($cart_item);
			$product = array();
			$product['product_id'] = $cart_item['product_id'];
			$product['variation_id'] = !isset($cart_item['variation_id']) || $cart_item['variation_id'] == "" ? 0 : $cart_item['variation_id'];
			$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] = isset($cart_item[WCUF_Cart::$sold_as_individual_item_cart_key_name]) ? $cart_item[WCUF_Cart::$sold_as_individual_item_cart_key_name] : null;
			
			$upload_fields_to_perform_upload = $wcuf_product_model->has_a_required_upload_in_its_single_page($product, true, $cart_item["quantity"]);
			if(!empty($upload_fields_to_perform_upload))
				foreach((array)$upload_fields_to_perform_upload as $field_id => $upload_field)
				{
					if(in_array($field_id,$upload_fields_already_processed))
						continue;
						
					$upload_fields_already_processed[] = $field_id;
					if(isset($upload_field['num_uploaded_files_error']) && $upload_field['num_uploaded_files_error'])
					{
						if($upload_field['min_uploadable_files'] == $upload_field['max_uploadable_files'])
						{
							$additional_product_text = $upload_field['disable_stacking'] ? sprintf(__(" for product <strong>%s</strong>",'woocommerce-files-upload'), '<a href="'.get_permalink( $upload_field['product_id'] ).'" target ="_blank">'.$upload_field['product_name'].'</a>') : "";
							wc_add_notice( sprintf(__('Upload <strong>%s</strong>%s requires <strong>%s file(s)</strong>. You have uploaded: <strong>%s file(s)</strong>. Please upload the requested number of files.','woocommerce-files-upload'), $upload_field['upload_field_name'], $additional_product_text, $upload_field['max_uploadable_files'],  $upload_field['num_uploaded_files']) ,'error');
							
						}
						else 
						{
							$additional_product_text = $upload_field['disable_stacking'] ? sprintf(__(" for product <strong>%s</strong>",'woocommerce-files-upload'), '<a href="'.get_permalink( $upload_field['product_id'] ).'" target ="_blank">'.$upload_field['product_name'].'</a>') : "";
							$num_uploaded_files_error = sprintf(__("Upload <strong>%s</strong>%s requires", 'woocommerce-files-upload'), $upload_field['upload_field_name'], $additional_product_text);
							$num_uploaded_files_error .= $upload_field['min_uploadable_files'] != 0 ? sprintf(__(" a minimum of <strong>%s file(s)</strong>", 'woocommerce-files-upload'), $upload_field['min_uploadable_files']) : "" ;
							$num_uploaded_files_error .= $upload_field['max_uploadable_files'] != 0 && $upload_field['min_uploadable_files'] != 0 ? __(" and ", 'woocommerce-files-upload') : "" ;
							$num_uploaded_files_error .= $upload_field['min_uploadable_files'] != 0 ?  sprintf(__(" a maximum of <strong>%s file(s)</strong>", 'woocommerce-files-upload'),$upload_field['max_uploadable_files']): "" ;
							$num_uploaded_files_error .= ". ".__('Please upload all the required files.','woocommerce-files-upload');
							wc_add_notice($num_uploaded_files_error,'error');
						}
					}
					else
						wc_add_notice( sprintf(__('Upload <strong>%s</strong> for product <strong>%s</strong> has not been performed.','woocommerce-files-upload'), $upload_field['upload_field_name'],'<a href="'.get_permalink( $upload_field['product_id'] ).'" target ="_blank">'.$upload_field['product_name'].'</a>') ,'error');
				}					
					
		}
		//wc_add_notice( __('Stop test','woocommerce-files-upload') ,'error');
	}
	function update_order_item_meta($item_id, $values, $cart_item_key)
	{
		if ( is_a( $values, 'WC_Order_Item_Product' ) ) 
		{
			$values = $values->legacy_values;
			//$cart_item_key = $values->legacy_cart_item_key;
		} 
		
		if(isset($values[WCUF_Cart::$sold_as_individual_item_cart_key_name]))
		{
			wc_add_order_item_meta($item_id, '_wcuf_sold_as_individual_unique_key', $values[WCUF_Cart::$sold_as_individual_item_cart_key_name], true);
			/* $old_item_name = wc_get_order_item_meta($item_id, 'order_item_name', true);
			wc_update_order_item($item_id, array('order_item_name' => $old_item_name." #".$values[WCUF_Cart::$sold_as_individual_item_cart_key_name])); */
		}
	}
	function save_uploads_after_checkout( $order_id)
	{
		global $wcuf_file_model, $wcuf_option_model, $wcuf_session_model, $wcuf_upload_field_model;
		/* if(!wp_verify_nonce($_POST['wcuf_attachment_nonce'], 'wcuf_checkout_upload')) 
		  return $order_id; */
		

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return $order_id;
		 
		$temp_uploads = $wcuf_session_model->get_item_data();
		//wcuf_var_dump("checkout");
		//wcuf_var_dump($temp_uploads);
		
		if(!empty($temp_uploads))
		{
			$order = new WC_Order($order_id);
			$status = $order->get_status();
			/* if($status == 'failed' || $status == 'cancelled')
				return; */
			
			//error_log($status);
			
			$file_fields_groups =  $wcuf_option_model->get_fields_meta_data();
			/* $file_order_metadata = $wcuf_option_model->get_order_uploaded_files_meta_data($order_id);
			$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0]; */
			$file_order_metadata = $wcuf_upload_field_model->get_uploaded_files_meta_data_by_order_id($order_id);
			$file_order_metadata = $wcuf_file_model->upload_files($order, $file_order_metadata, $file_fields_groups, $temp_uploads);
			//$file_order_metadata = $wcuf_file_model->upload_and_decode_files($order, $file_order_metadata, $file_fields_groups);
			
			//wcuf_var_dump($file_order_metadata);
		}
		//wp_die();
		$wcuf_session_model->remove_item_data();
	}
	function add_meta()
	{
		if(@is_checkout())
		{
			
			 echo '<meta http-equiv="Cache-control" content="no-cache">';
			echo '<meta http-equiv="Expires" content="-1">';
		}
	}
	function add_headers_meta()
	{
		if(@is_checkout())
		{
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache');
		}
	}
}
?>