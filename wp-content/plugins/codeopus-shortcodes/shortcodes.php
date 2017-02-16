<?php
if (!function_exists('codeopus_run_shortcode')) {
function codeopus_run_shortcode( $content ) {
    global $shortcode_tags;
 
    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes();

	add_shortcode('cdo_section', 'codeopus_section');
	add_shortcode('cdo_row', 'codeopus_row');
	add_shortcode('cdo_col_12', 'codeopus_col_12');
	add_shortcode('cdo_col_6', 'codeopus_col_6');
	add_shortcode('cdo_col_4', 'codeopus_col_4');
	add_shortcode('cdo_col_3', 'codeopus_col_3');
	add_shortcode('cdo_col_2', 'codeopus_col_2');
	add_shortcode('cdo_col_9', 'codeopus_col_9');
	add_shortcode('cdo_col_8', 'codeopus_col_8');

	
	add_shortcode('cdo_row_nested', 'codeopus_row');
	add_shortcode('cdo_col_12_nested', 'codeopus_col_12');
	add_shortcode('cdo_col_6_nested', 'codeopus_col_6');
	add_shortcode('cdo_col_4_nested', 'codeopus_col_4');
	add_shortcode('cdo_col_3_nested', 'codeopus_col_3');
	add_shortcode('cdo_col_2_nested', 'codeopus_col_2');
	add_shortcode('cdo_col_9_nested', 'codeopus_col_9');
	add_shortcode('cdo_col_8_nested', 'codeopus_col_8');
	
    add_shortcode('cdo_wp_menu', 'codeopus_wp_menu');
    add_shortcode('cdo_div', 'codeopus_div');
    add_shortcode('cdo_end_div', 'codeopus_div_closed');
    add_shortcode('cdo_code', 'codeopus_code');
	add_shortcode('cdo_button', 'codeopus_button');
	add_shortcode('cdo_heading', 'codeopus_heading');
	add_shortcode('cdo_span', 'codeopus_span');
	add_shortcode('cdo_small', 'codeopus_small');
	add_shortcode('cdo_dropcap', 'codeopus_drop_cap');
	add_shortcode('cdo_pullquote', 'codeopus_pullquote');
	add_shortcode('cdo_paragraph', 'codeopus_paragraph_text');
	add_shortcode('cdo_list', 'codeopus_list');
	add_shortcode('cdo_icon', 'codeopus_icons');
	add_shortcode('cdo_toggle', 'codeopus_toggle');
	add_shortcode('cdo_accordion', 'codeopus_accordion');
	add_shortcode('cdo_tabs', 'codeopus_tabs');
	add_shortcode('cdo_tab', 'codeopus_tab');
	add_shortcode('cdo_table', 'codeopus_table');
	add_shortcode('cdo_gmap','codeopus_googlemap');
	add_shortcode('cdo_spacer', 'codeopus_spacer');
	add_shortcode('cdo_clear', 'codeopus_clearfloat');
	
	add_shortcode('cdo_img_withtext', 'codeopus_img_withtext');
	add_shortcode('cdo_box_withtext', 'codeopus_box_withtext');
	add_shortcode('cdo_icon_withtext', 'codeopus_icon_withtext');
	add_shortcode('cdo_banner', 'codeopus_banner');
	add_shortcode('cdo_banner_inner', 'codeopus_banner_inner');
	add_shortcode('cdo_products', 'codeopus_products');
	add_shortcode('cdo_product_item', 'codeopus_product_item');
	add_shortcode('cdo_product_cat', 'codeopus_product_category');
	add_shortcode('cdo_products_slider', 'codeopus_products_slider');
	add_shortcode('cdo_lookbook_header', 'codeopus_lookbook_header');
	add_shortcode('cdo_lookbook', 'codeopus_lookbook');
	add_shortcode('cdo_lookbook_footer', 'codeopus_lookbook_footer');
	add_shortcode('cdo_testimonial', 'codeopus_testimonial_shortcode');
	add_shortcode('cdo_team', 'codeopus_team_shortcode');
	add_shortcode('cdo_portfolio', 'codeopus_portfolio_shortcode');
	
	add_shortcode('cdo_cake_messes_item', 'codeopus_cake_messes_item_shortcode');
	add_shortcode( 'cdo_pricing', 'codeopus_pricing_table_shortcode' );
	add_shortcode( 'cdo_pricing_item', 'codeopus_pricing_shortcode' );
	add_shortcode( 'cdo_newsflash', 'codeopus_newsflash_shortcode' );
	add_shortcode( 'cdo_newsflash_item', 'codeopus_newsflash_item_shortcode' );
	add_shortcode( 'cdo_testimonial_slider', 'codeopus_testimonial_slider_shortcode' );
	add_shortcode('cdo_product_category', 'codeopus_product_category2');
	
	
    // Do the shortcode (only the one above is registered)
    $content = do_shortcode( $content );
 
    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;
 
    return $content;
}
add_filter( 'the_content', 'codeopus_run_shortcode', 7 );
}

/* ======================================
   Section
   ======================================*/
