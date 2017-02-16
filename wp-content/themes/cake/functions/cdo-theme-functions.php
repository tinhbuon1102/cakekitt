<?php
/*====================================================================================================
Define content width
======================================================================================================*/
if ( ! isset( $content_width ) )
$content_width = 960;

/*====================================================================================================
Set Up Theme
======================================================================================================*/
if ( ! function_exists( 'cake_setup' ) ):
	function cake_setup() {
				
	//Make theme available for translation
	load_theme_textdomain( 'cake', get_template_directory() . '/languages' );


	//This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	
	//Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
		
	//This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
	'mainmenu' => esc_html__( 'Main Navigation','cake'),
	'mainmenuleft' => esc_html__( 'Header Navigation Left','cake'),
	'mainmenuright' => esc_html__( 'Header Navigation Right','cake'),
    'submenuleft' => esc_html__( 'Header Sub Navigation Left','cake'),
	'submenuright' => esc_html__( 'Header Sub Navigation Right','cake')

	) );
	
	//Switch default core markup for search form, comment form, and comments
	//to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	));
	
	//This theme uses post thumbnails
	if (function_exists('add_theme_support')) {
		add_theme_support( 'post-thumbnails');
		set_post_thumbnail_size( 200, 200 );
		add_image_size( 'cake-small-custom-image', 600, 960, false );
		add_image_size( 'cake-custom-image', 600, 662, false );
		add_image_size( 'cake-medium-custom-image', 1140, 500, false );
		add_image_size( 'cake-slider-thumb', 309, 324, false );
	}
	
	//This theme uses gallery, video post format
	add_theme_support( 'post-formats', array('image', 'gallery', 'video', 'audio', 'link', 'quote'));
	
	//Use shortcode on the exceprt
	add_filter( 'the_excerpt', 'do_shortcode');
	
	//Add woocommerce support
	add_theme_support( 'woocommerce' );
	
	//Add Title Tag support
	add_theme_support( 'title-tag' );
		
	}
endif;

/*====================================================================================================
WP Head
======================================================================================================*/
if (!function_exists('cake_wp_head')) :
function cake_wp_head() {
	
	//Variable
	$favico = get_theme_mod('cake_custom_favicon', '');
	$apple144 = get_theme_mod('cake_apple_touch_144','');
	$apple114 = get_theme_mod('cake_apple_touch_114','');
	$apple72 = get_theme_mod('cake_apple_touch_72','');
	$apple128 = get_theme_mod('cake_apple_touch_128','');
	$responsive = get_theme_mod('cake_responsive_layout', 'true');
	
	
	echo '<meta charset="'.get_bloginfo( 'charset' ).'">';
	if($responsive=='true'):
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	endif;
	echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
	echo '<link rel="pingback" href="'.get_bloginfo( 'pingback_url' ).'">';
	
	//Favicon
	wp_site_icon();
	
	//Comment Reply
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

}
endif;


