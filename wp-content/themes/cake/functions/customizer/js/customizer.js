/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	
	"use strict";
	
	wp.customize('cake_responsive_layout',function( value ) {
		value.bind(function(to) {
			var $css = $('#cake-responsive-css');
			var $metar = $('meta[name=viewport]')
			if (false == to) {
				$css.remove();
				$metar.remove();
			}
		});
	});
	
	wp.customize('cake_loader_effect',function( value ) {
		value.bind(function(to) {
			var $loader = $('.ip-header');
			if (true == to) {
				$loader.addClass('hidden');
			} else {
				$loader.removeClass('hidden');
			}
		});
	});
	
	wp.customize('cake_layout_type',function( value ) {
		value.bind(function(to) {
			var $layout = $('.ip-container');
			if ('boxed' == to) {
				$layout.addClass('boxed');
			} else {
				$layout.addClass('fullwidth');
			}
		});
	});
	
	
	wp.customize( 'cake_404_text', function( value ) {
		value.bind( function( to ) {
			$( '.not_found_text' ).text( to );
		} );
	} );
	
	wp.customize( 'cake_nav_label', function( value ) {
		value.bind( function( to ) {
			$( '#top_title_nav' ).text( to );
		} );
	} );
	
	wp.customize( 'cake_social_nav_label', function( value ) {
		value.bind( function( to ) {
			$( '#top_social_title_nav' ).text( to );
		} );
	} );
	
	wp.customize( 'cake_about_label', function( value ) {
		value.bind( function( to ) {
			$( '#top_title_desc' ).text( to );
		} );
	} );
	
	wp.customize( 'cake_about_text', function( value ) {
		value.bind( function( to ) {
			$( '.desctext' ).text( to );
		} );
	} );
	
	
	wp.customize('cake_footer_color',function( value ) {
		value.bind(function(to) {
			$('#footer, #footer ul li a, #footer #wp-calendar a, #footer a').css('color', '#ff9900' );
		});
	});

} )( jQuery );
