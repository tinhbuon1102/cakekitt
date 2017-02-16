<?php
//Check woocommerce Activate
if ( ! function_exists( 'codeopus_is_woocommerce_activated' ) ) {
	function codeopus_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}


//Product Slider Functions
if ( ! function_exists( 'cdo_product_slider_function' ) ) {
	function cdo_product_slider_function($id, $title, $subtitle, $subtitle_link, $type, $number){
		global $woocommerce, $wpdb, $yith_wcwl, $product ; 

		if(!function_exists('is_woocommerce')){ return false; }
		global $woocommerce; 


		if(!isset($woocommerce)) return ;
		
		$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );

		switch($type):

			case 'featured':

				$query_args['meta_query'] = $woocommerce->query->get_meta_query();

				$query_args['meta_query'][] = array(

					'key' => '_featured',

					'value' => 'yes'

				);

			break;

			case 'top_rated':

				$query_args['meta_query'] = $woocommerce->query->get_meta_query();		

			break;

			default:

				$query_args['meta_query'] = array();

				$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			
				$query_args['meta_query']   = array_filter( $query_args['meta_query'] );

			break;

		endswitch;

		$q = new WP_Query( $query_args );
		
		$out ='';

		if($q->have_posts()):
		
		$out .='<div class="bow-featured-wrap">';

			$out .='<div class="title-section-container">';
			if($title){
			$out .='<h2 class="title-section">'.$title.'</h2>';
			}
			if($subtitle){
			$out .='<a href="'.esc_url($subtitle_link).'" class="subtitle-section">'.$subtitle.'</a>';
			}
			$out .='</div>';

			$out .='<div class="product-grid">';

				$out .='<div class="woocommerce">';

					$out .='<div id="'.esc_attr($id).'" class="cdo-products-slider-container">';

						$out .='<ul class="products cdo-products-slider">';

					

						while($q->have_posts()): $q->the_post();


							$out .='<li class="product">';
							
								$out .='<div class="col-sm-12">';
								
								ob_start();
								do_action( 'woocommerce_before_shop_loop_item' );
								$out .= ob_get_clean();


										ob_start();
										do_action( 'woocommerce_before_shop_loop_item_title' );
										$out .= ob_get_clean();
										
										ob_start();
										do_action( 'woocommerce_shop_loop_item_title' );
										$out .= ob_get_clean();
										
										ob_start();
										do_action( 'woocommerce_after_shop_loop_item_title' );
										$out .= ob_get_clean();


								
								ob_start();
								do_action( 'woocommerce_after_shop_loop_item' );
								$out .= ob_get_clean();

								$out .='</div>';
								
							$out .='</li>';

							endwhile;

						$out .='</ul><!--.products -->';

					$out .='</div><!--.cdo-products-slider-container -->';

				$out .='</div><!--.woocommerce -->';

			$out .='</div><!--.product-grid-->';

		$out .='</div><!--.featured-wrap -->';
		

	endif; wp_reset_query();

	return $out;

	}
}

if ( ! function_exists( 'cdo_testimonial_slider_function' ) ) {
	function cdo_testimonial_slider_function($category, $color_style, $container_height, $class){
		
		global $post;
		
		$getcolorstyle = ($color_style !="" ?  $color_style : 'purple');
		
		
		$cat = explode(',',$category);
		
		if($category=="") return false;
		
		$args = array(
		'post_type' => 'testimonial',
		'post_status' => 'publish',
		'showposts' => '-1',
		'tax_query' => array(
			array(
				'taxonomy' => 'testimonial_category',
				'terms' => $cat,
				'field' => 'slug',
			)
		)
		);
		
		$looptesti = new WP_Query( $args );
		
		$out ='';
		
		
		if($looptesti->have_posts()){
			
			STATIC $i = 0;
	
			$i++;
			
			$out .='<div id="cdo-testimonial-slider'.$i.'" class="cdo-testimonial-slider '.esc_attr($getcolorstyle).' '.esc_attr($class).'">';
			
			$imgloop = get_posts( $args );
			
			$out .='<div class="cdo-testimonial-slider-img" data-height="'.$container_height.'">';
				if( $imgloop ) {
					
					$idx = 1;
					foreach($imgloop as $post) :
					setup_postdata($post);
					if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					$out.= get_the_post_thumbnail($post->ID, 'full', array('data-id' => $idx, 'class' => 'testiimg')); 
					}
					$idx++;
					endforeach;
				}
			$out .= '</div>';
			
			
			$out .='<div class="cdo-testimonial-slider-content" data-height="'.$container_height.'">';
			$out .="<div class='cdo-testimonial-slider-item'>";
			while($looptesti->have_posts()) : $looptesti->the_post();
			
				$info = get_post_meta($post->ID, 'cdo_testi_info',true);
			
				
				$out .='<div class="cdo-testimonial-slider-quote">';
				$out .='<div class="cdo-testimonial-slider-info"><strong>'.get_the_title().'</strong>'.' '.$info.'</div>';
				$out .= '<p>'.get_the_content().'</p>';
				$out .= '</div>';
			endwhile;
			$out .= '</div>';
			$out .= '</div>';
			
			$out .='<div class="cdo-slick-arrow">';
			$out .='<span class="cdo-slick-prev"></span><span class="cdo-slick-next"></span>';
			$out .= '</div>';
			
			$out .= '</div>';
		}
		
		wp_reset_postdata();
		
		return $out;
	
	}
}

