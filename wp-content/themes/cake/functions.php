<?php
//Load Functions
require get_template_directory() . '/functions/cdo-theme-metaboxes.php';
require get_template_directory() . '/functions/cdo-theme-functions.php';
require get_template_directory() . '/functions/cdo-theme-hooks.php';
require get_template_directory() . '/functions/cdo-theme-widgets.php';
require get_template_directory() . '/functions/cdo-theme-styles.php';
require get_template_directory() . '/functions/cdo-theme-scripts.php';
require get_template_directory() . '/functions/cdo-woocommerce.php';
require get_template_directory() . '/functions/class-tgm-plugin-activation.php';

require get_template_directory() . '/functions/customizer/extensions/utilities.php';
require get_template_directory() . '/functions/customizer/extensions/sanitization.php';
require get_template_directory() . '/functions/customizer/extensions/fonts.php';
require get_template_directory() . '/functions/customizer/theme-customizer.php';
require get_template_directory() . '/functions/customizer/extensions/preview.php';



//Install Plugins
add_action( 'tgmpa_register', 'cake_register_required_plugins' );
function cake_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'     				=> esc_html__('WooCommerce - excelling eCommerce','cake'), // The plugin name
			'slug'     				=> 'woocommerce', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('Codeopus Shortcode','cake'), // The plugin name
			'slug'     				=> 'codeopus-shortcodes', // The plugin slug (typically the folder name)
			'source'   				=> get_template_directory() . '/inc/plugins/codeopus-shortcodes.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
		),
		array(
			'name'     				=> esc_html__('Stag Custom Sidebars','cake'), // The plugin name
			'slug'     				=> 'stag-custom-sidebars', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('CMB2 Metabox','cake'), // The plugin name
			'slug'     				=> 'cmb2', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('YITH WooCommerce Wishlist','cake'), // The plugin name
			'slug'     				=> 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('YITH WooCommerce Badge Management','cake'), // The plugin name
			'slug'     				=> 'yith-woocommerce-badges-management', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('Breadcrumb Navxt','cake'), // The plugin name
			'slug'     				=> 'breadcrumb-navxt', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('Contact Form 7','cake'), // The plugin name
			'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('WP Pagenavi','cake'), // The plugin name
			'slug'     				=> 'wp-pagenavi', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('Customizer Export/Import','cake'), // The plugin name
			'slug'     				=> 'customizer-export-import', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
		array(
			'name'     				=> esc_html__('Widget Importer/Exporter','cake'), // The plugin name
			'slug'     				=> 'widget-importer-exporter', // The plugin slug (typically the folder name)
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		),
	);

	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'cake' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'cake' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'cake' ), // %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'cake' ),
			'notice_can_install_required'     => _n_noop(
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop(
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop(
				'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop(
				'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'cake'
			), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop(
				'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
				'cake'
			), // %1$s = plugin name(s).
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'cake'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'cake'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'cake'
			),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'cake' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'cake' ),
			'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'cake' ),
			'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'cake' ),  // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'cake' ),  // %1$s = plugin name(s).
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'cake' ), // %s = dashboard link.
			'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'cake' ),
			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),
	);

	tgmpa( $plugins, $config );
}
//アイキャッチ画像の定義と切り抜き
add_action( 'after_setup_theme', 'cake_theme_setup' );
function cake_theme_setup() {
 add_image_size('400_thumbnail', 400, 280, true );
}
//　一覧記事取得関数 --------------------------------------------------------------------------------
// "num" = 表示記事数, "cat" = カテゴリ番号
// 呼び出し元での指定も可能 -> [getCategoryArticle num="x" cat="y"]
function getCatItems($atts, $content = null) {
	extract(shortcode_atts(array(
	  "num" => '4',
	  "cat" => '54'
	), $atts));
	
	// 処理中のpost変数をoldpost変数に退避
	global $post;
	$oldpost = $post;
	
	// カテゴリーの記事データ取得
	$myposts = get_posts('numberposts='.$num.'&order=DESC&orderby=post_date&category='.$cat);
	
	if($myposts) {
		// 記事がある場合↓
		$retHtml = '<div class="getPostDispArea news-lists"><div class="row">';
		
		// 取得した記事の個数分繰り返す
		foreach($myposts as $post) :
			// 投稿ごとの区切りのdiv
			$retHtml .= '<div class="getPost col-sm-3">';

			// 記事オブジェクトの整形
			setup_postdata($post);

			// サムネイルの有無チェック
			if ( has_post_thumbnail() ) {
				// サムネイルがある場合↓
				$retHtml .= '<div class="getPostImgArea">' . get_the_post_thumbnail($page->ID, '400_thumbnail') . '</div>';
			} else {
				// サムネイルがない場合↓※何も表示しない
				$retHtml .= '';
			}
			
			// 文章のみのエリアをdivで囲う
			$retHtml .= '<div class="getPostStringArea home-list">';
			
			// 投稿年月日を取得
			/*$year = get_the_time('Y');	// 年
			$month = get_the_time('M');	// 月
			$day = get_the_time('d');	// 日
			
			
			$retHtml .= '<span>' . $day . '.' . $month . '.' . $year . '</span>';*/
		    // タイトル設定(リンクも設定する)
			$retHtml.= '<h4 class="getPostTitle jp">';
			$retHtml.= '<a href="' . get_permalink() . '">' . the_title("","",false) . '</a>';
			$retHtml.= '</h4>';
		
		    $cats = get_the_category();
		    $cat = $cats[0];
		    if($cat->parent){
            $parent = get_category($cat->parent);
		    
		    $retHtml .= '<span class="entry-meta">' . $parent->cat_name .' | '. get_post_time('j.M.Y') . '</span>';
			}else{
			$retHtml .= '<span class="entry-meta">' . $cat->cat_name .' | '. get_post_time('j.M.Y') . '</span>';
			}
			
			
			
			// 本文を抜粋して取得
			/*$getString = get_the_excerpt();
			$retHtml.= '<div class="getPostContent">' . $getString . '</div>';*/
			
			$retHtml.= '</div></div>';
			
		endforeach;
		$retHtml.= '</div></div>';
	} else {
		// 記事がない場合↓
		$retHtml='<p>記事がありません。</p>';
	}
	
	// oldpost変数をpost変数に戻す
	$post = $oldpost;
	
	return $retHtml;
}
// 呼び出しの指定
add_shortcode("getCategoryArticle", "getCatItems");

class Walker_Nav_Menu_Test extends Walker_Nav_Menu {
function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
	    $item_output .= '<span class="en-menu">';
		$item_output .= $args->link_before . $title . $args->link_after;
	    $item_output .= '</span>';
	    $item_output .= '<span class="ja-menu jp">';
	    $item_output .= $item->description;
	    $item_output .= '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	}
// Move product tabs
 
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );

?>
