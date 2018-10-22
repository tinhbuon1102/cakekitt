<?php
define('KITT_SHIPPING_POSTCODE_DISCOUNT_FEE', 1500);
define('KITT_FLAVOR_RATE', 1.1);
define('KITT_MINIMUM_PRICE_FOR_OTHER_POSTCODE', 50000);
define('KITT_MAX_LAYER_ESTIMATION', 3);
define('KITT_MINIMUM_PRICE_CHECKOUT', 8000);
define('KITT_DECORATE_FRUIT_RATE', 0.2);
define('KITT_CAKESIZE_ROUND_FOR_LAYER_1', 1);

function add_files() {
// サイト共通のCSSの読み込み
wp_enqueue_style( 'overwrite', get_stylesheet_directory_uri() . '/overwrite.css', "", '20180528' );
}
add_action( 'wp_enqueue_scripts', 'add_files', 1000 );

function prefix_nav_description( $item_output, $item, $depth, $args ) {
 if ( !empty( $item->description ) ) {
 $item_output = str_replace( '">' . $args->link_before . $item->title, '">' . $args->link_before . '<span class="menu-item-title">' . $item->title . '</span>' . '<span class="menu-item-desc ja">' . $item->description . '</span>' , $item_output );
 }
 return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'prefix_nav_description', 10, 4 );

add_shortcode('hurl', 'shortcode_hurl');
function shortcode_hurl() {
return home_url( '/' );
}

// Include order function file
include 'order_functions.php';

if (!function_exists('pr')) {
	function pr ( $data )
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}

function getPicupTimeArray(){
	$aTimes = array();
	for($i = 1; $i <= 24; $i += 0.5)
	{
		$szTime = str_replace('.5', ':30', (string)$i);
		$aTimeLabel = explode(':', $szTime);
		$hour = $aTimeLabel[0];
		$minute = $aTimeLabel[1] ? $aTimeLabel[1] : '00';
		$aTimes["$i"] = $hour . ':' . $minute;
	}
	return $aTimes;
}

function kitt_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s';", '%' . str_replace(get_site_url(), '', $image_url) . '%' ));
	return $attachment[0];
}

function getDiscountShippingCity(){
	return array('港区', '渋谷区', '品川区', '目黒区');
}

function getDiscountShippingPostcode(){
	return array(
		'1500022',
		'1500013',
		'1500021',
		'1500011',
		'1500012',
		'1080072',
		'1080071',
		'1080074',
		'1060031',
		'1080073',
		'1070062',
		'1410021',
		'1530061',
		'1530062',
		'1530063',
		'1500031',
		'1500042',
		'1500032',
		'1500002',
		'1500033',
		'1500046',
		'1500001',
		'1500041',
		'1500045',
		'1500034',
		'1500043',
		'1500036',
		'1500035',
		'1500044',
		'1070052',
		'1080014',
		'1050014',
		'1070061',
		'1050002',
		'1060042',
		'1060041',
		'1060045',
		'1050004',
		'1050011',
		'1050012',
		'1060032',
		'1050021',
		'1060044',
		'1050003',
		'1050001',
		'1410032',
		'1400001',
		'1410001',
		'1410031',
		'1410022',
		'1530051',
		'1530042'
	);
}

function getFlavorPrice($defaultPrice, $flavorType){
	$flavor_fee = 0;
	if (!in_array($flavorType, array('shortcake')))
	{
		$flavor_fee = $defaultPrice * KITT_FLAVOR_RATE - $defaultPrice; 
	}
	return $flavor_fee;
}

function convertPostcode($postcode){
	$postcode = str_replace('-', '', $postcode);
	return $postcode;
}

add_filter( 'woocommerce_states', 'kitt_woocommerce_states' );
function kitt_woocommerce_states( $states ) {
// 	$states['JP'] = array(
// 		'JP13' => '東京都',
// 	);
	return $states;
}

function kitt_get_year_month_day () {
	for ( $i = 1930; $i <= (date('Y') - 1); $i++ ) {
		$years[] = $i;
	}
	 
	$months = array(
		1 => __('January'),
		2 => __('February'),
		3 => __('March'),
		4 => __('April'),
		5 =>__('May'),
		6 =>__('June'),
		7 =>__('July'),
		8 =>__('August'),
		9 =>__('September'),
		10 =>__('October'),
		11 =>__('November'),
		12 =>__('December')
	);
	
	for ( $i = 1; $i <= 31; $i++ ) {
		$days[] = $i;
	}
	
	return array('years' => $years, 'months' => $months, 'days' => $days);
}
function kitt_acf_render_field_wrap($field)
{
	if (function_exists('acf_render_field_wrap')) {
		acf_render_field_wrap($field);
	}
	else {
		do_action('acf/create_field', $field);
	}
}

function kitt_get_order_type($order_id)
{
	$orderDetail = new WC_Order( $order_id );
	$items = $orderDetail->get_items();
	$order_type = wc_get_order_item_meta( key($items), '_order_type');
	return $order_type;
}

function is_custom_order($order_id)
{
	$order_type = kitt_get_order_type($order_id);
	return $order_type == KITT_CUSTOM_ORDER ? true : false;
}

function kitt_get_custom_fields()
{
	if (function_exists('acf_render_field_wrap')) {
		$postID = 1956;
		$cake_custom_fields = acf_get_fields($postID);
	}
	else {
		$postID = 1532;
		$cake_custom_fields = apply_filters('acf/field_group/get_fields', array(), $postID);
	}
	return $cake_custom_fields;
}

add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
	if (!current_user_can('level_10')) {
		//remove_menu_page( 'edit.php' );                   //Posts
		remove_menu_page( 'edit.php?post_type=page' );      //Page
		//remove_menu_page( 'upload.php' );                 //Media
		remove_menu_page( 'edit-comments.php' );          //Comments
		remove_menu_page( 'themes.php' );                 //Appearance
		//remove_menu_page( 'users.php' );                  //Users
		remove_menu_page( 'tools.php' );                  //Tools
		remove_menu_page( 'options-general.php' );        //Settings
		remove_menu_page( 'edit.php?post_type=portfolio' );
		remove_menu_page( 'edit.php?post_type=testimonial' );
		remove_menu_page( 'edit.php?post_type=team' );
		remove_menu_page( 'edit.php?post_type=essential_grid' );
		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'duplicator' );
		remove_menu_page( 'edit.php?post_type=yith-wcbm-badge' );

	}
};
function my_remove_jetpack() {
	if( class_exists( 'Jetpack' ) && !current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'jetpack' );
	}
}
add_action( 'admin_init', 'my_remove_jetpack' );

if (!current_user_can('level_10')) {
	// バージョン更新を非表示にする
	add_filter('pre_site_transient_update_core', '__return_zero');
	// APIによるバージョンチェックの通信をさせない
	remove_action('wp_version_check', 'wp_version_check');
	remove_action('admin_init', '_maybe_update_core');
}
// 管理バーのヘルプメニューを非表示にする
function my_admin_head(){
	if (!current_user_can('level_10')) {
		echo '<style type="text/css">#contextual-help-link-wrap{display:none;}</style>';
	}
 }
add_action('admin_head', 'my_admin_head');


// 親テーマ引き継ぎ用関数
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles ()
{
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
	
	if (is_checkout()) 
	{
		wp_deregister_script( 'wc-checkout');
		wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/woocommerce/assets/js/frontend/checkout.js', array( 'jquery', 'woocommerce', 'wc-country-select' ) );
	}
}
// admin login custom
function custom_login() {
	$files = '<link rel="stylesheet" href="'.get_template_directory_uri().'/admin-login.css" />';
	echo $files;
}
add_action( 'login_enqueue_scripts', 'custom_login' );
// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');


// Load jQuery within in Admin
function admin_script() {
  wp_enqueue_script('admin-script', get_template_directory_uri().'/admin.js', array('jquery'));
}
add_action('admin_enqueue_scripts', 'admin_script');

if ( ! is_admin() )
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', array(), '3.1.0');
}
function jqueryui_scripts ()
{
	wp_enqueue_script('jqueryui_js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array('jquery'), '1.8.6');
	wp_enqueue_script('fontawesome_js', 'https://use.fontawesome.com/d543855e1a.js', array(), '');
}
add_action('wp_enqueue_scripts', 'jqueryui_scripts');

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
	wp_enqueue_script('overlay_js', get_stylesheet_directory_uri() . '/js/loadingoverlay.js', array());
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('autoheight_js', get_stylesheet_directory_uri() . '/js/jQueryAutoHeight.js', array());
	wp_enqueue_style('cake_child_css', get_stylesheet_directory_uri() . '/css/fancybox.css');
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array(), false, true);
	
	// Localize the script with new data
	$phpvalues = array(
		'ajaxurl' =>  admin_url( 'admin-ajax.php' )
	);
	wp_localize_script( 'custom_js', 'jscon', $phpvalues );
}
add_action('wp_enqueue_scripts', 'icheck_scripts');

function formjs_scripts ()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js', array(), '1.8.6');
	wp_register_script('inputfocus_js', get_stylesheet_directory_uri() . '/js/jquery.inputfocus-0.9.min.js', array(
		'jquery'
	));
	wp_register_script('jsmain_js', get_stylesheet_directory_uri() . '/js/jquery.main.js', array(
		'jquery'
	));
	wp_register_script('singleproduct_js', get_stylesheet_directory_uri() . '/js/singleproduct.js', array(), false, true);
	if (!is_product()) {
		wp_enqueue_script('inputfocus_js');
		wp_enqueue_script('jsmain_js');
	}
	if (is_product()) {
		wp_enqueue_script('singleproduct_js');
	}
	/*wp_enqueue_script('inputfocus_js', get_stylesheet_directory_uri() . '/js/jquery.inputfocus-0.9.min.js', array(
		'jquery'
	));
	wp_enqueue_script('jsmain_js', get_stylesheet_directory_uri() . '/js/jquery.main.js', array(
		'jquery'
	));*/
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

function woojs_scripts ()
{
	wp_register_script( 'woocustom_js', get_stylesheet_directory_uri() . '/js/woo-custom.js');
	if (!is_product()) {
		wp_enqueue_script('woocustom_js');
	}
	//wp_enqueue_script('woocustom_js', get_stylesheet_directory_uri() . '/js/woo-custom.js');
}
add_action('wp_enqueue_scripts', 'woojs_scripts');

function customslider_scripts ()
{
	wp_register_script( 'slider_js', get_stylesheet_directory_uri() . '/js/slider.js');
	if (!is_product()) {
		wp_enqueue_script('slider_js');
	}
	//wp_enqueue_script('slider_js', get_stylesheet_directory_uri() . '/js/slider.js');
}
add_action('wp_enqueue_scripts', 'customslider_scripts');

// load es jquery only for gallery test page

add_action( 'wp_enqueue_scripts', 'call_esg_scripts' );

function call_esg_scripts() {
    wp_register_script('gridgal1_js', get_stylesheet_directory_uri() . '/js/gridgal/js/imagesloaded.pkgd.min.js', false, null, true);
	wp_register_script('gridgal2_js', get_stylesheet_directory_uri() . '/js/gridgal/js/masonry.pkgd.min.js', false, null, true);
	wp_register_script('gridgal3_js', get_stylesheet_directory_uri() . '/js/gridgal/js/classie.js', false, null, true);
	wp_register_script('gridgal4_js', get_stylesheet_directory_uri() . '/js/gridgal/js/cbpGridGallery.js', false, null, true);
	wp_register_script('gridgal6_js', get_stylesheet_directory_uri() . '/js/gridgal/js/modernizr.custom.js', false, null, true);
	wp_register_style( 'gridgal-css', get_stylesheet_directory_uri() . '/js/gridgal/css/component.css' );
	wp_register_script('featherlight_js', get_stylesheet_directory_uri() . '/js/featherlight/featherlight.js', false, null, true);
	wp_register_script('featherlightscript_js', get_stylesheet_directory_uri() . '/js/featherlight/script.js', false, null, true);
	wp_register_style('featherlight_css', get_stylesheet_directory_uri() . '/js/featherlight/featherlight.css');
if(is_page('gallery-test')){
	wp_enqueue_script('gridgal6_js'); 
    wp_enqueue_script('gridgal1_js'); 
	wp_enqueue_script('gridgal2_js');
	wp_enqueue_script('gridgal3_js');
	wp_enqueue_script('gridgal4_js');
	wp_enqueue_script('featherlight_js');
	wp_enqueue_script('featherlightscript_js');
	wp_enqueue_style('featherlight_css');
	wp_enqueue_style('gridgal-css');
}
if(is_page('gallery')){
	wp_enqueue_script('gridgal6_js'); 
    wp_enqueue_script('gridgal1_js'); 
	wp_enqueue_script('gridgal2_js');
	wp_enqueue_script('gridgal3_js');
	wp_enqueue_script('gridgal4_js');
	wp_enqueue_script('featherlight_js');
	wp_enqueue_script('featherlightscript_js');
	wp_enqueue_style('featherlight_css');
	wp_enqueue_style('gridgal-css');
}}


