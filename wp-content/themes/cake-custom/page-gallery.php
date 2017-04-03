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
		<div class="gal-action-rows">
		<div class="row">
			<div class="filter_opt fl_col">
				<?php
					$terms = get_terms( array(
						'taxonomy' => 'cakegal_taxonomy',
						'hide_empty' => false,
					) );
					if(!empty($terms)){
					?>
					<div class="dropdown-select">
					<select name="cakegal_cat" class="gal_cat">
						<option value=""><?php echo __('Select cake type', 'cake')?></option>
					<?php
						foreach($terms as $term){
					?>
						<option data-fid="<?php echo $term->term_id;?>" data-filter="filter-<?php echo $term->slug;?>" value="<?php echo $term->slug;?>"><?php echo $term->name;?></option>
					<?php
						}
					?>
					</select> 
					</div>
					<?php
					}
					$field_id = 'field_58c8df4c9c53d';
					$color_type = get_field_object($field_id);
					if(!empty($color_type['choices'])){
						?>
						<div class="dropdown-select">
						
						<select name="cakegal_color_type" class="gal_color_type">
							<option value=""><?php echo __('Select Color', 'cake')?></option>
						<?php
							foreach($color_type['choices'] as $key => $val){
						?>
							<option data-filter="filter-<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $val;?></option>
						<?php
							}
						?>
						</select> 
						</div>
						<?php
					}
					$field_id = 'field_58c94f4841353';
					$scene = get_field_object($field_id);
					if(!empty($scene['choices'])){
						?>
						<div class="dropdown-select">
						<select name="cakegal_scene" class="gal_scene"">
							<option value=""><?php echo __('Select Scene', 'cake')?></option>
						<?php
							foreach($scene['choices'] as $key => $val){
						?>
							<option data-filter="filter-<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $val;?></option>
						<?php
							}
						?>
						</select> </div>
						<?php
					}
				?>
			</div><!--/filter_opt-->
			<div class="order-link  fr_col">
				<a href="<?php bloginfo('url') ?>/order-made-form" class="btn order-link-btn"><i class="linericon-chevron-right-circle"></i>オーダーメイドケーキのご注文はコチラ</a>
			</div>
			</div>
			</div><!--/gal-action-rows-->
			
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
				
				<div id="grid-gallery" class="grid-gallery gallery">
					<ul class="gal_itms grid">
						<div id="wait"></div>
						<li class="grid-sizer"></li><!-- for Masonry column width -->
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
							<figure>
								<a href="#popUp<?php echo $post->ID;?>">
									<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>">
									<span class="zoomBtn"><i class="fa fa-search"></i></span>
								</a>
								</figure>
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
				$scene = get_field('scene',$post->ID);
				if(!empty($term_list)){
					$trm_name = array();
					$trm_slug = array();
					foreach($term_list as $term){
						$trm_name[] = $term->name;
						$trm_slug[] = $term->slug;
					}
				}
				?>
				
				<div id="popUp<?php echo $post->ID;?>" class="popUp">
	
					<div class="galBox">
						<div class="galcon-inner">
					    
					    <div class="image-outer">
					    <div class="image-middler">
						<div class="image-inner">
							<img src="<?php the_post_thumbnail_url('full');?>" alt="<?php the_title();?>" class="lightbox-image">
						</div>
						</div>
						</div>
						<div class="gal-content-inside-wrap">
						<div class="meta-info">
							<ul class="ck-info">
								<li><label>Category</label><span class="value"><?php if( isset($trm_name) && is_array($trm_name) && !empty($trm_name)){ echo implode(',',$trm_name);}?></span></li>
								<li><label>Size</label><span class="value size-value"><?php echo $custom_order_cakesize_round;?></span></li>
								<li><label>Price</label><span class="value price-value">¥<?php echo $est_price;?></span></li>
								<li><label>Scene</label><span class="value price-value"><?php if(!empty($scene)){ echo implode(',',$scene);}?></span></li>
							</ul>
							<a class="gallery_type_btn" href="http://kitt-sweets.jp/order-made-form?type=<?php if( isset($trm_slug) && is_array($trm_slug) && !empty($trm_slug)){ echo implode(',',$trm_slug);}?>&post_id=<?php echo $post->ID;?>">
								<input class="cdo-button" value="このケーキを参考に注文する" type="button">
							</a>
						</div>
						</div>
						
						
						</div>
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
