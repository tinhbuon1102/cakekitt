<?php
/**
 * Product Field - Input Text
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WEPOF_Product_Field_InputText')):
class WEPOF_Product_Field_InputText{
	public $order = '';
	public $type = '';
	public $id   = '';
	public $name = '';	

	public $value = '';
	public $placeholder = '';
	public $validator = '';
	public $cssclass = '';
	public $cssclass_str = '';

	public $title = '';
	public $title_class = '';
	public $title_class_str = '';
	public $title_position = 'default';
	
	public $required = false;
	public $enabled  = true;

	public $position = 'woo_before_add_to_cart_button';
	
	public $conditional_rules_json = '';
	public $conditional_rules = array();

	public function __construct() {
		$this->type = 'inputtext';
	}	

	public function is_valid(){
		if(empty($this->name) || empty($this->type)){
			return false;
		}
		return true;
	}
	
	public function show_field($product, $categories){
		$show = true;
		$conditional_rules = $this->get_conditional_rules();
		if(!empty($conditional_rules)){
			foreach($conditional_rules as $conditional_rule){				
				if(!$conditional_rule->is_satisfied($product, $categories)){
					$show = false;
				}
			}
		}
		return $show;
	}

	public function get_html(){
		$html = '';
		if($this->is_enabled()){
			$value = isset($_POST[$this->get_name()]) ? $_POST[$this->get_name()] : $this->get_value();
			
			/*if($this->get_title_position() === 'left'){
				$html .= '<table class="variations_l '. $this->get_cssclass_str() .'" cellspacing="0"><tbody><tr>';
				$html .= '<td class="label"><label for="'. $this->get_name() .'" class="'. $this->get_title_class_str() .'">'.$this->get_title().'</label></td>';
				$html .= '<td class="value">';
				$html .= '<input type="text" id="'.$this->get_name().'" name="'.$this->get_name().'" placeholder="'.$this->get_placeholder().'" value="'.$value.'" />';
				$html .= '</td>';
				$html .= '</tr></tbody></table>';
			}else{
				$html .= '<table class="variations_d '. $this->get_cssclass_str() .'" cellspacing="0"><tbody><tr>';
				$html .= '<td class="label"><label for="'. $this->get_name() .'" class="'. $this->get_title_class_str() .'">'.$this->get_title().'</label></td>';
				$html .= '<td class="value">';
				$html .= '<input type="text" id="'.$this->get_name().'" name="'.$this->get_name().'" placeholder="'.$this->get_placeholder().'" value="'.$value.'" />';
				$html .= '</td>';
				$html .= '</tr></tbody></table>';
			}*/
			
			if ($this->get_name() == 'plate')
			{
				$extra_option = '';
				$extra_option .= '<div class="thwepo-extra-options form-row form-row-wide suboption_box disable'. $this->get_cssclass_str() .'" id="MessageOptionbox">';
				$extra_option .= '<textarea id="'.$this->get_name().'" name="'.$this->get_name().'" class="validate[required]" placeholder="'.$this->get_placeholder().'" value="'.$value.'" >'.$value.'</textarea>';
				$extra_option .= '</div>';
				
				$field_mappings = getCustomFormFieldMapping();
				
				$html .= '<div class="plate_radio">';
				$html .= '<h4 class="heading-form display-table mb-3">
							<span class="display-table-cell pl-2">' . __( 'Do you need a message plate?', 'woocommerce' ) . '</span>
						</h4>';
				$html .= '</div>'; // End plate_radio
				$html .= '<ul class="cake-message text-radio list-type">';
				foreach ($field_mappings['custom_order_msgplate']['value'] as $value => $label) {
					$html .= '<li class="m-input__radio">';
					$html .= '<input type="radio" name="custom_order_msgplate" id="custom_order_msgplate_' . $value .'" class="radio_input"  value="'.$value.'">';
					$html .= '<label for="custom_order_msgplate_'. $value.'" class="js-fixHeightChildText radio_label">
								<div class="radio_option radio_size">
									<h5 class="js-fixHeightChildTitle radio_option_caption">
										<span class="caption_wrap">'.$label.'</span>
									</h5>
								</div>
							</label>';
					$html .= $value == 'msg_yes' ? $extra_option : '';
					$html .= '</li>';
				}
				$html .= '</ul>';
			}
			else {
				$extra_option = '';
				$extra_option .= '<div class="thwepo-extra-options form-row form-row-wide'. $this->get_cssclass_str() .'">';
				$extra_option .= '<textarea id="'.$this->get_name().'" name="'.$this->get_name().'" placeholder="'.$this->get_placeholder().'" value="'.$value.'" >'.$value.'</textarea>';
				$extra_option .= '</div>';
				
				$html .= $extra_option;
			}
		}
		return $html;
	}

	public function render_field(){
		echo $this->get_html();
	}	

   /***********************************
	**** Setters & Getters - START ****
	***********************************/
	public function set_order($order){
		$this->order = $order;
	}

	public function set_type($type){
		$this->type = $type;
	}

	public function set_id($id){
		$this->id = $id;
	}

	public function set_name($name){
		$this->name = $name;
	}	

	public function set_value($value){
		$this->value = $value;
	}

	public function set_placeholder($placeholder){
		$this->placeholder = $placeholder;
	}

	public function set_validator($validator){
		$this->validator = $validator;
	}

	public function set_cssclass($cssclass){
		$this->cssclass = $cssclass;
		$this->set_cssclass_str($cssclass);
	}

	public function set_cssclass_str($cssclass){
		if(!empty($cssclass)){
			$class_arr = explode(',', $cssclass);
			$this->cssclass_str = implode(' ', $class_arr);
		}else{
			$this->cssclass_str = '';
		}
	}

	public function set_title($title){
		$this->title = $title;
	}	

	public function set_title_class($title_class){
		$this->title_class = $title_class;
		$this->set_title_class_str($title_class);
	}

	public function set_title_class_str($title_class){
		if(!empty($title_class)){
			$class_arr = explode(',', $title_class);
			$this->title_class_str = implode(' ', $class_arr);
		}else{
			$this->title_class_str = '';
		}
	}

	public function set_title_position($title_position){
		$this->title_position = $title_position;
	}

	public function set_required($required){
		$this->required = $required;
	}

	public function set_enabled($enabled){
		$this->enabled = $enabled;
	}

	public function set_position($position){
		$this->position = $position;
	}
	
	public function set_conditional_rules_json($conditional_rules_json){
		$conditional_rules_json = str_replace("'", '"', $conditional_rules_json);
		$this->conditional_rules_json = $conditional_rules_json;
	}
	public function set_conditional_rules($conditional_rules){
		$this->conditional_rules = $conditional_rules;
	}
				
	/*** Getters ***/	
	public function get_order(){
		return $this->order;
	}

	public function get_type(){
		return $this->type;
	}	

	public function get_id(){
		return $this->id;
	}	

	public function get_name(){
		return $this->name;
	}

	public function get_value(){
		return $this->value;
	}

	public function get_placeholder(){
		return $this->placeholder;
	}

	public function get_validator(){
		return $this->validator;
	}

	public function get_cssclass(){
		return $this->cssclass;
	}

	public function get_cssclass_str(){
		return $this->cssclass_str;
	}
	
	public function get_title(){
		return $this->title;
	}		

	public function get_title_class(){
		return $this->title_class;
	}

	public function get_title_class_str(){
		return $this->title_class_str;
	}

	public function get_title_position(){
		return empty($this->title_position) ? 'default' : $this->title_position;
	}

	public function is_required(){
		return $this->required;
	}	

	public function is_enabled(){
		return $this->enabled;
	}

	public function get_position(){
		return empty($this->position) ? 'woo_before_add_to_cart_button' : $this->position;
	}
	
	public function get_conditional_rules_json(){
		return $this->conditional_rules_json;
	}
	public function get_conditional_rules(){
		return $this->conditional_rules;
	}

	/*** DUMMY SETTERS & GETTERS ***/
	public function set_options_str($options_str){}
	public function set_options($options){}

	public function get_options(){ return false; }
	public function get_options_str(){ return false; }

   /***********************************
	**** Setters & Getters - END ******
	***********************************/
}
endif;