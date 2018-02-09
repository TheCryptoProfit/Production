<?php 
/**
 * Firewall object
 * Block, log suspicious requests and send notifications
 *
 */
class SwiftSecurityFirewall{
	
	/**
	 * The name of the module
	 * @var string
	 */
	public $moduleName = 'Firewall';
	
	/**
	 * WP Session object
	 * @var WP_Session
	 */
	public $wp_session;
	
	/**
	 * Contains logging extra data (eg: attack type)
	 * @var array
	 */
	public $logData = array();
	
	/**
	 * Regural expression to filter GET requests
	 * @var string
	 */
	public $_SQLi = array();
	
	/**
	 * Regural expression to filter POST requests
	 * @var string
	 */
	public $_XSS = array();
	
	/**
	 * Regural expression to filter cookies
	 * @var string
	 */
	public $_Path = array();
	
	/**
	 * File injection filters
	 * @var array
	 */
	public $_File = array();
	
	/**
	 * Module settings
	 * @var array
	 */
	public $_settings = array();
	
	/**
	 * Plugin settings
	 * @var array
	 */
	public $_globalSettings = array();
	
	/**
	 * Create the firewall object
	 */
	public function __construct($settings){
		$this->_SQLi 			= (isset($settings['Firewall']['SQLi']) ? $settings['Firewall']['SQLi'] : array());
		$this->_XSS	 			= (isset($settings['Firewall']['XSS']) ? $settings['Firewall']['XSS'] : array());
		$this->_Path 			= (isset($settings['Firewall']['Path']) ? $settings['Firewall']['Path'] : array());
		$this->_File 			= (isset($settings['Firewall']['File']) ? $settings['Firewall']['File'] : array());
		$this->_IP			 	= (isset($settings['Firewall']['IP']) ? $settings['Firewall']['IP'] : array());
		$this->_settings		= (isset($settings['Firewall']['settings']) ? $settings['Firewall']['settings'] : array());
		$this->_globalSettings	= (isset($settings['GlobalSettings']) ? $settings['GlobalSettings'] : array());

		include_once SWIFTSECURITY_PLUGIN_DIR . '/helpers/wp-session-manager/wp-session-manager.php';
		$this->wp_session = WP_Session::get_instance();
				
		//Check the REMOTE_ADDRESS for IP filtering on login page
		if (!empty($this->_IP['Whitelist']) || !empty($this->_IP['CountryLoginWhitelist'])){
			add_filter('authenticate',  array($this, 'LoginIPWhitelistFilter'), 20, 3);
		}
		
		//Notification on login
		add_action('wp_login',  array($this, 'LogAuth'));
		
		//Notification failed login attempts
		add_action('wp_login_failed', array($this, 'LogFailedAuth'));
		
		//IP blacklist filter
		add_action('init',array($this, 'GeneralIPBlacklistFilter'));
		
		if (isset($settings['Firewall']['commentSpamBlocker']) && $settings['Firewall']['commentSpamBlocker'] == 'enabled'){
			add_action( 'comment_form', array($this,'GenerateAutoCaptcha'));
			
			add_filter( 'pre_comment_approved', array($this,'CheckAutoCaptcha'));
		}
		
		//Custom exceptions for 3rd party plugins.
		$this->CustomPluginExceptions();
		
	}
	
	/**
	 * Generates Auto Captcha
	 */
	public function GenerateAutoCaptcha(){
		$a = mt_rand(0,10000);
		$b = mt_rand(0,10000);
		$c = mt_rand(0,10000);
		if (!isset($this->wp_session['autocaptcha'])){
			$this->wp_session['autocaptcha'] = array();
		}
		$this->wp_session['autocaptcha'][($a + $b) * $c] = true;
		echo '<input type="hidden" name="swiftsecurity-autocaptcha">';
		echo '<script>document.getElementsByName("swiftsecurity-autocaptcha")[0].value = ('.$a.' + '.$b.') * '.$c.'</script>';
	}
	