//Product ID Functions
if ( ! function_exists( 'cdo_product_id_function' ) ) {
	function cdo_product_id_function($ids, $featured, $class){
		
	$id = explode(",",$ids);
	
	$getid = $id ? $id :'';

	$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'orderby' => 'post__in',
			'post__in' => $getid
			);
		$loop = new WP_Query( $args );
		$out='';
		if ( $loop->have_posts() ) {
			
			$out .='<div class="col-sm-4">';
            $out .='<div class="row">';
			while ( $loop->have_posts() ) : $loop->the_post();
				global $product;
			    $price = get_post_meta($loop->post->ID , '_price');

				if($featured=="yes"){
					
				wp_enqueue_script( 'cdo-jquery-owl-carousel');
				wp_enqueue_script( 'cdo-jquery-product-featured-setting');
				
				$featuredargs = array(
					'post_type' => 'product',
					'post_status' => 'publish',  
					'meta_query' => array(
						array(
							'key' => '_featured',
							'value' => 'yes',
						)
					)
				 );
				$featuredloop = get_posts( $featuredargs );
								
				if( $featuredloop ) {
					
				$out .='<div class="content-arrivals-style custom-arrivals">';
                      $out .='<div class="img-arrivals">';	
					  $out .='<div class="owl-arrivals">';
				foreach($featuredloop as $post) :

						setup_postdata($post);

						$len =60; 
						$newExcerpt = substr($post->post_excerpt, 0, $len);

						if(strlen($newExcerpt) < strlen($post->post_excerpt)) { $newExcerpt = $newExcerpt.'';}
								
                          $out .='<div class="item">';
								$out .='<a href="'.esc_url(get_permalink($post->ID)).'">';
								if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
								$out.= get_the_post_thumbnail($post->ID, 'product-thumb-small'); 
								}
								$out .='</a>';
                            $out .='<h3>';
							  $out .='<a href="'.esc_url(get_permalink($post->ID)).'">';
                              $out .= get_the_title($post->ID);
							  $out .='</a>';
                            $out .='</h3>';
                            $out .='<div class="center-hr">';
                              $out .='&nbsp;';
                            $out .='</div>';
                            $out .='<p class="product-desc">';
                              $out .= $newExcerpt;
                            $out .='</p>';
							if (codeopus_is_woocommerce_activated()) { 
							$out .= do_shortcode( '[add_to_cart id=' . $post->ID . ']' );
							}
                          $out .='</div>';
                        

				endforeach;

				$out .='</div>';
				$out .='</div>';
                $out .='</div>';
				}
				
				}
			
				$out .='<div class="cdo-product-grid">';
				  $out .='<figure class="effect-zoe">';
					$out .='<div class="content-arrivals-style">';
					  
						$out .='<div class="img-arrivals">';
								if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
								$out.= get_the_post_thumbnail($loop->post->ID, 'product-thumb-small'); 
								}
						  $out .='<h3>';
							
							$out .= get_the_title($loop->post->ID);
							
						  $out .='</h3>';
						$out .='</div>';
					 
					$out .='</div>';
					$out .='<figcaption>';
					  $out .='<p class="pull-left">';
						$out .='<a href="'.esc_url(get_permalink($loop->post->ID)).'">';
						$out .= get_the_title($loop->post->ID);
						$out .='</a>';
					  $out .='</p>';
					  $out .='<p class="icon-links">';
						
						if (codeopus_is_woocommerce_activated()) { 
						$out .= $product->get_price_html();
						}

					  $out .='</p>';
					$out .='</figcaption>';
				  $out .='</figure>';
				$out .='</div>';


			endwhile;
			
			$out .='</div>';
            $out .='</div>'; // end column
			
			
		} else {
			$out .=__( 'No products found', 'bow');
		}
		
		wp_reset_postdata();
		
		return $out;
		
	}
}

