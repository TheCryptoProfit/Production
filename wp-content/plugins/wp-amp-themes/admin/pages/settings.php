<?php
$wp_amp_themes_options = new \WP_AMP_Themes\Includes\Options();

$theme = $wp_amp_themes_options->get_setting( 'theme' );
$analytics_id = $wp_amp_themes_options->get_setting( 'analytics_id' );
$facebook_app_id = $wp_amp_themes_options->get_setting( 'facebook_app_id' );
$push_notifications_enabled = $wp_amp_themes_options->get_setting( 'push_notifications_enabled' );

$main_site_url_status = '';
$one_signal_options = get_option( 'OneSignalWPSetting' );
if ( is_array( $one_signal_options ) &&
	isset( $one_signal_options['is_site_https'] ) &&
	$one_signal_options['is_site_https'] ) {

		$wp_amp_themes_admin_init = new \WP_AMP_Themes\Admin\Admin_Init();
		$main_site_url_status = $wp_amp_themes_admin_init->check_main_url();

}

$wp_amp_themes_admin_updates = new \WP_AMP_Themes\Admin\Admin_Updates();
$premium_content = $wp_amp_themes_admin_updates->premium_themes();
$premium_themes = isset( $premium_content['list'] ) && is_array( $premium_content['list'] ) ? $premium_content['list'] : [];

$installed_themes = $wp_amp_themes_options->get_setting( 'installed_themes' );
?>

<script type="text/javascript">
	if (window.WATJSInterface && window.WATJSInterface != null){
		jQuery(document).ready(function(){

			WATJSInterface.localpath = "<?php echo plugins_url() . '/' . WP_AMP_THEMES_DOMAIN . '/'; ?>";
			WATJSInterface.init();
		});
	}
</script>

<div id="wp-amp-themes-admin">

	<div class="wrap">
		<div class="left-side">

			<div class="title_section">
				<h1 class="wp-heading-inline">WP AMP THEMES</h1>
				<div class="wp_amp_themes_sync_btn page-title-action">Sync</div> <p> (Press to detect newly installed themes) </p>
			</div>
			<hr class="separator" />
			<div class="spacer-20"></div>
			<div class="spacer-10"></div>
			<div class="theme-switcher">
				<?php if ( is_array( $installed_themes ) && ! empty( $installed_themes ) ): ?>
					<?php foreach ( $installed_themes as $installed_theme ): ?>
						<div class="theme <?php echo ( $installed_theme === $theme ) ? 'active' : '' ?>">
							<div class="theme-screenshot">
								<img src="<?php echo plugins_url() . '/' . WP_AMP_THEMES_DOMAIN . "/frontend/themes/$installed_theme/theme-$installed_theme.png"; ?>" />
							</div>
							<h2 class="theme-name">
								<?php if ( $installed_theme === $theme ): ?>
									<span>Active:</span>
								<?php endif; ?>
								<?php echo esc_html( ucwords( $installed_theme ) ); ?>
							</h2>
							<div class="theme-actions">
								<?php if ( $installed_theme === $theme ): ?>
									<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=amp_design&customize_amp=1' ) ); ?>" class="button button-primary">Customize</a>
								<?php else: ?>
									<div class="wp_amp_themes_switcher_select button-secondary" data-theme="<?php echo esc_html( $installed_theme ); ?>" >Activate</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach ?>
				<?php endif; ?>
				<div class="theme <?php echo ( 'default' === $theme ) ? 'active' : '' ?>">
					<img class="default-screenshot" src="<?php echo plugins_url() . '/' . WP_AMP_THEMES_DOMAIN . "/admin/images/default-theme.png"; ?>" />
					<h2 class="theme-name">
						<?php if ( 'default' === $theme ): ?>
							<span>Active:</span>
						<?php endif; ?>
						Default
					</h2>
					<div class="theme-actions">
						<?php if ( 'default' === $theme ): ?>
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=amp_design&customize_amp=1' ) ); ?>" class="button button-primary">Customize</a>
						<?php else: ?>
							<div class="wp_amp_themes_switcher_select button-secondary" data-theme="default" >Activate</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="spacer-10"></div>
			<h2>Settings</h2>
			<hr class="separator" />
			<form name="wp_amp_themes_settings_form" id="wp_amp_themes_settings_form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=wp_amp_themes_settings" method="post">
				<label class="textinput">Google Analytics ID:</label>
				<input
					type="text"
					name="wp_amp_themes_settings_analyticsid"
					id="wp_amp_themes_settings_analyticsid"
					value="<?php echo esc_attr($analytics_id); ?>"
					placeholder="UA-xxxxxx-01"
				>
				</input> <br/>
				<p class="field-message error" id="error_analyticsid_container"></p>
				<div <?php echo ( 'default' === $theme ) ? "style=display:none" : '' ?> >
					<label class="textinput">Facebook App ID: </label>
					<input
						type="text"
						name="wp_amp_themes_settings_facebookappid"
						id="wp_amp_themes_settings_facebookappid"
						value="<?php echo esc_attr($facebook_app_id); ?>"
					>
					</input> <br/>
				</div>
				<p class="field-message error" id="error_facebookappid_container"></p>
				<div class="spacer-10"></div>
				<input type="hidden" name="wp_amp_themes_settings_push_enabled" id="wp_amp_themes_settings_push_enabled" value="<?php echo esc_attr($push_notifications_enabled);?>" />
				<input type="checkbox" name="wp_amp_themes_settings_push_enabled_check" id="wp_amp_themes_settings_push_enabled_check" value="0" <?php if ($push_notifications_enabled == 1) echo "checked" ;?> />
				<label for ="wp_amp_themes_settings_push_enabled_check">Display push notifications on AMP pages via the OneSignal plugin</label>
				<p>For a guide on how to setup AMP push notifications please visit <a href="http://ampthemes.io/accelerated-mobile-pages-push-notifications-onesignal-wordpress-amp-themes/" target="_blank">this link</a>.</p>
				<div class="spacer-30"></div>
				<a href="javascript:void(0)" id="wp_amp_themes_settings_send_btn" class="button button-primary button-large">Save</a>
			</form>
			<div class="spacer-20"></div>
			<?php if ( $push_notifications_enabled && is_array( $main_site_url_status )  && !$main_site_url_status['valid'] ): ?>
				<div class="notice notice-error is-dismissible">
					<p><?php echo $main_site_url_status['message']; ?></p>
				</div>
			<?php endif; ?>

			<?php if ( count( $premium_themes ) > 0 ) : ?>

				<h2>Premium WP AMP Themes</h1>
				<hr class="separator" />

				<div class="themes">
					<?php
					foreach ( $premium_themes as $theme ) {
						include( WP_AMP_THEMES_PLUGIN_PATH . 'admin/sections/theme-box-premium.php' );
					}
					?>
				</div>

			<?php endif;?>
		</div>

		<div class="right-side">
			<?php include_once( WP_AMP_THEMES_PLUGIN_PATH . 'admin/sections/subscribe.php' ); ?>
			<div class="spacer-0"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	if (window.WATJSInterface && window.WATJSInterface != null){
		jQuery(document).ready(function(){

			window.WATJSInterface.add("UI_settings","WP_AMP_THEMES_SETTINGS",{'DOMDoc':window.document}, window);
			window.WATJSInterface.add("UI_sync","WP_AMP_THEMES_SYNC",{'DOMDoc':window.document}, window);
			window.WATJSInterface.add("UI_themes_switcher","WP_AMP_THEMES_SWITCHER",{'DOMDoc':window.document}, window);
		});
	}
</script>

