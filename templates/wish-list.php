<?php
/**
 * Wish list template.
 *
 * @author  WPFactory.
 * @version 3.0.8
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php
global $wp_query, $wp;
$theid = intval( $wp_query->queried_object->ID );

$the_query            = $params['the_query'];
$can_remove_items     = $params['can_remove_items'];
$default_wl_text      = $params['default_wishlist_text'];
$show_stock           = $params['show_stock'];
$show_price           = $params['show_price'];
$show_subtotal_col    = isset( $params['subtotal_column'] ) ? $params['subtotal_column'] : false;
$show_add_to_cart_btn = $params['show_add_to_cart_btn'];
$products_attributes  = isset( $params['product_attributes'] ) ? $params['product_attributes'] : false;
$is_email             = isset( $params['is_email'] ) ? $params['is_email'] : false;
$show_product_thumb   = true;
$work_with_cache      = $params['work_with_cache'];
$email_table_params   = '';
$stock_alert          = isset( $params['stock_alert'] ) ? $params['stock_alert'] : false;
$stock_alert_email    = isset( $params['stock_alert_email'] ) ? $params['stock_alert_email'] : false;
$stock_alert_enabled  = isset( $params['stock_alert_enabled'] ) ? $params['stock_alert_enabled'] : false;
$sku                  = isset( $params['sku'] ) ? $params['sku'] : false;
$description          = isset( $params['product_description'] ) ? $params['product_description'] : false;
$quantity             = isset( $params['quantity'] ) ? $params['quantity'] : false;
$show_prod_category   = isset( $params['show_prod_category'] ) ? $params['show_prod_category'] : false;
$taxonomies           = isset( $params['taxonomies'] ) ? $params['taxonomies'] : array();
$empty_wishlist_text  = isset( $params['empty_wishlist_text'] ) ? $params['empty_wishlist_text'] : __( 'The Wish list is empty.', 'wish-list-for-woocommerce' );
$drag_drop            = isset( $params['drag_and_drop'] ) ? $params['drag_and_drop'] : false;
$drag_drop_icon_class = isset( $params['drag_and_drop_icon_class'] ) ? $params['drag_and_drop_icon_class'] : '';
$arrow_sorting = isset( $params['arrow_sorting'] ) ? $params['arrow_sorting'] : false;
$arrow_sorting_up_icon_class = isset( $params['arrow_sorting_up_icon_class'] ) ? $params['arrow_sorting_up_icon_class'] : '';
$arrow_sorting_down_icon_class = isset( $params['arrow_sorting_down_icon_class'] ) ? $params['arrow_sorting_down_icon_class'] : '';

// Note Field
$note = isset( $params['note'] ) ? $params['note'] : false;
if ( $note ) {
	$note_field = new Alg_WC_Wish_List_Note_Field();
}

if ( $is_email ) {
	$show_subtotal_col    = false;
	$quantity             = false;
	$note                 = false;
	$show_add_to_cart_btn = false;
	$can_remove_items     = false;
	$drag_drop            = false;
	$arrow_sorting        = false;
	$show_product_thumb   = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_IMAGES_ON_EMAILS, 'no' ), FILTER_VALIDATE_BOOLEAN );
	$email_table_params   = 'border="1" style="width:100%;border-collapse: collapse;border:1px solid #ccc" cellpadding="15"';
}

$alg_wc_wl_dropdown_sorting = get_option( 'alg_wc_wl_dropdown_sorting', 'no' );
$alg_wc_wl_duplicate_option = get_option( 'alg_wc_wl_duplicate_option', 'no' );

// $current_page_id       =   get_the_ID();

$current_page_id       =   $theid;
$wish_list_permalink   =   get_permalink( $current_page_id );

$wl_tab_slug = get_option( 'alg_wc_wl_tab_slug', 'my-wish-list' );
$query_string = '?';
$page = '';

if( is_wc_endpoint_url( $wl_tab_slug ) ) {
	$structure = get_option( 'permalink_structure', '' );
	if( $structure == '' ){
		$wish_list_permalink  = untrailingslashit( $wish_list_permalink ) .'&' . $wl_tab_slug;
		$query_string = '&';
	} else {
		$wish_list_permalink  = untrailingslashit( $wish_list_permalink ) .'/' . $wl_tab_slug;
		$query_string = '?';
	}
	$page = $wl_tab_slug;
}

if ( is_user_logged_in() ) {
	$user    	= wp_get_current_user();
	$user_id 	= $user->ID;
} else {
	$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
}
$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );

$current_tab_id = '';
$current_tab_title = $default_wl_text;

if ( isset($_GET) && isset($_GET['wtab']) && $_GET['wtab'] > 0) {
	$current_tab_id = $_GET['wtab'];
}

$alg_wc_wl_style_wish_list_multiple_tab_font_color = get_option('alg_wc_wl_style_wish_list_multiple_tab_font_color', '#000');
$alg_wc_wl_style_wish_list_multiple_tab_bg_color = get_option('alg_wc_wl_style_wish_list_multiple_tab_bg_color', '#ffffff');
$alg_wc_wl_style_wish_list_multiple_tab_active_font_color = get_option('alg_wc_wl_style_wish_list_multiple_tab_active_font_color', '#000');
$alg_wc_wl_style_wish_list_multiple_tab_active_bg_color = get_option('alg_wc_wl_style_wish_list_multiple_tab_active_bg_color', '#ffffff');
?>

<div class="alg-wc-wl-view-table-container <?php echo $work_with_cache ? 'ajax-loading' : '' ?>">
    <i class="ajax-loading-icon fa fa-refresh fa-spin fa-3x"></i>
	<style type="text/css" scoped>
		.added_to_cart.wc-forward{
			display:none;
		}
        .ajax-loading{
            position:relative;
        }
        .ajax-loading:before{
            background:#fff;
            width:100%;
            height:100%;
            position:absolute;
            left:0;
            top:0;
            opacity:0.5;
            content:' ';
            display:block;
            z-index:999;
        }
        .alg-wc-wl-view-table-container .ajax-loading-icon{
            display:none;
        }
        .ajax-loading .ajax-loading-icon{
            display:block;
            left:50%;
            margin-left:-20px;
            margin-top:-5px;
            top:50%;
            position:absolute;
            z-index:9999;
        }
		
		/* Style tab links */
		.alg-wc-wl-tablink {
		  background-color: <?php echo $alg_wc_wl_style_wish_list_multiple_tab_bg_color; ?>;
		  color: <?php echo $alg_wc_wl_style_wish_list_multiple_tab_font_color; ?>;
		  float: left;
		  border: none;
		  outline: none;
		  cursor: pointer;
		  padding: 14px 20px;
		  font-size: 17px;
		}

		.alg-wc-wl-tablink:hover, .alg-wc-wl-tablink.active {; 
		  color: <?php echo $alg_wc_wl_style_wish_list_multiple_tab_active_font_color; ?>;
		  background-color: <?php echo $alg_wc_wl_style_wish_list_multiple_tab_active_bg_color; ?>;
		}
		
		.col-20per{
			border-right: 1px solid <?php echo $alg_wc_wl_style_wish_list_multiple_tab_active_font_color; ?>;
		}
		.alg-wc-delete-wishlist{
			width: 100%;
			margin-top: 20px;
			text-align: right;
			
		}
		.alg-wc-delete-wishlist a{
			background-color: #DC3232;
			color: white;
		}
	</style>
