<?php
/*====================================================================================================
Header Section Function
======================================================================================================*/
if( !function_exists('cake_get_header_section')):
function cake_get_header_section(){
	  
	global $post;
	
	$sitelogo = get_theme_mod('cake_custom_logo','');
	$footerlogo = get_theme_mod('cake_footer_logo', '');
	$addclasslogotext = ($sitelogo == '' ? 'logo-text' : '');
	$loader = get_theme_mod('cake_loader_effect', 'true');
	$headertype = get_theme_mod('cake_header_type','fixed');
	$searchtype = get_theme_mod('cake_search_type', 'product_search');
	$abouttext = get_theme_mod('cake_about_text', '');
	$aboutlabel = get_theme_mod('cake_about_label', '');
	$navlabel = get_theme_mod('cake_nav_label', '');
	$socialnavonoff = get_theme_mod('cake_social_nav_onoff', 'true');
	$socialnavlabel = get_theme_mod('cake_social_nav_label', '');
	$popupnav = get_theme_mod('cake_popup_nav', 'true');
	
	$menuclass =($headertype=='fixed' ? 'navbar-fixed-top' :  'navbar-static-top');
	$menurightclass =($popupnav=='true' ? '' :  'no-popup-menu');
	
	$output	='';
	
	if($popupnav=="true"){
	$output .='<div class="modal modal-fullscreen" id="nav-popup" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><img src="'.esc_url(get_template_directory_uri(). '/images/cancel.svg').'" width="20" height="20" alt="" /></button>
		  </div>
		  <div class="modal-body">';
			$output .='<div class="container">';
			$output .='<div class="row">';
			$output .='<div class="col-sm-9">';

			$output .='<ul>';
			$output .='<li class="w-24">';
			
			//Logo
			if($footerlogo!=""){
			$output .='<a href="'.esc_url(home_url()).'"><img src="'.esc_url($footerlogo).'" alt="'.get_bloginfo('name').'"></a>';
			}elseif($sitelogo!=""){
			$output .='<a href="'.esc_url(home_url()).'"><img src="'.esc_url($sitelogo).'" alt="'.get_bloginfo('name').'"></a>';
			}else{
			$output .='<h1><a href="'.esc_url(home_url()).'">'.get_bloginfo('name').'</a></h1>';	
			}
			$output .='</li>';
			
			$output .='<li class="visible-xs">';
			ob_start();
			do_action('cake_showthisinmobile');
			$output .= ob_get_clean();
			$output .='</li>';
			$output .='<li class="w-75">';
			
			//Main Navigation
			if($navlabel){
			$output .='<div class="top-nav-popup">';
            $output .='<h4 id="top_title_nav" class="text-center">'.esc_html($navlabel).'</h4>';
            $output .='</div>';
			}
			
			$output .= wp_nav_menu( array(
			'theme_location' => 'mainmenu',
			'sort_column' => 'menu_order',
			'menu_id' => 'menu',
			'menu_class' => '',
			'echo'       => false,
			'fallback_cb' => 'cake_menu_page_fallback'
			));
			
			
			//Social Media
			if($socialnavonoff=="true"){
				
				if($socialnavlabel){
				$output .='<div class="top-nav-popup">';
				$output .='<h4 id="top_social_title_nav" class="text-center">'.esc_html($socialnavlabel).'</h4>';
				$output .='</div>';
				}
				
				ob_start();
				$output .= do_action('cake_social_icon');
				$output .= ob_get_clean();
				
			}
			
			
			$output .='</li>';
			$output .='</ul>';
			
			
			$output .='</div>';
			$output .='<div class="col-sm-3">';
			
			
			//Navigation Text
			if($aboutlabel){
			$output .='<div class="top-nav-popup">';
            $output .='<h4 id="top_title_desc" class="text-center">'.esc_html($aboutlabel).'</h4>';
            $output .='</div>';
			}
			
			$output .= '<p class="desctext">'.esc_html($abouttext).'</p>';
			
			
			$output .='</div>';
			$output .='</div>';
			$output .='</div>';
		  $output .='</div>
		</div>
	  </div>
	</div>';
	}
	

	
	
	
	
	if($loader=='true'){
	$output	.='<!--initial header-->';
	$output	.='<header class="ip-header">';
		$output	.='<div class="ip-loader">';
		  $output	.='<svg class="ip-inner" height="60px" viewbox="0 0 80 80" width="60px"><path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"></path><path class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z" id="ip-loader-circle"></path></svg>';
		$output	.='</div>';
	$output	.='</header>';
	}
	
	$output .='<!--main content-->';
	$output .='<div class="ip-main">';
	
	$output .='<nav class="navbar navbar-brand-cake '.esc_attr($menuclass).'">';
		  $output .='<div class="nav-container '.esc_attr($addclasslogotext).'">';
		  
          $output .='<div class="container">';
		  
		  $output .='<div class="sub_head clearfix">';
	      $output .= wp_nav_menu( array(
						'theme_location' => 'submenuleft',
						'sort_column' => 'menu_order',
						'menu_id' => 'submenu_left',
						'depth' => '0',
						'menu_class' => 'nav navbar-nav navbar-abs hidden-xs hidden-sm',
						'echo'       => false,
						'fallback_cb' => ''
						));
	
	$output .='<div class="Header-supHeaderBurger"><div class="Header-button--circled">';
	$output .='<button class="linericon-menu" type="button" data-menu="toggleMenu"></button>';
	$output .='</div></div>';
	$output .='<div class="Header-supHeaderLogo">';
					  
						if($sitelogo!=""){
						$output .='<a href="'.esc_url(home_url()).'"><img src="'.esc_url($sitelogo).'" alt="'.get_bloginfo('name').'"></a>';
						}else{
						$output .='<h1><a href="'.esc_url(home_url()).'" class="navbar-brand">'.get_bloginfo('name').'</a></h1>';	
						}
				
						$output .='</div>';
					$output .= wp_nav_menu( array(
						'theme_location' => 'submenuright',
						'sort_column' => 'menu_order',
						'menu_id' => 'submenu_right',
						'depth' => '0',
						'menu_class' => 'nav navbar-nav navbar-abs navbar-abs-right '.esc_attr($menurightclass).'',
						'echo'       => false,
						'fallback_cb' => ''
					));
	      $output .='</div>';
	
	//added
	      $output .='<nav class="Header-navContainer"><div class="Header-navHeader hidden-pc"><ul class="mb-navigation">';
	                $output .= wp_nav_menu( array(
						'theme_location' => 'mainmenuleft',
						'sort_column' => 'menu_order',
						'menu_id' => 'menu_left',
						'depth' => '0',
						'items_wrap' => '%3$s',
						'container' => false,
						'echo'       => false,
						'fallback_cb' => ''
					));
	                $output .= wp_nav_menu( array(
						'theme_location' => 'mainmenuright',
						'sort_column' => 'menu_order',
						'menu_id' => 'menu_right',
						'depth' => '0',
						'items_wrap' => '%3$s',
						'container' => false,
						'echo'       => false,
						'fallback_cb' => ''
					));
	$output .='</ul></div></nav>';
		  $output .='<nav>';
				
					$output .= wp_nav_menu( array(
						'theme_location' => 'mainmenuleft',
						'sort_column' => 'menu_order',
						'menu_id' => 'menu_left',
						'depth' => '0',
						'menu_class' => 'nav navbar-nav navbar-abs hidden-xs hidden-sm',
						'echo'       => false,
						'fallback_cb' => ''
						));
				
					$output .= wp_nav_menu( array(
						'theme_location' => 'mainmenuright',
						'sort_column' => 'menu_order',
						'menu_id' => 'menu_right',
						'depth' => '0',
						'menu_class' => 'nav navbar-nav navbar-abs navbar-abs-right hidden-xs hidden-sm '.esc_attr($menurightclass).'',
						'echo'       => false,
						'fallback_cb' => ''
					));
				
					if($popupnav=="true"){
					$output .='<ul class="nav navbar-nav navbar-abs navbar-abs-right">';
                      $output .='<li>';
                        $output .='<a class="show-menu" data-toggle="modal" data-target="#nav-popup"><i class="fa fa-bars"></i></a>';
                      $output .='</li>';
                    $output .='</ul>';
					}
                    
					
                    $output .='<ul class="header-nav hidden-xs hidden-sm">';
                      $output .='<li class="center-logo">';
					  
						if($sitelogo!=""){
						$output .='<a href="'.esc_url(home_url()).'"><img src="'.esc_url($sitelogo).'" alt="'.get_bloginfo('name').'"></a>';
						}else{
						$output .='<h1><a href="'.esc_url(home_url()).'" class="navbar-brand">'.get_bloginfo('name').'</a></h1>';	
						}
				
						$output .='</li>';
                    $output .='</ul>';

                $output .='</nav>';
		  
			
          $output .='</div>';
		  $output .='</div>';
		  $output .= '';
        $output .='</nav>';
    $output .='<!--/.navbar-collapse-->';
	
	echo $output;
	  
}
add_action('cake_header_section', 'cake_get_header_section');
endif;



