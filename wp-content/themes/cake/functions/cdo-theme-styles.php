<?php
/*====================================================================================================
Load Google Font
======================================================================================================*/
if (!function_exists('cake_theme_slug_fonts_url')):
function cake_theme_slug_fonts_url() {
	
    $fonts_url = '';
	
	$bodyfont = get_theme_mod('cake_body_typo', 'Lato');
	$menufont = get_theme_mod('cake_menu_typo', 'Lato');
	$logofont = get_theme_mod('cake_logo_typo', 'Pacifico');
	$h1font = get_theme_mod('cake_h1_typo', 'Montserrat');
	$h2font = get_theme_mod('cake_h2_typo', 'Montserrat');
	$h3font = get_theme_mod('cake_h3_typo', 'Montserrat');
	$h4font = get_theme_mod('cake_h4_typo', 'Montserrat');
	$h5font = get_theme_mod('cake_h5_typo', 'Montserrat');
	$h6font = get_theme_mod('cake_h6_typo', 'Montserrat');

    $bodyfontshow = _x( 'on', $bodyfont.' font: on or off', 'cake' );
	$menufontshow = _x( 'on', $menufont.' font: on or off', 'cake' );
	$logofontshow = _x( 'on', $logofont.' font: on or off', 'cake' );
	$h1fontshow = _x( 'on', $h1font.' font: on or off', 'cake' );
	$h2fontshow = _x( 'on', $h2font.' font: on or off', 'cake' );
	$h3fontshow = _x( 'on', $h3font.' font: on or off', 'cake' );
	$h4fontshow = _x( 'on', $h4font.' font: on or off', 'cake' );
	$h5fontshow = _x( 'on', $h5font.' font: on or off', 'cake' );
	$h6fontshow = _x( 'on', $h6font.' font: on or off', 'cake' );
	
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'cake' );
	$pacifico = _x( 'on', 'Pacifico font: on or off', 'cake' );
 
 
 
    if ( 'off' !== $bodyfontshow || 'off' !== $menufontshow || 'off' !== $logofontshow || 'off' !== $h1fontshow || 'off' !== $h2fontshow || 'off' !== $h3fontshow || 'off' !== $h4fontshow || 'off' !== $h5fontshow || 'off' !== $h6fontshow || 'off' !== $montserrat || 'off' !== $pacifico ) {
		
        $font_families = array();
 
        if ( 'off' !== $bodyfontshow ) {
            $font_families[] = $bodyfont.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
 
		if ( 'off' !== $menufontshow ) {
            $font_families[] = $menufont.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $logofontshow ) {
            $font_families[] = $logofont.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h1fontshow ) {
            $font_families[] = $h1font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h2fontshow ) {
            $font_families[] = $h2font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h3fontshow ) {
            $font_families[] = $h3font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h4fontshow ) {
            $font_families[] = $h4font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h5fontshow ) {
            $font_families[] = $h5font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $h6fontshow ) {
            $font_families[] = $h6font.':400,300,400italic,300italic,600,600italic,700,700italic,800,800italic';
        }
		
		if ( 'off' !== $montserrat ) {
				$font_families[] = 'Montserrat:400,400italic,700,700italic';
		}
		
		if ( 'off' !== $pacifico ) {
				$font_families[] = 'Pacifico:400';
		}
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext,cyrillic,cyrillic-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}
endif;	

/*====================================================================================================
Load CSS
======================================================================================================*/
if (!function_exists('cake_add_stylesheet')):
function cake_add_stylesheet() {
   if (!is_admin()) {
	   
    global $post;
		$mdt_pid = cake_get_postid();
		$theID = ( isset( $post->ID ) ? $mdt_pid : "" );

		$sliderchoose = get_post_meta($theID, 'cake_slider_choose', true);
	   
	// Register google font
	wp_enqueue_style( 'cake-theme-fonts', cake_theme_slug_fonts_url(), array(), null );
	
	
	if(!class_exists('CDOShortcodes')){
		wp_enqueue_style('fontawesome', get_template_directory_uri() . '/css/fontawesome-cdn.css', '', '', 'screen, all');
		
	}

	// Register and Print CSS
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', '', null, 'screen, all');
	wp_enqueue_style('cake-global', get_template_directory_uri() . '/css/global.css', '', null, 'screen, all');
	wp_enqueue_style('cake-effect', get_template_directory_uri() . '/css/effect.css', '', null, 'screen, all');
	wp_enqueue_style('cake-audio', get_template_directory_uri() . '/css/audio.css', '', null, 'screen, all');
	wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/css/owl-carousel.css', '', null, 'screen, all');
	wp_enqueue_style('owl-theme', get_template_directory_uri() . '/css/owl.theme.default.min.css', '', null, 'screen, all');
	wp_enqueue_style('cake-woocommerce',  get_template_directory_uri() . '/css/woocommerce-theme.css', '', null, 'screen, all');
	wp_enqueue_style('cake-slideshow', get_template_directory_uri() . '/css/slideshow.css', '', null, 'screen, all');	
	wp_enqueue_style('cake-main', get_template_directory_uri() . '/style.css', '', null, 'all');
	
	if($sliderchoose=="slick-slider"){
		wp_enqueue_style('cdo-slick');
		wp_enqueue_style('cdo-slick-theme');
		
	}
	
	$responsive = get_theme_mod('cake_responsive_layout', 'true');
	if($responsive=='true'):
	wp_enqueue_style('cake-responsive', get_template_directory_uri() . '/css/responsive.css', '', null, 'screen, all');
	endif;

  }

}
add_action('wp_enqueue_scripts', 'cake_add_stylesheet');
endif;


/*====================================================================================================
Deregister Style
======================================================================================================*/
if (!function_exists('cake_deregister_styles')):
function cake_deregister_styles() {
    wp_deregister_style('mediaelement');
    wp_deregister_style('wp-mediaelement');
	wp_dequeue_style('cdo-animate');
}
add_action( 'wp_enqueue_scripts', 'cake_deregister_styles', 25);
endif;

/*====================================================================================================
Admin CSS
======================================================================================================*/
if (!function_exists('cake_admin_style')) :
function cake_admin_style() {
	wp_enqueue_style( 'cake-admin', get_template_directory_uri() . '/css/admin.css', false, '' );
}
add_action( 'admin_enqueue_scripts', 'cake_admin_style');
endif;

/*====================================================================================================
Print Custom CSS
======================================================================================================*/
if (!function_exists('cake_add_custom_style')) :
function cake_add_custom_style(){
	
	wp_enqueue_style('cake-custom', get_template_directory_uri() . '/css/custom.css', '', null, 'screen, all');
	
	$custom_css = cake_custom_css();
	wp_add_inline_style( 'cake-custom', $custom_css);
	
	wp_enqueue_style('cake-overwrite', get_template_directory_uri() . '/overwrite.css', '', null, 'screen, all');
	
}
add_action('wp_enqueue_scripts', 'cake_add_custom_style');
endif;


/*====================================================================================================
  Convert Hex Color to RGB
======================================================================================================*/
if(!function_exists("cake_color_reator")):
function cake_color_reator($color, $per) 
{ 
	$color = substr( $color, 1 ); // Removes first character of hex string (#)
	$rgb = ''; // Empty variable 
	$per = $per/100*255; // Creates a percentage to work with. Change the middle figure to control colour temperature
	 
	if  ($per < 0 ) // Check to see if the percentage is a negative number 
	{ 
		// DARKER 
		$per =  abs($per); // Turns Neg Number to Pos Number 
		for ($x=0;$x<3;$x++) 
		{ 
			$c = hexdec(substr($color,(2*$x),2)) - $per; 
			$c = ($c < 0) ? 0 : dechex($c); 
			$rgb .= (strlen($c) < 2) ? '0'.$c : $c; 
		}   
	}  
	else 
	{ 
		// LIGHTER         
		for ($x=0;$x<3;$x++) 
		{             
			$c = hexdec(substr($color,(2*$x),2)) + $per; 
			$c = ($c > 255) ? 'ff' : dechex($c); 
			$rgb .= (strlen($c) < 2) ? '0'.$c : $c; 
		}    
	} 
	return '#'.$rgb; 
}
endif;

/*====================================================================================================
  Convert Hex Color to RGB
======================================================================================================*/
if (!function_exists('cake_hex_to_RGB')) :
function cake_hex_to_RGB($Hex){
   
if (substr($Hex,0,1) == "#")
    $Hex = substr($Hex,1);

$R = substr($Hex,0,2);
$G = substr($Hex,2,2);
$B = substr($Hex,4,2);

$R = hexdec($R);
$G = hexdec($G);
$B = hexdec($B);

$RGB['R'] = $R;
$RGB['G'] = $G;
$RGB['B'] = $B;

return $RGB;

}
endif;


/*====================================================================================================
  Custom CSS
======================================================================================================*/
if(!function_exists("cake_custom_css")):
	function cake_custom_css() {
		global $post;
		$getpid = cake_get_postid();
		$theID = ( isset( $post->ID ) ? $getpid : "" );
		
		$darkPercent = -7;
		$lightPercent = 7;
		
		$predifinedcolor = get_theme_mod('cake_predifined_skin_color', 'pink');
		$customskincolor = get_theme_mod('cake_custom_skin_color','#f88c91');
		
		$primarycolor = ($predifinedcolor=='pink' ? '#f88c91' : ($predifinedcolor=='green' ? '#36dfb8' : ($predifinedcolor=='blue' ? '#59d4f0' : ($predifinedcolor=='orange' ? '#ffbb63' : ($predifinedcolor=='purple' ? '#a593c2' :  ($predifinedcolor=='custom' ? $customskincolor: '#f88c91'))))));
		
		$hoverbg = cake_hex_to_RGB($primarycolor);

		// Background Body
		$bgcolor = get_theme_mod('cake_body_background', '#f4f3ef');
		$bgimage = get_theme_mod('cake_body_background_image', '');
		$bgrepeat = get_theme_mod('cake_body_background_repeat', '');
		$bgposition = get_theme_mod('cake_body_background_position', '');
		$bgsize= get_theme_mod('cake_body_background_size', '');
		$bgattachment= get_theme_mod('cake_body_background_attachment', '');
				

		// Font Options
		$bodyfont = get_theme_mod('cake_body_typo', 'Lato');
		$bodyfcolor = get_theme_mod('cake_body_fc', '#a1a2a6');
		$bodyfstyle = get_theme_mod('cake_body_fw', 'normal');
		$bodyfsize = get_theme_mod('cake_body_fs', '14');
		$bodyflh = get_theme_mod('cake_body_flh', '25');
		
		$logofont = get_theme_mod('cake_logo_typo', 'Pacifico');
		$logofstyle = get_theme_mod('cake_logo_fw', 'normal');
		$logofsize = get_theme_mod('cake_logo_fs', '16');
		$logoflh = get_theme_mod('cake_logo_flh', '22');
		$logocolor = get_theme_mod('cake_logo_fc', '#a1a2a6');
		
		$menufont = get_theme_mod('cake_menu_typo', 'Lato');
		$menufcolor = get_theme_mod('cake_menu_fc', '#a1a2a6');
		$menufstyle = get_theme_mod('cake_menu_fw', 'normal');
		$menufsize = get_theme_mod('cake_menu_fs', '16');
		$menuflh = get_theme_mod('cake_menu_flh', '22');
			
		
		$TagTypes    = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');


		$i=1;
		foreach($TagTypes as $type){
			
		  $fs = ($i==1 ? '45' : ($i==2 ? '40' :($i==3 ? '24' : ($i==4 ? '18' : ($i==5 ? '14' : '12')))));
		  $flh = ($i==1 ? '50' : ($i==2 ? '45' :($i==3 ? '30' : ($i==4 ? '25' : ($i==5 ? '25' : '23')))));
		  
		  $headingfont[] = get_theme_mod('cake_h'.$i.'_typo', 'Montserrat');
		  $headingfcolor[] = get_theme_mod('cake_h'.$i.'_fc', '#767676');
		  $headingfstyle[] = get_theme_mod('cake_h'.$i.'_fw', 'bold');
		  $headingfsize[] = get_theme_mod('cake_h'.$i.'_fs', $fs);
		  $headingflh[] = get_theme_mod('cake_h'.$i.'_flh', $flh);
		  $i++;
		}		

		$loader = get_theme_mod('cake_loader_effect', 'true');
		$getbody = ($loader=='true' ? '.layout-switch' : '');
		
		// Print Custom CSS
	  
		$custom_css = '';
		
		//loader
		$custom_css .=  '.ip-header .ip-loader svg path.ip-loader-circle{stroke:'.$primarycolor.'}';
		

		//body background color & background image	
		if($bgcolor!="" || $bgimage!=""){
		$getbodybgimg = ($bgimage!=''?'background-image:url('. $bgimage.')':'');
		
		$custom_css .=  'body'.$getbody.'{ 
									background-color: '.$bgcolor.';
									'.$getbodybgimg .';
									background-repeat: '.$bgrepeat.';
									background-position: '.$bgposition.';
									background-size: '.$bgsize.';
									background-attachment : '.$bgattachment.';
								}'."\n";
		}
		

		
		//heading
		$custom_css .=  'h1{color:'.$headingfcolor[0].' ; font: '.$headingfsize[0].'px "'.$headingfont[0].'";  font-weight: '.$headingfstyle[0].';  line-height: '.$headingflh[0].'px;}';
		$custom_css .=  'h2{color:'.$headingfcolor[1].' ; font: '.$headingfsize[1].'px "'.$headingfont[1].'";  font-weight: '.$headingfstyle[1].';  line-height: '.$headingflh[1].'px;}';
		$custom_css .=  'h3{color:'.$headingfcolor[2].' ; font: '.$headingfsize[2].'px "'.$headingfont[2].'";  font-weight: '.$headingfstyle[2].';  line-height: '.$headingflh[2].'px;}';
		$custom_css .=  'h4{color:'.$headingfcolor[3].' ; font: '.$headingfsize[3].'px "'.$headingfont[3].'";  font-weight: '.$headingfstyle[3].';  line-height: '.$headingflh[3].'px;}';
		$custom_css .=  'h5{color:'.$headingfcolor[4].' ; font: '.$headingfsize[4].'px "'.$headingfont[4].'";  font-weight: '.$headingfstyle[4].';  line-height: '.$headingflh[4].'px;}';
		$custom_css .=  'h6{color:'.$headingfcolor[5].' ; font: '.$headingfsize[5].'px "'.$headingfont[5].'";  font-weight: '.$headingfstyle[5].';  line-height: '.$headingflh[5].'px;}';
		
		$custom_css .= '.widget-area .widget-title, .widget-area .widgettitle, .widget-area ul li:before, h2.entry-title a, h2.entry-title a:visited, a, a:hover{color:'.$primarycolor.'}';
		
		//font
		$custom_css .='body{color:'.$bodyfcolor.' ; font: '.$bodyfsize.'px '.$bodyfont.';  font-weight: '.$bodyfstyle.';  line-height: '.$bodyflh.'px;}';
		$custom_css .='.navbar-brand-cake .logo-text h1{font-size: '.$logofsize.';  line-height: '.$logoflh.';  font-family: '.$logofont.';}';
		$custom_css .='.navbar-brand-cake .logo-text h1, .navbar-brand-cake .logo-text h1 a{color:'.$logocolor.'}';
		$custom_css .='#menu a {color: '.$menufcolor.';  font-size: '.$menufsize.'px;  line-height: '.$menuflh.'px;  font-family: '.$menufont.'; font-weight:'.$menufstyle.'}';
		
		//header
		$animationlogo = get_theme_mod('cake_animation_logo','true');
		$shapelogo = get_theme_mod('cake_shape_logo','true');
		
		$custom_css .= '.header-nav li{background:'.$primarycolor.';  -webkit-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
	-moz-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
	box-shadow: 0px 0px 0px 0px '.$primarycolor.'}';
		
		if($shapelogo=='false'){
			$custom_css .= '.header-nav li{background:transparent}';
		}
		
		if($animationlogo=='true'){
	    $custom_css .= '@keyframes logos {
		  from {
			-webkit-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
			-moz-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
			box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		  }
		  to {
			-webkit-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
			-moz-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
			box-shadow: 0px 0px 0px 0px '.$primarycolor.';
		  }
		}';
		}
		
		//page header
		$bgdefault='';
		$bg ='';
		$pageheaderborder ='';
		
		$bgdefault = get_theme_mod('cake_page_header_img', '');		
		$bg = wp_get_attachment_image_src( get_post_meta( $theID, 'cake_page_header_img_id', 1 ), 'full' );
		$hbgpositionmeta = get_post_meta($theID, 'cake_page_background_position', true);
		
		if($bg!=""){
			$getbg = esc_url($bg[0]);
		}else{
			
			if($bgdefault!=""){
				$getbg = esc_url($bgdefault);
			}elseif($predifinedcolor=='pink'){
				/*$getbg = get_template_directory_uri().'/images/dot-pink.png';
				$pageheaderborder = get_template_directory_uri().'/images/hb-pink.png';*/
			}elseif($predifinedcolor=='green'){
				$getbg = get_template_directory_uri().'/images/dot-green.png';
				$pageheaderborder = get_template_directory_uri().'/images/hb-green.png';
			}elseif($predifinedcolor=='blue'){
				$getbg = get_template_directory_uri().'/images/dot-blue.png';
				$pageheaderborder = get_template_directory_uri().'/images/hb-blue.png';
			}elseif($predifinedcolor=='orange'){
				$getbg = get_template_directory_uri().'/images/dot-orange.png';
				$pageheaderborder = get_template_directory_uri().'/images/hb-orange.png';
			}elseif($predifinedcolor=='purple'){
				$getbg = get_template_directory_uri().'/images/dot-purple.png';
				$pageheaderborder = get_template_directory_uri().'/images/hb-purple.png';
			}elseif($predifinedcolor=='custom'){
				$getbg = '';
				$pageheaderborder = '';
			}else{
				$getbg ='';
				$pageheaderborder = '';
			}
		}
		//add
		if($hbgpositionmeta=="left"){
				$hbgposition ='left center !important';
			}elseif($layout=="right"){
				$hbgposition ='right center !important';
			}else{		
				$hbgposition ='center !important';	
			}
		
		$custom_css .= '.page-header{background:'.$primarycolor.' url('.$getbg.') }';
		$custom_css .= '.page-header{background-position:'.$hbgposition.'}';
		$pageheadercustomborder = cake_color_reator($primarycolor, $darkPercent);
		if($bg!=''){
		$custom_css .= '.page-header-border{background:transparent}';	
		}else{
		$custom_css .= '.page-header-border{background:'.$pageheadercustomborder.' url('.$pageheaderborder.')}';
		}
		
			
		
		//menu
		$menucolumn = get_theme_mod('cake_nav_column', '3');
		$getmenucolumn = ($menucolumn=="1" ? '100%': ($menucolumn=="2" ? '48%' : ($menucolumn=="4" ? '22%' : '29.8%')));
		$getmarginmenu = ($menucolumn=="1" ? '0px': ($menucolumn=="2" ? '20px' : ($menucolumn=="4" ? '21px' : '28px')));
		$menubghover = cake_color_reator($primarycolor, $darkPercent);
		$custom_css .='#nav-popup #menu li.addcol{width:'.$getmenucolumn.'}';
		$custom_css .='#nav-popup #menu li.addcol{margin-right:'.$getmarginmenu.'}';
		$custom_css .='#nav-popup #menu li.addcol:nth-child('.$menucolumn.'n){margin-right:0}';
		$custom_css .='#nav-popup .sub-menu li:hover,#nav-popup .children li:hover, #nav-popup .current_page_item{background:'.$menubghover.'}';
		$custom_css .='.nav-container{-webkit-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		-moz-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		box-shadow: 0px 0px 32px 0px '.$primarycolor.';}';
		$custom_css .='.ip-container.boxed .nav-container{-webkit-box-shadow: 0 15px 15px -15px '.$primarycolor.';
		-moz-box-shadow: 0 15px 15px -15px '.$primarycolor.';
		box-shadow: 0 15px 15px -15px '.$primarycolor.';}';
		$custom_css .='.hover-shadow {
		  -webkit-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
		  -moz-box-shadow: 0px 0px 0px 0px '.$primarycolor.';
		  box-shadow: 0px 0px 0px 0px '.$primarycolor.';
		}
		.hover-shadow:hover {
		  -webkit-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		  -moz-box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		  box-shadow: 0px 0px 32px 0px '.$primarycolor.';
		}';
		
		//slideshow
		if($predifinedcolor=='green'){
			$bgslide = get_template_directory_uri().'/images/bg-slick-slider-green.png';
		}elseif($predifinedcolor=='blue'){
			$bgslide = get_template_directory_uri().'/images/bg-slick-slider-blue.png';
		}elseif($predifinedcolor=='orange'){
			$bgslide = get_template_directory_uri().'/images/bg-slick-slider-orange.png';
		}elseif($predifinedcolor=='purple'){
			$bgslide = get_template_directory_uri().'/images/bg-slick-slider-purple.png';
		}else{
			$bgslide = get_template_directory_uri().'/images/bg-slick-slider.png';
		}
		$custom_css .= '#slideshow-container.slick-slider{background:url('.esc_url($bgslide).') repeat center center}';
		$shadow = cake_color_reator($primarycolor, $darkPercent);
		$custom_css .= '.banner-slick h2{text-shadow: -1px 3px 0px '.$shadow.';}';
		
		//content
		$custom_css	.= '.modal-fullscreen .modal-content{background:rgba('.$hoverbg['R'].', '.$hoverbg['G'].', '.$hoverbg['B'].', .95)}';
		$custom_css .= '.article-post .post-time, .more-link, .nav-previous a, .nav-next a, .wp-pagenavi a:hover, .wp-pagenavi .current, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current,.cake-tag-cloud a, .cake-tag-cloud a:visited, #commentform #submit, .image-post .owl-theme .owl-controls .owl-buttons div, .cake-product-img .cake-btn-container, .woocommerce div.product .summary .stock{background:'.$primarycolor.'}';
		$custom_css .= '.notfound-title, .colortext, .pf-title, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce ul.products li.product .price{color:'.$primarycolor.'}';
		$custom_css .= 'i.colortext, .woocommerce .star-rating span{color:'.$primarycolor.' !important}';
		$custom_css .= '.pf-button-container{background:rgba('.$hoverbg['R'].', '.$hoverbg['G'].', '.$hoverbg['B'].', .5)}';

						
		if($predifinedcolor!="pink"){
		$butcakehover = cake_color_reator($primarycolor, $darkPercent);
		$custom_css .= 'input[type="submit"].button-cake,
						input[type="button"].button-cake,
						button.button-cake{background:'.$primarycolor.'}';
		$custom_css .= 'input[type="submit"].button-cake:hover,
						input[type="button"].button-cake:hover,
						button.button-cake:hover, .cake-btn-container a{background:'.$butcakehover.'}';
		}
		
		$cakewoobutton = cake_color_reator($primarycolor, $darkPercent);
		$custom_css .= '.cake-woo-button span, .add_to_wishlist span, .yith-wcwl-wishlistaddedbrowse a span, .yith-wcwl-wishlistexistsbrowse a span{background:'.$cakewoobutton.'}';
		$custom_css .='.cake-dropdown-cart, #menu_left .sub-menu, #menu_right .sub-menu{background:'.$primarycolor.'; border-color:'.$cakewoobutton.'}';
		$custom_css .='.widget-area .woocommerce.widget_shopping_cart, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .reset_variations, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .reset_variations,
