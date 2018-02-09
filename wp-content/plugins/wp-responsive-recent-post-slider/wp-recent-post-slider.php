<?php
/**
 * Plugin Name: WP Responsive Recent Post Slider
 * Plugin URI: http://www.wponlinesupport.com/
 * Text Domain: wp-responsive-recent-post-slider
 * Domain Path: /languages/
 * Description: Easy to add and display Recent Post Slider  
 * Author: WP Online Support
 * Version: 1.3.4
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP Online Support
 */

/**
 * Basic plugin definitions
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.0.0
 */
if( !defined( 'WPRPS_VERSION' ) ) {
  define( 'WPRPS_VERSION', '1.3.4' ); // Version of plugin
}
if( !defined( 'WPRPS_DIR' ) ) {
    define( 'WPRPS_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPRPS_URL' ) ) {
    define( 'WPRPS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPRPS_POST_TYPE' ) ) {
    define( 'WPRPS_POST_TYPE', 'post' ); // Plugin post type
}


register_activation_hook( __FILE__, 'install_postslider_free_version' );
function install_postslider_free_version(){
if( is_plugin_active('wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php') ){
     add_action('update_option_active_plugins', 'deactivate_postslider_free_version');
    }
}
function deactivate_postslider_free_version() {
   deactivate_plugins('wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php',true);
}
add_action( 'admin_notices', 'freepostslider_admin_notice');
function freepostslider_admin_notice() {
    $dir = ABSPATH . 'wp-content/plugins/wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php';
    if( is_plugin_active( 'wp-responsive-recent-post-slider/wp-recent-post-slider.php' ) && file_exists($dir)) {
        global $pagenow;
        if( $pagenow == 'plugins.php' ){
            deactivate_plugins ( 'wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php',true);
            if ( current_user_can( 'install_plugins' ) ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Thank you for activating WP Responsive Recent Post Slider</strong>.<br /> It looks like you had PRO version <strong>(<em>WP Responsive Recent Post Slider Pro</em>)</strong> of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it. </p></div>';
            }
        }
    }
}  
 
add_action('plugins_loaded', 'wprps_load_textdomain');
function wprps_load_textdomain() {
	load_plugin_textdomain( 'wp-responsive-recent-post-slider', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
 

// Function file
require_once( WPRPS_DIR . '/includes/wppsac-function.php' );

// Script Fils
require_once( WPRPS_DIR . '/includes/class-wppsac-script.php' );

// Shortcodes
require_once( WPRPS_DIR . '/includes/shortcodes/wppsac-slider.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( WPRPS_DIR . '/includes/admin/wprps-how-it-work.php' );
}