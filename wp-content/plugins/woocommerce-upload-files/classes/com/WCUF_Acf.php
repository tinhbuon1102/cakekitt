<?php 
$wcuf_active_plugins = get_option('active_plugins');
$wcuf_acf_pro = 'advanced-custom-fields-pro/acf.php';
$wcuf_acf_pro_is_aleady_active = in_array($wcuf_acf_pro, $wcuf_active_plugins) || class_exists('acf') ? true : false;
if(!$wcuf_acf_pro_is_aleady_active)
	include_once( WCUF_PLUGIN_ABS_PATH . '/classes/acf/acf.php' );

$wcuf_hide_menu = true;
if ( ! function_exists( 'is_plugin_active' ) ) 
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
}
/* Checks to see if the acf pro plugin is activated  */
if ( is_plugin_active('advanced-custom-fields-pro/acf.php') || is_plugin_active('advanced-custom-fields-pro-master/acf.php'))  {
	$wcuf_hide_menu = false;
}

/* Checks to see if the acf plugin is activated  */
if ( is_plugin_active('advanced-custom-fields/acf.php') ) 
{
	add_action('plugins_loaded', 'wcuf_load_acf_standard_last', 10, 2 ); //activated_plugin
	add_action('deactivated_plugin', 'wcuf_detect_plugin_deactivation', 10, 2 ); //activated_plugin
	$wcuf_hide_menu = false;
}
function wcuf_detect_plugin_deactivation(  $plugin, $network_activation ) { //after
   // $plugin == 'advanced-custom-fields/acf.php'
	//wcuf_var_dump("wcuf_detect_plugin_deactivation");
	$acf_standard = 'advanced-custom-fields/acf.php';
	if($plugin == $acf_standard)
	{
		$active_plugins = get_option('active_plugins');
		$this_plugin_key = array_keys($active_plugins, $acf_standard);
		if (!empty($this_plugin_key)) 
		{
			foreach($this_plugin_key as $index)
				unset($active_plugins[$index]);
			update_option('active_plugins', $active_plugins);
			//forcing
			deactivate_plugins( plugin_basename( WP_PLUGIN_DIR.'/advanced-custom-fields/acf.php') );
		}
	}
} 
function wcuf_load_acf_standard_last($plugin, $network_activation = null) { //before
	$acf_standard = 'advanced-custom-fields/acf.php';
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_keys($active_plugins, $acf_standard);
	if (!empty($this_plugin_key)) 
	{ 
		foreach($this_plugin_key as $index)
			//array_splice($active_plugins, $index, 1);
			unset($active_plugins[$index]);
		//array_unshift($active_plugins, $acf_standard); //first
		array_push($active_plugins, $acf_standard); //last
		update_option('active_plugins', $active_plugins);
	} 
}


if(!$wcuf_acf_pro_is_aleady_active)
	add_filter('acf/settings/path', 'wcuf_acf_settings_path');
function wcuf_acf_settings_path( $path ) 
{
 
    // update path
    $path = WCUF_PLUGIN_ABS_PATH. '/classes/acf/';
    
    // return
    return $path;
    
}
if(!$wcuf_acf_pro_is_aleady_active)
	add_filter('acf/settings/dir', 'wcuf_acf_settings_dir');
function wcuf_acf_settings_dir( $dir ) {
 
    // update path
    $dir = wcuf_PLUGIN_PATH . '/classes/acf/';
    
    // return
    return $dir;
    
}

function wcuf_acf_init() {
    
    include WCUF_PLUGIN_ABS_PATH . "/assets/fields.php";
    
}
add_action('acf/init', 'wcuf_acf_init');

//hide acf menu
if($wcuf_hide_menu)	
	add_filter('acf/settings/show_admin', '__return_false');


//Auth key reset managment
function wcuf_acf_save_settings( $post_id ) {
    
    $wcuf_storage_service = get_field('wcuf_cloud_storage_service', 'option');
    
    if(isset($wcuf_storage_service) && $wcuf_storage_service != 'dropbox')
	{
		update_field('wcuf_dropbox_auth_key', "", 'option');
	}
		
}
add_action('acf/save_post', 'wcuf_acf_save_settings', 20);

// Custom filters
function wcuf_get_variation_complete_name($variation_id)
{
	$error = false;
	$variation = null;
	try
	{
		$variation = new WC_Product_Variation($variation_id);
	}
	catch(Exception $e){$error = true;}
	if($error) //no longer executed
		try
		{
			$error = false;
			$variation = new WC_Product($variation_id);
			return $variation->get_title();
		}catch(Exception $e){$error = true;}
	
	if($error)
		return false;
	
	$product_name = $variation->get_title()." - ";	
	if($product_name == " - " || $variation->get_title() == '')
		return false;
	
	$attributes_counter = 0;
	$attributes = "";
	
	foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
	{
		
		if($attributes_counter > 0)
			$attributes .= ", ";
		$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
		
		$attributes .= " ".wc_attribute_label($meta_key).": ".$value;
		$attributes_counter++;
	}
	if($attributes == "")
		return false;
	$product_name .= $attributes;
	
	return $product_name;
}
	
function wcuf_change_product_name( $title, $post, $field, $post_id ) 
{
    if($post->post_type == "product_variation" )
	{
		$variation_name = wcuf_get_variation_complete_name($post->ID);
		$title_temp = "#{$post->ID} - ".$variation_name;
		$title = $variation_name != false && !ctype_space($variation_name) && $variation_name != '' ? $title_temp : $title;
	}
	return $title;
}
add_filter('acf/fields/post_object/result', 'wcuf_change_product_name', 10, 4);

//Avoid custom fields metabox removed by pages
add_filter('acf/settings/remove_wp_meta_box', '__return_false');
?>