if (!function_exists('codeopus_section')) {   
function codeopus_section( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'id'	=> '',
		'container'	=> 'true',
		'triangle_top'	=> 'false',
		'triangle_bottom'	=> 'false',
		'margin_bottom' => '',
		'bgcolor'	=> '',
		'bgimage'	=> '',
		'bgrepeat'	=> 'repeat',
		'bgposition'	=> 'left bottom',
		'border_top_color'	=> '',
		'border_top_height'	=> '',
		'border_bottom_color'	=> '',
		'border_bottom_height'	=> '',
		'class'	=> '',
		'style'	=> ''
	), $atts));
	
	$id = ( $id!="" ? 'id='.esc_attr($id).'' : '');
	$class = ( $class!="" ? esc_attr($class) : '');
	
	$bg = ($bgcolor!="" || $bgimage!="" ? 'background:' . esc_attr($bgcolor).' url('.esc_url($bgimage).') '.esc_attr($bgposition).' '.$bgrepeat.';' : '');
	
	$marginbottom = ($margin_bottom=="no" || $margin_bottom=="false" ? 'margin-bottom:0px;' : '');
	$bordertopcolor = ($border_top_color != "" ? 'border-top:solid '.$border_top_height.'px '. $border_top_color .';' : '');
	$borderbottomcolor = ($border_bottom_color != "" ? 'border-bottom:solid '.$border_bottom_height.'px '. $border_bottom_color .';' : '');
	$nopadding = ($bgcolor =='' ? 'nopadding' : '');
	$hasbgimage = ($bgimage !='' ? 'hasbgimage' : '');
	
	
	$div = ($container=="true" || $container=="yes" ? '<div class="container">' : '');
	$div2 = ($container=="true" || $container=="yes" ? '</div>' : '');
	
	$triangletop = ($triangle_top=="true" || $triangle_top=="yes" ? '<div class="triangle-top"></div>' : '');
	$trianglebottom = ($triangle_bottom=="true" || $triangle_bottom=="yes" ? '<div class="triangle-bottom"></div>' : '');
	$hastriangletop = ($triangle_top=="true" || $triangle_top=="yes" ? 'hastriangletop' : '');
	$hastrianglebottom = ($triangle_bottom=="true" || $triangle_bottom=="yes" ? 'hastrianglebottom' : '');
	
	
	$out ='';
	$out.='<div '.esc_attr($id).' class="cdo-section '.esc_attr($class).' '.esc_attr($nopadding).' '.esc_attr($hastriangletop).' '.esc_attr($hastrianglebottom).' '.esc_attr($hasbgimage).'" style="'.esc_attr($bg). ' '. esc_attr($marginbottom).' '.esc_attr($bordertopcolor).' '.esc_attr($borderbottomcolor).'">';
		$out.= $triangletop;
		$out.= $div;
		$out.= do_shortcode($content);
		$out.= $div2;
		$out.= $trianglebottom;		
	$out.='</div>';
	
	return $out;
	
}
add_shortcode('cdo_section', 'codeopus_section');
}

/* ======================================
   Columns
   ======================================*/
if (!function_exists('codeopus_row')) {   
function codeopus_row( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	
	return '<div class="row '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
	
}
add_shortcode('cdo_row', 'codeopus_row');
add_shortcode('cdo_row_nested', 'codeopus_row');
}

if (!function_exists('codeopus_col_12')) {    
function codeopus_col_12( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	
	return '<div class="col-sm-12 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_12', 'codeopus_col_12');
add_shortcode('cdo_col_12_nested', 'codeopus_col_12');
}

if (!function_exists('codeopus_col_6')) {    
function codeopus_col_6( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	
	return '<div class="col-sm-6 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_6', 'codeopus_col_6');
add_shortcode('cdo_col_6_nested', 'codeopus_col_6');
}

if (!function_exists('codeopus_col_4')) {    
function codeopus_col_4( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	return '<div class="col-sm-4 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_4', 'codeopus_col_4');
add_shortcode('cdo_col_4_nested', 'codeopus_col_4');
}

if (!function_exists('codeopus_col_3')) {    
function codeopus_col_3( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	return '<div class="col-sm-3 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_3', 'codeopus_col_3');
add_shortcode('cdo_col_3_nested', 'codeopus_col_3');
}

if (!function_exists('codeopus_col_8')) {    
function codeopus_col_8( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
	return '<div class="col-sm-8 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_8', 'codeopus_col_8');
add_shortcode('cdo_col_8_nested', 'codeopus_col_8');
}

if (!function_exists('codeopus_col_9')) {    
function codeopus_col_9($atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
   return '<div class="col-sm-9 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_9', 'codeopus_col_9');
add_shortcode('cdo_col_9_nested', 'codeopus_col_9');
}

if (!function_exists('codeopus_col_2')) {    
function codeopus_col_2($atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
		'animate'	=> ''
	), $atts));
   return '<div class="col-sm-2 cdocolumn '.esc_attr($class).' animation" data-animate="'.esc_attr($animate).'">' . do_shortcode($content) . '</div>';
}
add_shortcode('cdo_col_2', 'codeopus_col_2');
add_shortcode('cdo_col_2_nested', 'codeopus_col_2');
}


/* ======================================
   Wordpress Menu
   ======================================*/
if (!function_exists('codeopus_wp_menu')) {
function codeopus_wp_menu($atts, $content = null) {
	extract(shortcode_atts(array(  
		'menu'            => '', 
		'container'       => 'div', 
		'container_class' => '', 
		'container_id'    => '', 
		'menu_class'      => 'menu', 
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 1,
		'walker'          => '',
		'theme_location'  => ''), 
		$atts));

	return wp_nav_menu( array( 
		'menu'            => $menu, 
		'container'       => $container, 
		'container_class' => $container_class, 
		'container_id'    => $container_id, 
		'menu_class'      => $menu_class, 
		'menu_id'         => $menu_id,
		'echo'            => false,
		'fallback_cb'     => $fallback_cb,
		'before'          => $before,
		'after'           => $after,
		'link_before'     => $link_before,
		'link_after'      => $link_after,
		'depth'           => $depth,
		'walker'          => $walker,
		'theme_location'  => $theme_location));
}
add_shortcode("cdo_wp_menu", "codeopus_wp_menu");
}

/* ======================================
   Div
   ======================================*/
if (!function_exists('codeopus_div')) {
function codeopus_div($atts, $content = null ) {
	extract(shortcode_atts(array('class' => '', 'id' => '' ), $atts));
	$return = '<div';
	if (!empty($class)) $return .= ' class="'.esc_attr($class).'"';
	if (!empty($id)) $return .= ' id="'.esc_attr($id).'"';
	$return .= '>' . do_shortcode($content);
	return $return;
}
add_shortcode('cdo_div', 'codeopus_div');
}

if (!function_exists('codeopus_div_closed')) {
function codeopus_div_closed($atts) {
	return '</div>';
}
add_shortcode('cdo_end_div', 'codeopus_div_closed');
}

/* ======================================
   Code
   ======================================*/
if (!function_exists('codeopus_code')) {
function codeopus_code($atts, $content = null ) {
	$text = array("[", "]", "+");
	$textreplace = array("&#x5b;", "&#x5D;", "");
	$getcontent = str_replace($text , $textreplace ,$content);
	extract(shortcode_atts(array('class' => '', 'id' => '' ), $atts));
	$return = '<code';
	if (!empty($class)) $return .= ' class="'.esc_attr($class).'"';
	$return .= '>' .do_shortcode($getcontent).'</code>';
	return $return;
}
add_shortcode('cdo_code', 'codeopus_code');
}

/* ======================================
   Buttons 
   ======================================*/