/*====================================================================================================
Remove height/width on images for responsive
======================================================================================================*/
if ( ! function_exists( 'cake_remove_thumbnail_dimensions' ) ):
	function cake_remove_thumbnail_dimensions( $html ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
endif;



/*-----------------------------------------------------------------------------------
Custom Comments Display
-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cake_wp_comment' ) ) :
	function cake_wp_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php esc_html_e( 'Pingback:', 'cake' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'cake' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		<?php else : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php echo get_avatar( $comment); ?>
					</div><!-- .comment-author -->

					<div class="comment-metadata">
						<?php printf( '%s', sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'cake' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( esc_html__( 'Edit', 'cake' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'cake' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
					<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
					?>
				</div><!-- .comment-content -->

				
			</article><!-- .comment-body -->

		<?php
		endif;
	}
endif;


/*-----------------------------------------------------------------------------------
Vimeo Video
-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cake_is_vimeo' ) ):
	function cake_is_vimeo($file) {
	  if (preg_match('/vimeo/i',$file)) {
		return true;
	  } else {
		return false;
	  }
	}
endif;

/*-----------------------------------------------------------------------------------
Youtube Video
-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cake_is_youtube' ) ):
	function cake_is_youtube($file) {
	  if (preg_match('/youtube/i',$file)) {
		return true;
	  } else {
		return false;
	  }
	}
endif;



/*-----------------------------------------------------------------------------------
Enable excerpt for page 
-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cake_add_excerpts_to_pages' ) ):
	function cake_add_excerpts_to_pages() {
		 add_post_type_support( 'page', 'excerpt' );
	}
endif;


/*-----------------------------------------------------------------------------------
Popular Post
-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cake_popular_post' ) ) :
	function cake_popular_post($num=3, $type="") {
	  global $post;
	  $type = ($type=="recent" ? '' : 'comment_count');
	  ?>
	  
	  
	  
	  <ul class="popular-list">
	  <?php
	  query_posts(array('posts_per_page'=>$num,'orderby'=>$type, 'ignore_sticky_posts' => 1));
	  while (have_posts()) : the_post();
	  ?>
		<li>
		
			<div class="popularcoltext">
			<?php if ( has_post_thumbnail() ) { ?>
			<span class="popular-img"><?php the_post_thumbnail( 'thumbnail' );?></span>
			<?php } ?>
			<span class="popular-date"><?php echo get_the_time('l , d/m/Y g:i a'); ?></span>
			<p class="popular-title"><a href="<?php esc_url(the_permalink());?>" title="<?php esc_attr(the_title()); ?>"><?php the_title();?></a></p>
			</div>
			
		</li> 
		<?php endwhile; wp_reset_query();?>
		</ul>
		<div class="clear"></div>
		<?php
	}
endif;

/*====================================================================================================
Main Navigation Fallback
======================================================================================================*/
if ( ! function_exists( 'cake_menu_page_fallback' ) ) :
	function cake_menu_page_fallback() {
		
		$out = esc_html__('Please add some menu here. Navigate to WP Admin >> Appearance >> Menus.','cake');
		echo $out;
		
	}
endif;


/*====================================================================================================
Highlight Parent Menu in Post type
======================================================================================================*/
if ( ! function_exists( 'cake_current_nav_class' ) ) :
	function cake_current_nav_class($classes, $item) {

		$post_type = get_query_var('post_type');
		
		// Removes current_page_parent class from blog menu item
		 if (is_singular($post_type) == $post_type )
			$classes = array_diff($classes, array( 'current_page_parent' ));
		
		// This adds a current_page_parent class to the parent menu item
		if ($item->xfn != '' && $item->xfn == $post_type) {

			array_push($classes, 'current-menu-item');

		};

		return $classes;

	}
endif;


/*====================================================================================================
Add Shortcode in Contact Form 7
======================================================================================================*/
if ( ! function_exists( 'cake_wpcf7_form_elements' ) ) :
	function cake_wpcf7_form_elements( $form ) {
		$form = do_shortcode( $form );
		return $form;
	}
endif;


/*====================================================================================================
Get Post Id
======================================================================================================*/
if( !function_exists('cake_get_postid')):
	function cake_get_postid(){
		
		global $post;
		
		if( is_home() ){
			$mdt_pid = get_option('page_for_posts');
		}elseif( function_exists( 'is_woocommerce' ) && is_shop() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_category() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_tag() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}else{
			$mdt_pid = get_the_ID();
		}
		
		return $mdt_pid;
	}
endif;


/*====================================================================================================
Sidebar Blog Position
======================================================================================================*/
if( !function_exists('cake_sidebar_blog_position')):
	function cake_sidebar_blog_position(){
		
		$layout = get_theme_mod('cake_blog_sidebar', 'sidebar-right');
		
		$position = ($layout=="sidebar-right" ? 'float:left' : ($layout=="sidebar-left" ? 'float:right' : ''));
		
		$class = $position!="" ? 'col-sm-8' : 'col-sm-12';
		
		return array('colclass' => $class, 'position' => $position);

	}
endif;


/*====================================================================================================
Woocommerce check
======================================================================================================*/
if ( ! function_exists( 'cake_is_woocommerce_activated' ) ) {
	function cake_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

if( !function_exists('cake_is_true_woocommerce')):
function cake_is_true_woocommerce(){
	if( function_exists("is_woocommerce") && is_woocommerce()){
	return true;
	}
	return false;
}
endif;

if( !function_exists('cake_is_true_product')):
function cake_is_true_product(){
	if( function_exists("is_product") && is_product()){
	return true;
	}
	return false;
}
endif;

if( !function_exists('cake_is_true_shop')):
function cake_is_true_shop(){
	if( function_exists("is_shop") && is_shop()){
	return true;
	}
	return false;
}
endif;

if( !function_exists('cake_is_true_woopage')):
function cake_is_true_woopage(){
	if( function_exists("is_cart") && is_cart()){
	return true;
	}elseif(function_exists("is_checkout") && is_checkout()){
	return true;	
	}elseif(function_exists("is_account_page") && is_account_page()){
	return true;		
	}
	return false;
}
endif;

if( !function_exists('cake_is_true_product_category')):
function cake_is_true_product_category(){
	if( function_exists("is_product_category") && is_product_category()){
	return true;
	}
	return false;
}
endif;



/*====================================================================================================
Sidebar Page Position
======================================================================================================*/
if( !function_exists('cake_sidebar_page_position')):
	function cake_sidebar_page_position(){

		global $post;
		
		if( function_exists( 'is_woocommerce' ) && is_shop() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_category() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_tag() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}else{
			$mdt_pid = get_the_ID();
		}
		
		$id = ( isset( $post->ID ) ? $mdt_pid : "" );
		$theID = $id;
		
		$layoutmeta = get_post_meta($theID, 'cake_page_layout', true);
		$getwidget = get_theme_mod('cake_product_single_widget','false');

		if($layoutmeta!="") {
			$layout  = $layoutmeta;
		} else { 
			$layout  = 'no-sidebar';
		}
		
		if(cake_is_true_product()){
			if($getwidget=="true" && $layout=="sidebar-right"){
				$position ="float:left";
			}elseif($getwidget=="true" && $layout=="sidebar-left"){
				$position ="float:right";
			}else{
				$position ="";
			}
			
		}else{
			
			if($layout=="sidebar-right"){
				$position ="float:left";
			}elseif($layout=="sidebar-left"){
				$position ="float:right";
			}else{		
				$position ="";	
			}	
		}
		
		
		if($position){
			
			if(cake_is_true_product()){
				if($getwidget=="true"){
				$class  = 'col-sm-9';
				$class2 = 'col-sm-3';
				}else{
				$class  = 'col-sm-12';
				$class2 = '';
				}
			}elseif(cake_is_true_woocommerce() || cake_is_true_woopage()){
				$class  = 'col-sm-9';
				$class2 = 'col-sm-3';//
			}else{
			
				$class  = 'col-sm-8';
				$class2 = 'col-sm-4';
			}
				

		}elseif(is_page_template( 'page-templates/page_blog.php' ) ){
			if($layoutmeta=="no-sidebar"){
			$class = 'col-sm-offset-2 col-sm-8 overflow-xs';
			$class2 = '';			
			}else{
			$class = 'col-sm-12';
			$class2 = '';
			}				
		}else{
			$class = 'col-sm-12';
			$class2 = '';
		}
		
		return array('colclass' => $class, 'colsidebar' => $class2, 'position' => $position);

	}
endif;

/*====================================================================================================
Add Class to Body
======================================================================================================*/
if( !function_exists('cake_body_classes')):
	function cake_body_classes( $classes ) {
		
		global $post;
		
		if( function_exists( 'is_woocommerce' ) && is_shop() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_category() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_tag() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product() ){
			$mdt_pid = woocommerce_get_page_id( 'shop' );
		}else{
			$mdt_pid = get_the_ID();
		}
		
		$id = ( isset( $post->ID ) ? $mdt_pid : "" );
		$theID = $id;
		
		$layout = get_theme_mod('cake_blog_sidebar', 'sidebar-right');
		$layoutmeta = get_post_meta($theID, 'cake_page_layout', true);
		$slidertype = get_post_meta($theID, 'cake_slider_choose', true);
		$headertype = get_theme_mod('cake_header_type', 'fixed');
		$loader = get_theme_mod('cake_loader_effect', 'true');
		$showpostdate = get_theme_mod('cake_date_meta', 'true');
		$getwidget = get_theme_mod('cake_product_single_widget','false');
	
		$gettheme = wp_get_theme();
		$thetheme = 'cdo-'.$gettheme->get( 'Name' );
		
		
		// Adds a class of group-blog to blogs with more than 1 published author
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		
		// Adds a class fullwidth or withsidebar
		if(cake_is_true_product()){
			if($getwidget=="true"){
				$classes[] = 'page-withsidebar';
			}else{
				$classes[] = 'page-fullwidth';
			}
			
		}elseif(is_page() || cake_is_true_woocommerce()){
			if($layoutmeta=="no-sidebar"){
				$classes[] = 'page-fullwidth';
			}else{
				$classes[] = 'page-withsidebar';
			}
			
		}else{
		
			if($layout=="no-blog-sidebar"){
				$classes[] = 'post-fullwidth';
			}else{
				$classes[] = 'post-withsidebar';
			}
			
		}
		
		// Has slider or not
		if($slidertype=="slick-slider"){
			$classes[] = 'has-slick-slider';
		}elseif($slidertype=="slice-slider"){
			$classes[] = 'has-slice-slider';
		}elseif($slidertype=="parallax-slider"){
			$classes[] = 'has-parallax-slider';
		}elseif($slidertype=="cycle-slider"){
			$classes[] = 'has-cycle-slider';
		}else{
			$classes[] = 'no-slider';
		}
		
		if($loader=='true'){
			
			$classes[] = 'cake-loader';
		}
		
		if($showpostdate=='false'){
			
			$classes[] = 'no-post-date';
		}
		
		$classes[] = 'cake-header-type-'.$headertype;
		$classes[] = $thetheme;
		
		return $classes;
	}
endif;



/*====================================================================================================
Add link to Menu
======================================================================================================*/
if( !function_exists('cake_add_element_to_menu')):
	function cake_add_element_to_menu( $items, $args ) {
		
		$searchicon = get_theme_mod('cake_top_search_icon', 'true');
		$loginlink =  get_theme_mod('cake_login_link', 'true');
		$carticon = get_theme_mod('cake_cart_icon', 'true');
		$wishlisticon = get_theme_mod('cake_wishlist_icon', 'true');
		$searchtype = get_theme_mod('cake_search_type', 'product_search');
		
			if(cake_is_woocommerce_activated()){
				
			//Cart widget
			global $woocommerce;
			$cake_cart_subtotal = $woocommerce->cart->get_cart_subtotal();
			$cake_product_link = $woocommerce->cart->get_cart_url();
			$cake_cart_items = $woocommerce->cart->get_cart_item_quantities();
			
			$cake_totalqty = 0;
			if(is_array($cake_cart_items)){
				foreach($cake_cart_items as $cake_cart_item){
					$cake_totalqty += (is_numeric($cake_cart_item))? $cake_cart_item : 0;
				}
			}
			
			
			ob_start();
			the_widget('WC_Widget_Cart', '', array('widget_id'=>'cart-dropdown',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<span class="hidden">',
			'after_title'   => '</span>'
			));
			$cake_cart_widget = ob_get_clean();
			
			}
		
			//Wishlist
			if( function_exists( 'YITH_WCWL' ) ){
				$wishlist_url = YITH_WCWL()->get_wishlist_url('/');
				$wishlist_item = YITH_WCWL()->count_products();
			}
			
			if ($args->theme_location == 'submenuright') {
				
				//login/logout link
				if($loginlink=='true'){
					
					if(cake_is_woocommerce_activated()){
						if (is_user_logged_in() ) {
							$loginout =  '<a href="'.esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )).'">'.esc_html__('Logout', 'cake').'</a>';
						}else{
							$loginout = '<a href="'.esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )).'">'.esc_html__('Login','cake').'</a>';
						}
					}else{
						
						$loginout = wp_loginout('', false);
					}
						
					$items .= '<li>';
					$items .= $loginout;
					$items .= '</li>';
				}
				
				//search icon
				if($searchicon=='true'){
					$items .= '<li class="searchnav">';
					$items .= '<a href="#" class="popup-search"><i class="fa fa-search"></i></a>';
					
					
					$items .= '</li>';
					$items .= '<li id="cake-search" class="cake-search">';
					if($searchtype=='blog_search'){
						
						$items .='<div>';
						ob_start();
						do_action('cake_post_search');
						$items .= ob_get_clean();
						$items .='</div>';
						
						}else{
						$items .='<div>';
						
						ob_start();
						do_action('cake_product_search');
						$items .= ob_get_clean();
						
						
						$items .='</div>';
						
					}
					$items .= '</li>';
				}
				
				if(cake_is_woocommerce_activated()){
					
					if( function_exists( 'YITH_WCWL' ) && $wishlisticon=='true' ){	
						$items .= '<li class="cake-wishlist-menu">';
						$items .= '<a href="'.esc_url($wishlist_url).'">';
						$items .= '<span class="cake-wishlist-icon">';
						$items .= '<i class="fa fa-heart"></i><span class="badge-custom">'. $wishlist_item .'</span>';
						$items .= '</span>';
						$items .= '</a>';
						$items .= '</li>';
					}
					
					//cart icon
					if($carticon=='true'){
						$items .= '<li>';
						$items .= '<a href="'.esc_url($cake_product_link).'">';
						$items .= '<div class="cake-menu-cart">';
						$items .= '<i class="fa fa-shopping-cart"></i><span class="cart-totalqty badge-custom">'.sprintf (_n( '%d', '%d', WC()->cart->get_cart_contents_count(),'cake' ), WC()->cart->get_cart_contents_count() ).'</span>';
						
						if(!is_cart() && !is_checkout()){
						
						$items .= '<div class="cake-dropdown-cart">';
						$items .= $cake_cart_widget;
						$items .= '</div>';
						
						}
						$items .= '</div>';
						$items .= '</a>';
						$items .= '</li>';
					}
				}//endif cake_is_woocommerce_activated

				
			}
		
		return $items;
	}
