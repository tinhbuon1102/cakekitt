<?php

if (!defined('ABSPATH')) exit;

?><nav class="elfsight-admin-menu">
    <ul class="elfsight-admin-menu-list">
        <li class="elfsight-admin-menu-list-item"><a href="#/widgets/" data-elfsight-admin-page="widgets"><?php _e('Widgets', $this->textDomain); ?></a></li>
        <li class="elfsight-admin-menu-list-item"><a href="#/support/" data-elfsight-admin-page="support"><?php _e('Support', $this->textDomain); ?></a></li>
        <li class="elfsight-admin-menu-list-item"><a href="#/preferences/" data-elfsight-admin-page="preferences"><?php _e('Preferences', $this->textDomain); ?></a></li>
        <li class="elfsight-admin-menu-list-item-activation elfsight-admin-menu-list-item">
            <a href="#/activation/" data-elfsight-admin-page="activation" class="elfsight-admin-tooltip-trigger">
                <?php _e('Activation', $this->textDomain); ?>

                <span class="elfsight-admin-menu-list-item-notification"></span>

                <span class="elfsight-admin-tooltip-content">
                    <span class="elfsight-admin-tooltip-content-inner">
                        <?php _e('The plugin is not activated', $this->textDomain); ?>
                    </span>
                </span>
            </a>
        </li>
    </ul>
</nav>   