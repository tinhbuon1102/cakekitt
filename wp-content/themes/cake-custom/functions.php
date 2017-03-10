<?php
if (!function_exists('pr')) {
	function pr ( $data )
	{
		echo '<pre>';
		print_r($data);
	}
}
// 親テーマ引き継ぎ用関数
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles ()
{
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
	
	if (is_checkout()) 
	{
		wp_deregister_script( 'wc-checkout');
		wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/woocommerce/assets/js/frontend/checkout.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ) );
		
	}
}
if ( ! is_admin() )
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', array(), '3.1.0');
}

function icheck_scripts ()
{
	wp_enqueue_style('rangeslider_css', get_stylesheet_directory_uri() . '/js/rangeslider/rangeslider.css');
	wp_enqueue_script('rangeslider_js', get_stylesheet_directory_uri() . '/js/rangeslider/rangeslider.js');
	
	wp_enqueue_style('validation_engine_css', get_stylesheet_directory_uri() . '/css/validationEngine.jquery.css');
	wp_enqueue_style('icheckall_css', get_stylesheet_directory_uri() . '/js/skins/all.css');
	wp_enqueue_style('icheckpink_css', get_stylesheet_directory_uri() . '/js/skins/square/pink.css');
	wp_enqueue_style('labelauty_css', get_stylesheet_directory_uri() . '/js/labelauty/jquery-labelauty.css');
	wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine.js', array(
		'jquery'
	));
	wp_enqueue_script('validation_engine_ja_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine-ja.js', array(
		'jquery'
	));
	wp_enqueue_script('icheck_js', get_stylesheet_directory_uri() . '/js/icheck.js', array(
		'jquery'
	));
	wp_enqueue_script('labelauty_js', get_stylesheet_directory_uri() . '/js/labelauty/jquery-labelauty.js', array());
	wp_enqueue_script('filestyle_js', get_stylesheet_directory_uri() . '/js/bootstrap-filestyle.min.js', array());
	wp_enqueue_script('pinbox_js', get_stylesheet_directory_uri() . '/js/jquery.pinBox.js', array());
	wp_enqueue_script('kana_js', get_stylesheet_directory_uri() . '/js/jquery.autoKana.js', array());
	wp_enqueue_style('cake_child_css', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array());
}
add_action('wp_enqueue_scripts', 'icheck_scripts');

function formjs_scripts ()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js', array(), '1.8.6');
	wp_enqueue_script('inputfocus_js', get_stylesheet_directory_uri() . '/js/jquery.inputfocus-0.9.min.js', array(
		'jquery'
	));
	wp_enqueue_script('jsmain_js', get_stylesheet_directory_uri() . '/js/jquery.main.js', array(
		'jquery'
	));
}
add_action('wp_enqueue_scripts', 'formjs_scripts');

function checkoption_scripts ()
{
	wp_enqueue_script('checkoption_js', get_stylesheet_directory_uri() . '/js/checkoption.js');
}
add_action('wp_enqueue_scripts', 'checkoption_scripts');

function calendar_scripts ()
{
	wp_enqueue_style('calendar_css', get_stylesheet_directory_uri() . '/js/calendar/src/css/pignose.calendar.css');
	wp_enqueue_script('moment_js', get_stylesheet_directory_uri() . '/js/calendar/vender/moment.min.js');
	wp_enqueue_script('calendar_js', get_stylesheet_directory_uri() . '/js/calendar/src/js/pignose.calendar.js', array(
		'jquery'
	));
	wp_enqueue_script('calendarcustom_js', get_stylesheet_directory_uri() . '/js/calendar/src/js/custom-calendar.js', array(
		'jquery'
	));
}
add_action('wp_enqueue_scripts', 'calendar_scripts');
function flatui_scripts ()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/js/Flat-UI-master/dist/js/vendor/jquery.min.js', array(), '1.11.3', true);
	wp_enqueue_style('bootstrap_css', get_stylesheet_directory_uri() . '/js/Flat-UI-master/dist/css/vendor/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style('flatui_css', get_stylesheet_directory_uri() . '/js/Flat-UI-master/dist/css/flat-ui.css');
	wp_enqueue_script('flatui_js', get_stylesheet_directory_uri() . '/js/Flat-UI-master/dist/js/flat-ui.min.js', array(
		'jquery'
	), '2.2.2', true);
	wp_enqueue_script('flatapp_js', get_stylesheet_directory_uri() . '/js/Flat-UI-master/docs/assets/js/application.js', array(
		'jquery'
	), '', true);
	wp_enqueue_script('flatscript_js', get_stylesheet_directory_uri() . '/js/Flat-UI-master/dist/js/flatscript.js', array(
		'jquery'
	), '', true);
}
add_action('wp_enqueue_scripts', 'flatui_scripts');

