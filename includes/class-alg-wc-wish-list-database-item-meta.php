<?php

/**
 * Wish List for WooCommerce - Wish list item meta table
 *
 * @version 1.1.2
 * @since   1.1.2
 * @author  Algoritmika Ltd.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Database_Item_Meta' ) ) {

	class Alg_WC_Wish_List_Database_Item_Meta {

		/**
		 * Name of the table
		 *
		 * @since   1.1.2
		 */
		const TABLE_NAME = 'alg_wc_wl_item_meta';

		/**
		 * Creates a wish list item meta table on database.
		 *
		 * The umeta_id is from usermeta
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 */
		public static function create_wish_list_item_meta_table() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::TABLE_NAME;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$table_name} (
			  meta_id INT(11) NOT NULL AUTO_INCREMENT,			  
			  umeta_id INT(11) NOT NULL,
			  meta_key VARCHAR(255) DEFAULT '' NOT NULL,
			  meta_value LONGTEXT NULL,
			  datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  PRIMARY KEY  (meta_id),
			  INDEX umeta_id (umeta_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/**
		 * Deletes the wish list item meta table from database.
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 */
		public static function delete_wish_list_item_meta_table() {
			global $wpdb;
			$table_name = $wpdb->prefix . self::TABLE_NAME;
			$sql        = "DROP TABLE IF EXISTS $table_name";
			$wpdb->query( $sql );
		}
	}
}