<?php 
cake_woocommerce();



/***** All functions for woocommerce customization *****/
function cake_woocommerce(){
	
	add_filter( 'woocommerce_add_to_cart_fragments', 'cake_woocommerce_header_add_to_cart_fragment' );
	
	/* Main Page Woocommerce Changes */
	add_filter('loop_shop_columns', 'loop_columns', 10, 1);
	add_filter('woocommerce_show_page_title', 'cake_woocommerce_show_page_title');
	add_filter('yith-wcwl-browse-wishlist-label','cake_wclc_browse_wishlist_text');
	add_filter( 'loop_shop_per_page', 'cake_show_products_per_page', 20 );
	add_filter( 'yith_quick_view_loader_gif', 'cake_replace_quick_view_loader');
	

	// Remove Action
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	

	/* Loop Woocommerce Changes */
	add_action( 'woocommerce_before_shop_loop', 'cake_woocommerce_loop_ulprod_wrapper_start', 40 );
	add_action( 'woocommerce_after_shop_loop', 'cake_woocommerce_loop_ulprod_wrapper_end', 6 );
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_nostock_badge', 5 );
	
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_loop_list_wrapper_start', 6 );
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_loop_img_wrapper_start', 7 );
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 15 );
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_loop_btn_wrapper_start', 16 );

	add_action('woocommerce_before_shop_loop_item','cake_add_custom_button',18);
	add_action('woocommerce_shop_loop_item_title', 'cake_product_title', 10);
	
	
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_loop_btn_wrapper_end', 19 );
	add_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_loop_img_wrapper_end', 20 );

	remove_action( 'woocommerce_before_shop_loop_item', 'cake_woocommerce_product_categories', 22 );
	add_action( 'woocommerce_after_shop_loop_item', 'cake_woocommerce_loop_list_wrapper_end', 11 );
	add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);
	
	/* Single Product Page changes*/
	add_action( 'woocommerce_before_single_product_summary', 'cake_before_single_product_summary', 5 );
	add_action( 'woocommerce_after_single_product_summary', 'cake_after_single_product_summary', 6 );
	add_action( 'woocommerce_after_single_product_summary', 'cake_woocommerce_upsell_display', 15 );
	add_filter( 'woocommerce_output_related_products_args', 'cake_woocommerce_related_products_args' );
	add_action( 'after_switch_theme', 'cake_woocommerce_image_dimensions', 1 );
	
	
	//Badge
	add_filter( 'woocommerce_sale_flash', 'cake_custom_replace_sale_text' );
	
	
}

// Update total item in dropdown cart
if (!function_exists('cake_woocommerce_header_add_to_cart_fragment')) {
function cake_woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>

	<span class="cart-totalqty badge-custom"><?php echo sprintf (_n( '%d', '%d', WC()->cart->get_cart_contents_count(),'cake' ), WC()->cart->get_cart_contents_count() );?></span>
	
	<?php
	
	$fragments['span.cart-totalqty'] = ob_get_clean();
	
	return $fragments;
}
}

// Change Loader image quick view
if (!function_exists('cake_replace_quick_view_loader')) {
function cake_replace_quick_view_loader($loaderimg){
	$loaderimg ='';
	$loaderimg .= get_template_directory_uri() . '/images/trans.png';
	return $loaderimg;
}
}



// Change product title
if (!function_exists('cake_product_title')) {
function cake_product_title(){
	global $product;
	
	echo '<h3><a href="'.esc_url(get_permalink($product->id)).'">'.get_the_title($product->id).'</a></h3>';
}
}

// Change number or products per row
if (!function_exists('loop_columns')) {
	function loop_columns() {
		
		$col = get_theme_mod('cake_product_column', '3');

		
		return $col; //products per row
	}
}



// Display product per page
if (!function_exists('cake_show_products_per_page')) {
	function cake_show_products_per_page() {
		
		$show_product = get_theme_mod('cake_product_per_page', '9');
			
		return $show_product;

	}
}

//Disable page title in shop page
if (!function_exists('cake_woocommerce_show_page_title')) {
	function cake_woocommerce_show_page_title(){
		return false;
	}
}

//Wishlist text
if (!function_exists('cake_wclc_browse_wishlist_text')) {
	function cake_wclc_browse_wishlist_text($cake_return=''){
		global $product;
		
		$browse_wishlist = get_option( 'yith_wcwl_browse_wishlist_text' );
		
		$cake_return ='';
		$cake_return .= '<i class="fa fa-check"></i><span>'.esc_html($browse_wishlist).'</span>';
		return $cake_return;
	}
}

//No stock text
if (!function_exists('cake_woocommerce_nostock_badge')) {
	function cake_woocommerce_nostock_badge(){
		global $product;

		$text = get_theme_mod('cake_product_outstock_label', esc_html__('No Stock','cake'));

		if ( !$product->is_in_stock() ) {
			echo '<span class="onsale soldout">'.esc_html($text).'</span>';
		}
		
	}
}

