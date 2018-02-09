<?php 
/**
 * Proxy POST requests to filter POST and UPLOAD data for each requests
 */

class SwiftSecurityProxy{

	public function __construct($settings, $Firewall){
		$this->settings = $settings;
		$this->Firewall = $Firewall;
	}

	/** 
	 * Check the POST and the uploaded files and stop the request when regexp match
	 */
	public function Proxy(){
		//Set WP_ADMIN because POST Proxy loose it
		if (!defined('WP_ADMIN') && preg_match('~^/'.str_replace(site_url(), '/', admin_url('admin.php')).'~',$_SERVER['REQUEST_URI'])){
			define ('WP_ADMIN', true);
		}
		
		//Proxy the request if it is post
		$request_scheme = (isset($_SERVER['HTTPS']) ? 'https' : 'http');
		$request_url =  $request_scheme . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		//Remove extra slashes
		$_POST = stripslashes_deep($_POST);
		$request_params = $_POST;		
		
		$upload_size = 0;
		//Multipart data fix for empty file uploads
		foreach ($_FILES as $file){
			$upload_size += $file['size'];
		}
		
		//Filter the post vars
		if ($upload_size == 0){
			if (!$this->_ShouldSkip()){
				$this->_FilterPost($request_params);
			}
			
			//Set POST data for proxy request
			$post_data = http_build_query($request_params);
		
		}
		//Filter uploaded files
		else if (count($_FILES) > 0){
			$proxied_FILES = array();
			foreach ((array)$_FILES as $key=>$value){
				if ((isset($this->settings['Firewall']['File']['exceptions']['POST']) && !in_array(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH), (array)$this->settings['Firewall']['File']['exceptions']['POST'])) && !$this->_ShouldSkip() && $this->settings['Firewall']['File']['settings']['POST'] == 'enabled'){
					$content = file_get_contents($_FILES[$key]['tmp_name']);
					$filters = $this->Firewall->GetRegexp('File', 'POST');
					//Filter content
					foreach ((array)$filters['content'] as $regexp){
						if (preg_match('~'.$regexp.'.~is',$content)){
							$this->Firewall->LogData = array(
									'attempt'	=> 'Upload',
									'channel'	=> 'POST'
							);
							$this->Firewall->Log();
							$this->Firewall->Forbidden();
						}
					}
					
					//Filter file extensions
					foreach ((array)$filters['extension'] as $regexp){
						if (preg_match('~^(.*)\.'.$regexp.'(\.(.*))?$.~i',$_FILES[$key]['name'])){
							$this->Firewall->LogData = array(
									'attempt'	=> 'Upload',
									'channel'	=> 'POST'
							);
							$this->Firewall->Log();
							$this->Firewall->Forbidden();
						}
					}
				}
				
				$proxy_name = pathinfo($_FILES[$key]['tmp_name'], PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $_FILES[$key]['name'];
				move_uploaded_file($_FILES[$key]['tmp_name'], $proxy_name);
				if (PHP_VERSION_ID < 50500){
					$proxied_FILES[$key] = '@' . $proxy_name;
				}
				else{
					$proxied_FILES[$key] = new CurlFile($proxy_name, $_FILES[$key]['type'], $_FILES[$key]['name']);
				}
			}
			
			//Set POST data for proxy request
			$post_data = array_merge($request_params, $proxied_FILES);
		}
		
		//Unset original $_POST array
		unset($_POST);

		//Get original headers
		$request_headers = array( );
		foreach ((array)$_SERVER as $key => $value ) {
			if ( substr( $key, 0, 5 ) == 'HTTP_' ) {
				$headername = str_replace( '_', ' ', substr( $key, 5 ) );
				$headername = str_replace( ' ', '-', ucwords( strtolower( $headername ) ) );
				if ($headername == 'Cookie'){
					//Get the cookie vars
					if (count($_COOKIE) > 0){
						$cookies = '';
						foreach ((array)$_COOKIE as $key=>$value){
							$cookies .= $key . '=' . $value .'; ';
						}
						$request_headers[] = 'Cookie: ' . $cookies;
					}
				}
				else{
					$request_headers[] = "$headername: $value";
				}
			}
		}
		
		$request_headers[] = 'X-SwiftSecurity-Proxy: ' . $this->settings['GlobalSettings']['sq'];
		$request_headers[] = 'X-Forwarded-For: ' . (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->Firewall->GetIP());
		$request_headers[] = 'X-Forwarded-For-'.$this->settings['GlobalSettings']['sq'].': ' . $this->Firewall->GetIP();
		
		//Send the request
		$ch = curl_init( $request_url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $request_headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,  $post_data );
		$response = curl_exec( $ch );
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close( $ch );
		
		

		//Delete proxied files after curl
		if (isset($proxied_FILES) && count($proxied_FILES) > 0){
			foreach ((array)$proxied_FILES as $file){
				if (is_file(substr($file->name,1))){
					unlink (substr($file->name,1));
				}
			}
		}
		//echo $response;die;
		//Resend the response original headers
		$response_headers = substr($response, 0, $header_size);
		$response_content = substr($response, $header_size);
					
		$response_headers = preg_split( '/(\r\n){1}/', $response_headers );
		foreach ((array)$response_headers as $key => $response_header ) {
			if (!preg_match( '/^(Transfer-Encoding|Content-Encoding):/', $response_header ) && !empty($response_header)) {
				header( $response_header, false );
			}
		}
		
		echo $response_content;
		die;
	}

