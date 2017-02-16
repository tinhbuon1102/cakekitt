<?php
/**
 * The template for displaying 404 pages (Not Found).
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
$page_not_found_text = get_theme_mod('cake_404_text');
get_header(); ?>

	<?php get_template_part('inc/page-title'); ?>

	<div id="content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="primary" class="content-area">
					<main id="main" class="site-main">
			
						<section class="error-404 not-found">
							
							<div class="page-content">
								<h3 class="text-center mar-top-20 notfound-title">4<span class="nol">&nbsp;</span>4</h3>
								
								<p class="not_found_text"><?php echo esc_attr($page_not_found_text) ? esc_attr($page_not_found_text) : esc_html__('It looks like nothing was found at this location.', 'cake' ); ?></p>

			
							</div><!-- .page-content -->
						</section><!-- .error-404 -->
			
					</main><!-- #main -->
				</div><!-- #primary -->
			</div><!-- .col-md-12 -->
		</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>