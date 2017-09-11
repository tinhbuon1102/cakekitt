<?php class WCUF_Html 
{
	function __construct()
	{
		
		add_action('wp_ajax_wcuf_get_upload_field_configurator_template', array(&$this, 'ajax_get_upload_field_configurator_template'));
		
	}
	/* private function return_bytes($val) 
	{
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				//$val *= 1024;
				$val *= 1024;
			case 'm':
				$val *= 1;
				break;
			case 'k':
				$val = 1;
		}
		return $val;
	} */
	public function ajax_get_upload_field_configurator_template()
	{
		$start_index = isset($_POST['start_index']) ? $_POST['start_index'] + 1: null;
		if(isset($start_index))
			$this->upload_field_configurator_template(array(array()), $start_index /* , true */);
		wp_die();
	}
	public function upload_field_configurator_template($file_fields_meta, $start_index /* , $is_an_empty_field = false */)
	{
		global $wcuf_customer_model, $wcuf_product_model, $wcuf_option_model;
		$php_settings = $wcuf_option_model->get_php_settings();
		//text
		$already_uploaded_default_message = __('<h4>Uploaded files:</h4>', 'woocommerce-files-upload')."\n"."[file_name_with_media_preview]";
		$upload_per_product_instruction = __("If you have choosen to display the upload field only on Cart and/or Checkout and/or Order details pages (thus excluding Products pages. If it is has been selected this option is required and cannot be disabled) by disabling the following option only one Upload Field will be displayed if at least one of the item in cart/order matches the filtering criteria (otherwise by default it is displayed one Upload Field for each matching product)",'woocommerce-files-upload');
		$upload_product_page_before_instruction = __('<strong>NOTE:</strong> By default, to offer all the feaures, the upload field is showed only <strong>AFTER</strong> the product has been added to the cart. Enabling this option the following feature will not work: <ol><li><strong>Max number of uploadable files depens on product quantity</strong></li><li><strong>Extra costs for specific variation (percentage option):</strong> If you have selected to add extra cost based on variation price percentage, the plugin will be not able to properly compute it.</li>', 'woocommerce-files-upload');
		$product_filtering_instruction = __('Select Product(s) (search typing product name, id or sku code)', 'woocommerce-files-upload');
		$required_field_instruction = __('In case the field is showed before adding the product to the cart, the plugin will try to hide <strong>Add to cart button</strong> (with some themes not 100% WooCommerce compliant this feature could not work). In case the product has been added to the cart, the plugin will <strong>try to deny the page leaving</strong> until all the required files have not been uploaded <strong>propting a warning dialog</strong> (some browsers, for security reasons, may not permit this denial).','woocommerce-files-upload');
		//
		$enable_for_all_text  = __('Enabled for every product (or order, according to "Upload per product" option)', 'woocommerce-files-upload');
		$enable_for_selected_categories_and_products  = __('Enabled for selected categories and products', 'woocommerce-files-upload');
		$enable_for_selected_categories_and_products_and_children_text  = __('Enabled for selected categories (and all its children) and products', 'woocommerce-files-upload');
		$disabled_for_selected_categories_and_products_text  = __('Disabled for selected categories and products', 'woocommerce-files-upload');
		$disabled_for_selected_categories_and_products_and_children_text  = __('Disable for selected categories (and all its children) and products', 'woocommerce-files-upload');
		//
		$post_max_size = WCUF_File::return_bytes($php_settings['post_max_size']);
		$post_max_size_text =$php_settings['post_max_size'];
		$upload_max_filesize =  WCUF_File::return_bytes($php_settings['upload_max_filesize']);
		$upload_max_filesize_text = $php_settings['upload_max_filesize'];
		$max_file_uploads = $php_settings['max_file_uploads'];
		$php_settings_notice = sprintf(__('The plugin has detected that your host has the following PHP settings: <strong>post_max_size</strong> value is <strong>%s</strong> and <strong>upload_max_filesize</strong> value is <strong>%s</strong>. The first setting means that <strong>the sum of the files sizes you are trying to upload cannot be greater than %s</strong> and the <strong>single uploadable file size cannot be greater than %s</strong> (min value between post_max_size and upload_max_filesize).', 'woocommerce-files-upload'),$post_max_size_text, $upload_max_filesize_text,$post_max_size_text, size_format( wp_max_upload_size() ));
		$size_that_can_be_posted = $post_max_size < $upload_max_filesize ? $post_max_size : $upload_max_filesize;
		//Error checking
		if($post_max_size == 0)
			$size_that_can_be_posted = $upload_max_filesize;
		if($upload_max_filesize == 0)
			$size_that_can_be_posted = $post_max_size;
		if($post_max_size == 0 && $upload_max_filesize == 0)
			$size_that_can_be_posted = 1000;
		
		$counter  = $start_index;
		foreach($file_fields_meta as $file_meta): 
						
				$file_meta['enable_for'] = !isset($file_meta['enable_for']) ?  'always':$file_meta['enable_for'];
				$file_meta['text_field_on_order_details_page'] = !isset($file_meta['text_field_on_order_details_page']) ?  false:$file_meta['text_field_on_order_details_page'];
				$file_meta['is_text_field_on_order_details_page_required'] = !isset($file_meta['is_text_field_on_order_details_page_required']) ?  false:$file_meta['is_text_field_on_order_details_page_required'];
				$file_meta['sort_order'] = !isset($file_meta['sort_order']) ?  0:$file_meta['sort_order'];
				$file_meta['notify_admin'] = !isset($file_meta['notify_admin']) ?  false:$file_meta['notify_admin'];
				$file_meta['notify_attach_to_admin_email'] = !isset($file_meta['notify_attach_to_admin_email']) ?  false:$file_meta['notify_attach_to_admin_email'];
				$file_meta['message_already_uploaded'] = !isset($file_meta['message_already_uploaded']) ?  $already_uploaded_default_message:$file_meta['message_already_uploaded'];
				$file_meta['disclaimer_checkbox'] = !isset($file_meta['disclaimer_checkbox']) ?  false:$file_meta['disclaimer_checkbox'];
				$file_meta['disclaimer_text'] = !isset($file_meta['disclaimer_text']) ?  "":$file_meta['disclaimer_text'];
				$selected_categories = !isset($file_meta['category_ids']) ? array():$file_meta['category_ids'];
				$selected_products = !isset($file_meta['products_ids']) ? array():$file_meta['products_ids'];
				$notifications_recipients = !isset($file_meta['notifications_recipients']) ? '':$file_meta['notifications_recipients'];
				$file_meta['width_limit'] = isset($file_meta['width_limit']) ? $file_meta['width_limit'] : 0;
				$file_meta['height_limit'] = isset($file_meta['height_limit']) ? $file_meta['height_limit'] : 0;
				$file_meta['min_width_limit'] = isset($file_meta['min_width_limit']) ? $file_meta['min_width_limit'] : 0;
				$file_meta['min_height_limit'] = isset($file_meta['min_height_limit']) ? $file_meta['min_height_limit'] : 0;
				$file_meta['upload_fields_editable_for_completed_orders'] = isset($file_meta['upload_fields_editable_for_completed_orders']) ? $file_meta['upload_fields_editable_for_completed_orders'] : false;
				$file_meta['enable_crop_editor'] = isset($file_meta['enable_crop_editor']) ? $file_meta['enable_crop_editor'] : false;
				$file_meta['cropped_image_width'] = isset($file_meta['cropped_image_width']) ? $file_meta['cropped_image_width'] : 200;
				$file_meta['cropped_image_height'] = isset($file_meta['cropped_image_height']) ? $file_meta['cropped_image_height'] : 200;
				$file_meta['min_dpi_limit'] = isset($file_meta['min_dpi_limit']) ? $file_meta['min_dpi_limit'] : 0;
				$file_meta['max_dpi_limit'] = isset($file_meta['max_dpi_limit']) ? $file_meta['max_dpi_limit'] : 0;
				$text_field_description = isset($file_meta['text_field_description']) ? $file_meta['text_field_description'] : "";
				$counter = isset($file_meta['id']) ? $file_meta['id'] : $counter;
				?>
				<li class="input_box " id="input_box_<?php echo $counter ?>"> 
					<label class="wcuf_sort_button"><span class="dashicons dashicons-sort"></span><?php _e('Drag to sort', 'woocommerce-files-upload');?></label>
					<label class="wcuf_required"><?php _e('Title (NO Html code)', 'woocommerce-files-upload');?></label>
					<input type ="hidden" class="wcuf_file_meta_id" name= "wcuf_file_meta[<?php echo $counter ?>][id]" value="<?php echo $counter; ?>" ></input>
					<input type ="hidden" class="wcuf_file_meta_sort_order" name= "wcuf_file_meta[<?php echo $counter ?>][sort_order]" value="<?php if(isset($file_meta['sort_order'])) echo $file_meta['sort_order']; else echo $counter; ?>" ></input> <!-- useless -->
					<input type="text" value="<?php if(isset($file_meta['title'])) echo $file_meta['title']; ?>" name="wcuf_file_meta[<?php echo $counter ?>][title]"  placeholder=" "  size="80" required></textarea >
					
					<div class="wcuf_visibility_info_box">
					<label><?php _e('Visibility info (according to the selected <i>Visibility per product</i> option)', 'woocommerce-files-upload');?></label>
					<span class="wcuf_visibility_type_info"><?php _e('Enabled/Disabled for:', 'woocommerce-files-upload');?></span>
					<i>
					<?php 
						switch($file_meta['enable_for'])
						{
							case 'always':
								echo $enable_for_all_text ;
							break;
							case 'categories':
								echo $enable_for_selected_categories_and_products ;	
							break;
							case 'categories_children':
								echo $enable_for_selected_categories_and_products_and_children_text ;	
							break;
							case 'disable_categories':
								echo $disabled_for_selected_categories_and_products_text ;	
							break;
							case 'disable_categories_children':
								echo $disabled_for_selected_categories_and_products_and_children_text ;	
							break;
						}
					
					?>
					</i>
					<?php //Categories
					if(!empty($selected_categories)): ?>
					<span class="wcuf_visibility_type_category_label"><?php _e('Categories:', 'woocommerce-files-upload');?></span>
					<ul>
					<?php foreach( $selected_categories as $category_id)
							{
								echo '<li>'.$wcuf_product_model->get_product_category_name($category_id).'</li>';
							}
					?>
					</ul>
					<?php endif; ?>
					<?php //Products
					if(!empty($selected_products)): ?>
					<span class="wcuf_visibility_type_product_label"><?php _e('Products:', 'woocommerce-files-upload');?></span>
					<ul>
					<?php foreach( $selected_products as $product_id)
							{
								echo '<li>'.$wcuf_product_model->get_product_name($product_id).'</li>';
							}
					?>
					</ul>
					<?php endif; ?>
					</div>
					<button data-id="<?php echo $counter ?>" class="button wcuf_collapse_options"><?php _e('Collapse/Expand Options Box', 'woocommerce-files-upload');?></button>
					<button class="remove_field button-secondary" data-id="<?php echo $counter ?>"><?php _e('Remove upload', 'woocommerce-files-upload');?></button>
					<div id="wcuf_collapsable_box_<?php echo $counter ?>" class="wcuf_collapsable_box wcuf_box_hidden">
						<label><?php _e('Description (HTML code permitted)', 'woocommerce-files-upload');?></label>
						<textarea  class="upload_description"  rows="5" cols="80" name="wcuf_file_meta[<?php echo $counter ?>][description]" placeholder="<?php _e('Description (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php if(isset($file_meta['description'])) echo $file_meta['description']; ?></textarea>
						
						<label class="option_label"><?php _e('Hide description after an upload has been completed?', 'woocommerce-files-upload');?></label>
						<input class="variant_option_input" type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][hide_upload_after_upload]" value="true" <?php if(isset($file_meta['hide_upload_after_upload']) && $file_meta['hide_upload_after_upload']) echo 'checked="checked"'?> ></input>
						
						<label class="wcuf_already_uploaded_message_label"><?php _e('Text to show after the upload has been completed (HTML code permitted)', 'woocommerce-files-upload'); ?></label>
						<p><?php _e('Permitted shortcodes:<br/><strong>[file_name]</strong> to display the file(s) name list. For every file is also reported the additional cost (only if any of the extra costs option have been enabled)<br/><strong>[file_name_no_cost]</strong> like previous but without costs display<br/><strong>[file_name_with_media_preview]</strong> like [file_name] shotcode with image preview (if the file(s) is a jpg/png) and audio files (mp3/wav) <br/><strong>[file_name_with_media_preview_no_cost]</strong> like previous shotcode without costs display<br/><strong>[image_preview_list]</strong> to display image preview (if the file(s) is a jpg/png) and audio files (mp3/wav) <br/><strong>[uploaded_files_num]</strong> to display total number of the uploaded files (useful if the "Multiple files upload" option has been enabled)<br/><strong>[additional_costs]</strong> (tax excluded) to display the sum of the additional costs of all the uploaded files', 'woocommerce-files-upload');?></p>
						<textarea  class="upload_description"  rows="5" cols="80" name="wcuf_file_meta[<?php echo $counter ?>][message_already_uploaded]" placeholder="<?php _e('This message is displayed after file description only if a file have been uploaded (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php if(isset($file_meta['message_already_uploaded'])) echo $file_meta['message_already_uploaded']; ?></textarea>
						
						<label class="option_label"><?php _e('In case of Variable Product, display full product name (product name and variant details)? If unchecked will be displayed only product name.', 'woocommerce-files-upload');?></label>
						<input class="variant_option_input" type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][full_name_display]" value="true" <?php if(!isset($file_meta['full_name_display']) || $file_meta['full_name_display']) echo 'checked="checked"'?> ></input>
						
						<label class="option_label"><?php _e('Allowed file type(s)', 'woocommerce-files-upload');?></label>
						<input type="text" name="wcuf_file_meta[<?php echo $counter ?>][types]" placeholder="<?php _e('File type(s), ex: .jpg,.bmp,.png leave empty to accept all file types. ', 'woocommerce-files-upload'); ?>" value="<?php if(isset($file_meta['types'])) echo $file_meta['types']; ?>" size="80"></input>
						
						
						<label class="option_label wcuf_required"><?php _e('Min file size (MB) limit', 'woocommerce-files-upload');?></label>
						<p><?php _e('Leave 0 for no limits. In case of multiple files upload field, each uploaded file size cannot be greater of the specified value.', 'woocommerce-files-upload');?></p>
						<input type="number" min="0"  name="wcuf_file_meta[<?php echo $counter ?>][min_size]" value="<?php if(isset($file_meta['min_size'])) echo $file_meta['min_size']; else echo "0";?>" required></input>
						
						<label class="option_label wcuf_required"><?php _e('Max file size (MB) limit', 'woocommerce-files-upload');?></label>
						<!-- <p><strong><?php _e('NOTE:', 'woocommerce-files-upload');?></strong> <?php echo $php_settings_notice;?></p> -->
						<p><?php _e('Leave 0 for no limits. In case of multiple files upload field, each uploaded file size cannot be greater of the specified value.', 'woocommerce-files-upload');?></p>
						<?php // max="<?php echo $size_that_can_be_posted;" ?>
						<input type="number" min="0"  name="wcuf_file_meta[<?php echo $counter ?>][size]" value="<?php if(isset($file_meta['size'])) echo $file_meta['size']; /*else echo $size_that_can_be_posted; */ else echo "0";?>" required></input>
						
						<label class="option_label"><?php _e('Custom CSS id', 'woocommerce-files-upload');?></label>
						<input class="" type="text" name="wcuf_file_meta[<?php echo $counter ?>][field_css_id]" value="<?php if(isset($file_meta['field_css_id'])) echo $file_meta['field_css_id']; ?>" ></input>
						
						<label><?php _e('Can user delete file(s)?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][user_can_delete]" value="true" <?php if(!isset($file_meta['user_can_delete']) || $file_meta['user_can_delete']) echo 'checked="checked"'?> ></input>
						<label><?php _e('Can user download uploaded file?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][user_can_download_his_files]" value="true" <?php if(!isset($file_meta['user_can_download_his_files']) || $file_meta['user_can_download_his_files']) echo 'checked="checked"'?> ></input>
						
						<label><?php _e('Upload fields are visible also for Orders marked as <i>completed</i> (Valid only for Order details page)?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][upload_fields_editable_for_completed_orders]" value="true" <?php if(isset($file_meta['upload_fields_editable_for_completed_orders']) && $file_meta['upload_fields_editable_for_completed_orders']) echo 'checked="checked"'?> ></input>
						
						<h3><?php _e('Page visibility: select in which page the upload has to be visible', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Display field on Checkout page?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][display_on_checkout]" value="true" <?php if(isset($file_meta['display_on_checkout']) && $file_meta['display_on_checkout']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Display field on Cart page?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" data-id="<?php echo $counter ?>" class="wcuf_display_on_cart_checkbox" name="wcuf_file_meta[<?php echo $counter ?>][display_on_cart]" value="true" <?php if(isset($file_meta['display_on_cart']) && $file_meta['display_on_cart']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Display field on Product page?', 'woocommerce-files-upload');?></label>
						<p><?php  _e('This will enable the "Upload per product" option. <strong>NOTE:</strong> for products for which has been enabled the "Sold as individual" feature (selected using the <i>Individual products configurator</i>), the upload field will be show automatically <strong>BEFORE</strong> adding them to cart. ', 'woocommerce-files-upload') ?></p> 
						<input type="checkbox" data-id="<?php echo $counter ?>" class="wcuf_display_on_product_checkbox" name="wcuf_file_meta[<?php echo $counter ?>][display_on_product]" value="true" <?php if(isset($file_meta['display_on_product']) && $file_meta['display_on_product']) echo 'checked="checked"'?> ></input>
						 
						<div class="wcuf_product_page_visibility_sub_option" id="wcuf_display_on_product_before_adding_to_cart_container_<?php echo $counter ?>">
							<label style="margin-top:20px;"><?php _e('on Product page, display the field BEFORE adding an item to the cart?', 'woocommerce-files-upload');?></label>
							<input type="checkbox" data-id="<?php echo $counter ?>" id="wcuf_display_on_product_before_adding_to_cart_<?php echo $counter ?>" class="" name="wcuf_file_meta[<?php echo $counter ?>][display_on_product_before_adding_to_cart]" value="true" <?php if(isset($file_meta['display_on_product_before_adding_to_cart']) && $file_meta['display_on_product_before_adding_to_cart']) echo 'checked="checked"'?> ></input>
							<p><?php  echo $upload_product_page_before_instruction; ?></p>
						</div>
						
						<label style="margin-top:20px;"><?php _e('Display field on Order detail page?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][display_on_order_detail]" value="true" <?php if(!isset($file_meta['display_on_order_detail']) || $file_meta['display_on_order_detail']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Hide on shortcode upload form?', 'woocommerce-files-upload');?></label>
						<p><?php _e('By default using the <strong>[wcuf_upload_form]</strong> shortcode all the upload fields that  match products in the cart are visible. Enabling this option this field will be hidden.', 'woocommerce-files-upload');?></p>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][hide_on_shortcode_form]" value="true" <?php if(isset($file_meta['hide_on_shortcode_form']) && $file_meta['hide_on_shortcode_form']) echo 'checked="checked"'?> ></input>
						
						
						
						<h3><?php _e('Upload field type: Show one upload field per order or per product', 'woocommerce-files-upload');?></h3>
						<p><?php echo $upload_per_product_instruction; ?>
						</p>
						<label style="margin-top:20px;"  ><?php _e('Upload per product', 'woocommerce-files-upload');?></label>
						<input type="checkbox" id="wcuf_multiple_uploads_checkbox_<?php echo $counter ?>" name="wcuf_file_meta[<?php echo $counter ?>][disable_stacking]" value="true" <?php if(isset($file_meta['disable_stacking']) && $file_meta['disable_stacking']) echo 'checked="checked"' ?> ></input>
						
						<label style="margin-top:20px;"  ><?php _e('Enable one upload field for every single product variation? (Works only with variable products,  if the "Upload per product" option has been enabled and all variations have been created. In case of "Any" variations, upload field will not work)', 'woocommerce-files-upload');?></label>
						<input type="checkbox"  name="wcuf_file_meta[<?php echo $counter ?>][disable_stacking_for_variation]" value="true" <?php if(isset($file_meta['disable_stacking_for_variation']) && $file_meta['disable_stacking_for_variation']) echo 'checked="checked"'?> ></input>
						
					
						<h3><?php _e('Multiple files upload per single upload field', 'woocommerce-files-upload');?></h3>
						<?php //if(!class_exists('ZipArchive')): ?>
							<!-- <strong><?php _e('This feature is not available because your server has not the "ZipArchive" php extension installed.', 'woocommerce-files-upload');?></strong> -->
						<?php //else: ?>
							<p><strong><?php _e('NOTE:', 'woocommerce-files-upload');?></strong> <?php _e('Using the <i>Upload files Configurator -> Options menu</i> you can also enable the special <strong>Enable quantity selection</strong> option that will allow your customers to specify a quantity value for each upload.', 'woocommerce-files-upload');?></p>
							
							
							<label style="margin-top:20px;"  ><?php _e('Enable multiple files upload per single field?', 'woocommerce-files-upload');?></label>
							<input type="checkbox"  name="wcuf_file_meta[<?php echo $counter ?>][enable_multiple_uploads_per_field]" value="true" <?php if(isset($file_meta['enable_multiple_uploads_per_field']) && $file_meta['enable_multiple_uploads_per_field']) echo 'checked="checked"'?> ></input>
							
							<label style="margin-top:20px;"  class="wcuf_required"><?php _e('Min file sizes sum limit (MB)', 'woocommerce-files-upload');?></label>
							<p><?php echo  _e('For each upload process, the sum of the file sizes cannot excede the following value. Leave 0 for no limit', 'woocommerce-files-upload'); ?></p>
							<input type="number" min="0" step="1" name="wcuf_file_meta[<?php echo $counter ?>][multiple_files_min_size_sum]" value="<?php if(isset($file_meta['multiple_files_min_size_sum'])) echo $file_meta['multiple_files_min_size_sum']; else echo 0; ?>"  required></input>
							
							
							<label style="margin-top:20px;"  class="wcuf_required"><?php _e('Max file sizes sum limit (MB)', 'woocommerce-files-upload');?></label>
							<p><?php echo  _e('For each upload process, the sum of the file sizes cannot be lesser than following value. Leave 0 for no limit', 'woocommerce-files-upload'); ?></p>
							<input type="number" min="0" step="1" name="wcuf_file_meta[<?php echo $counter ?>][multiple_files_max_size_sum]" value="<?php if(isset($file_meta['multiple_files_max_size_sum'])) echo $file_meta['multiple_files_max_size_sum']; else echo 0; ?>"  required></input>
							
							
							<label style="margin-top:20px;"><?php _e('Disable images preview before uploading (jpg/png)?', 'woocommerce-files-upload');?></label>
							<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][preview_images_before_upload_disabled]" value="true" <?php if(isset($file_meta['preview_images_before_upload_disabled']) && $file_meta['preview_images_before_upload_disabled']) echo 'checked="checked"'?> ></input>
							
							
							<div class="wcuf_standard_bordered_box">
								<label class="wcuf_required"><?php _e('Minimum number of files that can bes. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" option has been enabled)', 'woocommerce-files-upload');?></label>
								<input type="number"  min="0" name="wcuf_file_meta[<?php echo $counter ?>][multiple_uploads_minimum_required_files]" value="<?php if(isset($file_meta['multiple_uploads_minimum_required_files']) && $file_meta['multiple_uploads_minimum_required_files']) echo $file_meta['multiple_uploads_minimum_required_files']; else echo 0; ?>" required></input>
							</div>
							
							<div class="wcuf_standard_bordered_box">
								<label  class="wcuf_required"><?php _e('Max number of files that can be uploaded. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" has been enabled)', 'woocommerce-files-upload');?></label>
								<!--<p><?php echo sprintf(__('<strong>NOTE:</strong> Accorting to your PHP settings (<strong>max_file_uploads</strong>), you can upload no more than <strong>%s</strong> files. Please adjust that value in your PHP.ini if you want to upload more files.', 'woocommerce-files-upload'), $max_file_uploads); ?></p>-->
								<input type="number"  min="0"  name="wcuf_file_meta[<?php echo $counter ?>][multiple_uploads_max_files]" value="<?php if(isset($file_meta['multiple_uploads_max_files']) && $file_meta['multiple_uploads_max_files']) echo $file_meta['multiple_uploads_max_files']; else echo 0 /*$max_file_uploads;*/ ?>"   required></input>
								<!-- max="<?php echo $max_file_uploads;?>" -->
							</div>
							
							<label style=" margin-top:20px"  ><?php _e('Max number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
							<input type="checkbox"  name="wcuf_file_meta[<?php echo $counter ?>][multiple_uploads_max_files_depends_on_quantity]" value="true" <?php if(isset($file_meta['multiple_uploads_max_files_depends_on_quantity']) && $file_meta['multiple_uploads_max_files_depends_on_quantity']) echo 'checked="checked"'?> ></input>
							
							<label style=""  ><?php _e('Minimum number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
							<input type="checkbox"  name="wcuf_file_meta[<?php echo $counter ?>][multiple_uploads_min_files_depends_on_quantity]" value="true" <?php if(isset($file_meta['multiple_uploads_min_files_depends_on_quantity']) && $file_meta['multiple_uploads_min_files_depends_on_quantity']) echo 'checked="checked"'?> ></input>
							
						<?php //endif; ?>
						
						<h3><?php _e('Requirement', 'woocommerce-files-upload');?></h3>
						<p><?php echo $required_field_instruction; ?><br/>
						<?php _e('In case you want to <strong>give the possibility to leave the page</strong>, go to the <strong>Options</strong> menu and under <strong>Allow user to leave page in case of required field</strong> section select <strong>Yes</strong> option.','woocommerce-files-upload'); ?></p>
						<p><strong><?php _e('NOTE','woocommerce-files-upload');?>:</strong> <?php _e('if enabling this option your are experiencing multiple "Add to cart" buttons issues on your shop page, go to the Option menu and set False for the Disable View Button option', 'woocommerce-files-upload'); ?></p>
						<label style="margin-top:20px;"><?php _e('Upload is required', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][required_on_checkout]" value="true" <?php if(isset($file_meta['required_on_checkout']) && $file_meta['required_on_checkout']) echo 'checked="checked"'?> ></input>
						
						<h3><?php _e('Image media file (only for jpg/png media files)', 'woocommerce-files-upload');?></h3>
						<label><?php _e('Enable crop editor (this option will be ignored in case the multiple file option has been enabled)', 'woocommerce-files-upload');?></label>
						<input type="checkbox" min="0" name="wcuf_file_meta[<?php echo $counter ?>][enable_crop_editor]" value="true" <?php if(isset($file_meta['enable_crop_editor']) && $file_meta['enable_crop_editor']) echo 'checked="checked"'; ?>></input>
						
						<label class="wcuf_required"><?php _e('Cropped image width', 'woocommerce-files-upload');?></label>
						<input type="number" min="1" step="1" name="wcuf_file_meta[<?php echo $counter ?>][cropped_image_width]" value="<?php if(isset($file_meta['cropped_image_width'])) echo $file_meta['cropped_image_width']; ?>" required></input>
						<label class="wcuf_required"><?php _e('Cropped image height', 'woocommerce-files-upload');?></label>
						<input type="number" min="1"  step="1" name="wcuf_file_meta[<?php echo $counter ?>][cropped_image_height]" value="<?php if(isset($file_meta['cropped_image_height'])) echo $file_meta['cropped_image_height']; ?>" required></input>
						
						<div class="wcuf_dimensions_box">
							<label class="wcuf_required"><?php _e('Input image min width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][min_width_limit]" value="<?php if(isset($file_meta['min_width_limit'])) echo $file_meta['min_width_limit']; ?>" required></input>
							<label class="wcuf_required"><?php _e('Input image max height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][height_limit]" value="<?php if(isset($file_meta['height_limit'])) echo $file_meta['height_limit']; ?>" required></input>
					
							<select  name="wcuf_file_meta[<?php echo $counter ?>][dimensions_logical_operator]" class="wcuf_dimensions_logical_operator">
							  <option value="and" <?php if(isset($file_meta['dimensions_logical_operator']) && $file_meta['dimensions_logical_operator'] == 'and') echo 'selected'; ?>><?php _e('AND', 'woocommerce-files-upload');?></option>
							  <option value="or" <?php if(isset($file_meta['dimensions_logical_operator']) && $file_meta['dimensions_logical_operator'] == 'or') echo 'selected'; ?>><?php _e('OR', 'woocommerce-files-upload');?></option>
							</select>
							
							<label class="wcuf_required"><?php _e('Input image min height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][min_height_limit]" value="<?php if(isset($file_meta['min_height_limit'])) echo $file_meta['min_height_limit']; ?>" required></input>
							<label class="wcuf_required"><?php _e('Input image max width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][width_limit]" value="<?php if(isset($file_meta['width_limit'])) echo $file_meta['width_limit']; ?>" required></input>
						</div>
						
						<div class="wcuf_dpi_box">
							<label class="wcuf_required"><?php _e('Input image min DPI. Leave 0 for no limits (DPI are read from EXIF. If an image has no valid EXIF data check will fail and the upload will not be performed)', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][min_dpi_limit]" value="<?php echo $file_meta['min_dpi_limit'] ?>" required></input>
							<label class="wcuf_required"><?php _e('Input image max DPI. Leave 0 for no limits (DPI are read from EXIF. If an image has no valid EXIF data check will fail and the upload will not be performed)', 'woocommerce-files-upload');?></label>
							<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][max_dpi_limit]" value="<?php echo $file_meta['max_dpi_limit'] ?>" required></input>
						</div>
						
						<h3><?php _e('Extra costs (will not take effect on Order details page)', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Enable extra cost per upload?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_enabled]" value="true" <?php if(isset($file_meta['extra_cost_enabled']) && $file_meta['extra_cost_enabled']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_is_taxable]" value="true" <?php if(isset($file_meta['extra_cost_is_taxable']) && $file_meta['extra_cost_is_taxable']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Apply extra costs considering the item cart quantity', 'woocommerce-files-upload');?></label>
						<p><?php _e('The computed extra cost will be multiplied for the product cart quantity. If not, the extra cost will be applied only once regardles of item cart quantity. <strong>NOTE:</strong> This option will only work if the <strong>Upload per product</strong> option has been enabled (<strong>Upload field type</strong> section).', 'woocommerce-files-upload');?></p>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_multiply_per_product_cart_quantity]" value="true" <?php if(isset($file_meta['extra_cost_multiply_per_product_cart_quantity']) && $file_meta['extra_cost_multiply_per_product_cart_quantity']) echo 'checked="checked"'?> ></input>
						
						
						<label style="margin-top:20px;"><?php _e('Select overcharge type (Percentace type will not work if "Upload per product" option has not been enabled)', 'woocommerce-files-upload');?></label>							
						<p><?php _e('<strong>NOTE:</strong> if you are appling the <strong>Percentage</strong> option to a Variation/Variable product, make sure to have enabled the <strong>Enable one upload field for every single product variation</strong> option (<strong>Upload field type</strong> section) and disabled the <strong>on Product page, display the field BEFORE adding an item to the cart</strong> option (<strong>Page visibility</strong> section).', 'woocommerce-files-upload');?></p>
						
						<select  name="wcuf_file_meta[<?php echo $counter ?>][extra_overcharge_type]">
						  <option value="fixed" <?php if(isset($file_meta['extra_overcharge_type']) && $file_meta['extra_overcharge_type'] == 'fixed') echo 'selected'; ?>><?php _e('Fixed value', 'woocommerce-files-upload');?></option>
						  <option value="percentage" <?php if(isset($file_meta['extra_overcharge_type']) && $file_meta['extra_overcharge_type'] == 'percentage') echo 'selected'; ?>><?php _e('Percentage of item price', 'woocommerce-files-upload');?></option>
						</select>
						
						<label style="margin-top:20px; "><?php _e('Value (this will be the percentage or the fixed value added to the original item price)', 'woocommerce-files-upload');?></label>
						<p><?php _e('<strong>NOTE:</strong> using negative values, the fixed/percentage value will be subtracted to the cart (applying then a discount).', 'woocommerce-files-upload');?></p>
						
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_value]"  step="0.0001" value="<?php if(isset($file_meta['extra_cost_value'])) echo $file_meta['extra_cost_value']; else echo '1';?>" ></input>
						
						<?php if (true/* extension_loaded('imagick') */): ?>
						<label style="margin-top:20px;"><?php _e('Detect <span class="wcuf_pdf_label">PDF</span>', 'woocommerce-files-upload');?></label>
						<p><?php _e('The extra costs will be applied to each detected page.', 'woocommerce-files-upload');?></p>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_detect_pdf]" value="true" <?php if(isset($file_meta['extra_cost_detect_pdf']) && $file_meta['extra_cost_detect_pdf']) echo 'checked="checked"'?> ></input>
						<?php endif; ?>
						
						<label style="margin-top:20px;"><?php _e('Overcharge uploads limit', 'woocommerce-files-upload');?></label>
						<p><?php _e('Applies only if "Multiple files upload per single field" option has been enabled. If the number of uploaded files (excluding the "Free intems" defined in the option below) will pass this value will not be added extra overcharge for exceding uploads. Leave 0 for no limits. ', 'woocommerce-files-upload');?>
						<?php if (true/* extension_loaded('imagick') */):
							_e('In case of PDF detection: this will option will be applied to pages and in case di multiple uploads, extra cost pages limit is considered globally per field and not per each pdf.','woocommerce-files-upload');
						 endif; ?>
						</p>
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_overcharge_limit]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_overcharge_limit'])) echo $file_meta['extra_cost_overcharge_limit']; else echo '0';?>" ></input>
					
						<label style="margin-top:20px;"><?php _e('Free items', 'woocommerce-files-upload');?></label>
						<p><?php _e('Applies only if "Multiple files upload per single field" option has been enabled. For the first N uploads will not be applied any extra cost (where N is the value specified using the following number field). Leave 0 if you do not want to enable this option. ', 'woocommerce-files-upload');?>
						<?php if (true/* extension_loaded('imagick') */):
							_e('In case of PDF detection: this will be considered as "free pages number" and in case di multiple uploads, free pages are computed globally per field and not per each pdf.','woocommerce-files-upload');
						 endif; ?>
						</p>
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_free_items_number]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_free_items_number'])) echo $file_meta['extra_cost_free_items_number']; else echo '0';?>" ></input>
					
							
						<h3><?php _e('Extra costs per second (ONLY APPLICABLE IF UPLOADED FILE IS AN AUDIO/VIDEO)', 'woocommerce-files-upload');?></h3>
						<p><?php _e('WCUF will try do detect media file the duration (in seconds) extracting the info from its ID3 data (if any and well encoded). An extra cost will be added to the products for the seconds detected.', 'woocommerce-files-upload');?></p>
						<label style="margin-top:20px;"><?php _e('Enable extra cost per second?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_media_enabled]" value="true" <?php if(isset($file_meta['extra_cost_media_enabled']) && $file_meta['extra_cost_media_enabled']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_media_is_taxable]" value="true" <?php if(isset($file_meta['extra_cost_media_is_taxable']) && $file_meta['extra_cost_media_is_taxable']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Display the "Cost per second" on cart? (Will be added an extra text reporting how much cost a second)', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][show_cost_per_second]" value="true" <?php if(isset($file_meta['show_cost_per_second']) && $file_meta['show_cost_per_second']) echo 'checked="checked"'?> ></input>
						
						
						<label style="margin-top:20px; "><?php _e('Additional cost per second', 'woocommerce-files-upload');?></label>
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_per_second_value]" step="0.01" value="<?php if(isset($file_meta['extra_cost_per_second_value'])) echo $file_meta['extra_cost_per_second_value']; else echo '1';?>" ></input>
						
						
						<label style="margin-top:20px;"><?php _e('Maximun seconds overcharge limit', 'woocommerce-files-upload');?></label>
						<p><?php _e('If the number of seconds (excluding the "Free seconds" defined in the option below) will pass this value will not be added extra overcharge for exceding seconds. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_overcharge_seconds_limit]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_overcharge_seconds_limit'])) echo $file_meta['extra_cost_overcharge_seconds_limit']; else echo '0';?>" ></input>
						
						<label style="margin-top:20px;"><?php _e('Free seconds', 'woocommerce-files-upload');?></label>
						<p><?php _e('First N seconds can be free, set the desidered values. Leave 0 for no free seconds.', 'woocommerce-files-upload');?></p>
						<input class="wcuf_no_margin_bottom" type="number" name="wcuf_file_meta[<?php echo $counter ?>][extra_cost_free_seconds]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_free_seconds'])) echo $file_meta['extra_cost_free_seconds']; else echo '0';?>" ></input>
						
						
						<h3><?php _e('Feedback', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Add a text field where the customer can input text? (Note: text must be inserted before files are uploaded)', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][text_field_on_order_details_page]" value="true"  <?php if(isset($file_meta['text_field_on_order_details_page']) && $file_meta['text_field_on_order_details_page']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Label', 'woocommerce-files-upload');?></label>
						<input type="text" name="wcuf_file_meta[<?php echo $counter ?>][text_field_label]" value="<?php if(isset($file_meta['text_field_label'])) echo $file_meta['text_field_label']; ?>"   ></input>
						
						<label ><?php _e('Description (HTML  allowed)', 'woocommerce-files-upload');?></label>
						<textarea type="text" name="wcuf_file_meta[<?php echo $counter ?>][text_field_description]" cols="80" rows="5"><?php echo $text_field_description; ?></textarea>
						
						<label style=""><?php _e('Max input characters (leave 0 for no limits)', 'woocommerce-files-upload');?></label>
						<input type="number" min="0" name="wcuf_file_meta[<?php echo $counter ?>][text_field_max_input_chars]" value="<?php if(isset($file_meta['text_field_max_input_chars'])) echo $file_meta['text_field_max_input_chars']; else echo 0; ?>"   ></input>
						
						<label style=""><?php _e('Is required?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][is_text_field_on_order_details_page_required]" value="true"  <?php if(isset($file_meta['is_text_field_on_order_details_page_required']) && $file_meta['is_text_field_on_order_details_page_required']) echo 'checked="checked"'?> ></input>
						
						<h3><?php _e('Disclaimer', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Add a disclaimer checkbox?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][disclaimer_checkbox]" value="true"  <?php if(isset($file_meta['disclaimer_checkbox']) && $file_meta['disclaimer_checkbox']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Disclameir checkbox label (HTML accepted. Ex: "I have read and accepted the &lt;a href="www.link.to/disclaimer"&gt; Disclaimer &lt;/a&gt;")', 'woocommerce-files-upload');?></label>
						<textarea type="text" class="wcuf_disclaimer_text" name="wcuf_file_meta[<?php echo $counter ?>][disclaimer_text]" cols="80" rows="5"><?php if(isset($file_meta['disclaimer_text'])) echo $file_meta['disclaimer_text']; ?></textarea>
						
						<h3><?php _e('Notifications', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Notify admin via email when customer completed the upload?', 'woocommerce-files-upload');?></label>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][notify_admin]" value="true" <?php if(isset($file_meta['notify_admin']) && $file_meta['notify_admin']) echo 'checked="checked"'?> ></input>
						
						<label style="margin-top:20px;"><?php _e('Attach uploaded file to admin notification email? (<strong>NOTE:</strong> this option works only if admin notification email option has been enabled and for files stored locally)', 'woocommerce-files-upload');?></label>
						<p><small><?php _e('Remember that some some server email provider will not receive emails with attachments bigger than 10MB (<a target="_blank" href="https://www.outlook-apps.com/maximum-email-size/">Gmail: 25MB, Outlook and Hotmail 10MB,...</a>)', 'woocommerce-files-upload'); ?></small></p>
						<input type="checkbox" name="wcuf_file_meta[<?php echo $counter ?>][notify_attach_to_admin_email]" value="true" <?php if(isset($file_meta['notify_attach_to_admin_email']) && $file_meta['notify_attach_to_admin_email']) echo 'checked="checked"'?> ></input>
						
						<label class="option_label"><?php _e('Recipient(s)', 'woocommerce-files-upload');?></label>
						<p><small><?php  _e('Leave empty to send notifications to site admin email address.', 'woocommerce-files-upload'); ?></small></p>
						<input type="text" name="wcuf_file_meta[<?php echo $counter ?>][notifications_recipients]" placeholder="<?php _e("You can insert multiple email addresses comma separated, ex.: 'admin@site.com, managment@site.com'", "woocommerce-files-upload"); ?>" value="<?php echo $notifications_recipients; ?>" size="100"></input>
						
						
						<h3><?php _e('Visibility per role', 'woocommerce-files-upload');?></h3>
						<p><?php _e('<strong>Leave unselected to leave the upload field visible for all.</strong> Selecting at least one role will make the upload field to be visible/unvisible to that role.', 'woocommerce-files-upload');?></p>
						<label style="margin-top:20px;"><?php _e('Visibility type', 'woocommerce-files-upload');?></label>		
						<select  class="upload_type"  name="wcuf_file_meta[<?php echo $counter ?>][roles_policy]">
						  <option value="allow" <?php if(isset($file_meta['roles_policy']) && $file_meta['roles_policy'] == 'allow') echo 'selected'; ?>><?php _e('Allow for selected roles', 'woocommerce-files-upload');?></option>
						  <option value="deny" <?php if(isset($file_meta['roles_policy']) && $file_meta['roles_policy'] == 'deny') echo 'selected'; ?>><?php _e('Deny for selected roles', 'woocommerce-files-upload');?></option>
						</select>
						
						<label style="margin-top:20px;"><?php _e('Select roles', 'woocommerce-files-upload');?></label>	
						<?php foreach($wcuf_customer_model->get_user_roles() as $role_code => $role_name): ?>
							<?php $checked = isset($file_meta['roles'][$role_code]) ? ' checked="checked" ' : "";?>
							<label style="font-weight:normal;"><input type="checkbox" <?php echo $checked; ?> name="wcuf_file_meta[<?php echo $counter ?>][roles][<?php echo $role_code; ?>]" value="1"><?php echo $role_name['name'] ?></label>
						<?php endforeach; ?>
							<?php $checked = isset($file_meta['roles']['not_logged']) ? ' checked="checked" ' : "";?>
							<label style="font-weight:normal;"><input type="checkbox" <?php echo $checked; ?> name="wcuf_file_meta[<?php echo $counter ?>][roles][not_logged]" value="1"><?php _e('Guest (<strong>Not logged user</strong>)', 'woocommerce-files-upload');?></label>
						
						
						<h3><?php _e('Visibility per Payment Gatway (will take effect only in <i>order details</i> and <i>checkout</i> pages and will be visible only in those pages)', 'woocommerce-files-upload');?></h3>
						<p><?php _e('<strong>Leave unselected to leave the upload field visible for all gateways.</strong> Selecting at least one gateway option will make the upload field to be visible/unvisible to that gateway and only in <strong>Order details</strong> and <strong>Checkout</strong> pages.', 'woocommerce-files-upload');?></p>
						
						<label style="margin-top:20px;"><?php _e('Visibility type', 'woocommerce-files-upload');?></label>		
						<select  class="upload_type"  name="wcuf_file_meta[<?php echo $counter ?>][visibility_payment_gateway_policy]">
						  <option value="allow" <?php if(isset($file_meta['visibility_payment_gateway_policy']) && $file_meta['visibility_payment_gateway_policy'] == 'allow') echo 'selected'; ?>><?php _e('Allow for selected gateways', 'woocommerce-files-upload');?></option>
						  <option value="deny" <?php if(isset($file_meta['visibility_payment_gateway_policy']) && $file_meta['visibility_payment_gateway_policy'] == 'deny') echo 'selected'; ?>><?php _e('Deny for selected gateways', 'woocommerce-files-upload');?></option>
						</select>
						
						<label style="margin-top:20px;"><?php _e('Select gateways', 'woocommerce-files-upload');?></label>	
						<?php $gateways = new WC_Payment_Gateways() ?>
						<?php foreach($gateways->payment_gateways( ) as $gateway_code => $gateway): ?>
							<?php $checked = isset($file_meta['visibility_gateways'][$gateway_code]) ? ' checked="checked" ' : "";?>
							<label style="font-weight:normal;"><input type="checkbox" <?php echo $checked; ?> name="wcuf_file_meta[<?php echo $counter ?>][visibility_gateways][<?php echo $gateway_code; ?>]" value="1"><?php echo $gateway->title; ?></label>
						<?php endforeach; ?>
						
						<h3><?php _e('Visibility per product: Upload field may be visible only for selected products/catgories', 'woocommerce-files-upload');?></h3>
						<label style="margin-top:20px;"><?php _e('Filtering criteria: This upload field will be', 'woocommerce-files-upload');?></label>							
						<select  class="upload_type" data-id="<?php echo $counter ?>" name="wcuf_file_meta[<?php echo $counter ?>][enable_for]">
						  <option value="always" <?php if(isset($file_meta['enable_for']) && $file_meta['enable_for'] == 'always') echo 'selected'; ?>><?php echo $enable_for_all_text; ?></option>
						  <option value="categories" <?php if(isset($file_meta['enable_for']) && $file_meta['enable_for'] == 'categories') echo 'selected'; ?>><?php echo $enable_for_selected_categories_and_products; ?></option>
						  <option value="categories_children" <?php if(isset($file_meta['enable_for']) && $file_meta['enable_for'] == 'categories_children') echo 'selected'; ?>><?php echo $enable_for_selected_categories_and_products_and_children_text ?></option>
						  <option value="disable_categories"  <?php if(isset($file_meta['enable_for']) && $file_meta['enable_for'] == 'disable_categories') echo 'selected'; ?>><?php echo $disabled_for_selected_categories_and_products_text?></option>
						  <option value="disable_categories_children"  <?php if(isset($file_meta['enable_for']) && $file_meta['enable_for'] == 'disable_categories_children') echo 'selected'; ?>><?php echo $disabled_for_selected_categories_and_products_and_children_text?></option>
						</select>
						<div class="spacer" ></div>
						<div class="upload_categories_box" id='upload_categories_box<?php echo $counter ?>'>
						<label><?php _e('Select category(ies) (search typing category name)', 'woocommerce-files-upload');?></label>
						<?php  
							/* WCUF_UploadFieldsConfiguratorPage::WCUF_switch_to_default_lang();
							$select_cats = wp_dropdown_categories( array( 'echo' => 0, 'hide_empty' => 0, 'taxonomy' => 'product_cat', 'hierarchical' => 1) );
							WCUF_UploadFieldsConfiguratorPage::WCUF_restore_current_lang();
							
							if(count($selected_categories) > 0)
							{
								//set selected (if exists)
								foreach($selected_categories as $category_id)
									$select_cats = str_replace('value="'.$category_id.'"', 'value="'.$category_id.'" selected', $select_cats);
									
							}
							
							$select_cats = str_replace( "name='cat' id='cat' class='postform'", "style='width:200px;' id='upload_type_id".$counter."' name='wcuf_file_meta[".$counter."][categories][]' class='js-multiple' multiple='multiple' ", $select_cats ); 
							 echo $select_cats;  */
							 ?>
							 <select class="js-data-product-categories-ajax wcuf_select2"  id='upload_type_id<?php echo $counter; ?>' name='wcuf_file_meta[<?php echo $counter; ?>][categories][]'  multiple='multiple'> 
									<?php 
										foreach( $selected_categories as $category_id)
											{
												echo '<option value="'.$category_id.'" selected="selected" >'.$wcuf_product_model->get_product_category_name($category_id).'</option>';
											}
										?>
							</select>
							<div class="spacer" ></div>
							<label><?php echo $product_filtering_instruction;?></label>
							<select class="js-data-products-ajax wcuf_select2" id="product_select_box<?php echo $counter; ?>"  name='wcuf_file_meta[<?php echo $counter; ?>][products][]' multiple='multiple'> 
							<?php 
								foreach( $selected_products as $product_id)
									{
										echo '<option value="'.$product_id.'" selected="selected" >'.$wcuf_product_model->get_product_name($product_id).'</option>';
									}
								?>
							</select>
						</div>
						
						<div class="spacer" ></div>
						<button class="remove_field button-secondary" data-id="<?php echo $counter; ?>"><?php _e('Remove upload', 'woocommerce-files-upload');?></button>
					</div>
				</li>
		<?php $counter++; endforeach; 
	}
}