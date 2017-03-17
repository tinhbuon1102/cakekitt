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
    <label for="<?php echo "{$id}_{$row}_field_id";?>">Field Key</label>
    <input readonly="readonly" class="widefat" type="text" value="<?php echo $row;?>" id="<?php echo "{$id}_{$row}_field_id";?>"/>     
    <div class="scfp-field-settings-notice">
        <span class="dashicons dashicons-editor-help"></span>
        <p class="description">option allows to get unique field key that can be used for different development purposes<span class="dashicons dashicons-no-alt"></span></p>
    </div>
</div>
<?php
    endif;
