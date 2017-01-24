<?php
/**
 * Toggle button template - Default button
 *
 * Add or remove an item from Wishlist
 *
 * @author  Algoritmika Ltd.
 * @version 1.0.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="alg-wc-wl-btn-wrapper">
	<button data-item_id="<?php echo get_the_ID(); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] );?>" class="<?php echo esc_attr( $params['btn_class'] );?>">
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
			<span class="alg-wc-wl-btn-text"><?php echo esc_html( __( 'Add to Wish list', 'alg-wish-list-for-woocommerce' ) ); ?></span>
			<i class="fa fa-heart" aria-hidden="true"></i>
		</div>
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
			<span class="alg-wc-wl-btn-text"><?php echo esc_html( __( 'Remove from Wish list', 'alg-wish-list-for-woocommerce' ) ); ?></span>
			<i class="fa fa-heart" aria-hidden="true"></i>
		</div>
		<i class="loading fa fa-refresh fa-spin fa-fw"></i>
	</button>
</div>