<?php
/**
 * Single Product Image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product, $bow_opts;

?>
<div class="product-images">

	<?php 
	
	$galthumbdisplay = get_theme_mod('cake_product_gallery_thumb', 'true');
	$galthumbcarousel = get_theme_mod('cake_product_gallery_thumb_carousel', '8000');
	$attachid = get_post_thumbnail_id($product->id);
	$attachment_prod_ids = $product->get_gallery_attachment_ids();
    ?>

	
	<div class="product-large-image images">
		
		<?php
		if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$zoomimgurl 	  = wp_get_attachment_image_src($attachid, 'full');
			$zoomimg		  = $zoomimgurl[0];
			
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
				'id'	 => 'bow-spimg',
				'data-zoom-image' => $zoomimg,
				'data-id' =>  get_post_thumbnail_id()
			) );
			
			
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $post->ID );
			
			$x = 0;
			
			foreach ( $attachment_prod_ids as $attachment_prod_id ) {
			
			$imgzoom 	  = wp_get_attachment_image_src($attachment_prod_id, 'full');
			$customclass = ($x == 0 ? 'thefirst' : '');
			
			
			echo '<a href="'. esc_url($imgzoom[0])  .'" class="zoomLink '.esc_attr($customclass).'" class="woocommerce-main-image zoom" title="'. get_the_title($attachment_prod_id) .'" data-rel="prettyPhoto' . $gallery . '" data-id="'.esc_attr($attachment_prod_id).'"><i class="fa fa-search"></i></a>';
			
			$x++;

			}
			
			
			
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'cake' ) ), $post->ID );
		}

		?>

	</div>

	<?php if($attachment_count > 0 && $galthumbdisplay=="true") {?>
	
	<?php
	
	if($attachment_count == 1){
		$getcarouselitem = "1";
		$dataallowwrap = "false";
	}elseif($attachment_count == 2){
		$getcarouselitem = "2";
		$dataallowwrap = "false";
	}elseif($attachment_count == 3){
		$getcarouselitem = "3";
		$dataallowwrap = "false";
	}else{
		
		$getcarouselitem = "4";
		$dataallowwrap = "true";
	}
	
	?>
	
	<div class="product-thumbnails">
	<?php $timeout = ($galthumbcarousel!="" ? $galthumbcarousel : 0);?>
	<div class="product-thumbnails-list cycle-slideshow" data-cycle-fx="carousel"  data-cycle-timeout=<?php echo esc_attr($timeout);?> data-cycle-carousel-visible=<?php echo esc_attr($getcarouselitem);?>
    data-cycle-carousel-fluid="true" data-cycle-slides="> div.product-thumbnails-item" data-cycle-prev=".cdo-cycle-prev" data-cycle-next=".cdo-cycle-next" data-allow-wrap=<?php echo esc_attr($dataallowwrap);?> carousel-fluid="true">
	
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>
	
	</div>
	
	<?php if($attachment_count > 4){?>
	<div class="cdo-cycle-prev"><i class="fa fa-angle-left"></i></div>
    <div class="cdo-cycle-next"><i class="fa fa-angle-right"></i></div>
	<?php } ?>
	
	</div>
	
	<?php } ?>
</div>