<?php
/**
 * Wish list view template
 * Lists wishlist items
 *
 * @author  Thanks to IT
 * @version 1.5.6
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php
$the_query            = $params['the_query'];
$can_remove_items     = $params['can_remove_items'];
$show_stock           = $params['show_stock'];
$show_price           = $params['show_price'];
$show_add_to_cart_btn = $params['show_add_to_cart_btn'];
$is_email             = isset( $params['is_email'] ) ? $params['is_email'] : false;
$show_product_thumb   = true;
$email_table_params   = '';

if ( $is_email ) {
	$show_add_to_cart_btn = false;
	$can_remove_items     = false;
	$show_product_thumb   = false;
    $email_table_params = 'cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1"';
}
?>

<style type="text/css" scoped>
	.added_to_cart.wc-forward{
		display:none;
	}
</style>

<?php if ( $the_query != null && $the_query->have_posts() ) : ?>

	<?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE ); ?>
	<table <?php echo $email_table_params; ?> class="alg-wc-wl-view-table shop_table shop_table_responsive td">
		<thead>
		<tr>

			<?php // Product thumbnail ?>
			<?php if ( $show_product_thumb ) : ?>
			    <th class="td product-thumbnail"><?php _e( 'Thumbnail', 'wish-list-for-woocommerce' ); ?></th>
		    <?php endif; ?>

			<?php // Product title ?>
			<th class="td product-name"><?php _e( 'Title', 'wish-list-for-woocommerce' ); ?></th>

			<?php // Product price ?>
			<?php if ( $show_price ) : ?>
				<th class="td product-price"><?php _e( 'Price', 'wish-list-for-woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Product Stock ?>
			<?php if ( $show_stock ) : ?>
				<th class="td product-stock"><?php _e( 'Stock', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Add to cart button ?>
			<?php if ( $show_add_to_cart_btn ) : ?>
				<th class="td add_to_cart_btn"><?php _e( 'Add to cart', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Remove Items ?>
			<?php if ( $can_remove_items ) : ?>
				<th class="td product-removal"><?php _e( 'Remove', 'wish-list-for-woocommerce' ); ?></th>
			<?php endif; ?>

		</tr>
		</thead>
		<tbody>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $product = wc_get_product( get_the_ID() ); ?>
			<tr>

				<?php // Product thumbnail ?>
				<?php if ( $show_product_thumb ) : ?>
				<td data-title="<?php _e( 'Thumbnail', 'wish-list-for-woocommerce' ); ?>" class="td product-thumbnail">
					<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
                        <?php echo $product->get_image() ?>
					</a>
				</td>
                <?php endif; ?>

				<?php // Product title ?>
				<td data-title="<?php _e( 'Title', 'wish-list-for-woocommerce' ); ?>" class="td product-name"><a
							href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php echo $product->get_title(); ?></a></a>
				</td>

				<?php // Product price ?>
				<?php if ( $show_price ) : ?>
					<td data-title="<?php _e( 'Price', 'wish-list-for-woocommerce' ); ?>"
						class="td product-price"><?php echo $product->get_price_html(); ?>
					</td>
				<?php endif; ?>

				<?php // Product Stock ?>
				<?php if ( $show_stock ) : ?>
					<td data-title="<?php _e( 'Stock', 'woocommerce' ); ?>" class="td product-stock">
						<?php if ( $product->is_in_stock() ) : ?>
							<?php _e( 'In stock', 'woocommerce' ) ?>
						<?php else: ?>
							<?php _e( 'Out of stock', 'woocommerce' ); ?>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<?php // Add to cart button ?>
				<?php if ( $show_add_to_cart_btn ) : ?>
					<td data-title="<?php _e( 'Add to cart', 'woocommerce' ); ?>"
						class="td add-to-cart-btn"><?php echo do_shortcode('[add_to_cart show_price="false" style="" id="'.get_the_ID().'"]'); ?>
					</td>
				<?php endif; ?>

				<?php // Remove Items ?>
				<?php if ( $can_remove_items ) : ?>
					<td data-title="<?php _e( 'Remove', 'wish-list-for-woocommerce' ); ?>" class="td product-removal">
						<?php
						$params = Alg_WC_Wish_List_Toggle_Btn::get_toggle_btn_params();
						$params['btn_class'] .= ' remove alg-wc-wl-remove-item-from-wl';
						echo alg_wc_wl_locate_template( 'remove-button.php', $params );
						?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		</tbody>
	</table>
	<?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER ); ?>

<?php endif; ?>

<?php if ( ! $is_email ) : ?>
    <div class="alg-wc-wl-empty-wishlist"
         style="<?php echo ( $the_query == null || ! $the_query->have_posts() ) ? 'display:block' : ''; ?>">
		<?php _e( 'The Wish list is empty', 'wish-list-for-woocommerce' ); ?>
    </div>
<?php endif; ?>