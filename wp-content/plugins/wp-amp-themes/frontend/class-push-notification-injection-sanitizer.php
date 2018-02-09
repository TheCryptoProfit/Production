<?php

class WAT_Push_Notification_Injection_Sanitizer extends AMP_Base_Sanitizer {


	/**
	 * Enqueue scripts.
	 *
	 */
	public function get_scripts() {

        return [
			'amp-web-push' => 'https://cdn.ampproject.org/v0/amp-web-push-0.1.js',
		];
	}

	public function sanitize() {

		$wp_amp_themes_options = new \WP_AMP_Themes\Includes\Options();
		$body = $this->get_body_node();

		$push_div_node = AMP_DOM_Utils::create_node( $this->dom, 'div', [
			'class' => 'web-push',
		]);


		if ( $this->args['is_site_https'] == false ) {

			$helper_url = 'https://' . $this->args['subdomain'] . '.os.tc/amp/helper_frame?appId=' . $this->args['app_id'];
			$dialog_url = 'https://' . $this->args['subdomain'] . '.os.tc/amp/permission_dialog?appId=' . $this->args['app_id'];
			$worker_url = 'https://' . $this->args['subdomain'] . '.os.tc/OneSignalSDKWorker.js?appId=' . $this->args['app_id'];

		} else {

			$helper_url = plugins_url() . '/onesignal-free-web-push-notifications/sdk_files/amp-helper-frame.html?appId=' . $this->args['app_id'];
			$dialog_url = plugins_url() . '/onesignal-free-web-push-notifications/sdk_files/amp-permission-dialog.html?appId=' . $this->args['app_id'];
			$worker_url = plugins_url() . '/onesignal-free-web-push-notifications/sdk_files/OneSignalSDKWorker.js.php?appId=' . $this->args['app_id'];
		}

		$web_push_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-web-push', [
			'id' => 'amp-web-push',
			'layout' => 'nodisplay',
			'helper-iframe-url' => $helper_url,
			'permission-dialog-url' => $dialog_url,
			'service-worker-url' => $worker_url,
		] );

		$subscribe_widget_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-web-push-widget', [
			'visibility' => 'unsubscribed',
			'layout' => 'fixed',
			'width' => '245',
			'height' => '45',
		] );

		$subscribe_button_node = AMP_DOM_Utils::create_node( $this->dom, 'button', [
			'class' => 'subscribe',
			'on' => 'tap:amp-web-push.subscribe',
		] );

		$icon_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-img', [
			'class' => 'subscribe-icon',
			'width' => '24',
			'height' => '24',
			'layout' => 'fixed',
			'src' => 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0ic3Vic2NyaWJlLWljb24iIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xMS44NCAxOS44ODdIMS4yMnMtLjk0Ny0uMDk0LS45NDctLjk5NWMwLS45LjgwNi0uOTQ4LjgwNi0uOTQ4czMuMTctMS41MTcgMy4xNy0yLjYwOGMwLTEuMDktLjUyLTEuODUtLjUyLTYuMzA1czIuODUtNy44NyA2LjI2LTcuODdjMCAwIC40NzMtMS4xMzQgMS44NS0xLjEzNCAxLjMyNSAwIDEuOCAxLjEzNyAxLjggMS4xMzcgMy40MTMgMCA2LjI2IDMuNDE4IDYuMjYgNy44NyAwIDQuNDYtLjQ3NyA1LjIyLS40NzcgNi4zMSAwIDEuMDkgMy4xNzYgMi42MDcgMy4xNzYgMi42MDdzLjgxLjA0Ni44MS45NDdjMCAuODUzLS45OTYuOTk1LS45OTYuOTk1SDExLjg0ek04IDIwLjk3N2g3LjExcy0uNDkgMi45ODctMy41MyAyLjk4N1M4IDIwLjk3OCA4IDIwLjk3OHoiIGZpbGw9IiNGRkYiLz48L3N2Zz4='
		] );

		$unsubscribe_widget_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-web-push-widget', [
			'visibility' => 'subscribed',
			'layout' => 'fixed',
			'width' => '230',
			'height' => '45',
		] );

		$unsubscribe_button_node = AMP_DOM_Utils::create_node( $this->dom, 'button', [
			'class' => 'unsubscribe',
			'on' => 'tap:amp-web-push.unsubscribe',
		] );

		$subscribe_text = $this->dom->createTextNode( 'Subscribe to updates' );
		$subscribe_button_node->appendChild( $icon_node );
		$subscribe_button_node->appendChild( $subscribe_text );
		$subscribe_widget_node->appendChild( $subscribe_button_node );

		$unsubscribe_text = $this->dom->createTextNode( 'Unsubscribe from updates' );
		$unsubscribe_button_node->appendChild( $unsubscribe_text );
		$unsubscribe_widget_node->appendChild( $unsubscribe_button_node );


		$push_div_node->appendChild( $web_push_node );
		$push_div_node->appendChild( $subscribe_widget_node );
		$push_div_node->appendChild( $unsubscribe_widget_node );


		if ( 'default' == $wp_amp_themes_options->get_setting( 'theme' ) ) {

			$body->appendChild( $push_div_node );

		} else {

			$social_node = $body->getElementsByTagName( 'amp-social-share' );
			$body->insertBefore( $push_div_node, $social_node->item(0)->parentNode );

		}
	}
}
