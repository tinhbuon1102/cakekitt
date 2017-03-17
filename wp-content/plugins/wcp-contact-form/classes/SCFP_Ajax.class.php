<?php
use Webcodin\WCPContactForm\Core\Agp_AjaxAbstract;

class SCFP_Ajax extends Agp_AjaxAbstract {
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
    
    
    /**
     * Refresh Captcha Action
     */
    public function recreateCaptcha($data) {
        $result = array();
        $fieldsSettings = SCFP()->getSettings()->getFieldsSettings();
        
        $id = $data['id'];
        $key = $data['key'];        
        
        $form = new SCFP_Form($id);
        $form->getCaptcha()->isNumeric = !(empty($fieldsSettings[$key]['captcha']['type'])) &&  $fieldsSettings[$key]['captcha']['type'] == 'captcha-numeric';
        
        $result['img'] = 'data:image/png;base64,'.$form->getCaptcha()->CreateImage($key);
        return $result;
    }
    
    public function getExtendedSettingsTemplate ($data) {
        $result = array();
        
        $type = $data['type'];
        $enabled = !empty($data['enabled']) && $data['enabled'] == 'true';
        $params = !empty($data['params']) && is_serialized( $data['params'] ) ? unserialize( stripcslashes( $data['params']) ) : NULL;
        $result['template'] = SCFP()->getTemplate('admin/form/extended-settings/layout', array('selectedType' => $type, 'params' => $params, 'enabled' => $enabled ));
        if (SCFP()->getSettings()->hasExtendedSettings($type)) {
            $result['hasTemplate'] = 1;    
        }
        
        return $result; 
    }

    public function getExtendedSettingsCaptchaTemplate ($data) {
        $result = array();
        
        $type = 'captcha';
        $params = !empty($data['params']) && is_serialized( $data['params'] ) ? unserialize( stripcslashes( $data['params']) ) : NULL;
        $captchaType = $data['type'];
        
        $result['template'] = SCFP()->getTemplate('admin/form/extended-settings/captcha/layout', array('selectedType' => $type, 'params' => $params, 'captchaType' => $captchaType  ));
        
        return $result; 
    }    
    
}
