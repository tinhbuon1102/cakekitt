<?php
    $selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
    $params = !empty($params['params']) ? $params['params'] : NULL;

    $id = isset($params['id']) ? $params['id'] : NULL;
    if (!empty($id)) :
        $row = isset($params['row']) ? $params['row'] : 0;
        $num = isset($params['num']) ? $params['num'] : '';
        $data = !empty($params['data']) ? $params['data'] : NULL;   
        $types = array(
            'captcha' => 'Captcha',
            'captcha-numeric' => 'Numeric Captcha',
            'captcha-recaptcha' => 'reCAPTCHA',
        );
        $captchaType = !empty($data['captcha']['type']) && !is_array($data['captcha']['type']) ? $data['captcha']['type'] : 'captcha';
?>
<div class="scfp-field-extended-settings-row">
    <div class="scfp-field-extended-settings-captcha">
        <label for="<?php echo "{$id}_{$row}_captcha_type";?>">Captcha Type</label>
        <select id="<?php echo "{$id}_{$row}_captcha_type";?>" class="scfp-field-extended-settings-captcha-type widefat" name="<?php echo "{$id}[field_settings][{$row}][captcha][type]";?>">
            <?php foreach ($types as $k => $v) : ?>
            <option value="<?php echo $k;?>"<?php selected( $k==$captchaType );?>><?php echo $v;?></option>
            <?php endforeach;?>
        </select>
        <div class="scfp-field-settings-notice">
            <span class="dashicons dashicons-editor-help"></span>
            <p class="description">option allows to choose type of Captcha<span class="dashicons dashicons-no-alt"></span></p>
        </div>
    </div>
    <div class="scfp-field-extended-settings-captcha-box">
        <?php 
            echo SCFP()->getTemplate("admin/form/extended-settings/captcha/layout", array('selectedType' => $selectedType, 'params' => $params, 'captchaType' => $captchaType ));
        ?>
    </div>
</div>    
<?php
    endif;
