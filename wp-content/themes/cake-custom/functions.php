<?php
if (!function_exists('pr')) {
	function pr ( $data )
	{
		echo '<pre>';
		print_r($data);
	}
}
include 'order_functions.php';

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
function jqueryui_scripts ()
{
	wp_enqueue_script('jqueryui_js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array(), '1.8.6');
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

function woojs_scripts ()
{
	wp_enqueue_script('woocustom_js', get_stylesheet_directory_uri() . '/js/woo-custom.js');
}
add_action('wp_enqueue_scripts', 'woojs_scripts');

function gallery_scripts ()
{
// 	wp_enqueue_style('cubeportfolio_css', get_stylesheet_directory_uri() . '/js/cubeportfolio/css/cubeportfolio.css');
// 	wp_enqueue_script('cubeportfolio_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/jquery.cubeportfolio.js');
// 	wp_enqueue_script('cubeportfolioscript_js', get_stylesheet_directory_uri() . '/js/cubeportfolio/js/main.js');
}
add_action('wp_enqueue_scripts', 'gallery_scripts');

function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'woocommerce-filter-orders-by-product/woocommerce-filter-orders-by-product.php',
		'woocommerce-other-payment-gateway/woocommerce-other-payment-gateway.php',
		'wpcustom-category-image/load.php',
		'login-with-ajax/login-with-ajax.php',
		'advanced-custom-fields/acf.php',
		'wcp-contact-form/wcp-contact-form.php'
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
	$pastDateNumber = 7;
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

// Register Accept status for order
add_action( 'init', 'register_my_new_order_statuses' );
function register_my_new_order_statuses() {
	register_post_status( 'wc-accepted', array(
		'label'                     => _x( 'Accepted', 'Order status', 'woocommerce' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Accepted <span class="count">(%s)</span>', 'Accepted<span class="count">(%s)</span>', 'woocommerce' )
	) );
}

add_filter( 'wc_order_statuses', 'my_new_wc_order_statuses' );
// Register in wc_order_statuses.
function my_new_wc_order_statuses( $order_statuses ) {
	$order_statuses['wc-accepted'] = _x( 'Accepted', 'Order status', 'woocommerce' );
	$order_statuses = array('wc-accepted' => _x( 'Accepted', 'Order status', 'woocommerce' )) + $order_statuses; 
	return $order_statuses;
}

function order_send_invoice($orderid)
{
	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	$email_to_send = 'customer_invoice';
	if ( ! empty( $mails ) ) {
		foreach ( $mails as $mail ) {
			if ( $mail->id == $email_to_send ) {
				$mail->trigger( $orderid );
			}
		}
	}
}
add_action('woocommerce_order_status_pending_to_accepted','order_send_invoice');

function custom_meta_order_detail_box_markup($post)
{
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$field_mappings = getCustomFormFieldMapping();
	
	$order = new WC_Order($post->ID);
	$items = $order->get_items();
	$item_keys = array_keys($items);
	$order_type = wc_get_order_item_meta( @$item_keys[0], '_order_type');
	
	$orderFormData = get_post_meta($order->id, 'cake_custom_order', true);
	
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
	</style>';
	echo '<table class="order-detail-meta" style="clear:both; width: 100%">';
	if (!empty($orderFormData))
	{
		if ($order_type == KITT_CUSTOM_ORDER)
		{
			foreach ($field_mappings as $fieldName => $fields)
			{
				$showBlock = '';
				if (in_array($orderFormData['custom_order_cake_shape'], getArrayRoundShape()))
				{
					$showBlock = 'custom_order_cakesize_round';
				}
				else {
					$showBlock = 'custom_order_cakesize_square';
				}
				
				$class = ($showBlock != $fieldName && in_array($fieldName, array('custom_order_cakesize_round', 'custom_order_cakesize_square'))) ? 'disable' : '';
				
				$itemField = $fields['field'];
				$defaultValue = isset($orderFormData[$fieldName]) ? (is_array($orderFormData[$fieldName]) ? implode(PHP_EOL, $orderFormData[$fieldName]) : $orderFormData[$fieldName]) : '';
				if ($itemField['type'] == 'date_picker')
				{
					$defaultValue = $defaultValue ? $defaultValue : $itemField['display_format']; 
				}
				
				echo '<tr id="'.$itemField['name'].'_wraper" class="'.$class.'">
						<td class="col-left" style="text-align: left; width: 20%">'.$itemField['label'].'</td>
						<td class="col-right" style="text-align; width: 80%">';
				if ('custom_order_cakePic' == $fieldName || 'custom_order_photocakepic' == $fieldName)
				{
					$images = explode(PHP_EOL, $defaultValue);
					foreach ($images as $image)
					{
						echo '<img style="margin-right: 5px;max-width: 200px;" src="' . $image . '" />';
					}
				}
				$args = array(
					'type' => $itemField['type'],
					'name' => 'custom_order_meta['.$itemField['name'].']',
					'value' => $defaultValue,
					'choices' => isset($fields['value']) ? $fields['value'] : ''
				);
				
				if ($fieldName == 'custom_order_cake_type')
				{
					echo do_action('acf/create_field', $args);
				}
				else {
					$itemField['name'] = 'custom_order_meta['.$itemField['name'].']';
					$itemField['value'] = $defaultValue;
						
					echo do_action('acf/create_field', $itemField);
				}
				
				echo '</td></tr>';
			}
		}
		else {
			foreach ($orderFormData as $metaItemKey => $metaItemVal)
			{
				$field_mappings[$metaItemKey]['field']['name'] = 'custom_order_meta['.$metaItemKey.']';
				$field_mappings[$metaItemKey]['field']['value'] = $metaItemVal;
				
				echo '<tr id="'.$metaItemKey.'_wraper" >
					<td class="col-left" style="text-align: left; width: 20%">'.$field_mappings[$metaItemKey]['field']['label'].'</td>
					<td class="col-right" style="text-align; width: 80%"> ';
					echo do_action('acf/create_field', $field_mappings[$metaItemKey]['field']);
				echo '</td></tr>';
			}
		}
	}
	echo '</table>';
}

function save_custom_order_detail_meta_box ( $post_id, $post, $update )
{
	if ( ! isset($_POST["meta-box-nonce"]) || ! wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)) ) return $post_id;

	if ( ! current_user_can("edit_post", $post_id) ) return $post_id;

	if ( defined("DOING_AUTOSAVE") && DOING_AUTOSAVE ) return $post_id;

	update_post_meta($post_id, "cake_custom_order", $_POST['custom_order_meta']);
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
function remove_fields_my_account_page($fields) {
    unset( $fields ['account_company'] );
    return $fields;
}
add_filter( 'woocommerce_my_account_edit_address_title', 'remove_fields_my_account_page' );

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
	return $valid_order_statuses;
}
add_filter( 'woocommerce_valid_order_statuses_for_payment', 'woocommerce_valid_order_statuses_for_payment_custom_order', 10, 3 );

function disable_page_wpautop() {
	if ( is_page() ) remove_filter( 'the_content', 'wpautop' );
}
add_action( 'wp', 'disable_page_wpautop' );

function autoLoginUser($user_id){
	$user = get_user_by( 'id', $user_id );
	if( $user && $user->user_pass && !$user->user_activation_key ) {
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_login, $user);
	}
}

