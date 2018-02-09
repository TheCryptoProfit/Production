<?php
ini_set("display_errors", 0);
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");

SwiftSecurity::ClassInclude('HideWP');
SwiftSecurity::ClassInclude('Settings');
SwiftSecurity::ClassInclude('CSSMinifier');
if (!class_exists('\JShrink\Minifier')){
	SwiftSecurity::ClassInclude('JSMinifier');
}
$SettingsObject = new SwiftSecuritySettings();
$Settings = $SettingsObject->GetSettings();
$HideWP = new SwiftSecurityHideWP($Settings);

//Get request path
$RequestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Remove .wpengine from RequestPath
$RequestPath = preg_replace('~\.wpengine$~','',$RequestPath);

//Determine file type
$FileType = pathinfo($RequestPath, PATHINFO_EXTENSION);

parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $RequestQuery);
$RequestQuery['proxy_sq_' . md5($Settings['GlobalSettings']['sq'])] = 1;
$RequestQuery[$Settings['GlobalSettings']['sq']] = 1;
if (defined('SWIFTSECURITY_DISABLE_LOOPBACK') && SWIFTSECURITY_DISABLE_LOOPBACK){
	$subdir = parse_url(site_url(), PHP_URL_PATH);
	$response = array();
	$response['response']['code'] = 200;
	$response['body'] = file_get_contents(ABSPATH . apply_filters('swiftsecurity_reverse_replace', str_replace($subdir, '', $RequestPath)));
	$response['headers']['content-type'] = 'text/' . (pathinfo($RequestPath,PATHINFO_EXTENSION) == 'js' ? 'javascript' : 'css');
}
else{
	$response = wp_remote_get((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $RequestPath . '?' . http_build_query($RequestQuery), array('sslverify' => false));
}
if (is_array($response)){
	if (($response['response']['code'] == '200' || $response['response']['code'] == '304') && preg_match('~(text/css|javascript)~',$response['headers']['content-type'])){
		$content = $response['body'];
	}
	else if ($response['response']['code'] == '404' || !preg_match('~(text/css|javascript)~',$response['headers']['content-type'])){
		header("HTTP/1.0 ".$response['response']['code']." ".$response['response']['message']);
		include_once (get_404_template());
		die;
	}
	else{
		header("HTTP/1.0 ".$response['response']['code']." ".$response['response']['message']);
		die();
	}
}
else{
	header("HTTP/1.0 404 Not found");
	include_once (get_404_template());
	die;
}

if ((isset($Settings['HideWP']['minifycss']) && $Settings['HideWP']['minifycss'] == 'enabled') && $FileType == 'css'){
	try {
		$content = SwiftSecurityCSSMinifier::minify($content);
	} catch (Exception $e) {
		//Silent fail
	}
}
if ((isset($Settings['HideWP']['minifyjs']) && $Settings['HideWP']['minifyjs'] == 'enabled') && $FileType == 'js'){
	try {
		//Dont minify already minified JS files
		if (!preg_match('~\.min\.js$~',$RequestPath)){
			$MinifiedContent = \JShrink\Minifier::minify($content);
			
			//If it didn't minified
			if (!empty($content) && (strlen($MinifiedContent) / strlen($content) < 0.9)){
				$content = $MinifiedContent;
			}
		}
		$content  = preg_replace('~/\*.*?\*/~s', '', $content);
	} catch (Exception $e) {
		//Silent fail
	}	
}

//Send headers
header('HTTP/1.1 200 OK');
header('Content-Type: ' . $response['headers']['content-type']);
header('X-Via: CDN-Proxy');

//Make replacements if not admin
if (!isset($_GET['admin'])){
	//Replace CSS classnames and HTML ids
	$content = $HideWP->ReplaceCDNProxy($content);
	
	//Replace in javascript
	if ($FileType == 'js'){
		$content = $HideWP->ReplaceInJS($content);
	}
	
	//Replace in CSS
	if ($FileType == 'css'){
		$content = $HideWP->ReplaceInCSS($content);
	}
}
//Print content
echo $content;

//Cache file
if (!file_exists(SWIFTSECURITY_PLUGIN_DIR . '/cache' . $HideWP->cache_site_url_padding . pathinfo($RequestPath,PATHINFO_DIRNAME))){
		@mkdir(SWIFTSECURITY_PLUGIN_DIR . '/cache' . $HideWP->cache_site_url_padding . pathinfo($RequestPath,PATHINFO_DIRNAME), 0777, true);
		@chmod(SWIFTSECURITY_PLUGIN_DIR . '/cache' . $HideWP->cache_site_url_padding . pathinfo($RequestPath,PATHINFO_DIRNAME), 0777);
}
file_put_contents(SWIFTSECURITY_PLUGIN_DIR . '/cache' . $HideWP->cache_site_url_padding . $RequestPath, $content);

die;

?>