.single-product .entry-summary .yith-wcwl-add-to-wishlist a,
.single-product .entry-summary a.compare, .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .button, button, input[type="submit"], .cdo-button, .navbar-brand-cake .show-menu:hover, .woocommerce span.onsale, a.button-back-slice, a.button-next-slice{background:'.$primarycolor.';}';
		$custom_css .='.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .reset_variations:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover .woocommerce input.button:hover, .reset_variations:hover,
.single-product .entry-summary .yith-wcwl-add-to-wishlist a:hover,
.single-product .entry-summary a.compare:hover, .woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover, .button:hover, button:hover, input[type="submit"]:hover, .cdo-button:hover{background:'.$cakewoobutton.';}';
		/*$custom_css .='.woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce ul.products li.product .price, .wishlist_table ins{color:'.$primarycolor.'}';*/
		$custom_css .='.cake-woo-button span::after, .add_to_wishlist span::after, .yith-wcwl-wishlistaddedbrowse a span::after, .yith-wcwl-wishlistexistsbrowse a span::after{border-color: '.$cakewoobutton.' transparent transparent transparent;}';
		$custom_css .='.woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .sticky { border-color:'.$primarycolor.'}';
		
		$custom_css .='.yith-wcwl-share ul li a{background-color:'.$primarycolor.';}';
		$custom_css .='.yith-wcwl-share ul li a:hover, #nav-popup .cake-social-icon li:hover{background-color:'.$cakewoobutton.';}';
		
		//overwrite css from codeopus plugin
		$custom_css .='.resp-tabs-list li:hover, h4.resp-accordion:hover, h4.resp-accordion.resp-tab-active, h4.resp-accordion.resp-tab-active:hover, h4.resp-accordion.resp-tab-active:active, .resp-tabs-list li.resp-tab-active, .resp-tabs-list li.resp-tab-active:hover, .resp-tabs-list li.resp-tab-active:active,.cdo-toggle .ui-accordion-header-active, .cdo-accordion .ui-accordion-header-active, .cdo-toggle.bgtransparent .ui-accordion-header-active, .cdo-accordion.bgtransparent .ui-accordion-header-active,.cdo-dropcap.square, .cdo-dropcap.round{background:'.$primarycolor.'; border-color:'.$primarycolor.'}';
		
		$custom_css .='.cdo-dropcap, .head-title, .testi-name, .cdo-team-section h5{color:'.$primarycolor.'}';
		$lightborder = cake_color_reator($primarycolor, $lightPercent);
		$custom_css .='.cdo-team-section h5::after{background:'.$lightborder.';}';

		//footer
		$footercolor = get_theme_mod('footer_color','#ffffff');
		$socialiconbg = cake_color_reator($primarycolor, $lightPercent);
		$socialiconbghover = cake_color_reator($primarycolor, $darkPercent);
		
		$custom_css .='#footer, #scrollUp{background:'.$primarycolor.'; color:'.$footercolor.'}';
		$custom_css .='#footer ul li a, #footer #wp-calendar a, #footer a, #footer .popular-date{color:'.$footercolor.'}';
		$custom_css .='#footer .cake-social-icon li{background:'.$socialiconbg.';}';
		$custom_css .='#footer .cake-social-icon li:hover{background:'.$socialiconbghover.';}';
		
		return $custom_css;
		
	}
endif;
?>