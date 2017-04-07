<?php

return array(
    'scfp' => array(
        'page_title' => __('Contact Form', 'cake'), 
        'menu_title' => __('Contact Form', 'cake'), 
        'capability' => 'scfp_menu',
        'function' => '',
        'icon_url' => '',  
        'position' => null, 
        'hideInSubMenu' => TRUE,
        'icon_url'   => 'dashicons-email-alt',    
        'submenu' => array(
            'edit.php?post_type=form-entries' => array(
                'page_title' => __('Inbox', 'cake'), 
                'menu_title' => __('Inbox', 'cake'), 
                'capability' => 'scfp_view_inbox',
                'function' => '',   
            ),               
            'scfp_plugin_options' => array(
                'page_title' => __('Settings', 'cake'), 
                'menu_title' => __('Settings', 'cake'), 
                'capability' => 'scfp_edit_settings',
                'function' => array('SCFP_Settings', 'renderSettingsPage'),                         
            ),   
        ),
    ),
);
    