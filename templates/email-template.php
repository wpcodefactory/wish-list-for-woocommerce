<?php
/**
 * Email template
 *
 * @author  Algoritmika Ltd.
 * @version 1.2.2
 * @since   1.2.2
 */
?>

<?php $message = isset( $params['message'] ) ? '<p>' . sanitize_text_field( $params['message'] ) . '</p>' : ''; ?>
<?php
$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
$wish_list_link    = $wish_list_page_id !== false ? get_permalink( $wish_list_page_id ) : get_permalink();
?>

<?php echo $message; ?>

    <h1>
        <a href="<?php echo esc_url( $wish_list_link ); ?>"><?php _e( 'Wish list', 'wish-list-for-woocommerce' ) ?></a>
    </h1>

<?php echo do_shortcode( '[alg_wc_wl is_email="true"]' ); ?>