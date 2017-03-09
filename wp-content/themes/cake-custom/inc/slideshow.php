<?php
global $post, $paged;
$mdt_pid = cake_get_postid();
$theID = ( isset( $post->ID ) ? $mdt_pid : "" );

$sliderchoose = get_post_meta($theID, 'cake_slider_choose', true);

if($sliderchoose!="no-slider" && $sliderchoose!=""){
?> 


	<!-- slider here -->
      <section id="slideshow-container" class="<?php echo esc_attr($sliderchoose);?>">
		   <div class="cake-slideshow">
		  
			<?php
			$out='';
			
				if($sliderchoose=='parallax-slider'){
					
					$entries = get_post_meta( $theID, 'cake_parallax_slider_item', true );
					
					$out .='<div class="banner-parallax">';
					$out .='<div id="carousel-cake-slider" class="carousel slide carousel-fade" data-ride="carousel">';
					
					//Indicators
					$out .='<ol class="carousel-indicators">';
					$i = 0;
					foreach ( (array) $entries as $key => $entry ) {
					  $i++;
					  
					  $navlass = ($i == 1) ? 'active' : '';
					  
					  $out .='<li class="'.esc_attr($navlass).'" data-slide-to="'.esc_attr($i-1).'" data-target="#carousel-cake-slider"></li>';
					}
					$out .='</ol>';
					
					//Wrapper for slideshow
					$out .='<div class="carousel-inner" role="listbox">';
					
						$x = 0;
						foreach ( (array) $entries as $key => $entry ) {
							
						$x++;
							
						$img = $title = $subtitle = $text = $buttonlabel = $buttonlink = '';
						
						if ( isset( $entry['cake_sititle'] ) )
						$title = esc_html( $entry['cake_sititle'] );
						if ( isset( $entry['cake_sisubtitle'] ) )
						$subtitle = esc_html( $entry['cake_sisubtitle'] );
						$text = isset( $entry['cake_sitextdescription'] ) ? $entry['cake_sitextdescription'] : '';
						$buttonlabel = isset( $entry['cake_sibuttonlabel'] ) ? $entry['cake_sibuttonlabel'] : '';
						$buttonlink = isset( $entry['cake_sibuttonlink'] ) ? $entry['cake_sibuttonlink'] : '';
						$buttoncolor = isset( $entry['cake_sibuttoncolor'] ) ? $entry['cake_sibuttoncolor'] : 'purple';
	
						
						if ( isset( $entry['cake_siimage_id'] ) ) {
						$img = wp_get_attachment_image_src( $entry['cake_siimage_id'], 'full');
						}
						
						
						$itemclass = ($x == 1) ? 'active' : '';
					
						$out .='<div class="item '.esc_attr($itemclass).'">';
							$out .='<div class="parallax-image" data-image="'.esc_url($img[0]).'">';
							  $out .='<div class="parallax-text">';
								$out .='<div class="parallax-table">';
								  $out .='<div class="parallax-center">';
									$out .='<div class="container">';
									  $out .='<div class="row">';
										$out .='<div class="col-sm-12">';
										  $out .='<h2>'.esc_html($title).'</h2>';
										  $out .='<h3>'.esc_html($subtitle).'</h3>';
										  $out .='<p>';
											$out .= ($text);
										  $out .='</p>';
										  $out .='<div class="form-group">';
											$out .='<a href="'.esc_url($buttonlink).'" class="btn btn-lg btn-sld btn-'.esc_attr($buttoncolor).'-cake mar-top-20">'.esc_html($buttonlabel).'</a>';
										  $out .='</div>';
										$out .='</div>';
									  $out .='</div>';
									$out .='</div>';
								  $out .='</div>';
								$out .='</div>';
							  $out .='</div>';
							$out .='</div>';
						 $out .='</div>';
						 
						 
						}
					
					$out .='</div>';
					
					//Left and right controls
					$out .='<a class="left carousel-control" href="#carousel-cake-slider" role="button" data-slide="prev">';
						$out .='<i class="fa fa-angle-left"></i>';
					$out .='</a>';
						$out .='<a class="right carousel-control" href="#carousel-cake-slider" role="button" data-slide="next">';
						$out .='<i class="fa fa-angle-right"></i>';
					$out .='</a>';
					
					$out .='</div>';
					$out .='</div>';
					

				
				}elseif($sliderchoose=='slice-slider'){
					

					$entries = get_post_meta( $theID, 'cake_slider_item', true );
					$slicetitle = get_post_meta( $theID, 'cake_slice_slidertitle', true );
					$slicesubtitle = get_post_meta( $theID, 'cake_slice_slidersubtitle', true );
					$fullimg = get_post_meta( $theID, 'cake_slice_fullimage_id', 1 );
					$img = get_post_meta( $theID, 'cake_slice_sliderimg', 1 );
					
					
					$out .='<div class="banner-slice">';
					$out .='<div class="container">';
					
						$out .='<h1>'.esc_attr($slicetitle).'</h1>';
						$out .='<h2>'.esc_attr($slicesubtitle).'</h2>';
						
						$out .='<div class="img-slice-mobile visible-xs">';
							
							$out .= wp_get_attachment_image( $fullimg, 'full');
							
						$out .='</div>';
						
						$out .='<div class="slider-slice-item hidden-xs">';
						
						
						
						$out .='<div class="wrap-circle" id="image">';
						
						$out .='<div class="slice-circle">';
						
						$out .='<div class="wrap-slice">';
						
						$i = 1;

					    foreach ($img  as $attachment_id => $attachment_url) {
							
							
							$out .='<div class="slice-'.esc_attr($i).'-cake">';
							
								 
								$theimage = array_keys($attachment_url);	
								$key = $theimage[0];
								$out .= wp_get_attachment_image($key, 'full', null, array('class' => ''));
								

							$out .='</div>';
							
							$i++;
							
						}
						
						$out .='</div>';
						
						$out .='</div>';
						
						$out .='</div>';
						
						$out .='</div>';
						
						$out .='<a class="button-next-slice" href="javascript:void(0);" id="button"><i class="fa fa-angle-right"></i></a>';
					
					$out .='</div>';
					$out .='</div>';
					
				}elseif($sliderchoose=='cycle-slider'){
					

					$entries = get_post_meta( $theID, 'cake_cycle_slider_item', true );
					
					$out .='<div class="banner-cycle">';
					$out .='<div class="container">';
					$out .='<div class="cdocycle-slideshow">';
					$out .='<div class="cdocycle-slides">';
						
						foreach ( (array) $entries as $key => $entry ) {
							
						$img = $title = $subtitle = '';
						
						
						$title = isset( $entry['cake_cycle_slidertitle'] ) ? $entry['cake_cycle_slidertitle'] : '';
						$subtitle = isset( $entry['cake_cycle_slidersubtitle'] ) ? $entry['cake_cycle_slidersubtitle'] : '';
						
						if ( isset( $entry['cake_cycle_sliderimg_id'] ) ) {
						$img = wp_get_attachment_image_src( $entry['cake_cycle_sliderimg_id'], 'full');
						}
					
						$out .='<div class="cdocycle-slide">';
						$out .='<h1>'.esc_attr($title).'</h1>';
						$out .='<h2>'.esc_attr($subtitle).'</h2>';
						$out .= '<img src="'.esc_url($img[0]).'" alt="" />';
						$out .='</div>';
						
						}
						
						
						
					$out .='</div>';
					$out .='<div class="cdocycle-control">';
						$out .='<span class="prevControl"><i class="fa fa-angle-left"></i></span>';
						$out .='<span class="nextControl"><i class="fa fa-angle-right"></i></span>';
					$out .='</div>';
					$out .='</div>';
					$out .='</div>';
					$out .='</div>';
				
				}elseif($sliderchoose=='slick-slider'){
					
					$slicktitle = get_post_meta( $theID, 'cake_slick_slidertitle', true );
					$slicksubtitle = get_post_meta( $theID, 'cake_slick_slidersubtitle', true); 
					$slicksliderpost = get_post_meta( $theID, 'cake_slick_slider_post', true );
					$slicksliderwoopost = get_post_meta( $theID, 'cake_slick_slider_woo_post', true );
					$entries = get_post_meta( $theID, 'cake_slick_slider_post_item', true );
					
					$out .='<div class="banner-slick">';
						$out .='<div class="container">';
						
							$out .='<h1>'.esc_attr($slicktitle).'</h1>';
							$out .='<h2>'.esc_attr($slicksubtitle).'</h2>';
							
							$out .='<div class="cdoslick-slideshow">';
							
							
							if($slicksliderpost=="woocommercepost"){
								
								ob_start();
								cake_product_slider($slicksliderwoopost, -1);
								$out.= ob_get_clean();
								
							
							}else{
							
							
								foreach ( (array) $entries as $key => $entry ) {
									
								
								$img = $labeltext = $linkURL = '';
								
								$labeltext = isset( $entry['cake_slick_slidertext'] ) ? $entry['cake_slick_slidertext'] : '';
								if ( isset( $entry['cake_slick_slider_image_id'] ) ) {
								$img = wp_get_attachment_image( $entry['cake_slick_slider_image_id'], 'full');
								}
								$linkURL = isset( $entry['cake_slick_sliderlink'] ) ? $entry['cake_slick_sliderlink'] : '';
								
								 $out .='<div class="cdoslick-item">';
								 
										if($linkURL){
										$out .= '<a href="'.esc_url($linkURL).'">'.$img.'</a>';
										}else{
										$out .= $img;	
										}
										if($labeltext){
										$out .='<div class="price-cake hidden-xs">';
										  $out .='<p>'.esc_attr($labeltext).'</p>';
										$out .='</div>';
										}
									
									
									
								 $out .='</div>';
								 
								} //end foreach
							
							}
							
							
							$out .='</div>';//end cdoslick-slideshow
							
							$out .='<div class="cdoslick-control">';
								$out .='<span class="prevControlSlick"><i class="fa fa-angle-left"></i></span>';
								$out .='<span class="nextControlSlick"><i class="fa fa-angle-right"></i></span>';
							$out .='</div>';
						
						$out .='</div>';
						
						$out .='<div class="cdoslick-bottom-bg">';
						
						$out .='</div>';
						
						$out .='<div class="cdoslick-bottom-border">';
						
						$out .='</div>';
					
					$out .='</div>';
				
				}else{
					
					$out .='';
				}
				
			
			echo $out;
			?>
			
		   </div>
      </section>
      <!-- slider end here -->  

<?php } ?>