if (!function_exists('codeopus_button')) { 
function codeopus_button( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'color'	=> '',
		'size'	=> '',
		'label'	=> '',
		'icon'	=> '',
		'link'	=> '#',
		'target'	=> '_blank',
		'class'	=> '',
		'type'	=> '',
	), $atts));
	
	if($target=="_blank" || $target==""){
	 $target="target=_blank";
	}else{
	 $target="target=_self";
	}
	
	if($icon=="" || $icon=="Select icon"){
	
	$geticon= "";
	
	
	}else{
	
	$geticon= "<span class='but_icon'><i class='".esc_attr($icon)."'></i></span>";
	
	}
	
	$out = "<a class='cdo-button ".esc_attr($color)." ".esc_attr($size)." ".esc_attr($class)." ".esc_attr($type)."' href='".esc_url($link)."' ".esc_attr($target).">".$geticon." <span class='but_label'>". $label ."</span></a>";
  
	return $out;
}
add_shortcode('cdo_button', 'codeopus_button');
}


/* ======================================
   Heading 
   ======================================*/
if (!function_exists('codeopus_heading')) { 
function codeopus_heading( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'tag'	=> 'h3',
		'subtitle'=> '',
		'subtitle_link'=> '',
		'class'	=> '',
	), $atts));
	
	$subtitle = ($subtitle_link!=""? '<a href="'.esc_url($subtitle_link).'">'.$subtitle.'</a>' : $subtitle);

	$out='';
	$out.="<".esc_attr($tag)." class='".esc_attr($class)."'><span>" . do_shortcode($content) . "</span></".esc_attr($tag).">";
	if($subtitle){$out.='<div class="subtitle-link">'.$subtitle.'</div>';}
  
	return $out;
}
add_shortcode('cdo_heading', 'codeopus_heading');
}

/* ======================================
   Span
   ======================================*/
if (!function_exists('codeopus_span')) { 
function codeopus_span( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
	), $atts));
	
	$out = "<span class='".esc_attr($class)."'>" . do_shortcode($content) . "</span>";
  
	return $out;
}
add_shortcode('cdo_span', 'codeopus_span');
}


/* ======================================
   Small
   ======================================*/
if (!function_exists('codeopus_small')) { 
function codeopus_small( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'class'	=> '',
	), $atts));
	
	$out = "<small class='".esc_attr($class)."'>" . do_shortcode($content) . "</small>";
  
	return $out;
}
add_shortcode('cdo_small', 'codeopus_small');
}


/* ======================================
    Dropcap
   ======================================*/
if (!function_exists('codeopus_drop_cap')) {    
function codeopus_drop_cap( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'type'	=> '',
	), $atts));

	return '<span class="cdo-dropcap '.esc_attr($type).'" style="float:left">' . do_shortcode($content) . '</span>';
}
add_shortcode('cdo_dropcap', 'codeopus_drop_cap');
}

/* ======================================
   Pullquote
   ======================================*/
if (!function_exists('codeopus_pullquote')) { 
function codeopus_pullquote( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'align'	=> 'left',
	), $atts));

	return '<span class="cdo-pullquote-'.esc_attr($align).'">' . do_shortcode($content) . '</span>';
}
add_shortcode('cdo_pullquote', 'codeopus_pullquote');
}



/* ======================================
	List Styles 
	======================================*/
if (!function_exists('codeopus_list')) { 	
function codeopus_list( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'type'  => '',
		'class'  => ''
	), $atts));
	
	$content = str_replace('<ul>', '<ul class="'.esc_attr($type).' '.esc_attr($class).'">', do_shortcode($content));
	return do_shortcode($content);
	
}
add_shortcode('cdo_list', 'codeopus_list');
}


/* ======================================
   Paragraph Text
   ======================================*/
if (!function_exists('codeopus_paragraph_text')) { 
function codeopus_paragraph_text( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'class'  => ''
	), $atts));
   
   if($class!=""){	
   return '<p class="'.esc_attr($class).'">' . do_shortcode($content) . '</p>';
   }else{
   return '<p>' . do_shortcode($content) . '</p>';
   }
}
add_shortcode('cdo_paragraph', 'codeopus_paragraph_text');
}


/* ======================================
   Icon
   ======================================*/
if (!function_exists('codeopus_icons')) { 
function codeopus_icons( $atts, $content = null ) {
  extract(shortcode_atts(array(
		'icon'	=> '',
		'color'	=> '',
		'size'	=> '',
		'link'	=> '',
		'blank'	=> '',
		'class' => ''	
	), $atts));
	
	if($blank==1){
	 $target ="target=_blank";
	}else{
	 $target ="";
	}
	
	
	if($link!=""){
		return '<a href="'.esc_url($link).'" '.esc_attr($target).' style="color:'.esc_attr($color).'"><i class="cdo-icon '.esc_attr($icon).' '.esc_attr($size).' '.esc_attr($class).'"></i></a>';
	}else{
		return'<div><i class="cdo-icon '.esc_attr($icon).' '.esc_attr($size).' '.esc_attr($class).'" style="color:'.esc_attr($color).'"></i></div>';
	}
}
add_shortcode('cdo_icon', 'codeopus_icons');
}


/* ======================================
   Spacer
   ======================================*/
if (!function_exists('codeopus_spacer')) { 
function codeopus_spacer( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		'height' => false,
		'color'  => '',
		'gap_top'  => '',
		'gap_bottom'  => '',
		'class'  => ''
	), $atts));
  	
	
	return '<div class="cdo-spacer '.esc_attr($class).'" style="height:'.esc_attr($height).'px;  background:'.esc_attr($color).'; margin-top:'.esc_attr($gap_top).'px; margin-bottom:'.esc_attr($gap_bottom).'px;">&nbsp;</div>';
}
add_shortcode('cdo_spacer', 'codeopus_spacer');
}


/* ======================================
   Clear Float
   ======================================*/
if (!function_exists('codeopus_clearfloat')) { 
function codeopus_clearfloat( $atts, $content = null ) {
	return '<div class="clear"></div>';	
}
add_shortcode('cdo_clear', 'codeopus_clearfloat');
}


/* ======================================
   Tables
   ======================================*/
if (!function_exists('codeopus_table')) { 
function codeopus_table( $atts, $content = null ) {
  extract(shortcode_atts(array(
        'color'      => ''
    ), $atts));
    
	$content = "<div class='cdo-table-".esc_attr($color)."'>".str_replace('<table>', '<table class="table">', do_shortcode($content))."</div>";
	return $content;
	
}
add_shortcode('cdo_table', 'codeopus_table');
}