endif;


if( !function_exists('cake_showmenu_inmobile')):
	function cake_showmenu_inmobile() {
		
	$items ='';
	$items .= wp_nav_menu( array(
			'theme_location' => 'mainmenuleft',
			'sort_column' => 'menu_order',
			'container' => '',
			'menu_id' => 'menu_left_mobile',
			'depth' => '1',
			'menu_class' => 'themobilecart',
			'echo'       => false,
			'fallback_cb' => ''
			));

	echo $items;
	
	}
	
endif;

/*====================================================================================================
Blog Navigation
======================================================================================================*/
if (!function_exists( 'cake_content_nav')):
	function cake_content_nav($nav_id) {
		global $wp_query, $post;
		
		
		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous )
				return;
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() || is_page_template( 'page-templates/page_blog.php' ) || is_page_template( 'page-templates/page_blog_masonry.php' ) ) )
			return;

		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

		?>
		<nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr($nav_class); ?>">

		<?php if ( is_single() ) : // navigation links for single posts ?>
			
			<div class="row">
				<div class="col-md-6">
					<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '<i class="fa fa-chevron-left"></i>', 'Previous post link', 'cake' ) . '</span> <span class="nav-title">%title</span>' ); ?>
				</div><!-- .col-md-6 -->
				<div class="col-md-6 col-nav-next">
					<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="nav-title">%title</span> <span class="meta-nav">' . _x( '<i class="fa fa-chevron-right"></i>', 'Next post link', 'cake' ) . '</span>' ); ?>
				</div><!-- .col-md-6 -->
			</div><!-- .row -->

		<?php elseif ($wp_query->max_num_pages > 1 && (is_home() || is_archive() || is_search() || is_page_template( 'page-templates/page_blog.php' ) )) : // navigation links for home, archive, and search pages ?>
			<div class="row">
				<div class="col-md-6">
				
					<?php if (get_next_posts_link()) : ?>
					<div class="nav-previous"><?php next_posts_link('<span class="meta-nav"><i class="fa fa-chevron-left"></i></span> <span class="meta-nav-text1">'.esc_html__('Older','cake').'</span> <span class="meta-nav-text2">'.esc_html__('posts','cake').'</span>'); ?></div>
					<?php endif; ?>
					
				</div><!-- .col-md-6 -->
				<div class="col-md-6 col-nav-next">
				
					<?php if (get_previous_posts_link()) : ?>
					<div class="nav-next"><?php previous_posts_link('<span class="meta-nav-text1">'.esc_html__('Newer','cake').'</span> <span class="meta-nav-text2">'.esc_html__('posts','cake').'</span> <span class="meta-nav"><i class="fa fa-chevron-right"></i></span>'); ?></div>
					<?php endif; ?>
					
				</div><!-- .col-md-6 -->
			</div><!-- .row -->

		<?php endif; ?>

		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
		<?php
	}
