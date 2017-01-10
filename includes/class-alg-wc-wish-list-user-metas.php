<?php

/**
 * Wish List for WooCommerce - Wish list User Metas
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_User_Metas')) {

	class Alg_WC_Wish_List_User_Metas {

		/**
		 * Meta responsible for pointing what item is in user wishlist
		 *
		 * @since   1.0.0
		 */
		const WISH_LIST_ITEM = '_alg_wc_ws_item';

	}

}