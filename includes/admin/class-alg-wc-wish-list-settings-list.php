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
		const OPTION_TAB                = 'alg_wc_wl_tab';
		const OPTION_TAB_SLUG           = 'alg_wc_wl_tab_slug';
		const OPTION_TAB_LABEL          = 'alg_wc_wl_tab_label';
		const OPTION_TAB_PRIORITY       = 'alg_wc_wl_tab_priority';

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
					'title'     => __( 'Page', 'alg-wc-compare-products' ),
					'desc'      => __( 'A page that displays the wish list. You can create your own page simply adding the [alg_wc_wl] shortcode in it', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'Create your own page and add shortcode [alg_wc_wl]', 'wish-list-for-woocommerce' ),
					'id'        => Alg_WC_Wish_List_Page::PAGE_OPTION,
					'default'   => Alg_WC_Wish_List_Page::get_wish_list_page_id(),
					'options'   => $pages_pretty,
					'class'     => 'chosen_select',
					'type'      => 'select',
				),
				array(
					'title'     => __( 'Show stock', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows product stock on wish list', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_STOCK,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Show price', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows product price on wish list', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_PRICE,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Add to cart button', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows add to cart button on wish list', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ADD_TO_CART_BUTTON,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_loptions',
				),

				// Tab
				array(
					'title'     => __( 'My account tab', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_tab_options',
				),
				array(
					'title'     => __( 'Create tab', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Create tab on My Account Page', 'wish-list-for-woocommerce' ),
					'desc_tip'  => sprintf(__( 'If it does not work on the first attempt, please go to <a href="%s">Permalink Settings </a> and save changes', 'wish-list-for-woocommerce' ), admin_url('options-permalink.php') ),
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
					'default'   => __( 'Wish list', 'wish-list-for-woocommerce' ),
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
				)

			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;