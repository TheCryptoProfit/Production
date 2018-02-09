<?php 
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
class SwiftSecuritySettings{
	
	/**  
	 * Default settings
	 * @var array
	 */
	private $_defaultSettings = array(
			'HideWP' => array(
					'permalinks' => array(
							'author'				=> 'profiles',
							'search'				=> 'search',
							'category'				=> 'niche',
							'tag'					=> 'label'
					),
					'redirectTheme' => 'contents',
					'redirectThemeStyle' => 'bootstrap.min.css',
					'redirectChildTheme' => 'contents-ext',
					'redirectChildThemeStyle' => 'bootstrap-ext.min.css',
					'redirectDirs' => array(
						'wp-content/plugins' 	=> 'modules',
						'wp-content/uploads' 	=> 'media',
						'wp-includes'			=> 'assets',
						'wp-admin'				=> 'administrator',
					),
					'redirectFiles' => array(
						'wp-login.php'				=> 'user.php',
						'wp-comments-post.php'		=> 'comment.php',
						'wp-admin/admin-ajax.php'	=> 'ajax.php'
					),
					'queries' => array(
						'p' 		=> 'article',
						'author' 	=> 'user',
						's' 		=> 'query',
						'paged'		=> 'page',
						'cat'		=> 'niche',
						'page_id'	=> 'pid',
						'page_name'	=> 'pname',
						'tag'		=> 'label',
					),
					'hiddenFiles' => array(
						'readme.html',
						'license.txt'
					),
					'metas' => array(
						'generator' => ''
					),
					'customLogoutURL' => 'user.php',
					'minifycss' => 'enabled',
					'directPHP' => array(),
					'plugins' => array(),
					'otherFiles' => array(),
					'otherDirs' => array(),
					'regexInSource' => array(),
					'regexInClasses' => array(),
					'regexInIds' => array(),
					'regexInNames' => array(),						
					'regexInJS' => array(),
					'regexInCSS' => array(),
					'regexInAjax' => array(),
					'removeHTMLComments' => 'enabled',
					'redirect404' => '',
					'userRoles' => array(),
					'settings' => array()
			),
			'Firewall' => array(
					'SQLi' => array(
						'GET' => array(
								'union([^a]*a)+ll([^s]*s)+elect',
								'union([^s]*s)+elect',
								'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|drop|update|benchmark).*'
						),
						'POST' => array(),
						'COOKIE' => array(
								'union([^a]*a)+ll([^s]*s)+elect',
								'union([^s]*s)+elect',
						),
						'settings' => array(
								'GET' => 'enabled',
								'POST' => 'disabled',
								'COOKIE' => 'enabled'
						),
						'exceptions' => array(
								'GET' => array(),
								'POST' => array(),
								'COOKIE' => array()
						)
					),
					'XSS' => array(
						'GET' => array(
								'(<|%3C)([^s]*s)+cript.*(>|%3E)',
								'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
								'(<|%3C)([^o]*o)+bject.*(>|%3E)',
								'(<|%3C)([^i]*i)+frame.*(>|%3E)'
						),
						'POST' => array(),
						'settings' => array(
								'GET' => 'enabled',
								'POST' => 'disabled'
						),
						'exceptions' => array(
								'GET' => array(),
								'POST' => array()
						)
					),
					'Path' => array(
						'GET' => array(
								'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
						),
						'POST' => array(),
						'COOKIE' => array(),
						'settings' => array(
								'GET' => 'enabled',
								'POST' => 'disabled',
								'COOKIE' => 'disabled'
						),
						'exceptions' => array(
								'GET' => array(
										'(.*)\.(css|eot|woff|svg|png|jpg|jpeg|gif)$'	
								),
								'POST' => array(),
								'COOKIE' => array()
						)
					),
					'File' => array(
						'POST' => array(
								'content' => array(),
								'extension' => array()
						),
						'settings' => array(
								'POST' => 'disabled',
						),
						'exceptions' => array(
								'POST' => array()
						)
					),
					'IP' => array(
						'Whitelist' => array(),
						'Blacklist' => array(),
						'AutoBlacklistMaxAttempts' => 10,
						'CountryLoginWhitelist' => array(),
						'CountryBlacklist' => array()
					),
					'commentSpamBlocker' => 'enabled',
					'settings' => array(
						'presets' => array(
							'SQLi' => 1,
							'XSS' => 1,
							'Path' => 0,
							'File' => 0
						)
					)
			),
			'CodeScanner' => array(
				'scheduled' => 'none',
				'autoQuarantine' => 'enabled',
			),
			'Modules' => array(
					'HideWP' => 'enabled',
					'Firewall' => 'enabled'
			),
			'GlobalSettings' => array(
				'Notifications' => array(
					'email' => 'enabled',
					'pushover' => 'disabled',
					'NotificationEmail' => '',
					'NotificationPushoverToken' => '',
					'NotificationPushoverUser' => '',
					'NotificationPushoverSound' => 'pushover',
					'EmailNotifications' => array(
							'Login' => 'enabled',
							'Firewall' => 'enabled',
							'CodeScanner' => 'enabled'
					),
					'PushNotifications' => array(
							'Login' => 'disabled',
							'Firewall' => 'disabled',
							'CodeScanner' => 'disabled',
					)
				),
				'sq'	=> ''
			)			
	);
	