endif;


/*====================================================================================================
Attachment Image
======================================================================================================*/
if (!function_exists('cake_the_attached_image')) :
	/**
	 * Prints the attached image with a link to the next attached image.
	 */
	function cake_the_attached_image() {
		$post                = get_post();
		$attachment_size     = apply_filters( 'cake_attachment_size', array( 1200, 1200 ) );
		$next_attachment_url = wp_get_attachment_url();

		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID'
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id )
				$next_attachment_url = get_attachment_link( $next_id );

			// or get the URL of the first image attachment.
			else
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}

		printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
			esc_url( $next_attachment_url ),
			the_title_attribute( array( 'echo' => false ) ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
endif;

if (!function_exists( 'cake_enhanced_image_navigation')) :
/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function cake_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )

	return $url;
}
endif;

/*====================================================================================================
Post Category
======================================================================================================*/
if ( ! function_exists( 'cake_post_category' ) ) :
	function cake_post_category() {
		
		if ('post' == get_post_type()) : // Hide category and tag text for pages on Search
		
			$categories_list = get_the_category_list( __( ', ', 'cake' ) );
			if ( $categories_list ) :
			
			$category_string = '%1$s';
			$category_string = sprintf( $category_string, $categories_list);
			
			endif; // End if categories
		
		endif; // End if 'post' == get_post_type()

		printf(esc_html__('','cake').'<span class="cat-link"><i class="fa fa-folder-open" aria-hidden="true"></i>%1$s</span>',
		
			sprintf($category_string)

		);
	}
