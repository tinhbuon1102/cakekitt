<?php
/**
 * Plugin Name: WooCommerce Filter Orders by Product
 * Plugin URI: http://kowsarhossain.com/
 * Description: This plugin lets you filter the WooCommrce Orders by any specific product
 * Version: 2111.0.6
 * Author: Md. Kowsar Hossain
 * Author URI: http://kowsarhossain.com
 * Text Domain: woocommerce-filter-orders-by-product
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'WPINC' ) ) die;

class FOA_Woo_Filter_Orders_by_Product{
	private static $instance = null;

	private function __construct() {
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || !is_admin() ){
			return;
		}
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'restrict_manage_posts', array( $this, 'product_filter_in_order' ), 50  );
		add_action( 'posts_where', array( $this, 'product_filter_where' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ));
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    // Textdomain
    public function load_textdomain(){
        load_plugin_textdomain( 'woocommerce-filter-orders-by-product', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    }

	// Display dropdown
	public function product_filter_in_order(){
		global $typenow, $wpdb;

		if ( 'shop_order' != $typenow ) {
			return;
		}
	    ?>
	    <span id="order_type_filter_wrap">
		    <select name="order_type_filter" id="order_type_filter">
		    	<option value=""><?php _e('All Orders', 'woocommerce-filter-orders-by-product'); ?></option>
		    	<option value="<?php echo KITT_NORMAL_ORDER?>" <?php echo (isset($_REQUEST['order_type_filter']) && $_REQUEST['order_type_filter'] == KITT_NORMAL_ORDER) ? 'selected' : '';?>><?php _e('Normal Order', 'woocommerce-filter-orders-by-product'); ?></option>
		    	<option value="<?php echo KITT_CUSTOM_ORDER?>" <?php echo (isset($_REQUEST['order_type_filter']) && $_REQUEST['order_type_filter'] == KITT_CUSTOM_ORDER) ? 'selected' : '';?>><?php _e('Custom Order', 'woocommerce-filter-orders-by-product'); ?></option>
		    </select>
		</span>
	    <?php
	}

	// modify where clause in query
	public function product_filter_where( $where ) {
		global $wpdb;
		if (is_admin())
		{
			$where .= ' AND ID NOT IN (SELECT post_id FROM '.$wpdb->prefix . 'postmeta WHERE meta_key="is_custom_order_product") ';
		}
		
		if( is_search() ) {
			$t_posts = $wpdb->posts;
			$t_order_items = $wpdb->prefix . "woocommerce_order_items";  
			$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

			if ( isset( $_GET['order_type_filter'] ) && !empty( $_GET['order_type_filter'] ) ) {
				$order_type = intval($_GET['order_type_filter']);
				$order_where = $order_type == KITT_CUSTOM_ORDER ? " AND ". KITT_CUSTOM_ORDER ." IN " : " AND ". KITT_CUSTOM_ORDER ." NOT IN ";
				$where .= $order_where . "  
				(
					SELECT $t_order_itemmeta.meta_value 
					FROM $t_order_items LEFT JOIN $t_order_itemmeta on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id 
					WHERE $t_order_items.order_item_type='line_item' 
						AND $t_order_itemmeta.meta_key='_order_type' 
						AND $t_posts.ID=$t_order_items.order_id
				)";
			}
		}
		return $where;
	}
	// scripts_and_styles
	public function scripts_and_styles(){
		wp_enqueue_script( 'foa-fuzzy-script', plugin_dir_url( __FILE__ ).'fuzzy-dropdown.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'foa-fuzzy-styles', plugin_dir_url( __FILE__ ).'style.css' );
	}
}

FOA_Woo_Filter_Orders_by_Product::instance();
