<?php
namespace WP_AMP_Themes;

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit ();
}

require_once( 'vendor/autoload.php' );

$wp_amp_themes_options = new Includes\Options();

$wp_amp_themes_options->delete_settings($wp_amp_themes_options->options);
$wp_amp_themes_options->delete_settings('customize');


?>
