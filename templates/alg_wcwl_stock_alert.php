<?php if ( $stock_alert ): ?>
	<?php $enabled_str = $stock_alert_enabled ? 'checked' : '' ?>
	<h3><?php echo __( 'Stock alert', 'wish-list-for-woocommerce' ) ?></h3>
	<form id="alg_wcwl_user_stock_alert_form" method="post">
		<input type="hidden" name="alg_wcwl_user_stock_alert_form"/>
		<input style="margin-right:2px;position:relative;top:1px" type="checkbox" id="alg_wcwl_user_stock_alert"
		       name="alg_wcwl_user_stock_alert" <?php echo $enabled_str; ?>/>
		<label for="alg_wcwl_user_stock_alert">
			<?php _e( 'Receive email when a product becomes available, in case it gets out of stock in the first place', 'wish-list-for-woocommerce' ); ?>
		</label>
		<br/>
		<input type="text" placeholder="E-mail"
		       name="alg_wcwl_user_stock_alert_email" id="alg_wcwl_user_stock_alert_email" value="<?php echo esc_attr( $stock_alert_email ) ?>"/>
		<input type="submit" value="<?php _e( 'Save', 'wish-list-for-woocommerce' ); ?>"/>
	</form>
	<style>
		#alg_wcwl_user_stock_alert_form {
			margin-bottom: 20px;
		}
		#alg_wcwl_user_stock_alert_email {
			margin-top: 10px;
			margin-right: 14px;
			min-width: 400px
		}
		@media (max-width: 767.98px) {
			#alg_wcwl_user_stock_alert_email {
				min-width: auto
			}
		}
	</style>
<?php endif; ?>