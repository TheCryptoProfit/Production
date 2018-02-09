<?php
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
global $wp;

//Get the settings
$SettingsObject = new SwiftSecuritySettings();
$settings = $SettingsObject->GetSettings();

//Create Firewall object
$Firewall = new SwiftSecurityFirewall($settings);

//If the request type is POST proxy the request
if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
	SwiftSecurity::ClassInclude('Proxy');
	$Proxy = new SwiftSecurityProxy($settings, $Firewall);
	$Proxy->Proxy();
}
//Any other call is a possible attack attempt, so log and block it
else{
	//Skip whiteliste
	$user = wp_get_current_user();
	if (!isset($settings['Firewall']['WhitelistedUsers']) || !is_array($settings['Firewall']['WhitelistedUsers']) || !in_array($user->ID, $settings['Firewall']['WhitelistedUsers'])){
		$Firewall->LogData = array(
			'attempt'	=> $_GET['attempt'],
			'channel'	=> $_GET['channel']
		);
		$Firewall->Log();
		$Firewall->Forbidden();
	}
}
