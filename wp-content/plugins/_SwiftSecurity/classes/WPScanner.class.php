<?php 
/**
 * Scan Wordpress, identify setting problems and malicious files, code snippets
 */
class SwiftSecurityWPScanner{
	/**
	 * Define the module name
	 * @var string
	 */
	public $moduleName = 'WPScanner';
	
	/**
	 * Last file scan array. It contains the last -or the current- scan UNIX timestamp, and the scanned files md5 hash
	 * @var array
	 */
	public $WPScan = array(
		'timestamp' => 0,
		'progress' => 0,
		'report' => array(),
		'scans' => array(
			'mysql' => false,
			'php' => false,
			'filescan' => false
		)
	);
	
	/**
	 * Contains scans ratio of full progress (in percent)
	 * @var array
	 */
	public $ProgressRatio = array(
		'mysql' => 10,
		'filescan' => 90
	); 
	
	public $SwiftSecurity;
	
	/**
	 * Creates the WPScanner object and run scan if scan now button pushed
	 * @param array $settings
	 * @param stdClass $SwiftSecurity
	 */
	public function __construct($settings, $SwiftSecurity){
		$this->SwiftSecurity = $SwiftSecurity;
		$this->Settings = $settings;
		
		add_action('wp_ajax_SwiftSecurityWPScanner', array($this, 'SwiftSecurityWPScannerAjaxHandler'));
		add_action('wp_ajax_SwiftSecurityWPScannerFileAction', array($this, 'SwiftSecurityWPScannerFileAction'));
	}
	
	/**
	 * Ajax handler for scan, prints the report
	 */
	public function SwiftSecurityWPScannerAjaxHandler(){
		//Check wp-nonce
		check_ajax_referer( 'swiftsecurity', 'wp-nonce' );
		
		
		//Start new scan or continue the last one
		if ($_POST['type'] == 'start' || $_POST['type'] == 'continue' ){
			$new = ($_POST['type'] == 'start' ? true : false);
			$this->Scan($new, false);
		}
		
		//Get scan results
		$Scan = $this->GetScan();
	
		//Filtered response
		if($_POST['type'] == 'filtered'){
			$listEmtpy = true;
			$filter = (isset($_POST['filter']) ? $_POST['filter'] : '');
			switch($filter){
				case 'whitelisted':
					foreach ((array)$Scan['report']['filescan'] as $key=>$value){
						if ($value['whitelisted']){
							$listEmtpy = false;
							$Response['report']['filescan'][$key] = $value;
							$Response['report']['filescan'][$key]['forceshow'] = true;
						}
					}
					$Response['title'] = ($listEmtpy ? __('There are no whitelisted files', 'SwiftSecurity') : __('Whitelisted files', 'SwiftSecurity'));
					break;
				case 'quarantined':
					foreach ((array)$Scan['report']['filescan'] as $key=>$value){
						if (isset($value['quarantine']) && $value['quarantine']){
							$listEmtpy = false;
							$Response['report']['filescan'][$key] = $value;
						}
					}			
					$Response['title'] = ($listEmtpy ? __('There are no files in quarantine', 'SwiftSecurity') : __('Quarantined files', 'SwiftSecurity'));
					break;
				default:
				$Response = $Scan;
				$Response['title'] = ($Response['timestamp'] > 0 ? __('Scan report', 'SwiftSecurity') .' - '. date('d-m-Y H:i:s', $Scan['timestamp']) : __('Run your first scan','SwiftSecurity'));
			}
		}
		else{
			$Response = $Scan;
		}
		
		
		//Print report
		wp_die(json_encode($Response));
	}
	
