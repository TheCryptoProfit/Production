<?php
/**
* Returns word count of the sentences.
*
* @since newslite 1.0.0
*/
if ( ! function_exists( 'newslite_words_count' ) ) :
	function newslite_words_count( $length = 25, $newslite_content = null ) {
		$length = absint( $length );
		$more = __( '&hellip;','newslite' );
		$source_content = preg_replace( '`\[[^\]]*\]`', '', $newslite_content );
		$trimmed_content = wp_trim_words( $source_content, $length, $more );
		return $trimmed_content;
	}
endif;