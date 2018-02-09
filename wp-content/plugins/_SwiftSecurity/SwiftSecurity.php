<?php 
/**
 * Plugin Name: Swift Security
 * Plugin URI: http://swiftsecurity.swte.ch
 * Description: Hide the fact that you are using WordPress, enable a well-configurable, secure firewall, run scheduled code scans
 * Version: 1.4.2.15
 * Author: SWTE
 * Author URI: http://swte.ch
 **/
if (!class_exists('SwiftSecurity')):
class SwiftSecurity{

	/**
	 * Additional padding in htaccess for subdomain multisites
	 * @var unknown
	 */
	private $_multisite_padding;

	/**
	 * Htaccess rewrites and settings
	 * @var string
	 */
	private $_htaccess = array();

	/**
	 * htaccess flush counter
	 * value is 0 by default
	 * value is 1 if there is some misconfigurations
	 * @var integer
	*/
	private $_flush_htaccess_counter = 0;

	/**
	 * Wordpress version
	 * @var string
	 */
	private $_version;

	/**
	 * Nginx rewrites and settings
	 * @var string
	 */
	public $nginx = array();


	/**
	 * Create the SwiftSecurity object
	*/
	public function __construct(){
		//Set version
		$this->_version = get_bloginfo('version');

		//Set localization
		add_action( 'plugins_loaded', array($this, 'LoadTextDomain') );

		//Set the plugin-wide constants
		$this->SetConstants();

		//Set Environment info
		$this->environment = SwiftSecurity::CheckEnvironment();

		//Get active plugins
		$this->ActivePlugins = get_option('active_plugins',array());

		//Include general functions
		SwiftSecurity::FileInclude('functions');

		//Include Dashboard class
		SwiftSecurity::ClassInclude('Dashboard');

		//Add custom cron schedule recurrences
		add_filter('cron_schedules', array($this, 'CustomCronRecurrences'));

		//Create settings instance
		SwiftSecurity::ClassInclude('Settings');
		$this->SettingsObject = new SwiftSecuritySettings();

		//Get settings
		$this->settings = $this->SettingsObject->GetSettings();
		
		//Turn off modules on emergency
		if (defined('SWIFTSECURITY_EMERGENCY') && SWIFTSECURITY_EMERGENCY == true){
			$GLOBALS['SwiftSecurityMessage']['message'] = __('Swift Security Emergency mode is active!','SwiftSecurity');
			$GLOBALS['SwiftSecurityMessage']['type'] = 'update-nag';
			add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
			if ($this->settings['Modules']['Firewall'] == 'enabled' || $this->settings['Modules']['HideWP'] == 'enabled'){
				$this->settings['Modules']['Firewall'] = 'disabled';
				$this->settings['Modules']['HideWP'] = 'disabled';
				update_option('swiftsecurity_plugin_options', $this->settings);
				$this->FlushHtaccess();
			}
		}

		//Flush rewrites and save settings on switch theme
		add_action('switch_theme', array( $this, 'RefreshOptions'));

		//Disable assets management on theme switch
		add_action('switch_theme', array( $this, 'DisableAssetsManagement'));

		//Flush rewrites and save settings on plugin activation
		add_action('activated_plugin', array($this, 'RefreshOptions'));

		//Load modules
		$this->LoadModules();
		
		if (is_admin()) {
			//Hook for activate plugin
			register_activation_hook( __FILE__, array( $this, 'Activate' ) );
				
			//Hook for deactivate plugin
			register_deactivation_hook( __FILE__, array( $this, 'Deactivate' ) );

			//Create the admin menu
			add_action( 'admin_menu', array( $this, 'AdminMenu' ) );
			add_action( 'network_admin_menu', array( $this, 'AdminMenu' ) );
				
			//Plugin action links
			add_filter('plugin_action_links', array($this, 'ActionLinks'), 10, 2);
				
			//Set additional htaccess padding for multisites
			if (is_multisite()){
				$this->_multisite_padding = '-' . site_url();
			}
				
			//Send bug report
			if (isset($_POST['swiftsecurity_bug_report'])){
				//Change the mail from header
				$this->MailFrom = $_POST['reporter_email'];
				add_filter( 'wp_mail_from', array($this,'SetMailFrom'));

				//Send bug report
				add_action('init',array($this,'BugReport'));
			}
				
			//Plugin export/import settings
			if (isset($_POST['swift-security-exim'])){
				//Network wide settings
				if (is_network_admin()){
					foreach ((array)wp_get_sites() as $site){
						switch_to_blog($site['blog_id']);
						try {
							$this->SettingsObject->ExIm();
								
							//Get settings
							$this->settings = $this->SettingsObject->GetSettings();
							
							//Admin notice
							$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings imported successfully. Don`t forget to clear cache if you are using cache','SwiftSecurity');
							$GLOBALS['SwiftSecurityMessage']['type'] = 'updated';
							add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
						} catch (SwiftSecuritySettingsException $e) {
							//Admin notice on error
							$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
							$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
							add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
						}
						restore_current_blog();
					}
				}
				else{
					try {
						$this->SettingsObject->ExIm();
							
						//Get settings
						$this->settings = $this->SettingsObject->GetSettings();
	
						if ($this->environment['ServerSoftwareShort'] == 'nginx'){
							$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings imported successfully. Don`t forget to add Swift Security rules to your Nginx configuration file and reload Nginx','SwiftSecurity') . '<br><a href="admin.php?page=SwiftSecurity&option=nginx" target="_blank">'.__('Nginx rules','SwiftSecurity').'</a>';
							$GLOBALS['SwiftSecurityMessage']['type'] = 'update-nag';
						}
						else{
							$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings imported successfully. Don`t forget to clear cache if you are using cache','SwiftSecurity');
							$GLOBALS['SwiftSecurityMessage']['type'] = 'updated';
						}
						add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
					} catch (SwiftSecuritySettingsException $e) {
						$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
						$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
						add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
					}
				}
			}
				
			//Save settings modifications
			if (isset($_POST['swift-security-settings-save'])){
				//Network wide settings
				if (is_network_admin()){
					foreach ((array)wp_get_sites() as $site){
						switch_to_blog($site['blog_id']);
							try {
								//Save settings
								$settings = (isset($_POST['settings']) ? $_POST['settings'] : array());
								$this->SettingsObject->SaveSettings($settings);
								//Get settings
								$this->settings = $this->SettingsObject->GetSettings();

								//Admin notice
								$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings saved. Don`t forget to clear cache if you are using cache','SwiftSecurity');
								$GLOBALS['SwiftSecurityMessage']['type'] = 'updated';
								add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
							
							} catch (SwiftSecuritySettingsException $e) {
								//Admin notice on error
								$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
								$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
								add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
							}
							restore_current_blog();
					}
				}
				else{
					try {
						//Save settings
						$settings = (isset($_POST['settings']) ? $_POST['settings'] : array());
						$this->SettingsObject->SaveSettings($settings);
						//Get settings
						$this->settings = $this->SettingsObject->GetSettings();
							
						if ($this->environment['ServerSoftwareShort'] == 'nginx'){
							$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings saved. Don`t forget to add Swift Security rules to your Nginx configuration file and reload Nginx','SwiftSecurity') . '<br><a href="admin.php?page=SwiftSecurity&option=nginx" target="_blank">'.__('Nginx rules','SwiftSecurity').'</a>';
							$GLOBALS['SwiftSecurityMessage']['type'] = 'update-nag';
						}
						else{
							$GLOBALS['SwiftSecurityMessage']['message'] = __('Settings saved. Don`t forget to clear cache if you are using cache','SwiftSecurity');
							$GLOBALS['SwiftSecurityMessage']['type'] = 'updated';
						}
						add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
	
					} catch (SwiftSecuritySettingsException $e) {
						$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
						$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
						add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
					}
				}
			}
				
			//Checking nginx configuration for Hide WordPress and prompt message if they are missing
			if ($this->environment['ServerSoftwareShort'] == 'nginx'){
				if ($this->settings['Modules']['HideWP'] == 'enabled' ){
					$response = wp_remote_get(home_url($this->settings['HideWP']['redirectTheme'] . '/' . $this->settings['HideWP']['redirectThemeStyle']), array('sslverify' => false));
					if (!is_wp_error($response)){
						if (!preg_match('~(2|3)([0-9]){2}~',$response['response']['code'])){
							$this->settings['Modules']['HideWP'] = 'disabled';
							if (isset($this->HideWP)){
								$this->HideWP->Destroy();
							}
							$GLOBALS['SwiftSecurityMessage']['message'] = __('You have to add Swift Security rules to your Nginx configuration file and reload Nginx!','SwiftSecurity') . '<br><a href="admin.php?page=SwiftSecurity&option=nginx" target="_blank">'.__('Nginx rules','SwiftSecurity').'</a>';
							$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
							add_action('admin_notices', array('SwiftSecurity','AdminNotice'));

						}
					}
				}
			}

				
			//Cookie management, empty cache, rewrites and redirect after modify modules
			if ($this->SettingsObject->isModified){
				//Network wide settings
				if (is_network_admin()){
					foreach ((array)wp_get_sites() as $site){
						switch_to_blog($site['blog_id']);
						//Do the changes
						add_action('init', array($this, 'Modified'));
						
						//Get current blog's settings
						$this->settings = $this->SettingsObject->GetSettings();
						
						//Empty CDN cache
						$this->ClearCDNCache();
						
						//Empty htaccess and reload modules to regenerate htaccess
						$this->_htaccess = array();
						$this->LoadModules();
						
						//Update htaccess
						$this->FlushHtaccess();
						restore_current_blog();
					}
				}
				else{
					//Do the changes
					add_action('init', array($this, 'Modified'));
	
					//Empty CDN cache
					$this->ClearCDNCache();
	
					//Empty htaccess and reload modules to regenerate htaccess
					$this->_htaccess = array();
					$this->LoadModules();
	
					//Update htaccess
					$this->FlushHtaccess();
				}
			}
				
			//Set scheduled check CSS and JS modifications
			add_action('init', array($this,'SwiftSecurityScheduledChecks'));
				
			add_action('admin_notices', array('SwiftSecurity','ShowPermanentMessage'));
		}

		//If it is a firewall log/block event the firewall runs before WordPress
		if (isset($_GET['SwiftSecurity']) && $_GET['SwiftSecurity'] == 'firewall'){
			require_once(ABSPATH .'wp-includes/pluggable.php');
			$this->ParseRequest();
		}
		
		//Parse SwiftSecurtity requests
		add_action('init', array($this, 'ParseRequest'),11);

		//Don't change attachment urls in post editor
		add_action('init', array($this, 'RemoveAttachmentURLForAdmin'));

		//Set plugins order
		//add_action('wp',array($this,'SetPluginsOrder'),0);
		//add_action('shutdown',array($this,'SetPluginsOrder'),PHP_INT_MAX);

		//Load 3rd party plugin conflict patches
		$this->ThirdPartyCompatibility();
		
		//Regenerate htaccess if new sub site was added
		add_action('admin_footer',array($this, 'RefreshOptionsNewMS'));
		
		//Check updates
		if (isset($this->settings['GlobalSettings']['purchase-key'])){
			require SWIFTSECURITY_PLUGIN_DIR . '/helpers/plugin-update-checker/plugin-update-checker.php';
			$SwiftUpdateChecker = PucFactory::buildUpdateChecker(
					'http://api.swte.ch/info/SwiftSecurity/?purchase_key=' . $this->settings['GlobalSettings']['purchase-key'] . '&site=' . site_url(),
					__FILE__,
					'SwiftSecurityBundle'
			);
		}
		
		//Add purchase key to updater link
		add_filter('puc_request_info_result-SwiftSecurityBundle',array($this, 'AddPurchaseKey'));

		//Init smart cache management		
		add_action('plugins_loaded', array($this, 'SmartCacheManagement'));
		
	}	

