/**
 * Manages social options
 */

var alg_wc_wl_social = {

	/**
	 * Initializes.
	 */
	init: function () {
		this.email_options_toggler( '.alg-wc-wl-social-li .email', '.alg-wc-wl-email-options' );
		this.handle_send_to_option();
		this.handle_clipboard_button();
	},

	copyToClipboard: function ( text ) {
		if ( window.clipboardData && window.clipboardData.setData ) {
			// IE specific code path to prevent textarea being shown while dialog is visible.
			return clipboardData.setData( "Text", text );

		} else if ( document.queryCommandSupported && document.queryCommandSupported( "copy" ) ) {
			var textarea = document.createElement( "textarea" );
			textarea.textContent = text;
			textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
			document.body.appendChild( textarea );
			textarea.select();
			try {
				return document.execCommand( "copy" );  // Security exception may be thrown by some browsers.
			} catch ( ex ) {
				console.warn( "Copy to clipboard failed.", ex );
				return false;
			} finally {
				document.body.removeChild( textarea );
			}
		}
	},

	handle_clipboard_button: function () {
		jQuery( "body" ).on( "click", '.alg-wc-wl-social-li .copy', function ( e ) {
			e.preventDefault();
			var link = this.getAttribute( 'href' );
			alg_wc_wl_social.copyToClipboard( link );
			jQuery( "body" ).trigger( {
				type: "alg_wc_wl_copied_to_clipboard",
				link: link
			} );
		} )
	},

	email_options_toggler: function ( selector, options_elem_selector ) {
		var is_active = -1;
		jQuery( 'body' ).on( 'click', selector, function ( e ) {
			var trigger = jQuery( this );
			e.preventDefault();
			is_active *= -1;
			if ( is_active == 1 ) {
				trigger.addClass( 'active' );
			} else {
				trigger.removeClass( 'active' );
			}

			jQuery( options_elem_selector ).slideToggle();
		} )
	},

	handle_send_to_option: function () {
		jQuery( 'body' ).on( 'change', 'input[name="alg_wc_wl_email_send_to"]', function ( e ) {
			var selected_val = jQuery( this ).val();
			if ( selected_val == 'friends' ) {
				jQuery( '.alg-wc-wl-emails-input' ).show();
			} else if ( selected_val == 'admin' ) {
				jQuery( '.alg-wc-wl-emails-input' ).hide();
			}
		} );
	}
}

const social = {
	init: function () {
		jQuery( function ( $ ) {
			alg_wc_wl_social.init();
		} );
	}
}
module.exports = social;