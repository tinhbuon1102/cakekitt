jQuery(document).ready(function($){
	
	var owl     = $(".owl-arrivals");
		
	owl.owlCarousel({
     
      itemsCustom : [
        [0, 1],
        [450, 1],
        [600, 1],
        [700, 1],
        [1000, 1]
      ],
      navigation : false,
      transitionStyle : "fade"
 
	});

	
});