function gallery_scripts ()
{
// 	wp_enqueue_style('cubeportfolio_css', get_stylesheet_directory_uri() . '/js/cubeportfolio/css/cubeportfolio.css');
// 	wp_enqueue_script('cubeportfolio_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/jquery.cubeportfolio.js');
// 	wp_enqueue_script('cubeportfolioscript_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/main.js');
}
add_action('wp_enqueue_scripts', 'gallery_scripts');

function get_galposts_details(){
	$response = array();
	if (isset($_POST['post_id']))
	{
		$post_id = (int)$_POST['post_id'];
		$field_mappings = getCustomFormFieldMapping();
		$cake = get_post($post_id);
		$cakeShape = get_field('custom_order_cake_shape', $post_id);
		if (in_array($cakeShape, getArrayRoundShape())){
			$response['size'] = get_field('custom_order_cakesize_round', $post_id);
		}
		elseif (in_array($cakeShape, array('heart'))){
			$response['size'] = get_field('custom_order_cakesize_heart', $post_id);
		}
		else {
			$response['size'] = get_field('custom_order_cakesize_square', $post_id);
		}
		$response['price'] = showCakePrice(get_field('est-price', $post_id));
		$response['error'] = false;
	}
	else {
		$response['error'] = true;
	}
	echo json_encode($response); die();
}
add_action( 'wp_ajax_get_galposts_details', 'get_galposts_details' );
add_action( 'wp_ajax_nopriv_get_galposts_details', 'get_galposts_details' );

