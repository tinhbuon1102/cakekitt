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
    <label for="<?php echo "{$id}_{$row}_default";?>">Default Value</label>
    <input class="widefat" type="number" value="<?php echo !empty($data['default']) && !is_array($data['default']) ? $data['default'] : '' ;?>" id="<?php echo "{$id}_{$row}_default";?>" name="<?php echo "{$id}[field_settings][{$row}][default]";?>" />        
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span>
        <p class="description">option allows to specify default field value<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
