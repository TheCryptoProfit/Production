<?php 

if ( ! function_exists( 'et_resize_image' ) ){
	function et_resize_image( $thumb, $new_width, $new_height, $crop ){
		$thumb = apply_filters('swiftsecurity_reverse_replace', $thumb);
		if ( is_ssl() ) $thumb = preg_replace( '#^http://#', 'https://', $thumb );
		$info = pathinfo($thumb);
		$ext = $info['extension'];
		$name = wp_basename($thumb, ".$ext");
		$is_jpeg = false;
		$site_uri = apply_filters( 'et_resize_image_site_uri', site_url() );
		$site_dir = apply_filters( 'et_resize_image_site_dir', ABSPATH );

		#get main site url on multisite installation
		if ( is_multisite() ){
		switch_to_blog(1);
		$site_uri = site_url();
		restore_current_blog();
		}

		if ( 'jpeg' == $ext ) {
		$ext = 'jpg';
		$name = preg_replace( '#.jpeg$#', '', $name );
				$is_jpeg = true;
		}

		$suffix = "{$new_width}x{$new_height}";

		$destination_dir = '' != get_option( 'et_images_temp_folder' ) ? preg_replace( '#\/\/#', '/', get_option( 'et_images_temp_folder' ) ) : null;

				$matches = apply_filters( 'et_resize_image_site_dir', array(), $site_dir );
				if ( !empty($matches) ){
				preg_match( '#'.$matches[1].'$#', $site_uri, $site_uri_matches );
				if ( !empty($site_uri_matches) ){
				$site_uri = str_replace( $matches[1], '', $site_uri );
				$site_uri = preg_replace( '#/$#', '', $site_uri );
				$site_dir = str_replace( $matches[1], '', $site_dir );
				$site_dir = preg_replace( '#\\\/$#', '', $site_dir );
				}
				}

				#get local name for use in file_exists() and get_imagesize() functions
				$localfile = str_replace( apply_filters( 'et_resize_image_localfile', $site_uri, $site_dir, et_multisite_thumbnail($thumb) ), $site_dir, et_multisite_thumbnail($thumb) );

				$add_to_suffix = '';
				if ( file_exists( $localfile ) ) $add_to_suffix = filesize( $localfile ) . '_';

				#prepend image filesize to be able to use images with the same filename
				$suffix = $add_to_suffix . $suffix;
				$destfilename_attributes = '-' . $suffix . '.' . $ext;

				$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
				$checkfilename .= $destfilename_attributes;

				if ( $is_jpeg ) $checkfilename = preg_replace( '#.jpeg$#', '.jpg', $checkfilename );

				$uploads_dir = wp_upload_dir();
				$uploads_dir['basedir'] = preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] );

				if ( null !== $destination_dir && '' != $destination_dir && apply_filters('et_enable_uploads_detection', true) ){
					$site_dir = trailingslashit( preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] ) );
					$site_uri = trailingslashit( $uploads_dir['baseurl'] );
				}

				#check if we have an image with specified width and height

				if ( file_exists( $checkfilename ) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );

				$size = @getimagesize( $localfile );
				if ( !$size ) return new WP_Error('invalid_image_path', __('Image doesn\'t exist'), $thumb);
				list($orig_width, $orig_height, $orig_type) = $size;

				#check if we're resizing the image to smaller dimensions
				if ( $orig_width > $new_width || $orig_height > $new_height ){
					if ( $orig_width < $new_width || $orig_height < $new_height ){
						#don't resize image if new dimensions > than its original ones
						if ( $orig_width < $new_width ) $new_width = $orig_width;
						if ( $orig_height < $new_height ) $new_height = $orig_height;

						#regenerate suffix and appended attributes in case we changed new width or new height dimensions
						$suffix = "{$add_to_suffix}{$new_width}x{$new_height}";
						$destfilename_attributes = '-' . $suffix . '.' . $ext;

						$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
						$checkfilename .= $destfilename_attributes;

						#check if we have an image with new calculated width and height parameters
						if ( file_exists($checkfilename) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );
					}
						
					#we didn't find the image in cache, resizing is done here
					$result = image_resize( $localfile, $new_width, $new_height, $crop, $suffix, $destination_dir );

					if ( !is_wp_error( $result ) ) {
						#transform local image path into URI

						if ( $is_jpeg ) $thumb = preg_replace( '#.jpeg$#', '.jpg', $thumb);

						$site_dir = str_replace( '\\', '/', $site_dir );
						$result = str_replace( '\\', '/', $result );
						$result = str_replace( '//', '/', $result );
						$result = str_replace( $site_dir, trailingslashit( $site_uri ), $result );
				}
					
		#returns resized image path or WP_Error ( if something went wrong during resizing )
		return $result;
	}

	#returns unmodified image, for example in case if the user is trying to resize 800x600px to 1920x1080px image
		return $thumb;
	}
}

?>