function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'woocommerce-filter-orders-by-product/woocommerce-filter-orders-by-product.php',
		'woocommerce-other-payment-gateway/woocommerce-other-payment-gateway.php',
		'wpcustom-category-image/load.php',
		'login-with-ajax/login-with-ajax.php',
		'advanced-custom-fields/acf.php',
		'wcp-contact-form/wcp-contact-form.php',
		'woo-extra-product-options/woo-extra-product-options.php',
		'woocommerce-files-upload/upload-files.php'
	);
	$active_plugins = get_option('active_plugins');
	
	$myplugins = $wp_list_table->items;
	foreach ( $myplugins as $key => $val )
	{
		if ( in_array($key, $hidearr) && in_array($key, $active_plugins))
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
	$pastDateNumber = 7;
	$pastDate = date('Y-m-d',strtotime("-$pastDateNumber days"));
	$upload_dir = wp_upload_dir();
	
	// Remove product created by custom order form but not completed the order
	$args = array(
		'post_status'      => 'publish',
		'post_type'      => 'product',
		'meta_query' => array(
			array(
				'key'     => 'is_custom_order_product',
				'value'   => '1',
				'compare' => '=',
			),
		),
		'date_query'    => array(
			'column'  => 'post_date',
			'before'   => "-$pastDateNumber days"
		)
	);
	
	$remove_products = get_posts( $args );
	if (!empty($remove_products))
	{
		foreach ($remove_products as $product)
		{
			wp_delete_post($product->ID);
		}
	}
	
	$tempFolders = array(
		$upload_dir['basedir'] . '/temp/',
	);
	foreach ($tempFolders as $tempFolder)
	{
		cleanTempFiles($tempFolder, $pastDate);
	}
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
      'label' => __('Cake Gallery', 'cake'),  // 管理画面の左メニューに表示されるテキスト
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

// Register Accept status for order
add_action( 'init', 'register_my_new_order_statuses' );
function register_my_new_order_statuses() {
	register_post_status( 'wc-accepted', array(
		'label'                     => _x( '注文受付承認', 'Order status', 'woocommerce' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Accepted <span class="count">(%s)</span>', 'Accepted <span class="count">(%s)</span>', 'cake' )
	) );
}

add_filter( 'wc_order_statuses', 'my_new_wc_order_statuses' );
// Register in wc_order_statuses.
function my_new_wc_order_statuses( $order_statuses ) {
	$order_statuses['wc-accepted'] = _x( '注文受付承認', 'Order status', 'woocommerce' );
	$order_statuses = array('wc-accepted' => _x( '注文受付承認', 'Order status', 'woocommerce' )) + $order_statuses; 
	return $order_statuses;
}

function order_send_invoice($order_id)
{
	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	$email_to_send = 'customer_invoice';
	if ( ! empty( $mails ) ) {
		foreach ( $mails as $mail ) {
			if ( $mail->id == $email_to_send ) {
				$mail->trigger( $order_id );
			}
		}
	}
}
add_action('woocommerce_order_status_pending_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_on-hold_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_processing_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_complete_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_failed_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_cancelled_to_accepted','order_send_invoice');
add_action('woocommerce_order_status_refunded_to_accepted','order_send_invoice');

add_action('woocommerce_order_status_pending_to_on-hold','order_send_invoice');
add_action('woocommerce_order_status_processing_to_on-hold','order_send_invoice');
add_action('woocommerce_order_status_complete_to_on-hold','order_send_invoice');
add_action('woocommerce_order_status_failed_to_on-hold','order_send_invoice');
add_action('woocommerce_order_status_cancelled_to_on-hold','order_send_invoice');
add_action('woocommerce_order_status_refunded_to_on-hold','order_send_invoice');


add_action( 'woocommerce_order_status_accepted_to_on-hold', 'send_email_on_hold' );
function send_email_on_hold($order_id)
{
	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	$email_to_send = 'customer_on_hold_order';
	if ( ! empty( $mails ) ) {
		foreach ( $mails as $mail ) {
			if ( $mail->id == $email_to_send ) {
				$mail->trigger( $order_id );
			}
		}
	}
}

add_action( 'woocommerce_order_status_accepted_to_processing', 'send_email_processing' );
function send_email_processing($order_id)
{
	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	$email_to_send = 'customer_processing_order';
	if ( ! empty( $mails ) ) {
		foreach ( $mails as $mail ) {
			if ( $mail->id == $email_to_send ) {
				$mail->trigger( $order_id );
			}
		}
	}
}
/*function rename_menu_to_original( $translated, $original, $domain ) {

$strings = array(
	'WooCommerce' => 'Orders',
    'WooCommerce Product Subtitle' => 'Product Subtitle',
    'Custom Header' => 'Custom Kitt'
);

if ( isset( $strings[$original] ) && is_admin() ) {
    $translations = &get_translations_for_domain( $domain );
    $translated = $translations->translate( $strings[$original] );
}

  return $translated;
}

add_filter( 'gettext', 'rename_menu_to_original', 10, 3 );

function my_text_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
    case 'WooCommerce Product Subtitle' :
        $translated_text = __( 'Product Subtitle', 'cake' );
        break;
	case 'Orders' :
        $translated_text = __( 'Orders', 'cake' );
        break;
}
return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );*/

add_action( 'woocommerce_admin_order_data_after_order_details', 'kitt_woocommerce_admin_order_data_after_order_details', 10, 1 );
function kitt_woocommerce_admin_order_data_after_order_details($order)
{
	$field_mappings = getCustomFormFieldMapping();
	$orderFormData = get_post_meta($order->id, 'cake_custom_order', true);
	$fieldGenerates = array(
		'custom_order_pickup_date' => __('Date to get cake', 'cake'),
		'custom_order_pickup_time' => __('Time to get cake', 'cake')
	);
	
	foreach ($fieldGenerates as $fieldName => $fieldLabel)
	{
		$itemField = $field_mappings[$fieldName]['field'];
		
		$defaultValue = isset($orderFormData[$fieldName]) ? (is_array($orderFormData[$fieldName]) ? implode(PHP_EOL, $orderFormData[$fieldName]) : $orderFormData[$fieldName]) : '';
		if ($itemField['type'] == 'date_picker')
		{
			$defaultValue = $defaultValue ? $defaultValue : $itemField['display_format'];
		}
		
		$itemField['name'] = 'custom_order_meta['.$itemField['name'].']';
		$itemField['value'] = $defaultValue;
		if ($fieldName == 'custom_order_pickup_time')
		{
			$itemField['choices'] = getPicupTimeArray();
		}
		?> <div class="form-field form-field-wide <?php echo $itemField['name']?>"><h3><label ><?php echo $fieldLabel ? $fieldLabel : $itemField['label'] ?></label></h3> <?php
		kitt_acf_render_field_wrap( $itemField);
		echo '</div>';
	}
	
	// Hide shipping address if method = local pickup
	$shipping_method = current($order->get_shipping_methods());
	if ($shipping_method['method_id'] == KITT_SHIPPING_PICKUP || strpos(KITT_SHIPPING_PICKUP, $shipping_method['method_id']) !== false )
	{
		echo '<style>
			#order_data .order_data_column_container .order_data_column:nth-child(3) {display: none;}
		</style>';
	}
?>
<?php
}

function custom_meta_order_detail_box_markup($post)
{
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$field_mappings = getCustomFormFieldMapping();
	
	$order = new WC_Order($post->ID);
	$order_type = kitt_get_order_type($order->id);
	
	$orderFormData = get_post_meta($order->id, 'cake_custom_order', true);
	$removeFields = array(
		'custom_order_shipping',
		'custom_order_customer_name_last',
		'custom_order_customer_name_first',
		'custom_order_customer_name_last_kana',
		'custom_order_customer_name_first_kana',
		'custom_order_customer_tel',
		'custom_order_customer_email',
		'custom_order_deliver_name',
		'custom_order_deliver_storename',
		'custom_order_deliver_cipname',
		'custom_order_deliver_tel',
		'custom_order_deliver_postcode',
		'custom_order_deliver_pref',
		'custom_order_deliver_city',
		'custom_order_deliver_addr1',
		'custom_order_deliver_addr2',
		'custom_order_customer_sex',
		'custom_order_customer_birth_date',
		'custom_order_customer_birth_date_year',
		'custom_order_customer_birth_date_month',
		'custom_order_customer_birth_date_day',
		'custom_order_msgpt_text_no',
		'custom_order_pickup_date',
		'custom_order_pickup_time'
	);
	
	$aDecorations = getDecorationOption();
	
	echo '<script>
		var gl_ajaxUrl = "'. admin_url('admin-ajax.php') .'";	
		var roundGroup = '. json_encode(getArrayRoundShape()) .';
	</script>';
	echo '<style>
		.order-detail-meta .col-left, .order-detail-meta .col-right {padding-bottom: 10px;}	
		.order-detail-meta .col-right ul li {display: inline-block; margin-right: 10px;}
		.order-detail-meta textarea {width: 100% !important; font-size: 11px;}
		.order-detail-meta input[type="text"] {width: 60% !important;}
		.disable {display: none}
		.order-detail-meta .acf-label {display: none;}
		.order-detail-meta .acf-field {margin: 0;}
	</style>';
	echo '<table class="order-detail-meta" style="clear:both; width: 100%">';
	if (!empty($orderFormData))
	{
		if ($order_type == KITT_CUSTOM_ORDER)
		{
			foreach ($field_mappings as $fieldName => $fields)
			{
				// Remove address and user info fields
				if (in_array($fieldName, $removeFields))
				{
					continue;
				}
				
				$showBlock = '';
				if (in_array($orderFormData['custom_order_cake_shape'], getArrayRoundShape()))
				{
					$showBlock = 'custom_order_cakesize_round';
				}
				elseif (in_array($orderFormData['custom_order_cake_shape'], array('heart')))
				{
					$showBlock = 'custom_order_cakesize_heart';
				}
				else {
					$showBlock = 'custom_order_cakesize_square';
				}
				
				$class = ($showBlock != $fieldName && in_array($fieldName, array('custom_order_cakesize_round', 'custom_order_cakesize_square', 'custom_order_cakesize_heart'))) ? 'disable' : '';
				
				if (isset($aDecorations[$fieldName]) && isset($aDecorations[$fieldName]['label']))
				{
					$fields['field']['label'] = $aDecorations[$fieldName]['label'];
				}
				
				$itemField = $fields['field'];
				
				$defaultValue = isset($orderFormData[$fieldName]) ? (is_array($orderFormData[$fieldName]) ? implode(PHP_EOL, $orderFormData[$fieldName]) : $orderFormData[$fieldName]) : '';
				if ($itemField['type'] == 'date_picker')
				{
					$defaultValue = $defaultValue ? $defaultValue : $itemField['display_format']; 
				}
				//start of added by kyoko
				if (isset($aDecorations[$fieldName]) && isset($aDecorations[$fieldName]['label'])) {
					if ('custom_order_icingcookie_qty' == $fieldName) {
						echo '<tr id="icingcookie_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_icingcookie"></i>'.__('Icing cookie', 'cake').'</th></tr>';
					}
					if ('custom_order_cupcake_qty' == $fieldName) {
						echo '<tr id="cupcake_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_cupcake"></i>'.__('Cup cake', 'cake').'</th></tr>';
					}
					if ('custom_order_macaron_qty' == $fieldName) {
						echo '<tr id="macaron_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_macaron"></i>'.__('Macaron', 'cake').'</th></tr>';
					}
					if ('custom_order_flowercolor' == $fieldName) {
						echo '<tr id="flower_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_flower"></i>'.__('Flower', 'cake').'</th></tr>';
					}
// 					if ('custom_order_photocakepic' == $fieldName) {
// 						echo '<tr id="photocakepic_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_print"></i>'.__('Photo Cake Pic', 'cake').'</th></tr>';
// 					}
					if ('custom_order_candy_text' == $fieldName) {
						echo '<tr id="candy_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_candy"></i>'.__('Candy', 'cake').'</th></tr>';
					}
					if ('custom_order_doll_text' == $fieldName) {
						echo '<tr id="figure_wraper" class="'.$class.' group-label"><th colspan="2" class="deco-group-label"><i class="iconkitt-kitt_icons_figure"></i>'.__('Figure doll', 'cake').'</th></tr>';
					}
					
					echo '<tr id="'.$itemField['name'].'_wraper" class="'.$class.' deco-tr">
						<td class="col-left">'.$itemField['label'].'</td>
						<td class="col-right">';
				}
				//end of added by kyoko
				else {
					echo '<tr id="'.$itemField['name'].'_wraper" class="'.$class.'">
						<td class="col-left">'.$itemField['label'].'</td>
						<td class="col-right">';
				}
				
				
				if ('custom_order_cakePic' == $fieldName || 'custom_order_photocakepic' == $fieldName)
				{
// 					if ('custom_order_cakePic' == $fieldName)
// 					{
							echo '<div class="button_upload_pic_tmp_wraper" style="display: none;">
							<div class="acf-image-uploader clearfix" data-preview_size="thumbnail" data-library="all">
								<input class="acf-image-value" type="hidden" name="'.$fieldName.'[]" value="">
								<div class="has-image">
									<div class="hover">
										<ul class="bl">
											<li><a class="acf-button-delete ir" href="#">'.__('Remove').'</a></li>
											<li><a class="acf-button-edit ir" href="#">'.__('Edit').'</a></li>
										</ul>
									</div>
									<img class="acf-image-image" src="" alt="">
								</div>
								<div class="no-image">
									<p>'.__('No image selected').' <input type="button" class="button add-image" value="'.__('Add Image').'">
								</p></div>
							</div>
						</div>';
// 					}
				}
				$args = array(
					'type' => $itemField['type'],
					'name' => 'custom_order_meta['.$itemField['name'].']',
					'value' => $defaultValue,
					'choices' => isset($fields['value']) ? $fields['value'] : ''
				);
				
				if ($fieldName == 'custom_order_cake_type')
				{
					kitt_acf_render_field_wrap( $args);
				}
				elseif ('custom_order_cakePic' == $fieldName || 'custom_order_photocakepic' == $fieldName)
				{
					// Change the name and value for multiple
					$images = explode(PHP_EOL, $defaultValue);
					foreach ($images as $image)
					{
						$picName = $itemField['_name'].'[]';
						$itemField['name'] = $picName;
						$attach_id = kitt_get_image_id($image);
						$itemField['value'] = $attach_id;
						kitt_acf_render_field_wrap( $itemField);
					}
						
				}
				
				else {
					$itemField['name'] = 'custom_order_meta['.$itemField['name'].']';
					$itemField['value'] = $defaultValue;
					if ($itemField['type'] == 'select')
					{
						$itemField['choices'] = array('' => $itemField['label'] . __('Select', 'cake')) + $itemField['choices']; 
					}
					kitt_acf_render_field_wrap( $itemField);
				}
				
				if ('custom_order_cakePic' == $fieldName)
				{
					echo '<a href="#" class="add_more_pic button add-image" style="margin-top: 10px; clear: both; display: block; width: 120px; text-align: center;">'.__('Add More Pic', 'cake').'</a>';
				}
				
				echo '</td></tr>';
			}
		}
		else {
			
			unset($orderFormData['custom_order_customer_sex']);
			unset($orderFormData['custom_order_customer_birth_date']);
			unset($orderFormData['custom_order_customer_birth_date_year']);
			unset($orderFormData['custom_order_customer_birth_date_month']);
			unset($orderFormData['custom_order_customer_birth_date_day']);
			
			foreach ($orderFormData as $metaItemKey => $metaItemVal)
			{
				$field_mappings[$metaItemKey]['field']['name'] = 'custom_order_meta['.$metaItemKey.']';
				$field_mappings[$metaItemKey]['field']['value'] = $metaItemVal;
				
				echo '<tr id="'.$metaItemKey.'_wraper" >
					<td class="col-left" style="text-align: left; width: 20%">'.$field_mappings[$metaItemKey]['field']['label'].'</td>
					<td class="col-right" style="text-align; width: 80%"> ';
				kitt_acf_render_field_wrap( $field_mappings[$metaItemKey]['field']);
				echo '</td></tr>';
			}
		}
	}
	echo '</table>';
}

add_filter('views_edit-shop_order', 'kitt_sort_status_views_edit_shop_order', 100, 1);
function kitt_sort_status_views_edit_shop_order($views)
{
	$trash = $views['trash'];
	unset($views['trash']);
	$views['trash'] = $trash;
	return $views;
}

function save_custom_order_detail_meta_box ( $post_id, $post, $update )
{
	if ( ! isset($_POST["meta-box-nonce"]) || ! wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)) ) return $post_id;
	if ( ! current_user_can("edit_post", $post_id) ) return $post_id;
	if ( defined("DOING_AUTOSAVE") && DOING_AUTOSAVE ) return $post_id;
	
	// Change shipping custom meta if order admin change shipping method
	if (is_custom_order($post_id) && $post->post_type == 'shop_order')
	{
		$order = new WC_Order($post_id);
		$GLOBALS['shipping_methods'] = $shipping_methods = $order->get_shipping_methods();
		$aCustomeOrder = get_post_meta($post_id, 'cake_custom_order', true);
		$_POST['custom_order_meta']['custom_order_cakePic'] = $_POST['custom_order_cakePic'];
		$_POST['custom_order_meta']['custom_order_photocakepic'] = $_POST['custom_order_photocakepic'];
		
		foreach ($shipping_methods as $order_item_id => $shipping_method)
		{	
			$aCustomeOrder['custom_order_shipping'] = strpos($shipping_method['method_id'], 'flat_rate') !== false ? 'delivery' : 'pickup';
		}
			
		$updatedCustomOrder = $_POST['custom_order_meta'] + $aCustomeOrder;
		
		$aImageNames = array('custom_order_cakePic', 'custom_order_photocakepic');
		foreach ($aImageNames as $imageName)
		{
			if (isset($updatedCustomOrder[$imageName]) && !empty($updatedCustomOrder[$imageName]))
			{
				$picTmp = array();
				foreach($updatedCustomOrder[$imageName] as $attach_index => $attachment_id)
				{
					if ($attachment_id)
					{
						$att_src = wp_get_attachment_image_src($attachment_id, 'full', false);
						$src = isset($att_src[0]) && $att_src[0] ? $att_src[0] : '';
						
						if ($src)
						{
							$picTmp[$attach_index] = $src;
						}
					}
					
				}
				$updatedCustomOrder[$imageName] = implode(PHP_EOL, $picTmp);
			}
		}
		// Save custom order 
		update_post_meta($post_id, "cake_custom_order", $updatedCustomOrder);
		update_post_meta($post_id, 'custom_order_pickup_date_time', $updatedCustomOrder['custom_order_pickup_date'] . ' ' . str_replace('.5', ':30', $updatedCustomOrder['custom_order_pickup_time']));
		
		// Calculate automatically shipping, taxes
		$order_item_ids = array_keys($shipping_methods);
		$item_id = $order_item_ids[0];
		$zone        = WC_Shipping_Zones::get_zone(0);
		$methods = $zone->get_shipping_methods();
		
		// Get packages
		$packages = array();
		$cart_contents = array();
		$cart_item_key = uniqid();
		
		$product_items = $order->get_items();
		$product = current($product_items);
		$product_id = $product['product_id'];
		$product_data = wc_get_product( $product_id );
		$aCustomeOrder = get_post_meta($post_id, 'cake_custom_order', true);
		
		if (!$product_data || empty($product_data))
		{
			// Create temp product then add to order
			$product_id = kitt_create_temporary_product($aCustomeOrder);
			$product_data = wc_get_product( $product_id );
			// Remove old product in order
			wc_delete_order_item(key($product_items));
			$item_id = $order->add_product( get_product( $product_id ), 1 );
			wc_add_order_item_meta($item_id, '_order_type', KITT_CUSTOM_ORDER);
		}
		$cart_contents[ $cart_item_key ] = apply_filters( 'woocommerce_add_cart_item', array_merge( array(), array(
			'product_id'	=> $product_id,
			'variation_id'	=> 0,
			'variation' 	=> array(),
			'quantity' 		=> 1,
			'data'			=> $product_data
		) ), $cart_item_key );
		
		
		$packages['contents']                 = $cart_contents;		// Items in the package
		$packages['contents_cost']            = 0;						// Cost of items in the package, set below
		$packages['applied_coupons']          = array();
		$packages['user']['ID']               = $order->user_id;
		$packages['destination']['country']   = $_POST['_shipping_country'];
		$packages['destination']['state']     = $_POST['_shipping_state'];
		$packages['destination']['postcode']  = $_POST['_shipping_postcode'];
		$packages['destination']['city']      = $_POST['_shipping_city'];
		$packages['destination']['address']   = $_POST['_shipping_address_1'];
		$packages['destination']['address_2'] = $_POST['_shipping_address_2'];
		
		$session_class  = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
		WC()->session  = new $session_class();
		WC()->customer = new WC_Customer();
		
		if (!function_exists('wc_get_chosen_shipping_method_ids'))
		{
			function wc_get_chosen_shipping_method_ids()
			{
				global $shipping_methods;
				$method_ids     = array();
				foreach ($shipping_methods as $shipping_method)
				{
					$method_ids[] = $shipping_method['method_id'];
				}
					
				return $method_ids;
			}
		}
		foreach ($methods as $methodId => $method)
		{
			// Calculate order taxes, shipping
			if ($updatedCustomOrder['custom_order_shipping'] == 'delivery' && "$method->id:$methodId" == KITT_SHIPPING_DELIVERY)
			{
// 				wc_update_order_item_meta( $item_id, 'cost', $method->cost );
				wc_update_order_item_meta( $item_id, 'cost', current($_POST['shipping_cost']) );
				$method->calculate_shipping($packages);
// 				wc_update_order_item_meta( $item_id, 'taxes', $method->rates[KITT_SHIPPING_DELIVERY]->taxes );
				$order->calculate_taxes();
				wc_update_order_item_meta( $item_id, 'taxes', array(1 => $order->get_shipping_tax()) );
				
			}
			elseif ($updatedCustomOrder['custom_order_shipping'] == 'pickup' && "$method->id:$methodId" == KITT_SHIPPING_PICKUP)
			{
				wc_update_order_item_meta( $item_id, 'cost', $method->cost );
				wc_update_order_item_meta( $item_id, 'taxes', array() );
				
				$method->calculate_shipping();
			}
		}
		$order->calculate_totals();
	}
	return $post_id;
}
add_action("save_post", "save_custom_order_detail_meta_box", 10, 3);


