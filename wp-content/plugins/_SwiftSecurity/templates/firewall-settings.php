<?php defined('ABSPATH') or die("KEEP CALM AND CARRY ON");?>
<?php 
	try{
		SwiftSecurity::CompatibilityCheck('Firewall');
	}
	catch (SwiftSecuritySettingsException $e){
		$GLOBALS['SwiftSecurityMessage']['message'] = $e->getMessage();
		$GLOBALS['SwiftSecurityMessage']['type'] = 'sft-notification-error';
		SwiftSecurity::AdminNotice();
	}
	
	$countries = array( "AF" => "Afghanistan", "AL" => "Albania", "DZ" => "Algeria", "AS" => "American Samoa", "AD" => "Andorra", "AO" => "Angola", "AI" => "Anguilla", "AQ" => "Antarctica", "AG" => "Antigua and Barbuda", "AR" => "Argentina", "AM" => "Armenia", "AW" => "Aruba", "AU" => "Australia", "AT" => "Austria", "AZ" => "Azerbaijan", "BS" => "Bahamas", "BH" => "Bahrain", "BD" => "Bangladesh", "BB" => "Barbados", "BY" => "Belarus", "BE" => "Belgium", "BZ" => "Belize", "BJ" => "Benin", "BM" => "Bermuda", "BT" => "Bhutan", "BO" => "Bolivia", "BA" => "Bosnia and Herzegovina", "BW" => "Botswana", "BV" => "Bouvet Island", "BR" => "Brazil", "BQ" => "British Antarctic Territory", "IO" => "British Indian Ocean Territory", "VG" => "British Virgin Islands", "BN" => "Brunei", "BG" => "Bulgaria", "BF" => "Burkina Faso", "BI" => "Burundi", "KH" => "Cambodia", "CM" => "Cameroon", "CA" => "Canada", "CT" => "Canton and Enderbury Islands", "CV" => "Cape Verde", "KY" => "Cayman Islands", "CF" => "Central African Republic", "TD" => "Chad", "CL" => "Chile", "CN" => "China", "CX" => "Christmas Island", "CC" => "Cocos [Keeling] Islands", "CO" => "Colombia", "KM" => "Comoros", "CG" => "Congo - Brazzaville", "CD" => "Congo - Kinshasa", "CK" => "Cook Islands", "CR" => "Costa Rica", "HR" => "Croatia", "CU" => "Cuba", "CY" => "Cyprus", "CZ" => "Czech Republic", "CI" => "Côte d’Ivoire", "DK" => "Denmark", "DJ" => "Djibouti", "DM" => "Dominica", "DO" => "Dominican Republic", "NQ" => "Dronning Maud Land", "DD" => "East Germany", "EC" => "Ecuador", "EG" => "Egypt", "SV" => "El Salvador", "GQ" => "Equatorial Guinea", "ER" => "Eritrea", "EE" => "Estonia", "ET" => "Ethiopia", "FK" => "Falkland Islands", "FO" => "Faroe Islands", "FJ" => "Fiji", "FI" => "Finland", "FR" => "France", "GF" => "French Guiana", "PF" => "French Polynesia", "TF" => "French Southern Territories", "FQ" => "French Southern and Antarctic Territories", "GA" => "Gabon", "GM" => "Gambia", "GE" => "Georgia", "DE" => "Germany", "GH" => "Ghana", "GI" => "Gibraltar", "GR" => "Greece", "GL" => "Greenland", "GD" => "Grenada", "GP" => "Guadeloupe", "GU" => "Guam", "GT" => "Guatemala", "GG" => "Guernsey", "GN" => "Guinea", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HT" => "Haiti", "HM" => "Heard Island and McDonald Islands", "HN" => "Honduras", "HK" => "Hong Kong SAR China", "HU" => "Hungary", "IS" => "Iceland", "IN" => "India", "ID" => "Indonesia", "IR" => "Iran", "IQ" => "Iraq", "IE" => "Ireland", "IM" => "Isle of Man", "IL" => "Israel", "IT" => "Italy", "JM" => "Jamaica", "JP" => "Japan", "JE" => "Jersey", "JT" => "Johnston Island", "JO" => "Jordan", "KZ" => "Kazakhstan", "KE" => "Kenya", "KI" => "Kiribati", "KW" => "Kuwait", "KG" => "Kyrgyzstan", "LA" => "Laos", "LV" => "Latvia", "LB" => "Lebanon", "LS" => "Lesotho", "LR" => "Liberia", "LY" => "Libya", "LI" => "Liechtenstein", "LT" => "Lithuania", "LU" => "Luxembourg", "MO" => "Macau SAR China", "MK" => "Macedonia", "MG" => "Madagascar", "MW" => "Malawi", "MY" => "Malaysia", "MV" => "Maldives", "ML" => "Mali", "MT" => "Malta", "MH" => "Marshall Islands", "MQ" => "Martinique", "MR" => "Mauritania", "MU" => "Mauritius", "YT" => "Mayotte", "FX" => "Metropolitan France", "MX" => "Mexico", "FM" => "Micronesia", "MI" => "Midway Islands", "MD" => "Moldova", "MC" => "Monaco", "MN" => "Mongolia", "ME" => "Montenegro", "MS" => "Montserrat", "MA" => "Morocco", "MZ" => "Mozambique", "MM" => "Myanmar [Burma]", "NA" => "Namibia", "NR" => "Nauru", "NP" => "Nepal", "NL" => "Netherlands", "AN" => "Netherlands Antilles", "NT" => "Neutral Zone", "NC" => "New Caledonia", "NZ" => "New Zealand", "NI" => "Nicaragua", "NE" => "Niger", "NG" => "Nigeria", "NU" => "Niue", "NF" => "Norfolk Island", "KP" => "North Korea", "VD" => "North Vietnam", "MP" => "Northern Mariana Islands", "NO" => "Norway", "OM" => "Oman", "PC" => "Pacific Islands Trust Territory", "PK" => "Pakistan", "PW" => "Palau", "PS" => "Palestinian Territories", "PA" => "Panama", "PZ" => "Panama Canal Zone", "PG" => "Papua New Guinea", "PY" => "Paraguay", "YD" => "People's Democratic Republic of Yemen", "PE" => "Peru", "PH" => "Philippines", "PN" => "Pitcairn Islands", "PL" => "Poland", "PT" => "Portugal", "PR" => "Puerto Rico", "QA" => "Qatar", "RO" => "Romania", "RU" => "Russia", "RW" => "Rwanda", "RE" => "Réunion", "BL" => "Saint Barthélemy", "SH" => "Saint Helena", "KN" => "Saint Kitts and Nevis", "LC" => "Saint Lucia", "MF" => "Saint Martin", "PM" => "Saint Pierre and Miquelon", "VC" => "Saint Vincent and the Grenadines", "WS" => "Samoa", "SM" => "San Marino", "SA" => "Saudi Arabia", "SN" => "Senegal", "RS" => "Serbia", "CS" => "Serbia and Montenegro", "SC" => "Seychelles", "SL" => "Sierra Leone", "SG" => "Singapore", "SK" => "Slovakia", "SI" => "Slovenia", "SB" => "Solomon Islands", "SO" => "Somalia", "ZA" => "South Africa", "GS" => "South Georgia and the South Sandwich Islands", "KR" => "South Korea", "ES" => "Spain", "LK" => "Sri Lanka", "SD" => "Sudan", "SR" => "Suriname", "SJ" => "Svalbard and Jan Mayen", "SZ" => "Swaziland", "SE" => "Sweden", "CH" => "Switzerland", "SY" => "Syria", "ST" => "São Tomé and Príncipe", "TW" => "Taiwan", "TJ" => "Tajikistan", "TZ" => "Tanzania", "TH" => "Thailand", "TL" => "Timor-Leste", "TG" => "Togo", "TK" => "Tokelau", "TO" => "Tonga", "TT" => "Trinidad and Tobago", "TN" => "Tunisia", "TR" => "Turkey", "TM" => "Turkmenistan", "TC" => "Turks and Caicos Islands", "TV" => "Tuvalu", "UM" => "U.S. Minor Outlying Islands", "PU" => "U.S. Miscellaneous Pacific Islands", "VI" => "U.S. Virgin Islands", "UG" => "Uganda", "UA" => "Ukraine", "SU" => "Union of Soviet Socialist Republics", "AE" => "United Arab Emirates", "GB" => "United Kingdom", "US" => "United States", "ZZ" => "Unknown or Invalid Region", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VU" => "Vanuatu", "VA" => "Vatican City", "VE" => "Venezuela", "VN" => "Vietnam", "WK" => "Wake Island", "WF" => "Wallis and Futuna", "EH" => "Western Sahara", "YE" => "Yemen", "ZM" => "Zambia", "ZW" => "Zimbabwe", "AX" => "Åland Islands");
?>
<form id="FirewallForm" method="post" class="ss-base64-armored">
	<div class="pull-right">
		<?php if(isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled'):?>
			<a href="<?php echo (is_network_admin() ? network_admin_url( 'settings.php?page=SwiftSecurity') : admin_url( 'options-general.php?page=SwiftSecurity'));?>" class="sft-btn btn-sm"><?php _e('Dashboard', 'SwiftSecurity');?></a>
		<?php endif;?>
		<?php if(!is_network_admin()):?>
		<a href="<?php menu_page_url('SwiftSecurityFirewall', true);?>&option=Log" class="sft-btn btn-sm"><?php _e('See logs', 'SwiftSecurity');?></a>
		<?php endif;?>
	 	<button name="swift-security-settings-save" class="sft-btn btn-sm btn-green" value="Firewall"><?php _e('Save settings', 'SwiftSecurityFw');?></button>
	 	<button name="swift-security-settings-save" class="sft-btn btn-sm btn-gray restore-defaults" value="RestoreDefault"><?php _e('Restore defaults', 'SwiftSecurityFw');?></button>
	</div>	
    <?php wp_nonce_field( 'save_settings', 'SwiftSecurityNonce' ); ?>
	<div class="onoffswitch">
	    <input type="checkbox" name="settings[Modules][Firewall]" class="onoffswitch-checkbox" id="Firewall" value="enabled" <?php echo (isset($settings['Modules']['Firewall']) && $settings['Modules']['Firewall'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label" for="Firewall">
		    <span class="onoffswitch-inner"></span>
		    <span class="onoffswitch-switch"></span>
	    </label>
    </div>
    
    <h2>Swift Security - <?php _e('Firewall', 'SwiftSecurity');?></h2>
	<h4><?php _e('SQL injection prevention', 'SwiftSecurity');?></h4>
	<input type="hidden" id="preset-value-SQLi" name="settings[Firewall][Preset][SQLi]">
	<div class="firewall-slider-sqli fw-slider" data-min="0" data-max="3" data-current="<?php echo (isset($settings['Firewall']['settings']['presets']['SQLi']) ? $settings['Firewall']['settings']['presets']['SQLi'] : 0);?>" data-set="SQLi"></div>
	<div class="custom-settings">
		<h4><?php _e('Advanced Settings', 'SwiftSecurity');?></h4>
		<div id="SQLi-settings" class="firewall-settings" data-set="SQLi"></div>
	</div>
	
	<h4><?php _e('XSS prevention', 'SwiftSecurity');?></h4>
	<input type="hidden" id="preset-value-XSS" name="settings[Firewall][Preset][XSS]">
	<div class="firewall-slider-xss fw-slider" data-min="0" data-max="3" data-current="<?php echo (isset($settings['Firewall']['settings']['presets']['XSS']) ? $settings['Firewall']['settings']['presets']['XSS'] : 0);?>" data-set="XSS"></div>
	<div class="custom-settings">
	 	<h4><?php _e('Advanced Settings', 'SwiftSecurity');?></h4>
		<div id="XSS-settings" class="firewall-settings" data-set="XSS"></div>
	</div>
			
	<h4><?php _e('File path manipulation prevention', 'SwiftSecurity');?></h4>
	<input type="hidden" id="preset-value-Path" name="settings[Firewall][Preset][Path]">
	<div class="firewall-slider-path fw-slider" data-min="0" data-max="2" data-current="<?php echo (isset($settings['Firewall']['settings']['presets']['Path']) ? $settings['Firewall']['settings']['presets']['Path'] : 0);?>" data-set="Path"></div>
	<div class="custom-settings">
		<h4><?php _e('Advanced Settings', 'SwiftSecurity');?></h4>
		<div id="Path-settings" class="firewall-settings" data-set="Path"></div>
	</div>
		
	<h4><?php _e('Malicious file upload prevention', 'SwiftSecurity');?></h4>
	<input type="hidden" id="preset-value-File" name="settings[Firewall][Preset][File]">
	<div class="firewall-slider-file fw-slider"  data-min="0" data-max="3" data-current="<?php echo (isset($settings['Firewall']['settings']['presets']['File']) ? $settings['Firewall']['settings']['presets']['File'] : 0);?>" data-set="File"></div>
		
	<div class="custom-settings">
	 	<h4><?php _e('Advanced Settings', 'SwiftSecurity');?></h4>
		<div id="File-settings" class="firewall-settings" data-set="File"></div>
	</div>
	
	<div class="additional-settings">
	 	<h4>
	 		<?php _e('Comment spam filter', 'SwiftSecurity');?>
	 	</h4>
	 	 <div>
		 	<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[Firewall][commentSpamBlocker]" class="onoffswitch-checkbox-sm" id="onoff-sm-commentspamblocker" value="enabled" <?php echo ((isset($settings['Firewall']['commentSpamBlocker']) && $settings['Firewall']['commentSpamBlocker'] == 'enabled') ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-commentspamblocker">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label><?php _e('Blocks comment spam without captcha', 'SwiftSecurity');?></label>
		</div>
 	</section>
	
 	<h4><?php _e('IP whitelist for login', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 		<?php if (isset($settings['Firewall']['IP']['Whitelist'])):?>
  		<?php foreach ((array)$settings['Firewall']['IP']['Whitelist'] as $ip) :?>
 		<div>
 			<input type="text" name="settings[Firewall][IP][Whitelist][]" value="<?php echo $ip?>">
 			<a href="#" class="remove-input close-icon"></a>
 		</div>
 		<?php endforeach;?>
 		<?php endif;?>
 		<div class="sample-container">
 			<input type="text" class="input-sample" data-name="settings[Firewall][IP][Whitelist][]">
	 		<a href="#" class="remove-input close-icon"></a> 			
 		</div> 			
 	</div>
 	
 	<h4><?php _e('GEO whitelist for login', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 	 	<button class="sft-btn btn-sm btn-gray select-all"><?php _e('Select all', 'SwiftSecurity');?></button>
 		<button class="sft-btn btn-sm btn-gray deselect-all"><?php _e('Deselect all', 'SwiftSecurity');?></button>
 		<div class="ss-cblist">
		<?php foreach ($countries as $key=>$value):?>
 		    <label>
 				<input type="checkbox" name="settings[Firewall][IP][CountryLoginWhitelist][<?php echo $key;?>]" value="<?php echo $key;?>" <?php echo (isset($settings['Firewall']['IP']['CountryLoginWhitelist']) && in_array($key,$settings['Firewall']['IP']['CountryLoginWhitelist']) ? 'checked="checked"' : '')?>>
				<?php echo $value;?>
 			</label><br>
 		<?php endforeach;?>
 		</div>
 	</div>
 	
 	<h4><?php _e('IP blacklist', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 		<div><textarea name="settings[Firewall][IP][Blacklist]"><?php if(isset($settings['Firewall']['IP']['Blacklist'])) {foreach ($settings['Firewall']['IP']['Blacklist'] as $ip) { echo $ip."\n"; }}?></textarea></div> 		
 	</div>
 	
 	<h4><?php _e('GEO blacklist', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 	 	<button class="sft-btn btn-sm btn-gray select-all"><?php _e('Select all', 'SwiftSecurity');?></button>
 		<button class="sft-btn btn-sm btn-gray deselect-all"><?php _e('Deselect all', 'SwiftSecurity');?></button>
 		<div class="ss-cblist">
 		<?php foreach ($countries as $key=>$value):?>
 		    <label>
 				<input type="checkbox" name="settings[Firewall][IP][CountryBlacklist][<?php echo $key;?>]" value="<?php echo $key;?>" <?php echo (isset($settings['Firewall']['IP']['CountryBlacklist']) && in_array($key,$settings['Firewall']['IP']['CountryBlacklist']) ? 'checked="checked"' : '')?>>
 				<?php echo $value;?>
 			</label><br>
 		<?php endforeach;?>
 		</div>		
 	</div>
 	
 	<h4><?php _e('Anti-Brute Force', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 		<label>Maximum attempts</label>
 		<input type="number" name="settings[Firewall][IP][AutoBlacklistMaxAttempts]" value="<?php if(isset($settings['Firewall']['IP']['AutoBlacklistMaxAttempts'])){echo $settings['Firewall']['IP']['AutoBlacklistMaxAttempts'];}?>"> 		
 	</div>
 	
 	<h4><?php _e('Banned IPs', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
 		<div><textarea name="settings_extra[blocked_ips]"><?php foreach (get_option('swiftsecurity_banned_ips', array()) as $ip) { echo $ip."\n"; }?></textarea></div> 		
 	</div>
 	
 	<h4><?php _e('Whitelisted users', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
		<div class="ss-cblist">
 		<?php foreach (get_users() as $user):?>
 		    <label>
 				<input type="checkbox" name="settings[Firewall][WhitelistedUsers][<?php echo $user->ID;?>]" value="<?php echo $user->ID;?>" <?php echo (isset($settings['Firewall']['WhitelistedUsers']) && in_array($user->ID,$settings['Firewall']['WhitelistedUsers']) ? 'checked="checked"' : '')?>>
				<?php echo $user->user_login;?>
 			</label><br>
 		<?php endforeach;?>
 		</div>
 	</div>
 	
 	<h4><?php _e('Remote IP address', 'SwiftSecurity');?></h4>
 	<div class="additional-settings">
		<select name="settings[Firewall][IP][source]">
 		<?php foreach ( array('REMOTE_ADDR', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_CF_CONNECTING_IP') as $key ) :?>
 		    <option value="<?php echo $key;?>" <?php echo (isset($settings['Firewall']['IP']['source']) && $settings['Firewall']['IP']['source'] == $key ? 'selected="selected"' : '');?>><?php echo $key . (isset($_SERVER[$key]) && !empty($_SERVER[$key])? ' (' . $_SERVER[$key] . ')' : '');?></option> 		<?php endforeach;?>
 		</select>
 		<span class="tooltip-icon" data-tooltip="<?php echo __('You can set which source should be used for remote IP', 'SwiftSecurity');?>" data-tooltip-position="right">?</span>
 	</div>
 	
 	<input type="hidden" name="sq" value="<?php echo $settings['GlobalSettings']['sq']?>">
 	<input type="hidden" name="module" value="Firewall">
 	<br>
 	<button name="swift-security-settings-save" class="sft-btn btn-green" value="Firewall"><?php _e('Save settings', 'SwiftSecurity');?></button>
 	<button name="swift-security-settings-save" class="sft-btn btn-gray restore-defaults" value="RestoreDefault"><?php _e('Restore defaults', 'SwiftSecurity');?></button>
 </form> 