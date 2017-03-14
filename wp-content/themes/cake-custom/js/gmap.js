function initialize() {
	if (jQuery('#map_canvas').length) {
	  var latlng = new google.maps.LatLng(35.671759,139.772376);/*表示したい場所の経度、緯度*/
	  var myOptions = {
	    zoom: 18, /*拡大比率*/
	    center: latlng, /*中心点*/
	    mapTypeId: google.maps.MapTypeId.ROADMAP/*表示タイプの指定*/
	  };
	  var map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	}
}