/* ======================================
   Toggles
   ======================================*/
if (!function_exists('codeopus_toggle')) { 
function codeopus_toggle($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'title'    	 => 'Title goes here',
		'icon'    	 => 'icon-briefcase',
		'state'		 => 'closed',
		'class'    	 => ''
	), $atts));
	
	//enqueue script
	wp_enqueue_script( 'jquery-ui-accordion');
	wp_enqueue_script( 'cdo-accordion-jquery');

	return "<div data-id='".esc_attr($state)."' class='cdo-toggle ".esc_attr($class)."'><div class='cdo-toggle-title'><i class='".esc_attr($icon)."'></i>". $title ."</div><div class='cdo-toggle-inner'>". do_shortcode($content) ."</div></div>";
}
add_shortcode('cdo_toggle', 'codeopus_toggle');
}


/* ======================================
   Accordion
   ======================================*/
if (!function_exists('codeopus_accordion')) { 
function codeopus_accordion($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'title'    	 => 'Title goes here',
		'icon'    	 => 'icon-briefcase',
		'class'    	 => ''
	), $atts));
	
	//enqueue script
	wp_enqueue_script( 'jquery-ui-accordion');
	wp_enqueue_script( 'cdo-accordion-jquery');


	return "<div class='cdo-accordion ".esc_attr($class)."'><div class='cdo-accordion-title'><i class='".esc_attr($icon)."'></i>". $title ."</div><div class='cdo-accordion-inner'>". do_shortcode($content) ."</div></div>";
}
add_shortcode('cdo_accordion', 'codeopus_accordion');
}



/* ======================================
   Tabs
   ======================================*/
if (!function_exists('codeopus_tabs')) { 
function codeopus_tabs($atts, $content = null, $code) {
	$defaults= $tab_icons = array();
	extract(shortcode_atts(array(
	'menu_position' => 'top',
	'showicon' => 'yes',
	'class' => '',
	), $atts));
	
	//enqueue script
	wp_enqueue_script( 'cdo-easyResponsiveTabs-jquery' );
	wp_enqueue_script( 'cdo-tab-jquery' );
	
	STATIC $x = 0;
	$x++;

	// Extract the tab titles for use in the tab widget.
	preg_match_all( '/tab title="([^\"]+)" icon="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
	
	$tab_titles = array();
	
	if(!count($matches[1])){
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		if( isset($matches[1]) ){ $tab_titles = $matches[1];}
	}else{
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; $tab_icons= $matches[2];}
	}
	
	$output = '';
	
	if( count($tab_titles) ){
		$output .= '<div id="cdo-tabs-'. esc_attr($x) .'" class="cdo-tabs '. esc_attr($class) .'"><div class="'.esc_attr($menu_position).'-tab" id="'.esc_attr($menu_position).'-tab">';
		$output .= '<ul class="resp-tabs-list cdo-nav">';
		$i=0;
		
		foreach( $tab_titles as $tab ){
			$output .= '<li>';
			if($showicon=="yes"){
			$output .= '<span><i class="' . esc_attr($tab_icons[$i][0]) . '"></i></span>' . $tab[0];
			}else{
			$output .= $tab[0];
			}
			$output .= '</li>';
			$i++;
		}
		
		
		$output .= '</ul>';
		$output .='<div class="resp-tabs-container">';
		$output .= do_shortcode( $content );
		$output .= '</div>';
		$output .= '</div></div>';
	} else {
		$output .= do_shortcode( $content );
	}
	
	return $output;
	

}
add_shortcode('cdo_tabs', 'codeopus_tabs');
}

if (!function_exists('codeopus_tab')) { 
function codeopus_tab( $atts, $content = null ) {
	$defaults = array( 'title' => 'Tab', 'icon' => '', 'bg_image' => '', 'bg_position' => 'bottom left');
	extract( shortcode_atts( $defaults, $atts ) );
	return '<div id="cdo-tab-'. esc_attr(sanitize_title($title)) .'" class="cdo-tab"><div class="cdo-tab-content" style="background:#fff url('.esc_url($bg_image).') no-repeat '.esc_attr($bg_position).';">'. do_shortcode( $content ) .'</div></div>';
}
add_shortcode( 'cdo_tab', 'codeopus_tab' );
}




/* ======================================
   Google Map
   ======================================*/
