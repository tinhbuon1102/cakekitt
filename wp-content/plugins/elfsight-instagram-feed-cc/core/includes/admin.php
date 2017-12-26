<?php

if (!defined('ABSPATH')) exit;


if (!class_exists('ElfsightPluginAdmin')) {
    class ElfsightPluginAdmin {
        private $name;
        private $description;
        private $slug;
        private $version;
        private $textDomain;
        private $editorSettings;
        private $editorPreferences;
        private $menuIcon;
        private $menuId;

        private $pluginName;
        private $pluginFile;

        private $updateUrl;
        private $previewUrl;
        private $observerUrl;

        private $productUrl;
        private $supportUrl;

        private $widgetsApi;

        private $capability;

        public function __construct($config, $widgetsApi) {
            $this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->editorSettings = $config['editor_settings'];
            $this->editorPreferences = $config['editor_preferences'];
            $this->menuIcon = $config['menu_icon'];

            $this->pluginName = $config['plugin_name'];
            $this->pluginFile = $config['plugin_file'];

            $this->updateUrl = $config['update_url'];
            $this->previewUrl = $config['preview_url'];
            $this->observerUrl = !empty($config['observer_url']) ? $config['observer_url'] : null;

            $this->productUrl = $config['product_url'];
            $this->supportUrl = $config['support_url'];

            $this->widgetsApi = $widgetsApi;

            add_action('admin_menu', array($this, 'addMenuPage'));
            add_action('admin_init', array($this, 'registerAssets'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
            add_action('wp_ajax_' . $this->getOptionName('update_preferences'), array($this, 'updatePreferences'));
            add_action('wp_ajax_' . $this->getOptionName('update_activation_data'), array($this, 'updateActivationData'));

            $this->capability = apply_filters('elfsight_admin_capability', 'manage_options');
        }

        public function addMenuPage() {
            $this->menuId = add_menu_page($this->name, $this->name, $this->capability, $this->slug, array($this, 'getPage'), $this->menuIcon);
        }

        public function registerAssets() {
            wp_register_style('elfsight-admin', plugins_url('assets/elfsight-admin.css', $this->pluginFile), array(), $this->version);
            wp_register_script('elfsight-admin', plugins_url('assets/elfsight-admin.js', $this->pluginFile), array('jquery'), $this->version, true);
        }

        public function enqueueAssets($hook) {
            if ($hook == $this->menuId) {
                wp_enqueue_style('elfsight-admin');
                wp_enqueue_script('elfsight-admin');
            }
        }

        public function getPage() {
            $this->widgetsApi->upgrade();

            $widgets_clogged = get_option($this->getOptionName('widgets_clogged'), '');

            // preferences
            $uploads_dir_params = wp_upload_dir();

            $uploads_dir = $uploads_dir_params['basedir'] . '/' . $this->slug;

            $custom_css_path = $uploads_dir . '/' . $this->slug . '-custom.css';
            $custom_js_path = $uploads_dir . '/' . $this->slug . '-custom.js';
            $preferences_custom_css = is_readable($custom_css_path) ? file_get_contents($custom_css_path) : '';
            $preferences_custom_js = is_readable($custom_js_path) ? file_get_contents($custom_js_path) : '';
            $preferences_force_script_add = get_option($this->getOptionName('force_script_add'));

            // activation
            $purchase_code = get_option($this->getOptionName('purchase_code'), '');
            $activated = get_option($this->getOptionName('activated'), '') === 'true';
            $latest_version = get_option($this->getOptionName('latest_version'), '');
            $last_check_datetime = get_option($this->getOptionName('last_check_datetime'), '');
            $has_new_version = !empty($latest_version) && version_compare($this->version, $latest_version, '<');

            $activation_css_classes = '';
            if ($activated) {
                 $activation_css_classes .= 'elfsight-admin-activation-activated ';
            }
            else if (!empty($purchase_code)) {
                $activation_css_classes .= 'elfsight-admin-activation-invalid ';
            }
            if ($has_new_version) {
                $activation_css_classes .= 'elfsight-admin-activation-has-new-version ';
            }

            ?><div class="<?php echo $activation_css_classes; ?>elfsight-admin wrap">
                <h2 class="elfsight-admin-wp-notifications-hack"></h2>

                <div class="elfsight-admin-wrapper">
                    <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'header.php'))); ?>

                    <main class="elfsight-admin-main elfsight-admin-loading" data-elfsight-admin-slug="<?php echo $this->slug; ?>" data-elfsight-admin-widgets-clogged="<?php echo $widgets_clogged; ?>">
                        <div class="elfsight-admin-loader"></div>

                        <div class="elfsight-admin-menu-container">
                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'menu.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'menu-actions.php'))); ?>
                        </div>

                        <div class="elfsight-admin-pages-container">
                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-welcome.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-widgets.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-edit-widget.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-support.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-preferences.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-activation.php'))); ?>

                            <?php require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'page-error.php'))); ?>
                        </div>
                    </main>

                    <?php //require_once(plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'other-products.php'))); ?>
                </div>
            </div>
        <?php }

        public function updatePreferences() {
            if (!wp_verify_nonce($_REQUEST['nonce'], $this->getOptionName('update_preferences_nonce'))) {
                exit;
            }

            $result = array();

            // force script add
            if (isset($_REQUEST['preferences_force_script_add'])) {
                $result['success'] = true;

                update_option($this->getOptionName('force_script_add'),  $_REQUEST['preferences_force_script_add']);
            }

            // custom css
            if (isset($_REQUEST['preferences_custom_css'])) {
                $file_type = 'css';
                $file_content = $_REQUEST['preferences_custom_css'];
            }

            // custom js
            if (isset($_REQUEST['preferences_custom_js'])) {
                $file_type = 'js';
                $file_content = $_REQUEST['preferences_custom_js'];
            }

            if (isset($file_content)) {
                $uploads_dir_params = wp_upload_dir();
                $uploads_dir = $uploads_dir_params['basedir'] . '/' . $this->slug;

                if (!is_dir($uploads_dir)) {
                    wp_mkdir_p($uploads_dir);
                }

                $path = $uploads_dir . '/' . $this->slug . '-custom.' . $file_type;

                if (file_exists($path) && !is_writable($path)) {
                    $result['success'] = false;
                    $result['error'] = __('The file can not be overwritten. Please check the file permissions.', $this->textDomain);

                } else {
                    $result['success'] = true;

                    file_put_contents($path, stripslashes($file_content));
                }
            }
           
            exit(json_encode($result));
        }

        public function updateActivationData() {
            if (!wp_verify_nonce($_REQUEST['nonce'], $this->getOptionName('update_activation_data_nonce'))) {
                exit;
            }

            update_option($this->getOptionName('purchase_code'), !empty($_REQUEST['purchase_code']) ? $_REQUEST['purchase_code'] : '');
            update_option($this->getOptionName('activated'), !empty($_REQUEST['activated']) ? $_REQUEST['activated'] : '');
        }

        private function getOptionName($name) {
            return str_replace('-', '_', $this->slug) . '_' . $name;
        }
    }
}

?>