endif;

/*====================================================================================================
Post Tag
======================================================================================================*/
if ( ! function_exists( 'cake_post_tag' ) ) :
	function cake_post_tag() {
		
		if ('post' == get_post_type()) : // Hide category and tag text for pages on Search
		
			$tags_list = get_the_tag_list( '', __( ', ', 'cake' ) );
			if ( $tags_list ) :
			
			$tag_string = esc_html__( 'Tagged: %1$s', 'cake' );
			$tag_string = sprintf( $tag_string, $tags_list);
			else:
			$tag_string = '';
			endif; // End if $tags_list
		
		endif; // End if 'post' == get_post_type()
		
		printf('<span class="tag-link">%1$s</span>',
		
			sprintf($tag_string)

		);
	}
endif;

/*====================================================================================================
Post Author
======================================================================================================*/
if ( ! function_exists( 'cake_post_author' ) ) :
	function cake_post_author() {
		
		printf('<span class="byline"> '.esc_html__('by: ','cake').'%1$s</span>',
		
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( esc_html__( 'View all posts by %s', 'cake' ), get_the_author() ) ),
				esc_html( get_the_author() )
			)

		);
	}
endif;

/*====================================================================================================
Post Time
======================================================================================================*/
add_action('cake_post_meta_date','cake_post_time');
if ( ! function_exists( 'cake_post_time' ) ) :
	function cake_post_time() {
		$time_string = '<time class="entry-date published" datetime="%1$s"><span class="dd">%2$s</span><span class="mm">%3$s</span><span class="y">%4$s</span></time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'd' ) ),
			esc_html( get_the_date( 'M' ) ),
			esc_html( get_the_date( 'Y' ) )
		);
		
		printf( '<span class="post-time">%1$s</span>',
		
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				esc_url( get_permalink() ),
				esc_attr( get_the_time() ),
				$time_string
			)
		
		);
	}
