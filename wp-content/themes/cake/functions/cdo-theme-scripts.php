<?php
/*====================================================================================================
Load Javascript
======================================================================================================*/
if( !function_exists ('cake_add_javascripts') ){
	function cake_add_javascripts() {
		global $post;
		$mdt_pid = cake_get_postid();
		$theID = ( isset( $post->ID ) ? $mdt_pid : "" );

		$loader = get_theme_mod('cake_loader_effect', 'true');
		$wishlisticon = get_theme_mod('cake_wishlist_icon', 'true');
		$searchicon = get_theme_mod('cake_top_search_icon', 'true');
		$sliderchoose = get_post_meta($theID, 'cake_slider_choose', true);
				
		//enqueue script
		wp_enqueue_script('html5shiv',get_template_directory_uri().'/js/html5.js', array(), '', false);
		wp_script_add_data('html5shiv', 'conditional', 'lt IE 9' );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js', array( 'jquery'), '', true  );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery'), '', true  );
		wp_enqueue_script( 'cake-jquery-mediaelement', get_template_directory_uri() . '/js/mediaelement.min.js', array( 'jquery'), '', true  );
		wp_enqueue_script( 'cake-jquery-easing', get_template_directory_uri() . '/js/jquery.easing.min.js', array( 'jquery' ) , '', true);
		wp_enqueue_script( 'cake-jquery-scrollup', get_template_directory_uri() . '/js/jquery.scrollup.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'cake-jquery-classie', get_template_directory_uri() . '/js/classie.js', array( 'jquery' ), '', true );
		
		//loader js
		if($loader=='true'){
		wp_enqueue_script( 'cake-jquery-pathloader', get_template_directory_uri() . '/js/pathloader.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'cake-jquery-loader-effect', get_template_directory_uri() . '/js/loader-effect.js', array( 'jquery' ), '', true );
		}
		
		//owl-carousel js
		if (is_singular('portfolio') || is_page_template( 'page-templates/page_blog.php' ) || is_home() || is_single() || is_archive()) {
			wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery'), '', true );
		}
		
		//single portfolio js
		if(is_singular('portfolio')){
			wp_enqueue_script('cake-jquery-portfolio-single-setting', get_template_directory_uri() . '/js/portfolio.single.setting.js', array( 'jquery'), '', true );
		}
		
		//post format gallery carousel
		if ( is_page_template( 'page-templates/page_blog.php' ) || is_home() || is_single() || is_archive()) {
			wp_enqueue_script( 'cake-jquery-blog-gallery', get_template_directory_uri() . '/js/blog.gallery.js', array( 'jquery'), '', true  );
			
		}
		
		//header slideshow
		if($sliderchoose=='parallax-slider'){
			wp_enqueue_script( 'cake-jquery-parallax', get_template_directory_uri() . '/js/jquery.parallax.js', array( 'jquery'), '', true );
			wp_enqueue_script( 'cake-jquery-parallax.setting', get_template_directory_uri() . '/js/parallax.setting.js', array( 'jquery'), '', true);
		}elseif($sliderchoose=='slick-slider'){
			wp_enqueue_script( 'cdo-slick-slider' );
			wp_enqueue_script( 'cake-jquery-banner-slick-setting', get_template_directory_uri() . '/js/banner.slick.js', array( 'cdo-slick-slider'), '', true);
		}elseif($sliderchoose=='cycle-slider'){
			wp_enqueue_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', array( 'jquery'), '', true);
			wp_enqueue_script('cycle2-center', get_template_directory_uri() . '/js/jquery.cycle2.center.js', array( 'jquery'), '', true );
			wp_enqueue_script('cake-jquery-banner-cycle-setting', get_template_directory_uri() . '/js/banner.cycle.js', array( 'jquery'), '', true );
		}elseif($sliderchoose=='slice-slider'){
			wp_enqueue_script('cake-jquery-banner-slice-setting', get_template_directory_uri() . '/js/banner.slice.js', array( 'jquery'), '', true );
		}
		
		//wishlist
		if($wishlisticon=='true'){
		wp_enqueue_script('cake-jquery-wishlist', get_template_directory_uri() . '/js/wishlist.js', array( 'jquery' ), false, true );
		}
		
		if (cake_is_woocommerce_activated()) {
			
			wp_enqueue_script('cake-jquery-cart', get_template_directory_uri() . '/js/cart.js', array( 'jquery'), '', true);
			
			if(is_product()){

				wp_enqueue_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', array( 'jquery'), '', true);
				wp_enqueue_script('cycle2-carousel', get_template_directory_uri() . '/js/jquery.cycle2.carousel.min.js', array( 'jquery'), '', true);
				wp_enqueue_script('elevatezoom', get_template_directory_uri() . '/js/jquery.elevatezoom.js', array( 'jquery'), '', true);
				wp_enqueue_script('cake-jquery-product-setting', get_template_directory_uri() . '/js/product.setting.js', array( 'jquery'), '', true);
				
				
			}
		}
		
		wp_enqueue_script( 'cake-jquery-functions', get_template_directory_uri() . '/js/cake-functions.js', array( 'jquery'), '', true );
	  
	}
add_action( 'wp_enqueue_scripts', 'cake_add_javascripts' ); 
}

if (!function_exists('cake_deregister_script')):
	if (!is_admin()) {
		function cake_deregister_script() {
			wp_dequeue_script('cdo-waypoints');
			wp_dequeue_script('cdo-main');
			
		}
		add_action( 'init', 'cake_deregister_script');
	}
endif;

// Load admin scripts
if(!function_exists("cake_admin_scripts")){
function cake_admin_scripts() {
        wp_enqueue_script('cake-post-format', get_template_directory_uri() . '/js/post-formats.js', array('jquery'), '1.0.0', true);
}
add_action( 'admin_enqueue_scripts', 'cake_admin_scripts');
}
?>