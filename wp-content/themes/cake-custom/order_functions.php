<?php
define('KITT_NORMAL_ORDER', 1);
define('KITT_CUSTOM_ORDER', 2);
define('KITT_TEMP_PRODUCT_NAME', __('Custom Order Product', 'cake'));
define('KITT_SHIPPING_PICKUP', 'local_pickup:2');
define('KITT_SHIPPING_DELIVERY', 'flat_rate:3');
define('KITT_APPROXIMATELY_SYMBOL', '~');

function kitt_woocommerce_hidden_order_itemmeta ($meta_array) {
	$meta_array[] = '_order_type';
	return $meta_array;
}
add_filter( 'woocommerce_hidden_order_itemmeta', 'kitt_woocommerce_hidden_order_itemmeta', 10, 3);


add_action('wp','reset_custom_cart');
function reset_custom_cart(){
	if (!(defined('DOING_AJAX') && DOING_AJAX)) {
		resetCustomCart();
	}
}

function getFormData(){
	$aFormData = array();
	if (isset($_SESSION['cake_custom_order']) && !empty($_SESSION['cake_custom_order']))
	{
		foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
		{
			foreach ( $cakeStepData as $fieldName => $fieldValue )
			{
				if ( strpos($fieldName, 'custom_order_') !== false )
				{
					$aFormData[$fieldName] = $fieldValue;
				}
			}
		}
	}
	
	return $aFormData;
}
function kitt_woocommerce_cart_calculate_fees()
{
	if ($_REQUEST['action'] == 'cake_steps_store' || $_REQUEST['action'] == 'submit_form_order')
	{
		$aFormData = getFormData();
		if ($aFormData['custom_order_shipping'] == 'delivery')
		{
			// modify shipping fee base on city
			//@TODO add fee for city 
			if (isPostcodeDiscounted())
			{
				WC()->cart->shipping_total = KITT_SHIPPING_POSTCODE_DISCOUNT_FEE;
			}
		}
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'kitt_woocommerce_cart_calculate_fees', 10 );

add_action('woocommerce_add_order_item_meta', 'add_order_item_meta_custom', 10, 3);
function add_order_item_meta_custom ( $item_id, $values, $cart_item_key )
{
	// Check order is custom or normal
	if ( $values )
	{
		$product_id = $values['product_id'];
		$is_custom_order_product = get_post_meta($product_id, 'is_custom_order_product', true);

		$isCustomOrderProduct = KITT_NORMAL_ORDER;
		if ( $is_custom_order_product == 1 )
		{
			$isCustomOrderProduct = KITT_CUSTOM_ORDER;
		}
	}
	wc_add_order_item_meta($item_id, '_order_type', $isCustomOrderProduct);
}

// action for Cake file upload
add_action('wp_ajax_nopriv_cake_file_upload', 'handle_file_upload');
add_action('wp_ajax_cake_file_upload', 'handle_file_upload');
function handle_file_upload(){
	if(!(is_array($_POST) && is_array($_FILES) && defined('DOING_AJAX') && DOING_AJAX)){
		return;
	}

	if(!function_exists('wp_handle_upload')){
		require_once(ABSPATH . 'wp-admin/includes/file.php');
	}

	$response = array();

	foreach($_FILES as $file){
		if ($file['name'])
		{
			$name = $file['name']; // filename to get file's extension
			$size = $file['size'];
			$file_formats = array("jpg", "jpeg", "png", "gif", "bmp");
				
			$extension = substr($name, strrpos($name, '.') + 1);
			if (in_array(strtolower($extension), $file_formats)) { // check it if it's a valid format or not
				if ($size < (1024 * 1024 * 10)) {
					$upload_dir = wp_upload_dir();
					$file_name = uniqid() . '_' . basename($file['name']);
					$temp_folder = $upload_dir['basedir'] . '/temp/';
					$dest_file = $temp_folder . $file_name;

					if ( ! is_dir($temp_folder)) {
						mkdir($temp_folder);
					}
						
					if (move_uploaded_file($file['tmp_name'], $dest_file))
					{
						$response['error'] = false;
						$response['message'] = 'Done';
						$response['file_name'] = $file_name;
						$response['file_src'] = $upload_dir['baseurl'] . '/temp/' . $file_name;
							
					}
					else {
						$response['error'] = true;
						$response['message'] = __('Error, please try again', 'cake');
					}
				} else {
					$response['error'] = true;
					$response['message'] = __('Error, Your image size is bigger than 10MB', 'cake');;
				}
			} else {
				$response['error'] = true;
				$response['message'] = __('Error, Invalid file format, only accept picture', 'cake');;
			}
		}
	}

	echo json_encode($response);
	die();
}

function getArrayRoundShape(){
	return array('round', 'dorm');
}

function getCountryState(){
	global $woocommerce;
	$countries_obj   = new WC_Countries();
	$countries   = $countries_obj->__get('countries');
	$default_country = $countries_obj->get_base_country();
	$default_county_states = $countries_obj->get_states( $default_country );
	return array('countries' => $countries, 'states' => $default_county_states);
}

function showCakePrice($price = 0){
	$price = $price ? $price : 0;
	return get_woocommerce_currency_symbol() . ($price ? number_format($price, 0) : '-');
}

// action for Cake store step form data
add_action('wp_ajax_nopriv_get_size_cake_shape_price', 'get_size_cake_shape_price');
add_action('wp_ajax_get_size_cake_shape_price', 'get_size_cake_shape_price');
function get_size_cake_shape_price() {
	$fieldMapping = getCustomFormFieldMapping();
	$shapeSelected = isset($_POST['price']['type']['custom_order_cake_shape']) ? $_POST['price']['type']['custom_order_cake_shape'] : $_POST['custom_order_meta']['custom_order_cake_shape'];
	
	$html = '';
	if (in_array($shapeSelected, getArrayRoundShape()))
	{
		// Round
		foreach ($fieldMapping['custom_order_cakesize_round']['value'] as $sizeKey => $sizeVal)
		{
			$html .= '<option value="'.$sizeKey.'">'.$sizeVal.'</option>';
		}
	}
	elseif (in_array($shapeSelected, array('heart')))
	{
		// Heart
		foreach ($fieldMapping['custom_order_cakesize_heart']['value'] as $sizeKey => $sizeVal)
		{
			$html .= '<option value="'.$sizeKey.'">'.$sizeVal.'</option>';
		}
	}
	else {
		// Square
		foreach ($fieldMapping['custom_order_cakesize_square']['value'] as $sizeKey => $sizeVal)
		{
			$html .= '<option value="'.$sizeKey.'">'.$sizeVal.'</option>';
		}
	}
	echo $html;die;
}

// action for Cake store step form data
add_action('wp_ajax_nopriv_cake_steps_store', 'cake_steps_store');
add_action('wp_ajax_cake_steps_store', 'cake_steps_store');
function cake_steps_store(){
	// Remove cart items
	if (isset($_POST['data-item-remove'])){
		$step = $_POST['step_remove'];
		$item_remove = $_POST['data-item-remove'];
		if (isset($_POST['data-item-child-remove']))
		{
			$child_item_remove = $_POST['data-item-child-remove'];
			foreach ($_SESSION['cake_custom_order'][$step][$item_remove] as $key_item => $child_item)
			{
				$child_item == $child_item_remove;
				unset($_SESSION['cake_custom_order'][$step][$item_remove][$key_item]);
				break;
			}
		}
		else {
			unset($_SESSION['cake_custom_order'][$step][$item_remove]);
		}
	}
	else {
		if ($_POST['custom_order_cakecolor'] != 'other')
		{
			unset($_POST['custom_order_cakecolor_other']);
		}
		
		$_SESSION['cake_custom_order'] = isset($_SESSION['cake_custom_order']) ? $_SESSION['cake_custom_order'] : array();
		$_SESSION['cake_custom_order'][$_POST['step']] = $_POST;
	}

	$aResponse = array();

	// Show Cart items
	$aCartShowingItems = array(
		'custom_order_cake_type',
		'custom_order_cake_shape',
		'custom_order_layer',
		'custom_order_cakeflavor',
		'custom_order_cakecolor',
		'custom_order_printq',
		'custom_order_cake_decorate',
		'custom_order_msgplate'
	);
	$cartHtml = '';
	$fieldMapping = getCustomFormFieldMapping();
	$cakePrices = get_option('cake_custom_price');
	$cartTotal = 0;
	$defaultPrice = 0;
	$cake_shape_price = 0;
	foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
	{
		foreach ( $cakeStepData as $fieldName => $fieldValue )
		{
			if ( strpos($fieldName, 'custom_order_') === false ) continue;

			if ( in_array($fieldName, $aCartShowingItems) )
			{
				$fieldLabel = is_array(@$fieldMapping[$fieldName]['value']) ? @$fieldMapping[$fieldName]['value'][$fieldValue] : $fieldValue;
				switch ( $fieldName )
				{
					case 'custom_order_cake_type':
						$cakeTypeIndex = array_search($fieldValue, array_keys((array) $fieldMapping[$fieldName]['value']));
						$term_id = $fieldMapping[$fieldName]['field'][$cakeTypeIndex]->term_id;
						$attachment_id = get_option('categoryimage_' . $term_id);
						$src = wp_get_attachment_image_src($attachment_id, 'thumbnail', false);

						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2 cake-type-img">
									<span class="round-cut"><img src="' . $src[0] . '" class="cake-row__img sb-1" /></span>
								</span>
								<span class="display-table-cell width-full cake-type-name">' . $fieldLabel . '</span>
							</h5>';
						break;
					case 'custom_order_cake_shape':
						// Get shape size
						$cakeSize = $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] ? $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] : ($_SESSION['cake_custom_order'][$step]['custom_order_cakesize_square'] ? $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_square'] : $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_heart']);
						if (in_array($fieldValue, getArrayRoundShape()))
						{
							// Round
							$keyPrice = ('custom_order_cake_shape_custom_order_cakesize_round' . '__' . $fieldValue . '_' . $cakeSize);
						}
						elseif (in_array($fieldValue, array('heart')))
						{
							// Heart
							$keyPrice = ('custom_order_cake_shape_custom_order_cakesize_heart' . '__' . $fieldValue . '_' . $cakeSize);
						}
						else {
							// Square
							$keyPrice = ('custom_order_cake_shape_custom_order_cakesize_square' . '__' . $fieldValue . '_' . $cakeSize);
						}
						$cakePrice = $cakePrices[$keyPrice];
						$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
						$defaultPrice = $cakePrice;
						$cartTotal += $cakePrice;
						$cake_shape_price += $cakePrice;
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_shape-'.$fieldValue.' size30 blk"></i></span>
								<span class="display-table-cell width-full cake-shape-name">' . $fieldLabel . ' / ' . $cakeSize . '</span>
								<span class="display-table-cell price-value pr-5 cake-shape-price">'.showCakePrice($cakePrice).'</span>
							</h5>';
						break;
					
					case 'custom_order_layer':
						$fLayerPrice = 0 ;
						$keyPrice = ('custom_order_layer__' . $fieldValue);
						$cakePrice = $cakePrices[$keyPrice];
						$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
						
						$cakeSize = $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] ? $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] : ($_SESSION['cake_custom_order'][$step]['custom_order_cakesize_square'] ? $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_square'] : $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_heart']);
						
						// Get layer cake size
						if ($fieldValue <= KITT_MAX_LAYER_ESTIMATION)
						{
							$szCakeShape = $cakeStepData['custom_order_cake_shape'];
							if (in_array($szCakeShape, getArrayRoundShape()))
							{
								// Round
								$aCakeSize = array_keys($fieldMapping['custom_order_cakesize_round']['value']);
							}
							elseif (in_array($szCakeShape, array('heart')))
							{
								// Heart
								$aCakeSize = array_keys($fieldMapping['custom_order_cakesize_heart']['value']);
							}
							else {
								// Square
								$aCakeSize = array_keys($fieldMapping['custom_order_cakesize_square']['value']);
							}
							
							$cakeLayerOffsetEnd = array_search($cakeSize, $aCakeSize);
							// Get Layer size array
							$aCakeLayerSize = array();
							for($i = $cakeLayerOffsetEnd - 1; $i > ($cakeLayerOffsetEnd - $fieldValue); $i --)
							{
								$aCakeLayerSize[] = $aCakeSize[$i];
							}
							
							// Get Layer price
							foreach ($aCakeLayerSize as $szLayerSize)
							{
								if (in_array($szCakeShape, getArrayRoundShape()))
								{
									// Round
									$keyPriceSize = ('custom_order_cake_shape_custom_order_cakesize_round' . '__' . $szCakeShape . '_' . $szLayerSize);
								}
								elseif (in_array($szCakeShape, array('heart')))
								{
									// Heart
									$keyPriceSize = ('custom_order_cake_shape_custom_order_cakesize_heart' . '__' . $szCakeShape . '_' . $szLayerSize);
								}
								else {
									// Square
									$keyPriceSize = ('custom_order_cake_shape_custom_order_cakesize_square' . '__' . $szCakeShape . '_' . $szLayerSize);
								}
								
								$aFLayerPrice = $cakePrices[$keyPriceSize];
								$fLayerPrice += !empty($aFLayerPrice) ? $aFLayerPrice['amount'] : 0;
							}
						}
						$cakePrice += $fLayerPrice;
						
						// If size does not contain number => make it = 0
						if (!preg_match('/[0-9]+/', $cakeSize))
						{
							$cakePrice = 0;
						}
						
						// Add price to total
						$cartTotal += $cakePrice;
						$cake_shape_price += $cakePrice;
						
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="icon-outline-kitt_icons_weddingcake size30 blk"></i></span>
								<span class="display-table-cell width-full cake-layer-name">' . $fieldLabel . '</span>
								<span class="display-table-cell price-value pr-5 cake-layer-price">'.($cakePrice ? showCakePrice($cakePrice) : '').'</span>
							</h5>';
						break;
					case 'custom_order_cakeflavor':
						$cakePrice = getFlavorPrice($defaultPrice, $fieldValue);
						$cartTotal += $cakePrice;
						
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_'.$fieldValue.' size30 blk"></i></span>
								<span class="display-table-cell width-full cake-flavor-name">' . $fieldLabel . '</span>
								<span class="display-table-cell price-value pr-5 cake-flavor-price">'. ($cakePrice ? showCakePrice($cakePrice) : '') .'</span>
							</h5>';
						break;
					case 'custom_order_cakecolor':
						$background = '';
						if ($fieldValue == 'other')
						{
							$background = $cakeStepData['custom_order_cakecolor_other'];
						}
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><span class="color-choice head-custom color'.$fieldValue.'" style="'.($background ? ('background:'.$background . '; border-color: ' . $background) : '').'"></span></span>
								<span class="display-table-cell width-full cake-color-name">' . $fieldLabel . '</span>
							</h5>';
						break;
						
					case 'custom_order_printq':
						if ($fieldValue == 'print_yes')
						{
							$cakePrice = 1000;
							$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_printq size30 blk"></i></span>
								<span class="display-table-cell width-full cake-shape-name">' . $fieldMapping[$fieldName]['field']['label'] . '</span>
								<span class="display-table-cell price-value pr-5 printq-price">'.showCakePrice($cakePrice).'</span>
							</h5>';
							
							$cartTotal += $cakePrice;
						}
						break;
						
					case 'custom_order_cake_decorate':
						foreach ( $fieldValue as $keyDecorate => $decorate )
						{
							$keyPrice = ('custom_order_cake_decorate__' . $decorate);
							if ($decorate == 'fruit' && $cake_shape_price)
							{
								$cakePrice = $cake_shape_price * KITT_DECORATE_FRUIT_RATE;
							}
							else {
								$cakePrice = $cakePrices[$keyPrice];
								$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
								
								//@TODO Fixed price 
								$cakePrice = 2000;
							}
								
							$decorateQtyText = '';
							$qtyFormat = 'custom_order_'.$decorate.'_qty';
							if (isset($cakeStepData[$qtyFormat]) && $cakeStepData[$qtyFormat])
							{
								$decorateQty = $cakeStepData[$qtyFormat];
								$cakePrice = $cakePrice * $decorateQty;
								$decorateQtyText .= ' x ' . $decorateQty;
							}
								
							$cartTotal += $cakePrice;
								
							$cartHtml .= '
								<div class="options option-rows">
				                    <span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_'.$decorate.' size30 blk"></i></span>
									<span class="display-table-cell width-full">' . @$fieldMapping[$fieldName]['value'][$decorate] . $decorateQtyText . '</span>
									<span class="display-table-cell pr-2 price-value">'. showCakePrice($cakePrice) . KITT_APPROXIMATELY_SYMBOL .'</span>
									<span class="display-table-cell"><button class="cake-row__remove sb-2" data-step="'.$step.'" data-item-remove="custom_order_cake_decorate" data-item-child-remove="'.$decorate.'">×</button></span>
								</div>';
						}
						break;
					case 'custom_order_msgplate':
						$cartHtml .= '
								<h5 class="detail-row pt-1 pb-1">
									<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_msg-plate size30 blk"></i></span>
									<span class="display-table-cell width-full">'.$fieldLabel.'</span>
									<span class="display-table-cell pr-5 price-value">FREE</span>
								</h5>';
						break;
				}
			}
		}
	}
	
	if (!defined('WOOCOMMERCE_CHECKOUT'))
	{
		define('WOOCOMMERCE_CHECKOUT', 1);
	}
	
	$aData = array();
	$product_id = calculateProductCart($aData, $cartTotal);
	$cart = WC()->instance()->cart;
	$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
	$aResponse['cart_html'] = $cartHtml;
	
	$aData = getFormData();
	$szCakeShape = $aData['custom_order_cake_shape'];
	$cakeSize = isset($aData['custom_order_cakesize_round']) ? $aData['custom_order_cakesize_round'] : ($aData['custom_order_cakesize_square'] ? $aData['custom_order_cakesize_square'] : $aData['custom_order_cakesize_heart']);
	$lastCakeSize = isset($aData['custom_order_cakesize_round']) ? end($fieldMapping['custom_order_cakesize_round']['value']) : (isset($aData['custom_order_cakesize_square']) ? end($fieldMapping['custom_order_cakesize_square']['value']) : end($fieldMapping['custom_order_cakesize_heart']['value']));
	
	$noticeMessage = $cartTotal > 0 ? __('Cake price is not inluding the esitmation because of special size', 'cake') : __('We can’t calculate the esitmation  because of special size', 'cake');
	if ($cakeSize == $lastCakeSize)
	{
		$aResponse['cart_notice'] .= '<div class="col-md-12 columns">' . $noticeMessage .'</div>';
	}
	
	$aResponse['shipping_fee'] = $cart->shipping_total ? $cart->get_cart_shipping_total() : 0;
	$aResponse['sub_total'] = $cart->get_cart_subtotal() . KITT_APPROXIMATELY_SYMBOL;
	$aResponse['total_tax'] = $cart->get_cart_tax() . KITT_APPROXIMATELY_SYMBOL;
	$aResponse['cart_total'] = ($cart->total ? $cart->get_total() : showCakePrice($cakePrice)) . KITT_APPROXIMATELY_SYMBOL;
	
	// Show COnfirmation page
	if ($_POST['step'] >= 2 && $cake_shape_price)
	{
		// Check if price < 8000 -> not allow next step
		if ($cart->subtotal < KITT_MINIMUM_PRICE_CHECKOUT)
		{
			$aResponse['error'] = __('Sorry but we only accept order more than ¥8000', 'cake');
		}
	}
	
	if ($_POST['step'] >= 3)
	{
		$fieldMapping = getCustomFormFieldMapping();
		$aResponse['confirm_html'] = getOrderDetail();
	}

	echo json_encode($aResponse);die;
}

