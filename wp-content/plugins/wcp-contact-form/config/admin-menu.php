<?php

return array(
    'scfp' => array(
        'page_title' => __('問い合わせ', 'cake'), 
        'menu_title' => __('問い合わせ', 'cake'), 
        'capability' => 'scfp_menu',
        'function' => '',
        'icon_url' => '',  
        'position' => null, 
        'hideInSubMenu' => TRUE,
        'icon_url'   => 'dashicons-email-alt',    
        'submenu' => array(
            'edit.php?post_type=form-entries' => array(
                'page_title' => __('受信箱', 'cake'), 
                'menu_title' => __('受信箱', 'cake'), 
                'capability' => 'scfp_view_inbox',
                'function' => '',   
            ),               
            'scfp_plugin_options' => array(
                'page_title' => __('設定', 'cake'), 
                'menu_title' => __('設定', 'cake'), 
                'capability' => 'scfp_edit_settings',
                'function' => array('SCFP_Settings', 'renderSettingsPage'),                         
            ),   
        ),
    ),
);
    