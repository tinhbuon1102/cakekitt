<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce For Japan

 * @package woocommerce-for-japan
 * @version     1.2.0
 * @category Address for Japan
 * @author Artisan Workshop
 */

class AddressField4jp{
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
        // MyPage Edit And Checkout fields.
		add_filter( 'woocommerce_default_address_fields',array( &$this,  'address_fields'));
		add_filter( 'woocommerce_billing_fields',array( &$this,  'billing_address_fields'));
		add_filter( 'woocommerce_shipping_fields',array( &$this,  'shipping_address_fields'));
		add_filter( 'woocommerce_formatted_address_replacements', array( &$this, 'address_replacements'),10,2);
		add_filter( 'woocommerce_localisation_address_formats', array( &$this, 'address_formats'));
		//My Account Display for address
		add_filter( 'woocommerce_my_account_my_address_formatted_address', array( &$this, 'formatted_address'),10,3);//template/myaccount/my-address.php
		//Check Out Display for address
		add_filter( 'woocommerce_order_formatted_billing_address', array( &$this, 'wc4jp_billing_address'),10,2);//includes/abstract/abstract-wc-order.php
		add_filter( 'woocommerce_order_formatted_shipping_address', array( &$this, 'wc4jp_shipping_address'),10,2);//includes/abstract/abstract-wc-order.php
		//include get_order function
		add_filter( 'woocommerce_get_order_address', array( &$this, 'wc4jp_get_order_address'),10,3);//includes/abstract/abstract-wc-order.php
		//Admin CSS file 
		add_action( 'admin_enqueue_scripts', array( &$this, 'load_custom_wc4jp_admin_style') ,20);
		//FrontEnd CSS file 
		add_action( 'wp_enqueue_scripts', array( &$this, 'checkout_enqueue_style'), 10 );

		//
		add_action( 'woocommerce_after_checkout_billing_form', array( &$this, 'auto_zip2address_billing'), 10 );
		add_action( 'woocommerce_after_checkout_shipping_form', array( &$this, 'auto_zip2address_shipping'), 10 );
		add_action( 'woocommerce_after_edit_address_form_billing', array( &$this, 'auto_zip2address_billing'), 10 );
		add_action( 'woocommerce_after_edit_address_form_shipping', array( &$this, 'auto_zip2address_myaccount_shipping'), 10 );
		
