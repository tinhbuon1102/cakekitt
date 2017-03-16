(function($) {
	$(document).ready( function() {
		setTimeout(function() {
			$( '.cntctfrm_contact_form.cntctfrm_labels_position_left, .cntctfrm_contact_form.cntctfrm_labels_position_right ' ).each( function() {
				var $form = $( this ),
					$field_wrap = $form.find( '.cntctfrm_field_wrap' ),
					$column = $form.find( '> .cntctfrm_column:visible' ),
					column_margin = parseInt( $form.find( '#cntctfrm_first_column' ).css( 'margin-right' ) ),
					column_count = $column.length,
					column_max_width = parseInt( $column.css( 'max-width' ) ),
					label_max_width = parseInt( $form.find( '.cntctfrm_label:first' ).css( 'max-width' ) ),
					label_new_max_width = 0,
					side = ( $form.is( '.cntctfrm_labels_position_left' ) ) ? 'right' : 'left';

				$field_wrap.each( function( index ) {
					var $label = $( this ).find( '.cntctfrm_label label' ),
						label_width = $label.outerWidth();
					if ( label_width > label_new_max_width ) {
						label_new_max_width = label_width;
					}
				});

				var padding = parseInt( $form.find( '.cntctfrm_label:first' ).css( 'padding-' + side ) );
					label_new_max_width = label_new_max_width + padding,
					column_new_max_width = column_max_width - ( label_max_width - label_new_max_width ),
					form_wrap_max_width = ( column_new_max_width * column_count ) + column_margin;

				$form.find( '.cntctfrm_label' ).attr( 'style', 'max-width: ' + label_new_max_width + 'px !important; width: 100% !important;' );
				$form.find( '.cntctfrm_column' ).css( 'max-width', column_new_max_width );
				$form.css({ 
					'max-width' : form_wrap_max_width,
					'width'		: '100%'
				});
			});
			$(window).resize(function() {
				$( '.cntctfrm_contact_form.cntctfrm_two_columns' ).each( function() {
					var $cntctfrm_form = $( this ),
						$parent = $cntctfrm_form.parent(),
						$cntctfrm_column = $cntctfrm_form.find( '.cntctfrm_column' ),
						label_width = $cntctfrm_form.find( '.cntctfrm_label:first' ).outerWidth(),
						margin = 10,
						min_column_width = 200,
						max_column_width = $cntctfrm_form.is( '.cntctfrm_form_tooltips' ) ? 280: 320;

					if ( $cntctfrm_form.is( '.cntctfrm_labels_position_left' ) || $cntctfrm_form.is( '.cntctfrm_labels_position_right' ) ) {
						min_column_width = min_column_width + label_width,
						max_column_width =  max_column_width + label_width;
					}

					var min_form_width = min_column_width * 2 + margin,
						max_form_width = max_column_width * 2 + margin;

					if( $parent.width() < max_form_width  ) {
						var new_column_width = Math.floor( ( $parent.width() - margin ) / 2 );
						if ( new_column_width * 2 + margin >= min_form_width ) {
							$cntctfrm_column.css( 'max-width', new_column_width );
							if ( $cntctfrm_form.is( '.cntctfrm_labels_position_left' ) || $cntctfrm_form.is( '.cntctfrm_labels_position_right' ) ) {
								var min_inputs_width = new_column_width - label_width;
								$cntctfrm_form.find( '.cntctfrm_input, .cntctfrm_select, .cntctfrm_checkbox' ).css( 'max-width', min_inputs_width );
							}
						}
					}
				});
			}).trigger( 'resize' );
		}, 100);
	});
})(jQuery);