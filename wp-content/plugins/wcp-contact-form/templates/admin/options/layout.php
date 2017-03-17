<?php 
    $args = new stdClass();
    $args->settings = $params;
    $args->key = isset( $_GET['tab'] ) ? $_GET['tab'] : 'scfp_form_settings';
    $args->tabs = $args->settings->getTabs();
    $args->fieldSet = $args->settings->getFieldSet();
    $args->data = $args->settings->getSettings($args->key);
    $args->fields = $args->settings->getFields($args->key);
    $title = !empty($args->settings->getConfig()->admin->options->title) ? $args->settings->getConfig()->admin->options->title : '';
?>
<?php if (!empty($title)) :?>
<div class="scfp-plugin-headline">
    <table>
        <tr>
            <td class="scfp-plugin-headline-icon">                                                                                               
                <img src="<?php echo SCFP()->getAssetUrl( 'images/icons/icon-128x128.png' )?>" width="128" height="128" />    
            </td>
            <td class="scfp-plugin-headline-info">
                <h1><?php echo $title;?></h1>
                <p> To start using our contact form on your site, please <strong>check settings</strong> on the tabs below and <strong>add to a page shortcode</strong> of the contact form via <strong>button in the TinyMCE toolbar</strong> (option should be activated in tab "Form" > "TinyMCE Support") or <strong>copy &amp; paste following shortcode</strong>: [wcp_contactform id="wcpform_1"]</p> 
                <p>If you really like our plugin, please <a href="https://wordpress.org/support/view/plugin-reviews/wcp-contact-form?rate=5#postform" target="blank" title="Rate Our Plugin" class="scfp-plugin-headline-rate">rate us</a>!</p>  
            </td>
            <td class="scfp-plugin-headline-links">
                <div class="scfp-plugin-headline-links-wrapper">
                    <h2>Useful Links</h2>
                    <ul>
                        <li><a href="http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/" target="_blank" title="Documentation"><span class="dashicons dashicons-book"></span> Documentation</a></li>
                        <li><a href="http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/faq/" target="_blank" title="FAQ"><span class="dashicons dashicons-info"></span> FAQ</a></li>
                        <li><a href="http://wpdemo.webcodin.com/stay-in-touch/" target="_blank" title="Support Form"><span class="dashicons dashicons-sos"></span> Support Form</a></li>
                        <li><a href="http://wpdemo.webcodin.com/stay-in-touch/" target="_blank" title="Live Demo"><span class="dashicons dashicons-email-alt"></span> Live Demo</a></li>
                    </ul>                 
                </div>
            </td>
        </tr>
    </table>
</div>
<?php endif;?>



<div class="wrap scfp-form-wrap">
    <?php 
        screen_icon();
        settings_errors();
        
        echo $args->settings->getParentModule()->getTemplate('admin/options/render-tabs', $args);
    ?>
    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( $args->key ); ?>
        
        <?php echo $args->settings->getParentModule()->getTemplate('admin/options/render-page', $args); ?>
        
        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit">
            <a class="button button-primary" href="?page=<?php echo $args->settings->getPage();?>&tab=<?php echo $args->key;?>&reset-settings=true" >Reset to Default</a>
        </p>
    </form>
</div>