<?php 
$GLOBALS['swiftsecurity_die_404'] = true;
if ( ! function_exists( 'yit_image' ) ) {

    /**
     * YIT Image function, used to print the html of the image
     *
     * @param $args array
     * @param $echo bool
     *
     * @return \YIT_Image
     * @since  1.0.0
     * @author Antonino Scarfi <antonino.scarfi@yithemes.com>
     */
    function yit_image( $args = array(), $echo = true ) {
    	$args['src'] = apply_filters('swiftsecurity_reverse_replace', $args['src']);
        return YIT_Registry::get_instance()->image->image( $args, $echo );
    }
}
?>