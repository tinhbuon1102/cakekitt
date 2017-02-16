 jQuery(document).ready(function($){
	 
	 var angle = 0, img = document.getElementById('image');
		document.getElementById('button').onclick = function() {
			angle = (angle+45)%360;
			img.className = "wrap-circle rotate"+angle;
		}
		
			
 });
 