	/**
	 * Check auto captcha for comments
	 * @param string $approved
	 * @param array $commentdata
	 * @return string
	 */
	public function CheckAutoCaptcha($approved, $commentdata = array()){
		if ( !isset($_POST['swiftsecurity-autocaptcha']) || empty($_POST['swiftsecurity-autocaptcha']) || !(isset($this->wp_session['autocaptcha']) && $this->wp_session['autocaptcha'][$_POST['swiftsecurity-autocaptcha']] == true)){
			$approved = 'spam';
		}
		return $approved;
	}
	
	
	/**
	 * Return with the requested regexp string
	 * @param string $group (SQLi, XSS, Path, etc...) default is SQLi
	 * @param string $type (GET, POST, COOKIE)
	 * @return array 
	 */
	public function GetRegexp($group = 'SQLi', $type = 'POST'){
		$group = '_' . $group;
		$settings = $this->$group;
		if (isset($settings['settings'][$type]) && $settings['settings'][$type]== 'enabled'){
			return $settings[$type];
		}
		else{
			return array();		
		}
	}
	
	/**
	 * Build rewrite rules for .htaccess
	 */
	public function GetHtaccess(){
		$getSQLiRrewrites = '';
		$getXSSRrewrites = '';
		$getPathRrewrites = '';
		$cookieSQLiRrewrites = '';
		$cookiePathRrewrites = '';
		$SitePath = parse_url(site_url(),PHP_URL_PATH);
		
		$multisite_rewrite_cond = '';
		
		$sq2 = 'sq_' . md5($this->_globalSettings['sq']) . '=1';
		
		/*
		 * Prepare to multisite
		*/
		
		//Subdomain
		if (is_multisite() && defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL && !defined('SWIFTSECURITY_NETWORK_ONLY')){
			$multisite_rewrite_cond = 'RewriteCond %{HTTP_HOST} ^' . apply_filters('swiftsecurity_multisite_rewrite_cond', parse_url(home_url(),PHP_URL_HOST)) . '$' . PHP_EOL;
		}
		else if (is_multisite() && !defined('SWIFTSECURITY_NETWORK_ONLY')){
			$multisite_exclude_paths = '';
			foreach (wp_get_sites() as $site){
				if (get_current_blog_id() != $site['blog_id'] && $site['path'] != '/'){
					$multisite_exclude_paths .=  $site['path'] . '|'; 
				}
			}
			if (!empty($multisite_exclude_paths)){
				$multisite_rewrite_cond = 'RewriteCond %{REQUEST_URI} !^(' . trim($multisite_exclude_paths, '|') . ')(.*)$' .PHP_EOL;
			}
		}
		
		//Build the GET exceptions 
		if (isset($this->_SQLi['exceptions']['GET'])){
			for ($i=0;$i<count($this->_SQLi['exceptions']['GET']);$i++){
				$getSQLiRrewrites .= 'RewriteCond %{REQUEST_URI} !'. $this->_SQLi['exceptions']['GET'][$i]. ' [NC]' . PHP_EOL;
			}
		}
		if (isset($this->_XSS['exceptions']['GET'])){
			for ($i=0;$i<count($this->_XSS['exceptions']['GET']);$i++){
				$getXSSRrewrites .= 'RewriteCond %{REQUEST_URI} !'. $this->_XSS['exceptions']['GET'][$i] . ' [NC]' . PHP_EOL;
			}
		}
		if (isset($this->_Path['exceptions']['GET'])){
			for ($i=0;$i<count($this->_Path['exceptions']['GET']);$i++){
				$getPathRrewrites .= 'RewriteCond %{REQUEST_URI} !'. $this->_Path['exceptions']['GET'][$i] . ' [NC]' . PHP_EOL;
			}
		}
		
		//Build GET rewrite regexp
		if (isset($this->_SQLi['GET'])){
			for ($i=0;$i<count($this->_SQLi['GET']);$i++){
				$ending = ($i < count($this->_SQLi['GET'])-1 ? ' [NC,OR]' : ' [NC]');
				$getSQLiRrewrites .= 'RewriteCond %{THE_REQUEST} ' . $this->_SQLi['GET'][$i] . $ending . PHP_EOL;
			}
		}
		if (isset($this->_XSS['GET'])){
			for ($i=0;$i<count($this->_XSS['GET']);$i++){
				$ending = ($i < count($this->_XSS['GET'])-1 ? ' [NC,OR]' : ' [NC]');
				$getXSSRrewrites .= 'RewriteCond %{THE_REQUEST} ' . $this->_XSS['GET'][$i] . $ending . PHP_EOL;
			}
		}
		if (isset($this->_Path['GET'])){
			for ($i=0;$i<count($this->_Path['GET']);$i++){
				$ending = ($i < count($this->_Path['GET'])-1 ? ' [NC,OR]' : ' [NC]');
				$getPathRrewrites .= 'RewriteCond %{THE_REQUEST} ' . $this->_Path['GET'][$i] . $ending . PHP_EOL;
			}
		}

		//Build the COOKIE exceptions
		if (isset($this->_SQLi['exceptions']['COOKIE'])){
			for ($i=0;$i<count($this->_SQLi['exceptions']['COOKIE']);$i++){
				$cookieSQLiRrewrites .= 'RewriteCond %{REQUEST_URI} !'. $this->_SQLi['exceptions']['COOKIE'][$i] . ' [NC]' . PHP_EOL;
			}
		}
		if (isset($this->_Path['exceptions']['COOKIE'])){
			for ($i=0;$i<count($this->_Path['exceptions']['COOKIE']);$i++){
				$cookiePathRrewrites .= 'RewriteCond %{REQUEST_URI} !'. $this->_Path['exceptions']['COOKIE'][$i] . ' [NC]' . PHP_EOL;
			}
		}
		
		
		//Build cookie rewrite regexp
		if (isset($this->_SQLi['COOKIE'])){
			for ($i=0;$i<count($this->_SQLi['COOKIE']);$i++){
				$ending = ($i < count($this->_SQLi['COOKIE'])-1 ? ' [NC,OR]' : ' [NC]');
				$cookieSQLiRrewrites .= 'RewriteCond %{HTTP_COOKIE} ' . $this->_SQLi['COOKIE'][$i] . $ending . PHP_EOL;
			}
		}
		//Build cookie rewrite regexp
		if (isset($this->_Path['COOKIE'])){
			for ($i=0;$i<count($this->_Path['COOKIE']);$i++){
				$ending = ($i < count($this->_Path['COOKIE'])-1 ? ' [NC,OR]' : ' [NC]');
				$cookiePathRrewrites .= 'RewriteCond %{HTTP_COOKIE} ' . $this->_Path['COOKIE'][$i] . $ending . PHP_EOL;
			}
		}
				
		$htaccess  = '<IfModule mod_rewrite.c>'.PHP_EOL;
		$htaccess .= 'RewriteEngine On'.PHP_EOL;
		$htaccess  .= 'RewriteBase '.$SitePath.'/'.PHP_EOL;

		//If post proxy enabled
		if ((isset($this->_SQLi['settings']['POST']) && $this->_SQLi['settings']['POST'] == 'enabled') || (isset($this->_XSS['settings']['POST']) && $this->_XSS['settings']['POST'] == 'enabled') || (isset($this->_Path['settings']['POST']) && $this->_Path['settings']['POST'] == 'enabled') || (isset($this->_File['settings']['POST']) && $this->_File['settings']['POST'] == 'enabled')){
			$htaccess  .= 'RewriteCond %{HTTP:X-SwiftSecurity-Proxy} !^'.$this->_globalSettings['sq'] .'$ [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{REQUEST_METHOD} POST [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall&'.$sq2.' [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
				
		//Add get rewrites if not empty
		if (!empty($getSQLiRrewrites) && $this->_SQLi['settings']['GET'] == 'enabled'){
			$htaccess  .= $getSQLiRrewrites;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall&'.$sq2.' [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&attempt=SQLi&channel=GET&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
		if (!empty($getXSSRrewrites) && $this->_XSS['settings']['GET'] == 'enabled'){
			$htaccess  .= $getXSSRrewrites;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&attempt=XSS&channel=GET&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
		if (!empty($getPathRrewrites) && $this->_Path['settings']['GET'] == 'enabled'){
			$htaccess  .= $getPathRrewrites;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&attempt=Path&channel=GET&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
		
		//Add get rewrites if not empty
		if (!empty($cookieSQLiRrewrites) && $this->_SQLi['settings']['COOKIE'] == 'enabled'){
			$htaccess  .= $cookieSQLiRrewrites;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&attempt=SQLi&channel=COOKIE&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
		if (!empty($cookiePathRrewrites) && $this->_Path['settings']['COOKIE'] == 'enabled'){
			$htaccess  .= $cookiePathRrewrites;
			$htaccess  .= 'RewriteCond %{REQUEST_URI} !^/index.php$ [NC]'.PHP_EOL;
			$htaccess  .= 'RewriteCond %{QUERY_STRING} !SwiftSecurity=firewall [NC]'.PHP_EOL;
			$htaccess  .= $multisite_rewrite_cond;
			$htaccess  .= 'RewriteRule ^'.unleadingslashit($SitePath).'/?(.*) index.php?SwiftSecurity=firewall&attempt=Path&channel=COOKIE&'.$sq2.' [L]'.PHP_EOL.PHP_EOL;
		}
		
		$htaccess .= '</IfModule>'.PHP_EOL;
		
		return $htaccess;
	}
	
	/**
	 * Build rewrite rules for nginx
	 */
	public function GetNginxRules(){
		$nginx = '';
		$tab = "\t";
		$SitePath = '/' . parse_url(site_url(),PHP_URL_PATH);
		$getSQLiRrewrites = $tab.'set $getsql false;'.PHP_EOL;
		$getXSSRrewrites = $tab.'set $getxss false;'.PHP_EOL;
		$getPathRrewrites = $tab.'set $getpath false;'.PHP_EOL;
		$cookieSQLiRrewrites = $tab.'set $cookiesql false;'.PHP_EOL;
		$cookiePathRrewrites = $tab.'set $cookiepath false;'.PHP_EOL;
		
		$sq2 = 'sq_' . md5($this->_globalSettings['sq']) . '=1';
		
		//Build GET rewrite regexp
		if (isset($this->_SQLi['GET'])){
			for ($i=0;$i<count($this->_SQLi['GET']);$i++){
				$getSQLiRrewrites .= $tab.'if ($request_uri ~* "'. str_replace('"','\"',$this->_SQLi['GET'][$i]) . '"){' . PHP_EOL;
				$getSQLiRrewrites .= $tab.$tab.'set $getsql true;' . PHP_EOL;
				$getSQLiRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		if (isset($this->_XSS['GET'])){
			for ($i=0;$i<count($this->_XSS['GET']);$i++){
				$getXSSRrewrites .= $tab.'if ($request_uri ~* "'. str_replace('"','\"',$this->_XSS['GET'][$i]) . '"){' . PHP_EOL;
				$getXSSRrewrites .= $tab.$tab.'set $getxss true;' . PHP_EOL;
				$getXSSRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		if (isset($this->_Path['GET'])){
			for ($i=0;$i<count($this->_Path['GET']);$i++){
				$getPathRrewrites .= $tab.'if ($request_uri ~* "'. str_replace('"','\"',$this->_Path['GET'][$i]) . '"){' . PHP_EOL;
				$getPathRrewrites .= $tab.$tab.'set $getpath true;' . PHP_EOL;
				$getPathRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		
		//Build the GET exceptions
		if (isset($this->_SQLi['exceptions']['GET'])){
			for ($i=0;$i<count($this->_SQLi['exceptions']['GET']);$i++){
				$getSQLiRrewrites .= $tab.'if ($request_uri ~ '. $this->_SQLi['exceptions']['GET'][$i]. '){' . PHP_EOL;
				$getSQLiRrewrites .= $tab.$tab.'set $getsql false;' . PHP_EOL;
				$getSQLiRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		if (isset($this->_XSS['exceptions']['GET'])){
			for ($i=0;$i<count($this->_XSS['exceptions']['GET']);$i++){
				$getXSSRrewrites .= $tab.'if ($request_uri ~ '. $this->_XSS['exceptions']['GET'][$i]. '){' . PHP_EOL;
				$getXSSRrewrites .= $tab.$tab.'set $getxss false;' . PHP_EOL;
				$getXSSRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		if (isset($this->_Path['exceptions']['GET'])){
			for ($i=0;$i<count($this->_Path['exceptions']['GET']);$i++){
				$getPathRrewrites .= $tab.'if ($request_uri ~ '. $this->_Path['exceptions']['GET'][$i]. '){' . PHP_EOL;
				$getPathRrewrites .= $tab.$tab.'set $getpath false;' . PHP_EOL;
				$getPathRrewrites .= $tab.'}' . PHP_EOL;
		
			}
		}
		
		//Build cookie rewrite regexp
		if (isset($this->_SQLi['COOKIE'])){
			for ($i=0;$i<count($this->_SQLi['COOKIE']);$i++){				
				$cookieSQLiRrewrites .= $tab.'if ($http_cookie ~* "'. str_replace('"','\"',$this->_SQLi['COOKIE'][$i]) . '"){' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.$tab.'set $cookiesql true;' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		//Build cookie rewrite regexp
		if (isset($this->_Path['COOKIE'])){
			for ($i=0;$i<count($this->_Path['COOKIE']);$i++){
				$cookiePathRrewrites .= $tab.'if ($http_cookie ~* "'. str_replace('"','\"',$this->_Path['COOKIE'][$i]) . '"){' . PHP_EOL;
				$cookiePathRrewrites .= $tab.$tab.'set $cookiepath true;' . PHP_EOL;
				$cookiePathRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		
		//Build the COOKIE exceptions
		if (isset($this->_SQLi['exceptions']['COOKIE'])){
			for ($i=0;$i<count($this->_SQLi['exceptions']['COOKIE']);$i++){
				$cookieSQLiRrewrites .= $tab.'if ($request_uri ~ '. $this->_SQLi['exceptions']['COOKIE'][$i] . '){' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.$tab.'set $cookiesql false;' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.'}' . PHP_EOL;
			}
		}
		if (isset($this->_Path['exceptions']['COOKIE'])){
			for ($i=0;$i<count($this->_Path['exceptions']['COOKIE']);$i++){
				$cookieSQLiRrewrites .= $tab.'if (($request_uri ~ '. $this->_Path['exceptions']['COOKIE'][$i] . '){' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.$tab.'set $cookiepath false;' . PHP_EOL;
				$cookieSQLiRrewrites .= $tab.'}' . PHP_EOL;
			}
		}

		//If post proxy enabled
		if ((isset($this->_SQLi['settings']['POST']) && $this->_SQLi['settings']['POST'] == 'enabled') || (isset($this->_XSS['settings']['POST']) && $this->_XSS['settings']['POST'] == 'enabled') || (isset($this->_Path['settings']['POST']) && $this->_Path['settings']['POST'] == 'enabled') || (isset($this->_File['settings']['POST']) && $this->_File['settings']['POST'] == 'enabled')){
			
			$nginx .= $tab.'if ($request_method = POST){'.PHP_EOL;
			$nginx .= $tab.$tab.'set $swiftproxy true;'.PHP_EOL;
			$nginx .= $tab.'}'.PHP_EOL;
			
			$nginx .= $tab.'if ($http_x_swiftsecurity_proxy){'.PHP_EOL;
			$nginx .= $tab.$tab.'set $swiftproxy false;'.PHP_EOL;
			$nginx .= $tab.'}'.PHP_EOL;

			$nginx .= $tab.'if ($request_uri ~ ^/index.php){'.PHP_EOL;
			$nginx .= $tab.$tab.'set $swiftproxy false;'.PHP_EOL;
			$nginx .= $tab.'}'.PHP_EOL;
			
			$nginx .= $tab.'if ($arg_SwiftSecurity = firewall){'.PHP_EOL;
			$nginx .= $tab.$tab.'set $swiftproxy false;'.PHP_EOL;
			$nginx .= $tab.'}'.PHP_EOL;
				
			$nginx .= $tab.'if ($swiftproxy = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
			
		}
				
		//Add get rewrites if not empty
		if (!empty($getSQLiRrewrites) && $this->_SQLi['settings']['GET'] == 'enabled'){
			$nginx  .= $getSQLiRrewrites;
			$nginx .= $tab.'if ($getsql = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&attempt=SQLi&channel=GET&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
		}
		if (!empty($getXSSRrewrites) && $this->_XSS['settings']['GET'] == 'enabled'){
			$nginx  .= $getXSSRrewrites;
			$nginx .= $tab.'if ($getxss = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&attempt=XSS&channel=GET&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
					}
		if (!empty($getPathRrewrites) && $this->_Path['settings']['GET'] == 'enabled'){
			$nginx  .= $getPathRrewrites;
			$nginx .= $tab.'if ($getxss = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&attempt=Path&channel=GET&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
		}
		
		//Add get rewrites if not empty
		if (!empty($cookieSQLiRrewrites) && $this->_SQLi['settings']['COOKIE'] == 'enabled'){
			$nginx  .= $cookieSQLiRrewrites;
			$nginx .= $tab.'if ($cookiesql = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&attempt=SQLi&channel=COOKIE&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
		}
		if (!empty($cookiePathRrewrites) && $this->_Path['settings']['COOKIE'] == 'enabled'){
			$nginx  .= $cookiePathRrewrites;
			$nginx .= $tab.'if ($cookiepath = true){' . PHP_EOL;
			$nginx .= $tab.$tab.'rewrite .* ' . $SitePath . 'index.php?SwiftSecurity=firewall&attempt=Path&channel=COOKIE&'.$sq2.' last;' . PHP_EOL;
			$nginx .= $tab.'}' . PHP_EOL;
		}
		
		return $nginx;
	}
	
	/**
	 * Show the requested forbidden template
	 */
	public function Forbidden(){
		header("HTTP/1.1 403 Unauthorized");
		SwiftSecurity::FileInclude('403');		
		die;
	}
	
	/**
	 * Log the attack attempts
	 * @todo refactor this function because it designed only security events not login events
	 */
	public function Log($title = 'Blocked attack attempt', $autolog = true){
		SwiftSecurity::ClassInclude('SecurityLogObject');
		
		//Log the possible attack attempt
		$LogEntry = new SwiftSecurityLogObject($this->LogData, $this, $autolog); 
		
		//Prevent PHP notice
		$this->LogData['isLoginEvent'] = (!isset($this->LogData['isLoginEvent']) ? false : $this->LogData['isLoginEvent']);
		
		//Send email notification
		//Check event type and notification settings
		if (($this->_globalSettings['Notifications']['EmailNotifications']['Firewall'] == 'enabled' && !$this->LogData['isLoginEvent']) || ($this->_globalSettings['Notifications']['EmailNotifications']['Login'] == 'enabled' && $this->LogData['isLoginEvent'])){
			SwiftSecurity::SendEmailNotification(
				$this->_globalSettings['Notifications']['NotificationEmail'],
				__($title),
				$LogEntry->GetNotificationForEmail()
			);
		}
		
		//Send push notification
		//Check event type and notification settings
		if ((isset($this->_globalSettings['Notifications']['PushNotifications']['Firewall']) && $this->_globalSettings['Notifications']['PushNotifications']['Firewall'] == 'enabled' && !$this->LogData['isLoginEvent']) || (isset($this->_globalSettings['Notifications']['PushNotifications']['Login']) && $this->_globalSettings['Notifications']['PushNotifications']['Login'] == 'enabled' && $this->LogData['isLoginEvent'])){
				SwiftSecurity::SendPushNotification(
				$this->_globalSettings['Notifications']['NotificationPushoverToken'],
				$this->_globalSettings['Notifications']['NotificationPushoverUser'],
				$LogEntry->GetNotificationForPushover(),
				$this->_globalSettings['Notifications']['NotificationPushoverSound'],
				__($title)
			);
		}
	}
	
	/**
	 * Log and notify on successful authentication
	 */
	public function LogAuth($user_login, $user = null){
		$this->LogData = array(
				'attempt'		=> 'Login',
				'channel'		=> $user_login,
				'hard'			=> false,
				'isLoginEvent'	=> true
		);
		$this->Log('Successful login', true, false);
	}
	
	/**
	 * Log and notify on failed authentication
	 */
	public function LogFailedAuth($user_login){
		$this->LogData = array(
				'attempt'	=> 'FailedLogin',
				'channel'	=> $user_login,
				'isLoginEvent'	=> true
		);
		$this->Log('Failed login');
	}
	
	/**
	 * Filter and block login requests based on IP whitelist
	 * @param WP_User $user
	 * @param string $username
	 * @param string $password
	 * @return WP_Error|WP_User
	 */
	public function LoginIPWhitelistFilter($user, $username, $password){
		
		$IP = $this->GetIP();
		
		if (!empty($this->_IP['Whitelist']) && !in_array($IP, $this->_IP['Whitelist'])){
		   $error = new WP_Error();
		   $error->add( 'non_authenticated_ip', __( '<strong>ERROR</strong>: IP address rejected.') );
		   return $error;
		}
		else if (!empty($this->_IP['CountryLoginWhitelist']) && !in_array($this->GetCountry($IP), $this->_IP['CountryLoginWhitelist'])){
			$error = new WP_Error();
			$error->add( 'non_authenticated_ip', __( '<strong>ERROR</strong>: IP address rejected.') );
			return $error;
		}
		
		return $user;
		
	}
	
	/**
	 * Block all requests based on IP blacklist
	 */
	public function GeneralIPBlacklistFilter(){
		$IP = $this->GetIP();
		$server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : (isset($GLOBALS['_SERVER']['SERVER_ADDR']) ? $GLOBALS['_SERVER']['SERVER_ADDR'] : '127.0.0.1');
	
		//Don't block the Proxy and localhost
		if ($IP == $server_addr || $IP == '127.0.0.1'){
			return;
		}
		
		if (isset($this->_IP['Blacklist']) && in_array($IP, (array)$this->_IP['Blacklist'])){
			$this->Forbidden();
		}
		else if (isset($this->_IP['CountryBlacklist']) && in_array($this->GetCountry($IP), (array)$this->_IP['CountryBlacklist'])){
			$this->Forbidden();
		}
		else if(in_array($IP, (array)get_option('swiftsecurity_banned_ips',array()))){
			$this->Forbidden();
		}
		
	}
	
	/**
	 * Determine the user real ip
	 * @return string
	 */
	public function GetIP(){
		//Swift Security Proxy
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR_' . strtoupper($this->_globalSettings['sq'])])){
			return $_SERVER['HTTP_X_FORWARDED_FOR_' . strtoupper($this->_globalSettings['sq'])];
		}
		//Nginx, cloudfare, other proxies based on user's settings
		else if (isset($this->_IP['source']) && isset($_SERVER[$this->_IP['source']])){
			return $_SERVER[$this->_IP['source']];
		}
		//Default
		else{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	
	/**
	 * Set the country in the SESSION, based on the user's IP address
	 * @param string $ip 
	 * @return string
	 */
	public function GetCountry($ip){		
		//Lookup country
		if (!isset($this->wp_session['CountryCode'][$ip])){			
			$response = wp_remote_get('http://www.geoplugin.net/json.gp?ip=' . $ip);
			
			if (!is_wp_error($response) && $response['response']['code'] == '200'){
				$DecodedResponse = json_decode($response['body'],true);
			}
			else{
				$response = wp_remote_get('http://swiftgeoip.xyz/?ip=' . $ip);					
				if (!is_wp_error($response) && $response['response']['code'] == '200'){
					$DecodedResponse = json_decode($response['body'],true);
				}
				else{
					return '';
				}
			}
			
			if (is_array($DecodedResponse)){
				$this->wp_session['CountryCode'] = array($ip => $DecodedResponse['geoplugin_countryCode']);
			}
		}
		if (isset($this->wp_session['CountryCode'][$ip])){
			return $this->wp_session['CountryCode'][$ip];
		}
		return '';
	}
	
	/**
	 * Custom exceptions for 3rd party plugins. This function are not handling compatibility problems, only exceptions
	 * @return boolean
	 */
	public function CustomPluginExceptions(){
		//Custom sidebars by WPMU dev
		if (isset($_SERVER['HTTP_X_SWIFTSECURITY_PROXY'])){
			add_action('plugins_loaded', array($this, 'FixCustomSidebars'),0);
		}
	}
	
	/**
	 * Custom sidebars by WPMU dev fix to prevent POST proxy infinite loop
	 */
	public function FixCustomSidebars(){
			remove_action('plugins_loaded','inc_sidebars_free_init');
	}
	
}

?>