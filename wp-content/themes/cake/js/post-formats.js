jQuery( document ).ready( function($) {
	
	//POST FORMAT
	var	$linkSettings  = $('#cake_post_format_link_options').hide(),
		$videoSettings = $('#cake_post_format_video_options').hide(),
		$audioSettings = $('#cake_post_format_audio_options').hide(),
		$quoteSettings = $('#cake_post_format_quote_options').hide(),
		$contentEditor = $('#postdivrich').show(),
		$postFormat    = $('#post-formats-select input[name="post_format"]');
		
		
	
	$postFormat.each(function() {
		
		var $this = $(this);

		if( $this.is(':checked') )
			changePostFormat( $this.val() );

	});

	$postFormat.change(function() {

		changePostFormat( $(this).val() );

	});

	function changePostFormat( val ) {

		$linkSettings.hide();
		$videoSettings.hide();
		$audioSettings.hide();
		$quoteSettings.hide();
		$contentEditor.show();
		$('#postdivrich').css({'visibility':'visible', 'height' : 'auto'});

		if( val === 'link' ) {

			$linkSettings.show();
			$('#postdivrich').css({'visibility':'hidden', 'height' : '0px'});
			

		} else if( val === 'video' ) {

			$videoSettings.show();
		
			
		} else if( val === 'audio' ) {

			$audioSettings.show();
		
			
		} else if( val === 'quote' ) {

			$quoteSettings.show();
			$('#postdivrich').css({'visibility':'hidden', 'height' : '0px'});
			
		}
	}
	
	
	// SLIDER METABOX
	
	var $sliderchoose		= $('select[name="cake_slider_choose"]'),
		$slickslider		= $('#cake_slick_slider_options'),
		$sliceslider		= $('#cake_slice_slider_options'),
		$parallaxslider		= $('#cake_parallax_slider_options'),
		$cycleslider		= $('#cake_cycle_slider_options');
		
		$slickslider.hide();
		$sliceslider.hide();
		$parallaxslider.hide();
		$cycleslider.hide();
		
		/* Show Slider Type */
		if( $sliderchoose.val() === 'slick-slider' ) {
			$slickslider.show();
			$sliceslider.hide();
			$parallaxslider.hide();
			$cycleslider.hide();
		}else if($sliderchoose.val() === 'slice-slider'){
			$sliceslider.show();
			$slickslider.hide();
			$parallaxslider.hide();
			$cycleslider.hide();
		}else if($sliderchoose.val() === 'parallax-slider'){
			$parallaxslider.show();
			$slickslider.hide();
			$sliceslider.hide();
			$cycleslider.hide();
		}else if($sliderchoose.val() === 'cycle-slider'){
			$cycleslider.show();
			$slickslider.hide();
			$sliceslider.hide();
			$parallaxslider.hide();
		}else{
			$slickslider.hide();
			$sliceslider.hide();
			$parallaxslider.hide();
			$cycleslider.hide();
		}
		
		$sliderchoose.live("change", function(){
			if($(this).val() == "slick-slider") {
			 $slickslider.show();
			 $sliceslider.hide();
			 $parallaxslider.hide();
			 $cycleslider.hide();
			}else if($(this).val() == "slice-slider"){
			 $sliceslider.show();
			 $slickslider.hide();
			 $parallaxslider.hide();
			 $cycleslider.hide();
			}else if($(this).val() == "parallax-slider"){
			 $parallaxslider.show();
			 $slickslider.hide();
			 $sliceslider.hide();
			 $cycleslider.hide();
			}else if($(this).val() == "cycle-slider"){
			 $cycleslider.show();
			 $slickslider.hide();
			 $sliceslider.hide();
			 $parallaxslider.hide();
			} else {
			 $slickslider.hide();
			 $sliceslider.hide();
			 $parallaxslider.hide();
			 $cycleslider.hide();
			}
		});
		
		
		$slicksliderpost 	= $('select[name="cake_slick_slider_post"]');
		$slicksliderpostitem 	= $('#cake_slick_slider_post_item_repeat');
		$slicksliderwoopost 	= $('.cmb2-id-cake-slick-slider-woo-post');
		
		$slicksliderpostitem.hide();
		$slicksliderwoopost.hide();
		
		if( $slicksliderpost.val() === 'sliderpost' ) {
			$slicksliderpostitem.show();
		}else if($slicksliderpost.val() === 'woocommercepost'){
			$slicksliderwoopost.show();
		}else{
			$slicksliderpostitem.hide();
			$slicksliderwoopost.hide();
		}
		
		$slicksliderpost.live("change", function(){
			if($(this).val() == "sliderpost") {
				$slicksliderpostitem.show();
				$slicksliderwoopost.hide();
			}else if($(this).val() == "woocommercepost"){
				$slicksliderwoopost.show();
				$slicksliderpostitem.hide();
			}else {
				$slicksliderpostitem.hide();
				$slicksliderwoopost.hide();
			}
		});


}); 