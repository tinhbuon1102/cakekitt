<?php
/**
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
	<div class="container">
		<div class="row">
		<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
		
			<div class="filter_opt">
				<?php
					$terms = get_terms( array(
						'taxonomy' => 'cakegal_taxonomy',
						'hide_empty' => false,
					) );
					if(!empty($terms)){
					?>
					<select name="cakegal_cat" class="gal_cat" style="width:30%;float:right;margin:10px 5px">
						<option value="">Select Category</option>
					<?php
						foreach($terms as $term){
					?>
						<option data-fid="<?php echo $term->term_id;?>" data-filter="filter-<?php echo $term->slug;?>" value="<?php echo $term->slug;?>"><?php echo $term->name;?></option>
					<?php
						}
					?>
					</select> 
					<?php
					}
					$field_id = 'field_58c8df4c9c53d';
					$color_type = get_field_object($field_id);
					if(!empty($color_type['choices'])){
						?>
						<select name="cakegal_color_type" class="gal_color_type" style="width:30%;float:right;margin:10px 5px">
							<option value="">Select Color</option>
						<?php
							foreach($color_type['choices'] as $key => $val){
						?>
							<option data-filter="filter-<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $val;?></option>
						<?php
							}
						?>
						</select> 
						<?php
					}
					$field_id = 'field_58c94f4841353';
					$scene = get_field_object($field_id);
					if(!empty($scene['choices'])){
						?>
						<select name="cakegal_scene" class="gal_scene" style="width:30%;float:right;margin:10px 5px">
							<option value="">Select Scene</option>
						<?php
							foreach($scene['choices'] as $key => $val){
						?>
							<option data-filter="filter-<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $val;?></option>
						<?php
							}
						?>
						</select> 
						<?php
					}
				?>
			</div>
			<script type="text/javascript">
				
				jQuery('document').ready(function($){
				
					$(document).ajaxStart(function(){
						$("#wait").css("display", "block");
					});

					$(document).ajaxComplete(function(){
						$("#wait").css("display", "none");
					}); 
				
					$('body').on('change', '.gal_cat,.gal_color_type,.gal_scene', function() {
						var search_terms = {};
						$('.gal_itms li').css('display','none');
						$('.filter_opt select').each(function(i,e){
							if($("option:selected", this).val().length > 0){
								var si_cls = $(this).attr('class');
								var si_val = $("option:selected", this).val();
								search_terms[si_cls]= si_val;
							}
						});
						var searchtrm = JSON.stringify(search_terms);
						var data = {
							action: 'load_items',
							searchtrm: searchtrm
						};
						$.post(jscon.ajaxurl, data, function(msg) {
							if(msg.output.length > 0){
								var out = msg.output;
								$('.gal_itms').html(out);
							}
						}, 'json');
					});
				});
			</script>
			
			
				<?php
				$args = array (
					'post_type' => 'cakegal',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'orderby' => 'ID',
					'order' => 'DESC'
				);
				$cakegal = new WP_Query($args);
				if($cakegal->have_posts()):
				?>
				<div class="gallery">
					<ul class="gal_itms">
						<div id="wait"></div>
						<?php
						while($cakegal->have_posts()) : $cakegal->the_post();
						global $post;
						$color_type = get_field('color-type',$post->ID);
						$scene = get_field('scene',$post->ID);
						$term_list = get_the_terms($post, 'cakegal_taxonomy');
						if(!empty($term_list)){
							$tma = array();
							foreach($term_list as $term){
								$tma[] = $term->slug;
							}
						}
						
						// $thumbnail_url = get_the_post_thumbnail_url($post);
						// echo '<pre>';
						// print_r($thumbnail_url);
						// $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false );
						// echo $src[0];
						?>
							<li data-gal_color_type="<?php if(!empty($color_type)){ echo trim(implode(',',$color_type),',');}?>" data-gal_scene="<?php if(!empty($scene)){ echo implode(',',$scene);}?>" data-gal_cat="<?php if( isset($tma) && is_array($tma) && !empty($tma)){ echo implode(',',$tma);}?>">
								<a href="#popUp<?php echo $post->ID;?>">
									<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>">
									<span class="zoomBtn">&nbsp;</span>
								</a>
							</li>
						<?php endwhile;wp_reset_postdata();?>
					</ul>
				</div>
				<?php endif;?>
				
				<?php
				$args = array (
					'post_type' => 'cakegal',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'orderby' => 'ID',
					'order' => 'DESC'
				);
				$cakegal = new WP_Query($args);
				if($cakegal->have_posts()):
				while($cakegal->have_posts()) : $cakegal->the_post();
				global $post;
				$custom_order_cakesize_round = get_field('custom_order_cakesize_round',$post->ID);
				$est_price = get_field('est-price',$post->ID);
				$term_list = get_the_terms($post, 'cakegal_taxonomy');
<<<<<<< HEAD
				$scene = get_field('scene',$post->ID);
=======
>>>>>>> faa58c0020c734ac18f93ffed22188fcb8ed774f
				if(!empty($term_list)){
					$trm_name = array();
					$trm_slug = array();
					foreach($term_list as $term){
						$trm_name[] = $term->name;
						$trm_slug[] = $term->slug;
					}
				}
				?>
<<<<<<< HEAD
				
				
			<div class="clear"></div>
				<div id="popUp<?php echo $post->ID;?>" class="popUp">
	
					<div class="galBox">
						<div class="galcon-inner">
					    <div class="row">
					    <div class="image-outer">
						<div class="image-inner">
							<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>" class="esgbox-image">
						</div>
						</div>
						<div class="gal-content-inside-wrap">
						<div class="meta-info">
							<ul class="ck-info">
								<li><label>Category</label><span class="value"><?php if( isset($trm_name) && is_array($trm_name) && !empty($trm_name)){ echo implode(',',$trm_name);}?></span></li>
								<li><label>Size | </label><span class="value size-value"><?php echo $custom_order_cakesize_round;?></span></li>
								<li><label>Price | </label><span class="value price-value">¥<?php echo $est_price;?></span></li>
								<li><label>Scene  | </label><span class="value price-value"><?php if(!empty($scene)){ echo implode(',',$scene);}?></span></li>
=======
				<div id="popUp<?php echo $post->ID;?>" class="popUp">
					<div class="galBox">
						<div class="galBoxImg">
							<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>">
						</div>
						<div class="galBoxTxt">
							<ul>
								<li><label>Category</label><span class="value"><?php if( isset($trm_name) && is_array($trm_name) && !empty($trm_name)){ echo implode(',',$trm_name);}?></span></li>
								<li><label>Size | </label><span class="value size-value"><?php echo $custom_order_cakesize_round;?></span></li>
								<li><label>Price | </label><span class="value price-value">¥<?php echo $est_price;?></span></li>
>>>>>>> faa58c0020c734ac18f93ffed22188fcb8ed774f
							</ul>
							<a class="gallery_type_btn" href="http://kitt-sweets.jp/order-made-form?type=<?php if( isset($trm_slug) && is_array($trm_slug) && !empty($trm_slug)){ echo implode(',',$trm_slug);}?>&post_id=<?php echo $post->ID;?>">
								<input class="cdo-button" value="このケーキを参考に注文する" type="button">
							</a>
						</div>
<<<<<<< HEAD
						</div>
						
						</div>
						</div>
=======
>>>>>>> faa58c0020c734ac18f93ffed22188fcb8ed774f
					</div>
				</div>
				<?php endwhile;wp_reset_postdata();?>
				<?php endif;?>
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
