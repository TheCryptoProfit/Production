<?php
/**
 * This file is used to markup the feed items.
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/public/partials
 */

if ($feed_options['feed-layout'][0] == 'list1') 
{
	include( plugin_dir_path( __FILE__ ) . 'templates/list1.php' );
}
else if ($feed_options['feed-layout'][0] == 'list2') 
{
	include( plugin_dir_path( __FILE__ ) . 'templates/list2.php' );
}
