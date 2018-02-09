<?php

/**
 * Hide wordpress from non-logged in users
 * It change the plugins, upload, template directories and some WP specific files (eg: wp-login.php)
 * Prevent to access php files directly (eg /wp-config.php)
 * Change queries (?p=1, ?cat=2, etc...)
 * Cleans the <head> tag (remove rss links, shortlinks, etc...)
 * Replace custom strings in html source
 * Custom rewrite rules
 * Change classnames and ids in HTML, JS, and CSS source
 */
class SwiftSecurityHideWp {
	
	/**
	 * Define the module name
	 * @var string
	 */
	public $moduleName = 'HideWP';
	
	/**
	 * Server IP address
	 * @var string
	 */
	public $server_addr = '127.0.0.1';
	
	/**
	 * Filename for combined CSS file
	 * @var string
	 */
	public $CombinedCSSFilename = '';
	
	/**
	 * Style handles to combine
	 * @var array
	 */
	public $StylesToCombine = array();
	
	/**
	 * Handled scripts in header
	 * @var unknown
	 */
	public $handledHeaderScripts = array();
	
	/**
	 * Handled scripts in footer
	 * @var unknown
	 */
	public $handledFooterScripts = array();
	
	/**
	 * Create the HideWP object
	 */
	public function __construct($settings){
		//Get the settings
		$this->_settings		= $settings['HideWP'];
		$this->_globalSettings	= $settings['GlobalSettings'];
		
		$this->sitePath = parse_url(site_url(),PHP_URL_PATH);
		
		$this->server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : (isset($GLOBALS['_SERVER']['SERVER_ADDR']) ? $GLOBALS['_SERVER']['SERVER_ADDR'] : '127.0.0.1');
		
		if ($settings['Modules']['HideWP'] == 'enabled'){
			$this->Init();
		}
		
		//Set WP_ADMIN because POST Proxy loose it
		if (!defined('WP_ADMIN') && preg_match('~^/'.$this->_settings['redirectDirs']['wp-admin'] . '/admin.php~',$_SERVER['REQUEST_URI'])){
			define ('WP_ADMIN', true);
		}
		
		//site_url padding for multisites in non-NETWORK_ONLY mode
		if(!defined('SWIFTSECURITY_NETWORK_ONLY')){
			$this->cache_site_url_padding = '/'.apply_filters('swiftsecurity_multisite_rewrite_cond', parse_url(site_url(),PHP_URL_HOST));
		}
		else{
			$this->cache_site_url_padding = '';
		}
	}
	
	/**
	 * Initialize Hide Wordpress
	 */
	public function Init(){
		//Replace WP specific folders and files
		$tags = array(
				'plugins_url',
				'includes_url',
				'bloginfo',
				'bloginfo_url',
				'stylesheet_directory_uri',
				'template_directory_uri',
				'script_loader_src',
				'style_loader_src',
				'stylesheet_uri',
				'category_link',
				'category_feed_link',
				'author_link',
				'author_feed_link',
				'page_link',
				'get_pagenum_link',
				'post_link',
				'post_type_link',
				'attachment_link',
				'icon_dir_uri',
				'wp_get_attachment_url',
				'the_permalink',
				'tag_link',
				'tag_feed_link',
				'feed_link',
				'taxonomy_feed_link',
				'the_feed_link'
		);
		add_filters($tags, array($this,'ReplaceString'));
		
		//Replace upload dir on frontend
		add_filter('upload_dir', array($this, 'ReplaceUploadDir'));
		
		//Replace in wp-mail content
		add_filter('wp_mail', array($this, 'WPMailReplaceContent'));
		
		//Replace hardcoded media directory in post contents
		add_filters('the_content', array($this,'ReplaceHardcodedMediaDirecrory'));

		//Replace hardcoded media directory in admin ajax
		add_action('admin_init', array($this,'ReplaceHardcodedMediaDirecroryAdminAjax'));

		//Regular expression in admin ajax
		add_action('admin_init', array($this,'RegularExpressionAdminAjax'));
		
		//Replace hardcoded media directory in header
		add_action('wp_head', array($this,'ReplaceHardcodedMediaDirecroryHead'));
		
		//Change author and search base
		add_action('init', array($this, 'ChangeBases'));
	
		//Remove the WP specific things from <head>
		add_action('init',array($this, 'CleanWPHead'));
		
		//Rename REST API
		add_action('rest_url_prefix', array($this, 'RenameRestAPI'));
				
		//Remove the WP specific things from <head>
		add_filter('style_loader_tag',array($this, 'CleanStyleTags'));
				
		//Change the query parameters
		add_action( 'init', array($this, 'ChangeQueryParameters') , 10, 1 );
		
		//Change the HTML field names in POST and GET requests
		$this->ReplaceHTMLNames();
		
		//Change the HTML field names in POST and GET requests before any WP hook
		$this->RegexInRequest();
		
		//Set secure query cookie for logged-in user
		add_action('wp_login', array($this, 'CreateSqCookie'), 10, 2);
		
		//Reset secure query cookie
		add_action('wp_logout', array($this, 'ResetSqCookie'));
		
		//Change search field name
		add_action('get_search_form', array($this, 'RenameSearchfield'));

		//Replace admin login/logout urls
		if (isset($this->_settings['redirectDirs']['wp-admin'])){
			add_filter('admin_url', array($this, 'ReplaceAdminUrl'));
		}
		
		//Replace login/logout urls
		if (isset($this->_settings['redirectFiles']['wp-login.php'])){
			$filters = array(
					'login_url',
					'logout_url',
					'register_url',
					'wp_redirect',
			);
			add_filters($filters, array($this, 'ReplaceLoginUrl'));

			add_filter('retrieve_password_message', array($this,'ReplaceRetrievePasswordMessage'));
			
			add_filter('lostpassword_url', array($this,'ReplaceLostpasswordUrl'));
			
			add_action('wp_logout', array($this, 'RedirectLogout'));
		}
		
		//Set cookie to handle folder overrides
		$this->SetConstants();
		add_action('set_auth_cookie', array('SwiftSecurity','SwiftSecuritySetAuthCookie'));
		add_action('clear_auth_cookie', array('SwiftSecurity','SwiftSecurityClearAuthCookies'));
		
		//Replace strings in the content
		add_action('init', array($this, 'ReplaceContent'), 0);
		
		//Replace post url for login forms
		add_action('login_head', array($this, 'ReplaceLoginContent'), 0);
		
		//Replace strings in robots.txt
		if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/robots.txt'){
			add_action('init', array($this, 'ReplaceRobotsTxt'), 0);
		}

		//Remove version argument from scripts
		add_filter( 'style_loader_src', array($this,'RemoveCDNVersion'));
		add_filter( 'script_loader_src', array($this,'RemoveCDNVersion'));
		
		//Replace Theme specific folders and files for Frameworks
		add_action('init', array($this, 'ReplaceThemeExceptions'));
		
		//Fix redirect issues
		add_filter('wp_redirect',array($this,'RedirectFix'));
		
		//Reverse replace filter
		add_filter('swiftsecurity_reverse_replace', array($this, 'ReverseReplaceString'));
		
		//Apply custom exceptions for 3rd party plugins
		$this->CustomPluginExceptions();
		
		//Login redirect based on user roles
		add_filter('login_redirect', array($this, 'LoginRedirect'), 1000, 3 );
		
		//Remove admin bar
		add_action('init', array($this, 'RemoveAdminBar'));
		
		//Hide admin
		add_action('admin_init', array($this, 'HideAdmin'));
		
		//Order enqueued scripts on frontend
		add_action('wp_head', array($this, 'OrderEnqueuedScripts'),8);
			
		add_action('wp_head', array($this, 'CombineScriptsHeader'),8);
		add_action('wp_footer', array($this, 'CombineScriptsFooter'),19);

		//Order combine enqueued styles on frontend
		add_action('wp_head', array($this, 'OrderEnqueuedStyles'),0);
		
		//Change CDN hostnames
		$tags=array(
				'script_loader_src',
				'style_loader_src',
		);
		add_filters($tags, array($this, 'ManageCDN'),10,2);
		
		//Add admin flag to javascript and css files to turn off RegexInClasses, RegexInIds and RegexInJS in admin mode
		add_filter( 'script_loader_src', array($this,'AssetsAdminFlag') );
		add_filter( 'style_loader_src', array($this,'AssetsAdminFlag') );
		
		//Authenticate Super Admin for sub site
		add_action('init', array($this, 'CreateMultisiteSqCookie'));
		
		//Set redirects (eg for 404 redirect)
		add_action('wp',array($this, 'SwiftRedirects'));
		
		//Turn off RocketScript for wp-admin area 
		add_action('admin_init',array($this, 'RocketScript'));
		
		//Force 404 for wp-login.php if REQUEST_URI is wp-login.php even if cgifix cookie is set
		add_action('login_init',array($this, 'HideLogin'));
		
