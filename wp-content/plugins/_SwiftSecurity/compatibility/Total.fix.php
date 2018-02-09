<?php 

// Helper function for resizing images using the WPEX_Image_Resize class
if ( ! function_exists( 'wpex_image_resize' ) ) {
	function wpex_image_resize( $args ) {
		$class = new WPEX_Image_Resize;
		$args['image'] = apply_filters('swiftsecurity_reverse_replace',$args['image']);
		return $class->process( $args );
	}
}

?>