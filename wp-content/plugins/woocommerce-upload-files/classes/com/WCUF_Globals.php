<?php 
function wcuf_product_is_in_array($product, $array, $consider_variant = false, $disable_stacking = false, $is_order = false)
{
	global $wcuf_product_model;

	/* if(!$consider_variant || !isset($product["variation_id"]) || $product["variation_id"] == 0) */
	$product_obj = null;
	/*try{
		
		$product_obj = new WC_Product_Variation($product["product_id"]);

	}catch(Exception $e)
		{ 
			$product_obj = new WC_Product($product["product_id"]);
		}*/
	
	$wc_price_calculator_is_active =/*  isset($product_obj) ? */ $wcuf_product_model->wc_price_calculator_is_active_on_product( isset($product["variation_id"]) && $product["variation_id"] != 0 ? $product["variation_id"] :  $product["product_id"] /* $product_obj */ ) /* : false */;
	/* else
		$wc_price_calculator_is_active = $wcuf_product_model->wc_price_calculator_is_active_on_product( new WC_Product($product["variation_id"]) ); */
	
	$product_measures = "";
	if($wc_price_calculator_is_active && $disable_stacking)
	{
		$product_measures = !$is_order ? $wcuf_product_model->wc_price_calulator_get_cart_item_name($product) : $wcuf_product_model->wc_price_calulator_get_order_item_name($product);
	}
	
	foreach($array as $current_product)
	{
		$current_product_measures = "";
		$unique_individual_product_id = isset($product[WCUF_Cart::$sold_as_individual_item_cart_key_name]) ? $product[WCUF_Cart::$sold_as_individual_item_cart_key_name] : 0;
		$individual_product_has_already_been_added = true;
		if($wc_price_calculator_is_active  && $disable_stacking)
		{
			$current_product_measures = !$is_order ? $wcuf_product_model->wc_price_calulator_get_cart_item_name($current_product) : $wcuf_product_model->wc_price_calulator_get_order_item_name($current_product);
		}
		else if($unique_individual_product_id != 0) //enabled indivual product sale 
		{
		  $individual_product_has_already_been_added = isset($current_product[WCUF_Cart::$sold_as_individual_item_cart_key_name]) && $current_product[WCUF_Cart::$sold_as_individual_item_cart_key_name] == $unique_individual_product_id;
		}
			
		if( ((!$consider_variant && $current_product['product_id'] == $product['product_id']) ||
			($consider_variant && $current_product['product_id'] == $product['product_id'] && ($current_product['variation_id'] == $product['variation_id'] || ($product['variation_id'] == null && $current_product['variation_id'] == null) ))) &&
			((!$wc_price_calculator_is_active || $product_measures == $current_product_measures) && $individual_product_has_already_been_added)  )
			{
				return true;
			}
	}
	return false;
}
function wcuf_get_file_version( $file ) 
{

		// Avoid notices if file does not exist
		if ( ! file_exists( $file ) ) {
			return '';
		}

		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' );

		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 );

		// PHP will close file handle, but we are good citizens.
		fclose( $fp );

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] )
			$version = _cleanup_header_comment( $match[1] );

		return $version ;
	}
function wcuf_get_woo_version_number() 
{
        // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
	// Otherwise return null
		return NULL;
	}
}
?>