add_filter( 'woocommerce_product_is_taxable', 'kitt_woocommerce_product_is_taxable', 10, 2 );
function kitt_woocommerce_product_is_taxable ($taxable, $product)
{
	if (strpos($product->post->post_name, 'custom-order-product') !== false)
	{
		$taxable = false;
	}
	return $taxable;
}


function calculateProductCart(&$aData = array(), $cartTotal = 0, $b_isCreateOrder = false){
	$aFormData = getFormData();
	$cart = WC()->instance()->cart;
	$product_id = 0;
	if (!empty($cart->cart_contents))
	{
		foreach ($cart->cart_contents as $cart_key => $cart_item)
		{
			if (!get_post_meta($cart_item['product_id'], 'is_custom_order_product', true))
			{
				$cart->set_quantity($cart_key, 0);
			}
			else {
				if (!$cart_item['line_total'])
				{
					$cart->set_quantity($cart_key, 0);
					wp_delete_post( $cart_item['product_id'], true);
				}
				else{
					$product_id = $cart_item['product_id'];
				}
			}
		}
	}
	
	if ($b_isCreateOrder)
		return $product_id;
	
// 	$cartTotal = $cartTotal ? $cartTotal : calculateCustomOrderPrice($aData);
	
	// If custom product not exist and has price -> Create product and add to cart
	if (!$product_id)
	{
		$product_id = kitt_create_temporary_product($aData, $cartTotal);
	}
	
	if ($product_id)
	{
		// Modify product price and reset to cart
		update_post_meta( $product_id, '_regular_price', $cartTotal );
		update_post_meta( $product_id, '_sale_price', $cartTotal );
		update_post_meta( $product_id, '_price', $cartTotal );
	
		// Reset cart
		resetCustomCart();
	
		// Re add product to cart
		kitt_add_product_to_cart($product_id);
	
		// Set shipping method
		$shipping_method = $aFormData['custom_order_shipping'] == 'delivery' ? KITT_SHIPPING_DELIVERY : KITT_SHIPPING_PICKUP;
		$chosen_shipping_methods = array($shipping_method);
		WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );
		WC()->cart->calculate_totals();
	}
	
	return $product_id;
}

