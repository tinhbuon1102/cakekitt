<?php
use Webcodin\WCPContactForm\Core\Agp_Module;
use Webcodin\WCPContactForm\Core\Agp_Session;

class SCFP_Form extends Agp_Module {
    
    private $id;
    
    private $data = NULL;
    
    private $error = array();
    
    private $notifications = array();
    
    private $postId;
    
    /**
     * Session
     * 
     * @var Agp_Session
     */
    private $session;
    
    private $captcha;
    
    private $recaptcha;
    
    public function __construct($id) {
        parent::__construct(SCFP()->getBaseDir());        
        $this->id = $id;
        $this->session = Agp_Session::instance();

        $this->captcha = new SimpleCaptcha();
        $this->captcha->resourcesPath = SCFP()->getBaseDir() . "/inc/cool-php-captcha/resources";        
        $this->captcha->session_var = 'captcha-' . $this->id;
        $this->captcha->imageFormat = 'png';
        $this->captcha->scale = 3; 
        $this->captcha->blur = true;
        
        $this->recaptcha = new SCFP_Recaptcha();
        
        do_action_ref_array( 'scfp_form_create', array( &$this ) );
    }

    public function submit($postData) {
        
        if ( !empty($postData['form_id']) && $postData['form_id'] == $this->id && !empty($postData['action']) && $postData['action'] == 'scfp-form-submit' ) {        
            
            do_action( 'scfp_form_submit_before', $this );
            
            foreach(SCFP()->getSettings()->getFieldsSettings() as $key => $field) {
                if (!empty($field['visibility'])) {
                    switch ( $field['field_type'] ) {
                        case 'checkbox':
                        case 'radio':    
                        case 'select':    
                            $this->data[$key] = isset($postData['scfp-'.$key]) ? $postData['scfp-'.$key] : array();
                            break;
                        default:
                            $this->data[$key] = !empty($postData['scfp-'.$key]) ? esc_attr($postData['scfp-'.$key]) : '';
                            break;
                    }
                    
                    $this->data[$key] = apply_filters( 'scfp_form_submit_field', apply_filters( 'scfp_form_submit_field_' . $key, stripslashes_deep( $this->data[$key] ), $field, $this ), $key, $field, $this );
                }
                
                if (isset($_POST['scfp-' . $key])) {
                    unset($_POST['scfp-' . $key]);
                }
                unset($_POST['form_id']);
                unset($_POST['action']);
            }
            
            $this->data = apply_filters( 'scfp_form_submit_data', $this->data, $this );
            
            $this->notifications = array();
            
            if ($this->validation()) {
                if ($this->saveForm()) {
                    if ($this->notification()) {
                        $this->data = NULL;
                        
                        do_action( 'scfp_form_submit_success', $this );                        
                        
                        if (!$this->redirect()) {
                            $this->submitConfirmation();
                        }
                    }
                }
            }
        }
    }
    
    public function submitConfirmation () {
        $message = SCFP()->getSettings()->getConfirmationSettings();
        $this->notifications[] = $message;
        $this->notifications = apply_filters( 'scfp_form_submit_notifications', $this->notifications, $this );
    }
    
    public function validation() {
        $fields = SCFP()->getSettings()->getFieldsSettings();
        $this->error = array();
        
        foreach ($this->data as $key => $value) {

            $this->error[$key] = NULL;
            $this->error[$key] = apply_filters( 'scfp_form_validation_field', $this->error[$key], $key, $value, $fields[$key], $this );
            if (!empty($this->error[$key])) {
                continue;
            } else {
                unset($this->error[$key]);
            }
            
            //required_error
            if (!empty($fields[$key]['required']) && empty($value)) {
               $this->error[$key] = 'required_error';
               continue;
            }
            
            if ($fields[$key]['field_type'] == 'select' && !empty($fields[$key]['required'])) {
                $value = array_filter($value);
                if (empty($value)) {
                    $this->error[$key] = 'required_error';
                    continue;                    
                }
            }            

            //email_error
            if (!empty($value) && $fields[$key]['field_type'] == 'email'  && !is_email($value)) {
               $this->error[$key] = 'email_error';
               continue;
            }
           
            //number_error
            if ($fields[$key]['field_type'] == 'number') {
                if (!empty($value) && (!is_numeric($value) || is_numeric($value) && !is_int($value * 1))) {
                   $this->error[$key] = 'number_error';
                   continue;
                }                
                
                if (!empty($fields[$key]['min']) && is_numeric($fields[$key]['min']) && $value < $fields[$key]['min'] || !empty($fields[$key]['max']) && is_numeric($fields[$key]['max']) && $value > $fields[$key]['max']) {
                   $this->error[$key] = 'number_error';
                   continue;                    
                }                
            }

            //captcha error
            if ($fields[$key]['field_type'] == 'captcha' && !empty($fields[$key]['required'])) {
                //captcha error
                if ($fields[$key]['captcha']['type'] == 'captcha'  &&  ( empty( $_SESSION['captcha-'.$this->id][$key] ) || strtolower( trim($value ) ) != strtolower( trim($_SESSION['captcha-'.$this->id][$key] ))) ) {
                   $this->error[$key] = 'captcha_error';
                   continue;
                }

                //numeric captcha error
                if ($fields[$key]['captcha']['type'] == 'captcha-numeric'  &&  ( empty( $_SESSION['captcha-'.$this->id][$key] ) || strtolower( trim($value ) ) != strtolower( trim($_SESSION['captcha-'.$this->id][$key] ))) ) {
                   $this->error[$key] = 'captcha_error';
                   continue;
                }            

                //recaptcha error
                if (!empty($value) && $fields[$key]['captcha']['type'] == 'captcha-recaptcha' && !$this->recaptchaValidation($value)) {
                   $this->error[$key] = 'captcha_error';
                   continue;
                }                           
            }
            

        }
        
        $this->error = apply_filters( 'scfp_form_validation', $this->error, $fields, $this  );
        
        return empty($this->error);
    }
    
