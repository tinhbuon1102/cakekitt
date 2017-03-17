<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $params = !empty($params['params']) ? $params['params'] : NULL;
    echo SCFP()->getTemplate("admin/form/extended-settings/common/choices", array('selectedType' => $selectedType, 'params' => $params, 'multiselect' => TRUE, 'showAllowMultiselect' => FALSE ));    