<?php
/**
 * Single Product Thumbnails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();
$attachment_count = count( $product->get_gallery_attachment_ids() );

if ( $attachment_ids ) {
	$loop 		= 0;
	//$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<?php
	

		foreach ( $attachment_ids as $attachment_id ) {
			
			if($attachment_count == 1){
				$colclass = "col1";
			}elseif($attachment_count == 2){
				
				$colclass = "col2";
			}elseif($attachment_count == 3){
				
				$colclass = "col3";
			}else{
				
				$colclass = "col4";
			}

			$classes = array( 'product-thumbnails-item', $colclass);


			if ( $loop == 0)
				$classes[] = 'active';

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link )
				continue;

			$image_title 	= esc_attr( get_the_title( $attachment_id ) );
			$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

			$image       = wp_get_attachment_image( $attachment_id, 'shop_thumbnail');
			
			$image_url = wp_get_attachment_image_src($attachment_id, 'full'); 
			$image_url = $image_url[0];
			$image_url2 = wp_get_attachment_image_src($attachment_id, 'shop_single'); 
			$image_url2 = $image_url2[0];

			$image_class = esc_attr( implode( ' ', $classes ) );
			

			
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="%s" data-image="%s" data-zoom-image="%s" data-id="'.$attachment_id.'">%s</div>', $image_class, $image_url, $image_url2, $image ), $attachment_id, $post->ID, $image_class );

			$loop++;
		}

	?>
	<?php
}