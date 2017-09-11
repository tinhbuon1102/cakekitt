<?php 
class WCUF_Session
{
	var $current_session_id;
	var $timeout_duration = 3600; //1200: 60 min
	var $session_array_keys = array('quantity', //shared
									'tmp_name', 'name',  'file_temp_name', //Session
									 ); 
	var $order_array_keys = array('quantity', 'absolute_path', 'url', 'original_filename', 'source', 'ID3_info');
	public function __construct()
	{
		//add_filter( 'wc_session_expiring', array( &$this, 'session_expiring' ), 10 ,1);
		add_action( 'init', array( &$this, 'manage_session' ));
		add_action('wp_logout', array( &$this, 'clear_session_data' ));
	}
	public function manage_session() 
	{
		global $wcuf_file_model, $wcuf_option_model;
		$time = $_SERVER['REQUEST_TIME'];
		$this->timeout_duration = isset($wcuf_option_model) ? $wcuf_option_model->get_all_options('temp_files_clear_interval') : 20;
		$this->current_session_id = session_id();
		if(empty($this->current_session_id))
		{
			$this->create_session();
		}
	
		//Session: write
		/* if(!isset($_SESSION)) 
			@session_start(); */
	
		//Session: read
		$last_activity = $this->get_last_activity();
		//if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $this->timeout_duration) //old
		if (isset($last_activity) && ($time - $last_activity) > $this->timeout_duration) 
		{
			$this->clear_session_data();
		}
		
		//old files/sessions
		$wcuf_file_model->delete_expired_sessions_files($this->timeout_duration);
		$this->delete_expired_sessions();
		
		//Session: write		
		//$_SESSION['LAST_ACTIVITY'] = $time;
		$this->update_session($time);
	}
	private function delete_expired_sessions()
	{
		global $wcuf_db_model, $wcuf_option_model;
		//$this->timeout_duration = $wcuf_option_model->get_all_options('temp_files_clear_interval');
		$wcuf_db_model->delete_expired_sessions($this->timeout_duration);
	}
	private function create_session()
	{
		global $wcuf_db_model;
		@session_unset();     
		@session_destroy();
		@session_start();
		$this->current_session_id = session_id(); //new
		$wcuf_db_model->create_session_row($this->current_session_id);
	}
	private function get_last_activity()
	{
		global $wcuf_db_model;
		return $wcuf_db_model->read_session_row('last_activity', $this->current_session_id);
	}
	private function update_session($time = null)
	{
		global $wcuf_db_model;
		$time = !isset($time) ? time() : $time;
		
		//Session: write
		//$_SESSION['LAST_ACTIVITY'] = $time ; //old
		$wcuf_db_model->write_session_row('last_activity', $time , $this->current_session_id);
	}
	private function get_data_from_session($session_type)
	{
		global $wcuf_db_model;
		$result = $wcuf_db_model->read_session_row('item', $this->current_session_id);
		$result = !isset($result) ? array() : unserialize($result);
		return isset($result[$session_type]) ? $result[$session_type] : array();
	}
	private function save_data_into_session($data, $session_type)
	{
		global $wcuf_db_model;
		//$data = serialize($data);
		$wcuf_db_model->write_session_row('item', $data, $this->current_session_id, $session_type);
		$wcuf_db_model->write_session_row('session_type', $session_type,$this->current_session_id); //session_type no more usefull
	}
	private function delete_items_from_session($session_type = null)
	{
		global $wcuf_db_model;
		$wcuf_db_model->delete_session_row($this->current_session_id, $session_type);
	}
	public function clear_session_data( )
	{
		global $wcuf_db_model;
		$this->remove_item_data();
		$this->remove_item_data(null, false);
		
		//Session: delete
		//old
		/* @session_unset();     
		@session_destroy();
		@session_start();  */
		
		//new
		$this->delete_items_from_session();
		$this->create_session();
	}
	
	
	/*Format:
		array(2) {
	  ["wcufuploadedfile_3-59-60"]=>
	  array(5) {
		["name"]=>
		string(9) "test2.pdf"
		["type"]=>
		string(22) "application/x-download"
		["tmp_name"]=>
		string(113) "/var/.../wp-content/uploads/wcuf/tmp/34225430759"
		["error"]=>
		int(0)
		["size"]=>
		int(85996)
	  }
  */
	function assign_uploads_to_unique_item($product_id, $variation_id,$unique_cart_item_key)
	{
		global $wcuf_option_model;
		
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		foreach($file_fields_groups as $file_fields)
		{
			$key = $variation_id != 0 ? "wcufuploadedfile_".$file_fields['id']."-".$product_id."-".$variation_id : "wcufuploadedfile_".$file_fields['id']."-".$product_id;
			$all_data = $this->get_data_from_session('_wcuf_temp_uploads');
			if(isset($all_data[$key]))
			{
				$new_key = "wcufuploadedfile_".$file_fields['id']."-".$product_id."-".$variation_id."-".$unique_cart_item_key;
				$all_data[$new_key] =  $all_data[$key];
				unset($all_data[$key]);
				$this->save_data_into_session($all_data, '_wcuf_temp_uploads');
			}
		}
	}
	function set_item_data(  $key, $value, $file_already_moved = false, $is_order_details = false, $num_uploaded_files = 1, $ID3_info = null) 
	{
		global $wcuf_file_model;
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		
		$this->update_session();
		//Session: read
		/* if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key];  */
		$data = $this->get_data_from_session($session_key );
		$is_multiple_file_upload = is_array($value['tmp_name']) && count($value['tmp_name']) > 1;
		if ( empty( $data[$key] ) ) 
		{
			$data[$key] = array();
		}
		else
		{
			//Old version: delete old data 
			//$wcuf_file_model->delete_temp_file($data[$key]['tmp_name']);
			
			//New version: incremental upload. Old data is merged with new data 
			//the merge_item_data_arrays() will be lately called to marge recursively old and new values array
			
		}
		if(!$file_already_moved)
		{
			$results = $wcuf_file_model->move_temp_file($value['tmp_name']);
			
			$value['tmp_name'] = array();
			$value['file_temp_name'] = array();
			foreach($results as $index => $result)
			{
				$value['tmp_name'][$index] = $result['absolute_path'];
				$value['file_temp_name'][$index] = $result['file_temp_name'];
			}
		}
		 
		$value['title'] = $_POST['title'];
		$value['is_multiple_file_upload'] = $is_multiple_file_upload;
		$value['num_uploaded_files'] = $num_uploaded_files;
		$value['user_feedback'] = isset($_POST['user_feedback']) && $_POST['user_feedback'] != 'undefined' ? stripcslashes($_POST['user_feedback']):"";
		$value['ID3_info'] = isset($ID3_info) && !empty($ID3_info) ? $ID3_info: "none";
		//$data[$key] = $value;
		//New version: old and new values are merged
		//wcuf_var_dump($value);
		//wcuf_var_dump($data[$key]);
		$data[$key] = $this->merge_item_data_arrays($data[$key], $value);
		//wcuf_var_dump("Result:");
		//wcuf_var_dump($data[$key]);
		
		//Session: write
		//$_SESSION[$session_key] = $data;
		$this->save_data_into_session($data,$session_key);
	}
	public function merge_item_data_arrays($item_1, $item_2, $is_order = false)
	{
		/* wcuf_var_dump($item_1);
		wcuf_var_dump("********");
		wcuf_var_dump($item_2);
		wcuf_var_dump("********"); */
		if(empty($item_1))
			return $item_2;
		
		$array_key_to_merge = $is_order ? $this->order_array_keys : $this->session_array_keys ; //array('tmp_name', 'name', 'quantity', 'file_temp_name');
		
		//Base index computation
		$base_index =  0;
		if(isset($item_1['quantity']))
		{
			foreach((array)$item_1['quantity'] as $tmp_index => $tmp_quantity)
				$base_index = $tmp_index > $base_index ? $tmp_index : $base_index;
			$base_index++;
		}
		foreach($array_key_to_merge as $key)
			if(isset($item_2[$key]) && $key != 'ID3_info')
				foreach((array)$item_2[$key] as $elem_index => $elem)
				{
					if(!isset($item_1[$key]))
						$item_1[$key] = array();
					$item_1[$key][$base_index + $elem_index] = $elem;
				}
			
		
		$item_1['num_uploaded_files'] = isset($item_1['num_uploaded_files']) ? $item_1['num_uploaded_files'] + $item_2['num_uploaded_files'] : $item_2['num_uploaded_files'];
		$item_1['user_feedback'] = isset($item_2['user_feedback']) ? $item_2['user_feedback'] : "";
		$item_1['is_multiple_file_upload'] = is_array($item_2['quantity']) && count($item_2['quantity']) > 0 ? true : false; //$item_2['is_multiple_file_upload'];
		$item_1['ID3_info'] = isset($item_1['ID3_info']) ? $item_1['ID3_info'] : "none";
		$item_1['upload_field_id'] = isset($item_1['upload_field_id']) ? $item_1['upload_field_id'] : -1;
		$item_1['upload_field_id'] = isset($item_2['upload_field_id']) ? $item_2['upload_field_id'] : $item_1['upload_field_id'];
		
		//ID3_info: is an array in which $key = id of the uploaded file (num_file). The id is computed by iterating the $item_1 lenght + $item_2 current item index.
		if($item_2['ID3_info'] != 'none')
		{
			//$base_index = 0;
			$item_1['ID3_info'] = is_array($item_1['ID3_info']) ? $item_1['ID3_info'] : array();
				/* foreach((array)$item_1['ID3_info'] as $id3_key => $id3_info)
					$base_index = $id3_key > $base_index ? $id3_key + 1  : $base_index; */
			
			foreach($item_2['ID3_info'] as $id3_key => $id3_info)
			{
				$item_1['ID3_info'][$base_index + $id3_key] = $id3_info;
				$item_1['ID3_info'][$base_index + $id3_key]['index'] = $base_index + $id3_key;
			}
		}
		//wcuf_var_dump($item_1);
		return $item_1;
	}
	public function get_item_data( $key = null, $default = null, $is_order_details = false ) 
	{
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		
		//Session: read
		/* if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key];  */
		
		$data = $this->get_data_from_session($session_key );
		if ( $key == null ) 
			return isset($data) && !empty($data) ? $data : $default;
		else
			return empty( $data[$key] ) ? $default : $data[$key];
	}
	function remove_data_by_product_ids($cart_item)
	{
		global $wcuf_file_model, $wcuf_product_model;
		$id = "-".$cart_item['product_id'];
		if($cart_item['variation_id'] !=0)
			$id .= "-".$cart_item['variation_id'];
		
		$all_data = $this->get_item_data();
		if(isset($all_data))
		{
			foreach($all_data as $fieldname_id => $item)
			{
				/* $field_ids = $wcuf_file_model->get_product_ids_and_field_id_by_file_id($fieldname_id);
				$is_price_calulator_item = $field_ids['unique_product_id'] != "" && !$field_ids['idsai'] ? true : false;
				 */
				if($this->endsWith($fieldname_id, $id) || $this->contains($fieldname_id, $id."-"))
					$this->remove_item_data($fieldname_id);
			}
		}
	}
	function remove_all_item_data_by_unique_key($product_id, $variation_id, $unique_key = false, $is_order_details = false )
	{
		global $wcuf_session_model;
		//$item_to_remove = $item["variation_id"] == 0 ? $item["product_id"]."-".$item[WCUF_Cart::$sold_as_individual_item_cart_key_name] : $item["product_id"]."-".$item["variation_id"]."-".$item[WCUF_Cart::$sold_as_individual_item_cart_key_name];
		$complete_item_id = $unique_key !== false ? $product_id."-".$variation_id."-idsai".$unique_key : $product_id."-".$variation_id;
		$all_data = $this->get_item_data();
		if(isset($all_data))
			foreach($all_data as $fieldname_id => $item)
			{
				/* wcuf_var_dump($fieldname_id);
				wcuf_var_dump($this->contains($fieldname_id, $complete_item_id));
				wcuf_var_dump($complete_item_id); */
				if($this->contains($fieldname_id, $complete_item_id))
					$wcuf_session_model->remove_item_data($fieldname_id);
			
				/* if($unique_key !== false)
				{
					$simple_item_id = // $unique_key ? $product_id."-".$item[WCUF_Cart::$sold_as_individual_item_cart_key_name] :  
									  $product_id;
					if($this->contains($fieldname_id, $complete_item_id))
						$wcuf_session_model->remove_item_data($fieldname_id);
				} */
			}
	}
	function remove_all_item_data($field_id, $product_id = null, $variation_id = null)
	{
		$this->remove_item_data("wcufuploadedfile_".$field_id);
		if(isset($product_id))
			$this->remove_item_data("wcufuploadedfile_".$field_id."-".$product_id);
		if(isset($variation_id))
			$this->remove_item_data("wcufuploadedfile_".$field_id."-".$product_id."-".$variation_id);
	}
	public function remove_item_data( $key = null, $is_order_details = false) 
	{
		global $wcuf_file_model;
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		
		//Session: read
		/* if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key];  */
		$data = $this->get_data_from_session($session_key );
		// If no item is specified, delete *all* item data. This happens when we clear the cart (eg, completed checkout)
		if ( $key == null ) 
		{
			if(isset($data))
				foreach((array)$data as $temp_file_data)
					$wcuf_file_model->delete_temp_file($temp_file_data['tmp_name']);
			
			//Session: write
			//$_SESSION[$session_key] = array() ;
			$this->delete_items_from_session($session_key);
			return;
		}
		// If item is specified, but no data exists, just return
		if ( !isset( $data[$key] ) ) 
		{
			return;
		}
		else 
		{
			$wcuf_file_model->delete_temp_file($data[$key]['tmp_name']);
			unset( $data[$key] );
		}
		
		//Session: write
		//$_SESSION[$session_key] = $data;
		$this->save_data_into_session($data,$session_key);
	} 
	public function remove_upload_field_subitem($field_id, $single_file_id)
	{
		global $wcuf_file_model;
		$data = $this->get_data_from_session('_wcuf_temp_uploads' );
		if(!isset($data[$field_id]))
			return;
		
		//file delete
		$wcuf_file_model->delete_temp_file($data[$field_id]['tmp_name'][$single_file_id]);
		unset($data[$field_id]['tmp_name'][$single_file_id]);
		
		$result = $this->remove_subitem_from_session_array($data[$field_id], $single_file_id);
		if($result == null)
			unset($data[$field_id]);
		else 
			$data[$field_id] = $result;
			
		$this->save_data_into_session($data,'_wcuf_temp_uploads' );
	}
	public function remove_subitem_from_session_array($array, $index_to_remove)
	{
		if(!isset($array))
			return null;
		
		$key_to_delete = array_merge($this->session_array_keys, $this->order_array_keys);
		
		foreach($key_to_delete as $key_name)
		{
			if(is_array($array) && isset($array[$key_name]) && is_array($array[$key_name]) && isset($array[$key_name][$index_to_remove]))
				unset($array[$key_name][$index_to_remove]);
		}
		
		//returs null if the upload field is empty (last element was deleted)
		if(empty($array['quantity']))
			return null;
		
		return $array;
	}
	public function endsWith($haystack, $needle) 
	{
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
	public function contains($haystack, $needle) 
	{
		return $needle === "" || (strpos($haystack, $needle) !== false);
	}
}
?>