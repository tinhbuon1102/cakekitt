<?php

/*
 
  Plugin Name: Woocommerce Pay in Store Gateway
  Plugin URI: http://emspace.gr
  Description: Provides a Pay in Store upon pick up Payment Gateway for Woocommerce. Woocommerce from Woothemes Cash on Delivery (COD) copy to provide Pay in Store as a payment method.
  Version: 9991.0.0
  Author: emspace.gr 
  Author URI: http://emspace.gr
  License:           GPL-3.0+
  License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
 if (!defined('ABSPATH'))
    exit;

add_action('plugins_loaded', 'woocommerce_pis_init', 0);

function woocommerce_pis_init() {

    if (!class_exists('WC_Payment_Gateway'))
        return;

  

    /**
     * Gateway class
     */
class WC_Gateway_PIS extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     */
	public function __construct() {
		$this->id                 = 'pis';
		$this->icon               = apply_filters( 'woocommerce_pis_icon', '' );
		$this->method_title       = __( 'Pay in Store', 'woocommerce' );
		$this->method_description = __( 'Have your customers pay with cash (or by other means) in store upon  pickup.', 'woocommerce' );
		$this->has_fields         = false;
			$this->supports = array( 'products', 'pre-orders' );
		// process batch pre-order payments
   
		
		
		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Get settings
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->instructions       = $this->get_option( 'instructions', $this->description );
		$this->enable = $this->get_option( 'enable_for_methods', array() );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_pis', array( $this, 'thankyou_page' ) );

    	// Customer Emails
    	add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}
	
    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields() {
    	global $woocommerce;

    	$shipping_methods = array();

    	if ( is_admin() )
	    	foreach ( WC()->shipping->load_shipping_methods() as $method ) {
		    	$shipping_methods[ $method->id ] = $method->get_title();
	    	}

    	$this->form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enable Pis', 'woocommerce' ),
				'label'       => __( 'Enable Pay in Store ', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => __( 'Title', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
				'default'     => __( 'Pay in Store', 'woocommerce' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your website.', 'woocommerce' ),
				'default'     => __( 'Pay with cash upon delivery.', 'woocommerce' ),
				'desc_tip'    => true,
			),
			'instructions' => array(
				'title'       => __( 'Instructions', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', 'woocommerce' ),
				'default'     => __( 'Pay with cash upon delivery.', 'woocommerce' ),
				'desc_tip'    => true,
			),
 	   );
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @return array
     */
	public function process_payment( $order_id ) {

		$order = new WC_Order( $order_id );

		// Mark as processing (payment won't be taken until delivery)
		$order->update_status( 'processing', __( 'Payment to be made upon pick up from store.', 'woocommerce' ) );

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_return_url( $order )
		);
	}
	

	

    /**
     * Output for the order received page.
     */
	public function thankyou_page() {
		if ( $this->instructions )
        	echo wpautop( wptexturize( $this->instructions ) );
	}

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
    	if ( $sent_to_admin || $order->payment_method !== 'pis' )
    		return;

		if ( $this->instructions )
        	echo wpautop( wptexturize( $this->instructions ) );
	}
}

 function woocommerce_add_pis_gateway($methods) {
        $methods[] = 'WC_Gateway_PIS';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_pis_gateway');
}