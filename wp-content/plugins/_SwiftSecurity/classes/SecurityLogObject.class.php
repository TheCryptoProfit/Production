<?php 
/**
 * Represents a security event 
 *
 */

class SwiftSecurityLogObject{
	/**
	 * Simple Unix timestamp
	 * @var int
	 */
	public $timestamp;
		
	/**
	 * The type of the possible attack attempt
	 * SQLi (SQL injection)
	 * XSS (Cross-site-scripting)
	 * Path (File path manipulation)
	 * File (File inclusion)
	 * @var string
	 */
	public $attempt;
	
	/**
	 * The channel where the regexp matched.
	 * GET
	 * POST
	 * COOKIE
	 * @var string
	 */
	public $channel;

	/**
	 * The requested URL, for example: wp-login.php
	 * @var string
	 */
	public $requestURI;
	
	/**
	 * The current value of the http cookies
	 * @var string
	 */
	public $cookies;
	
	/**
	 * The attacker IP address
	 * @var string
	 */
	public $ip;
	
	/**
	 * The attacker hostname
	 * @var string
	 */
	public $host;
	
	
	/**
	 * User's user agent string
	 * @var string
	 */
	public $ua;
	
	/**
	 * The attacker username (if user is logged in). If the user isn't logged in it is false
	 * @var boolean|string
	 */
	public $user;
	
	/**
	 * Contains attempts human readable names
	 * @var array
	 */
	public $AttemptNames = array();
	
	/**
	 * Is it hard or soft event
	 * IP bans are based on hard events
	 */
	public $_hard = true;
	
	/**
	 * Firewall IP settings
	 * @var array
	 */
	private $_IPSettings = array();
	
	/**
	 * Plugin settings
	 * @var array
	 */
	private $_globalSettings = array();
	

	/**
	 * Creates the SecurityLogObject
	 * @param array $LogData
	 * @param boolean $autolog Automatic logging on create the object. Default is false
	 */
	public function __construct($LogData, $Firewall, $autolog = false, $hard = true){		
		$this->_IPSettings =  $Firewall->_IP;
		$this->_globalSettings = $Firewall->_globalSettings;
		
		//Human readable attempt names
		$this->AttemptNames = array(
				'SQLi'	=> __('SQL injection','SwiftSecurity'),
				'XSS'	=> __('Cross-site scripting','SwiftSecurity'),
				'Path'	=> __('File path manipulation','SwiftSecurity'),
				'File'	=> __('File inclusion','SwiftSecurity'),
				'Upload'=> __('File upload','SwiftSecurity'),
				'FailedLogin'=> __('Failed login','SwiftSecurity'),
				'Login'=> __('Successful login','SwiftSecurity'),
		);
		
		//Get the cookie vars
		foreach ($_COOKIE as $key=>$value){
			$this->cookies .= $key . '=' . $value .'; ';
		}
		
		//Get user datas if user is logged in
		if(is_user_logged_in()){
			$user = wp_get_current_user();
			$this->user = $user->user_login;
		}
		else{
			$this->user = false;
		}
		
		$this->timestamp = time();
		$this->attempt		= $LogData['attempt'];
		$this->channel		= $LogData['channel'];
		$this->requestURI	= $_SERVER['REQUEST_URI'];
		$this->ip			= $Firewall->GetIP();
		$this->host			= $_SERVER['HTTP_HOST'];
		$this->ua			= $_SERVER['HTTP_USER_AGENT'];
		$this->hard			= ((isset($LogData['hard']) && $LogData['hard'] == false) ? false : true);
		
		//If autolog is on write the log entry
		if ($autolog){
			$this->Log();
		}
	}
	
	
	/**
	 * Creates the log entry in db
	 */
	public function Log($hard = true){
		//Get the last 999 log entry
		$logs = array_slice(get_option('swiftsecurity_log', array()), -999);
		
		if ($this->hard){
			$attempts = 0;
			foreach ($logs as $entry){
				//count previous attempts
				if ($entry['ip'] == $this->ip && (isset($entry['hard']) && $entry['hard'] == true)){
					$attempts++;
				}
			}
			
			//block IP after $this->_IPSettings[AutoBlacklistMaxAttempts] attempts
			if ($attempts >= $this->_IPSettings['AutoBlacklistMaxAttempts']){
				$BannedIPs = get_option('swiftsecurity_banned_ips',array());
				if(!in_array($this->ip, $BannedIPs)){
					$BannedIPs[] = $this->ip;
					update_option('swiftsecurity_banned_ips', $BannedIPs);
				}
			}
		}
		
		//Create the new log entry
		$logs[] = array(
			'timestamp' 	=> $this->timestamp,
			'attempt'		=> $this->attempt,
			'channel'		=> $this->channel,
			'requestURI'	=> $this->requestURI,
			'cookies'		=> $this->cookies,
			'ip'			=> $this->ip,
			'host'			=> $this->host,
			'ua'			=> $this->ua,
			'user'			=> $this->user,
			'hard'			=> $this->hard
		);
		
		update_option('swiftsecurity_log',$logs);
	}
	
	/**
	 * Get the log entry as formatted text for email notifications
	 * @param string $mode (html|text) default is text
	 * @return string
	 */
	public function GetNotificationForEmail($mode = 'text'){
		$TitlePrefix = ($this->attempt != 'FailedLogin' && $this->attempt != 'Login' ? __('Blocked', 'SwiftSecurity') : '');
		 
		$title = $TitlePrefix .' '. $this->AttemptNames[$this->attempt] . ' ('.$this->channel.')';
		$timestamp = date('m-d-Y H:i:s',$this->timestamp);
		$uri = site_url().$this->requestURI;
		$cookies = $this->cookies;
		$ip = $this->ip;
		$host = $this->host;
		$useragent = $this->ua;
		$wpuser = ($this->user != false ? $this->user : 'Not logged in')."</p>";
	
		ob_start();
		include (SWIFTSECURITY_PLUGIN_DIR . '/templates/mail/firewall-notification.php');
		$mail = ob_get_clean();
		return $mail;
	}
	
	/**
	 * Get the log entry as formatted text for push notifications
	 * @return string
	 */
	public function GetNotificationForPushover (){
		$TitlePrefix = ($this->attempt != 'FailedLogin' && $this->attempt != 'Login' ? __('Blocked', 'SwiftSecurity') : '');
			
		$title = $TitlePrefix .' '. $this->AttemptNames[$this->attempt] . ' ('.$this->channel.')';
		$timestamp = date('m-d-Y H:i:s',$this->timestamp);
		$uri = site_url().$this->requestURI;
		$cookies = $this->cookies;
		$ip = $this->ip;
		$host = $this->host;
		$useragent = $this->ua;
		$wpuser = ($this->user != false ? $this->user : 'Not logged in')."</p>";
		
		ob_start();
		include (SWIFTSECURITY_PLUGIN_DIR . '/templates/mail/firewall-text-notification.php');
		$push = ob_get_clean();
		return $push;
	}
}

?>