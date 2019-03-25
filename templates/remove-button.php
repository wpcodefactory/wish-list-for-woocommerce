<?php
/**
 * Toggle button template - Remove button
 *
 * Add or remove an item from Wishlist
 *
 * @author  Thanks to IT
 * @version 1.0.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div data-item_id="<?php echo get_the_ID(); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<i class="fa fa-2x fa-times-circle" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<i class="fa fa-2x fa-times-circle" aria-hidden="true"></i>
	</div>
	<i class="loading fa fa-refresh fa-spin fa-fw"></i>
</div>