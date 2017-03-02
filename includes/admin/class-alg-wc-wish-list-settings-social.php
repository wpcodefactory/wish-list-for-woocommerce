<?php
/**
 * Wish List for WooCommerce - Social Section Settings
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Social' ) ) :

	class Alg_WC_Wish_List_Settings_Social extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ENABLE         = 'alg_wc_wl_social_enable';
		const OPTION_SHARE_POSITION = 'alg_wc_wl_social_position';
		const OPTION_FACEBOOK       = 'alg_wc_wl_social_facebook';
		const OPTION_GOOGLE         = 'alg_wc_wl_social_google';
		const OPTION_TWITTER        = 'alg_wc_wl_social_twitter';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true) {
			$this->id   = 'social';
			$this->desc = __( 'Social Networks', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title'    => __( 'General options', 'wish-list-for-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_wl_social',
				),
				array(
					'title'    => __( 'Enable', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Share on Social Networks', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => self::OPTION_ENABLE,
					'default'  => 'yes',
				),
				array(
					'title'    => __( 'Position', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Places where the social buttons will be loaded', 'wish-list-for-woocommerce' ),
					'type'     => 'multiselect',
					'options'  => array(
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE => __( 'Before Wish list table', 'wish-list-for-woocommerce' ),
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER  => __( 'After Wish list table', 'wish-list-for-woocommerce' ),
					),
					'id'       => self::OPTION_SHARE_POSITION,
					'default'  => array( 'alg_wc_wl_table_before' ),
					'class'    => 'chosen_select',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_wl_social',
				),
				array(
					'title'    => __( 'Social Networks', 'wish-list-for-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_wl_social_networks',
				),
				array(
					'title'    => __( 'Facebook', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Share on Facebook', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => self::OPTION_FACEBOOK,
					'default'  => 'yes',
				),
				array(
					'title'    => __( 'Google+', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Share on Google+', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => self::OPTION_GOOGLE,
					'default'  => 'yes',
				),
				array(
					'title'    => __( 'Twitter', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Share on Twitter', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => self::OPTION_TWITTER,
					'default'  => 'yes',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_wl_social_networks',
				),
			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;