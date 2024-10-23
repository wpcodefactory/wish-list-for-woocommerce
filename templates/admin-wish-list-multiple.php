<?php
/**
 * Wish List for WooCommerce Pro - Multiple Admin wish list.
 *
 * Template used to display the multiple wishlist on user profile page.
 *
 * @author  WPFactory.
 * @version 3.1.2
 * @since   3.0.8
 */
?>

<?php
$the_query           = $params['the_query'];
$products_attributes = isset( $params['product_attributes'] ) ? $params['product_attributes'] : false;
$empty_wishlist_msg  = isset( $params['empty_wishlist_msg'] ) ? $params['empty_wishlist_msg'] : false;


// If is current user's profile (profile.php)
if ( defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE ) {
    $user_id = get_current_user_id();
// If is another user's profile page
} elseif (! empty($_GET['user_id']) && is_numeric($_GET['user_id']) ) {
    $user_id = $_GET['user_id'];
// Otherwise something is wrong.
} else {
    $user_id = 0;
}

$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );
$tab_contents = '';
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
	
	/* Style the tab */
	.alg_wc_wl_admin_tab {
	  overflow: hidden;
	  border: 1px solid #ccc;
	  background-color: #f1f1f1;
	}

	/* Style the buttons inside the tab */
	.alg_wc_wl_admin_tab button {
	  background-color: inherit;
	  float: left;
	  border: none;
	  outline: none;
	  cursor: pointer;
	  padding: 14px 16px;
	  transition: 0.3s;
	  font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.alg_wc_wl_admin_tab button:hover {
	  background-color: #ddd;
	}

	/* Create an active/current tablink class */
	.alg_wc_wl_admin_tab button.alg_wc_wl_admin_active {
	  background-color: #ccc;
	}

	/* Style the tab content */
	.alg_wc_wl_admin_tabcontent {
	  display: none;
	  padding: 6px 12px;
	  border: 1px solid #ccc;
	  border-top: none;
	}

	
	#alg_wc_wl_admin_tab_default {
		display: block;
	}

	
</style>


<script type="text/javascript">

function alg_wc_wl_admin_open_multi_wishlist_admin(evt, cityName) {
  var i, alg_wc_wl_admin_tabcontent, alg_wc_wl_admin_tablinks;
  alg_wc_wl_admin_tabcontent = document.getElementsByClassName("alg_wc_wl_admin_tabcontent");
  for (i = 0; i < alg_wc_wl_admin_tabcontent.length; i++) {
    alg_wc_wl_admin_tabcontent[i].style.display = "none";
  }
  alg_wc_wl_admin_tablinks = document.getElementsByClassName("alg_wc_wl_admin_tablinks");
  for (i = 0; i < alg_wc_wl_admin_tablinks.length; i++) {
    alg_wc_wl_admin_tablinks[i].className = alg_wc_wl_admin_tablinks[i].className.replace(" alg_wc_wl_admin_active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " alg_wc_wl_admin_active";
}

</script>




<?php 

	if( is_array( $wishlist_list ) ) {
	
?>
		<div class="alg_wc_wl_admin_tab">
			<button type="button" class="alg_wc_wl_admin_tablinks alg_wc_wl_admin_active" onclick="alg_wc_wl_admin_open_multi_wishlist_admin(event, 'alg_wc_wl_admin_tab_default')" id="alg_wc_wl_admin_defaultOpen"><?php echo esc_html( get_option( 'alg_wc_wl_texts_default_wishlist', __( 'Default Wishlist', 'wish-list-for-woocommerce' ) ) ); ?></button>

			<?php 
			
				foreach( $wishlist_list as $k => $list ) {
					$tab_id = $k + 1;
					
					$tab_option_id = 'alg_wc_wl_admin_tab_' . $tab_id;
					
					$wishlisted_items = Alg_WC_Wish_List::get_multiple_wishlist_items( $user_id, false, false, $tab_id );
					$wishlisted_items  = array_values($wishlisted_items);
					$the_query = new WP_Query( array(
						'post_type'      => array( 'product', 'product_variation' ),
						'post_status'    => array( 'publish', 'trash' ),
						'posts_per_page' => - 1,
						'post__in'       => $wishlisted_items,
						'orderby'        => 'post__in',
						'order'          => 'asc'
					) );
					
					ob_start();
					
					?>
					
					<div id="<?php echo $tab_option_id; ?>" class="alg_wc_wl_admin_tabcontent">
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
					</div>
					<?php
					$ob_content = ob_get_clean();
					$tab_contents .= $ob_content;
			?>
				<button type="button" class="alg_wc_wl_admin_tablinks" onclick="alg_wc_wl_admin_open_multi_wishlist_admin(event, '<?php echo $tab_option_id; ?>')"><?php echo $list; ?></button>
			<?php 
				}
			?>
		</div>
<?php 
	}
?>

<div id="alg_wc_wl_admin_tab_default" class="alg_wc_wl_admin_tabcontent">
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
</div>
<?php echo $tab_contents; ?>

