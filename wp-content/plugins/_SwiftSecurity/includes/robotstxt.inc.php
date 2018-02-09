<?php 
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
global $SwiftSecurity;
if (file_exists(ABSPATH . 'robots.txt')){
	//return with replaced robots.txt
	$robots_txt = file_get_contents(ABSPATH . 'robots.txt');
	header('Content-Type: text/plain');
	echo $SwiftSecurity->HideWP->ReplaceString($robots_txt);
	die;
}
else{
	//404
	global $wp_query;
	global $wp_rewrite;
	$wp_rewrite->permalink_structure = '';
	$wp_query->is_404 = true;
	if (isset($GLOBALS['swiftsecurity_die_404']) && $GLOBALS['swiftsecurity_die_404']){
		die;
	}
}
?>