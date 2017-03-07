<?php
/**
 * Wish List for WooCommerce - General Section Settings
 *
 * @version 1.1.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_General' ) ) :

class Alg_WC_Wish_List_Settings_General extends Alg_WC_Wish_List_Settings_Section {

	const OPTION_FONT_AWESOME   = 'alg_wc_wl_fontawesome';
	const OPTION_ENABLED        = 'alg_wc_wl_enabled';
	const OPTION_METABOX_PRO    = 'alg_wc_wl_cmb_pro';
	//const UNLOGGED_USERS_METHOD = 'alg_wc_wl_unlogged_users_method';

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function __construct( $handle_autoload = true ) {
		$this->id   = '';
		$this->desc = __( 'General', 'wish-list-for-woocommerce' );
		parent::__construct( $handle_autoload );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.5
	 * @since   1.0.0
	 */
	function get_settings( $settings = null ) {
		$new_settings = array(
			array(
				'title'       => __( 'General options', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_options',
			),
			array(
				'title'       => __( 'General options', 'wish-list-for-woocommerce' ),
				'type'        => 'meta_box',
				'show_in_pro' => false,
				'title'       => 'Pro version',
				'description' => $this->get_meta_box_pro_description(),
				'id'          => self::OPTION_METABOX_PRO,
			),
			array(
				'title'       => __( 'Wish List for WooCommerce.', 'wish-list-for-woocommerce' ),
				'desc'        => '<strong>' . __( 'Enable', 'wish-list-for-woocommerce' ) . '</strong>',
				'desc_tip'    => __( 'Enable the plugin "Wish List for WooCommerce".', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_ENABLED,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),
			array(
				'title'       => __( 'Load FontAwesome', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'Load most recent version of Font Awesome', 'wish-list-for-woocommerce' ),
				'desc_tip'    => __( 'Only mark this if you are not loading Font Awesome nowhere else. Font Awesome is responsible for creating icons', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_FONT_AWESOME,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_options',
			),

			/*// Advanced
			array(
				'title'       => __( 'Advanced', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_advanced_opt',
			),
			array(
				'title'       => __( 'Unlogged users method', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'How unlogged users will be managed by the plugin', 'wish-list-for-woocommerce' ),
				'desc_tip'    => __( 'Default is "Cookies". Change it to Php Sessions if you are having issues with your wish list', 'wish-list-for-woocommerce' ),
				'id'          => self::UNLOGGED_USERS_METHOD,
				'default'     => 'cookies',
				'class'       => 'chosen_select',
				'type'        => 'select',
				'options'     => array(
					'cookies'    => __( 'Cookies', 'wish-list-for-woocommerce' ),
					'session'    => __( 'PHP Session', 'wish-list-for-woocommerce' ),
				),
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_advanced_opt',
			),*/
		);

		return parent::get_settings( array_merge( $settings, $new_settings ) );
	}

	/**
	 * Gets meta box description.
	 *
	 * The description is about the pro version of the plugin
	 *
	 * @version 1.1.2
	 * @since   1.1.2
	 */
	function get_meta_box_pro_description() {
		$presentation   = __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'wish-list-for-woocommerce' );
		$url            = 'http://coder.fm/item/wish-list-woocommerce/';
		$links          = sprintf( wp_kses( __( 'Check it out <a target="_blank" href="%s">here</a> or on this link: <a target="_blank" href="%s">%s</a>', 'wish-list-for-woocommerce' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ), esc_url( $url ), esc_url( $url ) );
		$features_title = __( 'Take a look on some of its features:', 'wish-list-for-woocommerce' );
		$features       = array(
			__( 'Choose custom icons from FontAwesome for all your buttons and notifications', 'wish-list-for-woocommerce' ),
			__( 'Customize the default button in all ways (background and hover color, font weight, size, margin and more)', 'wish-list-for-woocommerce' ),
			__( 'Choose precisely where thumbnail button will be displayed inside product image and also style it the way you want', 'wish-list-for-woocommerce' ),
			__( 'Style your notifications', 'wish-list-for-woocommerce' ),
			__( 'Choose your social icon colors', 'wish-list-for-woocommerce' ),
			__( 'Customize all messages displayed to users easily', 'wish-list-for-woocommerce' ),
			__( 'Use tooltips to make this plugin even easier to users', 'wish-list-for-woocommerce' ),
		);
		$features_str   =
			"<ul style='list-style:square inside'>" .
			"<li>" . implode( "</li><li>", $features ) . "</li>" .
			"</ul>";

		$call_to_action = sprintf( __( '<a target="_blank" style="margin:9px 0 15px 0;" class="button-primary" href="%s">Upgrade to Pro version now</a> ', 'wish-list-for-woocommerce' ), esc_url( $url ) );

		return
			"			
			<p>{$presentation}<br/>
			{$links}</p>
			<strong>{$features_title}</strong>
			{$features_str}
			{$call_to_action}
		";
	}

}

endif;