function resetCustomCart(){
	$cart = WC()->instance()->cart;
	if (!empty($cart->cart_contents))
	{
		foreach ($cart->cart_contents as $cart_key => $cart_item)
		{
			if (get_post_meta($cart_item['product_id'], 'is_custom_order_product', true))
			{
				$cart->set_quantity($cart_key, 0);
			}
		}
	}
}

function attachImageToProduct ($image, $post_id, $setThumbnail = false)
{
	// $filename should be the path to a file in the upload directory.
	$upload_dir = wp_upload_dir();
	$temp_folder = $upload_dir['basedir'] . '/temp/';
	$filename = $temp_folder . $image;

	$file_array = array();
	$file_array['name'] = basename($filename);
	$file_array['tmp_name'] = $filename;

	$attach_id = media_handle_sideload($file_array, $post_id);
	if (is_wp_error($attach_id))
	{
		return '';
	}

	if ($setThumbnail)
	{
		// Generate the metadata for the attachment, and update the database record.
		set_post_thumbnail( $post_id, $attach_id );
	}
	return $attach_id;
}

function calculateCustomOrderPrice($aData){
	$cakePrices = get_option('cake_custom_price');
	$cakePrices = is_array($cakePrices) ? $cakePrices : array();
	
	$totalPrice = 0;
	foreach ($cakePrices as $keyPrice => $cakePrice)
	{
		if (isset($cakePrice['type']))
		{
			$combine = false;
			foreach ($cakePrice['type'] as $typeKey => $typeVal)
			{
				if (isset($aData[$typeKey]) && in_array($typeVal, (array)$aData[$typeKey]))
				{
					$combine = true;
				}
				else {
					$combine = false;
					break;
				}
			}
	
			if ($combine)
			{
				$qtyFormat = 'custom_order_'.$typeVal.'_qty';
				if (isset($aData[$qtyFormat]) && $aData[$qtyFormat])
				{
					$decorateQty = $aData[$qtyFormat];
					$totalPrice += $cakePrice['amount'] * $decorateQty;
				}
				else {
					$totalPrice += $cakePrice['amount'];
				}
					
			}
		}
	}
	return $totalPrice;
}

function kitt_create_temporary_product(&$aData, $totalPrice) {
	// Create Custom Product
	$post = array(
		'post_author' => 1,
		'post_content' => '',
		'post_status' => "private",
		'post_title' => __('Custom Order Product', 'cake'),
		'post_parent' => '',
		'post_type' => "product",
	);
	
	//Create post
	$post_id = wp_insert_post( $post, $wp_error );
	if($post_id){
		$attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
		add_post_meta($post_id, '_thumbnail_id', $attach_id);
	}
	
	wp_set_object_terms($post_id, 'simple', 'product_type');
	update_post_meta( $post_id, '_visibility', 'hidden' );
	update_post_meta( $post_id, '_stock_status', 'instock');
	update_post_meta( $post_id, '_downloadable', 'no');
	update_post_meta( $post_id, '_virtual', 'no');
	update_post_meta( $post_id, '_featured', "no" );
	update_post_meta( $post_id, 'is_custom_order_product', 1);
	update_post_meta( $post_id, '_manage_stock', "no" );
	update_post_meta( $post_id, '_backorders', "no" );
	update_post_meta( $post_id, '_tax_status', "none" );
	
	
	update_post_meta( $post_id, '_regular_price', $totalPrice );
	update_post_meta( $post_id, '_sale_price', $totalPrice );
	update_post_meta( $post_id, '_price', $totalPrice );
	
	return $post_id;
}

