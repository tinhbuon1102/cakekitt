<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $multiselect = isset($params['multiselect']) ? $params['multiselect'] : TRUE;
    $showAllowMultiselect = isset($params['showAllowMultiselect']) ? $params['showAllowMultiselect'] : TRUE;
    $params = !empty($params['params']) ? $params['params'] : NULL;

    $id = isset($params['id']) ? $params['id'] : NULL;
    if (!empty($id)) :
        $row = isset($params['row']) ? $params['row'] : 0;
        $num = isset($params['num']) ? $params['num'] : '';
        $data = !empty($params['data']) ? $params['data'] : NULL;   
        $modes = array(
            'list' => 'Manual Input',
            'callback' => 'Callback Function (for developers)',
        );
        $selected_mode = !empty($data['choices']['mode']) && !is_array($data['choices']['mode']) ? $data['choices']['mode'] : 'list';
        $edit_values = !empty($data['choices']['edit_values']) && !is_array($data['choices']['edit_values']) ? TRUE : FALSE;
        
        if ($showAllowMultiselect) {
            $multiselect = !empty($data['choices']['multiselect']) ? 1 : 0;    
        }
?>
<div class="scfp-field-extended-settings-row">
    <div class="scfp-field-extended-settings-choices">
        <label for="<?php echo "{$id}_{$row}_choices_mode";?>">Choices</label>
        <select id="<?php echo "{$id}_{$row}_choices_mode";?>" class="scfp-field-extended-settings-choices-mode widefat" name="<?php echo "{$id}[field_settings][{$row}][choices][mode]";?>">
            <?php foreach ($modes as $k => $v) : ?>
            <option value="<?php echo $k;?>"<?php selected( $k==$selected_mode );?>><?php echo $v;?></option>
            <?php endforeach;?>
        </select>
        <div class="scfp-field-settings-notice">
            <span class="dashicons dashicons-editor-help"></span>
            <p class="description">option allows to select method of getting dataset for choices element</p>
        </div>
    </div>
    <div class="scfp-field-extended-settings-choices-box">
        <div class="scfp-field-extended-settings-choices-item scfp-field-extended-settings-choices-callback"<?php echo $selected_mode != 'callback' ? ' style="display: none;"' : '';?>>
            <label for="<?php echo "{$id}_{$row}_choices_callback";?>">Callback</label>
            <input class="widefat" type="text" value="<?php echo !empty($data['choices']['callback']) && !is_array($data['choices']['callback']) ? $data['choices']['callback'] : '' ;?>" id="<?php echo "{$id}_{$row}_choices_callback";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][callback]";?>" />        
            <div class="scfp-field-settings-notice">
                <span class="dashicons dashicons-editor-help"></span>
                <p class="description">option allows to specify PHP callback function that returns dataset array (for developers only)<span class="dashicons dashicons-no-alt"></span></p>
            </div>            
        </div>
        <div class="scfp-field-extended-settings-choices-item scfp-field-extended-settings-choices-list"<?php echo $selected_mode != 'list' ? ' style="display: none;"' : '';?>>
            <div class="scfp-field-extended-settings-choices-title-action">
                <label for="<?php echo "{$id}_{$row}_choices_list";?>">Manual</label>   
                <div class="scfp-field-extended-settings-choices-list-box-checkbox">
                    <input class="widefat extended-settings-choices-list-edit-values" type="checkbox" <?php checked( !empty($data['choices']['edit_values']) && !is_array($data['choices']['edit_values']) );?>  value="1" id="<?php echo "{$id}_{$row}_choices_edit_values";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][edit_values]";?>" />        
                    <label for="<?php echo "{$id}_{$row}_choices_edit_values";?>">Edit Values</label>
                </div>
                <div class="scfp-field-extended-settings-choices-list-box-checkbox"<?php if (!$showAllowMultiselect):?> style="display: none;"<?php endif;?>>
                    <input class="widefat extended-settings-choices-list-multiselect" type="checkbox" <?php checked( $multiselect );?>  value="1" id="<?php echo "{$id}_{$row}_choices_multiselect";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][multiselect]";?>" />        
                    <label for="<?php echo "{$id}_{$row}_choices_multiselect";?>">Allow Multiselect</label>
                </div> 
            </div> 
            <div class="scfp-field-extended-settings-choices-list-box">
                <div class="scfp-field-extended-settings-choices-list-box-table">
                    <div class="scfp-field-extended-settings-choices-list-box-header scfp-settings-table-header">
                        <div class="manage-column scfp-field scfp-field-num" scope="col">Num</div>
                        <div class="manage-column scfp-field scfp-field-default" scope="col">Selected</div>                        
                        <div class="manage-column scfp-field scfp-field-value list-edit-values" scope="col"<?php if (!$edit_values): ?> style="display: none;" <?php endif;?>>Value</div>
                        <div class="manage-column scfp-field scfp-field-label" scope="col">Label</div>
                    </div>
                    <div class="scfp-field-extended-settings-choices-list-box-data">
                        <div class="scfp-field-row-template" style="display: none;">
                            <div class="scfp-field scfp-field-num">
                                <span class="scfp-field-priority scfp-order"></span>
                            </div>
                            <div class="scfp-field scfp-field-default">
                                <?php 
                                    $def_type = $multiselect ? "checkbox" : "radio";
                                ?>
                                <input class="scfp-choice-type-<?php echo $def_type;?>" data-row="<?php echo $row;?>" data-id="<?php echo $id;?>" data-key="(00)" type="<?php echo $def_type;?>" value="(00)" id="<?php echo "{$id}_{$row}_choices_list_(00)_default";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][default][]";?>" />
                            </div>                            
                            <div class="scfp-field scfp-field-value list-edit-values"<?php if (!$edit_values): ?> style="display: none;" <?php endif;?>>
                                <input class="widefat" type="text" value="" id="<?php echo "{$id}_{$row}_choices_list_(00)_value";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][list][(00)][value]";?>" />
                            </div>
                            <div class="scfp-field scfp-field-label">
                                <input class="widefat" type="text" value="" id="<?php echo "{$id}_{$row}_choices_list_(00)_label";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][list][(00)][label]";?>" />
                            </div>
                            <div class="scfp-field scfp-field-actions">
                                <a class="button extended-settings-up-row" title="Up" href="javascript:void(0);">
                                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                                </a>
                                <a class="button extended-settings-down-row" title="Down" href="javascript:void(0);">
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                </a>                            
                                <a class="button extended-settings-del-row" title="Delete" href="javascript:void(0);">
                                    <span class="dashicons dashicons-trash"></span>
                                </a>                        
                            </div>
                        </div>          
                        <?php 
                            if (empty( $data['choices']['list'])) {
                                $k = uniqid();
                                $data['choices']['list'][$k] = array(
                                    'value' => '',
                                    'label' => '',
                                );
                                
                                if (!$multiselect) {
                                    $data['choices']['default'] = array( $k );    
                                }
                            }
                            
                            $num = 0;
                            foreach ($data['choices']['list'] as $k => $item) :
                                $num++;
                                $def_type = $multiselect ? "checkbox" : "radio";
                        ?>
                            <div class="scfp-field-extended-settings-choices-list-box-data-row scfp-field-row">
                                <div class="scfp-field scfp-field-num">
                                    <span class="scfp-field-priority scfp-order"><?php echo $num;?></span>
                                </div>
                                <div class="scfp-field scfp-field-default">
                                    <input class="scfp-choice-type-<?php echo $def_type;?>" data-row="<?php echo $row;?>" data-id="<?php echo $id;?>" data-key="<?php echo $k;?>" type="<?php echo $def_type;?>" value="<?php echo $k;?>" <?php checked(!empty($data['choices']['default']) && in_array($k, $data['choices']['default']) );?> id="<?php echo "{$id}_{$row}_choices_list_{$k}_default";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][default][]";?>" />
                                </div>                                
                                <div class="scfp-field scfp-field-value list-edit-values"<?php if (!$edit_values): ?> style="display: none;" <?php endif;?>>
                                    <input class="widefat" type="text" value="<?php echo !empty($item['value']) ? $item['value'] : '' ;?>" id="<?php echo "{$id}_{$row}_choices_list_{$k}_value";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][list][{$k}][value]";?>" />
                                </div>
                                <div class="scfp-field scfp-field-label">
                                    <input class="widefat" type="text" value="<?php echo !empty($item['label']) ? $item['label'] : '' ;?>" id="<?php echo "{$id}_{$row}_choices_list_{$k}_label";?>" name="<?php echo "{$id}[field_settings][{$row}][choices][list][{$k}][label]";?>" />
                                </div>
                                <div class="scfp-field scfp-field-actions">
                                    <a class="button extended-settings-up-row" title="Up" href="javascript:void(0);">
                                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                                    </a>
                                    <a class="button extended-settings-down-row" title="Down" href="javascript:void(0);">
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </a>                                                            
                                    <a class="button extended-settings-del-row" title="Delete" href="javascript:void(0);">
                                        <span class="dashicons dashicons-trash"></span>
                                    </a>                        
                                </div>
                            </div>                    
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="scfp-field-extended-settings-choices-list-box-action">
                    <a class="button extended-settings-add-row" href="javascript:void(0);">Add New</a>
                </div>                               
            </div>
            <div class="scfp-field-settings-notice">
                <span class="dashicons dashicons-editor-help"></span>
                <p class="description">option allows to create one or several choose options<span class="dashicons dashicons-no-alt"></span></p>
            </div>
        </div>        
    </div>
</div>    
<?php
    endif;
