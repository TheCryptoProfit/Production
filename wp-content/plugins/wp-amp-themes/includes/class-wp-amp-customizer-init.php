<?php
namespace WP_AMP_Themes\Includes;

use \WP_AMP_Themes\Core\Themes_Config;


/**
 * Class that initializes the theme customizer changes made by WP AMP Themes.
 */
class WP_AMP_Customizer_Init {

	/**
	 * Hook in and add the theme customizer changes when the AMP plugin initializes.
	 */
	function __construct() {

		add_action( 'amp_init', [ $this, 'init' ] );

	}

	/**
	 * Hook in to the AMP plugin's theme customizer and get_settings functions.
	 */
	public function init() {
		add_action( 'amp_customizer_init', [ $this, 'init_customizer' ] );
		add_action( 'amp_customizer_get_settings', [ $this, 'append_settings' ] );
	}

	/**
	 * Hook in to the AMP plugin's customizer after its default actions run.
	 */
	public function init_customizer() {

		add_action( 'amp_customizer_register_settings', [ $this, 'register_customizer_settings' ] );
		add_action( 'amp_customizer_register_ui', [ $this, 'register_customizer_ui' ], 11 );
		add_action( 'amp_customizer_enqueue_preview_scripts', [ $this, 'dequeue_customizer_preview_scripts' ], 11 );
	}

	/**
	 * Remove unused scripts from the Theme Customizer.
	 */
	public function dequeue_customizer_preview_scripts() {
		wp_dequeue_script( 'amp-customizer-design-preview' );
	}

	/**
	 * Register the WP AMP Themes plugin's theme customizer settings with WordPress.
	 *
	 * @param object $wp_customize
	 */
	public function register_customizer_settings( $wp_customize ) {

		$wp_amp_themes_config = new Themes_Config();
		$theme_presets = $wp_amp_themes_config->get_theme_config();

		foreach ( $theme_presets['presets'] as $key => $preset ) {

			$wp_customize->add_setting( 'wp_amp_themes_customize[' . $theme_presets['vars'][ $key ] . ']', [
				'type'				=> 'option',
				'default'			=> $preset,
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'			=> 'refresh',
			]);

		}

		$wp_customize->add_setting( 'wp_amp_themes_customize[logo]', [
			'type' => 'option',
		]);

	}


	/**
	 * Add controls to the theme customizer for the WP AMP Themes plugin's settings.
	 *
	 * @param object $wp_customize
	 */
	public function register_customizer_ui( $wp_customize ) {

		// Remove unused settings.
		$wp_customize->remove_control( 'amp_color_scheme' );
		$wp_customize->remove_control( 'amp_header_color' );
		$wp_customize->remove_control( 'amp_header_background_color' );

		$wp_amp_themes_config = new Themes_Config();
		$theme_presets = $wp_amp_themes_config->get_theme_config();

		foreach ( $theme_presets['presets'] as $key => $preset ) {

			// Add color pickers.
			$wp_customize->add_control(
				new \WP_Customize_Color_Control( $wp_customize, 'wp_amp_themes_' . $theme_presets['vars'][ $key ] . '_some_color', [
					'settings'		=> 'wp_amp_themes_customize[' . $theme_presets['vars'][ $key ] . ']',
					'label'			=> __( $theme_presets['labels'][ $key ], 'wp-amp-themes' ),
					'section'		=> 'amp_design',
				])
			);

		}

		// Add logo uploader.
		$wp_customize->add_control(
			new \WP_Customize_Image_Control( $wp_customize, 'wp_amp_themes_logo', [
				'settings'		=> 'wp_amp_themes_customize[logo]',
				'label'			=> __( 'Logo', 'wp-amp-themes' ),
				'section'		=> 'amp_design',
			])
		);

	}


	/**
	 * Append the new settings so that they can be retrieved with the get_customizer_setting function.
	 *
	 * @param array $settings
	 * @return array $settings
	 */
	public function append_settings( $settings ) {

		$settings = array_merge( $settings, get_option( 'wp_amp_themes_customize', [] ) );

		$wp_amp_themes_config = new Themes_Config();
		$theme_presets = $wp_amp_themes_config->get_theme_config();

		// Append colors to the settings array.
		foreach ( $theme_presets['presets'] as $key => $preset ) {

			$settings = wp_parse_args( $settings, [
				$theme_presets['vars'][ $key ] => $preset,
			]);
		}

		// Append logo url to the settings array.
		$settings = wp_parse_args( $settings, [
			'logo' => '',
		]);

		return $settings;
	}

}
