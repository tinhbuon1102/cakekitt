<?php
class WCUF_OrderDetailAddon
{
	var $current_order;
	var $email_sender;
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( &$this, 'woocommerce_metaboxes' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( &$this, 'woocommerce_process_shop_ordermeta' ), 5, 2 );
		add_action( 'woocommerce_after_order_itemmeta', array( &$this, 'display_order_item_meta' ), 10, 3 );
	}
	function display_order_item_meta($item_id, $item, $_product )
	{
		global $wcuf_order_model;		
		$item_individual_id = $wcuf_order_model->read_order_item_meta($item,'_wcuf_sold_as_individual_unique_key');
		
		if($item_individual_id)
			echo "<strong>".__('Individual ID:', 'woocommerce-files-upload')."</strong> ".$item_individual_id;
	}
	function woocommerce_process_shop_ordermeta( $post_id, $post ) 
	{
		global $wcuf_file_model, $wcuf_option_model, $wcuf_upload_field_model;
		//Used when admin save order from order detail page in backend
		if(isset($_POST['files_to_delete']))
		{
			$file_order_metadata = $wcuf_upload_field_model->get_uploaded_files_meta_data_by_order_id($post_id); //$wcuf_option_model->get_order_uploaded_files_meta_data($post_id);
			//$file_order_metadata = $file_order_metadata[0];
		
			foreach($_POST['files_to_delete'] as $value)
			{
				//var_dump(intval($value)." ".$file_order_metadata[$value]['absolute_path']." ".$post_id);
				$file_order_metadata = $wcuf_file_model->delete_file($value, $file_order_metadata, $post_id);
			}
		}
	}
	function woocommerce_metaboxes() 
	{

		add_meta_box( 'woocommerce-files-upload', __('File(s) uploaded', 'woocommerce-files-upload'), array( &$this, 'woocommerce_order_uploaded_files_box' ), 'shop_order', 'side', 'high');

	}
	function woocommerce_order_uploaded_files_box($post) 
	{
		global $wcuf_option_model,$wcuf_upload_field_model;
		$data = get_post_custom( $post->ID );
		$file_fields_meta = $wcuf_option_model->get_fields_meta_data();
		$uploaded_files = $wcuf_upload_field_model->get_uploaded_files_meta_data_by_order_id($post->ID); //$wcuf_option_model->get_order_uploaded_files_meta_data($post->ID);
		//$counter = 0;
		?>
		<div id="upload-box">
		<p><i><?php _e('Click "Save Order" button after one or more file deletion otherwise changes will no take effects.', 'woocommerce-files-upload'); ?></i></p>
			<?php if(empty($uploaded_files)): echo '<p><strong>'.__('Customer hasn\'t uploaded any file...yet.', 'woocommerce-files-upload').'</strong></p>'; 
			else:?>
			<ul class="totals">
			 <?php foreach($uploaded_files as $file_meta): 
				
																											//could be used 'soruce'. test if is setted and != 'dropbox'
				//$is_zip = is_array($file_meta['original_filename']) && !WCUF_DropBox::is_dropbox_file_path($file_meta['absolute_path']) ? true : false;
				$is_zip = $wcuf_upload_field_model->is_upload_field_content_managed_as_zip($file_meta);
				$is_multiple_files = $wcuf_upload_field_model->is_upload_field_content_managed_as_multiple_files($file_meta); 
				$original_name = "";
				//wcuf_var_dump($file_meta['id']);
				if($is_zip || $is_multiple_files)
					$original_name = __('Multiple files', 'woocommerce-files-upload');
				else if(isset($file_meta['original_filename']))
					$original_name = !is_array($file_meta['original_filename']) ? $file_meta['original_filename'] : $file_meta['original_filename'][0];
				
				if($original_name == "")
					continue;
				?>
				<li style="margin-bottom:40px;">
					<h4 style="margin-bottom:0px;">
					<?php echo $file_meta['title']." : ".$original_name;?></h4>
					<?php 
						$quantity = 1;
						if(!$is_zip && !$is_multiple_files)
							echo __('Quantity: ', 'woocommerce-files-upload')."<i>".$quantity."</i></br></br>";
					
					 
					if($is_zip || $is_multiple_files) //old multiple file managment
					{
						$files_name = "<p><ol>";
						//$counter = 0;
						foreach( $file_meta['original_filename'] as $counter => $temp_file_name)
						{
							if(isset($file_meta['quantity'][$counter]))
								$quantity = is_array($file_meta['quantity'][$counter]) ? array_sum($file_meta['quantity'][$counter]) : $file_meta['quantity'][$counter];
							if($is_zip)
							{
								$zip_file_name = basename ($file_meta['absolute_path']);
								if(!$wcuf_upload_field_model->is_dropbox_stored($file_meta))
									$files_name .= '<li><a target="_blank" href="'.get_site_url().'?wcuf_zip_name='.$zip_file_name.'&wcuf_single_file_name='.$temp_file_name.'&wcuf_order_id='.$post->ID.'">'.$temp_file_name.'</a> ('.__('Quantity: ', 'woocommerce-files-upload').$quantity.')</li>';
								else
									$files_name .= '<li>'.$temp_file_name.' ('.__('Quantity: ', 'woocommerce-files-upload').$quantity.')</li>';
							}
							else
								$files_name .= '<li><a target="_blank" href="'.$file_meta['url'][$counter].'" download>'.$temp_file_name.'</a> ('.__('Quantity: ', 'woocommerce-files-upload').$quantity.')</li>';
							$counter++;
						}
						$files_name .= "</ol></p>";
						echo $files_name;
					}
					?>
					
					
					<?php if(isset($file_meta['user_feedback']) && $file_meta['user_feedback'] != "" && $file_meta['user_feedback'] != "undefined"):?>
						<p style="margin-top:5px;">
							<strong><?php echo _e('User feedback', 'woocommerce-files-upload'); ?></strong></br>
							<?php echo $file_meta['user_feedback'];?>
						</p>
					<?php endif;?>
					<?php $media_counter = 0;
						if(isset($file_meta['ID3_info']) && $file_meta['ID3_info'] != "none"): ?>
						<p style="margin-top:5px;">
							<strong><?php echo _e('Media info', 'woocommerce-files-upload') ?></strong></br>
							<?php	foreach($file_meta['ID3_info'] as $file_media_info):?>
											<?php if($media_counter > 0) echo "<br/>";?>
											<?php  echo __('Name: ', 'woocommerce-files-upload')."<i>".$file_media_info['file_name']."</i>";?></br> 
											<?php echo __('Duration: ', 'woocommerce-files-upload')."<i>".$file_media_info['playtime_string']."</i>"?></br>
											<!-- <?php echo __('Quantity: ', 'woocommerce-files-upload')."<i>".$file_media_info['quantity']."</i>"?></br> -->
											<?php $media_counter++; 
									endforeach; ?>
						</p>
					<?php endif;?>
						
					<p style="margin-top:3px;">
						<?php if($is_zip || !$is_multiple_files): 
							$file_url = !is_array($file_meta['url']) ? $file_meta['url'] : $file_meta['url'][0];
						?>
							<a target="_blank" class="button button-primary" style="text-decoration:none; color:white;  margin-bottom:4px;" href="<?php echo $file_url; ?>"><?php _e('Download', 'woocommerce-files-upload'); ?></a>
						<?php elseif($wcuf_upload_field_model->can_be_zip_file_created_upload_field_content($file_meta)): ?>
							<p><strong><?php echo _e('Note:', 'woocommerce-files-upload') ?></strong> <?php echo _e('Only local files can be zipped.', 'woocommerce-files-upload') ?>
							<a target="_blank" class="button button-primary" style="text-decoration:none; color:white;" href="<?php echo get_site_url();?>?wcuf_create_zip_for_field=<?php echo $file_meta['id']; ?>&wcuf_order_id=<?php echo $post->ID;?>"><?php _e('Download all as zip', 'woocommerce-files-upload'); ?></a>
							</p>
						<?php endif; ?>
						<input  type="submit" class="button delete_button" data-fileid="<?php echo $file_meta['id'] ?>" value="<?php _e('Delete content(s)', 'woocommerce-files-upload'); ?>" onclick="clicked(event);" ></input>
					</p>
				</li>
			  <?php endforeach;?>
			</ul>
			<?php endif; ?>
		</div>
		<script type="text/javascript">
		var index = 0;
		function clicked(e) 
			{ 
			  /*  console.log(e.target); */
			   e.preventDefault();
			   if(confirm('<?php _e('Are you sure?', 'woocommerce-files-upload'); ?>'))
			   {
				   jQuery("#upload-box").append( '<input type="hidden" name="files_to_delete['+index+']" value="'+jQuery(e.target).data('fileid')+'"></input>');
				   jQuery(e.target).parent().remove();
				   index++;
			   }
			}
		</script>
		<div class="clear"></div>
		<?php 
	}	
}
?>