	/**
	 * Load requested modules
	 */
	public function LoadModules(){
		//Empty htaccess rules for multisite batch actions
		$this->_htaccess = array();
		
		//Destroy Hide Wordpress if it is already loaded
		if (isset($this->HideWP)){
			$this->HideWP->Destroy();
			unset($this->HideWP);
		}
		
		////Initialize the Code Scanner
		SwiftSecurity::ClassInclude('WPScanner');
		if (class_exists('SwiftSecurityWPScanner')){
			//Create Scheduled Code Scanner hook
			add_action('SwiftSecurityStartScheduledScan', array( $this, 'ScheduledCodeScan'));
				
			//Create Code Scanner object
			$this->WPScanner = new SwiftSecurityWPScanner($this->settings, $this);
			$this->AddHtaccess($this->WPScanner);
		}
		

		////Initialize the Firewall module
		SwiftSecurity::ClassInclude('Firewall');
		if (class_exists('SwiftSecurityFirewall') && $this->settings['Modules']['Firewall'] == 'enabled'){
			$this->Firewall = new SwiftSecurityFirewall($this->settings);
			$this->AddHtaccess($this->Firewall);
		}
		
		//Initialize the Hide Wordpress module
		SwiftSecurity::ClassInclude('HideWP');
		if (class_exists('SwiftSecurityHideWP') && $this->settings['Modules']['HideWP'] == 'enabled'){
			//Create check cache hook
			add_action('SwiftSecurityCheckCache', array( $this, 'CheckCache'));
			
			//Create create cache hook
			add_action('SwiftSecurityCreateCache', array( $this, 'CreateCache'));

			//Create check htaccess hook
			add_action('SwiftSecurityCheckHtaccess', array( $this, 'CheckHtaccess'));
			
			//Create Hide WordPress object
			$this->HideWP = new SwiftSecurityHideWP($this->settings);
			$this->AddHtaccess($this->HideWP);
		}

		////Initialize the Troubleshooting
		SwiftSecurity::ClassInclude('Troubleshooting');
		if (class_exists('SwiftSecurityTroubleshooting')){
			$this->Troubleshooting = new SwiftSecurityTroubleshooting($this->settings);
		}
	}