function add_custom_order_detail_meta_box($postType)
{
	if ($postType == 'shop_order')
	{
		add_meta_box("order-detail-meta-box", __('Order details', 'cake'), "custom_meta_order_detail_box_markup", "shop_order", "normal");
		remove_meta_box('postcustom', null, 'normal');
		add_meta_box('postcustom', __('Custom Fields'), 'post_custom_meta_box', 'shop_order', 'normal');
	}
}
add_action("add_meta_boxes", "add_custom_order_detail_meta_box");

// Remove essential grid meta box in order detail
function tp_remove_metabox_from_all_post_types ()
{
	if ( is_admin() && current_user_can('manage_options') )
	{
		$post_types = array('shop_order');
		foreach ( $post_types as $post_type )
		{
			remove_meta_box('eg-meta-box', $post_type, 'normal');
		}
	}
}
add_action('add_meta_boxes', 'tp_remove_metabox_from_all_post_types', 999);

//My Account

function storefront_child_remove_unwanted_form_fields($fields) {
    unset( $fields ['account_company'] );
    return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'storefront_child_remove_unwanted_form_fields' );


// Add accepted status for payment
function woocommerce_valid_order_statuses_for_payment_custom_order ( $valid_order_statuses )
{
	foreach($valid_order_statuses as $index => $status)
	{
		if ($status == 'pending' || $status == 'failed')
		{
			unset($valid_order_statuses[$index]);
		}
	}
	$valid_order_statuses[] = 'accepted';
	$valid_order_statuses[] = 'on-hold';
	return $valid_order_statuses;
}
add_filter( 'woocommerce_valid_order_statuses_for_payment', 'woocommerce_valid_order_statuses_for_payment_custom_order', 10, 3 );
add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', 'woocommerce_valid_order_statuses_for_payment_custom_order', 10, 3 );

function disable_page_wpautop() {
	if ( is_page() ) remove_filter( 'the_content', 'wpautop' );
}
add_action( 'wp', 'disable_page_wpautop' );

add_action( 'register_new_user', 'autoLoginUser', 10, 1 );
function autoLoginUser($user_id){
	$user = get_user_by( 'id', $user_id );
	if( $user && isset($_POST['login-with-ajax']) ) {
		wp_set_password($_POST['user_password'], $user_id);
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_login, $user);
	}
}


add_filter( 'lwa_ajax_login', 'kitt_lwa_ajax_login_get_data', 10, 1 );
add_filter( 'lwa_ajax_register', 'kitt_lwa_ajax_login_get_data', 10, 1 );
function kitt_lwa_ajax_login_get_data($response)
{
	if ($response['result'] == 1)
	{
		$userID = $response['user']->ID;
		$user = get_user_by('id', $userID);
		// Get user information like address + acc
		$birthDate = get_user_meta($userID, 'birth_date', true);
		$response['user_info']['custom_order_customer_name_first'] = get_user_meta($userID, 'first_name', true);
		$response['user_info']['custom_order_customer_name_last'] = get_user_meta($userID, 'last_name', true);
		$response['user_info']['custom_order_customer_name_first_kana'] = get_user_meta($userID, 'first_name_kana', true);
		$response['user_info']['custom_order_customer_name_last_kana'] = get_user_meta($userID, 'last_name_kana', true);
		$response['user_info']['custom_order_customer_email'] = $user->user_email;
		$response['user_info']['custom_order_customer_tel'] = get_user_meta($userID, 'tel', true);
		$response['user_info']['custom_order_customer_sex'] = get_user_meta($userID, 'sex', true);
		$response['user_info']['custom_order_customer_birth_date[year]'] = $birthDate['year'];
		$response['user_info']['custom_order_customer_birth_date[month]'] = $birthDate['month'];
		$response['user_info']['custom_order_customer_birth_date[day]'] = $birthDate['day'];
		
		
		$response['user_address']['custom_order_deliver_name'] = get_user_meta($userID, 'shipping_last_name', true);
		$response['user_address']['custom_order_deliver_storename'] = get_user_meta($userID, 'shipping_first_name', true);
		$response['user_address']['custom_order_deliver_cipname'] = get_user_meta($userID, 'shipping_company', true);
		$response['user_address']['custom_order_deliver_tel'] = get_user_meta($userID, 'shipping_phone', true);
		$response['user_address']['custom_order_deliver_addr1'] = get_user_meta($userID, 'shipping_address_1', true);
		$response['user_address']['custom_order_deliver_addr2'] = get_user_meta($userID, 'shipping_address_2', true);
		$response['user_address']['custom_order_deliver_city'] = get_user_meta($userID, 'shipping_city', true);
		$response['user_address']['custom_order_deliver_pref'] = get_user_meta($userID, 'shipping_state', true);
		$response['user_address']['custom_order_deliver_postcode'] = get_user_meta($userID, 'shipping_postcode', true);
	}
	
	return $response;
}

add_action('wp_ajax_nopriv_get_user_address_data', 'kitt_get_user_address_data');
add_action('wp_ajax_get_user_address_data', 'kitt_get_user_address_data');
function kitt_get_user_address_data(){
	$userID = get_current_user_id();
	$user = get_user_by('id', $userID);
	// Get user information like address + acc
	$response = array();
	$response['user_address']['custom_order_deliver_name'] = get_user_meta($userID, 'shipping_last_name', true);
	$response['user_address']['custom_order_deliver_storename'] = get_user_meta($userID, 'shipping_first_name', true);
	$response['user_address']['custom_order_deliver_cipname'] = get_user_meta($userID, 'shipping_company', true);
	$response['user_address']['custom_order_deliver_tel'] = get_user_meta($userID, 'shipping_phone', true);
	$response['user_address']['custom_order_deliver_addr1'] = get_user_meta($userID, 'shipping_address_1', true);
	$response['user_address']['custom_order_deliver_addr2'] = get_user_meta($userID, 'shipping_address_2', true);
	$response['user_address']['custom_order_deliver_city'] = get_user_meta($userID, 'shipping_city', true);
	$response['user_address']['custom_order_deliver_pref'] = get_user_meta($userID, 'shipping_state', true);
	$response['user_address']['custom_order_deliver_postcode'] = get_user_meta($userID, 'shipping_postcode', true);
	echo json_encode($response);die;
}

function woocommerce_save_account_details_custom ($userID)
{
	update_user_meta($userID, 'first_name_kana', $_POST['account_first_name_kana']);
	update_user_meta($userID, 'last_name_kana', $_POST['account_last_name_kana']);
	update_user_meta($userID, 'tel', $_POST['account_tel']);
	update_user_meta($userID, 'company', $_POST['account_company']);
	update_user_meta($userID, 'sex', $_POST['account_sex']);
	update_user_meta($userID, 'birth_date', $_POST['birth_date']);
}
add_action( 'woocommerce_save_account_details', 'woocommerce_save_account_details_custom' );

function extraFieldForShipping(){
	return array(
		'shipping_phone' => array(
			'label'     => __('Phone', 'woocommerce'),
			'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true
		),
	);
}

