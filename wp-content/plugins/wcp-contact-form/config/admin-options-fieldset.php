<?php
return array(
    'pages' => array('SCFP_Settings', 'getPagesFieldSet'),
    'emails' => array('SCFP_Settings', 'getEmailsFieldSet'),
    'userNames' => array('SCFP_Settings', 'getNamesFieldSet'),
    'userRoles' => array('SCFP_Settings', 'getUserRolesFieldSet'),
    'formFields' => array('SCFP_Settings', 'getFormFieldSet'),
    'formTextFields' => array('SCFP_Settings', 'getFormTextFieldSet'),
    'baseFontSizes' => array(
        '12px' => '12px',
        '14px' => '14px',
        '16px' => '16px',
        '18px' => '18px',
        '20px' => '20px',
        '22px' => '22px',
        '24px' => '24px',
        '26px' => '26px',
        '28px' => '28px',
        '30px' => '30px',
        '32px' => '32px',
        '34px' => '34px',
        '36px' => '36px',
    ),    
    'fieldTypes' => array(
        'captcha' => 'Captcha',
        'checkbox' => 'Checkbox(es)',
        'email' => 'Email',
        'number' => 'Number',
        'text' => 'Text',
        'textarea' => 'Textarea',
        'radio' => 'Radio Buttons',
        'select' => 'Drop Down',
    ),
    'borderStyle' => array (
        'solid' => 'Solid',
        'dotted' => 'Dotted',
        'dashed' => 'Dashed',
    ),
    'buttonPosition' => array(
        'left' => 'Left',
        'right' => 'Right',
        'center' => 'Center',
    ),
    'recaptchaTheme' => array(
        'light' => 'Light',        
        'dark' => 'Dark',
    ),
    'recaptchaType' => array(
        'image' => 'Image',        
        'audio' => 'Audio',
    ),    
    'recaptchaSize' => array(
        'normal' => 'Normal',        
        'compact' => 'Compact',
    ),        
);