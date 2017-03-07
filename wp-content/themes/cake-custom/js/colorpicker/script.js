// JavaScript Document
/*$(function(){
   $(".colorPicker").colorPicker({
    	colors: 'colors.json',
    	customcolors : ['#FFC0CB', '#afffff', '#FFD700'],
    	rowitem: 10,
    	insertcode: true,
        onSelect: function(ui, color){
            ui.css("background", color);
        }
    });
});*/
jQuery(function(){

	jQuery(".cp-select").colorPicker({
		colors: gl_templateUrl + '/js/colorpicker/colors.json',
		rowitem: 10,
		onSelect: function( ui, c ){
			jQuery('.selected-color').css('background-color', c);		
		}
	});

});