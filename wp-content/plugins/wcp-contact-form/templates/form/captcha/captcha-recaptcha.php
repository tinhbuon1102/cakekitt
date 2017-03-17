<?php
$params = !empty($params['params']) ? $params['params'] : $params;
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
$recaptcha = SCFP()->getSettings()->getRecaptchaSettings();
$recaptchaId = str_replace('-','_',$id) . '_' .str_replace('-','_',$key);

if (!empty($key)) :
    
    if (!empty($display_label)) {
        SCFP()->getSettings()->unescape($display_label);    
    }    
    
    if (!empty($recaptcha['rc_key'])) {
        wp_enqueue_script( 'scfp-recaptcha' );         
        wp_enqueue_script( 'scfp-recaptcha-api' );         
        SCFP()->registerRcWidget($key, $recaptchaId, $recaptcha);
    }
?>    
    <div class="scfp-form-row<?php echo !empty($css_class) ? ' '.$css_class : '';?>" id="rcwidget_row_<?php echo $recaptchaId; ?>">
        <?php if (!empty($display_label)) : ?>
            <label class="scfp-form-label" for="scfp-<?php echo $key; ?>"><?php echo $display_label;?><?php if ( !empty( $required ) ) : ?> <span class="scfp-form-field-required">*</span><?php endif;?></label>
        <?php endif;?>
        
        <div class="scfp-recaptcha scfp-theme-<?php echo $recaptcha['rc_theme'];?> scfp-type-<?php echo $recaptcha['rc_type'];?> scfp-size-<?php echo $recaptcha['rc_size'];?>">                                
            <?php if (!empty($recaptcha['rc_key'])) :?>
            <div id="rcwidget_<?php echo $recaptchaId; ?>" class="scfp-recaptcha-container"></div>
            <input id="scfp-<?php echo $key; ?>" name="scfp-<?php echo $key; ?>" type="hidden" class="scfp-rcwidget-response">
            <?php else:?>
            <div class="rcwidget-noconfig">
                reCAPTCHA is not properly configured. You need to configure reCAPTCHA settings on the plugin <a href="<?php echo admin_url('admin.php?page=scfp_plugin_options&tab=scfp_recaptcha_settings');?>" title="reCAPTCHA Settings" target="_blank">settings page</a>
            </div>            
            <?php endif;?>
        </div>       
            
        <?php if (!empty($description)) : ?>
            <div class="scfp-form-row-description"><?php echo $description;?></div>
        <?php endif;?>             
    </div>
<?php 
endif;