    public function recaptchaValidation ($value) {
        $result = FALSE;
        $recaptcha = SCFP()->getSettings()->getRecaptchaSettings();
        if (!empty($recaptcha['rc_secret_key'])) {
            $secret = $recaptcha['rc_secret_key'];    

            $this->getRecaptcha()->addRequestParam('secret', $secret);
            $this->getRecaptcha()->addRequestParam('response', $value);

            $json = $this->getRecaptcha()->post();
            if (!empty($json)) {
                $res = json_decode($json);
                $result = !empty($res->success);
            }
        }
        return $result;
    }
    
    private function saveForm() {
        do_action( 'scfp_form_save_before', $this );
        
        $num_entries = $this->getEntriesCount();
        
        $post = apply_filters( 'scfp_form_save_post_data', array(
            'post_type' => 'form-entries',
            'post_status' => 'unread',
            'post_content' => !empty($this->data['message']) ? $this->data['message'] : '',
        ), $this);
     
        $this->postId = wp_insert_post( $post, TRUE );
        
        if (!is_wp_error($this->postId )) {
            
            $i = get_option('entry_counter') && $num_entries ? get_option('entry_counter') : 1;            
            update_post_meta( $this->postId , 'entry_id', $i, true );            
            wp_update_post( array(
                    'ID'           => $this->postId ,
                    'post_title'   => 'Entry #' . $i,
                )
            );            
            update_option('entry_counter', ++$i);
            
            $this->data = apply_filters( 'scfp_form_save_data_before', $this->data, $this );
            
            $result = TRUE;
            foreach ($this->data as $key => $value) {
                if ($result) {
                    if (!is_array($value)) {
                        $value = nl2br($value);
                    }
                    $meta_key = 'scfp_' . $key;
                    $meta_value = apply_filters( 'scfp_form_save_field', apply_filters( 'scfp_form_save_field_' . $key, $value, $value, $this), $key, $value, $this);
                    $result = $result && add_post_meta($this->postId , $meta_key, $meta_value);                    
                }
            }
            
            return apply_filters( 'scfp_form_save_data', $result, $this->data, $this );            
        }
    }    
    
    public function getEntriesCount() {
        $query = new WP_Query( 
            array( 'post_status' => array('trash', 'read', 'unread'), 'post_type' => 'form-entries', 'posts_per_page' => -1 ) 
        );
        $num_result = count($query->posts);
        wp_reset_query();
        return $num_result;
    }
    
    public static function getUnreadEntriesCount() {
        $query = new WP_Query( 
            array( 'post_status' => array('unread'), 'post_type' => 'form-entries', 'posts_per_page' => -1 ) 
        );
        $num_result = count($query->posts);
        wp_reset_query();
        return $num_result;
    }    
    
