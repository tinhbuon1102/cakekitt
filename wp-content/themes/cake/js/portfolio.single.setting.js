 jQuery(document).ready(function(){
     CDOPfSingleSetting();	 
 });
 
 function CDOPfSingleSetting(){
	"use strict"; 
	
	var owl = jQuery("#cdo-pfslider-image");
	
	jQuery(".right-nav").click(function(){
		owl.trigger('owl.next');
	});

	jQuery(".left-nav").click(function(){
		owl.trigger('owl.prev');
	});


	owl.owlCarousel({
		itemsCustom:[[0,1],[400,1],[700,2],[1000,1],[1200,1],[1600,1]],
		autoPlay : 6000,
		navigation : false, 
		slideSpeed : 300,
		paginationSpeed : 400,
		pagination: false
	});
 
 }