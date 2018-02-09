<?php
/**
 * ChimpMate - WordPress MailChimp Assistant
 *
 * @package		ChimpMate - WordPress MailChimp Assistant
 * @author		Voltroid<care@voltroid.com>
 * @link		http://voltroid.com/chimpamte
 * @copyright	2017 Voltroid
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

if ( is_multisite() ) {

} else {
	
}