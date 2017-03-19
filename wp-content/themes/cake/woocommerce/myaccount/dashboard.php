<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<h1 class="mb-5">My Account</h1>
<div class="account-logout">
	<a class="button simple" title="Log out" href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );?>"><?php _e( 'Log out', 'woocommerce' ); ?></a>
</div>
<div class="account-welcome">
<p>
	<?php
		echo sprintf( esc_attr__( 'Hello %s%s%s (not %2$s? %sSign out%s)', 'woocommerce' ), '<strong>', esc_html( $current_user->display_name ), '</strong>', '<a href="' . esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) . '">', '</a>' );
	?>
</p>

<p>
	<?php
		echo sprintf( esc_attr__( 'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sshipping and billing addresses%2$s and %4$sedit your password and account details%2$s.', 'woocommerce' ), '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">', '</a>', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address' ) ) . '">', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-account' ) ) . '">' );
	?>
</p>
</div>
<div class="account-box account-order-history clearfix fright">
	<h3 class="section-header"><?php _e( 'Order History', 'woocommerce' ); ?></h3>
	<div class="account-box-content clearfix">
		<div class="account-box-image"><i class="linericon-clipboard-text"></i></div>
		<p><?php _e( 'You haven’t placed any orders yet', 'woocommerce' ); ?></p>
		
	</div>
</div>
<div class="account-box account-details clearfix fleft">
	<h3 class="section-header"><?php _e( 'Account Details', 'woocommerce' ); ?><a class="section-header-note" href="#"><?php _e( 'View details', 'woocommerce' ); ?></a></h3>
	<div class="account-box-content clearfix">
	<div class="account-box-image"><i class="linericon-clipboard-user"></i></div>
		<p class="account-box-label">Lastname Firstname</p>
		<p>1989.11.22</p>
		<p>kyoooko1122@icloud.com</p>
	</div>
</div>
<div class="account-box account-details clearfix fleft">
	<h3 class="section-header"><?php _e( 'Shipping info', 'woocommerce' ); ?><a class="section-header-note" href="#"><?php _e( 'View details', 'woocommerce' ); ?></a></h3>
	<div class="account-box-content clearfix">
	<div class="account-box-image"><i class="linericon-truck"></i></div>
		<p class="account-box-label">Store Name</p>
		<p>postcode</p>
		<p>PrefCityAddress1Address2</p>
	</div>
</div>
<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
?>