    public function notification() {
        if ( function_exists('siteorigin_panels_filter_content') ) {
            remove_filter( 'the_content', 'siteorigin_panels_filter_content' );    
        }
        
        do_action( 'scfp_form_notification_before', $this );        
        $result = TRUE;
        $settings = SCFP()->getSettings()->getNotificationSettings();
        
        if (empty($settings['disable'])) {
            $to = apply_filters( 'scfp_form_admin_notification_to', !empty($settings['another_email']) ? $settings['another_email'] : get_option('admin_email'), $this);
            $subject = apply_filters( 'scfp_form_admin_notification_subject', stripslashes ( $this->applyEmailVars( !empty($settings['subject']) ? $settings['subject'] : '' ) ), $this);
            $message = apply_filters( 'scfp_form_admin_notification_message', apply_filters( 'the_content',html_entity_decode( htmlspecialchars_decode( stripslashes ( $this->applyEmailVars( !empty($settings['message']) ? $settings['message'] : '') ) ) ), $this ) );
            $content = apply_filters( 'scfp_form_admin_notification_content', $this->getTemplate('email/mail-template', array('message' => $message)), $this);

            add_filter( 'wp_mail_content_type', array($this, 'sendHtmlContentType') );        
            add_filter( 'wp_mail_from', array($this, 'changeAdminMailFrom'), 99 ); 
            add_filter( 'wp_mail_from_name', array($this, 'changeAdminMailFromName'), 99 );
            
            if (!empty($to)) {
                wp_mail($to, $subject, $content);
            }
            
            remove_filter( 'wp_mail_from_name', array($this, 'changeAdminMailFromName'), 99 );
            remove_filter( 'wp_mail_from', array($this, 'changeAdminMailFrom'), 99 );
            remove_filter( 'wp_mail_content_type', array($this, 'sendHtmlContentType') );        
        }
        
        if (empty($settings['user_disable'])) {
            $email_field = !empty($settings['user_email']) ? $settings['user_email'] : 'email';
            
            $to = apply_filters( 'scfp_form_user_notification_to', !empty($this->data[$email_field]) ? $this->data[$email_field] : '', $this);
            $subject = apply_filters( 'scfp_form_user_notification_subject', stripslashes ( $this->applyEmailVars( !empty($settings['user_subject']) ? $settings['user_subject'] : '' ) ), $this);
            $message = apply_filters( 'scfp_form_user_notification_message', apply_filters( 'the_content', html_entity_decode( htmlspecialchars_decode( stripslashes ( $this->applyEmailVars( !empty($settings['user_message']) ? $settings['user_message'] : '') ) ) ), $this ) );
            $content = apply_filters( 'scfp_form_user_notification_content', $this->getTemplate('email/mail-template', array('message' => $message)), $this);

            add_filter( 'wp_mail_content_type', array($this, 'sendHtmlContentType') ); 
            add_filter( 'wp_mail_from', array($this, 'changeUserMailFrom'), 99 );      
            add_filter( 'wp_mail_from_name', array($this, 'changeUserMailFromName'), 99 );        
            
            if (!empty($to)) {
                wp_mail($to, $subject, $content);
            }
            remove_filter( 'wp_mail_from_name', array($this, 'changeUserMailFromName'), 99 );
            remove_filter( 'wp_mail_from', array($this, 'changeUserMailFrom'), 99 );
            remove_filter( 'wp_mail_content_type', array($this, 'sendHtmlContentType') );        
        } 
        
        if ( function_exists('siteorigin_panels_filter_content') ) {        
            add_filter( 'the_content', 'siteorigin_panels_filter_content' );            
        }
        
        return $result;
    }        
    
    
    public function changeUserMailFrom ( $from_email ) {
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_email_from = $this->applyEmailVars( !empty($settings['user_email_from']) ? $settings['user_email_from'] : '' );
        if (!empty($user_email_from) && is_email($user_email_from)) {
            $from_email = $user_email_from;    
        }
        return $from_email;
    }    
    
    
    public function changeUserMailFromName ( $from_name ) {
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_email_from_name = $this->applyEmailVars( !empty($settings['user_email_from_name']) ? $settings['user_email_from_name'] : '' );
        if (!empty($user_email_from_name)) {
            $from_name = $user_email_from_name;
        }
        return $from_name;
    }            
    
    public function changeAdminMailFrom ( $from_email ) {
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $email_from = $this->applyEmailVars( !empty($settings['email_from']) ? $settings['email_from'] : '' );
        if ( !empty($email_from) && is_email($email_from) ) {
            $from_email = $email_from;
        }
        return $from_email;
    }

    public function changeAdminMailFromName ( $from_name ) {
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $email_from_name = $this->applyEmailVars( !empty($settings['email_from_name']) ? $settings['email_from_name'] : '' );
        if (!empty($email_from_name)) {
            $from_name = $email_from_name;
        }

        return $from_name;
    }    

    
    private function getAdminName(){
        $admin_data = get_users( 'role=Administrator' );
        $admin_name = $admin_data[0]->data->display_name;
        
        return apply_filters( 'scfp_form_get_admin_name', $admin_name, $this );  
    }
    
