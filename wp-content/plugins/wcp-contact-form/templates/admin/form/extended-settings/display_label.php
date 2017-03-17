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
    <label for="<?php echo "{$id}_{$row}_display_label";?>">Display Label</label>
    <input class="widefat" type="text" value="<?php echo !empty($data['display_label']) && !is_array($data['display_label']) ? $data['display_label'] : '' ;?>" id="<?php echo "{$id}_{$row}_display_label";?>" name="<?php echo "{$id}[field_settings][{$row}][display_label]";?>" />        
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span>
        <p class="description">option allows to define field label that will be displayed on the site frontend for visitors<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
