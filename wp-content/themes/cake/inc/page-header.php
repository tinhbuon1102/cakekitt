<?php
global $post;
$getpid = cake_get_postid();
$theID = ( isset( $post->ID ) ? $getpid : "" );

$slidertype = get_post_meta($theID, 'cake_slider_choose',true);

if($slidertype!="no-slider" && $slidertype!=""){
	get_template_part('inc/slideshow');
	
}else{
	 get_template_part('inc/page-title');
}
?>