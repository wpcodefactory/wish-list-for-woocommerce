<?php
/**
 * Wish List for WooCommerce - Shortcodes settings
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Shortcodes' ) ) :

	class Alg_WC_Wish_List_Settings_Shortcodes extends Alg_WC_Wish_List_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'shortcodes';
			$this->desc = __( 'Shortcodes', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
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
					'desc'     => __( 'A number with the amount of items in the wishlist', 'wish-list-for-woocommerce' ),
					'default' => 'yes',
					'desc_tip' => Alg_WC_Wish_List_Shortcodes::format_shortcode_params( array(
						'ignore_excluded_items' => array(
							'desc'    => __( 'Ignore excluded items', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
						'template'              => array(
							'desc'    => __( 'HTML template used to display the counter', 'cost-of-goods-for-woocommerce' ),
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
							'desc'    => __( 'HTML tag', 'cost-of-goods-for-woocommerce' ),
							'default' => 'button',
						),
						'remove_label' => array(
							'desc'    => __( 'Label used for the remove button', 'cost-of-goods-for-woocommerce' ),
							'default' => __( 'Remove all', 'wish-list-for-woocommerce' ),
						),
						'auto_hide'    => array(
							'desc'    => __( 'Hides the button after clicking on it', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
						'show_loading' => array(
							'desc'    => __( 'Shows a loading icon after clicking on the button', 'cost-of-goods-for-woocommerce' ),
							'default' => 'false',
						),
					) ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_wl_sc_remove_all_btn',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_shortcode_opts',
				)
			);

			return parent::get_settings( array_merge( $settings, $shortcode_opts ) );
		}

	}

endif;