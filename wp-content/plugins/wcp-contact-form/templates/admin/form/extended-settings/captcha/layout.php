<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $captchaType = isset($params['captchaType']) ? $params['captchaType'] : 'captcha';    
    $params = !empty($params['params']) ? $params['params'] : NULL;
?>
<div class="scfp-field-extended-settings-captcha-settings scfp-field-extended-settings-captcha-settings-<?php echo $captchaType; ?>">
    <?php 
        echo SCFP()->getTemplate("admin/form/extended-settings/captcha/{$captchaType}", array('selectedType' => $selectedType, 'params' => $params, 'captchaType' => $captchaType ));
    ?>
</div>