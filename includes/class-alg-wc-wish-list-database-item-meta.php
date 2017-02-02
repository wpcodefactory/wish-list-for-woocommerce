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
			  user_id INT(11) NOT NULL,
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
		 * Adds item meta.
		 *
		 * Adds any kind of meta to database
		 *
		 * @global wpdb $wpdb WordPress database abstraction object.
		 *
		 * @param array $args {
		 *      Array of parameters.
		 *
		 *      @type int    $umeta_id User meta ID.
		 *
		 *      @type string $key Meta key name.
		 *
		 *      @type string $value Value to be added to database.
		 *
		 * }
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 *
		 * @return false|int
		 */
		public static function add_item_meta( $args = array() ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::TABLE_NAME;
			$umeta_id   = $args['umeta_id'];
			$key        = $args['key'];
			$value      = $args['value'];
			$datetime   = current_time( 'mysql' );

			return $wpdb->insert(
				$table_name,
				array(
					'umeta_id'   => $umeta_id,
					'meta_key'   => $key,
					'meta_value' => $value,
					'datetime'   => $datetime,
				),
				array(
					'%d',
					'%s',
					'%s',
					'%s',
				)
			);
		}

		/**
		 * Deletes item meta.
		 *
		 * Deletes any kind of meta from database
		 *
		 * @global wpdb $wpdb WordPress database abstraction object.
		 *
		 * @param array $args {
		 *      Array of parameters.
		 *
		 *      @type int    $umeta_id User meta ID.
		 *
		 *      @type string $key Meta key name.
		 *
		 *      @type string $value Value
		 *
		 * }
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 *
		 * @return false|int
		 */
		public static function delete_item_meta( $args = array() ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::TABLE_NAME;
			$umeta_id   = $args['umeta_id'];
			$key        = $args['key'];
			$value      = $args['value'];
			$datetime   = current_time( 'mysql' );

			return $wpdb->delete(
				$table_name,
				array(
					'umeta_id'   => $umeta_id,
					'meta_key'   => $key,
					'meta_value' => $value,
				),
				array(
					'%d',
					'%s',
					'%s',
				)
			);
		}

		/**
		 * Get item meta.
		 *
		 * Get all item metadata from user meta id
		 *
		 * @global wpdb $wpdb WordPress database abstraction object.
		 *
		 * @param array $args {
		 *      Array of parameters.
		 *
		 *      @type int    $umeta_id User meta ID.
		 *
		 *      @type string $key Meta key name.
		 *
		 *      @type string $value Value
		 *
		 * }
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 *
		 * @return array
		 */
		public static function get_item_meta( $args = array() ){
			global $wpdb;
			$args = wp_parse_args( $args, array(
				'umeta_id' => null,
				'key'      => null,
			) );
			$table_name = $wpdb->prefix . self::TABLE_NAME;
			$umeta_id   = $args['umeta_id'];
			$key        = $args['key'];

			$sql = "
			SELECT im.meta_id, um.user_id, um.umeta_id, um.meta_value AS product_id, im.meta_key, im.meta_value
			FROM $table_name im
			JOIN $wpdb->usermeta um ON um.umeta_id = im.umeta_id
			WHERE im.umeta_id = %d
			";

			$prepare_values = array($umeta_id);

			//If key is present
			if ( ! empty( $key ) ) {
				$sql .= "AND im.meta_key=%s";
				$prepare_values[] = $key;
			}

			$prepare = $wpdb->prepare($sql,$prepare_values);
			return $wpdb->get_results($prepare);
		}

		/**
		 * Deletes the wish list item meta table from database.
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 */
		public static function delete_wish_list_item_meta_table() {
			self::add_item_meta();
			global $wpdb;
			$table_name = $wpdb->prefix . self::TABLE_NAME;
			$sql        = "DROP TABLE IF EXISTS $table_name";
			$wpdb->query( $sql );
		}
	}
}