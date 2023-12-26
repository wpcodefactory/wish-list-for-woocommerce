<?php
/**
 * Toggle button template - Default button
 *
 * Add or remove an item from Wishlist
 *
 * @author  WPFactory
 * @version 1.7.2
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="alg-wc-wl-btn-wrapper">
	<button data-item_id="<?php echo esc_attr( $params['product_id'] );?>" data-action="<?php echo esc_attr( $params['btn_data_action'] );?>" class="<?php echo esc_attr( $params['btn_class'] );?> ">
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
			<span class="alg-wc-wl-btn-text"><?php echo esc_html( $params['add_label']); ?></span>
			<i class="<?php echo esc_attr( $params['btn_icon_class'] );?>" aria-hidden="true"></i>
		</div>
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
			<span class="alg-wc-wl-btn-text"><?php echo esc_html( $params['remove_label']); ?></span>
			<i class="<?php echo esc_attr( $params['btn_icon_class_added'] );?>" aria-hidden="true"></i>
		</div>
		<?php if ( $params['show_loading'] ): ?>
		    <i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
        <?php endif; ?>
	</button>
</div>