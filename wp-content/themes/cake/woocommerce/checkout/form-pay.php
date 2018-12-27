<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total_amount = cake_stripe_woocommerce_order_amount_total($order->order_total, $order, true);
$total_amount_html = $total_amount ? wc_price( (double)$total_amount, array( 'currency' => $order->get_order_currency() ) ) : '';
$total_amount_html = strip_tags($total_amount_html);
$total_amount_html = str_replace('&yen;', '¥', $total_amount_html);

?>
<script type="text/javascript">
	var stripe_total = <?php echo $total_amount ? (double)$total_amount : 0?>;
	var stripe_total_html = '<?php echo $total_amount ? wc_price( (double)$total_amount, array( 'currency' => $order->get_order_currency() ) ) : ''?>';
	var alert_stripe_text = '<?php echo sprintf(__('We charged 5%% fee for stripe payment, so your total payment will be %s', 'cake'), $total_amount_html)?>';
</script>
<h1 class="form_payment_title center-line"><span>お支払い画面</span></h1>
<form id="order_review" method="post">

	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php _e( 'Qty', 'woocommerce' ); ?></th>
				<th class="product-total"><?php _e( 'Totals', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( sizeof( $order->get_items() ) > 0 ) : ?>
				<?php foreach ( $order->get_items() as $item_id => $item ) : ?>
					<?php
						if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
							continue;
						}
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<td class="product-name">
							<?php
								echo apply_filters( 'woocommerce_order_item_name', esc_html( $item['name'] ), $item, false );

								do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );
								$order->display_item_meta( $item );
								do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
							?>
						</td>
						<td class="product-quantity"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', esc_html( $item['qty'] ) ) . '</strong>', $item ); ?></td>
						<td class="product-subtotal"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<?php if ( $totals = $order->get_order_item_totals() ) : ?>
				<?php foreach ( $totals as $total ) : ?>
					<tr>
						<th scope="row" colspan="2"><?php echo $total['label']; ?></th>
						<td class="product-total"><?php echo $total['value']; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tfoot>
	</table>
	
	<div id="form_order_detail">
		<?php do_action('woocommerce_form_pay_after_order_table', $order)?>
	</div>

	<div id="payment">
		<?php if ( $order->needs_payment() ) : 
		$orderDetail = new WC_Order( $order->id );
		$items = $orderDetail->get_items();
		$item_keys = array_keys($items);
		
		$order_type = wc_get_order_item_meta( $item_keys[0], '_order_type');
		?>
			<ul class="wc_payment_methods payment_methods methods">
				<?php
					if ( ! empty( $available_gateways ) ) {
						foreach ( $available_gateways as $gatewayName => $gateway ) {
							if ($gatewayName == 'other_payment') continue;
							if ($order_type == KITT_CUSTOM_ORDER && $gatewayName == 'pis') continue;
							
							wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
						}
					} else {
						echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>';
					}
				?>
			</ul>
		<?php endif; ?>
		<div class="form-row">
			<input type="hidden" name="woocommerce_pay" value="1" />

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

			<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<input type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

			<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-pay' ); ?>
		</div>
	</div>
</form>