endif;

/*====================================================================================================
Post Comment
======================================================================================================*/
if ( ! function_exists( 'cake_post_comment' ) ) :
	function cake_post_comment() {
		if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
		
		echo '<span class="comment-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'cake' ), esc_html__( '1 Comment', 'cake' ), esc_html__( '% Comments', 'cake' ) );
		echo '</span>';
		endif; // End if comment
	}
endif;

/*====================================================================================================
Posted On
======================================================================================================*/
add_action('cake_post_meta','cake_posted_on');
if ( ! function_exists( 'cake_posted_on' ) ) :
	function cake_posted_on() {
		
		
		$showauthor = get_theme_mod('cake_author_meta', 'true');
		$showcomment = get_theme_mod('cake_comment_meta', 'true');
		$showpostdate = get_theme_mod('cake_date_meta', 'true');
		$showpostcat = get_theme_mod('cake_category_meta', 'true');

		if($showpostcat=='true'){cake_post_category();}
		if($showcomment=='true'){cake_post_comment();}
		if($showauthor=='true'){cake_post_author();}
	}
endif;

/*====================================================================================================
Posted On Footer
======================================================================================================*/
add_action('cake_post_meta_footer','cake_posted_onfooter');
if ( ! function_exists( 'cake_posted_onfooter' ) ) :
	function cake_posted_onfooter() {
			
		$showposttag = get_theme_mod('cake_tag_meta', 'true');
		
		if($showposttag=='true'){cake_post_tag();}
	}
