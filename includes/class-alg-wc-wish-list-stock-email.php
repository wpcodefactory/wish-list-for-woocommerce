<?php
/**
 * Wish List for WooCommerce Pro - Email
 *
 * @version 2.2.1
 * @since   1.3.2
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Stock_Email' ) ) {

	class Alg_WC_Wish_List_Stock_Email extends WC_Email {

		/**
		 * Constructor
		 *
		 * @version 1.3.2
		 * @since   1.3.2
		 * @return string
		 */
		public function __construct() {

			$this->customer_email = true;

			// set ID, this simply needs to be a unique name
			$this->id = 'alg_wc_wl_stock';

			// this is the title in WooCommerce Email settings
			$this->title = 'Wish List - Product in stock';

			// this is the description in WooCommerce email settings
			$this->description = 'Sends emails to customers when items they have added to wish list change status from "out of stock" to "in stock".';

			// these are the default heading and subject lines that can be overridden using the settings
			$this->heading = 'Item in stock';
			$this->subject = 'Item in stock';

			// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
			$this->template_html = 'emails/alg_wcwl-item_has_stock.php';
			//$this->template_plain = 'emails/plain/admin-new-order.php';

			$this->email_type = 'html';

			parent::__construct();
		}

		/**
		 * Sends the email to customers who are subscribed to out of stock products.
		 *
		 * When products are in stock they will receive the email.
		 *
		 * @version 1.6.4
		 * @since   1.3.2
		 *
		 * @param $product_id
		 * @param $email
		 * @param $user_id
		 * @param $user_registered
		 */
		public function trigger_mail( $product_id, $email, $user_id, $user_registered ) {
			$product = wc_get_product( $product_id );
			if ( ! is_a( $product, 'WC_Product' ) ) {
				return;
			}
			$product_permalink = get_permalink( $product_id );
			$this->message     = $this->get_option( 'message' );
			$this->message     = str_replace( '{product_name}', '<a href="' . $product_permalink . '">' . $product->get_title() . '</a>', $this->message );
			$this->message     = str_replace( '{site_title}', '<a href="' . $product_permalink . '">' . $this->get_blogname() . '</a>', $this->message );
			$this->email_type  = 'html';

			if ( $user_registered ) {
				$user          = get_user_by( 'id', $user_id );
				$this->message = str_replace( '{username}', '<strong>' . $user->display_name . '</strong>', $this->message );
			} else {
				$this->message = str_replace( '{username}', '', $this->message );
			}

			$this->message = apply_filters( 'the_content', $this->message );
			$result        = $this->send( $email, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		/**
		 * get_content_html function.
		 *
		 * @version 1.3.2
		 * @since   1.3.2
		 * @return string
		 */
		public function get_content_html() {
			ob_start(); 
			wc_get_template( $this->template_html, array(
				'message'       => $this->message,
				'plain_text'    => false,
				'email_heading' => $this->get_heading(),
				'email'         => $this,
				'plain_text'    => false,
			) );
			return ob_get_clean();
		}

		/**
		 * Initialize Settings Form Fields
		 *
		 * @version 2.2.1
		 * @since   1.3.2
		 */
		public function init_form_fields() {
			parent::init_form_fields();

			$new_options = array(
				'message' => array(
					'title'       => 'Message',
					'desc_tip'    => __( 'Available placeholders: ', 'wish-list-for-woocommerce' ) . '<br />' . '{username}' . '<br />' . '{product_name}'.'<br />' . '{site_title}',
					'type'        => 'textarea',
					'description' => __( 'Email message displayed to customers', 'wish-list-for-woocommerce' ),
					'placeholder' => '',
					'default'     => __( 'Hello {username}, 

{product_name} is in stock. Please, check it out on {site_title}', 'wish-list-for-woocommerce' )
				)
			);

			unset( $this->form_fields['email_type'] );
			$this->form_fields['subject']['default'] = $this->form_fields['subject']['placeholder'];
			$this->form_fields['heading']['default'] = $this->form_fields['heading']['placeholder'];
			$this->form_fields                       = array_merge( $this->form_fields, $new_options );
		}

	}


}