<?php if( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ){ if( is_array( $wishlist_list ) ) { ?>
<div style="width:100%; display: flex; ">
	<div class="col-20per">
	<button class="alg-wc-wl-tablink col-20per <?php if($current_tab_id == ''){ echo "active"; } ?>" onclick="location.href='<?php echo $wish_list_permalink; ?>'"><?php echo esc_html( $default_wl_text ); ?></button>
	</div>

	<?php 
	
		foreach( $wishlist_list as $k => $list ) {
			$tab_id = $k + 1;
			$active = '';
			if( $tab_id == $current_tab_id ) {
				$active = 'active';
				$current_tab_title = $list;
			}
	?>
	<div class="col-20per">
	<button class="alg-wc-wl-tablink col-20per <?php echo $active ;?>" onclick="location.href='<?php echo $wish_list_permalink; ?><?php echo $query_string; ?>wtab=<?php echo $tab_id; ?>'" id="defaultOpen"><?php echo $list; ?></button>
	</div>
	<?php 
		}
	?>
</div>
<?php } } ?>
<div style="clear:both;"></div>
<?php 
if( $alg_wc_wl_dropdown_sorting == 'yes' ) {
	$alg_wc_wl_orderby = (isset($_GET['alg_wc_wl_orderby']) ? $_GET['alg_wc_wl_orderby'] : '');
	?>
	<form action="<?php echo add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ); ?>" method="GET" >
	<select name="alg_wc_wl_orderby" class="alg_wc_wl_orderby" aria-label="Wishlist order" onchange="this.form.submit()">
		<option value="" <?php selected('', $alg_wc_wl_orderby); ?>><?php _e( 'Default sorting', 'wish-list-for-woocommerce' ); ?></option>
		<option value="name-asc" <?php selected('name-asc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by product name A - Z', 'wish-list-for-woocommerce' ); ?></option>
		<option value="name-desc" <?php selected('name-desc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by product name Z - A', 'wish-list-for-woocommerce' ); ?></option>
		<option value="date-asc" <?php selected('date-asc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by latest', 'wish-list-for-woocommerce' ); ?></option>
		<option value="date-desc" <?php selected('date-desc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by oldest', 'wish-list-for-woocommerce' ); ?></option>
		<option value="price-asc" <?php selected('price-asc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by price: low to high', 'wish-list-for-woocommerce' ); ?></option>
		<option value="price-desc" <?php selected('price-desc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by price: high to low', 'wish-list-for-woocommerce' ); ?></option>
		<option value="sku-asc" <?php selected('sku-asc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by SKU A - Z', 'wish-list-for-woocommerce' ); ?></option>
		<option value="sku-desc" <?php selected('sku-desc', $alg_wc_wl_orderby); ?>><?php _e( 'Sort by SKU Z - A', 'wish-list-for-woocommerce' ); ?></option>
	</select>
	<input type="hidden" name="wtab" id="wtab" value="<?php echo (isset($_GET['wtab']) ? $_GET['wtab'] : ''); ?>">
	</form>
	<?php
}
?>
<div class="alg-wc-delete-wishlist">
<?php if( $user_id > 0 && $alg_wc_wl_duplicate_option == 'yes' ) { ?>
<a href="javascript:;" data-page="<?php echo $page; ?>" data-wishlist_tab_title="<?php echo $current_tab_title; ?>" data-wishlist_tab_id="<?php echo $current_tab_id; ?>" class="button copy-wishlist" title="<?php _e( 'Copy Wishlist', 'wish-list-for-woocommerce' ); ?>" rel="nofollow"><?php _e( 'Copy Wishlist', 'wish-list-for-woocommerce' ); ?></a>
<?php 
} 
if( $current_tab_id > 0 ){ 
?>

<a href="javascript:;" data-page="<?php echo $page; ?>" data-wishlist_tab_id="<?php echo $current_tab_id; ?>" class="button delete-customized-wishlist" title="<?php _e( 'Delete Wishlist', 'wish-list-for-woocommerce' ); ?>" rel="nofollow"><?php _e( 'Delete Wishlist', 'wish-list-for-woocommerce' ); ?></a>

<?php } ?>

