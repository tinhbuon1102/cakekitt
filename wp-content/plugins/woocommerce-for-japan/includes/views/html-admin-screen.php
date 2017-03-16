<div class="wrap woocommerce">
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wc4jp-options') ?>" class="nav-tab <?php echo ($tab == 'setting') ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Setting', 'woocommerce-for-japan' )?></a><a href="<?php echo admin_url('admin.php?page=wc4jp-options&tab=info') ?>" class="nav-tab <?php echo ($tab == 'info') ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Infomations', 'woocommerce-for-japan' )?></a>
    </h2>
	<?php
		switch ($tab) {
			case "setting" :
				$this->admin_setting_page();
			break;
			default :
				$this->admin_info_page();
			break;
		}
	?>
</div>