/*====================================================================================================
Footer Section Function
======================================================================================================*/
if( !function_exists('cake_get_footer_section')):
function cake_get_footer_section(){
	
	$footercolumn = get_theme_mod('cake_footer_widget_column', '3column');
	$footerwidget = get_theme_mod('cake_footer_widget', 'true');
	$acsidebar_onoff = get_theme_mod('cake_enable_after_content_sidebar', 'false');
	$footerlogo = get_theme_mod('cake_footer_logo', '');
	
	
	if($footercolumn=="3column"){
	$col1 = 'col-sm-4';
	$col2 = 'col-sm-4';
	$col3 = 'col-sm-4';
	$col4 = '';
	}else{
	$col1 = 'col-sm-3';
	$col2 = 'col-sm-3';
	$col3 = 'col-sm-3';
	$col4 = 'col-sm-3';
	}
	
	$output	='';
	
	if($acsidebar_onoff=='true'){
	$output	.='<div id="after-content">';
		$output .='<div class="container">';
				ob_start();
				get_sidebar('after-content');
				$output .= ob_get_clean();
		$output .='</div>';
	$output	.='</div>';
	}
	
	$output .='<footer id="footer">';
		  
          $output .='<div class="container">';
		  
					$output .='<div class="top-footer">';
					  $output .='<div class="row">';
						$output .='<div class="col-sm-6">';
						   if($footerlogo){
						   $output .='<div class="footer-logo"><img alt="'.get_bloginfo('name').'" src="'.esc_url($footerlogo).'"></div>';
						   }
						$output .='</div>';
						$output .='<div class="col-sm-6 text-right">';
						  ob_start();
						  $output .= do_action('cake_social_icon');
						  $output .= ob_get_clean();
						$output .='</div>';
					  $output .='</div>';
					$output .='</div>';
					
					
					if($footerwidget=='true'){
						
					$output .='<div class="line-top-white mar-btm-20 mar-top-20">';
					  $output .='&nbsp;';
					$output .='</div>';
					
					$output .='<div class="content-about-footer">';

					  $output .='<div class="'.esc_attr($col1).'">';
							if ( is_active_sidebar( 'cake-footer1' ) ) :
								ob_start();
								dynamic_sidebar( 'cake-footer1' );
								$output .= ob_get_clean();
							else :
								$output .='<span style="font-size:12px;">'.esc_html__('Add widget to WP Admin &rarr; Appearance &rarr; Widgets &rarr; Footer1','cake').'</span>';  
							endif;   
					  $output .='</div>';

					  $output .='<div class="'.esc_attr($col2).'">';
						
							if ( is_active_sidebar( 'cake-footer2' ) ) :
								ob_start();
								dynamic_sidebar( 'cake-footer2' );
								$output .= ob_get_clean();
							else :
								$output .='<span style="font-size:12px;">'.esc_html__('Add widget to WP Admin &rarr; Appearance &rarr; Widgets &rarr; Footer2','cake').'</span>';  
							endif;   
						
					  $output .='</div>';

					  $output .='<div class="'.esc_attr($col3).'">';
							
							if ( is_active_sidebar( 'cake-footer3' ) ) :
								ob_start();
								dynamic_sidebar( 'cake-footer3' );
								$output .= ob_get_clean();
							else :
								$output .='<span style="font-size:12px;">'.esc_html__('Add widget to WP Admin &rarr; Appearance &rarr; Widgets &rarr; Footer3','cake').'</span>';  
							endif;
							
							if($footercolumn!="4column"){
								
								if(get_theme_mod('cake_footer_payment_logo')){ 
								   $output .='<div class="footer-payment-logo">';
								   
								   $output .= '<p class="payment_text">'.get_theme_mod('cake_footer_payment_text', esc_html__('Payment Method', 'cake')).'</p>';
								   
								   $img = explode(',', get_theme_mod('cake_footer_payment_logo'));

								   foreach ($img as $theurl){
									   
									  $output .= '<img src="'.esc_url($theurl).'" alt="" />'; 

								   }
								   $output .='</div>';
							   }
							}
							
							
					  $output .='</div>';
					  
					  if($footercolumn=="4column"){
					  $output .='<div class="'.esc_attr($col4).'">';
							
							if ( is_active_sidebar( 'cake-footer4' ) ) :
								ob_start();
								dynamic_sidebar( 'cake-footer4' );
								$output .= ob_get_clean();
							else :
								$output .='<span style="font-size:12px;">'.esc_html__('Add widget to WP Admin &rarr; Appearance &rarr; Widgets &rarr; Footer4','cake').'</span>';  
							endif;
							
							if(get_theme_mod('cake_footer_payment_logo')){ 
							   $output .='<div class="footer-payment-logo">';
							   
							   $output .= '<p class="payment_text">'.get_theme_mod('cake_footer_payment_text', esc_html__('Payment Method', 'cake')).'</p>';
							   
							   $img = explode(',', get_theme_mod('cake_footer_payment_logo'));

							   foreach ($img as $theurl){
								   
								  $output .= '<img src="'.esc_url($theurl).'" alt="" />'; 

							   }
							   $output .='</div>';
							}
							
							
					  $output .='</div>';

					  
					  }
					  
					  
					  if(get_theme_mod('cake_footer_designby_logo')){ 
						   $output .='<div class="footer-designby-logo">';
						  
						   $designbyimg = get_theme_mod('cake_footer_designby_logo', '');

							  $output .= '<img src="'.esc_url($designbyimg).'" alt="" />'; 

						   $output .='</div>';
					 }
					  
					  
					  
					$output .='</div>'; //end content-about-footer
					
					}//endif $footerwidget
					
            
          $output .='</div><!--/.container -->';
    $output .='</footer>';
	
	echo $output;
	
}
add_action('cake_footer_section', 'cake_get_footer_section');
endif;

