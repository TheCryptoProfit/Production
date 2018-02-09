<?php

/**
 * @package           	Steemit-Feed
 * @since				1.1.0
 *
 * @wordpress-plugin
 * Plugin Name:       	Steemit Feed
 * Plugin URI:        	https://steemit.com/@wordpress-tips
 * Description:       	A simple Wordpress plugin that displays a feed of Steemit posts.
 * Version:           	1.1.1
 * Author: 				Minitek.gr
 * Author URI: 			https://www.minitek.gr/
 * License: 			GPLv3 or later
 * Text Domain: 		steemit-feed
 * Domain Path:       	/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SF__ADMIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ).'admin/' );
define( 'SF__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SF__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_steemit_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steemit-feed-activator.php';
	Steemit_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_steemit_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steemit-feed-deactivator.php';
	Steemit_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_steemit_feed' );
register_deactivation_hook( __FILE__, 'deactivate_steemit_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-steemit-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.0
 */
function run_steemit_feed() {

	$plugin = new Steemit_Feed();
	$plugin->run();

}
run_steemit_feed();