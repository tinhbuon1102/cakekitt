<?php
if(!defined("VSP_PATH")){exit;}
if(!class_exists("VSP_Framework_Core_Ajax")){
    class VSP_Framework_Core_Ajax {

        public function __construct(){
            add_action( 'wp_ajax_vsp-addon-action', array($this,'handle_request'));
        }

        public function handle_request(){
            if(isset($_REQUEST['hook_slug'])){
                do_action($_REQUEST['hook_slug'].'_handle_addon_request');
            }

            wp_send_json_error();
        }
    }
}