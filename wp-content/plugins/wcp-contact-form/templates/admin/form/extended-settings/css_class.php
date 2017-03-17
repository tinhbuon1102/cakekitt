<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $params = !empty($params['params']) ? $params['params'] : NULL;

    $id = isset($params['id']) ? $params['id'] : NULL;
    if (!empty($id)) :
        $row = isset($params['row']) ? $params['row'] : 0;
        $num = isset($params['num']) ? $params['num'] : '';
        $data = !empty($params['data']) ? $params['data'] : NULL;    
?>
<div class="scfp-field-extended-settings-row">
    <label for="<?php echo "{$id}_{$row}_css_class";?>">CSS Class</label>
    <input class="widefat" type="text" value="<?php echo !empty($data['css_class']) && !is_array($data['css_class']) ? $data['css_class'] : '' ;?>" id="<?php echo "{$id}_{$row}_css_class";?>" name="<?php echo "{$id}[field_settings][{$row}][css_class]";?>" />        
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span>    
        <p class="description">option allows to specify CSS class name that will be added to the field wrapper block and can be used for different development purposes<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