add_action('wp_ajax_nopriv_get_layer_cake_size', 'get_layer_cake_size');
add_action('wp_ajax_get_layer_cake_size', 'get_layer_cake_size');
function get_layer_cake_size() {
	$field_mappings = getCustomFormFieldMapping();
	$szShape = $_POST['shape'];
	$aSizeRound = $field_mappings['custom_order_cakesize_round']['value'];
	$aSizeSquare = $field_mappings['custom_order_cakesize_square']['value'];
	$aSizeHeart = $field_mappings['custom_order_cakesize_heart']['value'];
	$iLayer = $szShape == 'custom' ? -1 : (int)$_POST['layer'];
	$szSizeRound = $_POST['szSizeRound'];
	$szSizeSquare = $_POST['szSizeSquare'];
	$szSizeHeart = $_POST['szSizeHeart'];
	
	switch($iLayer)
	{
		case 1:
		case 2:
			// Hide first option of round/square size
			$aSizeRound = array_slice( $aSizeRound, 1, count($aSizeRound), TRUE );
			$aSizeSquare = array_slice( $aSizeSquare, 1, count($aSizeSquare), TRUE );
			$aSizeHeart = array_slice( $aSizeHeart, $iLayer, count($aSizeHeart), TRUE );
			break;
		case KITT_MAX_LAYER_ESTIMATION:
			// Hide 2 first options of round/square size
			$aSizeRound = array_slice( $aSizeRound, 2, count($aSizeRound), TRUE );
			$aSizeSquare = array_slice( $aSizeSquare, 2, count($aSizeSquare), TRUE );
			$aSizeHeart = array_slice( $aSizeHeart, $iLayer, count($aSizeHeart), TRUE );
			break;
		default:
			// Hide all options except last option round/square
			$aSizeRound = array_slice( $aSizeRound, -1, 1, TRUE );
			$aSizeSquare = array_slice( $aSizeSquare, -1, 1, TRUE );
			$aSizeHeart = array_slice( $aSizeHeart, -1, 1, TRUE );
			break;
	}
	
	if ($iLayer <= KITT_MAX_LAYER_ESTIMATION)
	{
		$aSizeRound = array('' => __('Select Size', 'cake')) + $aSizeRound;
		$aSizeSquare = array('' => __('Select Size', 'cake')) + $aSizeSquare;
		$aSizeHeart = array('' => __('Select Size', 'cake')) + $aSizeHeart;
	}
	
	$square_option = $round_option = $heart_option = '';
	foreach ($aSizeRound as $szSizeVal => $szSizeLabel)
	{
		$round_option .= '<option value="'.$szSizeVal.'"' .($szSizeRound == $szSizeVal  ? 'selected' : ''). '>' . 
							$szSizeLabel . 
						'</option>';
	}
	
	foreach ($aSizeSquare as $szSizeVal => $szSizeLabel)
	{
		$square_option .= '<option value="'.$szSizeVal.'"' .($szSizeSquare == $szSizeVal  ? 'selected' : ''). '>' .
							$szSizeLabel .
						'</option>';
	}
	
	foreach ($aSizeHeart as $szSizeVal => $szSizeLabel)
	{
		$heart_option .= '<option value="'.$szSizeVal.'"' .($szSizeHeart == $szSizeVal  ? 'selected' : ''). '>' .
				$szSizeLabel .
				'</option>';
	}
	
	$response = array('custom_order_cakesize_round' => $round_option, 
		'custom_order_cakesize_square' => $square_option,
		'custom_order_cakesize_heart' => $heart_option
	);
	echo json_encode($response);die;
}

add_action('wp_ajax_nopriv_submit_form_order', 'submit_form_order');
add_action('wp_ajax_submit_form_order', 'submit_form_order');
function submit_form_order(){
	$response_message = '';
	$response_error = false;
	$redirect = '';
	$errors = new WP_Error();

	// Remove temporary data
	cleanTemporaryData();
	
	$aRequiredFields = array(
// 		'custom_order_cake_type',
		'custom_order_cake_shape',
		'custom_order_cakecolor',
		'custom_order_cakeflavor',
		'custom_order_shipping',
		'custom_order_customer_name_last',
		'custom_order_customer_name_first',
		'custom_order_customer_name_last_kana',
		'custom_order_customer_name_first_kana',
		'custom_order_customer_tel',
		'custom_order_customer_email',
		'custom_order_pickup_date',
		'custom_order_pickup_time',
		
	);
	$aData = getFormData();
	
	//@TODO Validate required fields
	// If not logged in -> error
	if (!is_user_logged_in())
	{
		//$errors->add( 'user_not_logged', __("<strong>ERROR</strong>: User not logged in"), 'cake' );
	}
	
	foreach ($aRequiredFields as $requireField)
	{
		if (!isset($aData[$requireField]) || !$aData[$requireField])
		{
			$errors->add( $requireField.'_required', __("<strong>ERROR</strong>: Please enter all required fields before checkout"), 'cake' );
			break;
		}
	}

	if (!$errors->get_error_code())
	{
		if (!defined('WOOCOMMERCE_CHECKOUT'))
		{
			define('WOOCOMMERCE_CHECKOUT', 1);
		}
		
		WC()->cart->calculate_totals();
		$checkOut = new WC_Checkout();
		$checkOut->shipping_methods = (array) WC()->session->get( 'chosen_shipping_methods' );
		
		$product_id = calculateProductCart($aData, 0, true);
		$userID = (int) get_current_user_id();
		
		$totalPrice = WC()->instance()->cart->total;
		
		$order_id = $checkOut->create_order();
		
		if (!$order_id)
		{
			$errors->add( 'create_order_error', __("<strong>ERROR</strong>: Can not create order, please refresh and do gain"), 'cake' );
		}
		else {
			// update product status to private
// 			wp_update_post(array(
// 				'ID'    =>  $product_id,
// 				'post_status'   =>  'private'
// 			));

			// Add images if exists
			if (is_array($aData['custom_order_cakePic']) && !empty($aData['custom_order_cakePic']))
			{
				$aAttachIds = array();
				$aAttachUrl = array();
				foreach ($aData['custom_order_cakePic'] as $image)
				{
					$attach_id = attachImageToProduct ($image, $product_id, true);
					if ($attach_id)
					{
						$aAttachIds[] = $attach_id;
						$aAttachUrl[] =   wp_get_attachment_url( $attach_id );
					}
			
				}
			
				if (!empty($aAttachIds))
				{
					update_post_meta($product_id,'_product_image_gallery', implode(',', $aAttachIds));
				}
			}
				
			$order = wc_get_order( $order_id );
			
			// Create Custom Order
			$billing_address = array(
				'first_name' => $aData['custom_order_customer_name_first'] ? $aData['custom_order_customer_name_first'] : get_user_meta( $userID, 'billing_first_name', true ),
				'last_name'  => $aData['custom_order_customer_name_last'] ? $aData['custom_order_customer_name_last'] : get_user_meta( $userID, 'billing_last_name', true ),
				'first_name_kana' => $aData['custom_order_customer_name_first_kana'] ? $aData['custom_order_customer_name_first_kana'] : get_user_meta( $userID, 'billing_first_name_kana', true ),
				'last_name_kana'  => $aData['custom_order_customer_name_last_kana'] ? $aData['custom_order_customer_name_last_kana'] : get_user_meta( $userID, 'billing_last_name_kana', true ),
				'company'    => get_user_meta($userID, 'company', true),
				'email'      => $aData['custom_order_customer_email'] ? $aData['custom_order_customer_email'] : get_user_meta( $userID, 'billing_email', true ),
				'phone'      => $aData['custom_order_customer_tel'] ? $aData['custom_order_customer_tel'] : get_user_meta( $userID, 'billing_tel', true ),
				'address_1'  => get_user_meta( $userID, 'billing_address_1', true ),
				'address_2'  => get_user_meta( $userID, 'billing_address_2', true ),
				'city'       => get_user_meta( $userID, 'billing_city', true ),
				'state'      => get_user_meta( $userID, 'billing_state', true ),
				'postcode'   => get_user_meta( $userID, 'billing_postcode', true ),
				'country'    => 'JP',
			);
			
			$shipping_address = array(
				'last_name' => $aData['custom_order_deliver_name'] ? $aData['custom_order_deliver_name'] : get_user_meta( $userID, 'shipping_last_name', true ),
				'first_name'  => $aData['custom_order_deliver_storename'] ? $aData['custom_order_deliver_storename'] : get_user_meta( $userID, 'shipping_first_name', true ),
				'company'    => $aData['custom_order_deliver_cipname'] ? $aData['custom_order_deliver_cipname'] : get_user_meta( $userID, 'shipping_company', true ),
				'email'      => $aData['custom_order_customer_email'] ,
				'phone'      => $aData['custom_order_deliver_tel'] ? $aData['custom_order_deliver_tel'] : get_user_meta( $userID, 'shipping_phone', true ),
				'address_1'  => $aData['custom_order_deliver_addr1'] ? $aData['custom_order_deliver_addr1'] : get_user_meta( $userID, 'shipping_address_1', true ),
				'address_2'  => $aData['custom_order_deliver_addr2'] ? $aData['custom_order_deliver_addr2'] : get_user_meta( $userID, 'shipping_address_2', true ),
				'city'       => $aData['custom_order_deliver_city'] ? $aData['custom_order_deliver_city'] : get_user_meta( $userID, 'shipping_city', true ),
				'state'      => $aData['custom_order_deliver_pref'] ? $aData['custom_order_deliver_pref'] : get_user_meta( $userID, 'shipping_state', true ),
				'postcode'   => $aData['custom_order_deliver_postcode'] ? $aData['custom_order_deliver_postcode'] : get_user_meta( $userID, 'shipping_postcode', true ),
				'country'    => 'JP',
			);
			
			$order->set_address( $billing_address, 'billing' );
			$order->set_address( $shipping_address, 'shipping' );
	
			$shipping_items = $order->get_shipping_methods();
			wc_update_order_item_meta( key($shipping_items), 'cost', WC()->cart->shipping_total );
	
			update_post_meta( $order->id, '_payment_method', 'other_payment' );
			update_post_meta( $order->id, '_payment_method_title', __('Waiting Payment', 'cake') );
			update_post_meta( $order->id, '_customer_user', get_current_user_id() );
			update_post_meta( $order->id, '_order_total', $totalPrice );
			
	
			// Update custom field for order
			foreach ($aData as $fieldName => &$fieldValue)
			{
				if (is_array($fieldValue)) {
					if ($fieldName == 'custom_order_cakePic')
					{
						$fieldValue = implode(PHP_EOL, $aAttachUrl);
					}
				}
			}
	
			
			if ($aData['custom_order_photocakepic'])
			{
				$attach_id = attachImageToProduct ($aData['custom_order_photocakepic'], $product_id);
				$aData['custom_order_photocakepic'] = wp_get_attachment_url( $attach_id );
			}
			
			// Update order detail to meta
			update_post_meta($order->id, 'cake_custom_order', $aData);
			update_post_meta($order->id, 'survey_order', $_SESSION['cake_custom_order'][3]['survey']);
			update_post_meta($order->id, 'custom_order_pickup_date_time', $aData['custom_order_pickup_date'] . ' ' . str_replace('.5', ':30', $aData['custom_order_pickup_time']));
	
			if ($userID)
			{
				update_user_meta($userID, 'first_name', get_user_meta($userID, 'first_name', true) ? get_user_meta($userID, 'first_name', true) : $aData['custom_order_customer_name_first']);
				update_user_meta($userID, 'last_name', get_user_meta($userID, 'last_name', true) ? get_user_meta($userID, 'last_name', true) : $aData['custom_order_customer_name_last']);
				update_user_meta($userID, 'first_name_kana', get_user_meta($userID, 'first_name_kana', true) ? get_user_meta($userID, 'first_name_kana', true) : $aData['custom_order_customer_name_first_kana']);
				update_user_meta($userID, 'last_name_kana', get_user_meta($userID, 'last_name_kana', true) ? get_user_meta($userID, 'last_name_kana', true) : $aData['custom_order_customer_name_last_kana']);
				update_user_meta($userID, 'tel', get_user_meta($userID, 'tel', true) ? get_user_meta($userID, 'tel', true) : $aData['custom_order_customer_tel']);
				update_user_meta($userID, 'sex', $aData['custom_order_customer_sex'] ? $aData['custom_order_customer_sex'] : get_user_meta($userID, 'sex', true));
				update_user_meta($userID, 'birth_date', $aData['custom_order_customer_birth_date'] ? $aData['custom_order_customer_birth_date'] : get_user_meta($userID, 'birth_date', true));
				
				update_user_meta($userID, 'billing_email', get_user_meta($userID, 'billing_email', true) ? get_user_meta($userID, 'billing_email', true) : $billing_address['email']);
				update_user_meta($userID, 'billing_phone', get_user_meta($userID, 'billing_phone', true) ? get_user_meta($userID, 'billing_phone', true) : $billing_address['phone']);
				update_user_meta($userID, 'billing_state', get_user_meta($userID, 'billing_state', true) ? get_user_meta($userID, 'billing_state', true) : $billing_address['state']);
				update_user_meta($userID, 'billing_city', get_user_meta($userID, 'billing_city', true) ? get_user_meta($userID, 'billing_city', true) : $billing_address['city']);
				update_user_meta($userID, 'billing_country', get_user_meta($userID, 'billing_country', true) ? get_user_meta($userID, 'billing_country', true) : 'JP');
				update_user_meta($userID, 'billing_postcode', get_user_meta($userID, 'billing_postcode', true) ? get_user_meta($userID, 'billing_postcode', true) : $billing_address['postcode']);
				update_user_meta($userID, 'billing_address_1', get_user_meta($userID, 'billing_address_1', true) ? get_user_meta($userID, 'billing_address_1', true) : $billing_address['address_1']);
				update_user_meta($userID, 'billing_address_2', get_user_meta($userID, 'billing_address_2', true) ? get_user_meta($userID, 'billing_address_2', true) : $billing_address['address_2']);
				update_user_meta($userID, 'billing_company', get_user_meta($userID, 'billing_company', true) ? get_user_meta($userID, 'billing_company', true) : get_user_meta($userID, 'company', true));
				update_user_meta($userID, 'billing_first_name', get_user_meta($userID, 'billing_first_name', true) ? get_user_meta($userID, 'billing_first_name', true) : get_user_meta($userID, 'first_name', true));
				update_user_meta($userID, 'billing_last_name', get_user_meta($userID, 'billing_last_name', true) ? get_user_meta($userID, 'billing_last_name', true) : get_user_meta($userID, 'last_name', true));
				update_user_meta($userID, 'billing_first_name_kana', get_user_meta($userID, 'billing_first_name_kana', true) ? get_user_meta($userID, 'billing_first_name_kana', true) : get_user_meta($userID, 'first_name_kana', true));
				update_user_meta($userID, 'billing_last_name_kana', get_user_meta($userID, 'billing_last_name_kana', true) ? get_user_meta($userID, 'billing_last_name_kana', true) : get_user_meta($userID, 'last_name_kana', true));
				
				update_user_meta($userID, 'shipping_state', get_user_meta($userID, 'shipping_state', true) ? get_user_meta($userID, 'shipping_state', true) : $shipping_address['state']);
				update_user_meta($userID, 'shipping_country', get_user_meta($userID, 'shipping_country', true) ? get_user_meta($userID, 'shipping_country', true) : 'JP');
				update_user_meta($userID, 'shipping_postcode', get_user_meta($userID, 'shipping_postcode', true) ? get_user_meta($userID, 'shipping_postcode', true) : $shipping_address['postcode']);
				update_user_meta($userID, 'shipping_city', get_user_meta($userID, 'shipping_city', true) ? get_user_meta($userID, 'shipping_city', true) : $shipping_address['city']);
				update_user_meta($userID, 'shipping_address_1', get_user_meta($userID, 'shipping_address_1', true) ? get_user_meta($userID, 'shipping_address_1', true) : $shipping_address['address_1']);
				update_user_meta($userID, 'shipping_address_2', get_user_meta($userID, 'shipping_address_2', true) ? get_user_meta($userID, 'shipping_address_2', true) : $shipping_address['address_2']);
				update_user_meta($userID, 'shipping_company', get_user_meta($userID, 'shipping_company', true) ? get_user_meta($userID, 'shipping_company', true) : $shipping_address['company']);
				update_user_meta($userID, 'shipping_first_name', get_user_meta($userID, 'shipping_first_name', true) ? get_user_meta($userID, 'shipping_first_name', true) : $shipping_address['first_name']);
				update_user_meta($userID, 'shipping_last_name', get_user_meta($userID, 'shipping_last_name', true) ? get_user_meta($userID, 'shipping_last_name', true) : $shipping_address['last_name']);
				update_user_meta($userID, 'shipping_phone', get_user_meta($userID, 'shipping_phone', true) ? get_user_meta($userID, 'shipping_phone', true) : $shipping_address['phone']);
				update_user_meta($userID, 'shipping_company', get_user_meta($userID, 'shipping_company', true) ? get_user_meta($userID, 'shipping_company', true) : $shipping_address['company']);
			}
			
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status('pending', __( 'Awaiting payment', 'woocommerce-other-payment-gateway' ));
			
			kitt_send_email_customer_placed_order($order->id);
			
			// Redirect to thank you page
			$payment = new WC_Other_Payment_Gateway();
			$redirect = $payment->get_return_url($order) . '&order_placed=1';
		}
	}
	
	$response = array('error' => (boolean)$errors->get_error_code(), 'message' => $errors->get_error_messages(), 'redirect' => $redirect);
	echo json_encode($response);die;
}