	/**
	 * Ajax handler for scan file actions (whitelist/unwhitelist, quarantine/unquarantine files)
	 */
	public function SwiftSecurityWPScannerFileAction(){
		$needFlush = false;
		
		//Check wp-nonce
		check_ajax_referer( 'swiftsecurity', 'wp-nonce' );
		
		//Get scan results
		$scan = $this->GetScan();
		
		//Whitelist file
		if($_POST['type'] == 'whitelist'){
			$scan['report']['filescan'][$_POST['filename']]['whitelisted'] = true;
			$scan['report']['filescan'][$_POST['filename']]['quarantine'] = false;
		}
		//Undo whitelist
		if($_POST['type'] == 'unwhitelist'){
			$scan['report']['filescan'][$_POST['filename']]['whitelisted'] = false;
		}
		
		//Quarantine file
		if($_POST['type'] == 'quarantine'){
			$scan['report']['filescan'][$_POST['filename']]['quarantine'] = true;
			$scan['report']['filescan'][$_POST['filename']]['whitelisted'] = false;
			$needFlush = true;
		}
		//Undo quarantine
		if($_POST['type'] == 'unquarantine'){
			$scan['report']['filescan'][$_POST['filename']]['quarantine'] = false;
			$needFlush = true;
		}
		
		update_option('swiftsecurity_wpscan', $scan);
		
		//Flush htaccess if needed
		if ($needFlush){
			$this->SwiftSecurity->AddHtaccess($this);
			$this->SwiftSecurity->FlushHtaccess();
		}
		
		wp_die();
	}
	
	
	/**
	 * Get current or last scan details
	 * @param boolean $new if true starts new scan otherwise continue last scan. Default is false
	 * @return array
	 */
	public function GetScan($new = false){
		$Scan = get_option('swiftsecurity_wpscan', $this->WPScan);
		if ($new){
			//Start new scan
			$CurrentScan = $this->WPScan;
			$CurrentScan['report']['filescan'] = $Scan['report']['filescan'];
			update_option('swiftsecurity_wpscan', $CurrentScan);
		}
		else{
			$CurrentScan = $Scan;
		}
		return $CurrentScan;
	}
	
