<?php
/**
 * Toggle button template
 * 
 * Add or remove an item from Wishlist
 *
 * @author  Algoritmika Ltd. 
 * @version 1.0.0
 */

//error_log(print_r($params,true));

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<button data-item_id="<?php echo get_the_ID() ?>" data-action="<?php echo esc_attr($params['btn_data_action']);?>" class="<?php echo esc_attr($params['btn_class']);?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<span class="alg-wc-wl-btn-text"><?php echo esc_html(__('Add to Wishlist', ALG_WC_WL_DOMAIN)); ?></span>
		<i class="fa fa-heart" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<span class="alg-wc-wl-btn-text"><?php echo esc_html(__('Remove from Wishlist', ALG_WC_WL_DOMAIN)); ?></span>
		<i class="fa fa-heart" aria-hidden="true"></i>
	</div>
	<i class="loading fa fa-refresh fa-spin fa-fw"></i>
</button>