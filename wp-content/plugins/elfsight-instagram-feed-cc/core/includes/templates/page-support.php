<?php

if (!defined('ABSPATH')) exit;

?><article class="elfsight-admin-page-support elfsight-admin-page" data-elfsight-admin-page-id="support">
	<div class="elfsight-admin-page-heading">
		<h2><?php _e('Support', $this->textDomain); ?></h2>

		<div class="elfsight-admin-page-heading-subheading">
			<?php _e('We understand all the importance of product support for our customers. That’s why we are ready to solve all your issues and answer any questions related to our plugin.', $this->textDomain); ?>
		</div>
    </div>

    <div class="elfsight-admin-divider"></div>

	<div class="elfsight-admin-page-support-ticket">
		<h4><?php _e('Before submitting a ticket, make sure that:', $this->textDomain); ?></h4>

		<ul class="elfsight-admin-page-support-ticket-steps">
			<li class="elfsight-admin-page-support-ticket-steps-item-latest-version elfsight-admin-page-support-ticket-steps-item">
				<span class="elfsight-admin-page-support-ticket-steps-item-icon">
					<span class="elfsight-admin-icon-support-latest-version elfsight-admin-icon"></span>
				</span>

				<span class="elfsight-admin-page-support-ticket-steps-item-label"><?php _e('You use the latest version.', $this->textDomain); ?></span>
			</li>

			<li class="elfsight-admin-page-support-ticket-steps-item-javascript-errors elfsight-admin-page-support-ticket-steps-item">
				<span class="elfsight-admin-page-support-ticket-steps-item-icon">
					<span class="elfsight-admin-icon-support-javascript-errors elfsight-admin-icon"></span>
				</span>

				<span class="elfsight-admin-page-support-ticket-steps-item-label"><?php _e('There are no javascript errors on your website.', $this->textDomain); ?></span>
			</li>

			<li class="elfsight-admin-page-support-ticket-steps-item-documentation elfsight-admin-page-support-ticket-steps-item">
				<span class="elfsight-admin-page-support-ticket-steps-item-icon">
					<span class="elfsight-admin-icon-support-documentation elfsight-admin-icon"></span>
				</span>

				<span class="elfsight-admin-page-support-ticket-steps-item-label"><?php _e('The documentation doesn\'t help.', $this->textDomain); ?></span>
			</li>
		</ul>

		<div class="elfsight-admin-page-support-ticket-submit">
			<?php printf(__('Nothing of the above helped? <a href="%1$s" target="_blank">Open a ticket</a> at our Support Center.', $this->textDomain), $this->supportUrl); ?>
		</div>
	</div>

	<div class="elfsight-admin-divider"></div>

	<div class="elfsight-admin-page-support-includes-container">
		<div class="elfsight-admin-page-support-includes">
			<h4><?php _e('Our Support Includes', $this->textDomain); ?></h4>

			<ul class="elfsight-admin-page-support-includes-list">
				<li class="elfsight-admin-page-support-includes-list-item">
					<div class="elfsight-admin-page-support-includes-list-item-title"><?php _e('Fixing product bugs', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-includes-list-item-description"><?php _e('Our product doesn’t work properly on your website? Report your issue or bug by describing it in detail and providing us with a link to your website. We will do our best to find a solution.', $this->textDomain); ?></p>
				</li>
				
				<li class="elfsight-admin-page-support-includes-list-item">
					<div class="elfsight-admin-page-support-includes-list-item-title"><?php _e('Life-time updates', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-includes-list-item-description"><?php _e('We release new updates and features on a regular basis. Just don’t forget to check for the latest version in your WordPress admin panel.', $this->textDomain); ?></p>
				</li>

				<li class="elfsight-admin-page-support-includes-list-item">
					<div class="elfsight-admin-page-support-includes-list-item-title"><?php _e('Customer-friendly development', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-includes-list-item-description"><?php _e('We are open to your ideas. If you need some specific features, that might also improve our products, then just drop us a line. We will consider implementing them in our future updates.', $this->textDomain); ?></p>
				</li>
			</ul>
		</div>

		<div class="elfsight-admin-page-support-not-includes">
			<h4><?php _e('Our Support Doesn’t Include', $this->textDomain); ?></h4>
			
			<ul class="elfsight-admin-page-support-not-includes-list">
				<li class="elfsight-admin-page-support-not-includes-list-item">
					<div class="elfsight-admin-page-support-not-includes-list-item-title"><?php _e('Plugin installation', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-not-includes-list-item-description"><?php _e('We don’t provide installation services for our plugins. However, we\'re happy to provide you with installation tutorials. And if any errors come up during installation, feel free to contact us.', $this->textDomain); ?></p>
				</li>
				
				<li class="elfsight-admin-page-support-not-includes-list-item">
					<div class="elfsight-admin-page-support-not-includes-list-item-title"><?php _e('Plugin customization', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-not-includes-list-item-description"><?php _e('We don’t provide plugin customization services. If you need to modify the way some features work, share your ideas with us, and we will consider them for future updates.', $this->textDomain); ?></p>
				</li>

				<li class="elfsight-admin-page-support-not-includes-list-item">
					<div class="elfsight-admin-page-support-not-includes-list-item-title"><?php _e('3rd-party issues', $this->textDomain); ?></div>
					
					<p class="elfsight-admin-page-support-not-includes-list-item-description"><?php _e('We don’t fix bugs or issues related to other plugins and themes, created by 3rd-party developers. Also we don’t provide integration services for 3rd-party plugins and themes.', $this->textDomain); ?></p>
				</li>
			</ul>
		</div>
	</div>
</article>