if (!function_exists('codeopus_googlemap')) {    
function codeopus_googlemap($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		"width" => false,
		"height" => '400',
		"address" => '',
		"latitude" => 0,
		"longitude" => 0,
		"zoom" => 14,
		"html" => '',
		"popup" => 'false',
		"controls" => 'false',
		'pancontrol' => 'true',
		'zoomcontrol' => 'true',
		'maptypecontrol' => 'true',
		'scalecontrol' => 'true',
		'streetviewcontrol' => 'true',
		'overviewmapcontrol' => 'true',
		"scrollwheel" => 'true',
		'doubleclickzoom' =>'true',
		"maptype" => 'ROADMAP',
		"marker" => 'true',
		"icon" => 'http://www.google.com/mapfiles/marker.png',
		"icon_width" => '20',
		"icon_height" => '34',
		"logo" => '',
		"class" => '',
		'align' => false,
	), $atts));
	
	if($width){
		if(is_numeric($width)){
			$width = $width.'px';
		}
		$width = 'width:'.$width.';';
	}else{
		$width = '';
		$align = false;
	}
	if($height){
		if(is_numeric($height)){
			$height = $height.'px';
		}
		$height = 'height:'.$height.';';
	}else{
		$height = '';
	}
	
	//enqueue script
	wp_enqueue_script( 'cdo-gmap-jquery');
	
	/* fix */
	$search  = array('G_NORMAL_MAP', 'G_SATELLITE_MAP', 'G_HYBRID_MAP', 'G_DEFAULT_MAP_TYPES', 'G_PHYSICAL_MAP');
	$replace = array('ROADMAP', 'SATELLITE', 'HYBRID', 'HYBRID', 'TERRAIN');
	$maptype = str_replace($search, $replace, $maptype);
	/* end fix */
	
	if($controls == 'true'){
	$controls = '{
	panControl: '.$pancontrol.',
	zoomControl: '.$zoomcontrol.',
	mapTypeControl: '.$maptypecontrol.',
	scaleControl: '.$scalecontrol.',
	streetViewControl: '.$streetviewcontrol.',
	overviewMapControl: '.$overviewmapcontrol.'}';
	}
	
	$align = $align?' align'.$align:'';
	$id = rand(100,1000);
	
	$getlogo = ($logo!="" ? '<div class="google_map_logo"><img src="'.esc_url($logo).'" alt="" /></div>' : '');
	$getaddr = ($address!="" ? '<div class="google_map_addr">'.esc_attr($address).'</div>' : '');
	
		$out ='
		<div class="cdo-google-map '.esc_attr($class).'"><div id="google_map_'.esc_attr($id).'" class="google_map'.esc_attr($align).'" style="'.esc_attr($width).''.esc_attr($height).'"></div><div class="google_map_desc">'.$getlogo . $getaddr.'</div></div>
		<div style="line-height:0"><script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery("#google_map_'.esc_js($id).'").bind("initGmap",function(){';
				
			if($marker != 'false'){
				
				$out .='jQuery(this).gMap({
					zoom: '.esc_js($zoom).',
					markers:[{
						address: "'.esc_js($address).'",
						latitude: '.esc_js($latitude).',
						longitude: '.esc_js($longitude).',
						html: "'.esc_js($html).'",
						popup: '.esc_js($popup).'
					}],
					controls: '.esc_attr($controls).',
					icon: {
							image: "'.esc_url($icon).'",
							iconsize: ['.esc_attr($icon_width).', '.esc_attr($icon_height).'],
							iconanchor: [9,34]
					},
					maptype: "'.esc_js($maptype).'",
					doubleclickzoom:'.esc_js($doubleclickzoom).',
					scrollwheel:'.esc_js($scrollwheel).'
				});';
				
			}else{	
			
				$out .='jQuery(this).gMap({
				zoom: '.esc_js($zoom).',
				latitude: '.esc_js($latitude).',
				longitude: '.esc_js($longitude).',
				address: "'.esc_js($address).'",
				controls: '.esc_attr($controls).',
				maptype: "'.esc_js($maptype).'",
				doubleclickzoom:'.esc_js($doubleclickzoom).',
				scrollwheel:'.esc_js($scrollwheel).'
				});';
			}
				
			$out .='jQuery(this).data("gMapInited",true);
			}).data("gMapInited",false);
			jQuery("#google_map_'.esc_js($id).'").trigger("initGmap");
	});
	</script>
	</div>';

	return $out;

}
add_shortcode('cdo_gmap','codeopus_googlemap');
}

/* ======================================
   Image with Text
   ======================================*/
if (!function_exists('codeopus_img_withtext')) { 
function codeopus_img_withtext( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'image'  => '',
		'text1'  => '',
		'text2'  => '',
		'text3'  => '',
		'link'   => '',
		'height' => '',
		'type'   => '',
		'class'  => ''
	), $atts));
		
   $link = ($link!="" ? '<span><a href="'.esc_url($link).'">'.$text3.'</a></span>' : '<span>'.$text3.'</span>');
   $height = ($height!="" && $height!="auto" ? 'style="height:'.esc_attr($height).'px"' : ($height=="auto" ? 'style="height:auto"' : ''));
   
   $out='';
  
   $out.='<div class="cdo-image-text '.esc_attr($type .' '. $class).'" '.$height.'>';
	
	if($image){
	 $out.='<img src="'.esc_url($image).'" alt="" />';
	}
	
	$out.='<div class="text-table-bow-con">';
	$out.='<div class="text-table-bow">';
	$out.='<div class="text-center-bow">';
		$out.='<h3>'.$text1.'</h3>';
		$out.='<h2>'.$text2.'</h2>';
		$out.=$link;
	$out.='</div>';
	$out.='</div>';
	
   $out.='</div>';
   $out.='</div>';
   
   return $out;
}
add_shortcode('cdo_img_withtext', 'codeopus_img_withtext');
}

/* ======================================
   box with Text
   ======================================*/
if (!function_exists('codeopus_box_withtext')) { 
function codeopus_box_withtext( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'text1'  => '',
		'text2'  => '',
		'height' => '',
		'class'  => ''
	), $atts));
		
   $height = ($height!="" && $height!="auto" ? 'style="height:'.esc_attr($height).'px"' : ($height=="auto" ? 'style="height:auto"' : ''));
   
   $out='';
  
   $out.='<div class="cdo-box-text '.esc_attr($class).'" '.$height.'>';
	
	$out.='<div class="text-table-bow-con">';
	$out.='<div class="text-table-bow">';
	$out.='<div class="text-center-bow">';
		$out.='<h3>'.$text1.'</h3>';
		$out.='<div class="intro">'.$text2.'</div>';
		$out.=do_shortcode( $content );
	$out.='</div>';
	$out.='</div>';
	
   $out.='</div>';
   $out.='</div>';
   
   return $out;
}
add_shortcode('cdo_box_withtext', 'codeopus_box_withtext');
}

/* ======================================
   Icon with Text
   ======================================*/
if (!function_exists('codeopus_icon_withtext')) { 
function codeopus_icon_withtext( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'icon'  => '',
		'color'  => '',
		'text1'  => '',
		'text2'  => '',
		'column'  => '',
		'class'  => ''
	), $atts));
		
   $col = ($column=='one-fourth' ? 'col-sm-3': ($column=='one-third' ? 'col-sm-4' : ($column=='one-half' ? 'col-sm-6' : 'col-sm-12')));
   
   $out='';
  
   $out.='<div class="'.esc_attr($col .' '. $class).'">';
	if($icon){
	$out.='<div class="icon-image">';
		$out.='<i class="fa'.' '.esc_attr($icon).'" style="color:'.esc_attr($color).'"></i>';
	$out.='</div>';
	}
	$out.='<div class="icon-text">';
		$out.='<h4>'.$text1.'</h4>';
		$out.='<p>'.$text2.'</p>';
	$out.='</div>';
   $out.='</div>';
   
   return $out;
}
add_shortcode('cdo_icon_withtext', 'codeopus_icon_withtext');
}

/* ======================================
   Banner
   ======================================*/
