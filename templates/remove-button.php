<?php
/**
 * Toggle button template - Remove button
 *
 * Add or remove an item from Wishlist
 *
 * @version 3.1.7
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
?>
<?php
$current_tab_id = '-99';

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only tab identifier used to render output, no data is processed.
$wtab_get = isset( $_GET['wtab'] ) ? absint( wp_unslash( $_GET['wtab'] ) ) : 0;
if ( $wtab_get > 0 ) {
	$current_tab_id = $wtab_get;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only tab identifier used to render output, no data is processed.
$user_tab = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_TAB ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_TAB ] ) ) : '';
if ( $current_tab_id == '-99' && $user_tab ) {
	$current_tab_id = $user_tab;
}
?>

<div data-wtab_id="<?php echo esc_attr( $current_tab_id ); ?>" data-item_id="<?php echo absint( get_the_ID() ); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<i class="<?php echo esc_attr( $params['remove_btn_icon_class'] ); ?>" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<i class="<?php echo esc_attr( $params['remove_btn_icon_class'] ); ?>" aria-hidden="true"></i>
	</div>
	<i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
</div>