		//Handle domain mapping
		add_filter('swiftsecurity_multisite_rewrite_cond', array($this, 'DomainMapping'));
		
	} 
	
	/**
	 * Destroy all redirects and rewrites
	 * It is using when turn off the module
	 */
	public function Destroy(){
		//Replace WP specific folders and files
		$tags = array(
				'plugins_url',
				'includes_url',
				'bloginfo',
				'bloginfo_url',
				'stylesheet_directory_uri',
				'template_directory_uri',
				'script_loader_src',
				'style_loader_src',
				'stylesheet_uri',
				'category_link',
				'category_feed_link',
				'author_link',
				'author_feed_link',
				'page_link',
				'get_pagenum_link',
				'post_link',
				'post_type_link',
				'attachment_link',
				'icon_dir',
				'icon_dir_uri',
				'wp_get_attachment_url',
				'the_permalink',
				'tag_link',
				'tag_feed_link',
				'feed_link',
				'taxonomy_feed_link',
				'the_feed_link'
		);
		remove_filters($tags, array($this,'ReplaceString'));
		
		//Replace hardcoded media directory in post contents
		remove_filters('the_content', array($this,'ReplaceHardcodedMediaDirecrory'));
		
		//Replace hardcoded media directory in admin ajax
		remove_action('admin_init', array($this,'ReplaceHardcodedMediaDirecroryAdminAjax'));
		
		//Regular expression in admin ajax
		remove_action('admin_init', array($this,'RegularExpressionAdminAjax'));
		
		//Replace hardcoded media directory in header
		remove_action('admin_init', array($this,'ReplaceHardcodedMediaDirecroryHead'));
		
		//Change author and search base
		remove_action('init', array($this, 'ChangeBases'));
	
		//Remove the WP specific things from <head>
		remove_action('init',array($this, 'CleanWPHead'));
				
		//Change the query parameters
		remove_filter( 'init', array($this, 'ChangeQueryParameters') , 10, 1 );
		
		//Set secure query cookie for logged-in user
		remove_action('wp_login', array($this, 'CreateSqCookie'), 10, 2);
		
		//Reset secure query cookie
		remove_action('wp_logout', array($this, 'ResetSqCookie'));
		
		//Change search field name
		remove_action('get_search_form', array($this, 'RenameSearchfield'));

		//Replace admin login/logout urls
		if (isset($this->_settings['redirectDirs']['wp-admin'])){
			remove_filter('admin_url', array($this, 'ReplaceAdminUrl'));
		}
		
		//Replace login/logout urls
		if (isset($this->_settings['redirectFiles']['wp-login.php'])){
			$filters = array(
					'login_url',
					'logout_url',
					'register_url',
					'wp_redirect',
			);
			remove_filters($filters, array($this, 'ReplaceLoginUrl'));
			
			remove_filter('retrieve_password_message', array($this,'ReplaceRetrievePasswordMessage'));
			
			remove_filter('lostpassword_url', array($this,'ReplaceLostpasswordUrl'));
			
			remove_action('wp_logout', array($this, 'RedirectLogout'));
		}
					
		//Replace strings in the content
		remove_action('init', array($this, 'ReplaceContent'), 0);
		
		//Replace post url for login forms
		remove_action('login_head', array($this, 'ReplaceLoginContent'), 0);
		
		//Replace strings in robots.txt
		remove_action('init', array($this, 'ReplaceRobotsTxt'), 0);
		
		//Replace Theme specific folders and files for Frameworks
		remove_action('init', array($this, 'ReplaceThemeExceptions'));
		
		//Fix redirect issues
		remove_filter('wp_redirect',array($this,'RedirectFix'));
		
		//Login redirect based on user roles
		remove_filter('login_redirect', array($this, 'LoginRedirect'), 10, 3 );
	}
	
	public function RenameRestAPI(){
		if (isset($this->_settings['otherFiles']['wp-json']) && !empty($this->_settings['otherFiles']['wp-json'])){
			return $this->_settings['otherFiles']['wp-json'];
		}
	}
	
	/**
	 * Clean Wordpress specific header elements
	 * @todo it should be optional
	 */
	public function CleanWPHead(){
		//Remove the generator meta tag
		if (empty($this->_settings['metas']['generator'])){
			remove_action('wp_head', 'wp_generator');
		}
		//Or replace the generator meta tag
		else{
			add_filter('the_generator', array($this, 'ReplaceGenerator'));
		}

		//Remove pingback
		remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
		add_filter('wp_headers', array($this, 'RemovePingback'));
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
		add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );
		
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}
	
	/**
	 * Rename search field
	 * @param string $form
	 */
	public function RenameSearchfield($form){
		return preg_replace('~name="s"~','name="'.$this->_settings['queries']['s'].'"',$form);
	}
		
	/**
	 * Replace or remove generator
	 * @param string $generator
	 */
	public function ReplaceGenerator($generator){
		$generator = preg_replace('~<meta name="generator" content="([^"]*)" />~', '<meta name="generator" content="'.$this->_settings['metas']['generator'].'" />' ,$generator);
		return $generator;
	}
	
	public function ChangeBases(){
		global $wp_rewrite;
		
		//Change author base
		$wp_rewrite->author_base = $this->_settings['permalinks']['author'];
		//Change search base
		$wp_rewrite->search_base = $this->_settings['permalinks']['search'];
	}
	
	
	/**
	 * Change permalinks and flush rewrite rules.
	 */
	public function ChangePermalinks() {
		global $wp_rewrite;
				
		//Change category base
		$wp_rewrite->set_category_base($this->_settings['permalinks']['category']);

		//Change tag base
		$wp_rewrite->set_tag_base($this->_settings['permalinks']['tag']);
		
		//Flush rules
		delete_option( 'rewrite_rules' );
	}
	
	/**
	 * Set permalinks to default 
	 */
	public static function DefaultPermalinks(){
		global $wp_rewrite;
	
		//Change category base
		$wp_rewrite->set_category_base('category');
			
		//Change tag base
		$wp_rewrite->set_tag_base('tag');

		//Flush rules
		delete_option( 'rewrite_rules' );
	}
	
	/**
	 * Run Regular expression in source hardcoded values in wp-mail content
	 * @todo add regex in wp-mail
	 */
	public function WPMailReplaceContent($atts){
		$atts['message'] = $this->ReplaceContentCallback($atts['message']);
		return $atts;
	}
	
	/**
	 * Replace admin URL
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public function ReplaceAdminUrl($url, $path = '', $blog_id = null) {
		global $pagenow;
		if ($pagenow != 'sites.php' && (!function_exists('ms_is_switched') || !ms_is_switched()) && (!defined('SWIFTSECURITY_ORIGINAL_ADMIN_URL') || !SWIFTSECURITY_ORIGINAL_ADMIN_URL) ){
			return str_replace(site_url() . '/wp-admin', site_url() . '/' . $this->_settings['redirectDirs']['wp-admin'] ,$url);
		}
		return $url;
	}
	
	/**
	 * Replace login (and logout) URL
	 * @param string $path
	 * @return string
	 */
	public function ReplaceLoginUrl($path = '') {
		if (preg_match('~wp-login\.php~',$path)){
			$path = str_replace($this->sitePath, '', $path);
			$path = preg_replace('~wp-login\.php~', $this->_settings['redirectFiles']['wp-login.php'] ,htmlspecialchars_decode($path));
			//Add redirect to for register_url
			parse_str(parse_url($path, PHP_URL_QUERY),$QueryStrings);
			if (isset($QueryStrings['action']) && $QueryStrings['action'] == 'register'){
				$QueryStrings['redirect_to'] = site_url() . '/' . $this->_settings['redirectFiles']['wp-login.php'] . '?checkemail=registered';
			}
			return site_url(parse_url($path, PHP_URL_PATH) . (count($QueryStrings) > 0 ? '?'.http_build_query($QueryStrings) : ''));
		}
		return $path;
	}
	
	/**
	 * Replace retrieve password message 
	 * @param string $message
	 * @return string
	 */
	public function ReplaceRetrievePasswordMessage($message = '') {
		return  preg_replace('~wp-login\.php~', $this->_settings['redirectFiles']['wp-login.php'], $message);
	}
	
	/**
	 * Replace lost password URL
	 * @param string $path
	 * @param string $redirect
	 */
	public function ReplaceLostpasswordUrl($path = '', $redirect = '') {
		$path = str_replace($this->sitePath, '', $path);
		$redirect = preg_replace('~wp-login\.php~', $this->_settings['redirectFiles']['wp-login.php'] ,$redirect);
		return preg_replace('~wp-login\.php~', $this->_settings['redirectFiles']['wp-login.php'] ,$path);
	}
	
	/**
	 * Redirect to rewrited login url instead of wp-login.php 
	 * @param string $path
	 * @return string
	 */
	public function RedirectLogout() {
		$url = (preg_match('~^https?://~', $this->_settings['customLogoutURL']) ? $this->_settings['customLogoutURL'] : home_url($this->_settings['customLogoutURL']));
		wp_redirect($url);
		exit;
	}
	
	/**
	 * Redirect users after login based on user roles
	 * @param string $url
	 * @param string $request
	 * @param WP_User $user
	 */
	public function LoginRedirect($url, $request, $user ){
		if (isset($this->_settings['userRoles']) && $user && is_object( $user ) && is_a( $user, 'WP_User' ) ){
			if(!empty($this->_settings['userRoles'][$user->roles[0]]['loginRedirect'])) {
				$url = $this->_settings['userRoles'][$user->roles[0]]['loginRedirect'];
			}
		}	
		return $url;
	}
	
	/**
	 * Remove admin bar based on user roles
	 */
	public function RemoveAdminBar(){
		if ( is_user_logged_in() ) {
			$current_user   = wp_get_current_user();
	
			if (isset($current_user->roles[0]) && isset($this->_settings['userRoles'][$current_user->roles[0]]['removeAdminBar']) && $this->_settings['userRoles'][$current_user->roles[0]]['removeAdminBar'] == 'enabled'){
				add_filter('show_admin_bar', '__return_false');
			}
		}
	}
	
	/**
	 * Hide admin based on user roles
	 */
	public function HideAdmin(){
		if ( is_user_logged_in() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
			$current_user  = wp_get_current_user();
			if (isset($current_user->roles[0]) && isset($this->_settings['userRoles'][$current_user->roles[0]]['hideAdmin']) && $this->_settings['userRoles'][$current_user->roles[0]]['hideAdmin'] == 'enabled'){
				define('IFRAME_REQUEST',true);
				
				SwiftSecurity::ClassInclude('Misc');
				global $Misc_ReturnFalse;
				$Misc_ReturnFalse = new Misc();
				$GLOBALS['current_screen'] = $Misc_ReturnFalse;
				
				header("HTTP/1.0 404 Not Found");
				global $wp_query;
				global $wp_rewrite;
				$wp_rewrite->permalink_structure = '';
				$wp_query->is_404 = true;
				get_template_part('404');
				die;
			}
		}
	}
	
	/**
	 * Set the new constants (like PLUGINS_COOKIE_PATH, ADMIN_COOKIEPATH, etc...)
	 */
	public function SetConstants(){		
		if (!defined('SQ')){
			define('SQ', $this->_globalSettings['sq']);
		}
		
		//Admin URL
		if (!defined('SWIFT_ADMIN_URL')){
			$admin_url = (isset($this->_settings['redirectDirs']['wp-admin']) ? $this->_settings['redirectDirs']['wp-admin'] : 'wp-admin');
			define('SWIFT_ADMIN_URL', $admin_url);
		}
		
		//Login URL
		if (!defined('SWIFT_LOGIN_URL')){
			$login_url = (isset($this->_settings['redirectFiles']['wp-login.php']) ? $this->_settings['redirectFiles']['wp-login.php'] : 'wp-login.php');
			define('SWIFT_LOGIN_URL', $login_url);
		}
		
		//Cookie path
		if (!defined('COOKIEPATH')){
			define( 'COOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'home' ) . '/' ) );
		}
		
		//Site cookie path
		if (!defined('SITECOOKIEPATH')){
			define( 'SITECOOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'siteurl' ) . '/' ) );
		}
		
		//Plugins cookie path
		if (!defined('SWIFT_PLUGINS_COOKIE_PATH')){
			$plugin_cookie_path = (isset($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins']) ? SITECOOKIEPATH . $this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] : PLUGINS_COOKIE_PATH);
			define('SWIFT_PLUGINS_COOKIE_PATH', $plugin_cookie_path);
		}
		
		//Admin cookie path
		if (!defined('SWIFT_ADMIN_COOKIEPATH')){
			$admin_cookie_path = (isset($this->_settings['redirectDirs']['wp-admin']) ? SITECOOKIEPATH . $this->_settings['redirectDirs']['wp-admin'] : ADMIN_COOKIEPATH);
			define('SWIFT_ADMIN_COOKIEPATH', $admin_cookie_path);
		}
		
		//Cookie domain
		if (!defined('COOKIE_DOMAIN')){
			define( 'COOKIE_DOMAIN', parse_url(site_url(),PHP_URL_HOST));
		}

	}
	
	/**
	 * Overwrite the query parameters (like http://typicallwpsite.com/?p=123)
	 */
	public function ChangeQueryParameters(){
		if (!is_admin() && !(isset($_COOKIE[$this->_globalSettings['sq']]) && isset($_GET['preview']))){
			foreach ((array)$this->_settings['queries'] as $key=>$value){
				if (isset($_GET[$value])){
					$_GET[$key] = $_GET[$value];
				}
				else{
					unset($_GET[$key]);
				}
			}
		}
	}
	
	/**
	 * Overwrite the HTML names in GET and POST
	 */
	public function ReplaceHTMLNames(){
		if (isset($this->_settings['regexInNames']) && !is_admin()){
			foreach ((array)$this->_settings['regexInNames'] as $key=>$value){
				//GET
				if (isset($_GET[$value])){
					$_GET[$key] = $_GET[$value];
				}
				else{
					unset($_GET[$key]);
				}
				//POST
				if (isset($_POST[$value])){
					$_POST[$key] = $_POST[$value];
				}
				else{
					unset($_POST[$key]);
				}
				//REQUEST
				if (isset($_REQUEST[$value])){
					$_REQUEST[$key] = $_REQUEST[$value];
				}
				else{
					unset($_REQUEST[$key]);
				}
			}
		}
	}
	
	/**
	 * Change any string in GET, POST and Cookie requests
	 */
	public function RegexInRequest(){
		if (isset($this->_settings['regexInRequest']) && (!isset($_POST['sq']) || $_POST['sq'] != $this->_globalSettings['sq'])){
			//GET	
			foreach ((array)$this->_settings['regexInRequest'] as $pattern=>$replacement){
				$_GET = swift_replace_deep($_GET, $pattern, $replacement);
				$_POST = swift_replace_deep($_POST, $pattern, $replacement);
				$_COOKIE = swift_replace_deep($_COOKIE, $pattern, $replacement);
				$_REQUEST = swift_replace_deep($_REQUEST, $pattern, $replacement);
			}
		}
	}
	
	/**
	 * Start output buffering to replace values in content
	 * Calls ReplaceContentCallback
	 */
	public function ReplaceContent(){
		if(!is_admin()){
			ob_start(array(&$this,"ReplaceContentCallback"));
		}
	}
	
	/**
	 * Start output buffering to replace values in login page content
	 * Calls ReplaceLoginContentCallback
	 */
	public function ReplaceLoginContent(){
		if(!is_admin()){
			ob_start(array(&$this,"ReplaceLoginContentCallback"));
		}
	}
	
	/**
	 * Start output buffering to replace values in robots.txt
	 * Calls ReplaceString
	 */
	public function ReplaceRobotsTxt(){
		ob_start(array(&$this,"ReplaceString"));
	}
	
	/**
	 * Replace links in page content
	 * @param string $buffer
	 * @return string
	 */
	public function ReplaceContentCallback($buffer) {
		// Replace hardcoded wp-admin url (WP 4.5 compatibility)
		$buffer = str_replace(site_url('wp-admin'), site_url($this->_settings['redirectDirs']['wp-admin']), $buffer);
		
		//Replace orher files (only in URLs)
		foreach ((array)$this->_settings['otherFiles'] as $key=>$value){
			$buffer = str_replace($key, $value, $buffer);
			$buffer = str_replace($this->ReplaceString($key), $value, $buffer);
		}
		
		//Replace orher dirs (only in URLs)
		foreach ((array)$this->_settings['otherDirs'] as $key=>$value){
			$buffer = str_replace($key, $value, $buffer);
			$buffer = str_replace($this->ReplaceString($key), $value, $buffer);
		}
		
		//Change wp-comments-post.php
		$buffer = str_replace(site_url('wp-comments-post.php'), site_url($this->_settings['redirectFiles']['wp-comments-post.php']), $buffer);		
		
		//Replace in content 
		foreach ((array)$this->_settings['regexInSource'] as $key=>$value){
			$buffer = preg_replace('~'.$key.'~', $value, $buffer);
		}		
		
		//Replace in CSS classes
		foreach ((array)$this->_settings['regexInClasses'] as $key=>$value){
			$buffer = preg_replace('~class=([\'"])([^\'"]*?)'.$key.'([^\'"]*?)([\'"])~', "class=$1$2$value$3$1", $buffer);
		}
		
		//Replace in HTML Names
		foreach ((array)$this->_settings['regexInNames'] as $key=>$value){
			$buffer = preg_replace('~name=([\'"])'.$key.'([\'"])~', "name=$1$value$1", $buffer);
		}
		
		//Replace in HTML ids
		foreach ((array)$this->_settings['regexInIds'] as $key=>$value){
			$buffer = preg_replace('~id=([\'"])([^\'"]*?)'.$key.'([^\'"]*?)([\'"])~', "id=$1$2$value$3$1", $buffer);
		}
		
		//Remove HTML comments
		if (isset($this->_settings['removeHTMLComments']) && $this->_settings['removeHTMLComments'] == 'enabled'){
			$buffer = preg_replace('~<!--((?!\[(if|endif))[^>])+-->~s', '', $buffer);
		}

		if (isset($this->_settings['combineCSS']['status']) && $this->_settings['combineCSS']['status'] == 'enabled' && !empty($this->CombinedCSSFilename)){
			$buffer = str_replace('%SWIFTSECURITY_COMBINED_CSS_PLACEHOLDER%.css', $this->CombinedCSSFilename, $buffer);
		}
		
		if (isset($this->_settings['CDN']['Media']) && !empty($this->_settings['CDN']['Media'])){
			$buffer = preg_replace('~'.preg_replace('~http(s)?://~','',site_url()).'([^"\'\s]*).(jpe?g|png|gif|ico|swf|flv|mpeg|mpg|mpe|3gp|mov|avi|wav|flac|mp2|mp3|m4a|mp4|m4p|aac)~i',preg_replace('~http(s)?://~','',$this->_settings['CDN']['Media'])."$1.$2", $buffer);
		}
		
		return $buffer;
	}
	
	/**
	 * Replace post action for login page
	 * @param string $buffer
	 * @return string
	 */
	public function ReplaceLoginContentCallback($buffer) {
			$buffer = str_replace('wp-login.php', $this->_settings['redirectFiles']['wp-login.php'], $buffer);
			return $buffer;
	}
	
	/**
	 * Replace the hardcoded media dir (wp-content/uploads) in the_content
	 * @param string $content
	 * @return string
	 */
	public function ReplaceHardcodedMediaDirecrory($content){
		$content = preg_replace('~'.home_url(WP_CONTENT_DIRNAME . '/uploads').'/(.*?)~',home_url($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads'])."/$1",$content);
		$content = preg_replace('~'.preg_replace('~/~','\/',home_url(WP_CONTENT_DIRNAME . '/uploads')).'/(.*?)~',preg_replace('~/~','\/',home_url($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']))."/$1",$content);
		return $content;
	}
	
	/**
	 * Start output buffer for admin ajax requests
	 */
	public function ReplaceHardcodedMediaDirecroryAdminAjax(){
		if (defined('DOING_AJAX') && DOING_AJAX){
			ob_start(array(&$this,"ReplaceHardcodedMediaDirecroryCallback"));
		}
	}
	
	/**
	 * Start output buffer for admin ajax requests
	 */
	public function RegularExpressionAdminAjax(){
		if (defined('DOING_AJAX') && DOING_AJAX){
			ob_start(array(&$this,"RegularExpressionAdminAjaxCallback"));
		}
	}
	
	
	/**
	 * Start output buffer in header only
	 */
	public function ReplaceHardcodedMediaDirecroryHead(){
			ob_start(array(&$this,"ReplaceHardcodedMediaDirecroryCallback"));
			ob_flush();
	}
	
	/**
	 * Replace the hardcoded media dir (wp-content/uploads) in admin-ajax
	 * @param string $content
	 * @return string
	 */
	public function ReplaceHardcodedMediaDirecroryCallback($content){
			//Don't replace in admin
			if (is_admin()){
				return $content;
			}
			
			$content = preg_replace('~'.preg_replace('~^https?:~', '', home_url(WP_CONTENT_DIRNAME . '/uploads')).'/(.*?)~',preg_replace('~^https?:~', '', home_url($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']))."/$1",$content);
			$content = str_replace(preg_replace('~/~','\/',preg_replace('~^https?:~', '', home_url(WP_CONTENT_DIRNAME . '/uploads'))),preg_replace('~/~','\/',preg_replace('~^https?:~', '', home_url($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']))),$content);
			return $content;
	}
	
	/**
	 * Rugular exressio replace in admin-ajax
	 * @param string $content
	 * @return string
	 */
	public function RegularExpressionAdminAjaxCallback($content){
		//Replace in in admin ajax
		if (isset($this->_settings['regexInAjax'])){
			foreach ((array)$this->_settings['regexInAjax'] as $key=>$value){
				$content = preg_replace('~'.$key.'~', $value, $content);
			}
		}
		return $content;
	}
	
	/**
	 * Run ReplaceString recursively on an array
	 * @param array $array
	 * @return array
	 */
	public function RecursiveReplaceString($array){
		foreach ($array as $key=>$value){
			if (is_array($value)){
				$array[$key]= $this->RecursiveReplaceString($value);
			}
			else{
				$array[$key] = $this->ReplaceString($value);
			}
		}
		return $array;
	}
	
	/**
	 * Replace links, query strings in string
	 * @param string $string
	 * @return string
	 */
	function ReplaceString($string) {
			//Don't replace in admin panel
			if(is_admin()){
					return $string;
			}
		
			//replace theme directory and css for child themes
			if (defined ('TEMPLATEPATH') && is_child_theme()){
				$string = preg_replace('~([^[a-zA-Z0-9]]*)?' . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'([^[a-zA-Z0-9]]*)?~', "$1{$this->_settings['redirectChildTheme']}$2", $string);
				$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$this->_settings['redirectChildTheme'].'/style.css([^[a-zA-Z0-9]]*)?~', "$1{$this->_settings['redirectChildTheme']}/{$this->_settings['redirectChildThemeStyle']}$2", $string);
			}
		
			//replace theme directory
			$string = preg_replace('~([^[a-zA-Z0-9]]*)?' . WP_CONTENT_DIRNAME . '/themes/'.get_template().'([^[a-zA-Z0-9]]*)?~', "$1{$this->_settings['redirectTheme']}$2", $string);	
			
			//replace theme style.css
			$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$this->_settings['redirectTheme'].'/style.css([^[a-zA-Z0-9]]*)?~', "$1{$this->_settings['redirectTheme']}/{$this->_settings['redirectThemeStyle']}$2", $string);
			
			// replace plugin directory names
			foreach ((array)$this->_settings['plugins'] as $key=>$value){
				$string = preg_replace('~'. WP_CONTENT_DIRNAME . '/plugins/'.$key.'/~', WP_CONTENT_DIRNAME . "/plugins/$value/", $string);
				$string = preg_replace('~'.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'].'/'.$key.'/~', $this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins']."/$value/", $string);
			}
			
			// replace files
			foreach ((array)$this->_settings['redirectFiles'] as $key=>$value){
				$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$key.'([^[a-zA-Z0-9]]*)?~', "$1$value$2", $string);
			}
			
			// replace directory names
			foreach ((array)$this->_settings['redirectDirs'] as $key=>$value){
				$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$key.'/([^[a-zA-Z0-9]]*)?~', "$1$value/$2", $string);
			}
			
			// replace queries
			foreach ((array)$this->_settings['queries'] as $key=>$value){
				$string = preg_replace('~(\?|&)'.$key.'=~', "$1$value=", $string);
			}				
	
		return $string;
	}
	
	/**
	 * Change upload directory array on frontend
	 * @param array $paths
	 * @return array
	 */
	public function ReplaceUploadDir($paths){
		//Return the original array on backend
		if (is_admin()){
			return $paths;
		}
		
		//Change paths on frontend
		$paths['url'] = untrailingslashit($this->ReplaceString(trailingslashit($paths['url'])));
		$paths['baseurl'] = untrailingslashit($this->ReplaceString(trailingslashit($paths['baseurl'])));
		
		return $paths;
	}
	
	/**
	 * Replace CSS class names and HTML ids in $buffer for CDN proxy
	 * @param string $buffer
	 * @return string
	 */
	public function ReplaceCDNProxy($buffer){
		//Replace in CSS classes
		foreach ((array)$this->_settings['regexInClasses'] as $key=>$value){
			$buffer = preg_replace('~\.'.$key.'~', ".$value", $buffer);
		}
		
		//Replace in HTML ids
		foreach ((array)$this->_settings['regexInIds'] as $key=>$value){
			$buffer = preg_replace('~\#'.$key.'~', "#$value", $buffer);
		}
		
		return $buffer;
	}
	
	/**
	 * Turn off RegexInClasses, RegexInIds and RegexInJS in admin mode
	 * @param unknown $src
	 * @return string
	 */
	public function AssetsAdminFlag ( $src ) {
		if (is_admin()){
			if (parse_url(site_url(),PHP_URL_HOST) == parse_url($src,PHP_URL_HOST)){
				$SrcPath = parse_url($src,PHP_URL_PATH);
				parse_str(parse_url($src, PHP_URL_QUERY), $SrcQuery);
				$SrcQuery['admin_' . 'sq_' .md5($this->_globalSettings['sq'])] = '1';
					
				return $SrcPath.'?'.http_build_query($SrcQuery);
			}
		}
	
		return $src;
	}
	
	/**
	 * Removes the xmlrpc.php pingback from header
	 * @param array $headers
	 */
	public function RemovePingback($headers){
		unset($headers['X-Pingback']);
		return $headers;
	}
	
	/**
	 * Remove version argument from scripts and styles
	 * @param string $src
	 */
	public function RemoveCDNVersion($src){
		if(strpos($src, '?ver=')){
        	$src = remove_query_arg( 'ver', $src );
		}
    	return $src;
	}
	
	/**
	 * Replace text in Javascript files (eg: variables) for CDN proxy
	 * @param string $buffer
	 * @return string
	 */
	public function ReplaceInJS($buffer){
		if (isset($this->_settings['regexInJS'])){
			foreach ((array)$this->_settings['regexInJS'] as $key=>$value){
				$buffer = preg_replace('~'.$key.'~', $value, $buffer);
			}
		}
	
		return $buffer;
	}
	
	/**
	 * Replace text in CSS files (eg: variables) for CDN proxy
	 * @param string $buffer
	 * @return string
	 */
	public function ReplaceInCSS($buffer){
		if (isset($this->_settings['regexInCSS'])){
			foreach ((array)$this->_settings['regexInCSS'] as $key=>$value){
				$buffer = preg_replace('~'.$key.'~', $value, $buffer);
			}
		}
	
		return $buffer;
	}
	
	/**
	 * Reverse replace links, query strings in string for CSS and JS proxy
	 * @param string $string
	 * @return string
	 */
	public function ReverseReplaceString($string) {
		//replace child theme style.css
		$string = str_replace($this->_settings['redirectChildTheme'].'/'.$this->_settings['redirectChildThemeStyle'], $this->_settings['redirectChildTheme'].'/style.css', $string);
		
		//replace child theme directory
		$string = str_replace($this->_settings['redirectChildTheme'], WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet(), $string);
				
		//replace theme style.css
		$string = preg_replace('~([^[a-zA-Z0-9]]*)?' . $this->_settings['redirectTheme'] . '/' .$this->_settings['redirectThemeStyle'].'([^[a-zA-Z0-9]]*)?~', "$1" . WP_CONTENT_DIRNAME . "/themes/".get_template()."/style.css$2", $string);
		
		//replace theme directory
		$string = preg_replace('~([^[a-zA-Z0-9]]*)?' . $this->_settings['redirectTheme'] . '([^[a-zA-Z0-9]]*)?~', "$1" . WP_CONTENT_DIRNAME . "/themes/".get_template()."$2", $string);
		
		// replace directory names
		foreach ((array)$this->_settings['redirectDirs'] as $key=>$value){
			$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$value.'([^[a-zA-Z0-9]]*)?~', "$1$key$2", $string);
		}
			
		// replace plugin directory names
		foreach ((array)$this->_settings['plugins'] as $key=>$value){
			$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$value.'([^[a-zA-Z0-9]]*)?~', "$1$key$2", $string);
		}
	
		// replace files
		foreach ((array)$this->_settings['redirectFiles'] as $key=>$value){
			$string = preg_replace('~([^[a-zA-Z0-9]]*)?'.$value.'([^[a-zA-Z0-9]]*)?~', "$1$key$2", $string);
		}
			
		// replace queries
		foreach ((array)$this->_settings['queries'] as $key=>$value){
			$string = preg_replace('~(\?|&)'.$key.'=~', "$1$value=", $string);
		}
	
		return $string;
	}
	
	/**
	 * Fix redirect issues
	 * After add new user WordPress redirects to wp-admin/users.php, this redirect is wrong if Hide WordPress is on, so we fix it. 
	 * @param string $location
	 * @param int $status
	 * @return string
	 */
	public function RedirectFix($location){
		if (preg_match('~^'.preg_quote(home_url('users.php')).'~',$location)){
			$location = str_replace('users.php', $this->_settings['redirectDirs']['wp-admin'] . '/users.php', $location);
		}
		else if (preg_match('~^'.preg_quote(home_url('admin.php')).'~',$location)){
			$location = str_replace('admin.php', $this->_settings['redirectDirs']['wp-admin'] . '/admin.php', $location);
		}
		return $location;
	}
	
	/**
	 * Order enqueued scripts
	 */
	public function OrderEnqueuedScripts(){
		if (isset($this->_settings['manageJS']) && $this->_settings['manageJS'] == 'enabled'){
			
			add_filter('script_loader_tag', array($this, 'ScriptEmbedType'),10,2);
			
			if(isset($this->_settings['enqueuedScriptsHeader']) || isset($this->_settings['enqueuedScriptsFooter'])){
				global $wp_scripts;
				if (!isset($wp_scripts->queue) || !is_array($wp_scripts->queue)){
					//Nothing to do
					return;
				}
				
				$original_queue = $wp_scripts->queue;

				$this->_settings['enqueuedScriptsHeader'] = (isset($this->_settings['enqueuedScriptsHeader']) ? $this->_settings['enqueuedScriptsHeader'] : array());
				$this->_settings['enqueuedScriptsFooter'] = (isset($this->_settings['enqueuedScriptsFooter']) ? $this->_settings['enqueuedScriptsFooter'] : array());
				
				foreach ((array)$this->_settings['enqueuedScriptsHeader'] as $handle){
					if (isset($wp_scripts->registered[$handle])){
						if (isset($wp_scripts->registered[$handle]->extra['group'])){
							unset($wp_scripts->registered[$handle]->extra['group']);
						}
					}
					//Remove duplicates
					if (in_array($handle, $original_queue)){
						$key = array_search($handle, $original_queue);
						unset ($original_queue[$key]);
					}
					
					//Remove unregistered scripts
					if (!isset($wp_scripts->registered[$handle])){
						unset ($this->enqueuedScriptsHeader[$handle]);
						unset ($original_queue[$handle]);
					}
					
				}
				foreach ((array)$this->_settings['enqueuedScriptsFooter'] as $handle){
					if (isset($wp_scripts->registered[$handle])){
						if (!isset($wp_scripts->registered[$handle]->extra['group'])){
							$wp_scripts->registered[$handle]->extra['group'] = 1;
						}
					}
					//Remove duplicates
					if (in_array($handle, $original_queue)){
						$key = array_search($handle, $original_queue);
						unset ($original_queue[$key]);
					}
					
					//Remove unregistered scripts
					if (!isset($wp_scripts->registered[$handle])){
						unset ($this->enqueuedScriptsFooter[$handle]);
						unset ($original_queue[$handle]);
					}
					
				}
				
				$wp_scripts->queue = array_merge((array)$this->_settings['enqueuedScriptsHeader'], (array)$this->_settings['enqueuedScriptsFooter'], (array)$original_queue);
			}
		}
	}

	/**
	 * Combine scripts in header
	 */
	public function CombineScriptsHeader(){
			global $wp_scripts;	
			
			if ((!isset($_GET['SwiftSecurity']) || $_GET['SwiftSecurity'] != 'manage-assets') && isset($this->_settings['combineHeaderJS']['status']) && $this->_settings['combineHeaderJS']['status'] == 'enabled' && !empty($this->_settings['enqueuedScriptsHeader'])){
				$header_scripts = array();
				
				foreach ($wp_scripts->queue as $script){
					if (preg_match('~^'.site_url().'~',swift_get_absolute_url($wp_scripts->registered[$script]->src)) && isset($wp_scripts->registered[$script]) && !isset($wp_scripts->registered[$script]->extra['group'])){
						$this->enqueuedScriptDependences = array();
						$this->GetScriptDependences($script);
						$header_scripts = array_merge($this->enqueuedScriptDependences, $header_scripts);
						$header_scripts[] = $script;
						unset($wp_scripts->queue[array_search($script,$wp_scripts->queue)]);
					}
					
				}
				
				//Set array for scripts which are already handled in header, to prevent duplications in footer (eg jquery, jquery-migrate)
				$this->handledHeaderScripts = $header_scripts;
				
				$filename = preg_replace('~\.js$~', '-'.hash('crc32', implode('-',$header_scripts)).'.js', $this->_settings['combineHeaderJS']['name']);
				if (!file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/' . $filename) || filesize(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/' . $filename) == 0){
					@mkdir(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineHeaderJS']['name']), 0777, true);
					@chmod(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineHeaderJS']['name']), 0777);

					$content = $this->CombineAssets($header_scripts);					
					file_put_contents(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/'.$filename, $content);
				}
				
				add_filter('script_loader_tag', array($this, 'RemovejQuery'),10,2);
				wp_register_script('header-min', site_url() . '/' . $filename);
				$wp_scripts->queue[] = 'header-min';
			}	
	}
	
	/**
	 * Combine scripts in footer
	 */
	public function CombineScriptsFooter(){
		global $wp_scripts;
		
		if ((!isset($_GET['SwiftSecurity']) || $_GET['SwiftSecurity'] != 'manage-assets') && isset($this->_settings['combineFooterJS']['status']) && $this->_settings['combineFooterJS']['status'] == 'enabled' && !empty($this->_settings['enqueuedScriptsFooter'])){
			$footer_scripts = array();
			foreach ($wp_scripts->queue as $script){
				if ($script != 'header-min' && preg_match('~^'.site_url().'~',swift_get_absolute_url($wp_scripts->registered[$script]->src)) && isset($wp_scripts->registered[$script])){
					$this->enqueuedScriptDependences = array();
					$this->GetScriptDependences($script);
					$footer_scripts = array_merge($this->enqueuedScriptDependences,$footer_scripts);
					$footer_scripts[] = $script;
					unset($wp_scripts->queue[array_search($script, $wp_scripts->queue)]);
				}
			}

			//Remove duplicated scripts (eg jquery, jquery-migrate)
			$footer_scripts = array_diff($footer_scripts, $this->handledHeaderScripts);
			foreach ($footer_scripts as $key=>$value){
				if (in_array($value,$this->handledHeaderScripts)){
					unset($footer_scripts[$key]);
				}
			}
			
			$filename = preg_replace('~\.js$~', '-'.hash('crc32', implode('-',$footer_scripts)).'.js', $this->_settings['combineFooterJS']['name']);				
			if (!file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/' . $filename) || filesize(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/' . $filename) == 0){
				@mkdir(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineFooterJS']['name']), 0777, true);
				@chmod(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineFooterJS']['name']), 0777);
		
				$content = $this->CombineAssets($footer_scripts);
				file_put_contents(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/'.$filename, $content);
			}
			wp_register_script('footer-min', site_url() . '/' . $filename, array(), false, true);
			$wp_scripts->queue[] = 'footer-min';
		}
	}
	
	/**
	 * Get scripts dependencies and set it in $this->enqueuedScriptDependences
	 * @param string $handle
	 */
	public function GetScriptDependences($handle){
		global $wp_scripts;
		
		foreach ((array)$wp_scripts->registered[$handle]->deps as $dep){
			if (!in_array($dep, $this->enqueuedScriptDependences)){
				$this->GetScriptDependences($dep);
				$this->enqueuedScriptDependences[] = $dep;
			}
		} 
	}
	
	/**
	 * Get style dependencies and set it in $this->enqueuedStyleDependences
	 * @param string $handle
	 */
	public function GetStyleDependences($handle){
		global $wp_styles;
	
		foreach ((array)$wp_styles->registered[$handle]->deps as $dep){
			if (!in_array($dep, $this->enqueuedStyleDependences)){
				$this->GetStyleDependences($dep);
				$this->enqueuedStyleDependences[] = $dep;
			}
		}
	}
	
	
	/**
	 * Filter enqueued styles, allow to enqueue styles with conditional and header-min.css
	 * @param string $tag
	 * @param string $handle
	 */
	public function FilterEnqueuedStyles($tag, $handle){
		global $wp_styles;
		if (isset($wp_styles->registered[$handle]) && ($handle == 'header-min' || $handle == 'admin-bar' || isset($wp_styles->registered[$handle]->extra['conditional']) || !preg_match('~^'.site_url().'~',swift_get_absolute_url($wp_styles->registered[$handle]->src)) || $wp_styles->registered[$handle]->args == 'print')){
			return $tag;
		}
		else{
			$this->StylesToCombine[] = $handle;
			return false;
		}
	}
	
	/**
	 * Order enqueued styles for combine them
	 */
	public function OrderEnqueuedStyles(){
		if (!is_admin() && isset($this->_settings['combineCSS']['status']) && $this->_settings['combineCSS']['status'] == 'enabled'){
			global $wp_styles;
					
			wp_enqueue_style('header-min', site_url() . '/' . '%SWIFTSECURITY_COMBINED_CSS_PLACEHOLDER%.css');

			add_filter('style_loader_tag',array($this,'FilterEnqueuedStyles'),10,2);
			add_action('wp_footer', array($this,'CatchAllEnqueuedStyles'),9999);
		}
	}
	
	/**
	 * Catch all (proper- and late enqueued styles and put them the combined CSS file)
	 * @return unknown
	 */
	public function CatchAllEnqueuedStyles(){
		global $wp_styles;
		
		foreach ((array)$wp_styles->queue as $handle){
			if (isset($wp_styles->registered[$handle]) && !empty($wp_styles->registered[$handle]->deps)){
				$this->enqueuedStyleDependences = array();
				$this->GetStyleDependences($handle);
				$wp_styles->queue = array_merge($this->enqueuedStyleDependences, $wp_styles->queue);
				$wp_styles->registered[$handle]->deps = array('header-min');
			}
		}
		
		$this->CombinedCSSFilename = preg_replace('~\.css$~', '-'.hash('crc32', implode('-',$wp_styles->queue)).'.css', $this->_settings['combineCSS']['name']);
		if (!file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/'.$this->CombinedCSSFilename) || filesize(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/'.$this->CombinedCSSFilename) == 0){
			@mkdir(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineCSS']['name']), 0777, true);
			@chmod(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . dirname($this->sitePath.'/'.$this->_settings['combineCSS']['name']), 0777);
				
			$content = $this->CombineAssets($this->StylesToCombine, 'css');
			file_put_contents(SWIFTSECURITY_PLUGIN_DIR . '/cache'. $this->cache_site_url_padding . $this->sitePath . '/'.$this->CombinedCSSFilename, $content);
		}
	}
	
	/**
	 * Combine CSS and JS files
	 * @param array $handles
	 * @param string $asset
	 * @return string
	 */
	public function CombineAssets($handles, $asset = 'js'){
		global $wp_query;
		//Prevent infinite loop on 404 pages
		if ($wp_query->is_404){
			return;
		}
		
		if ($asset == 'js'){
			global $wp_scripts;
			$content = '';
			$queued = array();
						
			foreach ((array)$handles as $handle){
				if (in_array($handle, $queued)){
					continue;
				}
				
				$url = swift_get_absolute_url($wp_scripts->registered[$handle]->src);
				
				if (preg_match('~^'.site_url().'~',$url) && !isset($wp_scripts->registered[$handle]->extra['conditional'])){
					$url = $this->ReplaceString($url);
					
					//Add sq for the request
					parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
					$query_string[$this->_globalSettings['sq']] = 1;
					$url = $url . '?'.http_build_query($query_string);
						
					$response = wp_remote_get($url, array('timeout' => 60, 'sslverify' => false, 'user-agent'=> 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1'));
					if (!is_wp_error($response)){
						if (preg_match('~(2|3)([0-9]){2}~',$response['response']['code']) && ($response['headers']['content-type'] == 'text/javascript' || $response['headers']['content-type'] == 'application/javascript' || $response['headers']['content-type'] == 'application/x-javascript')){
							if (isset($wp_scripts->registered[$handle]->extra['data'])){
								$content .= $this->ReplaceInJS($wp_scripts->registered[$handle]->extra['data'])."\n";
							}
							$content .= $response['body']."\n";
						}
					} 
				}
				$queued[] = $handle;
			}
		}
		else if ($asset == 'css'){
			global $wp_styles;
			$content = '';

			foreach ((array)$handles as $handle){
				$url = swift_get_absolute_url($wp_styles->registered[$handle]->src);
				if ($handle != 'admin-bar' && preg_match('~^'.site_url().'~',$url) && !isset($wp_styles->registered[$handle]->extra['conditional'])){
					$url = $this->ReplaceString($url);
					//Add sq for the request
					parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
					$query_string[$this->_globalSettings['sq']] = 1;
					$url = $url . '?'.http_build_query($query_string);
					
					$response = wp_remote_get($url, array('timeout' => 60, 'sslverify' => false, 'user-agent'=> 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1'));
					if (!is_wp_error($response)){
						if (preg_match('~(2|3)([0-9]){2}~',$response['response']['code'])){
							$current_dir = dirname(str_replace(site_url(),'',$wp_styles->registered[$handle]->src));
							$current_content = preg_replace('~(\.\./)+~',"{$current_dir}/$0",$response['body']);
							$content .= $current_content."\n";
						}
					} 
				}
			}
			$content = $this->ReplaceInCSS($content);
		}
		
		return $content;
		
	}

	/**
	 * Remove the WordPress specific id='stylsheet-css' from style tags
	 * @param unknown $tag
	 */
	public function CleanStyleTags($tag){
		return preg_replace('~id=\'([^\']*)\'\s*~', '',$tag);
	}
	
	/**
	 * Manage CDN hosts
	 * @param unknown $url
	 * @param string $handle
	 * @return unknown
	 */
	public function ManageCDN($url, $handle = ''){
		//Use only for frontend
		if (is_admin()){
			return $url;
		}
		
		//CDN for CSS files
		if (isset($this->_settings['CDN']['CSS']) && !empty($this->_settings['CDN']['CSS']) && preg_match('~\.(css|eot|ttf|otf|cff|afm|lwfn|ffil|fon|pfm|pfb|woff2?|svg|std|pro|xsf)$~i',parse_url($url, PHP_URL_PATH))){
			return str_replace(preg_replace('~http(s)?://~','',site_url()),preg_replace('~http(s)?://~','',$this->_settings['CDN']['CSS']), $url);
		}
		
		//CDN for JS files
		if (preg_match('~\.js$~i',parse_url($url, PHP_URL_PATH))){
			global $wp_scripts;
			
			//Scripts in header
			if(isset($this->_settings['CDN']['JSHeader']) && !empty($this->_settings['CDN']['JSHeader']) && !did_action('wp_footer')){
				return str_replace(preg_replace('~http(s)?://~','',site_url()),preg_replace('~http(s)?://~','',$this->_settings['CDN']['JSHeader']), $url);
			}
			//Scripts in footer
			else if(isset($this->_settings['CDN']['JSFooter']) && !empty($this->_settings['CDN']['JSFooter']) && did_action('wp_footer')){
				return str_replace(preg_replace('~http(s)?://~','',site_url()),preg_replace('~http(s)?://~','',$this->_settings['CDN']['JSFooter']), $url);
			}
		}
		return $url;
	}
	
	/**
	 * Remove jQuery core and jQuery migrate if combined assets turned on
	 * @param string $tag
	 * @param string $handle
	 * @return string
	 */
	public function RemovejQuery($tag,$handle){
			if ($handle == 'jquery' || $handle == 'jquery-core' || $handle == 'jquery-migrate'){
				return '';
			}
			return $tag;
	}
	
	/**
	 * Embed type script properties
	 * @param string $tag
	 * @param string $handle
	 * @return string
	 */
	public function ScriptEmbedType($tag,$handle){
		if(isset($this->_settings['deferredScripts'][$handle]) && $this->_settings['deferredScripts'][$handle] == 'enabled'){
			$tag = str_replace('></script>',' defer></script>',$tag);
		}
		if(isset($this->_settings['asyncScripts'][$handle]) && $this->_settings['asyncScripts'][$handle] == 'enabled'){
			$tag = str_replace('></script>',' async></script>',$tag);
		}
		return $tag;
	}
	
	
	/**
	 * Swift handled redirects
	 */
	public function SwiftRedirects(){
		global $wp_query;
	
		if ($wp_query->is_404 && isset($this->_settings['redirect404']) && !empty($this->_settings['redirect404'])){
			wp_redirect($this->_settings['redirect404']);
		}
	}
	
	/**
	 * Build rewrite rules for .htaccess
	 */
	public function GetHtaccess(){
		$OtherFileRules = '';
		$OtherDirRules = '';
		$DirectoryRules = '';
		$Directory404 = '';
		$FileRules = '';
		$File404 = '';
		$HiddenFileRules = '';
		$CDNCache = '';
		$CDNProxy = '';
		$WPEngine = '';
		$multisite_rewrite_cond = '';
		$multisite_extra_rules = '';
		$multisite_prefix = '';
		
		$sq2 = 'sq_' . md5($this->_globalSettings['sq']) . '=1';
		
		/*
		 * Prepare to multisite
		 */
		
		//Subdomain
		if (is_multisite() && defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL && !defined('SWIFTSECURITY_NETWORK_ONLY')){
			$multisite_rewrite_cond = 'RewriteCond %{HTTP_HOST} ^' . apply_filters('swiftsecurity_multisite_rewrite_cond', parse_url(site_url(),PHP_URL_HOST)) . '$' . PHP_EOL;
		}
		else if (is_multisite() && !defined('SWIFTSECURITY_NETWORK_ONLY')){
			if (defined('PATH_CURRENT_SITE')){
				$multisite_prefix = preg_replace('~^' .PATH_CURRENT_SITE . '~', '', $this->sitePath) . '/';
			}
			else{
				$multisite_prefix = substr($this->sitePath,1) . '/';
			}
			$multisite_prefix = apply_filters('swiftsecurity_multisite_prefix', ($multisite_prefix == '/' || (defined('PATH_CURRENT_SITE') && PATH_CURRENT_SITE == $multisite_prefix) ? '' : $multisite_prefix));
			
			$multisite_exclude_paths = '';
			foreach (wp_get_sites() as $site){
				if (get_current_blog_id() != $site['blog_id'] && (defined('PATH_CURRENT_SITE') && PATH_CURRENT_SITE != $site['path'])){
					$multisite_exclude_paths .=  $site['path'] . '|'; 
					
					if (get_current_blog_id() == 1){
						$multisite_extra_rules .= 'RewriteRule ^'.unleadingslash(preg_replace('~^' .PATH_CURRENT_SITE . '~', '', $site['path'])).'(wp-(content|admin|includes).*) $1?'.$this->_globalSettings['sq'].'&ms_'.$sq2.' [L,QSA]'.PHP_EOL;
						$multisite_extra_rules .= 'RewriteRule ^'.unleadingslash(preg_replace('~^' .PATH_CURRENT_SITE . '~', '', $site['path'])).'(.*\.php)$ $1?'.$this->_globalSettings['sq'].' [L,QSA]'.PHP_EOL;
					}
				}
			}
			if (!empty($multisite_exclude_paths)){
				$multisite_rewrite_cond = 'RewriteCond %{REQUEST_URI} !^(' . trim($multisite_exclude_paths, '|') . ')(.*)$' .PHP_EOL;
			}
		}
		
		//robots.txt
		$RobotsTxt  = 'RewriteCond "'.ABSPATH.'robots.txt" -f' . PHP_EOL;
		$RobotsTxt .= $multisite_rewrite_cond;
		$RobotsTxt .= 'RewriteRule ^robots.txt$ index.php?SwiftSecurity=robotstxt&'.$sq2.' [L,QSA]'.PHP_EOL;
		
		
		//Rewrite theme directory and style.css
		if ($this->_settings['redirectTheme'] !== WP_CONTENT_DIRNAME . '/themes/'.get_template()){
			$DirectoryRules .= $multisite_rewrite_cond;
			$DirectoryRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectTheme'].'/(.*)" "' .$multisite_prefix. WP_CONTENT_DIRNAME . '/themes/'.get_template().'/$1?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
			
			$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
			$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$File404 .= $multisite_rewrite_cond;
			$File404 .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectTheme'].'/style.css$" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
		}
		
		//Rewrite theme style.css
		$FileRules .= $multisite_rewrite_cond;
		$FileRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectTheme'].'/'.$this->_settings['redirectThemeStyle'] . '" "' .$multisite_prefix. WP_CONTENT_DIRNAME . '/themes/'.get_template().'/style.css?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
			
		$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
		$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
		$File404 .= $multisite_rewrite_cond;
		$File404 .= 'RewriteRule "^'. $multisite_prefix . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/style.css$" '. $multisite_prefix .'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;		
		
		//Rewrite theme directory & style.css
		if (get_stylesheet() != get_template()){
			if ($this->_settings['redirectChildTheme'] != WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet()){
				$DirectoryRules .= $multisite_rewrite_cond;
				$DirectoryRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectChildTheme'].'/(.*)" "' .$multisite_prefix. WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/$1?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
			}
			//Rewrite child theme style.css
			$FileRules .= $multisite_rewrite_cond;
			$FileRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectChildTheme'].'/'.$this->_settings['redirectChildThemeStyle'] . '" "' .$multisite_prefix. WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/style.css?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
				
			$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
			$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$File404 .= $multisite_rewrite_cond;
			$File404 .= 'RewriteRule "^'. $multisite_prefix . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/style.css$" '. $multisite_prefix .'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
				
			$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
			$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$File404 .= $multisite_rewrite_cond;
			$File404 .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectChildTheme'].'/style.css$" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
				
		}
		
		//Rewrite other directories
		foreach ((array)$this->_settings['redirectDirs'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key) || $key == WP_CONTENT_DIRNAME . '/plugins' || $key == $value){
				continue;
			}
			
			$DirectoryRules .= $multisite_rewrite_cond;
			$DirectoryRules .= 'RewriteRule "^'.$multisite_prefix.$value.'/(.*)" "'.$multisite_prefix.$key.'/$1?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
			
			$Directory404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].''.PHP_EOL;
			$Directory404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$Directory404 .= $multisite_rewrite_cond;
			$Directory404 .= 'RewriteRule "^'.$multisite_prefix.'('.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] .'/'.$this->_settings['plugins'][basename(SWIFTSECURITY_PLUGIN_DIR)].'/cache/'.parse_url(site_url(),PHP_URL_HOST).'/)?'.$key.'/(.*)?" '. $multisite_prefix .'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
		}
		
		//Disable wp-content directory
		if (!defined('SWIFTSECURITY_ENABLE_WP_CONTENT') || SWIFTSECURITY_ENABLE_WP_CONTENT !== true){
			$Directory404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].''.PHP_EOL;
			$Directory404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$Directory404 .= $multisite_rewrite_cond;
			$Directory404 .= 'RewriteRule "^'.$multisite_prefix.'('.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] .'/'.$this->_settings['plugins'][basename(SWIFTSECURITY_PLUGIN_DIR)].'/cache/'.parse_url(site_url(),PHP_URL_HOST).'/)?'. WP_CONTENT_DIRNAME . '/(.*)?" '. $multisite_prefix .'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
		}
		
		//Rewrite plugin directories
		foreach ((array)$this->_settings['plugins'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key) || $key == $value){
				continue;
			}
				
			$DirectoryRules .= $multisite_rewrite_cond;
			$DirectoryRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'].'/'.$value.'/(.*)" "'.$multisite_prefix. WP_CONTENT_DIRNAME . '/plugins/'.urlencode($key).'/$1?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
		}

		//Enable renamed plugins url and the original plugin name for authenticated users
		if ($this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] != 'wp-content/plugins'){
			$DirectoryRules .= 'RewriteCond %{HTTP_COOKIE} '.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
			$DirectoryRules .= $multisite_rewrite_cond;
			$DirectoryRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'].'/(.*)?" "'.$multisite_prefix. WP_CONTENT_DIRNAME . '/plugins/$1" [L]'.PHP_EOL;
		}
		
		//Admin URL without trailing slash
		$FileRules .= $multisite_rewrite_cond;
		$FileRules .= 'RewriteRule "^'.$multisite_prefix.$this->_settings['redirectDirs']['wp-admin'] . '$" ' . $this->_settings['redirectDirs']['wp-admin'] . '/ [R=301,L]'.PHP_EOL;
				
		//Rewrite other core files
		foreach ((array)$this->_settings['redirectFiles'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key)){
				continue;
			}
			
			$FileRules .= $multisite_rewrite_cond;
			$FileRules .= 'RewriteRule "^'.$multisite_prefix.$value.'" '.$multisite_prefix.$key.'?'.$this->_globalSettings['sq'].' [L,QSA]'.PHP_EOL;
			
			//skip wp-login.php, we take care about HideLogin() instead of htaccess (@since 1.4.2.10)
			if ($key != 'wp-login.php'){
				$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
				$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
				$File404 .= $multisite_rewrite_cond;
				$File404 .= 'RewriteRule "^'.$multisite_prefix.$key.'$" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
			}
		}
		
		//Rewrite other files
		if (isset($this->_settings['otherFiles'])){
			foreach ((array)$this->_settings['otherFiles'] as $key=>$value){
				//Prevent htaccess misconfigurations
				if(empty($value) || empty($key)){
					continue;
				}
					
				$OtherFileRules .= $multisite_rewrite_cond;
				$OtherFileRules .= 'RewriteRule "^'.$multisite_prefix.$value.'" "'.$multisite_prefix.$key.'?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
					
				$File404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
				$File404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
				$File404 .= $multisite_rewrite_cond;
				$File404 .= 'RewriteRule "'.$multisite_prefix.$key.'$" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
			}
		}
		
		//Rewrite other directories
		if (isset($this->_settings['otherDirs'])){
			foreach ((array)$this->_settings['otherDirs'] as $key=>$value){
				//Prevent htaccess misconfigurations
				if(empty($value) || empty($key)){
					continue;
				}

				$OtherDirRules .= $multisite_rewrite_cond;
				$OtherDirRules .= 'RewriteRule "^'.$multisite_prefix.$value.'/(.*)" "'.$multisite_prefix.$key.'/$1?'.$this->_globalSettings['sq'].'" [L,QSA]'.PHP_EOL;
					
				$Directory404 .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].PHP_EOL;
				$Directory404 .= 'RewriteCond %{HTTP_COOKIE} !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
				$Directory404 .= $multisite_rewrite_cond;
				$Directory404 .= 'RewriteRule "'.$multisite_prefix.$key.'" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
			}
		}
		
		//Rewrite hidden files
		if (isset($this->_settings['hiddenFiles'])){
			foreach ((array)$this->_settings['hiddenFiles'] as $file){
				//Prevent htaccess misconfigurations
				if(empty($file)){
					continue;
				}		
				$HiddenFileRules .= $multisite_rewrite_cond;		
				$HiddenFileRules .= 'RewriteRule "^'.$multisite_prefix.$file.'" '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
			}
		}
		
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{REMOTE_ADDR} ^%{SERVER_ADDR}$ [OR]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.'wp-cron.php$ $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
					
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.'wp-login.php$ $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.'wp-comments-post.php$ $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.'wp-admin/(.*)$ $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule "^'.$multisite_prefix . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/(.*)" $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
		if (get_stylesheet() != get_template()){
			$HiddenFileRules .= $multisite_rewrite_cond;
			$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
			$HiddenFileRules .= 'RewriteRule "^'.$multisite_prefix. WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/(.*)" $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
		}
		
		if (isset($this->_settings['directPHP'])){
			foreach ((array)$this->_settings['directPHP'] as $directPHP){
				//Prevent htaccess misconfigurations
				if(empty($directPHP)){
					continue;
				}
				$HiddenFileRules .= $multisite_rewrite_cond;
				$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
				$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.$directPHP.'$ $0?authorized_'.$sq2.' [L,QSA]'.PHP_EOL;
			}
		}
		
		$HiddenFileRules .= $multisite_rewrite_cond;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !'.$this->_globalSettings['sq'].' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteCond %{QUERY_STRING} !authorized_'.$sq2.' [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteCond %{HTTP_COOKIE}  !'.$this->_globalSettings['sq'].'=([0-9abcdef]+) [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteCond %{REQUEST_URI}  !'.$this->sitePath.'/index.php$  [NC]'.PHP_EOL;
		$HiddenFileRules .= 'RewriteRule ^'.$multisite_prefix.'(.*)\.php '.$multisite_prefix.'index.php?SwiftSecurity=404&'.$sq2.' [L]'.PHP_EOL;
			
		if (
			(isset($this->_settings['minifycss']) && $this->_settings['minifycss'] == 'enabled') ||
			(isset($this->_settings['minifyjs']) && $this->_settings['minifyjs'] == 'enabled') ||
			(isset($this->_settings['regexInClasses']) && !empty($this->_settings['regexInClasses'])) ||
			(isset($this->_settings['regexInIds']) && !empty($this->_settings['regexInIds'])) ||
			(isset($this->_settings['regexInJS']) && !empty($this->_settings['regexInJS'])) ||
			(isset($this->_settings['regexInCSS']) && !empty($this->_settings['regexInCSS'])) ||
			(isset($this->_settings['combineCSS']['status']) && $this->_settings['combineCSS']['status'] == 'enabled') ||
			(isset($this->_settings['manageJS']) && $this->_settings['manageJS'] == 'enabled')
		){
			
			//Prompt warning if cache folder isn't writable and caching isn't blocked (!isset($this->_settings['cache'])) or if it is enabled ($this->_settings['cache'] == 'enabled')
			if (!is_writable(SWIFTSECURITY_PLUGIN_DIR . '/cache') && (!isset($this->_settings['cache']) || $this->_settings['cache'] == 'enabled')){
				SwiftSecurity::SetPermanentMessage('is_cache_writable', __('The cache folder is not writable for WordPress, it may cause performance problems. Please set the permission to 777 and set the owner to Apache (usually www-data)'), 'warning', false);
			}
			else{
				SwiftSecurity::RemovePermanentMessage('is_cache_writable');
			}
			
			//We turn on caching if it isn't blocked (!isset($this->_settings['cache'])) or if it is enabled ($this->_settings['cache'] == 'enabled')
			if (!isset($this->_settings['cache']) || $this->_settings['cache'] == 'enabled'){
				$CDNCache .= 'RewriteCond %{QUERY_STRING} !cached_'.$sq2.' [NC]'.PHP_EOL;
				$CDNCache .= 'RewriteCond %{QUERY_STRING} !proxy_'.$sq2.' [NC]'.PHP_EOL;
				$CDNCache .= 'RewriteCond %{QUERY_STRING} !admin_'.$sq2.' [NC]'.PHP_EOL;
				$CDNCache .= 'RewriteCond %{QUERY_STRING} !ms_'.$sq2.' [NC]'.PHP_EOL;
				$CDNCache .= $multisite_rewrite_cond;
				$CDNCache .= 'RewriteRule ^'.$multisite_prefix.'(.*)\.(css|js)$ ' . $multisite_prefix . $this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] .'/'.$this->_settings['plugins'][basename(SWIFTSECURITY_PLUGIN_DIR)].'/cache' . $this->cache_site_url_padding .'%{REQUEST_URI}?cached_'.$sq2.' [L,QSA]'.PHP_EOL;

				$CDNProxy .= 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL;
				$CDNProxy .= 'RewriteCond ' . $this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] .'/'.$this->_settings['plugins'][basename(SWIFTSECURITY_PLUGIN_DIR)].'/cache' . $this->cache_site_url_padding .'%{REQUEST_URI} !-f' . PHP_EOL;
			}
			$CDNProxy .= 'RewriteCond %{QUERY_STRING} !proxy_'.$sq2.PHP_EOL;
			$CDNProxy .= 'RewriteCond %{QUERY_STRING} !admin_'.$sq2.PHP_EOL;
			$CDNProxy .= 'RewriteCond %{QUERY_STRING} !ms_'.$sq2.' [NC]'.PHP_EOL;
			$CDNProxy .= $multisite_rewrite_cond;
			$CDNProxy .= 'RewriteRule ^'.$multisite_prefix.'(.*)\.(css|js)$ '.$multisite_prefix.'index.php?SwiftSecurity=cdnproxy&'.$sq2.' [L,QSA]'.PHP_EOL;
		}
			
		//Check WP Engine
		$environment = SwiftSecurity::CheckEnvironment();
		if ($environment['ManagedHosting'] == 'WPEngine'){
			$WPEngine .='#Start WPEngine'.PHP_EOL;
			$WPEngine .= $multisite_rewrite_cond;
			$WPEngine .='RewriteRule ^'.$multisite_prefix.'(.*)\.(css|js|html?|txt|jpe?g|gif|png|ico|eot|woff2?|ttf|svg|mp4|mp3)\.wpengine$ $1.$2 [L,QSA]'.PHP_EOL;
			$WPEngine .='#End WPEngine'.PHP_EOL;
		}
		
		$htaccess = '';
		
		//Add extra htaccess rules before Swift Security
		if (isset($this->_settings['extraHtaccess']) && !empty($this->_settings['extraHtaccess'])){
			$extra_htaccess = str_replace('[[SQ]]',$this->_globalSettings['sq'],$this->_settings['extraHtaccess']);
			$extra_htaccess = str_replace('[[SQMD5]]',md5($this->_globalSettings['sq']),$extra_htaccess);
			$htaccess .= $extra_htaccess.PHP_EOL.PHP_EOL;
		}
		
		$htaccess .= '<IfModule mod_rewrite.c>'.PHP_EOL;
		$htaccess .= 'RewriteEngine On'.PHP_EOL;
		$htaccess .= 'RewriteBase '.$this->sitePath.'/'.PHP_EOL.PHP_EOL;
		$htaccess .= $RobotsTxt . PHP_EOL;
		$htaccess .= $CDNCache.PHP_EOL;
		$htaccess .= $WPEngine.PHP_EOL;
		$htaccess .= $OtherFileRules.PHP_EOL;
		$htaccess .= $OtherDirRules.PHP_EOL;
		$htaccess .= $File404.PHP_EOL;
		$htaccess .= $FileRules.PHP_EOL;
		$htaccess .= $Directory404.PHP_EOL;
		$htaccess .= $DirectoryRules.PHP_EOL;
		$htaccess .= $HiddenFileRules.PHP_EOL;
		$htaccess .= $CDNProxy.PHP_EOL;
		$htaccess .= $multisite_extra_rules;
		$htaccess .= '</IfModule>'.PHP_EOL;
		return $htaccess;
	}
	
	/**
	 * Build rewrite rules for nginx
	 */
	public function GetNginxRules(){
		$nginx = '';
		$OtherFileRules = '';
		$OtherDirRules = '';
		$DirectoryRules = '';
		$Directory404 = '';
		$FileRules = '';
		$File404 = '';
		$HiddenFileRules = '';
		$DirectPHPRules = '';
		$CDNCache = '';
		$CDNProxy = '';
		$tab = "\t";
		
		$sitePath = trailingslashit($this->sitePath);
		
		$sq2 = 'sq_' . md5($this->_globalSettings['sq']) . '=1';
			
		//Rewrite theme directory
		$DirectoryRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectTheme'].'/(.*)" '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/$1?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
			
		$Directory404 .= $tab.$tab.'rewrite "^'.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/(.*)?" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' break;'.PHP_EOL;
		
		//Rewrite theme directory for child themes
		if (get_stylesheet() != get_template()){
			$DirectoryRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectChildTheme'].'/(.*)" '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/$1?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
		
			$Directory404 .= $tab.$tab.'rewrite "^'.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/(.*)?" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' break;'.PHP_EOL;
		}
		
		//Rewrite other directories
		foreach ((array)$this->_settings['redirectDirs'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key) || $key == WP_CONTENT_DIRNAME . '/plugins'){
				continue;
			}
				
			$DirectoryRules .= $tab.$tab.'rewrite "^'.$sitePath.$value.'/(.*)" '.$sitePath.$key.'/$1?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
				
			$Directory404 .= $tab.$tab.'rewrite "^'.$sitePath.$key.'/(.*)?" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' break;'.PHP_EOL;
		}
		//Disable wp-content directory
		$Directory404 .= $tab.$tab.'rewrite "^'.$sitePath . WP_CONTENT_DIRNAME . '/(.*)?" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' break;'.PHP_EOL;
		
		//Rewrite plugin directories
		foreach ((array)$this->_settings['plugins'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key)){
				continue;
			}
		
			$DirectoryRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'].'/'.$value.'/(.*)" '.$sitePath . WP_CONTENT_DIRNAME . '/plugins/'.urlencode($key).'/$1?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
		
			$Directory404 .= $tab.$tab.'rewrite "^'.$sitePath . WP_CONTENT_DIRNAME . '/plugins/'.$key.'/(.*)?" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		}
		
		//Admin URL without trailing slash
		$FileRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectDirs']['wp-admin'] . '$" '.$sitePath.$this->_settings['redirectDirs']['wp-admin'] . '/ permanent;'.PHP_EOL;
		
		//Admin URL with trailing slash
		$FileRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectDirs']['wp-admin'] . '/$" '.$sitePath.'wp-admin/index.php?'.$this->_globalSettings['sq'].'&$args last;'.PHP_EOL;
		
		//Rewrite theme style.css
		$FileRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectTheme'].'/'.$this->_settings['redirectThemeStyle'] . '" '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/style.css?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
		
		//Disable css and js files with trailing slash
		$File404 .= $tab.$tab.'rewrite \.(css|js)/$ '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		
		$File404 .= $tab.$tab.'rewrite "^'.$sitePath. WP_CONTENT_DIRNAME . '/themes/'.get_template().'/style.css$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		$File404 .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectTheme'].'/style.css$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		
		//Rewrite theme style.css for child themes
		if (get_stylesheet() != get_template()){
			$FileRules .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectChildTheme'].'/'.$this->_settings['redirectChildThemeStyle'] . '" '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/style.css?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
				
			$File404 .= $tab.$tab.'rewrite "^'.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/style.css$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
			$File404 .= $tab.$tab.'rewrite "^'.$sitePath.$this->_settings['redirectChildTheme'].'/style.css$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
				
		}
		
		//Rewrite other core files
		foreach ((array)$this->_settings['redirectFiles'] as $key=>$value){
			//Prevent htaccess misconfigurations
			if(empty($value) || empty($key)){
				continue;
			}
				
			$FileRules .= $tab.$tab.'rewrite "^'.$sitePath.$value.'" '.$sitePath.$key.'?'.$this->_globalSettings['sq'].'=x&$args last;'.PHP_EOL;
				
			$File404 .= $tab.$tab.'rewrite "^'.$sitePath.$key.'$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		}
		
		//Rewrite other files
		if (isset($this->_settings['otherFiles'])){
			foreach ((array)$this->_settings['otherFiles'] as $key=>$value){
				//Prevent htaccess misconfigurations
				if(empty($value) || empty($key)){
					continue;
				}
					
				$OtherFileRules .= $tab.'location '.$sitePath.$value.' {'.PHP_EOL;
				$OtherFileRules .= $tab.$tab.'try_files $uri '.$sitePath.$key.'?'.$this->_globalSettings['sq'].'=x&$args;'.PHP_EOL;
				$OtherFileRules .= $tab.'}'.PHP_EOL;
					
				$File404 .= $tab.$tab.'rewrite "^'.$sitePath.$key.'$"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
			}
		}
		
		//Rewrite other directories
		if (isset($this->_settings['otherDirs'])){
			foreach ((array)$this->_settings['otherDirs'] as $key=>$value){
				//Prevent htaccess misconfigurations
				if(empty($value) || empty($key)){
					continue;
				}
				
				$OtherDirRules .= $tab.'location ~ '.$sitePath.$value.'(.*) {'.PHP_EOL;
				$OtherDirRules .= $tab.$tab.'try_files $uri '.$sitePath.$key.'$1?'.$this->_globalSettings['sq'].'=x&$args;'.PHP_EOL;
				$OtherDirRules .= $tab.'}'.PHP_EOL;
				
				
				$Directory404 .= 'rewrite "^'.$sitePath.$key.'(.*)?"  '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
			}
		}
		
		//Rewrite hidden files
		if (isset($this->_settings['hiddenFiles'])){
			foreach ((array)$this->_settings['hiddenFiles'] as $file){
				//Prevent htaccess misconfigurations
				if(empty($file)){
					continue;
				}
				$HiddenFileRules .= $tab.'rewrite "^'.$sitePath.$file.'" '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
			}
		}
		
		$DirectPHPRules .= $tab.$tab.'if ($request_uri = '.$sitePath.'wp-login.php){'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'if ($request_uri = '.$sitePath.'wp-comments-post.php){'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'if ($request_uri ~ '.$sitePath.'wp-admin/(.*)){'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'if ($request_uri ~ '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_template().'/(.*)){'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
		if (get_stylesheet() != get_template()){
			$DirectPHPRules .= $tab.$tab.'if ($request_uri ~ '.$sitePath . WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet().'/(.*)){'.PHP_EOL;
			$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
			$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;				
		}
		$DirectPHPRules .= $tab.$tab.'if ($request_filename = $document_root/index.php){'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
		$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
		
		if (isset($this->_settings['directPHP'])){
			foreach ((array)$this->_settings['directPHP'] as $directPHP){
				//Prevent htaccess misconfigurations
				if(empty($directPHP)){
					continue;
				}
				$DirectPHPRules .= $tab.$tab.'if ($request_uri = '.$sitePath.$directPHP.'){'.PHP_EOL;
				$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
				$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;

				$DirectPHPRules .= $tab.$tab.'if ($request_filename = $document_root/'.$directPHP.'){'.PHP_EOL;
				$DirectPHPRules .= $tab.$tab.$tab.'set $rule_2 true;'.PHP_EOL;
				$DirectPHPRules .= $tab.$tab.'}'.PHP_EOL;
			}
		}
		
		if (
			(isset($this->_settings['minifycss']) && $this->_settings['minifycss'] == 'enabled') ||
			(isset($this->_settings['minifyjs']) && $this->_settings['minifyjs'] == 'enabled') ||
			(isset($this->_settings['regexInClasses']) && !empty($this->_settings['regexInClasses'])) ||
			(isset($this->_settings['regexInIds']) && !empty($this->_settings['regexInIds'])) ||
			(isset($this->_settings['regexInJS']) && !empty($this->_settings['regexInJS'])) ||
			(isset($this->_settings['regexInCSS']) && !empty($this->_settings['regexInCSS'])) ||
			(isset($this->_settings['combineCSS']['status']) && $this->_settings['combineCSS']['status'] == 'enabled') ||
			(isset($this->_settings['manageJS']) && $this->_settings['manageJS'] == 'enabled')
		){						
			//Prompt warning if cache folder isn't writable and caching isn't blocked (!isset($this->_settings['cache'])) or if it is enabled ($this->_settings['cache'] == 'enabled')
			if (!is_writable(SWIFTSECURITY_PLUGIN_DIR . '/cache') && (!isset($this->_settings['cache']) || $this->_settings['cache'] == 'enabled')){
				SwiftSecurity::SetPermanentMessage('is_cache_writable', __('The cache folder is not writable for WordPress, it may cause performance problems. Please set the permission to 777 and set the owner to Apache (usually www-data)'), 'warning', false);
			}
			else{
				SwiftSecurity::RemovePermanentMessage('is_cache_writable');
			}
				
			$CDNCache .= $tab.'set $rule_cdn 0;'.PHP_EOL;
			$CDNCache .= $tab.'if ($args ~ proxy_'.$sq2.'){'.PHP_EOL;
			$CDNCache .= $tab.$tab.'set $rule_cdn 1;'.PHP_EOL;
			$CDNCache .= $tab.'}'.PHP_EOL.PHP_EOL;
			
			$CDNCache .= $tab.'if ($args ~ cached_'.$sq2.'){'.PHP_EOL;
			$CDNCache .= $tab.$tab.'set $rule_cdn 2;'.PHP_EOL;
			$CDNCache .= $tab.'}'.PHP_EOL.PHP_EOL;
			
			$CDNCache .= $tab.'if ($args ~ admin_'.$sq2.'){'.PHP_EOL;
			$CDNCache .= $tab.$tab.'set $rule_cdn 3;'.PHP_EOL;
			$CDNCache .= $tab.'}'.PHP_EOL.PHP_EOL;
			
			//We turn on caching if it isn't blocked (!isset($this->_settings['cache'])) or if it is enabled ($this->_settings['cache'] == 'enabled')
			if (!isset($this->_settings['cache']) || $this->_settings['cache'] == 'enabled'){
				$CDNCache .= $tab.'if ($rule_cdn = 0){'.PHP_EOL;
				$CDNCache .= $tab.$tab.'set $rule_cdn 2;'.PHP_EOL;
				$CDNCache .= $tab.$tab.'rewrite (.*)\.(css|js)$ '.$sitePath.$this->_settings['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] .'/'.$this->_settings['plugins'][basename(SWIFTSECURITY_PLUGIN_DIR)].'/cache/'.parse_url(site_url(),PHP_URL_HOST).'$1.$2?$args&cached_'.$sq2.';'.PHP_EOL;
				$CDNCache .= $tab.'}'.PHP_EOL.PHP_EOL;
			
				$CDNCache .= $tab.'location ~ \.(css|js)$ {'.PHP_EOL;
				$CDNCache .= $tab.$tab.'try_files $uri @cdnproxy;'.PHP_EOL;
				$CDNCache .= $tab.'}'.PHP_EOL.PHP_EOL;
				
				$CDNProxy .= $tab.'location  @cdnproxy {'.PHP_EOL;
				$CDNProxy .= $tab.$tab.'rewrite .* '.$sitePath.'index.php?SwiftSecurity=cdnproxy&'.$sq2.'&$args last;'.PHP_EOL;
				$CDNProxy .= $tab.'}'.PHP_EOL.PHP_EOL;
			}
			else{
				$CDNProxy .= $tab.'location ~ \.(css|js)$ {'.PHP_EOL;
				$CDNProxy .= $tab.$tab.'rewrite .* '.$sitePath.'index.php?SwiftSecurity=cdnproxy&'.$sq2.'&$args last;'.PHP_EOL;
				$CDNProxy .= $tab.'}'.PHP_EOL.PHP_EOL;
				
			}		
			
		}
				
		$nginx.= $tab.'set $rule_1 false;'.PHP_EOL;
		$nginx.= $tab.'if ($http_cookie ~ '.$this->_globalSettings['sq'].'=([0-9abcdef]+)){'.PHP_EOL;
		$nginx.= $tab.$tab.'set $rule_1 true;'.PHP_EOL;
		$nginx.= $tab.'}'.PHP_EOL;
		$nginx.= $tab.'if ($args ~ '.$this->_globalSettings['sq'].'){'.PHP_EOL;
		$nginx.= $tab.$tab.'set $rule_1 true;'.PHP_EOL;
		$nginx.= $tab.'}'.PHP_EOL;
		$nginx.= $tab.'if ($http_x_real_ip = '.$this->server_addr.'){'.PHP_EOL;
		$nginx.= $tab.$tab.'set $rule_1 true;'.PHP_EOL;
		$nginx.= $tab.'}'.PHP_EOL;
		$nginx.= $OtherFileRules.PHP_EOL;
		$nginx.= $OtherDirRules.PHP_EOL;
		$nginx.= $tab.'if ($rule_1 = false){'.PHP_EOL;
		$nginx.= $File404.PHP_EOL;
		$nginx.= $Directory404.PHP_EOL;
		$nginx.= $tab.'}'.PHP_EOL;
		$nginx.= $HiddenFileRules.PHP_EOL;
		
		$nginx.= $CDNCache;
		
		$nginx.=$FileRules.PHP_EOL;
		$nginx.=$DirectoryRules.PHP_EOL;
		
		$nginx.= $tab.$tab.'set $rule_2 false;'.PHP_EOL;
		$nginx.= $DirectPHPRules;
		$nginx.= $tab.$tab.'if ($rule_2 = false){'.PHP_EOL;
		$nginx.= $tab.$tab.$tab.'rewrite (.*)\.php$ '.$sitePath.'index.php?SwiftSecurity=404&'.$sq2.' last;'.PHP_EOL;
		$nginx.= $tab.$tab.'}'.PHP_EOL;
		
		$nginx.= $CDNProxy;
		
	
		return $nginx;
	}
	
	/**
	 * Create cookie for all sites if sub site was opened from Network admin by Super Admin and SQ cookie is missing
	 * We are using javascript redirect for multisite wp-admin if Super Admin opened the site from Network Admin.
	 * We need use this workaround instead of wp_redirect, because of Safari cookie related privacy policy 
	 * @see https://bugs.webkit.org/show_bug.cgi?id=3512
	 */
	public function CreateMultisiteSqCookie(){
		if(is_multisite() && is_user_logged_in() && current_user_can('manage_sites') && !isset($_COOKIE[$this->_globalSettings['sq']])){
			setcookie($this->_globalSettings['sq'], md5(mt_rand(0,10000000000)),0,'/');
			echo '<!DOCTYPE html><html><head><title>'.__('Redirecting...', 'SwiftSecurity').'</title><script>document.location.href = "'.$_SERVER['REQUEST_URI'].'"</script></head><body><noscript><a href="'.admin_url().'">'.__('Click here to redirect.', 'SwiftSecurity').'</a></noscript></body></html>';
			die;
		}
	}
	
	
	/**
	 * Create Secure Query authentication cookie
	 * If this cookie is set all WP files and folders are accessable (eg: wp-login, wp-content/, etc...)
	 */
	public function CreateSqCookie($user_login = '', $user = null){
		if (isset($user->roles[0]) && isset($this->_settings['userRoles'][$user->roles[0]]['hideAdmin']) && $this->_settings['userRoles'][$user->roles[0]]['hideAdmin'] == 'enabled'){
			return;
		}
		setcookie($this->_globalSettings['sq'], md5(mt_rand(0,10000000000)),0,'/');
	}
	
	/**
	 * Reset secure query cookie
	 */
	public function ResetSqCookie(){
		setcookie($this->_globalSettings['sq'], null, -1);
	}
	
	/**
	 * Custom exceptions for 3rd party plugins. This function are not handling compatibility problems, only exceptions
	 * @return boolean
	 */
	public function CustomPluginExceptions(){
		//Remove plugin generated extra comments here if remove comments turned on
		if (isset($this->_settings['removeHTMLComments']) && $this->_settings['removeHTMLComments'] == 'enabled'){
			//W3TC
			add_filter('w3tc_can_print_comment', false);

			//Visual Composer
			add_action('init', array('SwiftSecurityHideWP','RemoveVCMetaData'));

			//WP Super Cache
			global $wp_super_cache_comments;
			$wp_super_cache_comments = 0;
		}

		//Change WP Maintenence mode HTTP status code for server
		if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']){
				add_filter('wpmm_status_code', array($this, 'WPMMStatusCode'));
		}
		
		//WP Rocket minify fix
		add_filter('rocket_buffer', array($this,'ReplaceContentCallback'),100);
		add_filter('rocket_pre_minify_path', array($this,'ReverseReplaceString'));
		
		//Codestyling localization
        add_action('admin_enqueue_scripts', array('SwiftSecurityHideWP','RemoveCSPSelfProtection'),11);
	}

	/**
	 * Change wp-content related paths in htaccess rewrite rules and mark them to be able to revert
	 * @param string $htaccess
	 * @return string
	 */
	public function ModifyThirdPartyHtaccess($htaccess){
		//Multisite extra rules
		$multisite_rewrite_cond = '';
		
		$multisite_extra_padding = (is_multisite() ? '-' . site_url() : '');
		
		//Subdomain
		if (is_multisite() && defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL){
				$multisite_rewrite_cond = 'RewriteCond %{HTTP_HOST} ^' . apply_filters('swiftsecurity_multisite_rewrite_cond', parse_url(site_url(),PHP_URL_HOST)) . '$' . PHP_EOL;
		}
		//Subfolder
		else if (is_multisite()){	
			$multisite_exclude_paths = '';
			foreach (wp_get_sites() as $site){
				if (get_current_blog_id() != $site['blog_id'] && $site['path'] != '/'){
					$multisite_exclude_paths .=  $site['path'] . '|';
				}
			}
			//For main site
			if (!empty($multisite_exclude_paths) && $this->sitePath == ''){
				$multisite_rewrite_cond = 'RewriteCond %{REQUEST_URI} !^(' . trim($multisite_exclude_paths, '|') . ')(.*)$' .PHP_EOL;
			}
			//For sub sites
			if (!empty($this->sitePath)){
				$multisite_rewrite_cond = 'RewriteCond %{REQUEST_URI} ^'.$site['path'] . PHP_EOL;
			}
		}
		
		$pure_htaccess = preg_replace("~######BEGIN SwiftSecurity([^#]*)######"."(.*)######END SwiftSecurity([^#]*)######"."(\s*)?~s",'',$htaccess);
		preg_match_all('~RewriteRule(.*)wp-content(.*)~', $pure_htaccess, $matches);
		foreach ($matches[0] as $rule){
			$backup_rule = "###START_SWIFT_RULE_BACKUP{$multisite_extra_padding}###$rule";
			$new_rule = $this->ReplaceString($rule);
			
			//Replace other files
			foreach ((array)$this->_settings['otherFiles'] as $key=>$value){
				$new_rule = str_replace($key, $value, $new_rule);
			}
			
			//Replace other dirs
			foreach ((array)$this->_settings['otherDirs'] as $key=>$value){
				$new_rule = str_replace($key, $value, $new_rule);
			}
			
			$new_rule = "###START_SWIFT_MODIFIED_RULE{$multisite_extra_padding}###\n".$multisite_rewrite_cond.$new_rule."\n###END_SWIFT_MODIFIED_RULE{$multisite_extra_padding}###\n";
			$htaccess = str_replace($rule, $backup_rule."\n".$new_rule, $htaccess);
		}
		
		return $htaccess;
	}
	
	/**
	 * Regarding the CGI Fix what we use to fix No input file specified problem for wp-login.php
	 * we need to use another checking for wp-login to hide it properly
	 */
	public function HideLogin(){
		if (preg_match('~wp-login\.php~',$_SERVER['REQUEST_URI'])){
			ini_set("display_errors", 0);
			global $wp_query;
			global $wp_rewrite;
			$wp_rewrite->permalink_structure = '';
			$wp_query->set_404();
			status_header(404);
			get_template_part('404');
			die;
		}
	}
	
	/**
	 * Call RocketScript callback to change text/rocketscript to text/javascript in wp-admin
	 */
	public function RocketScript(){
		if(!defined('DOING_AJAX') || !DOING_AJAX){
			ob_start(array(&$this,"RocketScriptCallback"));
		}
	}
	
	/**
	 * Change text/rocketscript to text/javascript 
	 * @param string $buffer
	 * @return string
	 */
	public function RocketScriptCallback($buffer){
		return str_replace('text/rocketscript','text/javascript',$buffer);
	}
	
	/**
	 * Handle domain mapping
	 * @param string $site
	 * @return string
	 */
	public function DomainMapping($site){
		if (defined('SUNRISE') && filter_var(SUNRISE, FILTER_VALIDATE_BOOLEAN)){
			global $wpdb;
			$dm = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = ".get_current_blog_id()." AND active = 1");
			if (!is_wp_error($dm) && $dm != null){
				$site = $dm;
			}
		}
		return $site;
	}
	
	/**
	 * Change WP Maintenence mode plugin status code to 200 for server
	 */
	public static function WPMMStatusCode($code){
		return 200;
	}
	
	/**
	 * Remove Visual Composer meta data
	 */
	public static function RemoveVCMetaData() {
		if (function_exists('visual_composer')){
			remove_action('wp_head', array(visual_composer(), 'addMetaData'));
		}
	}
	
	/**
	 * Block csp_self_script_protection and load jquery
	 */
	public static function RemoveCSPSelfProtection() {
		if (function_exists('csp_load_po_edit_admin_page')){
			wp_enqueue_script( 'jquery' );
				
			remove_filter('print_scripts_array', 'csp_filter_print_scripts_array');
			remove_action('admin_enqueue_scripts', 'csp_start_protection', 0);
			remove_action('in_admin_footer', 'csp_start_protection', 0);
			remove_action('admin_head', 'csp_self_script_protection_head', 9999);
			remove_action('admin_print_footer_scripts', 'csp_self_script_protection_footer', 9999);
		}
	}
	
	/**
	 * Empty supported cache plugins' cache
	 */
	public function PurgeThirdPartyCaches(){
		//W3TC
		if (class_exists('W3_CacheFlushLocal')){
			$w3tc = new W3_CacheFlushLocal();
			$w3tc->flush_all();
		}
		
		//WP Engine
		if (class_exists('WpeCommon')){
			WpeCommon::purge_varnish_cache();
		}
	}
	
	/**
	 * Replace links for themes and frameworks
	 * Special theme exceptions are executed here
	 * This function are not handling compatibility problems, only exceptions
	 */
	public function ReplaceThemeExceptions (){
		//Options Framework
		global $smof_data;
		if (isset($smof_data) && is_array($smof_data)){
			$smof_data = $this->RecursiveReplaceString($smof_data);
		}
		
		//Qode themes
		global $qode_options_proya;
		if (isset($qode_options_proya) && is_array($qode_options_proya)){
			$qode_options_proya = $this->RecursiveReplaceString($qode_options_proya);
		}
			
		//Pressa
		if (get_template() == 'pressa'){
			global $theme_option;
			if (isset($theme_option) && is_array($theme_option)){
				$theme_option = $this->RecursiveReplaceString($theme_option);
			}
		}
	}
	
}

?>