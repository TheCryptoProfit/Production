<?php 
	defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
	ini_set("display_errors", 0);
	global $wp_query;
	global $wp_rewrite;
	$wp_rewrite->permalink_structure = '';
	$wp_query->set_404();
	status_header(404);
	if ($GLOBALS['swiftsecurity_die_404']){
		die;
	}
?>