function colorpicker_scripts ()
{
 	wp_enqueue_style('colorpicker_css', get_stylesheet_directory_uri() . '/js/colorpicker/css/colorPicker.css');
 	wp_enqueue_script('colorpicker_js', get_stylesheet_directory_uri() . '/js/colorpicker/js/colorPicker.js');
	wp_enqueue_script('colorpickerscript_js', get_stylesheet_directory_uri() . '/js/colorpicker/script.js');
}
add_action('wp_enqueue_scripts', 'colorpicker_scripts');

function gallery_scripts ()
{
// 	wp_enqueue_style('cubeportfolio_css', get_stylesheet_directory_uri() . '/js/cubeportfolio/css/cubeportfolio.css');
// 	wp_enqueue_script('cubeportfolio_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/jquery.cubeportfolio.js');
// 	wp_enqueue_script('cubeportfolioscript_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/main.js');
}
add_action('wp_enqueue_scripts', 'gallery_scripts');

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
function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'woocommerce-filter-orders-by-product/woocommerce-filter-orders-by-product.php',
		'woocommerce-other-payment-gateway/woocommerce-other-payment-gateway.php',
		'wpcustom-category-image/load.php',
	);
	$myplugins = $wp_list_table->items;
	foreach ( $myplugins as $key => $val )
	{
		if ( in_array($key, $hidearr) )
		{
			unset($wp_list_table->items[$key]);
		}
	}
}
add_action('pre_current_active_plugins', 'hide_plugin_order_by_product');

//Clean temp data
function cleanTempFiles($source, $pastDate)
{
	if (!file_exists($source)) return;
	$list = glob($source . "/*");
	foreach ($list as $file) {
		if (date('Y-m-d', filemtime($file)) <= $pastDate)
		{
			@unlink($file);
		}
	}
}

function cleanTemporaryData(){
	// Number date Before current day
	$pastDateNumber = 5;
	$pastDate = date('Y-m-d',strtotime("-$pastDateNumber days"));
	$upload_dir = wp_upload_dir();
	
	$tempFolders = array(
		$upload_dir['basedir'] . '/temp/',
	);
	foreach ($tempFolders as $tempFolder)
	{
		cleanTempFiles($tempFolder, $pastDate);
	}
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
	$_SESSION['cake_custom_order'] = isset($_SESSION['cake_custom_order']) ? $_SESSION['cake_custom_order'] : array();
	$_SESSION['cake_custom_order'][$_POST['step']] = $_POST;
	
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
						$cakeTypeIndex = array_search($fieldValue, array_values((array) $fieldMapping[$fieldName]['value']));
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
						$cartHtml .= '
							<h5 class="detail-row pt-1 pb-1" id="cart_' . $fieldName . '">
								<span class="display-table-cell pr-2"><span class="color-choice head-custom color'.$fieldValue.'"></span></span>
								<span class="display-table-cell width-full cake-color-name">' . $fieldLabel . '</span>
							</h5>';
						break;
					case 'custom_order_cake_decorate':
						foreach ( $fieldValue as $keyDecorate => $decorate )
						{
							$keyPrice = ('custom_order_cake_decorate__' . $decorate);
							$cakePrice = $cakePrices[$keyPrice];
							$cakePrice = !empty($cakePrice) ? $cakePrice['amount'] : 0;
							$cartTotal += $cakePrice;
							
							$cartHtml .= '
								<div class="options option-rows">
				                    <span class="display-table-cell pr-2"><i class="iconkitt-kitt_icons_'.$decorate.' size30 blk"></i></span>
									<span class="display-table-cell width-full">' . @$fieldMapping[$fieldName]['value'][$decorate] . '</span>
									<span class="display-table-cell pr-2 price-value">'. showCakePrice($cakePrice) .'</span>
									<span class="display-table-cell"><button class="cake-row__remove sb-2" data-pie-cart-remove="'.$decorate.'">×</button></span>
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
	if ($_POST['step'] == 3)
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
							$divRow .= is_array($fieldMapping[$fieldName]['value'][$fieldValue]) ? $fieldMapping[$fieldName]['value'][$fieldValue] : (is_array($fieldMapping[$fieldName]['value']) ? $fieldMapping[$fieldName]['value'][$fieldValue] : $fieldValue);
						}
						$divRow .= '</div>';
						
						$divRow .= '</div>';
					}
				}
			}
		}
		$divRow .= $divRow ? '<div class="row"><input type="submit" name="submit" value="Submit"/><input type="hidden" name="confirmed" value="ok"/></div>' : '';
		$aResponse['confirm_html'] = $divRow;
	}
	
	echo json_encode($aResponse);die;
}

