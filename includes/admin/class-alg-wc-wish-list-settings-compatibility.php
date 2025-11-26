<?php
/**
 * Wish List for WooCommerce - Compatibility.
 *
 * @version 3.3.0
 * @since   2.0.9
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Compatibility' ) ) :

	class Alg_WC_Wish_List_Settings_Compatibility extends Alg_WC_Wish_List_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 3.2.2
		 * @since   2.0.9
		 */
		function __construct( $handle_autoload = false ) {
			$this->id = 'compatibility';
			parent::__construct( $handle_autoload );
		}

		/**
		 * set_section_variables.
		 *
		 * @version 3.2.2
		 * @since   3.2.2
		 *
		 * @return void
		 */
		public function set_section_variables() {
			parent::set_section_variables();
			$this->desc = __( 'Compatibility', 'wish-list-for-woocommerce' );
		}

		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.0.9
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 11;
		}

		/**
		 * get_settings.
		 *
		 * @version 3.3.0
		 * @since   2.0.9
		 */
		function get_settings( $settings = array() ) {

			$the7_compatibilty_opts = array(
				$this->get_default_compatibility_title_option( array(
					'title' => __( 'The7', 'wish-list-for-woocommerce' ),
					'link'  => 'https://the7.io/',
					'type'  => 'theme',
					'id'    => 'alg_wc_wl_the7_compatibility_opts',
				) ),
				array(
					'title'    => __( 'TI WooCommerce Wishlist', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Replace TI Wishlist shortcode by ours', 'wish-list-for-woocommerce' ),
					'desc_tip' => sprintf( __( 'The %s shortcode will trigger %s.', 'wish-list-for-woocommerce' ), '<code>[ti_wishlists_addtowishlist]</code>', '<code>[alg_wc_wl_toggle_item]</code>' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'id'       => 'alg_wc_wl_the7_ti_wishlist_replace_shortcode',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_the7_compatibility_opts',
				),
			);

			$wbw_product_table_opts = array(
				$this->get_default_compatibility_title_option( array(
					'title' => __( 'WBW Product Table', 'wish-list-for-woocommerce' ),
					'link'  => 'https://wordpress.org/plugins/woo-product-tables/',
					'type'  => 'plugin',
					'id'    => 'alg_wc_wl_wbw_product_table_opts',
				) ),
				array(
					'title'             => __( 'Wishlist column', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Add a Wishlist column to the product table', 'wish-list-for-woocommerce' ),
					'desc_tip'          => '',
					'type'              => 'checkbox',
					'id'                => 'alg_wc_wl_wbw_product_table_wishlist_col',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => 'no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_product_table_opts',
				),
			);

			return parent::get_settings( array_merge( $settings, $the7_compatibilty_opts, $wbw_product_table_opts ) );
		}

		/**
		 * get_default_compatibility_title_option.
		 *
		 * @version 3.2.9
		 * @since   3.2.9
		 *
		 * @param $args
		 *
		 * @return array
		 */
		function get_default_compatibility_title_option( $args = null ) {
			$args = wp_parse_args( $args, array(
				'link'  => '',
				'title' => '',
				'type'  => 'plugin', // plugin | theme
				'id'    => '',
			) );

			$product_type = 'plugin' === $args['type'] ? __( 'plugin', 'wish-list-for-woocommerce' ) : __( 'theme', 'wish-list-for-woocommerce' );

			return array(
				'title' => $args['title'],
				'type'  => 'title',
				'desc'  => sprintf(
					__( 'Compatibility with %s %s.', 'wish-list-for-woocommerce' ),
					'<a href="' . esc_url( $args['link'] ) . '" target="_blank">' . esc_html( $args['title'] ) . '</a>',
					$product_type
				),
				'id'    => $args['id']
			);
		}


	}

endif;