//Sale text
if (!function_exists('cake_custom_replace_sale_text')) {
	function cake_custom_replace_sale_text() {
		
		$text = get_theme_mod('cake_product_sale_label', esc_html__('Sale','cake'));	
		$out = '<div><span class="onsale">'.esc_html($text).'</span></div>';
		return $out;
		
	}
}

//Product ul wrapper start
if (!function_exists('cake_woocommerce_loop_ulprod_wrapper_start')) {
	function cake_woocommerce_loop_ulprod_wrapper_start(){
		
		$col = get_theme_mod('cake_product_column', '3');
		
		echo '<div class="woocommerce columns-'.esc_attr($col).'">';
	}
}

//Product ul wrapper end
if (!function_exists('cake_woocommerce_loop_ulprod_wrapper_end')) {
	function cake_woocommerce_loop_ulprod_wrapper_end(){
		echo '</div>';
	}
}


//Product list wrapper start
if (!function_exists('cake_woocommerce_loop_list_wrapper_start')) {
	function cake_woocommerce_loop_list_wrapper_start(){
		echo '<div class="cake-product-item">';
	}
}

//Product list wrapper end
if (!function_exists('cake_woocommerce_loop_list_wrapper_end')) {
	function cake_woocommerce_loop_list_wrapper_end(){
		echo '</div>';
	}
}

//Product image wrapper start
if (!function_exists('cake_woocommerce_loop_img_wrapper_start')) {
	function cake_woocommerce_loop_img_wrapper_start(){
		global $product;
		$product_link = get_permalink( $product->ID );
		
		echo '<div class="cake-product-img"><div class="cake-product-img-table"><a href="'.$product_link.'">';
		
	}
}

//Product image wrapper end
if (!function_exists('cake_woocommerce_loop_img_wrapper_end')) {
	function cake_woocommerce_loop_img_wrapper_end(){
		echo '</a></div></div>';
	}
}

//Product button wrapper start
if (!function_exists('cake_woocommerce_loop_btn_wrapper_start')) {
	function cake_woocommerce_loop_btn_wrapper_start(){
		//echo '<div class="cake-btn-container"><div class="cake-btn-container-table"><div class="cake-btn-container-cell">';
	}
}

//Add wishlist button
if (!function_exists('cake_woocommerce_loop_wishlist')) {
	function cake_woocommerce_loop_wishlist(){
		global $yith_wcwl, $product;
		
		update_option('yith_wcwl_add_to_wishlist_icon', 'fa fa-heart');
		echo do_shortcode('[yith_wcwl_add_to_wishlist label="<span></span>"]');
	}
}

//Product button wrapper end
if (!function_exists('cake_woocommerce_loop_btn_wrapper_end')) {
	function cake_woocommerce_loop_btn_wrapper_end(){
		//echo '</div></div></div>';
	}
}

//Product categories
if (!function_exists('cake_woocommerce_product_categories')) {
	function cake_woocommerce_product_categories(){
		global $product;
		
		$terms = get_the_terms( $product->id, 'product_cat' );
		if($terms=="") return false;
		$product_cat = array();
		
		echo '<div class="cake-productcat">';
		foreach ($terms as $term) {
			$term_link = get_term_link( $term );
			$product_cat[] = '<a href="'. esc_url($term_link) .'">' . esc_attr($term->name) . '</a>';
		}
		echo join( ", ", $product_cat );
		
		echo '</div>';
	}
}


//Add Custom Button
if (!function_exists('cake_add_custom_button')) {
	function cake_add_custom_button() {
		global $yith_wcwl, $product, $woocommerce;
		
		$enable = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
		
		if( function_exists( 'YITH_WCWL' ) ){
			$label_option = get_option( 'yith_wcwl_add_to_wishlist_text' );
			$browse_wishlist = get_option( 'yith_wcwl_browse_wishlist_text' );
			$default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists( array( 'is_default' => true ) ) : false;
			$wishlist_url = YITH_WCWL()->get_wishlist_url('/');

			if( ! empty( $default_wishlists ) ){
				$default_wishlist = $default_wishlists[0]['ID'];
			}
			else{
				$default_wishlist = false;
			}
			
			$exists = YITH_WCWL()->is_product_in_wishlist( $product->id, $default_wishlist );
		}
		
		
		$product_type = $product->product_type;

		switch ( $product_type ) {
			case 'external':
				$but_woo_label = esc_html__( 'Buy product', 'cake' );
				$link = $product->get_product_url();
				$icontype = '<i class="fa fa-external-link"></i>';
				$addclass = '';
			break;
			case 'grouped':
				$but_woo_label = esc_html__( 'View products', 'cake' );
				$link = $product->get_permalink();
				$icontype = '<i class="fa fa-gear"></i>';
				$addclass = '';
			break;
			case 'simple':
				$but_woo_label = esc_html__( 'Add to cart', 'cake' );
				$link = $product->add_to_cart_url();
				$icontype = '<i class="fa fa-shopping-cart"></i>';
				$addclass = 'ajax_add_to_cart';
			break;
			case 'variable':
				$but_woo_label = esc_html__( 'Select options', 'cake' );
				$link = $product->get_permalink();
				$icontype = '<i class="fa fa-gear"></i>';
				$addclass = '';
			break;
			default:
				$but_woo_label = esc_html__( 'Read more', 'cake' );
				$link = $product->get_permalink();
				$icontype = '<i class="fa fa-file"></i>';
				$addclass = '';
		}
		
		
		$out ='';
		
		if(function_exists('yith_wishlist_constructor')){
		$out .= do_shortcode('[yith_wcwl_add_to_wishlist exists="'.esc_attr($exists).'" label="<span>'.esc_attr($label_option).'</span>" product_id="' . esc_attr($product->id) . '" wishlist_url="'.esc_url($wishlist_url).'" icon="fa-heart"]');
		}

		//$out .='<a href="'.esc_url($link).'" class="cake-woo-button button add_to_cart_button product_type_'.esc_attr($product_type).' '.esc_attr($addclass).'" data-product_id="' . esc_attr($product->id) . '" data-product_sku="' . esc_attr($product->sku) . '">'. $icontype .'<span>'.$but_woo_label.'</span></a>';
		
		//$out .='<a href="'.esc_url($woocommerce->cart->get_cart_url()).'" class="cake-woo-button added_to_cart wc-forward"><i class="fa fa-eye"></i><span>'.esc_html__('View Cart','cake').'</span></a>';
		
		if(class_exists( 'YITH_WCQV' ) && $enable) {
			$label = esc_html( get_option( 'yith-wcqv-button-label' ) );
			//$out .='<a href="#" class="cake-woo-button yith-wcqv-button" data-product_id="' . esc_attr($product->id) . '"><i class="fa fa-search"></i><span>'.$label.'</span></a>';
		}else{
			$linkto = $product->get_permalink();
			//$out .= do_shortcode('<a href="'.esc_url($linkto).'" class="cake-woo-button"><i class="fa fa-search"></i><span>'.esc_html__('View Product','cake').'</span></a>');
		}
		
		echo $out;
	}
}

