<?php defined( 'ABSPATH' ) || exit; ?>
<?php if ( $stock_alert ): ?>
	<?php $enabled_str = $stock_alert_enabled ? 'checked' : '' ?>
	<h3><?php esc_html_e( 'Stock alert', 'wish-list-for-woocommerce' ); ?></h3>
	<form id="alg_wcwl_user_stock_alert_form" method="post">
		<input type="hidden" name="alg_wcwl_user_stock_alert_form"/>
		<?php wp_nonce_field( 'alg_wcwl_user_stock_alert_action', 'alg_wcwl_user_stock_alert_nonce' ); ?>
		<input type="checkbox" id="alg_wcwl_user_stock_alert"
			   name="alg_wcwl_user_stock_alert" <?php echo esc_attr( $enabled_str ); ?>/>
		<label for="alg_wcwl_user_stock_alert">
			<?php esc_html_e( 'Receive email when a product becomes available, in case it gets out of stock in the first place', 'wish-list-for-woocommerce' ); ?>
		</label>
		<br/>
		<input type="text" placeholder="E-mail"
			   name="alg_wcwl_user_stock_alert_email" id="alg_wcwl_user_stock_alert_email" value="<?php echo esc_attr( $stock_alert_email ) ?>"/>
		<input type="submit" value="<?php esc_attr_e( 'Save', 'wish-list-for-woocommerce' ); ?>"/>
	</form>
<?php endif; ?>