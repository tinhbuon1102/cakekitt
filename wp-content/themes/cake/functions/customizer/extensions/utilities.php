<?php
/**
 * Customizer Utility Functions
 *
 * @author	Codeopus
 */


 /**
 * Helper function to return defaults
 *
 */
function cake_customizer_library_get_default( $setting ) {

	$customizer_library = Customizer_Library::Instance();
	$options = $customizer_library->get_options();

	if ( isset( $options[$setting]['default'] ) ) {
		return $options[$setting]['default'];
	}

}

/**
 * Helper function to return choices
 *
 */
function cake_customizer_library_get_choices( $setting ) {

	$customizer_library = Customizer_Library::Instance();
	$options = $customizer_library->get_options();

	if ( isset( $options[$setting]['choices'] ) ) {
		return $options[$setting]['choices'];
	}

}