//Product Category Functions
if ( ! function_exists( 'cdo_product_category_function' ) ) {
	function cdo_product_category_function($category, $featured, $class){
		global $wp_query, $product;
	
		$getcats = explode(',',$category);

		$out='';
			
			$out .='<div class="col-sm-4">';
            $out .='<div class="row">';
			 foreach ($getcats as $prodcat) {
				 
				$cat = get_term_by('slug', $prodcat, 'product_cat');
				$cat_link = get_term_link( $cat->slug, $cat->taxonomy );
				$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				$imagealt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

				if($featured=="yes"){
					
				wp_enqueue_script( 'cdo-jquery-owl-carousel');
				wp_enqueue_script( 'cdo-jquery-product-featured-setting');
				
				$featuredargs = array(
					'post_type' => 'product',
					'post_status' => 'publish',  
					'meta_query' => array(
						array(
							'key' => '_featured',
							'value' => 'yes',
						)
					)
				 );
				$featuredloop = get_posts( $featuredargs );
								
				if( $featuredloop ) {
					
				$out .='<div class="content-arrivals-style custom-arrivals">';
                      $out .='<div class="img-arrivals">';	
					  $out .='<div class="owl-arrivals">';
				foreach($featuredloop as $post) :
				
				
						setup_postdata($post);

						$len =60; 
						$newExcerpt = substr($post->post_excerpt, 0, $len);

						if(strlen($newExcerpt) < strlen($post->post_excerpt)) { $newExcerpt = $newExcerpt.'';}

								
                          $out .='<div class="item">';
								$out .='<div><a href="'.esc_url(get_permalink($post->ID)).'">';
								$out.= get_the_post_thumbnail($post->ID, 'product-thumb-small'); 
								$out .='</a></div>';
                            $out .='<h3>';
							  $out .='<a href="'.esc_url(get_permalink($post->ID)).'">';
                              $out .= get_the_title($post->ID);
							  $out .='</a>';
                            $out .='</h3>';
                            $out .='<div class="center-hr">';
                              $out .='&nbsp;';
                            $out .='</div>';
                            $out .='<p class="product-desc">';
                              $out .= $newExcerpt;
                            $out .='</p>';
							if (codeopus_is_woocommerce_activated()) { 
							$out .= do_shortcode( '[add_to_cart id=' . $post->ID . ']' );
							}
                          $out .='</div>';
                        

				endforeach;
				//wp_reset_postdata();
				$out .='</div>';
				$out .='</div>';
                $out .='</div>';
				}
				
				}
			
				$out .='<div class="cdo-product-grid">';
				  $out .='<figure class="effect-zoe">';
					$out .='<div class="content-arrivals-style">';
					  
						$out .='<div class="img-arrivals">';
								
								$out.= '<div><img src="'.esc_url($image).'" width="220" height="220" alt="'.esc_attr($imagealt).'"/></div>'; 
								
						  $out .='<h3>';
							
							$out .= $cat->name;
							
						  $out .='</h3>';
						$out .='</div>';
					 
					$out .='</div>';
					$out .='<figcaption>';
					  $out .='<p class="pull-left">';
						$out .='<a href="'.esc_url($cat_link).'">';
						$out .= $cat->name;
						$out .='</a>';
					  $out .='</p>';
					$out .='</figcaption>';
				  $out .='</figure>';
				$out .='</div>';


			 }
			
			$out .='</div>';
            $out .='</div>'; // end column

		
		wp_reset_postdata();
		
		return $out;
		
	}
}

