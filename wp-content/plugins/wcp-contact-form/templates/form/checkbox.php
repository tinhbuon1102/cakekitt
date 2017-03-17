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
    $markLabel = !empty( $required ) && !empty($display_label);
    $markItem = !empty( $required ) && empty($display_label);
?>
    <div class="scfp-form-row scfp-form-row-checkbox<?php echo !empty($css_class) ? ' '.$css_class : '';?>">
        <?php if (!empty($display_label)) : ?>
            <label class="scfp-form-label" for="scfp-<?php echo $key; ?>"><?php echo $display_label;?><?php if ( $markLabel ) : ?> <span class="scfp-form-field-required">*</span><?php endif;?></label>
        <?php endif;?>
        
        <?php 
            if (!empty($choices_list)) : 
                foreach ($choices_list as $k => $v) :
                    $vue = $v;
                    SCFP()->getSettings()->unescape($vue);    
        ?>  
            <div class="scfp-form-row-checkbox-row">
                <input <?php echo $atts;?> type="checkbox" id="scfp-<?php echo $key; ?>-<?php echo $k;?>" name="scfp-<?php echo $key; ?>[]" <?php if ( !empty( $required ) && !empty($formSettings['html5_enabled']) && (count($choices_list) == 1) ) : ?> required <?php endif;?> <?php checked( in_array($k, $selected_values) );?> value="<?php echo $k;?>"/>            
                <label class="scfp-form-label" for="scfp-<?php echo $key; ?>-<?php echo $k;?>"><?php echo $vue;?><?php if ( $markItem ) : ?> <span class="scfp-form-field-required">*</span><?php endif;?></label>                            
            </div>
        <?php            
                endforeach;
            endif;
        ?>

        <?php if (!empty($description)) : ?>
            <div class="scfp-form-row-description"><?php echo $description;?></div>
        <?php endif;?>        
    </div>
<?php 
endif;
