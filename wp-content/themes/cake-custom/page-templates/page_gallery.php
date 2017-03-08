<?php
/**
 * Template Name: gallery
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

<?php 
$field_mappings = getCustomFormFieldMapping();
?>
<script type="text/javascript">
	var field_mappings = <?php echo json_encode($field_mappings)?>;
</script>

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
				var esgbox = $(this);
				imgBtnInterval = setInterval(function(){
					if ($('div.esgbox-title').length)
					{
						var mulCats = esgbox.closest('li').attr('class').split(' ');
						var selectedCat = '';
						$.each(mulCats, function(index, catVal){
							catVal = catVal.trim().replace('filter-', '');
							if (field_mappings['custom_order_cake_type']['value'][catVal]){
								selectedCat = catVal;
								return false;
							}
						});

						$('div.esgbox-title').append('<a href="<?php echo site_url()?>/order-made-form?type='+selectedCat+'" class="gallery_type_btn"><input class="cdo-button" type="button" value="<?php echo esc_html__('Buy this', 'cake')?>"></a>');
						clearInterval(imgBtnInterval);
						imgBtnInterval = null;
					}
				}, 10);
			});
		});
	</script> 
<?php get_footer(); ?>
