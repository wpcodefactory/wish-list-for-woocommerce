<?php
/**
 * Wishlist for WooCommerce - Social Section Settings
 *
 * @version 2.3.7
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Social' ) ) :

	class Alg_WC_Wish_List_Settings_Social extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ENABLE             = 'alg_wc_wl_social_enable';
		const OPTION_SHARE_POSITION     = 'alg_wc_wl_social_position';
		const OPTION_FACEBOOK           = 'alg_wc_wl_social_facebook';
		const OPTION_TWITTER            = 'alg_wc_wl_social_twitter';
		const OPTION_EMAIL              = 'alg_wc_wl_social_email';
		const OPTION_EMAIL_ADMIN_EMAILS = 'alg_wc_wl_social_email_adm_emails';
		const OPTION_EMAIL_SUBJECT      = 'alg_wc_wl_social_email_subject';
		const OPTION_COPY               = 'alg_wc_wl_social_copy';

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'social';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
			
			$this->desc = __( 'Share', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
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
			return 8;
		}

		/**
		 * get_settings.
		 *
		 * @version 1.7.6
		 * @since   1.0.0
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title' => __( 'Share options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Add sharing options on Wishlist page so customers can share their wishlists on their social media profiles.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_social',
				),
				array(
					'title'   => __( 'Enable', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share wishlist', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_ENABLE,
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Position', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Places where the share buttons will be loaded', 'wish-list-for-woocommerce' ),
					'type'    => 'multiselect',
					'options' => array(
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE => __( 'Before Wishlist table', 'wish-list-for-woocommerce' ),
						Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER  => __( 'After Wishlist table', 'wish-list-for-woocommerce' ),
					),
					'id'      => self::OPTION_SHARE_POSITION,
					'default' => array( 'alg_wc_wl_table_before' ),
					'class'   => 'chosen_select',
				),
				array(
					'title'   => __( 'Admin email(s)', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Admin email(s) that will receive wishlist notifications from users. ', 'wish-list-for-woocommerce' ),
					'desc_tip'=> __( 'Separate multiple values using commas. ', 'wish-list-for-woocommerce' ).'<br />'.__( 'Leave it empty if you want to hide this admin option on frontend. ', 'wish-list-for-woocommerce' ),
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
					'title' => __( 'Email', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_social_email',
				),
				array(
					'title'   => __( 'Subject', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Display a subject field', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_EMAIL_SUBJECT,
					'default' => 'no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_social_email',
				),
				array(
					'title' => __( 'Share buttons', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_social_networks',
				),
				array(
					'title'    => __( 'Facebook', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Share on Facebook', 'wish-list-for-woocommerce' ),
					'desc_tip' => sprintf( __( 'In order to display a proper image on Facebook posts a meta with the %s property is required.', 'wish-list-for-woocommerce' ), '<strong>' . 'og:image' . '</strong>' ) . '<br />' .
					              sprintf( __( 'You can easily setup it with the <a href="%s">Yoast SEO</a> plugin for example.', 'wish-list-for-woocommerce' ), 'https://wordpress.org/plugins/wordpress-seo/' ),
					'type'     => 'checkbox',
					'id'       => self::OPTION_FACEBOOK,
					'default'  => 'yes',
				),
				array(
					'title'   => __( 'X/Twitter', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share on X/Twitter', 'wish-list-for-woocommerce' ),
					'desc_tip' => sprintf( __( 'In order to display a proper image on Tweets a meta with the %s name is required.', 'wish-list-for-woocommerce' ), '<strong>' . 'twitter:card' . '</strong>' ) . '<br />' .
					              sprintf( __( 'You can easily setup it with the <a href="%s">Yoast SEO</a> plugin for example.', 'wish-list-for-woocommerce' ), 'https://wordpress.org/plugins/wordpress-seo/' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_TWITTER,
					'default' => 'yes',
				),
				/*
				array(
					'title'   => __( 'Email', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Share via Email', 'wish-list-for-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => self::OPTION_EMAIL,
					'default' => 'yes',
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				*/
				array(
					'title'   => __( 'Copy', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Copy Wishlist link to clipboard', 'wish-list-for-woocommerce' ),
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