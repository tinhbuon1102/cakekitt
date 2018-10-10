<?php
/*
	Plugin Name: Flexible Product Fields Pro
	Plugin URI: https://www.wpdesk.net/products/flexible-product-fields-pro-woocommerce/
	Description: Allow customers to customize WooCommerce products before adding them to cart. Add fields: text, dropdown, checkbox, radio and assign fixed or percentage prices.
	Version: 1.1.4
	Author: WP Desk
	Author URI: https://www.wpdesk.net/
	Text Domain: flexible-product-fields-pro
	Domain Path: /lang/
	Requires at least: 4.5
    Tested up to: 4.9.7
    WC requires at least: 3.0.0
    WC tested up to: 3.4.4

	Copyright 2017 WP Desk Ltd.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$plugin_data = array(
    'plugin' => plugin_basename( __FILE__ ),
    'product_id' => 'Flexible Product Fields PRO',
    'version'   => '1.1.4',
    'config_uri' => admin_url( 'edit.php?post_type=fpf_fields' )
);

require_once( plugin_basename( 'classes/wpdesk/class-plugin.php' ) );

class Flexible_Product_Fields_PRO_Plugin extends WPDesk_Plugin_1_2 {

	protected   $script_version = '1.1.4';
    static 		$_instance = null;

    public function __construct( $plugin_data ) {

        $this->_plugin_namespace = 'flexible-product-fields-pro';
        $this->_plugin_text_domain = 'flexible-product-fields-pro';

        $this->_plugin_has_settings = false;

        parent::__construct( $plugin_data );
        if ( $this->plugin_is_active() ) {
            $this->init();
            $this->hooks();
        }
    }

    public function init() {
        require_once ( 'classes/class-flexible-product-fields-pro.php' );
        $this->fpf_pro = new FPF_PRO( $this );
        require_once 'classes/class-duplicate-post.php';
        $duplicate = new FPF_PRO_Duplicate( $this );
        $duplicate->hooks();
    }

    public function hooks() {
        parent::hooks();
    }

    public static function get_instance( $plugin_data ) {
        if ( self::$_instance == null ) {
            self::$_instance = new self( $plugin_data );
        }
        return self::$_instance;
    }

	public function wp_enqueue_scripts() {
		if ( !defined( 'WC_VERSION' ) ) {
			return;
		}
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		if ( is_product() ) {
			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . '1.9.2' . '/themes/smoothness/jquery-ui.css' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_localize_jquery_ui_datepicker' ), 1000 );

			wp_enqueue_script( 'flexible_product_fields_front_js', trailingslashit( $this->get_plugin_assets_url() ) . 'js/front.js', array( 'jquery' ), $this->script_version );
		}
	}

	function wp_localize_jquery_ui_datepicker() {
		global $wp_locale;
		global $wp_version;

		if ( ! wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) || version_compare( $wp_version, '4.6' ) != -1 ) {
			return;
		}

		// Convert the PHP date format into jQuery UI's format.
		$datepicker_date_format = str_replace(
			array(
				'd', 'j', 'l', 'z', // Day.
				'F', 'M', 'n', 'm', // Month.
				'Y', 'y'            // Year.
			),
			array(
				'dd', 'd', 'DD', 'o',
				'MM', 'M', 'm', 'mm',
				'yy', 'y'
			),
			get_option( 'date_format' )
		);

		$datepicker_defaults = wp_json_encode( array(
			'closeText'       => __( 'Close' ),
			'currentText'     => __( 'Today' ),
			'monthNames'      => array_values( $wp_locale->month ),
			'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
			'nextText'        => __( 'Next' ),
			'prevText'        => __( 'Previous' ),
			'dayNames'        => array_values( $wp_locale->weekday ),
			'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
			'dayNamesMin'     => array_values( $wp_locale->weekday_initial ),
			'dateFormat'      => $datepicker_date_format,
			'firstDay'        => absint( get_option( 'start_of_week' ) ),
			'isRTL'           => $wp_locale->is_rtl(),
		) );

		wp_add_inline_script( 'jquery-ui-datepicker', "jQuery(document).ready(function(jQuery){jQuery.datepicker.setDefaults({$datepicker_defaults});});" );
	}

	public function links_filter( $links ) {
		$pl     = get_locale() === 'pl_PL';
		$domain = $pl ? 'pl' : 'net';
		$utm_source = $this->_plugin_namespace;
		$utm_medium = 'quick-link';

		$plugin_links = array();

		if ( wpdesk_is_plugin_active( 'flexible-product-fields/flexible-product-fields.php' ) ) {
			$plugin_links[] = '<a href="' . admin_url( 'edit.php?post_type=fpf_fields' ) . '">' . __( 'Settings', 'flexible-product-fields-pro' ) . '</a>';
		}

		$plugin_links[] = '<a href="https://www.wpdesk.' . $domain . '/docs/flexible-product-fields-woocommerce-docs/?utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=docs-quick-link" target="_blank">' . __( 'Docs', 'flexible-product-fields-pro' ) . '</a>';
		$plugin_links[] = '<a href="https://www.wpdesk.' . $domain . '/support/?utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=support-quick-link" target="_blank">' . __( 'Support', 'flexible-product-fields-pro' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}
}

if ( !function_exists( 'wpdesk_is_plugin_active' ) ) {
	function wpdesk_is_plugin_active( $plugin_file ) {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		return in_array( $plugin_file, $active_plugins ) || array_key_exists( $plugin_file, $active_plugins );
	}
}

if ( is_admin() && !wpdesk_is_plugin_active( 'flexible-product-fields/flexible-product-fields.php' ) ) {
	function flexible_product_fields_pro_flexible_product_fields_install( $api, $action, $args ) {
		$download_url = 'http://downloads.wordpress.org/plugin/flexible-product-fields.latest-stable.zip';

		if ( 'plugin_information' != $action ||
		     false !== $api ||
		     ! isset( $args->slug ) ||
		     'wpdesk-helper' != $args->slug
		) return $api;

		$api = new stdClass();
		$api->name = 'Flexible Product Fields';
		$api->version = '1.1.4';
		$api->download_link = esc_url( $download_url );
		return $api;
	}

	add_filter( 'plugins_api', 'flexible_product_fields_pro_flexible_product_fields_install', 10, 3 );

	function flexible_product_fields_pro_notice() {

		if ( wpdesk_is_plugin_active( 'flexible-product-fields/flexible-product-fields.php' ) ) return;

		$slug = 'flexible-product-fields';
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
		$activate_url = 'plugins.php?action=activate&plugin=' . urlencode( 'flexible-product-fields/flexible-product-fields.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_flexible-product-fields/flexible-product-fields.php' ) );

		$message = sprintf( wp_kses( __( 'Flexible Product Fields PRO requires free Flexible Product Fields plugin. <a href="%s">Install Flexible Product Fields →</a>', 'flexible-product-fields-pro' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $install_url ) );
		$is_downloaded = false;
		$plugins = array_keys( get_plugins() );
		foreach ( $plugins as $plugin ) {
			if ( strpos( $plugin, 'flexible-product-fields/flexible-product-fields.php' ) === 0 ) {
				$is_downloaded = true;
				$message = sprintf( wp_kses( __( 'Flexible Product Fields PRO requires activating Flexible Product Fields plugin. <a href="%s">Activate Flexible Product Fields →</a>', 'flexible-product-fields-pro' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url( $activate_url ) ) );
			}
		}
		echo '<div class="error fade"><p>' . $message . '</p></div>' . "\n";
	}
	add_action( 'admin_notices', 'flexible_product_fields_pro_notice' );
}


$GLOBALS['flexible_product_fields_pro'] = new Flexible_Product_Fields_PRO_Plugin( $plugin_data );

