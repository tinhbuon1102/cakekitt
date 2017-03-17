<?php
$id = !empty($params['id']) ? $params['id'] : NULL;
$form = !empty($params['form']) ? $params['form'] : NULL;
$key = !empty($params['key']) ? $params['key'] : NULL;
$field = !empty($params['field']) ? $params['field'] : NULL;
if (!empty($field) && is_array($field)) {
    extract($field);
}
$formSettings = !empty($params['formSettings']) ? $params['formSettings'] : NULL;
$formData = !empty($params['formData']) ? $params['formData'] : NULL;
$atts = SCFP()->compactAtts( !empty($field['atts']) ? $field['atts'] : '' );

if (!empty($key)) :
    if (!empty($display_label)) {
        SCFP()->getSettings()->unescape($display_label);    
    }
    $selected_values = SCFP()->getChoicesSelected($choices, $formData[$key]);
    $choices_list = SCFP()->getChoicesList($choices);
    $multiselect = !empty($choices['multiselect']);
?>
    <div class="scfp-form-row scfp-form-row-dropdown<?php echo !empty($css_class) ? ' '.$css_class : '';?>">
        <?php if (!empty($display_label)) : ?>
            <label class="scfp-form-label" for="scfp-<?php echo $key; ?>"><?php echo $display_label;?><?php if ( !empty( $required ) ) : ?> <span class="scfp-form-field-required">*</span><?php endif;?></label>
        <?php endif;?>            
        
        <?php if (!empty($choices_list)) : ?>
            <div class="scfp-form-row-dropdown-wrapper"> 
                <?php if (empty($multiselect)) : ?>                
                <div class="scfp-dropdown-arrow">
                    <div class="scfp-dropdown-arrow-inner">
                        <div class="scfp-dropdown-arrow-inner-icon">
                            <span class="scfp-icon-dwopdown-arrow"></span>
                        </div>    
                    </div>    
                </div>             
                <?php endif; ?>
                <select <?php echo $atts;?> id="scfp-<?php echo $key; ?>" class="scfp-form-field scfp-dropdown-field" name="scfp-<?php echo $key; ?>[]" <?php if ( !empty( $required ) && !empty($formSettings['html5_enabled']) ) : ?> required="required" <?php endif;?><?php if (!empty($multiselect)) :?> multiple="multiple"<?php endif;?>>          
    
                <?php if (!empty($placeholder) ):?>                
                    <option value="" <?php selected( empty($selected_values) );?>><?php echo $placeholder;?></option>
                <?php endif; ?>    
                    
                <?php    
                    foreach ($choices_list as $k => $v) :
                        $vue = $v;
                        SCFP()->getSettings()->unescape($vue);    
                ?>  
                    <option value="<?php echo $k;?>" <?php selected( in_array($k, $selected_values) );?>><?php echo $vue;?></option>    
                <?php            
                    endforeach;
                ?>      
                </select>
            </div>    
        <?php endif; ?>

        <?php if (!empty($description)) : ?>
            <div class="scfp-form-row-description"><?php echo $description;?></div>
        <?php endif;?>        
    </div>
<?php 
endif;
