<?php
define('KITT_NORMAL_ORDER', 1);
define('KITT_CUSTOM_ORDER', 2);

add_action('woocommerce_add_order_item_meta', 'add_order_item_meta_custom', 10, 3);
function add_order_item_meta_custom ( $item_id, $values, $cart_item_key )
{
	// Check order is custom or normal
	if ( $values )
	{
		$product_id = $values['product_id'];
		$is_custom_order_product = get_post_meta($product_id, 'is_custom_order_product');

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

	cleanTemporaryData();

	$response = array();

	foreach($_FILES as $file){
		if ($file['name'])
		{
			$name = $file['name']; // filename to get file's extension
			$size = $file['size'];
			$file_formats = array("jpg", "jpeg", "png", "gif", "bmp");
				
			$extension = substr($name, strrpos($name, '.') + 1);
			if (in_array(strtolower($extension), $file_formats)) { // check it if it's a valid format or not
				if ($size < (4096 * 1024)) {
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
					$response['message'] = __('Error, Your image size is bigger than 4MB', 'cake');;
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
	return get_woocommerce_currency_symbol() . number_format($price, 0);
}

// action for Cake store step form data
add_action('wp_ajax_nopriv_get_size_cake_shape_price', 'get_size_cake_shape_price');
add_action('wp_ajax_get_size_cake_shape_price', 'get_size_cake_shape_price');
function get_size_cake_shape_price() {
	$fieldMapping = getCustomFormFieldMapping();
	$cakePrices = get_option('cake_custom_price');
	$shapeSelected = $_POST['price']['type']['custom_order_cake_shape'];
	if (in_array($shapeSelected, getArrayRoundShape()))
	{
		// Round
		foreach ($fieldMapping['custom_order_cakesize_round']['value'] as $sizeKey => $sizeVal)
		{
			$priceKey = ('custom_order_cake_shape_custom_order_cakesize_round' . '__' . $shapeSelected . '_' . $sizeVal);
			if (!isset($cakePrices[$priceKey]))
			{
				$html .= '<option value="'.$sizeKey.'">'.$sizeVal.'</option>';
			}
		}
	}
	else {
		// Square
		foreach ($fieldMapping['custom_order_cakesize_square']['value'] as $sizeKey => $sizeVal)
		{
			$priceKey = ('custom_order_cake_shape_custom_order_cakesize_square' . '__' . $shapeSelected . '_' . $sizeVal);
			if (!isset($cakePrices[$priceKey]))
			{
				$html .= '<option value="'.$sizeKey.'">'.$sizeVal.'</option>';
			}
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
		$_SESSION['cake_custom_order'] = isset($_SESSION['cake_custom_order']) ? $_SESSION['cake_custom_order'] : array();
		$_SESSION['cake_custom_order'][$_POST['step']] = $_POST;
	}

	$aResponse = array();

	// Show Cart items
	$aCartShowingItems = array(
		'custom_order_cake_type',
		'custom_order_cake_shape',
		'custom_order_cakeflavor',
		'custom_order_cakecolor',
		'custom_order_cake_decorate',
		'custom_order_msgplate'
	);
	$cartHtml = '';
	$fieldMapping = getCustomFormFieldMapping();
	$cakePrices = get_option('cake_custom_price');
	$cartTotal = 0;

	foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
	{
		foreach ( $cakeStepData as $fieldName => $fieldValue )
		{
			if ( strpos($fieldName, 'custom_order_') === false ) continue;

			if ( in_array($fieldName, $aCartShowingItems) )
			{
				$fieldLabel = is_array($fieldMapping[$fieldName]['value']) ? $fieldMapping[$fieldName]['value'][$fieldValue] : $fieldValue;
				switch ( $fieldName )
				{
					case 'custom_order_cake_type':
						$cakeTypeIndex = array_search($fieldValue, array_keys((array) $fieldMapping[$fieldName]['value']));
						$term_id = $fieldMapping[$fieldName]['field'][$cakeTypeIndex]->term_id;
						$attachment_id = get_option('categoryimage_' . $term_id);
						$src = wp_get_attachment_image_src($attachment_id, 'thumbnail', false);

						$cakeTypeImg = WPCustomCategoryImage::get_category_image(array(
							'term_id' => $term_id,
							'size' => 'thumbnail'
						));
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
						$cakeSize = $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] ? $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_round'] : $_SESSION['cake_custom_order'][$step]['custom_order_cakesize_square'];
						if (in_array($fieldValue, getArrayRoundShape()))
						{
							// Round
							$keyPrice = ('custom_order_cake_shape_custom_order_cakesize_round' . '__' . $fieldValue . '_' . $cakeSize);
						}
						else {
							// Square
							$keyPrice = ('custom_order_cake_shape_custom_order_cakesize_square' . '__' . $fieldValue . '_' . $cakeSize);
						}
						$cakePrice = $cakePrices[$keyPrice];
						$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
						$cartTotal += $cakePrice;
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_shape-'.$fieldValue.' size30 blk"></i></span>
								<span class="display-table-cell width-full cake-shape-name">' . $fieldLabel . ' / ' . $cakeSize . '</span>
								<span class="display-table-cell price-value pr-5 cake-shape-price">'.showCakePrice($cakePrice).'</span>
							</h5>';
						break;
					case 'custom_order_cakeflavor':
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_'.$fieldValue.' size30 blk"></i></span>
								<span class="display-table-cell width-full cake-flavor-name">' . $fieldLabel . '</span>
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
					case 'custom_order_cake_decorate':
						foreach ( $fieldValue as $keyDecorate => $decorate )
						{
							$keyPrice = ('custom_order_cake_decorate__' . $decorate);
							$cakePrice = $cakePrices[$keyPrice];
							$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
								
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
									<span class="display-table-cell pr-2 price-value">'. showCakePrice($cakePrice) .'</span>
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

	$aResponse['cart_html'] = $cartHtml;
	$aResponse['cart_total'] = showCakePrice($cartTotal);

	// Show COnfirmation page
	if ($_POST['step'] >= 3)
	{
		$fieldMapping = getCustomFormFieldMapping();
		$divRow = '';
		foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
		{
			foreach ( $cakeStepData as $fieldName => $fieldValue )
			{
				if ( $fieldName == 'custom_order_pickup_time' )
				{
					$fieldValue = $fieldValue < 12 ? $fieldValue . ' AM' : $fieldValue . ' PM';
				}
				// If field name has text custom_order_ will be show
				if ( strpos($fieldName, 'custom_order_') !== false )
				{
					$fieldValues = (array) $fieldValue;
					foreach ( $fieldValues as $fieldValue )
					{
						$divRow .= '<div class="row">';

						$divRow .= '<div class="col-md-5 pt-md-5 pt-sm-6 pb-sm-5">';
						$divRow .= $fieldName == 'custom_order_cake_type' ? __('Cake Type', 'cake') : $fieldMapping[$fieldName]['field']['label'];
						$divRow .= '</div>';

						$divRow .= '<div class="col-md-7 pt-md-7 pt-sm-6 pb-sm-7">';
						if ( 'custom_order_cakePic' == $fieldName )
						{
							$upload_dir = wp_upload_dir();
							$temp_folder = $upload_dir['baseurl'] . '/temp/';
								
							if ( $fieldValue )
							{
								$fieldValue = $temp_folder . $fieldValue;
							}
							$divRow .= '<img style="max-width: 300px;" src="' . $fieldValue . '" />';
						}
						else
						{
							if ($fieldName == 'custom_order_deliver_pref')
							{
								$aCountrySates = getCountryState();
								$fieldValue = $aCountrySates['states'][$fieldValue];
							}
							$divRow .= is_array($fieldMapping[$fieldName]['value'][$fieldValue]) ? $fieldMapping[$fieldName]['value'][$fieldValue] : (is_array($fieldMapping[$fieldName]['value']) ? $fieldMapping[$fieldName]['value'][$fieldValue] : $fieldValue);
						}
						$divRow .= '</div>';

						$divRow .= '</div>';
					}
				}
			}
		}
		$aResponse['confirm_html'] = $divRow;
	}

	echo json_encode($aResponse);die;
}

function attachImageToProduct ($image, $post_id)
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

	// Generate the metadata for the attachment, and update the database record.
	set_post_thumbnail( $post_id, $attach_id );
	return $attach_id;
}

function insert_product ($product_data)
{
	$post = array( // Set up the basic post data to insert for our product

		'post_author'  => 1,
		'post_content' => $product_data['description'],
		'post_status'  => 'private',
		'post_title'   => $product_data['name'],
		'post_parent'  => '',
		'post_type'    => 'product'
	);

	$post_id = wp_insert_post($post); // Insert the post returning the new post id
	if (!$post_id) // If there is no post id something has gone wrong so don't proceed
	{
		return false;
	}

	update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
	update_post_meta($post_id, '_manage_stock', 'no');
	update_post_meta($post_id, '_stock_status', 'instock');
	update_post_meta($post_id, 'is_custom_order_product', 1);
	update_post_meta( $post_id,'_visibility','hidden'); // Set the product to visible, if not it won't show on the front end

	wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

	insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
	insert_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations
	
	return $post_id;
}

function insert_product_attributes ($post_id, $available_attributes, $variations)
{
	foreach ($available_attributes as $attribute_key => $attribute_value) // Go through each attribute
	{
		$values = array(); // Set up an array to store the current attributes values.

		foreach ($variations as $variation) // Loop each variation in the file
		{
			$attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

			foreach ($attribute_keys as $key) // Loop through each key
			{
				if ($key === $attribute_key) // If this attributes key is the top level attribute add the value to the $values array
				{
					$values[] = $variation['attributes'][$key];
				}
			}
		}

		// Essentially we want to end up with something like this for each attribute:
		// $values would contain: array('small', 'medium', 'medium', 'large');

		$values = array_unique($values); // Filter out duplicate values

		// Store the values to the attribute on the new post, for example without variables:
		// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
		wp_set_object_terms($post_id, $values, 'pa_' . $attribute_key);
	}

	$product_attributes_data = array(); // Setup array to hold our product attributes data

	foreach ($available_attributes as $attribute_key => $attribute_value) // Loop round each attribute
	{
		$product_attributes_data['pa_'.$attribute_key] = array( // Set this attributes array to a key to using the prefix 'pa'

			'name'         => 'pa_'.$attribute_key,
			'value'        => $attribute_value,
			'is_visible'   => '1',
			'is_variation' => '1',
			'is_taxonomy'  => '0'

		);
	}

	update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'
}

function insert_product_variations ($post_id, $variations)
{
	foreach ($variations as $index => $variation)
	{
		$variation_post = array( // Setup the post data for the variation

			'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
			'post_name'   => 'product-'.$post_id.'-variation-'.$index,
			'post_status' => 'publish',
			'post_parent' => $post_id,
			'post_type'   => 'product_variation',
			'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
		);

		$variation_post_id = wp_insert_post($variation_post); // Insert the variation

		foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
		{
			update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $value);
		}

		update_post_meta($variation_post_id, '_price', $variation['price']);
		update_post_meta($variation_post_id, '_regular_price', $variation['price']);
	}
}

function build_product_data(){
	$products_data = array(
		'description' => 'Product programmatically',
		'name' => 'Product programmatically',
		'available_attributes' => array('custom_size' => 'size40 | size30', 'decorate' => 'candy | sweet'),
		'variations' => array(
			array(
				'attributes' => array(
					'custom_size' => 'size40',
					'decorate' => 'candy'
				),
				'price' => '45.6'
			)
		)
	);
	return $products_data;
}

add_action('wp_ajax_nopriv_submit_form_order', 'submit_form_order');
add_action('wp_ajax_submit_form_order', 'submit_form_order');
function submit_form_order(){
	$response_message = '';
	$response_error = false;
	$redirect = '';
	$errors = new WP_Error();

	//@TODO Validate required fields
	// If not logged in -> error
	if (!is_user_logged_in())
	{
		$errors->add( 'user_not_logged', __("<strong>ERROR</strong>: User not logged in"), 'cake' );
	}

	if (!$errors->get_error_code())
	{
		// Create Custom Product
		$post_id = insert_product(build_product_data());
		if($post_id){
			$attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
			add_post_meta($post_id, '_thumbnail_id', $attach_id);
		}
		else {
			$errors->add( 'product_not_created', __("<strong>ERROR</strong>: Product not created, please contact to administrator"), 'cake' );
			$response = array('error' => (boolean)$errors->get_error_code(), 'message' => $errors->get_error_messages, 'redirect' => '');
			echo json_encode($response);die;
		}

		$aData = array();
		foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
		{
			foreach ( $cakeStepData as $fieldName => $fieldValue )
			{
				if ( strpos($fieldName, 'custom_order_') !== false )
				{
					$aData[$fieldName] = $fieldValue;
				}
			}
		}// Get price
		//@TODO add price
		// 		$cakePrices = get_option('cake_custom_price');
		// 		$myKey .= implode('_', array_keys($_POST['price']['type'])) .'__'. implode('_', $_POST['price']['type']);


		// Add images if exists
		if (is_array($aData['custom_order_cakePic']) && !empty($aData['custom_order_cakePic']))
		{
			$aAttachIds = array();
			$aAttachUrl = array();
			foreach ($aData['custom_order_cakePic'] as $image)
			{
				$attach_id = attachImageToProduct ($image, $post_id);
				if ($attach_id)
				{
					$aAttachIds[] = $attach_id;
					$aAttachUrl[] =   wp_get_attachment_url( $attach_id );
				}

			}
				
			if (!empty($aAttachIds))
			{
				update_post_meta($post_id,'_product_image_gallery', implode(',', $aAttachIds));
			}
		}

		// Create Custom Order
		$address = array(
			'first_name' => $aData['custom_order_customer_name_last'],
			'last_name'  => $aData['custom_order_customer_name_first'],
			'company'    => $aData['custom_order_deliver_storename'],
			'email'      => $aData['custom_order_customer_email'],
			'phone'      => $aData['custom_order_customer_name_first'],
			'address_1'  => $aData['custom_order_deliver_addr1'],
			'address_2'  => $aData['custom_order_deliver_addr2'],
			'city'       => $aData['custom_order_deliver_city'],
			'state'      => $aData['custom_order_deliver_pref'],
			'postcode'   => $aData['custom_order_deliver_postcode'],
			'country'    => 'JP',
		);

		$order_data = array(
			'status'        => 'on-hold',
			'customer_id'   => get_current_user_id(),
		);

		$order = wc_create_order();
		$order->add_product( get_product( $post_id ), 1 ); //(get_product with id and next is for quantity)
		$order->set_address( $address, 'billing' );
		$order->set_address( $address, 'shipping' );
		$order->calculate_totals();

		$orderDetail = new WC_Order( $order->id );

		$items = $orderDetail->get_items();
		$item_keys = array_keys($items);
		wc_add_order_item_meta($item_keys[0], '_order_type', KITT_CUSTOM_ORDER);

		update_post_meta( $order->id, '_payment_method', 'other_payment' );
		update_post_meta( $order->id, '_payment_method_title', 'Waiting Payment' );
		update_post_meta( $order->id, '_customer_user', get_current_user_id() );

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status('on-hold', __( 'Awaiting payment', 'woocommerce-other-payment-gateway' ));
		$order->update_status('pending', __( 'Awaiting payment', 'woocommerce-other-payment-gateway' ));
		// Delete notes
		global $wpdb;
		$posts_table = $wpdb->posts;
		$query = "DELETE FROM ". $wpdb->comments ." WHERE comment_post_ID = " .$order->id;
		$wpdb->query($query);

		// Update custom field for order
		foreach ($aData as $fieldName => &$fieldValue)
		{
			if (is_array($fieldValue)) {
				if ($fieldName == 'custom_order_cakePic')
				{
					$fieldValue = $aAttachUrl;
				}
			}
		}

		// Update order detail to meta
		update_post_meta($order->id, 'cake_custom_order', $aData);

		// Redirect to thank you page
		$payment = new WC_Other_Payment_Gateway();
		$redirect = $payment->get_return_url($order);
	}
	$response = array('error' => (boolean)$errors->get_error_code(), 'message' => $errors->get_error_messages, 'redirect' => $redirect);
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
	foreach ($terms as $term)
	{
		$cake_type_fields['custom_order_cake_type']['value'][$term->slug] = $term->name;
	}

	// Get cake custom fields
	// 	$post = get_page_by_title('Cake Gallery Custom Fields', OBJECT, 'acf');
	// 	if (!$post)
		// 	{
		// 		die('Please add Advanced Custom Field with name "Cake Gallery Custom Fields"');
		// 	}
	$postID = 1532;
	// 	$postID = $post->ID;
	$cake_custom_fields = get_post_meta( $postID );
	foreach($cake_custom_fields as $field_name => $custom_field)
	{
		$field_values = unserialize($custom_field[0]);
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