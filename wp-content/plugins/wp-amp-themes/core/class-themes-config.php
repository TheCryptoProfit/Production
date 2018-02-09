<?php
namespace WP_AMP_Themes\Core;

use \WP_AMP_Themes\Includes\Options;

class Themes_Config {

	public $allowed_themes = [
		'default',
		'obliq',
		'ghost',
		'palm',
	];


	public function get_theme_config() {

		$wp_amp_themes_options = new Options();

		$theme = $wp_amp_themes_options->get_setting( 'theme' );

		$theme_config_path = WP_AMP_THEMES_PLUGIN_PATH . 'frontend/themes/' . $theme . '/presets.json';
		$theme_config_url = plugin_dir_url( $theme_config_path ) . '/presets.json';

		if ( file_exists( $theme_config_path ) ) {

			$theme_config = wp_remote_get( $theme_config_url );
			$theme_config_json = json_decode( wp_remote_retrieve_body( $theme_config ), true );

			if ( $theme_config_json && ! empty( $theme_config_json ) &&
				array_key_exists( 'vars', $theme_config_json ) && is_array( $theme_config_json['vars'] ) &&
				array_key_exists( 'labels', $theme_config_json ) && is_array( $theme_config_json['labels'] ) &&
				array_key_exists( 'presets', $theme_config_json ) && is_array( $theme_config_json['presets'] ) ) {

					return $theme_config_json;

			}
		}

		return false;
	}
}
