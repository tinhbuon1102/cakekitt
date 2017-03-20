<?php
if (!function_exists('pr')) {
	function pr ( $data )
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
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
// 		'wcp-contact-form/wcp-contact-form.php'
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
	$order_type = kitt_get_order_type($order->id);
	
	$orderFormData = get_post_meta($order->id, 'cake_custom_order', true);
	
	$addressFields = array(
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
		
	);
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
				if (in_array($fieldName, $addressFields))
				{
					continue;
				}
				
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
					kitt_acf_render_field_wrap( $args);
				}
				else {
					$itemField['name'] = 'custom_order_meta['.$itemField['name'].']';
					$itemField['value'] = $defaultValue;
					kitt_acf_render_field_wrap( $itemField);
				}
				
				echo '</td></tr>';
			}
		}
		else {
			
			unset($orderFormData['custom_order_customer_sex']);
			unset($orderFormData['custom_order_customer_birth_date']);
			
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
	return $fields;
}

add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_admin_shipping_fields_label', 10, 1 );
function woocommerce_admin_shipping_fields_label($fields){
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
		if ( $is_custom_order_product )
		{
			return 'オーダーメイドケーキのご注文を承りました。誠にありがとうございます。';
		}
		else
		{
			return '以下のケーキのご注文を承りました。誠にありがとうございます。';
		}
	}
}
add_filter( 'woocommerce_thankyou_order_received_text', 'kittwp_title_order_received', 10, 2 );

//shipping address checked
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );

//show title for shipping on checkout page
function kitt_title_shipping() {
echo '<h4 class="heading-form mt-4 mb-2 text-gray">Where do you want your order delivered?</h3>';
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

    $months 	= array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
    $default	= array( 'day' => 1, 'month' => 'Jnuary', 'year' => 1950, );
    $birth_date = wp_parse_args( get_the_author_meta( 'birth_date', $user->ID ), $default );
	$sexs 	= array( 'Male', 'Female' );

    ?>
    <h3>Extra profile information</h3>

    <table class="form-table">
   	 <tr>
   		 <th><label for="birth-date-day">Birth date</label></th>
   		 <td>
   		 <select id="birth-date-year" name="birth_date[year]"><?php
   				 for ( $i = 1950; $i <= 2015; $i++ ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $birth_date['year'], $i, false ) );
   				 }
   			 ?></select>
   			 <select id="birth-date-month" name="birth_date[month]"><?php
   				 foreach ( $months as $month ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $month, selected( $birth_date['month'], $month, false ) );
   				 }
   			 ?></select>
   			 <select id="birth-date-day" name="birth_date[day]"><?php
   				 for ( $i = 1; $i <= 31; $i++ ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $birth_date['day'], $i, false ) );
   				 }
   			 ?></select>
   		 </td>
   	 </tr>
   	 <tr>
   		 <th><label for="sex">Sex</label></th>
   		 <td>
   		 <select id="sex" name="sex"><?php
   				 foreach ( $sexs as $sex ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $sex, selected( $sex, $sex, false ) );
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
	
	<div class="row">
		<div class="field col-md-6">
			<label class="label">Sex</label>
			<ul class="account_sex text-radio list-type">
				<li class="m-input__radio">
					<input type="radio" name="cake_custom_order[custom_order_customer_sex]" id="account_sex_male" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'male', true )?> value="male">
					<label for="account_sex_male" class="js-fixHeightChildText radio_label">
						<div class="radio_option radio_size">
							<h5 class="js-fixHeightChildTitle radio_option_caption">
								<span class="caption_wrap">Male</span>
							</h5>
						</div>
					</label>
				</li>
				<li class="m-input__radio">
					<input type="radio" name="cake_custom_order[custom_order_customer_sex]" id="account_sex_female" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'female', true )?> value="female">
					<label for="account_sex_female" class="js-fixHeightChildText radio_label">
						<div class="radio_option radio_size">
							<h5 class="js-fixHeightChildTitle radio_option_caption">
								<span class="caption_wrap">FeMale</span>
							</h5>
						</div>
					</label>
				</li>
			</ul>
		</div>
		<div class="field col-md-6">
			<label class="label">Birthday</label>
			<?php 
			$yearMonthDays = kitt_get_year_month_day();
			$birth_date = get_user_meta( get_current_user_id(), 'birth_date', true);
			$default	= array( 'day' => 1, 'month' => 1, 'year' => 1980, );
			$birth_date = $birth_date ? $birth_date : $default;
			?>
			<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide" >
				<select id="birth-date-year" name="cake_custom_order[custom_order_customer_birth_date_year]" required class="form-control select select-primary" data-toggle="select">>
					<option value=""><?php echo __('Select Birth Year')?></option>
					<?php
		   				 foreach($yearMonthDays['years'] as $yearNumber) {
		   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $yearNumber, selected( $birth_date['year'], $yearNumber, false ) );
		   				 }
		   			 ?></select>
		   			 <select id="birth-date-month" name="cake_custom_order[custom_order_customer_birth_date_month]" required class="form-control select select-primary" data-toggle="select">>
		   			 <option value=""><?php echo __('Select Birth Month')?></option>
		   			 <?php
		   				 foreach ( $yearMonthDays['months'] as $monthNumber => $monthText ) {
		   					 printf( '<option value="%1$s" %2$s>%3$s</option>', $monthNumber, selected( $birth_date['month'], $monthNumber, false ), $monthText );
		   				 }
		   			 ?></select>
		   			 <select id="birth-date-day" name="cake_custom_order[custom_order_customer_birth_date_day]" required class="form-control select select-primary" data-toggle="select">>
		   			 <option value=""><?php echo __('Select Birth Day')?></option>
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
		update_post_meta($order_id, 'cake_custom_order', $_POST['cake_custom_order']);
		
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
?>
