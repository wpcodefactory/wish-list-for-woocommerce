<?php
/**
 * Toggle button template - Thumbnail button
 *
 * Add or remove an item from Wishlist
 *
 * @author  Thanks to IT
 * @version 1.6.5
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div data-item_id="<?php echo esc_attr( $params['product_id'] );?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<i class="<?php echo esc_attr( $params['btn_icon_class'] );?>" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<i class="<?php echo esc_attr( $params['btn_icon_class'] );?>" aria-hidden="true"></i>
	</div>
	<?php if ( $params['show_loading'] ): ?>
        <i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
	<?php endif; ?>
</div>