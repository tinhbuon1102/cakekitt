<?php
/**
 * Template Name: 100% Width
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header();?>

	
	<?php get_template_part('inc/page-header');	?>
	<?php
$page = get_post( get_the_ID() );
// 現在表示しているページの投稿IDから投稿情報を取得

$slug = $page->post_name;
// 投稿のスラッグを取得
?>

	<div id="content-wrapper" class="page-<?php echo $slug; ?>">
	<div class="container expand">
	
			<div id="primary" class="content-area">
					<main id="main" class="site-main">
			
						<?php while ( have_posts() ) : the_post(); ?>
			
							<?php get_template_part( 'content', 'page' ); ?>
							
										
						<?php endwhile; // end of the loop. ?>
			
				</main><!-- #main -->
			</div><!-- #primary -->
			
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>
