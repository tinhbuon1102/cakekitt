<?php 
/*
Plugin Name: Contact Form to DB Pro by BestWebSoft
Plugin URI: http://bestwebsoft.com/products/contact-form-to-db/
Description: Save and manage contact form messages. Never lose important data.
Author: BestWebSoft
Text Domain: contact-form-to-db-pro
Domain Path: /languages
Version: 1.5.6
Author URI: http://bestwebsoft.com/
License: Proprietary
*/

/*
* Function for adding menu and submenu 
*/
if ( ! function_exists( 'cntctfrmtdb_admin_menu' ) ) {
	function cntctfrmtdb_admin_menu() {
		bws_general_menu();
		$settings = add_submenu_page( 'bws_panel', 'Contact Form to DB Pro', 'Contact Form to DB Pro', 'edit_themes', 'cntctfrmtdbpr_settings', 'cntctfrmtdb_settings_page' );
		if ( ! is_network_admin() ) {
			$hook = add_menu_page( 'CF to DB', 'CF to DB', 'edit_posts', 'cntctfrmtdb_manager', 'cntctfrmtdb_manager_page', plugins_url( "images/menu_single.png", __FILE__ ), '56.1' );
			/* add Contact Form to DB manager page */
			add_action( 'load-' . $hook, 'cntctfrmtdb_add_options_manager' );
			add_action( 'load-' . $settings, 'cntctfrmtdb_add_tabs' );
		}		
	}
}

