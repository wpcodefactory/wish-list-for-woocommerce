<?php
/**
 * Wish List for WooCommerce - Link Widget
 *
 * @version 1.1.4
 * @since   1.1.4
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Widget_Link' ) ) {

	class Alg_WC_Wish_List_Widget_Link extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @version 1.1.4
		 * @since   1.1.4
		 */
		function __construct() {
			parent::__construct(
				'alg_wc_wl_widget_link', // Base ID
				esc_html__( 'Wish list link', 'wish-list-for-woocommerce' ), // Name
				array( 'description' => esc_html__( 'A link pointing to the wish list', 'wish-list-for-woocommerce' ), ) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see     WP_Widget::widget()
		 * @version 1.1.4
		 * @since   1.1.4
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
			if ( empty( $wish_list_page_id ) ) {
				return;
			}

			$wish_list_link = get_permalink( Alg_WC_Wish_List_Page::get_wish_list_page_id() );
			$show_icon      = filter_var( $instance['show_icon'], FILTER_VALIDATE_BOOLEAN );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			$icon = $show_icon ? '<i class="fas fa-heart" aria-hidden="true"></i>' : '';
			$label = ! empty( $instance['link_label'] ) ? '<span style="margin-right:10px;" class="alg-wc-wl-widget-label">'.esc_html($instance['link_label']).'</span>' : '';

			if ( ! empty( $instance['link_label'] ) || $show_icon) {
				echo
                "
				    <a  class='alg-wc-wl-widget-link' href='" . esc_url( $wish_list_link ) . "'>
				        {$label}{$icon}
                    </a>
                ";
            }

			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @see     WP_Widget::form()
		 * @version 1.1.4
		 * @since   1.1.4
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title      = ! empty( $instance['title'] ) ? $instance['title'] : '' ;
			$link_label = ! empty( $instance['link_label'] ) ? $instance['link_label'] : '';
			$show_icon  = ! empty( $instance['show_icon'] ) ? filter_var( $instance['show_icon'], FILTER_VALIDATE_BOOLEAN ) : false;
			?>
            <p>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'wish-list-for-woocommerce' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       placeholder="<?php echo esc_html( __( 'Wish list', 'wish-list-for-woocommerce' ) ) ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'link_label' ) ); ?>"><?php esc_attr_e( 'Link label:', 'wish-list-for-woocommerce' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_label' ) ); ?>"
                       placeholder="<?php echo esc_html( __( 'View wish list', 'wish-list-for-woocommerce' ) ) ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'link_label' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $link_label ); ?>">
            </p>

            <p>

                <input id="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_icon' ) ); ?>" type="checkbox"
                       <?php echo $show_icon ? 'checked' : ''; ?>>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>"><?php esc_attr_e( 'Show icon', 'wish-list-for-woocommerce' ); ?></label>
            </p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see     WP_Widget::update()
		 * @version 1.1.4
		 * @since   1.1.4
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance               = array();
			$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['link_label'] = ( ! empty( $new_instance['link_label'] ) ) ? strip_tags( $new_instance['link_label'] ) : '';
			$instance['show_icon']  = ( ! empty( $new_instance['show_icon'] ) ) ? filter_var( sanitize_text_field( $new_instance['show_icon'] ), FILTER_VALIDATE_BOOLEAN ) : false;

			return $instance;
		}

	}
}