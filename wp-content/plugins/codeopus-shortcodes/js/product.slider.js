jQuery(document).ready(function($){
	
	var owl     = $(".cdo-products-slider");
		
	owl.owlCarousel({
     
      itemsCustom : [
        [0, 1],
        [450, 1],
		[598, 2],
        [600, 2],
        [700, 2],
        [1000, 3]
      ],
      pagination : false,
      navigation : true,
      mouseDrag: false,
      navigationText: [
      "<i class='fa fa-chevron-left'></i>",
      "<i class='fa fa-chevron-right'></i>"
      ],
      transitionStyle : "fade"
 
	});

	
});