function getCustomFormFieldMapping(){
	$mapping_fields = array();
	// Get cake types
	$cakeTypesArg = array(
		'taxonomy' => 'cakegal_taxonomy',
		'hide_empty' => false,
		'orderby'           => 'slug',
		'order'             => 'ASC',
	);
	$terms = get_terms($cakeTypesArg); // Get all terms of a taxonomy
	$cake_type_fields = array();
	
	$cake_type_fields['custom_order_cake_type']['field'] = $terms;
	$cake_type_fields['custom_order_cake_type']['field']['label'] = __('Cake Type', 'woocommerce');
	$cake_type_fields['custom_order_cake_type']['field']['type'] = 'select';
	$cake_type_fields['custom_order_cake_type']['field']['name'] = 'custom_order_cake_type';
	
	foreach ($terms as $term)
	{
		$cake_type_fields['custom_order_cake_type']['value'][$term->slug] = $term->name;
	}

	$cake_custom_fields = kitt_get_custom_fields();
	
	foreach($cake_custom_fields as $field_name => $field_values)
	{
		if ($field_values && isset($field_values['type']))
		{
			$field_values['id'] = $field_values['name'];
			$mapping_fields[$field_values['name']]['field'] = $field_values;
			if ($field_values['type'] == 'select' || $field_values['type'] == 'radio' || $field_values['type'] == 'checkbox')
			{
				$mapping_fields[$field_values['name']]['value'] = $field_values['choices'];
			}
			else {
				$mapping_fields[$field_values['name']]['value'] = $field_values['label'];
			}
		}
	}
	$cake_type_fields = array_merge($cake_type_fields, $mapping_fields);
	return $cake_type_fields;
}
//get size per shape
function getCakesizeGroup(){

	$CakeSize = array(
		'round' => array(
			'custom_order_cakesize_round'
		),
		'square' => array(
			'custom_order_cakesize_square'	
		),
		'heart' => array(
			'custom_order_cakesize_heart'	
		),
		'star' => array(
			'custom_order_cakesize_square'	
		),
		'custom' => array(
			'custom_order_cakesize_square'	
		),
	);
	return $CakeSize;
}
function getCakeSizeOption($shapeSelected, $aData){
	$fieldMapping = getCustomFormFieldMapping();
	
	if (in_array($shapeSelected, getArrayRoundShape()))
	{
		foreach ($fieldMapping['custom_order_cakesize_round']['value'] as $sizeKey => $sizeVal)
		{
			if ($sizeVal == $aData['custom_order_cakesize_round'])
				return $sizeVal;
		}
	}
	else if (in_array($shapeSelected, array('heart')))
	{
		foreach ($fieldMapping['custom_order_cakesize_heart']['value'] as $sizeKey => $sizeVal)
		{
			if ($sizeVal == $aData['custom_order_cakesize_heart'])
				return $sizeVal;
		}
	}
	else {
		foreach ($fieldMapping['custom_order_cakesize_square']['value'] as $sizeKey => $sizeVal)
		{
			if ($sizeVal == $aData['custom_order_cakesize_square'])
				return $sizeVal;
		}
	}
}

