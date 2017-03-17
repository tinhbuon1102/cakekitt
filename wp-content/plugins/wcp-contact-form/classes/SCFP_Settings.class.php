<?php
use Webcodin\WCPContactForm\Core\Agp_SettingsAbstract;

class SCFP_Settings extends Agp_SettingsAbstract {
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;    

    /**
     * Parent Module
     * 
     * @var Agp_Module
     */
    protected static $_parentModule;
    
	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance($parentModule = NULL) {
		if ( is_null( self::$_instance ) ) {
            self::$_parentModule = $parentModule;            
			self::$_instance = new self();
		}
		return self::$_instance;
	}    
    
	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
    }        
    
    /**
     * Constructor 
     * 
     * @param Agp_Module $parentModule
     */
    public function __construct() {
        
        $config = include ($this->getParentModule()->getBaseDir() . '/config/config.php');        
        parent::__construct($config);
        
        add_action('admin_menu', array($this, 'adminMenu'));                        
    }
    
    public static function getParentModule() {
       
        return self::$_parentModule;
    }

    public function applyCapabilitiesToRole( $roles = array(), $capability = '') {
        if ( !empty($capability) && !empty($roles) ) {
            if (!is_array($roles)) {
                $roles[] = $roles;
            }            
  
            $allRoles = self::getUserRolesFieldSet();
            foreach ($allRoles as $roleKey => $roleName) {
                $role = get_role( $roleKey );
                if (in_array($roleKey, $roles)) {
                    $role->add_cap($capability);
                } else {
                    $role->remove_cap($capability);
                }
            }
            
        }
        
    }
    
    /**
     * Sanitize settings
     * 
     * @param array $input
     * @return array
     */
    public function sanitizeSettings($input) {
        $result = array();

        if (!empty($input) && is_array($input)) {
            foreach ($input as $key => $value) {
                $field = $this->getFieldByName($key);
                if (!empty($field['type'])) {
                    switch ($field['type']) {
                        case 'checkboxes':
                            $this->applyCapabilitiesToRole(array_keys($value), 'scfp_menu');
                            $this->applyCapabilitiesToRole(array_keys($value), 'scfp_view_inbox');
                            $result[$key] = $value;
                            break;
                        case 'text':
                        case 'colorpicker':
                            $result[$key] = stripslashes(esc_attr(trim($value)));    
                            break;                        
                        case 'textarea':                            
                            $result[$key] = $value;    
                            break;                                                
                        case 'metabox' :
                            $hasError = FALSE;
                            $row = 0;
                            foreach ($value as $k => $v) {
                                if ($k == "0") {
                                    unset($value[$k]);
                                    continue;
                                } 
                                $row++;
                                if (empty($v['name'])) {
                                    if (empty($hasError)) {
                                        $hasError = TRUE;                                        
                                        add_settings_error( 'fields_main_input', 'texterror', 'One or more "Field Name" parameters in "Fields Settings" section is empty', 'error' );                                        
                                    }
                                } 
                                if (!empty($v['choices'])) {
                                    $mode = !empty($v['choices']['mode']) ? $v['choices']['mode'] : 'list';
                                    $edit_values = !empty($v['choices']['edit_values']);
                                    $list = !empty($v['choices']['list']) ? $v['choices']['list'] : NULL;
                                    if (!empty($list) && is_array($list)) {
                                        foreach ($list as $lk => $lv) {
                                            if ($lk == "(00)") {
                                                unset($value[$k]['choices']['list'][$lk]);
                                                continue;
                                            } 

                                            if ( !$edit_values && $lv['value'] == '' ) {
                                                $lv['value'] = uniqid();
                                                $value[$k]['choices']['list'][$lk]['value'] = $lv['value'];    
                                            }
                                        }
                                    }                                    
                                    if ($mode == 'callback') {
                                        $callback = !empty($v['choices']['callback']) ? $v['choices']['callback'] : NULL;
                                        if (!empty($callback) && !is_callable($callback)) {
                                            add_settings_error( 'fields_main_input', 'texterror', sprintf('Field #%s: Callback function "%s" is not callable', $row, esc_attr($callback)), 'error' );                                        
                                        }
                                    }
                                }
                            }
                            $result[$key] = $value;                            
                            break;                                                
                        default:
                            $result[$key] = $value;
                            break;
                    }
                }
            }            
        }
        
        $this->escape($result);
        return apply_filters( 'scfp_sanitize_settings', $result, self::$_parentModule);
    }  

    public function escape ( &$value, $key = NULL ) {
        if ( is_array( $value ) ) {
            array_walk( $value, array( $this, 'escape' ) );
        } else {
            $value = htmlspecialchars($value);
        }        
    }

    public function unescape ( &$value, $key = NULL ) {
        if ( is_array( $value ) ) {
            array_walk( $value, array( $this, 'unescape' ) );
        } else {
            $value = htmlspecialchars_decode($value);
        }        
    }
    
    public function getUnEscapedSettings($key = NULL) {
        $settings = $this->getSettings($key);
        $this->unescape($settings);
        return $settings;        
    }
    
    public static function renderSettingsPage() {
        echo self::getParentModule()->getTemplate('admin/options/layout', self::instance());
    }    
    
    public static function getUserRolesFieldSet() {
        global $wp_roles;
        $result = array();
        if (!empty($wp_roles->roles) and is_array($wp_roles->roles)) {
            foreach ( $wp_roles->roles as $key => $value ) {
                $role = get_role( $key );
                if (!empty($role) && !$role->has_cap( 'manage_options' )) {
                    $result[$key] = $value['name'];
                }
            }
        }
        
        return apply_filters( 'scfp_get_fieldset_user_roles', $result, self::$_parentModule);
    }        
    
    public static function getPagesFieldSet() {
        $result = array('' => '');        
        $pages = get_pages();
        if (!empty($pages) and is_array($pages)) {
            foreach ($pages as $page) {
                $result[$page->ID] = $page->post_title;
            }
        }
        
        return apply_filters( 'scfp_get_fieldset_pages', $result, self::$_parentModule);
    }        
    
    public static function getEmailsFieldSet() {
        $result = array('' => '');
        $items = SCFP()->getSettings()->getFieldsSettings();
        if (!empty($items) && is_array($items)) {
            foreach( $items as $key => $field ) {
                if (!empty($field['visibility']) && !empty($field['field_type']) && $field['field_type'] == 'email' ) {
                    $result[$key] = $field['name'];    
                }
            }
        }
        
        return apply_filters( 'scfp_get_fieldset_emails', $result, self::$_parentModule);
    }            
    
    public static function getNamesFieldSet() {
        $result = array('' => '');
        $items = SCFP()->getSettings()->getFieldsSettings();
        if (!empty($items) && is_array($items)) {
            foreach( $items as $key => $field ) {
                if (!empty($field['visibility']) && !empty($field['field_type']) && $field['field_type'] == 'text' ) {
                    $result[$key] = $field['name'];    
                }
            }
        }
        
        return apply_filters( 'scfp_get_fieldset_name', $result, self::$_parentModule);
    }                
    
    public static function getFormFieldSet() {
        $result = array('' => '');
        $items = SCFP()->getSettings()->getFieldsSettings();
        if (!empty($items) && is_array($items)) {
            foreach( $items as $key => $field ) {
                if (!empty($field['visibility']) && !empty($field['field_type'])) {
                    $result[$key] = $field['name'];    
                }
            }
        }
        
        return apply_filters( 'scfp_get_fieldset_name', $result, self::$_parentModule);
    }                    
    
    public static function getFormTextFieldSet() {
        $result = array('' => '');
        $items = SCFP()->getSettings()->getFieldsSettings();
        if (!empty($items) && is_array($items)) {
            foreach( $items as $key => $field ) {
                if (!empty($field['visibility']) && !empty($field['field_type']) && !in_array( $field['field_type'] , array('email', 'captcha', 'checkbox', 'radio', 'select', 'number'))) {
                    $result[$key] = $field['name'];    
                }
            }
        }
        
        return apply_filters( 'scfp_get_text_fieldset_name', $result, self::$_parentModule);
    }                        
    
    public function adminMenu () {
        parent::adminMenu();
    }

    
    public function getConfirmationConfig () {
        return apply_filters( 'scfp_get_confirmation_config', $this->objectToArray($this->getConfig()->admin->options->fields->scfp_error_settings->fields->submit_confirmation), self::$_parentModule);
    }

    public function getConfirmationDefaults () {
        return apply_filters( 'scfp_get_confirmation_defaults', $this->getConfig()->admin->options->fields->scfp_error_settings->fields->submit_confirmation->default, self::$_parentModule);        
    }        
    
    public function getConfirmationSettings () {
        $options = get_option('scfp_error_settings');
        return apply_filters( 'scfp_get_confirmation_settings', !empty($options['submit_confirmation']) ? $options['submit_confirmation'] : $this->getConfirmationDefaults(), self::$_parentModule);
    }                
    
    
    public function getErrorsConfig () {
        return apply_filters( 'scfp_get_errors_config', $this->objectToArray($this->getConfig()->form->errors), self::$_parentModule);                        
    }

    public function getErrorsDefaults () {
        $result = array();
        foreach ($this->getConfig()->form->errors as $key => $value) {
            $result[$key] = $value->default;
        }
        return apply_filters( 'scfp_get_errors_defaults', $result, self::$_parentModule);                        
    }        
    
    public function getErrorsSettings () {
        $options = get_option('scfp_error_settings');
        if (!empty($options['error_name']) && empty($options['errors'])) {
            $options['errors'] = $options['error_name'];
        }

        if (!empty($options)) {
            foreach ($this->getErrorsDefaults() as $k => $v) {
                if (!array_key_exists($k, $options['errors'])) {
                    $options['errors'][$k] = $v; 
                }
            }            
        }
        
        return apply_filters( 'scfp_get_errors_settings', !empty($options) ? $options : array( 'errors' => $this->getErrorsDefaults()), self::$_parentModule);                
    }    
    
    public function getFormConfig () {
        return apply_filters( 'scfp_get_form_config', $this->objectToArray($this->getConfig()->admin->options->fields->scfp_form_settings->fields), self::$_parentModule);        
    }

    public function getFormDefaults () {
        $result = array();
        foreach ($this->getConfig()->admin->options->fields->scfp_form_settings->fields as $key => $value) {
            if ($key == 'field_settings') {
                $result[$key] = $this->getFieldsDefaults();
            } else {
                $result[$key] = $value->default;    
            }
            
        }
        return apply_filters( 'scfp_get_form_defaults', $result, self::$_parentModule);        
    }        
    
    public function getFormSettings () {
        $options = get_option('scfp_form_settings');
        return apply_filters( 'scfp_get_form_settings', !empty($options) ? $options : $this->getFormDefaults(), self::$_parentModule);        
    }        
    
    public function getStyleConfig () {
        return apply_filters( 'scfp_get_style_config', $this->objectToArray($this->getConfig()->admin->options->fields->scfp_style_settings->fields), self::$_parentModule);        
    }

    public function getStyleDefaults () {
        $result = array();
        foreach ($this->getConfig()->admin->options->fields->scfp_style_settings->fields as $key => $value) {
            $result[$key] = $value->default;
        }
        return apply_filters( 'scfp_get_style_defaults', $result, self::$_parentModule);
    }        
    
    public function getStyleSettings () {
        $options = get_option('scfp_style_settings');
        return apply_filters( 'scfp_get_style_settings', !empty($options) ? $options : $this->getStyleDefaults(), self::$_parentModule);
    }            
    
    public function getNotificationConfig () {
        return apply_filters( 'scfp_get_notification_config', $this->objectToArray($this->getConfig()->admin->options->fields->scfp_notification_settings->fields), self::$_parentModule);
    }
    
    public function getNotificationDefaults () {
        $result = array();
        foreach ($this->getConfig()->admin->options->fields->scfp_notification_settings->fields as $key => $value) {
            $result[$key] = $value->default;    
        }
        return apply_filters( 'scfp_get_notification_defaults', $result, self::$_parentModule);
    }            
    
    public function getNotificationSettings () {
        $options = get_option('scfp_notification_settings');
        return apply_filters( 'scfp_get_notification_settings', !empty($options) ? $options : $this->getNotificationDefaults()  , self::$_parentModule);
    }            
    
    public function getRecaptchaConfig () {
        return apply_filters( 'scfp_get_recaptcha_config', $this->objectToArray($this->getConfig()->admin->options->fields->scfp_recaptcha_settings->fields) , self::$_parentModule);
    }
    
    public function getRecaptchaDefaults () {
        $result = array();
        foreach ($this->getConfig()->admin->options->fields->scfp_recaptcha_settings->fields as $key => $value) {
            $result[$key] = $value->default;    
        }
        return apply_filters( 'scfp_get_recaptcha_defaults', $result , self::$_parentModule);
    }            
    
    public function getRecaptchaSettings () {
        $options = get_option('scfp_recaptcha_settings');
        return apply_filters( 'scfp_get_recaptcha_settings', !empty($options) ? $options : $this->getRecaptchaDefaults() , self::$_parentModule);
    } 
    
    public function getFieldsConfig () {
        return apply_filters( 'scfp_get_fields_config', $this->objectToArray($this->getConfig()->form->fields) , self::$_parentModule);
    }    
    

    public function getFieldsDefaults () {
        $result = array();
        foreach ($this->getConfig()->form->fields as $key => $value) {
            $result[$key] = $this->objectToArray($value->default);
        }
        return apply_filters( 'scfp_get_fields_defaults', $result , self::$_parentModule);
    }        
    
    public function getFieldsSettings () {
        $defaults = $this->getFieldsDefaults();
        $types = $this->getFieldsTypes();

        $data = SCFP()->getFormSettings()->getData('scfp_form_settings');        
        if (empty($data)) {
            $data = $this->getFieldsDefaults();            
        }
        
        foreach ($data as $k => $v) {
            if (array_key_exists($k, $defaults)) {
                if (!empty($defaults[$k]['visibility_readonly'])) {
                    $data[$k]['visibility'] = $defaults[$k]['visibility'];
                    $data[$k]['visibility_readonly'] = $defaults[$k]['visibility_readonly'];
                }
                if (!empty($defaults[$k]['required_readonly'])) {
                    $data[$k]['required'] = $defaults[$k]['required'];
                    $data[$k]['required_readonly'] = $defaults[$k]['required_readonly'];
                }                
                if (!empty($defaults[$k]['no_email'])) {
                    $data[$k]['no_email'] = $defaults[$k]['no_email'];
                }                                
                if (!empty($defaults[$k]['no_csv'])) {
                    $data[$k]['no_csv'] = $defaults[$k]['no_csv'];
                }               
                
                
                
                if (!isset($data[$k]['field_type'])) {
                    $data[$k]['field_type'] = $types[$k];  
                }
            }
        }
        
        return apply_filters( 'scfp_get_fields_settings', apply_filters( 'wcp_contact_form_get_fields_settings', $data ), self::$_parentModule) ;
    }        
    
    public function getUserParamsConfig () {
        return apply_filters( 'scfp_get_user_params_config', $this->objectToArray($this->getConfig()->form->userParams) , self::$_parentModule); 
    }
    
    public function getFieldsTypes () {
        $result = array();
        foreach ($this->getConfig()->form->fields as $key => $value) {
            $result[$key] = $value->type;
        }
        return apply_filters( 'scfp_get_fields_types', $result , self::$_parentModule);
    }        
    
    public function getFieldExtendedSettings( ) {
        return apply_filters( 'scfp_get_field_extended_settings', $this->objectToArray($this->getConfig()->admin->options->fieldSettings) );
    }

    public function hasExtendedSettings( $fieldType ) {
        $extendedSettings = $this->getFieldExtendedSettings();
        return !empty($extendedSettings[$fieldType]);
    }
    
    /**
     * Reset settings to default values
     */
    public function resetSettings () {
        delete_option( 'wcp-contact-form-version' );
        
        parent::resetSettings();
    }
    
    /**
     * Custom Notices
     * 
     * @global string $pagenow
     */
    public function customAdminNotices() {

        global $pagenow;

        if ( $pagenow == 'admin.php' && isset($_REQUEST['is-reset']) && !isset($_REQUEST['settings-updated']) && $_REQUEST['page'] == $this->getPage()) {
            $message = 'Settings reset to default values';
            echo '<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"><p><strong>'.$message.'</strong></p>'
                . '<button class="notice-dismiss" type="button">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                   </button>'
                . '</div>';            
        }
    }            
    
}

