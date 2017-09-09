<!--div class="wcuf_spacer3"></div>-->
<?php if(!$is_ajax_request)
	echo '<script type="text/javascript" src="'.wcuf_PLUGIN_PATH.'/js/wcuf-frontend-global-error-catcher.js"></script>';
?>
<div id="wcuf_file_uploads_container">
<input type="hidden" value="yes" name="wcuf-uploading-data"></input>
<div id="wcuf-files-box"></div>
<?php 

//wp_nonce_field('wcuf_checkout_upload', 'wcuf_attachment_nonce');
$exists_one_required_field = false;
$exists_a_field_to_show_before_adding_item_to_cart = false;
//wcuf_var_dump($item_to_show_upload_fields);
$post_max_size = WCUF_File::return_bytes(ini_get('post_max_size'));
$max_chunk_size = WCUF_File::return_bytes($wcuf_option_model->get_php_settings('size_that_can_be_posted'));

$is_variable_product_page = isset($is_variable_product_page) ? $is_variable_product_page : false;
//$upload_max_filesize = ini_get('upload_max_filesize');
$exists_at_least_one_upload_field_bounded_to_variations = false;
$exists_at_least_one_upload_field_bounded_to_gateway = false;
$bad_chars = array('"', "'");

if(is_array($file_fields_groups))
foreach($file_fields_groups as $file_fields):  
	
		$enable_for = isset($file_fields['enable_for']) ? $file_fields['enable_for']:'always';
		$hide_upload_after_upload = isset($file_fields['hide_upload_after_upload']) ? $file_fields['hide_upload_after_upload']:false;
		$display_on_checkout = isset($file_fields['display_on_checkout']) ? $file_fields['display_on_checkout']:false;
		$display_on_product = isset($file_fields['display_on_product']) ? $file_fields['display_on_product']:false;
		$display_on_product_before_adding_to_cart = isset($file_fields['display_on_product_before_adding_to_cart']) ? $file_fields['display_on_product_before_adding_to_cart']:false;
		$display_on_cart = isset($file_fields['display_on_cart']) ? $file_fields['display_on_cart']:false;
		$display_text_field = isset($file_fields['text_field_on_order_details_page']) ? (bool)$file_fields['text_field_on_order_details_page']:false;
		$text_field_max_input_chars = !isset($file_fields['text_field_max_input_chars']) ?  0:$file_fields['text_field_max_input_chars'];
		$is_text_field_required = isset($file_fields['is_text_field_on_order_details_page_required']) ? (bool)$file_fields['is_text_field_on_order_details_page_required']:false;
		$display_disclaimer_checkbox = isset($file_fields['disclaimer_checkbox']) ? (bool)$file_fields['disclaimer_checkbox']:false;
		$disclaimer_text = isset($file_fields['disclaimer_text']) ? $file_fields['disclaimer_text']:"";
		$hide_on_shortcode_form = isset($file_fields['hide_on_shortcode_form']) ? $file_fields['hide_on_shortcode_form']:false;
		$required_on_checkout = isset($file_fields['required_on_checkout']) ? $file_fields['required_on_checkout']:false;
		$multiple_uploads_max_files_depends_on_quantity = isset($file_fields['multiple_uploads_max_files_depends_on_quantity']) && !$display_on_product_before_adding_to_cart ? $file_fields['multiple_uploads_max_files_depends_on_quantity']:false;
		$multiple_uploads_min_files_depends_on_quantity = isset($file_fields['multiple_uploads_min_files_depends_on_quantity']) && !$display_on_product_before_adding_to_cart ? $file_fields['multiple_uploads_min_files_depends_on_quantity']:false;
		$multiple_uploads_minimum_required_files = isset($file_fields['multiple_uploads_minimum_required_files']) ? $file_fields['multiple_uploads_minimum_required_files']:0;
		$disable_stacking = isset($file_fields['disable_stacking']) ? (bool)$file_fields['disable_stacking']:false;
		$disable_stacking_for_variation = isset($file_fields['disable_stacking_for_variation']) /* &&  !$display_on_product_before_adding_to_cart */? (bool)$file_fields['disable_stacking_for_variation']:false; //No longer need the "&& $display_on_product_before_adding_to_cart": now it is possible to display for variation before adding them to the cart
		$multiple_files_min_size_sum = isset($file_fields['multiple_files_min_size_sum']) ? $file_fields['multiple_files_min_size_sum']*1048576:0;
		$multiple_files_max_size_sum = isset($file_fields['multiple_files_max_size_sum']) ? $file_fields['multiple_files_max_size_sum']*1048576:0;
		$min_size = isset($file_fields['min_size']) ? $file_fields['min_size']*1048576:0;
		$selected_categories = isset($file_fields['category_ids']) ? $file_fields['category_ids']:array();
		$enable_multiple_uploads_per_field = isset($file_fields['enable_multiple_uploads_per_field']) ? (bool)$file_fields['enable_multiple_uploads_per_field'] : false;
		$display_product_fullname = isset($file_fields['full_name_display']) ? $file_fields['full_name_display']:true; //Usefull only for variable products
		$all_products_cats_ids = array();
		$products_for_which_stacking_is_disabled = array();
		$can_render = $enable_for == 'always' ? true:false;
		$dimensions_logical_operator = isset($file_fields['dimensions_logical_operator']) ? $file_fields['dimensions_logical_operator'] : 'and';
		$max_width = isset($file_fields['width_limit']) ? $file_fields['width_limit'] : 0;
		$max_height = isset($file_fields['height_limit']) ? $file_fields['height_limit'] : 0;
		$min_width_limit = isset($file_fields['min_width_limit']) ? $file_fields['min_width_limit'] : 0;
		$min_height_limit = isset($file_fields['min_height_limit']) ? $file_fields['min_height_limit'] : 0;
		$min_dpi_limit = isset($file_fields['min_dpi_limit']) ? $file_fields['min_dpi_limit'] : 0;		
		$max_dpi_limit = isset($file_fields['max_dpi_limit']) ? $file_fields['max_dpi_limit'] : 0;	
		$enable_crop_editor = isset($file_fields['enable_crop_editor']) ?  $file_fields['enable_crop_editor']:false;
		$cropped_image_width = isset($file_fields['cropped_image_width']) ?  $file_fields['cropped_image_width']:200;
		$cropped_image_height = isset($file_fields['cropped_image_height']) ?  $file_fields['cropped_image_height']:200;
		$text_field_description = isset($file_fields['text_field_description']) ? $file_fields['text_field_description'] : "";
		$text_field_label = isset($file_fields['text_field_label']) ? $file_fields['text_field_label'] : "";
		$roles = !isset($file_fields['roles']) ?  array():$file_fields['roles'];
		$roles_policy = !isset($file_fields['roles_policy']) ?  "allow":$file_fields['roles_policy'];
		$visibility_gateways = !isset($file_fields['visibility_gateways']) ?  array():$file_fields['visibility_gateways'];
		$visibility_payment_gateway_policy = !isset($file_fields['visibility_payment_gateway_policy']) ?  "allow":$file_fields['visibility_payment_gateway_policy'];
		$preview_images_before_upload_disabled = !isset($file_fields['preview_images_before_upload_disabled']) ? false:$file_fields['preview_images_before_upload_disabled'];
		$file_fields['extra_cost_detect_pdf'] = isset($file_fields['extra_cost_detect_pdf']) ? $file_fields['extra_cost_detect_pdf'] : false;
		$exists_one_required_field = !$exists_one_required_field && $required_on_checkout ? true:$exists_one_required_field;
		$exists_at_least_one_upload_field_bounded_to_gateway = $exists_at_least_one_upload_field_bounded_to_gateway ? true : !empty($visibility_gateways);
		
		
		//Role check
		if(!empty($roles) && !$wcuf_customer_model->belongs_to_allowed_roles($roles,$roles_policy))
			continue;
		
		if(!$disable_stacking_for_variation)
			foreach($item_to_show_upload_fields as $product)
				$product['variation_id'] = 0;
		
		//Visibility per gateway
		if(!empty($visibility_gateways) && (!isset($current_payment_method) || !$wcuf_order_model->is_selected_payment_method_allowed($current_payment_method, $visibility_gateways,$visibility_payment_gateway_policy)))
		{
			foreach($item_to_show_upload_fields as $product)
			{
				$wcuf_session_model->remove_all_item_data($file_fields['id'], $product['product_id'], $product['variation_id']);
			}	
			continue;
		}
		
		
		$selected_products = isset($file_fields['products_ids']) ? $file_fields['products_ids']:array();
		$enable_upload_per_file = false;
		if(($current_page == 'shortcode' && !$hide_on_shortcode_form) || ($display_on_checkout && $current_page == 'checkout') || ($display_on_cart && $current_page == 'cart')  || ($display_on_product && $current_page == 'product'))
		{
			if(($enable_for === 'always' && $disable_stacking) || $enable_for !== 'always' && (count($selected_categories) > 0 || count($selected_products) > 0 ))
			{
				//for every product in the order, look for its categories and parent categories ids
				//WCUF_UploadFieldsConfiguratorPage::WCUF_switch_to_default_lang();
				
				//Forcing field to show even if the product has not been added to the cart
				$filtered_item_to_show_upload_fields = $item_to_show_upload_fields;
				if($display_on_product && $current_page == 'product' && ($display_on_product_before_adding_to_cart || $wcuf_product_model->sold_as_individual_product($current_item_data["product_id"], $current_item_data["variation_id"]))) // Field will not be showed until a variation has been selected
				{
					$exists_a_field_to_show_before_adding_item_to_cart = true;
					$display_on_product_before_adding_to_cart = true; //In case was false BUT the product is sold as individul
					$filtered_item_to_show_upload_fields = array($current_item_data);
				}
				
				foreach($filtered_item_to_show_upload_fields as $product)
				{
					
					if($wcuf_product_model->sold_as_individual_product($product["product_id"], $product["variation_id"]))
					{
						//$disable_stacking_for_variation = true;
						if($current_page == 'product')
							$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] = $wcuf_cart_model->generate_unique_individual_id($product['product_id'], $product['variation_id'] , false/* !$is_ajax_request */);
						else 
							$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] = $wcuf_cart_model->retrieve_my_unique_individual_id($product);
						
					}
					else 
						$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] = 0;
					
					
					//if current item is not in cart or this is not the product page for configured id(s) (product page)
					if($current_page == 'product' && $product['product_id'] != $current_product_id)
					{
						//wcuf_var_dump($product['product_id']." ".$current_product_id);
						continue;
					}
					//$product['bundled_by']: to avoid that upload field is shown for "buldles" -> WooCommerceProduct Bundles
					if( isset($product['bundled_by']))
						continue;
					
					//WPML
					if($wcuf_wpml_helper->wpml_is_active())
					{
						$product['product_id'] = $wcuf_wpml_helper->get_main_language_id($product['product_id']);
						if($product['variation_id'] != 0)
							$product['variation_id'] = $wcuf_wpml_helper->get_main_language_id($product['variation_id'], 'product_variation');
					}
					
					//products
					$discard_field = false;
					if(!empty($selected_products) )
					{
						foreach($selected_products as $product_id)
						{	
							//wcuf_var_dump("current cart item: ".$product['product_id']." selected: ".$product_id.", enabled: ".$enable_for); 
							$variation_id = $is_variation = 0;
							//if(!$display_on_product_before_adding_to_cart) //No longer used: now it is possible to display for variation before adding them to the cart
							{
								$is_variation = $wcuf_product_model->is_variation($product_id);
								$variation_id = $is_variation > 0 ? $product_id : 0 ;
								$product_id = $is_variation > 0 ? $is_variation : $product_id ;
							}
							
							$discard_field = false;
							/* wcuf_var_dump(" id: ".$product['product_id'].", variation: ".$product['variation_id']);
							wcuf_var_dump(($product_id != $product['product_id'] && ($product['variation_id'] == 0 || $variation_id != $product['variation_id'])));
							 */
							if( ($product_id == $product['product_id'] && ($variation_id == 0 || $variation_id == $product['variation_id']) && ($enable_for === 'categories' || $enable_for === 'categories_children'))
								|| ( !in_array($product['product_id'], $selected_products) && !in_array($product['variation_id'], $selected_products) && ($enable_for === 'disable_categories' || $enable_for === 'disable_categories_children')) 
							   )
								{
									if($disable_stacking)
										$enable_upload_per_file = true;
									$can_render = true;
									
									/* $force_disable_stacking_for_variation =  $is_variation > 0 ?  true : $disable_stacking_for_variation;
									$product['force_disable_stacking_for_variation'] = $is_variation > 0 ? true : false; */
									$force_disable_stacking_for_variation = $product['force_disable_stacking_for_variation'] = $disable_stacking_for_variation;
									 
									//In case of variable
									if(!wcuf_product_is_in_array($product, $products_for_which_stacking_is_disabled, $force_disable_stacking_for_variation, $disable_stacking ))
									{
										$products_for_which_stacking_is_disabled[] = $product;
									}
								}
								elseif( $enable_for !== 'always') 
									$discard_field = true;
						}
					}
					else if($enable_for === 'always' && $disable_stacking)
					{
						$enable_upload_per_file = true;
						$can_render = true;
						//In case of variable
						if(!wcuf_product_is_in_array($product, $products_for_which_stacking_is_disabled, $disable_stacking_for_variation, $disable_stacking))
							$products_for_which_stacking_is_disabled[] = $product;
					}
							
			
					//product categories
					$product_cats = wp_get_post_terms( $product["product_id"], 'product_cat' );
					$current_product_categories_ids = array();
					foreach($product_cats as $category)
					{
						$category_id = $wcuf_wpml_helper->get_main_language_id( $category->term_id, 'product_cat');
						
						if(!$disable_stacking)
							array_push($all_products_cats_ids, (string)$category_id);
						else
							array_push($current_product_categories_ids, (string)$category_id);
						
						//parent categories
						if($enable_for == "categories_children" || $enable_for == "disable_categories_children")
						{
							$parents =  get_ancestors( $category_id , 'product_cat' ); 
							foreach($parents as $parent_id)
							{
								$temp_category = $wcuf_wpml_helper->get_main_language_id($parent_id, 'product_cat');
								if(!$disable_stacking)
									array_push($all_products_cats_ids, (string)$temp_category);
								else
									array_push($current_product_categories_ids, (string)$temp_category); //category_id
							}
						}
					}
					//Can enable upload for this product? (if stacking uploads are disabled)
					if($disable_stacking && count($selected_categories) > 0)
					{
						if($enable_for === 'categories' || $enable_for === 'categories_children')
						{
							if(array_intersect($selected_categories, $current_product_categories_ids))
							{
								if(!wcuf_product_is_in_array($product, $products_for_which_stacking_is_disabled, $disable_stacking_for_variation, $disable_stacking))
									array_push($products_for_which_stacking_is_disabled, $product);
								$can_render = true;
							}
						}
						elseif(!$discard_field)
						{
							if(!array_intersect($selected_categories, $current_product_categories_ids))
							{
								if(!wcuf_product_is_in_array($product, $products_for_which_stacking_is_disabled, $disable_stacking_for_variation, $disable_stacking))
									array_push($products_for_which_stacking_is_disabled, $product);
								$can_render = true;
							}
							else $can_render = false;
						}	
					}
				} //ends product foreach
				//WCUF_UploadFieldsConfiguratorPage::WCUF_restore_current_lang();	
				
				//NOTE $product is the last and only found element  found in foreach ************************
				
				//Cumulative ORDER catagories. If exists at least one product with an "enabled"/"disabled" category, upload field can be rendered
				if(!$disable_stacking && count($selected_categories) > 0)
					if($enable_for === 'categories' || $enable_for === 'categories_children')
					{  
						if(array_intersect($selected_categories, $all_products_cats_ids))
							$can_render = true;
					}
					elseif(!$discard_field)
					{ 
						if(!array_intersect($selected_categories, $all_products_cats_ids))
						//if( $selected_categories !== $all_products_cats_ids)
							$can_render = true;
						else $can_render = false;
					}	

				$exists_a_field_to_show_before_adding_item_to_cart = $can_render && $display_on_product && $current_page == 'product' && $display_on_product_before_adding_to_cart ? true : $exists_a_field_to_show_before_adding_item_to_cart;
				
				// Field will not be showed until a variation has been selected IF THE FIELD IS SPECIFIC TO VARIATIONS
				if($current_page == 'product' && ($is_variable_product_page && $disable_stacking_for_variation) && $display_on_product_before_adding_to_cart && !$is_ajax_request) 
				{
					$exists_at_least_one_upload_field_bounded_to_variations = true;
					continue;
				}
			}
			//End computation -> Fields rendering
			
			if($can_render): ?>
			<div class="wcuf_single_upload_field_container" id="<?php if(isset($file_fields['field_css_id'])) echo $file_fields['field_css_id'];?>">
			<?php
				if(!$disable_stacking && !$enable_upload_per_file): //?? $enable_upload_per_file == $disable_stacking  always?
				
				if(!isset($product))
					$product = null;
			
				$upload_field_id = "wcufuploadedfile_".$file_fields['id'];
				$num_of_uploaded_files = $wcuf_upload_field_model->get_num_uploaded_files_in_session($upload_field_id, $all_options['max_uploaded_files_number_considered_as_sum_of_quantities']);
				$item_in_cart_temp =  $wcuf_session_model->get_item_data($upload_field_id);
				$upload_has_been_performed = isset($item_in_cart_temp) ? true : false;
				$multiple_uploads_max_files = $upload_has_been_performed ? 0 : 1;
				$multiple_uploads_min_files = 1;
				$unlimited_uploads = $file_fields['multiple_uploads_max_files'] == 0 ? true : false;
				$feedback_can_be_peformed = $upload_has_been_performed ? false : true;
				
				//Min/max uploadable files
				if($enable_multiple_uploads_per_field)
				{
					if($required_on_checkout)
						$multiple_uploads_min_files = $multiple_uploads_min_files == 0 ? 1 : $multiple_uploads_min_files;
					$multiple_uploads_max_files  = $file_fields['multiple_uploads_max_files'] != 0 && $file_fields['multiple_uploads_max_files'] - $num_of_uploaded_files >= 0 ? $file_fields['multiple_uploads_max_files'] - $num_of_uploaded_files : 0;
					$multiple_uploads_min_files = $num_of_uploaded_files > $multiple_uploads_minimum_required_files ? 0 : $multiple_uploads_minimum_required_files - $num_of_uploaded_files;
					
					$feedback_can_be_peformed = $unlimited_uploads || $multiple_uploads_max_files > 0 ? true : false;
				}
				
				?>
				<div class="wcuf_upload_fields_row_element">
				<!-- Upload field title -->
				<<?php echo $all_options['upload_field_title_style']; ?> style="margin-bottom:5px;  margin-top:15px;" class="wcuf_upload_field_title <?php if($required_on_checkout /* && $current_page == 'checkout' */) echo 'wcuf_required_label'; ?>"><?php  echo $file_fields['title'] ?></<?php echo $all_options['upload_field_title_style']; ?>>
				<!-- Upload field rendering -->
				<?php if(!$hide_upload_after_upload || ($hide_upload_after_upload && !$upload_has_been_performed)):?>
					<p><?php echo do_shortcode($file_fields['description']); ?></p>
				<?php endif; ?>
				<?php if($display_text_field): ?>
					<?php if($text_field_label != ""):?>
						<h5><?php echo $text_field_label; ?></h5>
					<?php endif; ?>
					<?php if ($text_field_description != ""): ?>
						<div class="wpuef_text_field_description"><?php echo $text_field_description; ?></div>
					<?php endif; ?>
					<textarea data-id="<?php echo $file_fields['id']; ?>" class="wcuf_feedback_textarea" id="wcuf_feedback_textarea_<?php echo $file_fields['id']; ?>" name="wcuf[<?php echo $file_fields['id']; ?>][user_feedback]" <?php if($is_text_field_required) echo 'required="required"'; if(!$feedback_can_be_peformed) echo "disabled";?> <?php if($text_field_max_input_chars != 0) echo 'maxlength="'.$text_field_max_input_chars.'"';?>><?php if(isset($item_in_cart_temp) ) echo $item_in_cart_temp['user_feedback'];?></textarea>
				<?php endif;?>
				<?php //if(!isset($file_order_metadata[$file_fields['id']])):
					   $upload_field_unique_name = $file_fields['title'];
					  // if( !$upload_has_been_performed  /* $item_in_cart_temp == null */): 
					   if( ($enable_multiple_uploads_per_field && ($unlimited_uploads || $multiple_uploads_max_files > 0)) || !$upload_has_been_performed ): ?>
							<input type="hidden" name="wcuf[<?php echo $file_fields['id']; ?>][title]" value="<?php echo $upload_field_unique_name; ?>"></input>
							<input type="hidden" name="wcuf[<?php echo $file_fields['id']; ?>][id]" value="<?php echo $file_fields['id']; ?>"></input>
							<input type="hidden" id="wcuf-filename-<?php echo $file_fields['id']; ?>" name="wcuf[<?php echo $file_fields['id']; ?>][file_name]" value=""></input>
							<div class="wcuf_upload_button_container">
								<?php if($display_disclaimer_checkbox): ?>
									<label class="wcuf_disclaimer_label"><input type="checkbox" class="wcuf_disclaimer_checkbox" id="wcuf_disclaimer_checkbox_<?php echo $file_fields['id']."-".$product_id; ?>"></input><?php echo $disclaimer_text;?></label>
								<?php endif; ?>
								<button id="wcuf_upload_field_button_<?php echo $file_fields['id']; ?>"  style="margin-right:<?php echo $style_options['css_distance_between_upload_buttons']; ?>px;" class="button wcuf_upload_field_button <?php echo $additional_button_class;?>" data-id="<?php echo $file_fields['id']; ?>"><?php if(!$enable_multiple_uploads_per_field) echo $button_texts['browse_button']; else echo $button_texts['add_files_button']; ?></button>
								<button id="wcuf_upload_multiple_files_button_<?php echo $file_fields['id']; ?>" class="button wcuf_upload_multiple_files_button <?php echo $additional_button_class;?>" data-id="<?php echo $file_fields['id']; ?>"><?php echo $button_texts['upload_selected_files_button']; ?></button>
								<input type="file"  data-title="<?php echo $file_fields['title']; ?>" id="wcuf_upload_field_<?php echo $file_fields['id']; ?>" 
													data-disclaimer="<?php echo $display_disclaimer_checkbox;?>"  
													data-id="<?php echo $file_fields['id']; ?>" 
													data-required="<?php if($required_on_checkout) echo 'true'; else echo 'false' ?>" 
													data-min-files="<?php echo $multiple_uploads_min_files ?>" 
													data-max-files="<?php echo $multiple_uploads_max_files; ?>" 
													data-dimensions-logical-operator="<?php echo $dimensions_logical_operator; ?>" 
													data-max-width="<?php echo $max_width; ?>" 
													data-max-height="<?php echo $max_height; ?>" 
													data-min-height="<?php echo $min_height_limit; ?>" 
													data-min-width="<?php echo $min_width_limit; ?>" 
													data-min-dpi="<?php echo $min_dpi_limit; ?>" 
													data-max-dpi="<?php echo $max_dpi_limit; ?>" 
													data-images-preview-disabled= "<?php echo $preview_images_before_upload_disabled ? 'true' : 'false';?>"
													data-detect-pdf = "<?php echo $file_fields['extra_cost_detect_pdf'] ? 'true' : 'false';?>" 
													data-cropped-width="<?php echo $cropped_image_width; ?>" 
													data-cropped-height="<?php echo $cropped_image_height; ?>" 
													data-multiple-files-max-sum-size="<?php echo $multiple_files_max_size_sum; ?>"
													data-multiple-files-min-sum-size="<?php echo $multiple_files_min_size_sum; ?>"
													data-is-multiple-files="<?php if($enable_multiple_uploads_per_field) echo 'true'; else echo 'false'; ?>" 
													data-enable-crop-editor="<?php echo $enable_crop_editor; ?>" 
													class="wcuf_file_input <?php if($enable_multiple_uploads_per_field) echo 'wcuf_file_input_multiple'; ?>" <?php if($enable_multiple_uploads_per_field)  echo 'multiple="multiple"'; ?> 
													name="wcufuploadedfile_<?php echo $file_fields['id']?>"  <?php if($file_fields['types'] != '') echo 'accept="'.$file_fields['types'].'"';?> 
													data-min-size="<?php echo $min_size; ?>"
													data-size="<?php echo $file_fields['size']*1048576; ?>" value="<?php echo $file_fields['size']*1048576; ?>"   
													<?php //if($required_on_checkout ) echo 'required="required"'; ?>></input>
							</div>
														
							<strong class="wcuf_max_size_notice" id="wcuf_max_size_notice_<?php echo $file_fields['id'];?>">
											<?php if($min_size !=0) echo sprintf(__('Min size: %s MB', 'woocommerce-files-upload'), $min_size/1048576)."<br/>"; 
												  if($file_fields['size'] !=0) echo sprintf(__('Max size: %s MB', 'woocommerce-files-upload'),$file_fields['size'])."<br/>"; 	
												  if($enable_multiple_uploads_per_field && $multiple_uploads_min_files) __('Min files: ', 'woocommerce-files-upload').$multiple_uploads_min_files."<br/>"; 
												  if($enable_multiple_uploads_per_field && $multiple_uploads_max_files && !$unlimited_uploads) __('Max files: ', 'woocommerce-files-upload').$multiple_uploads_max_files."<br/>";
											      if($min_width_limit) echo __('Min width: ', 'woocommerce-files-upload').$min_width_limit."px"."<br/>"; 
												  if($max_width) echo __('Max width: ', 'woocommerce-files-upload').$max_width."px"."<br/>"; 
												  if($min_height_limit) echo __('Min height: ', 'woocommerce-files-upload').$min_height_limit."px"."<br/>"; 
												  if($max_height) echo __('Max height: ', 'woocommerce-files-upload').$max_height."px"."<br/>"; 
												  if($min_dpi_limit) echo __('Min DPI: ', 'woocommerce-files-upload').$min_dpi_limit."px"."<br/>";   
												  if($max_dpi_limit) echo __('Max DPI: ', 'woocommerce-files-upload').$max_dpi_limit."px"; 
												  ?>
							</strong>
							
							<?php if(!$enable_multiple_uploads_per_field && $enable_crop_editor): ?>
								<div class="wcuf_crop_container wcuf_not_to_be_showed" id="wcuf_crop_container_<?php echo $file_fields['id']; ?>">
									<div class="wcuf_crop_image_box" id="wcuf_crop_image_box_<?php echo $file_fields['id']; ?>">
										<div class="wcuf_crop_thumb_box" id="wcuf_crop_thumb_box_<?php echo $file_fields['id']; ?>"></div>
										<div class="wcuf_crop_thumb_spinner" style="display: none" id="wcuf_crop_thumb_spinner_<?php echo $file_fields['id']; ?>"><?php _e('Loading...','woocommerce-files-upload'); ?></div>
									</div>
									<div class="wcuf_crop_container_actions" id="wcuf_crop_container_actions_<?php echo $file_fields['id']; ?>">
										<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_zoomin_button" id="btnZoomIn_<?php echo $file_fields['id']; ?>" ><?php echo $button_texts['zoom_in_crop_button']; ?></button>
										<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_zoomout_button" id="btnZoomOut_<?php echo $file_fields['id']; ?>"  ><?php echo $button_texts['zoom_out_crop_button']; ?></button>										
										<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_rotate_left" id="btnRotateLeft_<?php echo $file_fields['id']; ?>"  data-id="<?php echo $file_fields['id']; ?>"><?php echo $button_texts['rotate_left_button']; ?></button>
										<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_rotate_right" id="btnRotateRight_<?php echo $file_fields['id']; ?>"  data-id="<?php echo $file_fields['id']; ?>"><?php echo $button_texts['rotate_right_button']; ?></button> 
										<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_crop_upload_button" id="btnCrop_<?php echo $file_fields['id']; ?>"  ><?php echo $button_texts['crop_and_upload_button']; ?></button>
									</div>
									<div id="wcuf_crop_upload_image_for_rotating_status_box_<?php echo $file_fields['id']; ?>" class="wcuf_crop_upload_image_for_rotating_status_box">
											<div class="wcuf_bar" id="wcuf_crop_rotating_upload_bar_<?php echo $file_fields['id']; ?>"></div >
											<div id="wcuf_crop_rotating_upload_percent_<?php echo $file_fields['id']; ?>">0%</div>
											<div class="wcuf_crop_rotating_upload_status_message" id="wcuf_crop_rotating_upload_status_message_<?php echo $file_fields['id']; ?>"><?php _e('Rotating the image, please wait...','woocommerce-files-upload'); ?></div>
									</div>
								</div>
							<?php endif; ?>
										
							<div class="wcuf_upload_status_box" id="wcuf_upload_status_box_<?php echo $file_fields['id']; ?>">
								<div class="wcuf_multiple_file_progress_container" id="wcuf_multiple_file_progress_container_<?php echo $file_fields['id']; ?>">
									<span class="wcuf_total_files_progress_bar_title"><?php _e('Total: ', 'woocommerce-files-upload'); ?></span>
									<div class="wcuf_bar" id="wcuf_multiple_file_bar_<?php echo $file_fields['id']; ?>"></div>
									<div id="wcuf_multiple_file_upload_percent_<?php echo $file_fields['id']; ?>"></div>
									<span class="wcuf_current_file_progress_bar_title" ><?php _e('Current: ', 'woocommerce-files-upload'); ?></span>
								</div>
								<div class="wcuf_bar" id="wcuf_bar_<?php echo $file_fields['id']; ?>"></div >
								<div id="wcuf_percent_<?php echo $file_fields['id']; ?>">0%</div>
								<div id="wcuf_status_<?php echo $file_fields['id']; ?>"></div>
							</div>
							<div id="wcuf_file_name_<?php echo $file_fields['id']; ?>" class="wcuf_file_name"></div>
							<div id="wcuf_delete_button_box_<?php echo $file_fields['id']; ?>">
						</div>
			      <?php 
					//else:
					endif;
					if($upload_has_been_performed): //not uplaoded data -> &upload_has_been_performed       $item_in_cart_temp  
				  ?>
						<div class="wcuf_already_uplaoded_data_container"><?php 
						
							if(!isset($file_fields['message_already_uploaded'])){
								//_e('File already uploaded', 'woocommerce-files-upload'); 
							}
							else
								{
									$already_uploaded_message = $file_fields['message_already_uploaded'];
									//[file_name]
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name]', $already_uploaded_message, $file_fields, $item_in_cart_temp, null);
									//[file_name_no_cost]
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, null, false, 0 , false);
									//[file_name_with_image_preview] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_image_preview]',$already_uploaded_message, $file_fields, $item_in_cart_temp,null,true); //old
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_media_preview]',$already_uploaded_message, $file_fields, $item_in_cart_temp,null,true);
									//[file_name_with_image_preview_no_cost] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_image_preview_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, null, true, 0, false); //old
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_media_preview_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, null, true, 0, false);
									//[image_preview_list] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[image_preview_list]',$already_uploaded_message, $file_fields, $item_in_cart_temp, null, true, 0, false, false);
									//[uploaded_files_num]
									$already_uploaded_message = $wcuf_shortcodes->uploaded_files_num($already_uploaded_message, $file_fields, $item_in_cart_temp);
									//[additional_costs]
									$already_uploaded_message = $wcuf_shortcodes->additional_costs($already_uploaded_message, $file_fields_groups, $item_in_cart_temp,$file_fields,null);
									
									echo do_shortcode( $already_uploaded_message);
								}
							?></div>
					 <?php if(true/* $file_fields['user_can_delete'] */):?>
							<button class="button delete_button" data-id="wcufuploadedfile_<?php echo $file_fields['id'];?>"><?php  echo $button_texts['delete_file_button']; ?></button>
					<?php endif; ?>	
			<?php endif; ?>
			<!-- <div class="wcuf_spacer2"></div> -->
			</div> <!-- wcuf_upload_fields_row_element -->
			
			<?php  else:  //Disable stacking: Upload per product & variant ?>
				
				<?php foreach($products_for_which_stacking_is_disabled as $product): ?>
				<div class="wcuf_upload_fields_row_element">
				<?php	
						//wcuf_var_dump($product["data"]);
						$product_id = $product["product_id"];
						//$product_data = $product["data"];
						$product_name = $wcuf_product_model->get_my_name($product_id );
						$product_name_backend = $product_name;
						$product_name = $current_page != 'product' ? $product_name  : "";
						$product_var_id = $product["variation_id"] == "" ? false:$product["variation_id"];
						$product_variation = null;
						$product['force_disable_stacking_for_variation'] = isset($product['force_disable_stacking_for_variation']) && $product['force_disable_stacking_for_variation'] ? $product['force_disable_stacking_for_variation'] : false;
						$show_upload_field_for_each_variation = false;
						if($product_var_id != false)
						{
							//$variation = wc_get_product($product_var_id);
							$product_name = $product_name_backend =  $wcuf_product_model->get_variation_parent_name($product_var_id); //$variation->parent->post->post_title;
						}
						
						if($product_var_id && ($disable_stacking_for_variation || $product['force_disable_stacking_for_variation']))	
						{
							$show_upload_field_for_each_variation = true;
							$_product = wc_get_product( $product_var_id );
							$_item = apply_filters( 'woocommerce_get_product_from_item', $_product, $product, null );
							
							$product_in_order = apply_filters( 'woocommerce_order_item_product', $_item , $product );
							$variation = new WC_Product_Variation($product_var_id);
							$item_meta    = version_compare( WC_VERSION, '3.1', '<' ) ? new WC_Order_Item_Meta( $product, $product_in_order) : new WC_Order_Item_Product($product, $product_in_order);
							$product_id .= "-".$product_var_id;

							if($display_product_fullname)
							{
								$product_name = $current_page != 'product' ? $variation->get_title()." - ": "";	
							}
							$product_name_backend = $variation->get_title()." - ";
							$attributes_counter = 0;
							//wcuf_var_dump($variation->get_variation_attributes( ));
							foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
							{
								
								if($attributes_counter > 0  && $display_product_fullname)
									$product_name .= ", ";
								$product_name_backend .=  $attributes_counter > 0 ? ", " : "";
								
								$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
								//if(strrpos($meta_key, "pa_", -strlen($meta_key)) !== false) //starts_with 
								if(isset($product['variation']) && !empty($product['variation']))
									foreach($product['variation'] as $attribute_name => $attribute_value)
										if($attribute_name == "attribute_".$meta_key)
												$value = $attribute_value;
								
								if($display_product_fullname)
									$product_name .= " ".wc_attribute_label( $meta_key, $product_in_order ).": ".$value;
									//$product_name .= wc_get_order_item_meta( $product_var_id, $attribute_name, array( 'fields' => 'names' )).": ".$value;
								$product_name_backend .= " ".wc_attribute_label( $meta_key, $product_in_order ).": ".$value;
								
								$attributes_counter++;
							} 
							$wc_price_calculator_is_active = $wcuf_product_model->wc_price_calculator_is_active_on_product( $variation );
						}
						else
							$wc_price_calculator_is_active = $wcuf_product_model->wc_price_calculator_is_active_on_product( new WC_Product($product["product_id"]) );
						
						 $upload_field_unique_name = $file_fields['title'].' ('.$product_name_backend.')';
						 $file_field_title = $file_fields['title'];
						 
						//Wc price calclator managment (if active)
						$unique_product_name_hash = $addtional_id_on_title = "";
						if($wc_price_calculator_is_active && ($disable_stacking_for_variation || $product['force_disable_stacking_for_variation']))
						{
							$measures_string = $wcuf_product_model->wc_price_calulator_get_cart_item_name($product);
							//wcuf_var_dump($measures_string);
							$product_name_backend .= $measures_string;
							$product_name .= $measures_string ;							
							$upload_field_unique_name = $file_fields['title'].' ('.$product_name_backend.')';
							$unique_product_name_hash = $wcuf_product_model->wc_price_calulator_get_unique_product_name_hash($upload_field_unique_name);
							$product_id .= "-".$unique_product_name_hash;
						}
						//individual product managment
						else if($wcuf_product_model->sold_as_individual_product($product["product_id"], $product["variation_id"]) && 
								$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] != 0) 
						{	
							
							$product_id .= !$disable_stacking_for_variation || !$product_var_id || $product_var_id == "" ? "-0"."-idsai".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] : "-idsai".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name];
							//$product_id .= !$wcuf_product_model->is_variable($product["product_id"]) || !$show_upload_field_for_each_variation ? "-0"."-idsai".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] : "-idsai".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name];
							$addtional_id_on_title = /* !$wcuf_product_model->is_variable($product["product_id"]) || $show_upload_field_for_each_variation ? */ " ".$button_texts['cart_individual_item_identifier'].$product[WCUF_Cart::$sold_as_individual_item_cart_key_name]/*  : "" */;
							$product_name .= $addtional_id_on_title;
							$upload_field_unique_name = $file_fields['title'].' ('.$product_name_backend.$addtional_id_on_title.') ';
						}
							
						//Multiple file upload managment
						$upload_field_id = "wcufuploadedfile_".$file_fields['id']."-".$product_id;
						$num_of_uploaded_files = $wcuf_upload_field_model->get_num_uploaded_files_in_session($upload_field_id, $all_options['max_uploaded_files_number_considered_as_sum_of_quantities']);
						$item_in_cart_temp =  $wcuf_session_model->get_item_data($upload_field_id);
						$upload_has_been_performed = isset($item_in_cart_temp) ? true : false;
						$multiple_uploads_max_files = $upload_has_been_performed ? 0 : 1;
						$multiple_uploads_min_files = 1;
						$unlimited_uploads = $file_fields['multiple_uploads_max_files'] == 0 ? true : false;
						$feedback_can_be_peformed = $upload_has_been_performed ? false : true;
						
						//Min/max uploadable files
						if($enable_multiple_uploads_per_field)
						{
							$multiple_uploads_max_files = $multiple_uploads_max_files_depends_on_quantity ? $product['quantity'] : $file_fields['multiple_uploads_max_files'];
							$multiple_uploads_min_files = $multiple_uploads_min_files_depends_on_quantity ? $product['quantity'] : $multiple_uploads_minimum_required_files;
						
							//Incremental upload
							if($required_on_checkout)
								$multiple_uploads_min_files = $multiple_uploads_min_files == 0 ? 1 : $multiple_uploads_min_files;
							$multiple_uploads_max_files  =  $multiple_uploads_max_files != 0 && $multiple_uploads_max_files - $num_of_uploaded_files >= 0 ? $multiple_uploads_max_files - $num_of_uploaded_files : 0;
							$multiple_uploads_min_files = $num_of_uploaded_files > $multiple_uploads_min_files ? 0 : $multiple_uploads_min_files - $num_of_uploaded_files;
						
							$feedback_can_be_peformed = $unlimited_uploads || $multiple_uploads_max_files > 0 ? true : false;
						}	
						
						//Sanatize 
						$file_fields['title'] = str_replace($bad_chars, "",$file_fields['title']);
						$product_name = str_replace($bad_chars, "",$product_name);
						$upload_field_unique_name = str_replace($bad_chars, "",$upload_field_unique_name);
						
				  ?>
					  <!-- Upload field title -->
					  <<?php echo $all_options['upload_field_title_style']; ?> style="margin-bottom:5px;  margin-top:15px;" class="wcuf_upload_field_title <?php if($required_on_checkout /* && $current_page == 'checkout' */) echo 'wcuf_required_label'; ?>"><?php  echo $file_field_title; ?></<?php echo $all_options['upload_field_title_style']; ?>>
					  <?php if(!empty($product_name) && $current_page != 'product') echo '<'.$all_options['product_title_style'].' class="wcuf_product_title_under_upload_field_name">'.$product_name.'</'.$all_options['product_title_style'].'>'; ?>
					  <!-- Upload field rendering -->
					  <?php if(!$hide_upload_after_upload || ($hide_upload_after_upload && !$upload_has_been_performed)):?>
							<p><?php echo do_shortcode($file_fields['description']); ?></p>
						<?php endif; ?>
						<?php if($display_text_field): ?>
							<?php if($text_field_label != ""):?>
								<h5><?php echo $text_field_label; ?></h5>
							<?php endif; ?>
							<?php if ($text_field_description != ""): ?>
								<div class="wpuef_text_field_description"><?php echo $text_field_description; ?></div>
							<?php endif; ?>
							<textarea class="wcuf_feedback_textarea" data-id="<?php echo $file_fields['id']."-".$product_id; ?>" id="wcuf_feedback_textarea_<?php echo $file_fields['id']."-".$product_id; ?>" name="wcuf[<?php echo $file_fields['id']."-".$product_id; ?>][user_feedback]" <?php if($is_text_field_required) echo 'required="required"'; if( !$feedback_can_be_peformed) echo "disabled";?> <?php if($text_field_max_input_chars != 0) echo 'maxlength="'.$text_field_max_input_chars.'"';?>><?php if(isset($item_in_cart_temp) ) echo $item_in_cart_temp['user_feedback'];?></textarea>
						<?php endif;?>
						<?php //if(!isset($file_order_metadata[$file_fields['id']."-".$product_id])): 
							 
							  //if(   !$upload_has_been_performed /*$item_in_cart_temp == null*/ ): 
							  if(   ($enable_multiple_uploads_per_field && ($unlimited_uploads || $multiple_uploads_max_files > 0)) || !$upload_has_been_performed): 
								
								 ?>
									<input type="hidden" name="wcuf[<?php echo $file_fields['id']."-".$product_id; ?>][title]" value="<?php echo $upload_field_unique_name; ?>"></input>
									<input type="hidden" name="wcuf[<?php echo $file_fields['id']."-".$product_id; ?>][id]" value="<?php echo $file_fields['id']."-".$product_id; ?>"></input>
									<input type="hidden" id="wcuf-filename-<?php echo $file_fields['id']."-".$product_id; ?>" name="wcuf[<?php echo $file_fields['id']."-".$product_id; ?>][file_name]" value=""></input>
									<div class="wcuf_upload_button_container">
										<?php if($display_disclaimer_checkbox): ?>
											<label class="wcuf_disclaimer_label"><input type="checkbox" class="wcuf_disclaimer_checkbox" id="wcuf_disclaimer_checkbox_<?php echo $file_fields['id']."-".$product_id; ?>"></input><?php echo $disclaimer_text;?></label>
										<?php endif; ?>
										<button id="wcuf_upload_field_button_<?php echo $file_fields['id']."-".$product_id; ?>"  style="margin-right:<?php echo $style_options['css_distance_between_upload_buttons']; ?>px;" class="button wcuf_upload_field_button <?php echo $additional_button_class;?>" data-id="<?php echo $file_fields['id']."-".$product_id; ?>"><?php if(!$enable_multiple_uploads_per_field) echo $button_texts['browse_button']; else echo $button_texts['add_files_button']; ?></button>
										<button id="wcuf_upload_multiple_files_button_<?php echo $file_fields['id']."-".$product_id; ?>" class="button wcuf_upload_multiple_files_button <?php echo $additional_button_class;?>" data-id="<?php echo $file_fields['id']."-".$product_id; ?>"><?php echo $button_texts['upload_selected_files_button']; ?></button>
										<input type="file"  data-title="<?php echo $upload_field_unique_name; ?>" 
															id="wcuf_upload_field_<?php echo $file_fields['id']."-".$product_id; ?>" 
															data-disclaimer="<?php echo $display_disclaimer_checkbox;?>" 
															data-id="<?php echo $file_fields['id']."-".$product_id; ?>" 
															data-required="<?php if($required_on_checkout) echo 'true'; else echo 'false' ?>" 
															data-min-files="<?php echo $multiple_uploads_min_files ?>" 
															data-max-files="<?php echo $multiple_uploads_max_files; ?>" 
															data-dimensions-logical-operator="<?php echo $dimensions_logical_operator; ?>" 
															data-max-width="<?php echo $max_width; ?>" 
															data-max-height="<?php echo $max_height; ?>" 															
															data-min-height="<?php echo $min_height_limit; ?>" 
															data-min-width="<?php echo $min_width_limit; ?>" 
															data-min-dpi="<?php echo $min_dpi_limit; ?>" 
															data-max-dpi="<?php echo $max_dpi_limit; ?>"
															data-images-preview-disabled= "<?php echo $preview_images_before_upload_disabled ? 'true' : 'false';?>" 
															data-detect-pdf = "<?php echo $file_fields['extra_cost_detect_pdf'] ? 'true' : 'false';?>" 
															data-enable-crop-editor="<?php echo $enable_crop_editor; ?>" 
															data-cropped-width="<?php echo $cropped_image_width; ?>" 
															data-cropped-height="<?php echo $cropped_image_height; ?>" 
															data-multiple-files-max-sum-size="<?php echo $multiple_files_max_size_sum; ?>"
															data-multiple-files-min-sum-size="<?php echo $multiple_files_min_size_sum; ?>"
															data-is-multiple-files="<?php if($enable_multiple_uploads_per_field) echo 'true'; else echo 'false'; ?>" 
															class="cropit-image-input wcuf_file_input <?php if($enable_multiple_uploads_per_field) echo 'wcuf_file_input_multiple'; ?>" <?php if($enable_multiple_uploads_per_field)  echo 'multiple="multiple"'; ?> 
															name="wcufuploadedfile_<?php echo $file_fields['id']."-".$product_id; ?>" <?php if($file_fields['types'] != '') echo 'accept="'.$file_fields['types'].'"';?> 
															data-min-size="<?php echo $min_size; ?>"
															data-size="<?php echo $file_fields['size']*1048576; ?>" value="<?php echo $file_fields['size']*1048576; ?>" 
															<?php //if($required_on_checkout ) echo 'required="required"'; ?>></input>
									<strong class="wcuf_max_size_notice" id="wcuf_max_size_notice_<?php echo $file_fields['id']."-".$product_id; ?>" >
													<?php if($min_size !=0) echo sprintf(__('Min size: %s MB', 'woocommerce-files-upload'), $min_size/1048576)."<br/>"; 
														  if($file_fields['size'] !=0) echo sprintf(__('Max size: %s MB', 'woocommerce-files-upload'),$file_fields['size'])."<br/>"; 
														  if($enable_multiple_uploads_per_field && $multiple_uploads_min_files ) echo __('Min files: ', 'woocommerce-files-upload').$multiple_uploads_min_files."<br/>"; 
														  if($enable_multiple_uploads_per_field && $multiple_uploads_max_files && !$unlimited_uploads) echo __('Max files: ', 'woocommerce-files-upload').$multiple_uploads_max_files."<br/>"; 
														  if($min_width_limit) echo __('Min width: ', 'woocommerce-files-upload').$min_width_limit."px"."<br/>"; 
														  if($max_width) echo __('Max width: ', 'woocommerce-files-upload').$max_width."px"."<br/>"; 
														  if($min_height_limit) echo __('Min height: ', 'woocommerce-files-upload').$min_height_limit."px"."<br/>"; 
														  if($max_height) echo __('Max height: ', 'woocommerce-files-upload').$max_height."px"."<br/>"; 
														  if($min_dpi_limit) echo __('Min DPI: ', 'woocommerce-files-upload').$min_dpi_limit."px"."<br/>";   
														  if($max_dpi_limit) echo __('Max DPI: ', 'woocommerce-files-upload').$max_dpi_limit."px"; 
												  ?>
									</strong>
									
									<?php if(!$enable_multiple_uploads_per_field && $enable_crop_editor): ?>
											<div class="wcuf_crop_container wcuf_not_to_be_showed" id="wcuf_crop_container_<?php echo $file_fields['id']."-".$product_id; ?>">
												<div class="wcuf_crop_image_box" id="wcuf_crop_image_box_<?php echo $file_fields['id']."-".$product_id; ?>">
													<div class="wcuf_crop_thumb_box" id="wcuf_crop_thumb_box_<?php echo $file_fields['id']."-".$product_id; ?>"></div>
													<div class="wcuf_crop_thumb_spinner" style="display: none" id="wcuf_crop_thumb_spinner_<?php echo $file_fields['id']."-".$product_id; ?>"><?php _e('Loading...','woocommerce-files-upload'); ?></div>
												</div>
												<div class="wcuf_crop_container_actions" id="wcuf_crop_container_actions_<?php echo $file_fields['id']."-".$product_id; ?>">
													<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_zoomin_button" id="btnZoomIn_<?php echo $file_fields['id']."-".$product_id; ?>" ><?php echo $button_texts['zoom_in_crop_button']; ?></button>
													<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_zoomout_button" id="btnZoomOut_<?php echo $file_fields['id']."-".$product_id; ?>"  ><?php echo $button_texts['zoom_out_crop_button']; ?></button>
													<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_rotate_left" id="btnRotateLeft_<?php echo $file_fields['id']."-".$product_id; ?>"  data-id="<?php echo $file_fields['id']."-".$product_id; ?>"><?php echo $button_texts['rotate_left_button']; ?></button>
													<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_rotate_right" id="btnRotateRight_<?php echo $file_fields['id']."-".$product_id; ?>"  data-id="<?php echo $file_fields['id']."-".$product_id; ?>"><?php echo $button_texts['rotate_right_button']; ?></button>
													<button class="button wcuf_crop_button wcuf_remove_button_extra_content wcuf_crop_upload_button" id="btnCrop_<?php echo $file_fields['id']."-".$product_id; ?>"  ><?php echo $button_texts['crop_and_upload_button']; ?></button>
													
												</div>
											</div>
											<div id="wcuf_crop_upload_image_for_rotating_status_box_<?php echo $file_fields['id']."-".$product_id; ?>" class="wcuf_crop_upload_image_for_rotating_status_box">
													<div class="wcuf_bar" id="wcuf_crop_rotating_upload_bar_<?php echo $file_fields['id']."-".$product_id; ?>"></div >
													<div id="wcuf_crop_rotating_upload_percent_<?php echo $file_fields['id']."-".$product_id; ?>">0%</div>
													<div class="wcuf_crop_rotating_upload_status_message" id="wcuf_crop_rotating_upload_status_message_<?php echo $file_fields['id']; ?>"><?php _e('Rotating the image, please wait...','woocommerce-files-upload'); ?></div>
											</div>
										<?php endif; ?>
										
									</div>
									
									<div class="wcuf_upload_status_box" id="wcuf_upload_status_box_<?php echo $file_fields['id']."-".$product_id; ?>">
										<div class="wcuf_multiple_file_progress_container" id="wcuf_multiple_file_progress_container_<?php echo $file_fields['id']."-".$product_id; ?>">
											<span class="wcuf_total_files_progress_bar_title"><?php _e('Total: ', 'woocommerce-files-upload'); ?></span>
											<div class="wcuf_bar" id="wcuf_multiple_file_bar_<?php echo $file_fields['id']."-".$product_id; ?>"></div>
											<div id="wcuf_multiple_file_upload_percent_<?php echo $file_fields['id']."-".$product_id; ?>"></div>
											<span class="wcuf_current_file_progress_bar_title" ><?php _e('Current: ', 'woocommerce-files-upload'); ?></span>
										</div>
										<div class="wcuf_bar" id="wcuf_bar_<?php echo $file_fields['id']."-".$product_id; ?>"></div >
										<div id="wcuf_percent_<?php echo $file_fields['id']."-".$product_id; ?>">0%</div>
										<div id="wcuf_status_<?php echo $file_fields['id']."-".$product_id; ?>"></div>
									</div>
									<div id="wcuf_file_name_<?php echo $file_fields['id']."-".$product_id; ?>" class="wcuf_file_name"></div>
									<div id="wcuf_delete_button_box_<?php echo $file_fields['id']."-".$product_id; ?>">
									</div>
						<?php
							//else:
							endif;
							if($upload_has_been_performed): //not uplaoded data -> $upload_has_been_performed      $item_in_cart_temp ?>
								<div class="wcuf_already_uplaoded_data_container"><?php 
								
								if(!isset($file_fields['message_already_uploaded'])){
									//_e('File already uploaded.', 'woocommerce-files-upload'); 
								}
								else
								{
									$already_uploaded_message = $file_fields['message_already_uploaded'];
									//[file_name]
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name]', $already_uploaded_message, $file_fields, $item_in_cart_temp, $product);
									//[file_name_no_cost]
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product, false, 0 , false);
									//[file_name_with_image_preview] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_image_preview]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product,true);//old
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_media_preview]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product,true);
									//[file_name_with_image_preview_no_cost] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_media_preview_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product, true, 0, false);
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_image_preview_no_cost]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product, true, 0, false);//ol
									//[image_preview_list] 
									$already_uploaded_message = $wcuf_shortcodes->get_file_names_with_additional_info('[image_preview_list]',$already_uploaded_message, $file_fields, $item_in_cart_temp, $product, true, 0, false, false);
									//[uploaded_files_num]
									$already_uploaded_message = $wcuf_shortcodes->uploaded_files_num($already_uploaded_message, $file_fields, $item_in_cart_temp);
									//[additional_costs]
									$already_uploaded_message = $wcuf_shortcodes->additional_costs($already_uploaded_message, $file_fields_groups, $item_in_cart_temp,$file_fields,$product);
									
									echo do_shortcode( $already_uploaded_message);
								}
								?></div>
							 <?php if(true/* $file_fields['user_can_delete'] */):?>
									<button class="button delete_button" data-id="wcufuploadedfile_<?php echo $file_fields['id']."-".$product_id;?>"><?php  echo $button_texts['delete_file_button']; ?></button>
							<?php endif; ?>
					<?php endif; ?>
						<!-- <div class="wcuf_spacer2"></div> -->
						</div> <!-- <div class="wcuf_upload_fields_row_element"> -->
					<?php endforeach; //products_for_which_stacking_is_disabled ?>
			<?php endif;//disable stacking ?>
				</div> <!--wcuf_single_upload_field_container-->
			<?php endif;//can render
		}
	endforeach; //upload field 