	/**
	 * Cases when Firewall should skip the checking
	 */
	private function _ShouldSkip(){
		$user = wp_get_current_user();
		
		//When you set regexp at the settings page
		if (isset($_POST['sq']) && $_POST['sq'] == $this->settings['GlobalSettings']['sq']){
			return true;
		}
		if (isset($this->settings['Firewall']['WhitelistedUsers']) && is_array($this->settings['Firewall']['WhitelistedUsers']) && in_array($user->ID, $this->settings['Firewall']['WhitelistedUsers'])){
			return true;
		}
		return false;
	}
	
	/**
	 * Recursive filter for POST array
	 * @param array $array
	 */
	private function _FilterPost($array){
		global $wp;

		foreach ((array)$array as $post){
			if (is_array($post)){
				$this->_FilterPost($post);
			}
			else{				
				//Filter SQL injections
				if (((isset($this->settings['Firewall']['SQLi']['exceptions']['POST']) && !in_array(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH), (array)$this->settings['Firewall']['SQLi']['exceptions']['POST'])) || !isset($this->settings['Firewall']['SQLi']['exceptions']['POST']))  && (isset($this->settings['Firewall']['SQLi']['settings']['POST']) && $this->settings['Firewall']['SQLi']['settings']['POST'] == 'enabled')){
					foreach ((array)$this->Firewall->GetRegexp('SQLi', 'POST') as $regexp){						
						if (preg_match('~'.$regexp.'~i',$post)){
							$this->Firewall->LogData = array(
									'attempt'	=> 'SQLi',
									'channel'	=> 'POST'
							);
							$this->Firewall->Log();
							$this->Firewall->Forbidden();
						}
					}
				}
				//Filter path injections
				if (((isset($this->settings['Firewall']['Path']['exceptions']['POST']) && !in_array(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),  (array)$this->settings['Firewall']['Path']['exceptions']['POST'])) || !isset($this->settings['Firewall']['Path']['exceptions']['POST'])) && (isset($this->settings['Firewall']['Path']['settings']['POST']) && $this->settings['Firewall']['Path']['settings']['POST'] == 'enabled')){
					foreach ((array)$this->Firewall->GetRegexp('Path', 'POST') as $regexp){
						if (preg_match('~'.$regexp.'~i',$post)){
							$this->Firewall->LogData = array(
									'attempt'	=> 'Path',
									'channel'	=> 'POST'
							);
							$this->Firewall->Log();
							$this->Firewall->Forbidden();
						}
					}
				}
			}
		}
	}
}

?>