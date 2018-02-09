<?php 
//Overwrite coworker's get_resized_image function
if( !function_exists( 'get_resized_image' ) ){
	function get_resized_image( $w, $h, $single = true ) {

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full');
		$url = apply_filters('swiftsecurity_reverse_replace',$image[0]);
		return semi_resize( $url, $w, $h, true, $single );

	}
}

?>