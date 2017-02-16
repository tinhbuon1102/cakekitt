<?php
/**
 * The template for displaying image attachments.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header();
?>
	<div id="content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="primary" class="content-area image-attachment">
					<main id="main" class="site-main">
			
					<?php while ( have_posts() ) : the_post(); ?>
			
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
							
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
								<?php 
									// Show breadcrumb navigation
									if(function_exists('bcn_display')){
									$breadcrumb = bcn_display_list(true);
									printf( '<ul class="breadcrumb">%s</ul>' , $breadcrumb);
									}
								?>
			
								
			
								<nav role="navigation" id="image-navigation" class="image-navigation">
									<div class="nav-previous"><?php previous_image_link( false, wp_kses(__( '<span class="meta-nav"><i class="fa fa-chevron-left"></i></span> Previous', 'cake' ),array('span' => array(),'i' => array()))); ?></div>
									<div class="nav-next"><?php next_image_link( false, wp_kses(__( 'Next <span class="meta-nav"><i class="fa fa-chevron-right"></i></span>', 'cake' ),array('span' => array(),'i' => array()))); ?></div>
								</nav><!-- #image-navigation -->
								<br/>
							</header><!-- .entry-header -->
			
							<div class="entry-content">
								<div class="entry-attachment">
									<div class="attachment">
										<?php cake_the_attached_image(); ?>
									</div><!-- .attachment -->
									
									<div class="entry-meta">
									<?php
										$metadata = wp_get_attachment_metadata();
										if ( $metadata ) {
											printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
												esc_html_x( 'Full size', 'Used before full size attachment link.', 'cake' ),
												esc_url( wp_get_attachment_url() ),
												absint( $metadata['width'] ),
												absint( $metadata['height'] )
											);
										}
									?>
									</div><!-- .entry-meta -->
			
									<?php if ( has_excerpt() ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div><!-- .entry-caption -->
									<?php endif; ?>
								</div><!-- .entry-attachment -->
			
								<?php
									the_content();
									wp_link_pages( array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cake' ),
										'after'  => '</div>',
									) );
								?>
							</div><!-- .entry-content -->
			
						</article><!-- #post-## -->
			
						<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || '0' != get_comments_number() )
								comments_template();
						?>
			
					<?php endwhile; // end of the loop. ?>
			
					</main><!-- #main -->
				</div><!-- #primary -->
			</div><!-- .col-md-12 -->
		</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>