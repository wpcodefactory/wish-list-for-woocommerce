<?php
/**
 * Wishlist for WooCommerce - Add all to cart button template.
 *
 * Displays a form with a button that adds all wishlist products to the cart.
 *
 * @version 3.5.8
 * @since   3.5.8
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( empty( $items ) || ! is_array( $items ) ) {
	return;
}
?>
<form method="post" action="" class="alg-wc-wl-add-all-to-cart-form">
	<input type="hidden" name="alg_wc_wl_add_all" value="1">
	<?php wp_nonce_field( $nonce_action, 'alg_wc_wl_add_all_nonce', true, true ); ?>
	<?php foreach ( $items as $item_id => $quantity ) : ?>
		<input type="hidden" name="alg_wc_wl_add_all_items[<?php echo absint( $item_id ); ?>]" value="<?php echo absint( $quantity ); ?>">
	<?php endforeach; ?>
	<button type="submit" class="<?php echo esc_attr( $btn_class ); ?>">
		<span class="alg-wc-wl-btn-text"><?php echo esc_html( $label ); ?></span>
	</button>
</form>