function storeOrderCustomToDB(){
	// Create Custom Product
	$post = array(
		'post_author' => 1,
		'post_content' => '',
		'post_status' => "private",
		'post_title' => 'Custom Order Product',
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
	update_post_meta( $post_id, 'total_sales', '0');
	update_post_meta( $post_id, '_downloadable', 'no');
	update_post_meta( $post_id, '_virtual', 'no');
	update_post_meta( $post_id, '_regular_price', "300" );
	update_post_meta( $post_id, '_sale_price', "300" );
	update_post_meta( $post_id, '_purchase_note', "" );
	update_post_meta( $post_id, '_featured', "no" );
	update_post_meta( $post_id, '_weight', "" );
	update_post_meta( $post_id, '_length', "" );
	update_post_meta( $post_id, '_width', "" );
	update_post_meta( $post_id, '_height', "" );
	update_post_meta( $post_id, '_sku', "");
	update_post_meta( $post_id, 'is_custom_order_product', 1);
	update_post_meta( $post_id, '_sale_price_dates_from', "" );
	update_post_meta( $post_id, '_sale_price_dates_to', "" );
	update_post_meta( $post_id, '_price', "300" );
	update_post_meta( $post_id, '_sold_individually', "" );
	update_post_meta( $post_id, '_manage_stock', "no" );
	update_post_meta( $post_id, '_backorders', "no" );
	update_post_meta( $post_id, '_stock', "" );
	
	$aData = array();
	foreach ( $_SESSION['cake_custom_order'] as $step => $cakeStepData )
	{
		foreach ( $cakeStepData as $fieldName => $fieldValue )
		{
			if ( strpos($fieldName, 'custom_order_') !== false )
			{
				$aData[$fieldName] = $fieldValue;
				// Add form data to product meta
				update_post_meta($post_id, $fieldName, $fieldValue);
			}
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
		'status'        => 'pending',
		'customer_id'   => 1,
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
}

add_action('init','register_cake_session');
function register_cake_session(){
	if( !session_id() )
		session_start();
}


function my_admin_enqueue( $hook ) {
	wp_enqueue_script( 'admin_custom_script', get_stylesheet_directory_uri() . '/js/custom-admin.js' );
}
add_action('admin_enqueue_scripts', 'my_admin_enqueue');

function get_array_key_element_index($array, $index) {
	$keys = array_keys($array);
	return $keys[$index - 1];
}

function get_array_value_element_index($array, $index) {
	$values = array_values($array);
	return $values[$index - 1];
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
// add_action('init', 'get_custom_form_field_mapping', 9999);
function get_custom_form_field_mapping() {
	getCustomFormFieldMapping();
}




add_action('woocommerce_product_options_general_product_data', 'my_custom_field');
function my_custom_field ()
{
	woocommerce_wp_text_input(array(
		'id' => '_productnamejp',
		'label' => __('Jp Product Name', 'woocommerce'),
		'placeholder' => '日本語の商品名',
		'description' => __('日本語の商品名を入力してください。', 'woocommerce')
	));
}
add_action('woocommerce_process_product_meta', 'my_custom_field_save');
function my_custom_field_save ( $post_id )
{
	$productnamejp = $_POST['_productnamejp'];
	if ( ! empty($productnamejp) ) update_post_meta($post_id, '_productnamejp', esc_attr($productnamejp));
}
function create_post_type() {
  $cakeGallery = [  // supports のパラメータを設定する配列（初期値だと title と editor のみ投稿画面で使える）
    'thumbnail',  // アイキャッチ画像
    'revisions',  // リビジョン
  	'title',
  ];
  register_post_type( 'cakegal',  // カスタム投稿名
    array(
      'label' => 'Cake Gallery',  // 管理画面の左メニューに表示されるテキスト
      'public' => true,  // 投稿タイプをパブリックにするか否か
      'has_archive' => true,  // アーカイブを有効にするか否か
      'menu_position' => 5,  // 管理画面上でどこに配置するか今回の場合は「投稿」の下に配置
      'supports' => $cakeGallery  // 投稿画面でどのmoduleを使うか的な設定
    )
  );
}
add_action( 'init', 'create_post_type' ); // アクションに上記関数をフックします
register_taxonomy(
  'cakegal_taxonomy',  // 追加するタクソノミー名（英小文字とアンダースコアのみ）
  'cakegal',  // どのカスタム投稿タイプに追加するか
  array(
    'label' => 'Cake Categories',  // 管理画面上に表示される名前（投稿で言うカテゴリー）
    'labels' => array(
      'all_items' => 'Cake Categories List',  // 投稿画面の右カラムに表示されるテキスト（投稿で言うカテゴリー一覧）
      'add_new_item' => 'Add New Cake Category'  // 投稿画面の右カラムに表示されるカテゴリ追加リンク
    ),
    'hierarchical' => true  // タクソノミーを階層化するか否か（子カテゴリを作れるか否か）
  )
);

add_action('admin_menu', 'cake_register_my_custom_submenu_page');

function cake_register_my_custom_submenu_page() {
	add_submenu_page(
			'edit.php?post_type=cakegal',
			'Cake Price Combination',
			'Cake Price Combination',
			'manage_options',
			'cake-price-combination',
			'cake_price_combination_callback' );
}

function cake_price_combination_callback() {
	get_template_part('admin-price-combine');
}
?>
