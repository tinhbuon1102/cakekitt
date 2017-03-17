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
			/*var dfimage = $('img.esgbox-image');
			dfimage.on('load',function(){
							var img = new Image();
							img.src = dfimage.attr('src');
							var imgWidth = img.width;
							var imgHeight = img.height;
							var aspectRatio = imgWidth / imgHeight;
							$('div.esgbox-skin').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
							if(aspectRatio >= 1){
								//横長画像の場合 divのheightに数値を合わせる
								$('div.galcon-inner').addClass('landscape');
							}else{
								//縦長画像の場合 divのwidthに数値を合わせる
								$('div.galcon-inner').addClass('portlait');
								//上下中央揃えにする場合は下記2行も
								//var i = (imgHeight-200)/2  //はみ出た部分を計算して÷2し、ネガティブマージンをつける
								//$(this).find('img').css('margin-top', '-'+i+'px');
							}
			
			});*/
			$('body').on('click', '.esgbox', function() {
				var esgbox = $(this);
				var selectedPost = esgbox.closest('li').attr('id').replace(/^eg-\d-post-id-/, '');
				
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
						$(".esgbox-wrap").addClass("kitt-wrap");
						$('.esgbox-skin > div, .esgbox-skin > a.esgbox-item.esgbox-close').wrapAll('<div class="galcon-inner"></div>');
						$('div.galcon-inner').wrapInner('<div class="row"></div>');
						//$('div.galcon-inner > .row > div').addClass('col-md-6');
						var dfimage = $('img.esgbox-image');
						dfimage.on('load',function(){
							var img = new Image();
							img.src = dfimage.attr('src');
							var imgWidth = img.width;
							var imgHeight = img.height;
							var aspectRatio = imgWidth / imgHeight;
							$('div.esgbox-skin').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
							$('div.galcon-inner > .row > div').addClass((aspectRatio < 1) ? 'col-md-6' : 'col-md-12');
							$(window).on('load resize', function(){
								//height値を取得する
								var hsize = $(window).height();
								var hinsize = hsize - 150;
								var galconWidth = $('.ordercake-cart-sidebar-container').width();
								if (imgHeight <= hsize) {
									$(".kitt-wrap .esgbox-skin .galcon-inner .image-inner").css("height", imgHeight + "px");
									$(".kitt-wrap .esgbox-skin .galcon-inner .image-inner").css("width", imgWidth + "px");
									$(".kitt-wrap .esgbox-skin").css("width", imgWidth + 50 + "px");
									$('.esgbox-wrap').addClass('nofullbox');
									//$(".kitt-wrap .esgbox-skin .galcon-inner").css("height", hsize - 100 + "px");
								} else if (imgHeight >= hsize) {
									$('.esgbox-wrap').addClass('fullbox');
									$(".kitt-wrap .esgbox-skin .galcon-inner .image-inner").css("height", hinsize + "px");
									$(".kitt-wrap .esgbox-skin .galcon-inner .image-inner").css("width", hinsize * aspectRatio + "px");
									$(".kitt-wrap > .esgbox-skin > .galcon-inner").css("height", hsize - 100 + "px");
								}
							});
						});
						
						$('.esgbox-inner > img.esgbox-image').unwrap();
						$('img.esgbox-image').wrapAll('<div class="image-inner"></div>');
						//$('div.esgbox-skin').addClass((aspectRatio < 1) ? 'portrait' : 'landscape');
						$('div.esgbox-title').append('<div class="meta-info"><ul class="ck-info"><li><label>Category</label><span class="value">デコレーションケーキ</span></li><li><label>Size</label><span class="value">5号/1段</span></li><li><label>Price</label><span class="value">25,000</span></li></ul></div>');

						$('div.esgbox-title').append('<a href="<?php echo site_url()?>/order-made-form?type='+selectedCat+'&post_id='+selectedPost+'" class="gallery_type_btn"><input class="cdo-button" type="button" value="<?php echo esc_html__('Buy this', 'cake')?>"></a>');
						clearInterval(imgBtnInterval);
						imgBtnInterval = null;
						$('img.esgbox-image').resize();
					}
				}, 10);
			});
			
			
		});
	</script> 
<?php get_footer(); ?>
