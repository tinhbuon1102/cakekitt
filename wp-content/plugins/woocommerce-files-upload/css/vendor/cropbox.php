<?php
/*** set the content type header ***/
header("Content-type: text/css");
/* if($_GET['clip_height'] > $_GET['clip_width'])
{
	$ratio = $_GET['clip_width']/$_GET['clip_height'];
	$_GET['clip_height'] = round($_GET['crop_area_height']*2/3);
	$_GET['clip_width'] = round($_GET['clip_height']*$ratio);
} 
else
{
	$ratio = $_GET['clip_height']/$_GET['clip_width'];
	$_GET['clip_width'] = round($_GET['crop_area_width']*2/3);
	$_GET['clip_height'] = round($_GET['clip_height']*$ratio);
}  */	
?>

.woocommerce.single.single-product .entry-summary form button.button.wcuf_zoomin_button,
button.button.wcuf_zoomin_button,
.woocommerce.single.single-product .entry-summary form button.button.wcuf_zoomout_button,
button.button.wcuf_zoomout_button,
.woocommerce.single.single-product .entry-summary form button.button.wcuf_rotate_left,
button.button.wcuf_rotate_left,
.woocommerce.single.single-product .entry-summary form button.button.wcuf_rotate_right,
button.button.wcuf_rotate_right
{
	float: left;
	width: 48%;
	margin-top: 2px;
    margin-right: 1px;
	overflow:hidden;
}
.woocommerce.single.single-product .entry-summary form button.button.wcuf_crop_upload_button,
button.button.wcuf_crop_upload_button
{
	float: left;
	width: 97%;
	margin-top: 2px;
}
.wcuf_crop_image_box
{
position: relative;
height: <?php echo urldecode($_GET['crop_area_height']);?>px;
width: <?php echo urldecode($_GET['crop_area_width']);?>px;
border:1px solid #aaa;
background: #fff;
overflow: hidden;
background-repeat: no-repeat; 
cursor:move;
}

.wcuf_crop_image_box .wcuf_crop_thumb_box
{
position: absolute;
top: 50%;
left: 50%;
width: 202px;
height: 202px;
margin-top: -101px;
margin-left: -101px;
box-sizing: border-box;
border: 1px solid rgb(102, 102, 102);
box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
background: none repeat scroll 0% 0% transparent;
}
.wcuf_crop_thumb_box
{
	pointer-events:none;
}

.wcuf_crop_image_box .wcuf_crop_thumb_spinner
{
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	text-align: center;
	line-height: 400px;
	background: rgba(0,0,0,0.7);
}
.wcuf_crop_container
{
	display:none;
	width: <?php echo urldecode($_GET['crop_area_width']);?>px;
	/* max-width: 600px; */
}