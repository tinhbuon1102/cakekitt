<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $params = !empty($params['params']) ? $params['params'] : NULL;

    $id = isset($params['id']) ? $params['id'] : NULL;
    if (!empty($id)) :
        $row = isset($params['row']) ? $params['row'] : 0;
        $num = isset($params['num']) ? $params['num'] : '';
        $data = !empty($params['data']) ? $params['data'] : NULL;    
?>
<div class="scfp-field-extended-settings-row scfp-field-extended-settings-textarea">
    <label for="<?php echo "{$id}_{$row}_description";?>">Description</label>
    <textarea class="widefat" rows="6" id="<?php echo "{$id}_{$row}_description";?>" name="<?php echo "{$id}[field_settings][{$row}][description]";?>"><?php echo !empty($data['description']) && !is_array($data['description']) ? $data['description'] : '' ;?></textarea>
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span> 
        <p class="description">option allows to add text note below the field<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
