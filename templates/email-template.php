<?php
/**
 * Email template
 *
 * @author  Algoritmika Ltd.
 * @version 1.3.3
 * @since   1.2.2
 */
?>

<?php $message = isset( $params['message'] ) ? '<p>' . sanitize_text_field( $params['message'] ) . '</p>' : ''; ?>
<?php
$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
$wish_list_link    = $wish_list_page_id !== false ? get_permalink( $wish_list_page_id ) : get_permalink();
?>

<?php if ( ! empty( $params['from_name'] ) ) : ?>
    <strong>
		<?php _e( 'Name: ', 'wish-list-for-woocommerce' ) ?>
    </strong>
	<?php echo $params['from_name']; ?>
    <br/><br/>
<?php endif; ?>

<?php if ( ! empty( $params['from_email'] ) ) : ?>
    <strong>
		<?php _e( 'Email: ', 'wish-list-for-woocommerce' ) ?>
    </strong>
	<?php echo $params['from_email']; ?>
    <br/><br/>
<?php endif; ?>

<?php if ( ! empty( $message ) ) : ?>
    <strong>
		<?php _e( 'Message: ', 'wish-list-for-woocommerce' ) ?>
    </strong><br/>
	<?php echo $message; ?>
    <br/><br/>
<?php endif; ?>


    <h1>
        <a href="<?php echo esc_url( $wish_list_link ); ?>"><?php _e( 'Wish list', 'wish-list-for-woocommerce' ) ?></a>
    </h1>

<?php echo do_shortcode( '[alg_wc_wl is_email="true"]' ); ?>