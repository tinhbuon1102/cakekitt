<?php
/**
 * The Template for displaying all single posts.
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
get_header(); ?>

	
	<?php get_template_part('inc/page-title'); ?>

	<div id="content-wrapper">
	<div class="container">
		<div class="row">
			
			<?php while (have_posts()) : the_post();?>
        
			<?php 
			$lightbox = get_post_meta($post->ID, 'cdo_lightbox_url',true);
			$get_attachments = get_children( array( 'post_parent' => $post->ID ) );
			$attachments_count = count( $get_attachments );
			$thumb   = get_post_thumbnail_id();
			$get_single_image =  wp_get_attachment_image_src($thumb,'cake-medium-custom-image' );
			$single_image =  $get_single_image[0];
		
			?>
    
		
		<div class="col-sm-4">
		
			<?php the_content(); ?>
		
		</div>
	
        <div class="col-sm-8">
          <?php if ($attachments_count > 1) { ?>
            
				<?php
                $args = array(
                    'order'          => 'ASC',
                    'post_type'      => 'attachment',
                    'post_parent'    => $post->ID,
                    'post_mime_type' => 'image',
                    'post_status'    => null,
                    'orderby'    => 'menu_order',
                    'numberposts'    => -1,
                );
                            
                $attachments = get_posts( $args );
                ?>
                
                <?php if ($attachments) { ?>
                    <div class="cdo-single-pfslider">
								
						<ul id="cdo-pfslider-image" class="owlCarousel">      
						  <?php
                            foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_image_src( $attachment->ID , 'cake-medium-custom-image' );
                                $image = $attachment_url[0];
                                
                                echo '<li><img src="'.esc_url($image).'" alt="'.esc_attr(get_the_title()).'" class=""></li>';
                            }
                            ?>
					   </ul> 
					
					
						<div class="pf-carousel-nav">
							<div class="left-nav">
							  <i class="fa-chevron-left"></i>
							</div>
							<div class="right-nav">
							  <i class="fa-chevron-right"></i>
							</div>
						</div>	
					
						
                    </div>
					
					
					
                <?php } ?>
                		           
            
            <?php } else { ?>
            
            	<?php if($lightbox) { ?>
                
                    <div class="cdo-single-video">
					<div class="single-video-container">
						<?php 
						$videourl = $lightbox;
						$htmlcode = wp_oembed_get(esc_url($videourl));
						echo $htmlcode;
						?>
                    </div>
					</div>
                            
                <?php }else{ ?>
                
                    <div class="cdo-single-image">
                        <img src="<?php echo esc_url($single_image);?>" alt="<?php esc_attr(the_title());?>" class="max-image"/>
                    </div>
                
				<?php }  ?>
                
            
            <?php } ?> 
			
			
			
			
        </div>

		<?php endwhile; ?>
			
		</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content-wrapper -->
<?php get_footer(); ?>