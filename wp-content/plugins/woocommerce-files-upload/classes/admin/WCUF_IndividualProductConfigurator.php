<?php 
class WCUF_IndividualProductConfigurator
{
	static $page_id = "upload-files-configurator_page_acf-options-individual-products-configurator";
	public function __construct()
	{
		//dd_filter('acf/init', array(&$this,'init_options_menu'));
		$this->init_options_menu();
	}
	function init_options_menu()
	{
		if( function_exists('acf_add_options_page') ) 
		{
			 acf_add_options_sub_page(array(
				'page_title' 	=> 'Individual products configurator',
				'menu_title'	=> 'Individual products configurator',
				'parent_slug'	=> 'woocommerce-files-upload',
			));
			
			
			
			add_action( 'current_screen', array(&$this, 'cl_set_global_options_pages') );
		}
	}
	/**
	 * Force ACF to use only the default language on some options pages
	 */
	function cl_set_global_options_pages($current_screen) 
	{
	  if(!is_admin())
		  return;
	  
	 //wcuf_var_dump($current_screen->id);
	  
	  $page_ids = array(
		WCUF_IndividualProductConfigurator::$page_id 
	  );
	  //wcuf_var_dump($current_screen->id);
	  if (in_array($current_screen->id, $page_ids)) 
	  {
		global $wcuf_wpml_helper;
		$wcuf_wpml_helper->switch_to_default_language();
		add_filter('acf/settings/current_language', array(&$this, 'cl_acf_set_language'), 100);
	  }
	}
	

	function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
}
?>