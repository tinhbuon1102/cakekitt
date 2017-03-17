<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $enabled = !empty($params['enabled']) && SCFP()->getSettings()->hasExtendedSettings($selectedType);    
    $params = !empty($params['params']) ? $params['params'] : NULL;
?>
<div class="scfp-field-extended-settings <?php echo !$enabled ? ' disabled': '';?>"<?php if (!$enabled):?> style="display: none;"<?php endif;?> data-params="<?php echo esc_attr( serialize($params) );?>">
    <div class="scfp-field-extended-settings-inner-title">
        <p>Advanced Field Options</p>
    </div> 
    <div class="scfp-field-extended-settings-inner">           
        <?php
            $templates = SCFP()->getSettings()->getFieldExtendedSettings();
            if ( !empty($templates[$selectedType]) && is_array($templates[$selectedType]) ) :
                foreach ($templates[$selectedType] as $template) :
                    if (!is_array($template)) :
                        echo SCFP()->getTemplate("admin/form/extended-settings/{$template}", array('selectedType' => $selectedType, 'params' => $params ));    
                    else:
                        $cnt = count($template);
                        if ($cnt > 0) :
                            $width = round( 100 / $cnt, 2 );
                        ?>   
                            <div class="scfp-field-extended-multi-columns">
                                <?php
                                    foreach ($template as $multi_column_item) :
                                ?>   
                                <div class="scfp-field-extended-multi-columns-col" style="width: <?php echo $width;?>%;">
                                    <?php
                                        foreach ($multi_column_item as $multi_column_template) :
                                            if (!is_array($multi_column_template)) :
                                                echo SCFP()->getTemplate("admin/form/extended-settings/{$multi_column_template}", array('selectedType' => $selectedType, 'params' => $params ));    
                                            endif;    
                                        endforeach;
                                    ?>                                                        
                                </div>
                                <?php
                                    endforeach;
                                ?>                        
                            </div>  
                        <?php    
                        endif;
                    endif;
                endforeach;
            endif;
        ?>
    </div>    
</div>    
