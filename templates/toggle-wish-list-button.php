<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<button data-item_id="<?php echo get_the_ID() ?>" data-action="<?php echo esc_attr($btn_data_action);?>" class="<?php echo esc_attr($btn_class);?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<span class="alg-wc-wl-btn-text"><?php echo esc_html(__('Add to Wishlist', ALG_WC_WL_DOMAIN)); ?></span>
		<i class="alg-wc-wl-btn-icon"></i>
	</div>	
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<span class="alg-wc-wl-btn-text"><?php echo esc_html(__('Remove from Wishlist', ALG_WC_WL_DOMAIN)); ?></span>
		<i class="alg-wc-wl-btn-icon"></i>
	</div>
</button>