		// Display delivery date and time
		add_action( 'woocommerce_before_order_notes', array( &$this, 'delivery_date_designation'), 10  );
		// Save delivery date and time values to order
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ) );
		// Show on order detail page (frontend)
		add_action( 'woocommerce_order_details_after_order_table',           array( $this, 'frontend_order_timedate' ) );
		// Show on order detail email (frontend)
		add_filter( 'woocommerce_email_order_meta', array( $this, 'email_order_delivery_details' ), 10, 4 );
		// Shop Order functions
		add_filter( 'manage_edit-shop_order_columns',           array( $this, 'shop_order_columns' ) );
		add_action( 'manage_shop_order_posts_custom_column',    array( $this, 'render_shop_order_columns' ), 2 );
		// Shop Order functions
		add_action( 'woocommerce_admin_order_data_after_billing_address',    array( $this, 'display_admin_order_meta' ), 10, 1 );

		// Admin Edit Address
		add_filter( 'woocommerce_admin_billing_fields', array( &$this, 'admin_billing_address_fields'));
		add_filter( 'woocommerce_admin_shipping_fields', array( &$this, 'admin_shipping_address_fields'));
		add_filter( 'woocommerce_customer_meta_fields', array( &$this, 'admin_customer_meta_fields'));
	}
	//Default address fields
    public function address_fields( $fields ) {
		$fields = array(
			'country' => array(
				'type'     => 'country',
				'label'    => __( 'Country', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
			),
			'last_name'          => array(
				'label'    => __( 'Last Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
			),
			'first_name' => array(
				'label'    => __( 'First Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true
			),
			'yomigana_last_name' => array(
				'label'    => __( 'Last Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
			),
			'yomigana_first_name' => array(
				'label'    => __( 'First Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true
			),
			'company' => array(
				'label' => __( 'Company Name', 'woocommerce-for-japan' ),
				'class' => array( 'form-row-wide' ),
			),
			'postcode' => array(
				'label'       => __( 'Postcode / Zip', 'woocommerce-for-japan' ),
				'placeholder' => _x( '123-4567', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-first', 'address-field' ),
				'validate'    => array( 'postcode' ),
				'clear'       => false
			),
			'state' => array(
				'type'        => 'state',
				'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-last', 'address-field' ),
				'validate'    => array( 'state' ),
				'clear'       => true
			),
			'city' => array(
				'label'       => __( 'Town / City', 'woocommerce-for-japan' ),
				'placeholder' => __( 'Town / City', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' )
			),
			'address_1' => array(
				'label'       => __( 'Address', 'woocommerce-for-japan' ),
				'placeholder' => _x( 'Street address', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' )
			),
			'address_2' => array(
				'placeholder' => _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'woocommerce-for-japan' ),
				'class'       => array( 'form-row-wide', 'address-field' ),
				'required'    => false
			),
		);
		if(!get_option( 'wc4jp-yomigana'))unset($fields['yomigana_last_name'],$fields['yomigana_first_name']);
		return $fields;
	}
		// Billing/Shipping Specific
    public function billing_address_fields( $fields ) {
		$address_fields = $fields;
		$address_fields['billing_state'] = array(
			'type'        => 'state',
			'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
			'required'    => true,
			'class'       => array( 'form-row-last', 'address-field' ),
			'clear'       => true,
			'validate'    => array( 'state' )
		);
		$address_fields['billing_email'] = array(
			'label' 		=> __( 'Email Address', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-first' ),
			'validate'		=> array( 'email' ),
		);
		$address_fields['billing_phone'] = array(
			'label' 		=> __( 'Billing Phone', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-last' ),
			'clear'			=> true,
			'validate'		=> array( 'phone' ),
		);
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['billing_company']);
		return $address_fields;
	}
    public function shipping_address_fields( $fields ) {
		$address_fields = $fields;

		$address_fields['shipping_state'] = array(
			'type'        => 'state',
			'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
			'required'    => true,
			'class'       => array( 'form-row-last', 'address-field' ),
			'clear'       => true,
			'validate'    => array( 'state' )
		);
		$address_fields['shipping_phone'] = array(
			'label' 		=> __( 'Shipping Phone', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-wide' ),
			'clear'			=> true,
			'validate'		=> array( 'phone' ),
		);
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['shipping_company']);
		return $address_fields;
	}

    public function address_replacements( $fields, $args ) {
		$fields['{name}'] = $args['last_name'] . ' ' . $args['first_name'];
		$fields['{name_upper}'] = strtoupper( $args['last_name'] . ' ' . $args['first_name'] );
		if(get_option( 'wc4jp-yomigana')){
			$fields['{yomigana_last_name}'] = $args['yomigana_last_name'];
			$fields['{yomigana_first_name}'] = $args['yomigana_first_name'];
		}
		$fields['{phone}'] = $args['phone'];

		return $fields;
	}
	public function address_formats( $fields ) {
		//honorific suffix
		$honorific_suffix = '';
		if(get_option('wc4jp-honorific-suffix'))$honorific_suffix = '様';
		
		if(get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
		}
		if(!get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
		}
		if(!get_option( 'wc4jp-company-name') and !get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
		}
		return $fields;
	}
	public function formatted_address( $fields, $customer_id, $name) {
		if(version_compare( WC_VERSION, '2.7', '>' )){
			$fields['yomigana_first_name']  = $customer->get_yomigana_first_name();
			$fields['yomigana_last_name']  = get_user_meta( $customer_id, $name . '_yomigana_last_name', true );
			$fields['phone']  = get_user_meta( $customer_id, $name . '_phone', true );
		}else{
			$fields['yomigana_first_name']  = get_user_meta( $customer_id, $name . '_yomigana_first_name', true );
			$fields['yomigana_last_name']  = get_user_meta( $customer_id, $name . '_yomigana_last_name', true );
			$fields['phone']  = get_user_meta( $customer_id, $name . '_phone', true );
		}

		return $fields;
	}
	public function wc4jp_billing_address( $fields, $args) {
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			$fields['yomigana_first_name'] = get_post_meta( $order_id, '_billing_yomigana_first_name', true );
			$fields['yomigana_last_name'] = get_post_meta( $order_id, '_billing_yomigana_last_name', true );
		}else{
			$fields['yomigana_first_name'] = $args->billing_yomigana_first_name;
			$fields['yomigana_last_name'] = $args->billing_yomigana_last_name;
			$fields['phone'] = $args->billing_phone;
		}

		return $fields;
	}
	public function wc4jp_shipping_address( $fields, $args) {
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			$fields['yomigana_first_name'] = get_post_meta( $order_id, '_shipping_yomigana_first_name', true );
			$fields['yomigana_last_name'] = get_post_meta( $order_id, '_shipping_yomigana_last_name', true );
			$fields['phone'] = get_post_meta( $order_id, '_shipping_phone', true );
		}else{
			$fields['yomigana_first_name'] = $args->shipping_yomigana_first_name;
			$fields['yomigana_last_name'] = $args->shipping_yomigana_last_name;
			$fields['phone'] = $args->shipping_phone;
		}

		return $fields;
	}
	public function wc4jp_get_order_address( $address, $type, $args ){
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			if ( 'billing' === $type ) {
				$address['yomigana_first_name'] = get_post_meta( $order_id, '_billing_yomigana_first_name', true );
				$address['yomigana_last_name'] = get_post_meta( $order_id, '_billing_yomigana_last_name', true );
			}else{
				$address['yomigana_first_name'] = get_post_meta( $order_id, '_shipping_yomigana_first_name', true );
				$address['yomigana_last_name'] = get_post_meta( $order_id, '_shipping_yomigana_last_name', true );
				$address['phone'] = get_post_meta( $order_id, '_shipping_phone', true );
			}
		}else{
			if ( 'billing' === $type ) {
				$address['yomigana_first_name'] =$args->billing_yomigana_first_name;
				$address['yomigana_last_name'] =$args->billing_yomigana_last_name;
			} else {
				$address['yomigana_first_name'] =$args->shipping_yomigana_first_name;
				$address['yomigana_last_name'] =$args->shipping_yomigana_last_name;
				$address['phone'] = $args->shipping_phone;
			}
		}
		return $address;
	}

	//Admin CSS file function
	public function load_custom_wc4jp_admin_style() {
		wp_register_style( 'custom_wc4jp_admin_css', plugins_url() . '/woocommerce-for-japan/includes/views/css/admin-wc4jp.css', false, WC4JP_VERSION );
		wp_enqueue_style( 'custom_wc4jp_admin_css' );
		$suffix       = '.min';

		// Register scripts
		wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/woocommerce/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION );
		wp_enqueue_script( 'woocommerce_admin' );
	}

	//FrontEnd CSS file function
	public function checkout_enqueue_style() {
		$current_theme = wp_get_theme();
		if((is_checkout() or is_account_page() )and $current_theme->get( 'Name' ) == 'Storefront'){
			wp_register_style( 'custom_checkout_wc4jp_css', plugins_url() . '/woocommerce-for-japan/includes/views/css/checkout-wc4jp.css', false, WC4JP_VERSION );
			wp_enqueue_style( 'custom_checkout_wc4jp_css' );
		}
	}

    public function admin_billing_address_fields( $fields ) {
	    $billing_address_fields = array(
		    'country' => $fields['country'],
		    'postcode' => $fields['postcode'],
		    'city' => $fields['city'],
		    'state' => $fields['state'],
		    'address_1' => $fields['address_1'],
		    'address_2' => $fields['address_2'],
		    'company' => $fields['company'],
		    'last_name' => $fields['last_name'],
		    'first_name' => $fields['first_name'],
		    'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
		    'email' => $fields['email'],
			'phone' => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'show'	=> false
			),
	    );

		if(!get_option( 'wc4jp-company-name'))unset($billing_address_fields['company']);
		if(!get_option( 'wc4jp-yomigana'))unset($billing_address_fields['yomigana_last_name'],$billing_address_fields['yomigana_first_name']);

		return $billing_address_fields;
	}
    public function admin_shipping_address_fields( $fields ) {
	    $shipping_address_fields = array(
		    'country' => $fields['country'],
		    'postcode' => $fields['postcode'],
		    'city' => $fields['city'],
		    'state' => $fields['state'],
		    'address_1' => $fields['address_1'],
		    'address_2' => $fields['address_2'],
		    'company' => $fields['company'],
		    'last_name' => $fields['last_name'],
		    'first_name' => $fields['first_name'],
		    'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'phone' => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'show'	=> false
			),
	    );

		if(!get_option( 'wc4jp-company-name'))unset($shipping_address_fields['company']);
		if(!get_option( 'wc4jp-yomigana'))unset($shipping_address_fields['yomigana_last_name'],$shipping_address_fields['yomigana_first_name']);

		return $shipping_address_fields;
	}
	public function admin_customer_meta_fields( $fields ){
		$customer_meta_fields = $fields;
		//Billing fields
		$billing_fields = $fields['billing']['fields'];
		$customer_meta_fields['billing']['fields'] = array(
			'billing_last_name' => $billing_fields['billing_last_name'],
			'billing_first_name' => $billing_fields['billing_first_name'],
			'billing_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_company'  => $billing_fields['billing_company'],
			'billing_country'  => $billing_fields['billing_country'],
			'billing_postcode' => $billing_fields['billing_postcode'],
			'billing_state'  => $billing_fields['billing_state'],
			'billing_city'  => $billing_fields['billing_city'],
			'billing_address_1'  => $billing_fields['billing_address_1'],
			'billing_address_2'  => $billing_fields['billing_address_2'],
			'billing_phone'  => $billing_fields['billing_phone'],
			'billing_email'  => $billing_fields['billing_email'],
		);
		//Shipping fields
		$shipping_fields = $fields['shipping']['fields'];
		$customer_meta_fields['shipping']['fields'] = array(
			'shipping_last_name' => $shipping_fields['shipping_last_name'],
			'shipping_first_name' => $shipping_fields['shipping_first_name'],
			'shipping_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_company'  => $shipping_fields['shipping_company'],
			'shipping_country'  => $shipping_fields['shipping_country'],
			'shipping_postcode' => $shipping_fields['shipping_postcode'],
			'shipping_state'  => $shipping_fields['shipping_state'],
			'shipping_city'  => $shipping_fields['shipping_city'],
			'shipping_address_1'  => $shipping_fields['shipping_address_1'],
			'shipping_address_2'  => $shipping_fields['shipping_address_2'],
			'shipping_phone'  => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'description' => '',
			),
		);
		if(!get_option( 'wc4jp-company-name'))unset($customer_meta_fields['billing']['fields']['billing_company'], $customer_meta_fields['shipping']['fields']['shipping_company']);
		if(!get_option( 'wc4jp-yomigana'))unset($customer_meta_fields['billing']['fields']['billing_yomigana_last_name'], $customer_meta_fields['billing']['fields']['billing_yomigana_first_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_last_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_first_name']);
		return $customer_meta_fields;
	}

	// Automatic input from postal code to Address for billing
	public function auto_zip2address_billing(){
		$this->auto_zip2address( 'billing', 2 );
	}

	// Automatic input from postal code to Address for shipping
	public function auto_zip2address_shipping(){
		$this->auto_zip2address( 'shipping', 4 );
	}

	// Automatic input from postal code to Address for shipping
	public function auto_zip2address_myaccount_shipping(){
		$this->auto_zip2address( 'shipping', 2 );
	}
	// Automatic input from postal code to Address
	function auto_zip2address( $method, $num ){
		global $states;
		add_action( 'wp_enqueue_scripts', 'yahoo_api_scripts' );
		if(get_option( 'wc4jp-yahoo-app-id' )){
			$yahoo_app_id = get_option( 'wc4jp-yahoo-app-id' );
		}else{
			$yahoo_app_id = 'dj0zaiZpPWZ3VWp4elJ2MXRYUSZzPWNvbnN1bWVyc2VjcmV0Jng9MmY-';
		}
		if(get_option( 'wc4jp-zip2address' )){
			wp_enqueue_script( 'yahoo-app','https://map.yahooapis.jp/js/V1/jsapi?appid='.$yahoo_app_id,array('jquery'),WC4JP_VERSION);
			echo '
<script type="text/javascript">
// Search Japanese Postal number
jQuery(function($) {
$(document).ready(function(){
    $("#'.$method.'_postcode").keyup(function(){
	    var zip = $("#'.$method.'_postcode").val(),
	    zipCount = zip.length;
	    if(zipCount == 4 && zip.charAt(zipCount -1) != "-") {
		    alert("'.__('Please enter a hyphen [-] when entering a zip code.','woocommerce-for-japan').'");
	    }else if(zipCount > 7) {
    var url = "https://map.yahooapis.jp/search/zip/V1/zipCodeSearch";
    var param = {
        appid: "'.$yahoo_app_id.'",
        output: "json",
        query: $("#'.$method.'_postcode").val()
    };
    $.ajax({
        url: url,
        data: param,
        dataType: "jsonp",
        success: function(result) {
            var ydf = new Y.YDF(result);
            // Display Address from Zip
            dispZipToAddress'.$method.'(ydf);
        },
        error: function() {
            // Error handling
        }
    });
    }
    });
});
});
// Display Address from Zipcode
function dispZipToAddress'.$method.'(ydf) {
	var address = ydf.features[0].property.Address;
	var state = address.substr( 0, 3 );
	var states = new Array();';
	foreach($states['JP'] as $key => $value){
		$key = substr($key, 2);
		if($key == '14' || $key == "30" || $key == "46"){
			echo 'states['.$key.'] = "'.mb_substr($value, 0, 3).'";';
		}else{
			echo 'states['.$key.'] = "'.$value.'";';
		}
	}
		echo '
	var state_id = jQuery.inArray(state, states);
	jQuery("#'.$method.'_state").val(state_id);	
	var text_num = 3;
	if(state_id == "14" || state_id == "30" || state_id == "46"){
		text_num = 4;
	}
	var city = address.substr( text_num );
	jQuery("#'.$method.'_city").val(city);
	states[14] = "'.$states['JP']['JP14'].'";
	states[30] = "'.$states['JP']['JP30'].'";
	states[46] = "'.$states['JP']['JP46'].'";
	if(state_id > 9){
	document.getElementById("'.$method.'_state").value = "JP" + state_id;
	}else{
	document.getElementById("'.$method.'_state").value = "JP0" + state_id;
	}
	document.getElementById("select2-chosen-'.$num.'").innerHTML = states[state_id];
}
</script>
		';
		}
	}
	// Delivery date designation
	public function delivery_date_designation(){
		$setting_methods = array( 'delivery-date','start-date','reception-period','unspecified-date','delivery-deadline','delivery-time-zone','unspecified-time');
		foreach($setting_methods as $setting_method){
			$setting[$setting_method] = get_option( 'wc4jp-'.$setting_method );
		}
		if($setting['delivery-date'] or $setting['delivery-time-zone']){
			echo '<h3>'.__('Delivery request date and time', 'woocommerce-for-japan' ).'</h3>';
		}
		$this->delivery_date_display($setting);
		$this->delivery_time_display($setting);
	}
	//Display Delivery date select
	function delivery_date_display($setting){
		if(get_option( 'wc4jp-delivery-date' )){
			$time = new DateTime();
			$time = $time->format('H:i');
			$now = get_date_from_gmt($time);
			if (strtotime($now) > strtotime($setting['delivery-deadline'])){
				$setting['start-date'] = $setting['start-date'] + 1;
			}
			echo '<p class="form-row delivery-date" id="order_delivery_date_field">';
			echo '<label for="delivery_date" class="">'.__('Preferred delivery date', 'woocommerce-for-japan' ).'</label>';
			echo '<select name="wc4jp_delivery_date" class="input-select" id="wc4jp_delivery_date">';
			echo '<option value="0">'.$setting['unspecified-date'].'</option>';
			for($i = $setting['start-date']; $i <= $setting['start-date']+$setting['reception-period']; $i++){
				$set_disp_date = new DateTime();
				$set_disp_date->modify('+'.$i.' day');
				$set_disp_date = $set_disp_date->format('Y/m/d');
				$valuedate[$i] = get_date_from_gmt($set_disp_date, 'Y-m-d');
				$dispdate[$i] = get_date_from_gmt($set_disp_date, __('Y/m/d', 'woocommerce-for-japan' ));
				echo '<option value="'.$valuedate[$i].'">'.$dispdate[$i].'</option>';
			}
			echo '</select>';
			echo '</p>';
		}		
	}
	//Display Delivery time select
	function delivery_time_display($setting){
		$time_zone_setting = get_option( 'wc4jp_time_zone_details' );
		if(get_option( 'wc4jp-delivery-time-zone' )){
			echo '<p class="form-row delivery-time" id="order_delivery_time_field">';
			echo '<label for="delivery_time_zone" class="">'.__('Delivery Time Zone', 'woocommerce-for-japan' ).'</label>';
			echo '<select name="wc4jp_delivery_time_zone" class="input-select" id="wc4jp_delivery_time_zone">';
			echo '<option value="0">'.$setting['unspecified-time'].'</option>';
			$count_time_zone = count($time_zone_setting);
			for($i = 0; $i <= $count_time_zone - 1; $i++){
				echo '<option value="'.$time_zone_setting[$i]['start_time'].'-'.$time_zone_setting[$i]['end_time'].'">'.$time_zone_setting[$i]['start_time'].__('-', 'woocommerce-for-japan' ).$time_zone_setting[$i]['end_time'].'</option>';
			}
			echo '</select>';
			echo '</p>';
		}
	}
	/**
	 * Helper: Update order meta on successful checkout submission
	 *
	 * @param str $order_id
	 */
	function update_order_meta( $order_id ) {

        $date = false;
        $time = false;

        if( isset($_POST['wc4jp_delivery_date'])) {
	        $date = $_POST['wc4jp_delivery_date'];
	        update_post_meta( $order_id, 'wc4jp-delivery-date', esc_attr(htmlspecialchars($date)));
        }
        if( isset($_POST['wc4jp_delivery_time_zone'])) {
	        $time = $_POST['wc4jp_delivery_time_zone'];
	        update_post_meta( $order_id, 'wc4jp-delivery-time-zone', esc_attr(htmlspecialchars($time)));
        }
	}
    /**
     * Frontend: Add date and timeslot to frontend order overview
     *
     * @param obj $order
     */
    function frontend_order_timedate( $order ){

        if( !$this->has_date_or_time( $order ) )
            return;

        $this->display_date_and_time_zone( $order, true );

    }
    /**
     * Helper: Display Date and Timeslot
     *
     * @param obj $order
     * @param bool $plain_text
     */
    public function display_date_and_time_zone( $order, $show_title = false, $plain_text = false ) {

        $date_time = $this->has_date_or_time( $order );

        if( !$date_time )
            return;
        if($date_time['date'] === 0 ){$date_time['date']=get_option( 'wc4jp-unspecified-date' );;}
        if($date_time['time'] === 0 ){$date_time['time']=get_option( 'wc4jp-unspecified-time' );;}

        if( $plain_text ) {

            echo "\n\n==========\n\n";

            if( $show_title ) {
                printf( "%s \n", strtoupper( apply_filters( 'wc4jp_delivery_details_text', __('Delivery request date and time', 'woocommerce-for-japan') ) ) );
            }

            if( $date_time['date'] ){
                printf( "\n%s: %s", apply_filters( 'wc4jp_delivery_date_text', __('Delivery Date', 'woocommerce-for-japan') ), $date_time['date'] );
            }

            if( $date_time['time'] ){
                printf( "\n%s: %s", apply_filters( 'wc4jp_time_zone_text', __('Time Zone', 'woocommerce-for-japan') ), $date_time['time'] );
            }

            echo "\n\n==========\n\n";

        } else {

            if( $show_title ) {
                printf( '<h2>%s</h2>', apply_filters( 'wc4jp_delivery_details_text', __('Delivery request date and time', 'woocommerce-for-japan') ) );
            }

            if( $date_time['date'] ){
                printf( "<p><strong>%s</strong> <br>%s</p>", apply_filters( 'wc4jp_delivery_date_text', __('Delivery Date', 'woocommerce-for-japan') ), $date_time['date'] );
            }

            if( $date_time['time'] ){
                printf( "<p><strong>%s</strong> <br>%s</p>", apply_filters( 'wc4jp_time_zone_text', __('Time Zone', 'woocommerce-for-japan') ), $date_time['time'] );
            }

        }

    }
    /**
     * Frontend: Add date and timeslot to order email
     *
     * @param obj $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @param obj $email
     */
    function email_order_delivery_details( $order, $sent_to_admin, $plain_text, $email ) {

        if( !$this->has_date_or_time( $order ) )
            return;

        if( $plain_text ) {

            $this->display_date_and_time_zone( $order, true, true );

        } else {

            $this->display_date_and_time_zone( $order, true );

        }

    }
    /**
     * Helper: Check if order has date or time
     *
     * @param obj $order
     * @return bool
     */
    function has_date_or_time( $order ) {

        $meta = array(
            'date' => false,
            'time' => false
        );
        $has_meta = false;
        $order_id = version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();

        $date = get_post_meta( $order_id, 'wc4jp-delivery-date', true);
        $time = get_post_meta( $order_id, 'wc4jp-delivery-time-zone', true);

        if( ( $date && $date != "" ) ) {

            $meta['date'] = $date;
            $has_meta = true;

        }

        if( ( $time && $time != "" ) ) {

            $meta['time'] = $time;
            $has_meta = true;

        }

        if( $has_meta ) {
            return $meta;
        }

        return false;

    }
    /**
     * Admin: Add Columns to orders tab
     *
     * @param arr $columns
     * @return arr
     */
    public function shop_order_columns( $columns ) {

        $columns['wc4jp_delivery'] = __( 'Delivery', 'woocommerce-for-japan' );

        return $columns;

    }

    /**
     * Admin: Output date and timeslot columns on orders tab
     *
     * @param str $column
     */
    public function render_shop_order_columns( $column ) {

        global $post, $woocommerce, $the_order;
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			if ( empty( $the_order ) || $the_order->get_id() != $post->ID ) {
            	$the_order = wc_get_order( $post->ID );
        	}
		}else{
        	if ( empty( $the_order ) || $the_order->ID != $post->ID ) {
            	$the_order = wc_get_order( $post->ID );
        	}			
		}

        switch ( $column ) {
            case 'wc4jp_delivery' :

                $this->display_date_and_time_zone( $the_order );

                break;
        }
    }
    /**
     * Admin: Display date and timeslot on the admin order page
     *
     * @param obj $order
     */
    function display_admin_order_meta( $order ) {

        $this->display_date_and_time_zone( $order );

    }
}
