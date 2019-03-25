<?php
/**
 * Email template
 *
 * @author  Thanks to IT
 * @version 1.5.2
 * @since   1.2.2
 */
?>

<?php $message = isset( $params['message'] ) ? '<p>' . sanitize_text_field( $params['message'] ) . '</p>' : ''; ?>
<?php
$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
$wish_list_link    = $params['email']['url'];
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

<br />
<h2>
    <center>
    <a href="<?php echo esc_url( $wish_list_link ); ?>"><?php _e( 'Visit my Wish List', 'wish-list-for-woocommerce' ) ?></a>
    </center>
</h2>