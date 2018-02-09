<?php
if( ! function_exists( 'newslite_excerpt_length' ) ) :

    /**
     * Excerpt length
     *
     * @since  newslite 1.0.0
     *
     * @param null
     * @return int
     */
    function newslite_excerpt_length( $length ){
        global $newslite_customizer_all_values;
        $excerpt_length = $newslite_customizer_all_values['newslite-excerpt-length'];
        if ( empty( $excerpt_length) ) {
            $excerpt_length = $length;
        }
        return absint( $excerpt_length );

    }

endif;
add_filter( 'excerpt_length', 'newslite_excerpt_length', 999 );


if ( ! function_exists( 'newslite_implement_read_more' ) ) :

    /**
     * Implement read more in excerpt.
     *
     * @since 1.0.0
     *
     * @param string $more The string shown within the more link.
     * @return string The excerpt.
     */
    function newslite_implement_read_more( $more ) {

        $flag_apply_excerpt_read_more = apply_filters( 'newslite_filter_excerpt_read_more', true );
        if ( true !== $flag_apply_excerpt_read_more ) {
            return $more;
        }

        $output = $more;
        $read_more_text = __('continue reading','newslite');
        if ( ! empty( $read_more_text ) ) {
            $output = ' <div class="read-more-text"><a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html( $read_more_text ) . '</a></div>';
            $output = apply_filters( 'newslite_filter_read_more_link' , $output );
        }
        return $output;

    }

endif;

add_action( 'excerpt_more', 'newslite_implement_read_more' );