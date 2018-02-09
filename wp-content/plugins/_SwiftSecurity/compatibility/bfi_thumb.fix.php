<?php

if ( ! function_exists( 'bfi_thumb' ) ) {
	function bfi_thumb( $url, $params = array(), $single = true ) {
		$url = apply_filters('swiftsecurity_reverse_replace',$url);
	    $class = BFI_Class_Factory::getNewestVersion( 'BFI_Thumb' );
	    return call_user_func( array( $class, 'thumb' ), $url, $params, $single );
	}
}

?>