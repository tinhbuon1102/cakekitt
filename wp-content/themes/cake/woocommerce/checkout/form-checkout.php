<div class="row" id="checkoutbox">
<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//echo('<div class="row" id="checkoutbox"><div class="col-md-8 columns">');

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
//echo('</div></div>');

?>

<form name="checkout" style="overflow: hidden;" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
<!--<div class="row" id="checkoutbox">-->
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
<div class="col-md-8 columns">
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>
		<div class="select-datetime">
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		</div>
	</div>
		

	<?php endif; ?>
<div class="col-md-4 columns position-static pt-md-4 pt-sm-2 pb-sm-4">
	<div class="ordercake-cart-sidebar-container2">
	<div class="cake-cart-sidebar">
	<div class="panel mb-3">
	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	</div><!--/panel-->
	</div><!--/cake-cart-sidebar-->
	</div><!--/cake-cart-sidebar-container-->
	</div><!--/col-md-4-->
	
	
</form>
</div><!--/checkoutbox-->

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