endif;

/*====================================================================================================
Remove Jump Link
======================================================================================================*/
if (!function_exists('cake_remove_more_jump_link')) :
	function cake_remove_more_jump_link($link) { 
		$offset = strpos($link, '#more-');
		if ($offset) {
			$end = strpos($link, '"',$offset);
		}
		if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}
endif;

/*====================================================================================================
Include Shortcode in Mneu
======================================================================================================*/
if (!function_exists('cake_do_menu_shortcodes')):
function cake_do_menu_shortcodes( $menu ){ 
	return do_shortcode( $menu ); 
} 
endif;

/*====================================================================================================
Wishlist Counter
======================================================================================================*/
if (!function_exists('cake_update_wishlist_count')):
function cake_update_wishlist_count(){
    if( function_exists( 'YITH_WCWL' ) ){
        wp_send_json( YITH_WCWL()->count_products() );
    }
}
endif;

if (!function_exists('cake_search_form')):
function cake_search_form($form){

	$form = '<form method="get" class="search-form" action="'.esc_url( home_url( '/' ) ).'">
	<input class="form-control form-cake" value="'.get_search_query().'" placeholder="'.esc_attr__( 'Search', 'cake' ).'" type="text" name="s">
	</form>';
	
	return $form;
	
}
endif;

if (!function_exists('cake_post_search_form')):
function cake_post_search_form(){

	$form = '<form method="get" class="search-form" action="'.esc_url( home_url( '/' ) ).'">
	<input class="form-control form-cake cake-search-input" value="'.get_search_query().'" placeholder="'.esc_attr__( 'Search', 'cake' ).'" type="search" name="s">
	</form>';
	
	echo $form;
	
}
endif;


if (!function_exists('cake_search_form_product')):
function cake_search_form_product(){

	$form ='<form method="get" class="search-form woocommerce-product-search" action="'.esc_url( home_url( '/'  ) ).'">
	<input type="search" class="search-field form-control form-cake cake-search-input" placeholder="'.esc_attr__( 'Search Products', 'cake').'" value="'.get_search_query().'" name="s" title="'.esc_attr__( 'Search for:', 'cake').'" />
	<input type="hidden" name="post_type" value="product" />
	</form>';
	
	echo $form;
	
}
endif;



/*====================================================================================================
Add Filter
======================================================================================================*/
add_filter('post_thumbnail_html', 'cake_remove_thumbnail_dimensions', 10 );
add_filter('image_send_to_editor', 'cake_remove_thumbnail_dimensions', 10 );
add_filter('nav_menu_css_class', 'cake_current_nav_class', 10, 2 );
add_filter('wpcf7_form_elements', 'cake_wpcf7_form_elements');
add_filter('body_class', 'cake_body_classes');
add_filter('wp_nav_menu_items','cake_add_element_to_menu', 10, 2 );
add_filter('attachment_link', 'cake_enhanced_image_navigation', 10, 2 );
add_filter('the_content_more_link', 'cake_remove_more_jump_link');
add_filter('wp_nav_menu', 'cake_do_menu_shortcodes'); 
add_filter( 'get_search_form','cake_search_form');


/*====================================================================================================
Add Action
======================================================================================================*/
add_action('after_setup_theme', 'cake_setup');
add_action('wp_head','cake_wp_head', 0);
add_action('init', 'cake_add_excerpts_to_pages');
add_action('codeopus_popular_post','cake_popular_post');
add_action('cake_product_search', 'cake_search_form_product');
add_action('cake_post_search', 'cake_post_search_form');
add_action('cake_showthisinmobile', 'cake_showmenu_inmobile');
add_action( 'wp_ajax_cake_update_wishlist_count', 'cake_update_wishlist_count' );
add_action( 'wp_ajax_nopriv_cake_update_wishlist_count', 'cake_update_wishlist_count' );
?>