	/**
	 * Contains pre-configured setting variations for firewall
	 * @var array
	 */
	private $_firewallPresets = array(
		'SQLi' => array(
			array(
				'GET' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|drop|update|benchmark).*'
				),
				'POST' => array(),
				'COOKIE' => array(),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'disabled',
						'COOKIE' => 'disabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array(),
						'COOKIE' => array()
				)
			),
			array(
				'GET' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|drop|update|benchmark).*'
				),
				'POST' => array(),
				'COOKIE' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'disabled',
						'COOKIE' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array(),
						'COOKIE' => array()
				)
			),
			array(
				'GET' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|drop|update|benchmark).*'
				),
				'POST' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|drop|update|benchmark).*'
				),
				'COOKIE' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00)(.*)?([^a-zA-Z]+)(/\*|union|select|benchmark).*'
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled',
						'COOKIE' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array(),
						'COOKIE' => array()
				)
			),
			array(
				'GET' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|cast|set|declare|drop|update|md5|benchmark).*',
						'(,|\(|--|/\*|#|%23)'
				),
				'POST' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|cast|set|declare|drop|update|md5|benchmark).*',
						'(,|\(|--|/\*|#|%23)'
				),
				'COOKIE' => array(
						'union([^a]*a)+ll([^s]*s)+elect',
						'union([^s]*s)+elect',
						'(;|<|>|\'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00)(.*)?([^a-zA-Z]+)(/\*|union|select|insert|cast|declare|drop|update|md5|benchmark).*',
						'(\(|--|/\*|#|%23)'
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled',
						'COOKIE' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(
							'wp-admin/load-styles.php',
							'wp-admin/load-scripts.php'
						),
						'POST' => array(),
						'COOKIE' => array()
				)
			)
		),
		'XSS' => array(
			array(
				'GET' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
				),
				'POST' => array(),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'disabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array()
				)
			),
			array(
				'GET' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
						'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
						'(<|%3C)([^o]*o)+bject.*(>|%3E)',
						'(<|%3C)([^i]*i)+frame.*(>|%3E)'
				),
				'POST' => array(),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'disabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array()
				)
			),
			array(
				'GET' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
						'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
						'(<|%3C)([^o]*o)+bject.*(>|%3E)',
						'(<|%3C)([^i]*i)+frame.*(>|%3E)'
				),
				'POST' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
						'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
						'(<|%3C)([^o]*o)+bject.*(>|%3E)',
						'(<|%3C)([^i]*i)+frame.*(>|%3E)'
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array()
				)
			),
			array(
				'GET' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
						'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
						'(<|%3C)([^o]*o)+bject.*(>|%3E)',
						'(<|%3C)([^i]*i)+frame.*(>|%3E)',
						'(\(|\)|;)'
				),
				'POST' => array(
						'(<|%3C)([^s]*s)+cript.*(>|%3E)',
						'(<|%3C)([^e]*e)+mbed.*(>|%3E)',
						'(<|%3C)([^o]*o)+bject.*(>|%3E)',
						'(<|%3C)([^i]*i)+frame.*(>|%3E)'
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(),
						'POST' => array()
				)
			)
		),
		'Path' => array(
			array(
				'GET' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'POST' => array(),
				'COOKIE' => array(),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'disabled',
						'COOKIE' => 'disabled'
				),
				'exceptions' => array(
						'GET' => array(
								'(.*)\.(css|eot|woff|svg|png|jpg|jpeg|gif)$'	
						),
						'POST' => array(),
						'COOKIE' => array()
				)
			),
			array(
				'GET' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'POST' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'COOKIE' => array(),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled',
						'COOKIE' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(
								'(.*)\.(css|eot|woff|svg|png|jpg|jpeg|gif)$'	
						),
						'POST' => array(),
						'COOKIE' => array()
				)
			),
			array(
				'GET' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'POST' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'COOKIE' => array(
						'(\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c)'
				),
				'settings' => array(
						'GET' => 'enabled',
						'POST' => 'enabled',
						'COOKIE' => 'enabled'
				),
				'exceptions' => array(
						'GET' => array(
								'(.*)\.(css|eot|woff|svg|png|jpg|jpeg|gif)$'	
						),
						'POST' => array(),
						'COOKIE' => array()
				)
			)
		),
		'File' => array(
			array(
				'POST' => array(
						'content' => array(),
						'extension' => array()
				),
				'settings' => array(
						'POST' => 'disabled',
				),
				'exceptions' => array(
						'POST' => array()
				)
			),
			array(
				'POST' => array(
						'content' => array(
								'<\?(php)?(.*)((shell_)exec|system|passthru|eval)',
								'^#!/usr/bin/php$'
						),
						'extension' => array(
								'htaccess',
								'php',
								'php3',
								'php4',
								'php5',
								'py',
								'pl',
								'cgi'
						)
				),
				'settings' => array(
						'POST' => 'enabled',
				),
				'exceptions' => array(
						'POST' => array()
				)
			),
			array(
				'POST' => array(
						'content' => array(
								'<\?(php)?(.*)((shell_|pcntl_)?exec|system|passthru|proc_open||eval|assert|ob_start|array_diff_uassoc|array_filter|array_diff_ukey|array_intersect_uassoc|array_intersect_ukey|array_map|array_reduce|array_udiff_assoc|array_udiff_uassoc|array_udiff|array_uintersect_assoc|array_uintersect_uassoc|array_uintersect|array_walk_recursive|array_walk|assert_options|uasort|uksort|usort|preg_replace_callback|spl_autoload_register|iterator_apply|call_user_func|call_user_func_array|register_shutdown_function|register_tick_function|set_error_handler|set_exception_handler|session_set_save_handler|sqlite_create_aggregate|sqlite_create_function|extract|phpinfo|proc_open|popen|show_source|highlight_file|phpinfo|posix_mkfifo|posix_getlogin|posix_ttyname|getenv|get_current_user|proc_get_status|get_cfg_var|getcwd|getlastmo|getmygid|getmyinode|getmypid|getmyuid)',
								'^#!/usr/bin/php$'
						),
						'extension' => array(
								'htaccess',
								'php',
								'php3',
								'php4',
								'php5',
								'py',
								'pl',
								'cgi'
						)
				),
				'settings' => array(
						'POST' => 'enabled',
				),
				'exceptions' => array(
						'POST' => array()
				)
			),
			array(
				'POST' => array(
						'content' => array(
								'<\?',
								'^#!/usr/bin/php$'
						),
						'extension' => array(
								'htaccess',
								'php',
								'php3',
								'php4',
								'php5',
								'py',
								'pl',
								'cgi'
						)
				),
				'settings' => array(
						'POST' => 'enabled',
				),
				'exceptions' => array(
						'POST' => array()
				)
			)
		)
	);
	
	public $JSMessages = array(
		'ARE_YOU_SURE'	=> 'Are you sure?',
		'UNKWOWN_ERROR' => 'Unknown error',
		'TEST_START' 	=> 'Starting...',
		'DONE'			=> 'Done'
	);
	
	/**
	 * If settings are modified it is true otherwise false;
	 * @var unknown
	 */
	public $isModified = false;
	
	/**
	 * If admin path is modified it is true otherwise false;
	 * @var unknown
	 */
	public $isAdminURLModified= false;
	
	/**
	 * Contains settings error messages
	 * @var string
	 */
	public $errorMessage = '';
	
	/**
	 * Contains settings error code
	 * @var integer
	 */
	public $errorCode = '';
	
	/**
	 * Error field id
	 * @var string
	 */
	public $errorField = '';
	
	public function __construct(){
		SwiftSecurity::ClassInclude('SettingsException');
		
		//Overwrite default settings from default.json file if file exists
		if (file_exists(SWIFTSECURITY_PLUGIN_DIR . '/default.json')){
			$settings = json_decode(file_get_contents(SWIFTSECURITY_PLUGIN_DIR . '/default.json'), true);
		
			if (is_array($settings) && !empty($settings)){
				$this->_defaultSettings = $settings;
			}
			$this->_defaultSettings['GlobalSettings']['sq'] = 'sq_' . md5(mt_rand(0,1000000000));
		}
		
		//Set default notification e-mail address
		$this->_defaultSettings['GlobalSettings']['Notifications']['NotificationEmail'] = get_option('admin_email');
		
		//Add ajax handler
		add_action('wp_ajax_SwiftSecurityFirewallAjaxHandler', array($this, 'SwiftSecurityFirewallAjaxHandler'));
	}
	
	/**
	 * Ajax handler to load firewall presets
	 */
	public function SwiftSecurityFirewallAjaxHandler(){
		//Check wp-nonce
		check_ajax_referer( 'swiftsecurity', 'wp-nonce' );
		
		//Define settings templates to prevent malicious file include
		$presets = array(
			'SQLi' => 'SQLi.preset',
			'XSS' => 'XSS.preset',
			'Path' => 'Path.preset',
			'File' => 'File.preset',								
		);
		
		if (isset($_POST['selected'])){
			$settings = $this->_firewallPresets[$_POST['set']][$_POST['selected']];
		}
		else{
			$GlobalSettings = $this->GetSettings();
			$settings = $GlobalSettings['Firewall'][$_POST['set']];
		}
		
		include_once SWIFTSECURITY_PLUGIN_DIR . '/templates/firewall-presets/'.$presets[$_POST['set']].'.php';
		
		wp_die();
	}
	
	/**
	 * Get settings, if there aren't any settings it returns the default settings.
	 * @return array
	 */
	public function GetSettings(){		
		//Use main blog settings for subsites if NETWORK_ONLY mode is active
		if(defined('SWIFTSECURITY_NETWORK_ONLY') && get_current_blog_id() != 1){
			switch_to_blog(1);
			$settings = $this->SettingsBackwardCompatibility($this->FixCorruptedSettings(get_option('swiftsecurity_plugin_options', $this->_defaultSettings)));
			restore_current_blog(); 
		}
		//Use current blog settings if NETWORK_ONLY mode is not active
		else{
			$settings = $this->SettingsBackwardCompatibility($this->FixCorruptedSettings(get_option('swiftsecurity_plugin_options', $this->_defaultSettings)));
		}
		
		//Set Regex in CSS for child themes
		if (defined('TEMPLATEPATH') && is_child_theme() && ((isset($settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/style.css']) && $settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/style.css'] != '/'.$settings['HideWP']['redirectTheme'].'/'.$settings['HideWP']['redirectThemeStyle']) || !isset($settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/style.css']))){
			$settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/style.css'] = '/'.$settings['HideWP']['redirectTheme'].'/'.$settings['HideWP']['redirectThemeStyle'];
		}
		if (defined('TEMPLATEPATH') && is_child_theme() && ((isset($settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/']) && $settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/'] != '/'.$settings['HideWP']['redirectTheme'].'/') || !isset($settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/']))){
			$settings['HideWP']['regexInCSS']['((\.\./)*)/'.get_template().'/'] = '/'.$settings['HideWP']['redirectTheme'].'/';
		}
		
		//Set plugin and uploads dir if WP_CONTENT_DIR is renamed
		if (WP_CONTENT_DIR != ABSPATH . 'wp-content' && !isset($this->_defaultSettings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins']) && isset($this->_defaultSettings['HideWP']['redirectDirs']['wp-content/plugins'])){
			$settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] = $settings['HideWP']['redirectDirs']['wp-content/plugins'];
			$settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads'] = $settings['HideWP']['redirectDirs']['wp-content/uploads'];
			
			unset($settings['HideWP']['redirectDirs']['wp-content/plugins']);
			unset($settings['HideWP']['redirectDirs']['wp-content/uploads']);
		}

		//Set swiftsecurity-autocaptcha rename automatically if it isn't set
		$rand = hash('crc32', mt_rand(0,900000000000000000));
		$settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha'] = (!isset($settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']) || empty($settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']) ? $rand : $settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']);
		$settings['HideWP']['regexInSource']['swiftsecurity-autocaptcha'] = (!isset($settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']) || empty($settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']) ? $rand : $settings['HideWP']['regexInNames']['swiftsecurity-autocaptcha']);
		
		//Plugin compatibility
		$settings = $this->PluginCompatibility($settings);
						
		//Set plugin dir rewrites if not set
		if (!function_exists('get_plugins')){
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();
		foreach ((array)$plugins as $key=>$value){
			$pluginDir 		= dirname($key);
			//If plugin is single file
			if ($pluginDir == '.'){
				continue;
			}
			$settings['HideWP']['plugins'][$pluginDir] = (empty($settings['HideWP']['plugins'][$pluginDir]) ? str_shuffle(strtolower($pluginDir)) : $settings['HideWP']['plugins'][$pluginDir]);
		}
		
		return $settings;
	}
	
	/**
	 * Save the settings
	 */
	public function SaveSettings($settings){
		//Use verify_wp_nonce before wp loaded
		require_once(ABSPATH .'wp-includes/pluggable.php');
		//Multisite network activated fix for verify_wp_nonce
		if (!defined('AUTH_COOKIE')){
			wp_cookie_constants();
		}
		
		//Get current settings
		$currentSettings = $this->GetSettings();

		//Check the privileges
		if (!current_user_can('manage_options')){
			return false;
		}
		
		/*
		 * Base64 decode armored values
		*/
		if (isset($_POST['base64'])){
			$settings = swift_base64_decode_deep($settings);
			$settings_extra = swift_base64_decode_deep(isset($_POST['settings_extra']) ? $_POST['settings_extra'] : array());
		}
		
		/*Hide Wordpress*/
		if ($_POST['swift-security-settings-save'] == 'HideWP'){
			$usedKeys = array();
			$usedValues = array();
			
			/*
			 * Field checking
			 */
			
			//Theme directory
			$settings['HideWP']['redirectTheme'] = (empty($settings['HideWP']['redirectTheme']) ? get_template() : $settings['HideWP']['redirectTheme']);
			$usedKeys[get_template()] = true;
			$usedValues[$settings['HideWP']['redirectTheme']] = true; 
			
			//Theme style
			$settings['HideWP']['redirectThemeStyle'] = (empty($settings['HideWP']['redirectThemeStyle']) ? 'style.css' : $settings['HideWP']['redirectThemeStyle']);
			$usedKeys[get_template() . '/style.css'] = true;
			$usedValues[$settings['HideWP']['redirectThemeStyle']] = true;
				
			//Remove empty hidden files inputs
			$settings['HideWP']['hiddenFiles'] = swift_remove_empty_elements_deep($settings['HideWP']['hiddenFiles']);
			
			//If value empty set the key as value
			foreach ((array)$settings['HideWP']['permalinks'] as $key=>$value){
				if (empty($value)){
					//$settings['HideWP']['permalinks'][$key] = $key;
				}
				
				//Check for duplicated key or values
				if (isset($usedKeys[$key]) || isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				//$usedKeys[$key] = true;
				//$usedValues[$value] = true;
			}
			
			//If value empty set the key as value
			foreach ((array)$settings['HideWP']['redirectDirs'] as $key=>$value){
				if (empty($value)){
					$settings['HideWP']['redirectDirs'][$key] = $key;
				}
				
				//Check for duplicated key or values
				if (isset($usedKeys[$key]) || isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				$usedKeys[$key] = true;
				$usedValues[$value] = true;
			}
			
			//If value empty set the key as value
			foreach ((array)$settings['HideWP']['redirectFiles'] as $key=>$value){
				if (empty($value)){
					$settings['HideWP']['redirectFiles'][$key] = $key;
				}
				
				//Check for duplicated key or values
				if (isset($usedKeys[$key]) || isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				$usedKeys[$key] = true;
				$usedValues[$value] = true;
			}
			
			//If value empty set the key as value
			foreach ((array)$settings['HideWP']['plugins'] as $key=>$value){
				if (empty($value)){
					$settings['HideWP']['plugins'][$key] = $key;
				}
			
				//Check for duplicated key or values
				if (isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				$usedValues[$value] = true;
			}
			
			//If value empty set the key as value
			foreach ((array)$settings['HideWP']['queries'] as $key=>$value){
				if (empty($value)){
					$settings['HideWP']['queries'][$key] = $key;
				}
			}
			
			foreach ((array)$settings['HideWP']['otherFiles'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['otherFiles'][$key]);
					continue;
				}
				//Check for duplicated key or values
				if (isset($usedKeys[$key]) || isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				$usedKeys[$key] = true;
				$usedValues[$value] = true;
			}
			
			foreach ((array)$settings['HideWP']['otherDirs'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['otherDirs'][$key]);
					continue;
				}
				//Check for duplicated key or values
				if (isset($usedKeys[$key]) || isset($usedValues[$value])){
					throw new SwiftSecuritySettingsException(array(
							'message' => __('Error! Duplicated value:', 'SwiftSecurity') . ' ' . $value
					));
				}
				$usedKeys[$key] = true;
				$usedValues[$value] = true;
			}

			foreach ((array)$settings['HideWP']['regexInSource'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInSource'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInClasses'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInClasses'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInIds'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInIds'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInNames'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInNames'][$key]);
					continue;
				}
			}

			foreach ((array)$settings['HideWP']['regexInAjax'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInAjax'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInJS'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInJS'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInCSS'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInCSS'][$key]);
					continue;
				}
			}
			
			foreach ((array)$settings['HideWP']['regexInRequest'] as $key=>$value){
				//Remove unnecessary empty fields
				if ($key == '0' || (int)$key > 0 || empty($value)){
					unset($settings['HideWP']['regexInRequest'][$key]);
					continue;
				}
			}
			
			//Compatibility check
			if (SwiftSecurity::CompatibilityCheck('HideWP') == false || SwiftSecurity::CompatibilityCheck('htaccess') == false){
				$settings['Modules']['HideWP'] = 'disabled';
			}
			
			//Set "disabled" for caching if it is turned off
			if (!isset($settings['HideWP']['cache'])){
				$settings['HideWP']['cache'] = 'disabled';
			}
			
			//Check cache folder is writable for combined assets
			if (((isset($settings['HideWP']['combineCSS']['status']) && $settings['HideWP']['combineCSS']['status'] == 'enabled') || (isset($settings['HideWP']['combineHeaderJS']['status']) && $settings['HideWP']['combineHeaderJS']['status'] == 'enabled') || (isset($settings['HideWP']['combineFooterJS']['status']) && $settings['HideWP']['combineFooterJS']['status'] == 'enabled')) && ($settings['HideWP']['cache'] != 'enabled' || !is_writable(SWIFTSECURITY_PLUGIN_DIR . '/cache'))){
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! The cache folder is not writable, or caching is turned off. You can\'t combine CSS and JS files.', 'SwiftSecurity')
				));
			}
						
			//set isAdminURLModified
			$this->isAdminURLModified = ($currentSettings['HideWP']['redirectDirs']['wp-admin'] != $settings['HideWP']['redirectDirs']['wp-admin'] ? true : false);
			
			//Update the settings
			$currentSettings['HideWP'] = $settings['HideWP'];
				
			//Enable/disable the module
			$currentSettings['Modules']['HideWP'] = (isset($settings['Modules']['HideWP']) ? $settings['Modules']['HideWP'] : 'disabled');
		}
		
		/*Firewall*/
		else if ($_POST['swift-security-settings-save'] == 'Firewall'){			
			/*Extra settings (these are not really settings but the user can manage them)*/
			
			//Banned IPs
			if (isset($settings_extra['blocked_ips'])){
				update_option('swiftsecurity_banned_ips', swift_remove_empty_elements_deep(explode("\n",$settings_extra['blocked_ips'])));
			}
			
			/*Set the presets*/
			
			//SQL injection settings
			$settings['Firewall']['settings']['presets']['SQLi'] = $settings['Firewall']['Preset']['SQLi'];
			//XSS settings
			$settings['Firewall']['settings']['presets']['XSS'] = $settings['Firewall']['Preset']['XSS'];
			//Path manipulation settings
			$settings['Firewall']['settings']['presets']['Path'] = $settings['Firewall']['Preset']['Path'];
			//File upload settings
			$settings['Firewall']['settings']['presets']['File'] = $settings['Firewall']['Preset']['File'];
			
			//Check CURL is enabled and ignore POST settings if not
			try{
				SwiftSecurity::CompatibilityCheck('Firewall');
			}
			catch (SwiftSecuritySettingsException $e){
				$settings['Firewall']['SQLi']['settings']['POST'] = 'disabled';
				$settings['Firewall']['XSS']['settings']['POST'] = 'disabled';
				$settings['Firewall']['Path']['settings']['POST'] = 'disabled';
				$settings['Firewall']['File']['settings']['POST'] = 'disabled';
				
				$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
				$GLOBALS['SwiftSecurityMessage']['type'] = 'sft-notification-error';
				SwiftSecurity::AdminNotice();
			}
			
			//Check htaccess again
			SwiftSecurity::CompatibilityCheck('htaccess');
			
			//Remove empty fields
			$settings['Firewall'] = swift_remove_empty_elements_deep($settings['Firewall']);

			//Parse blacklist textarea
			if (isset($settings['Firewall']['IP']['Blacklist'])){
				$settings['Firewall']['IP']['Blacklist'] = swift_remove_empty_elements_deep(explode("\n",$settings['Firewall']['IP']['Blacklist']));
			} 

			//Update the settings
			$currentSettings['Firewall'] = $settings['Firewall'];
				
			//Enable/disable the module
			$currentSettings['Modules']['Firewall'] = (isset($settings['Modules']['Firewall']) ? $settings['Modules']['Firewall'] : 'disabled');
		}
		
		/*Code Scanner*/
		else if ($_POST['swift-security-settings-save'] == 'CodeScanner'){
			//Update the settings
			$currentSettings['CodeScanner'] = $settings['CodeScanner'];
						
			wp_clear_scheduled_hook('SwiftSecurityStartScheduledScan');
			if ($settings['CodeScanner']['settings']['scheduled'] != 'none'){
				wp_schedule_event( time(), $settings['CodeScanner']['settings']['scheduled'], 'SwiftSecurityStartScheduledScan');
			}
		}
		
		/*General Settings*/
		else if ($_POST['swift-security-settings-save'] == 'General'){
			//Checkings
			$settings['GlobalSettings']['Notifications'] = swift_remove_empty_elements_deep($settings['GlobalSettings']['Notifications']);
			
			if (isset($settings['GlobalSettings']['Notifications']['PushNotifications']) && !empty($settings['GlobalSettings']['Notifications']['PushNotifications']) && empty($settings['GlobalSettings']['Notifications']['NotificationPushoverToken'])) {
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Please set your Pushover Application Key', 'SwiftSecurity')
				));
			}
			
			if (isset($settings['GlobalSettings']['Notifications']['PushNotifications']) && !empty($settings['GlobalSettings']['Notifications']['PushNotifications']) && empty($settings['GlobalSettings']['Notifications']['NotificationPushoverUser'])) {
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Error! Please set your Pushover User Key', 'SwiftSecurity')
				));
			}
			
			//Update the settings
			$currentSettings['GlobalSettings']['hide-menu'] = isset($settings['GlobalSettings']['hide-menu']) ? $settings['GlobalSettings']['hide-menu'] : null;
			$currentSettings['GlobalSettings']['safe-activation'] = isset($settings['GlobalSettings']['safe-activation']) ? $settings['GlobalSettings']['safe-activation'] : 'disabled';
			$currentSettings['GlobalSettings']['Notifications'] = $settings['GlobalSettings']['Notifications'];
			
			//Purchase key
			if (isset($settings['GlobalSettings']['purchase-key']) && !empty($settings['GlobalSettings']['purchase-key'])){
				$response = wp_remote_get('http://api.swte.ch/validate/SwiftSecurity?purchase_key=' . $settings['GlobalSettings']['purchase-key'] . '&site=' . site_url(), array('timeout' => 60));
				if (is_wp_error($response)){
					throw new SwiftSecuritySettingsException(array(
							'message' => __('Error, couldn\'t validate purchase key. ', 'SwiftSecurity') . $response->get_error_message()
					));
				}
				else{
					$json = json_decode($response['body'],true);
					if (isset($json['error']) && $json['error'] !== false){
						throw new SwiftSecuritySettingsException(array(
								'message' => __('Error, couldn\'t validate purchase key: ', 'SwiftSecurity') . $json['response']
						));
					}
					else if ($response['response']['code'] != '200'){
						throw new SwiftSecuritySettingsException(array(
								'message' => __('API error, couldn\'t validate purchase key. Please try again', 'SwiftSecurity')
						));
					}
					else{
						$currentSettings['GlobalSettings']['purchase-key'] = $settings['GlobalSettings']['purchase-key'];
					}
				}
			}
			
		}
		
		/*Default Settings*/
		else if ($_POST['swift-security-settings-save'] == 'RestoreDefault'){
			/*
			 * Base64 decode armored values
			 */
			if (isset($_POST['base64'])){
				$_POST['module'] = base64_decode($_POST['module']);
			}
			
			//Update the settings
			$currentSettings[$_POST['module']] = $this->_defaultSettings[$_POST['module']];
		}
		
		//Update settings
		update_option('swiftsecurity_plugin_options', $currentSettings);
		
		//Set isModified
		$this->isModified = true;		
	}
	
	/**
	 * Handle export/import plugin settings 
	 */
	public function ExIm(){
		//Use verify_wp_nonce before wp loaded
		require_once(ABSPATH .'wp-includes/pluggable.php');
		//Multisite network activated fix for verify_wp_nonce
		if (!defined('AUTH_COOKIE')){
			wp_cookie_constants();
		}
		
		//Check the referrer
		if (! isset( $_POST['SwiftSecurityNonce'] ) || (!wp_verify_nonce($_POST['SwiftSecurityNonce'], 'save_settings' ) && !wp_verify_nonce(base64_decode($_POST['SwiftSecurityNonce']), 'save_settings' ))){
			return false;
		}
		
		if ($_POST['swift-security-exim'] == 'export-settings'){
			$settings = json_encode($this->GetSettings());
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="swiftsecurity-settings.json"');
			header('Content-Length: ' . strlen($settings));
			echo $settings;
			die;
		}
		else if ($_POST['swift-security-exim'] == 'import-settings'){
			//Get current settings
			$currentSettings = $this->GetSettings();
			
			$settings = ((isset($_FILES['swiftsecurity-import-settings']['tmp_name']) && !empty($_FILES['swiftsecurity-import-settings']['tmp_name'])) ? file_get_contents($_FILES['swiftsecurity-import-settings']['tmp_name']) : '');
			/*Checkings*/ 
			
			if (strlen($settings) == 0){
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Imported file is empty', 'SwiftSecurity')
				));
			}
			//Create array from imported json
			$SettingsArray = json_decode($settings, true);
			if (!is_array($SettingsArray)){
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Imported file is corrupted', 'SwiftSecurity')
				));
			}
			
			//Check mandantory settings field are present
			$HideWPConsistence = array_diff_key_recursive(swift_remove_empty_elements_deep($this->_defaultSettings['HideWP']), $SettingsArray['HideWP']);
			
			foreach ($HideWPConsistence as $key => $value){
				if (!is_array($HideWPConsistence[$key])){
					unset($HideWPConsistence[$key]);
				}
			}
			
			if (!empty($HideWPConsistence) || empty($SettingsArray['GlobalSettings']['sq'])){
				throw new SwiftSecuritySettingsException(array(
						'message' => __('Imported settings are not consistent', 'SwiftSecurity')
				));
			}
			
			//Save imported settings 
			update_option('swiftsecurity_plugin_options', $SettingsArray);
			
			//Set isModified
			$this->isModified = true;
		}
	}
	
	/**
	 * Fix missing settings options
	 */
	public function SettingsBackwardCompatibility($settings){
		/*
		 * Add AutoBlacklistMaxAttempts if it is not set
		 * @since 1.0.2
		 */
		if (!isset($settings['Firewall']['IP']['AutoBlacklistMaxAttempts'])){
			$settings['Firewall']['IP']['AutoBlacklistMaxAttempts'] = 10;
		}
		
		/*
		 * Add child theme support
		 * @since 1.0.6
		 */	
		if (!isset($settings['HideWP']['redirectChildTheme'])){
			$settings['HideWP']['redirectChildTheme'] = 'contents-ext';
		}
		if (!isset($settings['HideWP']['redirectChildThemeStyle'])){
			$settings['HideWP']['redirectChildThemeStyle'] = 'bootstrap-ext.min.css';
		}
		
		/*
		 * Add notifications array
		 * @since 1.0.6
		 */
		if (!isset($settings['GlobalSettings']['Notifications'])){
			$settings['GlobalSettings']['Notifications'] = array(
					'NotificationEmail' => get_option('admin_email'),
					'NotificationPushoverToken' => '',
					'NotificationPushoverUser' => '',
					'NotificationPushoverSound' => 'pushover',
					'EmailNotifications' => array(
							'Login' => 'enabled',
							'Firewall' => 'enabled',
							'CodeScanner' => 'enabled'
					),
					'PushNotifications' => array(
							'Login' => 'disabled',
							'Firewall' => 'disabled',
							'CodeScanner' => 'disabled'
					)
				);
		}
		
		/*
		 * Add custom logout URL
		 * @since 1.2.4
		 */
		if (!isset($settings['HideWP']['customLogoutURL'])){
			$settings['HideWP']['customLogoutURL'] = 'user.php';
		}
		
		/*
		 * Add redirect 404 page
		 * @since 1.3.6
		 */
		if (!isset($settings['HideWP']['redirect404'])){
			$settings['HideWP']['redirect404'] = '';
		}
		
		/*
		 * Add regexInNames
		 * @since 1.3.6
		 */
		if (!isset($settings['HideWP']['regexInNames'])){
			$settings['HideWP']['regexInNames'] = array();
		}

		/*
		 * Add userRoles
		 * @since 1.3.10
		 */
		if (!isset($settings['HideWP']['userRoles'])){
			$settings['HideWP']['userRoles'] = array();
		}		
		
		/*
		 * Add regexInAjax
		 * @since 1.3.10
		 */
		if (!isset($settings['HideWP']['regexInAjax'])){
			$settings['HideWP']['regexInAjax'] = array();
		}
		
		/*
		 * Add regexInRequest
		 * @since 1.4.2.2
		 */
		if (!isset($settings['HideWP']['regexInRequest'])){
			$settings['HideWP']['regexInRequest'] = array();
		}
		
		/*
		 * Add ability to turn off cache
		 * @since 1.4.2.4
		 */
		if (!isset($settings['HideWP']['cache'])){
			$settings['HideWP']['cache'] = 'enabled';
		}

		/*
		 * Add ability to turn off safe activation
		 * @since 1.4.2.5
		 */
		if (!isset($settings['GlobalSettings']['safe-activation'])){
			$settings['GlobalSettings']['safe-activation'] = 'enabled';
		}
		
		/*
		 * Rename JSON API
		 * @since 1.4.2.10
		 */
		if (!isset($settings['HideWP']['otherFiles']['wp-json'])){
			$settings['HideWP']['otherFiles']['wp-json'] = 'rest-api';
		}
		
		/*
		 * Rename wp-embed.js
		 * @since 1.4.2.11
		 */
		if (!isset($settings['HideWP']['otherFiles']['wp-includes/js/wp-embed.min.js'])){
			$settings['HideWP']['otherFiles']['wp-includes/js/wp-embed.min.js'] = 'js/embed.min.js';
		}
		
		
		return $settings;
	}
	
	/**
	 * Custom rules for plugin compatibility to improve one-click activation
	 * @param array $settings
	 * @return array
	 */
	public function PluginCompatibility($settings){		
		/* TinyMCE */
		if (!is_array($settings['HideWP']['directPHP']) || !in_array($settings['HideWP']['redirectDirs']['wp-includes'] . '/js/tinymce/wp-tinymce.php', $settings['HideWP']['directPHP'])){
			if (file_exists(ABSPATH . 'wp-includes/js/tinymce/wp-tinymce.php')){
				$settings['HideWP']['directPHP'][] = $settings['HideWP']['redirectDirs']['wp-includes'] . '/js/tinymce/wp-tinymce.php';
			}
		}
		if (!is_array($settings['HideWP']['directPHP']) || !in_array('wp-includes/js/tinymce/wp-tinymce.php', $settings['HideWP']['directPHP'])){
			if (file_exists(ABSPATH . 'wp-includes/js/tinymce/wp-tinymce.php')){
				$settings['HideWP']['directPHP'][] = 'wp-includes/js/tinymce/wp-tinymce.php';
			}
		}

		//Cache plugins
		if (file_exists(ABSPATH . WP_CONTENT_DIRNAME . '/cache') && !isset($settings['HideWP']['otherDirs'][WP_CONTENT_DIRNAME . '/cache'])){
			$settings['HideWP']['otherDirs'][WP_CONTENT_DIRNAME . '/cache'] = 'cache';
		}
		
		//WPEngine mu-plugins
		$environment = SwiftSecurity::CheckEnvironment();
		if ($environment['ManagedHosting'] == 'WPEngine'){
			if (!isset($settings['HideWP']['otherDirs'][WP_CONTENT_DIRNAME . '/mu-plugins'])){
				$settings['HideWP']['otherDirs'][WP_CONTENT_DIRNAME . '/mu-plugins'] = 'extras';
			} 
		}
		
		return $settings;
	}
	
	/**
	 * If settings are corrupted returns the default settings, otherwise returns the current settings
	 * @param array $settings
	 * @return array
	 * @todo improve checkings
	 * @todo alert messages
	 */
	public function FixCorruptedSettings($settings){
		//If settings missing
		if (!is_array($settings)){
			$settings = $this->_defaultSettings;
		}
		
		//Generate random secure query if it is emtpy
		if (empty($settings['GlobalSettings']['sq'])){
			$settings['GlobalSettings']['sq'] = 'sq_' . md5(mt_rand(0,1000000000));
		}	

		//Fix corrupted Hide Wordpress settings
		if (!isset($settings['HideWP']) || !is_array($settings['HideWP'])){
			$settings['HideWP'] = $this->_defaultSettings['HideWP'];
		}
		
		//Fix corrupted Firewall settings
		if (!isset($settings['Firewall']) || !is_array($settings['Firewall'])){
			$settings['Firewall'] = $this->_defaultSettings['Firewall'];
		}
		
		//Fix corrupted Code Scanner settings
		if (!isset($settings['CodeScanner']) || !is_array($settings['CodeScanner'])){
			$settings['CodeScanner'] = $this->_defaultSettings['CodeScanner'];
		}
		
		//Fix corrupted modules settings
		if (!isset($settings['Modules']) || !is_array($settings['Modules'])){
			$settings['Modules'] = $this->_defaultSettings['Modules'];
		}
		
		//Fix corrupted global settings
		if (!isset($settings['GlobalSettings']) || !is_array($settings['GlobalSettings'])){
			$settings['GlobalSettings'] = $this->_defaultSettings['GlobalSettings'];
		}		
				
		return $settings;
	}
	
}

?>