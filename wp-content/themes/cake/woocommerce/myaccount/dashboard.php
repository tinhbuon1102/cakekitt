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

$customer_id = get_current_user_id();
$customer = new WC_Customer($customer_id);

$birth_date = get_user_meta( $customer_id, 'birth_date', true);
$yearMonthDays = kitt_get_year_month_day();
$aCountrySates = getCountryState();

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
	<div class="account-box-content clearfix">
		<!--show latest orders-->
		<?php
		
		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => 'shop_order',
			'post_status' => 'publish'
		) ) );
		
 
if ( $customer_orders && count( $customer_orders >= $number_of_orders ) ) { ?>
<h3 class="section-header"><?php _e( 'Order History', 'woocommerce' ); ?><a class="section-header-note" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) )?>"><?php _e( 'View all orders', 'woocommerce' ); ?></a></h3>
<div class="account-box-image"><i class="linericon-clipboard-text"></i></div>
<div class="latest-order-table">
	<table class="shop_table my_account_latest_orders">
 
		<thead>
			<tr>
				<th class="order-number"><span class="nobr"><?php _e( 'Order Number', 'woocommerce' ); ?></span></th>
				<th class="order-date"><span class="nobr"><?php _e( 'Date', 'woocommerce' ); ?></span></th>
				<th class="order-status"><span class="nobr"><?php _e( 'Status', 'woocommerce' ); ?></span></th>
				<th class="order-actions"> </th>
			</tr>
		</thead>
 
		<tbody>
		<?php
			foreach ( $customer_orders as $customer_order ) {
				$order = new WC_Order();
 
				$order->populate( $customer_order );
 
				$status = wc_get_order_status_name( $order->get_status() );
				$item_count = $order->get_item_count();
 
				?>
				<tr class="order">
					<td class="order-number">
						<a href="<?php echo $order->get_view_order_url(); ?>">
							<?php echo $order->get_order_number(); ?>
						</a>
					</td>
					<td class="order-date">
						<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
					</td>
					<td class="order-status" style="text-align:left; white-space:nowrap;">
						<?php echo $status; ?>
					</td>
					<td class="order-actions">
						<?php
							$actions = array();
 
							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);
							}
 
							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
									'name' => __( 'Cancel', 'woocommerce' )
								);
							}
 
							$actions['view'] = array(
								'url'  => $order->get_view_order_url(),
								'name' => __( 'View', 'woocommerce' )
							);
							
							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );
 
							if ($actions) {
								foreach ( $actions as $key => $action ) {
									echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
								}
							}
						?>
					</td>
				</tr><?php
			}
		?></tbody>
 
	</table>
</div>
<?php } else { ?>
	<h3 class="section-header"><?php _e( 'Order History', 'woocommerce' ); ?></h3>
	<div class="account-box-image"><i class="linericon-clipboard-text"></i></div>
	<p><?php _e( 'You haven’t placed any orders yet', 'woocommerce' ); ?></p>
<?php } ?>
		
	</div>
</div>
<div class="account-box account-details clearfix fleft">
	<h3 class="section-header"><?php _e( 'Account Details', 'woocommerce' ); ?><a class="section-header-note" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) )?>"><?php _e( 'View details', 'woocommerce' ); ?></a></h3>
	<div class="account-box-content clearfix">
	<div class="account-box-image"><i class="linericon-clipboard-user"></i></div>
		<p class="account-box-label"><?php echo  get_user_meta( $customer_id, 'billing_last_name', true ) . get_user_meta( $customer_id, 'billing_first_name', true );?></p>
		<?php 
		if ($birth_date)
		{
			echo '<p>' . $birth_date['year'] . '.' . $yearMonthDays['months'][$birth_date['month']] . '.' . $birth_date['day'] . '</p>';
		}
		?>
		<p><?php echo  get_user_meta( $customer_id, 'billing_email', true )?></p>
	</div>
</div>
<div class="account-box account-details clearfix fleft">
	<h3 class="section-header"><?php _e( 'Shipping info', 'woocommerce' ); ?><a class="section-header-note" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) )?>"><?php _e( 'View details', 'woocommerce' ); ?></a></h3>
	<div class="account-box-content clearfix">
	<div class="account-box-image"><i class="linericon-truck"></i></div>
		<p class="account-box-label"><?php echo  get_user_meta( $customer_id, 'shipping_first_name', true )?></p>
		<p><?php echo $customer->postcode?></p>
		<p><?php echo @$aCountrySates['state'][$customer->shipping_state] . $customer->shipping_city . $customer->shipping_address_1 . $customer->shipping_address_2?></p>
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
