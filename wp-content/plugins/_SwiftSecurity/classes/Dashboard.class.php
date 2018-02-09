<?php 

/**
 * Manage plugin's dashboard templates
 *
 */
class SwiftSecurityDashboard{
	
	/**
	 * Load the selected admin template
	 */
	public static function LoadTemplate(){
		//Get the settings
		SwiftSecurity::ClassInclude('Settings');
		$SettingsObject = new SwiftSecuritySettings();
		$settings = $SettingsObject->GetSettings();
		
		foreach ($SettingsObject->JSMessages as $key=>$value){
			$JSMessages[$key] = __($value, 'SwiftSecurity');
		}
		 
		
		//Get template based on GET[page] value
		switch($_GET['page']){
			case 'SwiftSecurity':
				if (isset($_GET['option']) && $_GET['option'] == 'nginx'){
					$template = 'nginx-rules';
				}
				else{
					$template = 'plugin-dashboard';
				}
				break;
			case 'SwiftSecurityGeneralSettings':
					$template = 'general-settings';
					break;
			case 'SwiftSecurityHideWP':
				$template = 'hide-wp-settings';
				break;
			case 'SwiftSecurityFirewall':
				if (isset($_GET['option']) && $_GET['option'] == 'Log'){
					//Show the logs template
					$template = 'firewall-logs';
				}
				else{
					//Show the main template
					$template = 'firewall-settings';
				}
				break;
			case 'SwiftSecurityCodeScanner':
				if (isset($_GET['option']) && $_GET['option'] == 'Settings'){
					//Show the settings template
					$template = 'wp-scanner-settings';
				}
				else{
					$template = 'wp-scanner-dashboard';
				}
				break;		
			case 'SwiftSecurityTroubleshooting':
				$template = 'troubleshooting';
				break;		
		}
		
		//Enqueue style
		wp_enqueue_style('swiftsecurity-style', SWIFTSECURITY_PLUGIN_URL .'/css/style.css');
		
		//Enqueue general scripts
		wp_enqueue_script('b64-script',  SWIFTSECURITY_PLUGIN_URL . '/js/Base64.js', array('jquery'));
		wp_enqueue_script('settings-script',  SWIFTSECURITY_PLUGIN_URL . '/js/Settings.js', array('jquery'));
		wp_enqueue_script('jquery-ui-sortable');
		wp_localize_script('settings-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'home_url' => home_url(), 'wp_nonce' => wp_create_nonce('swiftsecurity'), 'SwiftsecurityMessages' => json_encode($JSMessages)));

		//Enqueue nouislider.js for firewall 
		if ($template == 'firewall-settings'){
			wp_enqueue_script('nouislider-script',  SWIFTSECURITY_PLUGIN_URL . '/js/jquery.nouislider.all.min.js', array('jquery'));
			wp_enqueue_style('nouislider-style', SWIFTSECURITY_PLUGIN_URL . '/css/jquery.nouislider.css');
		}
		
		//Enqueue code scanner script
		if ($template == 'wp-scanner-dashboard'){
			wp_enqueue_script('scanner-script',  SWIFTSECURITY_PLUGIN_URL . '/js/WPScanner.js', array('jquery'));
			wp_localize_script('scanner-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'wp_nonce' => wp_create_nonce('swiftsecurity')));
		}
		
		include_once SWIFTSECURITY_PLUGIN_DIR . '/templates/'.$template.'.php';
	}
	
}

?>