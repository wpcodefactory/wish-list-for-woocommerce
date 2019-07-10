<?php
/**
 * Wish List for WooCommerce - Social Section Settings
 *
 * @version 1.5.8
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Social' ) ) :

	class Alg_WC_Wish_List_Settings_Social extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ENABLE             = 'alg_wc_wl_social_enable';
		const OPTION_SHARE_POSITION     = 'alg_wc_wl_social_position';
		const OPTION_FACEBOOK           = 'alg_wc_wl_social_facebook';
		const OPTION_GOOGLE             = 'alg_wc_wl_social_google';
		const OPTION_TWITTER            = 'alg_wc_wl_social_twitter';
		const OPTION_EMAIL              = 'alg_wc_wl_social_email';
		const OPTION_EMAIL_ADMIN_EMAILS = 'alg_wc_wl_social_email_adm_emails';
		const OPTION_COPY               = 'alg_wc_wl_social_copy';

		/**
		 * Constructor.
		 *
		 * @version 1.2.2
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'social';
			$this->desc = __( 'Share', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.5.7
		 * @since   1.0.0
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title' => __( 'Share options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_social',
				),
				array(
					'title'   => __( 'Enable', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share wish list', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_ENABLE,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Position', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Places where the share buttons will be loaded', 'wish-list-for-woocommerce' ),
					'type'    => 'multiselect',
					'options' => array(
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE => __( 'Before Wish list table', 'wish-list-for-woocommerce' ),
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER  => __( 'After Wish list table', 'wish-list-for-woocommerce' ),
					),
					'id'      => self::OPTION_SHARE_POSITION,
					'default' => array( 'alg_wc_wl_table_before' ),
					'class'   => 'chosen_select',
				),
				array(
					'title'   => __( 'Admin email(s)', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Admin email(s) that will receive wish list notifications from users. ', 'wish-list-for-woocommerce' ),
					'desc_tip'=> __( 'Separate multiple values using commas. ', 'wish-list-for-woocommerce' ).'<br /><br />'.__( 'Leave it empty if you want to hide this admin option on frontend. ', 'wish-list-for-woocommerce' ),
					'type'    => 'text',
					'id'      => self::OPTION_EMAIL_ADMIN_EMAILS,
					'default' => '',
					'placeholder' => get_option( 'admin_email' ),
					'class'=>'regular-input'
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_social',
				),
				array(
					'title' => __( 'Share buttons', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_social_networks',
				),
				array(
					'title'   => __( 'Facebook', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share on Facebook', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_FACEBOOK,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Google+', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share on Google+', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_GOOGLE,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Twitter', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share on Twitter', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_TWITTER,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Email', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share via Email', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_EMAIL,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Copy', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Copy Wish List link to clipboard', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_COPY,
					'default' => 'no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_social_networks',
				),
			);

			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;