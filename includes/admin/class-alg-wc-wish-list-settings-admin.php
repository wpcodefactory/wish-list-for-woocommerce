<?php
/**
 * Wishlist for WooCommerce - Advanced settings.
 *
 * @version 2.3.7
 * @since   2.0.1
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Admin' ) ) :

	class Alg_WC_Wish_List_Settings_Admin extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_REPORT_WISHLIST_COL_USERS_PAGE = 'alg_wc_wl_report_col_users_page';
		const OPTION_REPORT_WISHLIST_COL_USERS_PAGE_CLEAR_WISHLIST = 'alg_wc_wl_report_col_users_page_clear_wishlist';
		const OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE = 'alg_wc_wl_report_col_products_page';
		const OPTION_REPORT_WISHLIST_COL_PRODUCTS_UNLOGGED = 'alg_wc_wl_report_col_products_page_u';
		const OPTION_REPORT_WISHLIST_PRODUCT_EXPORT_COL = 'alg_wc_wl_report_prod_export_col';
		const OPTION_WISHLIST_USERS_COL_ON_PRODUCTS_EXPORT = 'alg_wc_wl_wl_users_col_on_products_export';
		const OPTION_WISHLIST_USERS_FIELD_ON_PRODUCT_EXPORT = 'alg_wc_wl_wl_users_field_products_export';
		const OPTION_PROD_EXPORT_COL_LOGGED_USERS_AMOUNT_METHOD = 'alg_wc_wl_prod_export_col_logged_u_method';

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   2.0.1
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'admin';
			$this->desc = __( 'Admin', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.0.1
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 11;
		}

		/**
		 * get_settings.
		 *
		 * @version 2.3.6
		 * @since   2.0.1
		 */
		function get_settings( $settings = array() ) {
			$report_opts = array(
				array(
					'title' => __( 'Report', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Wishlist report info oriented to the administrator.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_report_options',
				),
				array(
					'title'    => __( 'Users Page', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'desc'     => sprintf( __( 'Enable a column on the <a href="%s">users list page</a> showing how many items the user has added to the Wishlist.', 'wish-list-for-woocommerce' ), admin_url( 'users.php' ) ),
					'id'       => self::OPTION_REPORT_WISHLIST_COL_USERS_PAGE,
					'default'  => 'no',
				),
				array(
					'title'    => __( 'Users Page "Clear Wishlist" Button', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'desc'     => sprintf( __( 'Enable a column on the <a href="%s">users list page</a> showing a button able to clear Wishlist.', 'wish-list-for-woocommerce' ), admin_url( 'users.php' ) ),
					'id'       => self::OPTION_REPORT_WISHLIST_COL_USERS_PAGE_CLEAR_WISHLIST,
					'default'  => 'no',
				),
				array(
					'title'         => __( 'Products Page', 'wish-list-for-woocommerce' ),
					'type'          => 'checkbox',
					'desc'          => sprintf( __( 'Displays a column on the <a href="%s">products page</a> showing how many times a product has been added to the Wishlist.', 'wish-list-for-woocommerce' ), admin_url( 'edit.php?post_type=product' ) ),
					'desc_tip'      => __( 'Will only work for items added by registered users.' ),
					'id'            => self::OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE,
					'checkboxgroup' => 'start',
					'default'       => 'no',
				),
				array(
					//'title'    => __( 'Wishlist Column on Products Page', 'wish-list-for-woocommerce' ),
					'type'          => 'checkbox',
					'desc'          => __( 'Include items added by unlogged users', 'wish-list-for-woocommerce' ),
					'desc_tip'      => __( 'Will only work for items added to the wishlist after version 1.7.6.', 'wish-list-for-woocommerce' ),
					'id'            => self::OPTION_REPORT_WISHLIST_COL_PRODUCTS_UNLOGGED,
					'checkboxgroup' => 'end',
					'default'       => 'no',
				),
				array(
					'title'   => __( 'Get items method', 'wish-list-for-woocommerce' ),
					'type'    => 'select',
					'desc'    => __( 'Method used to get the wishlist items amount from registered users.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_PROD_EXPORT_COL_LOGGED_USERS_AMOUNT_METHOD,
					'options' => array(
						'post_meta' => __( 'Post meta', 'wish-list-for-woocommerce' ),
						'user_meta' => __( 'User meta', 'wish-list-for-woocommerce' ),
					),
					'default' => 'post_meta',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_report_options',
				),
			);

			$product_exporting_opts = array(
				array(
					'title' => __( 'Product exporting', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => sprintf( __( 'Options related to %s.', 'wish-list-for-woocommerce' ), '<a href="' . admin_url( 'edit.php?post_type=product&page=product_exporter' ) . '">' . __( 'WooCommerce product exporter', 'wish-list-for-woocommerce' ) . '</a>' ),
					'id'    => 'alg_wc_wl_product_exporting_options',
				),
				array(
					'type'     => 'checkbox',
					'title'    => __( 'Wishlist column', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Add a Wishlist column to the WooCommerce product exporter', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Counts how many times the products have been added to the wishlist.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_REPORT_WISHLIST_PRODUCT_EXPORT_COL,
					'default'  => 'no',
				),
				array(
					'type'     => 'checkbox',
					'title'    => __( 'Wishlist users column', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Add a Wishlist users column to the WooCommerce product exporter', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Displays the users who have added products to their wishlists.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_WISHLIST_USERS_COL_ON_PRODUCTS_EXPORT,
					'default'  => 'no',
				),
				array(
					'type'    => 'select',
					'desc'    => __( 'User field displayed on the column.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_WISHLIST_USERS_FIELD_ON_PRODUCT_EXPORT,
					'options' => array(
						'user_email'    => __( 'User email', 'wish-list-for-woocommerce' ),
						'user_nicename' => __( 'User nicename', 'wish-list-for-woocommerce' ),
						'ID'            => __( 'User ID', 'wish-list-for-woocommerce' ),
						'display_name'  => __( 'User display name', 'wish-list-for-woocommerce' ),
					),
					'default' => 'ID',
					'class'   => 'chosen_select',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_product_exporting_options',
				),
			);

			$product_exporting_opts = array(
				array(
					'title' => __( 'Product exporting', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => sprintf( __( 'Options related to %s.', 'wish-list-for-woocommerce' ), '<a href="' . admin_url( 'edit.php?post_type=product&page=product_exporter' ) . '">' . __( 'WooCommerce product exporter', 'wish-list-for-woocommerce' ) . '</a>' ),
					'id'    => 'alg_wc_wl_product_exporting_options',
				),
				array(
					'type'     => 'checkbox',
					'title'    => __( 'Wishlist column', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Add a Wishlist column to the WooCommerce product exporter', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Counts how many times the products have been added to the wishlist.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_REPORT_WISHLIST_PRODUCT_EXPORT_COL,
					'default'  => 'no',
				),
				array(
					'type'     => 'checkbox',
					'title'    => __( 'Wishlist users column', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Add a Wishlist users column to the WooCommerce product exporter', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Displays the users who have added products to their wishlists.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_WISHLIST_USERS_COL_ON_PRODUCTS_EXPORT,
					'default'  => 'no',
				),
				array(
					'type'    => 'select',
					'desc'    => __( 'User field displayed on the column.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_WISHLIST_USERS_FIELD_ON_PRODUCT_EXPORT,
					'options' => array(
						'user_email'    => __( 'User email', 'wish-list-for-woocommerce' ),
						'user_nicename' => __( 'User nicename', 'wish-list-for-woocommerce' ),
						'ID'            => __( 'User ID', 'wish-list-for-woocommerce' ),
						'display_name'  => __( 'User display name', 'wish-list-for-woocommerce' ),
					),
					'default' => 'ID',
					'class'   => 'chosen_select',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_product_exporting_options',
				),
			);

			$import_opts = array(
				array(
					'title' => __( 'Wishlist import', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Settings for importing the Wishlist.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_wl_import_opts',
				),
				array(
					'type'     => 'checkbox',
					'title'    => __( 'Import page', 'wish-list-for-woocommerce' ),
					'desc'     => sprintf( __( 'Create a <a href="%s">Wishlist import page</a>', 'wish-list-for-woocommerce' ), admin_url( 'tools.php?page=alg_wc_wl_import' ) ),
					'id'       => 'alg_wc_wl_create_wl_import_page',
					'default'  => 'no',
				),
				array(
					'type'     => 'text',
					'title'    => __( 'CSV file', 'wish-list-for-woocommerce' ),
					'desc'     => sprintf( __( 'The CSV file can be uploaded to the <a href="%s">media page</a> and its URL can be pasted here.', 'wish-list-for-woocommerce' ), admin_url( 'upload.php' ) ),
					'id'       => 'alg_wc_wl_csv_import_file',
					'default'  => '',
				),
				array(
					'type'    => 'select',
					'title'   => __( 'CSV User column', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'User identification method.', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_csv_import_user_id_method',
					'options' => array(
						'user_email' => __( 'User email', 'wish-list-for-woocommerce' ),
						'user_login' => __( 'User login', 'wish-list-for-woocommerce' ),
					),
					'class'   => 'chosen_select',
					'default' => 'user_email',
				),
				array(
					'type'     => 'number',
					'desc'     => __( 'User column position.', 'wish-list-for-woocommerce' ),
					'id'       => 'alg_wc_wl_csv_import_user_col_pos',
					'default'  => 0,
				),
				array(
					'type'    => 'select',
					'title'   => __( 'CSV Product column', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Product identification method.', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_csv_import_product_id_method',
					'options' => array(
						'product_sku' => __( 'SKU', 'wish-list-for-woocommerce' ),
					),
					'class'   => 'chosen_select',
					'default' => 'product_sku',
				),
				array(
					'type'     => 'number',
					'desc'     => __( 'Product column position.', 'wish-list-for-woocommerce' ),
					'id'       => 'alg_wc_wl_csv_import_product_col_pos',
					'default'  => 1,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_wl_import_opts',
				),
			);
			return parent::get_settings( array_merge( $settings, $report_opts, $product_exporting_opts, $import_opts ) );
		}


	}

endif;