add_action( 'register_new_user', 'autoLoginUser', 10, 1 );


function woocommerce_save_account_details_custom ($userID)
{
	update_user_meta($userID, 'first_name_kana', $_POST['account_first_name_kana']);
	update_user_meta($userID, 'last_name_kana', $_POST['account_last_name_kana']);
	update_user_meta($userID, 'tel', $_POST['account_tel']);
	update_user_meta($userID, 'company', $_POST['account_company']);
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
        "shipping_address_2"

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
	return $fields;
}

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


add_action( 'woocommerce_checkout_after_customer_details', 'extra_delivery_fields_in_checkout_page' );
function extra_delivery_fields_in_checkout_page( $checkout ) {
?>
	<ul>
		<li class="main-option">
			<h4 class="heading-form display-table mb-3">
				<span class="display-table-cell pl-2">When do you want your order delivered?</span>
			</h4>
			<div class="row">
				<div class="col-md-6 columns">
					<label class="label mb-2">
						<i class="icon-outline-kitt_icons_calendar01"></i>
						Pick Up Date
					</label>
					<div class="calendar"></div>
					<input type="hidden" name="cake_custom_order[custom_order_pickup_date]" id="custom_order_pickup_date" value="<?php echo date('Y-m-d')?>"/>
				</div>
				<div class="col-md-6 columns">
					<label class="label mb-2">
						<i class="icon-outline-kitt_icons_clock"></i>
						Pick Up Time
					</label>
					<div class="timepicker">
						<div class="timepick">
						<h3 class="input no-interaction text-center display-table width-full"><div class="display-table-cell"><output></output></div></h3>
							<div class="time-range display-table width-full mt-2">
								<div class="time-range__minus display-table-cell">
									<button type="button" class="button button--ghost circle">-</button>
								</div>
								<div class="display-table-cell">
									<input type="range" id="order_pickup_time" name="cake_custom_order[custom_order_pickup_time]" min="1" max="24" step="1" value="9" data-rangeslider />
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
	</ul>
<?php
}

add_action( 'woocommerce_checkout_update_order_meta', 'kitt_custom_checkout_field_update_order_meta' );
function kitt_custom_checkout_field_update_order_meta( $order_id ) 
{
	if ( isset($_POST['cake_custom_order']) ) {
		update_post_meta( $order_id, 'cake_custom_order', $_POST['cake_custom_order'] ) ;
	}
}


function insertAtSpecificIndex($array = [], $item = [], $position = 0) {
	$previous_items = array_slice($array, 0, $position, true);
	$next_items     = array_slice($array, $position, NULL, true);
	return $previous_items + $item + $next_items;
}
?>
