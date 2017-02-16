<?php
/**
 * The template for displaying Search Results pages.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	<?php $col = cake_sidebar_blog_position();?>
	
	<?php get_template_part('inc/page-title'); ?>
	
	<div id="content-wrapper">
	<div class="container">
		<div class="row">
		
			<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
				<main id="main" class="site-main">
		
				<?php if ( have_posts() ) : ?>
		
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
		
						<?php get_template_part( 'content', 'search' ); ?>
		
					<?php endwhile; ?>
		
					<?php if (  $wp_query->max_num_pages > 1 ) : ?>
						<?php if(function_exists('wp_pagenavi')) {  ?>
						
							<?php wp_pagenavi(); ?>
						 
						 <?php }else{ ?>
						
							<?php cake_content_nav('nav-below'); ?>
						
						<?php } ?>
					
					<?php endif; //end navigation ?>
		
				<?php else : ?>
		
					<?php get_template_part( 'no-results', 'search' ); ?>
		
				<?php endif; ?>
		
				</main><!-- #main -->
			</div><!-- #primary -->

			
			<?php if($col['position']){ ?>
			<div id="secondary" class="col-sm-4 widget-area" role="complementary">
				<?php get_sidebar(); ?>
			</div><!-- #secondary -->
			<?php } ?>
			
		</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>