/*====================================================================================================
Social Icon Function
======================================================================================================*/
if( !function_exists('cake_get_social_icon')):
function cake_get_social_icon(){
	
	$get_social_links = array('cake_rss'=>'rss','cake_facebook'=>'facebook','cake_twitter'=>'twitter','cake_instagram'=>'instagram', 'cake_linkedin'=>'linkedin', 'cake_flickr'=>'flickr','cake_google-plus'=>'google-plus', 'cake_dribbble'=>'dribbble' , 'cake_pinterest'=>'pinterest', 'cake_github'=>'github', 'cake_youtube'=>'youtube', 'cake_vimeo'=>'vimeo', 'cake_tumblr'=>'tumblr', 'cake_behance'=>'behance', 'cake_vk'=>'vk', 'cake_xing'=>'xing', 'cake_soundcloud'=>'soundcloud');
	
	$output ='';
	  $output .='<ul class="cake-social-icon">';
	  
		if($get_social_links){
			foreach($get_social_links as $social_link => $social_name) {
				if(get_theme_mod($social_link)) { 
					$output .='<li><a href="'. esc_url(get_theme_mod($social_link)) .'" title="'. esc_attr($social_name) .'" class="'.esc_attr($social_name).'-bgcolor"  target="_blank"><i class="fa fa-'.esc_attr($social_name).'"></i></a></li>';
				}								
			}
			if(get_theme_mod('cake_skype')) { 
			$output .='<li><a href="skype:'. get_theme_mod('cake_skype') .'?call" title="'. esc_attr(get_theme_mod('cake_skype')) .'" class="skype-bgcolor"  target="_blank"><i class="fa fa-skype"></i></a></li>';
			}	
		}		
		
		
	 $output .='</ul>';

	echo $output;
	
}
add_action('cake_social_icon', 'cake_get_social_icon');
endif;
?>