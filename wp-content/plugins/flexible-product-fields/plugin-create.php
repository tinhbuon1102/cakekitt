<?php

use WPDesk\PluginBuilder\BuildDirector\LegacyBuildDirector;
use WPDesk\PluginBuilder\Builder\InfoBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/** @var WPDesk_Plugin_Info $plugin_info */
$builder        = new InfoBuilder( $plugin_info );
$build_director = new LegacyBuildDirector( $builder );
$build_director->build_plugin();


add_action( 'plugins_loaded', 'flexible_product_fields_plugins_loaded' );
function flexible_product_fields_plugins_loaded() {
	if ( ! function_exists( 'should_enable_wpdesk_tracker' ) ) {
		function should_enable_wpdesk_tracker() {
			$tracker_enabled = true;
			if ( ! empty( $_SERVER['SERVER_ADDR'] ) && $_SERVER['SERVER_ADDR'] === '127.0.0.1' ) {
				$tracker_enabled = false;
			}

			return apply_filters( 'wpdesk_tracker_enabled', $tracker_enabled );
		}
	}

	$tracker_factory = new WPDesk_Tracker_Factory();
	$tracker_factory->create_tracker( basename( dirname( __FILE__ ) ) );
}



/**
 * Checks if Flexible Product Fields PRO is active
 *
 */
function is_flexible_products_fields_pro_active() {
	return wpdesk_is_plugin_active( 'flexible-product-fields-pro/flexible-product-fields-pro.php' );
}


if ( !function_exists( 'wpdesk__' ) ) {
	function wpdesk__( $text, $domain ) {
		if ( function_exists( 'icl_sw_filters_gettext' ) ) {
			return icl_sw_filters_gettext( $text, $text, $domain, $text );
		}
		if ( function_exists( 'pll__' ) ) {
			return pll__( $text );
		}
		return __( $text, $domain );
	}
}

if ( !function_exists( 'wpdesk__e' ) ) {
	function wpdesk__e( $text, $domain ) {
		echo wpdesk__( $text, $domain );
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
