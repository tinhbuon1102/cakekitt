<?php
/**
 * Template Name: gallery
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	<?php $col = cake_sidebar_page_position();?>
	
	<?php get_template_part('inc/page-header');?>

	<div id="content-wrapper">
	<div class="container">
		<div class="row">
			
			<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
					<main id="main" class="site-main">
			
						<?php while ( have_posts() ) : the_post(); ?>
			
							<?php get_template_part( 'content', 'page' ); ?>
							
										
						<?php endwhile; // end of the loop. ?>
			
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
	<script type="text/javascript">
		var imgBtnInterval = null;
		jQuery(function($){
			$('body').on('click', '.esgbox', function() {
				imgBtnInterval = setInterval(function(){
					if ($('div.esgbox-title').length)
					{
						$('div.esgbox-title').append('<a href="http://google.com" class="button gallery_type_btn">Go</a>');
						clearInterval(imgBtnInterval);
						imgBtnInterval = null;
					}
				}, 100);
			});
		});
	</script> 
<?php get_footer(); ?>
