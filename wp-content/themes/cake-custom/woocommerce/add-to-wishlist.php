<?php
/**
 * Add to wishlist template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product;
?>

<div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo $product_id ?>">
	<?php if( ! ( $disable_wishlist && ! is_user_logged_in() ) ): ?>
	    <div class="yith-wcwl-add-button <?php echo ( $exists && ! $available_multi_wishlist ) ? 'hide': 'show' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'none': 'block' ?>">

	        <?php yith_wcwl_get_template( 'add-to-wishlist-' . $template_part . '.php', $atts ); ?>

	    </div>
	    
<!--after added-->
	    <div class="yith-wcwl-wishlistaddedbrowse mark1 hide" style="display:none;">
	        
	        <!--<a href="<?php //echo esc_url( $wishlist_url )?>" rel="nofollow">
	            <?php //echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text )?>
	        </a>-->
	        <a class="remove_from_wishlist_custom round_button alt btn-style-border-blk gray" href="#" rel="nofollow" data-product-id="<?php echo $product_id ?>">
				<?php if (!is_product()) echo '<span>';?>
        <?php _e( 'お気に入りから外す', 'yith-wcwl' ) ?>
        <?php if (!is_product()) echo '</span>';?>
	        </a>
	    </div>
	    

	    <div class="yith-wcwl-wishlistexistsbrowse mark2 browsediv <?php echo ( $exists && ! $available_multi_wishlist && is_product() ) ? 'show' : 'hide' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist && is_product()) ? 'block' : 'none' ?>">
	        
	       <!--<a href="<?php //echo esc_url( $wishlist_url ) ?>" rel="nofollow">
	            <?php //echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text )?>
	        </a>-->
	      <a class="remove_from_wishlist_custom round_button alt btn-style-border-blk gray" href="#" rel="nofollow" data-product-id="<?php echo $product_id ?>">
	            <?php if (!is_product()) echo '<span>';?>
        <?php _e( 'お気に入りから外す', 'yith-wcwl' ) ?>
        <?php if (!is_product()) echo '</span>';?>
	        </a>
	    </div>
	    <!--/after added-->
	    
	    <!--remove button-->
	    <div class="yith-wcwl-wishlistexistsbrowse removebuttondiv hide mark1" style="display:none;">
	        <a class="remove_from_wishlist_custom button alt btn-style-border-blk gray" href="#" rel="nofollow" data-product-id="<?php echo $product_id ?>">
	        	<?php if (!is_product()) echo '<span>';?>
	            <?php _e( 'お気に入りから外す', 'yith-wcwl' ) ?>
	            <?php if (!is_product()) echo '</span>';?>
	        </a>
		    <img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
	    </div>

	    <div class="yith-wcwl-wishlistexistsbrowse removebuttondiv <?php echo ( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'block' : 'none' ?> mark2">
	        <a class="remove_from_wishlist_custom round_button alt btn-style-border-blk gray" href="#" rel="nofollow" data-product-id="<?php echo $product_id ?>">
	        <?php if (!is_product()) echo '<span>';?>
		        <?php _e( 'お気に入りから外す', 'yith-wcwl' ) ?>
		    <?php if (!is_product()) echo '</span>';?>
	        </a>
		    <img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
	    </div>
	    
<!--/remove button-->
	    <div style="clear:both"></div>
	    <div class="yith-wcwl-wishlistaddresponse"></div>
	<?php else: ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'wishlist_notice' => 'true', 'add_to_wishlist' => $product_id ), get_permalink( wc_get_page_id( 'myaccount' ) ) ) )?>" rel="nofollow" class="<?php echo str_replace( 'add_to_wishlist', '', $link_classes ) ?>" >
			<?php echo $icon ?>
			<?php echo $label ?>
		</a>
	<?php endif; ?>

</div>

<div class="clear"></div>