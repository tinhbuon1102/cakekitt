<?php global $woocommerce; ?>
<div class="wrap">
	<h2><?php echo  __( 'General Setting', 'woocommerce-for-japan' );?></h2>
	<div class="wc4jp-settings metabox-holder">
		<div class="wc4jp-sidebar">
			<div class="wc4jp-credits">
				<h3 class="hndle"><?php echo __( 'WooCommerce for Japan', 'woocommerce-for-japan' ) . ' ' . WC4JP_VERSION;?></h3>
				<div class="inside">
					<h4 class="inner"><?php echo __( 'Need support?', 'woocommerce-for-japan' );?></h4>
					<p class="inner"><?php echo sprintf(__( 'If you are having problems with this plugin, talk about them in the <a href="%s" target="_blank" title="Support forum">Support forum</a>.', 'woocommerce-for-japan' ),'https://support.artws.info/forums/forum/wordpress-official/woocommerce-for-japan-plugins-forum/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=top-support');?></p>
					<p class="inner"><?php echo sprintf(__( 'If you need professional support, please consider about <a href="%1$s" target="_blank" title="Site Construction Support service">Site Construction Support service</a> or <a href="%2$s" target="_blank" title="Maintenance Support service">Maintenance Support service</a>.', 'woocommerce-for-japan' ),'https://wc.artws.info/product-category/setting-support/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=setting-support','https://wc.artws.info/product-category/maintenance-support/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=maintenance-support');?></p>
					<hr />
					<h4 class="inner"><?php echo __( 'Finished Latest Update, WordPress and WooCommerce?', 'woocommerce-for-japan' );?></h4>
					<p class="inner"><?php echo sprintf(__( 'One the security, latest update is the most important thing. If you need site maintenance support, please consider about <a href="%s" target="_blank" title="Support forum">Site Maintenance Support service</a>.', 'woocommerce-for-japan' ),'https://wc.artws.info/shop/maintenance-support/woocommerce-professional-support-subscription/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=maintenance-support');?>
					</p>
					<hr />
					<h4 class="inner"><?php echo __( 'Where is the study group of Woocommerce in Japan?', 'woocommerce-for-japan' );?></h4>
					<p class="inner"><?php echo sprintf(__( '<a href="%s" target="_blank" title="Tokyo WooCommerce Meetup">Tokyo WooCommerce Meetup</a>.', 'woocommerce-for-japan' ),'http://meetup.com/ja-JP/Tokyo-WooCommerce-Meetup/?');?><br />
					<?php echo sprintf(__( '<a href="%s" target="_blank" title="Kansai WooCommerce Meetup">Kansai WooCommerce Meetup</a>.', 'woocommerce-for-japan' ),'http://meetup.com/ja-JP/Kansai-WooCommerce-Meetup/');?><br />
					<?php echo __('Join Us!', 'woocommerce-for-japan' );?>
					</p>
					<?php if ( ! get_option( 'wc4jp_admin_footer_text_rated' ) ) :?>
					<hr />
					<h4 class="inner"><?php echo __( 'Do you like this plugin?', 'woocommerce-for-japan' );?></h4>
					<p class="inner"><a href="https://wordpress.org/support/plugin/woocommerce-for-japan/reviews/#postform" target="_blank" title="' . __( 'Rate it 5', 'woocommerce-for-japan' ) . '"><?php echo __( 'Rate it 5', 'woocommerce-for-japan' )?> </a><?php echo __( 'on WordPress.org', 'woocommerce-for-japan' ); ?><br />
					</p>
					<?php endif;?>
					<hr />
					<p class="wc4jp-link inner"><?php echo __( 'Created by', 'woocommerce-for-japan' );?> <a href="https://wc.artws.info/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=created-by" target="_blank" title="Artisan Workshop"><img src="https://wc.artws.info/wp-content/uploads/2016/08/woo-logo.png" title="Artsain Workshop" alt="Artsain Workshop" class="wc4jp-logo" /></a><br />
					<a href="https://docs.artws.info/?utm_source=wc4jp-settings&utm_medium=link&utm_campaign=created-by" target="_blank"><?php echo __( 'WooCommerce Doc in Japanese', 'woocommerce-for-japan' );?></a>
					</p>
				</div>
			</div>
		</div>
		<form id="wc4jp-setting-form" method="post" action="">
			<div id="main-sortables" class="meta-box-sortables ui-sortable">
<?php
	//Display Setting Screen
	settings_fields( 'wc4jp_options' );
	$this->do_settings_sections( 'wc4jp_options' );
?>
			<p class="submit">
<?php
	submit_button( '', 'primary', 'save_wc4jp_options', false );
?>
			</p>
			</div>
		</form>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function ($) {
		// close postboxes that should be closed
		$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		// postboxes setup
		postboxes.add_postbox_toggles('wc4jp-options');
	});
	//]]>
	</script>
</div>