<?php
/**
 * The template for displaying shop pages.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>


	<?php 
	$col = cake_sidebar_page_position(); 
	?>
	
	<?php get_template_part('inc/page-header');	?>

	<div id="content-wrapper" class="cake-woo-page">
	<div class="container">
		<div class="row">
						
		
			<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
				<main id="main" class="site-main" role="main">
					
					 <?php woocommerce_content(); ?>
		
				</main><!-- #main -->
			</div><!-- #primary -->
			
			<?php if($col['position']){ ?>			
			<div id="secondary" class="<?php echo esc_attr($col['colsidebar']);?> widget-area" role="complementary">
					<?php get_sidebar(); ?>
			</div><!-- #secondary -->
			<?php } ?>

			
		</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>