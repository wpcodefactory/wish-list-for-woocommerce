<?php

if ( ! class_exists( 'Alg_WC_Wish_List' ) ) {

	/**
	 * Alg_WC_Wish_List Class
	 *
	 * @class   Alg_WC_Wish_List
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	class Alg_WC_Wish_List {

		/**
		 * Save wishlist from unregistered user to database when this user registers
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   type $user
		 * @return  type
		 */
		public static function save_wish_list_from_unregistered_user( $user_id ) {
			$wishlisted_items = self::get_wish_list( null );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				foreach ( $wishlisted_items as $key => $item_id ) {
					Alg_WC_Wish_List_Item::add_item_to_wish_list( $item_id, $user_id );
				}
			}

			return $user_id;
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

		/**
		 * Get user wishlist
		 * @param  type $user_id
		 * @return type
		 */
		public static function get_wish_list( $user_id = null ) {
			if ( $user_id ) {
				$wishlisted_items = get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, false );
			} else {
				if ( ! isset( $_SESSION[Alg_WC_Wish_List_Session_Vars::WISH_LIST] ) ) {
					$wishlisted_items = null;
				} else {
					$wishlisted_items = $_SESSION[Alg_WC_Wish_List_Session_Vars::WISH_LIST];
				}
			}
			return $wishlisted_items;
		}



	}

}