if (!function_exists('codeopus_banner')) { 
function codeopus_banner($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'title'    	 => '',
		'layout'     => '',
		'class'    	 => ''
	), $atts));
	
	//enqueue script
	wp_enqueue_script( 'cdo-jquery-owl-carousel');
	wp_enqueue_script( 'cdo-banner-setting-jquery');
	
	$out='';
	$out.='<div class="cdo-banner-container">';
	if($title){
	$out.='<h2 class="title-section">'.$title.'</h2>';
	}
	$out.='<div class="cdo-banner '.esc_attr($class). esc_attr($layout) .'">';
		$out.=do_shortcode( $content );
	$out.='</div>';
	$out.='</div>';

	return $out;
}
add_shortcode('cdo_banner', 'codeopus_banner');
}

if (!function_exists('codeopus_banner_item')) { 
function codeopus_banner_item($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'image'    	 => '',
		'text1'		 => '',
		'text2'    	 => '',
		'link'    	 => '',
		'link_text'  => '',
		'class'    	 => ''
	), $atts));
	
	$getbg = $image ? $image :'';
	
	if($link_text!=""){
		$getlink = '<a href="'.esc_url($link).'">'.$link_text.'</a>';
	}else{
		$getlink = $link_text;
	}
	
	$out ='';
	$out.='<div class="banner-item '.esc_attr($class).'" style="background:url('.esc_attr($getbg).')">';
		$out.='<div class="row">';
		$out.='<div class="col-sm-7">';
		$out.='<div class="item-text">'.$text1.'</div>';
		$out.='<div class="item-text2">'.$text2.'</div>';
		$out.='<div class="item-text3">'.$getlink.'</div>';
		$out.='</div>';
		$out.='</div>';
	$out.='</div>';

	return $out;
}
add_shortcode('cdo_banner_item', 'codeopus_banner_item');
}


/* ======================================
   Lookbook Content
   ======================================*/
if (!function_exists('codeopus_lookbook_header')) { 
function codeopus_lookbook_header($atts, $content = null, $code) {
	
	extract(shortcode_atts(array(
			'title'=> '',
			'subtitle'=> '',
			'subtitle2'=> '',
	), $atts));
	
	$out  ='';
		
	$out.='<div class="lookbook-top">';
		$out.='<h1>'.$title.'</h1>';
	$out.='</div>';
	$out.='<div class="lookbook-bottom">';
		$out.='<h1>'.$subtitle.'</h1>';
		$out.='<h4>'.$subtitle2.'</h4>';
	$out.='</div>';
	
	return $out;

}
add_shortcode('cdo_lookbook_header', 'codeopus_lookbook_header');
}

if (!function_exists('codeopus_lookbook')) { 
function codeopus_lookbook($atts, $content = null, $code) {
		extract(shortcode_atts(array(
			'title'=> '',
			'subtitle'=> '',
			'image'=> '',
			'color'=> '',
			'button_label'=> '',
			'button_link'=> '',
			'type'=> '',
		), $atts));
		
		$image = ($image!="" ? '<img alt="" src="'.esc_url($image).'" />' : '');
		$lookcolor = ($color=="pink" ? "look-pink" : ($color=="orange" ? "look-orange" : ($color=="green" ? "look-green" : "look-pink")));
		
		$out  ='';
		
		$out.='<div class="lookbook-content '.esc_attr($lookcolor).'">';
			$out.='<div class="pull-top-header"></div>';
			$out.='<div class="row">';
			  
			  if($type=="" || $type=="type1"){
			  $out.='<div class="col-sm-6">';
				$out.='<h4>'.$title.'</h4>';
				$out.='<h1>'.$subtitle.'</h1>';
				$out.='<div class="text-center">';
				  $out.= '<div>'.do_shortcode( $content ).'</div>';
				  $out.='<a class="cdo-button black medium square" href="'.esc_url($button_link).'">'.$button_label.'</a>';
				$out.='</div>';
			  $out.='</div>';
			  }
			  
			  $out.='<div class="col-sm-6">';
				$out.='<div class="lookbook-color '.esc_attr($lookcolor).'">';
				  $out.='<div class="lookbook-white">';
					$out.='<div class="img-abs-look">';
					  $out.= $image;
					$out.='</div>';
					$out.='&nbsp;';
				  $out.='</div>';
				$out.='</div>';
			  $out.='</div>';
			  
			  if($type=="type2"){
			  $out.='<div class="col-sm-6">';
				$out.='<h4>'.$title.'</h4>';
				$out.='<h1>'.$subtitle.'</h1>';
				$out.='<div class="text-center">';
				  $out.= '<div>'.do_shortcode( $content ).'</div>';
				  $out.='<a class="cdo-button black medium square" href="'.esc_url($button_link).'">'.$button_label.'</a>';
				$out.='</div>';
			  $out.='</div>';
			  }
			  
			  
			$out.='</div>';
		$out.='</div>';
		
		return $out;
}
add_shortcode('cdo_lookbook', 'codeopus_lookbook');
}


if (!function_exists('codeopus_lookbook_footer')) { 
function codeopus_lookbook_footer($atts, $content = null, $code) {
	
	extract(shortcode_atts(array(
			'title'=> '',
			'link'=> ''
	), $atts));
	
	$out  ='';
		
	$out.='<div class="lookbook-footer">';
		$out.='<div class="look-footer">';
		  $out.='<a href="'.esc_url($link).'">'.$title.'</a>';
		$out.='</div>';
	$out.='</div>';
	
	return $out;

}
add_shortcode('cdo_lookbook_footer', 'codeopus_lookbook_footer');
}

/* ======================================
   Products
   ======================================*/
if (!function_exists('codeopus_products')) { 
function codeopus_products($atts, $content = null, $code) {
		extract(shortcode_atts(array(
			'class'    	 => '',
		), $atts));
		
		$out  ='';
		$out .='<section class="bow-products-section">';
				$out .='<div class="bow-products-content">';
				$out .='<div class="row mar-both-0">'.do_shortcode($content).'</div>';
				$out .='</div>';
		$out .='</section>';
		
		return $out;
}
add_shortcode('cdo_products', 'codeopus_products');
}	
   
/* ======================================
   Product Item
   ======================================*/
if (!function_exists('codeopus_product_item')) { 
function codeopus_product_item($atts, $content = null) {
		extract(shortcode_atts(array(
			'ids'    	 => '',
			'featured'   => '',
			'class'    	 => '',
		), $atts));

		
	return  cdo_product_id_function($ids, $featured, $class);

}
add_shortcode('cdo_product_item', 'codeopus_product_item');
}


/* ======================================
   Product Category
   ======================================*/
