<?php
/**
 * Wishlist for WooCommerce - Wishlist Section Settings
 *
 * @version 1.5.6
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_List' ) ) :

	class Alg_WC_Wish_List_Settings_List extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_STOCK              = 'alg_wc_wl_lstock';
		const OPTION_PRICE              = 'alg_wc_wl_lprice';
		const OPTION_ADD_TO_CART_BUTTON = 'alg_wc_wl_ladd_to_cart_btn';
		const OPTION_TAB                = 'alg_wc_wl_tab';
		const OPTION_TAB_SLUG           = 'alg_wc_wl_tab_slug';
		const OPTION_TAB_LABEL          = 'alg_wc_wl_tab_label';
		const OPTION_TAB_PRIORITY       = 'alg_wc_wl_tab_priority';
		
		
		const OPTION_SAVE_ATTRIBUTES = 'alg_wc_wl_save_attributes';
		const OPTION_REMOVE_IF_BOUGHT = 'alg_wc_wl_remove_if_bought';
		const OPTION_REMOVE_IF_BOUGHT_STATUS = 'alg_wc_wl_remove_if_bought_status';
		const OPTION_STOCK_ALERT = 'alg_wc_wl_stock_alert_admin';
		const OPTION_IMAGES_ON_EMAILS = 'alg_wc_wl_images_on_emails';
		const OPTION_NOTE_FIELD = 'alg_wc_wl_note_field';
		const OPTION_NOTE_FIELD_LABEL = 'alg_wc_wl_note_field_label';
		const OPTION_NOTE_FIELD_TYPE = 'alg_wc_wl_note_field_type';
		const OPTION_NOTE_FIELD_MAX_LENGTH = 'alg_wc_wl_note_field_max_length';
		const OPTION_SHOW_SKU = 'alg_wc_wl_show_sku';
		const OPTION_SHOW_QUANTITY = 'alg_wc_wl_show_quantity';
		const OPTION_SHOW_PRODUCT_CATEGORY = 'alg_wc_wl_show_prod_category';
		const OPTION_SHOW_PRODUCT_DESCRIPTION = 'alg_wc_wl_show_prod_desc';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'wish_list';
			$this->desc = __( 'Wishlist Page', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}
		
		/**
		 * get_custom_product_taxonomies.
		 *
		 * @version 2.0.3
		 * @since   2.0.3
		 *
		 * @return array
		 */
		function get_custom_product_taxonomies() {
			$taxonomies = get_object_taxonomies( 'product', 'objects' );
			return wp_list_pluck( $taxonomies, 'label', 'name' );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.5.6
		 * @since   1.0.0
		 */
		function get_settings( $settings = array() ) {
			$pages_pretty = array( '' => __( 'None', 'wish-list-for-woocommerce' ) );
			$pages        = get_pages( array() );
			foreach ( $pages as $page ) {
				$pages_pretty[ $page->ID ] = $page->post_title;
			}

			$new_settings = array(
				array(
					'title'     => __( 'Wishlist options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_loptions',
				),
				array(
					'title'     => __( 'Wishlist page', 'alg-wc-compare-products' ),
					'desc'      => sprintf( __( 'A page that displays the wishlist. You can create your own page simply adding the %s shortcode on it.', 'wish-list-for-woocommerce' ), '<code>[alg_wc_wl]</code>' ),
					'desc_tip'  => __( 'Create your own page and add shortcode [alg_wc_wl]', 'wish-list-for-woocommerce' ),
					'id'        => Alg_WC_Wish_List_Page::PAGE_OPTION,
					'default'   => Alg_WC_Wish_List_Page::get_wish_list_page_id(),
					'options'   => $pages_pretty,
					'class'     => 'chosen_select',
					'type'      => 'select',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_loptions',
				),

				


				// Columns.
				array(
					'title'     => __( 'Wishlist table columns', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_table_cols_opt',
				),
				array(
					'title'     => __( 'Stock', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product stock', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_STOCK,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Price', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product price', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_PRICE,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Add to cart button', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show add to cart button', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ADD_TO_CART_BUTTON,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'SKU', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product SKU', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_SKU,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Quantity', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product quantity', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_QUANTITY,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Description', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product description', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_PRODUCT_DESCRIPTION,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Attributes', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show products attributes on the wish list', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'In order to see the attributes, it is necessary to select a variation before adding it to wish list.', 'wish-list-for-woocommerce' ).'<br />'.
					               sprintf( __( 'The option %s needs to be enabled.', 'wish-list-for-woocommerce' ), '<strong>' . __( 'General > Variable products', 'wish-list-for-woocommerce' ) . '</strong>' ),
					'id'        => self::OPTION_SAVE_ATTRIBUTES,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Images on Emails', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product images on emails', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_IMAGES_ON_EMAILS,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Subtotal', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show subtotal', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_subtotal_column',
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Categories', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Show products categories on the wish list', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_PRODUCT_CATEGORY,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Taxonomies', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_taxonomies',
					'default'   => array(),
					'class'     => 'chosen_select',
					'options'   => $this->get_custom_product_taxonomies(),
					'type'      => 'multiselect',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_table_cols_opt',
				),
				
				// Subtotal 
				array(
					'title' => __( 'Wishlist items subtotal', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'The subtotal will show the sum of all the items in the wish list.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_subtotal_opts',
				),
				array(
					'title'   => __( 'Subtotal', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display subtotal', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_subtotal',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Position', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_subtotal_position',
					'type'    => 'multiselect',
					'options' => array(
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE => __( 'Before Wish list table', 'wish-list-for-woocommerce' ),
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER  => __( 'After Wish list table', 'wish-list-for-woocommerce' ),
					),
					'default' => array( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER ),
					'class'   => 'chosen_select',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_subtotal_opts',
				),
				
				// Auto remove items
				array(
					'title' => __( 'Auto remove items', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Remove products from wish list in some specific circumstance.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_auto_remove_options',
				),
				array(
					'title'   => __( 'Purchased items', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Remove products from wish list in case they get purchased', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_REMOVE_IF_BOUGHT,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'desc'    => __( 'Order status that will make products be removed from wish list.', 'multi-order-for-woocommerce' ),
					'id'      => self::OPTION_REMOVE_IF_BOUGHT_STATUS,
					'type'    => 'multiselect',
					'class'   => 'chosen_select',
					'options' => wc_get_order_statuses(),
					'default' => array( 'wc-completed', 'wc-processing' )
				),
				array(
					'title'   => __( 'In cart items', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Remove item from wish list in case it gets added to cart', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_remove_if_added_to_cart',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_auto_remove_options',
				),
				
				
				// Tab
				array(
					'title'     => __( 'My account - Wishlist tab', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_tab_options',
				),
				array(
					'title'     => __( 'Wishlist tab', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Create a wishlist tab on "my account" page', 'wish-list-for-woocommerce' ),
					'desc_tip'  => sprintf(__( 'If it does not work on the first attempt, please go to <a href="%s"> Permalink Settings</a> and save changes.', 'wish-list-for-woocommerce' ), admin_url('options-permalink.php') ),
					'id'        => self::OPTION_TAB,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Tab slug', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Tab slug that will be part of url', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'Note: You cannot have two identical slugs on your site. If something goes wrong, try to change this slug', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TAB_SLUG,
					'default'   => 'my-wish-list',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Tab label', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Tab label that will be part of my account menu', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TAB_LABEL,
					'default'   => __( 'Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Priority', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'Try to change it if you are not getting good results, probably lowering it.', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Manages the WooCommerce hook responsible for adding the tab on My Account page. ', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TAB_PRIORITY,
					'default'   => 20,
					'type'      => 'number',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_tab_options',
				),



			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;