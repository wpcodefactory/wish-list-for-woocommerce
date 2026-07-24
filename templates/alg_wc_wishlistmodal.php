<?php
/**
 * Wish list modal template.
 *
 * @version 3.4.5
 * @since   3.2.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="algwcwishlistmodal-container js-algwcwishlistmodal-container">
    <div class="algwcwishlistmodal js-algwcwishlistmodal" data-modal="a">
        <button type="button" class="iziToast-close page__btn--cancel js-algwcwishlistmodal-btn-close">
            x
        </button>

        <div class="select-wishlist">
            <h2><?php esc_html_e( 'Select Wishlist', 'wish-list-for-woocommerce' ); ?></h2>
            <ul class="algwc-wishlist-collections-wrapper">

            </ul>

            <div class="button-split">
                <button class="page__btn page__btn--create js-algwcwishlistmodal-btn-create"><?php esc_html_e( 'Create Wishlist', 'wish-list-for-woocommerce' ); ?></button>
                <button class="page__btn page__btn--save js-algwcwishlistmodal-btn-save-wishlist"><?php esc_html_e( 'Done', 'wish-list-for-woocommerce' ); ?></button>
                <div class="float-clear"></div>
                <input type="hidden" name="wishlist_form_product_id" id="wishlist_form_product_id" value="0">
            </div>
        </div>

        <div class="create-wishlist-form is-hidden">
            <h2><?php esc_html_e( 'Create Wishlist', 'wish-list-for-woocommerce' ); ?></h2>
            <div class="form-field-wrap">
                <label for="wishlist_name"><?php esc_html_e( 'Wishlist Name', 'wish-list-for-woocommerce' ); ?></label>
                <input type="text" name="wishlist_name" id="wishlist_name" class="form-field">
            </div>
            <div class="button-split">
                <button class="page__btn page__btn--create js-algwcwishlistmodal-btn-save"><?php esc_html_e( 'Save Wishlist', 'wish-list-for-woocommerce' ); ?></button>
                <button class="page__btn page__btn--save js-algwcwishlistmodal-btn-cancel"><?php esc_html_e( 'Cancel', 'wish-list-for-woocommerce' ); ?></button>
                <div class="float-clear"></div>
            </div>
        </div>

        <?php do_action( 'alg_wc_wl_modal_action' ); ?>

    </div>
</div>
<div class="algwcwishlistmodal-overlay js-algwcwishlistmodal-overlay"></div>