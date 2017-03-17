<?php 
    $args = $params;
    $label = !empty($args->fields['fields'][$args->field]['label']) ? $args->fields['fields'][$args->field]['label'] : '';
    $class = !empty($args->fields['fields'][$args->field]['class']) ? $args->fields['fields'][$args->field]['class'] : ''; 
    $note = !empty($args->fields['fields'][$args->field]['note']) ? $args->fields['fields'][$args->field]['note'] : '';
    $atts = !empty($args->fields['fields'][$args->field]['atts']) ? $args->fields['fields'][$args->field]['atts'] : '';
    if (is_array($atts)) {
        $atts_s = '';
        foreach ($atts as $key => $value) {
            $atts_s .= $key . '="' . $value . '"';
        }
        $atts = $atts_s;
    }
    
    $list = $args->fieldSet[$args->fields['fields'][$args->field]['fieldSet']];
?>
<tr>
    <th scope="row"><?php echo $label;?></th>
    <td>
        <div class="scfp-field-settings-row scfp-field-settings-row-checkboxes">
            <div class="scfp-field-settings-row-checkboxes-wrapper">
            <?php 
                foreach( $list as $k => $v ):
                    $checked = !empty($args->data[$args->field]) && array_key_exists($k, $args->data[$args->field]);
            ?>
            <div class="scfp-field-settings-row-checkboxes-item">
                <input <?php echo $atts;?><?php echo !empty($class) ? ' class="'.$class.'"': '';?> type="checkbox" id="<?php echo "{$args->key}[{$args->field}][{$k}]"; ?>" name="<?php echo "{$args->key}[{$args->field}][{$k}]"; ?>" <?php checked( $checked ); ?>>                                
                <label for="<?php echo "{$args->key}[{$args->field}][{$k}]"; ?>"><?php echo $v;?></label>
            </div>    
            <?php 
                endforeach; 
            ?>
            </div>
            <?php if (!empty($note)): ?>
            <div class="scfp-field-settings-notice">
                <span class="dashicons dashicons-editor-help"></span>
                <p class="description"><?php echo $note;?><span class="dashicons dashicons-no-alt"></span></p>
            </div>     
            <?php endif;?>
        </div>
    </td>
</tr>    