	/**
	 * Set the plugin-wide constants
	 */
	public function SetConstants(){
		//Plugin directory
		if (!defined('SWIFTSECURITY_PLUGIN_DIR')){
			define('SWIFTSECURITY_PLUGIN_DIR', dirname(__FILE__));
		}

		//Plugin URL
		if (!defined('SWIFTSECURITY_PLUGIN_URL')){
			define('SWIFTSECURITY_PLUGIN_URL', plugins_url('', __FILE__ ));
		}

		//Version key
		if (!defined('SWIFTSECURITY_VERSION_KEY')){
			define('SWIFTSECURITY_VERSION_KEY', 'swiftsecurity_version');
		}

		//Version number
		if (!defined('SWIFTSECURITY_VERSION_NUM')){
			define('SWIFTSECURITY_VERSION_NUM', '1.4.2.15');
		}

		//Support e-mail
		if (!defined('SWIFTSECURITY_SUPPORT_EMAIL')){
			define('SWIFTSECURITY_SUPPORT_EMAIL', 'support@swte.ch');
		}

		//Prepare to renamed wp-content dir
		if (!defined('WP_CONTENT_DIRNAME')){
			define('WP_CONTENT_DIRNAME', basename(WP_CONTENT_DIR));
		}
	}

	/**
	 * Activate the plugin, add redirection rules to .htaccess
	 */
	public function Activate($network_wide) {
		//Check the .htaccess is writable
		SwiftSecurity::CompatibilityCheck('htaccess', true);

		//Check the compatibility for Hide Woddpress
		try {
			SwiftSecurity::CompatibilityCheck('HideWP');
		}
		catch(SwiftSecuritySettingsException $e){
			$this->settings['Modules']['HideWP'] = 'disabled';
		}
			
		//Turn off Hide WordPress and Firewall on activate if server software is nginx
		if ($this->environment['ServerSoftwareShort'] == 'nginx'){
			$this->settings['Modules']['HideWP'] = 'disabled';
			$this->settings['Modules']['Firewall'] = 'disabled';
		}

		//Network wide activation
		if ($network_wide){
			foreach ((array)wp_get_sites() as $site){
				switch_to_blog($site['blog_id']);
				//Get settings
				$this->settings = $this->SettingsObject->GetSettings();

				//Turn off modules for network wide activation
				$this->settings['Modules']['HideWP'] = 'disabled';
				$this->settings['Modules']['Firewall'] = 'disabled';

				//Remove SwiftSecurity settings from .htaccess
				$this->_multisite_padding = '-' . site_url();
				$this->RemoveHtaccess();

				//Save the settings
				update_option('swiftsecurity_plugin_options', $this->settings);

				//Set the version
				update_option(SWIFTSECURITY_VERSION_KEY, SWIFTSECURITY_VERSION_NUM);

				restore_current_blog();
			}
			//Get the current blog's settings
			$this->settings = $this->SettingsObject->GetSettings();
		}
		else{
			//Save the settings
			update_option('swiftsecurity_plugin_options', $this->settings);
			//Set the version
			update_option(SWIFTSECURITY_VERSION_KEY, SWIFTSECURITY_VERSION_NUM);
		}

		//Do the changes
		$this->Modified();

		//Flush .htaccess settings
		$this->FlushHtaccess();

	}

	/**
	 * Deactivate the plugin, and calls RemoveHtaccess to remove .htaccess modifications
	 */
	public function Deactivate($network_wide) {
		//Check the .htaccess is writable
		SwiftSecurity::CompatibilityCheck('htaccess', true);

		//Network wide deactivation
		if ($network_wide){
			foreach ((array)wp_get_sites() as $site){
				switch_to_blog($site['blog_id']);

				/*
				 * Change permalinks to default
				*/
				global $wp_rewrite;
				//Change category base
				$wp_rewrite->set_category_base('category');
				//Change tag base
				$wp_rewrite->set_tag_base('tag');
				//Flush rules
				delete_option( 'rewrite_rules' );
				
				//Get settings
				$this->settings = $this->SettingsObject->GetSettings();
				
				//Turn off modules for network wide activation
				$this->settings['Modules']['HideWP'] = 'disabled';
				$this->settings['Modules']['Firewall'] = 'disabled';
				
				//Remove SwiftSecurity settings from .htaccess
				$this->_multisite_padding = '-' . site_url();
				$this->RemoveHtaccess();
				
				//Save the settings
				update_option('swiftsecurity_plugin_options', $this->settings);

				//Remove options
				delete_option('swiftsecurity_cdn_cache');
				delete_option('swiftsecurity_log');
				delete_option('swiftsecurity_dismissable_messages');
				delete_option('swiftsecurity_wpscan');

				restore_current_blog();
			}
				
		}
		//Single deactivation
		else{
			//Change permalinks to default and destroy Hide Wordpress module
			if (isset($this->HideWP)){
				$this->HideWP->DefaultPermalinks();
				$this->HideWP->Destroy();
			}

			//Remove SwiftSecurity settings from .htaccess
			$this->RemoveHtaccess();

			//Remove options
			delete_option('swiftsecurity_cdn_cache');
			delete_option('swiftsecurity_log');
			delete_option('swiftsecurity_dismissable_messages');
			delete_option('swiftsecurity_wpscan');
				
		}
	}

	/**
	 * Run if settings changed, and at plugin activation
	 */
	public function Modified(){
		if ($this->settings['Modules']['HideWP'] == 'enabled'){
			$this->HideWP->ResetSqCookie();
			$this->HideWP->CreateSqCookie();

			//Change permalinks
			$this->HideWP->ChangePermalinks();
		}
		else{
			//Change permalinks to default
			if (!isset($this->HideWP)){
				SwiftSecurity::ClassInclude('HideWP');
				$this->HideWP = new SwiftSecurityHideWP($this->settings);
			}
			if ($this->environment['ServerSoftwareShort'] == 'apache'){
				$this->HideWP->DefaultPermalinks();
				$this->HideWP->Destroy();
			}
		}

		//If admin_url changed redirect to Hide Wordpress settings (under new admin URL)
		if ($this->SettingsObject->isAdminURLModified){
			wp_redirect(admin_url('admin.php?page=SwiftSecurityHideWP'));die;
		}
	}

	/**
	 * Modify the plugin-wide htaccess settings
	 * @param SwiftSecurityModul $modulObject
	 */
	public function AddHtaccess($modulObject){
		$startPadding = '###SwSc/'.$modulObject->moduleName.'###'.PHP_EOL;
		$endPadding = PHP_EOL.'###END SwSc/'.$modulObject->moduleName.'###'.PHP_EOL;
		$this->_htaccess[$modulObject->moduleName] =  $startPadding . $modulObject->GetHtaccess() . $endPadding;
	}


