<?php
class WCUF_Wpml
{
	var $current_lang;
	var $before_admin_lang;
	public function __construct()
	{
		add_action('plugins_loaded', array(&$this,'init_ajax_language'));
	}
	public function wpml_is_active()
	{
		return class_exists('SitePress');
	}
	public function init_ajax_language()
	{
		if(!isset($_POST['wcuf_wpml_language']) || !$this->wpml_is_active())
			return;
		
		global $sitepress;
		load_plugin_textdomain('woocommerce-files-upload', false, WCUF_PLUGIN_LANG_PATH );
		$sitepress->switch_lang($_POST['wcuf_wpml_language'], true);
	}
	public function switch_to_admin_default_lang()
	{
		if(!$this->wpml_is_active())
			return; 
		
		global $sitepress_settings,$sitepress,$locale;			
		$this->before_admin_lang = ICL_LANGUAGE_CODE;
		$sitepress->switch_lang($sitepress_settings['admin_default_language']);		
		$locale = $sitepress_settings['admin_default_language']."_".strtoupper($sitepress_settings['admin_default_language']); 
		//apply_filters ( 'override_load_textdomain', false, 'woocommerce-files-upload', WCUF_PLUGIN_LANG_PATH);
		load_plugin_textdomain('woocommerce-files-upload', false, WCUF_PLUGIN_LANG_PATH);
	}
	public function restore_from_admin_default_lang()
	{
		if(!$this->wpml_is_active())
			return;
		
		global $sitepress,$locale;
		$sitepress->switch_lang($this->before_admin_lang);
		$locale = $this->before_admin_lang."_".strtoupper($this->before_admin_lang);
		//apply_filters ( 'override_load_textdomain', true, 'woocommerce-files-upload', WCUF_PLUGIN_LANG_PATH);
		load_plugin_textdomain('woocommerce-files-upload', false, WCUF_PLUGIN_LANG_PATH);
	}
	public function remove_translated_id($items_array, $post_type = "product", $default_language = false)
	{
		if(!class_exists('SitePress'))
			return false;
		global $sitepress;
		$current_language = ICL_LANGUAGE_CODE;
		if($default_language)
			$current_language = $sitepress->get_default_language();
		$filtered_items_list = array();
		foreach($items_array as $item)	
		{
			/* $result = wpml_get_language_information($item->id);
			if(!is_bool (strpos($result['locale'], ICL_LANGUAGE_CODE)))
			{
				array_push($filtered_items_list, $item);
			}*/
			
			$item_id = is_object($item) && method_exists($item,'get_id') ? $item->get_id() : $item->id;
			
			//If in the selected language the $id is the same of the language, is not a transaltion so can be kept
			if(function_exists('icl_object_id'))
				$item_translated_id = icl_object_id($item_id, $post_type, false,$current_language);
			else
				$item_translated_id = apply_filters( 'wpml_object_id', $item_id, $post_type, false, $current_language );
			
			if($item_id == $item_translated_id)
				array_push($filtered_items_list, $item);
		}
			
		return $filtered_items_list ;
	}
	
