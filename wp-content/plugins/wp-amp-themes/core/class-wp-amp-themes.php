<?php

namespace WP_AMP_Themes\Core;

use \WP_AMP_Themes\Includes\Options;

/**
 * Main class for the WP AMP Themes plugin. This class handles:
 *
 * - activation / deactivation of the plugin
 */
class WP_AMP_Themes {


	/**
	 *
	 * The activate() method is called on the activation of the plugin.
	 */
	public function activate() {

		$wp_amp_themes_options = new Options();

		// Saves the default settings to the database, if the plugin was previously activated,
		// it does not change the old settings.
		$wp_amp_themes_options->save_settings( $wp_amp_themes_options->options );

	}


	/**
	 *
	 * The deactivate() method is called when the plugin is deactivated.
	 */
	public function deactivate() {

		$wp_amp_themes_options = new Options();

		// Delete saved transients when the plugin is deactivated.
		$wp_amp_themes_options->delete_transients();
	}
}
