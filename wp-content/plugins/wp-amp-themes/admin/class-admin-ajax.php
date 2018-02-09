<?php
namespace WP_AMP_Themes\Admin;

use \WP_AMP_Themes\Includes\Options;
use \WP_AMP_Themes\Core\Themes_Config;

/**
 * Admin_Ajax class for managing Ajax requests from the admin area of the plugin
 */
class Admin_Ajax {

	/**
	 * Update theme settings.
	 */
	public function settings() {

		if ( current_user_can( 'manage_options' ) ) {

			$response = [
				'status' => 0,
				'message' => 'There was an error. Please reload the page and try again.',
			];

			$changed = 0;

			if ( ! empty( $_POST ) ) {

				$wp_amp_themes_options = new Options();

				if ( isset( $_POST['wp_amp_themes_settings_analyticsid'] ) &&
				isset( $_POST['wp_amp_themes_settings_facebookappid'] ) &&
				isset( $_POST['wp_amp_themes_settings_push_enabled'] ) &&
				is_numeric( $_POST['wp_amp_themes_settings_push_enabled'] ) &&
				( $_POST['wp_amp_themes_settings_facebookappid'] == '' || is_numeric($_POST['wp_amp_themes_settings_facebookappid']) ) ) {

					// save analytics id
					$new_analytics_id = sanitize_text_field( $_POST['wp_amp_themes_settings_analyticsid'] );
					if ( $new_analytics_id !== $wp_amp_themes_options->get_setting( 'analytics_id' ) ) {

						$changed = 1;
						$wp_amp_themes_options->update_settings( 'analytics_id', $new_analytics_id );
					}

					// save facebook app id
					$new_facebook_app_id = sanitize_text_field( $_POST['wp_amp_themes_settings_facebookappid'] );

					if ( $new_facebook_app_id !== $wp_amp_themes_options->get_setting( 'facebook_app_id' ) ) {

						$changed = 1;
						$wp_amp_themes_options->update_settings( 'facebook_app_id', $new_facebook_app_id );
					}

					// save enable push setting
					$new_push_notifications_enabled = sanitize_text_field( $_POST['wp_amp_themes_settings_push_enabled']);

					if ( $new_push_notifications_enabled !== $wp_amp_themes_options->get_setting( 'push_notifications_enabled' ) ) {

						$changed = 1;
						$wp_amp_themes_options->update_settings( 'push_notifications_enabled', $new_push_notifications_enabled );
					}


					if ( $changed ) {

						$response['status'] = 1;
						$response['message'] = 'Your settings have been successfully modified!';

					} else {

						$response['message'] = 'Your settings have not changed.';
					}
				}

			} // End if().

			echo wp_json_encode( $response );
		} // End if().

		exit();
	}

	/**
	 * Mark the user as being subscribed to the mailing list.
	 */
	public function subscribe() {

		if ( current_user_can( 'manage_options' ) ) {

			$status = 0;

			if ( isset( $_POST ) && is_array( $_POST ) && ! empty( $_POST ) ) {

				if ( isset( $_POST['wp_amp_themes_subscribed'] ) && false != $_POST['wp_amp_themes_subscribed'] ) {

					$wp_amp_themes_options = new Options();
					$subscribed = $wp_amp_themes_options->get_setting( 'joined_subscriber_list' );

					if ( false == $subscribed ) {

						$status = 1;
						$wp_amp_themes_options->update_settings( 'joined_subscriber_list', $_POST['wp_amp_themes_subscribed'] );
					}
				}
			}

			echo $status;

		}

		exit();
	}

	/**
	 * Switch the user theme.
	 */
	public function switch_theme() {

		if ( current_user_can( 'manage_options' ) ){
			$response = 0;

			if ( ! empty( $_GET ) ) {

				$wp_amp_themes_config = new Themes_Config();

				if ( isset( $_GET['theme'] ) && in_array( $_GET['theme'], $wp_amp_themes_config->allowed_themes, true ) ) {

					$wp_amp_themes_options = new Options();
					$new_theme = sanitize_text_field( $_GET['theme'] );
					$wp_amp_themes_options->update_settings( 'customize', [] );
					$wp_amp_themes_options->update_settings( 'theme', $new_theme );

					$response = 1;
				}

			}

			echo $response;

		}

		exit();
	}

	/**
	 * Register installed premium themes in the database.
	 */
	 public function sync() {

		if ( current_user_can( 'manage_options' ) ) {

			$response = [
				'status' => 0,
				'message' => 'There was an error. Please reload the page and try again.',
			];

			$wp_amp_themes_options = new Options();
			$installed_themes = $wp_amp_themes_options->get_setting( 'installed_themes' );

			$wp_amp_themes_config = new Themes_Config();
			$allowed_themes = $wp_amp_themes_config->allowed_themes;

			$new_installed_themes = [];

			foreach ( $allowed_themes as $allowed_theme ) {

				if ( is_dir( WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$allowed_theme/") ) {
					$new_installed_themes[] = $allowed_theme;
				}
			}

			if ( $installed_themes === $new_installed_themes ) {
				$response['message'] = 'No new premium themes installed.';

			} else {

				$wp_amp_themes_options->update_settings( 'installed_themes', $new_installed_themes );
				$response['message'] = 'Sync complete. Your new themes have been registered.';
				$response['status'] = 1;
			}

			echo wp_json_encode( $response );
		}

		exit();

	 }


}
