<?php
/**
 * Wish List for WooCommerce - Wish list Section Settings
 *
 * @version 1.5.6
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_List' ) ) :

	class Alg_WC_Wish_List_Settings_List extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_STOCK              = 'alg_wc_wl_lstock';
		const OPTION_PRICE              = 'alg_wc_wl_lprice';
		const OPTION_ADD_TO_CART_BUTTON = 'alg_wc_wl_ladd_to_cart_btn';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'wish_list';
			$this->desc = __( 'Wish list', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
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
					'title'     => __( 'Wish list options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_loptions',
				),
				array(
					'title'     => __( 'Wish list page', 'alg-wc-compare-products' ),
					'desc'      => sprintf( __( 'A page that displays the wish list. You can create your own page simply adding the %s shortcode in it.', 'wish-list-for-woocommerce' ), '<code>[alg_wc_wl]</code>' ),
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
					'title'     => __( 'Wish list table columns', 'wish-list-for-woocommerce' ),
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
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_table_cols_opt',
				),



			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;