?> 
</div>
<div id="wcuf_deleting_message">
	<h4><?php _e('Deleting file, please wait...', 'woocommerce-files-upload'); ?></h4>
</div>
<div class="wcuf_spacer4"></div>
<?php 
//Summary data
$summary_box_data = array();
$all_uploaded_data = $wcuf_session_model->get_item_data();
if($display_summary_box != 'no' && isset($all_uploaded_data))
{
	foreach($all_uploaded_data as $completed_upload)
	{
		if(!isset( $summary_box_data[$completed_upload['title']]))
			$summary_box_data[$completed_upload['title']] = array();
		
		$show_file_name = true;
		$show_preview_image = true;
		switch($summary_box_info_to_display)
		{
			case "file_name": $show_file_name = true; $show_preview_image = false; break;
			case "preview_image": $show_file_name = false; $show_preview_image = true; break;
			/* case: "file_name_and_preview_image": break; */
		}
		$summary_box_data[$completed_upload['title']] = $wcuf_shortcodes->get_file_names_with_additional_info('[file_name_with_image_preview]', '[file_name_with_image_preview]', $file_fields, $completed_upload, null, $show_preview_image, 0, false, $show_file_name, false);
	}
}

if(!empty($summary_box_data) && in_array($current_page, $display_summary_box)): ?>
	<div id="wcuf_summary_uploaded_files">
		<h2><?php _e('Uploads Summary', 'woocommerce-files-upload');?></h2>
		<?php foreach($summary_box_data as $title => $file_list): ?>
			<div class="wcuf_summary_file_list_block">
				<h4 class="wcuf_upload_field_title wcuf_summary_uploaded_files_title"><?php echo $title; ?></h4>
				<?php echo $file_list; ?>
			</div>
			<!--<div class="wcuf_summary_uploaded_files_list_spacer"></div>-->
		<?php endforeach; ?>
	</div>