	/**
	 * Make the scan and create the report
	 * The process object are refreshing while the scan is running, so we can show the progress via ajax
	 * @param boolean $new if true starts new scan otherwise continue last scan. Default is false
	 * @todo check file owner
	 */
	public function Scan($new = false, $scheduled = false){
		//Get last scan results 
		$CurrentScan = $this->GetScan($new);
		//Start new scan or continue running scan (PHP max_execution time workaround)
		$CurrentScan['timestamp'] = time();
		
		/*
		 * PHP settings 
		 */
		
		if ($CurrentScan['scans']['php'] != true){
			 /*PHP Information Leakage*/
			if (ini_get('expose_php') == 1){
				$result = array(
					'score' =>20,
					'text' =>  __('PHP expose_php function is enabled. A malicious user can use this function to discover some information about your server', 'SwiftSecurity')
				);
			}
			else{
				$result = array(
					'score' => 0,
					'text' =>  __('OK - PHP expose_php function is disabled', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['php']['expose_php'] = array(
					'label' => __('PHP expose_php function', 'SwiftSecurity'),
					'result' => $result
			);
		
			 /*PHP Information Leakage*/
			if (ini_get('display_errors') == 1){
				$result = array(
						'score' => 100,
						'text' =>  __('Display errors is enabled. A malicious user can use this function to discover some information about your server', 'SwiftSecurity')
				);
			}
			else{
				$result = array(
						'score' => 0,
						'text' =>  __('OK - Display errors is disabled', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['php']['display_errors'] = array(
					'label' => __('Display errors', 'SwiftSecurity'),
					'result' => $result
			);
			
			 /*Remote code execution*/
			//URL fopen
			if (ini_get('allow_url_fopen') == 1){
				$result = array(
						'score' => 20,
						'text' =>  __('URL fopen is allowed. A malicious user can use this function to perform remote code execution', 'SwiftSecurity')
				);
			}
			else{
				$result = array(
						'score' => 0,
						'text' =>  __('OK - URL fopen is disallowed', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['php']['allow_url_fopen'] = array(
					'label' => __('Remote file reading', 'SwiftSecurity'),
					'result' => $result
			);
			
			//URL include
			if (ini_get('allow_url_include') == 1){
				$result = array(
						'score' => 100,
						'text' =>  __('URL include is allowed. A malicious user can use this function to perform remote code execution', 'SwiftSecurity')
				);
			}
			else{
				$result = array(
						'score' => 0,
						'text' =>  __('OK - URL include is disallowed', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['php']['allow_url_include'] = array(
					'label' => __('Remote file include', 'SwiftSecurity'),
					'result' => $result
			);
			
			//Disabled PHP functions
			if (preg_match('~(passthru|(shell_)?exec|system|proc_open|popen|show_source|highlight_file)~',ini_get('disable_functions'))){
				$result = array(
						'score' => 50,
						'text' =>  __('There are some functions which should be disabled. It is recommended to disable the following functions:','SwiftSecurity') . ' passthru, exec, shell_exec, system, proc_open, popen, show_source, highlight_file'
				);
			}
			else{
				$result = array(
						'score' => 0,
						'text' =>  __('OK - All necessary functions are disabled', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['php']['allow_url_include'] = array(
					'label' => __('Disabled PHP functions', 'SwiftSecurity'),
					'result' => $result
			);
			
			//PHP checks done
			$CurrentScan['scans']['php'] = true;
			
		}
		
		/*
		 * MySQL settings
		 */
		if ($CurrentScan['scans']['mysql'] != true){
			global $wpdb;
	
			 /*Check MySQL user*/
			$rows = $wpdb->get_results( "SELECT current_user() AS user",ARRAY_A);
			//WP use root mysql user
			if (preg_match('~^root@(.*)$~',$rows[0]['user'])){
				$result = array(
						'score' => 100,
						'text' => __('Wordpress uses the root MySQL user. This is vulnerable and very insecure. Create separate MySQL user for Wordpress', 'SwiftSecurity')
				);
			}
			//WP NOT use root mysql user
			else{
				$result = array(
					'score' => 0,
					'text' => __('OK - Wordpress is not using the root MySQL user', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['mysql']['is_user_root'] = array(
					'label' => __('MySQL user', 'SwiftSecurity'),
					'result' => $result
			);
				
			
			 /*Check file operation*/
			$rows = $wpdb->get_results( "SELECT LOAD_FILE('/etc/passwd') AS LoadFileTest",ARRAY_A);
			//FILE Privilege is on
			if (strlen($rows[0]['LoadFileTest']) > 0){
				$result = array(
					'score' => 100,
					'text' => __('Wordpress MySQL user has file privilege. This is vulnerable and very insecure', 'SwiftSecurity')
				);
			}	
			//FILE Privilege is ok
			else{
				$result = array(
					'score' => 0,
					'text' => __('MySQL user privileges are ok.', 'SwiftSecurity')
				);
			}
			$CurrentScan['report']['mysql']['file_privilege'] = array(
				'label' => __('MySQL privileges', 'SwiftSecurity'),
				'result' => $result
			);
				
			//MySQL checks done
			$CurrentScan['scans']['mysql'] = true;
		}
		
		//Set progress
		$CurrentScan['progress'] += $this->ProgressRatio['mysql'];
		
		/*
		 * File scanning
		 */
	
		if ($CurrentScan['scans']['filescan'] != true || $scheduled){
			SwiftSecurity::ClassInclude('MalwareDetect');
			$MalwareDetect = new SwiftSecurityMalwareDetect();
			$CreationTime = filectime(ABSPATH . 'index.php');
			
			// Remove deleted files from results
			if (isset($CurrentScan['report']['filescan'])){
				foreach ((array)$CurrentScan['report']['filescan'] as $filename=>$value){
					if (!file_exists($filename)){
						unset($CurrentScan['report']['filescan'][$filename]);
						update_option('swiftsecurity_wpscan', $CurrentScan);
					}
				}
			}
			
			//Get all files
			$Files 				= new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABSPATH, \FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
			$ExecutableFiles 	= new RegexIterator($Files, '~\.(php(3|4|5)?|cgi|pl|py|aspx?|jsp)~i', RecursiveRegexIterator::GET_MATCH);				
			//Counters for progress info
			$AllFilesCount = iterator_count($ExecutableFiles);
			$ProgressCounter = 0;
						
			//Iterate the executable files object
			foreach ($ExecutableFiles as $name => $object){
				//Prevent timeout
				set_time_limit(3600);
				
				//Set progress
				$ProgressCounter++;
				$CurrentScan['progress']	= round($ProgressCounter/$AllFilesCount * $this->ProgressRatio['filescan']) + (100-$this->ProgressRatio['filescan']);
				$CurrentScan['timestamp']	= time();
			
				//Skip if md5 checksum is equals with the last checked version
				$FileMd5 = md5_file($name);
				if ($FileMd5 == $CurrentScan['report']['filescan'][$name]['Checksum'] && file_exists($name)){
					continue;
				}
			
				//Detect suspicious functions and snippets
				$MalwareDetect->LoadFile($name, $FileMd5);
				$Results = $MalwareDetect->Detect();
			
				//Check double extensions like .php.js
				if (preg_match('~\.(php(3|4|5)?|cgi|pl|py|aspx?|jsp)\.(.*)$~i', $name)){
					$Results['Alerts'][]= array(
							'label' => __('Double file extension','SwiftSecurity'),
							'result' => array(
									'text' => __('This file has double file extension. It can be malicious (eg: something.php.jpg). It seems to be a jpg file but Apache executes it as PHP','SwiftSecurity')
							));
					$Results['Score'] += 50;
				}
			
				//Check file CTime (creation or last modification time)
				//If file creation time is different than the ABSPATH CTime
				if ($Results['Score'] > 0 && (abs(filectime($name) - $CreationTime) > 30)){
					$Results['Alerts'][]= array(
							'label' => __('Last modified','SwiftSecurity'),
							'result' => array(
									'text' => __('This file is modified or created since Wordpress was installed','SwiftSecurity')
							));
					$Results['Score'] *= 1.1;
				}
			
				//Maximalize score (100)
				$Results['Score'] = ($Results['Score'] > 100 ? 100 : $Results['Score']);
			
				//Create report entry
				$CurrentScan['report']['filescan'][$name] = array(
						'alerts' => $Results['Alerts'],
						'score'	=> $Results['Score'],
						'whitelisted' => $Results['Whitelisted'],
						'Checksum' => $FileMd5
				);
					
				//Update the scan
				update_option('swiftsecurity_wpscan', $CurrentScan);
			}
		}
		
		/*
		 * Scan done
		 */
		if ($scheduled){			
			//Auto-quarantine suspicious files
			$QuarantinedFiles=0; 
			$NewQuarantinedFiles=0;
			if($this->Settings['CodeScanner']['settings']['autoQuarantine'] == 'enabled'){
				foreach ((array)$CurrentScan['report']['filescan'] as $key=>$value){
					if ($value['score'] > 0){
						if ($CurrentScan['report']['filescan'][$key]['quarantine'] == true){
							$QuarantinedFiles++;
						}
						else{
							$CurrentScan['report']['filescan'][$key]['quarantine'] = true;
							$NewQuarantinedFiles++;
						}
					}
				}
				
				update_option('swiftsecurity_wpscan', $CurrentScan);
				
				$this->SwiftSecurity->AddHtaccess($this);
				$this->SwiftSecurity->FlushHtaccess();
			}
			
			//Count PHP setting problems
			$PHPErrors=0;
			foreach ((array)$CurrentScan['report']['php'] as $PHPReport){
				if ($PHPReport['result']['score'] > 0){
					$PHPErrors++;
				}
			}
			
			//Count MySQL setting problems
			$MySQLErrors=0;
			foreach ((array)$CurrentScan['report']['mysql'] as $MySQLReport){
				if ($MySQLReport['result']['score'] > 0){
					$MySQLErrors++;
				}
			}
			
			//Count MySQL errors
			$FileErrors=0;
			foreach ((array)$CurrentScan['report']['filescan'] as $FileReport){
				if ($FileReport['score'] > 0){
					$FileErrors++;
				}
			}
			
			$timestamp = date('d-m-Y H:i:s');
			
			//Send notification
			if($this->Settings['CodeScanner']['settings']['notification'] == 'enabled'){
				ob_start();
				include (SWIFTSECURITY_PLUGIN_DIR . '/templates/mail/scan-report.php');
				$EmailReport = ob_get_clean();
				SwiftSecurity::SendNotification(
					$this->Settings['GlobalSettings']['NotificationEmail'],
					__('Scheduled Code Scan completed','SwiftSecurity'),
					$EmailReport
				);
			}
			
			//Send push notification
			if ($this->Settings['GlobalSettings']['Notifications']['PushNotifications']['Firewall'] == 'enabled'){
				ob_start();
				include (SWIFTSECURITY_PLUGIN_DIR . '/templates/mail/scan-text-report.php');
				$PushoverReport = ob_get_clean();
				SwiftSecurity::SendPushNotification(
					$this->Settings['GlobalSettings']['Notifications']['NotificationPushoverToken'],
					$this->Settings['GlobalSettings']['Notifications']['NotificationPushoverUser'],
					$PushoverReport,
					$this->Settings['GlobalSettings']['Notifications']['NotificationPushoverSound'],
					__('Scheduled Code Scan completed', 'SwiftSecurity')
				);
			}
		}
	}
	
	/**
	 * Build rewrite rules for .htaccess
	 */
	public function GetHtaccess(){
		$Quarantine = '';
		$SitePath = parse_url(site_url(),PHP_URL_PATH);
		$multisite_rewrite_cond = '';
		
		$sq2 = 'sq_' . md5($this->SwiftSecurity->settings['GlobalSettings']['sq']) . '=1';
		
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
		
		
		$Scan = $this->GetScan();
		if (isset($Scan['report']['filescan'])){
			foreach ((array)$Scan['report']['filescan'] as $key=>$value){
				if (isset($value['quarantine']) && $value['quarantine'] == true && !empty($key)){
					$filename = preg_replace('~'.ABSPATH.'~','',$key);
					$Quarantine .= $multisite_rewrite_cond;
					$Quarantine .= 'RewriteCond %{REQUEST_FILENAME} "'.$filename.'"' .PHP_EOL;
					$Quarantine .= 'RewriteRule .* - [F]'.PHP_EOL;
				}
			}
		}
	
		if (!empty($Quarantine)){
			$htaccess  = '<IfModule mod_rewrite.c>'.PHP_EOL;
			$htaccess .= 'RewriteEngine On'.PHP_EOL;
			$htaccess .= 'RewriteBase '.$SitePath.'/'.PHP_EOL;
			$htaccess .= $Quarantine;
			$htaccess .= '</IfModule>'.PHP_EOL;
		}
		else{
			$htaccess = "# No files in quarantine";
		}
		return $htaccess;
	}
	
	/**
	 * Build rewrite rules for nginx
	 */
	public function GetNginxRules(){
		$nginx = '';
		$Quarantine = '';
		$tab = "\t";
		$SitePath = '/' . parse_url(site_url(),PHP_URL_PATH);
		
		$Scan = $this->GetScan();
		foreach ((array)$Scan['report']['filescan'] as $key=>$value){
			if ($value['quarantine'] == true && !empty($key)){
				$filename = preg_replace('~'.ABSPATH.'~','',$key);
				$Quarantine .= $tab.'rewrite ^'.$SitePath.$filename.'$ /swiftsecurity-403'.PHP_EOL;
			}
		}
	
		if (!empty($Quarantine)){
			$nginx .= $Quarantine;
			$nginx .= $tab.'location /swiftsecurity-403 {'.PHP_EOL;
			$nginx .= $tab.$tab.'return 403;'.PHP_EOL;
			$nginx .= $tab.'}'.PHP_EOL;
		}
		else{
			$nginx = "# No files in quarantine";
		}
		return $nginx;
	}
	
}

?>