function extraFieldForBilling(){
	return array(
		'billing_last_name_kana' => array(
			'label'     => __('姓(ふりがな)', 'woocommerce'),
			'placeholder'   => _x('姓(ふりがな)', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-first'),
			'clear'     => false
		),
		'billing_first_name_kana' => array(
			'label'     => __('名(ふりがな)', 'woocommerce'),
			'placeholder'   => _x('名(ふりがな)', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-last'),
			'clear'     => true
		),
	);
}

add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_admin_shipping_fields_extra', 10, 1 );
function woocommerce_admin_shipping_fields_extra($fields){
	$fields['phone'] = array(
		'label' => __( 'Phone', 'woocommerce' ),
		'show'  => false
	);
	
	$fields['last_name']['label'] = '宛名';
	$fields['first_name']['label'] = '店舗名';
	$fields['company']['label'] = '担当者名';
	
	return $fields;
}

// Add phone and store name in shipping address
add_filter( 'woocommerce_checkout_fields' , 'shipping_override_checkout_fields' );
function shipping_override_checkout_fields( $fields ) {
	$fields['shipping'] = $fields['shipping'] + extraFieldForShipping();
	$fields['shipping']['shipping_last_name']['label'] = '宛名';
	$fields['shipping']['shipping_first_name']['label'] = '店舗名';
	$fields['shipping']['shipping_company']['label'] = '担当者名';
	$fields['shipping']['shipping_postcode']['label'] = '郵便番号';
	$fields['shipping']['shipping_city']['label'] = '市区町村';
	$fields['shipping']['shipping_address_1']['label'] = '町名・番地';
	$fields['shipping']['shipping_address_2']['label'] = '建物・マンション名以降';
	// required
	$fields['shipping']['shipping_company']['required'] = true;
	
	return $fields;
}

//change label and class for shipping fields on front
add_filter( 'woocommerce_shipping_fields', 'custom_woocommerce_shipping_fields' );
function custom_woocommerce_shipping_fields( $fields ) {
	$fieldExtras = extraFieldForShipping();
	$fields = insertAtSpecificIndex($fields, $fieldExtras, array_search('shipping_company', array_keys($fields)) + 1);
	
	//change class
	$fields['shipping_last_name'] = array(
	'label'     => __('宛名', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first')
     );
	$fields['shipping_first_name'] = array(
	'label'     => __('店舗名', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
	'clear'     => true
     );
	$fields['shipping_company'] = array(
	'label'     => __('担当者名', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first')
     );
	$fields['shipping_phone'] = array(
	'label'     => __('電話番号', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
	'clear'     => true
     );
	$fields['shipping_postcode'] = array(
	'label'     => __('郵便番号', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first'),
    'clear'     => true
     );
	$fields['shipping_city'] = array(
	'label'     => __('市区町村', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
    'clear'     => true
     );
	$fields['shipping_address_1'] = array(
	'label'     => __('町名・番地', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first')
     );
	$fields['shipping_address_2'] = array(
	'label'     => __('建物・マンション名以降', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
    'clear'     => true
     );
	
	//change order
	$order = array(
        "shipping_last_name", 
        "shipping_first_name", 
        "shipping_company", 
        "shipping_phone", 
        "shipping_postcode", 
        "shipping_state", 
        "shipping_city", 
        "shipping_address_1", 
        "shipping_address_2",
		"shipping_country",

    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields[$field];
    }

    $fields = $ordered_fields;
	
	return $fields;
}
// Billing address

add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_admin_billing_fields_extra', 10, 1 );
function woocommerce_admin_billing_fields_extra($fields){
	$fieldExtras['first_name_kana'] = array(
		'label' => __( '名(ふりがな)', 'woocommerce' ),
		'show'  => false
	);
	
	$fieldExtras['last_name_kana'] = array(
		'label' => __( '姓(ふりがな)', 'woocommerce' ),
		'show'  => false
	);
	
	$fields = insertAtSpecificIndex($fields, $fieldExtras, array_search('last_name', array_keys($fields)) + 1);
	
	$fields['last_name'] = array(
		'label'     => __('姓', 'woocommerce'),
	);
	$fields['first_name'] = array(
		'label'     => __('名', 'woocommerce'),
	);
	
	unset($fields['company']);
	unset($fields['address_1']);
	unset($fields['address_2']);
	unset($fields['city']);
	unset($fields['postcode']);
	unset($fields['country']);
	unset($fields['state']);
	unset($fields['postcode']);
	unset($fields['city']);
	return $fields;
}
//My account menu name change
function wpb_woo_my_account_order() {
 $myorder = array(
 'dashboard' => __( 'Dashboard', 'woocommerce' ),
 'edit-account' => __( 'アカウント情報', 'woocommerce' ),
 'orders' => __( '注文履歴', 'woocommerce' ),
 'edit-address' => __( '配送先住所', 'woocommerce' ),
 'payment-methods' => __( 'Payment Methods', 'woocommerce' ),
 'customer-logout' => __( 'Logout', 'woocommerce' ),
 );
 return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );

// Add phone and store name in billing address
add_filter( 'woocommerce_checkout_fields' , 'billing_override_checkout_fields' );
function billing_override_checkout_fields( $fields ) {
	$fieldExtras = extraFieldForBilling();
	$fields['billing'] = insertAtSpecificIndex($fields['billing'], $fieldExtras, array_search('billing_first_name', array_keys($fields['billing'])) + 1);
	return $fields;
}

add_filter( 'woocommerce_billing_fields', 'custom_woocommerce_billing_fields' );
function custom_woocommerce_billing_fields( $fields ) {
	$fieldExtras = extraFieldForBilling();
	$fields = insertAtSpecificIndex($fields, $fieldExtras, array_search('billing_first_name', array_keys($fields)) + 1);
	
	unset($fields['billing_company']);
    unset($fields['billing_address_1']);
    unset($fields['billing_address_2']);
    unset($fields['billing_city']);
    unset($fields['billing_postcode']);
    unset($fields['billing_country']);
    unset($fields['billing_state']);
    unset($fields['billing_postcode']);
    unset($fields['billing_city']);
	
	//change class
	$fields['billing_last_name'] = array(
	'label'     => __('姓', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first')
     );
	$fields['billing_first_name'] = array(
	'label'     => __('名', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
	'clear'     => true
     );
	$fields['billing_last_name_kana'] = array(
	'label'     => __('姓(ふりがな)', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-first')
     );
	$fields['billing_first_name_kana'] = array(
	'label'     => __('名(ふりがな)', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
	'clear'     => true
     );
	
	//change order
	$order = array(
        "billing_last_name", 
        "billing_first_name", 
		"billing_last_name_kana", 
        "billing_first_name_kana", 
        "billing_email", 
        "billing_phone"

    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields[$field];
    }

    $fields = $ordered_fields;
	
	return $fields;
}


//thank you page

function kittwp_title_order_received( $text, $order ) {
	if (!$order) return '';
	foreach ( $order->get_items() as $item )
	{
		$_product = wc_get_product($item['product_id']);
		$is_custom_order_product = get_post_meta($item['product_id'], 'is_custom_order_product', true);
		//$methods = WC()->payment_gateways->payment_gateways($order);
		$bacs_class = get_post_meta($order->id, '_payment_method', true) == 'bacs';
		$stripe_class = get_post_meta($order->id, '_payment_method', true) == 'stripe';
		$waiting_class = get_post_meta($order->id, '_payment_method', true) == 'other_payment';
		if ( $is_custom_order_product && $waiting_class )
		{
			return '<h1 class="big-title big-thanks">Thank you!</h1><p>オーダーメイドケーキのご注文を承りました。こちらで注文内容を確認次第、担当者から連絡させていただきます。</p>';
		}
		elseif ( $is_custom_order_product && $bacs_class )
		{
			return '<h1 class="big-title big-thanks payment-intro jp">お支払いのご案内</h1><p>オーダーメイドケーキのご注文を受け付けました。<strong>ご入金確認後、ご注文受付が完了となり、ケーキの制作が開始されます。</strong></p>';
		}
		elseif ( $is_custom_order_product && $stripe_class )
		{
			return '<h1 class="big-title big-thanks payment-intro jp">カード決済完了</h1><p>お支払いありがとうございます。<br/>オーダーメイドケーキのご注文受付が完了となりました。</p>';
		}
		elseif ( !$is_custom_order_product && $waiting_class )
		{
			return '<h1 class="big-title big-thanks">Thank you!</h1><p>以下のケーキのご注文を承りました。こちらで注文内容を確認次第、<strong>請求書メール</strong>が送信されますので、そちらで<strong>お支払い後、注文が確定</strong>となります。</p>';
		}
		elseif ( !$is_custom_order_product && $bacs_class )
		{
			return '<h1 class="big-title big-thanks payment-intro jp">お支払いのご案内</h1><p>以下のケーキのご注文を受け付けました。<strong>ご入金確認後、ご注文受付が完了となります。</strong></p>';
		}
		elseif ( !$is_custom_order_product && $stripe_class )
		{
			return '<h1 class="big-title big-thanks payment-intro jp">カード決済完了</h1>お支払いありがとうございます。<br/>以下のケーキのご注文受付が完了となりました。</strong>';
		}
	}
}
add_filter( 'woocommerce_thankyou_order_received_text', 'kittwp_title_order_received', 10, 2 );

//shipping address checked
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );

//show title for shipping on checkout page
function kitt_title_shipping() {
echo '<h4 class="heading-form mt-4 mb-2 text-gray">どこにデリバリーをご希望ですか?</h3>';
}
 
add_action( 'woocommerce_before_checkout_shipping_form', 'kitt_title_shipping', 1 );

//show notice for shipping
function kitt_notice_shipping() {
echo '<ul class="notice"><li>お届け先が商業施設の場合には必ず「ケーキの持ち込みが可能」であることをご確認ください。</li><li>お届け先にお客様が不在の場合は、必ず代理の方のお名前を担当者名としてご記入ください。</li><li>お届け後、ケーキのご利用まで長時間空いてしまう場合は、お届け先にケーキの収まる「冷蔵庫」があることをご確認ください。</li></ul>';
}
 
add_action( 'woocommerce_before_order_notes', 'kitt_notice_shipping' );

//Change the Billing Address checkout label
function wc_billing_field_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Billing Details' :
$translated_text = __( 'Enter your information', 'woocommerce' );
break;
}
return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );

//add birthday and sex for user account
/**
 * Add new fields above 'Update' button.
 *
 * @param WP_User $user User object.
 */
function tm_additional_profile_fields( $user ) {

	$yearMonthDays = kitt_get_year_month_day();
	$birth_date = get_user_meta( get_current_user_id(), 'birth_date', true);
	$default	= array( 'day' => 1, 'month' => 1, 'year' => 1980, );
	$birth_date = $birth_date ? $birth_date : $default;
	$sexs = array('male' => __('Male', 'cake'), 'female' => __('Female', 'cake'));

    ?>
    <h3><?php _e( 'Extra profile information', 'woocommerce' ); ?></h3>

    <table class="form-table">
   	 <tr>
   		 <th><label for="birth-date-day"><?php _e( 'Birth date', 'woocommerce' ); ?></label></th>
   		 <td>
   		 <select id="birth-date-year" name="birth_date[year]"><?php
	   		 foreach($yearMonthDays['years'] as $yearNumber) {
	   		 	printf( '<option value="%1$s" %2$s>%1$s</option>', $yearNumber, selected( $birth_date['year'], $yearNumber, false ) );
	   		 }
   			 ?></select>
   			 <select id="birth-date-month" name="birth_date[month]"><?php
	   			 foreach ( $yearMonthDays['months'] as $monthNumber => $monthText ) {
	   			 	printf( '<option value="%1$s" %2$s>%3$s</option>', $monthNumber, selected( $birth_date['month'], $monthNumber, false ), $monthText );
	   			 }
   			 ?></select>
   			 <select id="birth-date-day" name="birth_date[day]"><?php
   			 foreach($yearMonthDays['days'] as $dayNumber) {
   			 	printf( '<option value="%1$s" %2$s>%1$s</option>', $dayNumber, selected( $birth_date['day'], $dayNumber, false ) );
   			 }
   			 ?></select>
   		 </td>
   	 </tr>
   	 <tr>
   		 <th><label for="sex"><?php _e( 'Sex', 'cake' ); ?></label></th>
   		 <td>
   		 <select id="sex" name="sex"><?php
   				 foreach ( $sexs as $sexKey => $sex ) {
   					 printf( '<option value="%1$s" %2$s>%3$s</option>', $sexKey, selected( get_user_meta(get_current_user_id(), 'sex', true), $sexKey, false ), $sex );
   				 }
   			 ?></select>
   		 </td>
   	 </tr>
    </table>
    <?php
}
/**
 * Save additional profile fields.
 *
 * @param  int $user_id Current user ID.
 */
function tm_save_profile_fields( $user_id ) {

    if ( ! current_user_can( 'edit_user', $user_id ) ) {
   	 return false;
    }

    if ( empty( $_POST['birth_date'] ) ) {
   	 return false;
    }
	if ( empty( $_POST['sex'] ) ) {
   	 return false;
    }

    update_usermeta( $user_id, 'birth_date', $_POST['birth_date'] );
	update_usermeta( $user_id, 'sex', $_POST['sex'] );
}

add_action( 'personal_options_update', 'tm_save_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_profile_fields' );

add_action( 'show_user_profile', 'tm_additional_profile_fields' );
add_action( 'edit_user_profile', 'tm_additional_profile_fields' );

add_action( 'woocommerce_checkout_after_customer_details', 'extra_delivery_fields_in_checkout_page' );
function extra_delivery_fields_in_checkout_page( $checkout ) {
?>
	<ul>
		<li class="main-option">
			<h4 class="heading-form display-table mb-3">
				<span class="title-number display-table-cell">2</span>
				<span class="display-table-cell pl-2"><?php _e('When do you want your order delivered?', 'woocommerce')?></span>
			</h4>
			<div class="row">
				<div class="col-md-6 columns">
					<label class="label mb-2">
						<i class="icon-outline-kitt_icons_calendar01"></i>
						<?php _e('Pick Up Date', 'woocommerce')?>
					</label>
					<div class="calendar"></div>
					<input type="hidden" name="cake_custom_order[custom_order_pickup_date]" id="custom_order_pickup_date" value="<?php echo date('Y-m-d')?>"/>
					<span class="text_helper">通常1日前までのオーダーのみ受け付けておりますが、場合によってはそれより短い期間でも製造可能ですので、店舗にお問合せ下さい。</span>
				</div>
				<div class="col-md-6 columns">
					<label class="label mb-2">
						<i class="icon-outline-kitt_icons_clock"></i>
						<?php _e('Pick Up Time', 'woocommerce')?>
					</label>
					<div class="timepicker">
						<div class="timepick">
						<h3 class="input no-interaction text-center display-table width-full"><div class="display-table-cell"><output></output></div></h3>
							<div class="time-range display-table width-full mt-2">
								<div class="time-range__minus display-table-cell">
									<button type="button" class="button button--ghost circle">-</button>
								</div>
								<div class="display-table-cell">
									<input type="range" id="order_pickup_time" name="cake_custom_order[custom_order_pickup_time]" min="15" max="23" step="0.5" value="15" data-rangeslider />
								</div>
								<div class="time-range__plus display-table-cell">
									<button type="button" class="button button--ghost circle">+</button>
								</div>
							</div>
							<!--/time-range-->
						</div>
						<!--/timepick-->
					</div>
					<!--/timepicker-->
				</div>
			</div>
		</li>
		<?php showSurveyCheckout(); ?>
	</ul>
<?php
}

function showSurveyCheckout()
{
	$userID = (int) get_current_user_id();
	$user_data = get_userdata( $userID );
	
	$meta_query_args = array(
		'relation' => 'AND',
		array(
			'key'     => '_customer_user',
			'value'   => $userID,
			'compare' => '='
		),
		array(
			'key'     => 'cake_custom_order',
			'value'   => '',
			'compare' => '!='
		)
	);
	
	$customer_orders = get_posts( array(
		'numberposts' => 1,
		'meta_query'    => $meta_query_args,
		'post_type'   => wc_get_order_types(),
		'post_status' => array_keys( wc_get_order_statuses() ),
	) );
	
	$yearMonthDays = kitt_get_year_month_day();
	$current_year = date('Y');
?>
<!--Start show this only for first time order by user or guest-->
	<?php if (!$userID || ($userID && empty($customer_orders))) {?>
		<li class="main-option">
		<h4 class="heading-form display-table mb-3">
			<span class="title-number display-table-cell">3</span>
			<span class="display-table-cell pl-2"><?php _e('アンケート', 'cake')?></span>
		</h4>
		<div class="form-fields question-form">
		<div class="row">
			<div class="field col-xs-12">
				<label class="label required"><?php _e( '当店をどこで知りましたか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_a" class="radio_input validate[required]" value="SNS">
						<label for="q01_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">SNS</span></h5>
							</div>
						</label>
						<!--show this if SNS is checked-->
						<span class="dropdown" style="display: none;" id="engine_sns">
							<select name="survey[social]">
								<option value="Instagram">Instagram</option>
								<option value="facebook">facebook</option>
								<option value="Twitter">Twitter</option>
								<option value="LINE＠">LINE＠</option>
								<option value="その他（記入）">その他（記入）</option>
							</select>
							
							<!--show this if その他（記入） is selected-->
							<span class="block_textarea" style="display: none;" id="engine_sns_comment">
								<textarea name="survey[social_comment]" class="validate[required]" placeholder=""></textarea>
							</span>
							<!--/show this if その他（記入） is selected-->
						</span>
						<!--/show this if SNS is checked-->
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_b" class="radio_input validate[required]" value="知人の紹介">
						<label for="q01_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">知人の紹介</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_d" class="radio_input validate[required]" value="雑誌">
						<label for="q01_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">雑誌</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_e" class="radio_input validate[required]" value="ポスター">
						<label for="q01_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">ポスター</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_f" class="radio_input validate[required]" value="インターネット">
						<label for="q01_f" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">インターネット</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_c" class="radio_input validate[required]" value="その他（記入）">
						<label for="q01_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">その他（記入）</span></h5>
							</div>
						</label>
						<!--show if その他（記入）is checked-->
						<span class="block_textarea" style="display: none;" id="engine_other">
							<textarea name="survey[engine_other]" class="validate[required]" placeholder=""></textarea>
						</span>
						<!--/show if その他（記入）is checked-->
					</li>
				</ul>
			</div>
			
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '当店のケーキをご注文されたことがありますか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[placed]" id="q02_a" class="radio_input" value="はい">
						<label for="q02_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">はい</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[placed]" id="q02_b" class="radio_input" value="いいえ">
						<label for="q02_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">いいえ</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '最近のご利用日を教えてください', 'woocommerce' ); ?></label>
				<div class="row">
					<div class="col-sm-4">
						<span class="dropdown"><select name="survey[use][year]" class="">
							<!--show from 2017-->
							<option value="">年を選択</option>
							<?php for($i = $current_year - 1; $i <= $current_year; $i ++) { ?>
							<option value="<?php echo $i?>"><?php echo $i?></option>
							<?php }?>
							</select></span><!--/dropdown-->
					</div>
					<div class="col-sm-4">
						<span class="dropdown"><select name="survey[use][month]" class="">
							<!--show All month-->
							<option value="">月を選択</option>
							<?php foreach($yearMonthDays['months'] as $monthNumber) { ?>
							<option value="<?php echo $monthNumber?>"><?php echo $monthNumber?></option>
							<?php }?>
						</select></span><!--/dropdown-->
					</div>
					<div class="col-sm-4">
						<span class="dropdown"><select name="survey[use][day]" class="">
							<!--show All dates-->
							<option value="">日を選択</option>
							<?php foreach($yearMonthDays['days'] as $dayNumber) { ?>
							<option value="<?php echo $dayNumber?>日"><?php echo $dayNumber?>日</option>
							<?php }?>
						</select></span><!--/dropdown-->
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'ご利用回数は何回目ですか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_a" class="radio_input" value="初めて">
						<label for="q04_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">初めて</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_b" class="radio_input" value="2回目">
						<label for="q04_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">2回目</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_c" class="radio_input" value="3回目以上">
						<label for="q04_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">3回目以上</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'お値段についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_a" class="radio_input" value="大変満足">
						<label for="q06_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_b" class="radio_input" value="満足">
						<label for="q06_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_c" class="radio_input" value="普通">
						<label for="q06_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_d" class="radio_input" value="やや不満">
						<label for="q06_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_e" class="radio_input" value="不満">
						<label for="q06_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'お味についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_a" class="radio_input" value="大変満足">
						<label for="q05_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_b" class="radio_input" value="満足">
						<label for="q05_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_c" class="radio_input" value="普通">
						<label for="q05_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_d" class="radio_input" value="やや不満">
						<label for="q05_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_e" class="radio_input" value="不満">
						<label for="q05_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<!--added newly-->
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'デザインについてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_a" class="radio_input" value="大変満足">
						<label for="q062_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_b" class="radio_input" value="満足">
						<label for="q062_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_c" class="radio_input" value="普通">
						<label for="q062_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_d" class="radio_input" value="やや不満">
						<label for="q062_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_e" class="radio_input" value="不満">
						<label for="q062_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<!--/added newly-->
							
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '特に良かった点についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_a" class="radio_input survey_particular" value="価格">
						<label for="q07_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">価格</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_b" class="radio_input survey_particular" value="味">
						<label for="q07_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">味</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_c" class="radio_input survey_particular" value="デザイン">
						<label for="q07_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">デザイン</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_d" class="radio_input survey_particular" value="接客サービス">
						<label for="q07_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">接客サービス</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_e" class="radio_input survey_particular" value="メニュー">
						<label for="q07_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">メニュー</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio list_full_radio">
						<input type="radio" name="survey[particular]" id="q07_f" class="radio_input survey_particular" value="その他">
						<label for="q07_f" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">その他（記入）</span></h5>
							</div>
						</label>
						<!--show input_show when above radio is selected-->
						<div class="input_show" id="survey_particular_comment" style="display: none;">
							<textarea name="survey_comment" class="validate[required]" placeholder=""></textarea>
						</div>
					</li>
				</ul>
			</div>
		</div>
							
		<!--added newly-->
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'ご意見・ご希望等ございましたら、お聞かせください', 'woocommerce' ); ?></label>
				<div class="input_textarea"><textarea name="survey[other_comment]" class="" ></textarea></div>
			</div>
		</div>
		<!--/added newly-->
	</div>
	</li>
	<?php }?>
	<!--End show this only for first time order by user or guest-->
<?php
}
add_action('woocommerce_after_checkout_billing_form', 'kitt_woocommerce_after_checkout_billing_form', 10, 3);
function kitt_woocommerce_after_checkout_billing_form($checkout)
{
?>
<div class="row">
		<div class="field col-md-6">
			<label class="label">性別</label>
			<ul class="account_sex text-radio list-type">
				<li class="m-input__radio">
					<input type="radio" name="cake_custom_order[custom_order_customer_sex]" id="account_sex_male" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'male', true )?> value="male">
					<label for="account_sex_male" class="js-fixHeightChildText radio_label">
						<div class="radio_option radio_size">
							<h5 class="js-fixHeightChildTitle radio_option_caption">
								<span class="caption_wrap"><?php _e( 'Male', 'woocommerce' ); ?></span>
							</h5>
						</div>
					</label>
				</li>
				<li class="m-input__radio">
					<input type="radio" name="cake_custom_order[custom_order_customer_sex]" id="account_sex_female" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'female', true )?> value="female">
					<label for="account_sex_female" class="js-fixHeightChildText radio_label">
						<div class="radio_option radio_size">
							<h5 class="js-fixHeightChildTitle radio_option_caption">
								<span class="caption_wrap"><?php _e( 'Female', 'woocommerce' ); ?></span>
							</h5>
						</div>
					</label>
				</li>
			</ul>
		</div>
		<div class="field col-md-6 birth-field">
			<label class="label"><?php _e( 'Birth date', 'cake' ); ?></label>
			<?php 
			$yearMonthDays = kitt_get_year_month_day();
			$birth_date = get_user_meta( get_current_user_id(), 'birth_date', true);
			$default	= array( 'day' => 1, 'month' => 1, 'year' => 1980, );
			$birth_date = $birth_date ? $birth_date : $default;
			?>
			<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide " >
				<select id="birth-date-year" name="cake_custom_order[custom_order_customer_birth_date_year]" required class="form-control select select-primary static-select">>
					<option value=""><?php echo __('Select Birth Year', 'woocommerce')?></option>
					<?php
		   				 foreach($yearMonthDays['years'] as $yearNumber) {
		   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $yearNumber, selected( $birth_date['year'], $yearNumber, false ) );
		   				 }
		   			 ?></select>
		   			 <select id="birth-date-month" name="cake_custom_order[custom_order_customer_birth_date_month]" required class="form-control select select-primary static-select" >>
		   			 <option value=""><?php echo __('Select Birth Month', 'woocommerce')?></option>
		   			 <?php
		   				 foreach ( $yearMonthDays['months'] as $monthNumber => $monthText ) {
		   					 printf( '<option value="%1$s" %2$s>%3$s</option>', $monthNumber, selected( $birth_date['month'], $monthNumber, false ), $monthText );
		   				 }
		   			 ?></select>
		   			 <select id="birth-date-day" name="cake_custom_order[custom_order_customer_birth_date_day]" required class="form-control select select-primary static-select">>
		   			 <option value=""><?php echo __('Select Birth Day', 'woocommerce')?></option>
		   			 <?php
		   			 foreach($yearMonthDays['days'] as $dayNumber) {
		   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $dayNumber, selected( $birth_date['day'], $dayNumber, false ) );
		   				 }
		   			 ?></select>
		   		 </td>
			</p>
		</div>
	</div>
<?php	
}
add_action( 'woocommerce_checkout_update_order_meta', 'kitt_custom_checkout_field_update_order_meta' );
function kitt_custom_checkout_field_update_order_meta( $order_id ) 
{
	$userID = get_current_user_id();
	if ( isset($_POST['cake_custom_order']) )
	{
		if ( isset($_POST['cake_custom_order']['custom_order_customer_birth_date_year']) )
		{
			$birth_date = array(
				'year' => $_POST['cake_custom_order']['custom_order_customer_birth_date_year'],
				'month' => $_POST['cake_custom_order']['custom_order_customer_birth_date_month'],
				'day' => $_POST['cake_custom_order']['custom_order_customer_birth_date_day']
			);
			update_user_meta($userID, 'birth_date', $birth_date);
		}
		
		if ( isset($_POST['cake_custom_order']['custom_order_customer_sex']) )
		{
			update_user_meta($userID, 'sex', $_POST['cake_custom_order']['custom_order_customer_sex']);
		}
		
		update_user_meta($userID, 'first_name_kana', $_POST['billing_first_name_kana']);
		update_user_meta($userID, 'last_name_kana', $_POST['billing_last_name_kana']);
		update_user_meta($userID, 'tel', $_POST['billing_phone']);
		
		unset($_POST['cake_custom_order']['custom_order_customer_birth_date_year']);
		unset($_POST['cake_custom_order']['custom_order_customer_birth_date_month']);
		unset($_POST['cake_custom_order']['custom_order_customer_birth_date_day']);
		unset($_POST['cake_custom_order']['custom_order_customer_sex']);
		
		update_post_meta($order_id, 'cake_custom_order', $_POST['cake_custom_order']);
		update_post_meta($order_id, 'survey_order', $_POST['survey']);
		update_post_meta($order_id, 'custom_order_pickup_date_time', $_POST['cake_custom_order']['custom_order_pickup_date'] . ' ' . str_replace('.5', ':30', $_POST['cake_custom_order']['custom_order_pickup_time']));
	}
}

add_action('wp_ajax_nopriv_wcp_contact_form_submit', 'wcp_contact_form_submit');
add_action('wp_ajax_wcp_contact_form_submit', 'wcp_contact_form_submit');
function wcp_contact_form_submit(){
	$form = new SCFP_Form($_POST['form_id']);
	$_POST['action'] = 'scfp-form-submit';
	$form->submit($_POST);
	
	$errors = $form->getError();
	$errorSettings = SCFP()->getSettings()->getErrorsSettings();
	$fieldsSettings = SCFP()->getSettings()->getFieldsSettings();
	$formSettings = SCFP()->getSettings()->getFormSettings();
	$styleSettings = SCFP()->getSettings()->getStyleSettings();
	$formData = $form->getData();
	$notifications = $form->getNotifications();
	
	$button_position = !empty($formSettings['button_position']) ? $formSettings['button_position'] : 'left';
	$no_border = !empty($styleSettings['no_border']) ? $styleSettings['no_border'] : '';
	$no_background = !empty($styleSettings['no_background']) ? $styleSettings['no_background'] : '';
	
	$content_classes = array() ;
	if (!empty($no_border)) {
		$content_classes[] = "scfp-form-noborder";
	}
	if (!empty($no_background)) {
		$content_classes[] = "scfp-form-nobackground";
	}
	if (!empty($formSettings['form_custom_css'])) {
		$content_classes[] = $formSettings['form_custom_css'];
	}
	$content_classes = !empty($content_classes) ? ' '.implode(' ', $content_classes) : '';
	
	$aResponse = array();
	ob_start();
	if( !empty( $errors ) ) { 
	?>
	<div class="scfp-form-error scfp-notifications<?php echo $content_classes;?>">
	    <div class="scfp-form-notifications-content">
	        <?php foreach( $errors as $errors_key => $errors_value ): ?>
	            <div class="scfp-error-item"><span><?php echo $fieldsSettings[$errors_key]['name'];?>:</span> <?php  echo $errorSettings['errors'][$errors_value ] ; ?></div>
	        <?php endforeach; ?>
	    </div>
	    <a class="scfp-form-notifications-close" title="Close" href="#">x</a>
	</div>
	<?php } 
	
	if( !empty( $notifications ) ) { ?>
	<div class="scfp-form-notification scfp-notifications<?php echo $content_classes;?>">
	    <div class="scfp-form-notifications-content">
	        <?php foreach( $notifications as $notification ): ?>
	            <div class="scfp-notification-item"><?php echo $notification; ?></div>
	        <?php endforeach; ?>
	    </div>
	    <a class="scfp-form-notifications-close" title="Close" href="javascript:void(0);">x</a> 
	</div>
	<?php }
	$buffer = ob_get_contents();
	ob_clean();
	$aResponse['error'] = !empty($errors);
	$aResponse['html'] = $buffer;
	echo(json_encode($aResponse));
	die();	
}

function insertAtSpecificIndex($array = [], $item = [], $position = 0) {
	$previous_items = array_slice($array, 0, $position, true);
	$next_items     = array_slice($array, $position, NULL, true);
	return $previous_items + $item + $next_items;
}


add_action( 'wp_ajax_load_items', 'load_items' );
add_action('wp_ajax_nopriv_load_items', 'load_items' );

function load_items(){
	global $wpdb, $wp_query;
	$searchtrm = json_decode(stripslashes($_POST['searchtrm']));
	// echo '<pre>';
	// print_r($searchtrm);
	// echo '</pre>';
	if(!empty($searchtrm)){
		
		foreach($searchtrm as $key => $value){
			if($key == 'gal_cat'){
				$tax_query []= array(
					'taxonomy'      => 'cakegal_taxonomy',
					'field' => 'slug',
					'terms'         => $value
				);
			}
			if($key == 'gal_color_type'){
				$meta_query []= array(
					'key'     => 'color-type',
					'value'   => $value,
					'compare' => 'LIKE'
				);
			}
			if($key == 'gal_scene'){
				$meta_query []= array(
					'key'     => 'scene',
					'value'   => $value,
					'compare' => 'LIKE'
				);
			}
		}
		if(sizeof($meta_query) > 1){
			$meta_query ['relation'] = 'AND';
		}
		
		$args = array(
			'post_type' => 'cakegal',
			'meta_query' =>$meta_query,
			'tax_query' => $tax_query,
			'posts_per_page'    => -1
		);
		$query = new WP_Query($args);
		// echo '<pre>';
		// print_r($args);
		// echo '</pre>';
		ob_start();
		if ( $query->have_posts() ) : 
			
			?>
			<div id="wait"></div>
			<?php
			while ( $query->have_posts() ) : $query->the_post();
			global $post;
			$color_type = get_field('color-type',$post->ID);
			$scene = get_field('scene',$post->ID);
			$term_list = get_the_terms($post, 'cakegal_taxonomy');
			if(!empty($term_list)){
				$tma = array();
				foreach($term_list as $term){
					$tma[] = $term->slug;
				}
			}
			?>
			<li data-gal_color_type="<?php if(!empty($color_type)){ echo trim(implode(',',$color_type),',');}?>" data-gal_scene="<?php if(!empty($scene)){ echo implode(',',$scene);}?>" data-gal_cat="<?php if( isset($tma) && is_array($tma) && !empty($tma)){ echo implode(',',$tma);}?>">
				<a href="#popUp<?php echo $post->ID;?>">
					<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>">
					<span class="zoomBtn"><i class="fa fa-search"></i></span>
				</a>
			</li>
			<?php
			endwhile; 
			wp_reset_postdata();
			
		else :
		?>
		<div id="wait"></div>
		<p>Nothings Found!</p>
		<?php
		endif;
		?>
		<?php
		$args = array (
			'post_type' => 'cakegal',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'orderby' => 'ID',
			'order' => 'DESC'
		);
		$cakegal = new WP_Query($args);
		if($cakegal->have_posts()):
		while($cakegal->have_posts()) : $cakegal->the_post();
		global $post;
		$custom_order_cakesize_round = get_field('custom_order_cakesize_round',$post->ID);
		$est_price = get_field('est-price',$post->ID);
		$term_list = get_the_terms($post, 'cakegal_taxonomy');
		$scene = get_field('scene',$post->ID);
		if(!empty($term_list)){
			$trm_name = array();
			$trm_slug = array();
			foreach($term_list as $term){
				$trm_name[] = $term->name;
				$trm_slug[] = $term->slug;
			}
		}
		?>
		<div id="popUp<?php echo $post->ID;?>" class="popUp">
			<div class="galBox">
			<div class="galcon-inner">
				<div class="image-outer">
				<div class="image-middler">
				<div class="image-inner">
					<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>" class="lightbox-image">
				</div>
				</div>
				</div>
				<div class="gal-content-inside-wrap">
				<div class="meta-info">
					<ul class="ck-info">
						<li><label>Category</label><span class="value"><?php if( isset($trm_name) && is_array($trm_name) && !empty($trm_name)){ echo implode(',',$trm_name);}?></span></li>
						<li><label>Size</label><span class="value size-value"><?php echo $custom_order_cakesize_round;?></span></li>
						<li><label>Price</label><span class="value price-value">¥<?php echo $est_price;?></span></li>
						<li><label>Scene</label><span class="value price-value"><?php if(!empty($scene)){ echo implode(',',$scene);}?></span></li>
					</ul>
					<a class="gallery_type_btn" href="http://kitt-sweets.jp/order-made-form?type=<?php if( isset($trm_slug) && is_array($trm_slug) && !empty($trm_slug)){ echo implode(',',$trm_slug);}?>&post_id=<?php echo $post->ID;?>">
						<input class="cdo-button" value="このケーキを参考に注文する" type="button">
					</a>
				</div>
				</div><!--/gal-content-inside-wrap-->
				</div><!--/inner-->
			</div><!--/galBox-->
		</div>
		<?php endwhile;wp_reset_postdata();?>
		<?php endif;?>
		<?php
		$buffer = ob_get_contents();
		ob_clean();
	}
	echo json_encode(array( 'output' => $buffer));
	exit;	
}

function isPostcodeDiscounted(){
	$current_user = wp_get_current_user();
	// Get user shipping
	$user_shipping_postcode = get_user_meta( $current_user->ID, 'shipping_postcode', true );
	$post_data = array();
	if (isset($_POST['post_data']))
	{
		parse_str($_POST['post_data'], $post_data);
	}
	
	if ((isset($_POST['s_postcode']) && in_array(convertPostcode($_POST['s_postcode']), getDiscountShippingPostcode())) ||
			(isset($_POST['shipping_postcode']) && in_array(convertPostcode($_POST['shipping_postcode']), getDiscountShippingPostcode())) ||
			(isset($_POST['_shipping_postcode']) && in_array(convertPostcode($_POST['_shipping_postcode']), getDiscountShippingPostcode())) ||
			(isset($_POST['custom_order_deliver_postcode']) && in_array(convertPostcode($_POST['custom_order_deliver_postcode']), getDiscountShippingPostcode())) ||
			(isset($post_data['shipping_postcode']) && in_array(convertPostcode($post_data['shipping_postcode']), getDiscountShippingPostcode())) ||
			((!$_POST['s_postcode'] && !$_POST['shipping_postcode'] && !$_POST['custom_order_deliver_postcode'] && !$_POST['_shipping_postcode'] && !$post_data['shipping_postcode']) && isset(WC()->session->customer['shipping_postcode']) && in_array(convertPostcode(WC()->session->customer['shipping_postcode']), getDiscountShippingPostcode())) ||
			((!$_POST['s_postcode'] && !$_POST['shipping_postcode'] && !$_POST['custom_order_deliver_postcode'] && !$_POST['_shipping_postcode'] && !$post_data['shipping_postcode']) && $user_shipping_postcode && in_array(convertPostcode($user_shipping_postcode), getDiscountShippingPostcode()))
			)
	{
		return true;
	}
	
	return false;
}


add_filter( 'woocommerce_shipping_zone_shipping_methods', 'kitt_woocommerce_shipping_zone_shipping_methods', 10, 4);
function kitt_woocommerce_shipping_zone_shipping_methods( $methods, $raw_methods, $allowed_classes, $shipping) {
	$shipping_fee = 0;
	if(isPostcodeDiscounted())
	{
		$shipping_fee = KITT_SHIPPING_POSTCODE_DISCOUNT_FEE;
	}
	else
	{
		$shipping_fee = false;
	}
	
	$is_admin_post = is_admin() && $_POST['shipping_method'];
	if ($is_admin_post || WC()->session)
	{
		$shipping_packages = array();
		if (WC()->session)
			$shipping_packages = WC()->session->get('shipping_for_package_0');
		
		foreach ($methods as $method_id => &$method)
		{
			if ($method->id == 'flat_rate' && $shipping_fee !== false)
			{
				$method->instance_settings['cost'] = $shipping_fee;
				$method->cost = $shipping_fee;
				if (is_array($shipping_packages) && !empty($shipping_packages))
				{
					$shipping_packages['rates'][$method->id . ':' . $method_id]->cost = $shipping_fee;
					WC()->session->set( 'shipping_for_package_0', $shipping_packages );
				}
			}
			elseif ($method->id == 'local_pickup')
			{
				$method->instance_settings['cost'] = 0;
				$method->cost = 0;
			}
		}
	}
	return $methods;
}

function addMinimumPriceNotice($total){
	wc_clear_notices();
	wc_add_notice( sprintf( __('<strong>With shipping Delivery, A Minimum of %s%s  is required before checking out.</strong><br />Current cart\'s total: %s%s', 'cake'),
			get_woocommerce_currency_symbol(),
			KITT_MINIMUM_PRICE_FOR_OTHER_POSTCODE,
			get_woocommerce_currency_symbol(),
			$total),
			'error' );
}

function addPostcodeNotAllowedNotice(){
	wc_clear_notices();
	wc_add_notice( __('Basic flat fee won’t be applied to your shipping address. We will apply extra shipping fee.<br />*Depend on location, please note that there is a possibility that we can’t deliver to your location', 'cake'));
}

// Set a minimum dollar amount per order
// add_action( 'woocommerce_before_cart_totals', 'kitt_woocommerce_before_cart_totals' );
function kitt_woocommerce_before_cart_totals() {
	$chosen_method = WC()->session->get( 'chosen_shipping_methods' );
	// Only run in the Cart or Checkout pages
	if( (!empty($chosen_method) && $chosen_method[0] == KITT_SHIPPING_DELIVERY) && !isPostcodeDiscounted()) {
		global $woocommerce;
		// Set minimum cart total
		$total = WC()->cart->subtotal;
		if( $total <= KITT_MINIMUM_PRICE_FOR_OTHER_POSTCODE  ) {
			// Display our error message
			addMinimumPriceNotice($total);
		}
	}
}

// add_action( 'woocommerce_review_order_before_cart_contents', 'kitt_check_shipping_minimum_price_before_checkout', 10, 3);
// add_action( 'woocommerce_before_checkout_process', 'kitt_check_shipping_minimum_price_before_checkout', 10, 3);
function kitt_check_shipping_minimum_price_before_checkout ($fragments)
{
	global $woocommerce;
	$chosen_method = WC()->session->get( 'chosen_shipping_methods' );
	// Set minimum cart total
	$total = WC()->cart->subtotal;
	if( $total <= KITT_MINIMUM_PRICE_FOR_OTHER_POSTCODE  && (!empty($chosen_method) && $chosen_method[0] == KITT_SHIPPING_DELIVERY) && !isPostcodeDiscounted()) {
		// Display our error message
		addMinimumPriceNotice($total);
	}
	return $fragments;
}


add_action( 'woocommerce_review_order_before_cart_contents', 'kitt_check_shipping_withpostcode_before_checkout', 10, 3);
function kitt_check_shipping_withpostcode_before_checkout ($fragments)
{
	global $woocommerce;
	$chosen_method = WC()->session->get( 'chosen_shipping_methods' );
	// Set minimum cart total
	if( (!empty($chosen_method) && $chosen_method[0] == KITT_SHIPPING_DELIVERY) && !isPostcodeDiscounted() && $_POST['s_postcode']) {
		// Display our error message
		addPostcodeNotAllowedNotice();
	}
	return $fragments;
}

add_filter( 'woocommerce_get_formatted_order_total', 'kitt_woocommerce_get_formatted_order_total', 10, 3 );
function kitt_woocommerce_get_formatted_order_total($formatted_total, $order)
{
	$f_zeroTotal = wc_price( 0, array( 'currency' => $order->get_order_currency() ) );
	if (!is_admin() && $f_zeroTotal == $formatted_total)
	{
		$formatted_total = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>-</span>';
	}
	return $formatted_total;
}

add_filter( 'woocommerce_order_formatted_line_subtotal', 'kitt_woocommerce_order_formatted_line_subtotal', 10, 3 );
function kitt_woocommerce_order_formatted_line_subtotal($formatted_total, $item, $order)
{
	$f_zeroTotal = wc_price( 0, array( 'currency' => $order->get_order_currency() ) );
	if (!is_admin() && $f_zeroTotal == $formatted_total)
	{
		$formatted_total = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>-</span>';
	}
	return $formatted_total;
}
/*

** Remove tabs from product details page

*/

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

unset( $tabs['description'] ); // Remove the description tab
unset( $tabs['reviews'] ); // Remove the reviews tab
unset( $tabs['additional_information'] ); // Remove the additional information tab

return $tabs;

}

add_action('wp', 'addPickUpdateTimeField');
function addPickUpdateTimeField(){	
	if ($_GET['update_pickup_date_time'])
	{
		$statues = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');
		$statues = array_merge($statues, array_keys(wc_get_order_statuses()));
		
		$args = array(
			'post_type' => 'shop_order',
// 			'meta_query' => array(
// 				array(
// 					'key' => 'cake_custom_order',
// 					'compare' => 'EXISTS',
// 				)
// 			),
			'post_status' => $statues,
			'posts_per_page' => -1
		);
		
		$query = new WP_Query( $args );
		foreach($query->posts as $post) {
			$orderFormData = get_post_meta($post->ID, 'cake_custom_order', true);
			if ($orderFormData)
			{
				$pickup_date = $orderFormData['custom_order_pickup_date'];
				$pickup_time = str_replace('.5', ':30', $orderFormData['custom_order_pickup_time']);
				$pickup_time = str_replace('23', '22', $pickup_time);
			}
			update_post_meta($post->ID, 'custom_order_pickup_date_time', isset($pickup_date) ? $pickup_date . ' ' . $pickup_time : '');
		}
	}
}

add_filter('thwepo_product_price_html', 'kitt_thwepo_product_price_html', 10, 2);
function kitt_thwepo_product_price_html ( $price_html, $product_id)
{
	$price_html = '<span class="extra_option_price_text">'  . __('Price including extra options:', 'cake') . '<span>' . $price_html;
	return $price_html;
}

add_action( 'wp_loaded', 'sillo_remove_that_filter' );
function sillo_remove_that_filter(){
	
}

add_filter( 'woocommerce_get_item_data', 'zoa_filter_get_extra_option_data', 1, 2 );
function zoa_filter_get_extra_option_data($item_data, $cart_item = null)
{
	global $wp_filter;
	if (isset($wp_filter['woocommerce_get_item_data']->callbacks['10']))
	{
		foreach ($wp_filter['woocommerce_get_item_data']->callbacks['10'] as $key_filter => $filter)
		{
			if(is_a($filter['function'][0], 'THWEPO_Public'))
			{
				unset($wp_filter['woocommerce_get_item_data']->callbacks['10'][$key_filter]);
				break;
			}
			
		}
	}
	
	if(apply_filters('thwepo_display_custom_cart_item_meta', true)){
		$item_data = is_array($item_data) ? $item_data : array();
		$extra_options = $cart_item && isset($cart_item['thwepo_options']) ? $cart_item['thwepo_options'] : false;
		$product_price = $cart_item && isset($cart_item['thwepo-original_price']) ? $cart_item['thwepo-original_price'] : false;
		$display_option_text = apply_filters('thwepo_order_item_meta_display_option_text', true);
		
		if($extra_options){
			$product_info = array();
			$product_info['id'] = $cart_item['product_id'];
			$product_info['price'] = $product_price;
			
			foreach($extra_options as $name => $data){
				if(isset($data['value']) && isset($data['label'])) {
					$ftype = isset($data['field_type']) ? $data['field_type'] : false;
					$value = isset($data['value']) ? $data['value'] : '';
					
					if($ftype === 'file'){
						$value = THWEPO_Utils::get_file_display_name($value, apply_filters('thwepo_item_display_filename_as_link', false, $name));
						//$value = THWEPO_Utils::get_filename_from_path($value);
						$item_data[] = array("name" => THWEPO_i18n::__t($data['label']), "value" => trim(stripslashes($value)));
					}
					elseif($ftype !== 'multiselect' && $ftype !== 'checkboxgroup'){
						$value = is_array($value) ? implode(",", $value) : $value;
						$value = $display_option_text ? THWEPO_Utils::get_option_display_value($name, $value, $data) : $value;
						$is_show_price = apply_filters('thwepo_show_price_for_item_meta', true, $name);
						if($is_show_price){
							$value .= THWEPO_Utils_Price::get_display_price_item_meta($data, $data['price_type'], $data['price'], $product_info);
						}
						$item_data[] = array("name" => THWEPO_i18n::__t($data['label']), "value" => trim(stripslashes($value)));
					}
					else {
						$is_show_price = apply_filters('thwepo_show_price_for_item_meta', true, $name);
						if($is_show_price){
							// $value = THWEPO_Utils_Price::get_display_price_item_meta($data, $data['price_type'], $data['price'], $product_info);
							$options = $data['options'];

							if ( is_array($options) && is_array($value) )
							{
								foreach ( $value as $option_value )
								{
									$fprice = 0;
									if ( isset($options[$option_value]) )
									{
										$selected_option = $options[$option_value];
										if ( isset($selected_option['price']) && isset($selected_option['price_type']) )
										{
											$price = $selected_option['price'];
											$fprice_type = $selected_option['price_type'];

											if ( $fprice_type === 'percentage' )
											{
												if ( is_numeric($price) && is_numeric($product_price) )
												{
													$fprice = $fprice + ($price / 100) * $product_price;
												}
											}
											else
											{
												if ( is_numeric($price) )
												{
													$fprice = $fprice + $price;
												}
											}

											$price_html = THWEPO_Utils_Price::display_price($fprice, $data, array(), false);
											$price_html = apply_filters('thwepo_item_meta_display_price', $price_html, $name, $data);

											$item_data[] = array(
												"name" => THWEPO_i18n::__t($selected_option['text']),
												"value" => trim(stripslashes($price_html))
											);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return $item_data;
}

add_filter( 'woocommerce_order_items_meta_get_formatted', 'cake_woocommerce_order_items_meta_get_formatted', 1000, 2 );
function cake_woocommerce_order_items_meta_get_formatted($formatted_meta, $itemClass)
{
	$extra_options = THWEPO_Utils::get_custom_sections();
	$option_fields = $extra_options['default']->fields;
	
	foreach ($formatted_meta as &$meta)
	{
		if ($meta['key'] == 'options_cake')
		{
			$options_cake = $option_fields[$meta['key']]->options;
			
			$meta_values = explode(',', $meta['value']);
			foreach ($meta_values as &$meta_value)
			{
				$meta_value = $options_cake[$meta_value]['text'];
			}
			$meta['value'] = implode(', ', $meta_values);
		}
		elseif ($meta['key'] == 'photo_upload' && $meta['value'])
		{
			$image = json_decode($meta['value']);
			$meta['value'] = '<img style="max-width: 100px;" src="'. $image->url .'" class="photo_upload_option"/>';
		}
		
		$meta['label'] = __($meta['label'], 'cake');
	}
	return $formatted_meta;
}

add_action('woocommerce_thankyou', 'cake_after_purchased', 10, 1);
function cake_after_purchased( $order_id ) {
	if ( ! $order_id )
		return;
		
	//if (get_post_meta($order_id, 'changed_extra_option_value', true)) return ;
	
	$extra_options = THWEPO_Utils::get_custom_sections();
	$option_fields = $extra_options['default']->fields;
		
	// Getting an instance of the order object
	$order = wc_get_order( $order_id );
	$line_items          = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
	foreach ( $line_items as $item_id => $item ) 
	{
		$metadata = $order->has_meta( $item_id );
		foreach ($metadata as &$meta)
		{
			if ($meta['meta_key'] == 'options_cake')
			{
				$options_cake = $option_fields[$meta['meta_key']]->options;
				$meta_values = explode(',', $meta['meta_value']);
				foreach ($meta_values as &$meta_value)
				{
					if (isset($options_cake[$meta_value]))
					{
						$meta_value = $options_cake[$meta_value]['text'];
					}
				}
				$meta['meta_value'] = implode(', ', $meta_values);
			}
			elseif ($meta['meta_key'] == 'photo_upload' && $meta['meta_value'])
			{
				if (strpos($meta['meta_value'], '<img') === false)
				{
					$image = json_decode($meta['meta_value']);
					$meta['meta_value'] = '<img style="max-width: 100px;" src="'. $image->url .'" class="photo_upload_option"/>';
				}
			}
			wc_update_order_item_meta($item_id, $meta['meta_key'], $meta['meta_value']);
		}
		
	}
	update_post_meta($order_id, 'changed_extra_option_value', 1);
}

add_filter( 'woocommerce_attribute_label', 'cake_woocommerce_attribute_label', 10, 3 );
function cake_woocommerce_attribute_label( $label, $name, $product)
{
	return __($label, 'cake');
}
?>
