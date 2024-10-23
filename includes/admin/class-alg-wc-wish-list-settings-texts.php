<?php
/**
 * Wishlist for WooCommerce Pro - Texts
 *
 * @version 3.1.2
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Texts' ) ) {

	class Alg_WC_Wish_List_Settings_Texts extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_TEXTS_ADD_TO_WISH_LIST        		= 'alg_wc_wl_texts_add_to_wish_list';
		const OPTION_TEXTS_SEE_YOUR_WISH_LIST      		= 'alg_wc_wl_texts_see_your_wish_list';
		const OPTION_TEXTS_ADDED_TO_WISH_LIST      		= 'alg_wc_wl_texts_remove_added_to_wish_list';
		const OPTION_TEXTS_REMOVE_FROM_WISH_LIST   		= 'alg_wc_wl_texts_remove_from_wish_list';
		const OPTION_TEXTS_REMOVED_FROM_WISH_LIST  		= 'alg_wc_wl_texts_removed_from_wish_list';
		const OPTION_TEXTS_ERROR                   		= 'alg_wc_wl_texts_error';
		const OPTION_TEXTS_EMAIL_TEXTAREA          		= 'alg_wc_wl_texts_email_textarea';
		const OPTION_TEXTS_EMAIL_LINK              		= 'alg_wc_wl_texts_email_link';
		const OPTION_TEXTS_SHARE                   		= 'alg_wc_wl_texts_share';
		const OPTION_TEXTS_SHARE_ADMIN             		= 'alg_wc_wl_texts_admin';
		const OPTION_TEXTS_SHARE_FRIENDS           		= 'alg_wc_wl_texts_friends';
		const OPTION_TEXTS_TWITTER_SHARE           		= 'alg_wc_wl_texts_twitter';
		const OPTION_TEXTS_DISALLOW_UNLOGGED       		= 'alg_wc_wl_texts_disallow_unlogged';
		const OPTION_TEXTS_EMPTY_WISHLIST          		= 'alg_wc_wl_texts_empty_wishlist';
		const OPTION_TEXTS_REMOVE_ALL_BTN_LABEL    		='alg_wc_wl_texts_remove_all_btn_label';
		const OPTION_TEXTS_REMOVE_ALL_SUCCESS_TEXT 		= 'alg_wc_wl_texts_remove_all_success_text';
		const OPTION_TEXTS_ADDED_TO_WISH_LIST_MULTIPLE 	= 'alg_wc_wl_texts_add_to_wish_list_multiple';
		
		protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'texts';
			
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array(
				$this,
				'get_settings'
			), PHP_INT_MAX );
			
			$this->desc = __( 'Texts', 'wish-list-for-woocommerce' );
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
			return 9;
		}

		/**
		 * get_settings.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function get_settings( $settings = null ) {
			$new_settings = array(

				// General options
				array(
					'title'     => __( 'Text options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Options that can be used to customize texts', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_general_opt',
				),
				array(
					'title'     => __( 'See your wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_SEE_YOUR_WISH_LIST,
					'desc'      => 'A link pointing to your wishlist page, after an item has been added to it.',
					'default'   => __('See your wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Add to Wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_ADD_TO_WISH_LIST,
					'desc'      => 'Message for adding an item in wishlist.',
					'default'   => __('Add to Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Remove from Wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_REMOVE_FROM_WISH_LIST,
					'desc'      => 'Message for removing an item from wishlist.',
					'default'   => __('Remove from Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Error', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_ERROR,
					'desc'      => __( 'Error text if some problem occurs', 'wish-list-for-woocommerce' ),
					'default'   => __('Sorry, Some error ocurred. Please, try again later.', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Added to wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_ADDED_TO_WISH_LIST,
					'desc'      => __( 'Notification text after an item is added to wishlist.', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( '%s will be replaced by the product title', 'wish-list-for-woocommerce' ),
					'default'   => __('%s was successfully added to wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
                array(
	                'title'     => __( 'Removed from wishlist', 'wish-list-for-woocommerce' ),
	                'id'        => self::OPTION_TEXTS_REMOVED_FROM_WISH_LIST,
	                'desc'      => __( 'Notification text after an item is removed from wishlist.', 'wish-list-for-woocommerce' ),
	                'desc_tip'  => __( '%s will be replaced by the product title', 'wish-list-for-woocommerce' ),
	                'default'   => __('%s was successfully removed from wishlist', 'wish-list-for-woocommerce' ),
	                'type'      => 'text',
	                'class'     => 'regular-input',
                ),
				array(
					'title'     => __( 'Disallow Unlogged', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_DISALLOW_UNLOGGED,
					'desc'      => __( 'Notification text if unlogged users try to interact with the Wishlist while not allowed to.', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( '{login} will be replaced by a login string with a link to "My Account" page', 'wish-list-for-woocommerce' ),
					'default'   => __( 'Please {login} if you want to use the Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Empty Wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_EMPTY_WISHLIST,
					'default'   => __( 'The Wishlist is empty.', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_texts_general_opt',
				),

				// Remove all from wishlist
				array(
					'title'     => __( 'Remove all from wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Remove all items from wishlist', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_remove_all_opt',
				),
				array(
					'title'     => __( 'Button label', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_REMOVE_ALL_BTN_LABEL,
					'default'   => __( 'Remove all', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Success notification', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'The success notification after all items have been removed from wishlist.', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_REMOVE_ALL_SUCCESS_TEXT,
					'default'   => __( 'All the items have been removed from your wishlist.', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_texts_remove_all_opt',
				),

				// Social
				array(
					'title'     => __( 'Sharing', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Sharing options', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_social_opt',
				),
				array(
					'title'     => __( 'Before Sharing Icons', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_SHARE,
					'desc'      => __( 'Text displayed before the sharing icons', 'wish-list-for-woocommerce' ),
					'default'   => __('Share on', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Twitter message', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_TWITTER_SHARE,
					'desc'      => __( 'Text displayed on Twitter', 'wish-list-for-woocommerce' ),
					'default'   => __('Check my Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_texts_general_opt',
				),


				array(
					'title'     => __( 'Email Sharing', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Email Sharing options', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_email_sharing_opt',
				),
				array(
					'title'     => __( 'Share with friends', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_SHARE_FRIENDS,
					'desc'      => __( 'Send wishlist to friends option <strong>(Email sharing)</strong>', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'This is an email sharing option displayed on "Send to" form', 'wish-list-for-woocommerce' ),
					'default'   => __('Friend(s)', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Share with admin', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_SHARE_ADMIN,
					'desc'      => __( 'Send wishlist to admin option <strong>(Email sharing)</strong>', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'This is an email sharing option displayed on "Send to" form', 'wish-list-for-woocommerce' ),
					'default'   => __('Admin', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Email message', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_EMAIL_TEXTAREA,
					'desc'      => __( 'Pre filled email text that is displayed when customers try to send the wishlist by email', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( '%s will be replaced by the wishlist page link', 'wish-list-for-woocommerce' ),
					'default'   => __('Hello, check my wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Link text', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_EMAIL_LINK,
					'desc'      => __( 'Text containing a link to the Wishlist', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'Leave it empty if you don\'t want to show the link', 'wish-list-for-woocommerce' ),
					'default'   => __('Visit my Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_texts_email_sharing_opt',
				),
				
				// Social
				array(
					'title'     => __( 'Multiple Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Sharing options', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_multiple_wishlist_opt',
				),
				array(
					'title'     => __( 'Saved to wishlist', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_TEXTS_ADDED_TO_WISH_LIST_MULTIPLE,
					'desc'      => __( 'Notification text after saved wishlist.', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( '%s will be replaced by the product title', 'wish-list-for-woocommerce' ),
					'default'   => __('Wishlist successfully saved.', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'title'     => __( 'Default Wishlist', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_texts_default_wishlist',
					'default'   => __('Default Wishlist', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'class'     => 'regular-input',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_texts_multiple_wishlist_opt',
				),
			);

			return parent::get_settings( array_merge( $settings, $new_settings ) );

		}
	}
}