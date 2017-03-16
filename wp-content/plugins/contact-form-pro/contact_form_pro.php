<?php
/*
Plugin Name: Contact Form Pro by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/contact-form/
Description: Simple contact form plugin any WordPress website must have.
Author: BestWebSoft
Text Domain: contact-form-pro
Domain Path: /languages
Version: 4.0.5
Author URI: https://bestwebsoft.com/
License: Proprietary
*/

/**
 * @todo remove file after 01.03.2017
 */
require_once( dirname( __FILE__ ) . '/includes/deprecated.php' );

/* Add option page in admin menu */
if ( ! function_exists( 'cntctfrm_add_admin_menu' ) ) {
	function cntctfrm_add_admin_menu() {
		bws_general_menu();
		$cntctfrm_settings =  add_submenu_page( 'bws_panel', __( 'Contact Form Pro Settings', 'contact-form-pro' ), 'Contact Form Pro', 'manage_options', "contact_form_pro.php", 'cntctfrm_pro_settings_page' );
		add_action( 'load-' . $cntctfrm_settings, 'cntctfrm_add_tabs' );
	}
}

if ( ! function_exists ( 'cntctfrm_pro_init' ) ) {
	function cntctfrm_pro_init() {
		global $cntctfrm_plugin_info;

		if ( ! session_id() )
			@session_start();

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( empty( $cntctfrm_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cntctfrm_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version  */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $cntctfrm_plugin_info, '3.8' );

		cntctfrm_update_activate();

		if ( ! is_admin() )
			cntctfrm_check_and_send();
	}
}

if ( ! function_exists ( 'cntctfrm_admin_init' ) ) {
	function cntctfrm_admin_init() {
		global $bws_plugin_info, $cntctfrm_plugin_info, $bws_shortcode_list, $cntctfrm_countries, $cntctfrm_lang_codes;
		/* Add variable for bws_menu */
		if ( empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '3', 'version' => $cntctfrm_plugin_info["Version"] );

		/* Display form on the setting page */
		$cntctfrm_lang_codes = array(
			'ab' => 'Abkhazian', 'aa' => 'Afar', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani',
			'bm' => 'Bambara', 'ba' => 'Bashkir', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali', 'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese',
			'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'ny' => 'Chichewa; Chewa; Nyanja', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'km' => 'Central Khmer', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree', 'hr' => 'Croatian', 'cs' => 'Czech',
			'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha',
			'en' => 'English','eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe',
			'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'ff' => 'Fulah',
			'gd' => 'Gaelic; Scottish Gaelic', 'gl' => 'Galician', 'lg' => 'Ganda', 'ka' => 'Georgian', 'de' => 'German', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati',
			'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian',
			'is' => 'Icelandic', 'io' => 'Ido', 'ig' => 'Igbo', 'id' => 'Indonesian', 'ie' => 'Interlingue', 'ia' => 'Interlingua (International Auxiliary Language Association)', 'iu' => 'Inuktitut', 'ik' => 'Inupiaq', 'ga' => 'Irish', 'it' => 'Italian',
			'ja' => 'Japanese', 'jv' => 'Javanese',
			'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'kr' => 'Kanuri', 'ks' => 'Kashmiri', 'kk' => 'Kazakh', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz', 'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish',
			'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lu' => 'Luba-Katanga', 'lb' => 'Luxembourgish; Letzeburgesch',
			'mk' => 'Macedonian', 'mg' => 'Malagasy', 'ms' => 'Malay', 'ml' => 'Malayalam', 'mt' => 'Maltese', 'gv' => 'Manx', 'mi' => 'Maori', 'mr' => 'Marathi', 'mh' => 'Marshallese', 'mo' => 'Moldavian', 'mn' => 'Mongolian',
			'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'se' => 'Northern Sami', 'no' => 'Norwegian', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Norwegian Bokmål; Bokmål, Norwegian',
			'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic',
			'pi' => 'Pali', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto',
			'qu' => 'Quechua',
			'ro' => 'Romanian', 'rm' => 'Romansh', 'rn' => 'Rundi', 'ru' => 'Russian',
			'sm' => 'Samoan', 'sg' => 'Sango', 'sa' => 'Sanskrit', 'sc' => 'Sardinian', 'sr' => 'Serbian', 'sn' => 'Shona', 'ii' => 'Sichuan Yi', 'sd' => 'Sindhi', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'su' => 'Sundanese', 'sw' => 'Swahili', 'ss' => 'Swati', 'sv' => 'Swedish',
			'tl' => 'Tagalog', 'ty' => 'Tahitian', 'tg' => 'Tajik', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'ts' => 'Tsonga', 'tn' => 'Tswana', 'tr' => 'Turkish', 'tk' => 'Turkmen', 'tw' => 'Twi',
			'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
			've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük',
			'wa' => 'Walloon', 'cy' => 'Welsh', 'fy' => 'Western Frisian', 'wo' => 'Wolof',
			'xh' => 'Xhosa',
			'yi' => 'Yiddish', 'yo' => 'Yoruba',
			'za' => 'Zhuang; Chuang', 'zu' => 'Zulu'
		);

		$cntctfrm_countries = array( "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

		/* Call register settings function */
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'contact_form_pro.php' )
			cntctfrm_settings();

		/* add contact form to global $bws_shortcode_list  */
		$bws_shortcode_list['cntctfrm'] = array( 'name' => 'Contact Form Pro', 'js_function' => 'cntctfrm_shortcode_init' );
	}
}

if ( ! function_exists ( 'cntctfrm_plugins_loaded' ) ) {
	function cntctfrm_plugins_loaded() {
		/* Internationalization */
		load_plugin_textdomain( 'contact-form-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/* Register settings for plugin */
if ( ! function_exists( 'cntctfrm_settings' ) ) {
	function cntctfrm_settings( $form_id = false ) {
		global $cntctfrm_options, $cntctfrm_plugin_info, $cntctfrm_related_plugins;

		$db_version = 'pro-1.0.2';

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( ! $cntctfrm_plugin_info )
			$cntctfrm_plugin_info = get_plugin_data( __FILE__ );

		/**
		 * @since 4.0.2
		 * @todo delete after 01.03.2017
		 */
		cntctfrm_check_old_options();

		if ( ! get_option( 'cntctfrm_options' ) ) {
			$option_defaults = cntctfrm_get_option_defaults();
			add_option( 'cntctfrm_options', $option_defaults );
		}

		/* Check contact-form-multi plugin */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		/* Get options from the database for default options */
		if ( $contact_form_multi_active ) {
			if ( ! get_option( 'cntctfrmmlt_options' ) ) {
				if ( ! isset( $option_defaults ) )
					$option_defaults = cntctfrm_get_option_defaults();
				add_option( 'cntctfrmmlt_options', $option_defaults );
			}

			$cntctfrmmlt_options = get_option( 'cntctfrmmlt_options' );

			if ( ! isset( $cntctfrmmlt_options['plugin_option_version'] ) || $cntctfrmmlt_options['plugin_option_version'] != 'pro-' . $cntctfrm_plugin_info["Version"] ) {

				if ( ! isset( $option_defaults ) )
					$option_defaults = cntctfrm_get_option_defaults();
				/**
				* @since 2.1.1
				* @todo delete after 25.04.2017
				*/
				$cntctfrmmlt_options = cntctfrm_options_update( $cntctfrmmlt_options, $option_defaults, $contact_form_multi_active );

				$cntctfrmmlt_options = array_merge( $option_defaults, $cntctfrmmlt_options );
				$cntctfrmmlt_options['plugin_option_version'] = 'pro-' . $cntctfrm_plugin_info["Version"];
				update_option( 'cntctfrmmlt_options', $cntctfrmmlt_options );
			}

			if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) || $form_id ) {
				$id = ( $form_id ) ? $form_id : $_SESSION['cntctfrmmlt_id_form'];
				if ( $cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $id ) ) {
					/* */
				} else {
					/**
					* @since 2.1.4
					* @todo delete after 1.08.2017. Remove all "get_option( 'cntctfrmmltpr_options_main' )" from all "if". It was added for compatibility with Contact Form Multi Pro with old prefixes. They have been changed approximately in v1.1.9.
					*/
					if ( 'pro' == $contact_form_multi_active && $cntctfrmmlt_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
						/**/
					} elseif ( $contact_form_multi_active ) {
						$cntctfrmmlt_options_main = get_option( 'cntctfrmmlt_options_main' );
					}

					if ( 1 == $id && 1 == count( $cntctfrmmlt_options_main['name_id_form'] ) ) {
						add_option( 'cntctfrmmlt_options_1' , get_option( 'cntctfrm_options' ) );
						$cntctfrm_options = get_option( 'cntctfrmmlt_options_1' );
					} else {
						$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
					}
				}
			} else {
				$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
			}
		} else {
			$cntctfrm_options = get_option( 'cntctfrm_options' );
		}

		if ( ! isset( $cntctfrm_options['plugin_option_version'] ) || $cntctfrm_options['plugin_option_version'] != 'pro-' . $cntctfrm_plugin_info["Version"] ) {

			if ( ! isset( $option_defaults ) )
				$option_defaults = cntctfrm_get_option_defaults();
			/**
			 * @since 2.1.1
			 * @todo delete after 25.04.2017
			 */
			$cntctfrm_options = cntctfrm_options_update( $cntctfrm_options, $option_defaults, $contact_form_multi_active );

			$cntctfrm_options = array_merge( $option_defaults, $cntctfrm_options );
			$cntctfrm_options['plugin_option_version'] = 'pro-' . $cntctfrm_plugin_info["Version"];

			if ( $contact_form_multi_active ) {
				if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) || $form_id ) {
					$id = ( $form_id ) ? $form_id : $_SESSION['cntctfrmmlt_id_form'];
					if ( get_option( 'cntctfrmmlt_options_' . $id ) ) {
						update_option( 'cntctfrmmlt_options_' . $id, $cntctfrm_options );
					} else {
						update_option( 'cntctfrmmlt_options', $cntctfrm_options );
					}
				} else {
					update_option( 'cntctfrmmlt_options', $cntctfrm_options );
				}
			} else {
				update_option( 'cntctfrm_options', $cntctfrm_options );
			}
		}

		/* create db table of fields list for Contact Form to DB */
		if ( ! isset( $cntctfrm_options['plugin_db_version'] ) || $cntctfrm_options['plugin_db_version'] != $db_version ) {
			cntctfrm_db_create();
			$cntctfrm_options['plugin_db_version'] = $db_version;
			if ( $contact_form_multi_active ) {
				if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) || $form_id ) {
					$id = $form_id ? $form_id : $_SESSION['cntctfrmmlt_id_form'];
					if ( get_option( 'cntctfrmmlt_options_' . $id ) ) {
						update_option( 'cntctfrmmlt_options_' . $id, $cntctfrm_options );
					} else {
						update_option( 'cntctfrmmlt_options', $cntctfrm_options );
					}
				} else {
					update_option( 'cntctfrmmlt_options', $cntctfrm_options );
				}
			} else {
				update_option( 'cntctfrm_options', $cntctfrm_options );
			}
		}
	}
}

/**
* @return array Default plugin options
* @since 4.0.2
*/
if ( ! function_exists( 'cntctfrm_get_option_defaults' ) ) {
	function cntctfrm_get_option_defaults() {
		global $cntctfrm_plugin_info;

		if ( ! $cntctfrm_plugin_info )
			$cntctfrm_plugin_info = get_plugin_data( __FILE__ );

		$sitename = strtolower( filter_var( $_SERVER['SERVER_NAME'], FILTER_SANITIZE_STRING ) );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;

		$option_defaults = array(
			'plugin_option_version' 		=> 'pro-' . $cntctfrm_plugin_info["Version"],
			'display_settings_notice'		=> 1,
			'suggest_feature_banner'		=> 1,
			'user_email' 					=> 'admin',
			'custom_email' 					=> get_option("admin_email"),
			'select_email'					=> 'custom',
			'departments' 					=> array( 'name' => array(), 'email' => array() ),
			'from_email' 					=> 'custom',
			'custom_from_email' 			=> $from_email,
			'attachment' 					=> 0,
			'attachment_explanations' 		=> 1,
			'send_copy' 					=> 0,
			'from_field' 					=> get_bloginfo( 'name' ),
			'select_from_field' 			=> 'custom',
			'required_department_field'		=> 0,
			'display_name_field'			=> 1,
			'display_location_field'		=> 0,
			'display_phone_field' 			=> 0,
			'display_address_field' 		=> 0,
			'display_privacy_check' 		=> 0,
			'display_optional_check' 		=> 0,
			'required_name_field'			=> 1,
			'required_location_field'		=> 0,
			'required_address_field'		=> 0,
			'required_email_field'			=> 1,
			'required_phone_field'			=> 0,
			'required_subject_field'		=> 1,
			'required_message_field'		=> 1,
			'required_symbol'				=> '*',
			'display_add_info' 				=> 1,
			'display_sent_from'				=> 1,
			'display_date_time'				=> 1,
			'mail_method' 					=> 'wp-mail',
			'display_coming_from' 			=> 1,
			'display_user_agent'			=> 1,
			'language'						=> array(),
			'change_label'					=> 0,
			'department_label'				=> array( 'default' => __( "Department", 'contact-form-pro' ) . ':' ),
			'name_label'					=> array( 'default' => __( "Name", 'contact-form-pro' ) . ':' ),
			'location_label'				=> array( 'default' => __( "Location", 'contact-form-pro' ) . ':' ),
			'address_label'					=> array( 'default' => __( "Address", 'contact-form-pro' ) . ':' ),
			'email_label'					=> array( 'default' => __( "Email Address", 'contact-form-pro' ) . ':' ),
			'phone_label'					=> array( 'default' => __( "Phone number", 'contact-form-pro' ) . ':' ),
			'subject_label'					=> array( 'default' => __( "Subject", 'contact-form-pro' ) . ':' ),
			'message_label'					=> array( 'default' => __( "Message", 'contact-form-pro' ) . ':' ),
			'attachment_label'				=> array( 'default' => __( "Attachment", 'contact-form-pro' ) . ':' ),
			'send_copy_label' 				=> array( 'default' => __( "Send me a copy", 'contact-form-pro' ) ),
			'privacy_check_label' 			=> array( 'default' => __( "I agree to the terms and conditions", 'contact-form-pro' ) ),
			'optional_check_label' 			=> array( 'default' => __( "I want to receive marketing newsletter", 'contact-form-pro' ) ),
			'submit_label' 					=> array( 'default' => __( "Submit", 'contact-form-pro' ) ),
			'department_error' 				=> array( 'default' => __( "Department is required.", 'contact-form-pro' ) ),
			'name_error' 					=> array( 'default' => __( "Your name is required.", 'contact-form-pro' ) ),
			'location_error' 				=> array( 'default' => __( "Your location is required.", 'contact-form-pro' ) ),
			'address_error' 				=> array( 'default' => __( "Address is required.", 'contact-form-pro' ) ),
			'email_error' 					=> array( 'default' => __( "A valid email address is required.", 'contact-form-pro' ) ),
			'phone_error' 					=> array( 'default' => __( "Phone number is required.", 'contact-form-pro' ) ),
			'subject_error' 				=> array( 'default' => __( "Subject is required.", 'contact-form-pro' ) ),
			'message_error'					=> array( 'default' => __( "Message text is required.", 'contact-form-pro' ) ),
			'attachment_error' 				=> array( 'default' => __( "File format is not valid.", 'contact-form-pro' ) ),
			'attachment_upload_error'		=> array( 'default' => __( "File upload error.", 'contact-form-pro' ) ),
			'attachment_move_error' 		=> array( 'default' => __( "The file could not be uploaded.", 'contact-form-pro' ) ),
			'attachment_size_error' 		=> array( 'default' => __( "This file is too large.", 'contact-form-pro' ) ),
			'privacy_check_error' 			=> array( 'default' => __( "Please confirm your agreement with the terms and conditions to send the form.", 'contact-form-pro' ) ),
			'captcha_error' 				=> array( 'default' => __( "Please fill out the CAPTCHA.", 'contact-form-pro' ) ),
			'form_error' 					=> array( 'default' => __( "Please make corrections below and try again.", 'contact-form-pro' ) ),
			'name_help' 					=> array( 'default' => __( "Please enter your full name...", 'contact-form-pro' ) ),
			'address_help' 					=> array( 'default' => __( "Please enter your address...", 'contact-form-pro' ) ),
			'email_help' 					=> array( 'default' => __( "Please enter your email address...", 'contact-form-pro' ) ),
			'phone_help' 					=> array( 'default' => __( "Please enter your phone number...", 'contact-form-pro' ) ),
			'subject_help' 					=> array( 'default' => __( "Please enter subject...", 'contact-form-pro' ) ),
			'message_help' 					=> array( 'default' => __( "Please enter your message...", 'contact-form-pro' ) ),
			'department_tooltip' 			=> array( 'default' => __( "Please select the department that receives the email", 'contact-form-pro' ) ),
			'name_tooltip' 					=> array( 'default' => __( "Please enter your full name...", 'contact-form-pro' ) ),
			'location_tooltip' 				=> array( 'default' => __( "Please select your location", 'contact-form-pro' ) ),
			'address_tooltip' 				=> array( 'default' => __( "Please enter your address...", 'contact-form-pro' ) ),
			'email_tooltip' 				=> array( 'default' => __( "Please enter your email address...", 'contact-form-pro' ) ),
			'phone_tooltip' 				=> array( 'default' => __( "Please enter your phone number...", 'contact-form-pro' ) ),
			'subject_tooltip' 				=> array( 'default' => __( "Please enter subject...", 'contact-form-pro' ) ),
			'message_tooltip' 				=> array( 'default' => __( "Please enter your message...", 'contact-form-pro' ) ),
			'attachment_tooltip'			=> array( 'default' => __( "Supported file types: HTML, TXT, CSS, GIF, PNG, JPEG, JPG, TIFF, BMP, AI, EPS, PS, CSV, RTF, PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR, WAV, MP3, PPT.", 'contact-form-pro' ) ),
			'captcha_tooltip' 				=> array( 'default' => __( "Please enter a number in the empty field.", 'contact-form-pro' ) ),
			'action_after_send'				=> 1,
			'thank_text'					=> array( 'default' => __( "Thank you for contacting us.", 'contact-form-pro' ) ),
			'redirect_url'					=> '',
			'error_displaying' 				=> 'both',
			'placeholder' 					=> 0,
			'tooltip_display_department' 	=> 0,
			'tooltip_display_name' 			=> 0,
			'tooltip_display_location' 		=> 0,
			'tooltip_display_address' 		=> 0,
			'tooltip_display_email' 		=> 0,
			'tooltip_display_phone' 		=> 0,
			'tooltip_display_subject' 		=> 0,
			'tooltip_display_message' 		=> 0,
			'tooltip_display_attachment'	=> 0,
			'tooltip_display_captcha' 		=> 0,
			'style_options' 				=> 0,
			'label_color' 					=> '',
			'error_color' 					=> '#ff0000',
			'input_background' 				=> '',
			'input_color' 					=> '',
			'input_placeholder_color' 		=> '',
			'input_placeholder_error_color' => '',
			'border_input_width' 			=> '',
			'border_input_color' 			=> '',
			'error_input_color' 			=> '#FFE7E4',
			'error_input_border_color'		=> '#F9BFB8',
			'border_input_color' 			=> '',
			'button_width' 					=> '',
			'button_backgroud' 				=> '',
			'button_color'					=> '',
			'border_button_color'			=> '',
			'delete_attached_file' 			=> 0,
			'header_reply_to' 				=> 0,
			'visible_subject'				=> 1,
			'visible_message'				=> 1,
			'disabled_subject'				=> 0,
			'disabled_message'				=> 0,
			'default_subject'				=> '',
			'default_message'				=> '',
			'html_email'					=> 1,
			'change_label_in_email'			=> 0,
			'default_name'					=> 0,
			'default_email'					=> 0,
			'disabled_name'					=> 0,
			'disabled_email'				=> 0,
			'visible_name'					=> 1,
			'visible_email'					=> 1,
			'auto_response'					=> 0,
			'auto_response_message'			=> "Dear %%NAME%%, " . "\n" . "Thank you for contacting us. We have received your message and will reply to it shortly. " . "\n" . "Regards, " . "\n" . "%%SITENAME%% Team.",
			'layout'						=> 1,
			'form_align'					=> 'left',
			'labels_settings' 				=> array(
												'position' => 'top',
												'align'    => 'left'
											),
			'submit_position'				=> 'left',
			'order_fields'					=> array(
													'first_column'  => array(
														'cntctfrm_contact_department',
														'cntctfrm_contact_name',
														'cntctfrm_contact_location',
														'cntctfrm_contact_address',
														'cntctfrm_contact_email',
														'cntctfrm_contact_phone',
														'cntctfrm_contact_subject',
														'cntctfrm_contact_message',
														'cntctfrm_contact_attachment',
														'cntctfrm_contact_send_copy',
														'cntctfrm_contact_privacy',
														'cntctfrm_contact_optional',
														'cntctfrm_subscribe',
														'cntctfrm_captcha',
													),
													'second_column' => array()
												)
		);

		return $option_defaults;
	}
}

/**
* @since 2.1.4
* @todo after 1.08.2017. Delete keys marked with # from $cntctfrm_related_plugins global massive. They are created for compatibility with versions of related plugins without modified prefixes or containing prefixes in options' names.
*/
if ( ! function_exists( 'cntctfrm_related_plugins' ) ) {
	function cntctfrm_related_plugins() {
		global $cntctfrm_related_plugins;

		$cntctfrm_related_plugins = array();

		if ( ! function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		/* Get Captcha options */
		if ( is_plugin_active( 'captcha/captcha.php' ) ) {
			$cptch_options = get_option( 'cptch_options' );

			$captcha_enabled = 0;

			if ( isset( $cptch_options['forms']['bws_contact']['enable'] ) ) {
				$captcha_enabled = $cptch_options['forms']['bws_contact']['enable'];
			} else {
				if ( isset( $cptch_options['cptch_contact_form'] ) ) {
					$captcha_enabled = $cptch_options['cptch_contact_form'];
				}

				if ( isset( $cptch_options['contact_form'] ) ) {
					$captcha_enabled = $cptch_options['contact_form'];
				}
			}

			$label = isset( $cptch_options['label_form'] ) ? 'label_form' : 'cptch_label_form';
			$required_symbol = isset( $cptch_options['required_symbol'] ) ? 'required_symbol' : 'cptch_required_symbol';
			$cntctfrm_related_plugins['captcha'] = array(
				'enabled'			=> $captcha_enabled,
				'options'			=> $cptch_options,
				'options_name'		=> 'cptch_options',
				'label'				=> $label,
				'required_symbol'	=> $required_symbol,
				'settings_page'		=> 'captcha.php'
			);
		} elseif ( is_plugin_active( 'captcha-plus/captcha-plus.php' ) ) {
			if ( $cptchpls_options = get_option( 'cptchpls_options' ) ) {
				$options_name = 'cptchpls_options';
			} elseif ( $cptchpls_options = get_option( 'cptch_options' ) ) {
				$options_name = 'cptch_options';
			}

			$captcha_enabled = 0;

			if ( isset( $cptchpls_options['forms']['bws_contact']['enable'] ) ) {
				$captcha_enabled = $cptchpls_options['forms']['bws_contact']['enable'];
			} else {
				if ( isset( $cptchpls_options['cptch_contact_form'] ) ) {
					$captcha_enabled = $cptchpls_options['cptch_contact_form'];
				}

				if ( isset( $cptchpls_options['contact_form'] ) ) {
					$captcha_enabled = $cptchpls_options['contact_form'];
				}
			}

			$label = isset( $cptchpls_options['label_form'] ) ? 'label_form' : 'cptchpls_label_form';
			$required_symbol = isset( $cptchpls_options['required_symbol'] ) ? 'required_symbol' : 'cptchpls_required_symbol';
			$cntctfrm_related_plugins['captcha'] = array(
				'enabled'			=> $captcha_enabled,
				'options'			=> $cptchpls_options,
				'options_name'		=> $options_name,
				'label'			=> $label,
				'required_symbol'	=> $required_symbol,
				'settings_page'		=> 'captcha-plus.php'
			);
		} elseif ( is_plugin_active( 'captcha-pro/captcha_pro.php' ) ) {
			if ( $cptchpr_options = get_option( 'cptchpr_options' ) ) {
				$options_name = 'cptchpr_options';
			} elseif ( $cptchpr_options = get_option( 'cptch_options' ) ) {
				$options_name = 'cptch_options';
			}

			$captcha_enabled = 0;

			if ( isset( $cptchpr_options['forms']['bws_contact']['enable'] ) ) {
				$captcha_enabled = $cptchpr_options['forms']['bws_contact']['enable'];
			} else {
				if ( isset( $cptchpr_options['cptch_contact_form'] ) ) {
					$captcha_enabled = $cptchpr_options['cptch_contact_form'];
				}

				if ( isset( $cptchpr_options['contact_form'] ) ) {
					$captcha_enabled = $cptchpr_options['contact_form'];
				}
			}

			$label = isset( $cptchpr_options['label_form'] ) ? 'label_form' : 'cptchpr_label_form';
			$required_symbol = isset( $cptchpr_options['required_symbol'] ) ? 'required_symbol' : 'cptchpr_required_symbol';

			$cntctfrm_related_plugins['captcha'] = array(
				'enabled'			=> $captcha_enabled,
				'options'			=> $cptchpr_options,
				'options_name'		=> $options_name,
				'label'				=> $label,
				'required_symbol'	=> $required_symbol,
				'settings_page'		=> 'captcha_pro.php'
			);
		}

		/* Get Google Captcha options */
		if ( is_plugin_active( 'google-captcha/google-captcha.php' ) ) {
			$gglcptch_options = get_option( 'gglcptch_options' );

			$cntctfrm_related_plugins['google-captcha'] = array(
				'options'			=> $gglcptch_options,
				'options_name'		=> 'gglcptch_options',
				'settings_page'		=> 'google-captcha.php'
			);
		} elseif ( is_plugin_active( 'google-captcha-pro/google-captcha-pro.php' ) ) {
			if ( $gglcptchpr_options = get_option( 'gglcptchpr_options' ) ) {
				$options_name = 'gglcptchpr_options';
			} elseif ( $gglcptchpr_options =  get_option( 'gglcptch_options' ) ) {
				$options_name = 'gglcptch_options';
			}
			$cntctfrm_related_plugins['google-captcha'] = array(
				'options'			=> $gglcptchpr_options,
				'options_name'		=> $options_name,
				'settings_page'		=> 'google-captcha-pro.php'
			);
		}

		/* Get Subscriber options */
		if ( is_multisite() ) {
			if ( is_plugin_active_for_network( 'subscriber/subscriber.php' ) ) {
				$sbscrbr_options = get_site_option( 'sbscrbr_options' );

				$cntctfrm_related_plugins['subscriber'] = array(
					'options'			=> $sbscrbr_options,
					'options_name'		=> 'sbscrbr_options',
					'settings_page'		=> 'sbscrbr_settings_page'
				);
			} elseif ( is_plugin_active_for_network( 'subscriber-pro/subscriber-pro.php' ) ) {
				if ( $sbscrbrpr_options = get_site_option( 'sbscrbrpr_options' ) ) {
					$options_name = 'sbscrbrpr_options';
				} elseif ( $sbscrbrpr_options = get_site_option( 'sbscrbr_options' ) ) {
					$options_name = 'sbscrbr_options';
				}

				$cntctfrm_related_plugins['subscriber'] = array(
					'options'			=> $sbscrbrpr_options,
					'options_name'		=> $options_name,
					'settings_page'		=> 'sbscrbrpr_settings_page'
				);
			}
		} else {
			if ( is_plugin_active( 'subscriber/subscriber.php' ) ) {
				$sbscrbr_options = get_option( 'sbscrbr_options' );

				$cntctfrm_related_plugins['subscriber'] = array(
					'options'			=> $sbscrbr_options,
					'options_name'		=> 'sbscrbr_options',
					'settings_page'		=> 'sbscrbr_settings_page'
				);
			} elseif ( is_plugin_active( 'subscriber-pro/subscriber-pro.php' ) ) {
				if ( $sbscrbrpr_options = get_option( 'sbscrbrpr_options' ) ) {
					$options_name = 'sbscrbrpr_options';
				} elseif ( $sbscrbrpr_options = get_option( 'sbscrbr_options' ) ) {
					$options_name = 'sbscrbr_options';
				}

				$cntctfrm_related_plugins['subscriber'] = array(
					'options'			=> $sbscrbrpr_options,
					'options_name'		=> $options_name,
					'settings_page'		=> 'sbscrbrpr_settings_page'
				);
			}
		}

		/* Get Contact Form to DB options */
		if ( is_plugin_active( 'contact-form-to-db/contact_form_to_db.php' ) ) {
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );

		$save_option = isset( $cntctfrmtdb_options['save_messages_to_db'] ) ? 'save_messages_to_db' : 'cntctfrmtdb_save_messages_to_db';
			$cntctfrm_related_plugins['contact-form-to-db'] = array(
				'options'			=> $cntctfrmtdb_options,
				'options_name'		=> 'cntctfrmtdb_options',
				'save_option'		=> $save_option,
				'settings_page'		=> 'cntctfrmtdb_settings'
			);
		} elseif ( is_plugin_active( 'contact-form-to-db-pro/contact_form_to_db_pro.php' ) ) {
				if ( $cntctfrmtdbpr_options = get_option( 'cntctfrmtdbpr_options' ) ) {
			$options_name = 'cntctfrmtdbpr_options';
			} elseif ( $cntctfrmtdbpr_options = get_option( 'cntctfrmtdb_options' ) ) {
				$options_name = 'cntctfrmtdb_options';
			}
		$save_option = isset( $cntctfrmtdbpr_options['save_messages_to_db'] ) ? 'save_messages_to_db' : 'cntctfrmtdbpr_save_messages_to_db';
			$cntctfrm_related_plugins['contact-form-to-db'] = array(
				'options'			=> $cntctfrmtdbpr_options,
				'options_name'		=> $options_name,
				'save_option'		=> $save_option,
				'settings_page'		=> 'cntctfrmtdbpr_settings'
			);
		}
	}
}

if ( ! function_exists( 'cntctfrm_check_cf_multi_active' ) ) {
	function cntctfrm_check_cf_multi_active() {
		/* Check contact-form-multi (free) plugin */
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) )
			$contact_form_multi_active = true;

		/* Check contact-form-multi-pro plugin */
		if ( is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) )
			$contact_form_multi_pro_active = true;

		if ( isset( $contact_form_multi_active ) )
			return "free";

		if ( isset( $contact_form_multi_pro_active ) )
			return "pro";

		return false;
	}
}

if ( ! function_exists( 'cntctfrm_db_create' ) ) {
	function cntctfrm_db_create() {
		global $wpdb, $cntctfrm_countries;

		if ( empty( $cntctfrm_countries ) )
			cntctfrm_admin_init();

		$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cntctfrm_field` (
			id int NOT NULL AUTO_INCREMENT,
			name CHAR(100) NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$fields = array(
			'department_selectbox',
			'name',
			'location',
			'email',
			'subject',
			'message',
			'address',
			'phone',
			'attachment',
			'attachment_explanations',
			'send_copy',
			'privacy_check',
			'optional_check',
			'sent_from',
			'date_time',
			'coming_from',
			'user_agent'
		);
		foreach ( $fields as $key => $value ) {
			$db_row = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_field WHERE `name` = '" . $value . "'", ARRAY_A );
			if ( ! isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert( $wpdb->prefix . "cntctfrm_field", array( 'name' => $value ), array( '%s' ) );
			}
		}
		/* table with locations */
		$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cntctfrm_location` (
			id int NOT NULL AUTO_INCREMENT,
			name CHAR(100) NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$locations = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_location" );
		if ( empty( $locations ) ) {
			foreach ( $cntctfrm_countries as $key => $value ) {
				$db_row = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_location WHERE `name` = '" . addcslashes( trim( $value ), "'" ) . "'", ARRAY_A );
				if ( ! isset( $db_row ) || empty( $db_row ) ) {
					$wpdb->insert(  $wpdb->prefix . "cntctfrm_location", array( 'name' => addcslashes( trim( $value ), "'" ) ), array( '%s' ) );
				}
			}
		}
	}
}

if ( ! function_exists ( 'cntctfrm_activation' ) ) {
	function cntctfrm_activation( $networkwide ) {
		global $wpdb;
		if ( function_exists( 'is_multisite' ) && is_multisite() && $networkwide ) {
			$cntctfrm_blog_id = $wpdb->blogid;
			$cntctfrm_get_blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $cntctfrm_get_blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				cntctfrm_settings();
				cntctfrm_db_create();
			}
			switch_to_blog( $cntctfrm_blog_id );
			return;
		} else {
			cntctfrm_settings();
			cntctfrm_db_create();
		}
	}
}

