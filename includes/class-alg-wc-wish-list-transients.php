<?php

/**
 * Wish List for WooCommerce - Transients
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Transients' ) ) {

	class Alg_WC_Wish_List_Transients {

		/**
		 * Transient responsible for saving user id of unlogged users
		 *
		 * This id is the session id concatenated with the AUTH_KEY constant mdfived
		 *
		 * @since   1.0.0
		 */
		const UNLOGGED_USER_ID = 'alg_wc_wl_uuid_';

	}

}