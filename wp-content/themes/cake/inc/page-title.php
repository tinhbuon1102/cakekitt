<?php
$blog_title = get_theme_mod('cake_blog_title', esc_html__('Blog', 'cake'));

global $post;
$getpid = cake_get_postid();
$theID = ( isset( $post->ID ) ? $getpid : "" );

$bgdefault = get_theme_mod('cake_page_header_img', '');
$bg = wp_get_attachment_image_src( get_post_meta( $theID, 'cake_page_header_img_id', 1 ), 'full' );
$jptitle = get_field('title_ja');
$headtext = get_post_meta($theID, 'cake_page_header_text', true);
$htxtpositionmeta = get_post_meta($theID, 'cake_page_bgtext_position', true);
if($htxtpositionmeta=="leftfloat"){
				$htextposition ='floatleft';
			}elseif($htxtpositionmeta=="rightfloat"){
				$htextposition ='floatright';
			}else{		
				$htextposition ='';	
			}
?>


		<header class="page-header <?php if (  ( $bg ) ) { ?>has-banner<?php } elseif (  ( $bgdefault) ) { ?>default-banner<?php } else { ?>no-banner<?php } ?> <?php if (  ( $headtext ) ) { ?>wider-height<?php } else { ?><?php } ?>">
		
			<div class="container">
			<div class="abs-container">
			<div class="tittle-sub-top <?php if (  ( $headtext ) ) { ?>has-description<?php } else { ?><?php } ?>">
              
            <?php if (  ( $headtext ) ) { ?>
            <?php
				// Show breadcrumb navigation
				if(function_exists('bcn_display')){
				$breadcrumb = bcn_display_list(true);
				printf( '<ul class="breadcrumb">%s</ul>' , $breadcrumb);
				}
			?>
           <div class="title-wrapper <?php echo $htextposition; ?>">
            <h1 class="page-title">
			
			<?php 
				
				if(is_singular('portfolio') || is_singular('team') || is_singular('testimonial')) {
					echo get_the_title(); 
				}elseif(is_search()){
					printf( esc_html__( 'Search Results for: %s', 'cake' ), esc_attr(get_search_query()) );
				}elseif ( function_exists('is_woocommerce') && is_woocommerce() ) {
					woocommerce_page_title($echo = true);
				}elseif (is_single() || is_home()) {
					echo esc_html($blog_title);
				}elseif (is_archive()) {
					
					if ( is_category() ) :
						single_cat_title();

					elseif ( is_tag() ) :
						single_tag_title();

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( esc_html__( 'Author: %s', 'cake' ), '<span class="vcard">' . get_the_author() . '</span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();

					elseif ( is_day() ) :
						printf( esc_html__( 'Day: %s', 'cake' ), get_the_date());

					elseif ( is_month() ) :
						printf( esc_html__( 'Month: %s', 'cake' ), get_the_date( 'F Y' ));

					elseif ( is_year() ) :
						printf( esc_html__( 'Year: %s', 'cake' ), get_the_date( 'Y' ));

					elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
						esc_html_e( 'Images', 'cake');

					elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
						esc_html_e( 'Videos', 'cake' );

					elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
						esc_html_e( 'Quotes', 'cake' );

					elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
						esc_html_e( 'Links', 'cake' );

					else :
						esc_html_e( 'Archives', 'cake' );

					endif;
					
				}else{
					
					the_title();
				}

			?>
			</h1>
			<p class="ja-subtitle jp"><?php echo $jptitle; ?></p>
			<div class="page-description">
				<?php printf($headtext); ?>
			</div>
			</div>
			
            <?php } else { ?>
			<h1 class="page-title">
			
			<?php 
				
				if(is_singular('portfolio') || is_singular('team') || is_singular('testimonial')) {
					echo get_the_title(); 
				}elseif(is_search()){
					printf( esc_html__( 'Search Results for: %s', 'cake' ), esc_attr(get_search_query()) );
				}elseif ( function_exists('is_woocommerce') && is_woocommerce() ) {
					woocommerce_page_title($echo = true);
				}elseif (is_single() || is_home()) {
					echo esc_html($blog_title);
				}elseif (is_archive()) {
					
					if ( is_category() ) :
						single_cat_title();

					elseif ( is_tag() ) :
						single_tag_title();

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( esc_html__( 'Author: %s', 'cake' ), '<span class="vcard">' . get_the_author() . '</span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();

					elseif ( is_day() ) :
						printf( esc_html__( 'Day: %s', 'cake' ), get_the_date());

					elseif ( is_month() ) :
						printf( esc_html__( 'Month: %s', 'cake' ), get_the_date( 'F Y' ));

					elseif ( is_year() ) :
						printf( esc_html__( 'Year: %s', 'cake' ), get_the_date( 'Y' ));

					elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
						esc_html_e( 'Images', 'cake');

					elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
						esc_html_e( 'Videos', 'cake' );

					elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
						esc_html_e( 'Quotes', 'cake' );

					elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
						esc_html_e( 'Links', 'cake' );

					else :
						esc_html_e( 'Archives', 'cake' );

					endif;
					
				}else{
					
					the_title();
				}

			?>
			</h1>
			
			<?php
				// Show breadcrumb navigation
				if(function_exists('bcn_display')){
				$breadcrumb = bcn_display_list(true);
				printf( '<ul class="breadcrumb">%s</ul>' , $breadcrumb);
				}
			?>
			<?php } ?>
			
			</div>
			</div>
			</div>
			
		</header><!-- .page-header -->
		<?php if (  ( $bg ) ) { ?>
		<?php } elseif (  ( $bgdefault) ) { ?>
		<div class="page-header-border></div>
		<?php } else { ?>
		<div class="page-header-border></div>
		<?php } ?>
