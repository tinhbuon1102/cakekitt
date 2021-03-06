<?php
/**
 * Custom product field TimePicker data object.
 *
 * @link       https://themehigh.com
 * @since      2.3.0
 *
 * @package    woocommerce-extra-product-options-pro
 * @subpackage woocommerce-extra-product-options-pro/includes/model/fields
 */
if(!defined('WPINC')){	die; }

if(!class_exists('WEPO_Product_Field_TimePicker')):

class WEPO_Product_Field_TimePicker extends WEPO_Product_Field{
	public $min_time = '';
	public $max_time = '';
	public $time_step = '';
	public $time_format = '';
	
	public function __construct() {
		$this->type = 'timepicker';
	}	
		
	/*public function get_html(){
		$price_data = $this->get_price_data();
		$input_class = $this->price_field ? 'thwepo-price-field' : '';
		$value = apply_filters( 'thwepo_product_extra_option_value_'.$this->name, $this->value );
		$value = isset($_POST[$this->name]) ? $_POST[$this->name] : $value;
		
		$field_props  = 'placeholder="'. $this->esc_html__wepo($this->placeholder) .'"';
		$field_props .= ' value="'.$value.'"';
		$field_props .= ' class="thwepo-input-field thwepo-time-picker input-text '.$input_class.'"';
		$field_props .= $price_data;
		$field_props .= ' data-min-time="'.$this->min_time.'" data-max-time="'.$this->max_time.'"';
		$field_props .= ' data-step="'.$this->time_step.'" data-format="'.$this->time_format.'"';
		
		$input_html  = '<input type="text" id="'. $this->name .'" name="'. $this->name .'" '. $field_props .' />';
		
		$html = $this->prepare_field_html($input_html);
		return $html;
	}*/
}

endif;