	public function get_main_language_ids($items_array, $post_type = "product")
	{
		if(!class_exists('SitePress'))
			return $items_array;
		
		global $sitepress;
		$filtered_items_list = array();
		foreach($items_array as $item)	
		{
			$item_id = is_object($item) && method_exists($item,'get_id') ? $item->get_id() : $item->id;
			
			if(function_exists('icl_object_id'))
				$item_translated_id = icl_object_id($item_id , $post_type, false, $sitepress->get_default_language());
			else
				$item_translated_id = apply_filters( 'wpml_object_id', $item_id , $post_type, false, $sitepress->get_default_language() );
			
			if(!$item_translated_id) //means is already main language id
				array_push($filtered_items_list, $item);
		}
			
		return $filtered_items_list ;
	}
	public function get_main_language_id($id_to_get_original, $post_type = "product")
	{
		if(!class_exists('SitePress') || $id_to_get_original == 0)
			return $id_to_get_original;
		
		global $sitepress;
		
		if(function_exists('icl_object_id'))
				$id_to_get_original = icl_object_id($id_to_get_original, $post_type, true, $sitepress->get_default_language());
			else
				$id_to_get_original = apply_filters( 'wpml_object_id',$id_to_get_original, $post_type, true, $sitepress->get_default_language() );
			
		return $id_to_get_original;
	}
	public function get_all_translation_ids($post_id, $post_type = "product")
	{
		if(!class_exists('SitePress'))
			return false;
		
		global $sitepress, $wpdb;
		$translations = array();
		$translations_result = array();
		
		//if($post_type == "product")
		{
			$trid = $sitepress->get_element_trid($post_id, 'post_'.$post_type);
			$translations = $sitepress->get_element_translations($trid, $post_type);
			//wctbp_var_dump($translations);
			foreach($translations as $language_code => $item)
			{
				if($language_code != $sitepress->get_default_language())
					$translations_result[] = $item->element_id;
			}
			//wctbp_var_dump($translations_result);
		}
		
		return !empty($translations_result) ? $translations_result:false;
	}
	
	
	function translate_single_string($id, $field)
	{
		if(!class_exists('SitePress'))
			return false;
		
		$result = array();
		$result['title'] =      			    apply_filters( 'wpml_translate_single_string', $field['title'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'_title', ICL_LANGUAGE_CODE  );
		$result['description'] = 				apply_filters( 'wpml_translate_single_string', $field['description'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'_description', ICL_LANGUAGE_CODE  );
		$result['message_already_uploaded'] =   apply_filters( 'wpml_translate_single_string', $field['message_already_uploaded'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'_already_uploaded', ICL_LANGUAGE_CODE  );
		$result['text_field_label'] =   			apply_filters( 'wpml_translate_single_string', $field['text_field_label'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'__text_field_label', ICL_LANGUAGE_CODE  );
		$result['text_field_description'] =   		apply_filters( 'wpml_translate_single_string', $field['text_field_description'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'_text_field_description', ICL_LANGUAGE_CODE  );
		$result['disclaimer_text'] =     apply_filters( 'wpml_translate_single_string', $field['disclaimer_text'], 'woocommerce-files-upload-field-text',  'wcuf_'.$id.'_disclaimer_text', ICL_LANGUAGE_CODE  );
			
		return $result;
	}
	public function register_strings($field_data)
	{
		if(!class_exists('SitePress'))
			return false;
		
		foreach($field_data as $file_meta)
		{
			//Register new string
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_title', $file_meta['title'] );
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_description', $file_meta['description'] );
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_already_uploaded', $file_meta['message_already_uploaded'] );
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_text_field_label', $file_meta['text_field_label'] );
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_text_field_description', $file_meta['text_field_description'] );
			do_action( 'wpml_register_single_string', 'woocommerce-files-upload-field-text', 'wcuf_'.$file_meta['id'].'_disclaimer_text', $file_meta['disclaimer_text'] );
		}
		
		return true;	
	}
	public function deregister_strings($fields, $ids_array = false)
	{
		if(!class_exists('SitePress'))
			return false;
		
		if(function_exists ( 'icl_unregister_string' ))
		{
			$ids_to_delete = array();
			foreach((array)$fields as $key => $value)
			{
				if(!$ids_array)
					$ids_to_delete[] = $value['id'];
				elseif($value)
					$ids_to_delete[] = $key;
			}
			
			foreach((array)$ids_to_delete as $id)
			{
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_title' );
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_description' );
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_already_uploaded' );
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_text_field_label' );
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_text_field_description' );
				icl_unregister_string ( 'woocommerce-files-upload-field-text', 'wcuf_'.$id.'_disclaimer_text' );
			}
		}
	}
	public function switch_to_default_language()
	{
		if(!$this->wpml_is_active())
			return;
		global $sitepress;
		$this->curr_lang = ICL_LANGUAGE_CODE ;
		$sitepress->switch_lang($sitepress->get_default_language());
	
	}
	public function switch_to_current_language()
	{
		if(!$this->wpml_is_active())
			return;
		
		global $sitepress;
		$sitepress->switch_lang($this->curr_lang);
	}
}
?>