<?php

if (!defined('ABSPATH')) exit;

?><article class="elfsight-admin-page-activation elfsight-admin-page" data-elfsight-admin-page-id="activation">
	<div class="elfsight-admin-page-heading">
		<h2><?php _e('Activation', $this->textDomain); ?></h2>

        <div class="elfsight-admin-page-activation-status">
            <span class="elfsight-admin-page-activation-status-activated"><?php _e('Activated', $this->textDomain); ?></span>
            <span class="elfsight-admin-page-activation-status-not-activated"><?php _e('Not Activated', $this->textDomain); ?></span>
        </div>

		<div class="elfsight-admin-page-heading-subheading">
			<?php _e('Activate your plugin in order to get awesome benefits!', $this->textDomain); ?>
		</div>
    </div>

    <div class="elfsight-admin-divider"></div>

    <div class="elfsight-admin-page-activation-benefits">
        <h4><?php _e('Get Awesome Benefits', $this->textDomain); ?></h4>

        <ul class="elfsight-admin-page-activation-benefits-list">
            <li class="elfsight-admin-page-activation-benefits-list-item-live-updates elfsight-admin-page-activation-benefits-list-item">
                <div class="elfsight-admin-page-activation-benefits-list-item-icon-container">
                    <span class="elfsight-admin-page-activation-benefits-list-item-icon">
                        <span class="elfsight-admin-icon-live-updates elfsight-admin-icon"></span>
                    </span>
                </div>

                <div class="elfsight-admin-page-activation-benefits-list-item-info">
                    <div class="elfsight-admin-page-activation-benefits-list-item-title"><?php _e('Simple live updates', $this->textDomain); ?></div>

                    <div class="elfsight-admin-page-activation-benefits-list-item-description"><?php _e('Keep the plugin up to date by installing the latest versions in one click directly from your admin panel. Forget about manual updating!', $this->textDomain); ?></div>
                </div>
            </li>

            <li class="elfsight-admin-page-activation-benefits-list-item-support elfsight-admin-page-activation-benefits-list-item">
                <div class="elfsight-admin-page-activation-benefits-list-item-icon-container">
                    <span class="elfsight-admin-page-activation-benefits-list-item-icon">
                        <span class="elfsight-admin-icon-support elfsight-admin-icon"></span>
                    </span>
                </div>

                <div class="elfsight-admin-page-activation-benefits-list-item-info">
                    <div class="elfsight-admin-page-activation-benefits-list-item-title"><?php _e('Fast & premium support', $this->textDomain); ?></div>

                    <div class="elfsight-admin-page-activation-benefits-list-item-description"><?php _e('Activating your plugin lets you get faster support. Make it easier for us to help you!', $this->textDomain); ?></div>
                </div>
            </li>
        </ul>
    </div>

    <div class="elfsight-admin-divider"></div>

	<div class="elfsight-admin-page-activation-form-container">
        <form class="elfsight-admin-page-activation-form" data-nonce="<?php echo wp_create_nonce($this->getOptionName('update_activation_data_nonce')); ?>" data-activation-url="<?php echo $this->updateUrl; ?>" data-activation-version="<?php echo $this->version; ?>">
            <h4><?php _e('Activate the plugin', $this->textDomain); ?></h4>

            <div class="elfsight-admin-page-activation-form-field">
                <label>
                    <span class="elfsight-admin-page-activation-form-field-label"><?php _e('Please enter your plugin\'s CodeCanyon purchase code', $this->textDomain); ?></span>
                    <input class="elfsight-admin-page-activation-form-activated-input" type="hidden" name="activated" value="<?php echo $activated; ?>">
                    <input class="elfsight-admin-page-activation-form-purchase-code-input" type="text" placeholder="<?php _e('Purchase code', $this->textDomain); ?>" name="purchase_code" value="<?php echo $purchase_code; ?>" class="regular-text" spellcheck="false" autocomplete="off">
                </label>
            </div>

            <div class="elfsight-admin-page-activation-form-message-success elfsight-admin-page-activation-form-message"><?php _e('The plugin is successfuly activated', $this->textDomain); ?></div>
            <div class="elfsight-admin-page-activation-form-message-error elfsight-admin-page-activation-form-message"><?php _e('Your purchase code is not valid', $this->textDomain); ?></div>
            <div class="elfsight-admin-page-activation-form-message-fail elfsight-admin-page-activation-form-message"><?php _e('Error occurred while checking your purchase code. Please, contact our support team via <a href="mailto:support@elfsight.com">support@elfsight.com</a>. We apologize for inconveniences.', $this->textDomain); ?></div>

            <div class="elfsight-admin-page-activation-form-field">
                <div class="elfsight-admin-page-activation-form-submit elfsight-admin-button-green elfsight-admin-button"><?php _e('Activate', $this->textDomain); ?></div>
            </div>
        </form>

        <div class="elfsight-admin-page-activation-faq">
            <h4><?php _e('FAQ', $this->textDomain); ?></h4>

            <ul class="elfsight-admin-page-activation-faq-list">
                <li class="elfsight-admin-page-activation-faq-list-item">
                    <div class="elfsight-admin-page-activation-faq-list-item-title"><?php _e('What is item purchase code?', $this->textDomain); ?></div>
                    <div class="elfsight-admin-page-activation-faq-list-item-text">
                        <?php printf(__('Purchase code is a license key, that you get after buying an item on <a href="%1$s" target="_blank">Codecanyon</a>.', $this->textDomain), $this->productUrl); ?>
                    </div>
                </li>

                <li class="elfsight-admin-page-activation-faq-list-item">
                    <div class="elfsight-admin-page-activation-faq-list-item-title"><?php _e('How to get my purchase code?', $this->textDomain); ?></div>
                    <div class="elfsight-admin-page-activation-faq-list-item-text">
                        <?php _e('After purchasing the item, go to <a href="http://codecanyon.net/downloads" target="_blank">http://codecanyon.net/downloads</a>, click "Download" and select “License Certificate & Purchase Code”. You’ll find your purchase code in the downloaded file. To find out more, read:<br><a href="https://elfsight.com/blog/2016/04/where-to-find-your-envato-purchase-code/" target="_blank">Where to find your Envato purchase code?</a>.', $this->textDomain); ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</article>