//Related Product
if (!function_exists('cake_woocommerce_related_products_args')) {
	function cake_woocommerce_related_products_args( $args ) {
		$args['posts_per_page']     = 3;
		$args['columns']            = 3;
		$args['orderby']            = 'rand';
		return $args;
	}
}

//Product upsell display
if (!function_exists('cake_woocommerce_upsell_display')) {
	function cake_woocommerce_upsell_display(){
		woocommerce_upsell_display( -1, 3);
	}
}

//Single product wrapper before
if (!function_exists('cake_before_single_product_summary')) {
	function cake_before_single_product_summary(){
		echo '<div class="cake-single-product-summary clearfix">';
	}
}

//Single product wrapper after
if (!function_exists('cake_after_single_product_summary')) {
	function cake_after_single_product_summary(){
		echo '</div>';
	}
}

//Product image dimensions
if (!function_exists('cake_woocommerce_image_dimensions')) {
	function cake_woocommerce_image_dimensions() {
		global $pagenow;
	 
		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}
		$catalog = array(
			'width' 	=> '280',	// px
			'height'	=> '280',	// px
			'crop'		=> 1 		// true
		);
		$single = array(
			'width' 	=> '368',	// px
			'height'	=> '368',	// px
			'crop'		=> 1 		// true
		);
		$thumbnail = array(
			'width' 	=> '65',	// px
			'height'	=> '65',	// px
			'crop'		=> 1 		// true
		);
		
		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
}

//Replace woocomerce navigation
if (!function_exists('woocommerce_pagination')) {
	function woocommerce_pagination() {
		if(function_exists('wp_pagenavi')) { 
			wp_pagenavi();
		}
	}
}

//Product Banner Slick
if ( ! function_exists( 'cake_product_slider' ) ) {
	function cake_product_slider($type, $number){
		global $woocommerce, $wpdb, $product ; 

		if(!function_exists('is_woocommerce')){ return false; }

		if(!isset($woocommerce)) return ;
		
		$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );

		switch($type):

			case 'featured':

				$query_args['meta_query'] = $woocommerce->query->get_meta_query();

				$query_args['meta_query'][] = array(

					'key' => '_featured',

					'value' => 'yes'

				);

			break;

			case 'top_rated':

				$query_args['meta_query'] = $woocommerce->query->get_meta_query();		

			break;

			default:

				$query_args['meta_query'] = array();

				$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			
				$query_args['meta_query']   = array_filter( $query_args['meta_query'] );

			break;

		endswitch;

		$q = new WP_Query( $query_args );
		
		$out ='';

		if($q->have_posts()):
		
		while($q->have_posts()): $q->the_post();


			$out .='<div class="cdoslick-item">';
			
				$out .='<a href="'.esc_url(get_permalink($q->ID)).'">';
					if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					$out.= get_the_post_thumbnail($q->ID, 'cake-slider-thumb'); 
					}
				$out .='</a>';
				
				$out .='<div class="price-cake thewoo hidden-xs">';
					$product = new WC_Product( get_the_ID() );
					$price = $product->price;
					$price_html = $product->get_price_html();
				  $out .='<p>'.$price_html .'</p>';
				$out .='</div>';
				
			$out .='</div>';

			endwhile;

		

	endif; wp_reset_query();

	echo $out;

	}
}
?>