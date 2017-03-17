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
    $value = !empty( $formData[$key] ) ? $formData[$key] : ( !empty( $default ) ? SCFP()->applyFormVars($default) : '' );
?>
    <div class="scfp-form-row<?php echo !empty($css_class) ? ' '.$css_class : '';?>">
        <?php if (!empty($display_label)) : ?>
            <label class="scfp-form-label" for="scfp-<?php echo $key; ?>"><?php echo $display_label;?><?php if ( !empty( $required ) ) : ?> <span class="scfp-form-field-required">*</span><?php endif;?></label>
        <?php endif;?>
        
        <textarea<?php if (!empty($placeholder)):?> placeholder="<?php echo $placeholder;?>"<?php endif;?> <?php echo $atts;?> class="scfp-form-field scfp-form-message-field" id="scfp-<?php echo $key; ?>" name="scfp-<?php echo $key;?>"<?php if ( !empty( $required ) && !empty($formSettings['html5_enabled']) ) : ?> required <?php endif;?>><?php echo $value; ?></textarea>        
        
        <?php if (!empty($description)) : ?>
            <div class="scfp-form-row-description"><?php echo $description;?></div>
        <?php endif;?>        
    </div>
<?php 
endif;
