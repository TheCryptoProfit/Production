<?php

 /**
 * Plugin Name: WP AMP Themes
 * Plugin URI:  http://wordpress.org/plugins/wp-amp-themes/
 * Description: The WordPress Accelerated Mobile Pages Themes plugin helps bloggers, publishers and other content creators to easily use various AMP themes on their WordPress websites.
 * Author: AMPThemes.io
 * Author URI: http://ampthemes.io/
 * Version: 1.2.1
 * Copyright (c) 2017 AMPThemes.io
 * License: The WP AMP Themes plugin is Licensed under the Apache License, Version 2.0
 * Text Domain: wp-amp-themes
 */
namespace WP_AMP_Themes;

require_once 'vendor/autoload.php';
require_once 'core/config.php';

global $wp_amp_themes;
$wp_amp_themes  = new Core\WP_AMP_Themes();

function wp_amp_themes_admin_init() {
	new Admin\Admin_Init();
}

function wp_amp_themes_frontend_init() {
	new Frontend\Frontend_Init();
}

function wp_amp_themes_customizer_init() {
	$wp_amp_themes_options = new Includes\Options();

	if ( 'default' !== $wp_amp_themes_options->get_setting( 'theme' ) ) {

		new Includes\WP_AMP_Customizer_Init();
	}
}

register_activation_hook( __FILE__, [ &$wp_amp_themes, 'activate' ] );
register_deactivation_hook( __FILE__, [ &$wp_amp_themes, 'deactivate' ] );


if ( is_admin() ) {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

		$wp_amp_themes_admin_ajax = new Admin\Admin_Ajax();

		add_action( 'wp_ajax_wp_amp_themes_settings', [ &$wp_amp_themes_admin_ajax, 'settings' ] );
		add_action( 'wp_ajax_wp_amp_themes_subscribe' , [ &$wp_amp_themes_admin_ajax, 'subscribe' ] );
		add_action( 'wp_ajax_wp_amp_themes_sync', [ &$wp_amp_themes_admin_ajax, 'sync' ] );
		add_action( 'wp_ajax_wp_amp_themes_switch_theme', [ &$wp_amp_themes_admin_ajax, 'switch_theme' ] );


	} else {

		add_action( 'plugins_loaded', 'WP_AMP_Themes\wp_amp_themes_admin_init' );
	}

} else {
	add_action( 'plugins_loaded', 'WP_AMP_Themes\wp_amp_themes_frontend_init' );

}


add_action( 'plugins_loaded', 'WP_AMP_Themes\wp_amp_themes_customizer_init');