</div>
<div style="clear:both;"></div>
<?php if ( $the_query != null && $the_query->have_posts() ) : ?>

	<?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, $the_query, $products_attributes, $params ); ?>
	<table <?php echo $email_table_params; ?> class="alg-wc-wl-view-table">
		<thead>
		<tr>
			<?php // Drag and drop ?>
			<?php if ( $drag_drop ) : ?>
				<th class="drag-drop"></th>
			<?php endif; ?>

			<?php // Arrow sorting ?>
			<?php if ( $arrow_sorting ) : ?>
				<th class="arrow-sorting"></th>
			<?php endif; ?>

			<?php // Product ?>
			<th colspan="<?php echo !$show_product_thumb ? '1' : '2'; ?>" class="product"><?php _e( 'Product', 'wish-list-for-woocommerce' ); ?></th>

			<?php // Product price ?>
			<?php if ( $show_price ) : ?>
				<th class="product-price"><?php _e( 'Price', 'wish-list-for-woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Product Category ?>
			<?php if ( $show_prod_category ) : ?>
				<th class="product-category"><?php _e( 'Category', 'wish-list-for-woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Product Taxonomies.  ?>
			<?php foreach ( $taxonomies as $tax_name => $tax ) : ?>
				<th class="product-tax <?php echo esc_attr( $tax_name ) ?>"><?php echo $tax->labels->menu_name ? esc_html( $tax->labels->menu_name ) : esc_html( $tax->label ); ?></th>
			<?php endforeach; ?>

			<?php // Product Description. ?>
			<?php if ( $description ) : ?>
                <th class="product-description"><?php _e( 'Description', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Product Stock ?>
			<?php if ( $show_stock ) : ?>
				<th class="product-stock"><?php _e( 'Stock', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // SKU ?>
			<?php if ( $sku ) : ?>
                <th class="product-sku"><?php _e( 'SKU', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Quantity ?>
			<?php if ( $quantity ) : ?>
				<th class="product-qty"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Subtotal ?>
			<?php if ( $show_subtotal_col ) : ?>
				<th class="product-subtotal"><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Note ?>
			<?php if ( $note ) : ?>
				<th class="product-note"><?php echo esc_html( $note_field->get_field_label() ); ?></th>
			<?php endif; ?>

			<?php // Add to cart button ?>
			<?php if ( $show_add_to_cart_btn ) : ?>
				<th class="add_to_cart_btn"><?php _e( 'Add to cart', 'woocommerce' ); ?></th>
			<?php endif; ?>

			<?php // Remove Items ?>
			<?php if ( $can_remove_items ) : ?>
				<th class="product-removal"><?php _e( 'Remove', 'wish-list-for-woocommerce' ); ?></th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php if(isset($products_attributes[ get_the_ID() ]['variation_id']) && !empty($products_attributes[ get_the_ID() ]['variation_id'])): ?>
				<?php $product = new WC_Product_Variation($products_attributes[ get_the_ID() ]['variation_id']); ?>
			<?php else: ?>
				<?php $product = wc_get_product( get_the_ID() ); ?>
			<?php endif; ?>
			<?php $data_product_id = $product->get_id(); ?>
			<tr data-product-id="<?php echo esc_attr( $data_product_id ) ?>">
				<?php if ( $drag_drop ) : ?>
					<td data-title="<?php _e( 'Drag and drop', 'wish-list-for-woocommerce' ); ?>" class="drag-drop">
						<div class="alg-wc-wl-move-icon alg-wc-wl-drag-drop-wl-item"><i class="<?php echo esc_attr( $drag_drop_icon_class )?>"></i></div>
					</td>
				<?php endif; ?>

				<?php if ( $arrow_sorting ) : ?>
					<td data-title="<?php _e( 'Sort', 'wish-list-for-woocommerce' ); ?>" class="arrow-sorting">
						<div class="alg-wc-wl-arrow-sorting-up-icon alg-wc-wl-arrow-sorting-trigger">
							<i class="<?php echo esc_attr( $arrow_sorting_up_icon_class )?>"></i>
						</div>
						<div class="alg-wc-wl-arrow-sorting-down-icon alg-wc-wl-arrow-sorting-trigger">
							<i class="<?php echo esc_attr( $arrow_sorting_down_icon_class )?>"></i>
						</div>
					</td>
				<?php endif; ?>

				<?php // Product thumbnail ?>
				<?php if ( $show_product_thumb ) : ?>
					<td data-title="<?php _e( 'Thumbnail', 'wish-list-for-woocommerce' ); ?>" class="product-thumbnail">
						<a href="<?php echo $product->get_permalink(); ?>">
							<?php if ( $is_email ) { ?>
								<?php echo $product->get_image( apply_filters( 'alg_wc_wl_product_image_size', 'woocommerce_gallery_thumbnail', 'email' ) ) ?>
							<?php } else { ?>
								<?php echo $product->get_image( apply_filters( 'alg_wc_wl_product_image_size', 'woocommerce_thumbnail', '' ) ) ?>
							<?php } ?>
						</a>
					</td>
				<?php endif; ?>

				<?php // Product ?>
				<td data-title="<?php _e( 'Product', 'wish-list-for-woocommerce' ); ?>" class="product-name">
					<a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_title();?></a>
					<?php if ( is_a( $product, 'WC_Product_Variation' ) && 'yes' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SAVE_ATTRIBUTES, 'yes' ) ) {
						foreach ( $product->get_attributes() as $variation_attribute => $term_slug ) {
							$term_slug_from_wl  = isset( $products_attributes[ $product->get_id() ]['attributes'] ) && ! empty( $value = $products_attributes[ $product->get_id() ]['attributes'][ 'attribute_' . $variation_attribute ] ) ? $value : '';
							$attribute_tax_slug = str_replace( 'attribute_', '', $variation_attribute );
							$attribute_label    = wc_attribute_label( $attribute_tax_slug, $product );
							$attribute_value    = ( $term = get_term_by( 'slug', $term_slug, $attribute_tax_slug ) ) ? $term->name : $term_slug;
							if ( empty( $attribute_value ) ) {
								$attribute_value = ( $term = get_term_by( 'slug', $term_slug_from_wl, $attribute_tax_slug ) ) ? $term->name : $term_slug_from_wl;
							}
							echo '<div style=\'font-size:13px;\'><strong>' . $attribute_label . ':</strong> ' . $attribute_value . '</div>';
						}
					} ?>
				</td>

				<?php // Product price ?>
				<?php if ( $show_price ) : ?>
					<td data-title="<?php _e( 'Price', 'wish-list-for-woocommerce' ); ?>" class="product-price">
						<?php echo $product->get_price_html(); ?>
					</td>
				<?php endif; ?>

				<?php // Product Category ?>
				<?php if ( $show_prod_category ) : ?>
					<td data-title="<?php _e( 'Category', 'wish-list-for-woocommerce' ); ?>" class="product-cat">
						<?php echo wc_get_product_category_list( $product->get_id() ); ?>
					</td>
				<?php endif; ?>

				<?php // Product taxonomies ?>
				<?php if ( ! empty( $taxonomies ) ) : ?>
					<?php // Product Taxonomies  ?>
					<?php foreach ( $taxonomies as $tax_name => $tax ) : ?>
						<td data-title="<?php echo esc_html( $tax->label ); ?>" class="product-tax <?php echo esc_attr( $tax_name ) ?>"><?php echo get_the_term_list( $product->get_id(), $tax_name, '', ', ', '' ); ?></td>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php // Product description ?>
				<?php if ( $description ) : ?>
                    <td data-title="<?php _e( 'Description', 'wish-list-for-woocommerce' ); ?>"
                        class="product-description"><?php echo $product->get_short_description(); ?>
                    </td>
				<?php endif; ?>

				<?php // Product Stock ?>
				<?php if ( $show_stock ) : ?>
					<td data-title="<?php _e( 'Stock', 'woocommerce' ); ?>" class="product-stock">
						<?php echo empty( $availability = $product->get_availability()['availability'] ) ? _e( 'In stock', 'woocommerce' ) : esc_html( $availability ); ?>
					</td>
				<?php endif; ?>

				<?php // Product SKU ?>
				<?php if ( $sku ) : ?>
                    <td data-title="<?php _e( 'SKU', 'wish-list-for-woocommerce' ); ?>"
                        class="product-sku"><?php echo $product->get_sku(); ?>
                    </td>
				<?php endif; ?>

				<?php // Quantity ?>
				<?php if ( $quantity ) : ?>
					<td data-title="<?php _e( 'Quantity', 'wish-list-for-woocommerce' ); ?>"
					    class="product-qty">
						<?php $qty = isset( $products_attributes[ $product->get_id() ] ) && isset( $products_attributes[ $product->get_id() ]['quantity'] ) ? esc_attr( $products_attributes[ $product->get_id() ]['quantity'] ) : null; ?>
						<?php echo woocommerce_quantity_input( null != $qty ? array( 'input_value' => $qty, 'min_value' => 1 ) : array( 'min_value' => 1 ), $product ); ?>
						<input type="hidden" class="prod-id" name="prod_id" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
					</td>
				<?php endif; ?>

				<?php // Subtotal ?>
				<?php if ( $show_subtotal_col ) : ?>
					<td data-title="<?php _e( 'Subtotal', 'wish-list-for-woocommerce' ); ?>" class="product-price">
						<?php $qty = isset( $products_attributes[ $product->get_id() ] ) && isset( $products_attributes[ $product->get_id() ]['quantity'] ) ? intval( $products_attributes[ $product->get_id() ]['quantity'] ) : 1; ?>
						<?php $subtotal_value = !empty($product->get_price()) ? $qty * $product->get_price() : ''; ?>
						<div class="alg-wc-wl-subtotal" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" data-price="<?php echo esc_attr( $product->get_price() ) ?>"><?php echo wc_price( $subtotal_value ); ?></div>
					</td>
				<?php endif; ?>

				<?php // Note ?>
				<?php if ( $note ) : ?>
					<td data-title="<?php echo esc_html( $note_field->get_field_label() ); ?>" class="product-note">
						<?php echo $note_field->get_field_output( $product, $params ); ?>
					</td>
				<?php endif; ?>

				<?php // Add to cart button ?>
				<?php if ( $show_add_to_cart_btn ) : ?>
					<td data-title="<?php _e( 'Add to cart', 'woocommerce' ); ?>"
					    class="add-to-cart-btn">
                        <?php
                        $add_to_cart_args = array(
	                        'add-to-cart' => $product->get_id()
                        );
                        $qty = ! isset( $qty ) || null === $qty ? 1 : $qty;
                        ?>
						<?php echo do_shortcode( '[add_to_cart quantity="'.$qty.'" show_price="false" style="" id="' . $product->get_id() . '"]' ); ?>
					</td>
				<?php endif; ?>

				<?php // Remove Items ?>
				<?php if ( $can_remove_items ) : ?>
					<td data-title="<?php _e( 'Remove', 'wish-list-for-woocommerce' ); ?>" class="product-removal">
						<?php
						echo alg_wc_wl_locate_template( 'remove-button.php', $params['remove_btn_params'] );
						?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		</tbody>
	</table>

	<?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, $the_query, $products_attributes, $params ); ?>

<?php endif; ?>

<?php if ( ! $is_email ) : ?>
	<div class="alg-wc-wl-empty-wishlist"
	     style="<?php echo ( $the_query == null || ! $the_query->have_posts() ) ? 'display:block' : ''; ?>">
		<?php echo esc_html( $empty_wishlist_text ); ?>
	</div>
<?php endif; ?>
</div>