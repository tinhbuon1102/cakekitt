 jQuery(document).ready(function($){
	"use strict";
	 $('.carousel-inner').find('.item').each(function(){
		var pr = $(this).find('.parallax-image');
		var bg = pr.data('image');
		pr.css({'background-image' : 'url('+ bg +')'}); 
	 });
	 $('.parallax-image').parallax("50%", 0.1);
 });