function getDecorationGroup(){

	$aDecoration = array(
		'icingcookie' => array(
			'custom_order_icingcookie_qty' => array('label' => __('qty', 'cake')),
			'custom_order_basecolor_text' => array('label' => __('detail note', 'cake')),
		),
		'cupcake' => array(
			'custom_order_cupcake_qty' => array('label' => __('qty', 'cake')),
			'custom_order_cpck_text' => array('label' => __('detail note', 'cake')),
		),
		'macaron' => array(
			'custom_order_macaron_qty' => array('label' => __('qty', 'cake')),
			'custom_order_macaron_color' => array('label' => __('color', 'cake')),
			'custom_order_macaron_color_text' => array('label' => __('color text', 'cake')),
		),
		'heartchoco' => array(
		),
		'fruit' => array(
			'custom_order_fruit_detail' => array('label' => __('Fruit Details', 'cake')),
			'custom_order_fruit_detail_text' => array('label' => __('Fruit Details text', 'cake')),
		),
		'flower' => array(
			'custom_order_flowercolor' => array('label' => __('color', 'cake')),
		),
// 		'print' => array(
// 			'custom_order_photocakepic' => array('label' => __('printing photo', 'cake')),
// 		),
		'candy' => array(
			'custom_order_candy_text' => array('label' => __('detail note', 'cake')),
		),
		'figure' => array(
			'custom_order_doll_text' => array('label' => __('detail note', 'cake')),
		),
		'chocolatedeco' => array(
			'custom_order_chocolatedeco_text' => array('label' => __('detail note', 'cake')),
		),
		'sugarcoating' => array(
		),
	);
	return $aDecoration;
}

function getDecorationOption(){
	$aDecoration = getDecorationGroup();
	$options = array();
	foreach ($aDecoration as $decoration)
	{
		$options = array_merge($options, $decoration );
	}
	
	return $options;
}

