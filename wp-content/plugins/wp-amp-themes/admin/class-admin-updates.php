<?php
namespace WP_AMP_Themes\Admin;

use \WP_AMP_Themes\Includes\Options;

/**
 * Admin_Updates class for reading premium updates.
 */
class Admin_Updates {

	/**
	 *
	 * Request json with premium themes.
	 * The method returns an array containing the upgrade information or an empty array by default.
	 *
	 * @return array or string
	 */
	public function premium_themes() {

		$wp_amp_themes_options = new Options();

		$json_data = get_transient( $wp_amp_themes_options->prefix . 'premium_themes' );

		if ( $json_data ) {

			if ( 'warning' === $json_data ) {
				return $json_data;
			}

			$response = json_decode( $json_data, true );

			if ( isset( $response['content'] ) && is_array( $response['content'] ) && ! empty( $response['content'] ) ) {

				if ( isset( $response['content']['version'] ) && WP_AMP_THEMES_PREMIUM_THEMES_VERSION == $response['content']['version'] ) {
					return $response['content'];
				}
			}
		}

		// JSON URL that should be requested.
		$json_url = WP_AMP_THEMES_PREMIUM_THEMES;

		// Get response.
		$json_response = $this->read_data( $json_url );

		if ( false !== $json_response && '' != $json_response ) {

			// Store this data in a transient.
			set_transient( $wp_amp_themes_options->prefix . 'premium_themes', $json_response, 3600 * 24 * 2 );

			$response = json_decode( $json_response, true );

			if ( isset( $response['content'] ) && is_array( $response['content'] ) && ! empty( $response['content'] ) ) {
				return $response['content'];
			}

		} elseif ( false == $json_response ) {

			// Store this data in a transient.
			set_transient( $wp_amp_themes_options->prefix . 'premium_themes', 'warning', 3600 * 24 * 2 );
			return 'warning';
		}

		return [];
	}

	/**
	 *
	 * Method used to request the content of different pages using wp_remote_get.
	 * This method returns an empty string if the json could not be read.
	 *
	 * @param string $json_url
	 * @return string
	 */
	public function read_data( $json_url ) {

		$args = [
			'timeout' => 2,
			'header' => [
				'Accept: application/json',
				'Content-type: application/json',
			],
			'sslverify' => false,
		];

		$response = wp_remote_get( $json_url, $args );

		$response_json = wp_remote_retrieve_body( $response );
		$response_status = wp_remote_retrieve_response_code( $response );

		if ( 200 == $response_status ) {
			return $response_json;
		}

		return '';

	}
}
