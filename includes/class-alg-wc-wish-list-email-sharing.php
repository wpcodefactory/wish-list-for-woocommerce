<?php
/**
 * Wishlist for WooCommerce - Email Sharing
 *
 * @version 1.7.0
 * @since   1.2.2
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Email_Sharing' ) ) {

	class Alg_WC_Wish_List_Email_Sharing {

		private $send_email_response;

		function __construct() {

			// Locates email params sent to template
			add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'locate_email_params' ), 10, 3 );

			// Manages query vars
			add_filter( 'query_vars', array( $this, 'handle_query_vars' ) );

			// Takes actions based on the requested url
			add_action( 'init', array( $this, 'route' ), 20 );
		}

		/**
		 * Takes actions based on the requested url
		 *
		 * @version 1.2.2
		 * @since   1.2.2
		 */
		public function route() {
			$args   = $_POST;
			$args   = wp_parse_args( $args, array(
				Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL => '',
			) );
			$action = filter_var( $args[ Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL ], FILTER_VALIDATE_BOOLEAN );
			if ( $action == true ) {
				$this->send_email_response = $this->send_wish_list_by_email( $args );
				add_action( 'wp_enqueue_scripts', array( $this, 'show_notification' ) );
			}
		}

		/**
		 * Notifies the user about the email
		 *
		 * @version 1.6.8
		 * @since   1.2.2
		 */
		public function show_notification() {
			$send_email_response = $this->send_email_response;
			if ( is_wp_error( $send_email_response ) ) {
				$response = array(
					'data' => array(
						'message' => '',
						'action'  => 'error',
					),
				);

				/* @var $send_email_response WP_Error */
				$response['data']['message'] .= '<ul style="padding:0;margin:0;list-style: inside;line-height:25px;">';
				foreach ( $send_email_response->get_error_messages() as $key => $error ) {
					$response['data']['message'] .= "<li>{$error}</li>";
				}
				$response['data']['message'] .= '</ul>';

			} else {
				$response = array(
					'data' => array(
						'message' => '',
						'icon'    => 'fas fa-envelope',
					),
				);

				$response['data']['message'] .= __( 'E-mail was sent successfully.', 'wish-list-for-woocommerce' );
			}

			$js = "
			jQuery(function ($) {
				var data = " . wp_json_encode( $response ) . ";
				alg_wc_wish_list.show_notification(data);
			});
			";
			wp_add_inline_script( 'alg-wc-wish-list', $js );
		}

		/**
		 * Validates comma separated emails
		 *
		 * @version 1.2.2
		 * @since   1.2.2
		 */
		private function validate_emails( $emails_by_comma ) {
			$emails_arr     = explode( ',', $emails_by_comma );
			$is_email_valid = strlen( $emails_by_comma ) > 0 ? true : false;
			foreach ( $emails_arr as $email ) {
				$email          = trim( $email );
				$is_email_valid = filter_var( $email, FILTER_VALIDATE_EMAIL );
				if ( ! $is_email_valid ) {
					break;
				}
			}

			return $is_email_valid;
		}

		/**
		 * Sends the wishlist by email
		 *
		 * @version 1.7.0
		 * @since   1.2.2
		 */
		public function send_wish_list_by_email( $args = array() ) {
			$errors = new WP_Error();
			$args   = wp_parse_args( $args, array(
				'alg_wc_wl_emails'        => '',
				'alg_wc_wl_email_admin'   => false,
				'alg_wc_wl_email_message' => '',
				'alg_wc_wl_email_send_to' => 'friends', // friends | admin
				'alg_wc_wl_from_email'    => '',
				'alg_wc_wl_from_name'     => '',
				'alg_wc_wl_subject'       => apply_filters( 'alg_wc_wl_default_email_subject', __( 'Email Sharing', 'wish-list-for-woocommerce' ) ),
			) );

			$emails         = sanitize_text_field( $args['alg_wc_wl_emails'] );
			$message        = sanitize_text_field( $args['alg_wc_wl_email_message'] );
			$send_to        = sanitize_text_field( $args['alg_wc_wl_email_send_to']);
			$send_to_admin  = $send_to == 'admin' ? true : false;
			$is_email_valid = $this->validate_emails( $emails );
			$to             = $emails;
			$from_email     = filter_var( $args['alg_wc_wl_from_email'], FILTER_SANITIZE_EMAIL );
			$from_name      = sanitize_text_field( $args['alg_wc_wl_from_name'] );
			$subject        = sanitize_text_field( $args['alg_wc_wl_subject'] );
			$headers        = array( 'Content-Type: text/html; charset=UTF-8' );
			$alg_wc_wl      = alg_wc_wish_list();
			remove_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $alg_wc_wl, 'handle_social' ) );
			remove_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $alg_wc_wl, 'handle_social' ) );
			$need_to_send_email = false;


			if ( empty( $from_name ) ) {
				$errors->add( 'missing_from_name', __( '<b>Name is missing</b>. Please, fill with your name', 'wish-list-for-woocommerce' ) );
			}

			if ( empty( $from_email ) ) {
				$errors->add( 'missing_from_email', __( '<b>Email is missing</b>. Please, fill with your email', 'wish-list-for-woocommerce' ) );
			}

			if ( !filter_var( $args['alg_wc_wl_from_email'], FILTER_VALIDATE_EMAIL ) ) {
				$errors->add( 'invalid_email', __( '<b>Invalid e-mail</b>. Please, provide a valid email. Separate it with comma for multiple values.', 'wish-list-for-woocommerce' ) );
			}

			if ( count( $errors->errors ) == 0 ) {
				if ( $send_to_admin ) {
					$admin_emails       = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_EMAIL_ADMIN_EMAILS ) );
					$admin_emails_valid = $this->validate_emails( $admin_emails );
					if ( ! empty( $from_name ) && ! empty( $from_email ) ) {
						$headers[] = 'From: ' . $from_name . ' <' . $from_email . '>';
					}
					if ( $admin_emails_valid ) {
						$to   = $admin_emails;
						$body = alg_wc_wl_locate_template( 'email-template.php',
							array(
								'message'    => $message,
								'from_name'  => $from_name,
								'from_email' => $from_email
							) );
						$complete_message = Alg_WC_Wish_List_Email::wrap_in_wc_email_template( $body, $subject );
						$mailer = WC()->mailer();
						$mailer->send( $to, $subject, $complete_message, $headers,'');
					}
				} else {
					if ( ! $is_email_valid && strlen( $emails ) == 0 ) {
						$errors->add( 'invalid_email', __( '<b>Invalid e-mail</b>. Please, provide a valid email. Separate it with comma for multiple values.', 'wish-list-for-woocommerce' ) );
					} else {
						if ( strlen( $emails ) > 0 ) {
							if ( ! empty( $from_name ) && ! empty( $from_email ) ) {
								$headers[] = 'From: ' . $from_name . ' <' . $from_email . '>';
							}
							$body           = alg_wc_wl_locate_template( 'email-template.php',
								array(
									'message'    => $message,
									'from_name'  => $from_name,
									'from_email' => $from_email
								) );
							$complete_message = Alg_WC_Wish_List_Email::wrap_in_wc_email_template( $body, $subject );
							$mailer = WC()->mailer();
							$email_response = $mailer->send( $to, $subject, $complete_message, $headers, '' );
							if ( ! $email_response ) {
								$errors->add( 'error_sending_email', __( 'Sorry, Some error occurred. Please, try again later.', 'wish-list-for-woocommerce' ) );
							}
						}
					}
				}
			}

			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $alg_wc_wl, 'handle_social' ) );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $alg_wc_wl, 'handle_social' ) );

			if ( ! empty( $errors->errors ) ) {
				return $errors;
			} else {
				return true;
			}
		}

		/**
		 * Manages query vars
		 *
		 * @version 1.2.2
		 * @since   1.2.2
		 */
		public function handle_query_vars( $vars ) {
			$vars[] = Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL;

			return $vars;
		}

		/**
		 * Locates email params sent to template
		 *
		 * @version 1.7.0
		 * @since   1.2.2
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 */
		public function locate_email_params( $params, $final_file, $path ) {
			if ( $path == 'share.php' || $path == 'email-template.php') {
				// Get current url with user id
				$url = Alg_WC_Wish_List::get_url();
				$params['email']['url'] = $url;
			}
			if ( $path == 'share.php' ) {
				$send_email_response = $this->send_email_response;
				$admin_emails = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_EMAIL_ADMIN_EMAILS ) );

				$params ['email'] = array(
					'active'         => true,
					'subject'        => 'yes' === get_option( Alg_WC_Wish_List_Settings_Social::OPTION_EMAIL_SUBJECT, 'no' ),
					'default_subject'=> apply_filters( 'alg_wc_wl_default_email_subject', __( 'Email Sharing', 'wish-list-for-woocommerce' ) ),
					'need_admin_opt' => strlen( $admin_emails ) > 0,
					'url'            => add_query_arg( array(
						Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL => 1,
					), wp_get_shortlink() ),
				);

				$args = $_POST;
				$args = wp_parse_args( $args, array(
					'alg_wc_wl_emails'        => '',
					'alg_wc_wl_email_admin'   => false,
					'alg_wc_wl_email_message' => sprintf( __( 'Hello, check my wishlist', 'wish-list-for-woocommerce' ), $url ),
				) );

				$args = wp_parse_args( $args, array(
					Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL => '',
				) );

				if ( is_user_logged_in() ) {
					$current_user                 = wp_get_current_user();
					$params['email']['fromname']  = $current_user->display_name;
					$params['email']['fromemail'] = $current_user->user_email;
				}

				$action = filter_var( $args[ Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL ], FILTER_VALIDATE_BOOLEAN );
				if ( $action == true ) {
					$params['email']['emails']        = sanitize_text_field( $args['alg_wc_wl_emails'] );
					$params['email']['send_to_admin'] = filter_var( $args['alg_wc_wl_email_admin'], FILTER_VALIDATE_BOOLEAN );
				} else {
					$params['email']['send_to_admin'] = true;
				}

				$params['email']['message'] = sanitize_text_field( $args['alg_wc_wl_email_message'] );
				$params['email']['share_email_friends_txt'] = __( 'Friend(s)', 'wish-list-for-woocommerce' );
				$params['email']['share_email_admin_txt'] = __( 'Admin', 'wish-list-for-woocommerce' );

				if ( $send_email_response ) {					
					$params['email']['emails']  = '';
					$params['email']['admin']   = true;
					$params['email']['message'] = '';
					$params['email']['fromname'] = '';
					$params['email']['fromemail'] = '';
					$params['email']['default_subject'] = '';
				}

			}

			return $params;
		}
	}
}