<?php endif; 
//End //Summary data ?>


<script> 
var wcuf_max_uploaded_files_number_considered_as_sum_of_quantities = <?php echo $all_options['max_uploaded_files_number_considered_as_sum_of_quantities'] ? 'true':'false'; ?>;
var wcuf_wpml_language = "<?php if(isset($sitepress)) echo $sitepress->get_current_language(); else echo "none"; ?>";
var wcuf_enable_select_quantity_per_file = <?php echo $all_options['enable_quantity_selection'] ? 'true':'false'; ?> ;
var wcuf_show_required_upload_add_to_cart_warning_message = <?php echo $all_options['show_required_upload_add_to_cart_warning_message'] ? 'true':'false'; ?> ;
var wcuf_quantity_per_file_label = "<?php echo $button_texts['select_quantity_label']; ?>";
var wcuf_force_require_check_before_adding_item_to_cart = <?php if($all_options['force_require_check_befor_adding_item_to_car']=='yes') echo 'true'; else echo 'false'; ?>;
var wcuf_allow_user_to_leave_page_in_case_of_required_field = '<?php echo $all_options['allow_user_to_leave_page_in_case_of_required_field']; // see wcuf_leave_the_page_after_warning_popup_prompt() method comments. ?>';
var wcuf_progressbar_color = "<?php echo $all_options['bar_color'] ?>";
var wcuf_ajax_reloading_fields_text = "<?php echo str_replace($bad_chars, "", __('Processing data...', 'woocommerce-files-upload')); ?>";
var wcuf_current_prduct_id = "<?php if($current_page == 'product') echo $current_product_id; else echo 0; ?>";
var wcuf_is_order_detail_page = false;
var wcuf_order_id = "0";
var wcuf_ajax_action = "upload_file_during_checkout_or_product_page";
var wcuf_ajax_delete_action = "delete_file_during_checkout_or_product_page";
var wcuf_ajax_delete_single_file_action = "delete_single_file_during_checkout_or_product_page";
var wcuf_is_deleting = false;
var wcuf_current_page = "<?php echo $current_page; ?>";
var wcuf_minimum_required_files_message = "<?php echo str_replace($bad_chars, "", __('You have to upload at least: ', 'woocommerce-files-upload')); ?>";
var wcuf_checkout_required_message = "<?php echo str_replace($bad_chars, "", __('Please upload all the required files before proceding to checkout', 'woocommerce-files-upload')); ?>";
var wcuf_user_feedback_required_message = "<?php echo str_replace($bad_chars, "", __('Please fill all required text fields before uploading file(s).', 'woocommerce-files-upload')); ?>";
var wcuf_multiple_uploads_error_message = "<?php echo $button_texts['incomplete_files_upload_message']; ?>";
var wcuf_unload_confirm_message = "<?php echo str_replace($bad_chars, "", __('Please upload all the required files before leaving the page.', 'woocommerce-files-upload')); ?>";
var wcuf_disclaimer_must_be_accepted_message = "<?php echo str_replace($bad_chars, "", __('You must accept the disclaimer', 'woocommerce-files-upload')); ?>";
var wcuf_unload_check = <?php if(($current_page == 'product'|| $current_page == 'cart'|| $current_page == 'shortcode') && $exists_one_required_field) echo "true"; else echo "false"; ?>;
var wcuf_exist_a_field_before_add_to_cart = <?php if(($current_page == 'product') && $exists_a_field_to_show_before_adding_item_to_cart) echo "true"; else echo "false"; ?>;
var wcuf_item_has_been_added_to_cart = <?php if(($current_page == 'product') && isset($has_already_added_to_cart) && $has_already_added_to_cart) echo "true"; else echo "false"; ?>;
var wcuf_file_size_type_header_error = "<?php echo str_replace($bad_chars, "", __(' size is incorrect or its type is not allowed.  ', 'woocommerce-files-upload')); ?>";
var wcuf_file_size_error = "<?php echo str_replace($bad_chars, "", __(' Max allowed size: ', 'woocommerce-files-upload')); ?>";
var wcuf_file_min_size_error = "<?php echo str_replace($bad_chars, "", __(' Min file size: ', 'woocommerce-files-upload')); ?>";
var wcuf_image_size_error = "<?php echo str_replace($bad_chars, "", __('One (or more) file is not an image or it has wrong sizes/DPI. Sizes/DPI allowed:', 'woocommerce-files-upload')); ?>";
var wcuf_image_exact_size_error = "<?php echo str_replace($bad_chars, "", __(' file is not an image or size to big. Size must be: ', 'woocommerce-files-upload')); ?>";
var wcuf_image_height_text = "<?php echo str_replace($bad_chars, "", __('max height', 'woocommerce-files-upload')); ?>";
var wcuf_image_width_text = "<?php echo str_replace($bad_chars, "", __('max width', 'woocommerce-files-upload')); ?>";
var wcuf_image_min_height_text = "<?php echo str_replace($bad_chars, "", __('min height', 'woocommerce-files-upload')); ?>";
var wcuf_image_min_width_text = "<?php echo str_replace($bad_chars, "", __('min width', 'woocommerce-files-upload')); ?>";
var wcuf_image_min_dip_text = "<?php echo str_replace($bad_chars, "", __('min DPI', 'woocommerce-files-upload')); ?>";
var wcuf_image_max_dip_text = "<?php echo str_replace($bad_chars, "", __('max DPI', 'woocommerce-files-upload')); ?>";
var wcuf_type_allowed_error = "<?php  echo str_replace($bad_chars, "", __('Allowed file types: ', 'woocommerce-files-upload')); ?>";
var wcuf_image_file_error = "<?php  echo str_replace($bad_chars, "", __('Input file must be an image', 'woocommerce-files-upload')); ?>";
var wcuf_file_num_error = "<?php  echo str_replace($bad_chars, "", __('Maximum of file upload error. You can upload max : ', 'woocommerce-files-upload')); ?>";
var wcuf_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var wcuf_success_msg = '<?php  echo str_replace($bad_chars, "", __('Done! ', 'woocommerce-files-upload')); ?>';
var wcuf_loading_msg = '<?php  echo str_replace($bad_chars, "", __('Loading, please wait until uploading is complete... ', 'woocommerce-files-upload')); ?>';
var wcuf_delete_msg = '<?php  echo str_replace($bad_chars, "", __('Deleting, pelase wait... ', 'woocommerce-files-upload')); ?>';
var wcuf_failure_msg = '<?php  echo str_replace($bad_chars, "", __('An error has occurred.', 'woocommerce-files-upload')); ?>';
var wcuf_delete_file_msg = '<?php  echo $button_texts['delete_file_button']; ?>';
var wcuf_html5_error = "<?php echo str_replace($bad_chars, "", __('The HTML5 standards are not fully supported in this browser, please upgrade it or use a more moder browser like Google Chrome or FireFox.', 'woocommerce-files-upload')); ?>";
//var wcuf_file_sizes_error = "<?php echo str_replace($bad_chars, "",__("The sum of file sizes cannot be greater than {$post_max_size}MB!", "woocommerce-files-upload")); ?>";
var wcuf_file_sizes_error = "<?php echo str_replace($bad_chars, "",__("The sum of file sizes cannot be greater than %s MB!", "woocommerce-files-upload")); ?>";
var wcuf_file_sizes_min_error = "<?php echo str_replace($bad_chars, "",__("The sum of file sizes cannot be minor than %s MB!", "woocommerce-files-upload")); ?>";
var wcuf_max_file_sizes = <?php echo $post_max_size*1024*1024;?>;
var wcuf_max_chunk_size = <?php echo $max_chunk_size*1024*1024;?>;
var wcuf_exists_at_least_one_upload_field_bounded_to_variations = <?php if($exists_at_least_one_upload_field_bounded_to_variations) echo 'true'; else echo 'false'; ?>;
var wcuf_exists_at_least_one_upload_field_bounded_to_gateway = <?php if($exists_at_least_one_upload_field_bounded_to_gateway) echo 'true'; else echo 'false'; ?>;
var wcuf_required_upload_add_to_cart_warning_message = '<div class="wcuf_required_upload_add_to_cart_warning_message" style="display:none;"><?php echo $button_texts['required_upload_add_to_cart_warning_message']; ?></div>';
var wcuf_delete_single_file_warning_msg = "<?php echo str_replace($bad_chars, "",__("Are sure you want to delete the file?", "woocommerce-files-upload")); ?>";
var wcuf_multiple_file_list_tile = "<?php echo str_replace($bad_chars, "",__("To upload:", "woocommerce-files-upload")); ?>";
var wcuf_auto_upload_for_multiple_files_upload_field = <?php echo $all_options['auto_upload_for_multiple_files_upload_field'] ? "true" : "false"; ?>;
</script>