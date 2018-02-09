<?php
namespace WP_AMP_Themes\Admin;

use \WP_AMP_Themes\Includes\Options;

/**
 * Admin_Init class for initializing the admin area of the WP AMP Themes plugin.
 *
 * Displays menu & loads static files for the admin page.
 */
class Admin_Init {

	private static $menu_title = WP_AMP_THEMES_PLUGIN_NAME;
	private static $label = WP_AMP_THEMES_SHORT_NAME;


	/**
	 * Constructor functions that adds all the admin hooks.
	 */
	function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_action( 'admin_notices', [ $this, 'amp_themes_notices' ] );

		add_filter( 'plugin_action_links_' . plugin_basename( WP_AMP_THEMES_PLUGIN_PATH . '/wp-amp-themes.php' ), [ $this, 'add_settings_link' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

	}

	/**
	 * Function that adds the plugin settings button to the wordpress menu side bar.
	 */
	public function admin_menu() {

		if ( is_plugin_active( 'amp/amp.php' ) ) {
			// Add menu hook.
			add_menu_page( self::$menu_title, self::$label, 'manage_options', 'wp-amp-themes', [ $this, 'settings' ], WP_PLUGIN_URL . '/' . WP_AMP_THEMES_DOMAIN . '/admin/images/amp-logo.png' );

		} else {

			add_menu_page( self::$menu_title, self::$label, 'manage_options', 'wp-amp-themes', [ $this, 'settings' ], WP_PLUGIN_URL . '/' . WP_AMP_THEMES_DOMAIN . '/admin/images/amp-logo-notice.png' );
		}
	}

	/**
	 * Load settings page.
	 */
	public function settings() {
		include( WP_AMP_THEMES_PLUGIN_PATH . 'admin/pages/settings.php' );
	}


	/**
	* Checks that the site url settings are configured correctly.
	*/
	public function check_main_url() {

		$url = plugins_url() . '/';

		$status = [
			'valid' => false,
			'message' => '',
		];

		if ( false !== strpos( $url, 'http://' ) ) {

			$status['message'] = 'There is a problem with your main site URL. Please change your WordPress Address(URL) or your Site Address(URL) to https in order for Push Notifications to function correctly.';
			return $status;
		}

		$response = wp_remote_head( $url );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( '301' == $response_code || '302' == $response_code ) {

			$status['message'] = 'There is a problem with your main site URL. Please make sure your WordPress Address(URL) or Site Address(URL) is valid in order for Push Notifications to function correctly.';
			return $status;
		}

		if ( false === strpos( $url, 'www.') ) {

			$www_check_response = wp_remote_head( str_replace( 'https://', 'https://www.', $url ) );
			$www_check_response_code = wp_remote_retrieve_response_code( $www_check_response );

		} else {

			$www_check_response = wp_remote_head( str_replace( 'https://www.', 'https://', $url ) );
			$www_check_response_code = wp_remote_retrieve_response_code( $www_check_response );
		}


		if ( '200' == $www_check_response_code && '200' == $response_code  ) {

			$status['message'] = 'There is a problem with your main site URL. Plase make sure you have a single main URL configured in order for Push Notifications to function correctly.';
			return $status;
		}

		$status['valid'] = true;

		return $status;
	}

	/**
	 * Checks if the AMP plugin from Automattic is installed and displays a notice if not.
	 */
	public function amp_themes_notices() {

		if ( ! is_plugin_active( 'amp/amp.php' ) ) {

			echo '<div class="notice notice-warning is-dismissible">
						<p><b>WP AMP Themes</b> requires that you have the AMP plugin from Automattic active.</p>
						<p>
							Please make sure you have the plugin installed and activated. <a href="' . get_admin_url() . 'plugin-install.php?s=amp&tab=search&type=term">Download the AMP plugin</a>
						</p>
				  </div>';

			return;
		}

		$wp_amp_themes_options = new Options();

		if ( $wp_amp_themes_options->get_setting( 'push_notifications_enabled' ) ) {

			if ( ! is_plugin_active( 'onesignal-free-web-push-notifications/onesignal.php' ) ) {

				echo '<div class="notice notice-warning is-dismissible">
							<p><b>WP AMP Themes</b> requires that you have the OneSignal plugin active in order for AMP Push Notifications to work.</p>
							<p>
								Please make sure you have the plugin installed and activated. <a href="' . get_admin_url() . 'plugin-install.php?s=onesignal&tab=search&type=term">Download the OneSignal plugin</a>
							</p>
					  </div>';

				return;
			}

			$one_signal_settings = get_option( 'OneSignalWPSetting' );

			if ( ! $one_signal_settings || ! is_array( $one_signal_settings ) || ! isset( $one_signal_settings['is_site_https'] ) ) {

				echo $this->build_message ( 'OneSignal plugin', 'OneSignal' );

				return;
			}

			if ( ! isset( $one_signal_settings['app_id'] ) || '' == $one_signal_settings['app_id'] ) {

				echo $this->build_message ( 'OneSignal appId', 'OneSignal' );

				return;
			}

			if ( ! $one_signal_settings['is_site_https'] ) {

				if ( isset( $one_signal_settings['subdomain'] ) && '' != $one_signal_settings['subdomain'] ) {
					return;
				}

				echo $this->build_message( 'subdomain', 'OneSignal' );

				return;

			}
		}
	}


	/**
	 * Returns the admin notice message for push notifications
	 */
	public function build_message ( $subject, $plugin ) {

		return '<div class="notice notice-warning is-dismissible">
					<p>
						Please make sure the ' . $subject . ' is set up correctly in the ' . $plugin . ' plugin configuration menu.
					</p>
				</div>';
	}

	/**
	 * Adds a settings link to the plugin in the plugin list.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . get_admin_url() . 'admin.php?page=wp-amp-themes">' . __( 'Settings' ) . '</a>';

		if ( ! is_plugin_active( 'amp/amp.php' ) ) {
			$settings_link = '<a href="' . get_admin_url() . 'plugin-install.php?s=amp&tab=search&type=term"><span style="color:#b30000">' . __( 'Action Required: Get AMP' ) . '</span></a>';
		}

		array_push( $links, $settings_link );

		return $links;
	}

	/**
	 * Used to enqueue scripts for the admin area.
	 */
	public function enqueue_scripts() {

		$wp_amp_themes_options = new Options();

		wp_enqueue_style( $wp_amp_themes_options->prefix . 'css_general', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/css/general-1_1.css' ), [], WP_AMP_THEMES_VERSION );

		$dependencies = [ 'jquery-core', 'jquery-migrate' ];

		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_validate', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Interface/Lib/jquery.validate.min.js' ), $dependencies, '1.11.1' );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_validate_additional', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Interface/Lib/validate-additional-methods.min.js' ), $dependencies, '1.11.1' );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_loader', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Interface/Loader.min.js' ), $dependencies, WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_ajax_upload', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Interface/AjaxUpload.min.js' ), $dependencies, WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_interface', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Interface/JSInterface.min.js' ), $dependencies, WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_settings', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Modules/WP_AMP_Themes_Settings.min.js' ), [], WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_subscribe', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Modules/WP_AMP_Themes_Subscribe.min.js' ), [], WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_sync', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Modules/WP_AMP_Themes_Sync.min.js' ), [], WP_AMP_THEMES_VERSION );
		wp_enqueue_script( $wp_amp_themes_options->prefix . 'js_switcher', plugins_url( WP_AMP_THEMES_DOMAIN . '/admin/js/UI.Modules/WP_AMP_Themes_Switcher.min.js' ), [], WP_AMP_THEMES_VERSION );
	}

}


