<?php
/*** set the content type header ***/
header("Content-type: text/css");
?>
.wcuf_summary_file_list_block 
{
    display: inline-block;
   /*  margin-right: 20px; */
	/*border: 1px #dedede solid;*/
	padding: 15px;
	margin-bottom: 10px; 
}
.wcuf_summary_file_list_block_new_line
{
	width:100%;
	height:10px;
	display:block;
	clear:both;
}

.wcuf_audio_control
{
	width:100%;
}
button.button.delete_button
{
	margin-bottom: 3px;
}
.wcuf_already_uplaoded_data_container h4
{
	margin-bottom: 0px;
}
.wcuf_already_uplaoded_data_container
{
	display:block;
	clear:both;
	margin-top: 20px;
	margin-bottom: 20px;
	overflow: hidden;
	padding: 10px;
	border: 1px #dedede solid;

}
ol.wcuf_file_preview_list
{
	/* list-style: decimal;*/ 
	list-style: none;
	margin: 0px 0px 0px 15px;
}
.wcuf_preview_file_title
{
	display: block;
	font-weight: bold;
	font-size: 14px;
	margin-bottom: 3px;
	word-wrap: break-word;
	word-break: break-all;
	padding-right: 10px;
}
.wcuf_required_upload_add_to_cart_warning_message
{
	font-style:italic;
	margin-bottom: 15px;
	clear:both;
}
.wcuf_file_preview_list_item /* , .wcuf_file_preview_list_item * */
{
	/* display: block; */ /* This remove the numerics */
	/* clear: both; */
	float:left;
}
li.wcuf_file_preview_list_item
{
	margin-top: 10px;
	
	/* vertical aligned */
	/* float: left;
    display: inline-block;
    vertical-align:middle;
    height: <?php echo urldecode($_GET['image_preview_height'])+70;?>px;
	width: <?php echo urldecode($_GET['image_preview_width'])+70;?>px;
    margin-right: 20px; */
	
	display:block;
	clear:both;
}
a.button.download_small_button {
 /*  font-size: 13px;
  padding: 6px;
  margin-top: 2px; */
   font-size: 13px;
    padding: 6px;
    margin-top: 2px;
    display: inline-block;
}
img.wcuf_file_preview_list_item_image {
    height: auto;
    max-width: 100%;
    display: block;
}
button.button.wcuf_upload_field_button {
  margin-bottom: 3px !important;
}
.wcuf_crop_upload_image_for_rotating_status_box
{
	display:none;
}
.wcuf_crop_rotating_upload_status_message
{
	display:block;
	clear:both;
	margin-top:5px;
}
#wcuf_alert_popup
{
	background: #fff none repeat scroll 0 0;
    margin: 40px auto;
    max-width: 700px;
    padding: 20px 30px;
    position: relative;
    text-align: center;
	color:black;
}
#wcuf_close_popup_alert, #wcuf_leave_page
{
	margin-top: 20px;
	/* padding: 3px 15px 3px 15px; */
}
#wcuf_alert_popup_title 
 {
  text-align: left;
  border-bottom: 1px solid #dedede;
  padding-bottom: 3px;
}
.wcuf_image_quantity_preview
{
  margin-right: 3px;
}
.wcuf_quantity_per_file_container
{
	display: block;
	clear:both;
    margin: 3px 0 30px;
}
.wcuf_quantity_per_file_input
{
	width: 60px; 
	margin-left: 5px;
	text-align: center;
	border:none;
	background-color: #eeeeee;
}
.wcuf_single_file_name_container
{
	display:block;
	clear:both;
	margin-top: 10px;
}
.wcuf_delete_single_file_in_multiple_list.wcuf_delete_file_icon,
.wcuf_delete_single_file_stored_on_server.wcuf_delete_file_icon
{
	background:url('../img/delete-icon-resized.png');
	height:16px;
	width: 16px;
	display: inline-block;
	margin-left: 10px;
	cursor: pointer;
	display:inline-block; 
	vertical-align:middle;
	margin-top: -4px;
}
.wpuef_text_field_description
{
	display:block;
	margin: 0px 0 5px 0;
}
audio::-internal-media-controls-download-button {
    display:none;
}

audio::-webkit-media-controls-enclosure {
    overflow:hidden;
}

audio::-webkit-media-controls-panel {
    width: calc(100% + 30px); /* Adjust as needed */
}