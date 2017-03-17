<?php
use Webcodin\WCPContactForm\Core\Agp_Module;
use Webcodin\WCPContactForm\Core\Agp_Session;

class SCFP extends Agp_Module {

    /**
     * Current plugin version
     * 
     * @var type 
     */
    private $version = '3.1.0';
    
    /**
     * Form Settings
     * 
     * @var SCFP_FormSettings
     */
    private $formSettings;    
    
    /**
     * Plugin settings
     * 
     * @var SCFP_Settings
     */
    private $settings;    
    
    
    /**
     * Session
     * 
     * @var Agp_Session
     */
    private $session;

    /**
     * Form entries
     * 
     * @var SCFP_FormEntries
     */
    private $formEntries;

    /**
     * Ajax
     * 
     * @var SCFP_Ajax 
     */
    private $ajax;
    
    /**
     * LESS Parser
     * 
     * @var Less_Parser
     */
    private $lessParser;
    
    
    /**
     * Rendered LESS CSS List
     * 
     * @var array
     */
    
    private $registeredLessCss =array();

    /**
     * Registered ReCAPTCHA Widgets
     * 
     * @var type 
     */
    private $registeredRcWidgets = array();
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;    
    
	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
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
    
    public function __construct() {
        parent::__construct(dirname(dirname(__FILE__)));
        
        include_once ( $this->getBaseDir() . '/types/form-entries-post-type.php' );     
        include_once ( $this->getBaseDir() . '/inc/cool-php-captcha/captcha.php' );     
        include_once ( $this->getBaseDir() . '/vendor/autoload.php' );     
        
        $this->settings = SCFP_Settings::instance( $this );
        $this->updatePlugin();        
        
        $this->formSettings = SCFP_FormSettings::instance();        
        $this->formEntries = SCFP_FormEntries::instance();
        $this->session = Agp_Session::instance();
        $this->ajax = SCFP_Ajax::instance();

        $this->lessParser = new Less_Parser();

        add_action( 'init', array($this, 'init' ) );                
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScripts' ) );                
        add_action( 'admin_enqueue_scripts', array($this, 'enqueueAdminScripts' ));            
        add_shortcode( 'scfp', array($this, 'doScfpShortcode') ); 
        add_shortcode( 'wcp_contactform', array($this, 'doScfpShortcode') ); 
        add_action( 'widgets_init', array($this, 'initWidgets' ) );
        add_action( 'admin_init', array($this, 'tinyMCEButtons' ) ); 
        add_action( 'admin_init', array($this, 'addDefaultCapabilities' ) ); 
        add_action( 'wp_footer', array($this, 'enqueueLessCss' ) ); 
        add_filter( 'clean_url', array($this, 'deferJavascripts' ), 11, 1 );
        add_action( 'wp_footer', array($this, 'enqueueRcWidgets' ) ); 
    }

    
    public function updatePlugin () {
        $currentVersion = $this->getVersion();
        $version = get_option( 'wcp-contact-form-version' );
        if (empty($version)) {
            $version = '1.0.0';
        }
        
        if ( function_exists( 'version_compare' ) && version_compare( $version , $currentVersion) == -1 ) {
            if ( version_compare( $version , '2.1.0', '<' ) ) {
                $form_settings = get_option('scfp_form_settings') ? get_option('scfp_form_settings') : array();
                $style_settings = get_option('scfp_style_settings') ? get_option('scfp_style_settings') : array();
                if (!empty($form_settings) && empty($style_settings)) {
                    $style_settings = $this->settings->getStyleDefaults();            
                    if (isset($form_settings['button_color'])) {
                        $style_settings['button_color'] = $form_settings['button_color'];
                        unset($form_settings['button_color']);
                    }
                    if (isset($form_settings['text_color'])) {
                        $style_settings['text_color'] = $form_settings['text_color'];
                        unset($form_settings['text_color']);
                    }        
                }
                update_option( 'scfp_form_settings', $form_settings );
                update_option( 'scfp_style_settings', $style_settings );                            
            }       
            
            
            if ( version_compare( $version , '3.0.0', '<' ) ) {
                $form_settings = get_option('scfp_form_settings') ? get_option('scfp_form_settings') : array();

                if (!empty($form_settings['field_settings'])) {
                    $fields  = $form_settings['field_settings'];
                    if ( is_array($fields) ) {
                        foreach ($fields as $key => $field) {
                            if (!empty($field['field_type'])) {
                                $field_type = $field['field_type'];
                                
                                switch ($field_type) {
                                    case 'captcha':
                                    case 'captcha-numeric':
                                    case 'captcha-recaptcha':        
                                        $field['field_type'] = 'captcha';
                                        $field['captcha']['type'] = $field_type;
                                        $field['display_label'] = $field['name'];
                                        break;
                                    case 'checkbox':
                                        $k = uniqid();
                                        $field['choices']['mode'] = 'list';
                                        $field['choices']['callback'] = '';
                                        $field['choices']['list'] = array(
                                            uniqid() => array(
                                                'value' => $k,
                                                'label' => $field['name'],
                                            ),
                                        );
                                        $field['choices']['multiselect'] = 1;
                                        break;
                                    case 'email':
                                        $field['default'] = '';
                                        $field['display_label'] = $field['name'];
                                        break;
                                    default :
                                        $field['display_label'] = $field['name'];
                                        break;
                                }
                                
                                $fields[$key] = $field;
                            }
                        }
                    }
                    $form_settings['field_settings'] = $fields;
                }

                update_option( 'scfp_form_settings', $form_settings );
            }            
            
            update_option( 'wcp-contact-form-version', $currentVersion );   
            $this->settings->refreshConfig();
        }
    }    
    
