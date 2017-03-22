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
							}else if(msg.output == null){
								$('.gal_itms').html('<p>Nothings Found!</p>');
							}
						}, 'json');
					});
				});
			</script>
			
			<div id="primary" class="<?php echo esc_attr($col['colclass']); ?> content-area" style="<?php echo esc_attr($col['position']);?>">
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
				<div>
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
					// echo '<pre>';
					// print_r($term_list);
					
					?>
						<li data-gal_color_type="<?php if(!empty($color_type)){ echo trim(implode(',',$color_type),',');}?>" data-gal_scene="<?php if(!empty($scene)){ echo implode(',',$scene);}?>" data-gal_cat="<?php if( isset($tma) && is_array($tma) && !empty($tma)){ echo implode(',',$tma);}?>" style="height: 210px; width: 280px; display: block; top: 0px; left: 0px; transform-origin: center center 0px; z-index: 2;float:left">
							<?php the_title();?>
							<img src="<?php the_post_thumbnail_url();?>" alt="">
						</li>
					<?php endwhile;wp_reset_postdata();?>
					</ul>
				</div>
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
