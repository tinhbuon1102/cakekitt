<?php
/**
 * Template Name: Page - Blog
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	<?php $col = cake_sidebar_page_position();?>
	
	<?php get_template_part('inc/page-header');	?>

	<div id="content-wrapper">
	<div class="container">
		<div class="row">
		
			<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
				<main id="main" class="site-main">
				
					<?php
					global $paged, $sticky, $wp_query;
					$getpostid = cake_get_postid();
					$theID = ( isset( $post->ID ) ? $getpostid : "" );
					
					$category = get_post_meta( $theID, 'cake_blog_taxonomy', true );
					$showpost = get_post_meta( $theID, 'cake_blog_showpost', true );
					
					if($category!=""){
						$getcat = implode(',',$category);
					}else{
						$getcat = '';	
					}
					
					$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
					$sticky = get_option( 'sticky_posts' );
					
														
					$thepostsarg = array(
						'post_type' => array('post'),
						'posts_per_page' =>$showpost,
						'category_name'  => $getcat,
						'paged' => $paged
					);

					query_posts($thepostsarg);
					
					?>
					
					<?php if (have_posts() ) : ?>
		
					<?php while (have_posts() ) : the_post(); ?>
		
						<?php get_template_part('content', get_post_format()); ?>
		
					<?php endwhile; // end of the loop. ?>
					
					
					<?php if (  $wp_query->max_num_pages > 1 ) : ?>
						<?php if(function_exists('wp_pagenavi')) {  ?>
						
							<?php wp_pagenavi(); ?>
						 
						 <?php }else{ ?>
						
							<?php cake_content_nav('nav-below'); ?>
						
						<?php } ?>
					
					<?php endif; //end navigation ?>
					
					
					<?php wp_reset_query();	?>
					
					<?php else : ?>
					
						<?php get_template_part( 'no-results', 'index' ); ?>
					
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
