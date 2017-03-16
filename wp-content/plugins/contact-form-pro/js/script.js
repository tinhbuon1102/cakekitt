(function($) {
	$(document).ready( function() {
		$( '#cntctfrm_show_multi_notice' ).removeAttr('href title').css('cursor', 'pointer');

		$( 'input[name="cntctfrm_custom_email"]' ).focus( function() {
			$( '#cntctfrm_select_email_custom' ).attr( 'checked', 'checked' );
		});

		$( 'input[name="cntctfrm_from_field"]' ).focus( function() {
			$( this ).trigger( 'change' );
			$( '#cntctfrm_select_from_custom_field' ).attr( 'checked', 'checked' );
		});

		$( 'input[name="cntctfrm_custom_from_email"]' ).focus( function() {
			$( this ).trigger( 'change' );
			$( '#cntctfrm_from_custom_email' ).attr( 'checked', 'checked' );
		});

		$( 'input[name="cntctfrm_redirect_url"]' ).focus( function() {
			$( '#cntctfrm_action_after_send' ).attr( 'checked', 'checked' );
		});

		$( '#cntctfrm_style_options' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_style_block' ).show();
			} else {
				$( '.cntctfrm_style_block' ).hide();
			}
		});
		$( '#cntctfrm_change_label' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_change_label_block' ).show();
			} else {
				$( '.cntctfrm_change_label_block' ).hide();
			}
		});
		$( '#cntctfrm_display_add_info' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_display_add_info_block' ).show();
			} else {
				$( '.cntctfrm_display_add_info_block' ).hide();
			}
		});
		$( '#cntctfrm_auto_response' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_auto_response_block' ).show();
			} else {
				$( '.cntctfrm_auto_response_block' ).hide();
			}
		});
		$( '#cntctfrm_add_language_button' ).click( function( event ) {
			event = event || window.event;
			event.preventDefault();
			$.ajax({
				url: '../wp-admin/admin-ajax.php',
				type: "POST",
				data: 'action=cntctfrm_add_language&is_ajax=true&lang=' + $( '#cntctfrm_languages' ).val() + '&cntctfrm_ajax_nonce_field=' + cntctfrm_ajax.cntctfrm_nonce,
				success: function(result) {
					var text = $.parseJSON( result );
					var lang_val = $( '#cntctfrm_languages' ).val();
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab, .cntctfrm_action_after_send_block .cntctfrm_language_tab' ).each( function() {
						$( this ).addClass( 'hidden' );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).first().clone().appendTo( '.cntctfrm_change_label_block' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_default' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).first().clone().insertBefore( '#cntctfrm_before' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_default' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( 'input' ).each( function() {
						$( this ).val( '' );
						$( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[default]', '[' + lang_val + ']' ) );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( 'textarea' ).each( function() {
						$( this ).text( '' );
						$( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[default]', '[' + lang_val + ']' ) );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_shortcode_for_language' ).last().html( text );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).val( '' ).attr( 'name', $( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).attr( 'name' ).replace( '[default]', '[' + lang_val + ']' ) );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_shortcode_for_language' ).last().html( text );
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab, .cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).each( function() {
						$( this ).removeClass( 'cntctfrm_active' );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_clear' ).prev().clone().attr( 'id', 'cntctfrm_label_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>' ).insertBefore( '.cntctfrm_change_label_block .cntctfrm_clear' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_clear' ).prev().clone().attr( 'id', 'cntctfrm_text_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>' ).insertBefore( '.cntctfrm_action_after_send_block .cntctfrm_clear' );
					$( '#cntctfrm_languages option:selected' ).remove();
				},
				error: function( request, status, error ) {
					alert( error + request.status );
				}
			});
		});

		$( '.cntctfrm_contact_form .cntctfrm_help_box' ).bind( 'show_tooltip', function() {
			$help_box = $( this ).children();
			$( this ).removeClass( 'cntctfrm_hide_tooltip' ).addClass( 'cntctfrm_show_tooltip' );
			if ( $help_box.offset().left + $help_box.innerWidth() > $( window ).width() ) {
				$help_box.addClass( 'cntctfrm_hidden_help_text_down' );
			}
		});

		$( '.cntctfrm_contact_form .cntctfrm_help_box' ).bind( 'hide_tooltip', function() {
			$help_box = $( this ).children();
			$( this ).removeClass( 'cntctfrm_show_tooltip' ).addClass( 'cntctfrm_hide_tooltip' );
			$help_box.removeClass( 'cntctfrm_hidden_help_text_down' );
		});

		$( '.cntctfrm_contact_form .cntctfrm_help_box' ).mouseenter( function() {
			$( this ).trigger( 'show_tooltip' );
		}).mouseleave( function() {
			$( this ).trigger( 'hide_tooltip' );
		});

		$( '.cntctfrm_contact_form .cntctfrm_help_box.cntctfrm_show_tooltip' ).on( 'click', function() {
			$( this ).trigger( 'hide_tooltip' );
		});

		$( '.cntctfrm_contact_form .cntctfrm_help_box.cntctfrm_hide_tooltip' ).on( 'click', function() {
			$( this ).trigger( 'show_tooltip' );
		});

		$( '#cntctfrm_show_errors_block' ).removeClass( 'hidden' );
		var arr = [ 'department', 'location', 'name', 'address', 'email', 'phone', 'subject', 'message', 'attachment', 'captcha' ];
		$.each( arr, function( index, value ) {
			if ( $( 'input[name="cntctfrm_tooltip_display_' + value + '"]' ).is( ':checked' ) ) {
				$( '#cntctfrm_contact_' + value ).next( '.cntctfrm_help_box' ).removeClass( 'hidden' );
			}
			$( "input:checkbox[name='cntctfrm_tooltip_display_" + value + "']" ).change( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( '#cntctfrm_contact_' + value ).next( '.cntctfrm_help_box' ).removeClass( 'hidden' );
				} else {
					$( '#cntctfrm_contact_' + value ).next( '.cntctfrm_help_box' ).addClass( 'hidden' );
				}
				if( $( "input:checkbox[name^='cntctfrm_tooltip_display']:checked").size() > 0 ) {
					$( "div.cntctfrm_contact_form" ).addClass( 'cntctfrm_form_tooltips' );
				} else {
					$( "div.cntctfrm_contact_form" ).removeClass( 'cntctfrm_form_tooltips' );
				}
			});
		});
		if ( $( 'input[name="cntctfrm_tooltip_display_attachment"]' ).is( ':checked' ) ) {
			$( '#cntctfrm_contact_attachment' ).siblings( 'label' ).css( 'display', 'none' );
		};
		$( 'input:checkbox[name="cntctfrm_tooltip_display_attachment"]' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '#cntctfrm_contact_attachment' ).siblings( 'label' ).css( 'display', 'none' );
			} else {
				$( '#cntctfrm_contact_attachment' ).siblings( 'label' ).css( 'display', 'block' );
			};
		});

		$( '#cntctfrm_show_errors' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				var error_displaying = $( 'select[name="cntctfrm_error_displaying"] option:selected' ).val();
				if ( error_displaying == 'labels' ) {
					$( '.cntctfrm_error_text' ).removeClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).removeClass( 'cntctfrm_error' );
				}
				if ( error_displaying == 'input_colors' ) {
					$( '.cntctfrm_error_text' ).addClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).not( '[type="file"], [type="checkbox"]' ).addClass( 'cntctfrm_error' );
				}
				if ( error_displaying == 'both' ) {
					$( '.cntctfrm_error_text' ).removeClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).not( '[type="file"], [type="checkbox"]' ).addClass( 'cntctfrm_error' );
				}
				$( '#cntctfrm_contact_form .cntctfrm_input_captcha .cntctfrm_help_box' ).addClass( 'cntctfrm_help_box_error' );
				$( '.cntctfrm_error' ).next( '.cntctfrm_help_box' ).addClass( 'cntctfrm_help_box_error' );
			} else {
				$( '.cntctfrm_error_text' ).addClass( 'hidden' );
				$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).removeClass( 'cntctfrm_error' );
				$( '.cntctfrm_help_box' ).removeClass( 'cntctfrm_help_box_error' );
			}
		});
		$( 'select[name="cntctfrm_error_displaying"]' ).change( function() {
			if ( $( '#cntctfrm_show_errors' ).is( ':checked' ) ) {
				var error_displaying = $( 'select[name="cntctfrm_error_displaying"] option:selected' ).val();
				if ( error_displaying == 'labels' ) {
					$( '.cntctfrm_error_text' ).removeClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).removeClass( 'cntctfrm_error' );
				}
				if ( error_displaying == 'input_colors' ) {
					$( '.cntctfrm_error_text' ).addClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).not( '[type="file"], [type="checkbox"]' ).addClass( 'cntctfrm_error' );
				}
				if ( error_displaying == 'both' ) {
					$( '.cntctfrm_error_text' ).removeClass( 'hidden' );
					$( '#cntctfrm_contact_form input.cntctfrm_test_error, #cntctfrm_contact_form textarea.cntctfrm_test_error, #cntctfrm_contact_form select.cntctfrm_test_error' ).not( '[type="file"], [type="checkbox"]' ).addClass( 'cntctfrm_error' );
				}
			}
		});

		$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
		$( '.cntctfrm_language_tab_block_mini' ).css( 'display', 'block' );

		var border_input = $( 'input[name="cntctfrm_border_input_width"]' ),
			button_width = $( 'input[name="cntctfrm_button_width"]' );

		border_input.blur( function border_width() {
			var color = $( this ).val();
			$( '#cntctfrm_contact_form input.text, #cntctfrm_contact_form textarea, #cntctfrm_contact_form select, #cntctfrm_contact_form .cntctfrm_contact_submit' ).css( 'border-width', color + 'px' );
		});

		button_width.blur( function button_width() {
			var color = $( this ).val();
			$( '#cntctfrm_contact_form .cntctfrm_contact_submit' ).css( 'width', color );
		});

		/* add styles for preview */
		$( '#cntctfrm_onload_styles' ).remove();
		border_input.trigger( 'blur' );
		button_width.trigger( 'blur' );

		$( '.cntctfrm_color' ).each( function() {
			var target_class = $( this ).attr( 'class' );
			var color = $( this ).val();
			target_class = target_class.split(" ");
			switch_color( target_class[0], color );
		});

		/* include color-picker */
		var colorPickerOptions = {
			change: function( event, ui ) {
				var color = ui.color.toString(),
					target_class = event.target.classList[0];
				switch_color( target_class, color );
			},

			clear: function() {
				var styleId = $( this ).prev().attr( 'class' );
				styleId = styleId.split(" ");
				$( '#' + styleId ).remove();
			},
			/* hide the color picker controls on load*/
			hide: true,
			/* show a group of common colors beneath the square or, supply an array of colors to customize further*/
			palettes: true
		};

		$( '.cntctfrm_color' ).wpColorPicker( colorPickerOptions );

		function switch_color( target_class, color ) {
			switch ( target_class ) {
				case 'cntctfrm_label_color':
					styleContent = '#cntctfrm_contact_form { color: ' + color + ' }'
					if ( 0 == $( '#cntctfrm_label' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_label">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_label' ).html( styleContent );
					break;
				case 'cntctfrm_placeholder_color':
					if ( 0 == $( '#cntctfrm_placeholder' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_placeholder">';
						$( 'head' ).append( styleBlock );
					}
					styleContent = '#cntctfrm_contact_form input::-moz-placeholder, #cntctfrm_contact_form textarea::-moz-placeholder {color: ' + color + ';} #cntctfrm_contact_form input::-webkit-input-placeholder, #cntctfrm_contact_form textarea::-webkit-input-placeholder {color: ' + color + ';} #cntctfrm_contact_form input:-ms-input-placeholder, #cntctfrm_contact_form textarea:-ms-input-placeholder {color: ' + color + ';} #cntctfrm_contact_form input:-moz-placeholder, #cntctfrm_contact_form textarea:-moz-placeholder { color: ' + color + ' }';
					$( '#cntctfrm_placeholder' ).html( styleContent );
					break;
				case 'cntctfrm_error_text_color':
					styleContent = '#cntctfrm_contact_form .cntctfrm_error_text { color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_error_text_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_error_text_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_error_text_color' ).html( styleContent );
					break;
				case 'cntctfrm_error_background_color':
					styleContent = '#cntctfrm_contact_form .cntctfrm_error { background-color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_error_background_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_error_background_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_error_background_color' ).html( styleContent );
					break;
				case 'cntctfrm_error_border_color':
					styleContent = '.cntctfrm_error { border-color: ' + color + '!important }';
					if ( 0 == $( '#cntctfrm_error_border_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_error_border_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_error_border_color' ).html( styleContent );
					break;
				case 'cntctfrm_error_placeholder':
					styleContent = '#cntctfrm_contact_form input.cntctfrm_error::-moz-placeholder, #cntctfrm_contact_form textarea.cntctfrm_error::-moz-placeholder {color: ' + color + ';}	#cntctfrm_contact_form input.cntctfrm_error::-webkit-input-placeholder, #cntctfrm_contact_form textarea.cntctfrm_error::-webkit-input-placeholder {color: ' + color + ';} #cntctfrm_contact_form input.cntctfrm_error:-ms-input-placeholder, #cntctfrm_contact_form textarea.cntctfrm_error:-ms-input-placeholder {color: ' + color + ';} #cntctfrm_contact_form input.cntctfrm_error:-moz-placeholder, #cntctfrm_contact_form textarea.cntctfrm_error:-moz-placeholder { color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_error_placeholder' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_error_placeholder">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_error_placeholder' ).html( styleContent );
					break;
				case 'cntctfrm_background_color':
					styleContent = '.cntctfrm_input input[type="text"], .cntctfrm_input textarea, .cntctfrm_select select { background-color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_background_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_background_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_background_color' ).html( styleContent );
					break;
				case 'cntctfrm_input_color':
					styleContent = '.cntctfrm_input input[type="text"], .cntctfrm_input textarea, .cntctfrm_select select { color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_input_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_input_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_input_color' ).html( styleContent );
					break;
				case 'cntctfrm_border_color':
					styleContent = '.cntctfrm_input input, .cntctfrm_input textarea, .cntctfrm_select select, .cntctfrm_input cntctfrm_input_submit { border-color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_border_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_border_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_border_color' ).html( styleContent );
					break;
				case 'cntctfrm_button_backgroud':
					styleContent = '.cntctfrm_submit_field_wrap input { background-color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_button_backgroud' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_button_backgroud">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_button_backgroud' ).html( styleContent );
					break;
				case 'cntctfrm_button_color':
					styleContent = '.cntctfrm_submit_field_wrap input { color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_button_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_button_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_button_color' ).html( styleContent );
					break;
				case 'cntctfrm_border_button_color':
					styleContent = '.cntctfrm_submit_field_wrap input { border-color: ' + color + ' }';
					if ( 0 == $( '#cntctfrm_border_button_color' ).length ) {
						styleBlock = '<style type="text/css" id="cntctfrm_border_button_color">';
						$( 'head' ).append( styleBlock );
					}
					$( '#cntctfrm_border_button_color' ).html( styleContent );
					break;
			}
		}

		/* departament select in admin */
		$( '.cntctfrm_add_new' ).addClass( 'hidden' );
		$( '#cntctfrm_department_add' ).removeClass( 'hidden' );
		$( '#cntctfrm_department_add' ).click( function() {
			var department = $( '.cntctfrm_department_block_new' ).clone().addClass( 'cntctfrm_department_block' ).removeClass( 'cntctfrm_department_block_new' );
			$( department ).children().children().val( '' );
			$( department ).appendTo( '.cntctfrm_department_sortable' );
		});
		$( '.cntctfrm_department_delete input' ).addClass( 'cntctfrm_del_check' );

		$( ".cntctfrm_department_sortable" ).sortable( {
			handle : '.cntctfrm_drag_departament'
		});
		$( '.cntctfrm_department_table' ).on( 'click', function() {
			$( '#cntctfrm_select_email_department' ).prop( "checked", true );
		});

		/* fields table validation */
		$( 'input[name=cntctfrm_required_subject_field], input[name=cntctfrm_required_message_field]' ).each( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( 'input[name=cntctfrm_visible_' + $( this ).attr( 'class' ) + '], input[name=cntctfrm_disabled_' + $( this ).attr( 'class' )  + ']' ).attr( 'disabled', 'disabled' );
			}
			$( this ).click( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( 'input[name=cntctfrm_visible_' + $( this ).attr( 'class' ) + ']' ).attr( 'disabled', 'disabled' ).prop( "checked", true );
					$( 'input[name=cntctfrm_disabled_' + $( this ).attr( 'class' )  + ']' ).attr( 'disabled', 'disabled' ).prop( "checked", false );
				} else {
					$( 'input[name=cntctfrm_visible_' + $( this ).attr( 'class' ) + '], input[name=cntctfrm_disabled_' + $( this ).attr( 'class' )  + ']' ).removeAttr( 'disabled' );
				}
			});
		});

		/* changing values in 'visible' and 'disabled' columns for 'name' field when 'default value' column is changed */
		$( 'input[name=cntctfrm_default_name]' ).each( function() {
			if ( ! $( this ).is( ':checked' ) ) {
				$( 'input[name=cntctfrm_visible_name], input[name=cntctfrm_disabled_name]' ).attr( 'disabled', 'disabled' );
			}
			$( this ).click( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( 'input[name=cntctfrm_visible_name], input[name=cntctfrm_disabled_name]' ).removeAttr( 'disabled' );
				} else {
					$( 'input[name=cntctfrm_visible_name]' ).attr( 'disabled', 'disabled' ).prop( "checked", true );
					$( 'input[name=cntctfrm_disabled_name]' ).attr( 'disabled', 'disabled' ).prop( "checked", false );
				}
			});
		});

		/* changing values in 'visible' and 'disabled' columns for 'email' field when 'default value' column is changed */
		$( 'input[name=cntctfrm_default_email]' ).each( function() {
			if ( ! $( this ).is( ':checked' ) ) {
				$( 'input[name=cntctfrm_visible_email], input[name=cntctfrm_disabled_email]' ).attr( 'disabled', 'disabled' );
			}
			$( this ).click( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( 'input[name=cntctfrm_visible_email], input[name=cntctfrm_disabled_email]' ).removeAttr( 'disabled' );
				} else {
					$( 'input[name=cntctfrm_visible_email]' ).attr( 'disabled', 'disabled' ).prop( "checked", true );
					$( 'input[name=cntctfrm_disabled_email]' ).attr( 'disabled', 'disabled' ).prop( "checked", false );
				}
			});
		});

		/* add style for row when click 'Used' for field */
		$( '.cntctfrm_checkbox_disabled_row' ).click( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).parents( 'tr' ).filter( ':first' ).removeClass( 'cntctfrm_disabled_row' );
			} else {
				$( this ).parents( 'tr' ).filter( ':first' ).addClass( 'cntctfrm_disabled_row' );
			}
		});

		/* change form layout in the settings page appearance tab */
		$( 'input[name="cntctfrm_layout"]' ).change( function() {

			var form_layout = $( this ).val();

			if ( form_layout == 1 ) {
				$( '#cntctfrm_settings_form #cntctfrm_contact_form' ).removeClass( 'cntctfrm_two_columns' );
				$( '#cntctfrm_settings_form #cntctfrm_contact_form' ).addClass( 'cntctfrm_one_column' );
				if( $( '#cntctfrm_second_column li' ).length > 0 ) {
					$( '#cntctfrm_first_column' ).append( $( '#cntctfrm_second_column' ).html() );
				}
				$( '#cntctfrm_second_column' ).html( '' );
			}

			if ( form_layout == 2 ) {
				$( '#cntctfrm_settings_form #cntctfrm_contact_form' ).removeClass( 'cntctfrm_one_column' );
				$( '#cntctfrm_settings_form #cntctfrm_contact_form' ).addClass( 'cntctfrm_two_columns' );
				$( '#cntctfrm_second_column' ).show();
			}

			$( '#cntctfrm_first_column, #cntctfrm_second_column' ).addClass( 'cntctfrm_column_placeholder' );
			$( '#cntctfrm_second_column' ).css( 'height', $( '#cntctfrm_first_column' ).height() );

			setTimeout( function() {
				$( '#cntctfrm_first_column, #cntctfrm_second_column' ).removeClass( 'cntctfrm_column_placeholder' );
				$( '#cntctfrm_second_column' ).css( 'height', 'auto' );
			}, 1000 );

			cntctfrm_form_width();
		});

		/* change labels position in the settings page appearance tab */
		$( 'input[name="cntctfrm_labels_position"]' ).change( function() {

			var new_labels_position = $( this ).val();

			$( '.cntctfrm_contact_form .cntctfrm_field_wrap' ).each( function() {
				var $hook = $( this ).find( '.cntctfrm_label_hook' ),
					$label = $( this ).find( '.cntctfrm_label' ),
					$error = $( this ).find( '.cntctfrm_error_text' );
				switch ( new_labels_position ) {
					case 'top':
						$label.insertBefore( $hook );
						$error.insertBefore( $hook );
						break;
					case 'bottom':
						$label.insertAfter( $hook );
						$error.insertAfter( $hook );
						break;
					case 'left':
					case 'right':
						$label.insertBefore( $hook );
						if ( ! $hook.is( '.cntctfrm_checkbox_privacy_check' ) ) {
							$error.insertBefore( $label );
						} else {
							$error.prependTo( $hook );
						}
						break;
				}
			});

			$( ".cntctfrm_contact_form" ).attr( 'class', function() {
				var current_class_labels_position = $( this ).attr( 'class' );
					class_name = 'cntctfrm_labels_position_' + new_labels_position,
					new_class_labels_position = current_class_labels_position.replace( /cntctfrm_labels_position_top|cntctfrm_labels_position_left|cntctfrm_labels_position_bottom|cntctfrm_labels_position_right/, class_name );
				return new_class_labels_position;
			});

			$( ".cntctfrm_appearance_ltr #cntctfrm_right_table, .cntctfrm_appearance_rtl #cntctfrm_left_table" ).attr( 'class', 'cntctfrm_labels_position_' + new_labels_position );

			cntctfrm_labels_width();
		});

		/* change labels align in the settings page appearance tab */
		$( 'input[name="cntctfrm_labels_align"]' ).change( function() {
			var current_align = $( this ).val();
			$( ".cntctfrm_contact_form" ).attr( 'class', function() {
				var current_class = $( this ).attr( 'class' );
					new_align = 'cntctfrm_labels_align_' + current_align,
					new_class = current_class.replace( /cntctfrm_labels_align_left|cntctfrm_labels_align_center|cntctfrm_labels_align_right/, new_align );
				return new_class;
			});
		});

		/* change submit position in the settings page appearance tab */
		$( 'input[name="cntctfrm_submit_position"]' ).data( 'prev_val', $( 'input[name="cntctfrm_submit_position"]:checked' ).val() );
		$( 'input[name="cntctfrm_submit_position"]' ).change( function() {
			var current_position = $( this ).val(),
				prev_position = $( this ).data( 'prev_val' ),
				direction = $( '.cntctfrm_contact_form' ).hasClass( 'cntctfrm_rtl' ) ? 'rtl' : 'ltr';
				submit = {
					'ltr' : {
						'left'  : '#cntctfrm_submit_first_column',
						'right' : '#cntctfrm_submit_second_column'
					},
					'rtl' : {
						'left'  : '#cntctfrm_submit_second_column',
						'right' : '#cntctfrm_submit_first_column'
					}
				},
				html = $( submit[ direction ][ prev_position ] ).find( '.cntctfrm_submit_field_wrap' ).html();
			$( submit[ direction ][ current_position ] ).find( '.cntctfrm_submit_field_wrap' ).html( html );
			$( submit[ direction ][ prev_position ] ).find( '.cntctfrm_submit_field_wrap' ).html( '' );
			$( 'input[name="cntctfrm_submit_position"]' ).data( 'prev_val', current_position );
			$( '.cntctfrm_input_submit' ).attr( 'style', 'text-align: ' + current_position + ' !important' );
		});

		/* resize labels when page load first time in the settings page appearance tab */
		cntctfrm_labels_width();

		/* resize labels in the settings page appearance tab */
		function cntctfrm_labels_width() {
			var $form = $( '.cntctfrm_contact_form' );

			$form.find( '.cntctfrm_column, .cntctfrm_label' ).removeAttr( 'style' );

			if ( $form.hasClass( 'cntctfrm_labels_position_left' ) || $form.hasClass( 'cntctfrm_labels_position_right' ) ) {
				setTimeout(function() {
					var $field_wrap = $form.find( '.cntctfrm_field_wrap' ),
						$column = $form.find( '#cntctfrm_wrap .cntctfrm_column:visible' ),
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
						column_new_max_width = ( column_max_width - ( label_max_width - label_new_max_width ) ),

					$form.find( '.cntctfrm_label' ).attr( 'style', 'max-width: ' + label_new_max_width + 'px !important;' );
					$form.find( '.cntctfrm_column' ).attr( 'style', 'max-width: ' + column_new_max_width + 'px !important; width: 100% !important;' );

					cntctfrm_form_width();
				}, 100);
			} else {
				cntctfrm_form_width();
			}
		}

		/* resize form in the settings page appearance tab */
		function cntctfrm_form_width() {
			var $form = $( '.cntctfrm_contact_form' ),
				$column = $form.find( '#cntctfrm_wrap .cntctfrm_column:visible' ),
				column_width = parseInt( $column.css( 'max-width' ) ),
				column_count = $column.length,
				column_margin = ( $form.hasClass( 'cntctfrm_two_columns' ) ) ? 10 : 0,
				column_borders = 4,
				form_wrap_max_width = ( column_width * column_count ) + column_margin + column_borders;

			$( '.cntctfrm_contact_form' ).attr( 'style', 'max-width: ' + form_wrap_max_width + 'px;' );
		}

		/* sorting fields in the settings page appearance tab */
		$( "#cntctfrm_first_column, #cntctfrm_second_column" ).sortable({
			items      : 'li',
			connectWith: '.cntctfrm_column',
			start: function ( e, ui ) {
				$( '#cntctfrm_first_column, #cntctfrm_second_column' ).addClass( 'cntctfrm_column_placeholder' );
				$( '#cntctfrm_first_column, #cntctfrm_second_column' ).css( 'padding-bottom', 1 );
			},
			stop: function ( e, ui ) {
				$( '#cntctfrm_first_column, #cntctfrm_second_column' ).removeClass( 'cntctfrm_column_placeholder' );
			},
			update: function ( e, ui ) {
				var fields_first_column = fields_second_column = '';

				$( '#cntctfrm_first_column .cntctfrm_field_wrap' ).each( function() {
					fields_first_column += $( this ).find( 'input, select, textarea' ).filter( ':first' ).attr( 'name' ) + ',';
				});
				fields_first_column = fields_first_column.substring( 0, fields_first_column.length - 1 );

				$( '#cntctfrm_second_column .cntctfrm_field_wrap' ).each( function() {
					fields_second_column += $( this ).find( 'input, select, textarea' ).filter( ':first' ).attr( 'name' ) + ',';
				});
				fields_second_column = fields_second_column.substring( 0, fields_second_column.length - 1 );

				$( '#cntctfrm_layout_first_column' ).val( fields_first_column );
				$( '#cntctfrm_layout_second_column' ).val( fields_second_column );

				if( typeof bws_show_settings_notice == 'function' ) {
					bws_show_settings_notice();
				}
			}
		}).disableSelection();
	});
	$(document).on( 'click', '.cntctfrm_change_label_block .cntctfrm_label_language_tab', function() {
		$( '.cntctfrm_label_language_tab' ).each( function() {
			$( this ).removeClass( 'cntctfrm_active' );
		});
		var index = $( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).index( $( this ) );
		$( this ).addClass( 'cntctfrm_active' );
		var blocks = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' );
		$( blocks[ index ] ).addClass( 'cntctfrm_active' );
		$( '.cntctfrm_language_tab' ).each( function() {
			$( this ).addClass( 'hidden' );
		});
		$( '.' + this.id.replace( 'label', 'tab' ) ).removeClass( 'hidden' );
	});
	$(document).on( 'click', '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab', function() {
		$( '.cntctfrm_label_language_tab' ).each( function() {
			$( this ).removeClass( 'cntctfrm_active' );
		});
		var index = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).index( $( this ) );
		$( this ).addClass( 'cntctfrm_active' );
		var blocks = $( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' );
		$( blocks[ index ] ).addClass( 'cntctfrm_active' );
		$( '.cntctfrm_language_tab' ).each( function() {
			$( this ).addClass( 'hidden' );
		});
		$( '.' + this.id.replace( 'text', 'tab' ) ).removeClass( 'hidden' );
	});
	$(document).on( 'click', '.cntctfrm_delete', function( event ) {
		event = event || window.event
		event.stopPropagation();
		if ( confirm( cntctfrm_ajax.cntctfrm_confirm_text ) ) {
			var lang = $( this ).attr( 'rel' );
			$.ajax({
				url: '../wp-admin/admin-ajax.php',
				type: "POST",
				data: 'action=cntctfrm_remove_language&is_ajax=true&lang=' + lang + '&cntctfrm_ajax_nonce_field=' + cntctfrm_ajax.cntctfrm_nonce,
				success: function( result ) {
					$( '#cntctfrm_label_' + lang + ', #cntctfrm_text_' + lang + ', .cntctfrm_tab_' + lang ).each( function() {
						$( this ).remove();
					});
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).removeClass( 'cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).removeClass('cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).addClass( 'hidden' ).first().removeClass( 'hidden' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).addClass( 'hidden' ).first().removeClass( 'hidden' );
				},
				error: function( request, status, error ) {
					alert( error + request.status );
				}
			});
		}
	});
	$(document).on( "click", '.cntctfrm_language_tab_block_mini', function() {
		if ( $( '.cntctfrm_language_tab_block' ).css( 'display' ) == 'none' ) {
			$( '.cntctfrm_language_tab_block' ).css( 'display', 'block' );
			$( '.cntctfrm_language_tab_block_mini' ).addClass( 'cntctfrm_language_tab_block_mini_open' );
		} else {
			$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
			$( '.cntctfrm_language_tab_block_mini' ).removeClass( 'cntctfrm_language_tab_block_mini_open' );
		}
	});
	$(document).on( 'click', '.cntctfrm_department_delete label', function() {
		$( this ).parents( '.cntctfrm_department_block' ).children().children().val( '' );
		$( this ).parents( '.cntctfrm_department_block' ).css( 'display', 'none' );
		var id = $( this ).parents( '.cntctfrm_department_block' ).children( '.cntctfrm_department_name' ).children().attr( 'id' );
		$.ajax({
			url: '../wp-admin/admin-ajax.php',
			type: "POST",
			data: 'action=cntctfrm_delete_departament&id=' + id + '&cntctfrm_ajax_nonce_field=' + cntctfrm_ajax.cntctfrm_nonce,
			success: function( result ) {
				//
			},
			error: function( request, status, error ) {
				alert( error + request.status );
			}
		});
	});
})(jQuery);