if ( ! function_exists( 'cdo_product_category2_function' ) ) {
	function cdo_product_category2_function($category, $number, $class){
		global $post, $woocommerce, $wpdb, $product ; 
	
		$getcats = explode(',',$category);
		
		if(!function_exists('is_woocommerce')){ return false; }

		if(!isset($woocommerce)) return ;
		
		$query_args = array(
		'posts_per_page' => $number,
		'post_status' => 'publish',
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'terms' => $getcats,
				'field' => 'slug',
			)
		)
		);
		
		$loopprod = new WP_Query( $query_args );
		
		$out='';
		
		if($loopprod->have_posts()){
			
			$out .='<div class="cdo-product '.esc_attr($class).'">';
			$out .='<div class="row">';
			
			while($loopprod->have_posts()) : $loopprod->the_post();
			
				$thecolor = get_post_meta( $post->ID, 'cake_product_style', true );
				$thesubtitle = get_post_meta( $post->ID, 'cake_product_subtitle', true );
				$thethumb = get_post_meta( $post->ID, 'cake_product_thumb_id', 1 );
				$product = new WC_Product( get_the_ID() );
				$price = $product->price;
				$price_html = $product->get_price_html();
				$theprice = preg_replace('/.00/', '', $price_html);
				
				if($thecolor!=""){
					$theclass1 = $thecolor.'-cake';
					$theclass2 = 'bottom-'.$thecolor;
					$theclass3 = $thecolor.'-dot';
					$theclass4 = $thecolor.'-button-cake';
					$theclass5 = $thecolor.'-line';
				}else{
					$theclass1 = 'blue-cake';
					$theclass2 = 'bottom-blue';
					$theclass3 = 'blue-dot';
					$theclass4 = 'blue-button-cake';
					$theclass5 = 'blue-line';
				}
				
				$out .='<div class="col-sm-4">';
				
				$out .='<div class="cdo-wrap-product">';
					
						$out.='<div class="cdo-product-thumb">';
						
						if($thethumb!=""){
							$out .= wp_get_attachment_image( $thethumb, 'product-thumb-small2');
						}else{
							if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
							$out.= get_the_post_thumbnail($loopprod->ID, 'product-thumb-small2');	
							}
						}

						$out .='</div>';						
						
                    $out .='<div class="top-product '.esc_attr($theclass1).'">';
					  
                      $out .='<span class="cdo-product-price">'.$theprice.'</span>';
                      $out .='<p class="cdo-product-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.get_the_title($post->ID).'</a></p>';
                      $out .='<span class="cdo-product-subtitle">'.esc_attr($thesubtitle).'</span>';
                    $out .='</div>';
                    $out .='<div class="bottom-product '.esc_attr($theclass2).'">';
                      $out .='<div class="bottom-product-abs '.esc_attr($theclass3).'">';
                        $out .='<div class="button-cake">';
                          $out .='<div class="'.esc_attr($theclass4).'">';
							$out .= do_shortcode('[add_to_cart id="'.$post->ID.'" style="border:0; padding:0"]');
                          $out .='</div>';
                        $out .='</div>';
                      $out .='</div>';
                      $out .='<div class="wrap-bottom-cake">';
						if ( has_excerpt( $post->ID ) ) {
                        $out .='<p>'.get_the_excerpt($post->ID).'</p>';
						}
                        $out .='<div class="'.esc_attr($theclass5).'"></div>';
                      $out .='</div>';
                    $out .='</div>';
                $out .='</div>';
				
				$out .='</div>';
				
			endwhile;
			
			$out .='</div>';
			$out .='</div>';
			
		}
		
		wp_reset_postdata();
		
		return $out;
		
	}
}

