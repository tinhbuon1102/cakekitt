<?php 
class WCUF_Product
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcuf_get_products_list', array(&$this, 'ajax_get_products_partial_list'));
			add_action('wp_ajax_wcuf_get_product_categories_list', array(&$this, 'ajax_get_product_categories_partial_list'));
		}
	}	
	public function get_product_id($product)
	{
		return version_compare( WC_VERSION, '2.7', '<' ) ? $product->id : $product->get_id();
	}
	public function get_my_name($product_id)
	{
		$product = new WC_Product($product_id);
		/* if(version_compare( WC_VERSION, '2.7', '<' ))
		{
			$product_data = $product["data"];
			return  $product_data->post->post_title;
		} */
		//wcuf_var_dump($product);
		return  $product->get_title();
	}
	public function get_variation_parent_name($variation_id)
	{
		$product = new WC_Product_Variation($variation_id);
		//wc_get_product($product_var_id);
		/* if(version_compare( WC_VERSION, '2.7', '<' ))
		{
			return  $product->parent->post->post_title;;
		} */
		return  $product->get_title();
	}
	public function get_product_meta($product_id, $key, $unique = true)
	{
		return get_post_meta($product_id, $key, $unique);
	}
	public function sold_as_individual_product($product_id, $variation_id = 0)
	{
		//return $this->get_product_meta($product_id, 'wcuf_add_to_cart_as_individual_product');
		global $wcuf_option_model,$wcuf_wpml_helper;
		
		//wpml 
		$product_id = $wcuf_wpml_helper->get_main_language_id($product_id, 'product');
		$variation_id = $variation_id != 0 ? $wcuf_wpml_helper->get_main_language_id($variation_id, 'product_variation') : 0;
		
		$individual_products_options = $wcuf_option_model->get_individual_products_options();
		if($individual_products_options['sell_all_products_as_individual'])
			return true;
		$products_ids = $individual_products_options['individual_products'];
		$additional_ids = $this->get_post_ids_using_categories('product_cat', $individual_products_options['individual_product_categories'], $individual_products_options['individual_products_children_categories']/* , $individual_products_options['selected_strategy'] */);
		if(!empty($additional_ids))
			$products_ids = array_merge($products_ids, $additional_ids);	
									
		/* if($individual_products_options['individual_products_selection_strategy'] != 'all')
				$products_ids = $this->get_complementry_ids($products_ids, 'product'); */
		
		return in_array($product_id, $products_ids) || in_array($variation_id, $products_ids);
	}
	public function wc_price_calculator_is_active_on_product($product)
	{
		if(!class_exists('WC_Price_Calculator_Product'))
			return false;
		//return  WC_Price_Calculator_Product::calculator_enabled( $product );
		return  WC_Price_Calculator_Product::calculator_enabled( wc_get_product( $product ) );
	}
	public function wc_price_calulator_get_unique_product_name_hash($product_name)
	{
		return $product_name != "" ? md5($product_name) : "0";
	}
	public function wc_price_calulator_get_cart_item_name($cart_item)
	{
		global $wcuf_price_calculator_measurement_helper;
		if(!class_exists('WC_Price_Calculator_Cart'))
			return "";
		 //$calculator = new WC_Price_Calculator_Cart();
		 $measurements = /* $calculator-> */$wcuf_price_calculator_measurement_helper->display_product_data_in_cart( array(), $cart_item );
		 $result = " ";
		 foreach((array)$measurements as $measurement)
		 {
			if(!$measurement['hidden'])
			 {
				 if($result != " ")
					 $result.= " - ";
				 //DO NOT CHANGE NAME FORMAT IT IS USED AS UNIQUE ID
				$result .= "   ".$measurement['name'].": ".$measurement['display'];
			 }
		 }
		return  $result;
	}
	public function wc_price_calulator_get_order_item_name( $order_item  )
	{
		global $wcuf_price_calculator_measurement_helper;
		if(!class_exists('WC_Price_Calculator_Cart') || !isset($order_item["measurement_data"]))
			return "";
		 //$calculator = new WC_Price_Calculator_Cart();
		
		 $order_item['pricing_item_meta_data'] = unserialize($order_item["measurement_data"]);
		 $order_item['data'] =  wc_get_product( $order_item['product_id'] );
		 // wcuf_var_dump($order_item);
		 $measurements = /* $calculator-> */$wcuf_price_calculator_measurement_helper->display_product_data_in_cart( array(), $order_item);
		 // wcuf_var_dump($measurements);
		 $result = " ";
		 foreach((array)$measurements as $measurement)
		 {
			if(!$measurement['hidden'])
			 {
				 if($result != " ")
					 $result.= " - ";
				 $display = is_array($measurement['display']) ? $measurement['display']['value'] : $measurement['display'];
				 //DO NOT CHANGE NAME FORMAT IT IS USED AS UNIQUE ID
				$result .= "   ".$measurement['name'].": ". $display;
			 }
		 } 
		return  $result;
	}
	public function get_product_category_name($category_id, $default = false)
	{
		global $wcuf_wpml_helper;
		$category_id = $wcuf_wpml_helper->get_main_language_id($category_id, 'product_cat');
		$category = get_term( $category_id, 'product_cat' );
		return isset($category) ? $category->name : $default;
	}
	public function get_product_name($product_id, $default = false)
	{
		global $wcuf_wpml_helper;
		$product_id = $wcuf_wpml_helper->get_main_language_id($product_id, 'product');
		$readable_name  = $default;
		
		if($this->is_variation($product_id))
		{
			$readable_name = $this->get_variation_complete_name($product_id);
			$readable_name = isset($readable_name) && $readable_name != "" && $readable_name!= " " ? "#".$product_id." - ".$readable_name  : $default;
		}
		else
		{
			try{
			    $product = new WC_Product($product_id);
			    $readable_name = isset($product) ? $product->get_formatted_name() : $default;
		    }catch (Exception $e){}
		}
		return $readable_name; //isset($product) ? $product->get_formatted_name() : $default;
	}
	 public function ajax_get_products_partial_list()
	 {
		 $products = $this->get_product_list($_GET['product']);
		 echo json_encode( $products);
		 wp_die();
	 }
	  public function ajax_get_product_categories_partial_list()
	 {
		  $product_categories = $this->get_product_category_list($_GET['product_category']);
		 echo json_encode( $product_categories);
		 wp_die();
	 }
	 
	 public function get_product_list($search_string = null)
	 {
		global $wpdb, $wcuf_wpml_helper;
		 $query_string = "SELECT products.ID as id, products.post_parent as product_parent, products.post_title as product_name, product_meta.meta_value as product_sku
							 FROM {$wpdb->posts} AS products
							 LEFT JOIN {$wpdb->postmeta} AS product_meta ON product_meta.post_id = products.ID AND product_meta.meta_key = '_sku'
							 WHERE  (products.post_type = 'product' OR products.post_type = 'product_variation')
							";
		if($search_string)
				$query_string .=  " AND ( products.post_title LIKE '%{$search_string}%' OR product_meta.meta_value LIKE '%{$search_string}%' OR products.ID LIKE '%{$search_string}%' ) 
								   AND (products.post_type = 'product' OR products.post_type = 'product_variation') ";
		
		$query_string .=  " GROUP BY products.ID ";
		$result = $wpdb->get_results($query_string ) ;
		
		if(isset($result) && !empty($result))
			foreach($result as $index => $product)
				{
					if($product->product_parent != 0 )
					{
						$readable_name = $this->get_variation_complete_name($product->id);
						$result[$index]->product_name = $readable_name != false ? "<i>".__('Variation','woocommerce-files-upload')."</i> ".$readable_name : $result[$index]->product_name;
					}
				}
		
		
		//WPML
		if($wcuf_wpml_helper->wpml_is_active())
		{
			$product_ids = $variation_ids = array();
			foreach($result as $product)
			{
				if($product->product_parent == 0 )
					$product_ids[] = $product;
				else
					$variation_ids[] = $product;
			}
			//$result = $wcuf_wpml_helper->remove_translated_id($result, 'product', true);
			
			//Filter products
			if(!empty($product_ids))
				$product_ids = $wcuf_wpml_helper->remove_translated_id($product_ids, 'product', true);
			
			//Filter variations
			if(!empty($variation_ids))
				$variation_ids = $wcuf_wpml_helper->remove_translated_id($variation_ids, 'product', true);
			
			$result = array_merge($product_ids, $variation_ids);
		}
		
		return $result;
	 }
	 public function get_variation_complete_name($variation_id)
	 {
		$error = false;
		$variation = null;
		try
		{
			$variation = new WC_Product_Variation($variation_id);
		}
		catch(Exception $e){$error = true;}
		if($error) 
			try
			{
				$error = false;
				$variation = new WC_Product($variation_id);
				return $variation->get_title();
			}catch(Exception $e){$error = true;}
		
		if($error)
			return "";
		
		$product_name = $variation->get_title()." - ";	
		if($product_name == " - ")
			return false;
		$attributes_counter = 0;
		foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
		{
			
			if($attributes_counter > 0)
				$product_name .= ", ";
			$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
			
			$product_name .= " ".wc_attribute_label($meta_key).": ".$value;
			$attributes_counter++;
		}
		return $product_name;
	 }
	 public function get_variations($product_id)
	 {
		global $wpdb, $wcuf_wpml_helper;
		
		if($wcuf_wpml_helper->wpml_is_active())
			$product_id = $wcuf_wpml_helper->get_main_language_id($product_id);
		 if(!isset($product_id))
			 return null; 
		 
		 $query = "SELECT *
		           FROM {$wpdb->posts} AS products 
				   WHERE  products.post_parent = {$product_id} "; //_regular_price
		 $result =  $wpdb->get_results($query); 
		 return isset($result) ? $result : null;		 
	 }
	 public function is_variable($product_id)
	 {
		 if(!isset($product_id) || $product_id == 0)
			 return false;
		 
		 $variations = $this->get_variations($product_id ); //Check _product_attributes meta? or _min_variation_price?
		
		 return !isset( $variations ) || empty( $variations ) ? false : true;
	 }
	 public function is_variation($product_id)
	 {
		 global $wpdb, $wcuf_wpml_helper;
		
		 if($wcuf_wpml_helper->wpml_is_active())
			$product_id = $wcuf_wpml_helper->get_main_language_id($product_id, 'product_variation');
		
		$query = "SELECT products.post_parent as product_parent 
				  FROM {$wpdb->posts} AS products 
				  WHERE  products.ID = {$product_id} ";
				  
		 $result =  $wpdb->get_results($query); 
		 //wcuf_var_dump($result);
		 return isset($result) && isset($result[0]) && $result[0] != "" ? $result[0]->product_parent : 0;	
	 }
	 public function get_product_category_list($search_string = null)
	 {
		 global $wpdb, $wcuf_wpml_helper;
		  $query_string = "SELECT product_categories.term_id as id, product_categories.name as category_name
							 FROM {$wpdb->terms} AS product_categories
							 LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id = product_categories.term_id 							 						 	 
							 WHERE tax.taxonomy = 'product_cat' 
							 AND product_categories.slug <> 'uncategorized' 
							";
		 if($search_string)
					$query_string .=  " AND ( product_categories.name LIKE '%{$search_string}%' )";
			
		$query_string .=  " GROUP BY product_categories.term_id ";
		$result = $wpdb->get_results($query_string ) ;
		
		//WPML
		if($wcuf_wpml_helper->wpml_is_active())
		{
			$result = $wcuf_wpml_helper->remove_translated_id($result, 'product_cat', true);
		} 
		
		return $result;
	 }
	
	private function get_post_ids_using_categories($category_type_name, $selected_categories, $get_post_belonging_to_children_categories /* , $strategy */ )
	{
		//$get_post_belonging_to_children_categories : "selected_only" || "all_children"
		
		global $wpdb;
		//$not_suffix = $strategy == "all" ? "  " : " NOT ";
		$not_suffix = " ";
		$results = $additional_categories_ids = array();
		
		//Retrieve children categories id
		if($get_post_belonging_to_children_categories == 'all_children')
		{
			foreach($selected_categories as $current_category)
			{
				$args = array(
						'type'                     => 'post',
						'child_of'                 => $current_category,
						'parent'                   => '',
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 1,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'number'                   => '',
						'taxonomy'                 => $category_type_name,
						'pad_counts'               => false

					); 

					$categories = get_categories( $args );
					//wcosm_var_dump($categories);
					foreach($categories as $result)
					{
						if(!is_array($result))
							$additional_categories_ids[] = $result->term_id;
					}
			}
		}
		if(!empty($additional_categories_ids))
			$selected_categories = array_merge($selected_categories, $additional_categories_ids);
		
		//GROUP_CONCAT(posts.ID)
		$wpdb->query('SET group_concat_max_len=50000000'); 
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT DISTINCT posts.ID
				 FROM {$wpdb->posts} AS posts 
				 INNER JOIN {$wpdb->term_relationships} AS term_rel ON term_rel.object_id = posts.ID
				 INNER JOIN {$wpdb->term_taxonomy} AS term_tax ON term_tax.term_taxonomy_id = term_rel.term_taxonomy_id 
				 INNER JOIN {$wpdb->terms} AS terms ON terms.term_id = term_tax.term_id
				 WHERE  posts.post_status IN({$this->get_selectable_post_statuses_query_string()}) 
				 AND  terms.term_id {$not_suffix} IN ('" . implode( "','", $selected_categories). "')  ";
				 //AND term_tax.taxonomy = '{$category_type_name}' "; 
		$ids = $wpdb->get_results($query, ARRAY_A);
	
		foreach($ids as $id)
			$results[] = $id['ID'];
		return $results;
	}
	private function get_complementry_ids($ids_to_exclude, $post_type = "post")
	{
		global $wpdb;
		$results = array();
		$query = "SELECT posts.ID 
				  FROM {$wpdb->posts} AS posts
				  WHERE posts.post_status IN({$this->get_selectable_post_statuses_query_string()}) 
				  AND posts.post_type = '{$post_type}' 
				  AND posts.ID NOT IN('".implode("','",$ids_to_exclude)."') ";
		$ids = $wpdb->get_results($query, ARRAY_A);
		foreach($ids as $id)
			$results[] = $id['ID'];
		return $results;
	}
	public function get_selectable_post_statuses_query_string()
	{
		return "'publish','draft'";
	}
	function has_a_required_upload_in_its_single_page($current_product, $check_if_upload_has_been_performed = false, $quantity = 1)
	{
		global $wcuf_product_model, $wcuf_option_model, $wcuf_wpml_helper,$sitepress, $wcuf_session_model,$wcuf_customer_model, $wcuf_order_model;
		$all_options = $wcuf_option_model->get_all_options();
		
		$fields_for_which_upload_has_not_been_performed = array();
		$product = is_object($current_product) ? array('product_id' => $wcuf_product_model->get_product_id($current_product), 'variation_id' => empty($current_product->variation_id) ? 0 : $current_product->variation_id) : $current_product;		
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		$max_uploaded_files_number_considered_as_sum_of_quantities = $all_options['max_uploaded_files_number_considered_as_sum_of_quantities'];
		
		if(is_array($file_fields_groups))
		foreach($file_fields_groups as $file_fields)
		{ 
			$enable_for = isset($file_fields['enable_for']) ? $file_fields['enable_for']:'always';
			$display_on_checkout = isset($file_fields['display_on_checkout']) ? $file_fields['display_on_checkout']:false;
			$display_on_product = isset($file_fields['display_on_product']) ? $file_fields['display_on_product']:false;
			$display_on_cart = isset($file_fields['display_on_cart']) ? $file_fields['display_on_cart']:false;
			$disable_stacking = isset($file_fields['disable_stacking']) ? (bool)$file_fields['disable_stacking']:false;
			$display_on_product_before_adding_to_cart = isset($file_fields['display_on_product_before_adding_to_cart']) ? $file_fields['display_on_product_before_adding_to_cart']:false;
			$disable_stacking_for_variation = isset($file_fields['disable_stacking_for_variation']) && !$display_on_product_before_adding_to_cart  ? (bool)$file_fields['disable_stacking_for_variation']:false;
			//max uploadable files
			$enable_multiple_uploads_per_field = isset($file_fields['enable_multiple_uploads_per_field']) ? (bool)$file_fields['enable_multiple_uploads_per_field'] : false;
			$multiple_uploads_max_files_depends_on_quantity = isset($file_fields['multiple_uploads_max_files_depends_on_quantity']) && !$display_on_product_before_adding_to_cart && $disable_stacking && $enable_multiple_uploads_per_field ? $file_fields['multiple_uploads_max_files_depends_on_quantity']:false;
			$multiple_uploads_min_files_depends_on_quantity = isset($file_fields['multiple_uploads_min_files_depends_on_quantity']) && !$display_on_product_before_adding_to_cart && $disable_stacking && $enable_multiple_uploads_per_field ? $file_fields['multiple_uploads_min_files_depends_on_quantity']:false;
			$multiple_uploads_minimum_required_files = isset($file_fields['multiple_uploads_minimum_required_files'])   ? $file_fields['multiple_uploads_minimum_required_files']:0;
			$multiple_uploads_maximum_required_files = isset($file_fields['multiple_uploads_max_files'])   ? $file_fields['multiple_uploads_max_files']:0;
			//
			$selected_categories = isset($file_fields['category_ids']) ? $file_fields['category_ids']:array();
			$display_product_fullname = isset($file_fields['full_name_display']) ? $file_fields['full_name_display']:true; //Usefull only for variable products
			$all_products_cats_ids = array();
			$products_for_which_stacking_is_disabled = array();
			$selected_products = isset($file_fields['products_ids']) ? $file_fields['products_ids']:array();
			$enable_upload_per_file = false;
			$required = isset($file_fields['required_on_checkout']) ? $file_fields['required_on_checkout']:false;
			$has_required_field = $enable_for == 'always' /* && $display_on_product */ && $required ? true:false;
			$roles = !isset($file_fields['roles']) ?  array():$file_fields['roles'];
			$roles_policy = !isset($file_fields['roles_policy']) ?  "allow":$file_fields['roles_policy'];
			$visibility_gateways = !isset($file_fields['visibility_gateways']) ?  array():$file_fields['visibility_gateways'];
			$visibility_payment_gateway_policy = !isset($file_fields['visibility_payment_gateway_policy']) ?  "allow":$file_fields['visibility_payment_gateway_policy'];
			$current_payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
			//Role check
			if((!empty($roles) && !$wcuf_customer_model->belongs_to_allowed_roles($roles,$roles_policy)) || (!$display_on_product && !$display_on_cart && !$display_on_checkout))
				continue;
			
			//Gateway
			if(!empty($visibility_gateways) && (!isset($current_payment_method) || !$wcuf_order_model->is_selected_payment_method_allowed($current_payment_method, $visibility_gateways,$visibility_payment_gateway_policy)))
				continue;
			
			if(( ($display_on_product || $display_on_cart || $display_on_checkout) && $required) && (($enable_for === 'always' /* && $disable_stacking */) || $enable_for !== 'always' && (count($selected_categories) > 0 || count($selected_products) > 0 )))
			{
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
					$variation_id = $is_variation = 0;
					
					foreach($selected_products as $product_id)
					{	
						$discard_field = false;
						$is_variation = $this->is_variation($product_id);
						$variation_id = $is_variation > 0 ? $product_id : 0 ;
						$product_id = $is_variation > 0 ? $is_variation : $product_id ;
						/* if( ($product_id == $product['product_id'] && ( $product['variation_id'] == 0 || $variation_id == 0 || $variation_id == $product['variation_id']) && ($enable_for === 'categories' || $enable_for === 'categories_children'))
							|| ( ($product_id != $product['product_id'] || ($is_variation > 0 && $product_id == $product['product_id'] && $variation_id != $product['variation_id'])) && ($enable_for === 'disable_categories' || $enable_for === 'disable_categories_children')) )
							{ */
						if( ($product_id == $product['product_id'] && ($variation_id == 0 || $variation_id == $product['variation_id']) && ($enable_for === 'categories' || $enable_for === 'categories_children'))
								|| ( !in_array($product['product_id'], $selected_products) && !in_array($product['variation_id'], $selected_products) && ($enable_for === 'disable_categories' || $enable_for === 'disable_categories_children')) 
						   )
							{
								
								if($disable_stacking)
									$enable_upload_per_file = true;
								$has_required_field = true;
							}
							elseif( $enable_for !== 'always') 
									$discard_field = true;
						
					}
				}
				else if($enable_for === 'always' && $disable_stacking)
				{
					$enable_upload_per_file = true;
					$has_required_field = true;
				}
					
		
				//product categories
				$product_cats = wp_get_post_terms( $product["product_id"], 'product_cat' );
				$current_product_categories_ids = array();
				foreach($product_cats as $category)
				{
					$category_id = $category->term_id;
					
					if(!$disable_stacking)
						array_push($all_products_cats_ids, (string)$category_id);
					else
						array_push($current_product_categories_ids, (string)$category_id);
					
					//parent categories
					if($enable_for == "categories_children" || $enable_for == "disable_categories_children")
					{
						$parents =  get_ancestors( $category->term_id, 'product_cat' ); 
						foreach($parents as $parent_id)
						{
							$temp_category = $parent_id;
							if(!$disable_stacking)
								array_push($all_products_cats_ids, (string)$temp_category);
							else
								array_push($current_product_categories_ids, (string)$temp_category);
						}
					}
				}
				
				//Can enable upload for this product? (if stacking uploads are disabled)
				if( $disable_stacking && count($selected_categories) > 0)
				{
					if($enable_for === 'categories' || $enable_for === 'categories_children')
					{
						if(array_intersect($selected_categories, $current_product_categories_ids))
						{
							$has_required_field = true;
						}
					}
					elseif(!$discard_field)
					{
						if(!array_intersect($selected_categories, $current_product_categories_ids))
						{
							$has_required_field = true;
						}
						else $has_required_field = false;
					}	
				}
			
				//Cumulative ORDER catagories. If exists at least one product with an "enabled"/"disabled" category, upload field can be rendered
				if( !$disable_stacking && count($selected_categories) > 0)
					if($enable_for === 'categories' || $enable_for === 'categories_children')
					{  
						if(array_intersect($selected_categories, $all_products_cats_ids))
							$has_required_field = true;
					}
					elseif(!$discard_field)
					{ 
						if(!array_intersect($selected_categories, $all_products_cats_ids))
							$has_required_field = true;
						else $has_required_field = false;
					}						
			}
			
			if($check_if_upload_has_been_performed /* && $required */ && ($has_required_field || $enable_multiple_uploads_per_field))
			{
				$individual_id = isset($product[WCUF_Cart::$sold_as_individual_item_cart_key_name]) ? "-idsai".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] : "";
				$disable_stacking_for_variation = $individual_id != "" ? true : $disable_stacking_for_variation;
				//wc_measuere id?
				
				$uploaded_performed = $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']) == null &&
									  $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id'].$individual_id) == null  && 
									  $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']) == null 
									  ? false : true;
				
				
				$current_uploaded_files_num = 0;	
				if($enable_multiple_uploads_per_field)
			    {
				   $multiple_uploads_maximum_required_files = $multiple_uploads_max_files_depends_on_quantity ? $quantity : $multiple_uploads_maximum_required_files;
				   $multiple_uploads_minimum_required_files = $multiple_uploads_min_files_depends_on_quantity ? $quantity : $multiple_uploads_minimum_required_files;
				   			   
				   
				   if($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']) != null)
						$current_uploaded_files_num = count($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id'])['name']);
				  
				  else if($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id'].$individual_id) != null)
						$current_uploaded_files_num = count($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id'].$individual_id)['name']);
				   
				   else if($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']) != null)
						$current_uploaded_files_num = count($wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id'])['name']);
					
				
				   if($max_uploaded_files_number_considered_as_sum_of_quantities)
				   {
					  $current_uploaded_files_num = 0;
					  //It could be moooooore simple  ---> could be avoided the 3 if from 411 to 418
					  if($individual_id != "")
						  $upload_field_data = $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id'].$individual_id);
					  else 
					  {
						  $upload_field_data = $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']);					  
						  if($upload_field_data == null)
							 $upload_field_data = $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id']) == null ? $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']) : $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id']) ;				
					   }
					if(isset($upload_field_data['quantity']))
					  foreach((array)$upload_field_data['quantity'] as $uploaded_file_quantity)
					   {
						  $current_uploaded_files_num += $uploaded_file_quantity;
					   }
				   }
					
			    }
				$num_uploaded_files_error = $enable_multiple_uploads_per_field && (($current_uploaded_files_num > $multiple_uploads_maximum_required_files && $multiple_uploads_maximum_required_files != 0) || $current_uploaded_files_num < $multiple_uploads_minimum_required_files);
				if((!$uploaded_performed && $has_required_field) || ($uploaded_performed && $num_uploaded_files_error))
				{
					$field_id = $file_fields['id'];
					if($disable_stacking)
						$field_id.="-". $product['product_id'];
					if($disable_stacking && $disable_stacking_for_variation)
						$field_id.="-". $product['variation_id'];
					
					
					$additional_name_identifier = isset($product[WCUF_Cart::$sold_as_individual_item_cart_key_name]) ? " #".$product[WCUF_Cart::$sold_as_individual_item_cart_key_name] : "";
					$fields_for_which_upload_has_not_been_performed[$field_id] = array('upload_field_name'=>$file_fields['title'], 
										  'product_name' => $this->get_product_name($product['variation_id'] != 0 ? $product['variation_id'] : $product['product_id']).$additional_name_identifier, 
										  'product_id' => $product['product_id'], 
										  'variation_id' => $product['variation_id'],
										  'disable_stacking' => $disable_stacking,
										  'disable_stacking_for_variation' => $disable_stacking_for_variation,
										  'num_uploaded_files_error' => $num_uploaded_files_error,
										  'min_uploadable_files' => $multiple_uploads_minimum_required_files,
										  'max_uploadable_files' => $multiple_uploads_maximum_required_files,
										  'num_uploaded_files' => $current_uploaded_files_num);
				}
					
			}
			else if(!$check_if_upload_has_been_performed && $has_required_field) //???
				return true;
		}//end foreach upload_fields
		
		if($check_if_upload_has_been_performed)
			return $fields_for_which_upload_has_not_been_performed;
		
		return false;
	}
}
?>