    public function deferJavascripts ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( !strpos( $url, 'recaptcha/api.js' ) || !strpos( $url, 'scfpOnLoadCallback' ) ) return $url;
        
        return "$url' async='async' defer='defer";
    }
    
    public function init() {
    }
    
    public function addDefaultCapabilities() {
        global $wp_roles;
        if (!empty($wp_roles->roles) and is_array($wp_roles->roles)) {
            foreach ( $wp_roles->roles as $key => $value ) {
                $role = get_role( $key );
                if (!empty($role) && $role->has_cap( 'manage_options' )) {
                    $role->add_cap( 'scfp_menu' );     
                    $role->add_cap( 'scfp_view_inbox' );     
                    $role->add_cap( 'scfp_edit_settings' );     
                }
            }
        }
        
    }
    
    public function initWidgets() {
        register_widget('SCFP_FormWidget');
    }
    
    public function enqueueScripts () {
        wp_register_script( 'scfp', $this->getAssetUrl('js/main.js'), array('jquery') ); 
        wp_localize_script( 'scfp', 'ajax_scfp', array( 
            'base_url' => site_url(),         
            'ajax_url' => admin_url( 'admin-ajax.php' ), 
            'ajax_nonce' => wp_create_nonce('ajax_atf_nonce'),        
        ));  
        
        $recaptcha = SCFP()->getSettings()->getRecaptchaSettings();
        $hl = !empty($recaptcha['rc_wp_lang']) ? '&hl=' . get_locale() : '';
        
        wp_register_script( 'scfp-recaptcha', $this->getAssetUrl('js/recaptcha.js'), array('jquery', 'scfp') );         
        wp_register_script( 'scfp-recaptcha-api', 'https://www.google.com/recaptcha/api.js?onload=scfpOnLoadCallback&render=explicit'.$hl, array('jquery', 'scfp-recaptcha') );         

        $styles = SCFP()->getSettings()->getStyleSettings();                
        if (empty($styles['no_style'])) {
            if ( is_rtl() ) {
                wp_register_style( 'scfp-css', $this->getAssetUrl('css/rtl/style.css') );             
            } else {
                wp_register_style( 'scfp-css', $this->getAssetUrl('css/style.css') );                     
            }            
        }
        
        wp_register_style( 'scfp-admin-toolbar-css', $this->getAssetUrl('css/admin-toolbar.css') );             
        
        $form_settings = $this->settings->getFormSettings();
        if (empty($form_settings['scripts_in_footer'])) {
            wp_enqueue_script( 'scfp' );         
        }

        if (is_admin_bar_showing()) {
            wp_enqueue_style( 'scfp-admin-toolbar-css' );    
        }
        
        wp_enqueue_style( 'scfp-css' );    
    }        
    
    public function enqueueAdminScripts () {
        global $current_screen;
        
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );            
        wp_enqueue_script( 'jquery-ui-sortable' );            
        
        wp_enqueue_script( 'scfp', $this->getAssetUrl('js/admin.js'), array('jquery', 'wp-color-picker') );   
        
        if ( is_rtl() ) {
            wp_enqueue_style( 'scfp-css', $this->getAssetUrl('css/rtl/admin.css'), array('wp-color-picker') );   
        } else {
            wp_enqueue_style( 'scfp-css', $this->getAssetUrl('css/admin.css'), array('wp-color-picker') );   
        }        

        wp_register_style( 'scfp-admin-toolbar-css', $this->getAssetUrl('css/admin-toolbar.css') );                     
        
        wp_localize_script('scfp', 'csvVar', array(
            'href' => add_query_arg(array('download_csv' => 1)),
            'active' =>  'form-entries' == $current_screen->post_type,
            'base_url' => site_url(),         
            'ajax_url' => admin_url( 'admin-ajax.php' ), 
            'ajax_nonce' => wp_create_nonce('ajax_atf_nonce'),                    
        ));
        
        if ( is_admin_bar_showing() ) {
            wp_enqueue_style( 'scfp-admin-toolbar-css' );    
        }
    }
    
    public function tinyMCEButtons () {
        
        $form_settings = $this->settings->getFormSettings();
        if ( current_user_can('edit_posts') && current_user_can('edit_pages') && !empty($form_settings['tinymce_button_enabled'])) {
            if ( get_user_option('rich_editing') == 'true' ) {
               add_filter( 'mce_buttons', array($this, 'tinyMCERegisterButtons'));                
               add_filter( 'mce_external_plugins', array($this, 'tinyMCEAddPlugin') );
            }        
        }        
    }
    
    public function tinyMCERegisterButtons( $buttons ) {
       array_push( $buttons, "|", "wcp_contactform" );
       return $buttons;
    }    
    
    public function tinyMCEAddPlugin( $plugin_array ) {
        $plugin_array['wcp_contactform'] = $this->getAssetUrl() . '/js/wcp-contactform.js';
        return $plugin_array;        
    }            
    
    public function enqueueLessCss() {
        if (!empty($this->registeredLessCss)) {
            echo '<style type="text/css" >' . implode('', $this->registeredLessCss) . '</style>';                    
        }
    }
  
    public function registerLessCss($id) {
        $styleSettings = SCFP()->getSettings()->getStyleSettings();        
        if (!array_key_exists($id, $this->registeredLessCss) && empty($styleSettings['no_style'])) {
            $this->lessParser->parseFile($this->getBaseDir() . '/assets/less/style.less');

            
            $this->lessParser->ModifyVars( array (
                    'id' => $id,
                    'no_border' => !empty($styleSettings['no_border']) ? $styleSettings['no_border'] : '',
                    'border_size' => !empty($styleSettings['border_size']) ? $styleSettings['border_size'] : '',
                    'border_style' => !empty($styleSettings['border_style']) ? $styleSettings['border_style'] : '',
                    'border_color' => !empty($styleSettings['border_color']) ? $styleSettings['border_color'] : '',            
                    'field_label_text_color' => !empty($styleSettings['field_label_text_color']) ? $styleSettings['field_label_text_color'] : '',
                    'field_label_marker_text_color' => !empty($styleSettings['field_label_marker_text_color']) ? $styleSettings['field_label_marker_text_color'] : '',            
                    'field_text_color' => !empty($styleSettings['field_text_color']) ? $styleSettings['field_text_color'] : '',
                    'field_description_color' => !empty($styleSettings['field_description_color']) ? $styleSettings['field_description_color'] : '',
                    'no_background' => !empty($styleSettings['no_background']) ? $styleSettings['no_background'] : '',
                    'background_color' => !empty($styleSettings['background_color']) ? $styleSettings['background_color'] : '',
                    'button_color' => !empty($styleSettings['button_color']) ? $styleSettings['button_color'] : '',
                    'text_color' => !empty($styleSettings['text_color']) ? $styleSettings['text_color'] : '',  
                    'hover_button_color' => !empty($styleSettings['hover_button_color']) ? $styleSettings['hover_button_color'] : '',
                    'hover_text_color' => !empty($styleSettings['hover_text_color']) ? $styleSettings['hover_text_color'] : '', 
                )
            );
            $this->registeredLessCss[] = $this->lessParser->getCss();            
        }
    }

    public function doScfpShortcode ($atts) {
        $form_settings = $this->settings->getFormSettings();
        if (!empty($form_settings['scripts_in_footer'])) {
            wp_enqueue_script( 'scfp' );         
            wp_enqueue_style( 'scfp-css' );                         
        }
        
        $atts = shortcode_atts( array(
            'id' => 'default-contactform-id',
        ), $atts );        
        
        if (!empty($atts['id'])) {
            $id = $atts['id'];
            $form = new SCFP_Form($id);
            
            if ( isset($_POST['form_id']) && $_POST['form_id'] == $id && isset($_POST['action']) && $_POST['action'] == 'scfp-form-submit' ) {        
                $form->submit($_POST);
            }

            $this->registerLessCss($id);                
            $atts['form'] = $form;
            return apply_filters( 'scfp_show_form', apply_filters( 'scfp_show_shortcode', $this->getTemplate('scfp', $atts), $atts ), $atts );                
        }
    }
    
    public function doContactFormWidget($atts){
        $form_settings = $this->settings->getFormSettings();
        if (!empty($form_settings['scripts_in_footer'])) {
            wp_enqueue_script( 'scfp' );         
            wp_enqueue_style( 'scfp-css' );                         
        }
        
        $atts = shortcode_atts( array(
            'id' => NULL,
        ), $atts );        
        
        if (!empty($atts['id'])) {
            $id = $atts['id'];
            $form = new SCFP_Form($id);
            
            if ( isset($_POST['form_id']) && $_POST['form_id'] == $id && isset($_POST['action']) && $_POST['action'] == 'scfp-form-submit' ) {        
                $form->submit($_POST);
            }

            $this->registerLessCss($id);                
            $atts['form'] = $form;
            return apply_filters( 'scfp_show_form', apply_filters( 'scfp_show_widget', $this->getTemplate('scfp-widget', $atts), $atts ), $atts );                
        }    
    }
 
    public function getTemplate($name, $params = NULL) {
        return apply_filters( 'scfp_get_template', apply_filters( 'scfp_get_template_' . $name, parent::getTemplate($name, $params), $params, $this), $name, $params, $this );
    }
    
    public function compactAtts ( $atts ) {
        if (!empty($atts) && is_array($atts)) {
            $atts_s = '';
            foreach ($atts as $k => $value) {
                $atts_s .= $k . '="' . $value . '"';
            }
            $atts = $atts_s;
        }
        return $atts;
    }
    
    public function getChoicesList ( $choices ) {
        $choices_list = array();
        
        if ($choices['mode'] == 'callback' && !empty($choices['callback']) && (is_callable($choices['callback'])) ) {
            $choices_list = call_user_func($choices['callback']);
        } elseif($choices['mode'] == 'list' && !empty($choices['list']) && is_array($choices['list'])) {
            $choices_list = array();
            foreach ($choices['list'] as $item) {
                if (!empty($item['value'])) {
                    $choices_list[$item['value']] = $item['label'];    
                }
            }
        }   

        return $choices_list;
    }
    
    public function getChoicesSelected ($choices, $selected = NULL) {
        $selected_values = array();
        
        if (isset($selected)) {
            if (is_array($selected)) {
                $selected_values = array_filter( $selected );    
            } else {
                $selected_values[] = $selected;
            }
        } elseif(!empty($choices['default'])) {
            $r = array();
            foreach ($choices['default'] as $i) {
                if (!empty($choices['list'][$i]['value'])) {
                    $r[] = $choices['list'][$i]['value'];
                }
            }
            $selected_values = $r;
        } 
        
        return $selected_values;
    }
    
    public function getChoicesView ($choices, $key) {
        if (is_array($choices)) {
            $fieldsSettings = SCFP()->getSettings()->getFieldsSettings();
            
            if (!empty($fieldsSettings[$key]['choices']) && is_array($fieldsSettings[$key]['choices'])) {
                $choices_list = SCFP()->getChoicesList($fieldsSettings[$key]['choices']);    
                if ( is_array($choices_list) ) {
                    if ( count($choices_list) == 1 && $fieldsSettings[$key]['field_type'] == 'checkbox' ) {
                        return !empty( $choices ) ? 'Yes' : 'No'; ;    
                    } else {
                        $result = array();
                        if (!empty( $choices ) && is_array($choices)) {
                            foreach ($choices as $choice) {
                                if (isset($choices_list[$choice])) {
                                    $result[] = $choices_list[$choice];
                                }
                            }    
                        }
                        return implode(', ', $result);
                    } 
                }
            }
        } else {
            return $choices;    
        }
    }    
    
    public function applyFormVars( $text ) {
        if( strpos( $text, '{$loggedin_user_email}' ) !== FALSE ) {
            $loggedin_user_email ='';
            $userId = get_current_user_id();
            if (!empty($userId)) {
                $user = get_userdata($userId);    
                if (!empty($user->user_email) && is_email($user->user_email)) {
                    $loggedin_user_email = $user->user_email;    
                }
            }  
            $text =  str_replace( '{$loggedin_user_email}', $loggedin_user_email, $text );
        }
        
        return apply_filters( 'scfp_apply_form_vars', $text, $this );
    }


    public function registerRcWidget($key, $recaptchaId, $recaptcha) {
        $this->registeredRcWidgets[] = array(
            'key' => $key,
            'recaptchaId' => $recaptchaId,
            'recaptcha' => $recaptcha,
        );
    }
    
    public function enqueueRcWidgets() {
        if (!empty($this->registeredRcWidgets)) {
            foreach ($this->registeredRcWidgets as $rcWidget) {
                $key = $rcWidget['key'];
                $recaptchaId = $rcWidget['recaptchaId'];
                $recaptcha = $rcWidget['recaptcha'];
                ?>        
                    <script type="text/javascript">
                        if (typeof scfp_rcwidget == 'undefined') {
                            var scfp_rcwidget = {};
                        }

                        scfp_rcwidget.rcwidget_<?php echo $recaptchaId; ?> = {
                            sitekey : '<?php echo $recaptcha['rc_key'];?>',
                            theme : '<?php echo $recaptcha['rc_theme'];?>',
                            type : '<?php echo $recaptcha['rc_type'];?>',
                            size : '<?php echo $recaptcha['rc_size'];?>',
                            callback : function(response) {
                                var el = '#rcwidget_row_<?php echo $recaptchaId; ?> #scfp-<?php echo $key; ?>';
                                jQuery(el).val(response);
                            }
                        };
                    </script>  
                <?php                
            }
        }
    }
    
    public function getSettings() {
        return $this->settings;
    }

    function getFormEntries() {
        return $this->formEntries;
    }
    
    function getFormSettings() {
        return $this->formSettings;
    }    

    public function getLessParser() {
        return $this->lessParser;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getRegisteredLessCss() {
        return $this->registeredLessCss;
    }

}
