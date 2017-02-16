 jQuery(document).ready(function(){
     cake_topcart_effects();
 });

function cake_topcart_effects(){
	"use strict";
	
	
	var btncart = jQuery(".cake-menu-cart");
	var catcont = jQuery(".cake-menu-cart .cake-dropdown-cart");
	
	btncart.mouseenter(function(){
		catcont.stop().fadeIn(100,'easeOutCubic');
	});
	btncart.mouseleave(function(){
		catcont.stop().fadeOut(100,'easeOutCubic');
	});
}
