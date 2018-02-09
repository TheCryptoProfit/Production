<?php

if ( ! class_exists( 'WAT_Social_Media_Embed_Handler' ) ) {

	/**
	 * Class WAT_Social_Media_Embed_Handler.
	 * Hook into the WP AMP post content to add social media sharing and AMP scripts for the side menu.
	 */
	class WAT_Social_Media_Embed_Handler extends AMP_Base_Embed_Handler {

		/**
		 * Register a new post content filter.
		 */
		public function register_embed() {

			// Add our new callback.
			add_filter( 'the_content', array( $this, 'add_social_media' ) );
		}

		/**
		 * Remove the post content filter.
		 */
		public function unregister_embed() {
			remove_filter( 'the_content', array( $this, 'add_social_media' ) );
		}

		/**
		 * Enqueue scripts.
		 *
		 * @todo Find a better way to enqueue the AMP scripts for side menu, once WP AMP allows it.
		 */
		public function get_scripts() {
			return array(
				'amp-accordion' => 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js',
				'amp-sidebar' => 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js',
				'amp-social-share' => 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js',
			);
		}

		/**
		 * Append social media buttons to the post content.
		 *
		 * @param string $content
		 * @return string
		 */
		public function add_social_media( $content ) {

			$wp_amp_themes_options = new \WP_AMP_Themes\Includes\Options();
			$facebook_app_id = $wp_amp_themes_options->get_setting( 'facebook_app_id' );

			$social_media = '<div class="ampstart-social-box py3">
				<amp-social-share type="twitter"></amp-social-share>
				<amp-social-share type="facebook" data-param-app_id="' . $facebook_app_id . '"></amp-social-share>
				<amp-social-share type="gplus"></amp-social-share>

				<amp-social-share type="pinterest"></amp-social-share>
				<amp-social-share type="email"></amp-social-share>
			</div>';

			$content .= $social_media;

			return $content;
		}
	}
}// End if().