function getOrderDetail($order_id = false, $order_type = KITT_CUSTOM_ORDER, $is_email = false) {
	$fieldMapping = getCustomFormFieldMapping();
	
	if (!$order_id)
	{
		// Get from session during order form
		$aData = getFormData();
		$cart = WC()->instance()->cart;
		$cartTotal = $cart->total;
	}
	else {
		// Get from meta when order already completed
		$aData = get_post_meta($order_id, 'cake_custom_order', true);
		$order = new WC_Order($order_id);
		$cartTotal = $order->get_total();
		
		// Fake custom order detail from billing + shipping detail
		$aData['custom_order_customer_name_last'] = $order->billing_last_name;
		$aData['custom_order_customer_name_first'] = $order->billing_first_name;
		$aData['custom_order_customer_name_last_kana'] = $order->billing_last_name_kana;
		$aData['custom_order_customer_name_first_kana'] = $order->billing_first_name_kana;
		$aData['custom_order_customer_tel'] = $order->billing_phone;
		$aData['custom_order_customer_email'] = $order->billing_email;
		
		$items = $order->get_items('shipping');
		$method_id = wc_get_order_item_meta( key($items), 'method_id');
		$isPickup = $method_id == KITT_SHIPPING_PICKUP ? true : false; 
		
		$aData['custom_order_shipping'] = $isPickup ? $fieldMapping['custom_order_shipping']['value']['pickup'] : $fieldMapping['custom_order_shipping']['value']['delivery'];
		if (!$isPickup)
		{
			$aData['custom_order_deliver_name'] = $order->shipping_last_name;
			$aData['custom_order_deliver_storename'] = $order->shipping_first_name;
			$aData['custom_order_deliver_cipname'] = $order->shipping_company;
			$aData['custom_order_deliver_tel'] = $order->shipping_phone;
			$aData['custom_order_deliver_postcode'] = $order->shipping_postcode;
			$aData['custom_order_deliver_pref'] = $order->shipping_state;
			$aData['custom_order_deliver_city'] = $order->shipping_city;
			$aData['custom_order_deliver_addr1'] = $order->shipping_address_1;
			$aData['custom_order_deliver_addr2'] = $order->shipping_address_2;
		}
	}
	
	if (!$aData || empty($aData)) return '';
	
	$aDetailBlocks = array(
		'time_info_wraper' => array(
			'class' => 'col-xs-12',
			'label' => __('Time Info', 'woocommerce'),
			'groups' => array(
				array(
					'custom_order_pickup_date' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_pickup_time' => array(
						'class' => 'col-xs-12'
					),
				)
			)
		),
		'cake_info_wraper' => array(
			'class' => 'col-xs-12',
			'label' => __('Cake Info', 'cake'),
			'groups' => array(
				array(
// 					'custom_order_cake_type' => array(
// 						'class' => 'col-xs-3'
// 					),
					'custom_order_cake_shape' => array(
						'class' => 'col-xs-3'
					),
					'custom_order_cakeflavor' => array(
						'class' => 'col-xs-3'
					),
					'custom_order_cakecolor' => array(
						'class' => 'col-xs-3'
					),
				),
				array(
					'custom_order_msgplate' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_cakePic' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_photocakepic' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_cake_decorate' => array(
						'class' => 'col-xs-12'
					),
				)
			)
		),
		
		'customer_info_wraper' => array(
			'class' => 'col-xs-6',
			'label' => __('Customer Info', 'cake'),
			'groups' => array(
				array(
					'custom_order_customer_name_last' => array(
						'class' => 'col-xs-12',
						'label' => 'お名前'
					),
					'custom_order_customer_name_last_kana' => array(
						'class' => 'col-xs-12',
						'label' => 'ふりがな'
					),
					'custom_order_customer_tel' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_customer_email' => array(
						'class' => 'col-xs-12'
					),
				)
			)
		),
		'delivery_info_wraper' => array(
			'class' => 'col-xs-6',
			'label' => __('Delivery Info', 'cake'),
			'groups' => array(
				array(
					'custom_order_shipping' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_deliver_name' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_deliver_storename' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_deliver_cipname' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_deliver_tel' => array(
						'class' => 'col-xs-12'
					),
					'custom_order_deliver_postcode' => array(
						'class' => 'col-xs-12',
						'label' => __('Address', 'cake'),
					),
				)
			)
		),
	);
	
	// Remove Cake info if order type = normal
	if ($order_type == KITT_NORMAL_ORDER)
	{
		unset($aDetailBlocks['cake_info_wraper']);
	}
	
	$blockWraper = '';

	$indexItem = 0;
	
	if ($is_email)
	{
		// Please add style for each html element here and use it like i did in line 1015
		$styleTableRow = ' style="color: #737373; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left; " ';
	}
	
	$divRow = '';
	
	// Show estimation notice - BEGIN
	$szCakeShape = $aData['custom_order_cake_shape'];
	$cakeSize = isset($aData['custom_order_cakesize_round']) ? $aData['custom_order_cakesize_round'] : ($aData['custom_order_cakesize_square'] ? $aData['custom_order_cakesize_square'] : $aData['custom_order_cakesize_heart']);
	$lastCakeSize = isset($aData['custom_order_cakesize_round']) ? end($fieldMapping['custom_order_cakesize_round']['value']) : (isset($aData['custom_order_cakesize_square']) ? end($fieldMapping['custom_order_cakesize_square']['value']) : end($fieldMapping['custom_order_cakesize_heart']['value']));
	
	if ($cakeSize == $lastCakeSize)
	{
		if (!$order_id || in_array($order->post_status, array('wc-pending')))
		{
			$noticeMessage = $cartTotal > 0 ? __('Cake price is not inluding the esitmation because of special size', 'cake') : __('We can’t calculate the esitmation  because of special size', 'cake');
			$divRow .= '<div class="estimation_notice">' . $noticeMessage . '</div>';
		}
	}
	// Show estimation notice -  END
	
	$divRow .= '<div class="order-detail-custom-table row" '.@$styleTableRow.'>';
	$is_original_email = $is_email;
	foreach ($aDetailBlocks as $blockName => $blockContent)
	{
		$is_email = $is_original_email;
		$is_email = $is_email && !in_array($blockName, array('customer_info_wraper', 'delivery_info_wraper'));
		
		$blockClass = $blockName . ' ' . $blockContent['class'];
		$blockLabel = $blockContent['label'];
		$blockGroups = $blockContent['groups'];
		
		if ($is_email)
		{
			$divRow .= '<h3 style=\'color: #e2a6c0; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 16px; font-weight: bold; line-height: 130%; margin: 16px 0 8px; text-align: left;\'>'.$blockLabel.'</h3>';
			$divRow .= '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4;" border="1" class="'.$blockClass.'"><tbody>';
		}
		else {
			if (in_array($blockName, array('customer_info_wraper', 'delivery_info_wraper')) && $is_original_email)
			{
				$divRow .= '<div class="'.$blockClass.'"><h3 style=\'color: #e2a6c0; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 16px; font-weight: bold; line-height: 130%; margin: 16px 0 8px; text-align: left;\'>'.$blockLabel.'</h3>';
			}
			else {
				$divRow .= '<div class="'.$blockClass.'"> <h3>'.$blockLabel.'</h3>';
			}
		}
		
		foreach ($blockGroups as $blockGroup)
		{
			if (!$is_email)
			{
				$divRow .= '<div class="row">'; // -- Start group row
			}
			
			foreach ($blockGroup as $fieldName => $blockVal)
			{
				// Get field Label
				$fieldLabel = $fieldName == 'custom_order_cake_type' ? __('Cake Type', 'woocommerce') : $fieldMapping[$fieldName]['field']['label'];
				$fieldValue = $aData[$fieldName];
				
				if ((is_array($fieldValue) && empty($fieldValue)) || !$fieldValue)
				{
					continue;
				}
				
				if (is_array($fieldValue))
				{
					//
				}
				elseif (is_array($fieldMapping[$fieldName]) && is_array($fieldMapping[$fieldName]['value']) && isset($fieldMapping[$fieldName]['value'][$fieldValue]))
				{
					$fieldValueName = $fieldMapping[$fieldName]['value'][$fieldValue];
				}
				else 
				{
					$fieldValueName = $fieldValue;
				}
				// Get field Value
				switch($fieldName)
				{
					case 'custom_order_pickup_time':
						$fieldValue = str_replace('.5', ':30', $fieldValue);
						$aPickTimes = explode(':', $fieldValue);
						$hourTime = strlen($aPickTimes[0]) == 1 ? '0' . $aPickTimes[0] : $aPickTimes[0];
						$minuteTime = $aPickTimes[1] ? $aPickTimes[1] : '00';
						
// 						$fieldValue = $fieldValue < 12 ? ($fieldValue . __('AM', 'cake')) : ($fieldValue . __('PM', 'cake'));
						$fieldValue = $hourTime . ':' . $minuteTime . ' ~ ' . ($hourTime + 1) . ':' . $minuteTime;
						break;
						
					case 'custom_order_cake_type':
						$cakeTypeIndex = array_search($fieldValue, array_keys((array) $fieldMapping[$fieldName]['value']));
						$term_id = $fieldMapping[$fieldName]['field'][$cakeTypeIndex]->term_id;
						$attachment_id = get_option('categoryimage_' . $term_id);
						$src = wp_get_attachment_image_src($attachment_id, 'thumbnail', false);
				
						if ($is_email)
						{
							$fieldValue = '<span class="round-img"><img style="width: 60px" src="' . $src[0] . '" class="cake-row__img sb-1" /></span></td><td style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #737373; padding: 12px;"><span class="value-text">'.$fieldValueName.'</span></td>';
						}
						else {
							$fieldValue = '<span class="round-img"><img src="' . $src[0] . '" class="cake-row__img sb-1" /></span><span class="value-text">'.$fieldValueName.'</span>';
						}
						break;
							
					case 'custom_order_cake_shape':
						$fieldCakeSize = getCakeSizeOption($fieldValue, $aData);	
						$cakeLayer = $fieldMapping['custom_order_layer']['value'][$aData['custom_order_layer']];
						$cakeLayer = $cakeLayer ? '/'.$cakeLayer : '';
						$fieldValue = '<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_shape-'.$fieldValue.' size30 blk"></i></span>' . 
										'<span class="shape-and-size">' . $fieldValueName . '/'. $fieldCakeSize . $cakeLayer .'</span>' . 
										($fieldValue == 'custom' && $aData['custom_order_cake_shape_custom'] ? '<span class="shape-desc desc">'.$aData['custom_order_cake_shape_custom'] . '</span>' : '');
						break;
							
					case 'custom_order_cakeflavor':
						$fieldValue = '<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_'.$fieldValue.' size30 blk"></i></span><span class="value-text">'.$fieldValueName.'</span>';
						break;
						
					case 'custom_order_cakecolor':
						if (!$aData['custom_order_cakecolor_other'] && $aData['custom_order_cakecolor'] != 'other')
						{
							// This is normal color
							$fieldValue = '<span class="display-table-cell pr-2"><span class="color-show color-choice head-custom color'.$fieldValue.'"></span></span>
									<span class="value-text">'.$fieldMapping['custom_order_cakecolor']['value'][$fieldValue].'</span>';
						}
						else {
							// This is other color
							$colorStyle = '';
							if ($is_email)
							{
								$colorStyle = ';display: inline-block;width: 30px;height: 30px;border-radius: 50%;';
							}
							$fieldValue = '<span class="display-table-cell pr-2"><span class="color-show color-choice head-custom color" style="background:'.$aData['custom_order_cakecolor_other'].$colorStyle .'";></span></span>
											<span class="value-text">'.$fieldMapping['custom_order_cakecolor']['value']['other'].'</span>';
						}
						break;
					
					case 'custom_order_cakePic':
					case 'custom_order_photocakepic':
						if (!$order_id)
						{
							$aPics = array();
							$aPicsTmp = (array)$fieldValue;
							$upload_dir = wp_upload_dir();
							$temp_folder = $upload_dir['baseurl'] . '/temp/';
						
							foreach ($aPicsTmp as $picTmp)
							{
								if ( $picTmp )
								{
									$aPics[] = $temp_folder . $picTmp;
								}
							}
						}
						else {
							$aPics = is_array($fieldValue) ? $fieldValue : explode(PHP_EOL, $fieldValue);
						}
						$fieldValue = '';
						if (!empty($aPics) && $aPics[0])
						{
							foreach ($aPics as $pic)
							{
								$fieldValue .= '<img style="max-width: 100px; margin-right: 10px;" src="' . $pic . '" />';
							}
						}
						break;
							
					case 'custom_order_customer_name_last':
						$fieldLabel = $blockVal['label'];
						$fieldValue = $aData['custom_order_customer_name_last'] . $aData['custom_order_customer_name_first'];
						break;
						
					case 'custom_order_msgplate' :
						$fieldValue = $aData['custom_order_msgpt_text_yes'] ?  $fieldValueName . '<span class="desc">' . $aData['custom_order_msgpt_text_yes'] . '</span>' : $fieldValueName;
						break;
						
					case 'custom_order_customer_name_last_kana':
						$fieldLabel = $blockVal['label'];
						$fieldValue = $aData['custom_order_customer_name_last_kana'] . $aData['custom_order_customer_name_first_kana'];
						break;
						
					case 'custom_order_deliver_postcode':
						$aCountrySates = getCountryState();
						
						$fieldLabel = $blockVal['label'];
						$fieldValue = '〒' . $aData['custom_order_deliver_postcode'] . '<br />' .
								$aCountrySates['states'][$aData['custom_order_deliver_pref']] . $aData['custom_order_deliver_city'] .
								$aData['custom_order_deliver_addr1'] . $aData['custom_order_deliver_addr2'];
					case 'custom_order_cake_decorate' :
						break;
					default :
						$fieldValue = $fieldValueName;
						break;
				}
				
				if (!$is_email)
				{
					$divRow .= '<div class="'.$blockVal['class'].'">'; // -- Start group cols
				}
				
				if ($fieldName == 'custom_order_cake_decorate')
				{
					$aDecoration = getDecorationGroup();
					
					$fieldValues = $fieldValue;
					$fieldValue = '';
					
					if ($is_email && !empty($fieldValues))
					{
						$divRow .= '<tr>';
						$divRow .= '<td colspan="3" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.$fieldLabel.'</td>';
						$divRow .= '</tr>';
					}
					
					foreach ($fieldValues as $decoMainKey => $decoreateMain)
					{
						foreach ($aDecoration as $decoVal => $aDeOptions)
						{
							if ($decoVal == $decoreateMain)
							{
								$divRowTmp = '';
								
								if (!$is_email)
								{
									$divRowTmp .= '<div class="form-row row-'.$fieldName.'">';
									$divRowTmp .= '<div class="label-div '.$decoMainKey.' " >'. ($decoMainKey != 0 ? '&nbsp;' : $fieldLabel).'</div>';
									$divRowTmp .= '<div class="show-value">' . $fieldMapping[$fieldName]['value'][$decoreateMain];
								}
								
								$optionCount = 0;
								foreach ($aDeOptions as $deOption => $deOptionInfo)
								{
									$optionCount++;
									if ('custom_order_photocakepic' == $deOption && $aData[$deOption])
									{
										if (!$order_id)
										{
											$upload_dir = wp_upload_dir();
											$temp_folder = $upload_dir['baseurl'] . '/temp/';
												
											if ( $aData[$deOption] )
											{
												$aData[$deOption] = $temp_folder . $aData[$deOption];
											}
										}
										$aData[$deOption] = '<img style="max-width: 100px;" src="' . $aData[$deOption] . '" />';
									}
										
									if ($aData[$deOption]) {
										if ($is_email)
										{
											$divRowTmp .= '<tr>';
											if ($optionCount == 1)
											{
												$divRowTmp .= '<td rowspan="'.count($aDeOptions).'" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.
														$fieldMapping[$fieldName]['value'][$decoreateMain].
												'</td>';
											}
											
											$divRowTmp .= '<td style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.
													@$fieldMapping[$deOption]['field']['label'] .
											'</td>';
											$divRowTmp .= '<td style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.
													(is_array($fieldMapping[$deOption]['value']) ? $fieldMapping[$deOption]['value'][$aData[$deOption]] : $aData[$deOption]) .
											'</td>';
											
											$divRowTmp .= '</tr>';
										}
										else {
											$divRowTmp .= '<span class="decorate_option '.$deOption.'">
																	<span class="decorate_option">'.@$fieldMapping[$deOption]['field']['label'].'</span>
																	<span class="decorate_option_value">'. (is_array($fieldMapping[$deOption]['value']) ? $fieldMapping[$deOption]['value'][$aData[$deOption]] : $aData[$deOption]) . '</span>
																</span>';
										}
										
									}
									else {
										continue;
									}
								}
								if (!$is_email)
								{
									$divRowTmp .= '</div>'; // -- end show-value
									$divRowTmp .= '</div>'; // -- end form-row
								}
							}
						}
						$divRow .= $divRowTmp;
					}
				}
				else {
					if ($is_email)
					{
						$divRow .= '<tr>';
							$divRow .= '<td style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.$fieldLabel.'</td>';
							$divRow .= '<td colspan="'.(in_array($fieldName, array('custom_order_cake_type')) ? 1 : 2).'" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #737373; padding: 12px;">'.$fieldValue.'</td>';
						$divRow .= '</tr>';
					}
					else {
						$divRow .= '<div class="form-row row-'.$fieldName.'">';
							$divRow .= '<div class="label-div">'.$fieldLabel.'</div>';
							$divRow .= '<div class="show-value">'.$fieldValue.'</div>';
						$divRow .= '</div>';
					}
					
				}
					
				if (!$is_email)
				{
					$divRow .= '</div>'; // -- End group cols
				}
			}
			if (!$is_email)
			{
				$divRow .= '</div>'; // -- End group row
			}
			
		}
		if ($is_email)
		{
			$divRow .= '</tbody></table>'; // --End block class div
		}
		else {
			$divRow .= '</div>'; // --End block class div
		}
	}
	$divRow .= '</div>';
	return $divRow;
}