	/**
	 * Flush htaccess rewrite rules
	 */
	public function FlushHtaccess(){
		//Break unecessary calls
		if ((is_multisite() && empty($this->_multisite_padding)) || (defined('SWIFTSECURITY_NETWORK_ONLY') && get_current_blog_id() != 1)){
			return;
		}
		
		//Set multisite padding
		$this->_multisite_padding = (is_multisite() ? '-' . site_url() : '');
		
		//Clear all supported 3rd party cache
		if (isset($this->HideWP)){			
			$this->HideWP->PurgeThirdPartyCaches();
		}
		
		if ($this->environment['ServerSoftwareShort'] == 'apache'){
			$this->RemoveHtaccess();
			$htaccess = file_get_contents(ABSPATH . '.htaccess');
			
			//Change WP paths in htaccess
			if (isset($this->HideWP) && $this->settings['Modules']['HideWP'] == 'enabled'){
				$htaccess = $this->HideWP->ModifyThirdPartyHtaccess($htaccess);
			}
			
			$htaccess ='######BEGIN SwiftSecurity'.$this->_multisite_padding.'######'.PHP_EOL.implode(PHP_EOL, (array)$this->_htaccess).PHP_EOL.'######END SwiftSecurity'. $this->_multisite_padding.'######' . PHP_EOL . PHP_EOL . $htaccess;
				
			//Force set htaccess to 0644 if it is 0444 - Hostgator fix
			if (substr(sprintf('%o', fileperms(ABSPATH . '.htaccess')), -4) == '0444'){
				chmod(ABSPATH . '.htaccess', 0644);
			}
				
			//Write htaccess
			file_put_contents(ABSPATH . '.htaccess', $htaccess);
			$this->_flush_htaccess_counter++;
				
			//Check htaccess is working
			if ((isset($_POST['swift-security-exim']) || isset($_POST['swift-security-settings-save'])) && (isset($this->settings['GlobalSettings']['safe-activation']) && $this->settings['GlobalSettings']['safe-activation'] == 'enabled')){
				$response = wp_remote_get(home_url(), array('timeout' => 60, 'sslverify' => false, 'user-agent'=> 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1'));
				if (is_wp_error($response)){
					$this->RevertSettings('900 (' . $response->get_error_message() .')');
				}
				else if (!preg_match('~(2|3)([0-9]){2}~',$response['response']['code'])){
					$this->RevertSettings($response['response']['code']);
				}
				else if(preg_match('~wp-(content|admin)~',$response['body']) && $this->settings['Modules']['HideWP'] == 'enabled' && (!defined('SWIFTSECURITY_ENABLE_WP_CONTENT') || SWIFTSECURITY_ENABLE_WP_CONTENT !== true)){
					SwiftSecurity::SetPermanentMessage('AutomatedTesting', __('Some hardcoded WordPress specific string has been detected. Please run ','SwiftSecurity') . '<a href="'.admin_url('admin.php').'?page=SwiftSecurityTroubleshooting">' .__('Automated testing', 'SwiftSecurity') . '</a>', 'warning');
				}
				else {
					SwiftSecurity::RemovePermanentMessage('AutomatedTesting');
				}
			}
				
			//WP Engine check
			if ($this->environment['ManagedHosting'] == 'WPEngine'){
				if ($this->settings['Modules']['HideWP'] == 'enabled' ){
					$response = wp_remote_get(home_url($this->settings['HideWP']['redirectTheme'] . '/' . $this->settings['HideWP']['redirectThemeStyle']), array('sslverify' => false));
					if (is_wp_error($response)){
						$this->RevertSettings('900 (' . $response->get_error_message() .')');
					}
					else if (!preg_match('~(2|3)([0-9]){2}~',$response['response']['code'])){
						$WPEngineRedirect  = 'Source: <input class="ss-hidden-input autoselect" value="(.*)\.(css|js|html?|txt|jpe?g|gif|png|ico|eot|woff2?|ttf|svg|mp4|mp3)$"><br>';
						$WPEngineRedirect .= 'Destination: <input class="ss-hidden-input autoselect" value="$1.$2.wpengine"><br>';
						$WPEngineRedirect .= 'Rewrite type (Advanced Settings):  <input class="ss-hidden-input" value="break">';
						SwiftSecurity::SetPermanentMessage('WPEngineRedirect', __('Before you activate Hide WordPress please set the following redirect rule on your WPEngine dashboard:','SwiftSecurity') . '<br>' . $WPEngineRedirect, 'error');

						//Turn off Hide WordPress
						$this->settings['Modules']['HideWP'] = 'disabled';
						update_option('swiftsecurity_plugin_options', $this->settings);

						if ($this->_flush_htaccess_counter < 2){
							$this->_htaccess = array();
							$this->LoadModules();
							$this->FlushHtaccess();
						}
					}
					else{
						SwiftSecurity::RemovePermanentMessage('WPEngineRedirect');
					}
				}
			}
		}
	}

	/**
	 * Remove the plugin redirection rules from .htaccess
	 */
	public function RemoveHtaccess() {
		$htaccess = '';
		if (file_exists(ABSPATH . '.htaccess')){
			$htaccess = file_get_contents(ABSPATH . '.htaccess');
		}
		$htaccess = preg_replace("~######BEGIN SwiftSecurity".$this->_multisite_padding."######"."(.*)######END SwiftSecurity".$this->_multisite_padding."######"."(\s*)?~s",'',$htaccess);
		
		/*
		 * Remove START_SWIFT_MODIFIED_RULE
		* @see SwiftSecurityHideWp::ModifyThirdPartyHtaccess()
		*/
		
		$htaccess = preg_replace("~###START_SWIFT_RULE_BACKUP".$this->_multisite_padding."###~",'', $htaccess);
		$htaccess = preg_replace("~\n###START_SWIFT_MODIFIED_RULE".$this->_multisite_padding."###((?!###END_SWIFT_MODIFIED_RULE".$this->_multisite_padding."###).)*###END_SWIFT_MODIFIED_RULE".$this->_multisite_padding."###\n~s",'', $htaccess);
		
		//Save cleaned htaccess
		file_put_contents(ABSPATH . '.htaccess', $htaccess);
	}

	/**
	 * Turn off the modules and remove Swift Security lines from htaccess
	 */
	public function RevertSettings($code){
		if ($this->_flush_htaccess_counter > 0){
			//Turn off modules
			$this->settings['Modules']['HideWP'] = 'disabled';
			$this->settings['Modules']['Firewall'] = 'disabled';

			//Update settings
			update_option('swiftsecurity_plugin_options', $this->settings);
			$this->settings = $this->SettingsObject->GetSettings();
		}

		//Empty htaccess and reload modules to regenerate htaccess
		$this->_htaccess = array();
		$this->LoadModules();

		//Update htaccess
		if ($this->_flush_htaccess_counter < 2){
			$this->FlushHtaccess();
		}

		$GLOBALS['SwiftSecurityMessage']['message'] = __('Error! There is some misconfiguration in the settings. Please check the settings and try again. If the problem persists, please contact us: ' . SWIFTSECURITY_SUPPORT_EMAIL . '. Error code: ', 'SwiftSecurity') . $code;
		$GLOBALS['SwiftSecurityMessage']['type'] = 'error';
		add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
	}

	/**
	 * Returns nginx rules for module
	 * @param string $moduleName
	 * @return string
	 */
	public function GetNginxRules ($moduleName){
		if (!isset($this->$moduleName)){
			SwiftSecurity::ClassInclude($moduleName);
			$moduleClassName = 'SwiftSecurity' . $moduleName;
			$this->$moduleName = new $moduleClassName($this->settings);
		}
		$startPadding = '###SwiftSecurity/'.$this->$moduleName->moduleName.'###'.PHP_EOL;
		$endPadding = PHP_EOL.'###END SwiftSecurity/'.$this->$moduleName->moduleName.'###'.PHP_EOL;
		return $startPadding . $this->$moduleName->GetNginxRules() . $endPadding;
	}

	/**
	 * Regenerate and reflush htaccess after theme switch
	 */
	public function RefreshOptions(){
		//Clear cache
		$this->ClearCDNCache();
		
		//Get settings
		$this->settings = $this->SettingsObject->GetSettings();

		//Empty htaccess and reload modules to regenerate htaccess
		$this->_htaccess = array();
		$this->LoadModules();
		
		//Update htaccess
		$this->FlushHtaccess();

		//Update options
		update_option('swiftsecurity_plugin_options', $this->settings);
	}
	
	/**
	 * Flush htaccess after new site was added
	 */
	public function RefreshOptionsNewMS(){
		global $pagenow;
		if ($pagenow == 'site-new.php'){
			foreach ((array)wp_get_sites() as $site){
				switch_to_blog($site['blog_id']);
				$this->settings = $this->SettingsObject->GetSettings();
				
				//Turn off modules for the new site
				if (isset($_GET['id']) && $site['blog_id'] == $_GET['id'] && !file_exists(SWIFTSECURITY_PLUGIN_DIR . '/default.json') && !defined('SWIFTSECURITY_NETWORK_ONLY') && apply_filters('swiftsecurity_disable_modules_on_multisite_activation',true)){
					$this->settings['Modules']['HideWP'] = 'disabled';
					$this->settings['Modules']['Firewall'] = 'disabled';
				}
				update_option('swiftsecurity_plugin_options', $this->settings);
				
				$this->LoadModules();
				$this->FlushHtaccess();
				restore_current_blog();
			}
			//Get the current blog's settings
			$this->settings = $this->SettingsObject->GetSettings();
		}
	}
	
	/**
	 * Disable manage JS and CSS combine on theme switch
	 */
	public function DisableAssetsManagement(){
		//Clear cache
		$this->ClearCDNCache();
		
		//Get settings
		$this->settings = $this->SettingsObject->GetSettings();
				
		if(isset($this->settings['HideWP']['manageJS'])){
			$this->settings['HideWP']['manageJS'] = 'disabled';
		}
		if(isset($this->settings['HideWP']['combineCSS'])){
			$this->settings['HideWP']['combineCSS'] = 'disabled';
		}
				
		//Update options
		update_option('swiftsecurity_plugin_options', $this->settings);
	}
	
	/**
	 * Empty cache when known cache plugins cache was cleared
	 * @todo Clear swfit cache in admin bar
	 */
	public function SmartCacheManagement(){
		//WP Supercache
		if (function_exists('wp_supercache_cache_for_admins')){
			add_action('init', array($this, 'ClearCDNCache_WPSupercache'));
		}
		else if (function_exists('rocket_init')){
			add_action('admin_post_purge_cache', array($this, 'ClearCDNCache_WPRocket'));
		}
		else if (function_exists('w3_instance')){
			add_action('w3tc_flush_all', array($this, 'ClearCDNCache_W3TC'));
			add_action('w3tc_flush', array($this, 'ClearCDNCache_W3TC'));
	
			add_actions(array(
			'w3tc_flush_all',
			'w3tc_flush',
			'w3tc_flush_url',
			'w3tc_flush_post',
			'w3tc_pgcache_flush',
			'w3tc_cdn_purge_files',
			'w3tc_browsercache_flush',
			'w3tc_minifycache_flush',
			'w3tc_dbcache_flush',
			'w3tc_objectcache_flush',
			'w3tc_fragmentcache_flush',
			), array($this, 'ClearCDNCache_W3TC'));
	
		}
	}
	
	/**
	 * Clear bypassed CSS and JS files from Swift Security cache if WP Supercache's cache cleared
	 */
	public function ClearCDNCache_WPSupercache(){
		$wp_supercache_valid_nonce = isset($_REQUEST['_wpnonce']) ? wp_verify_nonce($_REQUEST['_wpnonce'], 'wp-cache') : false;
		if (isset($_REQUEST['wp_delete_cache']) && $wp_supercache_valid_nonce){
			$this->ClearCDNCache();
		}
	}
	
	/**
	 * Clear bypassed CSS and JS files from Swift Security cache if WP Rocket's cache cleared
	 */
	public function ClearCDNCache_WPRocket(){
		$this->ClearCDNCache();
	}
	
	/**
	 * Clear bypassed CSS and JS files from Swift Security cache if W3TC's cache cleared
	 */
	public function ClearCDNCache_W3TC(){
		$this->ClearCDNCache();
	}

	/**
	 * Show error messages
	 * @param string $message
	 * @param integer $errno
	 */
	public static function ShowError($message, $errno) {
		if(isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
			echo '<strong>' . $message . '</strong>';
			exit;
		} else {
			trigger_error($message, $errno);
		}

	}

	/**
	 * Process plugin requests
	 */
	public function ParseRequest() {
		if (isset($_GET['SwiftSecurity']) && isset($_GET['sq_' . md5($this->settings['GlobalSettings']['sq'])]) || (isset($_REQUEST['wp-nonce']) && wp_verify_nonce( $_REQUEST['wp-nonce'], 'swiftsecurity' ))){
			@SwiftSecurity::FileInclude($_GET['SwiftSecurity']);
		}
	}

	/**
	 * Create the admin setting page
	 */
	public function AdminMenu() {
		//Hide settings on subpages in NETWORK ONLY mode
		if (defined('SWIFTSECURITY_NETWORK_ONLY') && !is_network_admin()){
			return ;
		}
		
		//Add menu as sub-menu under Settings
		if (isset($this->settings['GlobalSettings']['hide-menu']) && $this->settings['GlobalSettings']['hide-menu'] == 'enabled'){
			add_submenu_page( (is_network_admin() ? 'settings.php' : 'options-general.php'), 'Dashboard', 'Swift Security', 'manage_options', 'SwiftSecurity',  array('SwiftSecurityDashboard', 'LoadTemplate'));
		}
		//Add menu as root-level menu
		else{
			//Create admin menu
			add_menu_page( 'Swift Security Options', 'Swift Security', 'manage_options', 'SwiftSecurity', array('SwiftSecurityDashboard', 'LoadTemplate'),  plugins_url('images/icon.png', __FILE__));
		}
		//Dashboard
		add_submenu_page( 'SwiftSecurity', 'Dashboard', 'Dashboard', 'manage_options', 'SwiftSecurity',  array('SwiftSecurityDashboard', 'LoadTemplate'));

		//Create HideWordpress submenu
		if (class_exists('SwiftSecurityHideWP')){
			add_submenu_page( 'SwiftSecurity', 'Hide WordPress', 'Hide WordPress', 'manage_options', 'SwiftSecurityHideWP',  array('SwiftSecurityDashboard', 'LoadTemplate'));
		}

		//Create Firewall submenu
		if (class_exists('SwiftSecurityFirewall')){
			add_submenu_page( 'SwiftSecurity', 'Firewall', 'Firewall', 'manage_options', 'SwiftSecurityFirewall',  array('SwiftSecurityDashboard', 'LoadTemplate'));
		}

		//Create CodeScanner submenu

		if (class_exists('SwiftSecurityWPScanner')){
			add_submenu_page( 'SwiftSecurity', 'Code Scanner', 'Code Scanner', 'manage_options', 'SwiftSecurityCodeScanner',  array('SwiftSecurityDashboard', 'LoadTemplate'));
		}

		//Create General Settings submenu
		add_submenu_page( 'SwiftSecurity', 'General Settings', 'General Settings', 'manage_options', 'SwiftSecurityGeneralSettings',  array('SwiftSecurityDashboard', 'LoadTemplate'));

		//Create Troubleshoot submenu
		if (class_exists('SwiftSecurityTroubleshooting') && !is_network_admin()){
			add_submenu_page( 'SwiftSecurity', 'Troubleshooting', 'Troubleshooting', 'manage_options', 'SwiftSecurityTroubleshooting',  array('SwiftSecurityDashboard', 'LoadTemplate'));
		}
	}

	/**
	 * Action links for plugin settings page
	 * @param string links
	 * @param string $file
	 * @return string
	 */
	public function ActionLinks($links, $file) {
		$this_plugin = plugin_basename(__FILE__);

		if ($file == $this_plugin) {
			$settings_link = '<a href="' . admin_url('admin.php?page=SwiftSecurity') . '">'.__('Settings','SwiftSecurity').'</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Start scheduled code scanning
	 */
	public function ScheduledCodeScan(){
		$this->WPScanner->Scan(true, true);
	}

	/**
	 * Add weekly, monthly and 5 minute recurrances for wp_cron
	 * @param array $schedules
	 * @return array
	 */
	public function CustomCronRecurrences($schedules) {
		$schedules['5min'] = array(
				'interval' => 300,
				'display' => '5min'
		);

		$schedules['weekly'] = array(
				'interval' => 604800,
				'display' => 'weekly'
		);

		$schedules['monthly'] = array(
				'interval' => 2592000,
				'display' => 'monthly'
		);
		return $schedules;
	}

	/**
	 * Remove attachment url replacing for admin
	 */
	public function RemoveAttachmentURLForAdmin(){
		if (is_admin() && isset($this->HideWP)){
			remove_filter('wp_get_attachment_url', array($this->HideWP,'ReplaceString'));
		}
	}

	/**
	 * Localization
	 */
	public function LoadTextDomain(){
		load_plugin_textdomain( 'SwiftSecurity', false, dirname(plugin_basename( __FILE__ )) . '/languages' );
	}

	/**
	 * Clear cached CSS and JS files
	 * @param boolean|string $file
	 */
	public function ClearCDNCache($file = false){
		if (isset($this->settings['HideWP']['combineCSS']['name']) && '/'.$this->settings['HideWP']['combineCSS']['name'] == preg_replace('~-([0-9abcdef]{8})~','',$file) ||
			isset($this->settings['HideWP']['combineHeaderJS']['name']) && '/'.$this->settings['HideWP']['combineHeaderJS']['name'] == preg_replace('~-([0-9abcdef]{8})~','',$file) ||
			isset($this->settings['HideWP']['combineFooterJS']['name']) && '/'.$this->settings['HideWP']['combineFooterJS']['name'] == preg_replace('~-([0-9abcdef]{8})~','',$file) ||
			!file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache/')
		){
			return;
		}
		if ($file === false){
			$files = array_diff(scandir(SWIFTSECURITY_PLUGIN_DIR . '/cache/'), array('.','..'));
			foreach ($files as $file) {
				(is_dir(SWIFTSECURITY_PLUGIN_DIR . '/cache/'.$file)) ? recursive_rmdir(SWIFTSECURITY_PLUGIN_DIR . '/cache/'.$file) : unlink(SWIFTSECURITY_PLUGIN_DIR . '/cache/'.$file);
			}
			update_option('swiftsecurity_cdn_cache',time());
			wp_schedule_single_event(time(), 'SwiftSecurityCreateCache');
		}
		else if (file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache'.$file)){
			unlink(SWIFTSECURITY_PLUGIN_DIR . '/cache'.$file);
		}
	}

	/**
	 * Create cached CSS and JS files
	 * @return boolean|void;
	 */
	public function CreateCache(){
		//Return if Hide WordPress is not enabled
		if (!isset($this->HideWP)){
			return false;
		}

		SwiftSecurity::ClassInclude('CSSMinifier');
		SwiftSecurity::ClassInclude('JSMinifier');
		$home_content = wp_remote_post(home_url(), array('sslverify' => false));
		if (!is_wp_error($home_content)){
			preg_match_all('~//(.*)\.(css|js)~',$home_content['body'],$urls);
			foreach ($urls[0] as $url){
				wp_remote_post((isset($_SERVER['HTTPS']) ? 'https' : 'http') .':'. $url, array('sslverify' => false));
			}
		}
	}

	/**
	 * Check cached CSS and JS files and clear cache if there are any modifications
	 */
	public function CheckCache(){
		return;
		$LastCached 		= get_option('swiftsecurity_cdn_cache');
		$CachedFiles 		= new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABSPATH), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD), '~\.(js|css)~i', RecursiveRegexIterator::GET_MATCH);
		foreach ($CachedFiles as $name => $object){
			if (file_exists($name) && filectime($name) > $LastCached &&  !preg_match('~^'.SWIFTSECURITY_PLUGIN_DIR . '/cache~', pathinfo($name,PATHINFO_DIRNAME))){
				$this->ClearCDNCache($name);
			}
		}
	}
	
	/**
	 * Check htaccess and reflush them if it contains "wp-content" strings
	 */
	public function CheckHtaccess(){
		if (is_writable(ABSPATH . '.htaccess') && preg_match('~([\t ]*)?RewriteRule(.*)wp-content(.*)~',file_get_contents(ABSPATH . '.htaccess'))){
			$this->FlushHtaccess();
		}
	}

	/**
	 * Set scheduled check JS and CSS files
	 */
	public function SwiftSecurityScheduledChecks(){
		if (!wp_next_scheduled('SwiftSecurityCheckCache')) {
			wp_schedule_event( time(), '5min', 'SwiftSecurityCheckCache');
		}
		
		if (!wp_next_scheduled('SwiftSecurityCheckHtaccess')) {
			wp_schedule_event( time(), '5min', 'SwiftSecurityCheckHtaccess');
		}
	}

	/**
	 * Patches and overwrites to fix compatibility issues with other plugins and themes
	 */
	public function ThirdPartyCompatibility(){
		//Load 1.1.6 Aqua resizer for Secondtouch theme
		if (get_template() == 'secondtouch'){
			SwiftSecurity::FixInclude('aq_resizer_1.1.6');
		}
		
		// WP External Links fix
		if (isset($_GET['SwiftSecurity']) && $_GET['SwiftSecurity'] == 'cdnproxy'){
			if (!defined('WP_ADMIN')){
				define('WP_ADMIN', true);
			}
		}

		//Aqua Resizer
		SwiftSecurity::FixInclude('aq_resizer');
		
		//Bfi thumb
		SwiftSecurity::FixInclude('bfi_thumb');
		
		//Live Composer
		SwiftSecurity::FixInclude('live_composer');

		//Load modified get_resized_image function if Coworker theme in use
		if (get_template() == 'coworker'){
			SwiftSecurity::FixInclude('coworker');
		}
		
		//TheStyle thumbnail resizer
		if (get_template() == 'TheStyle'){
			SwiftSecurity::FixInclude('thestyle');
		}
		
		//Nielsen 404 infinite loop, image resizer
		if (get_template() == 'nielsen'){
			if (isset($this->HideWP)){
				SwiftSecurity::FixInclude('nielsen');
			}
		}
		
		//Total image resizer
		if (get_template() == 'Total'){
			if (isset($this->HideWP)){
				SwiftSecurity::FixInclude('Total');
			}
		}
		
		// Buddypress media
		add_filter('before_rtmedia_uploader_display', '__return_true');
	}

	/**
	 * Set Swift Security to load first
	 */
	public function SetPluginsOrder(){
		global $pagenow;
		// Don't set order if current action is deactivate
		if ($pagenow == 'plugins.php'){
			return;
		}
		
		//Force to load Swift Security before all other plugins
		$plugin_path = basename(dirname(__FILE__)) . "/SwiftSecurity.php";
		$key = array_search($plugin_path, $this->ActivePlugins);
		if ($key > 0) {
			array_splice($this->ActivePlugins, $key, 1);
			array_unshift($this->ActivePlugins, $plugin_path);
			update_option('active_plugins', $this->ActivePlugins);
		}
	}

	/**
	 * Creates and send bug report to SWIFTSECURITY_SUPPORT_EMAIL
	 */
	public function BugReport(){
		//Set default values
		$BugReport = array('plugins' => 'not provided',
				'theme' => 'not provided',
				'child_theme'=> 'not provided'
		);
		$phpinfo = 'not provided';
			
		//If user send system data
		if ($_POST['include_system_data'] == 'enabled'){
			$BugReport['plugins'] = print_r($this->ActivePlugins, true);
			$BugReport['theme'] = get_template();
			$BugReport['child_theme'] = get_stylesheet();

			//Get php info
			ob_start();
			phpinfo();
			$phpinfo = ob_get_clean();
			preg_match('~<body>(.*)</body>~s', $phpinfo, $x);
			$phpinfo = $x[1];
		}

		//Plugin settings
		$BugReport['settings'] = $this->settings;
		//Firewall log
		$BugReport['security_log'] = get_option('swiftsecurity_log');

		//Create the mail from template
		ob_start();
		include (SWIFTSECURITY_PLUGIN_DIR . '/templates/mail/bug-report.php');
		$message = ob_get_clean();
		//Send bug report
		SwiftSecurity::SendEmailNotification(SWIFTSECURITY_SUPPORT_EMAIL, 'Bug report - ' . home_url(), $message);
		$GLOBALS['SwiftSecurityMessage']['message'] = __('Bug report sent','SwiftSecurity');
		$GLOBALS['SwiftSecurityMessage']['type'] = 'updated';
		add_action('admin_notices', array('SwiftSecurity','AdminNotice'));
	}

	/**
	 * Add purchase key for plugin info
	 * @param array $info
	 */
	public function AddPurchaseKey($info){
		$info->download_url = str_replace('[[PARAMETERS]]', '?purchase_key=' . $this->settings['GlobalSettings']['purchase-key'] . '&site=' . site_url(), $info->download_url);
		return $info;
	}

	/**
	 * Set mail from for wp_mail()
	 * @return string
	 */
	public function SetMailFrom(){
		return $this->MailFrom;
	}

	/**
	 * Send e-mail notifications
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 */
	public static function SendEmailNotification($to, $subject, $message){
		//Attach logo image
		add_action('phpmailer_init', array('SwiftSecurity', 'AddEmbeddedImage'));

		//Set wp_mail to send HTML e-mails
		add_filter( 'wp_mail_content_type', array('SwiftSecurity','SetMailContentType'));

		wp_mail($to, $subject, $message);
		
		//Remove logo image and content type settings after mail sent
		remove_action('phpmailer_init', array('SwiftSecurity', 'AddEmbeddedImage'));
		remove_filter( 'wp_mail_content_type', array('SwiftSecurity','SetMailContentType'));
	}

	/**
	 * Add embedded image to email notifications
	 */
	public static function AddEmbeddedImage(){
		global $phpmailer;
		if (is_object($phpmailer)){
			$phpmailer->SMTPKeepAlive = true;
			$phpmailer->AddEmbeddedImage(SWIFTSECURITY_PLUGIN_DIR . '/images/mail-logo.png', 'swiftsecurity-logo', 'mail-logo.png');
		}
	}

	/**
	 * Set content-type to text/html for email notifications
	 * @param string $content_type
	 * @return string
	 */
	public static function SetMailContentType($content_type = ''){
		return 'text/html';
	}

	/**
	 * Send pushover notifications
	 * @param string $Token
	 * @param string $User
	 * @param string $Sound
	 * @param string $Title
	 * @param string $Message
	 */
	public static function SendPushNotification($Token, $User, $Message, $Sound = 'pushover', $Title = 'Swift Security'){
		wp_remote_post('https://api.pushover.net/1/messages.json', array(
		'body'=> array(
		'token' => $Token,
		'user' => $User,
		'title' => 'Swift Security - ' . $Title,
		'sound' => $Sound,
		'message' => $Message
		)
		));
	}

	/**
	 * Include files from include path
	 * @param string $inc included file name without .inc.php
	 */
	public static function FileInclude($inc){
		$inc = basename($inc);
		if (file_exists(SWIFTSECURITY_PLUGIN_DIR . '/includes/' . $inc . '.inc.php')){
			include_once SWIFTSECURITY_PLUGIN_DIR . '/includes/' . $inc . '.inc.php';
		}
	}

	/**
	 * Include class files from classes path
	 * @param string $class included file name without .class.php
	 */
	public static function ClassInclude($class){
		if (file_exists(SWIFTSECURITY_PLUGIN_DIR . '/classes/' . $class . '.class.php')){
			include_once SWIFTSECURITY_PLUGIN_DIR . '/classes/' . $class . '.class.php';
		}
	}


	/**
	 * Include overwrites to fix compatibility issues with other plugins and themes
	 * @param string $fix included file name without .fix.php
	 */
	public static function FixInclude($fix){
		if (file_exists(SWIFTSECURITY_PLUGIN_DIR . '/compatibility/' . $fix . '.fix.php')){
			include_once SWIFTSECURITY_PLUGIN_DIR . '/compatibility/' . $fix . '.fix.php';
		}
	}


	/**
	 * Show custom admin notices
	 */
	public static function AdminNotice(){
		if (current_user_can('manage_options')){
			echo '<div class="'.$GLOBALS['SwiftSecurityMessage']['type'].'" style="padding: 11px 15px;">'.$GLOBALS['SwiftSecurityMessage']['message'].'</div>';
		}
	}

	/**
	 * Compatibility checks
	 * @param string $module
	 * @param boolean $hard
	 * @return boolean
	 */
	public static function CompatibilityCheck($module = '', $hard = false){
		$compatible = true;
		$environment = SwiftSecurity::CheckEnvironment();
			
		//Check Hide Wordpress compatibility
		if ($module == 'HideWP'){
			//This version is working with Apache or nginx only
			if ($environment['ServerSoftwareShort'] != 'apache' && $environment['ServerSoftwareShort'] != 'nginx'){
				$message = __('The Hide Wordpress module working only with Apache webserver or Nginx.', 'SwiftSecurity') . ' ('.__('current software is', 'SwiftSecurity').' '.$environment['ServerSoftware'].')';
				$compatible = false;
			}
		}

		//Check Firewall compatibility
		if ($module == 'Firewall'){
			//Check CURL extension
			if (!function_exists('curl_version')){
				$message = __('CURL extension is not enabled. You can\'t use the POST filtering' , 'SwiftSecurity');
				$compatible = false;
			}
		}

		//Check Firewall compatibility
		if ($module == 'CodeScanner'){
			//Checl safe mode
			if (ini_get('safe_mode')){
				$message = __('Can\'t run scheduled scans in PHP safe mode' , 'SwiftSecurity');
				$compatible = false;
			}
		}

		//Check is htaccess writable or not
		if ($module == 'htaccess'){
			//Skip check if system software is not apache
			if ($environment['ServerSoftwareShort'] == 'apache'){
				if ((file_exists(ABSPATH . '.htaccess') && !is_writable(ABSPATH . '.htaccess')) || (!file_exists(ABSPATH . '.htaccess') && !is_writable(ABSPATH))){
					$message = __('The '.ABSPATH.'.htaccess file is not writable for WordPress. Please change the permissions.','SwiftSecurity');
					$compatible = false;
				}
			}
		}

		if ($compatible == false){
			if ($hard == false){
				throw new SwiftSecuritySettingsException(array(
						'message' => $message
				));
			}
			else{
				SwiftSecurity::ShowError($message,E_USER_ERROR);
			}
		}

		return $compatible;
	}

	/**
	 * Set authentication cookie
	 * @param string $auth_cookie
	 * @param string $expire
	 * @param string $expiration
	 * @param string $user_id
	 * @param string $scheme
	 */
	public static function SwiftSecuritySetAuthCookie($auth_cookie, $expire = 0, $expiration = 0 , $user_id = '', $scheme = ''){
		//Check cookie is already set to prevent duplicated cookie creation that caused by some plugins
		if (!defined('SWIFTSECURITY_AUTH_COOKIE_SENT')){
			if (class_exists('WP_Session_Tokens')){
				$manager = WP_Session_Tokens::get_instance( $user_id );
				$token = $manager->create( $expiration );
			}
	
			$secure = is_ssl();
	
			if ( $secure ) {
				$auth_cookie_name = SECURE_AUTH_COOKIE;
				$scheme = 'secure_auth';
			} else {
				$auth_cookie_name = AUTH_COOKIE;
				$scheme = 'auth';
			}
	
			setcookie($auth_cookie_name, $auth_cookie, $expire, SWIFT_PLUGINS_COOKIE_PATH, COOKIE_DOMAIN, false, true);
			setcookie($auth_cookie_name, $auth_cookie, $expire, SWIFT_ADMIN_COOKIEPATH, COOKIE_DOMAIN, false, true);
			define('SWIFTSECURITY_AUTH_COOKIE_SENT',true);
		}
	}

	/**
	 * Clear authentication and logged in cookies
	 */
	public static function SwiftSecurityClearAuthCookies(){
		setcookie( AUTH_COOKIE,        ' ', time() - YEAR_IN_SECONDS, SWIFT_ADMIN_COOKIEPATH,   COOKIE_DOMAIN );
		setcookie( SECURE_AUTH_COOKIE, ' ', time() - YEAR_IN_SECONDS, SWIFT_ADMIN_COOKIEPATH,   COOKIE_DOMAIN );
		setcookie( AUTH_COOKIE,        ' ', time() - YEAR_IN_SECONDS, SWIFT_PLUGINS_COOKIE_PATH, COOKIE_DOMAIN );
		setcookie( SECURE_AUTH_COOKIE, ' ', time() - YEAR_IN_SECONDS, SWIFT_PLUGINS_COOKIE_PATH, COOKIE_DOMAIN );
	}


	/**
	 * Show dismissable messages
	 */
	public static function ShowPermanentMessage(){
		if (current_user_can('manage_options')){
			$messages = get_option('swiftsecurity_dismissable_messages',array());
			foreach ((array)$messages as $message){
				$type = ('success' == $message['type'] ? 'updated' : ('warning' == $message['type'] ? 'update-nag' : 'error'));
				if (!$message['read']){
					echo '<div class="'.$type.'" style="padding: 11px 15px;">'.$message['message'].'</div>';
				}
			}
		}
	}

	/**
	 * Set a permanent message for admin
	 * @param string $key
	 * @param string $message
	 * @param string $type
	 * @param boolean $dismissable (default is true)
	 */
	public static function SetPermanentMessage($key, $message, $type, $dismissable = true){
		$messages = get_option('swiftsecurity_dismissable_messages');
		if (!isset($messages[$key])){
			$messages[$key]['message'] = $message;
			$messages[$key]['type'] = $type;
			$messages[$key]['dismissable'] = $dismissable;
			$messages[$key]['read'] = false;
			update_option('swiftsecurity_dismissable_messages', $messages);
		}
	}

	/**
	 * Remove a permanent message
	 * @param string $key
	 */
	public static function RemovePermanentMessage($key){
		$messages = get_option('swiftsecurity_dismissable_messages');
		if (isset($messages[$key])){
			unset($messages[$key]);
			update_option('swiftsecurity_dismissable_messages', $messages);
		}
	}

	/**
	 * Check the environment
	 * @return array
	 */
	public static function CheckEnvironment(){
		$environment['ServerSoftware'] = $_SERVER['SERVER_SOFTWARE'];
		$environment['ServerSoftwareShort'] = (preg_match('~(apache|litespeed|LNAMP)~i', $_SERVER['SERVER_SOFTWARE']) ? 'apache' : (preg_match('~(nginx|flywheel)~i', $_SERVER['SERVER_SOFTWARE']) ? 'nginx' : 'unknown'));
		$environment['ManagedHosting'] = null;

		if (function_exists('get_mu_plugins')){
			$mu_plugins = get_mu_plugins();
			if (isset($mu_plugins['mu-plugin.php']) && preg_match('~WP Engine~i',$mu_plugins['mu-plugin.php']['Name'])){
				$environment['ManagedHosting'] = 'WPEngine';
			}
		}

		return $environment;
	}
}
endif;

defined('ABSPATH') or die("KEEP CALM AND CARRY ON");

$SwiftSecurity = new SwiftSecurity();

?>
