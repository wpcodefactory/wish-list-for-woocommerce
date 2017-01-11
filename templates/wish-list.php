<?php
/**
 * Wish list view template
 *
 * Lists wishlist items
 *
 * @author  Algoritmika Ltd. 
 * @version 1.0.0
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly
?>

<?php $the_query = $params['the_query']; ?>

<?php if ($the_query != null && $the_query->have_posts()) : ?>
	
	<table class="alg-wc-wl-view-table shop_table">
		<thead>
			<tr>
				<th class="product-thumbnail"><?php _e('Thumbnail', ALG_WC_WL_DOMAIN); ?></th>
				<th class="product-name"><?php _e('Title', ALG_WC_WL_DOMAIN); ?></th>
				<th class="product-price"><?php _e('Price', ALG_WC_WL_DOMAIN); ?></th>
				<th class="product-removal"><?php _e('Remove', ALG_WC_WL_DOMAIN); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
				<?php $product = wc_get_product(get_the_ID()); ?>
				<tr>
					<td class="product-thumbnail">
						<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
							<?php echo $product->get_image() ?>
						</a>
					</td>
					<td class="product-name"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></td>
					<td class="product-price"><?php echo $product->get_price_html(); ?></td>
					<td class="product-removal">
						<?php 
							$params = Alg_WC_Wish_List_Toggle_Btn::get_toggle_btn_params();
							$params['btn_class'].=' remove alg-wc-wl-remove-item-from-wl';
							echo alg_wc_ws_locate_template('toggle-wish-list-button-small.php', $params); 
						?>
					</td>
				</tr>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</tbody>
	</table>

<?php endif; ?>

<div class="alg-wc-wl-empty-wishlist" style="<?php echo ($the_query == null || !$the_query->have_posts()) ? 'display:block' : ''?>">
	<?php _e('Your Wish list is empty',ALG_WC_WL_DOMAIN); ?>
</div>