add_action( 'woocommerce_email_customer_details', 'kitt_woocommerce_email_customer_details', 100, 4 );
function kitt_woocommerce_email_customer_details($order, $sent_to_admin, $plain_text, $email)
{
	if ($sent_to_admin)
	{
		$divRow .= '<div class="survey_wraper">'.
				'<h3 style=\'color: #e2a6c0; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 16px; font-weight: bold; line-height: 130%; margin: 16px 0 8px; text-align: left;\'>アンケート回答</h3>
				</h3>';
		$serveyLabels = kitt_get_survey_label();
		$survey_order = get_post_meta($order->id, 'survey_order', true);
		
		if (!empty($survey_order))
		{
			foreach ($survey_order as $survey_index => $survey)
			{
				$survey_value = is_array($survey) ? implode('-', $survey) : $survey;
				if (!$survey_value) continue;
				
				$divRow .= '<div class="survey_content">';
				$divRow .= '<span class="survey_label">'.$serveyLabels[$survey_index].': </span>';
				if ($survey_index == 'particular' && $survey == 'その他')
				{
					$divRow .= '<span class="survey_value">'.$survey_value . ($stepData['survey_comment'] ? '(' . $stepData['survey_comment'] . ')' : '').'</span>';
				}
				else {
					$divRow .= '<span class="survey_value">'.$survey_value	.'</span>';
				}
				$divRow .= '</div>';
			}
		}
		$divRow .= '</div>';
	}
	echo $divRow;
}

function kitt_get_survey_label ()
{
	return array(
		'engine' => '当店をどこで知りましたか？',
		'engine_other' => 'その他',
		'social' => 'SNS種類',
		'social_comment' => 'Social Other',
		'placed' => '当店のケーキをご注文されたことがありますか？',
		'use' => '最近のご利用日',
		'usage' => 'ご利用回数',
		'taste' => '味について',
		'price' => '値段について',
		'design' => 'デザインについて',
		'particular' => '特に良かった点',
		'other_comment' => 'ご意見・ご希望等',
	);
}
function kitt_add_product_to_cart($product_id) {
	$found = false;
	//check if product already in cart
	if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$_product = $values['data'];
			if ( $_product->id == $product_id )
				$found = true;
		}
		// if product not found, add it
		if ( ! $found )
			WC()->cart->add_to_cart( $product_id );
	} else {
		// if no products in cart, add it
		wp_update_post(array(
	        'ID'    =>  $product_id,
	        'post_status'   =>  'publish'
        ));
		$product = new WC_Product($product_id);
		WC()->cart->add_to_cart( $product_id );
	}
}

add_action( 'woocommerce_email_after_order_table', 'woocommerce_order_details_after_order_table_order_custom_email', 30, 4 );
function woocommerce_order_details_after_order_table_order_custom_email($order){
	$order_type = kitt_get_order_type( $order->id);
	?>
		<div class="custom_order_details">
			<?php echo getOrderDetail($order->id, $order_type, true); ?>
		</div><!--/custom_order_details-->
	
	<?php
}

add_action( 'woocommerce_form_pay_after_order_table', 		'woocommerce_order_details_after_order_table_order_custom', 30, 4 );
add_action( 'woocommerce_order_details_after_order_table', 	'woocommerce_order_details_after_order_table_order_custom', 30, 4 );
function woocommerce_order_details_after_order_table_order_custom ($order){
	$order_type = kitt_get_order_type( $order->id);
?>
	<div class="custom_order_details">
		<?php echo getOrderDetail($order->id, $order_type); ?>
	</div><!--/custom_order_details-->

<?php
}

function kitt_woocommerce_get_order_item_totals($total_rows, $order) {
	if (is_custom_order($order->id) && in_array($order->post_status, array('wc-pending', 'wc-on-hold')) )
	{
		$total_rows['cart_subtotal']['label'] = '見積もり小計';
		$total_rows['order_total']['label'] = '見積もり合計';
	}
	return $total_rows;
}
add_filter('woocommerce_get_order_item_totals', 'kitt_woocommerce_get_order_item_totals', 10, 2);

function kitt_send_email_customer_placed_order($order_id)
{
	global $woocommerce;
	$mailer = $woocommerce->mailer();
	$onHold = $mailer->emails['WC_Email_Customer_On_Hold_Order'];
	$onHold->template_html    = 'emails/customer-new-order.php';
	$onHold->template_plain   = 'emails/plain/customer-new-order.php';
	$onHold->trigger($order_id);
	
	// Now send to admin
	$mailer = $woocommerce->mailer();
	$onHold = $mailer->emails['WC_Email_New_Order'];
	$onHold->trigger($order_id);
}
// send place order email when created order
add_action( 'woocommerce_order_status_pending', 'kitt_send_email_customer_placed_order', 10, 3 );
add_action( 'woocommerce_checkout_order_processed', 'kitt_send_email_customer_placed_order', 10, 3 );

add_filter( 'ac/column/value', 'kitt_modify_shipping_date_order_column', 100, 3 );
function kitt_modify_shipping_date_order_column( $value, $id, $column ) {
	$fieldName = 'custom_order_pickup_date';
	$fieldNameTime = 'custom_order_pickup_time';
	
	if ( $column->get_option('field') == $fieldName ) {
		if (get_field('custom_order_pickup_date'))
		{
			$pickup_time = str_replace('.5', ':30', get_field('custom_order_pickup_time'));
			$value = date('Y/m/d', strtotime(get_field($fieldName))) . '<br />' . $pickup_time . ':00';
		}
		else {
			$orderFormData = get_post_meta($id, 'cake_custom_order', true);
			$defaultValueDate = isset($orderFormData[$fieldName]) ? (is_array($orderFormData[$fieldName]) ? implode(PHP_EOL, $orderFormData[$fieldName]) : $orderFormData[$fieldName]) : '';
			$defaultValueTime = isset($orderFormData[$fieldNameTime]) ? (is_array($orderFormData[$fieldNameTime]) ? implode(PHP_EOL, $orderFormData[$fieldNameTime]) : $orderFormData[$fieldNameTime]) : '';
			
			if ($defaultValueDate)
			{
				$defaultValueTime = str_replace('.5', ':30', $defaultValueTime);
				$value = date('Y/m/d', strtotime($defaultValueDate)) . '<br />' . $defaultValueTime . ':00';
			}
		}
		
	}
	return $value;
}