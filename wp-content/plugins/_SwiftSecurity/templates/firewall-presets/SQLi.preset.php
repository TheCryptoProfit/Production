<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][SQLi][settings][GET]" class="onoffswitch-checkbox-sm" id="onoff-sm-get" value="enabled" <?php echo (isset($settings['settings']['GET']) && $settings['settings']['GET'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-get">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('GET requests', 'SwiftSecurity');?></label>
	<div class="input-group">
		<label><?php _e('Rules', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('SQL injection filters for GET requests (regular expressions)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['GET'])) :?>
		<?php foreach ((array)$settings['GET'] as $regExp) :?>
		<div>
			<input type="text" name="settings[Firewall][SQLi][GET][]" value="<?php swiftsecurity_safe_echo($regExp)?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][GET][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Files, where SQL injection filters are ignored. (eg: wp-login.php)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['exceptions']['GET'])) :?>
		<?php foreach ((array)$settings['exceptions']['GET'] as $exception) :?>
			<div>
				<input type="text" name="settings[Firewall][SQLi][exceptions][GET][]" value="<?php swiftsecurity_safe_echo($exception)?>">
				<a href="#" class="remove-input close-icon"></a>
			</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][exceptions][GET][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
	</div>
</div>
<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][SQLi][settings][POST]" class="onoffswitch-checkbox-sm" id="onoff-sm-post" value="enabled" <?php echo (isset($settings['settings']['POST']) && $settings['settings']['POST'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-post">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('POST requests', 'SwiftSecurity');?></label>
	<div class="input-group">
		<label><?php _e('Rules', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('SQL injection filters for POST requests (regular expressions)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['POST'])) : ?>
		<?php foreach ((array)$settings['POST'] as $regExp) :?>
		<div>
			<input type="text" name="settings[Firewall][SQLi][POST][]" value="<?php swiftsecurity_safe_echo($regExp)?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][POST][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
	    <span class="tooltip-icon" data-tooltip="<?php _e('Files, where SQL injection filters are ignored. (eg: wp-login.php)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
	    <?php if(isset($settings['exceptions']['POST'])) : ?>
		<?php foreach ((array)$settings['exceptions']['POST'] as $exception) :?>
		<div class="sample-container">
			<input type="text" name="settings[Firewall][SQLi][exceptions][POST][]" value="<?php swiftsecurity_safe_echo($exception)?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][exceptions][POST][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
	</div>
</div>
<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][SQLi][settings][COOKIE]" class="onoffswitch-checkbox-sm" id="onoff-sm-cookie" value="enabled" <?php echo (isset($settings['settings']['COOKIE']) && $settings['settings']['COOKIE'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-cookie">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('COOKIES', 'SwiftSecurity');?></label>
	<div class="input-group">
		<label><?php _e('Rules', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('SQL injection filters for cookies (regular expressions)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['COOKIE'])) : ?>	
		<?php foreach ((array)$settings['COOKIE'] as $regExp) :?>
		<div>
			<input type="text" name="settings[Firewall][SQLi][COOKIE][]" value="<?php swiftsecurity_safe_echo($regExp);?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][COOKIE][]">
			<a href="#" class="remove-input close-icon"></a>			
		</div>
		<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Files, where SQL injection filters are ignored. (eg: wp-login.php)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['exceptions']['COOKIE'])) : ?>
		<?php foreach ((array)$settings['exceptions']['COOKIE'] as $exception) :?>
		<div>
			<input type="text" name="settings[Firewall][SQLi][exceptions][COOKIE][]" value="<?php swiftsecurity_safe_echo($exception)?>">
			<OOKIES</label>
	<div claa href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][SQLi][exceptions][COOKIE][]">
		 	<a href="#" class="remove-input close-icon"></a>
		</div>
	</div>
</div>