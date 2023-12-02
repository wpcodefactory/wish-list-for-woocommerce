<?php
/**
 * Wish List for WooCommerce Pro - Admin wish list.
 *
 * Template used to display the wishlist on user profile page.
 *
 * @author  WPFactory.
 * @version 2.1.3
 * @since   1.3.1
 */
?>

<?php
$the_query           = $params['the_query'];
$products_attributes = isset( $params['product_attributes'] ) ? $params['product_attributes'] : false;
$empty_wishlist_msg  = isset( $params['empty_wishlist_msg'] ) ? $params['empty_wishlist_msg'] : false;
?>

<style type="text/css" scoped>
    .alg-wc-wl-admin-wish-list .alg-wc-wl-item a{

    }
    .alg-wc-wl-admin-wish-list .alg-wc-wl-item img{
        width:50px;
        height:auto;
        margin-right:15px;
        border:3px solid #ccc;
    }
    .alg-wc-wl-admin-wish-list td{
        padding:0;
        vertical-align:middle;
    }
</style>

<table class="alg-wc-wl-admin-wish-list">
	<?php if ( $the_query != null && $the_query->have_posts() ) : ?>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php if(isset($products_attributes[ get_the_ID() ]['variation_id']) && !empty($products_attributes[ get_the_ID() ]['variation_id'])): ?>
				<?php $product = new WC_Product_Variation($products_attributes[ get_the_ID() ]['variation_id']); ?>
			<?php else: ?>
				<?php $product = wc_get_product( get_the_ID() ); ?>
			<?php endif; ?>
            <tr class="alg-wc-wl-item">
                <td>
                    <a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?>">
						<?php echo $product->get_image(); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo $product->get_permalink(); ?>">
						<?php echo $product->get_title(); ?>
                    </a>
                    <span>
                        <?php if ( is_a( $product, 'WC_Product_Variation' ) ) {
	                        foreach ( $product->get_attributes() as $variation_attribute => $term_slug ) {
		                        $taxonomy   = str_replace( 'attribute_', '', $variation_attribute );
		                        $label_name = wc_attribute_label( $taxonomy );
		                        $term_name = ( $term = get_term_by( 'slug', $term_slug, $taxonomy ) ) ? $term->name : $term_slug;
		                        echo '<div style=\'font-size:13px;\'><strong>' . $label_name . ':</strong> ' . $term_name . '</div>';
	                        }
                        } ?>
                    </span>
                </td>
            </tr>
		<?php endwhile; ?>
	<?php else: ?>
		<?php echo esc_html( $empty_wishlist_msg ) ?>
    <?php endif;?>
</table>
