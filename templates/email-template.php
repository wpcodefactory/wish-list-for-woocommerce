<?php
/**
 * Email template
 *
 * @author  WPFactory
 * @version 1.6.0
 * @since   1.2.2
 */
?>

<?php $message = isset( $params['message'] ) ? '<p>' . sanitize_text_field( $params['message'] ) . '</p>' : ''; ?>
<?php
$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
$wish_list_link    = $params['email']['url'];
$link_text         = get_option( 'alg_wc_wl_texts_email_link', __( 'Visit my Wishlist', 'wish-list-for-woocommerce' ) );
?>

<?php if ( ! empty( $params['from_name'] ) ) : ?>
    <strong>
		<?php _e( 'From: ', 'wish-list-for-woocommerce' ) ?>
    </strong>
	<?php echo $params['from_name']; ?> (<?php echo $params['from_email']; ?>)
    <br/><br/>
<?php endif; ?>

<?php if ( ! empty( $message ) ) : ?>
	<?php echo $message; ?>
<?php endif; ?>

<?php echo do_shortcode( '[alg_wc_wl is_email="true"]' ); ?>

<?php if(!empty($link_text)){ ?>
<br />
<h2>
    <center>
    <a href="<?php echo esc_url( $wish_list_link ); ?>"><?php echo $link_text; ?></a>
    </center>
</h2>
<?php }?>