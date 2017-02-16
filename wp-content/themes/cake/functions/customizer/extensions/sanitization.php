<?php
/**
 * Customizer Sanization
 *
 * @author codeopus
 */

if ( ! function_exists( 'cake_customizer_library_sanitize_text' ) ) :
/**
 * Sanitize a string to allow only tags in the allowedtags array.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function cake_customizer_library_sanitize_text( $string ) {
	global $allowedtags;
	return wp_kses( $string , $allowedtags );
}
endif;


if ( ! function_exists( 'cake_customizer_library_sanitize_textarea' ) ) :
/**
 * Sanitize a string to allow only tags in the allowedtags array.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function cake_customizer_library_sanitize_textarea( $string ) {
	
	 return esc_textarea( $string );
	
}
endif;

if ( ! function_exists( 'cake_customizer_library_sanitize_checkbox' ) ) :
/**
 * Sanitize a checkbox to only allow 0 or 1
 *
 * @param  boolean    $value    The unsanitized value.
 * @return boolean				The sanitized boolean.
 */
function cake_customizer_library_sanitize_checkbox( $value ) {
	if ( $value == 1 ) {
		return 1;
    } else {
		return 0;
    }
}
endif;

if ( ! function_exists( 'cake_customizer_library_sanitize_choices' ) ) :
/**
 * Sanitize a value from a list of allowed values.
 *
 * @param  mixed    $value      The value to sanitize.
 * @param  mixed    $setting    The setting for which the sanitizing is occurring.
 * @return mixed                The sanitized value.
 */
function cake_customizer_library_sanitize_choices( $input, $setting ) {
	 global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}
endif;


if ( ! function_exists( 'cake_customizer_library_sanitize_file_url' ) ) :
/**
 * Sanitize the url of uploaded media.
 *
 * @param  string    $value      The url to sanitize
 * @return string    $output     The sanitized url.
 */
function cake_customizer_library_sanitize_file_url( $url ) {

	$output = '';

	$filetype = wp_check_filetype( $url );
	if ( $filetype["ext"] ) {
		$output = esc_url_raw( $url );
	}

	return $output;
}
endif;

if ( ! function_exists( 'cake_sanitize_hex_color' ) ) :
/**
 * Sanitizes a hex color.
 *
 * Returns either '', a 3 or 6 digit hex color (with #), or null.
 * For sanitizing values without a #, see sanitize_hex_color_no_hash().
 *
 * @param string $color
 * @return string|null
 */
function cake_sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}
endif;

if ( ! function_exists( 'cake_customizer_library_sanitize_range' ) ) :
/**
 * Sanitizes a range value
 *
 * @param string $color
 * @return string|null
 */
function cake_customizer_library_sanitize_range( $value ) {

	if ( is_numeric( $value ) ) {
		return $value;
	}

	return 0;
}
endif;
