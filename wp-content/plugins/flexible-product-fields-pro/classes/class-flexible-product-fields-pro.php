<?php

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FPF_PRO {

    private $plugin = null;

    public function __construct(WPDesk_Plugin_1_2 $plugin) {
	    $this->plugin = $plugin;
	    $this->hooks();
    }

    public function hooks() {
    	add_filter( 'flexible_product_fields_sort_groups_posts', array( $this, 'flexible_product_fields_sort_groups_posts' ) );
	    add_filter( 'flexible_product_fields_field_types', array( $this, 'flexible_product_fields_field_types' ) );
	    add_filter( 'flexible_product_fields_assign_to_options', array( $this, 'flexible_product_fields_assign_to_options' ), 11 );

	    add_filter( 'woocommerce_form_field_fpfdate', array( $this, 'woocommerce_form_field_fpfdate' ), 999, 4 );

	    add_filter( 'flexible_product_fields_apply_logic_rules', array( $this, 'flexible_product_fields_apply_logic_rules' ), 10, 2 );
    }

    public function flexible_product_fields_apply_logic_rules( $fields, $post_data ) {
    	$unset_field = true;
    	while ( $unset_field ) {
		    $unset_field = false;
			foreach ( $fields['fields'] as $field_key => $field ) {
				$value = null;
				if ( isset( $field['logic'] ) && $field['logic'] == '1' ) {
					$show_field     = true;
					$logic_operator = $field['logic_operator'];
					if ( $logic_operator == 'or' ) {
						$show_field = false;
					}
					foreach ( $field['logic_rules'] as $field_rule ) {
						$compare = $field_rule['compare'];
						$value   = '';
						if ( isset( $post_data[ $field_rule['field'] ] ) ) {
							$value = $post_data[ $field_rule['field'] ];
						}
						if ( $field_rule['field_value'] == 'checked' && $value != '' ) {
							$value = 'checked';
						}
						if ( $field_rule['field_value'] == 'unchecked' && $value == '' ) {
							$value = 'unchecked';
						}
						$field_is_visible = false;
						foreach ( $fields['fields'] as $visible_field ) {
							if ( $visible_field['id'] == $field_rule['field'] ) {
								$field_is_visible = true;
							}
						}
						if ( ! $field_is_visible ) {
							$value = $field_rule['field_value'] . '1';
						}
						$compare_result = $field_rule['field_value'] == $value;
						if ( $compare == 'is_not' ) {
							$compare_result = ! $compare_result;
						}
						if ( $logic_operator == 'or' ) {
							$show_field = $show_field || $compare_result;
						}
						if ( $logic_operator == 'and' ) {
							$show_field = $show_field && $compare_result;
						}
					}
					if ( ! $show_field ) {
						$unset_field = true;
						unset( $fields['fields'][ $field_key ] );
					}
				}
			}
		}
		return $fields;
    }

	public function woocommerce_form_field_fpfdate( $no_parameter, $key, $args, $value ) {
		$required = '';
		if($args['required'] == true){
			$required = '<abbr class="required" title="'. __( 'Required Field', 'flexible-checkout-fields-pro' ).'">*</abbr>';
		}
		$field = '
                <p class="form-row form-datepicker ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field">
                    <label for="'.$key.'">'.$args['label'].' '.$required.'</label>
                    <input type="text" class="' . esc_attr( implode( ' ', $args['input_class'] ) ) . ' input-text load-datepicker" name="' . $key . '" id="' . $key .
		         '" placeholder="' . $args['placeholder'] . '" value="'. $value .
		         '" date_format="' . $args['custom_attributes']['date_format'] .
		         '" days_before="' . ( !empty( $args['custom_attributes']['days_before'] ) ? $args['custom_attributes']['days_before'] : '' ) .
		         '" days_after="' . ( !empty( $args['custom_attributes']['days_after'] ) ? $args['custom_attributes']['days_after'] : '' ) . '" />
                </p>
            ';
		return $field;
	}


	/**
	 * @param $a WP_Post
	 * @param $b WP_Post
	 */
	public function sort_groups_posts_cmp( $a, $b ) {
		return $a->menu_order > $b->menu_order;
	}

    public function flexible_product_fields_sort_groups_posts( $posts ) {
		if ( !is_array( $posts ) ) {
			return $posts;
		}
	    usort( $posts, array( $this, 'sort_groups_posts_cmp' ) );
	    return $posts;
    }

    public function flexible_product_fields_field_types( array $field_types ) {
        foreach ( $field_types as $key => $field_type ) {
            if ( $field_type['value'] == 'text'
                 || $field_type['value'] == 'textarea'
                 || $field_type['value'] == 'checkbox'
                 || $field_type['value'] == 'fpfdate'
            ) {
                $field_types[$key]['has_price'] = true;
	            $field_types[$key]['price_not_available'] = false;
            }
	        if ( $field_type['value'] == 'select'
	             || $field_type['value'] == 'radio'
	        ) {
		        $field_types[$key]['has_price_in_options'] = true;
		        $field_types[$key]['price_not_available_in_options'] = false;
	        }
	        $field_types[$key]['has_logic'] = true;
	        $field_types[$key]['logic_not_available'] = false;
        }
	    $field_types['heading'] = array(
		    'value'                 => 'heading',
		    'label'                 => __( 'Heading', 'flexible-product-fields-pro' ),
		    'has_max_length'        => false,
            'has_required'          => false,
		    'has_options'           => false,
            'has_price'             => false,
		    'price_not_available'   => false,
            'has_placeholder'       => false,
		    'has_value'             => false,
		    'is_available'          => true,
		    'has_logic'             => true,
		    'logic_not_available'   => false,
	    );
	    $field_types['fpfdate'] = array(
		    'value'                 => 'fpfdate',
		    'label'                 => __( 'Date', 'flexible-product-fields-pro' ),
		    'has_max_length'        => false,
		    'has_required'          => true,
		    'has_options'           => false,
		    'has_price'             => true,
		    'price_not_available'   => false,
		    'has_placeholder'       => true,
		    'has_value'             => false,
		    'is_available'          => true,
		    'has_logic'             => true,
		    'logic_not_available'   => false,
	    );
	    return $field_types;
    }

    public function flexible_product_fields_assign_to_options( $fpf_assign_to_options ) {
	    $fpf_assign_to_options = array(
		    array( 'value' => 'product', 'label' => __( 'Product', 'flexible-product-fields' ), 'is_available' => true ),
		    array( 'value' => 'category', 'label' => __( 'Category', 'flexible-product-fields-pro' ), 'is_available' => true ),
		    array( 'value' => 'all', 'label' => __( 'All products', 'flexible-product-fields' ), 'is_available' => true ),
	    );
	    return $fpf_assign_to_options;
    }

}
