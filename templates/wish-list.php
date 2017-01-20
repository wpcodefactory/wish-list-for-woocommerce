<?php
/**
 * Wish list view template
 * Lists wishlist items
 *
 * @author  Algoritmika Ltd.
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php
$the_query        = $params['the_query'];
$can_remove_items = $params['can_remove_items'];
$show_stock       = $params['show_stock'];
$show_price       = $params['show_price'];
?>

<?php if ( $the_query != null && $the_query->have_posts() ) : ?>


	<?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE ); ?>
    <table class="alg-wc-wl-view-table shop_table shop_table_responsive">
        <thead>
        <tr>

			<?php // Product thumbnail ?>
            <th class="product-thumbnail"><?php _e( 'Thumbnail', ALG_WC_WL_DOMAIN ); ?></th>

			<?php // Product title ?>
            <th class="product-name"><?php _e( 'Title', ALG_WC_WL_DOMAIN ); ?></th>

			<?php // Product price ?>
			<?php if ( $show_price ) : ?>
                <th class="product-price"><?php _e( 'Price', ALG_WC_WL_DOMAIN ); ?></th>
			<?php endif; ?>

			<?php // Product Stock ?>
			<?php if ( $show_stock ) : ?>
                <th class="product-stock"><?php _e( 'Stock', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Remove Items ?>
			<?php if ( $can_remove_items ) : ?>
                <th class="product-removal"><?php _e( 'Remove', ALG_WC_WL_DOMAIN ); ?></th>
			<?php endif; ?>

        </tr>
        </thead>
        <tbody>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $product = wc_get_product( get_the_ID() ); ?>
            <tr>

				<?php // Product thumbnail ?>
                <td data-title="<?php _e( 'Thumbnail', ALG_WC_WL_DOMAIN ); ?>" class="product-thumbnail">
                    <a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
						<?php echo $product->get_image() ?>
                    </a>
                </td>

				<?php // Product title ?>
                <td data-title="<?php _e( 'Title', ALG_WC_WL_DOMAIN ); ?>" class="product-name"><a
                            href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php the_title(); ?></a>
                </td>

				<?php // Product price ?>
				<?php if ( $show_price ) : ?>
                    <td data-title="<?php _e( 'Price', ALG_WC_WL_DOMAIN ); ?>"
                        class="product-price"><?php echo $product->get_price_html(); ?>
                    </td>
				<?php endif; ?>

				<?php // Product Stock ?>
				<?php if ( $show_stock ) : ?>
                    <td data-title="<?php _e( 'Stock', 'woocommerce' ); ?>" class="product-stock">
						<?php if ( $product->is_in_stock() ) : ?>
							<?php _e( 'In stock', 'woocommerce' ) ?>
						<?php else: ?>
							<?php _e( 'Out of stock', 'woocommerce' ); ?>
						<?php endif; ?>
                    </td>
				<?php endif; ?>

				<?php // Remove Items ?>
				<?php if ( $can_remove_items ) : ?>
                    <td data-title="<?php _e( 'Remove', ALG_WC_WL_DOMAIN ); ?>" class="product-removal">
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

<div class="alg-wc-wl-empty-wishlist"
     style="<?php echo ( $the_query == null || ! $the_query->have_posts() ) ? 'display:block' : ''; ?>">
	<?php _e( 'The Wish list is empty', ALG_WC_WL_DOMAIN ); ?>
</div>
