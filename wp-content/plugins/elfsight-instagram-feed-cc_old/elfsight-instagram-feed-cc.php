<?php
/*
Plugin Name: Elfsight Instagram Feed CC
Description: Add Instagram images to your website to engage your visitors.
Plugin URI: https://elfsight.com/instagram-feed-plugin/wordpress/?utm_source=markets&utm_medium=codecanyon&utm_campaign=instagram-feed&utm_content=plugin-site
Version: 3.0.0
Author: Elfsight
Author URI: https://elfsight.com/?utm_source=markets&utm_medium=codecanyon&utm_campaign=instagram-feed&utm_content=plugins-list
*/

if (!defined('ABSPATH')) exit;


require_once('core/elfsight-plugin.php');

$elfsight_instagram_feed_config_path = plugin_dir_path(__FILE__) . 'config.json';
$elfsight_instagram_feed_settings = json_decode(file_get_contents($elfsight_instagram_feed_config_path), true);

if (is_array($elfsight_instagram_feed_settings) && is_array($elfsight_instagram_feed_settings['properties'])) {
	array_push($elfsight_instagram_feed_settings['properties'], array(
		'id' => 'api',
		'name' => 'Custom API Url',
		'tab' => 'more',
		'type' => 'hidden',
		'defaultValue' => str_replace('elfsight-instashow', 'elfsight-instagram-feed-cc', get_option('elfsight_instashow_custom_api_url', plugin_dir_url(__FILE__) . 'api/index.php'))
	));
}


// instashow compatibility: rename widgets table
function elfsight_instagram_feed_activation() {
	global $wpdb;

	$instagram_feed_table_name = $wpdb->prefix . 'elfsight_instagram_feed_widgets';
	$instashow_table_name = $wpdb->prefix . 'elfsight_instashow_widgets';

	$instagram_feed_table_exist = !!$wpdb->get_var('SHOW TABLES LIKE "' . $instagram_feed_table_name . '"');
	$instashow_table_exist = !!$wpdb->get_var('SHOW TABLES LIKE "' . $instashow_table_name . '"');

	if (!$instagram_feed_table_exist && $instashow_table_exist) {
	    $wpdb->query('RENAME TABLE ' . $instashow_table_name . ' TO ' . $instagram_feed_table_name . ';');

	    // instashow compatibility: prepare widgets options to correct json
	    $select_sql = 'SELECT * FROM ' . $instagram_feed_table_name . ';';
	    $list = $wpdb->get_results($select_sql, ARRAY_A);

	    foreach ($list as &$widget) {
			$options = json_decode($widget['options'], true);

			if (isset($options['limit'])) {
				$options['limit'] = intval($options['limit']);
			}

			if (isset($options['cacheMediaTime'])) {
				$options['cacheMediaTime'] = intval($options['cacheMediaTime']);
			}

			if (isset($options['columns'])) {
				$options['columns'] = intval($options['columns']);
			}

			if (isset($options['rows'])) {
				$options['rows'] = intval($options['rows']);
			}

			if (isset($options['gutter'])) {
				$options['gutter'] = intval($options['gutter']);
			}

			if (isset($options['auto'])) {
				$options['auto'] = intval($options['auto']);
			}

			if (isset($options['speed'])) {
				$options['speed'] = intval($options['speed']);
			}

			if (!empty($options['responsive']) && !is_array($options['responsive'])) {
				$options['responsive'] = json_decode(rawurldecode($options['responsive']), true);

				$responsive_arr = array();

				foreach ($options['responsive'] as $r_key => $r_val) {
					$responsive_item = array();

					$responsive_item['minWidth'] = intval($r_key);

					if (isset($r_val['columns'])) {
						$responsive_item['columns'] = intval($r_val['columns']);
					}

					if (isset($r_val['rows'])) {
						$responsive_item['rows'] = intval($r_val['rows']);
					}

					if (isset($r_val['gutter'])) {
						$responsive_item['gutter'] = intval($r_val['gutter']);
					}

					$responsive_arr[] = $responsive_item;
				}

				$options['responsive'] = $responsive_arr;
			}

	        $wpdb->update($instagram_feed_table_name, array('options' => json_encode($options)), array('id' => $widget['id']));
	  	}
  	}
}
register_activation_hook(__FILE__, 'elfsight_instagram_feed_activation');


$elfsightInstagramFeed = new ElfsightPlugin(
	array(
		'name' => 'Instagram Feed',
		'description' => 'Add Instagram images to your website to engage your visitors',
		'slug' => 'elfsight-instagram-feed',
		'version' => '3.0.0',
		'text_domain' => 'elfsight-instagram-feed',
		'editor_settings' => $elfsight_instagram_feed_settings,
		'editor_preferences' => array(
			'previewUpdateTimeout' => 300
		),
		'script_url' => plugins_url('assets/elfsight-instagram-feed.js', __FILE__),

		'plugin_name' => 'Elfsight Instagram Feed',
		'plugin_file' => __FILE__,
		'plugin_slug' => plugin_basename(__FILE__),

		'vc_icon' => plugins_url('assets/img/vc-icon.png', __FILE__),

		'menu_icon' => plugins_url('assets/img/menu-icon.png', __FILE__),
		'update_url' => 'https://a.elfsight.com/updates/',

		'preview_url' => plugins_url('preview/', __FILE__),
		'observer_url' => plugins_url('preview/instagram-feed-observer.js', __FILE__),

		'product_url' => 'https://codecanyon.net/item/instagram-feed-wordpress-gallery-for-instagram/13004086?ref=Elfsight',
		'support_url' => 'https://elfsight.ticksy.com/submit/#100003625'
	)
);

add_shortcode('instashow', array($elfsightInstagramFeed, 'addShortcode'));

?>