//Testimonial Functions
if (!function_exists('cdo_testimonial_function')) :
function cdo_testimonial_function($category="", $column="", $showpost="", $orderby="", $order=""){
	
	
	global $post, $paged; 
	
	$getcatid = get_term_by('slug', $category, 'testimonial_category');
	$thecatslug = ($category!="" ? $getcatid->slug : '');
	
	$column = ($column=="3" ? "col-sm-4" : ($column=="4" ? "col-sm-3" : ($column=="1" ? "col-sm-12" : "col-sm-6")));
	
	$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	
	query_posts(array('testimonial_category' =>$thecatslug, 'post_type' => 'testimonial', 'posts_per_page' => $showpost, 'orderby'=>$orderby,'order'=>$order, 'paged'=> $paged));
	
	$out ='';
	
	$out.='<div class="cdo-testimonial-section">';
	while(have_posts()) : the_post();
	
		$thumb   = get_post_thumbnail_id();
		$testiimg = wp_get_attachment_url($thumb,'thumbnail' );
		$info = get_post_meta($post->ID, 'cdo_testi_info',true);
		
		$out.='<div class="'.esc_attr($column).'">';
		$out.='<div class="testi-container">';                           
			$out.='<div class="testi-text">';
				$out.='<blockquote><p>';
					$out.= get_the_content();
				$out.='</p></blockquote>';                                                         
			$out.='</div>';                                                                 
		$out.='</div>';
		$out.='<div class="testi-image">';
			if ($testiimg) {$out.= '<div><img src="'.esc_url($testiimg).'" alt="'.esc_attr(get_the_title()).'" /></div>';}	
		$out.='</div>';
		$out.='<div class="testi-name">';
			$out.= get_the_title(). '<br/>';
			$out.='<span class="company-name">'.$info.'</span>';
		$out.='</div>';
		$out.='</div>';
		
	endwhile;
	$out.='</div>';
	
	if(function_exists('wp_pagenavi')) {
		ob_start();
		wp_pagenavi();
		$out.= ob_get_clean();
	}
	
	wp_reset_query();
	
	return $out;
	
}
endif;


//Team Functions
if (!function_exists('cdo_team_function')) :
function cdo_team_function($category="", $column="", $showpost="", $orderby="", $order="", $paging=""){
	
	
	global $post; 
	
	$getcatid = get_term_by('slug', $category, 'team_category');
	$thecatslug = ($category!="" ? $getcatid->slug : '');
	
	$column = ($column=="3" ? "col-sm-4" : ($column=="4" ? "col-sm-3" : ($column=="1" ? "col-sm-12" : "col-sm-6")));
	$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	
	query_posts(array('team_category' =>$thecatslug, 'post_type' => 'team', 'posts_per_page' => $showpost, 'orderby'=>$orderby,'order'=>$order, 'paged'=> $paged));
	
	$out ='';
	
	$out.='<div class="cdo-team-section">';
	$out.='<div class="row">';
	while(have_posts()) : the_post();
	
		$thumb   = get_post_thumbnail_id();
		$teamimg = wp_get_attachment_url($thumb,'full' );
		$occupation = get_post_meta($post->ID, 'cdo_team_info',true);
		$facebook = get_post_meta($post->ID, 'cdo_team_fb',true);
		$twitter = get_post_meta($post->ID, 'cdo_team_twitter',true);
		$google = get_post_meta($post->ID, 'cdo_team_google+',true);
		$socialprofile = get_post_meta($post->ID, 'cdo_team_social',true);
		
		
		$out.='<div class="'.esc_attr($column).'">';
				
					if ($teamimg) {$out.= '<div><img src="'.esc_url($teamimg).'" alt="'.esc_attr(get_the_title()).'" /></div>';}
					
					$out.='<h5>'.get_the_title().'</h5>';
					$out.='<p class="cdo-team-info">'.$occupation.'</p>';
					$out.='<p class="cdo-team-desc">'.get_the_excerpt().'</p>';
					
					if($facebook){$out.='<a href="'.esc_url($facebook).'" target="_blank"><i class="fa-facebook"></i></a>';}
					if($twitter){$out.='<a href="'.esc_url($twitter).'" target="_blank"><i class="fa-twitter"></i></a>';}
					if($google){$out.='<a href="'.esc_url($google).'" target="_blank"><i class="fa-google-plus"></i></a>';}
					
					$out.= $socialprofile;
    
		$out.='</div>';
		
		
	endwhile;
	$out.='</div>';
	$out.='</div>';
	
	if($paging=="true" || $paging=="yes"){
	if(function_exists('wp_pagenavi')) {
		ob_start();
		wp_pagenavi();
		$out.= ob_get_clean();
	}
	}
	
	wp_reset_query();
	
	return $out;
	
}
endif;