if (!function_exists('codeopus_product_category')) { 
function codeopus_product_category($atts, $content = null) {
		extract(shortcode_atts(array(
			'category'    	 => '',
			'featured'   => '',
			'class'    	 => '',
		), $atts));

		
	return cdo_product_category_function($category, $featured, $class);
		

}
add_shortcode('cdo_product_cat', 'codeopus_product_category');
}

if (!function_exists('codeopus_product_category2')) { 
function codeopus_product_category2($atts, $content = null) {
		extract(shortcode_atts(array(
			'category'    	 => '',
			'showpost'    	 => '',
			'class'    	 => '',
		), $atts));

		
	return cdo_product_category2_function($category, $showpost, $class);
		

}
add_shortcode('cdo_product_category', 'codeopus_product_category2');
}

/* ======================================
   Product Slider
   ======================================*/
if (!function_exists('codeopus_products_slider')) { 
function codeopus_products_slider( $atts, $content = null ) {
	
  extract(shortcode_atts(array(
		'id'    	 => '',
		'title'    	 => '',
		'subtitle'   => '',
		'subtitle_link'   => '',
		'type'		 => '',
		'number'	 => ''
	), $atts));
	
	wp_enqueue_script('cdo-jquery-owl-carousel');
	wp_enqueue_script('cdo-jquery-product-slider');
		
	return cdo_product_slider_function($id, $title, $subtitle, $subtitle_link, $type, $number);
}
add_shortcode('cdo_products_slider', 'codeopus_products_slider');
}

/* ======================================
   Testimonial Shortcode
   ======================================*/
if (!function_exists('codeopus_testimonial_shortcode')) { 
function codeopus_testimonial_shortcode( $atts, $content = null ) {
  extract(shortcode_atts(array(
		'category'	=> '',
		'column'	=> '',
		'showpost'	=> '',
		'orderby'	=> 'date',
		'order'		=> 'DESC'
		
	), $atts));
	
	return cdo_testimonial_function($category, $column, $showpost, $orderby, $order);
	
}
add_shortcode('cdo_testimonial', 'codeopus_testimonial_shortcode');
}

/* ======================================
   Team Shortcode
   ======================================*/
if (!function_exists('codeopus_team_shortcode')) { 
function codeopus_team_shortcode( $atts, $content = null ) {
  extract(shortcode_atts(array(
		'category'	=> '',
		'column'	=> '',
		'showpost'	=> '',
		'orderby'	=> 'date',
		'order'		=> 'DESC'
		
	), $atts));
	
	return cdo_team_function($category, $column, $showpost, $orderby, $order);
	
}
add_shortcode('cdo_team', 'codeopus_team_shortcode');
}

/* ======================================
   Portfolio Shortcode
   ======================================*/
if (!function_exists('codeopus_portfolio_shortcode')) { 
function codeopus_portfolio_shortcode( $atts, $content = null ) {
  extract(shortcode_atts(array(
		'filter'	=> '',
		'category'	=> '',
		'column'	=> '',
		'showpost'	=> '',
		'zoomicon'	=> '',
		'linkicon'	=> '',
		'showtitle'	=> '',
		'showdesc'	=> '',
		'orderby'	=> 'date',
		'order'		=> 'DESC'
		
	), $atts));
	
	//enqueue script
	wp_enqueue_script( 'cdo-isotope');
	wp_enqueue_script( 'cdo-portfolio');
	wp_enqueue_script( 'cdo-fancybox');
	wp_enqueue_script( 'cdo-fancybox-media');
	wp_enqueue_script( 'cdo-fancybox-setting');
	
	return cdo_portfolio_function($filter, $category, $column, $showpost, $zoomicon, $linkicon, $showtitle, $showdesc, $orderby, $order);
	
}
add_shortcode('cdo_portfolio', 'codeopus_portfolio_shortcode');
}

/* ======================================
   Cake Messes Shortcode
   ======================================*/
if (!function_exists('codeopus_cake_messes_item_shortcode')) { 
function codeopus_cake_messes_item_shortcode( $atts, $content = null ) {
  extract(shortcode_atts(array(
		'color'	=> '',
		'custom_color'	=> '',
		'image'	=> '',
		'title'	=> '',
		'class'	=> ''
		
	), $atts));
	
	wp_enqueue_script('cdo-cake-messes-setting');
	
	STATIC $i = 0;
	
	$i++;
	
	$thecolorclass = ($color =='pink' ? 'pink-option' : ($color == 'green' ? 'green-option' : ($color == 'orange' ? 'orange-option' : ($color == 'blue' ? 'blue-option' : ($color == 'purple' ? 'purple-option' : ($color == 'dark-purple' ? 'dpurple-option' : ''))))));
	
	$thecustomcolorstyle = ($custom_color != '' ? 'background:'.$custom_color.'' : '');
	$theimage = ($image !='' ? '<img src="'.esc_url($image).'" alt=""/>' : '<img src="'.plugins_url( 'images/cake-white-lg.png', __FILE__ ).'" alt="cake"/>');
	
	$thecontainerclass = ($color =='pink' ? 'pink-messes' : ($color == 'green' ? 'green-messes' : ($color == 'orange' ? 'orange-messes' : ($color == 'blue' ? 'blue-messes' : ($color == 'purple' ? 'purple-messes' : ($color == 'dark-purple' ? 'dpurple-messes' : 'customcolor-messes'))))));
	
	$out  ='';
	$out  .='<div id="cake-messesdiv'.esc_attr($i).'" class="cake-messes '.esc_attr($thecontainerclass).' '.esc_attr($class).'" data-color="'.esc_attr($custom_color).'">';
	$out  .='<div class="messes">';
			  $out  .='<div class="messes-show"></div>';
			  $out  .='<div class="messes-round-wrap '.esc_attr($thecolorclass).'" style="'.esc_attr($thecustomcolorstyle).'">'.$theimage.'</div>';
			$out  .='</div>';
			$out  .='<h4 class="messes-title">'.$title.'</h4>';
			$out  .='<div class="messes-title-border"></div>';
			$out  .='<p class="messes-desc">'.do_shortcode($content).'</p>';
	$out  .='</div>';
	return $out;
	
	
}
add_shortcode('cdo_cake_messes_item', 'codeopus_cake_messes_item_shortcode');
}

/* ======================================
   Pricing Tables Shortcode
   ======================================*/
