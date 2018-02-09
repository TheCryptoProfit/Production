<?php
if ( ! class_exists( 'Newslite_Author_Widget' ) ) :

    /**
     * Author Widget Class
     *
     * @since Newslite 1.0
     *
     */
    class Newslite_Author_Widget extends WP_Widget {

        function __construct() {
            parent::__construct(
                'newslite_widget_author', // Base ID
                __('Newslite: Author Widget', 'newslite'),
                array( 'description' => __( 'Author Widget', 'newslite' ) ) // Args
            );
        }


        function widget( $args, $instance ) {
            extract( $args );

            $title              = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            $image_url          = empty($instance['image_url']) ? '' : $instance['image_url'];
            $author_link           = empty($instance['author_link']) ? '' : $instance['author_link'];
            $open_in_new_window = empty($instance['open_in_new_window']) ? '' : $instance['open_in_new_window'];
            $author_name        = empty($instance['author_name']) ? '' : $instance['author_name'];
            $description        = empty($instance['description']) ? '' : $instance['description'];
            $custom_class       = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );


            if ( $custom_class ) {
                $before_widget = str_replace('class="', 'class="'. $custom_class . ' ', $before_widget);
            }

            echo $before_widget;

            // Title
            if ( $title ) echo $before_title . $title . $after_title;
            //
            ?>
            <section class="widget">
                <div class="about-widget">
                    <?php
                    if ( ! empty( $image_url ) ) {
                        ?>
                        <div class="profile-thumb">
                            <a href="<?php echo esc_url( $author_link ); ?>">
                                <img src="<?php echo esc_url( $image_url ); ?>">
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <h2 class="user-title"><a href="<?php echo esc_url( $author_link ); ?>"><span><?php echo esc_html( $author_name ); ?></span></a></h2>
                    <p>
                        <?php echo esc_html( $description ); ?>
                    </p>

                </div>
            </section><!-- widget-list -->
            <?php
            //
            echo $after_widget;

        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title']              =   sanitize_text_field( strip_tags($new_instance['title']) );
            $instance['image_url']          =   esc_url_raw( $new_instance['image_url'] );
            $instance['author_link']        =   esc_url_raw( $new_instance['author_link'] );
            $instance['author_name']        =   sanitize_text_field( $new_instance['author_name'] );
            $instance['description']        =   wp_kses_post( $new_instance['description'] );
            $instance['open_in_new_window'] = isset( $new_instance['open_in_new_window'] );
            $instance['custom_class']       =   sanitize_text_field( $new_instance['custom_class'] );

            return $instance;
        }

        function form( $instance ) {

            //Defaults
            $instance = wp_parse_args( (array) $instance, array(
                'title'              => '',
                'image_url'          => '',
                'author_link'        => '',
                'featured_image'     => '',
                'open_in_new_window' => true,
                'author_name'        => '',
                'description'        => '',
                'custom_class'       => '',
            ) );
            $title              = esc_attr( $instance['title'] );
            $image_url          = esc_url( $instance['image_url'] );
            $author_link           = esc_url( $instance['author_link'] );
            $open_in_new_window = esc_attr( $instance['open_in_new_window'] );
            $author_name           = esc_attr( $instance['author_name'] );
            $description           = esc_attr( $instance['description'] );
            $custom_class       = esc_attr( $instance['custom_class'] );

            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'newslite' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <hr />
            <div>
                <label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php _e( 'Image URL:', 'newslite' ); ?></label><br/>
                <input id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>" class="img" />
                <input type="button" class="select-img button button-primary" value="<?php _e('Upload', 'newslite' ); ?>" data-uploader_title="<?php _e( 'Select Image', 'newslite' ); ?>" data-uploader_button_text="<?php _e( 'Choose Image', 'newslite' ); ?>" />
                <?php
                $full_image_url = '';
                if (! empty( $image_url ) ){
                    $full_image_url = $image_url;
                }
                $wrap_style = '';
                if ( empty( $full_image_url ) ) {
                    $wrap_style = ' style="display:none;" ';
                }
                ?>
                <div class="author-preview-wrap" <?php echo $wrap_style; ?>>
                    <img src="<?php echo esc_url( $full_image_url ); ?>" alt="<?php _e( 'Preview', 'newslite' ); ?>" style="max-width: 100%;"  />
                </div><!-- .author-preview-wrap -->

            </div>
            <p>
                <label for="<?php echo $this->get_field_id( 'author_link' ); ?>"><?php _e( 'Author Link:', 'newslite' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'author_link' ); ?>" name="<?php echo $this->get_field_name( 'author_link' ); ?>" type="text" value="<?php echo esc_attr( $author_link ); ?>" />
            </p>
            <p><input id="<?php echo $this->get_field_id( 'open_in_new_window' ); ?>" name="<?php echo $this->get_field_name( 'open_in_new_window' ); ?>" type="checkbox" <?php checked( isset( $instance['open_in_new_window'] ) ? $instance['open_in_new_window'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'open_in_new_window' ); ?>"><?php _e( 'Open in new window', 'newslite' ); ?></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'author_name' ); ?>"><?php _e( 'Author Name:', 'newslite' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'author_name' ); ?>" name="<?php echo $this->get_field_name( 'author_name' ); ?>" type="text" value="<?php echo esc_attr( $author_name ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'newslite' ); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
            </p>
            <hr />
            <p>
                <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'newslite' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class' ); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
            </p>

            <?php
        }

    }

endif;

add_action( 'widgets_init', 'newslite_author_single_widgets' );

if ( ! function_exists( 'newslite_author_single_widgets' ) ) :

    /**
     * Load widgets
     *
     * @since Newslite 1.0
     *
     */
    function newslite_author_single_widgets() {        // Author widget
        register_widget( 'Newslite_Author_Widget' );
    }

endif;