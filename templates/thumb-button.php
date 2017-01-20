<?php
/**
 * Toggle button template - Thumbnail button
 *
 * Add or remove an item from Wishlist
 *
 * @author  Algoritmika Ltd.
 * @version 1.0.0
 */

//error_log(print_r($params,true));

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div data-item_id="<?php echo get_the_ID(); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<i class="fa fa-heart" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<i class="fa fa-heart" aria-hidden="true"></i>
	</div>
	<i class="loading fa fa-refresh fa-spin fa-fw"></i>
</div>