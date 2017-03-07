<?php
/**
 *Template Name: order-index
 * The template for displaying all pages.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	<?php $col = cake_sidebar_page_position();?>
	
	<?php get_template_part('inc/page-header');?>

	<div id="content-wrapper">
	<div id="omc-form">
	<div class="container">
		<div class="row" id="pinBoxContainer">
			
			
						<?php while ( have_posts() ) : the_post(); ?>
			
							<?php get_template_part( 'content', 'cakeform' ); ?>
							
										
						<?php endwhile; // end of the loop. ?>
			
				
			
			<?php if($col['position']){ ?>
			<div id="secondary" class="<?php echo esc_attr($col['colsidebar']);?> widget-area" role="complementary">
				<?php get_sidebar(); ?>
			</div><!-- #secondary -->
			<?php } ?>
			
			
		</div><!-- .row -->
	</div><!-- .container -->
	</div>
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>
