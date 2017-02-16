<?php
/**
 * Customizer Utility Functions
 *
 * @author	Codeopus
 */
 
 /**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customizer_library_customize_preview_js() {

	wp_enqueue_script( 'cake-customizer', get_template_directory_uri() . '/functions/customizer/js/customizer.js', array( 'customize-preview'), '', true );

}
add_action( 'customize_preview_init', 'customizer_library_customize_preview_js' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customizer_library_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'cake_404_text' )->transport = 'postMessage';
	$wp_customize->get_setting( 'cake_nav_label' )->transport = 'postMessage';
	$wp_customize->get_setting( 'cake_social_nav_label' )->transport = 'postMessage';
	$wp_customize->get_setting( 'cake_about_label' )->transport = 'postMessage';
	$wp_customize->get_setting( 'cake_about_text' )->transport = 'postMessage';
	$wp_customize->get_setting( 'cake_footer_color' )->transport = 'postMessage';
}
add_action( 'customize_register', 'customizer_library_customize_register' );