    private function getUserName(){
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_name_field = !empty($settings['user_name']) ? $settings['user_name'] : 'name';
        
        if( get_post_meta( $this->postId , "scfp_{$user_name_field}", true ) ){
            $user_name = get_post_meta( $this->postId , "scfp_{$user_name_field}", true );
        } else {
            $user_name = 'Visitor';
        }
        return apply_filters( 'scfp_form_get_user_name', $user_name, $this );   
    }
    
    private function getUserSubject( $defaultSubject = ''){
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_subject_field = !empty($settings['reply_subject_field']) ? $settings['reply_subject_field'] : 'subject';
        
        if( get_post_meta( $this->postId , "scfp_{$user_subject_field}", true ) ){
            $user_subject = get_post_meta( $this->postId , "scfp_{$user_subject_field}", true );
        } else {
            $user_subject = $defaultSubject;
        }
        return apply_filters( 'scfp_form_get_user_subject', $user_subject, $this );   
    }    
    
    private function getUserFormMessage( $defaultMessage = ''){
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_message_field = !empty($settings['reply_message_field']) ? $settings['reply_message_field'] : 'message';
        
        if( get_post_meta( $this->postId , "scfp_{$user_message_field}", true ) ){
            $user_message = get_post_meta( $this->postId , "scfp_{$user_message_field}", true );
        } else {
            $user_message = $defaultMessage;
        }
        return apply_filters( 'scfp_form_get_user_message', $user_message, $this );   
    }        
    
    
    private function getUserEmail(){
        $settings = SCFP()->getSettings()->getNotificationSettings();
        $user_email_field = !empty($settings['user_email']) ? $settings['user_email'] : 'email';
        
        if( get_post_meta( $this->postId , "scfp_{$user_email_field}", true ) ){
            $user_email = get_post_meta( $this->postId , "scfp_{$user_email_field}", true );
        } else {
            $user_email = '';
        }
        return apply_filters( 'scfp_form_get_user_email', $user_email, $this );   
    }    
    
    private function getUserMessage(){
        $data = $this->getTemplate('variables/data', $this->postId );
                
        return apply_filters( 'scfp_form_get_user_message', $data, $this );
    }
    
    public function applyEmailVars($text) {
        
        if( strpos( $text, '{$admin_name}' ) !== FALSE){

            $admin_name = $this->getAdminName();
            $text =  str_replace( '{$admin_name}', $admin_name, $text );
        }
        
        
        if( strpos( $text, '{$user_name}' ) !== FALSE){

            $user_name = $this->getUserName();
            $text =  str_replace( '{$user_name}', $user_name, $text );
        }
        
        if( strpos( $text, '{$user_email}' ) !== FALSE){
            
            $user_email = $this->getUserEmail();
            $text =  str_replace( '{$user_email}', $user_email, $text );
        }        
        
        if( strpos( $text, '{$user_subject}' ) !== FALSE){
            
            $user_subject = $this->getUserSubject();
            $text =  str_replace( '{$user_subject}', $user_subject, $text );
        }                
        
        if( strpos( $text, '{$user_message}' ) !== FALSE){
            
            $user_message = $this->getUserFormMessage();
            $text =  str_replace( '{$user_message}', $user_message, $text );
        }                        
        
        if( strpos( $text, '{$data}' ) !== FALSE){

            $data = $this->getUserMessage();
            $text =  str_replace( '{$data}', $data, $text );
        }

       
        return apply_filters( 'scfp_form_apply_email_vars', $text, $this );
    }
    
    public function sendHtmlContentType ($content_type) {
        return 'text/html';
    }
    
    private function redirect() {
        $location = $this->getRedirectUrl();
        if (!empty($location)) {
            wp_redirect( $location );
            die();
        }        
        return FALSE;
    }
    
    public function getRedirectUrl() {
        $formSettings =   SCFP()->getSettings()->objectToArray( SCFP()->getSettings()->getFormSettings() );
        $value = $formSettings['page_name'];
        
        $mode = isset($value['mode']) ? $value['mode'] : 'page';
        $page = isset($value['page']) ? $value['page'] : $value;
        $url = isset($value['url']) ? $value['url'] : '';        
        
        $result = '';
        switch ($mode) {
            case 'url':
                $result = $url;
                break;
            default:
                if (!empty($page)) {
                    $result = get_page_link( $page );
                }                
                break;
        }

        return $result;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getSession() {
        return $this->session;
    }

    public function getCaptcha() {
        return $this->captcha;
    }
    

    public function getNotifications() {
        return $this->notifications;
    }

    public function getRecaptcha() {
        return $this->recaptcha;
    }

}