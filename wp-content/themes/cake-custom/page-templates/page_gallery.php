<?php
/**
 * Template Name: gallery
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	
	<?php get_template_part('inc/page-header-original');	?>

	<div id="content-wrapper" class="page-original">
	<div class="container expand">
	
			<div id="primary" class="content-area">
					<main id="main" class="site-main">
					<?php query_posts('post_type=cakegal'); ?>
<div class="container">
<?php if(have_posts()): while(have_posts()): the_post(); ?>

<div id="js-grid-mosaic-flat" class="cbp cbp-l-grid-mosaic-flat">

<?php get_template_part( 'content', 'gallery' ); ?>

</div>
<?php endwhile; endif; ?>
						</div>
			
						<?php //while ( have_posts() ) : the_post(); ?>
			
							<?php //get_template_part( 'content', 'gallery' ); ?>
							
										
						<?php //endwhile; // end of the loop. ?>
			
				</main><!-- #main -->
			</div><!-- #primary -->
			
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>
