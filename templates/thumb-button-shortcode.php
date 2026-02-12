<?php
/**
 * Thumb button shortcode template.
 *
 *
 * @version 3.3.5
 * @since   3.3.5
 * @author  WPFactory
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
?>

<div class="alg-wc-wl-thumb-btn-shortcode-wrapper">
	<div data-item_id="<?php echo esc_attr( $params['product_id'] ); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?> ">
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
			<i class="<?php echo esc_attr( $params['btn_icon_class'] ); ?>" aria-hidden="true"></i>
		</div>
		<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
			<i class="<?php echo esc_attr( $params['btn_icon_class_added'] ); ?>" aria-hidden="true"></i>
		</div>
		<?php if ( $params['show_loading'] ): ?>
			<i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
		<?php endif; ?>
	</div>
</div>