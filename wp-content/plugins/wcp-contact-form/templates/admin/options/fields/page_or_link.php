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
    
    
    
    $value = $args->data[$args->field];

    $mode = isset($value['mode']) ? $value['mode'] : 'page';
    $page = isset($value['page']) ? $value['page'] : $value;
    $url = isset($value['url']) ? $value['url'] : '';
    
    $list = $args->fieldSet[$args->fields['fields'][$args->field]['fieldSet']];
    $modes = array(
        'page' => 'Page',
        'url' => 'URL',
    );
?>
<tr>
    <th scope="row"><?php echo $label;?></th>
    <td>
        <div class="scfp-field-settings-row scfp-field-settings-row-redirect">
            <div <?php echo $atts;?><?php echo !empty($class) ? ' class="'.$class.'"': '';?>>
                <select class="scfp-mode widefat regular-select" id="<?php echo "{$args->key}[{$args->field}][mode]"; ?>" name="<?php echo "{$args->key}[{$args->field}][mode]"; ?>">
                    <?php 
                        foreach( $modes as $k => $v ):
                            $selected = !empty($mode) && $mode == $k;
                    ?>
                            <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
                    <?php 
                        endforeach; 
                    ?>
                </select>            
                <select class="scfp-mode-item scfp-mode-page widefat regular-select" id="<?php echo "{$args->key}[{$args->field}][page]"; ?>" name="<?php echo "{$args->key}[{$args->field}][page]"; ?>" <?php echo $mode != 'page' ? 'style="display: none;"' : ''; ?> >
                    <?php 
                        foreach( $list as $k => $v ):
                            $selected = !empty($page) && $page == $k;
                    ?>
                            <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
                    <?php 
                        endforeach; 
                    ?>
                </select>
                <input placeholder="http://" class="scfp-mode-item scfp-mode-url widefat regular-text" type="text" id="<?php echo "{$args->key}[{$args->field}][url]"; ?>" name="<?php echo "{$args->key}[{$args->field}][url]"; ?>" value="<?php echo $url;?>" <?php echo $mode != 'url' ? 'style="display: none;"' : ''; ?>>                            
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
