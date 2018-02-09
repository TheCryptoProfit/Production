<?php 
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");

if (!function_exists('add_filters')):
/**
 * Add batch filters
 * @param array $filters
 * @param array|string $callback
 */
function add_filters($filters, $callback, $position = 10){
	foreach ((array)$filters as $filter){
		add_filter($filter, $callback, $position);
	}
}
endif;

if (!function_exists('remove_filters')):
/**
 * Remove batch filters
* @param array $filters
* @param array|string $callback
*/
function remove_filters($filters, $callback){
	foreach ((array)$filters as $filter){
		remove_filter($filter, $callback);
	}
}
endif;

if (!function_exists('add_actions')):
/**
 * Add batch actions
* @param array $actions
* @param array|string $callback
*/
function add_actions($actions, $callback, $position = 10){
	foreach ((array)$actions as $action){
		add_action($action, $callback, $position);
	}
}
endif;

if (!function_exists('remove_actions')):
/**
 * Remove batch actions
* @param array $action
* @param array|string $callback
*/
function remove_actions($action, $callback){
	foreach ((array)$action as $filter){
		remove_action($action, $callback);
	}
}
endif;

if (!function_exists('http_build_url')):
/**
 * Builds URL from a parsed array
 */
function http_build_url($parsed_url){
	$scheme 	= (isset($parsed_url['scheme']) ? $parsed_url['scheme'].'://' : '//');
	$userparts	= (isset($parsed_url['user']) ? (isset($parsed_url['pass']) ? $parsed_url['user'] . ':'. $parsed_url['pass'] . '@' : $parsed_url['user'] . '@') : '');
	$host		= (isset($parsed_url['host']) ? $parsed_url['host'] : '');
	$path		= (isset($parsed_url['path']) ? $parsed_url['path'] : '');
	$query		= (isset($parsed_url['query']) ? '?'.$parsed_url['query'] : '');
	$fragment	= (isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '');
	
	return (empty($host) ? '' : $scheme) . $userparts . $host . $path . $query . $fragment;
}
endif;

if ( !function_exists('htmlspecialchars_decode') ):
/**
 * htmlspecialchars_decode
 */
function htmlspecialchars_decode($text){
	return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
}
endif;

if (!function_exists('swift_remove_empty_elements_deep')) :
/**
 * Recursive remove empty elements from array
 * @param array
 * @return array
 */
function swift_remove_empty_elements_deep($array){
	foreach ((array)$array as $key=>$value){
		if (is_array($value)){
			if (empty($value)){
				unset($array[$key]);
			}
			else{
				$array[$key] = swift_remove_empty_elements_deep($value);
			}
		}
		else{
			if (empty($value)){
				unset($array[$key]);
			}
		}
	}
	return $array;
}
endif;

if (!function_exists('swift_base64_decode_deep')) :
/**
 * Recursive base64 elements on array
 * @param array
 * @return array
 */
function swift_base64_decode_deep($array){
	foreach ((array)$array as $key=>$value){
		if (is_array($value)){
				$array[$key] = swift_base64_decode_deep($value);
		}
		else{
			$check = base64_decode($value,true);
			$utfonly = (function_exists('iconv') ? iconv("UTF-8","UTF-8//IGNORE",$check) : swiftsecurity_iconv_bypass($check));
			if ($check !== false && $check == $utfonly){
				$array[$key] = base64_decode($value);
			}
			else{
				$array[$key] = $value;
			}
		}
	}
	return $array;
}
endif;

if (!function_exists('swift_replace_deep')) :
/**
 * Recursive preg_replace on array's elements
* @param array $array
* @param strting $pattern
* @param string $replacement
* @return array
*/
function swift_replace_deep($array, $pattern, $replacement){
	foreach ((array)$array as $key=>$value){
		if (is_array($value)){
			$array[$key] = swift_replace_deep($value, $pattern, $replacement);
		}
		else{
			$array[$key] = preg_replace('~'.$pattern.'~', $replacement, $value);
		}
	}
	return $array;
}
endif;

if (!function_exists('swiftsecurity_safe_echo')) :
/**
 * Escape quotes and nullbyte
 * @param string
 * @return string
 */
function swiftsecurity_safe_echo($string){
	$string = str_replace('>','&gt;', $string);
	$string = str_replace('<','&lt;', $string);
	$string = str_replace('"','&quot;', $string);
	$string = str_replace('%00','%2500', $string);
	echo $string;
}
endif;

if (!function_exists('array_diff_key_recursive')) :
/**
 * Computes the difference of multidimensional arrays using keys for comparison
 * @param array $a1
 * @param array $a2
 * @return array
 */
function array_diff_key_recursive ($a1, $a2) {
	$r = array();
	foreach($a1 as $k => $v) {
		if (is_array($v))
		{
			$r[$k]=array_diff_key_recursive($a1[$k], $a2[$k]);
		}else
		{
			$r=array_diff_key($a1, $a2);
		}

		if (isset($r[$k]) && is_array($r[$k]) && count($r[$k])==0)
		{
			unset($r[$k]);
		}
	}
	return $r;
}
endif;

if (!function_exists('recursive_rmdir')) :
/**
 * Recursive rmdir
 * @param string $dir
 */
function recursive_rmdir($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? recursive_rmdir("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
}
endif;


if (!function_exists('iconv') && !function_exists('swiftsecurity_iconv_bypass')) :
/**
 * Remove non-utf8 characters from string. We use it if incov is not enabled on the server
 * @param string $string
 * @return string
 */
function swiftsecurity_iconv_bypass($string){
	//reject simple non utf-8 xters
	$string = preg_replace('/[^(\x20-\x7F)]*/','', $string);

	return $string;
}
endif;

if (!function_exists('unleadingslash')) :
/**
 * Remove leadingslash
 * @param string $string
 * @return string
 */
function unleadingslash($string){
	if (substr($string,0,1) == '/'){
		$string = substr($string,1);
	}

	return $string;
}
endif;

if (!function_exists('swift_get_absolute_url')):
/**
 * Returns the absolute URL, adding scheme if missing
 * @param string $url
 * @return string
 */
function swift_get_absolute_url($url){
	if (preg_match('~^//~',$url)){
		return parse_url(site_url(), PHP_URL_SCHEME) . ':' . $url;
	}
	else if(preg_match('~^/~',$url)){
		return site_url() . $url;
	}
	else{
		return $url;
	}
}
endif;

if (!function_exists('unleadingslashit')):
/**
 * Remove leading slash from string
 * @param string $url
 * @return string
 */
function unleadingslashit($url){
	if (substr($url,0,1) == '/'){
		return substr($url,1);
	}
	return $url;
}
endif;


if (!function_exists('swiftsecurity_menu_page_url')):
/**
 * Return menu_page_url or network menu_page_url if network admin is active
 * @param string $slug
 * @param boolean $echo default is false
 * @return void|string
 */
function swiftsecurity_menu_page_url($slug, $echo = false){
	$menu_page_url = menu_page_url($slug, false);
	//If network admin
	if(is_network_admin()){
		$menu_page_url = network_admin_url(str_replace(admin_url(),'',$menu_page_url));
	}
	
	if ($echo){
		echo $menu_page_url;
	}
	else{
		return $menu_page_url;
	}
	
}
endif;

?>