if ( ! function_exists( 'cntctfrm_get_ordered_fields' ) ) {
	function cntctfrm_get_ordered_fields() {
		global $cntctfrm_options, $cntctfrm_related_plugins;

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( ! $contact_form_multi_active ) {
			$display_captcha = $display_google_captcha = $display_subscriber = false;

			if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) )
				$display_captcha = ! empty( $cntctfrm_related_plugins['captcha']['enabled'] );

			if ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) )
				$display_google_captcha = ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] );

			if ( array_key_exists( 'subscriber', $cntctfrm_related_plugins ) )
				$display_subscriber = ! empty( $cntctfrm_related_plugins['subscriber']['options']['contact_form'] );
		} else {
			$display_captcha		= ( isset( $cntctfrm_options['display_captcha'] ) && 1 == $cntctfrm_options['display_captcha'] ) ? true : false;
			$display_google_captcha	= ( isset( $cntctfrm_options['display_google_captcha'] ) && 1 == $cntctfrm_options['display_google_captcha'] ) ? true : false;
			$display_subscriber		= ( isset( $cntctfrm_options['display_subscribe'] ) && 1 == $cntctfrm_options['display_subscribe'] ) ? true : false;
		}

		$default_order_fields = array(
			'cntctfrm_contact_department'	=> ( 'departments' == $cntctfrm_options['select_email'] ) ? true : false,
			'cntctfrm_contact_name'			=> ( 1 == $cntctfrm_options['display_name_field'] ) ? true : false,
			'cntctfrm_contact_location'		=> ( 1 == $cntctfrm_options['display_location_field'] ) ? true : false,
			'cntctfrm_contact_address'		=> ( 1 == $cntctfrm_options['display_address_field'] ) ? true : false,
			'cntctfrm_contact_email'		=> true,
			'cntctfrm_contact_phone'		=> ( 1 == $cntctfrm_options['display_phone_field'] ) ? true : false,
			'cntctfrm_contact_subject'		=> true,
			'cntctfrm_contact_message'		=> true,
			'cntctfrm_contact_attachment'	=> ( 1 == $cntctfrm_options['attachment'] ) ? true : false,
			'cntctfrm_contact_send_copy'	=> ( 1 == $cntctfrm_options['send_copy'] ) ? true : false,
			'cntctfrm_contact_privacy'		=> ( 1 == $cntctfrm_options['display_privacy_check'] ) ? true : false,
			'cntctfrm_contact_optional'		=> ( 1 == $cntctfrm_options['display_optional_check'] ) ? true : false,
			'cntctfrm_subscribe'			=> $display_subscriber,
			'cntctfrm_captcha'				=> $display_captcha || $display_google_captcha ? true : false,
		);
		$display_fields = array();
		foreach ( $default_order_fields as $field => $value ) {
			if ( $value == true ) {
				array_push( $display_fields, $field );
			}
		}

		$ordered_fields = array_merge( $cntctfrm_options['order_fields']['first_column'], $cntctfrm_options['order_fields']['second_column'] );
		$cntctfrm_diff_fields = array_diff( $display_fields, $ordered_fields );

		foreach ( $cntctfrm_diff_fields as $field ) {
			array_push( $cntctfrm_options['order_fields']['first_column'], $field );
		}

		return $cntctfrm_options['order_fields'];
	}
}
/* Add settings page in admin area */
if ( ! function_exists( 'cntctfrm_pro_settings_page' ) ) {
	function cntctfrm_pro_settings_page() {
		global $cntctfrm_options, $wpdb, $wp_version, $cntctfrm_plugin_info, $bstwbsftwppdtplgns_options, $cntctfrm_countries, $cntctfrm_lang_codes, $cntctfrm_related_plugins;

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		$error = $message = $notice = '';
		$plugin_basename = plugin_basename( __FILE__ );

		if ( ! function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		/* Check contact-form-multi plugin */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( 1 == $cntctfrm_options['display_location_field'] ) {
			$locations = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_location" );
		}

		/* location & first form id*/
		if ( 'pro' == $contact_form_multi_active && $multi_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
			$location_table = $wpdb->prefix . "cntctfrm_location" . $multi_options_main['id_form'];
			$multi_options_main_id_form = $multi_options_main['id_form'];

			reset( $multi_options_main['name_id_form'] );
			$first_form_id = key( $multi_options_main['name_id_form'] );
		} elseif ( $contact_form_multi_active ) {
			$multi_options_main = get_option( 'cntctfrmmlt_options_main' );
			$multi_options_main_id_form = $multi_options_main['id_form'];
			$location_table = $wpdb->prefix . "cntctfrm_location" . $multi_options_main['id_form'];

			reset( $multi_options_main['name_id_form'] );
			$first_form_id = key( $multi_options_main['name_id_form'] );
		} else {
			$location_table = $wpdb->prefix . "cntctfrm_location";
		}

		$all_plugins = get_plugins();

		if ( ! $contact_form_multi_active || ( 'pro' == $contact_form_multi_active || ( 'free' == $contact_form_multi_active && isset( $_SESSION['cntctfrmmlt_id_form'] ) && ( $first_form_id == $_SESSION['cntctfrmmlt_id_form'] || empty( $first_form_id ) ) ) ) )
			$display_pro_options = true;

		$userslogin = get_users( 'blog_id=' . $GLOBALS['blog_id'] . '&role=administrator' );

		/* Save data for settings page */
		if ( isset( $_POST['cntctfrm_form_submit'] ) && check_admin_referer( $plugin_basename, 'cntctfrm_nonce_name' ) ) {
			if ( ! isset( $_GET['action'] ) || 'additional' == $_GET['action'] ) {
				/* Save data from "Settings" or "Additional settings" tab */
				$options_submit['user_email']	= trim( $_POST['cntctfrm_user_email'] );
				$options_submit['custom_email']	= trim( stripslashes( esc_html( $_POST['cntctfrm_custom_email'] ) ), " ," );
				$options_submit['select_email']	= trim( $_POST['cntctfrm_select_email'] );

				/* Get data from each block "Add selectbox to the contact form" */
				if ( isset( $display_pro_options ) ) {
					foreach ( $_POST["cntctfrm_department_name"] as $key => $value ) {
						$options_submit["departments"]["name"][ $key ] = trim( stripslashes( esc_html( $_POST["cntctfrm_department_name"][ $key ] ) ) );
						$options_submit["departments"]["email"][ $key ] = trim( stripslashes( esc_html( $_POST["cntctfrm_department_email"][ $key ] ) ), " ," );
					}
					if ( isset( $_REQUEST["cntctfrm_department_delete"] ) && is_array( $_REQUEST["cntctfrm_department_delete"] ) ) {
						foreach ( $_REQUEST["cntctfrm_department_delete"] as $key => $value ) {
							unset( $options_submit["departments"]["name"][ $value ], $options_submit["departments"]["email"][ $value ] );
						}
					}
				}

				$options_submit['from_email'] = $_POST['cntctfrm_from_email'];
				$options_submit['custom_from_email'] = trim( stripslashes( esc_html( $_POST['cntctfrm_custom_from_email'] ) ) );

				$options_submit['mail_method']					= $_POST['cntctfrm_mail_method'];
				$options_submit['from_field']					= trim( stripslashes( esc_html( $_POST['cntctfrm_from_field'] ) ) );
				$options_submit['select_from_field']			= $_POST['cntctfrm_select_from_field'];
				$options_submit['header_reply_to']				= isset( $_POST['cntctfrm_header_reply_to'] ) ? 1 : 0;
				$options_submit['required_symbol']				= trim( stripslashes( esc_html( $_POST['cntctfrm_required_symbol'] ) ) );

				$options_submit['required_department_field']	= isset( $_POST['cntctfrm_required_department_field'] ) ? 1 : 0;
				$options_submit['display_name_field']			= isset( $_POST['cntctfrm_display_name_field'] ) ? 1 : 0;
				$options_submit['display_location_field']		= isset( $_POST['cntctfrm_display_location_field'] ) ? 1 : 0;
				$options_submit['display_address_field']		= isset( $_POST['cntctfrm_display_address_field'] ) ? 1 : 0;
				$options_submit['display_phone_field']			= isset( $_POST['cntctfrm_display_phone_field'] ) ? 1 : 0;
				$options_submit['attachment']					= isset( $_POST['cntctfrm_attachment'] ) ? 1 : 0;

				if ( $options_submit['display_name_field'] == 0 ) {
					$options_submit['required_name_field'] = 0;
					if ( isset( $display_pro_options ) ) {
						$options_submit['visible_name'] = $options_submit['disabled_name'] = $options_submit['default_name'] = 0;
					}
				} else {
					$options_submit['required_name_field'] = isset( $_POST['cntctfrm_required_name_field'] ) ? 1 : 0;
					if ( isset( $display_pro_options ) ) {
						$options_submit['default_name'] 		= isset( $_POST['cntctfrm_default_name'] ) ? 1 : 0;
						if ( 0 == $options_submit['default_name'] ) {
							$options_submit['visible_name'] 	= 1;
							$options_submit['disabled_name']	= 0;
						} else {
							$options_submit['visible_name'] 		= isset( $_POST['cntctfrm_visible_name'] ) ? 1 : 0;
							$options_submit['disabled_name'] 	= isset( $_POST['cntctfrm_disabled_name'] ) ? 1 : 0;
						}
					}
				}
				if ( $options_submit['display_location_field'] == 0 ) {
					$options_submit['required_location_field'] = 0;
				} else {
					$options_submit['required_location_field'] = isset( $_POST['cntctfrm_required_location_field'] ) ? 1 : 0;
				}
				if ( $options_submit['display_address_field'] == 0 ) {
					$options_submit['required_address_field'] = 0;
				} else {
					$options_submit['required_address_field'] = isset( $_POST['cntctfrm_required_address_field'] ) ? 1 : 0;
				}
				$options_submit['required_email_field'] = isset( $_POST['cntctfrm_required_email_field'] ) ? 1 : 0;

				if ( $options_submit['display_phone_field'] == 0 ) {
					$options_submit['required_phone_field'] = 0;
				} else {
					$options_submit['required_phone_field']	= isset( $_POST['cntctfrm_required_phone_field'] ) ? 1 : 0;
				}
				$options_submit['required_subject_field']	= isset( $_POST['cntctfrm_required_subject_field'] ) ? 1 : 0;
				$options_submit['required_message_field']	= isset( $_POST['cntctfrm_required_message_field'] ) ? 1 : 0;

				if ( isset( $display_pro_options ) ) {
					$options_submit['default_email'] 	= isset( $_POST['cntctfrm_default_email'] ) ? 1 : 0;
					$options_submit['visible_email'] 	= isset( $_POST['cntctfrm_visible_email'] ) ? 1 : 0;
					$options_submit['disabled_email'] 	= isset( $_POST['cntctfrm_disabled_email'] ) ? 1 : 0;

					if ( 0 == $options_submit['default_email'] ) {
						$options_submit['visible_email'] = 1;
						$options_submit['disabled_email'] = 0;
					}

					if ( 0 == $options_submit['required_subject_field'] ) {
						$options_submit['visible_subject']		= isset( $_POST['cntctfrm_visible_subject'] ) ? 1 : 0;
						$options_submit['disabled_subject']		= isset( $_POST['cntctfrm_disabled_subject'] ) ? 1 : 0;
					} else {
						$options_submit['visible_subject']		= 1;
						$options_submit['disabled_subject']		= 0;
					}

					if ( 0 == $options_submit['required_message_field'] ) {
						$options_submit['visible_message']		= isset( $_POST['cntctfrm_visible_message'] ) ? 1 : 0;
						$options_submit['disabled_message']		= isset( $_POST['cntctfrm_disabled_message'] ) ? 1 : 0;
					} else {
						$options_submit['visible_message']		= 1;
						$options_submit['disabled_message']		= 0;
					}

					$options_submit['default_subject']		= stripslashes( esc_html( trim( $_POST['cntctfrm_default_subject'] ) ) );
					$options_submit['default_message']		= stripslashes( esc_html( trim( $_POST['cntctfrm_default_message'] ) ) );

					if ( '' == $options_submit['default_subject'] && ( 0 == $options_submit['visible_subject'] || 1 == $options_submit['disabled_subject'] ) ) {
						$error .= __( "If you want to hide or disable the subject field, please fill it out with a default value.", 'contact-form-pro' );
					}
				}

				if ( isset( $_POST['cntctfrm_add_language_button'] ) )
					cntctfrm_add_language();

				if ( isset( $_POST['cntctfrm_delete_button'] ) )
					cntctfrm_remove_language();

				$options_submit['attachment_explanations'] 	= isset( $_POST['cntctfrm_attachment_explanations'] ) ? 1 : 0;
				$options_submit['send_copy']				= isset( $_POST['cntctfrm_send_copy'] ) ? 1 : 0;

				if ( isset( $display_pro_options ) ) {
					$options_submit['display_privacy_check']	= isset( $_POST['cntctfrm_display_privacy_check'] ) ? 1 : 0;
					$options_submit['display_optional_check']	= isset( $_POST['cntctfrm_display_optional_check'] ) ? 1 : 0;
				}

				if ( $contact_form_multi_active ) {
					$options_submit['display_captcha']			= isset( $_POST['cntctfrm_display_captcha'] ) ? 1 : 0;
					$options_submit['display_google_captcha']	= isset( $_POST['cntctfrm_display_google_captcha'] ) ? 1 : 0;
					$options_submit['display_subscribe']		= isset( $_POST['cntctfrm_display_subscriber'] ) ? 1 : 0;
					$options_submit['save_email_to_db']			= isset( $_POST['cntctfrm_save_email_to_db'] ) ? 1 : 0;
				} else {
					/* Update related plugins options if Contact Form Multi is not active */
					if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) ) {
						$cptch_enable = isset( $_POST['cntctfrm_display_captcha'] ) ? 1 : 0;
						$cntctfrm_related_plugins['captcha']['enabled'] = $cptch_enable;
						$cntctfrm_related_plugins['captcha']['options']['forms']['bws_contact']['enable'] = $cptch_enable;
						update_option( $cntctfrm_related_plugins['captcha']['options_name'], $cntctfrm_related_plugins['captcha']['options'] );
					}

					if ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) ) {
						$cntctfrm_related_plugins['google-captcha']['options']['contact_form'] = isset( $_POST['cntctfrm_display_google_captcha'] ) ? 1 : 0;
						update_option( $cntctfrm_related_plugins['google-captcha']['options_name'], $cntctfrm_related_plugins['google-captcha']['options'] );
					}

					if ( is_multisite() ) {
						if ( array_key_exists( 'subscriber', $cntctfrm_related_plugins ) ) {
							$cntctfrm_related_plugins['subscriber']['options']['contact_form'] = isset( $_POST['cntctfrm_display_subscriber'] ) ? 1 : 0;
							update_site_option( $cntctfrm_related_plugins['subscriber']['options_name'], $cntctfrm_related_plugins['subscriber']['options'] );
						}
					} else {
						if ( array_key_exists( 'subscriber', $cntctfrm_related_plugins ) ) {
							$cntctfrm_related_plugins['subscriber']['options']['contact_form'] = isset( $_POST['cntctfrm_display_subscriber'] ) ? 1 : 0;
							update_option( $cntctfrm_related_plugins['subscriber']['options_name'], $cntctfrm_related_plugins['subscriber']['options'] );
						}
					}

					if ( array_key_exists( 'contact-form-to-db', $cntctfrm_related_plugins ) ) {
						$cntctfrm_related_plugins['contact-form-to-db']['options'][ $cntctfrm_related_plugins['contact-form-to-db']['save_option'] ] = isset( $_POST['cntctfrm_save_email_to_db'] ) ? 1 : 0;
						update_option( $cntctfrm_related_plugins['contact-form-to-db']['options_name'], $cntctfrm_related_plugins['contact-form-to-db']['options'] );
					}
				}

				$options_submit['delete_attached_file'] = isset( $_POST['cntctfrm_delete_attached_file'] ) ? 1 : 0;

				$options_submit['html_email'] = isset( $_POST['cntctfrm_html_email'] ) ? 1 : 0;

				$options_submit['display_add_info'] = isset( $_POST['cntctfrm_display_add_info'] ) ? 1 : 0;

				$options_submit['auto_response']			= isset( $_POST['cntctfrm_auto_response'] ) ? 1 : 0;
				$options_submit['auto_response_message']	= stripslashes( esc_html( $_POST['cntctfrm_auto_response_message'] ) );

				$options_submit['display_sent_from']	= isset( $_POST['cntctfrm_display_sent_from'] ) ? 1 : 0;
				$options_submit['display_date_time']	= isset( $_POST['cntctfrm_display_date_time'] ) ? 1 : 0;
				$options_submit['display_coming_from']	= isset( $_POST['cntctfrm_display_coming_from'] ) ? 1 : 0;
				$options_submit['display_user_agent']	= isset( $_POST['cntctfrm_display_user_agent'] ) ? 1 : 0;
				if ( 0 == $options_submit['display_sent_from'] && 0 == $options_submit['display_date_time'] && 0 == $options_submit['display_coming_from'] && 0 == $options_submit['display_user_agent'] )
					$options_submit['display_add_info'] = 0;

				if ( $options_submit['display_add_info'] == 0 ) {
					$options_submit['display_sent_from'] = $options_submit['display_date_time']	= $options_submit['display_coming_from'] = $options_submit['display_user_agent'] = 1;
				}

				$options_submit['change_label'] 		 = isset( $_POST['cntctfrm_change_label'] ) ? 1 : 0;
				$options_submit['change_label_in_email'] = isset( $_POST['cntctfrm_change_label_in_email'] ) ? 1 : 0;

				if ( $options_submit['change_label'] == 1 ) {
					foreach ( $_POST['cntctfrm_name_label'] as $key => $val ) {
						$options_submit['department_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_department_label'][ $key ] ) );
						$options_submit['name_label'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_name_label'][ $key ] ) );
						$options_submit['location_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_location_label'][ $key ] ) );
						$options_submit['address_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_address_label'][ $key ] ) );
						$options_submit['email_label'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_email_label'][ $key ] ) );
						$options_submit['phone_label'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_phone_label'][ $key ] ) );
						$options_submit['subject_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_subject_label'][ $key ] ) );
						$options_submit['message_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_message_label'][ $key ] ) );
						$options_submit['attachment_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_attachment_label'][ $key ] ) );
						$options_submit['send_copy_label'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_send_copy_label'][ $key ] ) );
						$options_submit['privacy_check_label'][ $key ]		= strip_tags( stripslashes( $_POST['cntctfrm_privacy_check_label'][ $key ] ),'<a>' );
						$options_submit['optional_check_label'][ $key ]		= stripslashes( esc_html( $_POST['cntctfrm_optional_check_label'][ $key ] ) );
						$options_submit['thank_text'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_thank_text'][ $key ] ) );
						$options_submit['submit_label'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_submit_label'][ $key ] ) );
						$options_submit['department_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_department_error'][ $key ] ) );
						$options_submit['name_error'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_name_error'][ $key ] ) );
						$options_submit['location_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_location_error'][ $key ] ) );
						$options_submit['address_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_address_error'][ $key ] ) );
						$options_submit['email_error'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_email_error'][ $key ] ) );
						$options_submit['phone_error'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_phone_error'][ $key ] ) );
						$options_submit['subject_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_subject_error'][ $key ] ) );
						$options_submit['message_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_message_error'][ $key ] ) );
						$options_submit['attachment_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_attachment_error'][ $key ] ) );
						$options_submit['attachment_upload_error'][ $key ]	= stripslashes( esc_html( $_POST['cntctfrm_attachment_upload_error'][ $key ] ) );
						$options_submit['attachment_move_error'][ $key ]	= stripslashes( esc_html( $_POST['cntctfrm_attachment_move_error'][ $key ] ) );
						$options_submit['attachment_size_error'][ $key ]	= stripslashes( esc_html( $_POST['cntctfrm_attachment_size_error'][ $key ] ) );
						$options_submit['privacy_check_error'][ $key ]		= stripslashes( esc_html( $_POST['cntctfrm_privacy_check_error'][ $key ] ) );
						$options_submit['captcha_error'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_captcha_error'][ $key ] ) );
						$options_submit['form_error'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_form_error'][ $key ] ) );
						$options_submit['name_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_name_help'][ $key ] ) );
						$options_submit['address_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_address_help'][ $key ] ) );
						$options_submit['email_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_email_help'][ $key ] ) );
						$options_submit['phone_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_phone_help'][ $key ] ) );
						$options_submit['subject_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_subject_help'][ $key ] ) );
						$options_submit['message_help'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_message_help'][ $key ] ) );
						$options_submit['department_tooltip'][ $key ]		= stripslashes( esc_html( $_POST['cntctfrm_department_tooltip'][ $key ] ) );
						$options_submit['name_tooltip'][ $key ]				= stripslashes( esc_html( $_POST['cntctfrm_name_tooltip'][ $key ] ) );
						$options_submit['location_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_location_tooltip'][ $key ] ) );
						$options_submit['address_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_address_tooltip'][ $key ] ) );
						$options_submit['email_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_email_tooltip'][ $key ] ) );
						$options_submit['phone_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_phone_tooltip'][ $key ] ) );
						$options_submit['subject_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_subject_tooltip'][ $key ] ) );
						$options_submit['message_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_message_tooltip'][ $key ] ) );
						$options_submit['attachment_tooltip'][ $key ]		= stripslashes( esc_html( $_POST['cntctfrm_attachment_tooltip'][ $key ] ) );
						$options_submit['captcha_tooltip'][ $key ]			= stripslashes( esc_html( $_POST['cntctfrm_captcha_tooltip'][ $key ] ) );
					}
				} else {
					$option_defaults = cntctfrm_get_option_defaults();

					if ( empty( $cntctfrm_options['language'] ) ) {
						$options_submit['department_label']			= $option_defaults['department_label'];
						$options_submit['name_label']				= $option_defaults['name_label'];
						$options_submit['location_label']			= $option_defaults['location_label'];
						$options_submit['address_label']			= $option_defaults['address_label'];
						$options_submit['email_label']				= $option_defaults['email_label'];
						$options_submit['phone_label']				= $option_defaults['phone_label'];
						$options_submit['subject_label']			= $option_defaults['subject_label'];
						$options_submit['message_label']			= $option_defaults['message_label'];
						$options_submit['attachment_label']			= $option_defaults['attachment_label'];
						$options_submit['send_copy_label']			= $option_defaults['send_copy_label'];
						$options_submit['privacy_check_label']		= $option_defaults['privacy_check_label'];
						$options_submit['optional_check_label']		= $option_defaults['optional_check_label'];
						$options_submit['thank_text']				= $_POST['cntctfrm_thank_text'];
						$options_submit['submit_label']				= $option_defaults['submit_label'];
						$options_submit['department_error']			= $option_defaults['department_error'];
						$options_submit['name_error']				= $option_defaults['name_error'];
						$options_submit['location_error']			= $option_defaults['location_error'];
						$options_submit['address_error']			= $option_defaults['address_error'];
						$options_submit['email_error']				= $option_defaults['email_error'];
						$options_submit['phone_error']				= $option_defaults['phone_error'];
						$options_submit['subject_error']			= $option_defaults['subject_error'];
						$options_submit['message_error']			= $option_defaults['message_error'];
						$options_submit['attachment_error']			= $option_defaults['attachment_error'];
						$options_submit['attachment_upload_error']	= $option_defaults['attachment_upload_error'];
						$options_submit['attachment_move_error']	= $option_defaults['attachment_move_error'];
						$options_submit['attachment_size_error']	= $option_defaults['attachment_size_error'];
						$options_submit['privacy_check_error']		= $option_defaults['privacy_check_error'];
						$options_submit['captcha_error']			= $option_defaults['captcha_error'];
						$options_submit['form_error']				= $option_defaults['form_error'];
						$options_submit['name_help']				= $option_defaults['name_help'];
						$options_submit['address_help']				= $option_defaults['address_help'];
						$options_submit['email_help']				= $option_defaults['email_help'];
						$options_submit['phone_help']				= $option_defaults['phone_help'];
						$options_submit['subject_help']				= $option_defaults['subject_help'];
						$options_submit['message_help']				= $option_defaults['message_help'];
						$options_submit['department_tooltip']		= $option_defaults['department_tooltip'];
						$options_submit['name_tooltip']				= $option_defaults['name_tooltip'];
						$options_submit['location_tooltip']			= $option_defaults['location_tooltip'];
						$options_submit['address_tooltip']			= $option_defaults['address_tooltip'];
						$options_submit['email_tooltip']			= $option_defaults['email_tooltip'];
						$options_submit['phone_tooltip']			= $option_defaults['phone_tooltip'];
						$options_submit['subject_tooltip']			= $option_defaults['subject_tooltip'];
						$options_submit['message_tooltip']			= $option_defaults['message_tooltip'];
						$options_submit['attachment_tooltip']		= $option_defaults['attachment_tooltip'];
						$options_submit['captcha_tooltip']			= $option_defaults['captcha_tooltip'];
						foreach ( $options_submit['thank_text'] as $key => $val ) {
							$options_submit['thank_text'][ $key ] = stripslashes( esc_html( $val ) );
						}
					} else {
						$options_submit['department_label']['default']		= $option_defaults['department_label']['default'];
						$options_submit['name_label']['default']			= $option_defaults['name_label']['default'];
						$options_submit['location_label']['default']		= $option_defaults['location_label']['default'];
						$options_submit['address_label']['default']			= $option_defaults['address_label']['default'];
						$options_submit['email_label']['default']			= $option_defaults['email_label']['default'];
						$options_submit['phone_label']['default']			= $option_defaults['phone_label']['default'];
						$options_submit['subject_label']['default']			= $option_defaults['subject_label']['default'];
						$options_submit['message_label']['default']			= $option_defaults['message_label']['default'];
						$options_submit['attachment_label']['default']		= $option_defaults['attachment_label']['default'];
						$options_submit['send_copy_label']['default']		= $option_defaults['send_copy_label']['default'];
						$options_submit['privacy_check_label']['default']	= $option_defaults['privacy_check_label']['default'];
						$options_submit['optional_check_label']['default']	= $option_defaults['optional_check_label']['default'];
						$options_submit['submit_label']['default']			= $option_defaults['submit_label']['default'];
						$options_submit['department_error']['default']		= $option_defaults['department_error']['default'];
						$options_submit['name_error']['default']			= $option_defaults['name_error']['default'];
						$options_submit['location_error']['default']		= $option_defaults['location_error']['default'];
						$options_submit['address_error']['default']			= $option_defaults['address_error']['default'];
						$options_submit['email_error']['default']			= $option_defaults['email_error']['default'];
						$options_submit['phone_error']['default']			= $option_defaults['phone_error']['default'];
						$options_submit['subject_error']['default']			= $option_defaults['subject_error']['default'];
						$options_submit['message_error']['default']			= $option_defaults['message_error']['default'];
						$options_submit['attachment_error']['default']		= $option_defaults['attachment_error']['default'];
						$options_submit['attachment_upload_error']['default']= $option_defaults['attachment_upload_error']['default'];
						$options_submit['attachment_move_error']['default']	= $option_defaults['attachment_move_error']['default'];
						$options_submit['attachment_size_error']['default']	= $option_defaults['attachment_size_error']['default'];
						$options_submit['privacy_check_error']['default']	= $option_defaults['privacy_check_error']['default'];
						$options_submit['captcha_error']['default']			= $option_defaults['captcha_error']['default'];
						$options_submit['form_error']['default']			= $option_defaults['form_error']['default'];
						$options_submit['name_help']['default']				= $option_defaults['name_help']['default'];
						$options_submit['address_help']['default']			= $option_defaults['address_help']['default'];
						$options_submit['email_help']['default']			= $option_defaults['email_help']['default'];
						$options_submit['phone_help']['default']			= $option_defaults['phone_help']['default'];
						$options_submit['subject_help']['default']			= $option_defaults['subject_help']['default'];
						$options_submit['message_help']['default']			= $option_defaults['message_help']['default'];
						$options_submit['department_tooltip']['default']	= $option_defaults['department_tooltip']['default'];
						$options_submit['name_tooltip']['default']			= $option_defaults['name_tooltip']['default'];
						$options_submit['location_tooltip']['default']		= $option_defaults['location_tooltip']['default'];
						$options_submit['address_tooltip']['default']		= $option_defaults['address_tooltip']['default'];
						$options_submit['email_tooltip']['default']			= $option_defaults['email_tooltip']['default'];
						$options_submit['phone_tooltip']['default']			= $option_defaults['phone_tooltip']['default'];
						$options_submit['subject_tooltip']['default']		= $option_defaults['subject_tooltip']['default'];
						$options_submit['message_tooltip']['default']		= $option_defaults['message_tooltip']['default'];
						$options_submit['attachment_tooltip']['default']	= $option_defaults['attachment_tooltip']['default'];
						$options_submit['captcha_tooltip']['default']		= $option_defaults['captcha_tooltip']['default'];
						foreach ( $_POST['cntctfrm_thank_text'] as $key => $val ) {
							$options_submit['thank_text'][ $key ] = stripslashes( esc_html( $_POST['cntctfrm_thank_text'][ $key ] ) );
						}
					}
				}
				$options_submit['action_after_send']	= $_POST['cntctfrm_action_after_send'];
				$options_submit['redirect_url']			= esc_url( $_POST['cntctfrm_redirect_url'] );

				/* if 'FROM' field was changed */
				if ( ( 'custom' == $cntctfrm_options['from_email'] && 'custom' != $options_submit['from_email'] ) ||
					( 'custom' == $options_submit['from_email'] && $cntctfrm_options['custom_from_email'] != $options_submit['custom_from_email'] ) ) {
					$notice = __( "Email 'FROM' field option was changed, which may cause email messages being moved to the spam folder or email delivery failures", 'contact-form-pro' ) . ".";
				}

				$cntctfrm_options = array_merge( $cntctfrm_options, $options_submit );
				if ( $options_submit['action_after_send'] == 0
					&& ( trim( $options_submit['redirect_url'] ) == "" || ! filter_var( $options_submit['redirect_url'], FILTER_VALIDATE_URL ) ) ) {
						$error .= __( "If the option 'Redirect to page' is enabled, the URL field should have such format", 'contact-form-pro' ) . " <code>http://your_site/your_page</code>";
						$cntctfrm_options['action_after_send'] = 1;
				}

				if ( isset( $display_pro_options ) ) {
					foreach ( $options_submit["departments"]["email"] as $key => $value ) {
						/* avoid empty blocks */
						if ( "" == $options_submit["departments"]["name"][ $key ] && "" == $options_submit["departments"]["email"][ $key ] ) {
							unset( $options_submit["departments"]["name"][ $key ], $options_submit["departments"]["email"][ $key ] );
						} else {
							if ( "" == $options_submit["departments"]["name"][ $key ] || "" == $options_submit["departments"]["email"][ $key ] ) {
								$error .= __(  "Please fill both fields for 'Add selectbox to the contact form:'.", 'contact-form-pro' );
							} else {
								if ( preg_match( '|,|', $options_submit["departments"]["email"][ $key ] ) ) {
									$cntctfrm_custom_department_emails = explode( ',', $options_submit["departments"]["email"][ $key ] );
								} else {
									$cntctfrm_custom_department_emails[0] = $options_submit["departments"]["email"][ $key ];
								}
								foreach ( $cntctfrm_custom_department_emails as $cntctfrm_custom_department_email ) {
									if ( $cntctfrm_custom_department_email == "" || ! is_email( trim( $cntctfrm_custom_department_email ) ) ) {
										$error .= __( "Please enter a valid email address in the 'Add department selectbox' field.", 'contact-form-pro' );
										break;
									}
								}
							}
						}
					}
					$cntctfrm_options["departments"]["name"] = $options_submit["departments"]["name"] = array_values( $options_submit["departments"]["name"] );
					$cntctfrm_options["departments"]["email"] = $options_submit["departments"]["email"] = array_values( $options_submit["departments"]["email"] );
				}

				if ( isset( $_FILES["cntctfrm_default_location"]["tmp_name"] ) && $_FILES["cntctfrm_default_location"]["tmp_name"] != "" ) {
					$uploads = wp_upload_dir();
					if ( ! isset( $uploads['path'] ) && isset( $uploads['error'] ) )
						$error .= __( "An error occurred when loading the file for location selectbox.", 'contact-form-pro' );
					else
						$cntctfrm_path_of_uploaded_file = $uploads['path'] . "/cntctfrm_" . sanitize_file_name( $_FILES["cntctfrm_default_location"]["name"] );

					$tmp_path = $_FILES["cntctfrm_default_location"]["tmp_name"];
					$path_info = pathinfo( $cntctfrm_path_of_uploaded_file );

					if ( isset( $path_info['extension'] ) && $path_info['extension'] == 'txt' ) {
						if ( is_uploaded_file( $tmp_path ) ) {
							if ( move_uploaded_file( $tmp_path, $cntctfrm_path_of_uploaded_file ) ) {
								if ( $location_content = file_get_contents( $cntctfrm_path_of_uploaded_file ) ) {
									if ( $location_content_array = explode( ',', $location_content ) ) {
										/* table with locations */
										$sql = "CREATE TABLE IF NOT EXISTS `" . $location_table . "` (
											id int NOT NULL AUTO_INCREMENT,
											name CHAR(100) NOT NULL,
											UNIQUE KEY id (id)
										);";
										require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
										dbDelta( $sql );
										$wpdb->query( "TRUNCATE TABLE `" . $location_table . "`" );
										foreach ( $location_content_array as $key => $value ) {
											if ( trim( $value ) != '' ) {
												$wpdb->insert( $location_table, array( 'name' => esc_html( trim( $value ) ) ), array( '%s' ) );
											}
										}
										$message .= __( 'The file for location selectbox has been successfully downloaded and processed.', 'contact-form-pro' ) . ' ';
									} else {
										$error .= __( "An error occurred while reading the file for location selectbox.", 'contact-form-pro' );
									}
								} else {
									$error .= __( "An error occurred while reading the file for location selectbox.", 'contact-form-pro' );
								}
								unlink( $cntctfrm_path_of_uploaded_file );
							} else {
								$letter_upload_max_size = substr( ini_get('upload_max_filesize'), -1 );
								$upload_max_size = substr( ini_get('upload_max_filesize'), 0, -1 );
								switch( strtoupper( $letter_upload_max_size ) ) {
									case 'P':
										$upload_max_size *= 1024;
									case 'T':
										$upload_max_size *= 1024;
									case 'G':
										$upload_max_size *= 1024;
									case 'M':
										$upload_max_size *= 1024;
									case 'K':
										$upload_max_size *= 1024;
										break;
								}
								if ( isset( $upload_max_size ) && isset( $_FILES["cntctfrm_default_location"]["size"] ) &&
									 $_FILES["cntctfrm_default_location"]["size"] <= $upload_max_size ) {
									$error .= __( "An error occurred while moving the file for location selectbox.", 'contact-form-pro' );
								} else {
									$error .= __( "An error occurred while moving the file for location selectbox.", 'contact-form-pro' );
								}
							}
						} else {
							$error .= __( "An error occurred when loading the file for location selectbox.", 'contact-form-pro' );
						}
					} else {
						$error .= __( "Please upload the TXT file for location selectbox.", 'contact-form-pro' );
					}
				} elseif ( $options_submit['display_location_field'] == 1 ) {
					/* table with locations */
					$sql = "CREATE TABLE IF NOT EXISTS `" . $location_table . "` (
						`id` int NOT NULL AUTO_INCREMENT,
						`name` CHAR(100) NOT NULL,
						UNIQUE KEY id (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $sql );
					$locations = $wpdb->get_results( "SELECT * FROM " . $location_table );
					if ( empty( $locations ) ) {
						foreach ( $cntctfrm_countries as $key => $value ) {
							$db_row = $wpdb->get_row( "SELECT * FROM " . $location_table . " WHERE `name` = '" . addcslashes( trim( $value ), "'" ) . "'", ARRAY_A );
							if ( ! isset( $db_row ) || empty( $db_row ) ) {
								$wpdb->insert( $location_table, array( 'name' => addcslashes( trim( $value ), "'" ) ), array( '%s' ) );
							}
						}
					}
				}

				if ( 'user' == $options_submit['select_email'] ) {
					if ( false !== get_user_by( 'login', $options_submit['user_email'] ) ) {
						/**/
					} else {
						$error .=__(  "Such user does not exist.", 'contact-form-pro' );
					}
				} elseif ( 'custom' == $options_submit['select_email'] ) {
					if ( preg_match( '|,|', $options_submit['custom_email'] ) ) {
						$cntctfrm_custom_emails = explode( ',', $options_submit['custom_email'] );
					} else {
						$cntctfrm_custom_emails[0] = $options_submit['custom_email'];
					}
					foreach ( $cntctfrm_custom_emails as $cntctfrm_custom_email ) {
						if ( $cntctfrm_custom_email == "" || ! is_email( trim( $cntctfrm_custom_email ) ) ) {
							$error .= __( "Please enter a valid email address in the 'Use this email address:' field.", 'contact-form-pro' );
							break;
						}
					}
				} elseif ( 'departments' == $options_submit['select_email'] && empty( $cntctfrm_options["departments"]["email"] ) ) {
					$error .= __( "Please add the department name and email address if You want to add a selectbox.", 'contact-form-pro' );
					$options_submit['select_email'] = 'user';
				}

				if ( 'custom' == $options_submit['from_email'] ) {
					if ( $options_submit['custom_from_email'] == "" || ! is_email( $options_submit['custom_from_email'] ) ) {
						$error .= __( "Please enter a valid email address in the 'FROM' field.", 'contact-form-pro' );
					}
				}

				if ( '' == $error ) {
					if ( 'pro' == $contact_form_multi_active && $multi_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
						if ( $multi_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
							add_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'] , $cntctfrm_options );
						else if ( $multi_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
							update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'] , $cntctfrm_options );
					} elseif ( $contact_form_multi_active ) {
						$multi_options_main = get_option( 'cntctfrmmlt_options_main' );

						if ( $multi_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
							add_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'] , $cntctfrm_options );
						else if ( $multi_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
							update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'] , $cntctfrm_options );
					} else {
						update_option( 'cntctfrm_options', $cntctfrm_options );
					}

					if ( 'departments' == $options_submit['select_email'] && count( $cntctfrm_options["departments"]["email"] ) == 1 ) {
						$message = __( "Settings saved. You need at least 2 fields to display selectbox in the frontend, currently there is only one.", 'contact-form-pro' );
					} else {
						$message .= __( "Settings saved.", 'contact-form-pro' );
					}
				} else {
					$error .= ' ' . __( "Settings are not saved.", 'contact-form-pro' );
				}
			} elseif ( 'appearance' == $_GET['action'] ) {
				/* Save data from "Settings" or "Appearance" tab */
				$cntctfrm_options['layout'] = $cntctfrm_layout = ( isset( $_POST['cntctfrm_layout'] ) ) ? (int) $_POST[ 'cntctfrm_layout' ] : 1;
				$cntctfrm_options['submit_position'] = ( isset( $_POST['cntctfrm_submit_position'] ) ) ? stripslashes( esc_html( $_POST['cntctfrm_submit_position'] ) ) : 'left';
				$cntctfrm_options['form_align'] = ( isset( $_POST['cntctfrm_form_align'] ) ) ? stripslashes( esc_html( $_POST['cntctfrm_form_align'] ) ) : 'left';
				$cntctfrm_options['labels_settings']['position'] = ( isset( $_POST['cntctfrm_labels_position'] ) ) ? stripslashes( esc_html( $_POST['cntctfrm_labels_position'] ) ) : 'top';
				$cntctfrm_options['labels_settings']['align'] = ( isset( $_POST['cntctfrm_labels_align'] ) ) ? stripslashes( esc_html( $_POST['cntctfrm_labels_align'] ) ) : 'left';

				$layout_first_column_string = stripslashes( esc_html( $_POST['cntctfrm_layout_first_column'] ) );
				$layout_first_column = explode( ',', $layout_first_column_string );
				$layout_first_column = array_diff( $layout_first_column, array('') );

				$layout_second_column_string = stripslashes( esc_html( $_POST['cntctfrm_layout_second_column'] ) );
				$layout_second_column = explode( ',', $layout_second_column_string );
				$layout_second_column = array_diff( $layout_second_column, array('') );

				if ( $cntctfrm_layout === 1 && ! empty( $layout_second_column ) ) {
					$layout_first_column = array_merge( $layout_first_column, $layout_second_column );
					$layout_second_column = array();
				}

				$cntctfrm_options['order_fields']['first_column'] = $layout_first_column;
				$cntctfrm_options['order_fields']['second_column'] = $layout_second_column;

				if ( isset( $display_pro_options ) ) {
					$cntctfrm_options['error_displaying'] = trim( $_POST['cntctfrm_error_displaying'] );
					$cntctfrm_options['placeholder'] = isset( $_POST['cntctfrm_placeholder'] ) ? 1 : 0;

					$cntctfrm_options['tooltip_display_department']		= isset( $_POST['cntctfrm_tooltip_display_department'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_name']			= isset( $_POST['cntctfrm_tooltip_display_name'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_location']		= isset( $_POST['cntctfrm_tooltip_display_location'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_address']		= isset( $_POST['cntctfrm_tooltip_display_address'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_email']			= isset( $_POST['cntctfrm_tooltip_display_email'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_phone']			= isset( $_POST['cntctfrm_tooltip_display_phone'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_subject']		= isset( $_POST['cntctfrm_tooltip_display_subject'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_message']		= isset( $_POST['cntctfrm_tooltip_display_message'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_attachment']		= isset( $_POST['cntctfrm_tooltip_display_attachment'] ) ? 1 : 0;
					$cntctfrm_options['tooltip_display_captcha']		= isset( $_POST['cntctfrm_tooltip_display_captcha'] ) ? 1 : 0;

					$cntctfrm_options['style_options'] = isset( $_POST['cntctfrm_style_options'] ) ? 1 : 0;

					if ( $cntctfrm_options['style_options'] != 0 ) {
						$cntctfrm_options['label_color']					= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_label_color'] ) ) ) );
						$cntctfrm_options['error_color']					= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_error_color'] ) ) ) );
						$cntctfrm_options['input_background']				= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_input_background'] ) ) ) );
						$cntctfrm_options['input_color']					= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_input_color'] ) ) ) );
						$cntctfrm_options['input_placeholder_color']		= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_input_placeholder_color'] ) ) ) );
						$cntctfrm_options['input_placeholder_error_color']= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_input_placeholder_error_color'] ) ) ) );
						$cntctfrm_options['border_input_width']			= ( '' != trim( $_REQUEST['cntctfrm_border_input_width'] ) ) ? intval( $_REQUEST['cntctfrm_border_input_width'] ) : '';
						$cntctfrm_options['border_input_color']			= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_border_input_color'] ) ) ) );
						$cntctfrm_options['error_input_color']			= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_error_input_color'] ) ) ) );
						$cntctfrm_options['error_input_border_color']		= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_error_input_border_color'] ) ) ) );
						$cntctfrm_options['button_width']					= ( '' != trim( $_REQUEST['cntctfrm_button_width'] ) ) ? intval( $_REQUEST['cntctfrm_button_width'] ) : '';
						$cntctfrm_options['button_backgroud']				= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_button_backgroud'] ) ) ) );
						$cntctfrm_options['button_color']					= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_button_color'] ) ) ) );
						$cntctfrm_options['border_button_color']			= stripslashes( esc_html( trim( str_replace( "-", "", $_REQUEST['cntctfrm_border_button_color'] ) ) ) );
					}
				}

				if ( '' == $error ) {
					if ( 'pro' == $contact_form_multi_active && $cntctfrmmltpr_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
						if ( $cntctfrmmltpr_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
							add_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'] , $cntctfrm_options );
						else if ( $cntctfrmmltpr_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
							update_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'] , $cntctfrm_options );
					} elseif ( $contact_form_multi_active ) {
						$cntctfrmmlt_options_main = get_option( 'cntctfrmmlt_options_main' );

						if ( $cntctfrmmlt_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
							add_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'] , $cntctfrm_options );
						else if ( $cntctfrmmlt_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
							update_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'] , $cntctfrm_options );
					} else {
						update_option( 'cntctfrm_options', $cntctfrm_options );
					}

					$message = __( "Settings saved.", 'contact-form-pro' );
				}
			}
		}

		/* Add restore function */
		if ( isset( $_REQUEST['bws_restore_confirm'] ) && check_admin_referer( $plugin_basename, 'bws_settings_nonce_name' ) ) {

			$option_defaults = cntctfrm_get_option_defaults();

			if ( $contact_form_multi_active ) {
				$contact_form_multi_options = array(
					'display_captcha'				=> 0,
					'display_google_captcha'		=> 0,
					'display_subscribe'				=> 0,
					'save_email_to_db'				=> 1,
				);
				$option_defaults = array_merge( $option_defaults, $contact_form_multi_options );
			}

			$cntctfrm_options = $option_defaults;

			if ( $contact_form_multi_active ) {
				if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) && get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] ) ) {
					update_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'], $cntctfrm_options );
				} else {
					update_option( 'cntctfrmmlt_options', $cntctfrm_options );
				}
			} else {
				update_option( 'cntctfrm_options', $cntctfrm_options );
			}

			/* table with locations */
			$sql = "CREATE TABLE IF NOT EXISTS `" . $location_table . "` (
				`id` int NOT NULL AUTO_INCREMENT,
				`name` CHAR(100) NOT NULL,
				UNIQUE KEY id (id)
			);";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			$wpdb->query( "TRUNCATE TABLE `" . $location_table . "`" );
			foreach ( $cntctfrm_countries as $key => $value ) {
				$db_row = $wpdb->get_row( "SELECT * FROM " . $location_table . " WHERE `name` = '" . addcslashes( trim( $value ), "'" ) . "'", ARRAY_A );
				if ( ! isset( $db_row ) || empty( $db_row ) ) {
					$wpdb->insert( $location_table, array( 'name' => addcslashes( trim( $value ), "'" ) ), array( '%s' ) );
				}
			}
			$message = __( 'All plugin settings were restored.', 'contact-form-pro' );
		} /* end */

		/* GO PRO - CF Multi PRO */
		if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) {
			$go_pro_result = bws_go_pro_tab_check( $plugin_basename );
			if ( ! empty( $go_pro_result['error'] ) )
				$error = $go_pro_result['error'];
		} elseif ( isset( $_POST['bws_license_nonce_name'] ) ) {
			/* check license for CF PRO */
			if ( wp_verify_nonce( $_POST['bws_license_nonce_name'], 'contact-form-pro/contact_form_pro.php' ) ) {
				$result_check_pro = bws_check_pro_license( 'contact-form-pro/contact_form_pro.php' );
				if ( ! empty( $result_check_pro['error'] ) )
					$error = $result_check_pro['error'];
				elseif ( ! empty( $result_check_pro['message'] ) )
					$message = $result_check_pro['message'];
			} elseif ( wp_verify_nonce( $_POST['bws_license_nonce_name'], 'contact-form-multi-pro/contact-form-multi-pro.php' ) ) {
				/* Check license For CF Multi PRO */
				if ( 'pro' == $contact_form_multi_active ) {
					$result_check_pro = bws_check_pro_license( 'contact-form-multi-pro/contact-form-multi-pro.php' );
					if ( ! empty( $result_check_pro['error'] ) )
						$error = $result_check_pro['error'];
					elseif ( ! empty( $result_check_pro['message'] ) )
						$message = $result_check_pro['message'];
				}
			}
		}

		/* Tab name */
		if ( ! isset( $_GET['action'] ) ) {
			$tab_name = 'Contact Form Pro | ' . __( "Settings", 'contact-form-pro' );
		} else {
			switch ( $_GET['action'] ) {
				case 'additional':
					$tab_name = 'Contact Form Pro | ' . __( "Additional settings", 'contact-form-pro' );
					break;
				case 'appearance':
					$tab_name = 'Contact Form Pro | ' . __( "Appearance", 'contact-form-pro' );
					break;
				case 'custom_code':
					$tab_name = 'Contact Form Pro | ' . __( "Custom Code", 'contact-form-pro' );
					break;
				default:
					$tab_name = 'Contact Form Pro | ' . __( "Settings", 'contact-form-pro' );
					break;
			}
		} ?>
		<div class="wrap">
			<h1><?php echo $tab_name; ?></h1>
			<div id="cntctfrm_nav_container">
				<ul class="subsubsub cntctfrm_how_to_use">
					<li><a href="https://docs.google.com/document/d/1qZYPJhkSdVyyM6XO5WfiBcTS2Sa9_9UMn4vS2g48JRY/" target="_blank"><?php _e( 'How to Use Step-by-step Instruction', 'contact-form-pro' ); ?></a></li>
				</ul>
				<h2 id="cntctfrm_nav" class="nav-tab-wrapper">
					<a class="nav-tab<?php if ( ! isset( $_GET['action'] ) ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form_pro.php"><?php _e( 'Settings', 'contact-form-pro' ); ?></a>
					<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'additional' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form_pro.php&amp;action=additional"><?php _e( 'Additional settings', 'contact-form-pro' ); ?></a>
					<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'appearance' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form_pro.php&amp;action=appearance"><?php _e( 'Appearance', 'contact-form-pro' ); ?></a>
					<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'custom_code' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form_pro.php&amp;action=custom_code"><?php _e( 'Custom Code', 'contact-form-pro' ); ?></a>
					<?php if ( 'free' == $contact_form_multi_active ) { ?>
						<a class="nav-tab bws_go_pro_tab<?php if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form_pro.php&amp;action=go_pro"><?php _e( 'Go PRO for Contact Form Multi', 'contact-form-pro' ); ?></a>
					<?php } ?>
				</h2>
				<div class="cntctfrm_clear"></div>
			</div>
			<div class="updated fade below-h2" <?php if ( "" == $message || $error != "" ) echo 'style="display:none"'; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div class="error below-h2" <?php if ( "" == $error ) echo 'style="display:none"'; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<?php bws_show_settings_notice();
			if ( ! empty( $notice ) ) { ?>
				<div class="error below-h2"><p><strong><?php _e( 'Notice', 'contact-form-pro' ); ?>:</strong> <?php echo $notice; ?></p></div>
			<?php }

			if ( ( ! isset( $_GET['action'] ) || ( 'go_pro' != $_GET['action'] && 'custom_code' != $_GET['action'] ) ) && ! $contact_form_multi_active && ! isset( $_REQUEST['bws_restore_default'] ) ) {
				/* display multi + buttons */ ?>
					<h3 class="nav-tab-wrapper">
						<span class="nav-tab nav-tab-active"><?php _e( 'NEW_FORM', 'contact-form-pro' ); ?></span>
						<a id="cntctfrm_show_multi_notice" class="nav-tab" target="_new" href="https://bestwebsoft.com/products/wordpress/plugins/contact-form-multi/?k=57d8351b1c6b67d3e0600bd9a680c283&amp;pn=3&amp;v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>" title="<?php _e( "If you want to create multiple contact forms, please install the Contact Form Multi plugin.", 'contact-form-pro' ); ?>">+</a>
					</h3>
			<?php }
			/* display tabs */
			if ( ! isset( $_GET['action'] ) || 'additional' == $_GET['action'] ) {
				if ( isset( $_REQUEST['bws_restore_default'] ) && check_admin_referer( $plugin_basename, 'bws_settings_nonce_name' ) ) {
					bws_form_restore_default_confirm( $plugin_basename );
				} else {
					/* main 'settings' or 'additional' settings page */
					$form_action = ( ! isset( $_GET['action'] ) ) ? 'admin.php?page=contact_form_pro.php' : 'admin.php?page=contact_form_pro.php&amp;action=' . $_GET['action']; ?>
					<form id="cntctfrm_settings_form" class="bws_form" method="post" action="<?php echo esc_url( $form_action ); ?>" enctype="multipart/form-data">
						<div style="margin: 20px 0;">
							<?php printf( __( "If you would like to add a Contact Form to your page or post, please use %s button", 'contact-form-pro' ),
								'<span class="bws_code"><span class="bwsicons bwsicons-shortcode"></span></span>'
							); ?>
							<div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help">
								<div class="bws_hidden_help_text" style="min-width: 260px;">
									<?php printf(
										__( "You can add the Contact Form to your page or post by clicking on %s button in the content edit block using the Visual mode. If the button isn't displayed, please use the shortcode %s or %s where * stands for Contact Form language.", 'contact-form-pro' ),
										'<span class="bws_code"><span class="bwsicons bwsicons-shortcode"></span></span>',
										sprintf( '<br/><span class="bws_code">[bestwebsoft_contact_form%s]</span><br/>', ( ! $contact_form_multi_active ) ? '' : ' id=' . $_SESSION['cntctfrmmlt_id_form'] ),
										sprintf( '<br/><span class="bws_code">[bestwebsoft_contact_form%s lang=*]</span>,<br/>', ( ! $contact_form_multi_active ) ? '' : ' id=' . $_SESSION['cntctfrmmlt_id_form'] )
									); ?>
								</div>
							</div>
						</div>
						<!-- Main 'settings' tab -->
						<div <?php if ( isset( $_GET['action'] ) ) echo 'style="display: none;"'; ?> >
							<p><?php _e( "If you leave the fields empty, the messages will be sent to the email address specified during registration.", 'contact-form-pro' ); ?></p>
							<table class="form-table" style="width:auto;">
								<tr valign="top">
									<th scope="row"><?php _e( "The user's email address", 'contact-form-pro' ); ?>: </th>
									<td colspan="2">
										<label>
											<input type="radio" id="cntctfrm_select_email_user" name="cntctfrm_select_email" value="user" <?php if ( $cntctfrm_options['select_email'] == 'user' ) echo 'checked="checked" '; ?>/>
											<select name="cntctfrm_user_email">
												<option disabled><?php _e( "Select a username", 'contact-form-pro' ); ?></option>
												<?php foreach ( $userslogin as $key => $value ) {
													if ( isset( $value->data ) ) {
														if ( $value->data->user_email != '' ) { ?>
															<option value="<?php echo $value->data->user_login; ?>" <?php if ( $cntctfrm_options['user_email'] == $value->data->user_login ) echo 'selected="selected" '; ?>><?php echo $value->data->user_login; ?></option>
														<?php }
													} else {
														if ( $value->user_email != '' ) { ?>
															<option value="<?php echo $value->user_login; ?>" <?php if ( $cntctfrm_options['user_email'] == $value->user_login ) echo 'selected="selected" '; ?>><?php echo $value->user_login; ?></option>
														<?php }
													}
												} ?>
											</select>
											<span class="bws_info cntctfrm_info"><?php _e( 'Select a username of the person who should get the messages from the contact form.', 'contact-form-pro' ); ?></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Use this email address", 'contact-form-pro' ); ?>:</th>
									<td colspan="2">
										<label>
											<input type="radio" id="cntctfrm_select_email_custom" name="cntctfrm_select_email" value="custom" <?php if ( $cntctfrm_options['select_email'] == 'custom' ) echo 'checked="checked" '; ?>/>
											<input type="text" maxlength="500" name="cntctfrm_custom_email" value="<?php echo $cntctfrm_options['custom_email']; ?>" />
											<span class="bws_info cntctfrm_info"><?php _e( 'Enter the email address for receiving messages', 'contact-form-pro' ); ?>.</span>
										</label>
									</td>
								</tr>
								<?php if ( isset( $display_pro_options ) ) { ?>
									<tr valign="top">
										<th scope="row" style="width:200px;"><?php _e( "Add department selectbox to the contact form", 'contact-form-pro' ); ?>:</th>
										<td colspan="2">
											<input type="radio" id="cntctfrm_select_email_department" name="cntctfrm_select_email" value="departments" <?php if ( $cntctfrm_options['select_email'] == 'departments' ) echo 'checked="checked" '; ?>/>
											<div class="cntctfrm_department_table">
												<div class="cntctfrm_department_sortable">
													<?php if ( ! empty( $cntctfrm_options['departments']['name'] ) ) {
														foreach ( $cntctfrm_options['departments']['name'] as $key => $value ) { ?>
															<div class="cntctfrm_department_block">
																<span class="cntctfrm_department_name">
																	<input id="<?php echo $key; ?>" placeholder="<?php _e( "Enter the department name", 'contact-form-pro' ); ?>" type="text" maxlength="100" name="cntctfrm_department_name[]" value="<?php echo $value; ?>" />
																</span>
																<img class="cntctfrm_drag_departament" title="" src="<?php echo plugins_url( 'images/dragging-arrow.png', __FILE__ ); ?>" alt=""/>
																<span class="cntctfrm_department_email"><input placeholder="<?php _e( "Enter the department email", 'contact-form-pro' ); ?>" type="text" maxlength="250" name="cntctfrm_department_email[]" value="<?php echo $cntctfrm_options['departments']['email'][ $key ]; ?>" /></span>
																<span class="cntctfrm_department_delete">
																	<input type="checkbox" name="cntctfrm_department_delete[]" title="<?php _e( "Delete", 'contact-form-pro' ); ?>" value="<?php echo $key; ?>" /><label></label><!-- not remove<label></label> -->
																</span>
															</div>
														<?php }
													} ?>
													<div class="cntctfrm_department_block_new">
														<span class="cntctfrm_department_name"><input placeholder="<?php _e( "Enter the department name", 'contact-form-pro' ); ?>" type="text" maxlength="100" name="cntctfrm_department_name[]" value="" /></span>
														<img class="cntctfrm_drag_departament" title="" src="<?php echo plugins_url( 'images/dragging-arrow.png', __FILE__ ); ?>" alt=""/>
														<span class="cntctfrm_department_email"><input placeholder="<?php _e( "Enter the department email", 'contact-form-pro' ); ?>" type="text" maxlength="250" name="cntctfrm_department_email[]" value="" /></span>
														<span class="cntctfrm_department_delete"></span>
													</div>
												</div>
												<div class="bws_info cntctfrm_add_new"><?php _e( "Fill the fields, save settings and new block will appear", 'contact-form-pro' ); ?></div>
												<div class="cntctfrm_add_new_button"><input id="cntctfrm_department_add" class="button-small button hidden" type="button" value="<?php _e( "Add", 'contact-form-pro' ); ?>">
												<div class="cntctfrm_clear"></div>
											</div>
										</td>
									</tr>
								<?php } ?>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Save emails to the database", 'contact-form-pro' ); ?> </th>
									<td colspan="2">
										<?php if ( array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) || array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) {
											if ( array_key_exists( 'contact-form-to-db', $cntctfrm_related_plugins ) ) {
												$save_emails  = false;
												if ( ! $contact_form_multi_active ) {
													$save_emails = ! empty( $cntctfrm_related_plugins['contact-form-to-db']['options'][ $cntctfrm_related_plugins['contact-form-to-db']['save_option'] ] ) ? true : false;
												} else {
													$save_emails = ! empty( $cntctfrm_options['save_email_to_db'] ) ? true : false;
												}
												if (  ! $contact_form_multi_active || ! empty( $cntctfrm_related_plugins['contact-form-to-db']['options'][ $cntctfrm_related_plugins['contact-form-to-db']['save_option'] ] ) ) { ?>
													<label><input type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( $save_emails ) echo 'checked="checked"'; ?> />
														<span class="bws_info"> (<?php _e( 'Using', 'contact-form-pro' ); ?>
															<a href="<?php echo self_admin_url( '/admin.php?page=' . $cntctfrm_related_plugins['contact-form-to-db']['settings_page'] ); ?>" target="_blank">Contact Form to DB by BestWebSoft</a>)
														</span>
													</label>
												<?php } else { ?>
													<label><input type="checkbox" name="cntctfrm_save_email_to_db" value="1" disabled="disabled" <?php if ( $save_emails ) echo 'checked="checked"'; ?> /></label>
													<span class="bws_info">&nbsp;(<?php _e( 'Please activate the appropriate option on', 'contact-form-pro' ) ?>&nbsp;
														<?php printf( '<a href="%s" target="_blank"> Contact Form to DB %s</a>&nbsp;)',
															self_admin_url( '/admin.php?page=' . $cntctfrm_related_plugins['contact-form-to-db']['settings_page'] ),
															__( 'settings page', 'contact-form-pro' ) ); ?>
													</span>
												<?php }
											} else { ?>
												<label><input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( ! empty( $cntctfrm_options["save_email_to_db"] ) ) echo 'checked="checked"'; ?> />
													<span class="bws_info">(<?php _e( 'Using', 'contact-form-pro' ); ?> Contact Form to DB by BestWebSoft)
													<?php printf( '<a href="%s" target="_blank">%s Contact Form to DB</a>', self_admin_url( 'plugins.php' ), __( 'Activate', 'contact-form-pro' ) ); ?>
													</span>
												</label>
											<?php }
										} else { ?>
											<label><input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" />
												<span class="bws_info">(<?php _e( 'Using', 'contact-form-pro' ); ?> Contact Form to DB by BestWebSoft)
													<?php printf( '<a href="https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/?k=b3bfaac63a55128a35e3f6d0a20dd43d&amp;pn=3&amp;v=%s&amp;wp_v=%s"> %s Contact Form to DB</a>', $cntctfrm_plugin_info["Version"], $wp_version, __( 'Download', 'contact-form-pro' ) ); ?>
												</span>
											</label>
										<?php } ?>
									</td>
								</tr>
							</table>
						</div>
						<!-- 'Additional settings' tab -->
						<div <?php if ( ! isset( $_GET['action'] ) ) echo 'style="display: none;"'; ?> >
							<table class="form-table" style="width:auto;">
								<tr>
									<th scope="row" style="width:200px;"><?php _e( 'Sending method', 'contact-form-pro' ); ?></th>
									<td colspan="2">
										<fieldset>
											<label><input type='radio' name='cntctfrm_mail_method' value='wp-mail' <?php if ( $cntctfrm_options['mail_method'] == 'wp-mail' ) echo 'checked="checked" '; ?>/>
											<?php _e( 'Wp-mail', 'contact-form-pro' ); ?>
											<span class="bws_info">(<?php _e( 'You can use the Wordpress wp_mail function for mailing', 'contact-form-pro' ); ?>)</span></label><br/>
											<label><input type='radio' name='cntctfrm_mail_method' value='mail' <?php if ( $cntctfrm_options['mail_method'] == 'mail' ) echo 'checked="checked" '; ?>/>
											<?php _e( 'Mail', 'contact-form-pro' ); ?>
											<span class="bws_info">(<?php _e( 'You can use the PHP mail function for mailing', 'contact-form-pro' ); ?>)</span></label>
										</fieldset>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( "'FROM' field", 'contact-form-pro' ); ?></th>
									<td class="cntctfrm_td_name" style="vertical-align: top;">
										<table id="cntctfrm_table_from_name">
											<tbody>
												<tr>
													<td colspan="2"><?php _e( "Name", 'contact-form-pro' ); ?></td>
												</tr>
												<tr>
													<td class="cntctfrm_radio_from_name"><input type="radio" id="cntctfrm_select_from_custom_field" name="cntctfrm_select_from_field" value="custom" <?php if ( 'custom' == $cntctfrm_options['select_from_field'] ) echo 'checked="checked" '; ?> /></td>
													<td><input type="text" name="cntctfrm_from_field" value="<?php echo stripslashes( $cntctfrm_options['from_field'] ); ?>" size="18" maxlength="100" /></td>
												</tr>
												<tr>
													<td class="cntctfrm_radio_from_name">
														<input type="radio" id="cntctfrm_select_from_field" name="cntctfrm_select_from_field" value="user_name" <?php if ( 'user_name' == $cntctfrm_options['select_from_field'] ) echo 'checked="checked" '; ?>/>
													</td>
													<td>
														<label for="cntctfrm_select_from_field"><?php _e( "User name", 'contact-form-pro' ); ?></label>
														<div class="bws_help_box dashicons dashicons-editor-help">
															<div class="bws_hidden_help_text" style="min-width: 200px;"><?php echo __( "The name of the user who fills the form will be used in the field 'From'.", 'contact-form-pro' ); ?></div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td class="cntctfrm_td_email" style="vertical-align: top;">
										<table id="cntctfrm_table_from_email">
											<tbody>
												<tr>
													<td colspan="2"><?php _e( "Email", 'contact-form-pro' ); ?></td>
												</tr>
												<tr>
													<td class="cntctfrm_radio_from_email"><input type="radio" id="cntctfrm_from_custom_email" name="cntctfrm_from_email" value="custom" <?php if ( 'custom' == $cntctfrm_options['from_email'] ) echo 'checked="checked" '; ?>/></td>
													<td><input type="text" name="cntctfrm_custom_from_email" value="<?php echo $cntctfrm_options['custom_from_email']; ?>" maxlength="100" /></td>
												</tr>
												<tr>
													<td class="cntctfrm_radio_from_email">
														<input type="radio" id="cntctfrm_from_email" name="cntctfrm_from_email" value="user" <?php if ( 'user' == $cntctfrm_options['from_email'] ) echo 'checked="checked" '; ?>/>
													</td>
													<td>
														<label for="cntctfrm_from_email"><?php _e( "User email", 'contact-form-pro' ); ?></label>
													<div class="bws_help_box dashicons dashicons-editor-help">
														<div class="bws_hidden_help_text" style="min-width: 200px;"><?php echo __( "The email address of the user who fills the form will be used in the field 'From'.", 'contact-form-pro' ); ?></div>
													</div>
													</td>
												</tr>
												<tr>
													<td>
													</td>
													<td>
														<div>
															<span class="bws_info">(<?php _e( "If this option is changed, email messages may be moved to the spam folder or email delivery failures may occur.", 'contact-form-pro' ); ?>)</span>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Add field 'Reply-To' to the email header", 'contact-form-pro' ); ?></th>
									<td colspan="2">
										<label><input type="checkbox" id="cntctfrm_header_reply_to" name="cntctfrm_header_reply_to" value="1" <?php if ( $cntctfrm_options['header_reply_to'] == '1' ) echo 'checked="checked" '; ?>/> <span class="bws_info">(<?php _e( "Field 'Reply-To' will be initialized by user email", 'contact-form-pro' ); ?>)</span></label>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Required symbol", 'contact-form-pro' ); ?></th>
									<td colspan="2">
										<input type="text" maxlength="100" name="cntctfrm_required_symbol" value="<?php echo $cntctfrm_options['required_symbol']; ?>" />
									</td>
								</tr>
							</table>
							<br />
							<table class="cntctfrm_settings_table">
								<thead>
									<tr valign="top">
										<th scope="row"><?php _e( "Fields", 'contact-form-pro' ); ?></th>
										<th><?php _e( "Used", 'contact-form-pro' ); ?></th>
										<th><?php _e( "Required", 'contact-form-pro' ); ?></th>
										<th><?php _e( "Visible", 'contact-form-pro' ); ?></th>
										<th><?php _e( "Disabled for editing", 'contact-form-pro' ); ?></th>
										<th scope="row"><?php _e( "Field's default value", 'contact-form-pro' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if ( isset( $display_pro_options ) ) {
										if ( $cntctfrm_options['select_email'] == 'departments' ) { ?>
											<tr valign="top" >
												<td><?php _e( "Department selectbox", 'contact-form-pro' ); ?></td>
												<td></td>
												<td>
													<label><input type="checkbox" name="cntctfrm_required_department_field" value="1" <?php if ( $cntctfrm_options['required_department_field'] == '1' ) echo 'checked="checked" '; ?>/>
													<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
												</td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										<?php }
									} ?>
									<tr valign="top" <?php if ( $cntctfrm_options['display_name_field'] != '1' ) echo 'class="cntctfrm_disabled_row" '; ?>>
										<td><?php _e( "Name", 'contact-form-pro' ); ?></td>
										<td>
											<label><input class="cntctfrm_checkbox_disabled_row" type="checkbox" name="cntctfrm_display_name_field" value="1" <?php if ( $cntctfrm_options['display_name_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Used", 'contact-form-pro' ); ?></span></label>
										</td>
										<td>
											<label><input type="checkbox" name="cntctfrm_required_name_field" value="1" <?php if ( $cntctfrm_options['required_name_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<?php if ( isset( $display_pro_options ) ) { ?>
											<td>
												<label><input class="name" type="checkbox" name="cntctfrm_visible_name" value="1" <?php if ( $cntctfrm_options['visible_name'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Visible", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label><input class="name" type="checkbox" name="cntctfrm_disabled_name" value="1" <?php if ( $cntctfrm_options['disabled_name'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Disabled for editing", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label>
													<input class="name" type="checkbox" name="cntctfrm_default_name" value="1" <?php if ( $cntctfrm_options['default_name'] == '1' ) echo 'checked="checked" '; ?>/>
													<?php _e( "Use User's name as a default value if the user is logged in.", 'contact-form-pro' ); ?><br />
												</label>
												<span class="bws_info">(<?php _e( "'Visible' and 'Disabled for editing' options will be applied only to logged-in users.", 'contact-form-pro' ); ?>)</span>
											</td>
										<?php } else { ?>
											<td></td>
											<td></td>
											<td></td>
										<?php } ?>
									</tr>
									<?php if ( isset( $display_pro_options ) ) { ?>
										<tr valign="top" <?php if ( $cntctfrm_options['display_location_field'] != '1' ) echo 'class="cntctfrm_disabled_row" '; ?>>
											<td><?php _e( "Location selectbox", 'contact-form-pro' ); ?></td>
											<td>
												<label><input class="cntctfrm_checkbox_disabled_row" type="checkbox" name="cntctfrm_display_location_field" value="1" <?php if ( $cntctfrm_options['display_location_field'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Used", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label><input type="checkbox" name="cntctfrm_required_location_field" value="1" <?php if ( $cntctfrm_options['required_location_field'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
											</td>
											<td></td>
											<td></td>
											<td id="cntctfrm_default_location_cell">
												<span class="cntctfrm_mobile_title"><?php _e( "Field's default value", 'contact-form-pro' ); ?></span>
												<input type="file" name="cntctfrm_default_location" id="cntctfrm_default_location" />
												<div class="bws_help_box dashicons dashicons-editor-help">
													<div class="bws_hidden_help_text" style="min-width: 200px;"><?php _e( "Please upload the TXT file that includes the names of locations to overwrite the standard values. Example: location1,location2,location3", 'contact-form-pro' ); ?></div>
												</div>
											</td>
										</tr>
									<?php } ?>
									<tr valign="top" <?php if ( $cntctfrm_options['display_address_field'] != '1' ) echo 'class="cntctfrm_disabled_row" '; ?>>
										<td><?php _e( "Address", 'contact-form-pro' ); ?></td>
										<td>
											<label><input class="cntctfrm_checkbox_disabled_row" type="checkbox" name="cntctfrm_display_address_field" value="1" <?php if ( $cntctfrm_options['display_address_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Used", 'contact-form-pro' ); ?></span></label>
										</td>
										<td>
											<label><input type="checkbox" name="cntctfrm_required_address_field" value="1" <?php if ( $cntctfrm_options['required_address_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr valign="top">
										<td><?php _e( "Email Address", 'contact-form-pro' ); ?></td>
										<td></td>
										<td>
											<label><input type="checkbox" name="cntctfrm_required_email_field" value="1" <?php if ( $cntctfrm_options['required_email_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<?php if ( isset( $display_pro_options ) ) { ?>
											<td>
												<label><input class="email" type="checkbox" name="cntctfrm_visible_email" value="1" <?php if ( $cntctfrm_options['visible_email'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Visible", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label><input class="email" type="checkbox" name="cntctfrm_disabled_email" value="1" <?php if ( $cntctfrm_options['disabled_email'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Disabled for editing", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label>
													<input class="email" type="checkbox" name="cntctfrm_default_email" value="1" <?php if ( $cntctfrm_options['default_email'] == '1' ) echo 'checked="checked" '; ?>/>
													<?php _e( "Use User's email as a default value if the user is logged in.", 'contact-form-pro' ); ?><br />
												</label>
												<span class="bws_info">(<?php _e( "'Visible' and 'Disabled for editing' options will be applied only to logged-in users.", 'contact-form-pro' ); ?>)</span>
											</td>
										<?php } else { ?>
											<td></td>
											<td></td>
											<td></td>
										<?php } ?>
									</tr>
									<tr valign="top" <?php if ( $cntctfrm_options['display_phone_field'] != '1' ) echo 'class="cntctfrm_disabled_row" '; ?>>
										<td><?php _e( "Phone number", 'contact-form-pro' ); ?></td>
										<td>
											<label><input class="cntctfrm_checkbox_disabled_row" type="checkbox" name="cntctfrm_display_phone_field" value="1" <?php if ( $cntctfrm_options['display_phone_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Used", 'contact-form-pro' ); ?></span></label>
										</td>
										<td>
											<label><input type="checkbox" name="cntctfrm_required_phone_field" value="1" <?php if ( $cntctfrm_options['required_phone_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr valign="top">
										<td><?php _e( "Subject", 'contact-form-pro' ); ?></td>
										<td></td>
										<td>
											<label><input class="subject" type="checkbox" name="cntctfrm_required_subject_field" value="1" <?php if ( $cntctfrm_options['required_subject_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<?php if ( isset( $display_pro_options ) ) { ?>
											<td>
												<label><input class="subject" type="checkbox" name="cntctfrm_visible_subject" value="1" <?php if ( $cntctfrm_options['visible_subject'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Visible", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label><input class="subject" type="checkbox" name="cntctfrm_disabled_subject" value="1" <?php if ( $cntctfrm_options['disabled_subject'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Disabled for editing", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label>
													<span class="cntctfrm_mobile_title"><?php _e( "Field's default value", 'contact-form-pro' ); ?></span>
													<input class="subject" type="text" maxlength="250" name="cntctfrm_default_subject" value="<?php echo $cntctfrm_options['default_subject']; ?>" />
												</label>
											</td>
										<?php } else { ?>
											<td></td>
											<td></td>
											<td></td>
										<?php } ?>
									</tr>
									<tr valign="top">
										<td><?php _e( "Message", 'contact-form-pro' ); ?></td>
										<td></td>
										<td>
											<label><input class="message" type="checkbox" id="cntctfrm_required_message_field" name="cntctfrm_required_message_field" value="1" <?php if ( $cntctfrm_options['required_message_field'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Required", 'contact-form-pro' ); ?></span></label>
										</td>
										<?php if ( isset( $display_pro_options ) ) { ?>
											<td>
												<label><input class="message" type="checkbox" name="cntctfrm_visible_message" value="1" <?php if ( $cntctfrm_options['visible_message'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Visible", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label><input class="message" type="checkbox" name="cntctfrm_disabled_message" value="1" <?php if ( $cntctfrm_options['disabled_message'] == '1' ) echo 'checked="checked" '; ?>/>
												<span class="cntctfrm_mobile_title"><?php _e( "Disabled for editing", 'contact-form-pro' ); ?></span></label>
											</td>
											<td>
												<label>
													<span class="cntctfrm_mobile_title"><?php _e( "Field's default value", 'contact-form-pro' ); ?></span>
													<input class="message" type="text" maxlength="250" name="cntctfrm_default_message" value="<?php echo $cntctfrm_options['default_message']; ?>" />
												</label>
											</td>
										<?php } else { ?>
											<td></td>
											<td></td>
											<td></td>
										<?php } ?>
									</tr>
									<tr valign="top" <?php if ( $cntctfrm_options['attachment'] != '1' ) echo 'class="cntctfrm_disabled_row" '; ?>>
										<td>
											<?php _e( "Attachment block", 'contact-form-pro' ); ?>
											<div class="bws_help_box dashicons dashicons-editor-help">
												<div class="bws_hidden_help_text" style="min-width: 200px;"><?php echo __( "Users can attach the following file formats", 'contact-form-pro' ) . ": html, txt, css, gif, png, jpeg, jpg, tiff, bmp, ai, eps, ps, csv, rtf, pdf, doc, docx, xls, xlsx, zip, rar, wav, mp3, ppt, aar, sce"; ?></div>
											</div>
										</td>
										<td>
											<label><input class="cntctfrm_checkbox_disabled_row" type="checkbox" name="cntctfrm_attachment" value="1" <?php if ( $cntctfrm_options['attachment'] == '1' ) echo 'checked="checked" '; ?>/>
											<span class="cntctfrm_mobile_title"><?php _e( "Used", 'contact-form-pro' ); ?></span></label>
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>
							<br />
							<table class="form-table" style="width:auto;">
								<tr valign="top">
									<th scope="row"><?php _e( "Add to the form", 'contact-form-pro' ); ?></th>
									<td colspan="3"><fieldset>
										<div style="clear: both;">
											<label><input type="checkbox" name="cntctfrm_attachment_explanations" value="1" <?php if ( $cntctfrm_options['attachment_explanations'] == '1' && $cntctfrm_options['attachment'] == '1' ) echo 'checked="checked" '; ?>/> <?php _e( "Tips below the Attachment", 'contact-form-pro' ); ?></label>
											<?php echo bws_add_help_box( '<img src="' . plugins_url( 'images/tooltip_attachment_tips.png', __FILE__ ) . '" />', 'bws-hide-for-mobile bws-auto-width' ); ?>
										</div>
										<div>
											<label><input type="checkbox" id="cntctfrm_send_copy" name="cntctfrm_send_copy" value="1" <?php if ( $cntctfrm_options['send_copy'] == '1' ) echo 'checked="checked" '; ?>/> <?php _e( "'Send me a copy' block", 'contact-form-pro' ); ?></label>
											<?php echo bws_add_help_box( '<img src="' . plugins_url( 'images/tooltip_sendme_block.png', __FILE__ ) . '" />', 'bws-hide-for-mobile bws-auto-width' ); ?>
										</div>
										<?php if ( isset( $display_pro_options ) ) { ?>
											<div>
												<label><input type="checkbox" id="cntctfrm_display_privacy_check" name="cntctfrm_display_privacy_check" value="1" <?php if ( $cntctfrm_options['display_privacy_check'] == '1' ) echo 'checked="checked" '; ?>/> <?php _e( "Agreement checkbox", 'contact-form-pro' ); ?></label> <span class="bws_info">(<?php _e( "Required checkbox for submitting the form", 'contact-form-pro' ); ?>)</span><br />
											</div>
											<div>
												<label><input type="checkbox" id="cntctfrm_display_optional_check" name="cntctfrm_display_optional_check" value="1" <?php if ( $cntctfrm_options['display_optional_check'] == '1' ) echo 'checked="checked" '; ?>/> <?php _e( "Optional checkbox", 'contact-form-pro' ); ?></label> <span class="bws_info">(<?php _e( "Optional checkbox, the results of which will be displayed in email", 'contact-form-pro' ); ?>)</span><br />
											</div>
										<?php } ?>
										<div style="clear: both;">
											<?php if ( array_key_exists( 'subscriber/subscriber.php', $all_plugins ) || array_key_exists( 'subscriber-pro/subscriber-pro.php', $all_plugins ) ) {
												if ( array_key_exists( 'subscriber', $cntctfrm_related_plugins ) ) {
													if ( ! $contact_form_multi_active ) {
														$display_subscriber = ! empty( $cntctfrm_related_plugins['subscriber']['options']['contact_form'] ) ? true : false;
													} else {
														$display_subscriber = ! empty( $cntctfrm_options['display_subscribe'] ) ? true : false;
													}
													if ( ! $contact_form_multi_active || ! empty( $cntctfrm_related_plugins['subscriber']['options']['contact_form'] ) ) { ?>
														<label><input type="checkbox" name="cntctfrm_display_subscriber" value="1" <?php if ( $display_subscriber ) echo 'checked="checked"'; ?> /> Subscriber by BestWebSoft</label>
													<?php } else { ?>
														<label><input type="checkbox" name="cntctfrm_display_subscriber" value="1" disabled="disabled" <?php if ( $display_subscriber ) echo 'checked="checked"'; ?> /> Subscriber by BestWebSoft</label>
														<span class="bws_info">&nbsp;(<?php _e( 'Please activate the appropriate option on', 'contact-form-pro' ) ?>&nbsp;
															<?php printf( '<a href="%s" target="_blank"> Subscriber %s</a>&nbsp;)',
																network_admin_url( '/admin.php?page=' . $cntctfrm_related_plugins['subscriber']['settings_page'] ),
																__( 'settings page', 'contact-form-pro' ) ); ?>
														</span>
													<?php }
												} else { ?>
													<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_subscriber" value="1" <?php if ( isset( $cntctfrm_options['display_subscribe'] ) && 1 == $cntctfrm_options['display_subscribe'] ) echo 'checked="checked"'; ?> /> Subscriber by BestWebSoft</label>
													<span class="bws_info">
														<?php if ( ! is_multisite() ) {
															printf( '<a href="%s" target="_blank"> %s Subscriber</a>', admin_url( 'plugins.php' ), __( 'Activate', 'contact-form-pro' ) );
														} else {
															printf( '<a href="%s" target="_blank"> %s Subscriber</a>', network_admin_url( 'plugins.php' ), __( 'Activate for network', 'contact-form-pro' ) );
														} ?>
													</span>
												<?php }
											} else { ?>
												<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_subscriber" value="1" />	Subscriber by BestWebSoft</label>
												<span class="bws_info">
													<?php printf( '<a href="https://bestwebsoft.com/products/wordpress/plugins/subscriber/?k=93a2f4d12565a35d9aed24ddf9f2d013&amp;pn=72&amp;v=%s&amp;wp_v=%s">%s Subscriber</a>', $cntctfrm_plugin_info["Version"], $wp_version, __( 'Download', 'contact-form-pro' ) ); ?>
												</span>
											<?php } ?>
										</div>
										<div style="clear: both;">
											<?php if ( array_key_exists( 'captcha/captcha.php', $all_plugins ) || array_key_exists( 'captcha-plus/captcha-plus.php', $all_plugins ) || array_key_exists( 'captcha-pro/captcha_pro.php', $all_plugins ) ) {
												if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) ) {
													$captcha_enabled = ! empty( $cntctfrm_related_plugins['captcha']['enabled'] ) ? true : false;

													if ( ! $contact_form_multi_active ) {
														$display_captcha = $captcha_enabled;
													} else {
														$display_captcha = ! empty( $cntctfrm_options['display_captcha'] ) ? true : false;
													}

													if ( ! $contact_form_multi_active ) { ?>
														<label><input type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( $display_captcha ) echo 'checked="checked"'; ?> /> Captcha by BestWebSoft </label>
													<?php } else {
														if ( $captcha_enabled ) { ?>
															<label><input type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( $display_captcha ) echo 'checked="checked"'; ?> /> Captcha by BestWebSoft </label>
														<?php } else { ?>
															<label>
																<input type="checkbox" name="cntctfrm_display_captcha" value="1" disabled="disabled" <?php if ( $display_captcha ) echo 'checked="checked"'; ?> /> Captcha by BestWebSoft
																<span class="bws_info">&nbsp;(<?php _e( 'Please activate the appropriate option on', 'contact-form-pro' ) ?>&nbsp;
																	<?php printf( '<a href="%s" target="_blank"> Captcha %s</a>&nbsp;)',
																	self_admin_url( '/admin.php?page=' . $cntctfrm_related_plugins['captcha']['settings_page'] ),
																	__( 'settings page', 'contact-form-pro' ) ); ?>
																</span>
															</label>
														<?php }
													}
												} else { ?>
													<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" /> Captcha by BestWebSoft</label>
													<span class="bws_info">
														<?php printf( '<a href="%s" target="_blank">%s Captcha</a>', self_admin_url( 'plugins.php' ), __( 'Activate', 'contact-form-pro' ) ); ?>
													</span>
												<?php }
											} else { ?>
												<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" /> Captcha by BestWebSoft</label>
												<span class="bws_info">
													<?php printf( '<a href="https://bestwebsoft.com/products/wordpress/plugins/captcha/?k=a4a758ce3ab075b3b6b11a7392aa4cb0&amp;pn=3&amp;v=%s&amp;wp_v=%s">%s Captcha</a>', $cntctfrm_plugin_info["Version"], $wp_version, __( 'Download', 'contact-form-pro' ) ) ?>
												</span>
											<?php } ?>
										</div>
										<div style="clear: both;">
											<?php if ( array_key_exists( 'google-captcha/google-captcha.php', $all_plugins ) || array_key_exists( 'google-captcha-pro/google-captcha-pro.php', $all_plugins ) ) {
												if ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) ) {
													if ( ! $contact_form_multi_active ) {
														$display_google_captcha = ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] ) ? true : false;
													} else {
														$display_google_captcha = ! empty( $cntctfrm_options['display_google_captcha'] ) ? true : false;
													}

													if ( ! $contact_form_multi_active || ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] ) ) { ?>
														<label><input type="checkbox" name="cntctfrm_display_google_captcha" value="1" <?php if ( $display_google_captcha ) echo 'checked="checked"'; ?> /> Google Captcha (reCaptcha) by BestWebSoft</label>
													<?php } else { ?>
														<label>
															<input type="checkbox" name="cntctfrm_display_google_captcha" value="1" disabled="disabled" <?php if ( $display_google_captcha ) echo 'checked="checked"'; ?> /> Google Captcha (reCaptcha) by BestWebSoft
															<span class="bws_info">&nbsp;(<?php _e( 'Please activate the appropriate option on', 'contact-form-pro' ) ?>&nbsp;
																<?php printf( '<a href="%s" target="_blank"> Google Captcha %s</a>&nbsp;)',
																self_admin_url( '/admin.php?page=' . $cntctfrm_related_plugins['google-captcha']['settings_page'] ),
																__( 'settings page', 'contact-form-pro' ) ); ?>
															</span>
														</label>
													<?php }
												} else { ?>
													<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_google_captcha" value="1" /> Google Captcha (reCaptcha) by BestWebSoft</label>
													<span class="bws_info">
														<?php printf( '<a href="%s" target="_blank">%s Google Captcha</a>', self_admin_url( 'plugins.php' ), __( 'Activate', 'contact-form-pro' ) ); ?>
													</span>
												<?php }
											} else { ?>
												<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_google_captcha" value="1" /> Google Captcha (reCaptcha) by BestWebSoft</label> <span class="bws_info">
													<?php printf( '<a href="https://bestwebsoft.com/products/wordpress/plugins/google-captcha/?k=c3e9050155d0beda620fe707097cceb9&amp;pn=3&amp;v=%s&amp;wp_v=%s">%s Google Captcha</a>', $cntctfrm_plugin_info["Version"], $wp_version, __( 'Download', 'contact-form-pro' ) ) ?>
													</span>
											<?php } ?>
										</div>
									</fieldset></td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Delete an attachment file from the server after the email is sent", 'contact-form-pro' ); ?> </th>
									<td colspan="2">
										<input type="checkbox" id="cntctfrm_delete_attached_file" name="cntctfrm_delete_attached_file" value="1" <?php if ( $cntctfrm_options['delete_attached_file'] == '1' ) echo 'checked="checked" '; ?>/>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Email in HTML format sending", 'contact-form-pro' ); ?></th>
									<td colspan="2"><input type="checkbox" name="cntctfrm_html_email" value="1" <?php if ( $cntctfrm_options['html_email'] == '1' ) echo 'checked="checked" '; ?>/></td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Display additional info in the email", 'contact-form-pro' ); ?></th>
									<td style="width:15px;" class="cntctfrm_td_top_align">
										<input type="checkbox" id="cntctfrm_display_add_info" name="cntctfrm_display_add_info" value="1" <?php if ( $cntctfrm_options['display_add_info'] == '1') echo 'checked="checked" '; ?>/>
									</td>
									<td class="cntctfrm_display_add_info_block" <?php if ( $cntctfrm_options['display_add_info'] == '0' ) echo 'style="display:none"'; ?>>
										<fieldset>
											<label><input type="checkbox" id="cntctfrm_display_sent_from" name="cntctfrm_display_sent_from" value="1" <?php if ( $cntctfrm_options['display_sent_from'] == '1') echo 'checked="checked" '; ?>/> <?php _e( "Sent from (IP address)", 'contact-form-pro' ); ?></label> <span class="bws_info"><?php _e( "Example: Sent from (IP address):	127.0.0.1", 'contact-form-pro' ); ?></span><br />
											<label><input type="checkbox" id="cntctfrm_display_date_time" name="cntctfrm_display_date_time" value="1" <?php if ( $cntctfrm_options['display_date_time'] == '1') echo 'checked="checked" '; ?>/> <?php _e( "Date/Time", 'contact-form-pro' ); ?></label> <span class="bws_info"><?php _e( "Example: Date/Time:	August 19, 2013 8:50 pm", 'contact-form-pro' ); ?></span><br />
											<label><input type="checkbox" id="cntctfrm_display_coming_from" name="cntctfrm_display_coming_from" value="1" <?php if ( $cntctfrm_options['display_coming_from'] == '1') echo 'checked="checked" '; ?>/> <?php _e( "Sent from (referer)", 'contact-form-pro' ); ?></label> <span class="bws_info"><?php _e( "Example: Sent from (referer):	https://bestwebsoft.com/contacts/contact-us/", 'contact-form-pro' ); ?></span><br />
											<label><input type="checkbox" id="cntctfrm_display_user_agent" name="cntctfrm_display_user_agent" value="1" <?php if ( $cntctfrm_options['display_user_agent'] == '1') echo 'checked="checked" '; ?>/> <?php _e( "Using (user agent)", 'contact-form-pro' ); ?></label> <span class="bws_info"><?php _e( "Example: Using (user agent):	Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36", 'contact-form-pro' ); ?></span>
										</fieldset>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Language settings for the field names in the form", 'contact-form-pro' ); ?></th>
									<td colspan="2">
										<select name="cntctfrm_languages" id="cntctfrm_languages" style="max-width: 300px">
											<?php foreach ( $cntctfrm_lang_codes as $key => $val ) {
												if ( ! empty( $cntctfrm_options['language'] ) && in_array( $key, $cntctfrm_options['language'] ) )
													continue;
												echo '<option value="' . esc_attr( $key ) . '"> ' . esc_html ( $val ) . '</option>';
											} ?>
										</select>
										<input type="submit" class="button-secondary" name="cntctfrm_add_language_button" id="cntctfrm_add_language_button" value="<?php _e( 'Add a language', 'contact-form-pro' ); ?>" />
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Change the labels of the contact form fields and error messages", 'contact-form-pro' ); ?></th>
									<td style="width:15px;" class="cntctfrm_td_top_align">
										<input type="checkbox" id="cntctfrm_change_label" name="cntctfrm_change_label" value="1" <?php if ( $cntctfrm_options['change_label'] == '1') echo 'checked="checked" '; ?>/>
									</td>
									<td class="cntctfrm_change_label_block" <?php if ( '0' == $cntctfrm_options['change_label'] ) echo 'style="display:none"'; ?>>
										<div class="cntctfrm_label_language_tab <?php echo ! isset( $_POST['cntctfrm_change_tab'] ) || 'default' == $_POST['cntctfrm_change_tab'] ? 'cntctfrm_active' : ''; ?>" id="cntctfrm_label_default"><?php _e( 'Default', 'contact-form-pro' ); ?><noscript><input type="submit" class="cntctfrm_change_tab" value="default" name="cntctfrm_change_tab"></noscript></div>
										<?php if ( ! empty( $cntctfrm_options['language'] ) ) {
											foreach ( $cntctfrm_options['language'] as $val ) {
												$active_tab_class = isset( $_POST["cntctfrm_change_tab"] ) && $val == $_POST["cntctfrm_change_tab"] ? "cntctfrm_active" : "";
												echo '<div class="cntctfrm_label_language_tab ' . $active_tab_class . '" id="cntctfrm_label_' . $val . '">' . $cntctfrm_lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span><noscript><input type="submit" class="cntctfrm_change_tab" value="' . $val . '" name="cntctfrm_change_tab"><span class="cntctfrm_del_button_wrap"><input type="submit" class="cntctfrm_delete_button" value="' . $val . '" name="cntctfrm_delete_button"></span></noscript></div>';
											}
										} ?>
										<div class="cntctfrm_clear"></div>
										<div class="cntctfrm_language_tab cntctfrm_tab_default <?php echo ! isset( $_POST['cntctfrm_change_tab'] ) || 'default' == $_POST['cntctfrm_change_tab'] ? '' : 'hidden' ?>" style="padding: 1px 3px;">
											<div class="cntctfrm_language_tab_block_mini" style="display:none;"><?php _e( "click to expand/hide the list", 'contact-form-pro' ); ?></div>
											<div class="cntctfrm_language_tab_block">
												<input type="text" maxlength="250" name="cntctfrm_department_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['department_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Department selectbox", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_name_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['name_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Name", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_location_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['location_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Location", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_address_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['address_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Address", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_email_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['email_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Email Address", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_phone_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['phone_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Phone number", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_subject_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['subject_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Subject", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_message_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['message_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Message", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Attachment", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_send_copy_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['send_copy_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Send me a copy", 'contact-form-pro' ); ?></span><br />
												<textarea name="cntctfrm_privacy_check_label[default]"><?php echo stripcslashes( $cntctfrm_options['privacy_check_label']['default'] ); ?></textarea> <span class="bws_info"><?php _e( "Label for Agreement checkbox", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_optional_check_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['optional_check_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Label for Optional checkbox", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_submit_label[default]" value="<?php echo stripcslashes( $cntctfrm_options['submit_label']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Submit", 'contact-form-pro' ); ?></span><br />
											</div>
											<div class="cntctfrm_language_tab_block">
												<input type="text" maxlength="250" name="cntctfrm_department_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['department_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Department field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_name_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['name_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Name field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_location_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['location_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Location field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_address_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['address_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Address field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_email_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['email_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Email field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_phone_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['phone_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Phone Number field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_subject_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['subject_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Subject field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_message_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['message_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Message field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message about the file type for the Attachment field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_upload_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_upload_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Attachment field regarding file upload to the server", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_move_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_move_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_size_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_size_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message if the file size limit for the Attachment field is exceeded", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_privacy_check_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['privacy_check_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for agreement checkbox", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_captcha_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['captcha_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Captcha field", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_form_error[default]" value="<?php echo stripcslashes( $cntctfrm_options['form_error']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the whole form", 'contact-form-pro' ); ?></span><br />
											</div>
											<div class="cntctfrm_language_tab_block">
												<input type="text" maxlength="250" name="cntctfrm_name_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['name_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for the Name field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_address_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['address_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Address field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_email_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['email_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Email Address field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_phone_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['phone_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Phone number field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_subject_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['subject_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Subject field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_message_help[default]" value="<?php echo stripcslashes( $cntctfrm_options['message_help']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Message field", 'contact-form-pro' ); ?>:</span><br />
											</div>
											<div class="cntctfrm_language_tab_block">
												<input type="text" maxlength="250" name="cntctfrm_department_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['department_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for department select", 'contact-form-pro' ); ?></span><br />
												<input type="text" maxlength="250" name="cntctfrm_name_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['name_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Name field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_location_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['location_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Location field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_address_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['address_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Address field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_email_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['email_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Email Address field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_phone_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['phone_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Phone number field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_subject_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['subject_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Subject field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_message_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['message_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Message field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_attachment_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['attachment_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Attachment field", 'contact-form-pro' ); ?>:</span><br />
												<input type="text" maxlength="250" name="cntctfrm_captcha_tooltip[default]" value="<?php echo stripcslashes( $cntctfrm_options['captcha_tooltip']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Captcha field", 'contact-form-pro' ); ?>:</span><br />
											</div>
											<?php if ( ! $contact_form_multi_active ) { ?>
												<span class="bws_info cntctfrm_shortcode_for_language"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
											<?php } else { ?>
												<span class="bws_info cntctfrm_shortcode_for_language" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
											<?php } ?>
										</div>
										<?php if ( ! empty( $cntctfrm_options['language'] ) ) {
											foreach ( $cntctfrm_options['language'] as $val ) {
												if ( ( isset( $_POST['cntctfrm_change_tab'] ) && $val != $_POST['cntctfrm_change_tab'] ) || ! isset( $_POST['cntctfrm_change_tab'] ) )
													$labels_table_class = 'hidden';
												else
													$labels_table_class = ''; ?>
												<div class="cntctfrm_language_tab <?php echo $labels_table_class; ?> cntctfrm_tab_<?php echo $val; ?>">
													<div class="cntctfrm_language_tab_block_mini" style="display:none;"><?php _e( "click to expand/hide the list", 'contact-form-pro' ); ?></div>
													<div class="cntctfrm_language_tab_block">
														<input type="text" maxlength="250" name="cntctfrm_department_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['department_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['department_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Department selectbox", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_name_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['name_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['name_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Name", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_location_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['location_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['location_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Location", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_address_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['address_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['address_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Address", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_email_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['email_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['email_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Email Address", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_phone_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['phone_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['phone_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Phone number", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_subject_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['subject_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['subject_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Subject", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_message_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['message_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['message_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Message", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Attachment", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_send_copy_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['send_copy_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['send_copy_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Send me a copy", 'contact-form-pro' ); ?></span><br />
														<textarea name="cntctfrm_privacy_check_label[<?php echo $val; ?>]"><?php if ( isset( $cntctfrm_options['privacy_check_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['privacy_check_label'][$val] ); ?></textarea> <span class="bws_info"><?php _e( "Label for Agreement checkbox", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_optional_check_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['optional_check_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['optional_check_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Label for Optional checkbox", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_submit_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['submit_label'][$val] ) ) echo stripcslashes( $cntctfrm_options['submit_label'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Submit", 'contact-form-pro' ); ?></span><br />
													</div>
													<div class="cntctfrm_language_tab_block">
														<input type="text" maxlength="250" name="cntctfrm_department_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['department_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['department_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Department field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_name_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['name_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['name_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Name field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_location_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['location_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['location_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Location field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_address_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['address_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['address_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Address field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_email_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['email_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['email_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Email field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_phone_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['phone_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['phone_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Phone Number field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_subject_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['subject_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['subject_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Subject field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_message_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['message_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['message_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Message field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message about the file type for the Attachment field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_upload_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_upload_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_upload_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Attachment field regarding file upload to the server", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_move_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_move_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_move_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_size_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_size_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_size_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message if the file size limit for the Attachment field is exceeded", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_privacy_check_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['privacy_check_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['privacy_check_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for agreement checkbox", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_captcha_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['captcha_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['captcha_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the Captcha field", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_form_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['form_error'][$val] ) ) echo stripcslashes( $cntctfrm_options['form_error'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Error message for the whole form", 'contact-form-pro' ); ?></span><br />
													</div>
													<div class="cntctfrm_language_tab_block">
														<input type="text" maxlength="250" name="cntctfrm_name_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['name_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['name_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for the Name field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_address_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['address_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['address_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Address field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_email_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['email_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['email_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Email Address field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_phone_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['phone_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['phone_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Phone number field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_subject_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['subject_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['subject_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Subject field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_message_help[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['message_help'][$val] ) ) echo stripcslashes( $cntctfrm_options['message_help'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Placeholder for Message field", 'contact-form-pro' ); ?>:</span><br />
													</div>
													<div class="cntctfrm_language_tab_block">
														<input type="text" maxlength="250" name="cntctfrm_department_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['department_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['department_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for department select", 'contact-form-pro' ); ?></span><br />
														<input type="text" maxlength="250" name="cntctfrm_name_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['name_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['name_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Name field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_location_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['location_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['location_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Location field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_address_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['address_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['address_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Address field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_email_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['email_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['email_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Email Address field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_phone_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['phone_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['phone_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Phone number field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_subject_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['subject_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['subject_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Subject field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_message_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['message_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['message_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Message field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_attachment_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['attachment_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['attachment_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Attachment field", 'contact-form-pro' ); ?>:</span><br />
														<input type="text" maxlength="250" name="cntctfrm_captcha_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['captcha_tooltip'][$val] ) ) echo stripcslashes( $cntctfrm_options['captcha_tooltip'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Tooltip for Captcha field", 'contact-form-pro' ); ?>:</span><br />
													</div>
													<?php if ( ! $contact_form_multi_active ) { ?>
														<span class="bws_info cntctfrm_shortcode_for_language" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
													<?php } else { ?>
														<span class="bws_info cntctfrm_shortcode_for_language" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val . " id=" . $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
													<?php } ?>
												</div>
											<?php }
										} ?>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( 'Use the changed names of the contact form fields in the email', 'contact-form-pro' ); ?></th>
									<td colspan="2">
										<input type="checkbox" name="cntctfrm_change_label_in_email" value="1" <?php if ( $cntctfrm_options['change_label_in_email'] == '1' ) echo 'checked="checked" '; ?>/>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( "Action after email is sent", 'contact-form-pro' ); ?></th>
									<td colspan="2" class="cntctfrm_action_after_send_block">
										<label><input id="cntctfrm_action_after_send" type="radio" name="cntctfrm_action_after_send" value="1" <?php if ( $cntctfrm_options['action_after_send'] == '1' ) echo 'checked="checked" '; ?>/> <?php _e( "Display text", 'contact-form-pro' ); ?></label><br />
										<div class="cntctfrm_label_language_tab <?php echo ! isset( $_POST['cntctfrm_change_tab'] ) || 'default' == $_POST['cntctfrm_change_tab'] ? 'cntctfrm_active' : ''; ?>" id="cntctfrm_text_default"><?php _e( 'Default', 'contact-form-pro' ); ?>
											<noscript>
												<input type="submit" class="cntctfrm_change_tab" value="default" name="cntctfrm_change_tab">
											</noscript>
										</div>
										<?php if ( ! empty( $cntctfrm_options['language'] ) ) {
											foreach ( $cntctfrm_options['language'] as $val ) {
												$active_tab_class = isset( $_POST["cntctfrm_change_tab"] ) && $val == $_POST["cntctfrm_change_tab"] ? "cntctfrm_active" : "";
												echo '<div class="cntctfrm_label_language_tab ' . $active_tab_class . '" id="cntctfrm_text_' . $val . '">' . $cntctfrm_lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span><noscript><input type="submit" class="cntctfrm_change_tab" value="' . $val . '" name="cntctfrm_change_tab"><span class="cntctfrm_del_button_wrap"><input type="submit" class="cntctfrm_delete_button" value="' . $val . '" name="cntctfrm_delete_button"></span></noscript></div>';
											}
										} ?>
										<div class="cntctfrm_clear"></div>
										<div class="cntctfrm_language_tab cntctfrm_tab_default <? echo ! isset( $_POST['cntctfrm_change_tab'] ) || 'default' == $_POST['cntctfrm_change_tab'] ? '' : 'hidden' ?>" style="padding: 5px 10px 5px 5px;">
											<input style="margin: 5px 10px 5px 5px;" type="text" maxlength="250" name="cntctfrm_thank_text[default]" value="<?php echo stripcslashes( $cntctfrm_options['thank_text']['default'] ); ?>" /> <span class="bws_info"><?php _e( "Text", 'contact-form-pro' ); ?></span><br />
											<?php if ( ! $contact_form_multi_active ) { ?>
												<span class="bws_info cntctfrm_shortcode_for_language"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
											<?php } else { ?>
												<span class="bws_info cntctfrm_shortcode_for_language"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
											<?php } ?>
										</div>
										<?php if ( ! empty( $cntctfrm_options['language'] ) ) {
											foreach ( $cntctfrm_options['language'] as $val ) {
												if ( ( isset( $_POST['cntctfrm_change_tab'] ) && $val != $_POST['cntctfrm_change_tab'] ) || ! isset( $_POST['cntctfrm_change_tab'] ) )
													$labels_table_class = 'hidden';
												else
													$labels_table_class = ''; ?>
												<div class="cntctfrm_language_tab <?php echo $labels_table_class; ?> cntctfrm_tab_<?php echo $val; ?>" style="padding: 5px 10px 5px 5px;">
													<input style="margin: 5px 10px 5px 5px;"  type="text" maxlength="250" name="cntctfrm_thank_text[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['thank_text'][$val] ) ) echo stripcslashes( $cntctfrm_options['thank_text'][$val] ); ?>" /> <span class="bws_info"><?php _e( "Text", 'contact-form-pro' ); ?></span><br />
													<?php if ( ! $contact_form_multi_active ) { ?>
														<span class="bws_info cntctfrm_shortcode_for_language"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
													<?php } else { ?>
														<span class="bws_info cntctfrm_shortcode_for_language"><?php _e( "Use shortcode", 'contact-form-pro' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val . " id=" . $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact-form-pro' ); ?></span>
													<?php } ?>
												</div>
											<?php }
										} ?>
										<div id="cntctfrm_before"></div>
										<br />
										<label><input type="radio" id="cntctfrm_action_after_send" name="cntctfrm_action_after_send" value="0" <?php if ( $cntctfrm_options['action_after_send'] == '0' ) echo 'checked="checked" '; ?> /> <?php _e( "Redirect to the page", 'contact-form-pro' ); ?></label><br />
										<input type="text" maxlength="250" name="cntctfrm_redirect_url" value="<?php echo $cntctfrm_options['redirect_url']; ?>" /> <span class="bws_info"><?php _e( "URL", 'contact-form-pro' ); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:200px;"><?php _e( 'Auto Response', 'contact-form-pro' ); ?></th>
									<td style="width:15px;">
										<input type="checkbox" id="cntctfrm_auto_response"  name="cntctfrm_auto_response" value="1" <?php if ( $cntctfrm_options['auto_response'] == '1' ) echo 'checked="checked" '; ?>/>
									</td>
									<td class="cntctfrm_auto_response_block" <?php if ( $cntctfrm_options['auto_response'] == '0' ) echo 'style="display:none"'; ?>>
										<textarea name="cntctfrm_auto_response_message"><?php echo stripcslashes( $cntctfrm_options['auto_response_message'] ); ?></textarea><br/>
										<span class="bws_info"><?php _e( "You can use %%NAME%% to display data from the email field and %%MESSAGE%% to display data from the Message field, as well as %%SITENAME%% to display blog name.", 'contact-form-pro' ); ?></span>
									</td>
								</tr>
							</table>
						</div>
						<!-- End of 'Additional settings' block  -->
						<input type="hidden" name="cntctfrm_form_submit" value="submit" />
						<p class="submit">
							<input id="bws-submit-button" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'contact-form-pro' ); ?>" />
						</p>
						<?php wp_nonce_field( plugin_basename(__FILE__), 'cntctfrm_nonce_name' ); ?>
					</form>
					<?php bws_form_restore_default_settings( $plugin_basename );
					if ( ! isset( $_GET['action'] ) ) {
						bws_check_pro_license_form( 'contact-form-pro/contact_form_pro.php' );
						if ( 'pro' == $contact_form_multi_active ) {
							global $bstwbsftwppdtplgns_options;
							$license_key = ( isset( $bstwbsftwppdtplgns_options[ 'contact-form-multi-pro/contact-form-multi-pro.php' ] ) ) ? $bstwbsftwppdtplgns_options[ 'contact-form-multi-pro/contact-form-multi-pro.php' ] : ''; ?>
							<div class="cntctfrm_clear"></div>
							<form method="post" action="admin.php?page=contact_form_pro.php">
								<p><?php _e( 'Check license key for plugin Contact Form Multi Pro', 'contact-form-pro' ); ?>:</p>
								<p>
									<input type="text" maxlength="20" name="bws_license_key" value="<?php echo $license_key ?>" />
									<input type="hidden" name="bws_license_submit" value="submit" />
									<input type="submit" class="button-secondary" value="<?php _e( 'Check license key', 'contact-form-pro' ); ?>" />
									<?php wp_nonce_field( 'contact-form-multi-pro/contact-form-multi-pro.php', 'bws_license_nonce_name' ); ?>
								</p>
							</form>
						<?php }
					}
				}
			} elseif ( 'appearance' == $_GET['action'] ) {
				if ( isset( $_REQUEST['bws_restore_default'] ) && check_admin_referer( $plugin_basename, 'bws_settings_nonce_name' ) ) {
					bws_form_restore_default_confirm( $plugin_basename );
				} else {
					if ( isset( $display_pro_options ) ) { ?>
						<style type="text/css" id="cntctfrm_onload_styles">
							<?php if ( '' != $cntctfrm_options['label_color'] ) { ?>
							.cntctfrm_contact_form label {
								color: <?php echo $cntctfrm_options['label_color']; ?>;
							}
							.cptch_block, .cptchpr_block {
								color: <?php echo $cntctfrm_options['label_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['error_color'] ) { ?>
							.cntctfrm_error_text {
								color: <?php echo $cntctfrm_options['error_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['error_input_color'] ) { ?>
							.cntctfrm_contact_form input.text.cntctfrm_error, .cntctfrm_contact_form textarea.cntctfrm_error, .cntctfrm_error {
								background: <?php echo $cntctfrm_options['error_input_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['error_input_border_color'] ) { ?>
							.cntctfrm_contact_form input.text.cntctfrm_error, .cntctfrm_contact_form textarea.cntctfrm_error, .cntctfrm_error {
								border-color: <?php echo $cntctfrm_options['error_input_border_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['input_background'] ) { ?>
							.cntctfrm_contact_form input.text, .cntctfrm_contact_form textarea, .cntctfrm_contact_form select {
								background-color: <?php echo $cntctfrm_options['input_background']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['input_color'] ) { ?>
							.cntctfrm_contact_form input.text, .cntctfrm_contact_form textarea, .cntctfrm_contact_form select {
								color: <?php echo $cntctfrm_options['input_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['input_placeholder_color'] ) { ?>
							.cntctfrm_contact_form input::-webkit-input-placeholder, .cntctfrm_contact_form textarea::-webkit-input-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_color']; ?>;
							}
							.cntctfrm_contact_form input::-moz-placeholder, .cntctfrm_contact_form textarea::-moz-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_color']; ?>;
							}
							.cntctfrm_contact_form input:-ms-input-placeholder, .cntctfrm_contact_form textarea:-ms-input-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_color']; ?>;
							}
							.cntctfrm_contact_form input:-moz-placeholder, .cntctfrm_contact_form textarea:-moz-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['input_placeholder_error_color'] ) { ?>
							.cntctfrm_contact_form input.cntctfrm_error::-webkit-input-placeholder, .cntctfrm_contact_form textarea.cntctfrm_error::-webkit-input-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_error_color']; ?>;
							}
							.cntctfrm_contact_form input.cntctfrm_error::-moz-placeholder, .cntctfrm_contact_form textarea.cntctfrm_error::-moz-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_error_color']; ?>;
							}
							.cntctfrm_contact_form input.cntctfrm_error:-ms-input-placeholder, .cntctfrm_contact_form textarea.cntctfrm_error:-ms-input-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_error_color']; ?>;
							}
							.cntctfrm_contact_form input.cntctfrm_error:-moz-placeholder, .cntctfrm_contact_form textarea.cntctfrm_error:-moz-placeholder {
								color: <?php echo $cntctfrm_options['input_placeholder_error_color']; ?>;
							}
							<?php }
							if ( '' != $cntctfrm_options['border_input_width'] ) { ?>
							.cntctfrm_contact_form input.text, .cntctfrm_contact_form textarea, .cntctfrm_contact_form select {
								border-width: <?php echo $cntctfrm_options['border_input_width']; ?>px;
							}
							<?php }
							if ( '' != $cntctfrm_options['border_input_color'] ) { ?>
							.cntctfrm_contact_form input.text, .cntctfrm_contact_form textarea, .cntctfrm_contact_form select {
								border-color: <?php echo $cntctfrm_options['border_input_color']; ?>;
							}
							<?php }
							if ( ! empty( $cntctfrm_options['button_width'] ) || '' != $cntctfrm_options['button_backgroud'] || '' != $cntctfrm_options['button_color'] || '' != $cntctfrm_options['border_button_color'] ) { ?>
							.cntctfrm_contact_form .cntctfrm_contact_submit {
								<?php if ( ! empty( $cntctfrm_options['button_width'] ) ) { ?>
									width: <?php echo $cntctfrm_options['button_width']; ?>px;
								<?php }
								if ( '' != $cntctfrm_options['button_backgroud'] ) { ?>
									background: <?php echo $cntctfrm_options['button_backgroud']; ?>;
								<?php }
								if ( '' != $cntctfrm_options['button_color'] ) { ?>
									color: <?php echo $cntctfrm_options['button_color']; ?>;
								<?php }
								if ( '' != $cntctfrm_options['border_button_color'] ) { ?>
									border-color: <?php echo $cntctfrm_options['border_button_color']; ?>;
								<?php } ?>
							}
							<?php } ?>
						</style>
					<?php } ?>
					<noscript>
						<div class="error">
							<p>
								<strong><?php printf( __( "Please enable JavaScript to change '%s', '%s', '%s', '%s' options and for fields sorting.", 'contact-form-pro' ), __( "Form layout", 'contact-form-pro' ), __( "Labels position", 'contact-form-pro' ), __( "Labels align", 'contact-form-pro' ), __( "Submit position", 'contact-form-pro' ), __( "Add tooltips", 'contact-form-pro' ), __( "Style options", 'contact-form-pro' ) ); ?></strong>
							</p>
						</div>
					</noscript>
					<?php /* Appearance (ex Extra settings) tab */
					/* Tooltip display option */
					if ( $cntctfrm_options['tooltip_display_department'] == 1 || $cntctfrm_options['tooltip_display_name'] == 1 || $cntctfrm_options['tooltip_display_location'] == 1 ||
					$cntctfrm_options['tooltip_display_address'] == 1 || $cntctfrm_options['tooltip_display_email'] == 1 || $cntctfrm_options['tooltip_display_phone'] == 1 ||
					$cntctfrm_options['tooltip_display_subject'] == 1 || $cntctfrm_options['tooltip_display_message'] == 1 || $cntctfrm_options['tooltip_display_attachment'] == 1 ||
					$cntctfrm_options['tooltip_display_captcha'] == 1 ) {
						$cntctfrm_form_tooltips = 1;
					} else {
						$cntctfrm_form_tooltips = 0;
					} ?>
					<div class="cntctfrm_clear"></div>
					<form id="cntctfrm_settings_form" class="bws_form" method="post" action="admin.php?page=contact_form_pro.php&amp;action=appearance">
						<div id="cntctfrm_appearance_wrap" class="cntctfrm_appearance_<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
							<div id="<?php echo is_rtl() ? 'cntctfrm_right_table' : 'cntctfrm_left_table'; ?>">
								<table class="form-table" style="width:auto;" >
									<tr valign="top">
										<th scope="row"><?php _e( "Form layout", 'contact-form-pro' ); ?></th>
										<td colspan="2">
											<fieldset>
												<input id="cntctfrm_layout_one_column" name="cntctfrm_layout" type="radio" value="1" <?php if ( (int) $cntctfrm_options['layout'] === 1 ) echo 'checked="checked"' ?>>
												<label for="cntctfrm_layout_one_column"><?php _e( 'One column', 'contact-form-pro' ); ?></label>
												<br/>
												<input id="cntctfrm_layout_two_columns" name="cntctfrm_layout" type="radio" value="2" <?php if ( (int) $cntctfrm_options['layout'] === 2 ) echo 'checked="checked"' ?>>
												<label for="cntctfrm_layout_two_columns"><?php _e( 'Two columns', 'contact-form-pro' ); ?></label>
											</fieldset>
										</td>
									</tr>
									<?php if ( isset( $display_pro_options ) ) { ?>
										<tr valign="top">
											<th scope="row"><?php _e( "Form align", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<fieldset>
													<input id="cntctfrm_form_align_left" name="cntctfrm_form_align" type="radio" value="left" <?php if ( $cntctfrm_options['form_align'] === 'left' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_form_align_left"><?php _e( 'Left', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_form_align_center" name="cntctfrm_form_align" type="radio" value="center" <?php if ( $cntctfrm_options['form_align'] === 'center' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_form_align_center"><?php _e( 'Center', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_form_align_right" name="cntctfrm_form_align" type="radio" value="right" <?php if ( $cntctfrm_options['form_align'] === 'right' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_form_align_right"><?php _e( 'Right', 'contact-form-pro' ); ?></label>
												</fieldset>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php _e( "Labels position", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<fieldset>
													<input id="cntctfrm_labels_position_top" name="cntctfrm_labels_position" type="radio" value="top" <?php if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_position_top"><?php _e( 'Top', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_labels_position_left" name="cntctfrm_labels_position" type="radio" value="left" <?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_position_left"><?php _e( 'Left', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_labels_position_right" name="cntctfrm_labels_position" type="radio" value="right" <?php if ( $cntctfrm_options['labels_settings']['position'] == 'right' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_position_right"><?php _e( 'Right', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_labels_position_bottom" name="cntctfrm_labels_position" type="radio" value="bottom" <?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_position_bottom"><?php _e( 'Bottom', 'contact-form-pro' ); ?></label>
												</fieldset>
											</td>
										</tr>
										<tr valign="top" id="cntctfrm_labels_align" class="cntctfrm_labels_align_show">
											<th scope="row"><?php _e( "Labels align", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<fieldset>
													<input id="cntctfrm_labels_align_left" name="cntctfrm_labels_align" type="radio" value="left" <?php if ( $cntctfrm_options['labels_settings']['align'] == 'left' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_align_left"><?php _e( 'Left', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_labels_align_center" name="cntctfrm_labels_align" type="radio" value="center" <?php if ( $cntctfrm_options['labels_settings']['align'] == 'center' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_align_center"><?php _e( 'Center', 'contact-form-pro' ); ?></label>
													<br/>
													<input id="cntctfrm_labels_align_right" name="cntctfrm_labels_align" type="radio" value="right" <?php if ( $cntctfrm_options['labels_settings']['align'] == 'right' ) echo 'checked="checked"' ?>>
													<label for="cntctfrm_labels_align_right"><?php _e( 'Right', 'contact-form-pro' ); ?></label>
												</fieldset>
											</td>
										</tr>
									<?php } ?>
									<tr valign="top">
										<th scope="row"><?php _e( "Submit position", 'contact-form-pro' ); ?></th>
										<td colspan="2">
											<fieldset>
												<input id="cntctfrm_submit_position_left" name="cntctfrm_submit_position" type="radio" value="left" <?php if ( $cntctfrm_options['submit_position'] == 'left' ) echo 'checked="checked"' ?>>
												<label for="cntctfrm_submit_position_left"><?php _e( 'Left', 'contact-form-pro' ); ?></label>
												<br/>
												<input id="cntctfrm_submit_position_right" name="cntctfrm_submit_position" type="radio" value="right" <?php if ( $cntctfrm_options['submit_position'] == 'right' ) echo 'checked="checked"' ?>>
												<label for="cntctfrm_submit_position_right"><?php _e( 'Right', 'contact-form-pro' ); ?></label>
											</fieldset>
										</td>
									</tr>
									<?php if ( isset( $display_pro_options ) ) { ?>
										<tr valign="top">
											<th scope="row" style="width:200px;"><?php _e( "Errors output", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<select name="cntctfrm_error_displaying">
													<option value="labels" <?php if ( $cntctfrm_options['error_displaying'] == 'labels' ) echo 'selected="selected" '; ?>><?php _e( "Display error messages", 'contact-form-pro' ); ?></option>
													<option value="input_colors" <?php if ( $cntctfrm_options['error_displaying'] == 'input_colors' ) echo 'selected="selected" '; ?>><?php _e( "Color of the input field errors.", 'contact-form-pro' ); ?></option>
													<option value="both" <?php if ( $cntctfrm_options['error_displaying'] == 'both' ) echo 'selected="selected" '; ?>><?php _e( "Display error messages & color of the input field errors", 'contact-form-pro' ); ?></option>
												</select>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row" style="width:200px;"><?php _e( "Add placeholder to the input blocks", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<input type="checkbox" name="cntctfrm_placeholder" value="1" <?php if ( 1 == $cntctfrm_options['placeholder'] ) echo 'checked="checked" '; ?>/>
												<div class="bws_help_box dashicons dashicons-editor-help">
													<div class="bws_hidden_help_text" style="min-width: 260px;">
														<?php _e( 'You can change placeholders text in "Additional settings" -> "Change the labels of the contact form fields and error messages"', 'contact-form-pro' ); ?>
													</div>
												</div>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row" style="width:200px;"><?php _e( "Add tooltips", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<?php if ( $cntctfrm_options['select_email'] == 'departments' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_department" value="1" <?php if ( $cntctfrm_options['tooltip_display_department'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Department selectbox", 'contact-form-pro' ); ?></label>
													</div>
												<?php }
												if ( $cntctfrm_options['display_name_field'] == '1' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_name" value="1" <?php if ( $cntctfrm_options['tooltip_display_name'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Name", 'contact-form-pro' ); ?></label>
													</div>
												<?php }
												if ( $cntctfrm_options['display_location_field'] == '1' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_location" value="1" <?php if ( $cntctfrm_options['tooltip_display_location'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Location", 'contact-form-pro' ); ?></label>
													</div>
												<?php }
												if ( $cntctfrm_options['display_address_field'] == '1' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_address" value="1" <?php if ( $cntctfrm_options['tooltip_display_address'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Address", 'contact-form-pro' ); ?></label>
													</div>
												<?php } ?>
												<div>
													<label><input type="checkbox" name="cntctfrm_tooltip_display_email" value="1" <?php if ( $cntctfrm_options['tooltip_display_email'] == '1' ) echo 'checked="checked" '; ?>/>
													<?php _e( "Email address", 'contact-form-pro' ); ?></label>
												</div>
												<?php if ( $cntctfrm_options['display_phone_field'] == '1' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_phone" value="1" <?php if ( $cntctfrm_options['tooltip_display_phone'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Phone Number", 'contact-form-pro' ); ?></label>
													</div>
												<?php } ?>
												<div>
													<label><input type="checkbox" name="cntctfrm_tooltip_display_subject" value="1" <?php if ( $cntctfrm_options['tooltip_display_subject'] == '1' ) echo 'checked="checked" '; ?>/>
													<?php _e( "Subject", 'contact-form-pro' ); ?></label>
												</div>
												<div>
													<label><input type="checkbox" name="cntctfrm_tooltip_display_message" value="1" <?php if ( $cntctfrm_options['tooltip_display_message'] == '1' ) echo 'checked="checked" '; ?>/>
													<?php _e( "Message", 'contact-form-pro' ); ?></label>
												</div>
												<?php if ( $cntctfrm_options['attachment_explanations'] == '1' && $cntctfrm_options['attachment'] == '1' ) { ?>
													<div>
														<label><input type="checkbox" name="cntctfrm_tooltip_display_attachment" value="1" <?php if ( $cntctfrm_options['tooltip_display_attachment'] == '1' ) echo 'checked="checked" '; ?>/>
														<?php _e( "Attachment", 'contact-form-pro' ); ?></label>
													</div>
												<?php }
												if ( array_key_exists( 'captcha/captcha.php', $all_plugins ) || array_key_exists( 'captcha-plus/captcha-plus.php', $all_plugins ) || array_key_exists( 'captcha-pro/captcha_pro.php', $all_plugins ) ) {
													if ( is_plugin_active( 'captcha-pro/captcha_pro.php' ) || is_plugin_active( 'captcha-plus/captcha-plus.php' ) || is_plugin_active( 'captcha/captcha.php' ) ) { ?>
														<div>
															<label><input type="checkbox" name="cntctfrm_tooltip_display_captcha" value="1" <?php if ( 1 == $cntctfrm_options['tooltip_display_captcha'] ) echo 'checked="checked"'; ?> />
															Captcha by BestWebSoft</label>
														</div>
													<?php } else { ?>
														<input disabled="disabled" type="checkbox" name="cntctfrm_tooltip_display_captcha" value="1" <?php if ( 1 == $cntctfrm_options['tooltip_display_captcha'] ) echo 'checked="checked"'; ?> /> <label for="cntctfrm_tooltip_display_captcha">Captcha by BestWebSoft</label> <span class="bws_info"> <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate Captcha', 'captcha' ); ?></a></span>
													<?php }
												} else { ?>
													<input disabled="disabled" type="checkbox" name="cntctfrm_tooltip_display_captcha" value="1" <?php if ( 1 == $cntctfrm_options['tooltip_display_captcha'] ) echo 'checked="checked"'; ?> /> <label for="cntctfrm_tooltip_display_captcha">Captcha by BestWebSoft</label> <span class="bws_info"><a href="https://bestwebsoft.com/products/wordpress/plugins/captcha/?k=a4a758ce3ab075b3b6b11a7392aa4cb0&amp;pn=3&amp;v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>"><?php _e( 'Download Captcha', 'captcha' ); ?></a></span>
												<?php } ?>
											</td>
										</tr>
										<tr valign="top">
											<th colspan="3" scope="row" style="width:200px;"><label><input type="checkbox" id="cntctfrm_style_options" name="cntctfrm_style_options" value="1" <?php if ( $cntctfrm_options['style_options'] == '1' ) echo 'checked="checked" '; ?> /> <?php _e( "Style options", 'contact-form-pro' ); ?></label></th>
										</tr>
										<tr valign="top" class="cntctfrm_style_block" <?php if ( $cntctfrm_options['style_options'] == '0') echo 'style="display:none"'; ?>>
											<th scope="row" style="width:200px;"><?php _e( "Text color", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<div>
													<input type="text" maxlength="7" name="cntctfrm_label_color" value="<?php echo $cntctfrm_options['label_color']; ?>" id="#cntctfrm_contact_form_label" class="cntctfrm_label_color cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( 'Label text color', 'contact-form-pro' ); ?></div>

												</div>
												<div>
													<input maxlength="7" name="cntctfrm_input_placeholder_color" value="<?php echo $cntctfrm_options['input_placeholder_color']; ?>" class="cntctfrm_placeholder_color cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( "Placeholder color", 'contact-form-pro' ); ?></div>
												</div>
											</td>
										</tr>
										<tr valign="top" class="cntctfrm_style_block" <?php if ( $cntctfrm_options['style_options'] == '0') echo 'style="display:none"'; ?>>
											<th scope="row" style="width:200px;"><?php _e( "Errors color", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<div>
													<input  maxlength="7" name="cntctfrm_error_color" data-default-color="#ff0000" value="<?php echo $cntctfrm_options['error_color']; ?>" class="cntctfrm_error_text_color cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( 'Error text color', 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_error_input_color" data-default-color="#ffe7e4" value="<?php echo $cntctfrm_options['error_input_color']; ?>" class="cntctfrm_error_background_color cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( 'Background color of the input field errors', 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_error_input_border_color" data-default-color="#f9bfb8" value="<?php echo $cntctfrm_options['error_input_border_color']; ?>" class="cntctfrm_error_border_color cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( 'Border color of the input field errors', 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_input_placeholder_error_color" value="<?php echo $cntctfrm_options['input_placeholder_error_color']; ?>" class="cntctfrm_error_placeholder cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( "Placeholder color of the input field errors", 'contact-form-pro' ); ?></div>
												</div>
											</td>
										</tr>
										<tr valign="top" class="cntctfrm_style_block" <?php if ( $cntctfrm_options['style_options'] == '0') echo 'style="display:none"'; ?>>
											<th scope="row" style="width:200px;"><?php _e( "Input fields", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<div>
													<input maxlength="7" name="cntctfrm_input_background" value="<?php echo $cntctfrm_options['input_background']; ?>" class="cntctfrm_background_color cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( "Input fields background color", 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_input_color" value="<?php echo $cntctfrm_options['input_color']; ?>" class="cntctfrm_input_color cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( "Text fields color", 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input size="8" type="text" maxlength="3" value="<?php echo $cntctfrm_options["border_input_width"]; ?>" name="cntctfrm_border_input_width" />
													<div class="cntctfrm_label_block"><?php _e( 'Border width in px, numbers only', 'contact-form-pro' ); ?></div><br />
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_border_input_color" value="<?php echo $cntctfrm_options["border_input_color"]; ?>" class="cntctfrm_border_color cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( 'Border color', 'contact-form-pro' ); ?></div>
												</div>
											</td>
										</tr>
										<tr valign="top" class="cntctfrm_style_block" <?php if ( $cntctfrm_options['style_options'] == '0') echo 'style="display:none"'; ?>>
											<th scope="row" style="width:200px;"><?php _e( "Submit button", 'contact-form-pro' ); ?></th>
											<td colspan="2">
												<input size="8" type="text" maxlength="5" value="<?php echo $cntctfrm_options['button_width']; ?>" name="cntctfrm_button_width" />
												<div class="cntctfrm_label_block"><?php _e( 'Width in px, numbers only', 'contact-form-pro' ); ?></div><br />
												<div>
													<input maxlength="7" name="cntctfrm_button_backgroud" value="<?php echo $cntctfrm_options['button_backgroud']; ?>" class="cntctfrm_button_backgroud cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( 'Button color', 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_button_color" value="<?php echo $cntctfrm_options['button_color']; ?>"  class="cntctfrm_button_color cntctfrm_color" />
													<div class="cntctfrm_label_block"><?php _e( "Button text color", 'contact-form-pro' ); ?></div>
												</div>
												<div>
													<input maxlength="7" name="cntctfrm_border_button_color" value="<?php echo $cntctfrm_options['border_button_color']; ?>" class="cntctfrm_border_button_color cntctfrm_color"/>
													<div class="cntctfrm_label_block"><?php _e( 'Border color', 'contact-form-pro' ); ?></div>
												</div>
											</td>
										</tr>
									<?php } ?>
								</table>
							</div>
							<?php /* Right class labels position and display "show errors" block for tabs Contact Form Multi */
							if ( 'free' == $contact_form_multi_active && isset( $_SESSION['cntctfrmmlt_id_form'] ) && 1 != $_SESSION['cntctfrmmlt_id_form'] ) {
								$class_labels_position = "cntctfrm_labels_position_top";
							} else {
								$display_show_errors_block = true;
								$class_labels_position = "cntctfrm_labels_position_" . $cntctfrm_options['labels_settings']['position'];
							} ?>
							<div id="<?php echo is_rtl() ? 'cntctfrm_left_table' : 'cntctfrm_right_table'; ?>" class="<?php echo $class_labels_position; ?>">
								<h3><?php _e( "Contact Form | Preview", 'contact-form-pro' ); ?></h3>
								<span class="bws_info"><?php _e( 'Drag the necessary field to sort fields.', 'contact-form-pro' ); ?></span>
								<?php
									$classes = ( (int) $cntctfrm_options['layout'] === 1 ) ? ' cntctfrm_one_column' : ' cntctfrm_two_columns';
									$classes .= is_rtl() ? ' cntctfrm_rtl' : ' cntctfrm_ltr';
									$classes .= ' ' . $class_labels_position;
									$classes .= ' cntctfrm_labels_align_' . $cntctfrm_options['labels_settings']['align'];
									$cntctfrm_tooltip_position = ( $cntctfrm_options['labels_settings']['position'] == 'right' ) ? 'left' : 'right';
								?>
								<?php if ( isset( $display_show_errors_block ) ) { ?>
									<div id="cntctfrm_show_errors_block" class="hidden">
										<input type="checkbox" id="cntctfrm_show_errors" class="bws_no_bind_notice" name="cntctfrm_show_errors" /> <label for="cntctfrm_show_errors"><?php _e( "Show with errors", 'contact-form-pro' ); ?></label>
									</div>
								<?php } ?>
								<div id="cntctfrm_contact_form" class="cntctfrm_contact_form<?php if ( $cntctfrm_form_tooltips == 1 ) echo ' cntctfrm_form_tooltips'; echo $classes; ?>">
									<div id="cntctfrm_main_error_wrap">
										<div id="cntctfrm_main_error_text" class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['form_error']['default']; ?></div>
									</div>
									<div id="cntctfrm_wrap">
										<?php $ordered_fields = cntctfrm_get_ordered_fields();
										for ( $i = 1; $i <= 2; $i++ ) {
											$column = ( $i == 1 ) ? 'first_column' : 'second_column'; ?>
											<ul id="cntctfrm_<?php echo $column; ?>" class="cntctfrm_column" <?php if ( $i == 2 && (int) $cntctfrm_options['layout'] === 1 ) echo 'style="display: none;"'; ?>>
												<?php foreach ( $ordered_fields[ $column ] as $cntctfrm_field ) {
													switch ( $cntctfrm_field ) {
														case 'cntctfrm_contact_department':
															if ( $cntctfrm_options['select_email'] == 'departments' ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_department_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['department_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_department">
																			<label for="cntctfrm_contact_department">
																				<?php echo $cntctfrm_options['department_label']['default'];
																				if ( $cntctfrm_options['required_department_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_department">
																			<label for="cntctfrm_contact_department">
																				<?php echo $cntctfrm_options['department_label']['default'];
																				if ( $cntctfrm_options['required_department_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																		<?php if ( $cntctfrm_options['required_department_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['department_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_select cntctfrm_select_department cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<select id="cntctfrm_contact_department" class="bws_no_bind_notice<?php if ( $cntctfrm_options['required_department_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" name="cntctfrm_contact_department">
																			<?php if ( $cntctfrm_options['required_department_field'] == 1 ) { ?>
																				<option value="">...</option>
																			<?php } ?>
																			<?php foreach ( $cntctfrm_options['departments']['name'] as $key => $value ) { ?>
																				<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
																			<?php } ?>
																		</select>
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_department'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['department_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_department_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['department_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_department">
																			<label for="cntctfrm_contact_department">
																				<?php echo $cntctfrm_options['department_label']['default'];
																				if ( $cntctfrm_options['required_department_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_name':
															if ( 1 == $cntctfrm_options['display_name_field'] && ( 0 == $cntctfrm_options['default_name'] || ( 1 == $cntctfrm_options['default_name'] && 1 == $cntctfrm_options['visible_name'] ) ) ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_name_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['name_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_name">
																			<label for="cntctfrm_contact_name">
																				<?php echo $cntctfrm_options['name_label']['default'];
																				if ( $cntctfrm_options['required_name_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_name">
																			<label for="cntctfrm_contact_name">
																				<?php echo $cntctfrm_options['name_label']['default'];
																				if ( $cntctfrm_options['required_name_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																		<?php if ( $cntctfrm_options['required_name_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['name_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_name cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['name_help']['default'] . '"' : ''; ?> class="text bws_no_bind_notice<?php if ( $cntctfrm_options['required_name_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" type="text" maxlength="250" size="40" value="" name="cntctfrm_contact_name" id="cntctfrm_contact_name" />
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_name'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['name_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_name_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['name_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_name">
																			<label for="cntctfrm_contact_name">
																				<?php echo $cntctfrm_options['name_label']['default'];
																				if ( $cntctfrm_options['required_name_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?>
																			</label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_location':
															if ( $cntctfrm_options['display_location_field'] == 1 && ! empty( $locations ) ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_location_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['location_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_location">
																			<label for="cntctfrm_contact_location">
																				<?php echo $cntctfrm_options['location_label']['default'];
																				if ( $cntctfrm_options['required_location_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?>
																			</label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_location">
																			<label for="cntctfrm_contact_location">
																				<?php echo $cntctfrm_options['location_label']['default'];
																				if ( $cntctfrm_options['required_location_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?>
																			</label>
																		</div>
																		<?php if ( $cntctfrm_options['required_location_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['location_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_select cntctfrm_select_location cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<select id="cntctfrm_contact_location" class="bws_no_bind_notice<?php if ( $cntctfrm_options['required_location_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" name="cntctfrm_contact_location">
																			<?php if ( $cntctfrm_options['required_location_field'] == 1 ) { ?>
																				<option value="">...</option>
																			<?php } ?>
																			<?php foreach ( $locations as $key => $value ) { ?>
																				<option value="<?php echo stripcslashes( $value->name ); ?>"><?php echo stripcslashes( $value->name ); ?></option>
																			<?php } ?>
																		</select>
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_location'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['location_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_location_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['location_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_location">
																			<label for="cntctfrm_contact_location"><?php echo $cntctfrm_options['location_label']['default'];
																			if ( $cntctfrm_options['required_location_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_address':
															if ( $cntctfrm_options['display_address_field'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_address_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['address_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_address">
																			<label for="cntctfrm_contact_address">
																				<?php echo $cntctfrm_options['address_label']['default'];
																				if ( $cntctfrm_options['required_address_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?>
																			</label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_address">
																			<label for="cntctfrm_contact_address">
																				<?php echo $cntctfrm_options['address_label']['default'];
																				if ( $cntctfrm_options['required_address_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?>
																			</label>
																		</div>
																		<?php if ( $cntctfrm_options['required_address_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['address_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_address cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['address_help']['default'] . '"' : ''; ?> class="text bws_no_bind_notice<?php if ( $cntctfrm_options['required_address_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" type="text" maxlength="250" size="40" value="" name="cntctfrm_contact_address" id="cntctfrm_contact_address" />
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_address'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['address_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_address_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['address_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_address">
																			<label for="cntctfrm_contact_address">
																				<?php echo $cntctfrm_options['address_label']['default'];
																				if ( $cntctfrm_options['required_address_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?>
																			</label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_email':
															if ( 0 == $cntctfrm_options['default_email'] || ( 1 == $cntctfrm_options['default_email'] && 1 == $cntctfrm_options['visible_email'] ) ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_email_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['email_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_email">
																			<label for="cntctfrm_contact_email"><?php echo $cntctfrm_options['email_label']['default'];
																			if ( $cntctfrm_options['required_email_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_email">
																			<label for="cntctfrm_contact_email"><?php echo $cntctfrm_options['email_label']['default'];
																			if ( $cntctfrm_options['required_email_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																		<?php if ( $cntctfrm_options['required_email_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['email_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_email cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['email_help']['default'] . '"' : ''; ?> class="text bws_no_bind_notice<?php if ( $cntctfrm_options['required_email_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" type="text" maxlength="250" size="40" value="" name="cntctfrm_contact_email" id="cntctfrm_contact_email" />
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_email'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['email_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_email_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['email_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_email">
																			<label for="cntctfrm_contact_email"><?php echo $cntctfrm_options['email_label']['default'];
																			if ( $cntctfrm_options['required_email_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_phone':
															if ( $cntctfrm_options['display_phone_field'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																		<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_phone_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['phone_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_phone">
																			<label for="cntctfrm_contact_phone"><?php echo $cntctfrm_options['phone_label']['default'];
																			if ( $cntctfrm_options['required_phone_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_phone">
																			<label for="cntctfrm_contact_phone"><?php echo $cntctfrm_options['phone_label']['default'];
																			if ( $cntctfrm_options['required_phone_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																		<?php if ( $cntctfrm_options['required_phone_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['phone_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_phone cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['phone_help']['default'] . '"' : ''; ?> class="text bws_no_bind_notice<?php if ( $cntctfrm_options['required_phone_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" type="text" maxlength="250" size="40" value="" name="cntctfrm_contact_phone" id="cntctfrm_contact_phone" />
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_phone'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['phone_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_phone_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['phone_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_phone">
																			<label for="cntctfrm_contact_phone"><?php echo $cntctfrm_options['phone_label']['default'];
																			if ( $cntctfrm_options['required_phone_field'] == 1 ) echo '<span class="required">' . $cntctfrm_options['required_symbol'] . '</span>'; ?></label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_subject':
															if ( 1 == $cntctfrm_options['visible_subject'] ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_subject_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['subject_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_subject">
																			<label for="cntctfrm_contact_subject"><?php echo $cntctfrm_options['subject_label']['default'];
																			if ( $cntctfrm_options['required_subject_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_subject">
																			<label for="cntctfrm_contact_subject"><?php echo $cntctfrm_options['subject_label']['default'];
																			if ( $cntctfrm_options['required_subject_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																		<?php if ( $cntctfrm_options['required_subject_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['subject_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_subject cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['subject_help']['default'] . '"' : ''; ?> class="text bws_no_bind_notice<?php if ( $cntctfrm_options['required_subject_field'] == 1 ) echo ' cntctfrm_test_error'; ?>" type="text" maxlength="250" size="40" value="" name="cntctfrm_contact_subject" id="cntctfrm_contact_subject" />
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_subject'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['subject_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_subject_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['subject_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_subject">
																			<label for="cntctfrm_contact_subject"><?php echo $cntctfrm_options['subject_label']['default'];
																			if ( $cntctfrm_options['required_subject_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_message':
															if ( 1 == $cntctfrm_options['visible_subject'] ) { ?>
															<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
																		if ( $cntctfrm_options['required_message_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['message_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_message">
																			<label for="cntctfrm_contact_message"><?php echo $cntctfrm_options['message_label']['default'];
																			if ( $cntctfrm_options['required_message_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_message">
																			<label for="cntctfrm_contact_message"><?php echo $cntctfrm_options['message_label']['default'];
																			if ( $cntctfrm_options['required_message_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																		<?php if ( $cntctfrm_options['required_message_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['message_error']['default']; ?></div>
																		<?php }
																	} ?>
																	<div class="cntctfrm_input cntctfrm_input_message cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<textarea <?php echo $cntctfrm_options['placeholder'] ? 'placeholder="' . $cntctfrm_options['message_help']['default'] . '"' : ''; ?> rows="5" cols="30" name="cntctfrm_contact_message" id="cntctfrm_contact_message" class="bws_no_bind_notice<?php if ( $cntctfrm_options['required_message_field'] == 1 ) echo ' cntctfrm_test_error'; ?>"></textarea>
																		<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_message'] == 0 ) echo ' hidden'; ?>">
																			<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['message_tooltip']['default']; ?></div>
																		</div>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
																		if ( $cntctfrm_options['required_message_field'] == 1 ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['message_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_label cntctfrm_label_message">
																			<label for="cntctfrm_contact_message"><?php echo $cntctfrm_options['message_label']['default'];
																			if ( $cntctfrm_options['required_message_field'] == 1 ) echo '<span class="required">'.$cntctfrm_options['required_symbol'].'</span>'; ?></label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_attachment':
															if ( $cntctfrm_options['attachment'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																		<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['attachment_error']['default']; ?></div>
																		<div class="cntctfrm_label cntctfrm_label_attachment">
																			<label for="cntctfrm_contact_attachment">
																				<?php echo $cntctfrm_options['attachment_label']['default']; ?>
																			</label>
																		</div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_attachment">
																			<label for="cntctfrm_contact_attachment">
																				<?php echo $cntctfrm_options['attachment_label']['default']; ?>
																			</label>
																		</div>
																		<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['attachment_error']['default']; ?></div>
																	<?php } ?>
																	<div class="cntctfrm_input cntctfrm_input_attachment cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input type="file" name="cntctfrm_contact_attachment" id="cntctfrm_contact_attachment" class="bws_no_bind_notice cntctfrm_test_error" />
																		<?php if ( $cntctfrm_options['attachment_explanations'] == 1 ) { ?>
																			<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip <?php if ( $cntctfrm_options['tooltip_display_attachment'] == 0 ) echo ' hidden'; ?>">
																				<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['attachment_tooltip']['default']; ?></div>
																			</div>
																			<label class="cntctfrm_contact_attachment_extensions"><br/><?php _e( "You can attach the following file formats", 'contact-form-pro' ); ?>: html, txt, css, gif, png, jpeg, jpg, tiff, bmp, ai, eps, ps, csv, rtf, pdf, doc, docx, xls, xlsx, zip, rar, wav, mp3, ppt</label>
																		<?php } ?>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																		<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['attachment_error']['default']; ?></div>
																		<div class="cntctfrm_label cntctfrm_label_attachment">
																			<label for="cntctfrm_contact_attachment">
																				<?php echo $cntctfrm_options['attachment_label']['default']; ?>
																			</label>
																		</div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_send_copy':
															if ( $cntctfrm_options['send_copy'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'top' || $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_send_copy"></div>
																	<?php } ?>
																	<div class="cntctfrm_checkbox cntctfrm_checkbox_send_copy cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input type="checkbox" value="1" name="cntctfrm_contact_send_copy" id="cntctfrm_contact_send_copy" class="bws_no_bind_notice" />
																		<label for="cntctfrm_contact_send_copy"><?php echo $cntctfrm_options['send_copy_label']['default']; ?></label>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_send_copy"></div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_privacy':
															if ( $cntctfrm_options['display_privacy_check'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_privacy_check"></div>
																	<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_privacy_check"></div>
																		<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['privacy_check_error']['default']; ?></div>
																	<?php } ?>
																	<div class="cntctfrm_checkbox cntctfrm_checkbox_privacy_check cntctfrm_label_hook">
																		<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['privacy_check_error']['default']; ?></div>
																		<?php } ?>
																		<div class="cntctfrm_drag_wrap"></div>
																		<input type="checkbox" value="1" name="cntctfrm_contact_privacy" id="cntctfrm_privacy_check" class="bws_no_bind_notice cntctfrm_test_error" />
																		<label for="cntctfrm_privacy_check"><?php echo $cntctfrm_options['privacy_check_label']['default']; ?></label>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																		<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['privacy_check_error']['default']; ?></div>
																		<div class="cntctfrm_label cntctfrm_label_privacy_check"></div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_contact_optional':
															if ( $cntctfrm_options['display_optional_check'] == 1 ) { ?>
																<li class="cntctfrm_field_wrap">
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'top' || $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_optional_check"></div>
																	<?php } ?>
																	<div class="cntctfrm_checkbox cntctfrm_checkbox_optional_check cntctfrm_label_hook">
																		<div class="cntctfrm_drag_wrap"></div>
																		<input type="checkbox" value="1" name="cntctfrm_contact_optional" id="cntctfrm_optional_check" class="bws_no_bind_notice" />
																		<label for="cntctfrm_optional_check"><?php echo $cntctfrm_options['optional_check_label']['default']; ?></label>
																	</div>
																	<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																		<div class="cntctfrm_label cntctfrm_label_optional_check"></div>
																	<?php } ?>
																	<div class="cntctfrm_clear"></div>
																</li>
															<?php }
															break;
														case 'cntctfrm_subscribe':
															if ( array_key_exists( 'subscriber', $cntctfrm_related_plugins ) ) {
																if ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['subscriber']['options']['contact_form'] ) ) || ! empty( $cntctfrm_options['display_subscribe'] ) ) { ?>
																	<li class="cntctfrm_field_wrap">
																		<?php if ( $cntctfrm_options['labels_settings']['position'] == 'top' || $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																			<div class="cntctfrm_label cntctfrm_label_subscribe"></div>
																		<?php } ?>
																		<div class="cntctfrm_checkbox cntctfrm_checkbox_subscribe cntctfrm_label_hook">
																			<div class="cntctfrm_drag_wrap"></div>
																			<input type="hidden" value="1" name="cntctfrm_subscribe"/>
																			<?php $cntctfrm_sbscrbr_checkbox = apply_filters( 'sbscrbr_cntctfrm_checkbox_add', array() );
																				if ( isset( $cntctfrm_sbscrbr_checkbox['content'] ) ) {
																					echo $cntctfrm_sbscrbr_checkbox['content'];
																				} ?>
																		</div>
																		<?php if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																			<div class="cntctfrm_label cntctfrm_label_subscribe"></div>
																		<?php } ?>
																		<div class="cntctfrm_clear"></div>
																	</li>
																<?php }
															}
															break;
														case 'cntctfrm_captcha':
															if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) ||
																array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) ) {

																$display_captcha_label = '';

																if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) &&
																	( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['captcha']['enabled'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_captcha'] ) ) ) ) {
																	$display_captcha = true;

																	$captcha_label = isset( $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['label'] ] ) ? $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['label'] ] : '';

																	if ( ! empty( $captcha_label ) ) {
																		$captcha_required_symbol = sprintf( ' <span class="required">%s</span>', ( isset( $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['required_symbol'] ] ) ) ? $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['required_symbol'] ] : '' );
																		$display_captcha_label = $captcha_label . $captcha_required_symbol;
																	}
																}

																if ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) &&
																	( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_google_captcha'] ) ) ) )
																	$display_google_captcha = true;

																if ( isset( $display_google_captcha ) || isset( $display_captcha ) ) { ?>
																	<li class="cntctfrm_field_wrap">
																	<input type="hidden" value="1" name="cntctfrm_captcha"/>
																		<?php if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['captcha_error']['default']; ?></div>
																			<div class="cntctfrm_label cntctfrm_label_captcha">
																				<label><?php echo $display_captcha_label; ?></label>
																			</div>
																		<?php } else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) { ?>
																			<div class="cntctfrm_label cntctfrm_label_captcha">
																				<label><?php echo $display_captcha_label; ?></label>
																			</div>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['captcha_error']['default']; ?></div>
																		<?php }

																		if ( isset( $display_captcha ) ) { ?>
																			<div class="cntctfrm_input cntctfrm_input_captcha cntctfrm_label_hook">
																				<img src="<?php echo plugins_url( 'images/cptch.png', __FILE__ ); ?>">
																				<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_captcha'] == 0 ) echo ' hidden'; ?>">
																					<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['captcha_tooltip']['default']; ?></div>
																				</div>
																			</div>
																		<?php }
																		if ( isset( $display_google_captcha ) ) { ?>
																			<div class="cntctfrm_input cntctfrm_input_captcha cntctfrm_label_hook">
																				<img src="<?php echo plugins_url( 'images/google-captcha.png', __FILE__ ); ?>">
																				<div class="cntctfrm_help_box cntctfrm_help_box_position_<?php echo $cntctfrm_tooltip_position; ?> cntctfrm_hide_tooltip<?php if ( $cntctfrm_options['tooltip_display_captcha'] == 0 ) echo ' hidden'; ?>">
																					<div class="cntctfrm_hidden_help_text"><?php echo $cntctfrm_options['captcha_tooltip']['default']; ?></div>
																				</div>
																			</div>
																		<?php }
																		if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) { ?>
																			<div class="cntctfrm_error_text hidden"><?php echo $cntctfrm_options['captcha_error']['default']; ?></div>
																			<div class="cntctfrm_label cntctfrm_label_captcha">
																				<label><?php echo $display_captcha_label; ?></label>
																			</div>
																		<?php } ?>
																		<div class="cntctfrm_clear"></div>
																	</li>
																<?php }
															}
															break;
														default:
															break;
													}
												} ?>
											</ul>
										<?php } ?>
										<div class="cntctfrm_clear"></div>
									</div>
									<div class="cntctfrm_submit_wrap">
										<?php $cntctfrm_direction = is_rtl() ? 'rtl' : 'ltr';
										$cntctfrm_submit_position_value = array(
											'ltr' => array(
												'left'  => 1,
												'right' => 2
											),
											'rtl' => array(
												'left'  => 2,
												'right' => 1
											),
										);
										for ( $i = 1; $i <= 2; $i++ ) {
											$column = ( $i == 1 ) ? 'first_column' : 'second_column'; ?>
											<div id="cntctfrm_submit_<?php echo $column; ?>" class="cntctfrm_column">
												<div class="cntctfrm_submit_field_wrap">
													<?php if ( $i == $cntctfrm_submit_position_value[ $cntctfrm_direction ][ $cntctfrm_options['submit_position'] ] ) { ?>
														<div class="cntctfrm_input cntctfrm_input_submit" style="<?php printf( 'text-align: %s !important;', $cntctfrm_options['submit_position'] ); ?>">
															<input type="button" value="<?php echo $cntctfrm_options['submit_label']['default']; ?>" class="cntctfrm_contact_submit bws_no_bind_notice" style="cursor: pointer; margin: 0; border-style: solid;" />
														</div>
													<?php } ?>
												</div>
											</div>
										<?php } ?>
										<div class="cntctfrm_clear"></div>
									</div>
								</div>
								<div id="cntctfrm_shortcode" class="cntctfrm_one_column">
									<?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget", 'contact-form-pro' ); ?>:
									<div id="cntctfrm_shortcode_code">
										<span class="cntctfrm_shortcode">[bestwebsoft_contact_form<?php if ( $contact_form_multi_active ) printf( ' id=%s', $_SESSION['cntctfrmmlt_id_form'] ); ?>]</span>
									</div>
								</div>
							</div>
							<div class="cntctfrm_clear"></div>
							<input type="hidden" name="cntctfrm_form_submit" value="submit" />
							<input type="hidden" id="cntctfrm_layout_first_column" name="cntctfrm_layout_first_column" value="<?php echo implode( ',', $cntctfrm_options['order_fields']['first_column'] ); ?>" />
							<input type="hidden" id="cntctfrm_layout_second_column" name="cntctfrm_layout_second_column" value="<?php echo implode( ',', $cntctfrm_options['order_fields']['second_column'] ); ?>" />
							<p class="submit">
								<input id="bws-submit-button" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'contact-form-pro' ); ?>" />
							</p>
							<?php wp_nonce_field( plugin_basename(__FILE__), 'cntctfrm_nonce_name' ); ?>
						</div>
					</form>
					<?php bws_form_restore_default_settings( $plugin_basename ); ?>
				<?php }
			} elseif ( 'custom_code' == $_GET['action'] ) {
				bws_custom_code_tab();
			} elseif ( 'go_pro' == $_GET['action'] ) {
				global $cntctfrmmlt_plugin_info;
				/* display GO PRO form */
				bws_go_pro_tab_show( false, array( "Version" => $cntctfrm_plugin_info["Version"], "Name" => $cntctfrmmlt_plugin_info["Name"] ), $plugin_basename, 'contact_form_pro.php', 'contact_form_pro.php', 'contact-form-multi-pro/contact-form-multi-pro.php', 'contact-form-multi', '57d8351b1c6b67d3e0600bd9a680c283', '3', isset( $go_pro_result['pro_plugin_is_activated'] ) );
			}
			bws_plugin_reviews_block( $cntctfrm_plugin_info['Name'], 'contact-form-plugin' ); ?>
		</div>
	<?php }
}

/**
* add or remove filters for compatibility with Captcha and Google Captcha
* @since 4.0.2
* $action            	string    can be 'remove_filters' or 'add_filters'
* $removed_filters    	array of existed filters (returned from this function when 'remove_filters' action)
* @return  				array of existed filters for 'remove_filters' or 'false' for 'add_filters'
*/
if ( ! function_exists( 'cntctfrm_handle_captcha_filters' ) ) {
	function cntctfrm_handle_captcha_filters( $action, $removed_filters = false ) {
		global $cntctfrm_options, $cntctfrm_related_plugins;

		if ( 'remove_filters' == $action ) {
			if ( empty( $cntctfrm_related_plugins ) )
				cntctfrm_related_plugins();

			$contact_form_multi_active = cntctfrm_check_cf_multi_active();

			$removed_filters = $remove_captcha = array();

			if ( ! ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['captcha']['enabled'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_captcha'] ) ) ) ) )
				$remove_captcha[] = 'captcha';
			if ( ! ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_google_captcha'] ) ) ) ) )
				$remove_captcha[] = 'google-captcha';

			$filters = array(
				'google-captcha'	=> array(
					'gglcptch_cf_display'	=> 'gglcptch_recaptcha_check',
					'gglcptchpr_cf_display'	=> 'gglcptchpr_recaptcha_check'
				),
				'captcha'			=> array(
					'cptch_custom_form'		=> 'cptch_check_custom_form',
					'cptchpls_custom_form'	=> 'cptchpls_check_custom_form',
					'cptchpr_custom_form'	=> 'cptchpr_check_custom_form',
				)
			);

			if ( ! empty( $remove_captcha ) ) {
				foreach ( $remove_captcha as $remove ) {
					foreach ( $filters[ $remove ] as $display_filter => $check_filter ) {
						if ( has_filter( 'cntctfrm_display_captcha', $display_filter ) ) {
							remove_filter( 'cntctfrm_display_captcha', $display_filter );
							$removed_filters[] = array( 'cntctfrm_display_captcha' => $display_filter );
						}

						if ( has_filter( 'cntctfrm_check_form', $check_filter ) ) {
							remove_filter( 'cntctfrm_check_form', $check_filter );
							$removed_filters[] = array( 'cntctfrm_check_form' => $check_filter );
						}
					}
				}
			}
			return $removed_filters;
		} elseif ( 'add_filters' == $action && ! empty( $removed_filters ) ) {
			foreach ( $removed_filters as $function_array ) {
				foreach ( $function_array as $tag => $function ) {
					add_filter( $tag, $function );
				}
			}
		}
		return false;
	}
}

/* Display contact form in front end - page or post */
if ( ! function_exists( 'cntctfrm_display_form' ) ) {
	function cntctfrm_display_form( $atts = array( 'lang' => 'default' ) ) {
		global $cntctfrm_error_message, $cntctfrm_options, $cntctfrm_plugin_info, $cntctfrm_result, $cntctfrmmlt_ide, $cntctfrmmlt_active_plugin, $wpdb, $cntctfrm_form_count, $cntctfrm_related_plugins, $cntctfrm_stile_options;

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		if ( ! wp_script_is( 'cntctfrm_frontend_script', 'registered' ) )
			wp_register_script( 'cntctfrm_frontend_script', plugins_url( 'js/cntctfrm.js', __FILE__ ), array( 'jquery' ), false, true  );

		$cntctfrm_form_count = empty( $cntctfrm_form_count ) ? 1 : ++$cntctfrm_form_count;
		$form_countid = ( 1 == $cntctfrm_form_count ? '' : '_' . $cntctfrm_form_count );

		$content = "";

		/* Get options for the form with a definite identifier */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( $contact_form_multi_active ) {
			extract( shortcode_atts( array( 'lang' => 'default', 'id' => $cntctfrmmlt_ide ), $atts ) );
			if ( isset( $atts['id'] ) ) {
				cntctfrm_settings( $atts['id'] );
				$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $atts['id'] );
				$options_name = 'cntctfrmmlt_options_' . $atts['id'];
				$location = $wpdb->prefix . 'cntctfrm_location' . $atts['id'];
				if ( ! $cntctfrm_options ) {
					$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
					$options_name = 'cntctfrmmlt_options';
					$location = $wpdb->prefix . 'cntctfrm_location';
				}
			} else {
				cntctfrm_settings();
				if ( 'pro' == $contact_form_multi_active && $multi_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
					/**/
				} else {
					$multi_options_main = get_option( 'cntctfrmmlt_options_main' );
				}

				if ( ! empty( $multi_options_main ) ) {
					reset( $multi_options_main['name_id_form'] );
					$id = key( $multi_options_main['name_id_form'] );
					$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $id );
					$options_name = 'cntctfrmmlt_options_' . $id;
					if ( empty( $cntctfrm_options ) ) {
						$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
						$options_name = 'cntctfrmmlt_options';
					}
					$location = $wpdb->prefix . 'cntctfrm_location' . $id;
				} else {
					$options_name = 'cntctfrmmlt_options';
				}
			}
		} else {
			cntctfrm_settings();
			$cntctfrm_options = get_option( 'cntctfrm_options' );
			$location = $wpdb->prefix . 'cntctfrm_location';
			extract( shortcode_atts( array( 'lang' => 'default' ), $atts ) );
			$options_name = 'cntctfrm_options';
		}

		$cntctfrm_stile_options[ $cntctfrm_form_count ] = $options_name;
		/* check lang and replace with en default if need */
		foreach ( $cntctfrm_options as $key => $value ) {
			if ( is_array( $value ) && array_key_exists( 'default', $value ) && ( ! array_key_exists( $lang, $value ) || ( isset( $cntctfrm_options[ $key ][ $lang ] ) && $cntctfrm_options[ $key ][ $lang ] == '' ) ) ) {
				$cntctfrm_options[ $key ][ $lang ] = $cntctfrm_options[ $key ]['default'];
			}
		}

		$page_url = esc_url( add_query_arg( array() ) . '#cntctfrm_contact_form' );

		if ( $cntctfrm_options['display_location_field'] == 1 )
			$locations = $wpdb->get_results( "SELECT * FROM " . $location );

		/* get info on current user if logged-in*/
		if ( is_user_logged_in() ) {
			$user_is_logged_in = true;
			$current_user = wp_get_current_user();
		} else {
			$user_is_logged_in = false;
		}

		/* If contact form submited */
		$form_submited = isset( $_POST['cntctfrm_form_submited'] ) ? $_POST['cntctfrm_form_submited'] : 0;

		$department = ( isset( $_POST['cntctfrm_department'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( esc_html( $_POST['cntctfrm_department'] ) ) : "";

		if ( $user_is_logged_in && 1 == $cntctfrm_options['default_name'] )
			$name = ( isset( $_POST['cntctfrm_contact_name'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_name'] ) ) : $current_user->display_name;
		else
			$name = ( isset( $_POST['cntctfrm_contact_name'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_name'] ) ) : "";

		$location = ( isset( $_POST['cntctfrm_location'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( esc_html( $_POST['cntctfrm_location'] ) ) : "";

		$address = ( isset( $_POST['cntctfrm_contact_address'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_address'] ) ) : "";
		if ( $user_is_logged_in && 1 == $cntctfrm_options['default_email'] )
			$email = ( isset( $_POST['cntctfrm_contact_email'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_email'] ) ) : $current_user->user_email;
		else
			$email = ( isset( $_POST['cntctfrm_contact_email'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_email'] ) ) : "";
		if ( $cntctfrm_options['default_subject'] != '' )
			$subject = ( isset( $_POST['cntctfrm_contact_subject'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_subject'] ) ) : $cntctfrm_options['default_subject'];
		else
			$subject = ( isset( $_POST['cntctfrm_contact_subject'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_subject'] ) ) : "";
		if ( $cntctfrm_options['default_message'] != '' )
			$message = ( isset( $_POST['cntctfrm_contact_message'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_message'] ) ) : $cntctfrm_options['default_message'];
		else
			$message = ( isset( $_POST['cntctfrm_contact_message'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_message'] ) ) : "";
		$phone = ( isset( $_POST['cntctfrm_contact_phone'] ) && $cntctfrm_form_count == $form_submited ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_contact_phone'] ) ) : "";
		$department_key = ( isset( $_POST['cntctfrm_department'] ) && $cntctfrm_form_count == $form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_department'] ) ) : "";

		$name = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) );
		$address = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) );
		$email = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) );
		$subject = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) );
		$message = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) );
		$phone = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) );
		$department_key = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $department_key ) ) );

		$send_copy		= ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $cntctfrm_form_count == $form_submited ) ? $_POST['cntctfrm_contact_send_copy'] : "";
		$privacy_check	= ( isset( $_POST['cntctfrm_privacy_check'] ) && $cntctfrm_form_count == $form_submited ) ? $_POST['cntctfrm_privacy_check'] : "";
		$optional_check	= ( isset( $_POST['cntctfrm_optional_check'] ) && $cntctfrm_form_count == $form_submited ) ? $_POST['cntctfrm_optional_check'] : "";
		$subscribe		= ( isset( $_POST['cntctfrm_subscribe'] ) && $cntctfrm_form_count == $form_submited ) ? $_POST['cntctfrm_subscribe'] : "";


		if ( $cntctfrm_options['tooltip_display_department'] == 1 || $cntctfrm_options['tooltip_display_name'] == 1 || $cntctfrm_options['tooltip_display_location'] == 1 ||
		$cntctfrm_options['tooltip_display_address'] == 1 || $cntctfrm_options['tooltip_display_email'] == 1 || $cntctfrm_options['tooltip_display_phone'] == 1 ||
		$cntctfrm_options['tooltip_display_subject'] == 1 || $cntctfrm_options['tooltip_display_message'] == 1 || $cntctfrm_options['tooltip_display_attachment'] == 11 ) {
			$cntctfrm_form_tooltips = 1;
		} else {
			$cntctfrm_form_tooltips = 0;
		}

		/* If it is good */
		if ( true === $cntctfrm_result && $cntctfrm_form_count == $form_submited ) {
			$_SESSION['cntctfrm_send_mail'] = true;
			if ( $cntctfrm_options['action_after_send'] == 1 )
				$content .= '<div id="cntctfrm_contact_form' . $form_countid . '"><div id="cntctfrm_thanks">' . $cntctfrm_options['thank_text'][ $lang ] . '</div></div>';
			else
				$content .= "<script type='text/javascript'>window.location.href = '" . $cntctfrm_options['redirect_url'] . "';</script>";
		} elseif ( false === $cntctfrm_result && $cntctfrm_form_count == $form_submited ) {
			/* If email not be delivered */
			$cntctfrm_error_message['error_form'] = __( "Sorry, email message could not be delivered.", 'contact-form-pro' );
		}
		if ( true !== $cntctfrm_result || $cntctfrm_form_count != $form_submited ) {
			$_SESSION['cntctfrm_send_mail'] = false;

			$classes = ( isset( $atts['id'] ) ) ? ' cntctfrm_id_' . $atts['id'] : '';
			$classes .= ( $cntctfrm_form_tooltips == 1 ) ? ' cntctfrm_form_tooltips' : '';
			$classes .= is_rtl() ? ' cntctfrm_rtl' : ' cntctfrm_ltr';
			$classes .= ( (int) $cntctfrm_options['layout'] === 1 ) ? ' cntctfrm_one_column' : ' cntctfrm_two_columns';
			if ( ! $contact_form_multi_active ||
			  ( 'free' == $contact_form_multi_active && isset( $atts['id'] ) && $id == $atts['id'] ) ||
			  ( 'free' == $contact_form_multi_active && ! isset( $atts['id'] ) ) ||
			  'pro' == $contact_form_multi_active ) {
				$classes .= ' cntctfrm_form_align_' . $cntctfrm_options['form_align'];
				$classes .= ' cntctfrm_labels_position_' . $cntctfrm_options['labels_settings']['position'];
				$classes .= ' cntctfrm_labels_align_' . $cntctfrm_options['labels_settings']['align'];
			}
			$ordered_fields = cntctfrm_get_ordered_fields();

			if ( $cntctfrm_options['form_align'] == 'left' || $cntctfrm_options['form_align'] == 'right' ) {
				$content .= '<div class="cntctfrm_clear"></div>';
			}
			/* Output form */
			$content .= sprintf( '<form method="post" id="cntctfrm_contact_form%1$s" class="cntctfrm_contact_form%2$s" action="%3$s" enctype="multipart/form-data">', $form_countid, $classes, $page_url . $form_countid );
				if ( isset( $cntctfrm_error_message['error_form'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
					$content .= sprintf( '<div id="cntctfrm_main_error_text" class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_form'] );
				}
				for ( $i = 1; $i <= 2; $i++ ) {
					$column = ( $i == 1 ) ? 'first_column' : 'second_column';
					$content .= sprintf( '<div id="cntctfrm_%1$s" class="cntctfrm_column" style="display: %2$s;">', $column, ( $i == 2 && (int) $cntctfrm_options['layout'] === 1 ) ? 'none' : 'block' );
						foreach ( $ordered_fields[ $column ] as $cntctfrm_field ) {
							switch( $cntctfrm_field ) {
								case 'cntctfrm_contact_department':
									if ( $cntctfrm_options['select_email'] == 'departments') {
										$department_visibility = ( count( $cntctfrm_options["departments"]["email"] ) == '1' ) ? 'style="display: none;"' : '';
										$department_label_symbol_required = ( $cntctfrm_options['required_department_field'] == 1 ) ? sprintf( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
										$department_class_tooltip = ( $cntctfrm_options['tooltip_display_department'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$department_class_required = ( $cntctfrm_options['required_department_field'] == 1 ) ? ' cntctfrm_required_field' : '';
										$department_class_error = ( isset( $cntctfrm_error_message['error_department'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';

										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_department_wrap"%s>', $department_visibility );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {

												if ( isset( $cntctfrm_error_message['error_department'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_department'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_department">' );
													$content .= sprintf( '<label for="cntctfrm_contact_department%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['department_label'][ $lang ], $department_label_symbol_required );
												$content .= '</div>';

											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_department">' );
													$content .= sprintf( '<label for="cntctfrm_contact_department%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['department_label'][ $lang ], $department_label_symbol_required );
												$content .= '</div>';

												if ( isset( $cntctfrm_error_message['error_department'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_department'] );
												}
											}

											$content .= sprintf( '<div class="cntctfrm_select cntctfrm_select_department%s">', $department_class_tooltip );
												$content .= sprintf( '<select id="cntctfrm_contact_department%1$s" class="cntctfrm_contact_department%2$s%3$s" name="cntctfrm_department">', $form_countid, $department_class_required, $department_class_error );
													$content .= ( $cntctfrm_options['required_department_field'] == 1 ) ? '<option value="">...</option>' : '';
													foreach ( $cntctfrm_options['departments']['name'] as $key => $value ) {
														$content .= sprintf( '<option value="%1$s"%2$s>%3$s</option>',
															$key,
															( $department != '' && $department == $key ) ? ' selected="selected"' : '',
															stripcslashes( $value )
														);
													}
												$content .= '</select>';
												if ( '1' == $cntctfrm_options['tooltip_display_department'] ) {
													$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_department'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error': '',
														$cntctfrm_options['department_tooltip'][ $lang ] );
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_department'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_department'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_department">' );
													$content .= sprintf( '<label for="cntctfrm_contact_department%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['department_label'][ $lang ], $department_label_symbol_required );
												$content .= '</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_name':
									if ( $cntctfrm_options['display_name_field'] == 1 ) {
										$name_visibility = ( $user_is_logged_in && 0 == $cntctfrm_options['visible_name'] ) ? ' style="display: none;"' : '';
										$name_label_symbol_required = ( $cntctfrm_options['required_name_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
										$name_class_tooltip = ( $cntctfrm_options['tooltip_display_name'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$name_class_required = ( $cntctfrm_options['required_name_field'] == 1 ) ? ' cntctfrm_required_field' : '';
										$name_class_error = ( isset( $cntctfrm_error_message['error_name'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
										$name_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['name_help'][ $lang ] ) : '';
										$name_readonly = ( $user_is_logged_in && 1 == $cntctfrm_options['disabled_name'] ) ? 'readonly="readonly"' : '';

										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_name_wrap"%s>', $name_visibility );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {

												if ( isset( $cntctfrm_error_message['error_name'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_name'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_name">' );
													$content .= sprintf( '<label for="cntctfrm_contact_name%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['name_label'][ $lang ], $name_label_symbol_required );
												$content .=	'</div>';

											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_name">' );
													$content .= sprintf( '<label for="cntctfrm_contact_name%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['name_label'][ $lang ], $name_label_symbol_required );
												$content .=	'</div>';

												if ( isset( $cntctfrm_error_message['error_name'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_name'] );
												}

											}

											$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_name%s">', $name_class_tooltip );
												$content .= sprintf( '<input %1$s %2$s class="text cntctfrm_contact_name%3$s%4$s" type="text" size="40" value="%5$s" name="cntctfrm_contact_name" id="cntctfrm_contact_name%6$s" />',
													$name_placeholder,
													$name_readonly,
													$name_class_required,
													$name_class_error,
													$name,
													$form_countid
												);

												if ( '1' == $cntctfrm_options['tooltip_display_name'] ) {
													$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_name'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error': '',
														$cntctfrm_options['name_tooltip'][ $lang ]
													);
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_name'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_name'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_name">' );
													$content .= sprintf( '<label for="cntctfrm_contact_name%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['name_label'][ $lang ], $name_label_symbol_required );
												$content .=	'</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_location':
									if ( $cntctfrm_options['display_location_field'] == 1 && ! empty( $locations ) ) {
										$location_label_symbol_required = ( $cntctfrm_options['required_location_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
										$location_class_tooltip = ( $cntctfrm_options['tooltip_display_location'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$location_class_required = $cntctfrm_options['required_location_field'] == 1 ? ' cntctfrm_required_field' : '';
										$location_class_error = ( isset( $cntctfrm_error_message['error_location'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';

										$content .= '<div class="cntctfrm_field_wrap cntctfrm_field_location_wrap">';
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
												if ( isset( $cntctfrm_error_message['error_location'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_location'] );
												}

												$content .= '<div class="cntctfrm_label cntctfrm_label_location">';
													$content .= sprintf( '<label for="cntctfrm_contact_location%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['location_label'][ $lang ], $location_label_symbol_required );
												$content .= '</div>';
											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= '<div class="cntctfrm_label cntctfrm_label_location">';
													$content .= sprintf( '<label for="cntctfrm_contact_location%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['location_label'][ $lang ], $location_label_symbol_required );
												$content .= '</div>';

												if ( isset( $cntctfrm_error_message['error_location'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_location'] );
												}
											}

											$content .= sprintf( '<div class="cntctfrm_select cntctfrm_select_location%s">', $location_class_tooltip );
												$content .= sprintf( '<select id="cntctfrm_contact_location%1$s" class="cntctfrm_contact_location%2$s%3$s" name="cntctfrm_location">',
													$form_countid,
													$location_class_required,
													$location_class_error
												);
													$content .= ( $cntctfrm_options['required_location_field'] == 1 ) ? '<option value="">...</option>' : '';
													foreach ( $locations as $key => $value ) {
														$content .= sprintf( '<option value="%1$s"%2$s>%3$s</option>',
															stripcslashes( $value->name ),
															( $location == $value->name ) ? ' selected="selected"' : '',
															stripcslashes( $value->name )
														);
													}
												$content .= '</select>';

												if ( '1' == $cntctfrm_options['tooltip_display_location'] ) {
													$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_location'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
														$cntctfrm_options['location_tooltip'][ $lang ]
													);
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_location'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_location'] );
												}

												$content .= '<div class="cntctfrm_label cntctfrm_label_location">';
													$content .= sprintf( '<label for="cntctfrm_contact_location%1$s">%2$s%3$s</label>', $form_countid, $cntctfrm_options['location_label'][ $lang ], $location_label_symbol_required );
												$content .= '</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_address':
									if ( $cntctfrm_options['display_address_field'] == 1 ) {
										$address_label_symbol_required = ( $cntctfrm_options['required_address_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
										$address_class_tooltip = ( $cntctfrm_options['tooltip_display_location'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$address_class_required = $cntctfrm_options['required_address_field'] == 1 ? ' cntctfrm_required_field' : '';
										$address_class_error = ( isset( $cntctfrm_error_message['error_address'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
										$address_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['address_help'][ $lang ] ) : '';

										$content .= '<div class="cntctfrm_field_wrap cntctfrm_field_address_wrap">';
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
												if ( isset( $cntctfrm_error_message['error_address'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_address'] );
												}

												$content .= '<div class="cntctfrm_label cntctfrm_label_address">';
													$content .= sprintf( '<label for="cntctfrm_contact_address%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['address_label'][ $lang ],
														$address_label_symbol_required
													);
												$content .= '</div>';
											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= '<div class="cntctfrm_label cntctfrm_label_address">';
													$content .= sprintf( '<label for="cntctfrm_contact_address%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['address_label'][ $lang ],
														$address_label_symbol_required
													);
												$content .= '</div>';

												if ( isset( $cntctfrm_error_message['error_address'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_address'] );
												}
											}
											$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_address%s">', $address_class_tooltip );
												$content .= sprintf( '<input %1$s class="text cntctfrm_contact_address%2$s%3$s" type="text" size="40" value="%4$s" name="cntctfrm_contact_address" id="cntctfrm_contact_address%5$s" />',
													$address_placeholder,
													$address_class_required,
													$address_class_error,
													$address,
													$form_countid
												);

												if ( '1' == $cntctfrm_options['tooltip_display_address'] ) {
													$content .= sprintf ( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_address'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
														$cntctfrm_options['address_tooltip'][ $lang ]
													);
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_address'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_address'] );
												}

												$content .= '<div class="cntctfrm_label cntctfrm_label_address">';
													$content .= sprintf( '<label for="cntctfrm_contact_address%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['address_label'][ $lang ],
														$address_label_symbol_required
													);
												$content .= '</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_email':
									$email_visibility = ( $user_is_logged_in && 0 == $cntctfrm_options['visible_email'] ) ? ' style="display: none;"' : '';
									$email_label_symbol_required = ( $cntctfrm_options['required_email_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
									$email_class_tooltip = ( $cntctfrm_options['tooltip_display_email'] == 1 ) ? ' cntctfrm_tooltip' : '';
									$email_class_required = $cntctfrm_options['required_email_field'] == 1 ? ' cntctfrm_required_field' : '';
									$email_class_error = ( isset( $cntctfrm_error_message['error_email'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
									$email_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['email_help'][ $lang ] ) : '';
									$email_readonly = ( $user_is_logged_in && 1 == $cntctfrm_options['disabled_email'] ) ? 'readonly="readonly"' : '';

									$content .= sprintf ( '<div class="cntctfrm_field_wrap cntctfrm_field_email_wrap"%s>', $email_visibility );
										if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
											if ( isset( $cntctfrm_error_message['error_email'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_email'] );
											}

											$content .= '<div class="cntctfrm_label cntctfrm_label_email">';
												$content .= sprintf ( '<label for="cntctfrm_contact_email%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['email_label'][ $lang ],
													$email_label_symbol_required
												);
											$content .= '</div>';
										} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
											$content .= '<div class="cntctfrm_label cntctfrm_label_email">';
												$content .= sprintf ( '<label for="cntctfrm_contact_email%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['email_label'][ $lang ],
													$email_label_symbol_required
												);
											$content .= '</div>';

											if ( isset( $cntctfrm_error_message['error_email'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_email'] );
											}
										}

										$content .= sprintf ( '<div class="cntctfrm_input cntctfrm_input_email%s">', $email_class_tooltip );
											$content .= sprintf( '<input %1$s %2$s class="text cntctfrm_contact_email%3$s%4$s" type="text" size="40" value="%5$s" name="cntctfrm_contact_email" id="cntctfrm_contact_email%6$s" />',
												$email_placeholder,
												$email_readonly,
												$email_class_required,
												$email_class_error,
												$email,
												$form_countid
											);

											if ( '1' == $cntctfrm_options['tooltip_display_email'] ) {
												$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
													( isset( $cntctfrm_error_message['error_email'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
													$cntctfrm_options['email_tooltip'][ $lang ]
												);
											}
										$content .= '</div>';

										if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
											if ( isset( $cntctfrm_error_message['error_email'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_email'] );
											}

											$content .= '<div class="cntctfrm_label cntctfrm_label_email">';
												$content .= sprintf ( '<label for="cntctfrm_contact_email%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['email_label'][ $lang ],
													$email_label_symbol_required
												);
											$content .= '</div>';
										}
										$content .= '<div class="cntctfrm_clear"></div>';
									$content .= '</div>';
									break;
								case 'cntctfrm_contact_phone':
									if ( $cntctfrm_options['display_phone_field'] == 1 ) {
										$phone_label_symbol_required = ( $cntctfrm_options['required_phone_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
										$phone_class_tooltip = ( $cntctfrm_options['tooltip_display_phone'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$phone_class_required = $cntctfrm_options['required_phone_field'] == 1 ? ' cntctfrm_required_field' : '';
										$phone_class_error = ( isset( $cntctfrm_error_message['error_phone'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
										$phone_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['phone_help'][ $lang ] ) : '';

										$content .= sprintf ( '<div class="cntctfrm_field_wrap cntctfrm_field_phone_wrap">' );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
												if ( isset( $cntctfrm_error_message['error_phone'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_phone'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_phone">' );
													$content .= sprintf( '<label for="cntctfrm_contact_phone%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['phone_label'][ $lang ],
														$phone_label_symbol_required
													);
												$content .= '</div>';
											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_phone">' );
													$content .= sprintf( '<label for="cntctfrm_contact_phone%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['phone_label'][ $lang ],
														$phone_label_symbol_required
													);
												$content .= '</div>';

												if ( isset( $cntctfrm_error_message['error_phone'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_phone'] );
												}
											}

											$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_phone%s">', $phone_class_tooltip );
												$content .= sprintf( '<input %1$s class="text cntctfrm_contact_phone%2$s%3$s" type="text" size="40" value="%4$s" name="cntctfrm_contact_phone" id="cntctfrm_contact_phone%5$s" />',
													$phone_placeholder,
													$phone_class_required,
													$phone_class_error,
													$phone,
													$form_countid
												);

												if ( '1' == $cntctfrm_options['tooltip_display_phone'] ) {
													$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_phone'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
														$cntctfrm_options['phone_tooltip'][ $lang ]
													);
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_phone'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_phone'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_phone">' );
													$content .= sprintf( '<label for="cntctfrm_contact_phone%1$s">%2$s%3$s</label>',
														$form_countid,
														$cntctfrm_options['phone_label'][ $lang ],
														$phone_label_symbol_required
													);
												$content .= '</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_subject':
									$subject_visibility = ( 0 == $cntctfrm_options['visible_subject'] ) ? ' style="display: none;"' : '';
									$subject_label_symbol_required = ( $cntctfrm_options['required_subject_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
									$subject_class_tooltip = ( $cntctfrm_options['tooltip_display_subject'] == 1 ) ? ' cntctfrm_tooltip' : '';
									$subject_class_required = $cntctfrm_options['required_subject_field'] == 1 ? ' cntctfrm_required_field' : '';
									$subject_class_error = ( isset( $cntctfrm_error_message['error_subject'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
									$subject_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['subject_help'][ $lang ] ) : '';
									$subject_readonly = ( $cntctfrm_options['disabled_subject'] ) ? 'readonly="readonly"' : '';

									$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_subject_wrap"%s>', $subject_visibility );
										if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
											if ( isset( $cntctfrm_error_message['error_subject'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_subject'] );
											}

											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_subject">' );
												$content .= sprintf( '<label for="cntctfrm_contact_subject%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['subject_label'][ $lang ],
													$subject_label_symbol_required
												);
											$content .= '</div>';
										} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_subject">' );
												$content .= sprintf( '<label for="cntctfrm_contact_subject%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['subject_label'][ $lang ],
													$subject_label_symbol_required
												);
											$content .= '</div>';

											if ( isset( $cntctfrm_error_message['error_subject'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_subject'] );
											}
										}
										$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_subject%s">', $subject_class_tooltip );
											$content .= sprintf( '<input %1$s %2$s class="text cntctfrm_contact_subject%3$s%4$s" type="text" size="40" value="%5$s" name="cntctfrm_contact_subject" id="cntctfrm_contact_subject%6$s" />',
												$subject_placeholder,
												$subject_readonly,
												$subject_class_required,
												$subject_class_error,
												$subject,
												$form_countid
											);

											if ( '1' == $cntctfrm_options['tooltip_display_subject'] ) {
												$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
													( isset( $cntctfrm_error_message['error_subject'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
													$cntctfrm_options['subject_tooltip'][ $lang ]
												);
											}
										$content .= '</div>';

										if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
											if ( isset( $cntctfrm_error_message['error_subject'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_subject'] );
											}

											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_subject">' );
												$content .= sprintf( '<label for="cntctfrm_contact_subject%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['subject_label'][ $lang ],
													$subject_label_symbol_required
												);
											$content .= '</div>';
										}
										$content .= '<div class="cntctfrm_clear"></div>';
									$content .= '</div>';
									break;
								case 'cntctfrm_contact_message':
									$message_visibility = ( 0 == $cntctfrm_options['visible_message'] ) ? ' style="display: none;"' : '';
									$message_label_symbol_required = ( $cntctfrm_options['required_message_field'] == 1 ) ? sprintf ( '<span class="required">%s</span>', $cntctfrm_options['required_symbol'] ) : '';
									$message_class_tooltip = ( $cntctfrm_options['tooltip_display_message'] == 1 ) ? ' cntctfrm_tooltip' : '';
									$message_class_required = $cntctfrm_options['required_message_field'] == 1 ? ' cntctfrm_required_field' : '';
									$message_class_error = ( isset( $cntctfrm_error_message['error_message'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';
									$message_placeholder = ( 1 == $cntctfrm_options['placeholder'] ) ? sprintf( 'placeholder="%s"', $cntctfrm_options['message_help'][ $lang ] ) : '';
									$message_readonly = ( $cntctfrm_options['disabled_message'] ) ? 'readonly="readonly"' : '';

									$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_message_wrap"%s>', $message_visibility );
										if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
											if ( isset( $cntctfrm_error_message['error_message'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_message'] );
											}

											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_message">' );
												$content .= sprintf( '<label for="cntctfrm_contact_message%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['message_label'][ $lang ],
													$message_label_symbol_required
												);
											$content .= '</div>';
										} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_message">' );
												$content .= sprintf( '<label for="cntctfrm_contact_message%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['message_label'][ $lang ],
													$message_label_symbol_required
												);
											$content .= '</div>';

											if ( isset( $cntctfrm_error_message['error_message'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_message'] );
											}
										}

										$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_message%s">', $message_class_tooltip );
											$content .= sprintf( '<textarea %1$s %2$s class="text cntctfrm_contact_message%3$s%4$s" rows="5" cols="30" name="cntctfrm_contact_message" id="cntctfrm_contact_message%5$s">%6$s</textarea>',
												$message_placeholder,
												$message_readonly,
												$message_class_required,
												$message_class_error,
												$form_countid,
												$message
											);

											if ( '1' == $cntctfrm_options['tooltip_display_message'] ) {
												$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
													( isset( $cntctfrm_error_message['error_message'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
													$cntctfrm_options['message_tooltip'][ $lang ]
												);
											}
										$content .= '</div>';

										if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
											if ( isset( $cntctfrm_error_message['error_message'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
												$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_message'] );
											}

											$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_message">' );
												$content .= sprintf( '<label for="cntctfrm_contact_message%1$s">%2$s%3$s</label>',
													$form_countid,
													$cntctfrm_options['message_label'][ $lang ],
													$message_label_symbol_required
												);
											$content .= '</div>';
										}
										$content .= '<div class="cntctfrm_clear"></div>';
									$content .= '</div>';
									break;
								case 'cntctfrm_contact_attachment':
									if ( $cntctfrm_options['attachment'] == 1 ) {
										$attachment_class_tooltip = ( $cntctfrm_options['tooltip_display_attachment'] == 1 ) ? ' cntctfrm_tooltip' : '';
										$attachment_class_error = ( isset( $cntctfrm_error_message['error_attachment'] ) && 'labels' != $cntctfrm_options['error_displaying'] && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '';

										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_attachment_wrap">' );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
												if ( isset( $cntctfrm_error_message['error_attachment'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_attachment'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_attachment">' );
													$content .= sprintf( '<label for="cntctfrm_contact_attachment%1$s">%2$s</label>',
														$form_countid,
														$cntctfrm_options['attachment_label'][ $lang ]
													);
												$content .= '</div>';
											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_attachment">' );
													$content .= sprintf( '<label for="cntctfrm_contact_attachment%1$s">%2$s</label>',
														$form_countid,
														$cntctfrm_options['attachment_label'][ $lang ]
													);
												$content .= '</div>';

												if ( isset( $cntctfrm_error_message['error_attachment'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_attachment'] );
												}
											}

											$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_attachment%s">', $attachment_class_tooltip );
												$content .= sprintf( '<input type="file" class="cntctfrm_contact_attachment%1$s" name="cntctfrm_contact_attachment" id="cntctfrm_contact_attachment%2$s" />',
													$attachment_class_error,
													$form_countid
												);

												if ( $cntctfrm_options['attachment_explanations'] == 1 && '1' == $cntctfrm_options['tooltip_display_attachment'] ) {
													$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip%1$s"><div class="cntctfrm_hidden_help_text">%2$s</div></div>',
														( isset( $cntctfrm_error_message['error_attachment'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : '',
														$cntctfrm_options['attachment_tooltip'][ $lang ]
													);
												} elseif ( $cntctfrm_options['attachment_explanations'] == 1 ) {
													$content .= sprintf( '<label class="cntctfrm_contact_attachment_extensions"><br />%s: html, txt, css, gif, png, jpeg, jpg, tiff, bmp, ai, eps, ps, csv, rtf, pdf, doc, docx, xls, xlsx, zip, rar, wav, mp3, ppt</label>', __( "You can attach the following file formats", 'contact-form-pro' ) );
												}
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												if ( isset( $cntctfrm_error_message['error_attachment'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
													$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_attachment'] );
												}

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_attachment">' );
													$content .= sprintf( '<label for="cntctfrm_contact_attachment%1$s">%2$s</label>',
														$form_countid,
														$cntctfrm_options['attachment_label'][ $lang ]
													);
												$content .= '</div>';
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_send_copy':
									if ( $cntctfrm_options['send_copy'] == 1 ) {
										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_send_copy_wrap">' );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' || $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_send_copy"></div>' );
											}

											$content .= sprintf ( '<div class="cntctfrm_checkbox cntctfrm_checkbox_send_copy">' );
												$content .= sprintf ( '<input type="checkbox" class="cntctfrm_contact_send_copy" value="1" name="cntctfrm_contact_send_copy" id="cntctfrm_contact_send_copy%1$s" %2$s />',
													$form_countid,
													( $send_copy == '1' ) ? ' checked="checked"' : ''
												);
												$content .= sprintf( ' <label for="cntctfrm_contact_send_copy%1$s">%2$s</label>',
													$form_countid,
													$cntctfrm_options['send_copy_label'][ $lang ]
												);
											$content .=	'</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_send_copy"></div>' );
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .=	'</div>';
									}
									break;
								case 'cntctfrm_contact_privacy':
									if ( $cntctfrm_options['display_privacy_check'] == 1 ) {
										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_privacy_wrap">' );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_privacy"></div>' );
											} else if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_privacy"></div>' );
												$content .= sprintf( '<div id="error_privacy_check" class="cntctfrm_error_text"%1$s>%2$s</div>',
													( isset( $cntctfrm_error_message['error_privacy_check'] ) && $cntctfrm_form_count == $form_submited ) ? '' : ' style="display: none;"',
													$cntctfrm_options['privacy_check_error'][ $lang ]
												);
											}

											$content .= '<div class="cntctfrm_checkbox cntctfrm_checkbox_privacy_check">';
												if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {
													$content .= sprintf( '<div id="error_privacy_check" class="cntctfrm_error_text"%1$s style="padding: 0">%2$s</div>',
														( isset( $cntctfrm_error_message['error_privacy_check'] ) && $cntctfrm_form_count == $form_submited ) ? '' : ' style="display: none;"',
														$cntctfrm_options['privacy_check_error'][ $lang ]
													);
												}

												$content .= sprintf( '<input type="checkbox" class="cntctfrm_privacy_check cntctfrm_required_field%1$s" value="1" name="cntctfrm_privacy_check" id="cntctfrm_privacy_check%2$s" %3$s />',
													( isset( $cntctfrm_error_message['error_privacy_check'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_error' : '',
													$form_countid,
													( $privacy_check == '1' ) ? ' checked="checked"' : ''
												);
												$content .= sprintf( ' <label for="cntctfrm_privacy_check%1$s">%2$s</label>',
													$form_countid,
													$cntctfrm_options['privacy_check_label'][ $lang ]
												);

											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												$content .= sprintf( '<div id="error_privacy_check" class="cntctfrm_error_text"%1$s>%2$s</div>',
													( isset( $cntctfrm_error_message['error_privacy_check'] ) && $cntctfrm_form_count == $form_submited ) ? '' : ' style="display: none;"',
													$cntctfrm_options['privacy_check_error'][ $lang ]
												);

												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_privacy"></div>' );
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_contact_optional':
									if ( $cntctfrm_options['display_optional_check'] == 1 ) {
										$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_optional_wrap">' );
											if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' || $cntctfrm_options['labels_settings']['position'] == 'top' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_optional"></div>' );
											}

											$content .= sprintf( '<div class="cntctfrm_checkbox cntctfrm_checkbox_optional_check">' );
												$content .= sprintf( '<input type="checkbox" class="cntctfrm_optional_check" value="1" name="cntctfrm_optional_check" id="cntctfrm_optional_check%1$s" %2$s />',
													$form_countid,
													( $optional_check == '1' ) ? ' checked="checked"' : ''
												 );
												$content .= sprintf(' <label for="cntctfrm_optional_check%1$s">%2$s</label>',
													$form_countid,
													$cntctfrm_options['optional_check_label'][ $lang ]
												);
											$content .= '</div>';

											if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
												$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_optional"></div>' );
											}
											$content .= '<div class="cntctfrm_clear"></div>';
										$content .= '</div>';
									}
									break;
								case 'cntctfrm_subscribe':
									if ( has_filter( 'sbscrbr_cntctfrm_checkbox_add' ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['subscriber']['options']['contact_form'] ) ) || ! empty( $cntctfrm_options['display_subscribe'] ) ) ) {
											$content .= '<div class="cntctfrm_field_wrap cntctfrm_field_checkbox_subscribe_wrap">';
												if ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' || $cntctfrm_options['labels_settings']['position'] == 'top' ) {
													$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_subscribe"></div>' );
												}
												$content .= '<div class="cntctfrm_checkbox cntctfrm_checkbox_subscribe">';
													$cntctfrm_sbscrbr_checkbox = apply_filters( 'sbscrbr_cntctfrm_checkbox_add', array(
														'form_id' => 'cntctfrm_' . $cntctfrm_form_count,
														'display' => ( isset( $cntctfrm_error_message['error_sbscrbr'] ) && $cntctfrm_form_count == $form_submited ) ? $cntctfrm_error_message['error_sbscrbr'] : false
													) );
													if ( isset( $cntctfrm_sbscrbr_checkbox['content'] ) ) {
														$content .= $cntctfrm_sbscrbr_checkbox['content'];
													}
												$content .= '</div>';

												if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
													$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_subscribe"></div>' );
												}
												$content .= '<div class="cntctfrm_clear"></div>';
											$content .= '</div>';
									}
									break;
								case 'cntctfrm_captcha':
									$removed_filters = cntctfrm_handle_captcha_filters( 'remove_filters' );

									if ( has_filter( 'cntctfrm_display_captcha' ) ) {
										$display_captcha_label = '';
										if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['captcha']['enabled'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_captcha'] ) ) ) ) {
											$display_captcha = true;
										}

										if ( array_key_exists( 'google-captcha', $cntctfrm_related_plugins ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['google-captcha']['options']['contact_form'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_google_captcha'] ) ) ) ) {
											$display_google_captcha = true;
										}
										if ( ! empty( $display_captcha ) ) {
											if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) ) {
												$captcha_label = isset( $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['label'] ] ) ? $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['label'] ] : '';
												if ( ! empty( $captcha_label ) ) {
													$captcha_required_symbol = sprintf( '<span class="required">%s</span>', ( ! empty( $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['required_symbol'] ] ) ? $cntctfrm_related_plugins['captcha']['options'][ $cntctfrm_related_plugins['captcha']['required_symbol'] ] : '' ) );
													$display_captcha_label = $captcha_label . $captcha_required_symbol;
												}
											}
										}

										if ( ! empty( $display_captcha ) || ! empty( $display_google_captcha ) ) {
											$captcha_class_tooltip = ( $cntctfrm_options['tooltip_display_captcha'] == 1 ) ? ' cntctfrm_tooltip' : '';

											$content .= sprintf( '<div class="cntctfrm_field_wrap cntctfrm_field_captcha_wrap">' );
												if ( $cntctfrm_options['labels_settings']['position'] == 'top' ) {
													$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_captcha"><label>%s</label></div>', $display_captcha_label );

													if ( isset( $cntctfrm_error_message['error_captcha'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
														$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_captcha'] );
													}
												} elseif ( $cntctfrm_options['labels_settings']['position'] == 'left' || $cntctfrm_options['labels_settings']['position'] == 'right' ) {

													if ( isset( $cntctfrm_error_message['error_captcha'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
														$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_captcha'] );
													}

													$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_captcha"><label>%s</label></div>', $display_captcha_label );
												}

												$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_captcha%s">', $captcha_class_tooltip );

													/**
													 * Change to $content .= apply_filters( 'cntctfrm_display_captcha', '', 'bws_contact' );
													 * @deprecated since 4.0.2
													 * @todo update after 01.02.2017
													 */
													$content .= apply_filters( 'cntctfrm_display_captcha', ( $cntctfrm_form_count == $form_submited ) ? $cntctfrm_error_message : false, '', 'bws_contact' );

													if ( '1' == $cntctfrm_options['tooltip_display_captcha'] ) {
														$content .= sprintf( '<div class="cntctfrm_help_box cntctfrm_hide_tooltip cntctfrm_hidden_help_text_captcha%1$s">',
															( isset( $cntctfrm_error_message['error_captcha'] ) && $cntctfrm_form_count == $form_submited ) ? ' cntctfrm_help_box_error' : ''
														);
															$content .= sprintf( '<div class="cntctfrm_hidden_help_text">%s', $cntctfrm_options['captcha_tooltip'][ $lang ] );
																$content .= sprintf( '<p class="bold_example_text"><img title="" alt="Captcha Example" src="%s" style="margin: 0; position: relative; border:none;"></p>', plugins_url( 'images/captcha_example.jpg', __FILE__ ) );
															$content .= '</div>';
														$content .= '</div>';
													}
													$content .= '<div class="cntctfrm_clear"></div>';
												$content .= '</div>';

												if ( $cntctfrm_options['labels_settings']['position'] == 'bottom' ) {
													if ( isset( $cntctfrm_error_message['error_captcha'] ) && ( 'labels' == $cntctfrm_options['error_displaying'] || 'both' == $cntctfrm_options['error_displaying'] ) && $cntctfrm_form_count == $form_submited ) {
														$content .= sprintf( '<div class="cntctfrm_error_text">%s</div>', $cntctfrm_error_message['error_captcha'] );
													}
													$content .= sprintf( '<div class="cntctfrm_label cntctfrm_label_captcha"><label>%s</label></div>', $display_captcha_label );
												}
												$content .= '<div class="cntctfrm_clear"></div>';
											$content .= '</div>';
										}
									}
									cntctfrm_handle_captcha_filters( 'add_filters', $removed_filters );
									break;
							}
						}
					$content .= '</div>';
				}
				$content .= '<div class="cntctfrm_clear"></div>';
				$content .=	'<div class="cntctfrm_submit_wrap">';

					$cntctfrm_direction = is_rtl() ? 'rtl' : 'ltr';
					$cntctfrm_submit_position_value = array(
						'ltr' => array(
							'left'  => 1,
							'right' => 2
						),
						'rtl' => array(
							'left'  => 2,
							'right' => 1
						),
					);

					for ( $i = 1; $i <= 2; $i++ ) {
						$column = ( $i == 1 ) ? 'first_column' : 'second_column';
						$content .= sprintf( '<div id="cntctfrm_submit_%s" class="cntctfrm_column">', $column );
							if ( $i == $cntctfrm_submit_position_value[ $cntctfrm_direction ][ $cntctfrm_options['submit_position'] ] ) {
								$content .= sprintf( '<div class="cntctfrm_input cntctfrm_input_submit" style="text-align: %s !important;">', $cntctfrm_options['submit_position'] );
									if ( isset( $atts['id'] ) ) {
										$content .= sprintf( '<input type="hidden" value="%s" name="cntctfrmmlt_shortcode_id">', esc_attr( $atts['id'] ) );
									}
									$content .= '<input type="hidden" value="send" name="cntctfrm_contact_action" />';
									$content .= sprintf( '<input type="hidden" value="Version: %s" />', $cntctfrm_plugin_info['Version'] );
									$content .= sprintf( '<input type="hidden" value="%s" name="cntctfrm_language" />', esc_attr( $lang ) );
									$content .= sprintf( '<input type="hidden" value="%s" name="cntctfrm_options_name" />', $options_name );
									$content .= sprintf( '<input type="hidden" value="%s" name="cntctfrm_form_submited" />', $cntctfrm_form_count );
									$content .= sprintf( '<input type="submit" value="%s" class="cntctfrm_contact_submit" />', $cntctfrm_options['submit_label'][ $lang ] );
								$content .= '</div>';
							}
						$content .= '</div>';
					}
					$content .= '<div class="cntctfrm_clear"></div>';
				$content .=	'</div>';

			$content .= '</form>';

			if ( $cntctfrm_options['form_align'] == 'left' || $cntctfrm_options['form_align'] == 'right' ) {
				$content .= '<div class="cntctfrm_clear"></div>';
			}

		}
		return $content;
	}
}

if ( ! function_exists( 'cntctfrm_check_and_send' ) ) {
	function cntctfrm_check_and_send() {
		global $cntctfrm_result, $cntctfrm_options;

		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( ( isset( $_POST['cntctfrm_contact_action'] ) && isset( $_POST['cntctfrm_language'] ) ) || true === $cntctfrm_result ) {
			$cntctfrm_options = get_option( $_POST['cntctfrm_options_name'] );

			if ( isset( $_POST['cntctfrm_contact_action'] ) ) {
				/* Check all input data */
				$cntctfrm_result = cntctfrm_check_form();
			}

			/* If it is good */
			if ( true === $cntctfrm_result ) {
				$_SESSION['cntctfrm_send_mail'] = true;
				if ( $cntctfrm_options['action_after_send'] == 0 ) {
					wp_redirect( $cntctfrm_options['redirect_url'] );
					exit;
				}
			}
		}
	}
}

/* Check all input data */
if ( ! function_exists( 'cntctfrm_check_form' ) ) {
	function cntctfrm_check_form() {
		global $cntctfrm_error_message, $cntctfrm_options, $cntctfrm_related_plugins;

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		$removed_filters = cntctfrm_handle_captcha_filters( 'remove_filters' );

		$language = ( isset( $_POST['cntctfrm_language'] ) && 0 !=$cntctfrm_options['change_label'] ) ? $_POST['cntctfrm_language'] : 'default';
		$cntctfrm_path_of_uploaded_file = $cntctfrm_result = '';
		/* Error messages array */
		$cntctfrm_error_message = array();
		$department = isset( $_POST['cntctfrm_department'] ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_department'] ) ) : "";
		$name = isset( $_POST['cntctfrm_contact_name'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_name'] ) : "";
		$location = isset( $_POST['cntctfrm_location'] ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_location'] ) ) : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_address'] ) : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_subject'] ) : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_message'] ) : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_phone'] ) : "";

		$department = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $department ) ) );
		$name = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) );
		$location = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $location ) ) );
		$address = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) );
		$email = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) );
		$subject = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) );
		$message = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) );
		$phone = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) );

		$privacy_check	= isset( $_POST['cntctfrm_privacy_check'] ) ? $_POST['cntctfrm_privacy_check'] : "";

		if ( $cntctfrm_options['required_department_field'] == 1 )
			$cntctfrm_error_message['error_department'] = $cntctfrm_options['department_error'][ $language ];
		if ( $cntctfrm_options['required_name_field'] == 1 && $cntctfrm_options['display_name_field'] == 1 )
			$cntctfrm_error_message['error_name'] = $cntctfrm_options['name_error'][ $language ];
		if ( $cntctfrm_options['required_location_field'] == 1 && $cntctfrm_options['display_location_field'] == 1 )
			$cntctfrm_error_message['error_location'] = $cntctfrm_options['location_error'][ $language ];
		if ( $cntctfrm_options['required_address_field'] == 1 && $cntctfrm_options['display_address_field'] == 1 )
			$cntctfrm_error_message['error_address'] = $cntctfrm_options['address_error'][ $language ];
		if ( $cntctfrm_options['required_email_field'] == 1 )
			$cntctfrm_error_message['error_email'] = $cntctfrm_options['email_error'][ $language ];
		if ( $cntctfrm_options['required_subject_field'] == 1 )
			$cntctfrm_error_message['error_subject'] = $cntctfrm_options['subject_error'][ $language ];
		if ( $cntctfrm_options['required_message_field'] == 1 )
			$cntctfrm_error_message['error_message'] = $cntctfrm_options['message_error'][ $language ];
		if ( $cntctfrm_options['required_phone_field'] == 1 && $cntctfrm_options['display_phone_field'] == 1 )
			$cntctfrm_error_message['error_phone'] = $cntctfrm_options['phone_error'][ $language ];
		if ( $cntctfrm_options['display_privacy_check'] == 1 )
			$cntctfrm_error_message['error_privacy_check'] = $cntctfrm_options['privacy_check_error'][ $language ];

		$cntctfrm_error_message['error_form'] = $cntctfrm_options['form_error'][ $language ];
		if ( $cntctfrm_options['attachment'] == 1 ) {
			global $cntctfrm_path_of_uploaded_file, $cntctfrm_mime_type;
			$cntctfrm_mime_type = array(
				'html'=>'text/html',
				'htm'=>'text/html',
				'txt'=>'text/plain',
				'css'=>'text/css',
				'gif'=>'image/gif',
				'png'=>'image/x-png',
				'jpeg'=>'image/jpeg',
				'jpg'=>'image/jpeg',
				'jpe'=>'image/jpeg',
				'tiff'=>'image/tiff',
				'tif'=>'image/tiff',
				'bmp'=>'image/x-ms-bmp',
				'ai'=>'application/postscript',
				'eps'=>'application/postscript',
				'ps'=>'application/postscript',
				'csv'=>'text/csv',
				'rtf'=>'application/rtf',
				'pdf'=>'application/pdf',
				'doc'=>'application/msword',
				'docx'=>'application/msword',
				'xls'=>'application/vnd.ms-excel',
				'xlsx'=>'application/vnd.ms-excel',
				'zip'=>'application/zip',
				'rar'=>'application/rar',
				'wav'=>'audio/wav',
				'mp3'=>'audio/mp3',
				'ppt'=>'application/vnd.ms-powerpoint',
				'aar'=>'application/sb-replay',
				'sce'=>'application/sb-scenario' );
			$cntctfrm_error_message['error_attachment'] = $cntctfrm_options['attachment_error'][ $language ];
		}
		/* Check information wich was input in fields */
		if ( $cntctfrm_options['required_department_field'] == 1 && "" != $department )
			unset( $cntctfrm_error_message['error_department'] );
		if ( $cntctfrm_options['display_name_field'] == 1 && $cntctfrm_options['required_name_field'] == 1 && "" != $name )
			unset( $cntctfrm_error_message['error_name'] );
		if ( $cntctfrm_options['display_location_field'] == 1 && $cntctfrm_options['required_location_field'] == 1 && "" != $location )
			unset( $cntctfrm_error_message['error_location'] );
		if ( $cntctfrm_options['display_address_field'] == 1 && $cntctfrm_options['required_address_field'] == 1 && "" != $address )
			unset( $cntctfrm_error_message['error_address'] );
		if ( $cntctfrm_options['required_email_field'] == 1 && "" != $email && is_email( trim( stripslashes( $email ) ) ) )
			unset( $cntctfrm_error_message['error_email'] );
		if ( $cntctfrm_options['required_subject_field'] == 1 && "" != $subject )
			unset( $cntctfrm_error_message['error_subject'] );
		if ( $cntctfrm_options['required_message_field'] == 1 && "" != $message )
			unset( $cntctfrm_error_message['error_message'] );
		if ( $cntctfrm_options['display_phone_field'] == 1 && $cntctfrm_options['required_phone_field'] == 1 && "" != $phone )
			unset( $cntctfrm_error_message['error_phone'] );
		if ( $cntctfrm_options['display_privacy_check'] == 1 && "" != $privacy_check )
			unset( $cntctfrm_error_message['error_privacy_check'] );

		/* If captcha plugin exists */
		$result = true;
		if ( has_filter( 'cntctfrm_check_form' ) )
			$result = apply_filters( 'cntctfrm_check_form', true );

		cntctfrm_handle_captcha_filters( 'add_filters', $removed_filters );

		if ( array_key_exists( 'captcha', $cntctfrm_related_plugins ) && ( ( ! $contact_form_multi_active && ! empty( $cntctfrm_related_plugins['captcha']['enabled'] ) ) || ( $contact_form_multi_active && ! empty( $cntctfrm_options['display_captcha'] ) ) ) ) {
			if ( false === $result ) {/* for CAPTCHA older than PRO - v1.0.7, PLUS - v1.1.0 v FREE - 1.2.5 */
				$cntctfrm_error_message['error_captcha'] = $cntctfrm_options['captcha_error'][ $language ];
			} else if ( ! empty( $result ) && ( is_string( $result ) || is_wp_error( $result ) ) ) {
				$cntctfrm_error_message['error_captcha'] = is_string( $result ) ? $result : $result->get_error_message();
			}
		}

		if ( isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" ) {

			$new_filename = 'cntctfrm_' . md5( sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) . time() . $email . mt_rand() ) . '_' . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] );

			if ( is_multisite() ) {
				if ( defined( 'UPLOADS' ) ) {
					if ( ! is_dir( ABSPATH . UPLOADS ) ) {
						wp_mkdir_p( ABSPATH . UPLOADS );
					}
					$cntctfrm_path_of_uploaded_file = ABSPATH . UPLOADS . $new_filename;
				} else if ( defined( 'BLOGUPLOADDIR' ) ) {
					if ( ! is_dir( BLOGUPLOADDIR ) ) {
						wp_mkdir_p( BLOGUPLOADDIR );
					}
					$cntctfrm_path_of_uploaded_file = BLOGUPLOADDIR . $new_filename;
				} else {
					$uploads = wp_upload_dir();
					if ( ! isset( $uploads['path'] ) && isset( $uploads['error'] ) )
						$cntctfrm_error_message['error_attachment'] = $uploads['error'];
					else
						$cntctfrm_path_of_uploaded_file = $uploads['path'] . "/" . $new_filename;
				}
			} else {
				$uploads = wp_upload_dir();
				if ( ! isset( $uploads['path'] ) && isset ( $uploads['error'] ) )
					$cntctfrm_error_message['error_attachment'] = $uploads['error'];
				else
					$cntctfrm_path_of_uploaded_file = $uploads['path'] . "/" . $new_filename;
			}

			$tmp_path = $_FILES["cntctfrm_contact_attachment"]["tmp_name"];
			$path_info = pathinfo( $cntctfrm_path_of_uploaded_file );

			if ( array_key_exists( strtolower( $path_info['extension'] ), $cntctfrm_mime_type ) ) {
				if ( is_uploaded_file( $tmp_path ) ) {
					if ( move_uploaded_file( $tmp_path, $cntctfrm_path_of_uploaded_file ) ) {
						do_action( 'cntctfrm_get_attachment_data', $cntctfrm_path_of_uploaded_file ); /* for Contact Form to DB */
						unset( $cntctfrm_error_message['error_attachment'] );
					} else {
						$letter_upload_max_size = substr( ini_get( 'upload_max_filesize' ), -1);
						/* $upload_max_size = substr( ini_get('upload_max_filesize'), 0, -1); */
						$upload_max_size = '1';
						switch( strtoupper( $letter_upload_max_size ) ) {
							case 'P':
								$upload_max_size *= 1024;
							case 'T':
								$upload_max_size *= 1024;
							case 'G':
								$upload_max_size *= 1024;
							case 'M':
								$upload_max_size *= 1024;
							case 'K':
								$upload_max_size *= 1024;
								break;
						}
						if ( isset( $_FILES["cntctfrm_contact_attachment"]["size"] ) &&
							 $_FILES["cntctfrm_contact_attachment"]["size"] <= $upload_max_size ) {
							$cntctfrm_error_message['error_attachment'] = $cntctfrm_options['attachment_move_error'][ $language ];
						} else {
							$cntctfrm_error_message['error_attachment'] = $cntctfrm_options['attachment_size_error'][ $language ];
						}
					}
				} else {
					$cntctfrm_error_message['error_attachment'] = $cntctfrm_options['attachment_upload_error'][ $language ];
				}
			}
		} else {
			unset( $cntctfrm_error_message['error_attachment'] );
		}
		if ( 1 == count( $cntctfrm_error_message ) ) {
			if ( has_filter( 'sbscrbr_cntctfrm_checkbox_check' ) ) {
				$cntctfrm_sbscrbr_check = apply_filters( 'sbscrbr_cntctfrm_checkbox_check', array(
					'form_id' => 'cntctfrm_' . $_POST['cntctfrm_form_submited'],
					'email'   => $email,
					'name'    => $name
				) );
				if ( isset( $cntctfrm_sbscrbr_check['response'] ) && $cntctfrm_sbscrbr_check['response']['type'] == 'error' ) {
					$cntctfrm_error_message['error_sbscrbr'] = $cntctfrm_sbscrbr_check['response'];
					return $cntctfrm_result;
				}
			}
			unset( $cntctfrm_error_message['error_form'] );
			/* If all is good - send mail */
			$cntctfrm_result = cntctfrm_send_mail();

			$save_emails  = false;
			if ( ! $contact_form_multi_active && array_key_exists( 'contact-form-to-db' , $cntctfrm_related_plugins ) )
				$save_emails = ! empty( $cntctfrm_related_plugins['contact-form-to-db']['options'][ $cntctfrm_related_plugins['contact-form-to-db']['save_option'] ] );
			else
				$save_emails = ! empty( $cntctfrm_options['save_email_to_db'] );

			if ( $save_emails )
				do_action( 'cntctfrm_check_dispatch', $cntctfrm_result ); /* for Contact Form to DB */
		}
		return $cntctfrm_result;
	}
}

/* Send mail function */
if ( ! function_exists( 'cntctfrm_send_mail' ) ) {
	function cntctfrm_send_mail() {
		global $cntctfrm_options, $cntctfrm_path_of_uploaded_file, $wp_version, $wpdb, $cntctfrm_related_plugins;

		if ( empty( $cntctfrm_related_plugins ) )
			cntctfrm_related_plugins();

		$to = $headers = "";
		$lang = isset( $_POST['cntctfrm_language'] ) ? $_POST['cntctfrm_language'] : 'default';

		$department = isset( $_POST['cntctfrm_department'] ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_department'] ) ): "";
		$name = isset( $_POST['cntctfrm_contact_name'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_name'] ) : "";
		$location = isset( $_POST['cntctfrm_location'] ) ? stripcslashes( htmlspecialchars( $_POST['cntctfrm_location'] ) ) : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_address'] ) : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_subject'] ) : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_message'] ) : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? htmlspecialchars( $_POST['cntctfrm_contact_phone'] ) : "";
		$department_key = isset( $_POST['cntctfrm_department'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_department'] ) ) : "";
		$user_agent = cntctfrm_clean_input( $_SERVER['HTTP_USER_AGENT'] );

		$department = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $department ) ) );
		$name = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) ) );
		$location = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $location ) ) );
		$address = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) ) );
		$email = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) ) );
		$subject = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) ) );
		$message = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) ) );
		$phone = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) ) );
		$department_key = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $department_key ) ) ) );

		$optional = isset( $_POST['cntctfrm_optional_check'] ) ? 'ticked' : 'unticked';

		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( isset( $_SESSION['cntctfrm_send_mail'] ) && $_SESSION['cntctfrm_send_mail'] == true )
			return true;

		if ( 'user' == $cntctfrm_options['select_email'] ) {
			if ( false !== $user = get_user_by( 'login', $cntctfrm_options['user_email'] ) )
				$to = $user->user_email;
		} elseif ( $cntctfrm_options['select_email'] == 'custom' ) {
			$to = $cntctfrm_options['custom_email'];
		} else {
			$to = $cntctfrm_options["departments"]['email'][ $department_key ];
		}

		/* If email options are not certain choose admin email */
		if ( "" == $to )
			$to = get_option( "admin_email" );

		if ( "" != $to ) {
			$user_info_string = $userdomain = '';
			$attachments = array();

			if ( getenv('HTTPS') == 'on' ) {
				$form_action_url = esc_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			} else {
				$form_action_url = esc_url( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			}

			if ( $cntctfrm_options['display_add_info'] == 1 ) {
				$cntctfrm_remote_addr = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP );
				$userdomain = @gethostbyaddr( $cntctfrm_remote_addr );
				if ( $cntctfrm_options['display_add_info'] == 1 ||
						$cntctfrm_options['display_sent_from'] == 1 ||
						$cntctfrm_options['display_coming_from'] == 1 ||
						$cntctfrm_options['display_user_agent'] == 1 ) {
					if ( 1 == $cntctfrm_options['html_email'] )
						$user_info_string .= '<tr><td><br /></td><td><br /></td></tr>';
				}
				if ( $cntctfrm_options['display_sent_from'] == 1 ) {
					if ( 1 == $cntctfrm_options['html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Sent from (IP address)', 'contact-form-pro' ) . ':</td><td>' . $cntctfrm_remote_addr . " ( " . $userdomain . " )" . '</td></tr>';
					else
						$user_info_string .= __( 'Sent from (IP address)', 'contact-form-pro' ) . ': ' . $cntctfrm_remote_addr . " ( " . $userdomain . " )" . "\n";
				}
				if ( $cntctfrm_options['display_date_time'] == 1 ) {
					if ( 1 == $cntctfrm_options['html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Date/Time', 'contact-form-pro' ) . ':</td><td>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . '</td></tr>';
					else
						$user_info_string .= __( 'Date/Time', 'contact-form-pro' ) . ': ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . "\n";
				}
				if ( $cntctfrm_options['display_coming_from'] == 1 ) {
					if ( 1 == $cntctfrm_options['html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Sent from (referer)', 'contact-form-pro' ) . ':</td><td>' . $form_action_url . '</td></tr>';
					else
						$user_info_string .= __( 'Sent from (referer)', 'contact-form-pro' ) . ': ' . $form_action_url . "\n";
				}
				if ( $cntctfrm_options['display_user_agent'] == 1 ) {
					if ( 1 == $cntctfrm_options['html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Using (user agent)', 'contact-form-pro' ) . ':</td><td>' . $user_agent . '</td></tr>';
					else
						$user_info_string .= __( 'Using (user agent)', 'contact-form-pro' ) . ': ' . $user_agent . "\n";
				}
			}
			/* message */
			$message_order_fields = array_merge( $cntctfrm_options['order_fields']['first_column'], $cntctfrm_options['order_fields']['second_column'] );

			if ( 1 == $cntctfrm_options['html_email'] ) {
				$message_text = '<html>
					<head>
						<title>'. __( "Contact from", 'contact-form-pro' ) . ' ' . get_bloginfo( 'name' ) . '</title>
					</head>
					<body>
						<table>
							<tr>
								<td>' . __( "Site", 'contact-form-pro' ) . '</td>
								<td>' . get_bloginfo( "url" ) . '</td>
							</tr>';
				foreach ( $message_order_fields as $field ) {
					$field = str_replace( 'cntctfrm_contact_', '', $field );
					switch ( $field ) {
						case "department":
							if ( 'departments' == $cntctfrm_options['select_email'] ) {
								$message_text .= '<tr><td width="160">';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['department_label'][ $lang ] : __( "Department", 'contact-form-pro' );
								$message_text .= '</td><td>'. $cntctfrm_options["departments"]['name'][ $department_key ] .'</td></tr>';
							}
							break;
						case "optional":
							if ( 1 == $cntctfrm_options['display_optional_check'] ) {
								$message_text .= '<tr><td>';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['optional_check_label'][ $lang ] : __( "Optional checkbox", 'contact-form-pro' );
								$message_text .= '</td><td>' . $optional . '</td></tr>';
							}
							break;
						case "name":
							if ( 1 == $cntctfrm_options['display_name_field'] ) {
								$message_text .= '<tr><td width="160">';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['name_label'][ $lang ] : __( "Name", 'contact-form-pro' );
								$message_text .= '</td><td>'. $name .'</td></tr>';
							}
							break;
						case "location":
							if ( 1 == $cntctfrm_options['display_location_field'] ) {
								$message_text .= '<tr><td width="160">';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['location_label'][ $lang ] : __( "Location", 'contact-form-pro' );
								$message_text .= '</td><td>'. $location .'</td></tr>';
							}
							break;
						case "address":
							if ( 1 == $cntctfrm_options['display_address_field'] ) {
								$message_text .= '<tr><td>';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['address_label'][ $lang ] : __( "Address", 'contact-form-pro' );
								$message_text .= '</td><td>'. $address .'</td></tr>';
							}
							break;
						case "email":
							$message_text .= '<tr><td>';
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['email_label'][ $lang ] : __( "Email", 'contact-form-pro' );
							$message_text .= '</td><td>'. $email .'</td></tr>';
							break;
						case "subject":
							$message_text .= '<tr><td>';
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['subject_label'][ $lang ] : __( "Subject", 'contact-form-pro' );
							$message_text .= '</td><td>' . $subject .'</td></tr>';
							break;
						case "message":
							$message_text .= '<tr><td>';
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['message_label'][ $lang ] : __( "Message", 'contact-form-pro' );
							$message_text .= '</td><td>' . $message .'</td></tr>';
							break;
						case "phone":
							if ( 1 == $cntctfrm_options['display_phone_field'] ) {
								$message_text .= '<tr><td>';
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['phone_label'][ $lang ] : __( "Phone Number", 'contact-form-pro' );
								$message_text .= '</td><td>'. $phone .'</td></tr>';
							}
							break;
					}
				}
				$message_text .= '<tr><td><br /></td><td><br /></td></tr>';

				$message_text_for_user = $message_text . '</table></body></html>';
				$message_text .= $user_info_string . '</table></body></html>';
			} else {
				$message_text = __( "Site", 'contact-form-pro' ) . ': ' . get_bloginfo("url") . "\n";;
				foreach ( $message_order_fields as $field ) {
					$field = str_replace( 'cntctfrm_contact_', '', $field );
					switch ( $field ) {
						case "department":
							if ( 'departments' == $cntctfrm_options['select_email'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['department_label'][ $lang ] : __( "Department", 'contact-form-pro' );
								$message_text .= ': '. $cntctfrm_options["departments"]['name'][ $department_key ] . "\n";
							}
							break;
						case "optional":
							if ( 1 == $cntctfrm_options['display_optional_check'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['optional_check_label'][ $lang ] : __( "Optional checkbox", 'contact-form-pro' );
								$message_text .= ': ' . $optional . "\n";
							}
							break;
						case "name":
							if ( 1 == $cntctfrm_options['display_name_field'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['name_label'][ $lang ] : __( "Name", 'contact-form-pro' );
								$message_text .= ': '. $name . "\n";
							}
							break;
						case "location":
							if ( 1 == $cntctfrm_options['display_location_field'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['location_label'][ $lang ] : __( "Location", 'contact-form-pro' );
								$message_text .= ': '. $location . "\n";
							}
							break;
						case "address":
							if ( 1 == $cntctfrm_options['display_address_field'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['address_label'][ $lang ] : __( "Address", 'contact-form-pro' );
								$message_text .= ': '. $address . "\n";
							}
							break;
						case "email":
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['email_label'][ $lang ] : __( "Email", 'contact-form-pro' );
							$message_text .= ': ' . $email . "\n";
							break;
						case "subject":
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['subject_label'][ $lang ] : __( "Subject", 'contact-form-pro' );
							$message_text .= ': ' . $subject . "\n";
							break;
						case "message":
							$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['message_label'][ $lang ] : __( "Message", 'contact-form-pro' );
							$message_text .= ': ' . $message ."\n";
							break;
						case "phone":
							if ( 1 == $cntctfrm_options['display_phone_field'] ) {
								$message_text .= ( 1 == $cntctfrm_options['change_label_in_email'] ) ? $cntctfrm_options['phone_label'][ $lang ] : __( "Phone Number", 'contact-form-pro' );
								$message_text .= ': '. $phone . "\n";
							}
							break;
					}
				}
				$message_text .= "\n";

				$message_text_for_user = $message_text;
				$message_text .= $user_info_string;
			}

			$save_emails  = false;
			if ( ! $contact_form_multi_active && array_key_exists( 'contact-form-to-db' , $cntctfrm_related_plugins ) )
				$save_emails = ! empty( $cntctfrm_related_plugins['contact-form-to-db']['options'][ $cntctfrm_related_plugins['contact-form-to-db']['save_option'] ] );
			else
				$save_emails = ! empty( $cntctfrm_options['save_email_to_db'] );

			/* for Contact Form to DB */
			if ( $save_emails )
				do_action( 'cntctfrm_get_mail_data', array( 'sendto' => $to, 'refer' => $form_action_url, 'useragent' => $user_agent ) );

			$message_text_for_auto_response = str_replace( '%%SITENAME%%', get_bloginfo( 'name' ), str_replace( '%%MESSAGE%%', $message, str_replace( '%%NAME%%', $name, html_entity_decode( $cntctfrm_options['auto_response_message'] ) ) ) );

			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			/* 'from' name */
			$from_field_name = ( 'custom' == $cntctfrm_options['select_from_field'] ) ? stripslashes( $cntctfrm_options['from_field'] ) : $name;
			/* 'from' email */
			$from_email = ( 'custom' == $cntctfrm_options['from_email'] ) ? stripslashes( $cntctfrm_options['custom_from_email'] ) : $email;
			if ( $from_email == "" || ! is_email( $from_email ) ) {
				$sitename = strtolower( filter_var( $_SERVER['SERVER_NAME'], FILTER_SANITIZE_STRING ) );
				if ( substr( $sitename, 0, 4 ) == 'www.' ) {
					$sitename = substr( $sitename, 4 );
				}
				$from_email = 'wordpress@' . $sitename;
			}

			if ( ( is_plugin_active( 'email-queue/email-queue.php' ) || is_plugin_active( 'email-queue-pro/email-queue-pro.php' ) ) && function_exists( 'mlq_if_mail_plugin_is_in_queue' ) && mlq_if_mail_plugin_is_in_queue( 'contact-form-pro/contact_form_pro.php' ) ) {
				/* if email-queue plugin is active and this plugin's "in_queue" status is 'ON' */
				/* attachment file source */
				$attachment_file = ( 1 == $cntctfrm_options['attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) ? $cntctfrm_path_of_uploaded_file : '';
				/* headers */
				/* content type */
				$headers .= ( 1 == $cntctfrm_options['html_email'] ) ? 'Content-type: text/html; charset=utf-8' . "\n" : 'Content-type: text/plain; charset=utf-8' . "\n";
				/* reply-to */
				if ( 1 == $cntctfrm_options['header_reply_to'] )
					$headers .= 'Reply-To: ' . $email . "\n";
				$headers_for_auto_response = $headers;

				/* Additional headers */
				$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>';
				$headers_for_auto_response .= 'From: ' . $to . '';

				if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && 1 == $_POST['cntctfrm_contact_send_copy'] ) {
					do_action( 'cntctfrm_get_mail_data_for_mlq', 'contact-form-pro/contact_form_pro.php', $email, $subject, $message_text_for_user, $attachment_file, false, $headers );
				}
				if ( 1 == $cntctfrm_options['auto_response'] ) {
					do_action( 'cntctfrm_get_mail_data_for_mlq', 'contact-form-pro/contact_form_pro.php', $email, $subject, $message_text_for_auto_response, $attachment_file, $to, $headers_for_auto_response );
				}
				global $mlq_mail_result;
				do_action( 'cntctfrm_get_mail_data_for_mlq', 'contact-form-pro/contact_form_pro.php', $to, $subject, $message_text, $attachment_file, $email, $headers );
				/* return $mail_result = true if email-queue has successfully inserted mail in its DB; in other case - return false */
				return $mail_result = $mlq_mail_result;
			} else {
				if ( 'wp-mail' == $cntctfrm_options['mail_method'] ) {
					/* To send HTML mail, the Content-type header must be set */
					if ( 1 == $cntctfrm_options['html_email'] )
						$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
					else
						$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";

					if ( 1 == $cntctfrm_options['header_reply_to'] )
						$headers .= 'Reply-To: ' . $email . "\n";

					$headers_for_auto_response = $headers;

					/* Additional headers */
					$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>';

					$headers_for_auto_response .= 'From: ' . $to . '';

					if ( $cntctfrm_options['attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "") {
						$path_parts = pathinfo( $cntctfrm_path_of_uploaded_file );
						$cntctfrm_path_of_uploaded_file_changed = $path_parts['dirname'] . '/' . preg_replace( '/^cntctfrm_[A-Z,a-z,0-9]{32}_/i', '', $path_parts['basename'] );

						if ( ! @copy( $cntctfrm_path_of_uploaded_file, $cntctfrm_path_of_uploaded_file_changed ) )
							$cntctfrm_path_of_uploaded_file_changed = $cntctfrm_path_of_uploaded_file;

						$attachments = array( $cntctfrm_path_of_uploaded_file_changed );
					}

					if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $_POST['cntctfrm_contact_send_copy'] == 1 ) {
						wp_mail( $email, $subject, $message_text_for_user, $headers, $attachments );
					}
					if ( 1 == $cntctfrm_options['auto_response'] ) {
						wp_mail( $email, $subject, $message_text_for_auto_response, $headers_for_auto_response, false );
					}

					/* Mail it */
					$mail_result = wp_mail( $to, $subject, $message_text, $headers, $attachments );
					/* Delete attachment */
					if ( 1 == $cntctfrm_options['attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"]
						&& $cntctfrm_path_of_uploaded_file_changed != $cntctfrm_path_of_uploaded_file ) {
						@unlink( $cntctfrm_path_of_uploaded_file_changed );
					}
					if ( $cntctfrm_options['attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" && $cntctfrm_options['delete_attached_file'] == '1' ) {
						@unlink( $cntctfrm_path_of_uploaded_file );
					}
					return $mail_result;
				} else {
					/* Set headers */
					$headers  .= 'MIME-Version: 1.0' . "\n";

					$headers_for_auto_response = $headers;
					$headers_for_auto_response .= 'From: ' . get_bloginfo( 'name' ) . ' <' . stripslashes( $to ) . '>' . "\n";

					if ( 'custom' == $cntctfrm_options['select_from_field'] )
						$from_field_name = stripslashes( $cntctfrm_options['from_field'] );
					else
						$from_field_name = $name;

					/* Additional headers */
					if ( 'custom' == $cntctfrm_options['from_email'] )
						$headers .= 'From: ' . $from_field_name . ' <' . stripslashes( $cntctfrm_options['custom_from_email'] ) . '>' . "\n";
					else
						$headers .= 'From: ' . $from_field_name . ' <' . stripslashes( $email ) . '>' . "\n";

					if ( 1 == $cntctfrm_options['header_reply_to'] )
						$headers .= 'Reply-To: ' . $email . "\n";

					if ( $cntctfrm_options['attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "") {
						global $cntctfrm_path_of_uploaded_file;
						$message_block = $message_text;
						$message_block_for_user = $message_text_for_user;

						$bound_text = 	"jimmyP123";
						$bound = 	"--" . $bound_text . "";
						$bound_last = 	"--" . $bound_text . "--";

						$headers .= "Content-Type: multipart/mixed; boundary=\"$bound_text\"";
						$headers_for_auto_response .= "Content-Type: multipart/mixed; boundary=\"$bound_text\"";

						$message_text = $message_text_for_user = __( "If you can see this MIME, it means that the MIME type is not supported by your email client!", 'contact-form-pro' ) . "\n";

						if ( 1 == $cntctfrm_options['html_email'] ) {
							$message_text .= $bound . "\n" . "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
							$message_text_for_user .= $bound . "\n" . "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block_for_user . "\n\n";
						} else {
							$message_text .= $bound . "\n" . "Content-Type: text/plain; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
							$message_text_for_user .= $bound . "\n" . "Content-Type: text/plain; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block_for_user . "\n\n";
						}

						$file = file_get_contents( $cntctfrm_path_of_uploaded_file );

						$message_text .= $bound . "\n" .
							"Content-Type: application/octet-stream; name=\"" . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) ."\"\n" .
							"Content-Description: " . basename( $cntctfrm_path_of_uploaded_file ) . "\n" .
							"Content-Disposition: attachment;\n" . " filename=\"" . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) . "\"; size=" . filesize( $cntctfrm_path_of_uploaded_file ) . ";\n" .
							"Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n" .
							$bound_last;
						$message_text_for_user .= $bound . "\n" .
							"Content-Type: application/octet-stream; name=\"" . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) ."\"\n" .
							"Content-Description: " . basename( $cntctfrm_path_of_uploaded_file ) . "\n" .
							"Content-Disposition: attachment;\n" . " filename=\"" . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) . "\"; size=" . filesize( $cntctfrm_path_of_uploaded_file ) . ";\n" .
							"Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n" .
							$bound_last;
					} else {
						/* To send HTML mail, the Content-type header must be set */
						if ( 1 == $cntctfrm_options['html_email'] ) {
							$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
							$headers_for_auto_response .= 'Content-type: text/html; charset=utf-8' . "\n";
						} else {
							$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";
							$headers_for_auto_response .= 'Content-type: text/plain; charset=utf-8' . "\n";
						}
					}

					if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $_POST['cntctfrm_contact_send_copy'] == 1 ) {
						@mail( $email, $subject, $message_text_for_user, $headers );
					}
					if ( 1 == $cntctfrm_options['auto_response'] ) {
						@mail( $email, $subject, $message_text_for_auto_response, $headers_for_auto_response );
					}
					$mail_result = @mail( $to, $subject, $message_text, $headers);
					/* delete attachment */
					if ( $cntctfrm_options['attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" && $cntctfrm_options['delete_attached_file'] == '1' ) {
						@unlink( $cntctfrm_path_of_uploaded_file );
					}
					return $mail_result;
				}
			}
		}
		return false;
	}
}

/**
 * Function that is used by email-queue to check for compatibility
 * @return void
 */
if ( ! function_exists( 'cntctfrm_check_for_compatibility_with_mlq' ) ) {
	function cntctfrm_check_for_compatibility_with_mlq() {
		return false;
	}
}

if ( ! function_exists( 'cntctfrm_plugin_action_links' ) ) {
	function cntctfrm_plugin_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row. */
		static $this_plugin;
		if ( ! $this_plugin )
			$this_plugin = 'contact-form-pro/contact_form_pro.php';

		if ( $file == $this_plugin ) {
			$settings_link = '<a href="admin.php?page=contact_form_pro.php">' . __( 'Settings', 'contact-form-pro' ) . '</a>';
			/* array_unshift( $links, $settings_link ); */
			/* $links["settings"] = $settings_link;	*/
			$settings_link_array = array();
			$settings_link_array["settings"] = $settings_link;
			foreach ( $links as $key => $value ) {
				if ( "settings" != $key )
					$settings_link_array[ $key ] = $value;
			}
			$links = $settings_link_array;
		}
		return $links;
	}
}

if ( ! function_exists( 'cntctfrm_register_plugin_links' ) ) {
	function cntctfrm_register_plugin_links( $links, $file ) {
		$base = 'contact-form-pro/contact_form_pro.php';
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=contact_form_pro.php">' . __( 'Settings', 'contact-form-pro' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538909" target="_blank">' . __( 'FAQ', 'contact-form-pro' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support', 'contact-form-pro' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists( 'cntctfrm_clean_input' ) ) {
	function cntctfrm_clean_input( $string, $preserve_space = 0 ) {
		if ( is_string( $string ) ) {
			if ( $preserve_space ) {
				return cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ), $preserve_space );
			}
			return trim( cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ) ) );
		} else if ( is_array( $string ) ) {
			reset( $string );
			while ( list( $key, $value ) = each( $string ) ) {
				$string[ $key ] = cntctfrm_clean_input($value,$preserve_space);
			}
			return $string;
		} else {
			return $string;
		}
	}
}

/* functions for protecting and validating form vars */
if ( ! function_exists( 'cntctfrm_sanitize_string' ) ) {
	function cntctfrm_sanitize_string( $string, $preserve_space = 0 ) {
		if ( ! $preserve_space )
			$string = preg_replace("/ +/", ' ', trim( $string ) );

		return preg_replace( "/[<>]/", '_', $string );
	}
}

if ( ! function_exists ( 'cntctfrm_admin_head' ) ) {
	function cntctfrm_admin_head() {
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'contact_form_pro.php' ) {
			global $wp_version, $cntctfrm_plugin_info;

			wp_enqueue_style( 'cntctfrm_stylesheet', plugins_url( 'css/style.css', __FILE__ ), array( 'wp-color-picker' ), $cntctfrm_plugin_info["Version"] );

			if ( isset( $_GET['action'] ) && 'appearance' == $_GET['action'] ) {
				wp_enqueue_style( 'cntctfrm_form_style', plugins_url( 'css/form_style.css', __FILE__ ), false, $cntctfrm_plugin_info["Version"] );
			}

			$script_vars = array(
				'cntctfrm_nonce' 			=> wp_create_nonce( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' ),
				'cntctfrm_confirm_text'  	=> __( 'Are you sure that you want to delete this language data?', 'contact-form-pro' )
			);

			if ( wp_is_mobile() )
				wp_enqueue_script( 'jquery-touch-punch' );

			wp_enqueue_script( 'cntctfrm_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ) );
			wp_localize_script( 'cntctfrm_script', 'cntctfrm_ajax', $script_vars );

			/* Add tooltip */
			if ( ! function_exists( 'bws_add_tooltip_in_admin' ) )
				require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
			$tooltip_args = array(
				'tooltip_id'	=> 'cntctfrm_install_multi_tooltip',
				'css_selector' 	=> '#cntctfrm_show_multi_notice',
				'actions' 		=> array(
					'click' 	=> true,
					'onload' 	=> true
				),
				'content' 			=> '<h3>' . __( 'Add multiple forms', 'contact-form-pro' ) . '</h3>' .'<p>' . __( 'Install Contact Form Multi plugin to create unlimited number of contact forms.', 'contact-form-pro' ) . '</p>',
				'buttons'			=> array(
					array(
						'type' => 'link',
						'link' => 'https://bestwebsoft.com/products/wordpress/plugins/contact-form-multi/?k=57d8351b1c6b67d3e0600bd9a680c283&pn=3&amp;v=' . $cntctfrm_plugin_info["Version"] . '&amp;wp_v=' . $wp_version,
						'text' => __( 'Learn more', 'contact-form-pro' )
					),
					'close' => array(
						'type' => 'dismiss',
						'text' => __( 'Close', 'contact-form-pro' )
					),
				),
				'position' => array(
					'edge' 		=> 'top',
					'align' 	=> is_rtl() ? 'right' : 'left',
				),
			);
			bws_add_tooltip_in_admin( $tooltip_args );

			if ( isset( $_GET['action'] ) && 'custom_code' == $_GET['action'] )
				bws_plugins_include_codemirror();
		}
	}
}

if ( ! function_exists ( 'cntctfrm_add_admin_script' ) ) {
	function cntctfrm_add_admin_script() {
		global $cntctfrm_options;

		if ( isset( $_REQUEST['page'], $_GET['action'] ) && $_REQUEST['page'] == 'contact_form_pro.php' && 'appearance' == $_GET['action'] ) { ?>
			<script type="text/javascript">
				(function($) {
					$(document).ready( function() {
						$( 'div.cntctfrm_contact_form .cntctfrm_error' ).on( 'input paste change', function() {
							$( this ).removeClass( 'cntctfrm_error' );
							$( this ).next( '.cntctfrm_help_box' ).removeClass( 'cntctfrm_help_box_error' );
						});
						if ( ! $( "input:checkbox[name='cntctfrm_placeholder']" ).is( ':checked' ) ) {
							$( '#cntctfrm_contact_form input.text, #cntctfrm_contact_form textarea' ).removeAttr( 'placeholder' );
						};
						$( "input:checkbox[name='cntctfrm_placeholder']" ).change( function() {
							if ( $( this ).is( ':checked' ) ){
								$( '#cntctfrm_contact_name' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['name_help']['default'] ); ?>" );
								$( '#cntctfrm_contact_address' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['address_help']['default'] ); ?>" );
								$( '#cntctfrm_contact_email' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['email_help']['default'] ); ?>" );
								$( '#cntctfrm_contact_subject' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['subject_help']['default'] ); ?>" );
								$( '#cntctfrm_contact_phone' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['phone_help']['default'] ); ?>" );
								$( '#cntctfrm_contact_message' ).attr( 'placeholder', "<?php echo stripcslashes( $cntctfrm_options['message_help']['default'] ); ?>" );
							} else {
								$( '#cntctfrm_contact_form input.text, #cntctfrm_contact_form textarea' ).removeAttr( 'placeholder' );
							};
						});
					});
				})(jQuery);
			</script>
		<?php }
	}
}

if ( ! function_exists( 'cntctfrm_wp_enqueue_style' ) ) {
	function cntctfrm_wp_enqueue_style() {
		global $cntctfrm_plugin_info;
		wp_enqueue_style( 'cntctfrm_form_style', plugins_url( 'css/form_style.css', __FILE__ ), false, $cntctfrm_plugin_info["Version"] );
	}
}

/* add css style to contact form */
if ( ! function_exists ( 'cntctfrm_wp_footer' ) ) {
	function cntctfrm_wp_footer() {
		global $cntctfrm_form_count, $cntctfrm_stile_options;

		if ( wp_script_is( 'cntctfrm_frontend_script', 'registered' ) )
			wp_enqueue_script( 'cntctfrm_frontend_script' );

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		for( $count = 1; $count <= $cntctfrm_form_count; $count ++ ) {
			$form_countid = ( 1 == $count ? '#cntctfrm_contact_form' : '#cntctfrm_contact_form_' . $count );
			$options = get_option( $cntctfrm_stile_options[ $count ] ); ?>
			<style type="text/css">
				<?php if ( '' != $options['label_color'] ) { ?>
					<?php echo $form_countid; ?> label,
					<?php echo $form_countid; ?> .cptch_block,
					<?php echo $form_countid; ?> .cptchpr_block {
						color: <?php echo $options['label_color']; ?>;
					}
				<?php }
				if ( '' != $options['error_color'] ) { ?>
					<?php echo $form_countid; ?> .cntctfrm_error_text {
						color: <?php echo $options['error_color']; ?>;
					}
					<?php echo $form_countid; ?> .cptch_error,
					<?php echo $form_countid; ?> .cptchpr_error {
						color: <?php echo $options['error_color']; ?> !important;
					}
				<?php }
				if ( '' != $options['error_input_color'] ) { ?>
					<?php echo $form_countid; ?> input.text.cntctfrm_error,
					<?php echo $form_countid; ?> textarea.cntctfrm_error,
					<?php echo $form_countid; ?> .cntctfrm_error {
						background: <?php echo $options['error_input_color']; ?> !important;
					}
				<?php }
				if ( '' != $options['error_input_border_color'] ) { ?>
					<?php echo $form_countid; ?> input.text.cntctfrm_error,
					<?php echo $form_countid; ?> textarea.cntctfrm_error,
					<?php echo $form_countid; ?> .cntctfrm_error {
						border-color: <?php echo $options['error_input_border_color']; ?> !important;
					}
				<?php }
				if ( '' != $options['input_background'] ) { ?>
					<?php echo $form_countid; ?> input.text,
					<?php echo $form_countid; ?> textarea,
					<?php echo $form_countid; ?> select,
					<?php echo $form_countid; ?> .cptch_block input,
					<?php echo $form_countid; ?> .cptchpr_block input {
						background-color: <?php echo $options['input_background']; ?>;
					}
				<?php }
				if ( '' != $options['input_color'] ) { ?>
					<?php echo $form_countid; ?> input.text,
					<?php echo $form_countid; ?> textarea,
					<?php echo $form_countid; ?> select,
					<?php echo $form_countid; ?> .cptch_block input,
					<?php echo $form_countid; ?> .cptchpr_block input {
						color: <?php echo $options['input_color']; ?>;
					}
				<?php }
				if ( '' != $options['input_placeholder_color'] ) { ?>
					<?php echo $form_countid; ?> input::-webkit-input-placeholder,
					<?php echo $form_countid; ?> textarea::-webkit-input-placeholder {
						color: <?php echo $options['input_placeholder_color']; ?>;
					}
					<?php echo $form_countid; ?> input::-moz-placeholder,
					<?php echo $form_countid; ?> textarea::-moz-placeholder {
						color: <?php echo $options['input_placeholder_color']; ?>;
					}
					<?php echo $form_countid; ?> input:-ms-input-placeholder,
					<?php echo $form_countid; ?> textarea:-ms-input-placeholder {
						color: <?php echo $options['input_placeholder_color']; ?>;
					}
					<?php echo $form_countid; ?> input:-moz-placeholder,
					<?php echo $form_countid; ?> textarea:-moz-placeholder {
						color: <?php echo $options['input_placeholder_color']; ?>;
					}
				<?php }
				if ( '' != $options['input_placeholder_error_color'] ) { ?>
					<?php echo $form_countid; ?> input.cntctfrm_error::-webkit-input-placeholder,
					<?php echo $form_countid; ?> textarea.cntctfrm_error::-webkit-input-placeholder {
						color: <?php echo $options['input_placeholder_error_color']; ?>;
					}
					<?php echo $form_countid; ?> input.cntctfrm_error::-moz-placeholder,
					<?php echo $form_countid; ?> textarea.cntctfrm_error::-moz-placeholder {
						color: <?php echo $options['input_placeholder_error_color']; ?>;
					}
					<?php echo $form_countid; ?> input.cntctfrm_error:-ms-input-placeholder,
					<?php echo $form_countid; ?> textarea.cntctfrm_error:-ms-input-placeholder {
						color: <?php echo $options['input_placeholder_error_color']; ?>;
					}
					<?php echo $form_countid; ?> input.cntctfrm_error:-moz-placeholder,
					<?php echo $form_countid; ?> textarea.cntctfrm_error:-moz-placeholder {
						color: <?php echo $options['input_placeholder_error_color']; ?>;
					}
				<?php }
				if ( '' != $options['border_input_width'] ) { ?>
					<?php echo $form_countid; ?> input.text,
					<?php echo $form_countid; ?> input.cntctfrm_contact_submit,
					<?php echo $form_countid; ?> textarea,
					<?php echo $form_countid; ?> select,
					<?php echo $form_countid; ?> .cptch_block input,
					<?php echo $form_countid; ?> .cptchpr_block input {
						border-width: <?php echo $options['border_input_width']; ?>px;
					}
				<?php }
				if ( '' != $options['border_input_color'] ) { ?>
					<?php echo $form_countid; ?> input.text,
					<?php echo $form_countid; ?> input.cntctfrm_contact_submit,
					<?php echo $form_countid; ?> textarea,
					<?php echo $form_countid; ?> select,
					<?php echo $form_countid; ?> .cptch_block input,
					<?php echo $form_countid; ?> .cptchpr_block input {
						border-color: <?php echo $options['border_input_color']; ?>;
					}
				<?php }
				if ( '' != $options['button_width'] || '' != $options['button_backgroud'] || '' != $options['button_color'] || '' != $options['border_button_color'] ) { ?>
					<?php echo $form_countid; ?> .cntctfrm_contact_submit {
						<?php if ( '' != $options['button_width'] ) { ?>
							width: <?php echo $options['button_width']; ?>px;
						<?php }
						if ( '' != $options['button_backgroud'] ) { ?>
							background: <?php echo $options['button_backgroud']; ?>;
						<?php }
						if ( '' != $options['button_color'] ) { ?>
							color: <?php echo $options['button_color']; ?>;
						<?php }
						if ( '' != $options['border_button_color'] ) { ?>
							border-color: <?php echo $options['border_button_color']; ?>;
						<?php } ?>
					}
				<?php } ?>
			</style>
		<?php } ?>
		<script type="text/javascript">
			(function($){
				$(document).ready(function() {
					$( '.cntctfrm_contact_form .cntctfrm_error, .cntctfrm_contact_form input[name^=cptch]' ).on( 'input paste change', function() {
						$( this ).removeClass( 'cntctfrm_error' );
						$( this ).next( '.cntctfrm_help_box' ).removeClass( 'cntctfrm_help_box_error' );
						$( this ).parent( '.cptch_block, .cptchpr_block' ).next( '.cntctfrm_help_box' ).removeClass( 'cntctfrm_help_box_error' );
						if ( $( this ).is( 'input[type=checkbox].cntctfrm_privacy_check' ) && $( this ).is( ':checked' ) ) {
							$( this ).prev( '#error_privacy_check' ).css( 'display', 'none' );
						}
					});

					$( '.cntctfrm_contact_form .cntctfrm_help_box' ).bind( 'show_tooltip', function() {
						$help_box = $( this ).children();
						$( this ).removeClass( 'cntctfrm_hide_tooltip' );
						$( this ).addClass( 'cntctfrm_show_tooltip' );
						if ( $help_box.offset().left + $help_box.innerWidth() > $( window ).width() ) {
							$help_box.addClass( 'cntctfrm_hidden_help_text_down' );
						}
					});

					$( '.cntctfrm_contact_form .cntctfrm_help_box' ).bind( 'hide_tooltip', function() {
						$help_box = $( this ).children();
						$( this ).removeClass( 'cntctfrm_show_tooltip' );
						$( this ).addClass( 'cntctfrm_hide_tooltip' );
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
				});
			})(jQuery);
		</script>
	<?php }
}

if ( ! function_exists ( 'cntctfrm_add_language' ) ) {
	function cntctfrm_add_language() {
		$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( $is_ajax )
			check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' );
		else
			$_POST['cntctfrm_change_tab'] = $_REQUEST['cntctfrm_languages'];

		$lang_slug = $is_ajax ? $_REQUEST['lang'] : $_REQUEST['cntctfrm_languages'];
		$lang = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', htmlspecialchars( $lang_slug ) ) ) );

		/* Check contact-form-multi plugin */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( $contact_form_multi_active ) {
			if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) && $options = get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] ) ) {
				/**/
			} else {
				$options = get_option( 'cntctfrmmlt_options' );
			}
		} else {
			$options = get_option( 'cntctfrm_options' );
		}

		if ( ! in_array( $lang, $options['language'] ) ) {
			$options['language'][] = $lang;

			if ( $contact_form_multi_active ) {
				if ( 'pro' == $contact_form_multi_active && $multi_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
					update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'], $options );
				} else {
					$multi_options_main = get_option( 'cntctfrmmlt_options_main' );
					update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'], $options );
				}
			} else {
				update_option( 'cntctfrm_options', $options );
			}
		}

		if ( ! $contact_form_multi_active ) {
			$result = __( "Use shortcode", 'contact-form-pro' ) . ' <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=' . $lang . ']</span> ' . __( "for this language", 'contact-form-pro' );
		} else {
			$result = __( "Use shortcode", 'contact-form-pro' ) . ' <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=' . $lang . ' id=' . $_SESSION['cntctfrmmlt_id_form'] . ']</span> ' . __( "for this language", 'contact-form-pro' );
		}

		if ( $is_ajax ) {
			echo json_encode( $result );
			die();
		}
	}
}

if ( ! function_exists ( 'cntctfrm_remove_language' ) ) {
	function cntctfrm_remove_language() {
		$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		if ( $is_alax )
			check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' );
		else
			$_POST['cntctfrm_change_tab'] = 'default';

		/* Check contact-form-multi plugin */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( $contact_form_multi_active ) {
			if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) && get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] ) ) {
				$options = get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] );
			} else {
				$options = get_option( 'cntctfrmmlt_options' );
			}
		} else {
			$options = get_option( 'cntctfrm_options' );
		}

		$lang = $is_ajax ? $_REQUEST['lang'] : $_REQUEST['cntctfrm_delete_button'];

		if ( $key = array_search( $lang, $options['language'] ) !== false )
			$options['language'] = array_diff( $options['language'], array( $lang ) );
		if ( isset( $options['name_label'][ $lang ] ) )
			unset( $options['name_label'][ $lang ] );
		if ( isset( $options['location_label'][ $lang ] ) )
			unset( $options['location_label'][ $lang ] );
		if ( isset( $options['address_label'][ $lang ] ) )
			unset( $options['address_label'][ $lang ] );
		if ( isset( $options['email_label'][ $lang ] ) )
			unset( $options['email_label'][ $lang ] );
		if ( isset( $options['phone_label'][ $lang ] ) )
			unset( $options['phone_label'][ $lang ] );
		if ( isset( $options['subject_label'][ $lang ] ) )
			unset( $options['subject_label'][ $lang ] );
		if ( isset( $options['message_label'][ $lang ] ) )
			unset( $options['message_label'][ $lang ] );
		if ( isset( $options['attachment_label'][ $lang ] ) )
			unset( $options['attachment_label'][ $lang ] );
		if ( isset( $options['send_copy_label'][ $lang ] ) )
			unset( $options['send_copy_label'][ $lang ] );
		if ( isset( $options['privacy_check_label'][ $lang ] ) )
			unset( $options['privacy_check_label'][ $lang ] );
		if ( isset( $options['optional_check_label'][ $lang ] ) )
			unset( $options['optional_check_label'][ $lang ] );
		if ( isset( $options['thank_text'][ $lang ] ) )
			unset( $options['thank_text'][ $lang ] );
		if ( isset( $options['submit_label'][ $lang ] ) )
			unset( $options['submit_label'][ $lang ] );
		if ( isset( $options['department_error'][ $lang ] ) )
			unset( $options['department_error'][ $lang ] );
		if ( isset( $options['name_error'][ $lang ] ) )
			unset( $options['name_error'][ $lang ] );
		if ( isset( $options['address_error'][ $lang ] ) )
			unset( $options['address_error'][ $lang ] );
		if ( isset( $options['email_error'][ $lang ] ) )
			unset( $options['email_error'][ $lang ] );
		if ( isset( $options['phone_error'][ $lang ] ) )
			unset( $options['phone_error'][ $lang ] );
		if ( isset( $options['subject_error'][ $lang ] ) )
			unset( $options['subject_error'][ $lang ] );
		if ( isset( $options['message_error'][ $lang ] ) )
			unset( $options['message_error'][ $lang ] );
		if ( isset( $options['attachment_error'][ $lang ] ) )
			unset( $options['attachment_error'][ $lang ] );
		if ( isset( $options['attachment_upload_error'][ $lang ] ) )
			unset( $options['attachment_upload_error'][ $lang ] );
		if ( isset( $options['attachment_move_error'][ $lang ] ) )
			unset( $options['attachment_move_error'][ $lang ] );
		if ( isset( $options['attachment_size_error'][ $lang ] ) )
			unset( $options['attachment_size_error'][ $lang ] );
		if ( isset( $options['privacy_check_error'][ $lang ] ) )
			unset( $options['privacy_check_error'][ $lang ] );
		if ( isset( $options['captcha_error'][ $lang ] ) )
			unset( $options['captcha_error'][ $lang ] );
		if ( isset( $options['form_error'][ $lang ] ) )
			unset( $options['form_error'][ $lang ] );

		if ( $contact_form_multi_active ) {
			if ( 'pro' == $contact_form_multi_active && get_option( 'cntctfrmmltpr_options_main' ) ) {
				$multi_options_main = get_option( 'cntctfrmmltpr_options_main' );
				update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'], $options );
			} else {
				$multi_options_main = get_option( 'cntctfrmmlt_options_main' );
				update_option( 'cntctfrmmlt_options_' . $multi_options_main['id_form'], $options );
			}
		} else {
			update_option( 'cntctfrm_options', $options );
		}

		if ( $is_ajax )
			die();
	}
}

/* Function for delete departament block */
if ( ! function_exists ( 'cntctfrm_delete_departament' ) ) {
	function cntctfrm_delete_departament() {
		check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' );
		$options = get_option( 'cntctfrm_options' );
		$key = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', htmlspecialchars( $_REQUEST['id'] ) ) ) );
		unset( $options["departments"]["name"][ $key ] );
		unset( $options["departments"]["email"][ $key ] );
		update_option( 'cntctfrm_options', $options );
		die();
	}
}

/*======= Functions for adding all functionality for updating ====*/

/* function for adding all functionality for updating */
if ( ! function_exists( 'cntctfrm_update_activate' ) ) {
	function cntctfrm_update_activate() {
		global $bstwbsftwppdtplgns_options;

		$pro = 'contact-form-pro/contact_form_pro.php';
		$free = 'contact-form-plugin/contact_form.php';

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( ! is_plugin_active_for_network( $pro ) )
				$deactivate_not_for_all_network = true;
		}

		if ( isset( $deactivate_not_for_all_network ) && is_plugin_active_for_network( $free ) ) {
			global $wpdb;
			deactivate_plugins( $free );

			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				activate_plugin( $free );
			}
			switch_to_blog( $old_blog );
		} else
			deactivate_plugins( $free );

		/* api for update bws-plugins */
		if ( ! function_exists ( 'bestwebsoft_wp_update_plugins' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );

		if ( empty( $bstwbsftwppdtplgns_options ) )
			$bstwbsftwppdtplgns_options = ( is_multisite() ) ? get_site_option( 'bstwbsftwppdtplgns_options' ) : get_option( 'bstwbsftwppdtplgns_options' );

		/* add license_key.txt after update */
		if ( $bstwbsftwppdtplgns_options && ! file_exists( dirname( __FILE__ ) . '/license_key.txt' ) ) {
			if ( isset( $bstwbsftwppdtplgns_options[ $pro ] ) ) {
				$bws_license_key = $bstwbsftwppdtplgns_options[ $pro ];
				$file = @fopen( dirname( __FILE__ ) . "/license_key.txt" , "w+" );
				if ( $file ) {
					@fwrite( $file, $bws_license_key );
					@fclose( $file );
				}
			}
		}
	}
}

if ( ! function_exists( 'cntctfrm_license_cron_task' ) ) {
	function cntctfrm_license_cron_task() {
		/* check if we solve the problem */
		if ( ! function_exists ( 'bestwebsoft_license_cron_task' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );
		bestwebsoft_license_cron_task( 'contact-form-pro/contact_form_pro.php', 'contact-form-plugin/contact_form.php' );
	}
}

if ( ! function_exists ( 'cntctfrm_plugin_update_row' ) ) {
	function cntctfrm_plugin_update_row( $file, $plugin_data ) {
		bws_plugin_update_row( 'contact-form-pro/contact_form_pro.php' );
	}
}

/* Banner - Update and CF-to-DB*/
if ( ! function_exists ( 'cntctfrm_plugin_banner' ) ) {
	function cntctfrm_plugin_banner() {
		global $hook_suffix;
		if ( 'plugins.php' == $hook_suffix  || ( isset( $_REQUEST['page'] ) && 'contact_form_pro.php' == $_REQUEST['page'] ) ) {
			global $cntctfrm_plugin_info, $wp_version, $bstwbsftwppdtplgns_cookie_add, $bstwbsftwppdtplgns_banner_array;

			if ( 'plugins.php' == $hook_suffix ) {
				bws_plugin_banner_timeout( 'contact-form-pro/contact_form_pro.php', 'cntctfrm', $cntctfrm_plugin_info['Name'], 'contact-form-plugin' );
				bws_plugin_banner_to_settings( $cntctfrm_plugin_info, 'cntctfrm_options', 'contact-form-plugin', 'admin.php?page=contact_form_pro.php' );
			}

			if ( isset( $_REQUEST['page'] ) && 'contact_form_pro.php' == $_REQUEST['page'] ) {
				bws_plugin_suggest_feature_banner( $cntctfrm_plugin_info, 'cntctfrm_options', 'contact-form-plugin' );
			}

			/**
			 * @since 4.0.3
			 * @todo delete after 01.04.2017
			 */
			echo cntctfrm_display_deprecated_shortcode_message();

			if ( ! function_exists( 'get_plugins' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
				echo '<script type="text/javascript" src="' . plugins_url( 'bws_menu/js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>';
				$bstwbsftwppdtplgns_cookie_add = true;
			}

			if ( empty( $bstwbsftwppdtplgns_banner_array ) ) {
				if ( ! function_exists( 'bws_get_banner_array' ) )
					require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
				bws_get_banner_array();
			}

			$all_plugins = get_plugins();
			$this_banner = 'cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page';
			foreach ( $bstwbsftwppdtplgns_banner_array as $key => $value ) {
				if ( $this_banner == $value[0] ) {
					if ( ! array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) && ! array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) ) {	?>
						<script type="text/javascript">
							(function($) {
								$(document).ready( function() {
									var hide_message_for_ctfrmtdbpr = $.cookie( "cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page" );
									if ( hide_message_for_ctfrmtdbpr == "true" ) {
										$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "none" );
									} else {
										$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "block" );
									}
									$( ".cntctfrm_for_ctfrmtdb_close_icon" ).click( function() {
										$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "none" );
										$.cookie( "cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page", "true", { expires: 32 } );
									});
								});
							})(jQuery);
						</script>
						<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
							<div class="cntctfrm_message_for_ctfrmtdb bws_banner_on_plugin_page" style="display: none;">
								<button class="cntctfrm_for_ctfrmtdb_close_icon close_icon notice-dismiss bws_hide_settings_notice" title="<?php _e( 'Close notice', 'contact-form-pro' ); ?>"></button>
								<div class="icon">
									<img title="" src="//ps.w.org/contact-form-to-db/assets/icon-128x128.png" alt=""/>
								</div>
								<div class="text">
									<strong>Contact Form to DB</strong> <?php _e( "allows to store your messages to the database.", 'contact-form-pro' ); ?><br />
									<span><?php _e( "Manage messages that have been sent from your website.", 'contact-form-pro' ); ?></span>
								</div>
								<div class="button_div">
									<a class="button" target="_blank" href="https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/?k=c789b154151a94569dfdb78dbc1b6ec9&amp;pn=3&amp;v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>"><?php _e( "Learn More", 'contact-form-pro' ); ?></a>
								</div>
							</div>
						</div>
						<?php break;
					}
				}
				if ( isset( $all_plugins[ $value[1] ] ) && $all_plugins[ $value[1] ]["Version"] >= $value[2] && is_plugin_active( $value[1] ) && ! isset( $_COOKIE[ $value[0] ] ) ) {
					break;
				}
			}
		}
	}
}

if ( ! function_exists( 'cntctfrm_inject_info' ) ) {
	function cntctfrm_inject_info( $result, $action = null, $args = null ) {
		if ( ! function_exists( 'bestwebsoft_inject_info' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );

		return bestwebsoft_inject_info( $result, $action, $args, 'contact-form-pro' );
	}
}

if ( ! function_exists( 'cntctfrm_shortcode_button_content' ) ) {
	function cntctfrm_shortcode_button_content( $content ) {
		global $wp_version, $cntctfrm_lang_codes;
		$lang_default = '...';

		/* Check contact-form-multi plugin */
		$contact_form_multi_active = cntctfrm_check_cf_multi_active();

		if ( $contact_form_multi_active ) {
			if ( 'pro' == $contact_form_multi_active && $multi_options_main = get_option( 'cntctfrmmltpr_options_main' ) ) {
				/**/
			} else {
				$multi_options_main = get_option( 'cntctfrmmlt_options_main' );
			}

			if ( ! $multi_options_main ) {
				$contact_form_multi_active = false;
			} else {
				if ( $multi_options_main['name_id_form'] ) {
					$multi_forms = $multi_ids = $multi_forms_languages = array();
					foreach ( $multi_options_main['name_id_form'] as $id => $title ) {
						$multi_forms[ $id ] = $title;
						array_push( $multi_ids, $id );
						$multi_options = get_option( 'cntctfrmmlt_options_' . $id );
						$language = isset( $multi_options['language'] ) ? $multi_options['language'] : array();
						array_unshift( $language, 'default' );
						$multi_forms_languages[ $id ] = $language;
					}
				}
			}
		}

		if ( ! $contact_form_multi_active ) {
			$options = get_option( 'cntctfrm_options' );
			array_unshift( $options['language'], 'default' );
		} ?>
		<div id="cntctfrm" style="display:none;">
			<fieldset>
				<?php if ( $contact_form_multi_active ) { ?>
					<label>
						<select name="cntctfrm_forms_list" id="cntctfrm_forms_list">
							<?php foreach ( $multi_forms as $id => $title ) {
								printf( '<option value="%1$s">%2$s</option>', $id, $title );
							} ?>
						</select>
						<span class="title"><?php _e( 'Contact form', 'contact-form-pro' ); ?></span>
					</label>
					<br>
					<label>
						<select name="cntctfrm_multi_languages_list" id="cntctfrm_multi_languages_list">
							<?php $i = 1;
							foreach ( $multi_forms_languages as $id => $languages ) {
								foreach ( $languages as $language ) {
									printf( '<option value="%1$s" data-form-id="%2$s" %3$s>%4$s</option>', strtolower( $language ), $id, ($i > 1) ? 'style="display: none;"' : '', ( $language == 'default' ) ? $lang_default : $cntctfrm_lang_codes[ $language ] );
								}
								$i++;
							} ?>
						</select>
						<span class="title"><?php _e( 'Language', 'contact-form-pro' ); ?></span>
					</label>
					<input class="bws_default_shortcode" type="hidden" name="default" value="[bestwebsoft_contact_form id=<?php echo array_shift( $multi_ids ); ?>]" />
				<?php } else { ?>
					<label>
						<select name="cntctfrm_languages_list" id="cntctfrm_languages_list">
							<?php foreach ( $options['language'] as $language ) {
									printf( '<option value="%1$s">%2$s</option>', strtolower( $language ), ( $language == 'default' ) ? $lang_default : $cntctfrm_lang_codes[ $language ] );
							} ?>
						</select>
						<span class="title"><?php _e( 'Language', 'contact-form-pro' ); ?></span>
					</label>
					<input class="bws_default_shortcode" type="hidden" name="default" value="[bestwebsoft_contact_form]" />
				<?php } ?>
			</fieldset>
			<script type="text/javascript">
				function cntctfrm_shortcode_init() {
					(function($) {
						var current_object = '<?php echo ( $wp_version < 3.9 ) ? "#TB_ajaxContent" : ".mce-reset" ?>';
						<?php if ( $contact_form_multi_active ) { ?>
							$( current_object + ' #bws_shortcode_display' ).bind( 'display_shortcode', function() {
								var cntctfrm_form_id = $( current_object + ' #cntctfrm_forms_list option:selected' ).val(),
									cntctfrm_get_form_language = $( current_object + ' #cntctfrm_multi_languages_list option:selected' ).val(),
									cntctfrm_form_language = ( cntctfrm_get_form_language == 'default' ) ? '' : ' lang=' + cntctfrm_get_form_language,
									shortcode = '[bestwebsoft_contact_form' + cntctfrm_form_language + ' id=' + cntctfrm_form_id + ']';
								$( this ).text( shortcode );
							});
							$( current_object + ' #cntctfrm_forms_list' ).on( 'change', function() {
								var cntctfrm_form = $( this ).find( 'option:selected' ).val(),
									cntctfrm_languages = $( current_object + ' #cntctfrm_multi_languages_list' ),
									cntctfrm_languages_options = cntctfrm_languages.find( 'option' );
								cntctfrm_languages_options.hide();
								cntctfrm_languages_options.filter( '[data-form-id="' + cntctfrm_form + '"]' ).show();
								cntctfrm_languages_options.filter( '[value="default"]' ).attr( 'selected', true );
								$( current_object + ' #bws_shortcode_display' ).trigger( 'display_shortcode' );
							});
							$( current_object + ' #cntctfrm_multi_languages_list' ).on( 'change', function() {
								$( current_object + ' #bws_shortcode_display' ).trigger( 'display_shortcode' );
							});
						<?php } else { ?>
							$( current_object + ' #cntctfrm_languages_list' ).on( 'change', function() {
								var cntctfrm_get_language = $( current_object + ' #cntctfrm_languages_list option:selected' ).val(),
									cntctfrm_language = ( cntctfrm_get_language == 'default' ) ? '' : ' lang=' + cntctfrm_get_language,
									shortcode = '[bestwebsoft_contact_form' + cntctfrm_language + ']';
								$( current_object + ' #bws_shortcode_display' ).text( shortcode );
							});
						<?php } ?>
					})(jQuery);
				}
			</script>
			<div class="cntctfrm_clear"></div>
		</div>
		<?php return $content;
	}
}

/* add help tab  */
if ( ! function_exists( 'cntctfrm_add_tabs' ) ) {
	function cntctfrm_add_tabs() {
		$screen = get_current_screen();
		$args = array(
			'id' 			=> 'cntctfrm',
			'section' 		=> '200538909'
		);
		bws_help_tab( $screen, $args );
	}
}

/* Function for delete options */
if ( ! function_exists ( 'cntctfrm_delete_options' ) ) {
	function cntctfrm_delete_options() {
		global $wpdb;
		$all_plugins = get_plugins();

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );

				$location_tables_array = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . "cntctfrm_location%' ;", ARRAY_N );
				$location_tables_str = '';
				foreach ( $location_tables_array as $key => $value ) {
					$location_tables_str = empty( $location_tables_str ) ? $value[0] : $location_tables_str . ', ' . $value[0];
				}
				if ( ! empty( $location_tables_str ) )
					$wpdb->query( "DROP TABLES IF EXISTS " . $location_tables_str . ";" );

				if ( ! array_key_exists( 'contact-form-plugin/contact_form.php', $all_plugins ) ) {
					$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "cntctfrm_field" );
					delete_option( 'cntctfrm_options' );
				}
			}
			switch_to_blog( $old_blog );
		} else {
			$location_tables_array = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . "cntctfrm_location%' ;", ARRAY_N );
			$location_tables_str = '';
			foreach ( $location_tables_array as $key => $value ) {
				$location_tables_str = empty( $location_tables_str ) ? $value[0] : $location_tables_str . ', ' . $value[0];
			}
			if ( ! empty( $location_tables_str ) )
				$wpdb->query( "DROP TABLES IF EXISTS " . $location_tables_str . ";" );

			if ( ! array_key_exists( 'contact-form-plugin/contact_form.php', $all_plugins ) ) {
				$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->prefix . "cntctfrm_field`;" );
				delete_option( 'cntctfrm_options' );
			}
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

/* Hooks */
register_activation_hook( __FILE__, 'cntctfrm_activation' );

add_action( 'admin_menu', 'cntctfrm_add_admin_menu' );

add_action( 'init', 'cntctfrm_pro_init', 50 );
add_action( 'admin_init', 'cntctfrm_admin_init' );
add_action( 'plugins_loaded', 'cntctfrm_plugins_loaded' );

/* add style and script to contact form */
add_action( 'admin_enqueue_scripts', 'cntctfrm_admin_head' );
add_action( 'admin_head', 'cntctfrm_add_admin_script' );
add_action( 'wp_enqueue_scripts', 'cntctfrm_wp_enqueue_style' );
add_action( 'wp_footer', 'cntctfrm_wp_footer' );

/*Additional links on the plugin page */
add_filter( 'plugin_action_links', 'cntctfrm_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cntctfrm_register_plugin_links', 10, 2 );

add_shortcode( 'bws_contact_form', 'cntctfrm_display_form' );
add_shortcode( 'bestwebsoft_contact_form', 'cntctfrm_display_form' );

/* custom filter for bws button in tinyMCE */
add_filter( 'bws_shortcode_button_content', 'cntctfrm_shortcode_button_content' );
add_filter( 'widget_text', 'do_shortcode' );

add_action( 'wp_ajax_cntctfrm_add_language', 'cntctfrm_add_language' );
add_action( 'wp_ajax_cntctfrm_remove_language', 'cntctfrm_remove_language' );
add_action( 'wp_ajax_cntctfrm_delete_departament', 'cntctfrm_delete_departament' );

add_action( 'admin_notices', 'cntctfrm_plugin_banner');

add_action( 'after_plugin_row_contact-form-pro/contact_form_pro.php', 'cntctfrm_plugin_update_row', 10, 2 );
add_filter( 'plugins_api', 'cntctfrm_inject_info', 20, 3 );
/* Function update cron */
add_action( 'contact_form_pro_license_cron', 'cntctfrm_license_cron_task' );

register_uninstall_hook( __FILE__, 'cntctfrm_delete_options' );