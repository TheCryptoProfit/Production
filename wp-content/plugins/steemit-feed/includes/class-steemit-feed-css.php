<?php

/**
 * CSS class
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/includes
 */

class Steemit_Feed_CSS {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()
	
	/**
	 * Creates inline css for the pagination buttons.
	 *
	 * @since    1.1.0
	 */
	/*public function getPaginationCss($feed_options, $feed_id) {
		
		$steemitfeed = 'steemitfeed-wrapper-'.$feed_id;
		$background_color = $feed_options['feed-pagination-color'][0];
		$text_color = $feed_options['feed-pagination-text-color'][0];
		$border_radius = (int)$feed_options['feed-pagination-border-radius'][0];
		
		$css = '
		#'.$steemitfeed.' a.steemitfeed-page-btn {
			border-radius: '.$border_radius.'px;
			background-color: '.$background_color.';
			border-color: '.$background_color.';
			color: '.$text_color.';
		}
		';	
		
		return $css;	
		
	}*/
		
	/**
	 * Creates inline css for the responsive levels.
	 *
	 * @since    1.1.0
	 */
	public function getResponsiveCss($feed_options, $feed_id) {
		
		$steemitfeed = '#steemitfeed-wrapper-'.$feed_id;
		
		// Responsive layout options
		$responsive_breakpoint = isset($feed_options['feed-breakpoint'][0]) ? (int)$feed_options['feed-breakpoint'][0] : '400';
		$title_font_size = (isset($feed_options['feed-title-font-size'][0]) && is_numeric($feed_options['feed-title-font-size'][0])) ? (int)$feed_options['feed-title-font-size'][0].'px' : '18px';
		$body_font_size = (isset($feed_options['feed-body-font-size'][0]) && is_numeric($feed_options['feed-body-font-size'][0])) ? (int)$feed_options['feed-body-font-size'][0].'px' : '15px';
		$tags_font_size = (isset($feed_options['feed-tags-font-size'][0]) && is_numeric($feed_options['feed-tags-font-size'][0])) ? (int)$feed_options['feed-tags-font-size'][0].'px' : '14px';
		
		// Start CSS
		$css = '';
		
		// Scroll bar
		$enable_scrollbar = $feed_options['feed-scrollbar'][0];
		$max_height = (isset($feed_options['feed-maxheight'][0]) && is_numeric($feed_options['feed-maxheight'][0])) ? (int)$feed_options['feed-maxheight'][0].'px' : '400px';
		if ($enable_scrollbar === 'yes')
		{
			$css .= '
			'.$steemitfeed.'.steemitfeed-wrapper {
				max-height: '.$max_height.';
				border-top: 1px solid #e5e5e5;
				border-bottom: 1px solid #e5e5e5;
			}
			'.$steemitfeed.' .sf-li {
				margin-right: 10px;	
				margin-left: 10px;	
			}
			'.$steemitfeed.' .sf-list1 .sf-li:last-of-type {
				border-bottom: 0;
			}
			'.$steemitfeed.' .sf-pagination {
				margin-right: 10px;	
				margin-left: 10px;	
				border-top: 1px solid #eee;
			}
			';
		}
		
		// Typography
		$css .= '
		'.$steemitfeed.' .sf-li-title {
			font-size: '.$title_font_size.';
		}
		'.$steemitfeed.' .sf-li-tags {
			font-size: '.$tags_font_size.';
		}
		'.$steemitfeed.' .sf-li-body {
			font-size: '.$body_font_size.';
		}
		';
		
		// Responsive levels
		$css .= '@media only screen and (max-width:'.$responsive_breakpoint.'px) 
		{
			'.$steemitfeed.' .sf-list1 .sf-image {
				float: none;
				display: block;
				width: 100%;
				margin: 0 0 12px;
			}
		}';		 		
	
		return $css;	
		
	}
	
} // class