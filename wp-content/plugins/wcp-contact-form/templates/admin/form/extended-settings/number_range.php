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
    <div class="scfp-field-extended-multi-columns">
        <div class="scfp-field-extended-multi-columns-col" style="width: 50%;">
            <label for="<?php echo "{$id}_{$row}_min";?>">Min Value</label>
            <input class="widefat" type="number" value="<?php echo !empty($data['min']) && !is_array($data['min']) ? $data['min'] : '' ;?>" id="<?php echo "{$id}_{$row}_min";?>" name="<?php echo "{$id}[field_settings][{$row}][min]";?>" />        
        </div>
        <div class="scfp-field-extended-multi-columns-col" style="width: 50%;">
            <label for="<?php echo "{$id}_{$row}_max";?>">Max Value</label>
            <input class="widefat" type="number" value="<?php echo !empty($data['max']) && !is_array($data['max']) ? $data['max'] : '' ;?>" id="<?php echo "{$id}_{$row}_max";?>" name="<?php echo "{$id}[field_settings][{$row}][max]";?>" />        
        </div>        
    </div>
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span>
        <p class="description">option allows to create a range of legal values in case if you use the "min" value together with the "max" value. <br />The "min" value specifies the minimum value for the field. The "max" value specifies the maximum value for the field.<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