if ( ! function_exists( 'cntctfrmtdb_plugins_loaded' ) ) {
	function cntctfrmtdb_plugins_loaded() {
		/* textdomain of plugin */
		load_plugin_textdomain( 'contact-form-to-db-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/*
* Function initialisation plugin 
*/
if ( ! function_exists( 'cntctfrmtdb_pro_init' ) ) {
	function cntctfrmtdb_pro_init() {
		global $cntctfrmtdb_plugin_info, $cntctfrmtdb_pages;
		
		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		
		if ( empty( $cntctfrmtdb_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cntctfrmtdb_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version  */
		bws_wp_min_version_check( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $cntctfrmtdb_plugin_info, '3.8' );

		cntctfrmtdb_update_activate();

		/* Call register settings function */
		$cntctfrmtdb_pages = array(
			'cntctfrmtdb_manager',
			'cntctfrmtdbpr_settings'
		);
		if ( ! is_admin() || ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) )
			cntctfrmtdb_settings();
	}
}

if ( ! function_exists( 'cntctfrmtdb_admin_init' ) ) {
	function cntctfrmtdb_admin_init() {
		global $bws_plugin_info, $cntctfrmtdb_plugin_info;

		/* Add variable for bws_menu */
		if ( empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '93', 'version' => $cntctfrmtdb_plugin_info["Version"] );				

		if ( isset( $_REQUEST['page'] ) && 'cntctfrmtdb_manager' == $_REQUEST['page'] )
			cntctfrmtdb_action_links();			
	}
}

/*
* Function to register default settings of plugin
*/
if ( ! function_exists( 'cntctfrmtdb_settings' ) ) {
	function cntctfrmtdb_settings() {
		global $cntctfrmtdb_options, $cntctfrmtdb_plugin_info, $cntctfrmtdb_option_defaults;
		$cntctfrmtdb_db_version = 'pro_1.2';

		/* set default settings */
		$cntctfrmtdb_option_defaults = array(
			'plugin_option_version'		=>	'pro-' . $cntctfrmtdb_plugin_info["Version"],
			'plugin_db_version'			=>	$cntctfrmtdb_db_version,
			'save_messages_to_db'		=>	1,
			'save_attachments'			=>	1,
			'save_attachments_to'		=>	'uploads',
			'format_save_messages'		=>	'xml',
			'csv_separator'				=>	",",
			'csv_enclosure'				=>	"\"",
			'include_attachments'		=>	0,
			'mail_address'				=>	1,
			'delete_messages'			=>	1,
			'delete_messages_after'		=>	'daily',
			'show_attachments'			=>	1,
			'use_fancybox'				=>	1,
			'display_settings_notice'	=>	1,
			'suggest_feature_banner'	=>	1,
		);

		if ( is_multisite() && is_network_admin() ) {
			/**
			* @deprecated since 1.5.6
			* @todo remove after 12.03.2017
			*/
			if ( $old_options = get_site_option( 'cntctfrmtdbpr_options' ) ) {
				if ( ! get_site_option( 'cntctfrmtdb_options' ) )
					add_site_option( 'cntctfrmtdb_options', $old_options );
				else
					update_site_option( 'cntctfrmtdb_options', $old_options );
				delete_site_option( 'cntctfrmtdbpr_options' );
			}
			/* install the option defaults */
			if ( ! get_site_option( 'cntctfrmtdb_options' ) ) {
				$cntctfrmtdb_option_defaults['network_apply'] = 'default';
				$cntctfrmtdb_option_defaults['network_view'] = '1';
				$cntctfrmtdb_option_defaults['network_change'] = '1';				
				add_site_option( 'cntctfrmtdb_options', $cntctfrmtdb_option_defaults );		
			}	
			$cntctfrmtdb_options = get_site_option( 'cntctfrmtdb_options' );
		} else {
			/**
			* @deprecated since 1.5.6
			* @todo remove after 12.03.2017
			*/
			if ( $old_options = get_option( 'cntctfrmtdbpr_options' ) ) {
				if ( ! get_option( 'cntctfrmtdb_options' ) )
					add_option( 'cntctfrmtdb_options', $old_options );
				else
					update_option( 'cntctfrmtdb_options', $old_options );
				delete_option( 'cntctfrmtdbpr_options' );
			}
			/* install the option defaults */
			if ( ! get_option( 'cntctfrmtdb_options' ) ) {
				if ( is_multisite() ) {
					if ( $cntctfrmtdb_network_options = get_site_option( 'cntctfrmtdb_options' ) ) {
						if ( 'off' != $cntctfrmtdb_network_options['network_apply'] ) {
							$cntctfrmtdb_option_defaults = $cntctfrmtdb_network_options;
							unset( $cntctfrmtdb_option_defaults['network_apply'], $cntctfrmtdb_option_defaults['network_view'], $cntctfrmtdb_option_defaults['network_change'] );
						}
					}
				}
				add_option( 'cntctfrmtdb_options', $cntctfrmtdb_option_defaults );
			}	
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
		}

		/* Array merge in case this version has added new options */
		if ( ! isset( $cntctfrmtdb_options['plugin_option_version'] ) || $cntctfrmtdb_options['plugin_option_version'] != 'pro-' . $cntctfrmtdb_plugin_info["Version"] ) {
			$cntctfrmtdb_options = array_merge( $cntctfrmtdb_option_defaults, $cntctfrmtdb_options );
			$cntctfrmtdb_options['plugin_option_version'] = 'pro-' . $cntctfrmtdb_plugin_info["Version"];
			$update_option = true;
		}	

		/* create or update db table */
		if ( ! isset( $cntctfrmtdb_options['plugin_db_version'] ) || $cntctfrmtdb_options['plugin_db_version'] != $cntctfrmtdb_db_version ) {
			if ( is_network_admin() )
				cntctfrmtdb_plugin_activate( true );
			else
				cntctfrmtdb_create_table();

			$cntctfrmtdb_options['plugin_db_version'] = $cntctfrmtdb_db_version;
			$update_option = true;
		}

		if ( isset( $update_option ) ) {
			if ( is_multisite() && is_network_admin() )
				update_site_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
			else
				update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
		}
	}
}

/**
 * Activation plugin function
 */
if ( ! function_exists( 'cntctfrmtdb_plugin_activate' ) ) {
	function cntctfrmtdb_plugin_activate( $networkwide ) {
		global $wpdb;
		/* Activation function for network, check if it is a network activation - if so, run the activation function for each blog id */
		if ( function_exists( 'is_multisite' ) && is_multisite() && $networkwide ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				cntctfrmtdb_create_table();
			}
			switch_to_blog( $old_blog );
			return;
		}
		cntctfrmtdb_create_table();

		/* create settings */
		cntctfrmtdb_settings();
		/* Function to change cron recurrence or disable it */
		cntctfrmtdb_change_cron();
	}
}

/* 
* Function to create a new tables in data base 
*/
if ( ! function_exists( 'cntctfrmtdb_create_table' ) ) {
	function cntctfrmtdb_create_table() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "message_status` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` CHAR(30) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "blogname` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`blogname` CHAR(100) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "to_email` (
			`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
			`email` CHAR(50) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "hosted_site` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`site` CHAR(50) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "refer` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`refer` CHAR(50) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "mime_types` (
			`mime_types_id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`mime_type` CHAR(50) NOT NULL,
			PRIMARY KEY  (`mime_types_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "upload_path` (
			`path_id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`path` CHAR(100) NOT NULL,
			PRIMARY KEY  (`path_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "attachments` (
			`id` INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			`message_id` INT UNSIGNED NOT NULL,
			`name` CHAR(50) NOT NULL,
			`mime_type_id` TINYINT(2) UNSIGNED NOT NULL,
			`size` INT(7) UNSIGNED NOT NULL,
			`content` LONGBLOB,
			`att_upload_path_id` TEXT NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "thumbnails` (
			`thumb_id` INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			`message_id` INT UNSIGNED NOT NULL,
			`attachment_id` INT(6) UNSIGNED NOT NULL,
			`thumb_name` CHAR(50) NOT NULL,
			`thumb_mime_type_id` TINYINT(2) UNSIGNED NOT NULL,
			`thumb_size` INT(7) UNSIGNED NOT NULL,
			`thumb_content` LONGBLOB,
			`thumb_upload_path_id` TEXT NOT NULL,
			PRIMARY KEY  (`thumb_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "message` (
			`id` INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			`from_user` CHAR(50) NOT NULL,
			`user_email` CHAR(50) NOT NULL,
			`send_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`subject` TINYTEXT NOT NULL,
			`message_text` TEXT NOT NULL,
			`was_read` TINYINT(1) NOT NULL,
			`sent` TINYINT(1) NOT NULL,
			`dispatch_counter` SMALLINT UNSIGNED NOT NULL,
			`status_id` TINYINT(2) UNSIGNED NOT NULL,
			`to_id` SMALLINT UNSIGNED NOT NULL, 
			`blogname_id` TINYINT UNSIGNED NOT NULL,
			`hosted_site_id` TINYINT(2) UNSIGNED NOT NULL,
			`refer_id` TINYINT(2) UNSIGNED NOT NULL,
			`attachment_status` INT(1) UNSIGNED NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "field_selection` (
			`cntctfrm_field_id` INT NOT NULL,
			`message_id` MEDIUMINT(6) UNSIGNED NOT NULL,
			`field_value` CHAR(50) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$status = array( 'normal',
			'spam',
			'trash'
		);
		foreach ( $status as $key => $value ) {
			$db_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `" . $prefix . "message_status` WHERE `name` = %s", $value  ), ARRAY_A );
			if ( ! isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert(  $prefix . "message_status", array( 'name' => $value ), array( '%s' ) );	
			}
		}
		$mime_types = array( 
			'text/html',
			'text/plain',
			'text/css',
			'image/gif',
			'image/png',
			'image/x-png',
			'image/jpeg',
			'image/tiff',
			'image/bmp',
			'image/x-ms-bmp',
			'application/postscript',
			'application/rtf',
			'application/pdf',
			'application/msword',
			'application/vnd.ms-excel',
			'application/zip',
			'application/x-zip',
			'application/x-zip-compressed',
			'application/rar',
			'application/x-rar',
			'application/x-rar-compressed',
			'audio/wav',
			'audio/mp3',
			'application/vnd.ms-powerpoint',
			'application/octet-stream',
			'application/force-download'
		);
		foreach ( $mime_types as $key => $value ) {
			$db_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `" . $prefix . "mime_types` WHERE `mime_type` = %s", $value ), ARRAY_A );
			if ( !isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert(  $prefix . "mime_types", array( 'mime_type' => $value ), array( '%s' ) );
			}
		}
	}
}

/*
* Function to add stylesheets and scripts for admin bar 
*/
if ( ! function_exists ( 'cntctfrmtdb_admin_head' ) ) {
	function cntctfrmtdb_admin_head() {
		global $cntctfrmtdb_pages, $cntctfrmtdb_options;

		wp_enqueue_style( 'cntctfrmtdb_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		
		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) {
			if ( 1 == $cntctfrmtdb_options['use_fancybox'] ) {
				wp_enqueue_style( 'cntctfrmtdb_Fancybox_Stylesheet', plugins_url( 'css/jquery.fancybox.css', __FILE__ ) );
				wp_enqueue_script( 'cntctfrmtdb_FancyboxScript', plugins_url( 'js/jquery.fancybox.pack.js', __FILE__ ) );
				$script_vars = array(
					'fancyBoxError' => __( 'The requested content cannot be loaded.<br/>Please try again later.' , 'contact-form-to-db-pro' )
				);
				wp_localize_script( 'cntctfrmtdb_FancyboxScript', 'cntctfrmtdb', $script_vars );
			}

			wp_enqueue_script( 'cntctfrmtdb_script', plugins_url( 'js/script.js', __FILE__ ) );
			$script_vars = array(
				'letter'           => __( 'Letter' , 'contact-form-to-db-pro' ),
				'spam'             => __( 'Spam!' , 'contact-form-to-db-pro' ),
				'trash'            => __( 'in Trash' , 'contact-form-to-db-pro' ),
				'statusNotChanged' => __( 'Status was not changed' , 'contact-form-to-db-pro' ),
				'preloaderSrc'     => plugins_url( 'images/preloader.gif', __FILE__ ),
				'ajax_nonce'       => wp_create_nonce( 'cntctfrmtdb_ajax_nonce_value' )
			);			
			wp_localize_script( 'cntctfrmtdb_script', 'cntctfrmtdb', $script_vars );			
		}
	}
}

/*
* Function to add actions link to block with plugins name on "Plugins" page 
*/
if ( ! function_exists( 'cntctfrmtdb_plugin_action_links' ) ) {
	function cntctfrmtdb_plugin_action_links( $links, $file ) {
		if ( $file == 'contact-form-to-db-pro/contact_form_to_db_pro.php' ) {
			$settings_link = '<a href="admin.php?page=cntctfrmtdbpr_settings">' . __( 'Settings', 'contact-form-to-db-pro' ) . '</a>';
			/* array_unshift( $links, $settings_link ); */
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

/*
* Function to add links to description block on "Plugins" page 
*/
if ( ! function_exists( 'cntctfrmtdb_register_plugin_links' ) ) {
	function cntctfrmtdb_register_plugin_links( $links, $file ) {
		if ( $file == 'contact-form-to-db-pro/contact_form_to_db_pro.php' ) {
			$links[] = '<a href="admin.php?page=cntctfrmtdbpr_settings">' . __( 'Settings', 'contact-form-to-db-pro' ) . '</a>';
			$links[] = '<a href="http://bestwebsoft.com/products/contact-form-to-db/faq/" target="_blank">' . __( 'FAQ','contact-form-to-db-pro' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support','contact-form-to-db-pro' ) . '</a>';
		}
		return $links;
	}
}

/*
* Function for displaying settings page of plugin 
*/
if ( ! function_exists( 'cntctfrmtdb_settings_page' ) ) {
	function cntctfrmtdb_settings_page() {
		global $cntctfrmtdb_options, $cntctfrmtdb_plugin_info, $wpdb, $cntctfrmtdb_option_defaults;
		$error = $message = $change_permission_attr = '';
		$view_permission = true;

		if ( is_multisite() && ! is_network_admin() ) {
			if ( $cntctfrmtdb_network_options = get_site_option( 'cntctfrmtdb_options' ) ) {
				if ( 'all' == $cntctfrmtdb_network_options['network_apply'] && 0 == $cntctfrmtdb_network_options['network_change'] )
					$change_permission_attr = ' readonly="readonly" disabled="disabled"';	
				if ( 'all' == $cntctfrmtdb_network_options['network_apply'] && 0 == $cntctfrmtdb_network_options['network_view'] )
					$view_permission = false;	
			}		
		}

		if ( $view_permission ) {
			$admin_url = ( is_multisite() && is_network_admin() ) ? network_admin_url( '/' ) : admin_url( '/' );

			/* set value of input type="hidden" when options is changed */
			if ( isset( $_POST['cntctfrmtdb_form_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'cntctfrmtdb_nonce_name' ) && '' == $change_permission_attr ) {
				$cntctfrmtdb_options_submit['save_messages_to_db']	= isset( $_POST['cntctfrmtdb_save_messages_to_db'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['save_attachments']		= isset( $_POST['cntctfrmtdb_save_attachments'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['save_attachments_to']	= isset( $_POST['cntctfrmtdb_save_attachments_to'] ) ? $_POST['cntctfrmtdb_save_attachments_to'] : 0;
				$cntctfrmtdb_options_submit['format_save_messages']	= $_POST['cntctfrmtdb_format_save_messages'];
				
				if ( 'csv' == $cntctfrmtdb_options_submit['format_save_messages'] ) {
					$cntctfrmtdb_options_submit['csv_separator'] = $_POST['cntctfrmtdb_csv_separator'];
					$cntctfrmtdb_options_submit['csv_enclosure'] = $_POST['cntctfrmtdb_csv_enclosure'];
				} else {
					$cntctfrmtdb_options_submit['csv_separator'] = ",";
					$cntctfrmtdb_options_submit['csv_enclosure'] = '"';
				}
				$cntctfrmtdb_options_submit['include_attachments']	= isset( $_POST['cntctfrmtdb_include_attachments'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['mail_address']			= isset( $_POST['cntctfrmtdb_mail_address'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['delete_messages']		= isset( $_POST['cntctfrmtdb_delete_messages'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['delete_messages_after']	= isset( $_POST['cntctfrmtdb_delete_messages_after'] ) ? $_POST['cntctfrmtdb_delete_messages_after'] : 'daily';
				$cntctfrmtdb_options_submit['show_attachments']		= isset( $_POST['cntctfrmtdb_show_attachments'] ) ? 1 : 0;
				$cntctfrmtdb_options_submit['use_fancybox']			= isset( $_POST['cntctfrmtdb_use_fancybox'] ) ? 1 : 0;
				/* update options of plugin in database */
				$cntctfrmtdb_options = array_merge( $cntctfrmtdb_options, $cntctfrmtdb_options_submit );

				/* Update options in the database */
				if ( is_multisite() && is_network_admin() ) {					
					if ( 'all' == $_REQUEST['cntctfrmtdb_network_apply'] ) {
						/* Get all blog ids */
						$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
						$old_blog = $wpdb->blogid;
						foreach ( $blogids as $blog_id ) {
							switch_to_blog( $blog_id );								
							update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
							/* Function to change cron recurrence or disable it */
							cntctfrmtdb_change_cron();
						}
						switch_to_blog( $old_blog );
					}
					$cntctfrmtdb_options['network_apply']  = $_REQUEST['cntctfrmtdb_network_apply'];
					$cntctfrmtdb_options['network_view']   = isset( $_REQUEST['cntctfrmtdb_network_view'] ) ? 1 : 0;
					$cntctfrmtdb_options['network_change'] = isset( $_REQUEST['cntctfrmtdb_network_change'] ) ? 1 : 0;
					update_site_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
				} else {
					update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
					/* Function to change cron recurrence or disable it */
					cntctfrmtdb_change_cron();
				}

				$message = __( "Settings saved.", 'contact-form-to-db-pro' );				
			}
		}

		if ( isset( $_REQUEST['bws_restore_confirm'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'bws_settings_nonce_name' ) ) {
			if ( is_multisite() && is_network_admin() ) {
				/* install the option defaults */
				$cntctfrmtdb_options = $cntctfrmtdb_option_defaults;
				$cntctfrmtdb_options['network_apply']  = 'default';
				$cntctfrmtdb_options['network_view']   = '1';
				$cntctfrmtdb_options['network_change'] = '1';				
				update_site_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );		
			} else {
				$cntctfrmtdb_options = $cntctfrmtdb_option_defaults;
				update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
			}
			$message = __( 'All plugin settings were restored.', 'contact-form-to-db-pro' );
		}

		$result_check_pro = bws_check_pro_license( 'contact-form-to-db-pro/contact_form_to_db_pro.php' );
		if ( ! empty( $result_check_pro['error'] ) )
			$error = $result_check_pro['error'];
		elseif ( ! empty( $result_check_pro['message'] ) )
			$message = $result_check_pro['message']; ?>
		<!-- creating page of options -->
		<div class="wrap cntctfrmtdb_settings_page">			
			<h1>Contact Form to DB Pro <?php if ( is_network_admin() ) echo __( 'Network', 'contact-form-to-db-pro' ) . ' '; _e( 'Settings', 'contact-form-to-db-pro' ); ?></h1>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab<?php if ( ! isset( $_GET['action'] ) ) echo ' nav-tab-active'; ?>" href="admin.php?page=cntctfrmtdbpr_settings"><?php _e( 'Settings', 'contact-form-to-db-pro' ); ?></a>
				<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'user_guide' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=cntctfrmtdbpr_settings&action=user_guide"><?php _e( 'User guide', 'contact-form-to-db-pro' ); ?></a>
				<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'faq' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=cntctfrmtdbpr_settings&action=faq"><?php _e( 'FAQ', 'contact-form-to-db-pro' ); ?></a>
			</h2>
			<?php if ( ! isset( $_GET['action'] ) ) {
				bws_show_settings_notice(); ?>
				<div class="updated fade below-h2" <?php if ( $message == '' || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
				<div class="error below-h2" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
				<div id="cntctfrmtdb_network_notice" class="updated below-h2" style="display:none"><p><strong><?php _e( "Notice:", 'contact-form-to-db-pro' ); ?></strong> <?php _e( "This option will replace all current settings on separate sites.", 'contact-form-to-db-pro' ); ?></p></div>
				<?php if ( $view_permission && $change_permission_attr != '' ) { ?>
					<div class="error below-h2"><p><strong><?php _e( "Notice:", 'contact-form-to-db-pro' ); ?></strong> <strong><?php _e( "It is prohibited to change Contact Form to DB Pro settings on this site in the Contact Form to DB Pro network settings.", 'contact-form-to-db-pro' ); ?></strong></p></div>
				<?php }
				if ( ! $view_permission ) { ?>
					<div class="error below-h2"><p><strong><?php _e( "Notice:", 'contact-form-to-db-pro' ); ?></strong> <strong><?php _e( "It is prohibited to view Contact Form to DB Pro settings on this site in the Contact Form to DB Pro network settings.", 'contact-form-to-db-pro' ); ?></strong></p></div>
				<?php } else {
					if ( isset( $_REQUEST['bws_restore_default'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'bws_settings_nonce_name' ) ) {
						bws_form_restore_default_confirm( plugin_basename( __FILE__ ) );
					} else { ?>
						<div id="cntctfrmtdb_settings_form_block">
							<?php if ( $change_permission_attr != '' ) { ?>
								<div class="cntctfrmtdb_bg"></div>
							<?php } ?>
							<form id="cntctfrmtdb_settings_form" class="bws_form" method="post" action="admin.php?page=cntctfrmtdbpr_settings">
								<table class="form-table">
									<?php if ( is_multisite() && is_network_admin() ) { ?>
									<tr valign="top" class="cntctfrmtdb_network_settings">
										<th scope="row"><?php _e( 'Apply network settings', 'contact-form-to-db-pro' ); ?></th>
										<td>
											<label><input type="radio" name="cntctfrmtdb_network_apply" value="all" <?php if ( "all" == $cntctfrmtdb_options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Apply to all sites and use by default', 'contact-form-to-db-pro' ); ?> <span class="bws_info">(<?php _e( 'All current settings on separate sites will be replaced', 'contact-form-to-db-pro' ); ?>)</span></label><br />
											<div class="cntctfrmtdb_network_apply_all">
												<label><input type="checkbox" name="cntctfrmtdb_network_change" value="1" <?php if ( 1 == $cntctfrmtdb_options['network_change'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Allow to change the settings on separate websites', 'contact-form-to-db-pro' ); ?></label><br />
												<label><input type="checkbox" name="cntctfrmtdb_network_view" value="1" <?php if ( 1 == $cntctfrmtdb_options['network_view'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Allow to view the settings on separate websites', 'contact-form-to-db-pro' ); ?></label><br />
											</div>
											<label><input type="radio" name="cntctfrmtdb_network_apply" value="default" <?php if ( "default" == $cntctfrmtdb_options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'By default', 'contact-form-to-db-pro' ); ?> <span class="bws_info">(<?php _e( 'Settings will be applied to newly added websites by default', 'contact-form-to-db-pro' ); ?>)</span></label><br />
											<label><input type="radio" name="cntctfrmtdb_network_apply" value="off" <?php if ( "off" == $cntctfrmtdb_options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Do not apply', 'contact-form-to-db-pro' ); ?> <span class="bws_info">(<?php _e( 'Change the settings on separate sites of the multisite only', 'contact-form-to-db-pro' ); ?>)</span></label><br />
										</td>
									</tr>
									<?php } ?>				
									<tr valign="top">
										<th scope="row" style="width:200px;"><label for="cntctfrmtdb_save_messages_to_db"><?php _e( 'Save messages to database', 'contact-form-to-db-pro' ); ?></label></th>
										<td>
											<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_save_messages_to_db" name="cntctfrmtdb_save_messages_to_db" value="1" <?php if ( 1 == $cntctfrmtdb_options['save_messages_to_db'] ) echo 'checked="checked" '; ?>/>
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( ! 1 == $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th scope="row" style="width:200px;"><label for="cntctfrmtdb_save_attachments"><?php _e( 'Save attachments', 'contact-form-to-db-pro' ); ?></label></th>
										<td>
											<fieldset>							
												<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_save_attachments" name="cntctfrmtdb_save_attachments" value="1" <?php if ( $cntctfrmtdb_options['save_attachments'] == '1' ) echo "checked=\"checked\" ";?>/>
												<br/>
												<div class="cntctfrmtdb_save_to_block" <?php if ( 1 != $cntctfrmtdb_options['save_attachments'] ) echo 'style="display: none;"' ?>>
													<input<?php echo $change_permission_attr; ?> type="radio" id="cntctfrmtdb_save_to_database" name="cntctfrmtdb_save_attachments_to" value="database" <?php if ( $cntctfrmtdb_options['save_attachments_to'] == 'database' ) echo "checked=\"checked\" "; ?>/>
													<label for="cntctfrmtdb_save_to_database"><?php _e( 'Save attachments to database.', 'contact-form-to-db-pro' ); ?></label><br/>
													<input<?php echo $change_permission_attr; ?> type="radio" id="cntctfrmtdb_save_to_uploads" name="cntctfrmtdb_save_attachments_to" value="uploads" <?php if ( $cntctfrmtdb_options['save_attachments_to'] == 'uploads' ) echo "checked=\"checked\" "; ?>/>
													<label for="cntctfrmtdb_save_to_uploads"><?php _e( 'Save attachments to "Uploads".', 'contact-form-to-db-pro' ); ?></label><br/>
												</div>
											</fieldset>
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( ! 1 == $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th scope="row" style="width:200px;"><?php _e( 'Download messages in', 'contact-form-to-db-pro' ); ?></th>
										<td>
											<select<?php echo $change_permission_attr; ?> name="cntctfrmtdb_format_save_messages" id="cntctfrmtdb_format_save_messages">
												<option value='xml' <?php if ( 'xml' == $cntctfrmtdb_options['format_save_messages'] ) echo 'selected="selected" '; ?>><?php echo '.xml'; ?></option>
												<option value='eml' <?php if ( 'eml' == $cntctfrmtdb_options['format_save_messages'] ) echo 'selected="selected" '; ?>><?php echo '.eml'; ?></option>
												<option value='csv' <?php if ( 'csv' == $cntctfrmtdb_options['format_save_messages'] ) echo 'selected="selected" '; ?>><?php echo '.csv'; ?></option>
											</select>
											<label><?php _e( ' format', 'contact-form-to-db-pro' ); ?></label><br/>
											<div class="cntctfrmtdb_csv_separators" <?php if ( 'csv' != $cntctfrmtdb_options['format_save_messages'] ) echo 'style="display: none;"'; ?>>
												<label><?php _e( 'Input symbols for separator and enclosure', 'contact-form-to-db-pro' ); ?></label></br>
												<select<?php echo $change_permission_attr; ?> name="cntctfrmtdb_csv_separator" id="cntctfrmtdb_csv_separator">
													<option value="," <?php if ( "," == $cntctfrmtdb_options['csv_separator'] ) echo 'selected="selected" '; ?>><?php echo ","; ?></option>
													<option value=";" <?php if ( ";" == $cntctfrmtdb_options['csv_separator'] ) echo 'selected="selected" '; ?>><?php echo ";"; ?></option>
													<option value="t" <?php if ( "t" == $cntctfrmtdb_options['csv_separator'] ) echo 'selected="selected" '; ?>><?php echo "\\t"; ?></option>
												</select>
												<label for="cntctfrmtdb_csv_separator"><?php _e( ' separator', 'contact-form-to-db-pro' ); ?></label><br/>
												<select<?php echo $change_permission_attr; ?> name="cntctfrmtdb_csv_enclosure" id="cntctfrmtdb_csv_enclosure">
													<option value='"' <?php if ( "\"" == $cntctfrmtdb_options['csv_enclosure'] ) echo 'selected="selected" '; ?>><?php echo "\""; ?></option>
													<option value="'" <?php if ( "'" == $cntctfrmtdb_options['csv_enclosure'] ) echo 'selected="selected" '; ?>><?php echo "'"; ?></option>
													<option value="`" <?php if ( "`" == $cntctfrmtdb_options['csv_enclosure'] ) echo 'selected="selected" '; ?>><?php echo "`"; ?></option>
												</select>
												<label for="cntctfrmtdb_csv_enclosure"><?php _e( ' enclosure', 'contact-form-to-db-pro' ); ?></label><br/>
												<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_include_attachments" name="cntctfrmtdb_include_attachments" <?php if ( "1" == $cntctfrmtdb_options['include_attachments'] ) echo "checked=\"checked\" "?>/>
												<label for="cntctfrmtdb_include_attachments"><?php _e( 'Include content of  attachments in to "csv"-file', 'contact-form-to-db-pro' ); ?></label><br/>
											</div><!-- .cntctfrmtdb_csv_separators -->
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( ! 1 == $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th scope="row" style="width:200px;"><label for="cntctfrmtdb_mail_address"><?php _e( 'Re-send a message to the email address specified in Contact Form Settings', 'contact-form-to-db-pro' ); ?></label></th>
										<td>
											<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_mail_address" name="cntctfrmtdb_mail_address" <?php if ( "1" == $cntctfrmtdb_options['mail_address'] ) echo "checked=\"checked\" "?>/>
											<br/>
											<span class="bws_info"><?php _e( '(If the option is disabled, all messages will be sent to the email address which was valid at the time of receipt of the message )', 'contact-form-to-db-pro' ); ?></span><br/>
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( ! 1 == $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th scope="row" style="width:200px;"><label for="cntctfrmtdb_delete_messages"><?php _e( 'Periodically delete old messages', 'contact-form-to-db-pro' ); ?></label></th>
										<td>
											<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_delete_messages" name="cntctfrmtdb_delete_messages" <?php if ( "1" == $cntctfrmtdb_options['delete_messages'] ) echo "checked=\"checked\" "?>/>
											<div class="cntctfrmtdb_delete_block" <?php if ( 1 != $cntctfrmtdb_options['delete_messages'] ) echo 'style="display:none"' ?>>
													<select<?php echo $change_permission_attr; if ( "1" != $cntctfrmtdb_options['delete_messages'] ) echo ' disabled="disabled"'?> name="cntctfrmtdb_delete_messages_after" id="cntctfrmtdb_delete_messages_after">
														<option value='daily' <?php if ( 'daily' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 24 hours', 'contact-form-to-db-pro' ); ?></option>
														<option value='every_three_days' <?php if ( 'every_three_days' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 3 days', 'contact-form-to-db-pro' ); ?></option>
														<option value='weekly' <?php if ( 'weekly' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 1 week', 'contact-form-to-db-pro' ); ?></option>
														<option value='every_two_weeks' <?php if ( 'every_two_weeks' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 2 weeks', 'contact-form-to-db-pro' ); ?></option>
														<option value='monthly' <?php if ( 'monthly' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 1 month', 'contact-form-to-db-pro' ); ?></option>
														<option value='every_six_months' <?php if ( 'every_six_months' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 6 months', 'contact-form-to-db-pro' ); ?></option>
														<option value='yearly' <?php if ( 'yearly' == $cntctfrmtdb_options['delete_messages_after'] ) echo 'selected="selected" ';?>><?php _e( 'every 1 year', 'contact-form-to-db-pro' ); ?></option>
													</select><br/>
													<span class="bws_info"><?php _e( '(all messages older than the specified period will be deleted at the end of the same period )', 'contact-form-to-db-pro' ); ?></span><br/>
											</div>
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( '1' != $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th scope="row" style="width:200px;"><label for="cntctfrmtdb_show_attachments"><?php _e( 'Show attachments', 'contact-form-to-db-pro' ); ?></label></th>
										<td>
											<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_show_attachments" name="cntctfrmtdb_show_attachments" value="1" <?php if( '1' == $cntctfrmtdb_options['show_attachments'] ) echo "checked=\"checked\" "; ?>/>
										</td>
									</tr>
									<tr valign="top" class="cntctfrmtdb_options" <?php if ( '1' != $cntctfrmtdb_options['save_messages_to_db'] ) echo 'style="display: none;"' ;?>>
										<th><label for="cntctfrmtdb_use_fancybox"><?php _e( 'Use fancybox to image view', 'contact-form-to-db-pro' ); ?></label></br></th>
										<td>
											<input<?php echo $change_permission_attr; ?> type="checkbox" id="cntctfrmtdb_use_fancybox" name="cntctfrmtdb_use_fancybox" value="1" <?php if( '1' == $cntctfrmtdb_options['use_fancybox'] ) echo "checked=\"checked\" "; ?>/>
										</td>
									</tr>
								</table>								
								<p class="submit">
									<input<?php echo $change_permission_attr; ?> type="hidden" name="cntctfrmtdb_form_submit" value="submit" />
									<input<?php echo $change_permission_attr; ?> id="bws-submit-button" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'contact-form-to-db-pro' ) ?>" />
									<?php wp_nonce_field( plugin_basename( __FILE__ ), 'cntctfrmtdb_nonce_name' ); ?>
								</p>								
							</form>
							<?php bws_form_restore_default_settings( plugin_basename( __FILE__ ), $change_permission_attr );	?>
						</div>				 
						<?php bws_check_pro_license_form( 'contact-form-to-db-pro/contact_form_to_db_pro.php' ); 
					}
				}				
			} elseif ( 'user_guide' == $_GET['action'] ) {
				cntctfrmtdb_user_guide();
			} elseif ( 'faq' == $_GET['action'] ) {
				cntctfrmtdb_faq();
			}
			bws_plugin_reviews_block( $cntctfrmtdb_plugin_info['Name'], 'contact-form-to-db' ); ?>
		</div>
	<?php }
}

/*
 * Function to display page of user guide
*/
if ( ! function_exists( 'cntctfrmtdb_user_guide' ) ) {
	function cntctfrmtdb_user_guide() { ?>
		<div class="cntctfrmtdb-page">
			<h3><?php _e( 'Content', 'contact-form-to-db-pro' ); ?></h3>
			<ul class="cntctfrmtdb-content">
				<li><a href="#cntctfrmtdb-anchor-1">1. <?php _e( 'Introduction', 'contact-form-to-db-pro' ); ?></a></li>
				<li><a href="#cntctfrmtdb-anchor-2">2. <?php _e( 'Settings page', 'contact-form-to-db-pro' ); ?></a></li>
				<li><a href="#cntctfrmtdb-anchor-3">3. <?php _e( 'Messages manager page', 'contact-form-to-db-pro' ); ?></a>
					<ul class="cntctfrmtdb-subcontent">
						<li><a href="#cntctfrmtdb-anchor-3-1"><?php _e( 'Column "Status"', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-3-2"><?php _e( 'Column "From"', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-3-3"><?php _e( 'Column "Message"', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-3-4"><?php _e( 'Column "Attached files"', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-3-5"><?php _e( 'Column "Send Counter"', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-3-6"><?php _e( 'Column "Date"', 'contact-form-to-db-pro' ); ?></a></li>
					</ul>
				</li>					
				<li><a href="#cntctfrmtdb-anchor-4">4. <?php _e( 'Main functions', 'contact-form-to-db-pro' ); ?></a>
					<ul class="cntctfrmtdb-subcontent">
						<li><a href="#cntctfrmtdb-anchor-4-1"><?php _e( 'Drop-down menu functions', 'contact-form-to-db-pro' ); ?></a></li>
						<li><a href="#cntctfrmtdb-anchor-4-2"><?php _e( 'Column "Message" functions', 'contact-form-to-db-pro' ); ?></a></li>
					</ul>
				</li>
			</ul>
			<h3 id="cntctfrmtdb-anchor-1">1. <?php _e( 'Introduction', 'contact-form-to-db-pro' ); ?></h3>
			<p><?php _e( 'Plugin Contact Form to DB Pro is an extension of the Contact Form plugin by BestWebSoft. The plugin allows to view and manage messages, coming via the contact form on your site.', 'contact-form-to-db-pro' ); ?> <strong><?php _e( 'Attention:', 'contact-form-to-db-pro' ); ?> </strong> <?php _e( "Plugin Contact Form to DB Pro is only compatible with the Contact Form plugin by BestWebSoft and doesn't work with any other similar plugins.", 'contact-form-to-db-pro' ); ?></p>
			<h3 id="cntctfrmtdb-anchor-2">2. <?php _e( 'Settings page', 'contact-form-to-db-pro' ); ?></h3>
			<img src="<?php echo plugins_url( 'screenshot-2.png', __FILE__ ); ?>" alt="<?php _e( 'Picture not found', 'contact-form-to-db-pro' ); ?>" title="<?php _e( 'Options page', 'contact-form-to-db-pro' ); ?>">
			<p><?php _e( 'On the plugin settings page there are redirecting links to the following pages: "User guide" and "FAQs".', 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( 'On the settings page you can select the required settings and turn off unnecessary settings.', 'contact-form-to-db-pro' ); ?></p>
			<h3 id="cntctfrmtdb-anchor-3">3. <?php _e( 'Messages manager', 'contact-form-to-db-pro' ); ?></h3>
			<img src="<?php echo plugins_url( 'images/user-guide-images/manager.png', __FILE__ ); ?>" alt="<?php _e( 'Picture not found', 'contact-form-to-db-pro' ); ?>" title="<?php _e( 'Manager page', 'contact-form-to-db-pro' ); ?>">
			<p><?php _e( 'The messages manager page shows all the messages coming via contact form on your site.', 'contact-form-to-db-pro' ); ?><p>
			<p><?php _e( 'The main elements of the page: search field, drop-down menu "Bulk actions", filters panel, the table of messages.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong><?php _e( 'The search field', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'serves to search messages in accordance with the applied criterion.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong><?php _e( 'Drop-down menu "Bulk actions"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'includes the functions for processing messages in groups. Depending on the selected filter, different messages will be displayed.', 'contact-form-to-db-pro' ); ?></p><br/>
			<img src="<?php echo plugins_url( 'images/user-guide-images/filter-row.png', __FILE__ ); ?>" alt="<?php _e( 'Picture not found', 'contact-form-to-db-pro' ); ?>" title="<?php _e( 'Row of filters', 'contact-form-to-db-pro' ); ?>">
			<p><?php _e( 'By clicking one of the links in', 'contact-form-to-db-pro' ); ?> <strong><?php _e( 'filters panel', 'contact-form-to-db-pro' ); ?></strong><?php _e( ', you can view messages grouped according to one of the criteria:', 'contact-form-to-db-pro' ); ?></p>
			<ul>
				<li><p><?php _e( '"All" - all the meaasges that are not spam or are not in the trash to be deleted later. This filter is set by default when entering the page.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Sent" - all successfully sent messages that are not spam or are not in the trash to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Not sent" - all the messages that failed to be sent and that are not spam or are not in the trash to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Read" - all read messages that are not spam or are not in the trash to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Unread" - all unread messages that are not spam or are not in the trash to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Has attachment" - all the messages having attachments that are not spam or are not in the trash to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Spam" - all the messages marked as spam.', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( '"Trash" - all the messages to be deleted later.', 'contact-form-to-db-pro' ); ?></p></li>
			</ul>
			<p><?php _e( 'Work with columns', 'contact-form-to-db-pro' ); ?> <strong><?php _e( 'tables of messages.', 'contact-form-to-db-pro' ); ?></strong></p>
			<p><strong id="cntctfrmtdb-anchor-3-1"><?php _e( 'Column "Status"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'displays three possible statuses of a message:', 'contact-form-to-db-pro' ); ?></p>
			<p><span class="cntctfrmtdb-letter"></span> <?php _e( 'Status "Normal" - is set by default for all messages, is available to be displayed by means of all filters except "Spam" and "Trash". The message maked "Normal" is available for all actions, provided by the plugin Contact Form to DB Pro.', 'contact-form-to-db-pro' ); ?></p>
			<p><span class="cntctfrmtdb-spam"></span> <?php _e( 'Status "Spam" - is set for suspicious messages that have possibly been sent as a result of commercial, political etc mailout. It is possible to view spam-messages by applying the "Spam" filter. A spam-message is not available for downloading or forwarding.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong><?php _e( 'Attention:', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'Plugin Contact Form to DB Pro does not provide automatic messages marking as "Spam". This marking is up to you.', 'contact-form-to-db-pro' ); ?></p>
			<p><span class="cntctfrmtdb-trash"></span> <?php _e( 'Status "in Trash" - is set for messages to be deleted later. Forwarding or downloading functions are not available for a message marked as "in Trash".', 'contact-form-to-db-pro' ); ?></p>
			<p><strong><?php _e( 'Message status change:', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'if it is necessary to change the status of a message, you can click the icon displaying the current status. The icon will be changed click-by-click. After the last click the status will be changed automatically, the message will be removed from the list and will be available by applying a filter corresponding the new status of the message from the filter panel.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong><?php _e( 'Attention:', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'for your convenience in working with this option it is necessary to enable javascript in your browser.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong id="cntctfrmtdb-anchor-3-2"><?php _e( 'Column "From"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( "displays the sender's contact information and the recipient's email address. Depending on the Contact Form plugin settings and the fields, filled out by the sender, the list of contact information can vary.", 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( "To view the contact information and the whole message ( if javascript is enabled ) you just need to click on the sender's name.", 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( "If the sender's name is in bold, the message has the status of 'Unread'. This staus is automatically changed for 'Read' by clicking on the name of the message's sender.", 'contact-form-to-db-pro' ); ?></p>
			<p><strong id="cntctfrmtdb-anchor-3-3"><?php _e( 'Column "Message"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( "displays the text of te message, as well as the name and the size of attached files. If javascript is enabled, the corresponding thumbnail is displayed in case the attached file is an image. To view the whole text of the message you should click on the sender's name in the column 'From'.", 'contact-form-to-db-pro' ); ?></p>
			<p><strong id="cntctfrmtdb-anchor-3-4"><?php _e( 'In column "Attached files"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'the following icons can be displayed:', 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( 'Icon', 'contact-form-to-db-pro' ); ?> <span class="cntctfrmtdb-has-attachment"></span> <?php _e( 'shows if there is an attached file.', 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( 'Icon', 'contact-form-to-db-pro' ); ?> <span class="cntctfrmtdb-not-saved-attachment"></span> <?php _e( "is displayed if a file has been attached to the message but because of the Contact Form to DB Pro plugin settings it hasn't been saved.", 'contact-form-to-db-pro' ); ?></p>
			<p><?php _e( 'Icon', 'contact-form-to-db-pro' ); ?> <span class="cntctfrmtdb-warning-attachment"></span> <?php _e( 'notifies if there are any errors can occur when saving attached files on the server or in the database.', 'contact-form-to-db-pro' ); ?></p>
			<p><strong id="cntctfrmtdb-anchor-3-5"><?php _e( 'Column "Send Counter"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( "displays the total number of messages sending attempts both via the contact form and the site's admin panel. Icon", 'contact-form-to-db-pro' ); ?> <span class="cntctfrmtdb-warning"></span> <?php _e( "as well as the message's lines highlited pink (if javascript is enabled ) is displayed in case if the message failed to be sent.", 'contact-form-to-db-pro' ); ?></p>
			<p><strong id="cntctfrmtdb-anchor-3-6"><?php _e( 'Column "Date"', 'contact-form-to-db-pro' ); ?></strong> <?php _e( "shows the date when the message's was recorded.", 'contact-form-to-db-pro' ); ?><p>
			<h3 id="cntctfrmtdb-anchor-4">4. <?php _e( 'Main functions', 'contact-form-to-db-pro' ); ?></h3>
			<p><strong id="cntctfrmtdb-anchor-4-1"><?php _e( 'Drop-down menu functions ( Bulk actions )', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'serve to manage messages in groups.', 'contact-form-to-db-pro' ); ?></p>
			<img src="<?php echo plugins_url( 'images/user-guide-images/bulk-actions-open.png', __FILE__ ); ?>" alt="<?php _e( 'Picture not found', 'contact-form-to-db-pro' ); ?>" title="<?php _e( 'Bulk actions', 'contact-form-to-db-pro' ); ?>">
			<ul>
				<li><p><?php _e( "'Re-send messages' - allows to send all selected messages to the sender's email address, specified in column 'From'. The function is not available to manage spam-messages or messages marked as 'in Trash'", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Download messages' - allows to save all selected messages on a local PC in the following formats: csv, xml, eml. The format can be selected on the plugin settings page.The function is not available to manage spam-messages or messages marked as 'in Trash'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Download attachments' - allows to download an attached file. The function is not available to manage spam-messages or messages marked as 'in Trash'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Mark as Spam' - allows to mark all selected messages as spam.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Mark as Trash' - allows to mark all selected messages that will be deleted later.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Not Spam' - allows to mark all selected spam-messages as not spam. The messages will be marked as 'Normal'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Restore' - allows to remove all selected messages from the list of messages to be deleted  later. The messages will be marked as 'Normal'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Delete Permanently' - allows to delete completely the information about all selected messages.", 'contact-form-to-db-pro' ); ?></p></li>
			</ul>					
			<p><strong id="cntctfrmtdb-anchor-4-2"><?php _e( 'Column "Messages" functions', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'serve to manage single messages and they are displayed when pointing to the column.', 'contact-form-to-db-pro' ); ?><p>
			<img src="<?php echo plugins_url( 'images/user-guide-images/message-row-actions.png', __FILE__ ); ?>" alt="<?php _e( 'Picture not found', 'contact-form-to-db-pro' ); ?>" title="<?php _e( 'Manager page', 'contact-form-to-db-pro' ); ?>">
			<ul>
				<li><p><?php _e( "'Re-send message' - allows to re-send a message to the sender's email address, specified in column 'From'. The function is not available to manage spam-messages or messages marked as 'in Trash'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Download message' - allows to save a message on a local PC in the following formats: csv, xml, eml. The format can be selected on the plugin settings page. The function is not available to manage spam-messages or messages marked as 'in Trash'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Spam' - allows to mark a message as spam.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Trash' - allows to mark a message that will be deleted later.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Not Spam' - allows to mark a spam-message as not spam. The message will be marked as 'Normal'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Restore' - allows to remove a message from the list of messages to be deleted  later. The messages will be marked as 'Normal'.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Delete Permanently' - allows to delete completely the information about the message.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'Download' - allows to download an attached file.", 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( "'View' - allows to view an image attached to the message.", 'contact-form-to-db-pro' ); ?></p></li>
			</ul>
		</div> <!-- .cntctfrmtdb-page -->
	<?php }
}

/*
 * Function to display page of frequently asked questions (FAQ)
*/
if ( ! function_exists( 'cntctfrmtdb_faq' ) ) {
	function cntctfrmtdb_faq() { ?>
		<div class="cntctfrmtdb-page">
			<h3><?php _e( 'Is it possible to forward messages to other users?', 'contact-form-to-db-pro' ); ?></h3>
			<p><?php _e( 'A message can only be sent to the users whose email addresses were specified in the Contact Form plugin settings as of the time sending a message or specifed in current time.', 'contact-form-to-db-pro' ); ?></p>
			<h3><?php _e( 'I would like to get extra settings for your plugin', 'contact-form-to-db-pro' ); ?></h3>
			<p><?php _e( "You can contact our company's", 'contact-form-to-db-pro' ); ?> <a href="http://support.bestwebsoft.com" target="_blank"><?php _e( 'support team', 'contact-form-to-db-pro' ); ?></a> <?php _e( 'with a detailed description of your requirements. Our support team will be glad to assist you.', 'contact-form-to-db-pro' ); ?></p>
			<h3><?php _e( 'Is it possible to re-send a spam-message?', 'contact-form-to-db-pro' ); ?></h3>
			<p><?php _e( 'If you are sure that the necessary message to be re-send is not spam, it is necessary to change its status for "Normal". In order to do that you can:', 'contact-form-to-db-pro' ); ?></p>
			<ol>
				<li><p><?php _e( 'change the status of the message in column "Status" ( how to do that see here', 'contact-form-to-db-pro' ); ?> <a href="admin.php?page=cntctfrmtdbpr_settings&action=user_guide"><?php _e( "the user's guide", 'contact-form-to-db-pro' ); ?></a> );</p></li>
				<li><p><?php _e( 'click "Not spam" in the panel of functional references of column "Messages";', 'contact-form-to-db-pro' ); ?></p></li>
				<li><p><?php _e( 'select the necessary message ( or messages ), and then make use of the function "Not spam" in the drop-down menu "Gruop actions".', 'contact-form-to-db-pro' ); ?></p></li>
			 </ol>
			 <p><?php _e( 'After that the message is available for re-sending and downloading.', 'contact-form-to-db-pro' ); ?></p>
		</div> <!-- .cntctfrmtdb-page -->
	<?php }
}

if ( ! function_exists( 'cntctfrmtdb_clear_data' ) ) {
	function cntctfrmtdb_clear_data( $data ) {
		return htmlspecialchars( stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $data ) ) ) ) );
	}
}

if ( ! function_exists( 'cntctfrm_options_for_this_plugin' ) ) {
	function cntctfrm_options_for_this_plugin() {
		global $cntctfrm_options_for_this_plugin;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		/**
		* @deprecated since 1.5.6
		* @todo update after 12.03.2017
		*/
		if ( is_plugin_active( 'contact-form-plugin/contact_form.php' ) ) {
			$cntctfrm_options_for_this_plugin = get_option( 'cntctfrm_options' );
		} elseif ( is_plugin_active( 'contact-form-pro/contact_form_pro.php' ) ) {				
			$cntctfrm_options_for_this_plugin = get_option( 'cntctfrmpr_options' );
			if ( empty( $cntctfrm_options_for_this_plugin ) )
				$cntctfrm_options_for_this_plugin = get_option( 'cntctfrm_options' );
		}
		/**
		* @deprecated since 1.5.6
		* @todo remove after 12.03.2017
		*/
		foreach ( $cntctfrm_options_for_this_plugin as $key => $value ) {
			if ( ! is_array( $value ) ) {
				$cntctfrm_options_for_this_plugin[ str_replace( 'cntctfrm_', '', $key ) ] = $value;
			}
		}
	}
}

/**
 * Function to get mail data from contact form
 * @param $name, $email, $address, $phone, $subject, $message, $form_action_url, $user_agent, $userdomain, $location deprecated since v1.3.9 - will be deleted in the future
 */
if ( ! function_exists( 'cntctfrmtdb_get_mail_data' ) ) {
	function cntctfrmtdb_get_mail_data( $to = '', $name = '', $email = '', $address = '', $phone = '', $subject = '', $message = '', $form_action_url = '', $user_agent = '', $userdomain = '', $location = '' ) {
		global $cntctfrmtdb_mail_data, $cntctfrm_options_for_this_plugin;

		$cntctfrmtdb_mail_data = array();
		if ( is_array( $to ) ) {
			$cntctfrmtdb_mail_data['sendto']          = $to['sendto'];
			$cntctfrmtdb_mail_data['refer']           = $to['refer'];
			$cntctfrmtdb_mail_data['useragent']       = $to['useragent'];
			$cntctfrmtdb_mail_data['username']        = isset( $_POST['cntctfrmpr_contact_name'] ) ? $_POST['cntctfrmpr_contact_name'] : '';
			if ( empty( $cntctfrmtdb_mail_data['username'] ) && isset( $_POST['cntctfrm_contact_name'] ) )
				$cntctfrmtdb_mail_data['username']    = $_POST['cntctfrm_contact_name'];
			$cntctfrmtdb_mail_data['useraddress']     = isset( $_POST['cntctfrmpr_contact_address'] ) ? $_POST['cntctfrmpr_contact_address'] : '';
			if ( empty( $cntctfrmtdb_mail_data['useraddress'] ) && isset( $_POST['cntctfrm_contact_address'] ) )
				$cntctfrmtdb_mail_data['useraddress'] = $_POST['cntctfrm_contact_address'];
			$cntctfrmtdb_mail_data['useremail']       = isset( $_POST['cntctfrmpr_contact_email'] ) ? $_POST['cntctfrmpr_contact_email'] : '';
			if ( empty( $cntctfrmtdb_mail_data['useremail'] ) && isset( $_POST['cntctfrm_contact_email'] ) )
				$cntctfrmtdb_mail_data['useremail']   = $_POST['cntctfrm_contact_email'];
			$cntctfrmtdb_mail_data['userphone']       = isset( $_POST['cntctfrmpr_contact_phone'] ) ? $_POST['cntctfrmpr_contact_phone'] : '';
			if ( empty( $cntctfrmtdb_mail_data['userphone'] ) && isset( $_POST['cntctfrm_contact_phone'] ) )
				$cntctfrmtdb_mail_data['userphone']   = $_POST['cntctfrm_contact_phone'];
			$cntctfrmtdb_mail_data['message_subject'] = isset( $_POST['cntctfrmpr_contact_subject'] ) ? $_POST['cntctfrmpr_contact_subject'] : '';
			if ( empty( $cntctfrmtdb_mail_data['message_subject'] ) && isset( $_POST['cntctfrm_contact_subject'] ) )
				$cntctfrmtdb_mail_data['message_subject'] = $_POST['cntctfrm_contact_subject'];
			$cntctfrmtdb_mail_data['message_text']    = isset( $_POST['cntctfrmpr_contact_message'] ) ? $_POST['cntctfrmpr_contact_message'] : '';
			if ( empty( $cntctfrmtdb_mail_data['message_text'] ) && isset( $_POST['cntctfrm_contact_message'] ) )
				$cntctfrmtdb_mail_data['message_text'] = $_POST['cntctfrm_contact_message'];
			$cntctfrmtdb_mail_data = array_map( 'cntctfrmtdb_clear_data', $cntctfrmtdb_mail_data );
		} else { /* for compatibility with old versions of Contact From by BestWebSoft */
			$cntctfrmtdb_mail_data['sendto']          = $to;
			$cntctfrmtdb_mail_data['username']        = $name;
			$cntctfrmtdb_mail_data['useremail']       = $email;
			$cntctfrmtdb_mail_data['userlocation']    = $location;
			$cntctfrmtdb_mail_data['useraddress']     = $address;
			$cntctfrmtdb_mail_data['userphone']       = $phone;
			$cntctfrmtdb_mail_data['message_subject'] = $subject;
			$cntctfrmtdb_mail_data['message_text']    = $message;
			$cntctfrmtdb_mail_data['refer']           = $form_action_url;
			$cntctfrmtdb_mail_data['useragent']       = $user_agent;
		}

		if ( isset( $_POST['cntctfrm_department'] ) ) {
			if ( empty( $cntctfrm_options_for_this_plugin ) )
				cntctfrm_options_for_this_plugin();

			if ( isset( $cntctfrm_options_for_this_plugin['departments']['name'][ $_POST['cntctfrm_department'] ] ) )
				$cntctfrmtdb_mail_data['department'] = cntctfrmtdb_clear_data( $cntctfrm_options_for_this_plugin['departments']['name'][ $_POST['cntctfrm_department'] ] );
		} else
			/**
			* @deprecated since 1.5.6
			* @todo update after 12.03.2017
			*/
			if ( isset( $_POST['cntctfrmpr_department'] ) ) {			
			global $cntctfrmpr_options;
			if ( empty( $cntctfrmpr_options ) )
				$cntctfrmpr_options = get_option( 'cntctfrmpr_options' );

			if ( isset( $cntctfrmpr_options['departments']['name'][ $_POST['cntctfrmpr_department'] ] ) )
				$cntctfrmtdb_mail_data['department'] = cntctfrmtdb_clear_data( $cntctfrmpr_options['departments']['name'][ $_POST['cntctfrmpr_department'] ] );
		}
	}
}

/*
* Function to check attachment if is image
*/
if ( ! function_exists( 'cntctfrmtdb_is_image' ) ) {
	function cntctfrmtdb_is_image( $mime_type ) {
		if ( in_array( $mime_type, array( 'image/gif', 'image/x-png', 'image/jpeg', 'image/tiff', 'image/x-ms-bmp', 'image/bmp', 'image/png' ) ) ) 
			return true;
		else
			return false;
	}
}

/*
* Function to get attachments and thumbnails
*/
if ( ! function_exists( 'cntctfrmtdb_get_attachment_data' ) ) {
	function cntctfrmtdb_get_attachment_data( $path_of_uploaded_file ) {
		global $cntctfrmtdb_options, $attachment_status, $wpdb;
		$attachment_status = 0;

		if ( empty( $cntctfrmtdb_options ) )
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );

		if ( 1 == $cntctfrmtdb_options['save_attachments'] ) {
			global $attachments_name, $attachments_type, $attachments_size, $attachments_content, $thumbnail_name, $thumbhail_type, $thumbnail_size, $thumbnail_content;
			
			$path_of_thumbnail = $attachment_details = '';

			/* we get the maximum possible file size.  
			60000 bytes is the approximate number of data packets that are sent together with the content file in our case
			*/
			$upload_max_size = $wpdb->get_var( 'SELECT @@global.max_allowed_packet' ) - 60000; 
			
			if ( ! get_magic_quotes_gpc() )
				$attachments_name = addslashes( $attachments_name );

			$attachments_type = isset( $_FILES["cntctfrm_contact_attachment"] ) ? $_FILES["cntctfrm_contact_attachment"]["type"] : $_FILES["cntctfrmpr_contact_attachment"]["type"];
			$attachments_size = isset( $_FILES["cntctfrm_contact_attachment"] ) ? $_FILES["cntctfrm_contact_attachment"]["size"] : $_FILES["cntctfrmpr_contact_attachment"]["size"];
			
			/* save file in to 'attachments' */
			if ( defined( 'UPLOADS' ) ) {
				if ( ! is_dir( ABSPATH . UPLOADS ) ) 
					wp_mkdir_p( ABSPATH . UPLOADS );
				$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
			} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
				if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
					wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
				$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
			} else {
				$upload_path = wp_upload_dir();
				$save_file_path = $upload_path['basedir'] . '/attachments';
			}
			if ( ! is_dir( $save_file_path ) )
				wp_mkdir_p( $save_file_path );

			/* ads numeric prefix to avoid coincidence of names */
			$attachments_name = isset( $_FILES["cntctfrm_contact_attachment"] ) ? rand( 1000, 9999 ) . '_' . sanitize_file_name( $_FILES["cntctfrm_contact_attachment"]["name"] ) : rand( 1000, 9999 ) . '_' . sanitize_file_name( $_FILES["cntctfrmpr_contact_attachment"]["name"] );

			if ( @copy( $path_of_uploaded_file, $save_file_path . '/' . $attachments_name ) ) {
				$attachment_status = 1;
				if ( 'database' == $cntctfrmtdb_options['save_attachments_to'] ) {
					if ( $upload_max_size >= $attachments_size ) {
						$fp = fopen( $path_of_uploaded_file, 'r' );
						$attachments_content = fread( $fp, $attachments_size );
						if ( ! get_magic_quotes_gpc() )
							$attachments_content = addslashes( $attachments_content );
						fclose( $fp );
					} else {
						$attachment_status = 4;
					}
				}
				/* create thumbnails on server */
				if ( cntctfrmtdb_is_image( $attachments_type ) ) {
					$max_width = 150;
					$max_height = 150;
					$attachment_details = getimagesize( $path_of_uploaded_file );
					if ( '' != $attachment_details ) {
						$width = $attachment_details[0];
						$height = $attachment_details[1];
						$img_create = '';
						if ( $width > $max_width && $height >$max_height ) {
							$aspect_ratio = $width / $height;
							if ( 1 <= $aspect_ratio ) {
								$thumbnail_width	= $max_width;
								$thumbnail_height 	= intval( $max_height / $aspect_ratio );
							} else {
								$thumbnail_width	= intval( $max_width * $aspect_ratio );
								$thumbnail_height	= $max_height;
							}
							if ( 1 == $attachment_details[2] ) {
								$img_create_from	= "imagecreatefromgif";
								$img_create			= "imagegif";
							} elseif ( 2 == $attachment_details[2] ) {
								$img_create_from	= "imagecreatefromjpeg";
								$img_create			= "imagejpeg";
							} elseif ( 3 == $attachment_details[2] ) {
								$img_create_from	= "imagecreatefrompng";
								$img_create			= "imagepng";
							} 
							if ( '' != $img_create ) {
								$thumbnail_name = 'small_' . $attachments_name;
								$path_of_thumbnail = $save_file_path . '/' . $thumbnail_name;
								if ( 3 == $attachment_details[2] ) {
									$thumbnail = imagecreate( $thumbnail_width, $thumbnail_height );
									imagecolorallocate( $thumbnail, 0xfc, 0xfc, 0xfc );
								} else {
									$thumbnail = imagecreatetruecolor( $thumbnail_width, $thumbnail_height );
								}
								$source = $img_create_from( $save_file_path . '/' . $attachments_name );
								imagecopyresized( $thumbnail, $source, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width, $height );
								$img_create( $thumbnail, $path_of_thumbnail );
								imagedestroy( $thumbnail );
							} else {
								/* create jpg-thumbnails from tiff- and bmp-images */
								if ( class_exists( 'Imagick' ) ) {
									$attachment_info	= pathinfo( $save_file_path . '/' . $attachments_name );
									$path_of_thumbnail	= $save_file_path . '/small_' . $attachment_info[ 'filename' ] . '.jpg';
									$image_thumbnail	= new Imagick( $save_file_path . '/' . $attachments_name );
									$image_thumbnail->writeImage( $path_of_thumbnail );
									$image_thumbnail->thumbnailImage( $thumbnail_width, 0 );
									$image_thumbnail->setImageFormat( 'jpeg' );
									$image_thumbnail->clear();
								}
							} 
						} else {
							if ( 'image/tiff' == $attachments_type || 'image/bmp' == $attachments_type || 'image/x-ms-bmp' == $attachments_type ) {
								if ( class_exists( 'Imagick' ) ) {
									$attachment_info	= pathinfo( $save_file_path . '/' . $attachments_name );
									$path_of_thumbnail	= $save_file_path . '/small_' . $attachment_info[ 'filename' ] . '.jpg';
									$image_thumbnail	= new Imagick( $save_file_path . '/' . $attachments_name );
									$image_thumbnail->writeImage( $path_of_thumbnail );
									$image_thumbnail->setImageFormat( 'jpeg' );
									$image_thumbnail->clear();
								}
							}
						}
						/* get thumbnail data  */
						if ( '' != $path_of_thumbnail ) {
							$thumbnail_name			= basename( $path_of_thumbnail );
							$thumbnails_details = getimagesize( $path_of_thumbnail );
							$thumbhail_type			= $thumbnails_details['mime'];
							$thumbnail_size			= filesize( $path_of_thumbnail );
							if ( 'database' == $cntctfrmtdb_options['save_attachments_to'] ) {
								$fp = fopen( $path_of_thumbnail, 'r' );
								$thumbnail_content = fread( $fp, $thumbnail_size );
								if( ! get_magic_quotes_gpc() )
									$thumbnail_content = addslashes( $thumbnail_content );
								fclose( $fp );
								unlink( $path_of_thumbnail );
							}
						}
					} else {
						/* if can not read data of attachment */
						$attachment_status = 2;
					}
				}
				if ( 'database' == $cntctfrmtdb_options['save_attachments_to'] ) {
					if ( $upload_max_size >= $attachments_size ) {
						unlink( $save_file_path . '/' . $attachments_name );
					}
				}
			} else {
				/* if attachment was not create in "attachments" folder */
				$attachment_status = 2;
			}
		} else {
			$attachment_status = 3;
		}
	}
}

/*
* Function to check was sent message or not 
*/
if ( ! function_exists( 'cntctfrmtdb_check_dispatch' ) ) {
	function cntctfrmtdb_check_dispatch( $cntctfrm_result ) {
		global $cntctfrmtdb_dispatched, $cntctfrmtdb_options;
		if ( empty( $cntctfrmtdb_options ) )
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
		$cntctfrmtdb_dispatched   = $cntctfrm_result ? 1 : 0;
		$save_message = '1' == $cntctfrmtdb_options['save_messages_to_db'] ? true : false;
		$message_sent = ( isset( $_SESSION['cntctfrm_send_mail'] ) && false == $_SESSION['cntctfrm_send_mail'] ) || ( isset( $_SESSION['cntctfrmpr_send_mail'] ) && false == $_SESSION['cntctfrmpr_send_mail'] ) ? true : false;
		if ( $save_message && $message_sent )
			cntctfrmtdb_save_message();
	}
}

/*
 * Function to save new message in database
 */
if ( ! function_exists( 'cntctfrmtdb_save_new_message' ) ) {
	function cntctfrmtdb_save_new_message() {
		global $cntctfrmtdb_mail_data, $attachments_name, $attachments_type, $attachments_size, $attachments_content, $attachment_status, 
		$thumbnail_name, $thumbhail_type, $thumbnail_size, $thumbnail_content, 
		$cntctfrmtdb_dispatched, $wpdb, $cntctfrm_options_for_this_plugin, $cntctfrmtdb_options;
		/* We fill necessary tables by Contact Form to DB plugin  */
		$attachments_id = $blogname_id = $to_email_id = $blogurl_id = $refer_id = '';
		$upload_path_id = 0;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';	

		if ( empty( $attachment_status ) )
			$attachment_status = 0;

		/* insert data about who was adressed to email */
		$to_email_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "to_email` WHERE `email`='" . $cntctfrmtdb_mail_data['sendto'] . "'" );
		if ( ! isset( $to_email_id ) ) {
			$wpdb->insert( $prefix . 'to_email', array( 'email' => $cntctfrmtdb_mail_data['sendto'] ) );
			$to_email_id = $wpdb->insert_id;
		}

		/* insert data about blogname */
		$blogname_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "blogname` WHERE `blogname`='" . get_bloginfo( 'name' ) . "'" );
		if ( ! isset( $blogname_id ) ) {
			$wpdb->insert( $prefix . 'blogname', array( 'blogname' => get_bloginfo( 'name' ) ) );
			$blogname_id = $wpdb->insert_id;
		}		
		
		/* insert URL of hosted site */
		$blogurl_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "hosted_site` WHERE `site`='" . get_bloginfo( "url" ) . "'" );
		if ( ! isset( $blogurl_id ) ) {
			$wpdb->insert( $prefix . 'hosted_site', array( 'site' => get_bloginfo( "url" ) ) );
			$blogurl_id = $wpdb->insert_id;
		}
	
		/* insert data about refer */
		$refer_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "refer` WHERE `refer`='" . $cntctfrmtdb_mail_data['refer'] . "'" );
		if ( ! isset( $refer_id ) ) {
			$wpdb->insert( $prefix . 'refer', array( 'refer' => $cntctfrmtdb_mail_data['refer'] ) );
			$refer_id = $wpdb->insert_id;
		}

		$wpdb->insert( $prefix . 'message',
			array(
				'from_user'         => $cntctfrmtdb_mail_data['username'],
				'user_email'        => $cntctfrmtdb_mail_data['useremail'],
				'subject'           => $cntctfrmtdb_mail_data['message_subject'],
				'message_text'      => $cntctfrmtdb_mail_data['message_text'],
				'dispatch_counter'  => '1',
				'to_id'          	=> $to_email_id,
				'was_read'          => '0',
				'sent'              => $cntctfrmtdb_dispatched,
				'status_id'         => '1',
				'attachment_status' => $attachment_status,
				'blogname_id'    	=> $blogname_id,			
				'hosted_site_id' 	=> $blogurl_id,
				'refer_id'       	=> $refer_id,
				'send_date'			=> current_time( 'mysql' )
			)
		);
		$message_id = $wpdb->insert_id;
		/* get option from Contact form or Contact form PRO */
		if ( ! $cntctfrm_options_for_this_plugin )
			cntctfrm_options_for_this_plugin();

		/* insert data about upload path of attachments */
		if ( 'uploads' == $cntctfrmtdb_options['save_attachments_to'] || '4' != $attachment_status ) {
			if ( defined( 'UPLOADS' ) ) {
				if ( ! is_dir( ABSPATH . UPLOADS ) ) 
					wp_mkdir_p( ABSPATH . UPLOADS );
				$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
			} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
				if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
					wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
				$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
			} else {
				$upload_path	= wp_upload_dir();
				$save_file_path = $upload_path['basedir'] . '/attachments';
			}
			$upload_path = substr( $save_file_path, strlen( ABSPATH ), -12 );

			$upload_path_id = $wpdb->get_var( "SELECT `path_id` FROM `" . $prefix . "upload_path` WHERE `path`='" . $upload_path . "'" );
			if ( ! isset( $upload_path_id ) ) {
				$wpdb->insert( $prefix . 'upload_path', array( 'path' => $upload_path ) );
				$upload_path_id = $wpdb->insert_id;
			}
		}

		/* insert data in to "attachments" table */
		if ( '' != $attachments_name || '' !=  $attachments_size ) {
			$mime_type_id = $wpdb->get_var( "SELECT `mime_types_id` FROM `" . $prefix . "mime_types` WHERE `mime_type`='" . $attachments_type . "';" );

			$wpdb->insert( $prefix . 'attachments',
				array(
					'message_id'         	=> $message_id,
					'name'               	=> $attachments_name,
					'size'               	=> $attachments_size,
					'content'            	=> $attachments_content,
					'att_upload_path_id' 	=> $upload_path_id,
					'mime_type_id' 			=> $mime_type_id
				)
			);
			$attachments_id = $wpdb->insert_id;
		}

		/* insert data in to "thumbnails" table */
		if ( isset( $thumbnail_name ) || isset( $thumbnail_size ) ) {
			$mime_type_id = $wpdb->get_var( "SELECT `mime_types_id` FROM `" . $prefix . "mime_types` WHERE `mime_type`='" . $thumbhail_type . "';" );

			$wpdb->insert( $prefix . 'thumbnails',
				array(
					'message_id'           	=> $message_id,
					'attachment_id'        	=> $attachments_id,
					'thumb_name'           	=> $thumbnail_name,
					'thumb_size'           	=> $thumbnail_size,
					'thumb_content'        	=> $thumbnail_content,
					'thumb_upload_path_id' 	=> $upload_path_id,
					'thumb_mime_type_id' 	=> $mime_type_id
				)
			);
		}		

		/* insert data about additionals fields */
		if ( isset( $cntctfrmtdb_mail_data['userlocation'] ) && '' != $cntctfrmtdb_mail_data['userlocation'] ) {
			$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='location'");
			$wpdb->insert( $prefix . 'field_selection',
				array( 
					'cntctfrm_field_id' => $field_id,
					'message_id'        => $message_id,
					'field_value'       =>  $cntctfrmtdb_mail_data['userlocation']
				)
			);
		}
		if ( isset( $cntctfrmtdb_mail_data['useraddress'] ) && '' != $cntctfrmtdb_mail_data['useraddress'] ) {
			$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='address'");
			$wpdb->insert( $prefix . 'field_selection',
				array( 
					'cntctfrm_field_id' => $field_id,
					'message_id'        => $message_id,
					'field_value'       =>  $cntctfrmtdb_mail_data['useraddress']
				)
			);
		}
		if ( isset( $cntctfrmtdb_mail_data['userphone'] ) && '' != $cntctfrmtdb_mail_data['userphone'] ) {
			$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='phone'");
			$wpdb->insert( $prefix . 'field_selection',
				array(
					'cntctfrm_field_id' => $field_id,
					'message_id'        => $message_id,
					'field_value'       => $cntctfrmtdb_mail_data['userphone']
				)
			);
		}
		if ( isset( $cntctfrmtdb_mail_data['department'] ) && '' != $cntctfrmtdb_mail_data['department'] ) {
			$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox'");
			$wpdb->insert( $prefix . 'field_selection',
				array(
					'cntctfrm_field_id' => $field_id,
					'message_id'        => $message_id,
					'field_value'       => $cntctfrmtdb_mail_data['department']
				)
			);
		}
		if ( '1' == $cntctfrm_options_for_this_plugin['display_user_agent']  ) {
			if ( isset( $cntctfrmtdb_mail_data['useragent'] ) && '' != $cntctfrmtdb_mail_data['useragent'] ) {
				$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='user_agent'");
				$wpdb->insert( $prefix . 'field_selection',
					array(
						'cntctfrm_field_id' => $field_id,
						'message_id'        => $message_id,
						'field_value'       => $cntctfrmtdb_mail_data['useragent']
					)
				);
			}
		}
	}
}

/*
* Function to check if is a new message and save message in database 
*/
if ( ! function_exists( 'cntctfrmtdb_save_message' ) ) {
	function cntctfrmtdb_save_message() {
		global $cntctfrmtdb_mail_data, $cntctfrmtdb_dispatched, $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		/* If message was not sent for some reason and user click again on "submit", counter of dispathces will +1.
		 in details:
		 - We get content of previous message. If previous message is not exists, we save current message in database.
		 - If previous message exists: we check message text and author name of previous message with message text and author name of current message.
		 - If the same, then we increments the dispatch counter previous message, if message was sent in this time, we so update 'sent' column in 'message' table.
		  - If not - write new message in database. */
		$previous_message_data = $wpdb->get_row( "SELECT `id`, `from_user`, `message_text`, `dispatch_counter`, `sent` FROM `" . $prefix . "message` WHERE `id` = ( SELECT MAX(`id`) FROM `" . $prefix . "message` )", ARRAY_A );
		if ( ! empty( $previous_message_data ) ) {
			if ( $cntctfrmtdb_mail_data['message_text'] == $previous_message_data['message_text'] && $cntctfrmtdb_mail_data['username'] == $previous_message_data['from_user'] ) {
				$counter = intval( $previous_message_data['dispatch_counter'] );
				$counter++;
				$wpdb->update( $prefix . 'message',
					array(
						'sent'             => $cntctfrmtdb_dispatched,
						'dispatch_counter' => $counter
					), array(
						'id' => $previous_message_data['id']
					)
				);
			} else {
				cntctfrmtdb_save_new_message();
			}
		} else {
			cntctfrmtdb_save_new_message();
		}
	}
}

/*
* Function to handle action links
*/
if ( ! function_exists( 'cntctfrmtdb_action_links' ) ) {
	function cntctfrmtdb_action_links() {
		global $wpdb, $cntctfrm_options_for_this_plugin, $cntctfrmtdb_done_message, $cntctfrmtdb_error_message, $cntctfrmtdb_options;

		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {				
			wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}

		if ( ( isset( $_REQUEST['action'] ) || isset( $_REQUEST['action2'] ) ) && check_admin_referer( plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) ) {

			/* get option from Contact form or Contact form PRO */
			if ( ! $cntctfrm_options_for_this_plugin )
				cntctfrm_options_for_this_plugin();

			/* prefix to the names of files to be saved */
			$random_number = rand( 100, 999 ); 
			/* We get path to 'attachments' folder */
			if ( defined( 'UPLOADS' ) ) {
				if ( ! is_dir( ABSPATH . UPLOADS ) ) 
					wp_mkdir_p( ABSPATH . UPLOADS );
				$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
			} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
				if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
					wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
				$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
			} else {
				$upload_path		= wp_upload_dir();
				$save_file_path = $upload_path['basedir'] . '/attachments';
			}
			if ( ! is_dir( $save_file_path ) ) {
				wp_mkdir_p( $save_file_path );
			}

			$prefix = $wpdb->prefix . 'cntctfrmtdb_';
			$ids = '';
			$action = ( isset( $_REQUEST['action'] ) && '-1' != $_REQUEST['action'] ) ? $_REQUEST['action'] : $_REQUEST['action2'];

			if ( isset( $_REQUEST['message_id'] ) && '' !=  $_REQUEST['message_id'] ) {
				/* when action is "undo", "restore" or "spam" - message id`s is a string like "2,3,4,5,6," */
				if ( preg_match( '|,|', $_REQUEST['message_id'][0] ) ) 
					$ids = explode( ',', $_REQUEST['message_id'][0] );
				
				$message_id = ( '' != $ids ) ? $ids : $_REQUEST['message_id'];

				$i = $error_counter = $counter = $have_not_attachment = $can_not_create_zip = $file_created = $can_not_create_file = $can_not_create_xml = 0;
				/* Create ZIP-archive if:
				 create zip-archives is possible and  one embodiment of the:
				 1) need to save several attachments
				 2) need to save several messages in "csv"-format and disabled option "Include content of attachments in to "csv"-file"
				 3) need to save several messages in "eml"-format */
				if ( class_exists( 'ZipArchive' ) && ( 'download_attachments' == $action  || 
					( 'download_messages' == $action && ( ( 'csv' == $cntctfrmtdb_options['format_save_messages'] && 0 == $cntctfrmtdb_options['include_attachments'] ) || 'eml' == $cntctfrmtdb_options['format_save_messages'] ) ) ) ) {
					/* create new zip-archive */
					$zip = new ZipArchive();
					$zip_name = $save_file_path . '/' .time() . ".zip";
					if ( ! $zip->open( $zip_name, ZIPARCHIVE::CREATE ) )
						$can_not_create_zip = 1;
				}
				/* we create a new "xml"-file */
				if ( in_array( $action, array( 'download_message', 'download_messages' ) ) && 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
					$xml = new DOMDocument( '1.0','utf-8' );
					$xml->formatOutput = true;
					/* create main element <messages></messages> */
					$messages = $xml->appendChild( $xml->createElement( 'cnttfrmtdb_messages' ) );
				}
				foreach ( $message_id as $id ) {
					if ( '' != $id ) {
						switch ( $action ) {
							case 're_send_message':
							case 're_send_messages':
								$message_text = '';
								/* we get message content from database */
								$message_data = $wpdb->get_results(
									"SELECT `from_user`, `user_email`, `send_date`, `subject`, `message_text`, `sent`, `dispatch_counter`, `name`, `size`, `content`, `path`, `mime_type`, `blogname`, `site`, `refer`, `email`
									FROM `" . $prefix . "message`
									LEFT JOIN `" . $prefix . "attachments` ON " . $prefix . "message.id=" . $prefix . "attachments.message_id
									LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id
									LEFT JOIN `" . $prefix . "mime_types` ON " . $prefix . "attachments.mime_type_id=" . $prefix . "mime_types.mime_types_id
									LEFT JOIN `" . $prefix . "blogname` ON " . $prefix . "message.blogname_id=" . $prefix . "blogname.id
									LEFT JOIN `" . $prefix . "hosted_site` ON " . $prefix . "message.hosted_site_id=" . $prefix . "hosted_site.id
									LEFT JOIN `" . $prefix . "refer` ON " . $prefix . "message.refer_id=" . $prefix . "refer.id
									LEFT JOIN `" . $prefix . "to_email` ON " . $prefix . "message.to_id=" . $prefix . "to_email.id
									WHERE " . $prefix . "message.id=" . $id
								);
								
								$additional_fields = $wpdb->get_results( 
									"SELECT `field_value`, `name` 
									FROM `" . $prefix . "field_selection`
									LEFT JOIN " . $wpdb->prefix . "cntctfrm_field ON " . $wpdb->prefix . "cntctfrm_field.id=" . $prefix . "field_selection.cntctfrm_field_id
									WHERE " . $prefix . "field_selection.message_id=" . $id
								);
								/* forming of message */
								foreach ( $message_data as $data ) {
									foreach ( $additional_fields as $field ) {
										if ( 'address' == $field->name )
											$data_address = $field->field_value;
										elseif ( 'phone' == $field->name )
											$data_phone = $field->field_value;
										elseif ( 'user_agent' == $field->name )
											$data_user_agent = $field->field_value;
										elseif ( 'location' == $field->name )
											$data_location = $field->field_value;
									}
									$message_text .=
										'<html>
											<head>
												<title>' . __( "Contact form to DB Pro", 'contact_form' );
									if ( '' != $data->blogname ) {
										$message_text .= $data->blogname;
									} else {
										$message_text .= get_bloginfo( 'name' );
									}
									$message_text .=
										'</title>
											</head>
												<body>
													<p>' . __( 'This message was re-sent from ', 'contact-form-to-db-pro' ) . home_url() . '</p>
													<table>
														<tr>
															<td width="160">' . __( "Name", 'contact-form-to-db-pro' ) . '</td><td>' . $data->from_user . '</td>
														</tr>';
									if ( isset( $data_location ) && '' != $data_location ) {
										$message_text .= '<tr><td>' . __( "Location", 'contact-form-to-db-pro' ) . '</td><td>' . $data_location . '</td></tr>';
									}
									if ( isset( $data_address ) && '' != $data_address ) {
										$message_text .= '<tr><td>' . __( "Address", 'contact-form-to-db-pro' ) . '</td><td>' . $data_address . '</td></tr>';
									}
									$message_text .= '<tr><td>' . __( "Email", 'contact-form-to-db-pro' ) . '</td><td>' . $data->user_email . '</td></tr>';
									if ( isset( $data_phone ) && '' != $data_phone ) {
										$message_text .= '<tr><td>' . __( "Phone", 'contact-form-to-db-pro' ) . '</td><td>' . $data_phone . '</td></tr>';
									}
									$message_text .= 
										'<tr><td>'. __( "Subject", 'contact-form-to-db-pro' ) . '</td><td>'. $data->subject .'</td></tr>
										<tr><td>'. __( "Message", 'contact-form-to-db-pro' ) . '</td><td>'. $data->message_text .'</td></tr>
										<tr><td>' . __( 'Site', 'contact-form-to-db-pro' ) . '</td><td>' . $data->site . '</td></tr>
										<tr><td><br /></td><td><br /></td></tr>
										<tr><td><br /></td><td><br /></td></tr>';

									if ( 1 == $cntctfrm_options_for_this_plugin['display_sent_from'] ) {
										$ip = '';
										if ( isset( $_SERVER ) ) {
											$sever_vars = array( 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
											foreach ( $sever_vars as $var ) {
												if ( isset( $_SERVER[ $var ] ) && ! empty( $_SERVER[ $var ] ) ) {
													if ( filter_var( $_SERVER[ $var ], FILTER_VALIDATE_IP ) ) {
														$ip = $_SERVER[ $var ];
														break;
													} else { /* if proxy */
														$ip_array = explode( ',', $_SERVER[ $var ] );
														if ( is_array( $ip_array ) && ! empty( $ip_array ) && filter_var( $ip_array[0], FILTER_VALIDATE_IP ) ) {
															$ip = $ip_array[0];
															break;
														}
													}
												}
											}
										}
										$message_text .= '<tr><td>' . __( 'Sent from (ip address)', 'contact-form-to-db-pro' ) . ':</td><td>' . $ip . " ( " . @gethostbyaddr( $ip ) ." )".'</td></tr>';
									}
									if ( '' != $data->send_date ) {
										$message_text .='<tr><td>' . __( 'Date/Time', 'contact-form-to-db-pro' ) . ':</td><td>' . date_i18n( get_option( 'date_format' ) . ' '. get_option( 'time_format' ), strtotime( $data->send_date ) ) . '</td></tr>';
									}
									if ( '' != $data->refer ) {
										$message_text .= '<tr><td>' . __( 'Sent from (referer)', 'contact-form-to-db-pro' ) . ':</td><td>' . $data->refer . '</td></tr>';
									}
									if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
										$message_text .= '<tr><td>' . __( 'Sent from (referer)', 'contact_form' ) . ':</td><td>' . $data_user_agent . '</td></tr>';
									}

									$message_text .=
												'</table>
											</body>
										</html>';
									/* add headers and attachments to message */
									$headers  = '';
									if ( '' !=  $data->name ) { /* if attachment exists */
										$message_block = $message_text;
										if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$headers .= 'From: ' . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ). '>' . "\n";
										else
											$headers .= 'From: ' . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ).' <' . $data->user_email . '>' . "\n";
																									
										$bound_text = "jimmyP123";
										$bound			= "--" . $bound_text . "";
										$bound_last = "--" . $bound_text . "--";
										$headers			.= "MIME-Version: 1.0\n" .	"Content-Type: multipart/mixed; boundary=\"$bound_text\"";
										$message_text .= __( "If you can see this MIME, it means that the MIME type is not supported by your email client!", "contact_form" ) . "\n";
										$message_text .= $bound . "\n" . "Content-Type: text/html; charset=\"utf-8\"\n" .
											"Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
										$message_text			.= $bound . "\n";
										$attachment_name	= substr( $data->name, 5 );
										$message_text			.= "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\"\n" .
											"Content-Description: " . $attachment_name . "\n" .
											"Content-Disposition: attachment;\n" . " filename=\"".$attachment_name."\"; size=" . $data->size.";\n";
										if ( '' !=  $data->content ) {
											$message_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( stripslashes( $data->content ) ) ) . "\n\n";
										} else {
											if ( '' != $data->path ) {
												$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
											} else {
												$path_to_file = $save_file_path . '/' . $data->name;
											}

											if ( file_exists( $path_to_file ) ) {
												$file = file_get_contents( $path_to_file );
												$message_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n";		
											}
										}
										$message_text .= $bound_last;
									} else {
										$headers  .= 'MIME-Version: 1.0' . "\n";
										$headers	.= 'Content-type: text/html; charset=utf-8' . "\n";
										if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$headers .= 'From: ' . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ). '>' . "\n";
										else
											$headers .= 'From: ' . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . $data->user_email . '>' . "\n";
										
										$headers .= 'To: ' . $data->email . "\n";
										$headers .= 'Subject: ' . $data->subject . "\n";
										$headers .= 'Date: ' . date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) ."\n";
									} 
									/* if option "Re-send a message to the email address specified in Contact Form Options" is enabled */
									if ( 1 == $cntctfrmtdb_options['mail_address'] ) {
										if ( $cntctfrm_options_for_this_plugin['select_email'] == 'user' ) {
											if ( false !== $user = get_user_by( 'login', $cntctfrm_options_for_this_plugin['user_email'] ) )
												$to = $user->user_email;
										} elseif ( $cntctfrm_options_for_this_plugin['select_email'] == 'departments' ) {
											$to = $data->email;
										} else {
											$to = $cntctfrm_options_for_this_plugin['custom_email'];
										}
										if ( empty( $to ) ) {
											/* If email options are not certain choose admin email */
											$to = get_option( "admin_email" );
										}
									} else {
										$to = $data->email;
									}
									
									/* send mail */
									if ( wp_mail( $to, $data->subject, $message_text, $headers ) ) {
										if ( '0' == $data->sent ) {
											$wpdb->update( $prefix . 'message', array(
													'sent'             => '1',
													'dispatch_counter' => intval( $data->dispatch_counter ) + 1
												), array( 
													'id' => $id 
												)
											);
										} else {
											$wpdb->update( $prefix . 'message', array( 'dispatch_counter' => intval( $data->dispatch_counter ) + 1 ), array( 'id' => $id ) );
										}
										$counter++;
									} else {
										$error_counter ++; 
									}
								}
								break;
							case 'download_message':
							case 'download_messages':
								/* we get message  content */
								$message_text = '';
								$message_data = $wpdb->get_results(
									"SELECT `from_user`, `user_email`, `send_date`, `subject`, `message_text`, `name`, `size`, `content`, `path`, `mime_type`, `blogname`, `site`, `refer`, `email`
									FROM `" . $prefix . "message`
									LEFT JOIN `" . $prefix . "attachments` ON " . $prefix . "message.id=" . $prefix . "attachments.message_id
									LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id
									LEFT JOIN `" . $prefix . "mime_types` ON " . $prefix . "attachments.mime_type_id=" . $prefix . "mime_types.mime_types_id
									LEFT JOIN `" . $prefix . "blogname` ON " . $prefix . "message.blogname_id=" . $prefix . "blogname.id
									LEFT JOIN `" . $prefix . "hosted_site` ON " . $prefix . "message.hosted_site_id=" . $prefix . "hosted_site.id
									LEFT JOIN `" . $prefix . "refer` ON " . $prefix . "message.refer_id=" . $prefix . "refer.id
									LEFT JOIN `" . $prefix . "to_email` ON " . $prefix . "message.to_id=" . $prefix . "to_email.id
									WHERE " . $prefix . "message.id=" . $id
								);
								$additional_fields = $wpdb->get_results( 
									"SELECT `field_value`, `name` 
									FROM `" . $prefix . "field_selection`
									LEFT JOIN " . $wpdb->prefix . "cntctfrm_field ON " . $wpdb->prefix . "cntctfrm_field.id=" . $prefix . "field_selection.cntctfrm_field_id
									WHERE " . $prefix . "field_selection.message_id=" . $id
								);
								/* forming file in "XML" format */
								if ( 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
									foreach ( $message_data as $data ) {
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name )
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
											elseif ( 'location' == $field->name )
												$data_location = $field->field_value;
										}
										/* creation main element for single message <message></message> */
										$message		= $messages->appendChild( $xml->createElement( 'cnttfrmtdb_message' ) );
										/* insert <from></from> in to <message></messsage> */
										$from			= $message->appendChild( $xml->createElement( 'cnttfrmtdb_from' ) );
										/* insert text  in to <from></from> */
										$from_text		= $from->appendChild( $xml->createTextNode( $data->blogname . '&lt;' . $data->user_email . '&gt;' ) );
										/* insert <to></to> in to <message></messsage>  */
										$to				= $message->appendChild( $xml->createElement( 'cnttfrmtdb_to' ) );
										/* insert text  in to <to></to> */
										$to_text		= $to->appendChild( $xml->createTextNode( $data->email ) );
										if ( '' !=  $data->subject ) {
											/* insert <subject></subject> in to <message></messsage> */
											$subject		= $message->appendChild( $xml->createElement( 'cnttfrmtdb_subject' ) );
											/* insert text  in to <subject></subject> */
											$subject_text	= $subject->appendChild( $xml->createTextNode( $data->subject ) );
										}
										/* insert <send_date></send_date> in to <message></messsage> */
										$send_date	= $message->appendChild( $xml->createElement( 'cnttfrmtdb_send_date' ) );
										/* insert text  in to <send_date></send_date> */
										$data_text	= $send_date->appendChild( $xml->createTextNode( $data->send_date ) );
										/* insert <content></content> in to <message></messsage> */
										$content	= $message->appendChild( $xml->createElement( 'cnttfrmtdb_content' ) );
										if ( '' != $data->subject ) {
											/* insert <name></name> in to <content></content> */
											$name				= $content->appendChild( $xml->createElement( 'cnttfrmtdb_name' ) );
											/* insert text  in to <name></name> */
											$name_text	= $name->appendChild( $xml->createTextNode( $data->from_user ) );
										}
										if ( isset( $data_location ) && '' != $data_location ) {
											/* insert <location></location> in to <content></content> */
											$location = $content->appendChild( $xml->createElement( 'cnttfrmtdb_location' ) );
											/* insert text  in to <location></location> */
											$location_text = $location->appendChild( $xml->createTextNode( $data_location ) );
										}
										if ( isset( $data_address ) && '' != $data_address ) {
											/* insert <address></address> in to <content></content> */
											$address = $content->appendChild( $xml->createElement( 'cnttfrmtdb_address' ) );
											/* insert text  in to <address></address> */
											$address_text = $address->appendChild( $xml->createTextNode( $data_address ) );
										}
										if ( '' != $data->user_email ) {
											/* insert <from_email></from_email> in to <content></content> */
											$from_email				= $content->appendChild( $xml->createElement( 'cnttfrmtdb_from_email' ) );
											/* insert text  in to <from_email></from_email> */
											$from_email_text	= $from_email->appendChild( $xml->createTextNode( $data->user_email ) );
										}
										if ( isset( $data_phone ) && '' !=  $data_phone ) {
											/* insert <phone></phone> in to <content></content> */
											$phone			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_phone' ) );												
											/* insert text  in to <phone></phone> */
											$phone_text		= $phone->appendChild( $xml->createTextNode( $data_phone ) );
										}
										if ( '' != $data->message_text ) {
											/* insert <text></text> in to <content></content> */
											$text			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_text' ) );
											/* insert message text in to <text></text> */
											$message_text 	= $text->appendChild( $xml->createTextNode( $data->message_text ) );
										}
										/* insert <hosted_site></hosted_site> in to <content></content> */
										$hosted_site		= $content->appendChild( $xml->createElement( 'cnttfrmtdb_hosted_site' ) );
										/* insert text in to <hosted_site></hosted_site> */
										$hosted_site_text	= $hosted_site->appendChild( $xml->createTextNode( $data->site ) );
										/* insert <sent_from_refer></sent_from_refer> in to <content></content> */
										$sent_from_refer	= $content->appendChild( $xml->createElement( 'cnttfrmtdb_sent_from_refer' ) ); 
										/* insert text in to <sent_from_refer></sent_from_refer> */
										$refer_text			= $sent_from_refer->appendChild( $xml->createTextNode( $data->refer ) );
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											/* insert <user_agent></user_agent> in to <content></content> */
											$user_agent				= $content->appendChild( $xml->createElement( 'cnttfrmtdb_user_agent' ) );
											/* insert text in to <user_agent></user_agent> */
											$user_agent_text	= $user_agent->appendChild( $xml->createTextNode( $data_user_agent ) );
										}
										/* insert <attachment></attachment> in to <content></content> and add attachment content */
										if ( '' !=  $data->name ) { 
											/* if attachment exists */
											$attachment_text		= '';
											$attachment				= $message->appendChild( $xml->createElement( 'cnttfrmtdb_attachment' ) ); 
											$bound_text				= "jimmyP123";
											$bound					= "--" . $bound_text . "--";
											$bound_last				= "--" . $bound_text . "--";

											$attachment_text .= $bound."\n";
											$attachment_text .= "Content-Type: application/octet-stream; name=\"" . substr( $data->name, 5 ) . "\"\n" .
												"Content-Description: ". substr( $data->name, 5 ) ."\n" .
												"Content-Disposition: attachment;\n" . " filename=\"". substr( $data->name, 5 ) . "\"; size=".$data->size.";\n";
											if ( '' !=  $data->content ) {
												$attachment_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( stripslashes( $data->content ) ) ) . "\n\n";
											} else {
												if ( '' != $data->path ) {
													$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
												} else {
													$path_to_file = $save_file_path . '/' . $data->name;
												}

												if ( file_exists( $path_to_file ) ) {
													$file = file_get_contents( $path_to_file );
													$attachment_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n";
												}
											}
											$attachment_text .= $bound_last;
											$attachment_content = $attachment->appendChild( $xml->createTextNode( $attachment_text ) );
										}
									}
								/* forming file in "EML" format */
								} elseif ( 'eml' == $cntctfrmtdb_options['format_save_messages'] ) {
									foreach ( $message_data as $data ) {
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name )
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
											elseif ( 'location' == $field->name )
												$data_location = $field->field_value;
										}
													
										$message_text .= 
											'<html>
												<head>
													<title>'. __( "Contact from to DB", 'contact_form' ) . ' ';
										$message_text .= ( '' != $data->blogname ) ? $data->blogname : get_bloginfo( 'name' );
										$message_text .= '</title>
												</head>
													<body>
														<p>' . __( 'This message was re-sent from ', 'contact-form-to-db-pro' ) . home_url() . '</p>
														<table>
															<tr><td width="160">'. __( "Name", 'contact-form-to-db-pro' ) . '</td><td>' . $data->from_user . '</td></tr>';
										if ( isset( $data_location ) && '' != $data_location ) {
											$message_text .= '<tr><td>'. __( "Location", 'contact-form-to-db-pro' ) . '</td><td>'. $data_location .'</td></tr>';
										}
										if ( isset( $data_address ) && '' != $data_address ) {
											$message_text .= '<tr><td>'. __( "Address", 'contact-form-to-db-pro' ) . '</td><td>'. $data_address .'</td></tr>';
										}
										$message_text .= '<tr><td>'. __( "Email", 'contact-form-to-db-pro' ) . '</td><td>' . $data->user_email . '</td></tr>';
										if ( isset( $data_phone ) && '' !=  $data_phone ) {
											$message_text .= '<tr><td>' . __( "Phone", 'contact-form-to-db-pro' ) . '</td><td>' . $data_phone . '</td></tr>';
										}
										$message_text .=
											'<tr><td>' . __( "Subject", 'contact-form-to-db-pro' ) . '</td><td>'. $data->subject .'</td></tr>
											<tr><td>' . __( "Message", 'contact-form-to-db-pro' ) . '</td><td>'. $data->message_text .'</td></tr>
											<tr><td>' . __( 'Site', 'contact-form-to-db-pro' ) . '</td><td>'. $data->site .'</td></tr>
											<tr><td><br /></td><td><br /></td></tr>
											<tr><td><br /></td><td><br /></td></tr>';
										if ( 1 == $cntctfrm_options_for_this_plugin['display_sent_from'] ) {
											$ip = '';
											if ( isset( $_SERVER ) ) {
												$sever_vars = array( 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
												foreach ( $sever_vars as $var ) {
													if ( isset( $_SERVER[ $var ] ) && ! empty( $_SERVER[ $var ] ) ) {
														if ( filter_var( $_SERVER[ $var ], FILTER_VALIDATE_IP ) ) {
															$ip = $_SERVER[ $var ];
															break;
														} else { /* if proxy */
															$ip_array = explode( ',', $_SERVER[ $var ] );
															if ( is_array( $ip_array ) && ! empty( $ip_array ) && filter_var( $ip_array[0], FILTER_VALIDATE_IP ) ) {
																$ip = $ip_array[0];
																break;
															}
														}
													}
												}
											}
											$message_text .= '<tr><td>' . __( 'Sent from (ip address)', 'contact-form-to-db-pro' ) . ':</td><td>' . $ip . " ( " . @gethostbyaddr( $ip ) ." )".'</td></tr>';
										}
										if ( '' != $data->send_date ) {
											$message_text .= '<tr><td>' . __( 'Date/Time', 'contact-form-to-db-pro' ) . ':</td><td>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $data->send_date ) ) . '</td></tr>';
										}
										if ( '' != $data->refer ) {
											$message_text .= '<tr><td>' . __( 'Sent from (referer)', 'contact-form-to-db-pro' ) . ':</td><td>' . $data->refer . '</td></tr>';
										}
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											$message_text .= '<tr><td>' . __( 'Sent from (referer)', 'contact_form' ) . ':</td><td>' . $data_user_agent . '</td></tr>';
										}
										$message_text .=
													'</table>
												</body>
											</html>';
									}
									/* get headers and attachments */
									$headers = '';
									if ( '' != $data->name ) { /* if attachment exists */
										$message_block = $message_text;
										if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$headers .= __( 'From: ', 'contact-form-to-db-pro' ) . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ).' <'.stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ). '>' . "\n";
										else
											$headers .= __( 'From: ', 'contact-form-to-db-pro' ) .stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ).' <' . $data->user_email . '>' . "\n";
										$headers .= __( 'To: ', 'contact-form-to-db-pro' ) . $data->email . "\n";
										$headers .= __( 'Subject: ', 'contact-form-to-db-pro' ) . $data->subject . "\n";
										$headers .= __( 'Date/Time: ', 'contact-form-to-db-pro' ) . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . "\n";
													
										$bound_text		= "jimmyP123";
										$bound			= "--" . $bound_text . "";
										$bound_last		= "--" . $bound_text . "--";
										$headers		.= "MIME-Version: 1.0\n" . "Content-Type: multipart/mixed; boundary=\"$bound_text\"";
										$message_text .= __( "If you can see this MIME, it means that the MIME type is not supported by your email client!", "contact_form" ) . "\n";
										$message_text .= $bound . "\n" . "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
										$message_text .= $bound . "\n";
										$attachment_name = substr( $data->name, 5 );
										$message_text		.= "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\"\n" .
											"Content-Description: " . $attachment_name ."\n" .
											"Content-Disposition: attachment;\n" . " filename=\"" . $attachment_name . "\"; size=" . $data->size . ";\n";
										if ( '' !=  $data->content ) { /* if attachment was save in database */
											$message_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( stripslashes( $data->content ) ) ) . "\n\n";
										} else { /* if attachment was save in "uploads" folder */

											if ( '' != $data->path ) {
												$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
											} else {
												$path_to_file = $save_file_path . '/' . $data->name;
											}

											if ( file_exists( $path_to_file ) ) {
												$file = file_get_contents( $path_to_file );
												$message_text .= "Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n";		
											}
										}
										$message_text .= $bound_last;
									} else {
										$headers .= 'MIME-Version: 1.0' . "\n";
										$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
										if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$headers .= __( 'From: ', 'contact-form-to-db-pro' ) . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ) . '>' . "\n";	
										else
											$headers .= __( 'From: ', 'contact-form-to-db-pro' ) . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . $data->user_email . '>' . "\n";
										$headers .= __( 'To: ', 'contact-form-to-db-pro' ) . $data->email . "\n";
										$headers .= __( 'Subject: ', 'contact-form-to-db-pro' ) . $data->subject . "\n";
										$headers .= __( 'Date/Time: ', 'contact-form-to-db-pro' ) . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . "\n";
									} 
									$message = $headers . $message_text;
									/* generate a file name */
									/* add numeric prefix to file name */
									$random_prefix = $random_number + $i; 
									/* to names have been streamlined */
									$i ++;
									$file_name = 'message_' . 'ID_' . $id . '_' . $random_prefix . '.eml';
									if ( 'download_messages' == $action ) { 
										/* add message to zip-archive if need save a several messages */
										if ( class_exists( 'ZipArchive' ) ) {
											/* add file content to zip - archive */
											$zip->addFromString( $file_name, $message );
											$counter ++;
										}
									} else {
										/* save message to local computer if need save a single message */
										if ( file_exists( $save_file_path . '/' . $file_name ) )
											$file_name = time() . '_' . $file_name;
										$fp = fopen( $save_file_path . '/' . $file_name, 'w');
										fwrite( $fp, $message );
										$file_created = fclose( $fp );
										if ( '0' != $file_created ) {
											header( 'Content-Description: File Transfer' );
											header( 'Content-Type: application/force-download' );
											header( 'Content-Disposition: attachment; filename=' . $file_name );
											header( 'Content-Transfer-Encoding: binary' );
											header( 'Expires: 0' );
											header( 'Cache-Control: must-revalidate');
											header( 'Pragma: public' );
											header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name )  );
											flush();
											$file_downloaded = readfile( $save_file_path . '/' . $file_name );
											if ( $file_downloaded )
												unlink( $save_file_path . '/' . $file_name );
										} else {
											$error_counter ++;
										}
									}
								/* forming files in to "CSV" format */
								} elseif ( 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
									$count_messages = count( $message_id ); /* number of messages which was chosen for downloading */
									/* we get enclosure anf separator from option */
									$enclosure = stripslashes( $cntctfrmtdb_options['csv_enclosure'] );
									if ( 't' == $cntctfrmtdb_options['csv_separator'] )
										$separator = "\\" . stripslashes( $cntctfrmtdb_options['csv_separator'] );
									else
										$separator = stripslashes( $cntctfrmtdb_options['csv_separator'] );
									/* forming file content */
									foreach ( $message_data as $data ) {
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name ) 
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
											elseif ( 'location' == $field->name )
												$data_location = $field->field_value;
										}
										/* if was chosen only one message and disabled option "Include content of attachments in to "csv"-file" and
										message has attachment and create ZIP-archive is possible  */												
										if ( 1 == $count_messages && 0 == $cntctfrmtdb_options['include_attachments'] && '' != $data->name && class_exists( 'ZipArchive' ) ) {
											/* create new zip-archive */
											$zip = new ZipArchive(); 
											$zip_name = $save_file_path . '/' .time() . ".zip";
											if ( ! $zip->open( $zip_name, ZIPARCHIVE::CREATE )  ) {
												$can_not_create_zip = 1;
												break;
											}
										}
										if ( ! isset( $message ) ) 
											$message = '';
										if( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$message .= $enclosure . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ) . '>' . $enclosure . $separator ;
										else
											$message .= $enclosure . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . $data->user_email . '>' . $enclosure . $separator ; 
										$message .= $enclosure . $data->email . $enclosure . $separator;
										if ( '' !=  $data->subject )
											$message .= $enclosure . $data->subject . $enclosure . $separator;
										if ( '' !=  $data->message_text )
											$message .= $enclosure . $data->message_text . $enclosure . $separator;
										$message .= $enclosure . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $data->send_date ) ) . $enclosure . $separator;
										$message .= $enclosure . $data->from_user . $enclosure . $separator;
										if ( isset( $data_location ) && '' !=  $data_location ) 
											$message .= $enclosure . $data_location . $enclosure . $separator;
										if ( isset( $data_address ) && '' !=  $data_address ) 
											$message .= $enclosure . $data_address . $enclosure . $separator;
										if ( '' !=  $data->user_email )
											$message .= $enclosure . $data->user_email . $enclosure . $separator;
										if ( isset( $data_phone ) && '' !=  $data_phone )
											$message .= $enclosure . $data_phone . $enclosure . $separator;
										$message .= $enclosure . $data->site . $enclosure . $separator;
										if ( 1 == $cntctfrm_options_for_this_plugin['display_sent_from'] ) {
											$ip = '';
											if ( isset( $_SERVER ) ) {
												$sever_vars = array( 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
												foreach ( $sever_vars as $var ) {
													if ( isset( $_SERVER[ $var ] ) && ! empty( $_SERVER[ $var ] ) ) {
														if ( filter_var( $_SERVER[ $var ], FILTER_VALIDATE_IP ) ) {
															$ip = $_SERVER[ $var ];
															break;
														} else { /* if proxy */
															$ip_array = explode( ',', $_SERVER[ $var ] );
															if ( is_array( $ip_array ) && ! empty( $ip_array ) && filter_var( $ip_array[0], FILTER_VALIDATE_IP ) ) {
																$ip = $ip_array[0];
																break;
															}
														}
													}
												}
											}
											$message .= $enclosure . __( 'Sent from (ip address): ', 'contact-form-to-db-pro' ) . $ip . " ( " . @gethostbyaddr( $ip ) ." )" . $enclosure . $separator; 
										}
										if ( '' != $data->refer ) {
											$message .= $enclosure . $data->refer . $enclosure . $separator;
										}
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											$message .= $enclosure . $data_user_agent . $enclosure . $separator;
										}
										if ( '' !=  $data->name ) {
											/* add attachment data to "csv"-file if enabled option "Include content of attachments in to "csv"-file" */
											if ( 1 == $cntctfrmtdb_options['include_attachments'] ) {
												$bound_text				= "jimmyP123";
												$bound					= "--" . $bound_text . "--";
												$bound_last				= "--" . $bound_text . "--";
												$attachment_name		= substr( $data->name, 5 );
												
												$message				.= $enclosure .  $bound . "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\""
													. "Content-Description: ". $attachment_name . "Content-Disposition: attachment;" . " filename=\"".$attachment_name."\"; size=" . $data->size . ";";
												if ( '' !=  $data->content ) {
													$message .= "Content-Transfer-Encoding: base64" . chunk_split( base64_encode( stripslashes( $data->content ) ) );
												} else {
													if ( '' != $data->path ) {
														$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
													} else {
														$path_to_file = $save_file_path . '/' . $data->name;
													}

													if ( file_exists( $path_to_file ) ) {
														$file = file_get_contents( $path_to_file );
														$message .= "Content-Transfer-Encoding: base64" . chunk_split( base64_encode( $file ) );		
													}
												}
												$message .= $bound_last . $enclosure . $separator;
											} else { 
												/* add attachet file to zip-archve if disabled option "Include content of attachments in to "csv"-file" */
												if ( class_exists( 'ZipArchive' ) ) {
													$file_name = substr( $data->name, 5 );
													if ( '' !=  $data->content ) {
														$zip->addFromString( $file_name, stripslashes( $data->content ) );
													} else {
														if ( '' != $data->path ) {
															$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
														} else {
															$path_to_file = $save_file_path . '/' . $data->name;
														}

														if ( file_exists( $path_to_file ) ) {
															$zip->addFile( $path_to_file, $file_name );
														}
													}
												}
											}

										}
										/* if was chosen only one message and disabled option "Include content of attachments in to "csv"-file" and
										 message has attachment and create ZIP-archive is possible
										 we save zip-file on local computer */
										if ( 1 == $count_messages && 0 == $cntctfrmtdb_options['include_attachments'] &&
										 '' != $data->name && class_exists( 'ZipArchive' ) ) {	
										 	$file_name = 'message_' . 'ID_' . $id . '_' . $random_number . '.csv';
											$zip->addFromString( $file_name, $message );											 	
											$zip->close();
											if ( file_exists( $zip_name ) ) {
												header( 'Content-Description: File Transfer' );
												header( 'Content-Type: application/x-zip-compressed' );
												header( 'Content-Disposition: attachment; filename=' . time() . '.zip' );
												header( 'Content-Transfer-Encoding: binary' );
												header( 'Expires: 0' );
												header( 'Cache-Control: must-revalidate' );
												header( 'Pragma: public' );
												header( 'Content-Length: ' . filesize( $zip_name ) );
												flush();
												$file_downloaded = readfile( $zip_name );
												if ( $file_downloaded ) {
													unlink( $zip_name );		
												}										
											}
										/* if was chosen only one message and ( message has not attachment  or enabled option "Include content of attachments in to "csv"-file"* )
										 * - in this condition it means if message has attachment then content of current attachment was include in "csv"-file  */
										} elseif ( 1 == $count_messages && ( '' == $data->name || 1 == $cntctfrmtdb_options['include_attachments'] ) ) {
											/* saving file to local computer */
											$file_name = 'message_' . 'ID_' . $id . '_' . $random_number . '.csv';
											if ( file_exists( $save_file_path . '/' . $file_name ) )
												$file_name = time() . '_' . $file_name;
											$fp = fopen( $save_file_path . '/' . $file_name, 'w');
											fwrite( $fp, $message );
											$file_created = fclose( $fp );
											if ( '0' != $file_created ) {
												header( 'Content-Description: File Transfer' );
												header( 'Content-Type: application/force-download' );
												header( 'Content-Disposition: attachment; filename=' . $file_name );
												header( 'Content-Transfer-Encoding: binary' );
												header( 'Expires: 0' );
												header( 'Cache-Control: must-revalidate');
												header( 'Pragma: public' );
												header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name )  );
												flush();
												$file_downloaded = readfile( $save_file_path . '/' . $file_name );
												if ( $file_downloaded )
													unlink( $save_file_path . '/' . $file_name );
											} else {
												$error_counter ++;
											}
										/* if was chosen more then one message */
										} elseif ( 1 < $count_messages ) {
											/* if attachment to message exists we add attachment name in "csv"-file to find needed file */
											if ( '' !=  $data->name )
												$message .= $enclosure . substr( $data->name, 5 ) . $enclosure . $separator;
											$message .= "\n";
										}
									}
								} else {
									$error_counter ++;
									$unknown_format = 1;
								}
								break;
							case 'download_attachment':
							case 'download_attachments':
								/* get attachment data */
								$attachment_data = $wpdb->get_results( 
									"SELECT `id`, `name`, `size`, `content`, `path`,`mime_type` FROM `" . $prefix . "attachments`
									LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id 
									LEFT JOIN `" . $prefix . "mime_types` ON " . $prefix . "attachments.mime_type_id=" . $prefix . "mime_types.mime_types_id 
									WHERE `message_id`=" . $id 
								);
								$file_not_found = 0;
								foreach ( $attachment_data as $data ) {
									$file_name = 'ID' . '_' .$id . '_' . substr( $data->name, 5 );
									/* if file was save in database */
									if ( '' !=  $data->content ) {											
										if ( 'download_attachments' == $action ) {
											if ( class_exists( 'ZipArchive' ) ) {
												/* add file content to zip - archive */
												$zip->addFromString( $file_name, stripslashes( $data->content ) ); 
												$counter ++;
											}
										} else {											
											if ( file_exists( $save_file_path . '/' . $file_name ) )
												$file_name = time() . '_' . $file_name;
											$fp = fopen( $save_file_path . '/' . $file_name, 'w');
											fwrite( $fp, stripslashes( $data->content ) );
											$file_created = fclose( $fp );
											if ( '0' != $file_created ) {
												header( 'Content-Description: File Transfer' );
												header( 'Content-Type: application/force-download' );
												header( 'Content-Disposition: attachment; filename=' . $file_name );
												header( 'Content-Transfer-Encoding: binary' );
												header( 'Expires: 0' );
												header( 'Cache-Control: must-revalidate');
												header( 'Pragma: public' );
												header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name )  );
												flush();
												$file_downloaded = readfile( $save_file_path . '/' . $file_name );
												if ( $file_downloaded )
													unlink( $save_file_path . '/' . $file_name );
											} else {
												$error_counter ++;
											}
										}
									} else {
										if ( '' != $data->path ) {
											$path_to_file = ABSPATH . $data->path . '/attachments/' . $data->name;
										} else {
											$path_to_file = $save_file_path . '/' . $data->name;
										}
										/* if file was find in "attacnments" folder */
										if ( file_exists( $path_to_file ) ) { 
											if ( 'download_attachments' == $action ) {
												if ( class_exists( 'ZipArchive' ) ) {
													 /* add file content to zip - archive */
													$zip->addFile( $path_to_file, $file_name );
													$counter ++;
												}
											} else {
												header( 'Content-Description: File Transfer' );
												header( 'Content-Type: application/force-download' );
												header( 'Content-Disposition: attachment; filename=' . $file_name );
												header( 'Content-Transfer-Encoding: binary' );
												header( 'Expires: 0' );
												header( 'Cache-Control: must-revalidate' );
												header( 'Pragma: public' );
												header( 'Content-Length: ' . $data->size  );												
												flush();
												readfile( $path_to_file );
											}
										} else {
											$file_not_found ++;
											$error_counter ++;
										}
	 								}
								}
								break;
							case 'delete_message':
							case 'delete_messages':
								$attachment_name = $thumbnail_name = $upload_path = '';
								
								$attachmnent_data = $wpdb->get_results( 
									"SELECT `name`, `path`, `thumb_name` FROM `" . $prefix . "attachments` 
									LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id
									LEFT JOIN `" . $prefix . "thumbnails` ON " . $prefix . "attachments.id=" . $prefix . "thumbnails.attachment_id 
									WHERE " . $prefix . "attachments.message_id=" . $id );
								foreach ( $attachmnent_data as $file_name ) {
									$attachment_name	= $file_name->name;
									$thumbnail_name		= $file_name->thumb_name;
									$upload_path		= $file_name->path;
								}
								/* delete all records about choosen message from database  */
								$error = 0;
								$wpdb->query( "DELETE FROM `" . $prefix . "message` WHERE " . $prefix . "message.id=" . $id );
								$error += $wpdb->last_error ? 1 : 0;
								$wpdb->query( "DELETE FROM `" . $prefix . "attachments` WHERE `message_id`=" . $id );
								$error += $wpdb->last_error ? 1 : 0;	
								$wpdb->query( "DELETE FROM `" . $prefix . "thumbnails` WHERE `message_id`=" . $id );
								$error += $wpdb->last_error ? 1 : 0;
								$wpdb->query( "DELETE FROM `" . $prefix . "field_selection` WHERE `message_id`=" . $id );	
								$error += $wpdb->last_error ? 1 : 0;
								if ( '' !=  $attachment_name ) {
									if ( '' != $upload_path ) {
										$path_to_file = ABSPATH . $upload_path . '/attachments/' . $attachment_name;
									} else {
										$path_to_file = $save_file_path . '/' . $attachment_name;
									}

									if ( file_exists( $path_to_file ) ) {
										$error += unlink ( $path_to_file )? 0 : 1; 
									}	
								}	
								if ( '' !=  $thumbnail_name ) {
									if ( '' != $upload_path ) {
										$path_to_file = ABSPATH . $upload_path . '/attachments/' . $thumbnail_name;
									} else {
										$path_to_file = $save_file_path . '/' . $thumbnail_name;
									}

									if ( file_exists( $path_to_file ) ) {
										$error +=unlink( $path_to_file ) ? 0 : 1; 
									}
								}
								if ( 0 == $error )
									$counter++;
								else
									$error_counter++;
								break;
							/* marking messages as Spam */
							case 'spam':
								$wpdb->update( $prefix . 'message', array( 'status_id' => 2 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++; 
								else
									$counter ++;
								break;
							/* marking messages as Trash */
							case 'trash':
								$wpdb->update( $prefix . 'message', array( 'status_id' => 3 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++; 
								else
									$counter ++;
								break;
							case 'unspam':
							case 'restore':
								if ( isset( $_REQUEST['old_status'] ) && '' != $_REQUEST['old_status'] ) {
									$wpdb->update( $prefix . 'message', array( 'status_id' => $_REQUEST['old_status'] ), array( 'id' => $id ) );
								} else {
									$wpdb->update( $prefix . 'message', array( 'status_id' => 1 ), array( 'id' => $id ) );
								}
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++; 
								else
									$counter ++;
								break;							
							case 'undo':
								if ( isset( $_REQUEST['old_status'] ) && '' != $_REQUEST['old_status'] ) {
									$wpdb->update( $prefix . 'message', array( 'status_id' => $_REQUEST['old_status'] ), array( 'id' => $id ) );
								} else {
									$wpdb->update( $prefix . 'message', array( 'status_id' => 1 ), array( 'id' => $id ) );
								}
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++; 
								else
									$counter ++;
								break;
							case 'change_status':
								$new_status = $_REQUEST['status'] + 1;
								if ( 3 <  $new_status || 1 > $new_status ) 
									$new_status = 1;
								$wpdb->update( $prefix . 'message', array( 'status_id' => $new_status ), array( 'id' => $id ) );
								break;
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++;
								break;
							case 'change_read_status':
								$wpdb->update( $prefix . 'message', array( 'was_read' => 1 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error ) 
									$error_counter ++;
								break;
							default:
								$unknown_action = 1;
								break;
						}
					}
				}
				/* end of foreach */
				/* create zip-archives is possible and  one embodiment of the:
				 1) need to save several attachments
				 2) need to save several messages in "csv"-format and disabled option "Include content of attachments in to "csv"-file"
				 3) need to save several messages in "eml"-format */
				if (  ( 'download_attachments' == $action  || 
					( 'download_messages' == $action &&
					( ( 'csv' == $cntctfrmtdb_options['format_save_messages'] && 0 == $cntctfrmtdb_options['include_attachments'] ) || 
					'eml' == $cntctfrmtdb_options['format_save_messages'] ) ) ) ) {
					if ( class_exists( 'ZipArchive' ) ) {
						if ( 'download_messages' == $action && 1 < count( $message_id ) && 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
							$file_name = 'messages.csv';
							/* add file content to zip - archive */
							$zip->addFromString( $file_name, $message ); 
						}
						$zip->close();
						if ( file_exists( $zip_name ) ) {
							/* saving file to local computer */
							header( 'Content-Description: File Transfer' );
							header( 'Content-Type: application/x-zip-compressed' );
							header( 'Content-Disposition: attachment; filename=' . time() . '.zip' );
							header( 'Content-Transfer-Encoding: binary' );
							header( 'Expires: 0' );
							header( 'Cache-Control: must-revalidate' );
							header( 'Pragma: public' );
							header( 'Content-Length: ' . filesize( $zip_name ) );
							flush();
							$file_downloaded = readfile( $zip_name );
							if ( $file_downloaded )
								unlink( $zip_name );
						}
					} else {
						$can_not_create_zip = 1;
					}
				} 
				if ( 'download_messages' == $action && 1 < count( $message_id ) && 1 == $cntctfrmtdb_options['include_attachments'] ) {
					/* saving single chosen "csv"-file to local computer if content of attachment was include in csv */
					$file_name = 'messages.csv';
					if ( file_exists( $save_file_path . '/' . $file_name ) )
						$file_name = time() . '_' . $file_name;
					$fp = fopen( $save_file_path . '/' . $file_name, 'w');
					fwrite( $fp, $message );
					$file_created = fclose( $fp );
					if ( '0' != $file_created ) {
						header( 'Content-Description: File Transfer' );
						header( 'Content-Type: application/force-download' );
						header( 'Content-Disposition: attachment; filename=' . $file_name );
						header( 'Content-Transfer-Encoding: binary' );
						header( 'Expires: 0' );
						header( 'Cache-Control: must-revalidate');
						header( 'Pragma: public' );
						header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name )  );
						flush();
						$file_downloaded = readfile( $save_file_path . '/' . $file_name );
						if ( $file_downloaded )
							unlink( $save_file_path . '/' . $file_name );
					} else {
						$error_counter ++;
					}
				}
				/* saving "xml"-file to local computer */
				if ( in_array( $action, array( 'download_message', 'download_messages' ) ) && 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
					if ( 'download_message' == $action ) {
						/* name prefix */
						$random_prefix = $random_number; 
						$file_name = 'message_' . 'ID_' . $id . '_' . $random_prefix . '.xml';
					} else {
						$file_name = 'messages_' . time() . '.xml';
					}
					/* create string with file content */
					$file_xml = $xml->saveXML(); 
					if ( '' != $file_xml ) {
						if ( file_exists( $save_file_path . '/' . $file_name ) )
							$file_name = time() . '_' . $file_name;
						$fp = fopen( $save_file_path . '/' . $file_name, 'w');
						fwrite( $fp, $file_xml );
						$file_created = fclose( $fp );
						if ( '0' != $file_created ) {
							header( 'Content-Description: File Transfer' );
							header( 'Content-Type: application/force-download' );
							header( 'Content-Disposition: attachment; filename=' . $file_name );
							header( 'Content-Transfer-Encoding: binary' );
							header( 'Expires: 0' );
							header( 'Cache-Control: must-revalidate');
							header( 'Pragma: public' );
							header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name )  );
							flush();
							$file_downloaded = readfile( $save_file_path . '/' . $file_name );
							if ( $file_downloaded )
								unlink( $save_file_path . '/' . $file_name );
						} else {
							$error_counter ++;
						}
					} else {
						$can_not_create_xml = 1;
					}
						
				}
				/* display the operation results or error messages */
				switch ( $action ) {
					case 're_send_message':
					case 're_send_messages':
						if ( 0 == $error_counter ) {
							$cntctfrmtdb_done_message =  sprintf ( _n( 'One message was re-sent successfully', '%s messages were re-sent successfully.', $counter, 'contact-form-to-db-pro' ) , number_format_i18n( $counter ) );
						}  else {
							$cntctfrmtdb_error_message = __( 'There are some problems while re-sending messages.', 'contact-form-to-db-pro' );
						}
						break;
					case 'download_message':
					case 'download_messages':						
						if ( 0 != $can_not_create_xml ) {
							$cntctfrmtdb_error_message = __( 'Can not create XML-files.', 'contact-form-to-db-pro' );
						}
						if ( 0 != $can_not_create_zip ) {
							if ( '' == $cntctfrmtdb_error_message ) { 
								$cntctfrmtdb_error_message = __( 'Can not create ZIP-archive.', 'contact-form-to-db-pro' );
							} 
						}
						if ( isset( $unknown_format ) )
							$cntctfrmtdb_error_message = __( 'Unknown format.', 'contact-form-to-db-pro' );
						break;
					case 'download_attachment':
					case 'download_attachments':
						if ( 0 != $can_not_create_zip )
							$cntctfrmtdb_error_message = __( 'Can not create ZIP-archive.', 'contact-form-to-db-pro' );
						if ( 0 != $error_counter) {
							$cntctfrmtdb_error_message = __( 'There are some problems while downloading files.', 'contact-form-to-db-pro' );
							if (  '0' != $file_not_found ) {
								$cntctfrmtdb_error_message .=  sprintf( _n( 'One file was not found.', '%s files were not found.', $error_counter, 'contact-form-to-db-pro' ), number_format_i18n( $error_counter ) );
							}
						}
						break;
					case 'delete_message':
					case 'delete_messages':
						if ( 0 == $error_counter ) {
							$cntctfrmtdb_done_message =  sprintf( _n( 'One message was deleted successfully', '%s messages were deleted successfully.', $counter, 'contact-form-to-db-pro' ), number_format_i18n( $counter ) );
						} else { 
							$cntctfrmtdb_error_message = __( 'There are some problems while deleting messages.', 'contact-form-to-db-pro' );
						}
						break;
					case 'spam':
						$ids = '';
						if ( 0 == $error_counter ) {
							if ( 1 < count( $message_id ) ) {
								/* get ID`s of message to string in format "1,2,3,4,5" to add in action link */
								foreach( $message_id as $value )
									$ids .= $value . ',';
							} else {
								$ids = $message_id['0'];
							}
							$cntctfrmtdb_done_message =  sprintf( _n( 'One message was marked as Spam.', '%s messages were marked as Spam.', $counter, 'contact-form-to-db-pro' ), number_format_i18n( $counter ) );
							$cntctfrmtdb_done_message .= ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $ids, plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a>';
						} else {
							$cntctfrmtdb_error_message = __( 'Problems while marking messages as Spam.', 'contact-form-to-db-pro' );
						}
						break;
					case 'trash':
						$ids = '';
						if ( 0 == $error_counter ) {
							if ( 1 < count( $message_id ) ) {
								/* get ID`s of message to string in format "1,2,3,4,5" to add in action link */
								foreach( $message_id as $value )
									$ids .= $value . ',';
							} else {
								$ids = $message_id['0'];
							}
							$cntctfrmtdb_done_message =  sprintf( _n( 'One message was moved to Trash.', '%s messages were moved to Trash.', $counter, 'contact-form-to-db-pro' ), number_format_i18n( $counter ) ); 
							$cntctfrmtdb_done_message .= ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $ids, plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a>';
						} else {
							$cntctfrmtdb_error_message .= __( "Problems while moving messages to Trash.", "contact-form-to-db-pro" ) . ' ' . __( "Please, try it later.", "contact-form-to-db-pro" ); 
						}
						break;
					case 'unspam':
					case 'restore':
						if ( 0 == $error_counter ) {
							$cntctfrmtdb_done_message = sprintf ( _n( 'One message was restored.', '%s messages were restored.', $counter, 'contact-form-to-db-pro' ), number_format_i18n( $counter ) );
						} else {
							$cntctfrmtdb_error_message = __( 'Problems with restoring messages', 'contact-form-to-db-pro' ); 
						}
						break;					
					case 'undo':
						if ( 0 == $error_counter ) {
							$cntctfrmtdb_done_message = sprintf ( _n( 'One message was restored.', '%s messages were restored.', $counter, 'contact-form-to-db-pro' ), number_format_i18n( $counter ) );
						} else {
							$cntctfrmtdb_error_message = __( 'Problems with restoring messages', 'contact-form-to-db-pro' ); 
						}
						break;
					case 'change_status':
						if ( 0 == $error_counter ) {
							switch ( $new_status ) {
								case 1:
									$cntctfrmtdb_done_message = __( 'One message was marked as Normal.', 'contact-form-to-db-pro' );
									 break;
								case 2: 
									$cntctfrmtdb_done_message = __( 'One message was marked as Spam. ', 'contact-form-to-db-pro' ) . '<a href="?page=cntctfrmtdb_manager&action=undo&message_id[]=' .  $id . '&old_status=' . $_REQUEST['status'] . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a>';
									break;
								case 3:
									$cntctfrmtdb_done_message = __( 'One message was marked as Trash. ', 'contact-form-to-db-pro' ) . '<a href="?page=cntctfrmtdb_manager&action=undo&message_id[]=' .  $id . '&old_status=' . $_REQUEST['status'] . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a>';
									break;
								default:
									$cntctfrmtdb_error_message = __( 'Unknown result.', 'contact-form-to-db-pro' ); 
									break;
							}
						} else { 
							$cntctfrmtdb_error_message = __( 'Problems while changing status of message.', 'contact-form-to-db-pro' );
						}
						break;
					case 'change_read_status':
						break;
					default:
						if ( 1 == $unknown_action ) { 
							$cntctfrmtdb_error_message = __( 'Unknown action.', 'contact-form-to-db-pro' );
						} else {
							$cntctfrmtdb_error_message = __( 'Can not display results.', 'contact-form-to-db-pro' );
						}
						break;
				}
			} else {
				if ( ! ( in_array( $_REQUEST['action'], array( 'cntctfrmtdb_show_attachment', 'cntctfrmtdb_read_message', 'cntctfrmtdb_change_staus' )  ) || isset( $_REQUEST['s'] ) ) ) {
					$cntctfrmtdb_error_message = __( 'Can not handle request. May be you need to choose some messages to handle them.', 'contact-form-to-db-pro' );
				}
			}
		}
	}
}

/*
 * Function to get number of messages 
 */
if ( ! function_exists( 'cntctfrmtdb_number_of_messages' ) ) {
	function cntctfrmtdb_number_of_messages() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		$sql_query = "SELECT COUNT(`id`) FROM " . $prefix . "message ";
		if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
				$search = stripslashes( esc_html( trim( $_REQUEST['s'] ) ) );
				$sql_query .= "WHERE `from_user` LIKE '%" . $search . "%' OR `user_email` LIKE '%" . $search . "%' OR `subject` LIKE '%" . $search . "%' OR  `message_text` LIKE '%" . $search . "%'";
		} elseif ( isset( $_REQUEST['message_status'] ) ) {
			/* depending on request display different list of messages */
			if ( 'sent' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.sent='1' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'not_sent' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.sent='0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'read_messages' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.was_read='1' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'not_read_messages' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.was_read='0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'has_attachment' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.attachment_status<>'0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'all' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='1'";
			} elseif ( 'spam' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='2'";
			} elseif ( 'trash' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='3'";
			}
		} else {
			$sql_query .= "WHERE " . $prefix . "message.status_id='1'";
		}
		$number_of_messages = $wpdb->get_var( $sql_query );
		return $number_of_messages;
	}
}

/*
* create class Cntctfrmtdb_Manager to display list of messages 
*/
if ( ! class_exists( 'Cntctfrmtdb_Manager' ) ) {
	if ( ! class_exists( 'WP_List_Table' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	class Cntctfrmtdb_Manager extends WP_List_Table {
		var $message_status;
		var $is_cf_pro_activated;
		/*
		* Constructor of class 
		*/
		function __construct() {
			parent::__construct( array(
				'singular'  => __( 'message', 'contact-form-to-db-pro' ),
				'plural'    => __( 'messages', 'contact-form-to-db-pro' ),
				'ajax'      => true
				)
			);
			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$this->is_cf_pro_activated = is_plugin_active( 'contact-form-pro/contact_form_pro.php' );
			$this->message_status = isset( $_REQUEST['message_status'] ) ? $_REQUEST['message_status'] : 'all';
		}
		/*
		* Function to prepare data before display 
		*/
		function prepare_items() {
			global $cntctfrmtdb_options;

			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = $this->get_sortable_columns();
			$primary               = 'message';
			$this->_column_headers = array( $columns, $hidden, $sortable, $primary );

			if ( ! in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment', 'spam', 'trash' ) ) )
				$this->message_status = 'all';
			$this->items = $this->get_message_list();

			$this->set_pagination_args( array(
				'total_items' => intval( cntctfrmtdb_number_of_messages() ),
				'per_page'    => $this->get_items_per_page( 'cntctfrmtdb_letters_per_page', 30 ),
				)
			);
		}
		/*
		* Function to show message if no data found
		*/
		function no_items() {
			if ( 'sent' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that have been sent.', 'contact-form-to-db-pro' ) . ' -<i>';
			} elseif ( 'not_sent' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that have not been sent.', 'contact-form-to-db-pro' ) . '-<i>';
			} elseif ( 'read_messages' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that have been read.', 'contact-form-to-db-pro' ) . ' -<i>';
			} elseif ( 'not_read_messages' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that have not been read.', 'contact-form-to-db-pro' ) . ' -<i>';
			} elseif ( 'has_attachment' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that have attachments.', 'contact-form-to-db-pro' ) . ' -<i>';
			} elseif ( 'spam' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that were marked as Spam.', 'contact-form-to-db-pro' ) . ' -<i>';
			} elseif ( 'trash' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that were marked as Trash.', 'contact-form-to-db-pro' ) . ' -<i>';
			} else {
				echo '<i>- ' . __( 'No messages found.', 'contact-form-to-db-pro' ) . ' -<i>';
			}
		}
		/*
		* Function to add column names 
		*/
		function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'status':
				case 'from':
				case 'message':
				case 'attachment':
				case 'department':
				case 'sent':
				case 'date':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ) ;
			}
		}
		/*
		* Function to add column titles 
		*/
		function get_columns() {
			$columns = array(
				'cb'         => '<input type="checkbox" />',
				'status'     => '',
				'from'       => __( 'From', 'contact-form-to-db-pro' ),
				'message'    => __( 'Message', 'contact-form-to-db-pro' ),
				'attachment' => '<span class="hidden">' . __( 'Attachment', 'contact-form-to-db-pro' ) . '</span><div class="cntctfrmtdb-attachment-column-title"></div>',
				'sent'       => __( 'Send Counter', 'contact-form-to-db-pro' ),
				'date'       => __( 'Date', 'contact-form-to-db-pro' ),
			);
			/* insert column 'department' after column 'message' */
			if ( $this->is_cf_pro_activated )
				$columns = array_slice( $columns, 0, 4, true ) + array( 'department' => __( 'Department', 'contact-form-to-db-pro' ) ) + array_slice( $columns, 4, count( $columns ), true );
			return $columns;
		}
		/**
         * Get a list of sortable columns.  
         * @return array list of sortable columns
         */
        function get_sortable_columns() {
            $sortable_columns = array(
                'from'       => array( 'from', false ),
                'date'       => array( 'date', false )
            );
            return $sortable_columns;
        }

        /**
         * Add necessary classes for tag <table>
         */
        function get_table_classes() {
			return array( 'widefat' );
		}

		/**
		* Function to add action links before and after list of messages 
		*/
		function get_views() {
			global $wpdb;
			$status_links  = array();
			$prefix = $wpdb->prefix . 'cntctfrmtdb_';

			$status = array(
				'all'               => __( 'All', 'contact-form-to-db-pro' ),
				'sent'              => __( 'Sent', 'contact-form-to-db-pro' ),
				'not_sent'          => __( 'Not sent',  'contact-form-to-db-pro' ),
				'read_messages'     => __( 'Read', 'contact-form-to-db-pro' ),
				'not_read_messages' => __( 'Unread', 'contact-form-to-db-pro' ),
				'has_attachment'    => __( 'Has attachments', 'contact-form-to-db-pro' ),
				'spam'              => __( 'Spam', 'contact-form-to-db-pro' ),
				'trash'             => __( 'Trash', 'contact-form-to-db-pro' )
			);
			
			$filters_count = $wpdb->get_results(
				"SELECT COUNT(`id`) AS `all`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.sent=1 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `sent`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.sent=0 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `not_sent`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.was_read=1 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `was_read`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.was_read=0 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `was_not_read`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.attachment_status<>0 AND " . $prefix . "message.status_id NOT IN (2,3)) AS `has_attachment`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.status_id=2 ) AS `spam`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.status_id=3 ) AS `trash`
				FROM " . $prefix . "message WHERE " . $prefix . "message.status_id NOT IN (2,3)"
			);
			foreach ( $filters_count as $value ) {
				$all_count					= $value->all;
				$sent_count					= $value->sent;
				$not_sent_count				= $value->not_sent;
				$read_messages_count		= $value->was_read;
				$not_read_messages_count	= $value->was_not_read;
				$has_attachment_count		= $value->has_attachment;
				$spam_count					= $value->spam;
				$trash_count				= $value->trash;
			} 
			foreach ( $status as $key => $value ) {
				$class = ( $key == $this->message_status ) ? ' class="current"' : '';				
				$status_links[ $key ] = '<a href="?page=cntctfrmtdb_manager&message_status=' . $key . '" ' . $class . '">' . $value . ' <span class="count">(<span class="' . str_replace( '_', '-', $key ) . '-count">' . ${ $key . '_count'} . '</span>)</span></a>';
			}
			return $status_links;
		}

		/*
		* Function to add filters before and after list of messages 
		*/
		function extra_tablenav( $which ) {
			if ( 'top' !== $which )
				return;

			global $wpdb, $cntctfrmtdb_department;			
			if ( $this->is_cf_pro_activated ) { 
				$departments = $wpdb->get_results( "SELECT DISTINCT `field_value` FROM `" . $wpdb->prefix . "cntctfrmtdb_field_selection`, `" . $wpdb->prefix . "cntctfrm_field` WHERE `cntctfrm_field_id`=`id` AND `name`='department_selectbox'", ARRAY_A );
				if ( ! empty( $departments ) ) { ?>
					<div class="alignleft actions">
						<label class="screen-reader-text" for="filter-by-department"><?php _e( 'Filter by department', 'contact-form-to-db-pro' ); ?></label>						
						<select id="filter-by-department" name="cntctfrmtdb_department">
							<option value=""><?php _e( 'All departments', 'contact-form-to-db-pro' ); ?></option>
							<?php foreach ( $departments as $department ) { ?>
								<option value="<?php echo $department['field_value']; ?>"<?php selected( $cntctfrmtdb_department, $department['field_value'], true ); ?>><?php echo $department['field_value']; ?></option>
							<?php } ?>
						</select>
						<?php submit_button( __( 'Filter', 'contact-form-to-db-pro' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) ); ?>
					</div>
				<?php }
			}
		}

		/*
		* Function to add action links to drop down menu before and after table depending on status page
		*/
		function get_bulk_actions() {
			$actions = array();
			if ( in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment' ) ) ) {
				$actions['re_send_messages']		= __( 'Re-send messages', 'contact-form-to-db-pro' );
				$actions['download_messages']		= __( 'Download messages', 'contact-form-to-db-pro' );
				$actions['download_attachments']	= __( 'Download attachments', 'contact-form-to-db-pro' );
				$actions['spam']					= __( 'Mark as Spam', 'contact-form-to-db-pro' );
			}
			if ( 'spam' == $this->message_status )
				$actions['unspam'] = __( 'Not Spam', 'contact-form-to-db-pro' );
			if ( 'trash' == $this->message_status )
				$actions['restore'] = __( 'Restore', 'contact-form-to-db-pro' );
			if ( in_array( $this->message_status, array( 'spam', 'trash' ) ) )
				$actions['delete_messages'] = __( 'Delete Permanently', 'contact-form-to-db-pro' );
			else
				$actions['trash'] = __( 'Mark as Trash', 'contact-form-to-db-pro' );
			return $actions;
		}
		/*
		* Function to add action links to  message column depenting on status page
		*/
		function column_message( $item ) {
			$actions = array();
			$plugin_basename = plugin_basename( __FILE__ );

			if ( in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment' ) ) ) {
				$actions['re_send_message'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=re_send_message&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Re-send Message', 'contact-form-to-db-pro' ) . '</a>';
				$actions['download_message'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=download_message&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Download Message', 'contact-form-to-db-pro' ) . '</a>';
				$actions['spam'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=spam&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Spam', 'contact-form-to-db-pro' ) . '</a>';
				$actions['trash'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=trash&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Trash', 'contact-form-to-db-pro' ) . '</a>';
			}
			if ( 'spam' == $this->message_status )
				$actions['unspam'] = '<a style="color:#006505" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=unspam&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Not spam', 'contact-form-to-db-pro' ) . '</a>';
			if ( 'trash' == $this->message_status )
				$actions['untrash'] = '<a style="color:#006505" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=restore&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Restore', 'contact-form-to-db-pro' ) . '</a>';
			if ( in_array( $this->message_status, array( 'spam', 'trash' ) ) )
				$actions['delete_message'] = '<a style="color:#BC0B0B" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=delete_message&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Delete Permanently', 'contact-form-to-db-pro' ) . '</a>';
			else
				$actions['trash'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=trash&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Trash', 'contact-form-to-db-pro' ) . '</a>';
			return sprintf( '%1$s %2$s', $item['message'], $this->row_actions( $actions ) );
		}
		/*
		* Function to add column of checboxes 
		*/
		function column_cb( $item ) {
			return sprintf( '<input id="cb_%1s" type="checkbox" name="message_id[]" value="%2s" />', $item['id'], $item['id'] );
		}

		/*
		* Function to get data in message list
		*/
		function get_message_list() {
			global $wpdb, $attachment_src, $cntctfrmtdb_options, $cntctfrmtdb_department;
			$prefix = $wpdb->prefix . 'cntctfrmtdb_';
			if ( empty( $cntctfrmtdb_options ) )
				$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
			$per_page = $this->get_items_per_page( 'cntctfrmtdb_letters_per_page', 30 );
			$start_row = ( isset( $_REQUEST['paged'] ) && 1 < intval( $_REQUEST['paged'] ) ) ? $per_page * ( absint( intval( $_REQUEST['paged'] ) - 1 ) ) : 0;

			if ( $this->is_cf_pro_activated ) {
				$sql_query = "SELECT *, `field_value` AS `department` FROM `" . $prefix . "message` LEFT JOIN `" . $prefix . "field_selection` ON `" . $prefix . "message`.id=`" . $prefix . "field_selection`.message_id AND `" . $prefix . "field_selection`.`cntctfrm_field_id`=( SELECT `id` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox' ) ";
			} else {
				$sql_query = "SELECT * FROM " . $prefix . "message ";
			}

			if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
				$search = stripslashes( esc_html( trim( $_REQUEST['s'] ) ) );
				$sql_query .= "WHERE `from_user` LIKE '%" . $search . "%' OR `user_email` LIKE '%" . $search . "%' OR `subject` LIKE '%" . $search . "%' OR  `message_text` LIKE '%" . $search . "%'";
			} elseif ( isset( $_REQUEST['message_status'] ) ) { 
				/* depending on request display different list of messages */
				if ( 'sent' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.sent=1 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'not_sent' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.sent=0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'read_messages' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.was_read=1 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'not_read_messages' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.was_read=0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'has_attachment' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.attachment_status<>0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'all' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=1";
				} elseif ( 'spam' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=2";
				} elseif ( 'trash' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=3";
				}
			} else {
				$sql_query .= "WHERE " . $prefix . "message.status_id=1";
			}

			$cntctfrmtdb_department = !empty( $_REQUEST['cntctfrmtdb_department'] ) ? $_REQUEST['cntctfrmtdb_department'] : '';
			if ( ! empty( $cntctfrmtdb_department ) )
				$sql_query .= " AND `field_value`='" . $cntctfrmtdb_department . "'";

			if ( isset( $_REQUEST['orderby'] ) ) {
				switch ( $_REQUEST['orderby'] ) {
					case 'from':
						$order_by = 'from_user';
						break;
					case 'department':
						$order_by = 'department';
						break;
					case 'date':
					default:
						$order_by = 'send_date';
						break;
				}
			} else {
				$order_by = 'send_date';
			}
			$order = isset( $_REQUEST['order'] ) ?  $_REQUEST['order'] : 'DESC';
			$sql_query .= " ORDER BY " . $order_by . " " . $order . " LIMIT " . $per_page . " OFFSET " . $start_row;
			$messages = $wpdb->get_results( $sql_query );
			$i = 0;
			$list_of_messages = array();
			$plugin_basename = plugin_basename( __FILE__ );

			foreach ( $messages as $value ) { 
				/* fill "status" column  */
				$the_message_status = '<a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=change_status&status=' . $value->status_id . '&message_id[]=' . $value->id, $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) .  '">';
				if ( '1' == $value->status_id )
					$the_message_status .= '<div class="cntctfrmtdb-letter" title="'. __( 'Mark as Spam', 'contact-form-to-db-pro' ) . '">' . $value->status_id . '</div>';
				elseif ( '2' == $value->status_id )
					$the_message_status .= '<div class="cntctfrmtdb-spam" title="'. __( 'Mark as Trash', 'contact-form-to-db-pro' ) . '">' . $value->status_id . '</div>';
				elseif ( '3' == $value->status_id )
					$the_message_status .= '<div class="cntctfrmtdb-trash" title="'. __( 'in Trash', 'contact-form-to-db-pro' ) . '">' . $value->status_id . '</div>';
				else
					$the_message_status .= '<div class="cntctfrmtdb-unknown" title="'. __( 'unknown status', 'contact-form-to-db-pro' ) . '">' . $value->status_id . '</div>';
				$the_message_status .= '</a>';
				$from_data = '<a class="from-name';
				if ( '1' != $value->was_read )
					$from_data .= ' not-read-message" href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=change_read_status&message_id[]=' . $value->id, $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">';
				else
					$from_data .= '" href="javascript:void(0);">'; 
				if ( '' !=  $value->from_user )
					$from_data .= $value->from_user;
				else
					$from_data .= '<i>- ' . __( 'Unknown name', 'contact-form-to-db-pro' ) . ' -</i>';
				$from_data .= '</a>';
				/* fill "from" column  */
				$add_from_data = '';
				if ( '' !=  $value->user_email )
					$add_from_data .= '<strong>email: </strong>' . $value->user_email . '</br>';
				$additional_filelds = $wpdb->get_results( "SELECT `cntctfrm_field_id`, `field_value`, `name` FROM `" . $prefix . "field_selection` INNER JOIN `" . $wpdb->prefix . "cntctfrm_field` ON `cntctfrm_field_id`=`id` WHERE `message_id`='" . $value->id . "' AND `cntctfrm_field_id` <> ( SELECT `id` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox' )" );
				if ( '' !=  $additional_filelds ) {
					foreach ( $additional_filelds as $field ) {
						$field_name = $wpdb->get_var( "SELECT `name` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `id`='" . $field->cntctfrm_field_id . "'");
						if ( 'user_agent' != $field->name )
							$add_from_data .= '<strong>' . $field->name . ': </strong>' . $field->field_value . '</br>';
					}
				}
				$to_email = $wpdb->get_var( "SELECT `email` FROM `" . $prefix . "to_email` WHERE `id`='" . $value->to_id . "'" );
				$add_from_data .= '<strong>to: </strong>' . $to_email;
				if ( '' !=  $add_from_data ) {
					$from_data .= '<div class="from-info">' . $add_from_data . '</div>';
				}
				/* fill "message" column */
				$message_content = '<div class="message-container"><div class="message-text"><strong>' . $value->subject . '</strong> - ';
					if ( '' !=  $value->message_text ) 
						$message_content .= $value->message_text . '</div>';
					else
						$message_content .= '<i> - ' . __( 'No text in this message', 'contact-form-to-db-pro' ) . ' - </i></div>';
				if ( $cntctfrmtdb_options['show_attachments'] =='1' ) {
					$list_attachments = $wpdb->get_results( 
						"SELECT `id`, `name`, `size`, `mime_type`, `path` 
						FROM `" . $prefix . "attachments` 
						INNER JOIN `" . $prefix . "mime_types` ON " . $prefix . "attachments.mime_type_id=" . $prefix . "mime_types.mime_types_id 
						LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id
						WHERE " . $prefix . "attachments.message_id='" . $value->id . "'" );
					if ( '' !=  $list_attachments  ) {
						$message_content .= '<table class="attachments-preview">
											<tbody>';					
						foreach ( $list_attachments as $attachment ) {
							/* Show list of attachments in "Message" column get file size in human-readable form */
							$attachment_size	= '';
							$att_size			= $attachment->size;
							if ( 104857 <= $att_size ) {
								/* if file size more then 100KB */
								$att_size = round( $att_size/1048576, 2 );
								$attachment_size .= $att_size . ' ' . __( 'MB', 'contact-form-to-db-pro' );
							} elseif ( 1024 <= $att_size && 104857 >= $att_size ) {
								/* if file size more then 1KB but less then 100KB */
								$att_size = round( $att_size/1024, 2 );
								$attachment_size .= $att_size . ' ' . __( 'KB', 'contact-form-to-db-pro' );
							} else {
								/* if file size under 1KB */
								$attachment_size .= $att_size . ' ' . __( 'Bytes', 'contact-form-to-db-pro' );
							}
							$attachment_name	= substr( $attachment->name, 5 );
							$message_content .='<tr '; 
							if ( cntctfrmtdb_is_image( $attachment->mime_type ) ) {
								global $attachment_type;
								$attachment_type	= $attachment->mime_type;
								$message_content .= 'class="attachment-img"';
							}
							$message_content .= 'align="center">
								<td class="attachment-info" valign="middle">
									<input type="hidden" name="attachment_id" value="' . $attachment->id . '"/>
									<span>' . $attachment_name . '</span></br>
									<span>' . $attachment_size . '</span></br>
									<span><a class="cntctfrmtdb-download-attachment" href="' . wp_nonce_url( '?page=' . $_REQUEST['page'] . '&action=download_attachment&message_id[]=' . $value->id . '&attachment_id=' . $attachment->id, $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Download', 'contact-form-to-db-pro' ) . '</a></span></br>';
							/* display thumbnail */
							if ( cntctfrmtdb_is_image( $attachment->mime_type ) && 'image/tiff' !=$attachment->mime_type ) {
								$attachment_content = $wpdb->get_results( "SELECT `content` FROM `" . $prefix . "attachments` WHERE `id`=" . $attachment->id );
								$attachment_src			= '';
								foreach ( $attachment_content as $content ) {
									if ( '' != $content->content ) {
										$attachment_src = 'data:' . $attachment->mime_type . ';base64,' . base64_encode( stripslashes( $content->content ) );
									} else {
										if ( defined( 'UPLOADS' ) ) {
											if ( ! is_dir( ABSPATH . UPLOADS ) ) 
												wp_mkdir_p( ABSPATH . UPLOADS );
											$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
											$path_to_image	= home_url() . '/' . trailingslashit( UPLOADS ) . 'attachments';
										} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
											if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
												wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
											$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
											$path_to_image	=  home_url() . '/' . trailingslashit( BLOGUPLOADDIR ) . 'attachments';
										} else {
											$upload_path	= wp_upload_dir();
											$save_file_path = $upload_path['basedir'] . '/attachments';
											$path_to_image	= $upload_path['baseurl'] . '/attachments';
										}
									
										if ( '' != $attachment->path ) {
											$path_to_file		= ABSPATH . $attachment->path . '/attachments/' . $attachment->name;
											$path_to_image	= home_url() . '/' . $attachment->path . '/attachments';
										} else {
											$path_to_file		= $save_file_path . '/' . $attachment->name;
										}

										if ( file_exists( $path_to_file ) ) {
											$attachment_src = $path_to_image . '/' . $attachment->name;
										} else { 
											$attachment_src = plugins_url( "images/no-image.jpg", __FILE__ );
										}

									}
								}
								$message_content .= '<span><a ';
								if ( '1' == $cntctfrmtdb_options['use_fancybox'] )
									$message_content .= 'class="attachment-fancybox" rel="link-' . $value->id . '"';
								$message_content .= ' href="'. $attachment_src .'">' . __( 'View', 'contact-form-to-db-pro' ) . '</a></span>';
							} 
							$message_content .= '</td>';						
						}
						$message_content .= '</tr>
								</tbody>
							</table>';
					}
				}
				$message_content .= '</div>';
				/* display icons in "attacment" column */
				$attachments_icon = '';
				switch ( $value->attachment_status ) {
					case 0:
						break;
					case 1:
						$attachments_icon = '<div class="cntctfrmtdb-has-attachment" title="' . $attachment_name . '"></div>';
						break;
					case 2:
						$attachments_icon = '<div class="cntctfrmtdb-warning-attachment" title="' . __( 'Problems while uploading file ', 'contact-form-to-db-pro' ) .  $attachment_name . ' ( ' .$attachment_size . ' )' . __( 'See User guide', 'contact-form-to-db-pro' ) . '"></div>';
						break;
					case 3:
						$attachments_icon = '<div class="cntctfrmtdb-not-saved-attachment" title="' . __( 'Attachment has not been saved because of the plugin settings', 'contact-form-to-db-pro' ) . '"></div>';
						break;
					case 4:
						if ( 'database' == $cntctfrmtdb_options['save_attachments_to'] ) {
							$attachments_icon = '<div class="cntctfrmtdb-warning-attachment" title="' . __( 'File was saved in the Attachments folder on the server because the file uploaded by the user exceeds the maximum allowed size of the file that can be saved in the database', 'contact-form-to-db-pro' ) .'"></div>';
						} else {
							$attachments_icon = '<div class="cntctfrmtdb-has-attachment" title="' . $attachment_name . '"></div>';
						}
						break;
					default:
						break;
				}
				/* display counter */
				$counter_sent_status = '<span class="counter" title="' . __( 'The number of dispatches', 'contact-form-to-db-pro' ) . '">' . $value->dispatch_counter . '</span>';
				if ( '0' == $value->sent )
					$counter_sent_status .= '<span class="warning" title="' . __( 'This message was not sent', 'contact-form-to-db-pro' ) . '"></span>';
				/* display date */
				$send_date = date( 'd M Y H:i', strtotime( $value->send_date ) );
				/* forming massiv of messages */
				$list_of_messages[ $i ] = array(
					'id'         => $value->id,
					'status'     => $the_message_status,
					'from'       => $from_data,
					'message'    => $message_content,
					'attachment' => $attachments_icon,
					'sent'       => $counter_sent_status,
					'date'       => $send_date
				);
				if ( $this->is_cf_pro_activated )
					$list_of_messages[ $i ]['department'] = $value->department;
				$i++;
			}
			return $list_of_messages;
		}

	}
}
/* End of class */

/*
* Function to save pagination options to data base 
* and create new instance of the class cntctfrmtdb_manager 
*/
if ( ! function_exists( 'cntctfrmtdb_add_options_manager' ) ) {
	function cntctfrmtdb_add_options_manager() {
		global $cntctfrmtdb_manager;
		cntctfrmtdb_add_tabs();
		$args = array(
			'label'   => __( 'Letters per page', 'contact-form-to-db-pro' ),
			'default' => 30,
			'option'  => 'cntctfrmtdb_letters_per_page'
		);
		add_screen_option( 'per_page', $args );
		if ( empty( $cntctfrmtdb_manager ) )
			$cntctfrmtdb_manager = new Cntctfrmtdb_Manager();
	}
}

if ( ! function_exists( 'cntctfrmtdb_set_screen_option' ) ) {
	function cntctfrmtdb_set_screen_option( $status, $option, $value ) {
		if ( 'cntctfrmtdb_letters_per_page' == $option ) 
			return $value;
	}
}

/*
* Function to display pugin page
*/
if ( ! function_exists( 'cntctfrmtdb_manager_page' ) ) {
	function cntctfrmtdb_manager_page() {
 		global $cntctfrmtdb_manager, $wpdb, $cntctfrmtdb_done_message, $cntctfrmtdb_error_message, $cntctfrmtdb_manager;

 		$cntctfrmtdb_manager->prepare_items(); ?>
		<div class="cntctfrmtdb-help-pages">
			<a href="admin.php?page=cntctfrmtdbpr_settings&action=user_guide"><span class="user-guide-icon"></span><?php _e( 'User Guide', 'contact-form-to-db-pro' ); ?></a>
			<a href="admin.php?page=cntctfrmtdbpr_settings&action=faq"><span class="faq-icon"></span><?php _e( 'FAQ', 'contact-form-to-db-pro' ); ?></a></li>
		</div>
		<div class="wrap cntctfrmtdb">			
			<h1>
				<?php _e( 'Contact Form to DB Pro', 'contact-form-to-db-pro' ); ?>
			</h1>
			<noscript>
				<div class="error below-h2">
					<p><strong><?php _e( 'WARNING:', 'contact-form-to-db-pro' ); ?></strong> <?php _e( 'For fully-functional work of plugin, please, enable javascript.', 'contact-form-to-db-pro' ); ?></p>
				</div>
			</noscript>
			<div class="updated below-h2" <?php if ( '' == $cntctfrmtdb_done_message ) echo 'style="display: none;"'?>><p><?php echo $cntctfrmtdb_done_message ?></p></div>
			<div class="error below-h2" <?php if ( '' == $cntctfrmtdb_error_message ) echo 'style="display: none;"'?>><p><strong><?php _e( 'WARNING:', 'contact-form-to-db-pro' ); ?></strong> <?php echo $cntctfrmtdb_error_message . ' ' . __( 'Please, try it later.', 'contact-form-to-db-pro' ); ?></p></div>
			<?php if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] )
				printf( '<span class="subtitle">' . sprintf( __( 'Search results for &#8220;%s&#8221;', 'contact-form-to-db-pro' ), wp_html_excerpt( esc_html( stripslashes( $_REQUEST['s'] ) ), 50 ) ) . '</span>' );
			$cntctfrmtdb_manager->views(); ?>
			<form id="posts-filter" method="get">
				<input type="hidden" name="page" value="cntctfrmtdb_manager" />
				<input type="hidden" name="message_status" class="message_status_page" value="<?php echo !empty($_REQUEST['message_status']) ? esc_attr($_REQUEST['message_status']) : 'all'; ?>" />
				<?php $cntctfrmtdb_manager->search_box( __( 'Search mails', 'contact-form-to-db-pro' ), 'search_id' ); ?>
				<?php $cntctfrmtdb_manager->display(); 
				wp_nonce_field( plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ); ?>
			</form>
		</div>
	<?php }
}

/*
* 			WP-CRON
*
* Function to set time interval for deleting messages 
*/
if ( ! function_exists( 'cntctfrmtdb_add_intervals' ) ) {
	function cntctfrmtdb_add_intervals( $schedules ) {
		global $cntctfrmtdb_options;

		if ( empty( $cntctfrmtdb_options ) ) {
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
			if ( empty( $cntctfrmtdb_options ) ) {
				cntctfrmtdb_settings();
				$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
			} 
		}

		if ( '1' == $cntctfrmtdb_options['delete_messages'] ) {
			$schedules['every_three_days'] = array(
				'interval' => 259200,
				'display'  => __( 'every 3 days', 'contact-form-to-db-pro' )
			);
			$schedules['weekly'] = array(
				'interval' => 604800,
				'display'  => __( 'weekly', 'contact-form-to-db-pro' )
			);
			$schedules['every_two_weeks'] = array(
				'interval' => 1317600,
				'display'  => __( 'every 2 weeks', 'contact-form-to-db-pro' )
			);
			$schedules['monthly'] = array(
				'interval' => 2635200,
				'display'  => __( 'monthly', 'contact-form-to-db-pro' )
			);
			$schedules['every_six_months'] = array(
				'interval' => 15811200,
				'display'  => __( 'every 6 months', 'contact-form-to-db-pro' )
			);
			$schedules['yearly'] = array(
				'interval' => 31557600,
				'display'  => __( 'yearly', 'contact-form-to-db-pro' )
			);
		}
		return $schedules;
	}
}


/*
* Function to change cron recurrence or disable him
*/
if ( ! function_exists( 'cntctfrmtdb_change_cron' ) ) {
	function cntctfrmtdb_change_cron() {
		global $cntctfrmtdb_options;

		if ( empty( $cntctfrmtdb_options ) ) {
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
			if ( empty( $cntctfrmtdb_options ) ) {
				cntctfrmtdb_settings();
				$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
			}
		}

		wp_clear_scheduled_hook( 'cntctfrmtdb_cron' );
			
		if ( '1' == $cntctfrmtdb_options['delete_messages'] && ! wp_next_scheduled( 'cntctfrmtdb_cron' ) )
			wp_schedule_event( current_time( 'timestamp' ), $cntctfrmtdb_options['delete_messages_after'], 'cntctfrmtdb_cron' );
	}
}

/*
* Function for deletion of messages 
*/
if ( ! function_exists( 'cntctfrmtdb_delete_messages' ) ) {
	function cntctfrmtdb_delete_messages() {
		global $wpdb, $cntctfrmtdb_options;

		if ( empty( $cntctfrmtdb_options ) )
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );		

		if ( '1' == $cntctfrmtdb_options['delete_messages'] ) {
			$prefix = $wpdb->prefix . 'cntctfrmtdb_';

			if ( defined( 'UPLOADS' ) ) {
				if ( ! is_dir( ABSPATH . UPLOADS ) ) 
					wp_mkdir_p( ABSPATH . UPLOADS );
				$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
			} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
				if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
					wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
				$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
			} else {
				$upload_path = wp_upload_dir();
				$save_file_path = $upload_path['basedir'] . '/attachments';
			}

			switch ( $cntctfrmtdb_options['delete_messages_after'] ) {
				case 'daily':
					$save_time_limit = 86400;
					break;
				case 'every_three_days':
					$save_time_limit = 259200;
					break;
				case 'weekly':
					$save_time_limit = 604800;
					break;
				case 'every_two_weeks':
					$save_time_limit = 1317600;
					break;
				case 'monthly':
					$save_time_limit = 2635200;
					break;
				case 'every_six_months':
					$save_time_limit = 15811200;
					break;
				case 'yearly':
					$save_time_limit = 31557600;
					break;
			}

			$shedule_delete_list = $wpdb->get_results( 
				"SELECT `id`
				FROM `" . $prefix . "message`
				WHERE `send_date` < '" . date( "Y-m-d H:i:s", current_time( 'timestamp' ) - $save_time_limit ) . "'"
			);

			if ( ! empty( $shedule_delete_list ) ) {
				foreach ( $shedule_delete_list as $message ) {
					$attachmnent_data = $wpdb->get_results( 
						"SELECT `name`, `thumb_name`, `path` 
						FROM `" . $prefix . "attachments` 
						LEFT JOIN `" . $prefix . "thumbnails` ON " . $prefix . "attachments.id=" . $prefix . "thumbnails.attachment_id 
						LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id
						WHERE " . $prefix . "attachments.message_id=" . $message->id 
					);
					foreach ( $attachmnent_data as $file_name ) {
						if ( ! empty( $file_name->name ) ) {
							$path_to_file = ( '' !=  $file_name->path ) ? ABSPATH .  $file_name->path . '/attachments/' : $save_file_path;

							if ( is_dir( $path_to_file ) && file_exists( $path_to_file  . $file_name->name ) )
								unlink( $path_to_file . $file_name->name ); 
						}
						if ( ! empty( $file_name->thumb_name ) ) {
							$path_to_file = ( '' !=  $file_name->path ) ? ABSPATH .  $file_name->path . '/attachments/' : $save_file_path;
							if ( is_dir( $path_to_file ) && file_exists( $path_to_file ) )
								unlink( $path_to_file . $file_name->thumb_name ); 
						}
					}
					/* deleting all records about choosen message */
					$wpdb->query( "DELETE FROM `" . $prefix . "message` WHERE id=" . $message->id );
					$wpdb->query( "DELETE FROM `" . $prefix . "attachments` WHERE `message_id`=" . $message->id );
					$wpdb->query( "DELETE FROM `" . $prefix . "thumbnails` WHERE `message_id`=" . $message->id );
					$wpdb->query( "DELETE FROM `" . $prefix . "field_selection` WHERE `message_id`=" . $message->id );					
				}
			}
		}
	}
}

/*
*
*                         AJAX functions
*
* Function to change read/not-read message status 
*/
if ( ! function_exists( 'cntctfrmtdb_read_message' ) ) {
	function cntctfrmtdb_read_message() {
		check_ajax_referer( 'cntctfrmtdb_ajax_nonce_value', 'cntctfrmtdb_nonce' );

		global $wpdb;
		$wpdb->update( $wpdb->prefix . 'cntctfrmtdb_message', array( 'was_read' => $_POST['cntctfrmtdb_ajax_read_status'] ), array( 'id' => $_POST['cntctfrmtdb_ajax_message_id'] ) );
		die();
	}
}

/*
* Function to show attachment of message 
*/
if ( ! function_exists( 'cntctfrmtdb_show_attachment' ) ) {
	function cntctfrmtdb_show_attachment() {
		if ( isset( $_POST['action'] ) && 'cntctfrmtdb_show_attachment' == $_POST['action'] ) {
			global $wpdb, $cntctfrmtdb_options;
			if ( empty( $cntctfrmtdb_options ) )
				$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );

			$prefix = $wpdb->prefix . 'cntctfrmtdb_';
			$attachment_info = '';
			$list_attachments = $wpdb->get_results( 
				"SELECT `id`,`mime_type`,`message_id` 
				FROM `" . $prefix . "attachments` 
				LEFT JOIN `" . $prefix . "mime_types` ON " . $prefix . "attachments.mime_type_id=" . $prefix . "mime_types.mime_types_id 
				WHERE " . $prefix . "attachments.message_id='" . $_POST['cntctfrmtdb_ajax_message_id'] ."'" 
			);
			if ( '' !=  $list_attachments  ) {
				foreach ( $list_attachments as $attachment ) {
					/* add thumbnail for image  */
					if ( cntctfrmtdb_is_image( $attachment->mime_type ) ) {
						$save_file_path	= $attachment_src = $thumbnail_src = $thumbnail_content	= $thumbnail_name = $attachment_exists = '';

						$thumbnail_data	= $wpdb->get_results( 
							"SELECT `name`, `content`, `thumb_id`, `thumb_content`,`thumb_name`, `mime_type`, `path` 
							FROM `" . $prefix . "attachments` 
							LEFT JOIN `" . $prefix . "thumbnails` ON " . $prefix . "attachments.id = " . $prefix . "thumbnails.attachment_id 
							LEFT JOIN `" . $prefix . "mime_types` ON " . $prefix . "thumbnails.thumb_mime_type_id=" . $prefix . "mime_types.mime_types_id 
							LEFT JOIN `" . $prefix . "upload_path` ON ". $prefix . "attachments.att_upload_path_id=" . $prefix . "upload_path.path_id 
							WHERE `id`=" . $attachment->id
						);
						foreach ( $thumbnail_data as $thumb_data ) {
							$thumbnail_content	= $thumb_data->thumb_content;
							$thumbnail_name			= $thumb_data->thumb_name;
							$thumbnail_type			= $thumb_data->mime_type;
							$attachment_name		= $thumb_data->name;
							$attachment_content = $thumb_data->content;
							$upload_path				= $thumb_data->path;
							if ( defined( 'UPLOADS' ) ) {
								if ( ! is_dir( ABSPATH . UPLOADS ) ) 
									wp_mkdir_p( ABSPATH . UPLOADS );
								$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
								$path_to_image	= home_url() . '/' . trailingslashit( UPLOADS ) . 'attachments';
							} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
								if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
									wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
								$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
								$path_to_image	= home_url() . '/' . trailingslashit( BLOGUPLOADDIR ) . 'attachments';
							} else {
								$uploads_path		= wp_upload_dir();
								$save_file_path = $uploads_path['basedir'] . '/attachments';
								$path_to_image	= $uploads_path['baseurl'] . '/attachments';
							}
							if ( '' !=  $attachment_content ) {
								/* if image was save in database */
								$attachment_content = stripslashes( $attachment_content );
								$attachment_src			= 'data:' . $attachment->mime_type .';base64,' . base64_encode( $attachment_content );
								$attachment_exists	= 1 ;
							} else {
								if ( '' != $upload_path ) {
									$path_to_file		= ABSPATH . $upload_path . '/attachments/' . $attachment_name;
									$path_to_image	 = home_url() . '/' . $upload_path . '/attachments';
								} else {
									$path_to_file = $save_file_path . '/' .$attachment_name;
								}

								if ( file_exists( $path_to_file ) )
									$attachment_src = $path_to_image . '/' . $attachment_name;
							}
								
							if ( '' !=  $thumbnail_content ) {
								/* if thumbnail was save in database */
								$thumbnail_content = stripslashes( $thumbnail_content );
								$thumbnail_src = 'data:' . $thumb_data->mime_type .';base64,' . base64_encode( $thumbnail_content );
							} else {
								if ( '' !=  $thumbnail_name ) { 
									/* if we get thumbnai name from database */
									if ( '' != $upload_path ) {
										$path_to_file		= ABSPATH . $upload_path . '/attachments/' . $thumbnail_name;
										$path_to_image	= home_url() . '/' . $upload_path . '/attachments';
									} else {
										$path_to_file = $save_file_path . '/' .$thumbnail_name;
									}

									if ( file_exists( $path_to_file ) ) {
										$thumbnail_src = $path_to_image . '/' . $thumbnail_name;
									} else {
										if ( '' !=  $attachment_exists ) {
											$thumbnail_src = $attachment_src;
										} else {
											$thumbnail_src = plugins_url( "images/no-image.jpg", __FILE__ );
										}
									}
								} else {
									if ( file_exists( $save_file_path . '/' .  $attachment_name ) ) {
										$thumbnail_src = $path_to_image . '/' . $attachment_name;
									} else {
										if( 'image/tiff' == $attachment->mime_type )
											$thumbnail_src = plugins_url( "images/no-image.jpg", __FILE__ );
										else
											$thumbnail_src = $attachment_src;
									}
								}
							}
							$attachment_info .= '<td valign="middle" class="cntctfrmtdb-thumbnail">';
							if ( '' != $attachment_src && 'image/tiff' != $attachment->mime_type ) {
								$attachment_info .= '<a';
								if ( '1' == $cntctfrmtdb_options['use_fancybox'] ) {
									$attachment_info .= ' class="attachment-fancybox" rel="' . $attachment->message_id . '"'; 
								}
								$attachment_info .= ' href="' . $attachment_src . '">';
								$attachment_info .= '<img src="' .  $thumbnail_src . '" title="' . substr( $attachment_name, 5 ) . '" alt="' . __( 'Can not display thumbnail','contact-form-to-db-pro' ) . '" />';
								$attachment_info .= '</a>';
							}
							$attachment_info .= '</td>';
						}
					}
				}
			}
			$result = $attachment_info;
			echo $result;
			die();
		}
	}
}

/*
* Function to change message status 
*/
if ( ! function_exists( 'cntctfrmtdb_change_status' ) ) {
	function cntctfrmtdb_change_status() {
		global $wpdb;
		/* $result = ''; */
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		check_ajax_referer( 'cntctfrmtdb_ajax_nonce_value', 'cntctfrmtdb_nonce' );
		$wpdb->update( $prefix . 'message', array( 'status_id' => $_POST['cntctfrmtdb_ajax_message_status'] ), array( 'id' => $_POST['cntctfrmtdb_ajax_message_id'] ) );
		if ( ! $wpdb->last_error ) {
			switch ( $_POST['cntctfrmtdb_ajax_message_status'] ) {
				case 1:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Normal.', 'contact-form-to-db-pro' ) . '</a></p></div>';
					break;
				case 2:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Spam.', 'contact-form-to-db-pro' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $_POST['cntctfrmtdb_ajax_message_id'] . '&old_status=' . $_POST['cntctfrmtdb_ajax_old_status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a></p></div>';
					break;
				case 3:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Trash.', 'contact-form-to-db-pro' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $_POST['cntctfrmtdb_ajax_message_id'] . '&old_status=' . $_POST['cntctfrmtdb_ajax_old_status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db-pro' ) . '</a></p></div>';
					break;
				default:
					$result = '<div class="error below-h2"><p><strong>' . __( 'WARNING:', 'contact-form-to-db-pro' ) . '</strong> ' . __( 'Unknown result.', 'contact-form-to-db-pro' ) . '</p></div>';
					break;
			}
		} else {
			$result = '<div class="error below-h2"><p><strong>' . __( 'WARNING:', 'contact-form-to-db-pro' ) . '</strong> ' . __( 'Problems while changing status of message. Please, try it later.', 'contact-form-to-db-pro' ) . '</p></div>';
		}
		echo $result;
		die();
	}
}

/* add help tab */
if ( ! function_exists( 'cntctfrmtdb_add_tabs' ) ) {
	function cntctfrmtdb_add_tabs() {
		global $cntctfrmtdb_pages;
		$screen = get_current_screen();
		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) {
			$args = array(
				'id' 			=> 'cntctfrmtdb',
				'section' 		=> '200538679'
			);
			bws_help_tab( $screen, $args );
		}
	}
}

/* 
* function for adding all functionality for updating and deactivatind free version
*/
if ( ! function_exists( 'cntctfrmtdb_update_activate' ) ) {
	function cntctfrmtdb_update_activate(){ 
		global $bstwbsftwppdtplgns_options;
		
		$pro = 'contact-form-to-db-pro/contact_form_to_db_pro.php';
		$free = 'contact-form-to-db/contact_form_to_db.php';

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

		/* add license_key.txt after update */
		if ( empty( $bstwbsftwppdtplgns_options ) )
			$bstwbsftwppdtplgns_options = ( is_multisite() ) ? get_site_option( 'bstwbsftwppdtplgns_options' ) : get_option( 'bstwbsftwppdtplgns_options' );

		/* api for update bws-plugins */
		if ( ! function_exists ( 'bestwebsoft_wp_update_plugins' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );

		/* add license_key.txt after update */
		if ( $bstwbsftwppdtplgns_options && !file_exists( dirname( __FILE__ ) . '/license_key.txt' ) ) {
			if ( isset( $bstwbsftwppdtplgns_options['contact-form-to-db-pro/contact_form_to_db_pro.php'] ) ) {
				$bws_license_key = $bstwbsftwppdtplgns_options['contact-form-to-db-pro/contact_form_to_db_pro.php'];
				$file = @fopen( dirname( __FILE__ ) . "/license_key.txt" , "w+" );
				if ( $file ) {
					@fwrite( $file, $bws_license_key );
					@fclose( $file );
				}
			}
		}	
	}
}

/*
* cron task for deleting plugin if it's illegal use
*/
if ( ! function_exists( 'cntctfrmtdb_license_cron_task' ) ) {
	function cntctfrmtdb_license_cron_task() { 
		if ( ! function_exists ( 'bestwebsoft_license_cron_task' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );

		bestwebsoft_license_cron_task( 'contact-form-to-db-pro/contact_form_to_db_pro.php', 'contact-form-to-db/contact_form_to_db.php' );
	}
}

if ( ! function_exists ( 'cntctfrmtdb_plugin_update_row' ) ) {
	function cntctfrmtdb_plugin_update_row( $file, $plugin_data ) {
		bws_plugin_update_row( 'contact-form-to-db-pro/contact_form_to_db_pro.php' );
	}
}

/*
* Add notises on plugins page if Contact Form plugin is not installed or not active
*/
if ( ! function_exists( 'cntctfrmtdb_show_notices' ) ) {
	function cntctfrmtdb_show_notices() { 
		global $hook_suffix, $bstwbsftwppdtplgns_cookie_add, $cntctfrmtdb_plugin_info, $cntctfrmtdb_pages;

		/* Сhecking for the existence of Contact Form Plugin or Contact Form Pro Plugin */		
		if ( $hook_suffix == 'plugins.php' || ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) ) {
			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );		
			$all_plugins = get_plugins();
			
			if ( ! ( array_key_exists( 'contact-form-plugin/contact_form.php', $all_plugins ) || array_key_exists( 'contact-form-pro/contact_form_pro.php', $all_plugins ) ) ) {
				$contact_form_notice = __( 'Contact Form Plugin is not found.</br>You need install and activate this plugin for correct  work with Contact Form to DB Pro.</br>You can download Contact Form Plugin from ', 'contact-form-to-db-pro' ) . '<a href="' . esc_url( 'http://bestwebsoft.com/products/contact-form/' ) . '" title="' . __( 'Developers website', 'contact-form-to-db-pro' ). '"target="_blank">' . __( 'website of plugin Authors ', 'contact-form-to-db-pro' ) . '</a>' . __( 'or ', 'contact-form-to-db-pro' ) . '<a href="' . esc_url( 'http://wordpress.org/plugins/contact-form-plugin/' ) .'" title="WordPress" target="_blank">'. __( 'WordPress.', 'contact-form-to-db-pro' ) . '</a>';
			} else {
				$contact_form_notice = '';
				if ( ! ( is_plugin_active( 'contact-form-plugin/contact_form.php' ) || is_plugin_active( 'contact-form-pro/contact_form_pro.php' ) ) ) {
					$contact_form_notice .= __( 'Contact Form Plugin is not active.</br>You need activate this plugin for correct work with Contact Form to DB Pro. ', 'contact-form-to-db-pro' );
					if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'cntctfrmtdb_manager', 'cntctfrmtdbpr_settings' ) ) )
						$contact_form_notice .= '<br/><a href="plugins.php">' . __( 'Activate plugin', 'contact-form-to-db-pro' ) . '</a>';
				}
				/* old version */
				if ( ( is_plugin_active( 'contact-form-plugin/contact_form.php') && isset( $all_plugins['contact-form-plugin/contact_form.php']['Version'] ) && $all_plugins['contact-form-plugin/contact_form.php']['Version'] < '3.60' )  || 
					( is_plugin_active( 'contact-form-pro/contact_form_pro.php' ) && isset( $all_plugins['contact-form-pro/contact_form_pro.php']['Version'] ) && $all_plugins['contact-form-pro/contact_form_pro.php']['Version'] < '1.12' ) ) {
					$contact_form_notice .= __( 'Contact Form Plugin has old version.</br>You need update this plugin for correct work with Contact Form to DB plugin.', 'contact-form-to-db-pro' );
					if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'cntctfrmtdb_manager', 'cntctfrmtdbpr_settings' ) ) )
						$contact_form_notice .= '<br/><a href="plugins.php">' . __( 'Update plugin', 'contact-form-to-db-pro' ) . '</a>';
				}
			}

			if ( ! empty( $contact_form_notice ) ) { ?>
				<div class="error below-h2">
					<p><strong><?php _e( 'WARNING:', 'contact-form-to-db-pro'); ?></strong> <?php echo $contact_form_notice; ?></p>
				</div>
			<?php }
		}

		/* check plugin settings and add notice */
		if ( isset( $_REQUEST['page'] ) && 'cntctfrmtdb_manager' == $_REQUEST['page'] ) {
			global $cntctfrmtdb_options;
			if ( ! isset( $cntctfrmtdb_options['save_messages_to_db'] ) )
				$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );

			if ( isset( $cntctfrmtdb_options['save_messages_to_db'] ) && 0 == $cntctfrmtdb_options['save_messages_to_db'] ) {
				if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
					echo '<script type="text/javascript" src="' . plugins_url( '/bws_menu/js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>';
					$bstwbsftwppdtplgns_cookie_add = true;
				} ?>
				<script type="text/javascript">		
					(function($) {
						$(document).ready( function() {		
							var hide_message = $.cookie( "cntctfrmtdb_save_messages_to_db" );
							if ( hide_message == "true" ) {
								$( ".cntctfrmtdb_save_messages_to_db" ).css( "display", "none" );
							} else {
								$( ".cntctfrmtdb_save_messages_to_db" ).css( "display", "block" );
							}
							$( ".cntctfrmtdb_close_icon" ).click( function() {
								$( ".cntctfrmtdb_save_messages_to_db" ).css( "display", "none" );
								$.cookie( "cntctfrmtdb_save_messages_to_db", "true", { expires: 7 } );
							});	
						});
					})(jQuery);				
				</script>
				<div class="updated fade cntctfrmtdb_save_messages_to_db" style="display: none;">		       							                      
					<img style="float: right;cursor: pointer;" class="cntctfrmtdb_close_icon" title="" src="<?php echo plugins_url( '/bws_menu/images/close_banner.png', __FILE__ ); ?>" alt=""/>
					<div style="float: left;margin: 5px;"><strong><?php _e( 'Notice:', 'contact-form-to-db-pro'); ?></strong> <?php _e( 'Option "Save messages to database" was disabled on the plugin settings page.', 'contact-form-to-db-pro'); ?> <a href="admin.php?page=cntctfrmtdbpr_settings"><?php _e( 'Enable it for saving messages from Contact Form', 'contact-form-to-db-pro'); ?></a></div>
					<div style="clear:both;float: none;margin: 0;"></div>
				</div>
			<?php }
		}
		if ( $hook_suffix == 'plugins.php' ) { 
			bws_plugin_banner_timeout( 'contact-form-to-db-pro/contact_form_to_db_pro.php', 'cntctfrmtdb', $cntctfrmtdb_plugin_info['Name'], '//ps.w.org/contact-form-to-db/assets/icon-128x128.png' );
			bws_plugin_banner_to_settings( $cntctfrmtdb_plugin_info, 'cntctfrmtdb_options', 'contact-form-to-db', 'admin.php?page=cntctfrmtdbpr_settings' );
		}

		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) {
			bws_plugin_suggest_feature_banner( $cntctfrmtdb_plugin_info, 'cntctfrmtdb_options', 'contact-form-to-db' );
		}
	}
}

if ( ! function_exists( 'cntctfrmtdb_inject_info' ) ) {
	function cntctfrmtdb_inject_info( $result, $action = null, $args = null ) {
		if ( ! function_exists( 'bestwebsoft_inject_info' ) )
			require_once( dirname( __FILE__ ) . '/bws_update.php' );

		return bestwebsoft_inject_info( $result, $action, $args, 'contact-form-to-db-pro' );
	}
}

/*
* Function for delete options and tables 
*/
if ( ! function_exists ( 'cntctfrmtdb_uninstall_hook' ) ) {
	function cntctfrmtdb_uninstall_hook() {
		global $wpdb;
		$all_plugins = get_plugins();
		
		if ( is_multisite() ) {
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			$old_blog = $wpdb->blogid;
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				$prefix = 1 == $blog_id ? $wpdb->base_prefix . 'cntctfrmtdb_' : $wpdb->base_prefix . $blog_id . '_cntctfrmtdb_';
				if ( ! array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) ) {
					$sql = "DROP TABLE `" . $prefix . "message_status`, `" . $prefix . "blogname`, `" . $prefix . "to_email`, `" . $prefix . "hosted_site`, `" . $prefix . "refer`, `" . $prefix . "mime_types`, `" . $prefix . "attachments`, `" . $prefix . "field_selection`, `" . $prefix . "message`, `" . $prefix . "thumbnails`, `" . $prefix . "upload_path`;";
					delete_option( "cntctfrmtdb_options" );
				} else {
					$sql = "DROP TABLE `" . $prefix . "mime_types`, `" . $prefix . "attachments`, `" . $prefix . "message`, `" . $prefix . "thumbnails`, `" . $prefix . "upload_path`;";
				}
				$wpdb->query( $sql );				
				wp_clear_scheduled_hook( 'cntctfrmtdb_cron' );
			}
			switch_to_blog( $old_blog );
			wp_clear_scheduled_hook( 'cntctfrmtdb_cron' );			
			delete_site_option( "cntctfrmtdb_options" );
		} else {
			$prefix = $wpdb->prefix . 'cntctfrmtdb_';
			if ( ! array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) ) {
				$sql = "DROP TABLE `" . $prefix . "message_status`, `" . $prefix . "blogname`, `" . $prefix . "to_email`, `" . $prefix . "hosted_site`, `" . $prefix . "refer`, `" . $prefix . "mime_types`, `" . $prefix . "attachments`, `" . $prefix . "field_selection`, `" . $prefix . "message`, `" . $prefix . "thumbnails`, `" . $prefix . "upload_path`;";
				delete_option( "cntctfrmtdb_options" );
			} else {
				$sql = "DROP TABLE `" . $prefix . "mime_types`, `" . $prefix . "attachments`, `" . $prefix . "message`, `" . $prefix . "thumbnails`, `" . $prefix . "upload_path`;";
			}
			$wpdb->query( $sql );
			wp_clear_scheduled_hook( 'cntctfrmtdb_cron' );
		}

		/* delete images */
		if ( ! array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) ) {
			if ( is_multisite() ) {
				switch_to_blog( 1 );
				$upload_dir = wp_upload_dir();
				restore_current_blog();
			} else {
				$upload_dir = wp_upload_dir();
			}
			$images_dir = $upload_dir['basedir'] . '/attachments';
			array_map( 'unlink', glob( $images_dir . "/" . "*.*" ) );
			rmdir( $images_dir );
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

/* 
* Add all hooks
*/
register_activation_hook( __FILE__, 'cntctfrmtdb_plugin_activate' );
add_action( 'plugins_loaded', 'cntctfrmtdb_plugins_loaded' );
/* add menu items in to dashboard menu */
add_action( 'admin_menu', 'cntctfrmtdb_admin_menu' );
if ( function_exists( 'is_multisite' ) && is_multisite() )
	add_action( 'network_admin_menu', 'cntctfrmtdb_admin_menu' );
/* init hooks */
add_action( 'init', 'cntctfrmtdb_pro_init' );
add_action( 'admin_init', 'cntctfrmtdb_admin_init' );
/* add pligin scripts and stylesheets */
add_action( 'admin_enqueue_scripts', 'cntctfrmtdb_admin_head' );
/* add action link of plugin on "Plugins" page */
add_filter( 'plugin_action_links', 'cntctfrmtdb_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cntctfrmtdb_register_plugin_links', 10, 2 );
/* hooks for get mail data from Contact form plugin */
add_action( 'cntctfrm_get_mail_data', 'cntctfrmtdb_get_mail_data', 10, 11 );
add_action( 'cntctfrm_get_attachment_data', 'cntctfrmtdb_get_attachment_data' );
add_action( 'cntctfrm_check_dispatch', 'cntctfrmtdb_check_dispatch', 10, 1 );
add_filter( 'set-screen-option', 'cntctfrmtdb_set_screen_option', 10, 3 );

/* check for installed and activated Contact Form plugin */
add_action( 'admin_notices', 'cntctfrmtdb_show_notices' );

add_filter( 'cron_schedules', 'cntctfrmtdb_add_intervals' );
add_action( 'cntctfrmtdb_cron', 'cntctfrmtdb_delete_messages' );
/* hooks for ajax */
add_action( 'wp_ajax_cntctfrmtdb_read_message', 'cntctfrmtdb_read_message' );
add_action( 'wp_ajax_cntctfrmtdb_show_attachment', 'cntctfrmtdb_show_attachment' );
add_action( 'wp_ajax_cntctfrmtdb_change_staus', 'cntctfrmtdb_change_status' );

add_action( 'contact_form_to_db_pro_license_cron', 'cntctfrmtdb_license_cron_task' );
add_action( 'after_plugin_row_contact-form-to-db-pro/contact_form_to_db_pro.php', 'cntctfrmtdb_plugin_update_row', 10, 2 );
add_filter( 'plugins_api', 'cntctfrmtdb_inject_info', 20, 3 );

/* uninstal hook */
register_uninstall_hook( __FILE__, 'cntctfrmtdb_uninstall_hook' );