if (!function_exists('codeopus_pricing_table_shortcode')) { 
function codeopus_pricing_table_shortcode( $atts, $content = null  ) {
  
  extract( shortcode_atts( array(
		'column' => '2',
		'class' => '',
	), $atts ) );
	
	wp_enqueue_script('cdo-pricingtables');
	
	$out = '<div class="cdo-pricing-grid '.esc_attr($class).'" data-column="'.esc_attr($column).'">'; 
	$out .= do_shortcode($content);
	$out .= '</div>';

	return $out;
	
}
add_shortcode( 'cdo_pricing', 'codeopus_pricing_table_shortcode' );
}

if (!function_exists('codeopus_pricing_shortcode')) { 
function codeopus_pricing_shortcode( $atts, $content = null  ) {
  
  extract( shortcode_atts( array(
		'color' => 'purple',
		'title' => '',
		'price_symbol' => '',
		'price' => '',
		'per' => '',
		'subtitle' => '',
		'icon' => '',
		'image' => '',
		'button_url' => '',
		'button_text' => 'Order',
		'button_class' => ''
	), $atts ) );
	
	$getimage = ($image !='' ? '<img src="'.esc_url($image).'" alt="" />' : '');
	$geticon = ($icon !='' ? '<div class="cdo-pricing-icon-con"><i class="fa '.esc_attr($icon).'"></i></div>' : '');
	$getbutton = ($button_url !='' ? '<a href="'.esc_url($button_url).'">'.$button_text.'</a>' : esc_attr($button_text) );
	$hasicon = ($icon !='' ? 'hasicon' : '');
	
	$out = '<div class="col-sm-3 cdo-pricing-item-container">';
	$out .='<div class="cdo-pricing-item '.esc_attr($color).'-color">';	
	$out .='<div class="cdo-pricing-icon '.esc_attr($hasicon).'">';	
	$out .= $getimage;
	$out .= $geticon;
	$out .= '</div>';
	$out .='<div class="cdo-pricing-price">';
	$out .= $price_symbol . $price . '<span>'.$per.'</span>';
	$out .= '</div>';
	$out .='<div class="cdo-pricing-content">';
	$out .='<div class="triangle-top"></div>';
	$out .='<h4 class="p-title">'.$title.'</h4>';
	$out .= do_shortcode($content);
	$out .= '</div>';
	$out .='<div class="cdo-pricing-button">';
	$out .= $getbutton;
	$out .= '</div>';
	$out .= '</div>';
	$out .= '</div>';

	return $out;
	
}
add_shortcode( 'cdo_pricing_item', 'codeopus_pricing_shortcode' );
}

/* ======================================
   Newsflash Shortcode
   ======================================*/
   
if (!function_exists('codeopus_newsflash_shortcode')) { 
function codeopus_newsflash_shortcode( $atts, $content = null  ) {
	
	wp_enqueue_script('cdo-masonry');
	wp_enqueue_script('cdo-cake-newsflash');
	
	$out = '';
	$out .='<div class="cdo-newsflash-grid">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	
	return $out;
	
}
add_shortcode( 'cdo_newsflash', 'codeopus_newsflash_shortcode' );
}

if (!function_exists('codeopus_newsflash_item_shortcode')) { 
function codeopus_newsflash_item_shortcode( $atts, $content = null  ) {
	
	extract( shortcode_atts( array(
		'type' => 'square',
		'text1' => '',
		'text2' => '',
		'width' => '',
		'height' => '',
		'bgcolor' => '',
		'image' => '',
		'link' => '',
		'animation' => 'no',
		'animation_color' => 'orange',
		'testimonial_color_style' => 'purple',
		'testimonial_category' => '',
		'class' => ''
	), $atts ) );
	
	$gettype = ($type == "square" ? "square-type" : ($type =="portrait" ? "portrait-type" : ($type =="landscape" ? "landscape-type" : "")));
	$hasimage = ($image !="" ? "hasimage" : "");
	$animationcolor = ($animation_color == "blue" ? "wizz-blue" : ($animation_color == "green" ? "wizz-green" : ($animation_color == "pink" ? "wizz-pink" : ($animation_color == "purple" ? "wizz-purple" : "wizz-orange"))));
	
	$out = '';
	$out .='<div class="cdo-newsflash-grid-item '.esc_attr($class).' '.esc_attr($gettype).' '.esc_attr($hasimage).'" data-width="'.esc_attr($width).'" data-height="'.esc_attr($height).'" data-bgcolor="'.esc_attr($bgcolor).'">';
	
	
	if($type=="testimonial"){
		
		$out .= do_shortcode('[cdo_testimonial_slider category="'.esc_attr($testimonial_category).'" color_style="'.esc_attr($testimonial_color_style).'"  container_height="'.esc_attr($height).'"]');
	}else{
	
		$out .='<div class="cdo-newsflash-content">';
	
	
	
		if($animation!="yes" || $image==""){
			if($link){
				$out .= '<h3><a href="'.esc_url($link).'">'.$text1.'<span>'.$text2.'</span></a></h3>';
			}else{
				$out .= '<h3>'.$text1.'<span>'.$text2.'</span></h3>';
			}
		}
		
		if($animation=="yes" && $image!=""){
			
			$out .= '<a href="'.esc_url($link).'"><span class="wizz-effect '.esc_attr($animationcolor).'">';
				$out .= '<span class="wrap-info">';
				  $out .= $text1;
				$out .= '</span>';
			$out .= '</span></a>';
		}
		
		if($image){
		$out .= '<img src="'.esc_url($image).'" alt="'.$text1.'" class="asbg"/>';
		}
	
	
		$out .= '</div>';
		
	}
	
	$out .= '</div>';
	
	
	return $out;
	
}
add_shortcode( 'cdo_newsflash_item', 'codeopus_newsflash_item_shortcode' );
}

if (!function_exists('codeopus_testimonial_slider_shortcode')) { 
function codeopus_testimonial_slider_shortcode( $atts, $content = null  ) {
	
	extract( shortcode_atts( array(
		'category' => '',
		'color_style' => '',
		'container_height' => '',
		'class' => ''
	), $atts ) );
	
	wp_enqueue_script('cdo-slick-slider');
	wp_enqueue_script('cdo-slick-slider-setting');
	
	return cdo_testimonial_slider_function($category, $color_style, $container_height, $class);

}
add_shortcode( 'cdo_testimonial_slider', 'codeopus_testimonial_slider_shortcode' );
}
?>