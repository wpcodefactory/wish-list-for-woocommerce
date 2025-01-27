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

if ( isset( $_GET ) && isset( $_GET['wtab'] ) && $_GET['wtab'] > 0 ) {
	$current_tab_id = $_GET['wtab'];
}

$user_tab = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_TAB ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_TAB ] ) : '';
if ( $current_tab_id == '-99' && $user_tab ) {
	$current_tab_id = $user_tab;
}
?>

<div data-wtab_id="<?php echo $current_tab_id; ?>" data-item_id="<?php echo get_the_ID(); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<i class="<?php echo esc_attr( $params['remove_btn_icon_class'] ); ?>" aria-hidden="true"></i>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<i class="<?php echo esc_attr( $params['remove_btn_icon_class'] ); ?>" aria-hidden="true"></i>
	</div>
	<i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
</div>