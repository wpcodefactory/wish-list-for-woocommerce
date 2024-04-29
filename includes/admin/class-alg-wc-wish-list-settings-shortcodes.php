<?php
/**
 * Wish List for WooCommerce Pro - Shortcodes.
 *
 * @version 2.3.7
 * @since   2.2.1
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Shortcodes' ) ) :

	class Alg_WC_Wish_List_Settings_Shortcodes extends Alg_WC_Wish_List_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   2.2.1
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'shortcodes';
			$this->desc = __( 'Shortcodes', 'wish-list-for-woocommerce' );
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array(
				$this,
				'get_settings'
			), PHP_INT_MAX );
			
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_custom_product_taxonomies.
		 *
		 * @version 2.2.1
		 * @since   2.2.1
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
		 * @version 2.2.1
		 * @since   2.2.1
		 */
		function get_settings( $settings = array() ) {
			
			$shortcode_opts = array(
				array(
					'title' => __( 'Shortcodes', 'wish-list-for-woocommerce' ),
					'desc'  => __( 'Wishlist shortcodes.', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_shortcode_opts',
				),
				array(
					'title' => __( '[alg_wc_wl]', 'wish-list-for-woocommerce' ),
					'desc'  => __( 'Displays the wishlist', 'wish-list-for-woocommerce' ),
					'type'  => 'checkbox',
					'default' => 'yes',
					'id'    => 'alg_wc_wl_sc_alg_wc_wl',
				),
				array(
					'title'    => __( '[alg_wc_wl_counter]', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Number indicating the amount of items in the wishlist', 'wish-list-for-woocommerce' ),
					'default' => 'yes',
					'desc_tip' => Alg_WC_Wish_List_Shortcodes::format_shortcode_params( array(
						'ignore_excluded_items' => array(
							'desc'    => __( 'Ignore excluded items.', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
						'template'              => array(
							'desc'    => __( 'HTML template used to display the counter.', 'cost-of-goods-for-woocommerce' ),
							'default' => '<span class="alg-wc-wl-counter">{content}</span>'
						)
					) ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_wl_sc_counter',
				),
				array(
					'title'    => __( '[alg_wc_wl_remove_all_btn]', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Button that removes all products from the wishlist', 'wish-list-for-woocommerce' ),
					'default' => 'yes',
					'desc_tip' => Alg_WC_Wish_List_Shortcodes::format_shortcode_params( array(
						'tag'          => array(
							'desc'    => __( 'HTML tag.', 'cost-of-goods-for-woocommerce' ),
							'default' => 'button',
						),
						'remove_label' => array(
							'desc'    => __( 'Label used for the remove button.', 'cost-of-goods-for-woocommerce' ),
							'default' => __( 'Remove all', 'wish-list-for-woocommerce' ),
						),
						'auto_hide'    => array(
							'desc'    => __( 'Hides the button after clicking on it.', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
						'show_loading' => array(
							'desc'    => __( 'Shows a loading icon after clicking on the button.', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
					) ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_wl_sc_remove_all_btn',
				),
				
				array(
					'title'    => '[alg_wc_wl_toggle_item_btn]',
					'desc'     => __( 'Button that will add or remove an item from the wishlist', 'wish-list-for-woocommerce' ),
					'desc_tip' => \Alg_WC_Wish_List_Shortcodes::format_shortcode_params( array(
						'product_id' => array(
							'desc' => __( 'Product ID.', 'cost-of-goods-for-woocommerce' ) . ' ' .
							          __( 'If empty, will try to get the product id from the current product.', 'cost-of-goods-for-woocommerce' ),
						),
					) ),
					'type'     => 'checkbox',
					'default'  => 'yes',
					'id'       => 'alg_wc_wl_sc_toggle_item_btn',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'    => '[alg_wc_wl_icon]',
					'desc'     => __( 'Wishlist icon with a number indicating the amount of items in the wishlist.', 'wish-list-for-woocommerce' ),
					'desc_tip' => sprintf( __( 'Used behind the scenes on the %s option, an enhanced version of the %s shortcode.', 'wish-list-for-woocommerce' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_wish_list' ) . '">' . __( 'General > Nav menu item', 'wish-list-for-woocommerce' ) . '</a>', '<code>[alg_wc_wl_counter]</code>' ) . ' ' .
					              sprintf( __( 'The icon used is the same from the thumb button and can be changed with the option %s.', 'wish-list-for-woocommerce' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_wish_list&section=style' ) . '">' . __( 'Style > Thumb button > Icon - Added', 'wish-list-for-woocommerce' ) ) . '</a>' .
					              '<br /><br />' .
					              \Alg_WC_Wish_List_Shortcodes::format_shortcode_params( array(
						              'ignore_excluded_items' => array(
							              'desc'    => __( 'Ignore excluded items.', 'cost-of-goods-for-woocommerce' ),
							              'default' => 'false',
						              ),
						              'link' => array(
							              'desc'    => __( 'If enabled, the icon will point to the wishlist page.', 'cost-of-goods-for-woocommerce' ),
							              'default' => 'false',
						              ),
					              ) ),
					'type'     => 'checkbox',
					'default'  => 'yes',
					'id'       => 'alg_wc_wl_sc_icon',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_shortcode_opts',
				),
			);

			return parent::get_settings( array_merge( $settings, $shortcode_opts ) );
		}

	}

endif;