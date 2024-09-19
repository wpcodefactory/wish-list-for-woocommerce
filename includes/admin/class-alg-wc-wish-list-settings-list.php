<?php
/**
 * Wishlist for WooCommerce - Wishlist Section Settings
 *
 * @version 2.3.7
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
		
		protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'wish_list';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
			
			$this->desc = __( 'Wishlist Page', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}
		
		/**
		 * get_custom_product_taxonomies.
		 *
		 * @version 2.3.7
		 * @since   2.3.7
		 *
		 * @return array
		 */
		function get_custom_product_taxonomies() {
			$taxonomies = get_object_taxonomies( 'product', 'objects' );
			return wp_list_pluck( $taxonomies, 'label', 'name' );
		}
		
		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.3.7
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 9;
		}

		/**
		 * get_settings.
		 *
		 * @version 2.3.7
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
					'title'   => __( 'Sorting', 'alg-wc-compare-products' ),
					'desc'    => __( 'The way the wishlist items will be sorted.', 'alg-wc-compare-products' ),
					'id'      => 'alg_wc_wl_sorting_method',
					'default' => 'latest_to_bottom',
					'options' => array(
						'latest_to_bottom' => __( 'Latest to bottom', 'alg-wc-compare-products' ),
						'alpha_asc'        => __( 'Alphabetical - ASC', 'alg-wc-compare-products' ),
						'alpha_desc'       => __( 'Alphabetical - DESC', 'alg-wc-compare-products' ),
					),
					'class'   => 'chosen_select',
					'type'    => 'select',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_loptions',
				),
				
				array(
					'title' => __( 'Dropdown sorting', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Sort the wishlist items by choosing an option from the dropdown.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_dropdown_sorting_opts',
				),
				
				array(
					'title'   => __( 'Dropdown ordering', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable wishlist item ordering by choosing an option from the dropdown. Ensure the dropdown also appears on the wishlist page.', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_dropdown_sorting',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_dropdown_sorting_opts',
				),
				
				array(
					'title' => __( 'Duplicate', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Duplicate functionality for wishlist.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_duplicate_opts',
				),
				
				array(
					'title'   => __( 'Duplicate Functionality', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'A "COPY" button will appear on every wishlist page. This function is applicable to logged-in users.', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_duplicate_option',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_duplicate_opts',
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
					'title'   => __( 'When purchased', 'wish-list-for-woocommerce' ),
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
					'title'   => __( 'When added to cart', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Remove item from wish list in case it gets added to cart', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_remove_if_added_to_cart',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_auto_remove_options',
				),
				
				
				array(
					'title' => __( 'Drag and drop Ordering', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Sort the wish list items using drag and drop.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_drag_drop_opts',
				),
				array(
					'title'   => __( 'Drag and drop Ordering', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable wish list item ordering using drag and drop', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_drag_drop_sorting',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'    => __( 'Jquery UI Touch Punch', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Enqueue Touch Punch script responsible for enabling the use of touch events', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'desc_tip' => __( 'Enable if the drag and drop does not work on some IOS devices.', 'wish-list-for-woocommerce' ),
					'id'       => 'alg_wc_wl_drag_drop_touch_punch',
					'default'  => 'no',
					'type'     => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'   => __( 'Desktop', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display drag and drop on desktop', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_drag_drop_sorting_desktop',
					'default' => 'yes',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'   => __( 'Mobile', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display drag and drop on mobile', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_drag_drop_sorting_mobile',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_drag_drop_opts',
				),
				
				array(
					'title' => __( 'Arrow ordering', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Sort the wish list items by clicking on down/up arrows present on each row.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_arrow_sorting_opts',
				),
				array(
					'title'   => __( 'Arrow ordering', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable wish list item ordering with arrows', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_arrow_sorting',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'   => __( 'Desktop', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display arrow sorting on desktop', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_arrow_sorting_desktop',
					'default' => 'no',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'   => __( 'Mobile', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display arrow sorting on mobile', 'wish-list-for-woocommerce' ) . '<br>' . apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) ),
					'id'      => 'alg_wc_wl_arrow_sorting_mobile',
					'default' => 'yes',
					'type'    => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_arrow_sorting_opts',
				),
				
				
				
				array(
					'title' => __( 'Note field', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'A new column added to the wish list table allowing users to enter a note for each added item.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_note_opt',
				),
				array(
					'title'   => __( 'Note field', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable the note field', 'wish-list-for-woocommerce' ),
					'desc_tip'=> sprintf( __( 'It\'s necessary to enable the option %s', 'wish-list-for-woocommerce' ), '<strong>' . __( 'Wish list table columns > Attributes > Show products attributes on the wish list', 'wish-list-for-woocommerce' ) . '</strong>' ),
					'id'      => self::OPTION_NOTE_FIELD,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Field label', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_NOTE_FIELD_LABEL,
					'default' => __( 'Note', 'wish-list-for-woocommerce' ),
					'type'    => 'text',
				),
				array(
					'title'   => __( 'Field type', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_NOTE_FIELD_TYPE,
					'default' => 'text',
					'options' => array(
						'text'     => __( 'Text', 'wish-list-for-woocommerce' ),
						'textarea' => __( 'TextArea', 'wish-list-for-woocommerce' ),
					),
					'type'    => 'select',
				),
				array(
					'title'    => __( 'Max length', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Max number of characters allowed in the field.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_NOTE_FIELD_MAX_LENGTH,
					'default'  => 20,
					'type'     => 'number',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_note_opt',
				),
				
				array(
					'title' => __( 'Stock alert', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Notify users via email when out-of-stock products they had added to their wishlist are in-stock.', 'wish-list-for-woocommerce' ) . '<br />' .
					           __( 'Note: Users can enable/disable this option on their wish list pages.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_stock_alert_opt',
				),
				array(
					'title'   => __( 'Stock alert', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable Stock alert', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STOCK_ALERT,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_stock_alert_opt',
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