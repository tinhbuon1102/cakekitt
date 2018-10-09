<?php
/**
 * Custom product field Hidden data object.
 *
 * @link       https://themehigh.com
 * @since      2.3.0
 *
 * @package    woocommerce-extra-product-options-pro
 * @subpackage woocommerce-extra-product-options-pro/includes/model/fields
 */
if(!defined('WPINC')){	die; }

if(!class_exists('WEPO_Product_Field_Hidden')):

class WEPO_Product_Field_Hidden extends WEPO_Product_Field{
	public function __construct() {
		$this->type = 'hidden';
	}	
		
	/*public function get_html(){
		$price_data = $this->get_price_data();
		$input_class = $this->price_field ? 'thwepo-price-field' : '';
		$value = apply_filters( 'thwepo_product_extra_option_value_'.$this->name, $this->value );
		$value = isset($_POST[$this->name]) ? $_POST[$this->name] : $value;
		
		$field_props  = 'value="'.$value.'" class="thwepo-input-field '.$input_class.'"';
		$field_props .= $price_data;
				
		$input_html  = '<input type="hidden" id="'.$this->name.'" name="'.$this->name.'" '.$field_props.' />';
		
		return $input_html;
	}*/
}

endif;