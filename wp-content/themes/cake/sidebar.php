<?php
/**
 * The Sidebar containing the main widget areas.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
?>
	
<?php
	global $post;
	$getpid = cake_get_postid();
	$theID = ( isset( $post->ID ) ? $getpid : "" );

	$getsidebars = get_post_meta($theID,"cake_page_sidebar_widget",true);
	
	if(!empty($getsidebars)) { 
		dynamic_sidebar( $getsidebars );
	}elseif(function_exists('is_woocommerce') && is_woocommerce()){
		dynamic_sidebar($getsidebars);
	}else{
		if (is_active_sidebar( 'cake-post-sidebar' )) { 
			dynamic_sidebar( 'cake-post-sidebar' ); 
		}		
	}
?>