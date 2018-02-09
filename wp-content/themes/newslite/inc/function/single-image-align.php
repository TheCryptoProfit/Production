<?php 
/*image alignment single post*/

if( ! function_exists( 'newslite_single_post_image_align' ) ) :
    /**
     * newslite default layout function
     *
     * @since  newslite 1.0.0
     *
     * @param int
     * @return string
     */
    function newslite_single_post_image_align( $post_id = null ){
        global $newslite_customizer_all_values,$post;
        if( null == $post_id && isset ( $post->ID ) ){
            $post_id = $post->ID;
        }
        $newslite_single_post_image_align = $newslite_customizer_all_values['newslite-single-post-image-align'];
        $newslite_single_post_image_align_meta = get_post_meta( $post_id, 'newslite-single-post-image-align', true );

        if( false != $newslite_single_post_image_align_meta ) {
            $newslite_single_post_image_align = $newslite_single_post_image_align_meta;
        }
        return $newslite_single_post_image_align;
    }
endif;