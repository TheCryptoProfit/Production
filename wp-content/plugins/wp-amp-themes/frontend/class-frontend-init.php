<?php

namespace WP_AMP_Themes\Frontend;

use \WP_AMP_Themes\Includes\Options;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Frontend_Init class for initializing the admin area of the WP AMP Themes plugin.
 *
 * Displays menu & loads static files for the admin page.
 */
class Frontend_Init {


	public function __construct() {

		$this->integrate_template();

	}


	/**
	 * Load a custom AMP template on top of the AMP plugin.
	 */
	public function integrate_template() {

		$wp_amp_themes_options = new Options();
		$theme = $wp_amp_themes_options->get_setting( 'theme' );

		add_filter( 'amp_post_template_metadata', [ $this, 'add_logo_and_image_meta' ], 10, 2 );

		if ( 'default' !== $theme ) {

			add_filter( 'amp_post_template_file', [ $this, 'set_wp_amp_theme_template' ], 10, 3 );
			add_filter( 'amp_content_embed_handlers', [ $this, 'set_wp_amp_post_social_embed' ], 10, 2 );
		}


		if ( '' !== $wp_amp_themes_options->get_setting( 'analytics_id' ) ) {

			add_filter( 'amp_post_template_analytics', [ $this, 'add_analytics' ] );

		}

		if ( $wp_amp_themes_options->get_setting( 'push_notifications_enabled' ) && is_plugin_active( 'onesignal-free-web-push-notifications/onesignal.php' ) ) {

			add_filter( 'amp_content_sanitizers', [ $this, 'add_sanitizer' ], 10, 2 );

			if ( 'default' == $theme ) {

				add_action( 'amp_post_template_css', [ $this, 'add_push_styles' ] );
			}
		}

	}

	/**
	 * Callback for adding logo and image metadata to the schema
	 */
	public function add_logo_and_image_meta( $metadata, $post ) {

		$wp_amp_themes_options = new Options();
		$customizer_settings = $wp_amp_themes_options->get_setting( 'customize' );

		if ( has_post_thumbnail( $post->ID ) ) {

			$metadata['image'] = [
				'@type' => 'ImageObject',
				'url' => get_the_post_thumbnail_url( $post->ID ),
			];

		}

		if ( [] != $customizer_settings && isset( $customizer_settings['logo'] ) && '' != $customizer_settings['logo'] ) {

			$metadata['publisher']['logo'] = [
				'@type' => 'ImageObject',
				'url' => $customizer_settings['logo'],
			];
		}

		return $metadata;

	}


	/**
	 * Callback for loading the custom template together with its parts.
	 *
	 * @param $file
	 * @param $type
	 * @param $post
	 * @return string
	 */
	public function set_wp_amp_theme_template( $file, $type, $post ) {

		$wp_amp_themes_options = new Options();
		$theme = $wp_amp_themes_options->get_setting( 'theme' );

		switch ( $type ) {

			case 'single' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/single.php";

			case 'style' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/style.php";

			case 'side-menu' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/side-menu.php";

			case 'featured-image' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/featured-image.php";

			case 'meta-categories' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/meta-categories.php";

			case 'meta-author-date' :
				return WP_AMP_THEMES_PLUGIN_PATH . "frontend/themes/$theme/meta-author-date.php";

			default :
				return $file;

		}

	}


	/**
	 * Add filter for adding the social media share buttons to a post
	 *
	 * @param $embed_handler_classes
	 * @param $post
	 */
	public function set_wp_amp_post_social_embed( $embed_handler_classes, $post ) {
		require_once( dirname( __FILE__ ) . '/class-embed-handler.php' );
		$embed_handler_classes['WAT_Social_Media_Embed_Handler'] = array();
		return $embed_handler_classes;
	}


	/**
	 * Callback for adding push html elemenets and scripts
	 */
	public function add_sanitizer( $sanitizer_classes, $post ) {

		$one_signal_options = get_option( 'OneSignalWPSetting' );


		if ( $one_signal_options &&
			 is_array( $one_signal_options ) &&
			 isset( $one_signal_options['is_site_https'] ) &&
			 isset( $one_signal_options['app_id'] ) &&
			 '' !== $one_signal_options['app_id'] ) {

				$has_options_http = false == $one_signal_options['is_site_https'] &&
								isset( $one_signal_options['subdomain'] ) &&
								'' !== $one_signal_options['subdomain'];

				if ( $has_options_http || $one_signal_options['is_site_https'] ) {

					require_once( dirname( __FILE__ ) . '/class-push-notification-injection-sanitizer.php' );
					$sanitizer_classes[ 'WAT_Push_Notification_Injection_Sanitizer' ] = $one_signal_options;

					return $sanitizer_classes;
				}
		}

		return $sanitizer_classes;
	}

	/**
	 * Add Google Analytics ID to the template.
	 *
	 * @param array $analytics
	 * @return array
	 */
	public function add_analytics( $analytics ) {

		$wp_amp_themes_options = new Options();
		$analytics_id = $wp_amp_themes_options->get_setting( 'analytics_id' );

		if ( ! is_array( $analytics ) ) {
			$analytics = [];
		}

		$analytics['wp-amp-themes-google-analytics'] = [
			'type' => 'googleanalytics',
			'attributes' => [],
			'config_data' => [
				'vars' => [
					'account' => $analytics_id,
				],
				'triggers' => [
					'trackPageview' => [
						'on' => 'visible',
						'request' => 'pageview',
					],
				],
			],
		];

		return $analytics;
	}


	/**
	 * Add push notification button style for default theme.
	 */
	 public function add_push_styles( $amp_template ) {
		?>
		.web-push {
		margin-top: 16px;
		}

		amp-web-push-widget button.subscribe {
					display: inline-flex;
					align-items: center;
					border-radius: 2px;
					border: 0;
					box-sizing: border-box;
					margin: 0;
					padding: 10px 15px;
					cursor: pointer;
					outline: none;
					font-size: 15px;
					font-weight: 400;
					background: #4A90E2;
					color: white;
					box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.5);
					-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
		}

		amp-web-push-widget button.subscribe .subscribe-icon {
			margin-right: 10px;
		}

		amp-web-push-widget button.subscribe:active {
			transform: scale(0.99);
		}

		amp-web-push-widget button.unsubscribe {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			height: 45px;
			border: 0;
			margin: 0;
			cursor: pointer;
			outline: none;
			font-size: 15px;
			font-weight: 400;
			background: transparent;
			color: #B1B1B1;
			-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
		}
		<?php
	 }
}
