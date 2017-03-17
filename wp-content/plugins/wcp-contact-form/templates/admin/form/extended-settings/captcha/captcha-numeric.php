<?php
/**
 * TODO: Numeric Captcha Settings
 */

$selectedType = isset($params['selectedType']) ? $params['selectedType'] : '';
$captchaType = isset($params['captchaType']) ? $params['captchaType'] : 'captcha';
$params = !empty($params['params']) ? $params['params'] : NULL;