//Portfolio Functions
if (!function_exists('cdo_portfolio_function')) :
function cdo_portfolio_function($filter, $category, $column, $showpost, $zoomicon, $linkicon, $showtitle, $showdesc, $orderby, $order){
		
	global $post, $paged;
	
	$column = ($column=="3" ? "col-sm-4" : ($column=="4" ? "col-sm-3" : ($column=="1" ? "col-sm-12" : "col-sm-6")));
	
	$getcat = explode(',',$category);
	
	if(is_singular('portfolio')){
		
		query_posts(array(
		'post_type' => 'portfolio',
		'posts_per_page' => $showpost,
		'paged' => get_query_var( 'paged' ),
		'orderby' => $orderby,
		'order'=> $order,
		'post__not_in' => array( $post->ID),
		'tax_query' => array(
			array(
				'taxonomy' => 'portfolio_category',
				'terms' => $getcat,
				'field' => 'slug',
			)
		)
		));
	
	
	}else{
	
		query_posts(array(
		'post_type' => 'portfolio',
		'posts_per_page' => $showpost,
		'paged' => get_query_var( 'paged' ),
		'orderby' => $orderby,
		'order'=> $order,
		'tax_query' => array(
			array(
				'taxonomy' => 'portfolio_category',
				'terms' => $getcat,
				'field' => 'slug',
			)
		)
		));
	
	}
	
	$out='';
	
	if(have_posts()) :

	
	if($filter=="yes"){
		$out.='<div class="col-sm-12">';
			$out.='<div id="cdo-pffilter">';
				$out.='<ul class="option-set" data-option-key="filter">';
					
					$out.='<li><i class="fa-bars"></i></li>';
					$out.='<li><a href="" class="selected" data-option-value="*">'.__('All','codeopus').'</a></li>';
					
					if($category){
						foreach ($getcat as $pfcat) {
						$cat = get_term_by('slug', $pfcat, 'portfolio_category');
						$out.='<li><a href="" data-option-value=".'.esc_attr($cat->slug).'">'.esc_attr($cat->name).'</a></li>';
						}
					}else{
						$pf_categories = get_categories('taxonomy=portfolio_category&orderby=ID&title_li=&hide_empty=0');
						foreach ($pf_categories as $cat) {
							$out .='<li><a href="" data-option-value=".'.esc_attr($cat->slug).'">'.esc_attr($cat->name).'</a></li>';
						}
						
					}
					
				$out.='</ul>';
			$out.='</div>'; 
		$out.='</div>';                                             
	}
	
	$out.='<div class="cdo-pf-container">';
	
	while ( have_posts() ) : the_post();
	
	$thumb   = get_post_thumbnail_id();
	$portfolioimg = wp_get_attachment_url($thumb,'full');
	$lightbox = get_post_meta($post->ID, 'cdo_lightbox_url',true);
	$customlink = get_post_meta($post->ID, 'cdo_custom_link',true);
	$zoom = ($lightbox!="" ? $lightbox : $portfolioimg);
	$link = ($customlink!="" ?  $customlink: get_permalink());
	
	
		$out .='<div class="'.esc_attr($column).' element ';
				$cats = get_the_terms($post->ID,'portfolio_category');
				foreach ($cats as $cat ) {
					$out .= $cat->slug." ";
				}      
				$out .='">';
			$out.='<figure>';
				if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
				$out.= get_the_post_thumbnail($post->ID, 'large', array('class'=>''));
				}
				$out.='<div class="pf-button-container">';
				$out.='<div class="pf-button">';
				
					$addclass= ($linkicon=="yes" ? "with-permalink" : ''); 
					$addclass2= ($zoomicon!="yes" ? "no-zoom" : ''); 
					
					$out.='<div class="pf-button-icon '.esc_attr($addclass).' '.esc_attr($addclass2).'">';
					if($zoomicon=="yes"){$out.='<a class="fancybox" href="'.esc_url($zoom).'" data-fancybox-group="'.esc_attr('gallery').'" title="'.esc_attr(get_the_title()).'"><i class="fa-search"></i></a>';}
					if($linkicon=="yes"){$out.='<a class="permalink" href="'.esc_url($link).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'codeopus' ), the_title_attribute( 'echo=0' ) ).'"><i class="fa-link"></i></a>';}
					$out.='</div>';
				$out.='</div>';
				$out.='</div>';
			$out.='</figure>';
			if($showtitle=="yes"){$out.='<h3 class="pf-title">'.get_the_title().'</h3>';}
			if($showdesc=="yes" && $post->post_excerpt!=""){$out .= '<p class="pf-excerpt">'.get_the_excerpt().'</p>';}			
		$out.='</div>';
		
	endwhile;
	
	$out.='</div>';
	
	endif;
	
	wp_reset_query();
	
	return $out;
	
}
endif;
?>