<?php
/**
 * Wish List for WooCommerce Pro - Note Field.
 *
 * @version 2.1.4
 * @since   1.7.4
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Note_Field' ) ) {

	class Alg_WC_Wish_List_Note_Field {

		/**
         * init.
         *
		 * @version 1.7.4
		 * @since   1.7.4
         *
		 * @return void
		 */
		function init() {
			add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'override_wishlist_params' ), 11, 3 );
			add_action( 'wp_ajax_' . 'alg_wc_wl_save_note', array( $this, 'save_note_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . 'alg_wc_wl_save_note', array( $this, 'save_note_ajax' ) );
			add_filter( 'wp_footer', array( $this, 'save_note_js' ), 99 );
		}

		/**
         * save_note_js.
         *
		 * @version 1.7.4
		 * @since   1.7.4
         *
		 * @return void
		 */
		function save_note_js() {
			global $post;
			if (
				'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD, 'no' ) ||
				! (
					is_singular() &&
					is_a( $post, 'WP_Post' ) &&
					has_shortcode( $post->post_content, Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST )
				) &&
				! is_account_page()
			) {
				return;
			}
			?>
			<script>
				document.addEventListener('DOMContentLoaded', function () {
					document.body.addEventListener("focusout", function (event) {
						if (
							!event.target.classList.contains("alg-wc-wl-item-note") ||
							!event.target.closest('.alg-wc-wl-view-table')
						) {
							return;
						}
						let note = event.target.value;
						let request = new XMLHttpRequest();
						let prodId = event.target.getAttribute('data-item_id');
						let tabId = event.target.getAttribute('data-wtab_id');
						if (prodId) {
							request.open('POST', '<?php echo admin_url( 'admin-ajax.php' ); ?>', true);
							request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
							request.send('action=alg_wc_wl_save_note&note=' + note + '&security=' + '<?php echo wp_create_nonce( "alg-wc-wl-security-save-note" ); ?>' + '&prod_id=' + prodId + '&wltab_id=' + tabId);
						}
					});
				})
			</script>
			<?php
		}

		/**
         * get_field_label.
         *
		 * @version 1.7.4
		 * @since   1.7.4
         *
		 * @return false|mixed|null
		 */
		function get_field_label() {
			$label = get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD_LABEL, __( 'Note', 'wish-list-for-woocommerce' ) );
			return $label;
		}

		/**
         * get_field_output.
         *
		 * @version 3.0.8
		 * @since   1.7.4
         *
		 * @param $product
		 * @param $params
		 *
		 * @return array|string|string[]
		 */
		function get_field_output( $product, $params = null ) {
			$tab_id = '-99';
			
			$item_id            = $product->get_id();
			$product_attributes = isset( $params['product_attributes'] ) ? $params['product_attributes'] : false;
			$item_value         = isset( $product_attributes[ $product->get_id() ] ) && isset( $product_attributes[ $product->get_id() ]['note'] ) ? esc_attr( $product_attributes[ $product->get_id() ]['note'] ) : '';
			
			if ( isset( $_GET['wtab'] ) && $_GET['wtab'] > 0 ) {
				$tab_id 						= $_GET['wtab'];
				$transient 						= Alg_WC_Wish_List_Transients::WISH_LIST_METAS_MULTIPLE_STORE;
				
				if ( is_user_logged_in() ) {
					$user    					= wp_get_current_user();
					$user_id 					= $user->ID;
				} else {
					$use_id_from_unlogged_user 	= true;
					$user_id                   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
				}
				
				if ( is_int( $user_id ) && $user_id > 0 ) {
					
					// get only multiple wishlist items
					$old_user_meta_multiple =  get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS_MULTIPLE, true );
					
				} else {
					$old_user_meta_multiple 		= get_transient( "{$transient}{$user_id}" );
				}
				
				$old_user_meta 					= ( isset( $old_user_meta_multiple[$tab_id] ) ? $old_user_meta_multiple[$tab_id] : array() );
				
				$item_value         			= isset( $old_user_meta[ $product->get_id() ] ) && isset( $old_user_meta[ $product->get_id() ]['note'] ) ? esc_attr( $old_user_meta[ $product->get_id() ]['note'] ) : '';
			}
			
			$replace_arr        = array(
				'{class}'   => 'alg-wc-wl-item-note',
				'{name}'    => 'alg_wc_wl_item_note',
				'{value}'   => esc_html( $item_value ),
				'{item_id}' => esc_attr( $item_id ),
				'{wtab_id}' => esc_attr( $tab_id ),
				'{maxlength}' => esc_attr( get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD_MAX_LENGTH, 20 ) ),
			);
			$field_type          = get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD_TYPE, 'text' );
			if ( 'text' == $field_type ) {
				$field_str_dynamic = '<input class="{class}" maxlength="{maxlength}" name="{name}" id="{name}" type="text" value="{value}" data-item_id="{item_id}" data-wtab_id="{wtab_id}"/>';
			} elseif ( 'textarea' == $field_type ) {
				$field_str_dynamic = '<textarea class="{class}" maxlength="{maxlength}" name="{name}" id="{name}" type="text" data-item_id="{item_id}" data-wtab_id="{wtab_id}">{value}</textarea>';
			}
			$field = str_replace( array_keys( $replace_arr ), array_values( $replace_arr ), $field_str_dynamic );
			return $field;
		}

		/**
         * save_note_ajax.
         *
		 * @version 2.1.4
		 * @since   1.7.4
         *
		 * @return void
		 */
		function save_note_ajax() {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD, 'no' ) ) {
				return;
			}

			// Ajax
			check_ajax_referer( 'alg-wc-wl-security-save-note', 'security' );

			// Field Type
			$field_type = get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD_TYPE, 'text' );

			// Field length
			$max_length = get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD_MAX_LENGTH, 20 );

			// Sanitize
			$sanitize_function = 'sanitize_text_field';
			if ( 'textarea' == $field_type ) {
				$sanitize_function = 'sanitize_textarea_field';
			}

            // Conditions
			if (
				! isset( $_POST['note'] ) ||
				strlen( $note = $sanitize_function( $_POST['note'] ) ) > $max_length ||
				! isset( $_POST['prod_id'] ) ||
				empty( $prod_id = intval( $_POST['prod_id'] ) )
			) {
				die();
			}
			if ( is_user_logged_in() ) {
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $prod_id, 'note', $note, get_current_user_id() );
			} else {
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $prod_id, 'note', $note, null, true );
			}
		}

		/**
		 * override_wishlist_params.
		 *
		 * @version 1.7.4
		 * @since   1.7.4
         *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function override_wishlist_params( $params, $final_file, $path ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD, 'no' ) ) {
				return $params;
			}
			switch ( $path ) {
				case 'wish-list.php':
					$params = $this->add_note_input( $params, $final_file, $path );
					break;
			}
			return $params;
		}

		/**
         * add_note_input.
         *
		 * @version 1.7.4
		 * @since   1.7.4
         *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function add_note_input( $params, $final_file, $path ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_NOTE_FIELD, 'no' ) ) {
				return $params;
			}
			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
			$queried_user_id           = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$queried_user_id           = empty( $queried_user_id ) ? $user_id_from_query_string : $queried_user_id;

			// Doesn't show if queried user id is the user itself
			if ( $queried_user_id && Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() != $queried_user_id ) {
				return $params;
			}

			$params['note'] = true;
			return $params;
		}
	}
}