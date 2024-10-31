<?php

class PTDL_Widget extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'ptdl_widget',
            'description' => __( 'Posts To Do List', 'posts-to-do-list' ),
        );
        parent::__construct( 'ptdl_widget', __( 'Posts To Do List', 'posts-to-do-list' ), $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args Widget arguments
     * @param array $instance Saved values from db
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) )
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];

        if( ! is_user_logged_in() AND ! posts_to_do_list_core::$posts_to_do_list_options['show_widget_non_logged_in'] )
            _e( 'Only available to logged-in users.', 'posts-to-do-list' );
        else
            posts_to_do_list_core::posts_to_do_list_metabox_post();

        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Posts To Do List', 'posts-to-do-list' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'posts-to-do-list' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array Updated values
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    }
}