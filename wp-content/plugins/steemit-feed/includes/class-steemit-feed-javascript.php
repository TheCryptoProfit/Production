<?php

/**
 * Javascript class
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/includes
 */

class Steemit_Feed_Javascript {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()
	
	/**
	 * Creates main javascript.
	 *
	 * @since    1.1.0
	 */
	public function getMainScript($feed_id) {
		
		$steemitfeed_id = 'steemitfeed-'.$feed_id;
		$steemitfeed_wrapper_id = 'steemitfeed-wrapper-'.$feed_id;
		$feed_options = get_post_meta( $feed_id, '', false );
		$steemitfeed_ajaxurl = admin_url( 'admin-ajax.php' );
		$encoded_options = json_encode($feed_options);
	
		$javascript = "
		<script type='text/javascript'>
			(function ($) {
				$(document).ready(function() 
				{
		";
		
		if ($feed_options['feed-load-asynch'][0] == 'yes')
		{	
			$javascript .= "
						// Options
						var feedId = ".$feed_id.";
											
						// Initialize spinner
						var createSpinner = function()
						{ 
							var spinner_options = {
							  lines: 9,
							  length: 4,
							  width: 3,
							  radius: 3,
							  corners: 1,
							  rotate: 0,
							  direction: 1,
							  color: '#333',
							  speed: 1,
							  trail: 52,
							  shadow: false,
							  hwaccel: false,
							  className: 'spinner',
							  zIndex: 2e9,
							  top: '50%',
							  left: '50%'
							};
							$('#steemitfeed-loader-'+feedId+'').append(new Spinner(spinner_options).spin().el);
							
							return;
						}
						
						// Check if spinner has initialized
						if (!$('#steemitfeed-loader-'+feedId+' .spinner').length)
							createSpinner();
				
						// Show loader
						$('#steemitfeed-loader-'+feedId+'').show();
			";
		}
		
		$javascript .= "													
				});
			})(jQuery)
		</script>
		";
		
		return $javascript;
		
	}

} // class