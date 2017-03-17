<?php
$captcha = !empty($params['field']['captcha']['type']) ? $params['field']['captcha']['type'] : NULL;
if (!empty($captcha)) {
    echo SCFP()->getTemplate("form/captcha/{$captcha}", array( 'params' => $params ) );
}
